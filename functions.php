<?php
function fingerprint(){
		
		$fingerprint = md5($_SERVER['REMOTE_ADDR'].'jikI/20Y,!'.$_SERVER['HTTP_USER_AGENT']);
		return $fingerprint;
	}
	function checkLogin() {
		$fingerprint = fingerprint();
		if(!session_id())
	 {
		session_start();
		}

		if (!isset($_SESSION['Username']) || $_SESSION['fingerprint'] != $fingerprint) logout();
		session_regenerate_id();
	}

	function logout()
	{
		session_start();
		session_destroy();
	
		header("location: index.php");
	}
  

function sanitize($var)
{
        $var = stripslashes($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
}


