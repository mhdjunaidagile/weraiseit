<?php

class MobileIII
{


			public static function GenerateMobileOutput($data)
	{
		return json_encode($data);
	}
	// version III
	
	// Event Registration QR code
	
	public function eventRegistration($data)
    {
		
		$user = new UserIII();

        global $_error;
        // parameter validating
		if(!(isset($data['userId'])||isset($data['eventId'])||isset($data['action'])))
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
			if($data['mobuserId']==''||$data['eventId']==''||$data['action']==''){
				
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
		$user = new User();
                
          $data['id']=  $data['mobuserId'];  
    
		 $result = $user->InfoMobileUser($data);
		 
		
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
					$code= $user->appAuthenticateQRCode($data);
					
					$upflag= $user->isExistActionBiddersTable($data['mobuserId'], $data['eventId'], $data['email'], $code);
					
					if($upflag){
					 $status 	= 	"success";
					$error 		= 	''; 
					$result		=   $code;}
					else
					{
							$status 	= 	"failure";
							$error 		= 	'Error encountered'; 
							$result		=   '';
					}
			}
		}
		else
		{ $status 	= 	"failure";
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
