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
                        $merchantId     = $user->getMerchants($data['email']);
                       
			$status 	= 	'merchants added success';
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
                        "userId"        => $usrId,
                        "merchantId"    => $merchantId
			
                   
                        
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

	# function to register/login with facebook
	public static function AddMobileUserFB($data)
	{
		global $_error;
		$data["fb_id"]	=	"";
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	$insId;
		$user = new User();
		$result = $user->AddUser1($data);
		if($result > 0)
		{
			$status 	= 	'success';
			$error 		= 	$result['error'];
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
			$status 	= 	' success';
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

	
	public function checkValidUser($data)
	{
		global $_error;
		$data   = Core::SanitizeData($data);
		$status = '';
		$error 	= '';
		$usrId  = 0;
		$user 	= new User();
		$result = $user->getNormalUserWithEmail($data['email']);
		//$pass='';
		
		if($result)
		{ 
			$randPaswd 	= $user->generateRandomString(6);
			$respReset 	= $user->updateUserTempPassword($result, $randPaswd, $data['email']);
		//	$pass=$randPaswd;
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;
//  			mail($data['email'],"Password Reset","Your Password Has Been Reset and Your New Password Is: $randPaswd  ");
 			
 $from ="help@weraiseit.com"; // sender
      $subject = "Password Reset";
      $message = "Your Password Has Been Reset and Your New Password Is: $randPaswd  ";
      // message lines should not exceed 70 characters (PHP rule), so wrap it
      $message = wordwrap($message, 70);
      // send mail
      mail($data['email'],$subject,$message,"From: $from\n");

		} else if(!$result) {
		        $status 	= 'failure';
			$respUserExists	= $user->GetUserIdwithEmail($data['email']);
			$error 		= ($respUserExists) ? 'Sorry. You are using this email with Facebook login' : 'This Email not found as registered.';
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId,
			//"password"	=>$pass
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


        # function to add merchant Info app user
	public function updateMerchantInfo($data)
	{

		global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->VerifyMerchantUser($data);

		if($result > 0)
		{
                        
                        $addRelation = $user->AddMerchantUserRelation($data);
                        if($addRelation > 0)
                        {
			$status 	= 	"Merchants Registerd With Mobile Users";
			$error 		= 	'';
			
                        }
                         else
                       {
				$status 	='';
                               $error 		= $_SESSION["error"];
				}
                          	
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
			
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}

       public function ViewMerchantEvents($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getLiveEvents($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"Live Events";
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
			"error"		=> $error,
			"Events"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
	
	
}
?>
