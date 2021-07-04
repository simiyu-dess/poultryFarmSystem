<?php
include 'includes/action.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="./loginStyles.css" />
<title>KPMS Register</title>
</head>
<body>
<div class="login__container">
    <h1> Setup Admin</h1>
    <?php
        // display error message
        if(isset($message)){
            echo '<label class="text-danger">' . $message . '</label>';
        }
    ?>
    <form action="" method="post">
        <input type="text" name="Username" placeholder="Username">
        <input type="password" name="Password" placeholder="Password">
        <input type="password" name="confirm__password" placeholder="repeat password">
        <button type="submit" name="setup_admin">Setup</button>
    </form>
</div>   
</body>
</html>
