<?php
// Initialize the character limit for messages
$character_limit = 500; // Set this variable to whatever limit you want, if set to 0 it's unlimited

// If the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check the captcha
    if ($_SESSION["captcha"] != $_POST["captcha"]) {
        $errorMsg = "Invalid captcha (It's case sensitive)";
    } else {
        // Get the form data
        $name = $_POST['name'] ? $_POST['name'] : "anon";
        $message = $_POST['message'];
        if (!empty($message)) {
            if ($character_limit > 0 && strlen($message) > $character_limit) {
                $errorMsg = "Your message exceeds the character limit of " . $character_limit;
            } else {
                // Load the existing data
                $data = array();
                if (file_exists('data.json') && filesize('data.json') > 0) {
                    $data = json_decode(file_get_contents('data.json'), true);
                }
                // Check if the message already exists
                $flag = true;
                foreach ($data as $submission) {
                    if ($submission['name'] == $name && $submission['message'] == $message) {
                        $flag = false;
                        break;
                    }
                }
                // If the message is new, add it to the data
                if ($flag) {
                    array_unshift($data, array("name" => $name, "message" => $message, "rname" => "", "rmessage" => ""));
                    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
                }
            }
        }
    }
}
// Load the data
$json = file_get_contents('data.json');
$data = json_decode($json, true);
?>

<div class="entry">
    <h2>Leave a Message</h2>
    <form action="" method="post">
        <input class="box" type="text" id="name" name="name" placeholder="enter name or leave empty for anon"><br><br>
        <textarea class="box" id="message" name="message" placeholder="enter your message"></textarea><br><br>
        <img src="captcha.php" alt="captcha"> <input class="box" type="text" name="captcha" placeholder="enter the captcha"><br><br>
        <input class="button" type="submit" name="submit" value="Submit">
    </form>
    <?php if (isset($errorMsg)): ?>
        <div class="entrysq">
            <h2>Error</h2>
            <p><?= $errorMsg ?></p>
        </div>
    <?php endif; ?>
    <div class="entrysq">
        <h2>Messages</h2>
        <div id="messages">
            <?php
            if (!empty($data)) {
                foreach ($data as $item) {
                    echo '<div class="entrysq">';
                    echo '<p><strong>' . htmlspecialchars($item['name']) . '</strong>: ' . htmlspecialchars($item['message']) . '</p>';
                    if (!empty($item['rname']) || !empty($item['rmessage'])) {
                        echo '<div class="entrysq">';
                        echo '<p><strong>' . htmlspecialchars($item['rname']) . '</strong>: ' . htmlspecialchars($item['rmessage']) . '</p>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>
