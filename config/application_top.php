<?php
ob_start("ob_gzhandler");
session_start();
	
require('config.php');

# DB Connection

$dbo = Database::getInstance();
$dbo->connect();

if(is_array($_POST) && basename($_SERVER['PHP_SELF']) != "petitions_add.php") {
	foreach($_POST as $key => $val) {
		$_POST[$key] = htmlspecialchars(trim($val));
	}
}

$general = new General();

?>
