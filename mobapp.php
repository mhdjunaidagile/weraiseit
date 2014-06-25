<?php
ob_start ();
	//session_start();
echo "Hello";
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
		case "shownews" : 
				ActionShowNews();
				break;
		case "showads" : 
				ActionShowAds();
				break;
		case "browsepetitions" : 
				ActionBrowsePetitions();
				break;
		case "showtopics" : 
				ActionShowTopics();
				break;
		case "showrefinemenu" : 
				ActionShowRefineMenu();
				break;
		case "viewpetition" : 
				ActionViewPetition();
				break;
		case "showquestion" : 
				ActionShowQuestion();
				break;
		case "mysigns" : 
				ActionShowMySignatures();
				break;
		case "paswdreset" :
				ActionResetPaswd();
				break;
		case "changepaswd" :
				ActionChangePaswd();
				break;
		case "savesign" :
				ActionSaveSign();
				break;

		case "showpoll" : 
				ActionShowPoll();
				break;

		case "showpollarchive" : 
				ActionShowPollArchive();
				break;

		case "pollresult" : 
				ActionPollResult();
				break;
		default :
	}

	function ActionShowPoll()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->viewPollInfo($data);
	}

	function ActionShowPollArchive()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->viewPollArchive($data);
	}

	function ActionPollResult()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->viewPollResult($data);
	}

	function ActionSaveSign()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->initUserPetition($data);
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
	function ActionShowMySignatures()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listMySignatures($data);
	}

	function ActionCheckFbUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->fetchUserFbInfo($data);
	}
	function ActionShowQuestion()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listActiveTopics($data);
	}

	function ActionViewPetition()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->viewPetitionInfo($data);
	}

	function ActionShowRefineMenu()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listAllActiveTopics($data);
	}

	function ActionShowTopics()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listActiveTopics($data);
	}

	function ActionShowAds()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listActiveAds($data);
	}

	function ActionShowNews()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listAllNews($data);
	}

	function ActionBrowsePetitions()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->viewAllPetitions($data);
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

?>
