<div>
    <h2>Leave a Message</h2>
    <form action="" method="post">
        <input class="box" type="text" id="name" name="name" placeholder="enter name or leave empty for anon"><br><br>
        <textarea class="box" id="message" name="message" placeholder="enter your message"></textarea><br><br>
        <input class="button" type="submit" name="submit" value="Submit">
    </form>
    <div>
        <h2>Messages</h2>
        <div id="messages">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $message = $_POST['message'];
                $rname = "";
                $rmessage = "";

                if(empty($name)){
                    $name = "anon";
                }
                if(!empty($message)){
                    if (file_exists('data.json') && filesize('data.json') > 0) {
                        $data = json_decode(file_get_contents('data.json'), true);
                    } else {
                        $data = array();
                    }
                    $flag = true;
                    foreach ($data as $submission) {
                        if ($submission['name'] == $name && $submission['message'] == $message) {
                            $flag = false;
                            break;
                        }
                    }
                    if ($flag) {
                        array_unshift($data, array("name" => $name, "message" => $message,"rname" => $rname,"rmessage" => $rmessage));
                        file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT));
                    }
                }
            }

            $json = file_get_contents('data.json');
            $data = json_decode($json, true);

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
