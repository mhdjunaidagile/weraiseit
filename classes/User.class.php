<?php
class User
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


# test
  # Function to get All Live Events

        public function getLiveEvents($data)
 
       {

         self::getConnection();

        $qry = "SELECT event_name,city,start_date,end_date FROM `events` WHERE `end_date`> NOW()";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   
       }





 # FUnction to add merchant User Relation

        public function AddMerchantUserRelation($data)
 
       {

              $currPaswd="63c5e17c552eb54e4f4239f2defffe80";
              self::getConnection();
for ($i = 1; $i <= $data['totalmerchants']; $i++) {
 $merchant_id = $data['merchant'.$i.'id'];
	    	 $qry    = " INSERT INTO     `mobile_merchant_user` 
		                                (`id`, `user_id`, `merchant_id`) 
		                VALUES          (NULL, '".$data['userId']."', '". $merchant_id."'); ";
		self::$dbo->doQuery($qry);
		$insId = self::$dbo->getInsertID();
}
                 return $insId;
       }


         # FUnction to add get User ID of merchnats

        public function VerifyMerchantUser($data)
 
       {

	   
	       self::getConnection(); 
	    for ($i = 1; $i <= $data['totalmerchants']; $i++) {
           $merchant_id = $data['merchant'.$i.'id'];   
	     $password1 = $data['merchant'.$i.'password'];   
	     $password= md5($password1);
	        $qry="SELECT merchant_id from users WHERE merchant_id = '".$merchant_id."' and password = '".$password."' "; 
	        self::$dbo->doQuery($qry);
		$results = self::$dbo->getResultRow();
                if($results == null)
                  {
                  return 0;
               }
	 }
              return $results;


	    
	}
       # FUnction to get Merchants

        public function getMerchants($email)
 
       {

         self::getConnection();

        $qry = "SELECT DISTINCT U.merchant_id,M.website FROM users U INNER JOIN merchants M ON U.merchant_id=M.id WHERE username='".$email."'";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   
       }


	# Function to insert user
	public function AddUser($dat)
	{
		
		self::getConnection();
		
		$req_fields = self::$required_fields;
    		array_push($req_fields,'password');
    		$val_field  = self::$validate_fields;
    		$val_field  = array_merge($val_field,array('email' => 'IsEmailUnique'));
		//if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		   $password = ($dat['password'] != "")?Core::Encrypt($dat['password']):'';
		   $qry    = " INSERT INTO     `mobile_users` 
		                                (`id`, `first_name`, `last_name`, `email`, `password`) 
		                VALUES          (NULL, '{$dat['first_name']}', '{$dat['last_name']}', '{$dat['email']}', 
								 '{$password}'); ";
		self::$dbo->doQuery($qry);
		$insId = self::$dbo->getInsertID();
			$userEmail = $dat['email'];

			
			
			return $insId;
		}
        	return FALSE;
	}
	
	#Function to list whole User details
	public function ViewUsers($offset=0, $limit='')
	{
		self::getConnection();
		
	    	$qry = "SELECT SQL_CALC_FOUND_ROWS * FROM mobile_users ORDER BY id DESC LIMIT {$offset},{$limit}";
		self::$dbo->doQuery($qry);
		$resp['data'] = self::$dbo->getResultSet();

		$Query = "SELECT FOUND_ROWS() AS maxCount" ;
		self::$dbo->doQuery($Query);
		$results = self::$dbo->getResultRow();
		$resp['maxCount'] = $results['maxCount'];

		return $resp;
	}
	
	#Function to view selected User details
	public function get_user_details($id)
	{
		self::getConnection();
		
	    	$qry = "SELECT * FROM mobile_users WHERE id='".$id."'";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultRow();
		return $result;
	}
	
	# Function to add FB User
	public function AddFBUser($dat)
	{
		self::getConnection();
		$req_fields = self::$required_fields;
    		$val_field  = self::$validate_fields;
		if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		  	$qry    = "INSERT INTO     `mobile_users` 
		                                (`id`, `first_name`, `last_name`, `email`,`fb_id`) 
		                VALUES          (NULL, '{$dat['first_name']}', '{$dat['last_name']}', '{$dat['email']}','{$dat['fb_id']}'); ";
		    	self::$dbo->doQuery($qry);
			$insId = self::$dbo->getInsertID();

			$userEmail = $dat['email'];


		    	return $insId;
		}
        	return FALSE;
	}

	#Function to update User
	public function UpdateUser($dat)
	{
	    	global $_error;
	    	$req_fields = self::$required_fields;
		$val_field  = self::$validate_fields;
		if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		    if(self::CheckEmailAvailable($dat['email'],$dat['user_id']))
		    {
			if(!empty($dat['paswd']) && ($dat['paswd'] == $dat['retypepaswd']))
			{
				$qry    = " UPDATE  `mobile_users` 
			                SET     `first_name`	= '{$dat['first_name']}',
			                        `last_name` 	= '{$dat['last_name']}',
			                        `email` 	= '{$dat['email']}',
						`password`	= '".md5(md5($dat['paswd']))."',
			                        `updated_at` 	= NOW()
			                WHERE   `mobile_users`.`id` 	= {$dat['user_id']}; ";
			} else {
				$qry    = " UPDATE  `mobile_users` 
			                SET     `first_name`	= '{$dat['first_name']}',
			                        `last_name` 	= '{$dat['last_name']}',
			                        `email` 	= '{$dat['email']}',
			                        `updated_at` 	= NOW()
			                WHERE   `mobile_users`.`id` 	= {$dat['user_id']}; ";
			}
			    self::$dbo->doQuery($qry);
			    if(self::$dbo->getResultNumRows() > 0)
			    {
				return TRUE;
			    } 
		    }
		    else
		    {
		        $_error->AddError('Email Already Exists.');
		        return false;
		    }
		}
		return FALSE;
	}
	
	#Function to update facebook id
	public function InsertFBId($fb_id,$user_id)
	{
		self::getConnection();
		if(!Validate::isEmpty($fb_id))
		{
			$qry = "UPDATE 		`mobile_users`
					SET `fb_id` = '{$fb_id}'
					WHERE `id` = {$user_id}";
			self::$dbo->doQuery($qry);
			if(self::$dbo->getResultNumRows() > 0)
			{
				return TRUE;
			}
		}
		return FALSE;
	}
	
	#Function to check email is unique or not
	public function CheckEmailUnique($email)
    	{
		self::getConnection();
		
	    	$qry   =   "SELECT count(*)
	    	            FROM   mobile_users
	    	            WHERE  email   =   '{$email}'";
		self::$dbo->doQuery($qry);
	    	$count =   self::$dbo->FetchValue();
	    	if($count > 0)
	    	{
	    		return false;
	    	}
	    	else
	    	{
	    		return true;
	    	}
   	}

	#function to get user with facebook id
	public function CheckFacebookId($fb_id)
	{
		self::getConnection();
		
	    	$qry   =   "SELECT `id`
	    	            FROM   mobile_users
	    	            WHERE  `fb_id`   =   '{$fb_id}'";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
	    	return $value;
	}
	
	#function to get userid with email
	public function GetUserIdwithEmail($email)
	{
		self::getConnection();
		
	    	$qry   =   "SELECT `id`
	    	            FROM   mobile_users
	    	            WHERE  email   =   '{$email}'";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
		return $value;
	}

	#function to authenticate user
	public function Authenticate($dat)
    	{
		global $_error;
		self::getConnection();
		if (!self::ValidateUserData($dat))
		{
		    return false;
		}

$pwd= md5($dat['password']);

$qry    = " SELECT 	id, first_name, last_name
					FROM	mobile_users 
					WHERE	email = '{$dat['email']}'
							AND password ='".$pwd."' ";
                 
		self::$dbo->doQuery($qry);
		$rst = self::$dbo->getResultRow();
		if($rst)
		{
			return $rst;
		}
		else
		{
			$_error->AddError("Email/Password is incorrect!");
			return false;
		}
   	}    
    
    	private static function ValidateUserData($dat)
    	{
		if (Validate::isEmail($dat['email']) && !Validate::isEmpty($dat['password']))
		{
		    return true;
		}
        	return false;
    	}
    
	/* checks email availability before update */
	    public static function CheckEmailAvailable($email, $user_id)
	    {
		self::getConnection();
		$qry   =   "SELECT count(id)
			    FROM mobile_users
			    WHERE `email` = '{$email}'
			    and `id` != '{$user_id}'";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
		if($value > 0)
		{
		    return false;
		}
		else
		{
		    return true;
		}
	    }
	
	#Function to fetch user
	public function FetchUser($user_id)
    	{
		self::getConnection();
		$qry    = " SELECT  `id`, `first_name`, `last_name`,`email`,
		            FROM    `mobile_users`
		            WHERE   `id` = {$user_id} ";
		self::$dbo->doQuery($qry);
        	return self::$dbo->getResultRow();
    	}

	public function isFbUserExists($data)
	{
		self::getConnection();
		if(!empty($data['fb_id']))
		{
			self::getConnection();
		
		    	$qry	= "SELECT id FROM mobile_users
		    	            WHERE fb_id='".$data['fb_id']."' AND email='".$data['email']."'
					LIMIT 1";
			self::$dbo->doQuery($qry);
		    	$value =   self::$dbo->FetchValue();
		    	return $value;
		}
	}

	function generateRandomString($length = 10) {
	    $characters = 'abcdefghjkmnpqrstuvwxyz23456789ABCDEFGHJKMNPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	
	public function getNormalUserWithEmail($email)
	{
		self::getConnection();
	    	$qry   =   "SELECT id
	    	            FROM   mobile_users
	    	            WHERE  email='{$email}' AND password<>''";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
		return $value;
	}
	
	public function updateUserTempPassword($userId, $tempPaswd, $userEmail)
	{
		self::getConnection();
		if(!Validate::isEmpty($tempPaswd))
		{
			$qry = "UPDATE mobile_users
				SET password = '".Core::Encrypt(Core::Encrypt($tempPaswd))."'
				WHERE id = '".$userId."'";
		
			self::$dbo->doQuery($qry);
			if(self::$dbo->getResultNumRows() > 0)
			{
				



				return TRUE;
			}
		}
		return FALSE;
	}

	public function verifyUser($userId,$currPaswd)
	{
		self::getConnection();
	    	$qry   =   "SELECT email
	    	            FROM   mobile_users
	    	            WHERE  id='".$userId."' AND password='".Core::Encrypt($currPaswd)."'";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
		return $value;
	}

	public function updateUserPassword($userId, $currPaswd, $newPaswd, $userEmail)
	{
		self::getConnection();
		if(!Validate::isEmpty($newPaswd))
		{
			$qry = "UPDATE mobile_users
				SET password = '".Core::Encrypt($newPaswd)."'
				WHERE id = '".$userId."' AND password='".Core::Encrypt($currPaswd)."'";
		
			self::$dbo->doQuery($qry);
			if(!empty($userEmail))
			{


				return TRUE;
			}				
		}
		return FALSE;
	}

	public function deleteUser($id)
    	{
		self::getConnection();
		
		$qry = "DELETE FROM mobile_users WHERE id='".$id."'";
		self::$dbo->doQuery($qry);

        	return true;
    	}
	
	public function getUserSignatureCount($id)
	{
		self::getConnection();
	    	$qry = "SELECT COUNT(userPollingId) AS userSignatureCount
			FROM user_polls up
			LEFT JOIN mobile_users u ON up.userId=u.id
			WHERE up.userId='".$id."'
			LIMIT 1";
		self::$dbo->doQuery($qry);
		$resp = self::$dbo->getResultRow();

		return $resp['userSignatureCount'];
	}
	
	public function AddUser1($dat)
	{
		
		self::getConnection();
		if ($dat['email']=='' && $dat['username']=='' && $dat['channel']!=='') return FALSE;
		//$dat['status']='Active';
		$req_fields = self::$required_fields;
    		//array_push($req_fields,'password');
    		$val_field  = self::$validate_fields;
    		//$val_field  = array_merge($val_field,array('email' => 'IsEmailUnique'));



		//if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		//{

		
		$currUserId = $this->checkUserExists($dat['channel'], $dat['email'], $dat['username']);
		if($currUserId > 0) {
		      $text=array("error"=>'Already exist', "id"=>$currUserId);
		    return $text;

		} 
		
	      else {
		    //  $password = ($dat['password'] != "")?Core::Encrypt($dat['password']):'';
		    $qry 	= " INSERT INTO `mobile_users` 
				  (username, first_name, last_name, email, channel) 
				    VALUES
				  ('".$dat['username']."', '".$dat['first_name']."', '".$dat['last_name']."', '".$dat['email']."', '".$dat['channel']."');";

		    self::$dbo->doQuery($qry);
		    $insId = self::$dbo->getInsertID();
		$text=array("error"=>'', "id"=>$insId);
		    return $text;
		}
		//}
        	return FALSE;
	}

	#function to get userid with email
	public function checkUserExists($channel, $email='', $username='')
	{
		self::getConnection();
		
	    	$qry   =   "SELECT `id`
	    	            FROM   mobile_users
	    	            WHERE  ((username = '".$username."' AND username != '') OR (email = '".$email."' AND email != '')) AND channel = '".$channel."'";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
		return $value;
	}
}

?>
