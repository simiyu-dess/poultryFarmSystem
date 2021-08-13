<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/loginServer.php";
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/functions.php";
    
    // instantiating LoginServer class to access its functions/methods
    $data = new LoginServer();
    // variable to store message
    $message = "";
    // check if login was clicked
    if(isset($_POST["login"])){
        $field = array(
            "Username" => $_POST["Username"],
            "Password" => $_POST["Password"]
        );
        $username = sanitize($_POST["Username"]);
        $password = sanitize($_POST['Password']);
        if($data->loginValidation($field)){
            if($data->canLogin("User",$username,$password)){
                $_SESSION["Username"] = $_POST["Username"];
                $where = array("Ugroup_ID" => $_SESSION['ugroupid']);
                $sql = "SELECT * FROM Ugroup WHERE Ugroup_ID = $_SESSION[ugroupid]";
                $query = $db->connect()->query($sql);
                $permisions_array = mysqli_fetch_array($query);
                $_SESSION['perm_admin'] = $permisions_array['Ugroup_admin'];
                $_SESSION['perm_medicine'] = $permisions_array['Ugroup_medicine'];
                $_SESSION['perm_feeds'] = $permisions_array['Ugroup_feeds'];
                $_SESSION['perm_purchase'] = $permisions_array['Ugroup_purchases'];
                $_SESSION['perm_sales'] = $permisions_array['Ugroup_sales'];
                $_SESSION['perm_birds'] = $permisions_array['Ugroup_birds'];
                $_SESSION['perm_eggs'] = $permisions_array['Ugroup_eggs'];
                $_SESSION['fingerprint'] = fingerprint();
                header("location: dashboard.php");
            }else{
                $message = $data->error;
            }
        }else{
            // if input fields are blank, execute else statement: if both input fields are blank
            $message = $data->error;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./loginStyles.css" />
    <title>KPMS Login</title>
</head>
<body>
    <div class="login__container">
        <h1> USER Login</h1>
        <?php
            // display error message
            if(isset($message)){
                echo '<label class="text-danger">' . $message . '</label>';
            }
        ?>
        <form action="" method="post">
            <input type="text" name="Username" placeholder="Username">
            <input type="password" name="Password" placeholder="Password">
            <button type="submit" name="login">Login</button>
        </form>
    </div>   
</body>
</html>