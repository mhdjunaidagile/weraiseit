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
		
	
	public function appAuthenticateQRCode($data)
	{
		
				$user_id= $data['mobuserId'];
				$event_id=$data['eventId'];
				$id=$user_id.$event_id;
				$len= strlen ( $id );
				$add_len=10-$len;
				 $result = '';

				for($i = 0; $i < $add_len; $i++)
				{
					$result .= mt_rand(0, 9);
				}
				
				
				$id.=$result;
				
				return $id;
	}
	
	
	public function isExistActionBiddersTable($mobuserid, $eventid, $email, $code)
 
       	{
				$qry = "SELECT id FROM auction_bidders  WHERE event_id=".$eventid." and user_id=".$mobuserid." AND email='".$email."'";
				self::$dbo->doQuery($qry);
				$result = self::$dbo->getResultSet();
				
				if(is_array($result))
				{	$id= $result[0]['id']; 
					$qry = "UPDATE auction_bidders set event_registration_code=".$code." where id=".$id;
					$stat =self::$dbo->updateQuery($qry);
					if($stat)
						{return 1;}
					else
						{return 0;}
				}
				else
				{
					
					$qry = "SELECT *  from events where id=".$eventid;
					self::$dbo->doQuery($qry);
					$event = self::$dbo->getResultSet();
					
					$qry = "SELECT *  from mobile_users where id =".$mobuserid;
					self::$dbo->doQuery($qry);
					$mobileuser = self::$dbo->getResultSet();
					
					$qry = "SELECT id from users where username ='".$email."'";
					self::$dbo->doQuery($qry);
					$userid = self::$dbo->getResultSet();
					/*echo '<pre>';print_r($mobileuser);echo '</pre>';
					echo '<pre>';print_r($event);echo '</pre>';
					echo '<pre>';print_r($userid );echo '</pre>';exit;*/
					
				}
				
		return $result;
			
	}

}
