<?php
$messageCharacterLimit = 500; // Set the limit here for message, set to 0 for unlimited characters
$usernameCharacterLimit = 30; // Set the limit here for username, set to 0 for unlimited characters

// If the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check the captcha
    if ($_SESSION["captcha"] != $_POST["captcha"]) {
        $errorMsg = "Invalid captcha, it's case sensitive<br><h3>Your Message</h3><p>" . htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') . "</p>";
    } else {
        // Get the form data
        $name = $_POST['name'] ? $_POST['name'] : "anon";
        $message = $_POST['message'];

        // Check the character limit for username and message
        $errorMsg = checkCharacterLimit($name, $usernameCharacterLimit, "Username");
        if(!$errorMsg){
            $errorMsg = checkCharacterLimit($message, $messageCharacterLimit, "Message");
        }

        if(!$errorMsg){
            $errorMsg = checkMessage($message, $name);
        }
    }
}

function checkCharacterLimit($input, $limit, $type){
    if ($limit > 0 && strlen($input) > $limit) {
        return "$type exceeds the character limit of $limit characters<br><h3>Your $type</h3><p>" . htmlspecialchars($input, ENT_QUOTES, 'UTF-8') . "</p>";
    } else if (empty($input)) {
        return "$type cannot be empty";
    }
    return false;
}

function checkMessage($message, $name){
    // Load the existing data
    $data = array();
    if (file_exists('data.json') && filesize('data.json') > 0) {
        $data = json_decode(file_get_contents('data.json'), true);
    }
    // Check if the message already exists
    foreach ($data as $submission) {
        if ($submission['message'] == $message) {
            return "Message is a duplicate<br><h3>Your Message</h3><p>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>";
        }
    }
    // If the message is new, add it to the data
    array_unshift($data, array("name" => $name, "message" => $message, "rname" => "", "rmessage" => ""));
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
    return false;
}

// Load the data
$json = file_get_contents('data.json');
$data = json_decode($json, true);
?>

<div>
    <h2>Leave a Message</h2>
    <form action="" method="post">
        <input class="box" type="text" id="name" name="name" placeholder="enter name or leave empty for anon"><br><br>
        <textarea class="box" id="message" name="message" placeholder="enter your message"></textarea><br><br>
        <img src="/assets/main/pages/captcha.php" alt="captcha"> <input class="box" type="text" name="captcha" placeholder="enter the captcha"><br><br>
        <input class="button" type="submit" name="submit" value="Submit">
    </form>
    <?php if (isset($errorMsg)): ?>
        <div>
            <h2>Error</h2>
            <p><?= $errorMsg ?></p>
        </div>
    <?php endif; ?>
    <div>
        <h2>Messages</h2>
        <div id="messages">
            <?php
            if (!empty($data)) {
                foreach ($data as $item) {
                    echo '<div class="message">';
                    echo '<p><strong>' . htmlspecialchars($item['name']) . '</strong>: ' . htmlspecialchars($item['message']) . '</p>';
                    if (!empty($item['rname']) || !empty($item['rmessage'])) {
                        echo '<div class="reply">';
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
