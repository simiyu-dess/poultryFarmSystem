<?PHP
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/action.php";

include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/functions.php";

checkLogin();


?>
<DOCTYPE HTML>
<html>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php"; ?>

<body id = "body">
<div class="container">
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar_settings.php"; ?>
<main>
<div class="main__container">
<h1>Set Price For Eggs </h1>
<form method="POST" action="includes/action.php">
<label for="">Price per egg</label>
<input type="number" placeholder="<?php echo $eggPrice;?>" name="eggprice"/>
<button name="saveFee" value="Save">Save</button>
</form>
</div>
</main>
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php"; ?>
</div>
</body>
</html>