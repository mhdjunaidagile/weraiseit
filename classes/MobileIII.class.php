<?php

class MobileIII
{


			public static function GenerateMobileOutput($data)
	{
		return json_encode($data);
	}
	// version III
	
	public function eventIsUserPurchased($data)
	{
		$data = Core::SanitizeData($data);
		$user = new UserIII();
		$user1 = new User();
		$status=$user->isPurchasedMobileUser($data['eventId'],$data['mobileUserId']);
		
			$resultArr = array(
			"status" 	=> $status['stat'],
			"error"		=> $status['msg'],
			"info"        => $status['info']                  
                        );
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
		
		
	}
	
	public function getMealListIfAvail($data)
	{
		$user = new UserIII();
		$user1 = new User();
		 global $_error;
		 $data = Core::SanitizeData($data);
		 // validation start
		 if(!(isset($data['mobileUserId'])||isset($data['eventId'])))
		{
			 $resultArr = array(
			"status" 	=> "failure",
			"error"		=> "Invalid arguments",
			"action"			=> "",
			"info"        => "Access Denied"                  
                        );
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
		}
		else
		{
			if($data['mobileUserId']==''||$data['eventId']=='')
			{
				
				 $resultArr = array(
								"status" 	=> "failure",
								"error"		=> "Arguments Could not be empty",
								"action"			=> "",
								"info"        => "Access Denied"                  
									);
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
				
				
			}
			
			
		}
		
		
		$eventValid=$user->isEventId($data['eventId']);
		
		if($eventValid==0)
		{
			 $resultArr = array(
								"status" 	=> "failure",
								"error"		=> "Invalid Event",
								"action"			=> "",
								"info"        => "Access Denied"                  
									);
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
				
				
		}
			
		$userValid=$user->isMobileUser($data['mobileUserId']);
		if($userValid==0)
		{
			 $resultArr = array(
								"status" 	=> "failure",
								"error"		=> "Invalid User",
								"action"			=> "",
								"info"        => "Access Denied"                  
									);
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
				
				
		}
		
		// validation over
		 $meals=$user->getMealsListReminingEvents($data['eventId']);
		 $resultArr = array(
						"status" 	=> "success",
						"error"		=> "",
						"action"			=> "skip",
						"info"        => "No meals available"                  
							);
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
		
		
	}
	
	
	
	
	// Event Registration QR code
	
	public function eventRegistration($data)
    {
		
		$user = new UserIII();
		$user1 = new User();
		

        global $_error;
        // parameter validating
		if(!(isset($data['userId'])||isset($data['eventId'])||isset($data['action']) ||isset($data['usrStat'])))
		{
			
			$resultArr = array(
			"status" 	=> "failure",
			"error"		=> "Invalid arguments",
			"info"        => "Access Denied"                  
                        );
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
		}
		else
		{
			if($data['mobuserId']==''||$data['eventId']==''||$data['action']=='' ||$data['usrStat']==''){
				
				$resultArr = array(
			"status" 	=> "failure",
			"error"		=> "Arguments Could not be empty",
			"info"        => "Access Denied"                  
                        );
		
			$rst = self::GenerateMobileOutput($resultArr);
			return $rst;
			}
			
			
		}
		$data = Core::SanitizeData($data);
		
		
		
		
		$status 	= 	'';
		$error 		= 	'';
		$usrId 		= 	0;
		
                
          $data['id']=  $data['mobuserId'];  
    
		 $result = $user1->InfoMobileUser($data);
		 
		
		 if(is_array($result))
		 {
			 
			$eventexist= $user->isEventId($data['eventId']);
			 if($eventexist=="0" || $eventexist== 0 || $eventexist=='0')
			 {
				 
				 $resultArr = array(
						"status" 	=> "failure",
						"error"		=> "Invalid Event ID",
						"info"        => "Access Denied" 
							);
		
				$rst = self::GenerateMobileOutput($resultArr);
				return $rst;
			 }
			 
			 if($result[0]['id']==$data['mobuserId'])
			 { // success validations
					
					$data['email']=$result[0]['email'];
					if($data['usrStat']=='reg')
					{
						$code= $user->appAuthenticateQRCode($data);
						$data['code']=$code;
					
						$upflag= $user->isExistActionBiddersTable($data['mobuserId'], $data['eventId'], $data['email'], $code);
					
						if($upflag)
						{
							echo  $status 	= 	"success";
							$error 		= 	''; 
							$result		=   $code;
						}
						else
						{
						
								echo "new dfdf";
								$status 	= 	"failure";
								$error 		= 	'Error encountered'; 
								$result		=   '';
						}
					}
					else
					{
							//reg not completed
						
						
						
					}
			}
		}
		else
		{ 
			echo "invalid mobile user";
			$status 	= 	"failure";
                $error          =       "Invalid user ID";
                $result		=      "Access Denied";	}
		
                
                
                
                
                
                
                
                
                
              /*  if($result > 0)
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
                
                */
		$resultArr = array(
			"status" 	=> $status,
			"error"		=> $error,
			"info"        => $result
                       
                        
		);
		
		$rst = self::GenerateMobileOutput($resultArr);
		return $rst;


            }
	
}


?>
