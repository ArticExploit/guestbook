<?php
$messageCharacterLimit = 300; // Set the limit here for message, set to 0 for unlimited characters
$usernameCharacterLimit = 15; // Set the limit here for username, set to 0 for unlimited characters

$errorMsg = array(); // Initialize an array to store all errors

// If the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check the captcha
    if ($_SESSION["captcha"] != $_POST["captcha"]) {
        $errorMsg['captcha'] = "Invalid captcha, it's case sensitive";
    } 

    // Get the form data
    $name = $_POST['name'] ? $_POST['name'] : "anon";
    $message = $_POST['message'];

    // Check the character limit for username
    $usernameError = checkCharacterLimit($name, $usernameCharacterLimit, "Username");
    if($usernameError){
        $errorMsg['username'] = $usernameError;
    }

    // Check the character limit for message
    $messageError = checkCharacterLimit($message, $messageCharacterLimit, "Message");
    if($messageError){
        $errorMsg['message'] = $messageError;
    }

    // Check for duplicates
    $duplicateError = checkMessage($message);
    if($duplicateError){
        $errorMsg['duplicate'] = $duplicateError;
    }

    // If there are no errors, write the data to the JSON file
    if (empty($errorMsg)) {
        // Load the existing data
        $data = array();
        if (file_exists('data.json') && filesize('data.json') > 0) {
            $data = json_decode(file_get_contents('data.json'), true);
        }
        // Add the new message to the data
        array_unshift($data, array("name" => $name, "message" => $message, "rname" => "", "rmessage" => ""));
        file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
    }
}

function checkCharacterLimit($input, $limit, $type){
    if ($limit > 0 && strlen($input) > $limit) {
        return "$type exceeds the character limit of $limit characters";
    } else if (empty($input)) {
        return "$type cannot be empty";
    }
    return false;
}

function checkMessage($message){
    // Load the existing data
    $data = array();
    if (file_exists('data.json') && filesize('data.json') > 0) {
        $data = json_decode(file_get_contents('data.json'), true);
    }
    // Check if the message already exists
    foreach ($data as $submission) {
        if ($submission['message'] == $message) {
            return "Comment is a duplicate";
        }
    }
    return false;
}

// Load the data
$json = file_get_contents('data.json');
$data = json_decode($json, true);
?>

<div class="entry">
    <h2>Leave a Comment</h2>
    <form action="" method="post">
        <input class="box" type="text" id="name" name="name" placeholder="enter name or leave empty for anon"><br><br>
        <textarea class="box" id="message" name="message" placeholder="enter your message"></textarea><br><br>
        <img src="/assets/main/pages/captcha.php" alt="captcha"> <input class="box" type="text" name="captcha" placeholder="enter the captcha"><br><br>
        <input class="button" type="submit" name="submit" value="Submit">
    </form>
    <?php if(isset($errorMsg['captcha']) || isset($errorMsg['message']) || isset($errorMsg['username'])): ?>
        <div class="error">
            <h3>Your Name</h3>
            <p><?= htmlspecialchars($name) ?></p>
            <?php if(!empty($message)): ?>
                <h3>Your Comment</h3>
                <p><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if(isset($errorMsg['captcha'])): ?>
        <div class="error">
            <h2>Captcha Error</h2>
            <p><?= $errorMsg['captcha'] ?></p>
        </div>
    <?php endif; ?>
    <?php if(isset($errorMsg['username'])): ?>
        <div class="error">
            <h2>Name Error</h2>
            <p><?= $errorMsg['username'] ?></p>
        </div>
    <?php endif; ?>
    <?php if(isset($errorMsg['message']) || isset($errorMsg['duplicate'])): ?>
        <div class="error">
            <h2>Comment Error</h2>
            <?php if(isset($errorMsg['duplicate'])): ?>
                <p><?= $errorMsg['duplicate'] ?></p>
            <?php endif; ?>
            <?php if(isset($errorMsg['message'])): ?>
                <p><?= $errorMsg['message'] ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div>
        <h2>Comments</h2>
        <div id="comments">
            <?php
            if (!empty($data)) {
                foreach ($data as $item) {
                    echo '<div class="comment">';
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
