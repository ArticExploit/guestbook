<?php
// check if form was submitted
if(isset($_POST['submit'])){
    // get form data
    $name = $_POST['name'];
    $message = $_POST['message'];
    // set rname and rmessage to empty string
    $rname = "";
    $rmessage = "";

    // if name is empty, set it to "anon"
    if(empty($name)){
        $name = "anon";
    }

    // if message is not empty
    if(!empty($message)){
        // get existing json data
        $data = json_decode(file_get_contents('data.json'), true);
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
header("Location: https://articexploit.xyz:8443/test/");
exit();
