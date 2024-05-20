<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

$logged_in_username = $_SESSION["username"];

$sql = "SELECT username FROM users WHERE username != '$logged_in_username'";

if($result = mysqli_query($link, $sql)){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Friends</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            margin-top: 50px;
        }
        .container {
            max-width: 600px;
            text-align: center;
            margin: 0 auto;
        }
        .username-btn {
            margin-bottom: 10px;
        }
        h2 {
            color: #343a40;
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Friends</h2>
        <?php
        while($row = mysqli_fetch_array($result)){
            echo "<a href='chats.php?username=" . urlencode($row['username']) . "'><button class='btn btn-primary username-btn'>" . $row['username'] . "</button></a>";
        }
        ?>
    </div>
</body>
</html>
<?php
    mysqli_free_result($result);
} else{
    echo "<div class='container'><p class='text-center'>ERROR: Could not able to execute $sql. " . mysqli_error($link) . "</p></div>";
}

mysqli_close($link);
?>
