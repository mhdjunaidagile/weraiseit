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
    $from ="admin@weraiseit.com"; // sender
      $subject = "Account Registered";
      $message = "Your account has been registered ";
      // message lines should not exceed 70 characters (PHP rule), so wrap it
      $message = wordwrap($message, 70);
      // send mail
      mail($data['email'],$subject,$message,"From: $from\n");

                        $merchantId     = $user->getMerchants($data['email']);
                       
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
		$result = $user->AddUserSocial($data);
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
		//print_r($result);exit;
		if($result)
		{ 
			$randPaswd 	= $user->generateRandomString(6);
			$respReset 	= $user->updateUserTempPassword($result, $randPaswd, $data['email']);
			$pass=$randPaswd;
			$status 	= 'success';
			$error 		= '';
			$usrId 		= $result;
  		//	mail($data['email'],"Password Reset","Your Password Has Been Reset and Your New Password Is: $randPaswd  ");
 			
 $from ="help@weraiseit.com"; // sender
      $subject = "Password Reset";
      $message = "Your Password Has Been Reset and Your New Password Is: $randPaswd  ";
      // message lines should not exceed 70 characters (PHP rule), so wrap it
      $message = wordwrap($message, 70);
      // send mail
      mail($data['email'],$subject,$message,"From: $from\n");

		} 
		else if($result == 0) {
		        $status 	= 'failure';
			$respUserExists	= $user->GetUserIdwithEmail($data['email']);
			$error 		= 'Sorry. Please enter a valid email!';
			$_error->ResetError();
			$usrId 		= "Null";
		}
		else if(!$result) {
		        $status 	= 'failure';
			$respUserExists	= $user->GetUserIdwithEmail($data['email']);
			$error 		= ($respUserExists) ? 'Sorry. You are using this email with Facebook login' : 'This Email not found as registered.';
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"user"		=> $usrId,
		//	"password"	=>$pass
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
                       
                      
			$status 	= 	"success";
			$error 		= 	'';
			$verified       =$result;
                      
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
                        $verified       =   'All credentials are wrong';
			$_error->ResetError();
		}
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
                         "Verified"     =>$verified
			
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}
# function to get event list by name, date and day
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
                        
			$status 	= 	"success";
			$error 		= 	'';  
        	}
                
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else 
                {
                $status 	= 	"failure";
                $error          =       "No Events";
                $result		=      "";
                }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Events"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
	


// Search Item Event Items GetItemEvents
	

  public function GetItemEvents($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getItemEventsList($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
                        
			
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else
                 {
                     $status 	= 	'failure';
                 }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Events"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
	
  public function viewEvents($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getEvents($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
                        
			
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else
                 {
                     $status 	= 	'failure';
                 }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Events"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }

  public function imagesList($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'No Items';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getImagesList($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
                        
			
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else
                 {
                     $status 	= 	'failure';
                 }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Items"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }

  public function eventItemsList($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'No Items';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getItemsList($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
                        
			
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else
                 {
                     $status 	= 	'failure';
                 }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Items"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }


  public function userBids($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'No Bids';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getBids($data);
		   if($result == 'No Result.')
		{
                        
                       
			$status 	= 	"failure";
			$error 		= 	'No Bidder Item';  
                        
			
                          	
		}
                if($result != '')
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
                        
			
                          	
		}
		else if(empty($result))
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else
                 {
                     $status 	= 	'failure';
                 }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Bids"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
         


       public function ViewAuctions($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getAuctions($data);
                if($result > 0)
		{
                        
			$status 	= 	"success";
			$error 		= 	'';  
        	}
                
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else 
                {
                $status 	= 	"failure";
                $error          =       "No Events";
                $result		=      "";
                }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Events"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }

       public function ViewAuctionItems($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->getAuctionsItems($data);
                if($result > 0)
		{
                        
			$status 	= 	"success";
			$error 		= 	'';  
        	}
                
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else 
                {
                $status 	= 	"failure";
                $error          =       "No Events";
                $result		=      "";
                }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Events"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
            
###################
###Auctionitemiddetails
####################

       public function ViewItemsAuctioned($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$watchlist	=	'';
		$user = new User();
                
		 $result = $user->getAuctionsItemsDetails($data);//print_r($result);exit;
               if($result > 0)
               // if(!(isset($result[''])
		{
                        
			$status 	= 	"success";
			$error 		= 	'';  
		//	$watchlist	=	'Added';
        	}
                
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else 
                {
                $status 	= 	"failure";
                $error          =       "No Items";
                $result		=      "";
             //   $watchlist	=	'Not Added';
                }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Items"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }

###################
#######Update A new Bid on anItem
   function updateBids($data)
		{

   global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                //return "dfdsfds";
		 $result = $user->updateItemBid($data);
                if($result == 2)
			{
			$status 	= 	"failure";
			$error 		= 	'Incomplete';
			}

                else if($result > 0)
		{
                        $pushes =$user->pushNotification($data);
                      
			$status 	= 	"success";
			$error 		= 	'';  
        	}
                
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else 
                {
                $status 	= 	"failure";
                $error          =       "No Events";
                $result		=      "";
                }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"BidId"        => $result,
                       "Push"        => $pushes
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


		}



######################
######## function to Order a Donation Returns Order ID
####################
	
        public static function OrderDonation($data)
	
	{
	
		global $_error;
	
		$data = Core::SanitizeData($data);
	
		$status 	= 	'';
	
		$error 		= 	'';
	
		$order_Id 	= 	0;
	
		$user = new User();
		
		$result = $user->MakeDonation($data);
	
		 if($result > 0)
	
			{
                        
             
          			 $status = 'success';
	
				 $order_id= $result;

				 $error 		= 	'';  

                                  $_SESSION['order_id']=$order_id;
	
		
                          	
		}
	
		 else if(!$result)
	
			{
		  
     				 $status = 'failure';

			         $error  = $_SESSION["error"];
		
				 $_error->ResetError();
		
			}
 
                          else
                
 			{
              
       		
			         $status = 'failure';
        
         		}
		
		$resultArr = array(
	
		"status" 	=> $status,
	
		"DonationId"	=> $order_id,
	
		"error"		=> $error
		
		
                    );
	
	
		$rst = self::GenerateMobileOutput($resultArr);
	
			return $rst;
		

	}
	





####################
############################# EDit USer Profile
public static function UpdateUser($data)

	{
		
         global $_error;
	
	 $data = Core::SanitizeData($data);
	
	 $status 	= 	'';
	

	 $error 		= 	'';
	
	 $userId 	= 	0;
	
	$user = new User();
		
	$result = $user->UpdateProfile($data);
	
	 if($result)// > 0)
	
	{
                        
     
                   $status 	= 	'success';
	
		//$usertId	= 	$result;
	
		$error 		= 	'';  
   
                      	
		}
	
	else if(!$result)
		{
	
	        $status 	= 	'failure';

			$error 		= 	$_SESSION["error"];

			$_error->ResetError();
	
	}
                else
            
     {
              
       $status 	= 	'failure';
  
               }
		
		$resultArr = array(
	
		"status" 	=> $status,
	
		"userid"	=> $userId,
	
		"error"		=> $error   
                 
                        
	);

		
$rst = self::GenerateMobileOutput($resultArr);
	
	return $rst;
	
}



####################
############################# Edit Get Donation Types
public static function GetDonationTypes($data)

	{
		
         global $_error;
	
	 $data = Core::SanitizeData($data);
	
	 $status 	= 	'';
	

	 $error 		= 	'';
	
	 $userId 	= 	0;
	
	$user = new User();
		
	$result = $user->getDonationTypes($data);
	
        $midtid = $user->getMidTid($data);

	 if($result > 0 && $midtid >0)
	
	{
                        
     
                 $status 	= 	'success';
	
		 $donationType	= 	$result;

                 $merchantids	= 	 $midtid;
	
		 $error 		= '';  
   
                      	
		}
	
	   else if(!$result)
		{
	
	              $status 	= 	'failure';

			$error 		= 	$_SESSION["error"];

			$_error->ResetError();
	
	            }
              
           else
            
              {
              
         $status 	= 	'failure';
  
         $error      = 	$_SESSION["error"];

               }
		
		$resultArr = array(
	
		"status" 	=> $status,
	
		"donationType"	=> $donationType,
	
		 
                 "merchantids"  => $merchantids,
                 "error"		=> $error
                 
                        
	);

		
$rst = self::GenerateMobileOutput($resultArr);
	
	return $rst;
	
}





	#############
######################### Add credit Card User 

	public function addCardUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->AddCardUser($data);
			
		if(!(isset($result["error"])) )
		{
       
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;
                          	
		}
		else 
		{
		        $status 	= 	'failure';
			$error 		= 	$result["error"];
			$usrId 		= 	$result['id']['id'];
			$_error->ResetError();
		} 
               
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
             "userId"        => $usrId,
			
                                   
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}

#######################
###################### Update Credit Card User


	public function updateCardUser($dat)
	{
		global $_error;
		$data = Core::SanitizeData($dat);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->UpdateCardUser($dat);//print_r($dat);
                 
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
                        "userId"        => $usrId,
			
                   
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}
	
########################
############# Delete Credit card user

	public function deleteCardUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->DeleteCardUser($data);
		if($result)
		{
			$status 	= 	'success';
			$error 		= 	'Card User Deleted';
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
	
########
##########Credit Card User List

	  public function CardUserList($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'No Users';
		$usrId 		= 	0;
		$user = new User();
           //     $qwerty=array();
                
		 $result = $user->ListCardUser($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
			$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			
					   );
                        foreach($result as $key=>$value)
                        {
                       $append=$key;
                        $index="CardUser".$key;
                        $resultArr[$index]=$value;
			    
                        
                        }
			
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
			$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Users"        => $result
                       
                        
		);
		
		}
                else
                 {
                     $status 	= 	'failure';
                     $resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Users"        => $result
                       
                        
		);
		
                 }
		
		$rst = self::GenerateMobileOutput($resultArr);
		//echo "<pre>";print_r($rst);exit(0);
		return $rst;


            }
            
	public function addWatchlistUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->AddWatchlistUser($data);
                 
		if(!(isset($result["error"])) )
		{
 
                       
			$status 	= 	'success';
			$error 		= 	'';
			$usrId 		= 	$result;
                          	
		}
		else 
		{
		        $status 	= 	'failure';
			$error 		= 	$result["error"];
			$usrId 		= 	$result['id']['id'];
			$_error->ResetError();
		}
               
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			 "userId"        => $usrId,
                        			           
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}
	
	public function deleteWatchlistUser($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->DeleteWatchlistUser($data);
                 
		if($result > 0)
		{
 
                       
			$status 	= 	'success';
			$error 		= 	'Watchlist User Deleted bidderitemid='.$data['bidderitemid'].' userid='. $data['userid'];
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
			
                   
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}
	
	##info of mobile user According to ID
	
       public function mobileuserinfo($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->InfoMobileUser($data);
                if($result > 0)
		{
                        
			$status 	= 	"success";
			$error 		= 	'';  
        	}
                
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else 
                {
                $status 	= 	"failure";
                $error          =       "Invalid user ID";
                $result		=      "";
                }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Userinfo"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
            
            #list watchlist user
            
	  public function listwatchlist($data)
            {

            global $_error;
		
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'No Items';
		$usrId 		= 	0;
		$user = new User();
                
		 $result = $user->listwatchlistuser($data);
                if($result > 0)
		{
                        
                       
			$status 	= 	"success";
			$error 		= 	'';  
                        
			
                          	
		}
		else if(!$result)
		{
		        $status 	= 	'failure';
			$error 		= 	$_SESSION["error"];
			$_error->ResetError();
		}
                else
                 {
                     $status 	= 	'failure';
                 }
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"Items"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
	
	public function pushnotification($data)
	{
		global $_error;
		$data = Core::SanitizeData($data);
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		$user = new User();
		$result = $user->pushNotification($data);
      
                
                
                
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
                        "userId"        => $usrId,
                     
			
                   
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst; 		
	}
	
	
	
	
	
	
	
	
	
	
}
?>
