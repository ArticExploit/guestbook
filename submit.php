<?php
// check if form was submitted
if(isset($_POST['submit'])){
    // get form data
    $name = $_POST['name'];
    $message = $_POST['message'];
    $rname = "";
    $rmessage = "";

    // if name is empty, set it to "anon"
    if(empty($name)){
        $name = "anon";
    }

    // if message is not empty
    if(!empty($message)){
        // get existing json data
        if (file_exists('data.json') && filesize('data.json') > 0) {
            $data = json_decode(file_get_contents('data.json'), true);
        } else {
            $data = array();
        }
        $flag = true;
        // loop through previous submissions
        foreach ($data as $submission) {
            // check if current submission matches any previous submissions in both name and message
            if ($submission['name'] == $name && $submission['message'] == $message) {
                $flag = false;
                break;
            }
        }
        // if current submission does not match any previous submissions, append it to the JSON file
        if ($flag) {
            array_unshift($data, array("name" => $name, "message" => $message,"rname" => $rname,"rmessage" => $rmessage));
            file_put_contents('data.json', json_encode($data));
        }
    }
}
header("Location: https://example.org");
exit();
