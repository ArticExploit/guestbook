<?php
// Fetch and decode JSON data
$json = file_get_contents('data.json');
$data = json_decode($json, true);

// Loop through data and generate HTML
foreach ($data as $item) {
    echo '<div class="comment">';
    echo '<p><strong>' . htmlspecialchars($item['name']) . '</strong>: ' . htmlspecialchars($item['message']) . '</p>';

    // Add reply div if reply is not empty
    if (!empty($item['rname']) || !empty($item['rmessage'])) {
        echo '<div class="reply">';
        echo '<p><strong>' . htmlspecialchars($item['rname']) . '</strong>: ' . htmlspecialchars($item['rmessage']) . '</p>';
        echo '</div>';
    }

    echo '</div>';
}
?>
