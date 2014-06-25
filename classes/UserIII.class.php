<?php
class UserIII
{
	public static $dbo;
	protected static $required_fields  = array('first_name','email');
	protected static $fields_size       = array();
    	protected static $validate_fields  = array('email' => 'isEmail');

	public static function getConnection()
	{
	    self::$dbo = Database::getInstance();
	    self::$dbo->connect(); 
	}



		// return true if event exist else 0
	// argument event id
	public function isEventId($id)
 
       	{
				self::getConnection();
				$qry = "SELECT id FROM events  WHERE id=".$id." and publish=1";
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				
				if(is_array($result))
				{	
					if($result[0]['id']==$id)
					{
						
						$result="1";
					}
					else
					{
						$result="0";
					}
				}
				else
				{
					$result="0";
				}
				
		return $result;
			
		}
		
	public function isMobileUser($id)
	{
		self::getConnection();
     
       $qry="select id from mobile_users where id ='".$id."' ";
      	
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		if(is_array($result))
		{
			return 1;
		}
		else
		{
			return 0;
		}
		
		return 0;
		
	}
		
	public function isPurchasedMobileUser($eventId,$mobileUserId)
	{
		/*
		 * 1. mobileUserId convert to userid
		 * 2. check entry in Auctionbidder table with the combination of
		 * 		merchantid, eventid, userid
		 * 	if an enry exist
		 * 			purchased user
		 * else 
		 * 			not purchased 
		 * 
		 * return true or false
		 * 
		 * */
				self::getConnection();
				$retunvar=array();
				$userId=self::mobileUserToNormalUserId($eventId,$mobileUserId);
				if($userId==false)
					{	//invalid id or reg not completed
						
						$retunvar['msg']="invalid eventId or mobileUserId";
						$retunvar['stat']="failure";
						$retunvar['info']="";
						return $retunvar;
					}
				
				$merchantId=self::eventMerchantId($eventId);//fetch merchantid
					
					if($merchantId==false)
					{$retunvar['msg']="invalid eventId or mobileUserId";
						$retunvar['stat']="failure";
						$retunvar['info']="";
						return $retunvar;}
					
				// check auction bidder entry 
				$qry = "SELECT id FROM auction_bidders  WHERE event_id".$eventId." AND merchant_id=".$merchantId." AND user_id=".$userId;
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				if(is_array($result))
				{
							$retunvar['msg']="";
						$retunvar['stat']="success";
						$retunvar['info']="true";
						return $retunvar;
				}
				else
				{
							$retunvar['msg']="";
							$retunvar['stat']="success";
							$retunvar['info']="false";
							return $retunvar;
				}
				$retunvar['msg']="invalid eventId or mobileUserId";
				$retunvar['stat']="failure";
				$retunvar['info']="";
				return $retunvar;	
		
	}	
	
	public function getMealsListReminingEvents($eventId)
	{
		
		self::getConnection();
		echo  $qry = "SELECT dinner_available FROM events  WHERE id=".$eventId;
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				print_r($result);exit;
		
	}
		
	public function mobileUserToNormalUserId($eventId,$mobileUserId)
	{
		
		/*
		 * 
		 * 
		 * 
		 * Fetch email id from 'mobile_users' table
		 * 
		 * Using  merchant_id and email of mobile_users search for user id in 'users' table
		 * if not then add to 'users' table and return id
		 * 
		 * 
		 * 
		 * */
			$qry = "SELECT email FROM mobile_users  WHERE id=".$mobileUserId;
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				if(is_array($result))
				{// to check mobile user id is valid or not  if valid get email
					if(isset($result[0]['email']))
					{
						if($result[0]['email']!='')
						{
							$email=$result[0]['email'];
						}
						else
						{
								return false;							
						}						
						
					}
					else
					{
						return false;
					}
				}
				else 
				{
						return false;					
				}
				
				if(isset($email))
				{
					
					$merchantId=self::eventMerchantId($eventId);//fetch merchantid
					
					if($merchantId==false)
					{return false;}
					
					$qry = "SELECT id FROM users  WHERE username='".$email."' and merchant_id=".$merchantId;
					self::$dbo->doQuery($qry);
					$result = self::$dbo->getResultSet();
					if(is_array($result))
					{
						// This user is registerd under this merchant already
						return ($result[0]['id']);
						
					}
					else
					{
							// This user is not registerd under this merchant 
							//We want to add this mobile user to 'user' table under this merchant
							$qry = "SELECT * FROM mobile_users  WHERE id=".$mobileUserId;
							self::$dbo->doQuery($qry);
							$result = self::$dbo->getResultSet();
							if(is_array($result))
							{
								$qry = "INSERT INTO users (merchant_id,username,group_id,created,modified) VALUES (".$merchantId.",'".$result[0]['email']."',0,NOW(),NOW())";
								self::$dbo->doQuery($qry);
								$result=self::$dbo->getInsertID();
								return($result);
							}
							else
							{
								//invalid user
								return false;
							}						
							
					}						
					
				}
				else
				{
					return false;
				}
				
				return false;

	}
	
	//Return merchant id of an event
	public function eventMerchantId($eventId)
	{
				$qry = "SELECT merchant_id FROM events  WHERE id=".$eventId;
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				if(is_array($result))
				{
					return($result[0]['merchant_id']);
				}
				else
				{
					return false;
					
				}		
	}
	
	public function appAuthenticateQRCode($data)
	{
				self::getConnection();
				$user_id= $data['mobuserId'];
				$event_id=$data['eventId'];
				$id=$user_id.$event_id;
				$len= strlen ( $id );
				$add_len=10-$len;
				 $result = '';

				for($i = 0; $i < $add_len; $i++) {
					$result .= mt_rand(0, 9);
				}
				
				
				$id.=$result;
				
				return $id;
	}
	
	
	public function isExistActionBiddersTable($data)
 
       	{//check pls
				self::getConnection();
				$qry = "SELECT id FROM auction_bidders  WHERE event_id=".$data['eventId']." and user_id=".$data['mobuserId']." AND email='".$data['email']."'";
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				
				if(is_array($result))
				{	$id= $result[0]['id']; 
					$qry = "UPDATE auction_bidders set event_registration_code=".$data['code']." where id=".$id;
					$stat =self::$dbo->updateQuery($qry);
					if($stat)
						{return 1;}
					else
						{return 0;}
				}
				else
				{
					
						if($data['usrStat']=='reg'){
							$qry = "SELECT *  from events where id=".$data['eventId'];
							self::$dbo->doQuery($qry);
							$event = self::$dbo->getResultSet();
						
							$qry = "SELECT *  from mobile_users where id =".$data['mobuserId'];
							self::$dbo->doQuery($qry);
							$mobileuser = self::$dbo->getResultSet();
						
							$qry = "SELECT id from users where username ='".$data['email']."'";
							self::$dbo->doQuery($qry);
							$userid = self::$dbo->getResultSet();
							/*echo '<pre>';print_r($mobileuser);echo '</pre>';
							echo '<pre>';print_r($event);echo '</pre>';
							echo '<pre>';print_r($userid );echo '</pre>';exit;*/
						}
					
				}
				
		return $result;
			
		}

}
