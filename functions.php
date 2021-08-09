<?php
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/classes.php";
function fingerprint(){
		
		//$fingerprint = md5($_SERVER['REMOTE_ADDR'].'jikI/20Y,!'.$_SERVER['HTTP_USER_AGENT']);
		$fingerprint = md5('!@#$fjslkvmnfdkp&*()^%$#SDFGHJK*&^%$#@!7454454');
		return $fingerprint;
	}

  

function sanitize($var)
{
        $var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
}

function getEggPrice()
{
	$database_object = new Database();
	$sql = "SELECT Fee_Amount FROM Fees WHERE Fee_ID > 0";
	$query_sql = $database_object->connect()->query($sql);
	$array_fee = mysqli_fetch_array($query_sql);
	$eggPrice = $array_fee['Fee_Amount'];

	return $eggPrice;
}
