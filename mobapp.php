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
                case "searchItems" : 
				ActionSearchItems();
				break;
		case "viewItems" : 
				ActionViewEventItems();
				break;
		case "getImages" : 
				ActionGetImages();
				break;
		case "itemlist" : 
				ActionItemsList();
				break;
		case "bidder" : 
				ActionViewBidder();
				break;
                case "orderpayment" : 
				ActionOrderPayment();
				break;
		case "donateToEvent" :	
				ActionDonate();
				break;
                case "viewauctions" :
				ActionViewAuctions();
				break;
                case "onlineauctionitems" :
				ActionViewOnlineItems();
				break;
		case "userprofile" :
				ActionEditProfile();
				break;

		case "bidnow" :
				ActionBidNow();
				break;
                 case "donationTypes" :
                                ActionDonationTypes();
			        break;
		case "carduserlist" : 
				ActionCardUserList();
				break;
		case "addcreditcard" : 
				ActionAddCardUser();
				break;
		case "updatecreditcard" : 
				ActionUpdateCardUser();
				break;
		case "deletecreditcard" : 
				ActionDeleteCardUser();
				break;
		case "watchlist" : 
				Actionwatchlist();
				break;
		case "deletewatchlist" : 
				Actiondeletewatchlistuser();
				break;
		case "listwatchlist" : 
				Actionlistwatchlist();
				break;
		case "pushnotification" : 
				Actionpushnotification();
				break;
                case "auctionitem" :
				ActionViewAuctionItems();
				break;
		case "mobileuserinfo" : 
				Actionmobileuserinfo();
				break;
		case "isUserPurchased" : 
				actionIsUserPurchased();
				break;
		case "eventRegistration" : 
				actionEventRegistration();
				break;
		case "getMeals" : 
				actionGetMeals();
				break;
		default :
	}


        function ActionViewOnlineItems()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->ViewAuctionItems($data);
	}
	
        function ActionViewAuctionItems()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->ViewItemsAuctioned($data);
	}
	
		function Actionlistwatchlist()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->listwatchlist($data);
	}
	
	function Actionmobileuserinfo()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->mobileuserinfo($data);
	}
	

	function Actionpushnotification()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->pushnotification($data);
		

   	
   	
		
		
	}
	
	function Actiondeletewatchlistuser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->deleteWatchlistUser($data);
	}
	
	function Actionwatchlist()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->addWatchlistUser($data);
	}

	function ActionAddCardUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->addCardUser($data);
	}
	
	function ActionDeleteCardUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->deleteCardUser($data);
	}
	
	function ActionUpdateCardUser()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->updateCardUser($data);
	}
	
	function ActionCardUserList()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->CardUserList($data);
	}

        function ActionViewAuctions()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->ViewAuctions($data);
	}


	function ActionChangePaswd()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->authenticateUser($data);
	}

	function ActionViewBidder()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->userBids($data);
	}

	function ActionItemsList()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->eventItemsList($data);
	}

	function ActionGetImages()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->imagesList($data);
	}

	function ActionViewEventItems()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->viewEvents($data);
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

     function ActionSearchItems()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->GetItemEvents($data);
	}
     function ActionBidNow()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->updateBids($data);
	}

	function ActionDonate()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->OrderDonation($data);
	}

  
        	function ActionOrderPayment()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->OrderPayment($data);
	}

        	function ActionEditProfile()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->UpdateUser($data);
	}

 	function ActionDonationTypes()
	{
		$data = $_REQUEST;
		$mobile = new Mobile();
		echo $mobile->GetDonationTypes($data);
	}
	// spring III
	
	
	function actionIsUserPurchased()
	{
		$data = $_REQUEST;
		$mobile = new MobileIII();
		echo $mobile->eventIsUserPurchased($data);
	}
	
	function actionEventRegistration()
	{
		$data = $_REQUEST;
		$mobile = new MobileIII();
		echo $mobile->eventRegistration($data);
	}
	
	function actionGetMeals()
	{
		$data = $_REQUEST;
		$mobile = new MobileIII();
		echo $mobile->getMealListIfAvail($data);
	}
      

?>
