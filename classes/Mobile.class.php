<?php

class Mobile
{
	# function to add user
	public function AddMobileUser($data)
	{
		global $_error;
		$data["fb_id"]	=	"";
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->AddUser($data);
		if($result > 0)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}

	# function to register/login with facebook
	public static function AddMobileFBUser($data)
	{
		global $_error;
		$data["password"]	= 	"";
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$user_id = $user->CheckFacebookId($data['fb_id']);
		$first_time = "false";
		if(!empty($user_id))
		{
			$result = $user_id;
			$first_time = "false";
		}
		else
		{
			$user_id = $user->GetUserIdwithEmail($data['email']);
			if(!empty($user_id))
			{
				$rst = $user->InsertFBId($data['fb_id'],$user_id);
				if($rst)
					$result = $user_id;
				$first_time = "true";
				
			}
			else
			{
				$result = $user->AddFBUser($data);
				$first_time = "true";
				
			}
		}
		if($result > 0)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId,
			"firsttime"     => $first_time
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
		
	}

	#function to authenticate mobile user
	public function AuthenticateMobileUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->Authenticate($data);
		if($result > 0)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result['id'];	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 
	}

	public function ViewMobileUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->FetchUser($data['user_id']);
		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function UpdateMobileUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$data['status'] = 'Active'; 
		$result = $user->UpdateUser($data);
		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	#function to get json formatted string
	public static function GenerateMobileOutput($data)
	{
		return json_encode($data);
	}

	public function viewAllPetitions($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$petition 	= new Petition();
		$result 	= $petition->fetchAllPetitions($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function listAllNews($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$news 	= new News();
		$result = $news->fetchActiveNews($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function listAllDiscussion($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$discussion 	= new Discussion();
		$result = $discussion->fetchActiveDiscussion($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function listActiveAds($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$ad 	= new Ad();
		$result = $ad->fetchActiveAds($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function listActiveTopics($data)
	{
		global $_error;
		$data 		= Core::SanitizeData($data);
		$status 	= '';
		$error 		= '';
		$usrId 		= 0;
		$category 	= new Category();
		$result 	= $category->fetchActiveTopics($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function listAllActiveTopics($data)
	{
		global $_error;
		$data 		= Core::SanitizeData($data);
		$status 	= '';
		$error 		= '';
		$usrId 		= 0;

		$memcache 	= new Memcache;
		$cacheAvailable = $memcache->connect('localhost', 11211);
		if($result = $memcache->get('droidActiveCategories')) {
			
		} else {
			$category 	= new Category();
			$result 	= $category->fetchAllActiveTopics($data);
			$memcache->set('droidActiveCategories', $result, false, 3600);
		}

		if($result)
		{
			$status 	= 	'success';
			$error 		= '';
			$usrId 		= $result;	
		}
		else if(!$result)
		{
		        $status 	= 'failure';
			$error 		= $_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function viewPetitionInfo($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$petition 	= new Petition();
		$result 	= $petition->fetchPetitionDetails($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function fetchUserFbInfo($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user 	= new User();
		$result = $user->isFbUserExists($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function listMySignatures($data)
	{
		global $_error;
		$data 	= Core::SanitizeData($data);
		$status = '';
		$error 	= '';
		$usrId 	= 0;
		$petition 	= new Petition();
		$result 	= $petition->fetchMySignaturePetitions($data);

		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function checkValidUser($data)
	{
		global $_error;
		$data   = Core::SanitizeData($data);
		$status = '';
		$error 	= '';
		$usrId  = 0;
		$user 	= new User();
		$result = $user->getNormalUserWithEmail($data['email']);

		if($result)
		{
			$randPaswd 	= $user->generateRandomString(6);
			$respReset 	= $user->updateUserTempPassword($result, $randPaswd, $data['email']);
		
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;
		} else if(!$result) {
		        $status 	= 'failure';
			$respUserExists	= $user->GetUserIdwithEmail($data['email']);
			$error 		= ($respUserExists) ? 'Sorry. You are using this email with Facebook login' : 'This Email not found as registered.';
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst 	= self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function authenticateUser($data)
	{
		global $_error;
		$data   = Core::SanitizeData($data);
		$status = '';
		$error 	= '';
		$usrId  = 0;
		$user 	= new User();
		$result = $user->verifyUser($data['user'],$data['currpaswd']);

		if($result && !empty($data['newpaswd']))
		{
			$respReset 	= $user->updateUserPassword($data['user'],$data['currpaswd'],$data['newpaswd'],$result);
		
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;
		} else if(!$result) {
		        $status 	= 'failure';
			$error 		= '';
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst 	= self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function initUserPetition($data)
	{
		global $_error;
		$data 		= Core::SanitizeData($data);
		$status		= '';
		$error 		= '';
		$usrId 		= 0;
		$objPetition	= new Petition();
		$result 	= $objPetition->saveUserSignedPetition($data);

		if($result)
		{
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;	
		} else if(!$result) {
		        $status 	= 'failure';
			$error 		= $_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function viewPollInfo($data)
	{
		global $_error;
		$data 			= Core::SanitizeData($data);
		$status 		= '';
		$error 			= '';
		$usrId 			= 0;
		$objPoll 		= new Poll();
		$result 		= $objPoll->showPoll($data);
		if($result)
		{
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;	
		}
		else if(!$result)
		{
		        $status 	= 'failure';
			$error 		= $_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function viewPollArchive($data)
	{
		global $_error;
		$data 			= Core::SanitizeData($data);
		$status 		= '';
		$error 			= '';
		$usrId 			= 0;
		$objPoll 		= new Poll();
		$result 		= $objPoll->showPollArchive($data);
		if($result)
		{
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;	
		}
		else if(!$result)
		{
		        $status 	= 'failure';
			$error 		= $_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}

	public function viewPollResult($data)
	{
		global $_error;
		$data 			= Core::SanitizeData($data);
		$status 		= '';
		$error 			= '';
		$usrId 			= 0;
		$objPoll 		= new Poll();
		$result 		= $objPoll->PollResult($data);
		if($result)
		{
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;	
		}
		else if(!$result)
		{
		        $status 	= 'failure';
			$error 		= $_SESSION["error"];
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId	
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;
	}
}
?>
