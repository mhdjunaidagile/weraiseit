<?php
ob_start ();
	//session_start();

	require_once('config/config.php');
	$action = $_REQUEST['action'];
	$_SESSION["error"] = array();
	switch($action)
	{
		case "signup" 	: 
				ActionSignUp();
				break;
		case "fbsignin" : 
				ActionFbSignUp();
				break;
		case "signin"	: 
				ActionSignIn();
				break;
		case "currfbuser":
				ActionCheckFbUser();
				break;
		case "viewuser" : 
				ActionViewUser();
				break;
		case "updateuser" : 
				ActionUpdateUser();
				break;		
		case "paswdreset" :
				ActionResetPaswd();
				break;
		case "changepaswd" :
				ActionChangePaswd();
				break;
                case "updatemerchants" :
				ActionUpdateMerchants();
				break;
                case "viewEvents" :
				ActionViewEvents();
				break;
		case "signinfb" : 
				ActionSignUpFb();
				break;
		
		default :
	}

	

	function ActionChangePaswd()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->authenticateUser($data);
	}
	function ActionResetPaswd()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->checkValidUser($data);
	}
	
	function ActionCheckFbUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->fetchUserFbInfo($data);
	}
	

       function ActionUpdateMerchants()
	{
                 
		$data = $_REQUEST;
		$mobile = new Mobile();
                echo $mobile->updateMerchantInfo($data);
	}
	


	function ActionSignUp()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->AddMobileUser($data);
	}

	function ActionFbSignUp()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->AddMobileFBUser($data);
	}

	function ActionSignUpFb()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->AddMobileUserFB($data);
	}

	function ActionSignIn()
	{

		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->AuthenticateMobileUser($data);
	}

	function ActionViewUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->ViewMobileUser($data);
	}

	function ActionUpdateUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->UpdateMobileUser($data);
	}

        function ActionViewEvents()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->ViewMerchantEvents($data);
	}

?>
