<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo json_encode(array("success" => false, "error" => "User is not logged in"));
    exit;
}

require_once "config.php";

$logged_in_username = $_SESSION["username"];

if (isset($_GET['username'])) {
    $contact_username = $_GET['username'];
    
    $sql = "SELECT * FROM messages WHERE ((sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)) AND timestamp > ? ORDER BY timestamp";
    if ($stmt = mysqli_prepare($link, $sql)) {
        $last_update_time = isset($_GET['last_update_time']) ? $_GET['last_update_time'] : 0;
        mysqli_stmt_bind_param($stmt, "ssssi", $logged_in_username, $contact_username, $contact_username, $logged_in_username, $last_update_time);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $messages = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = "<div class='message'><span class='message-sender'>" . $row['sender'] . ":</span><span class='message-content'>" . $row['message'] . "</span></div>";
        }
        echo json_encode(array("success" => true, "messages" => $messages));
    } else {
        echo json_encode(array("success" => false, "error" => "Error preparing statement"));
    }
} else {
    echo json_encode(array("success" => false, "error" => "Contact username not provided"));
}


