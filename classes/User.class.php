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

	# Function to insert user
	public function AddUser($dat)
	{
		
		self::getConnection();
		
		$dat['status']='Active';
		$req_fields = self::$required_fields;
    		array_push($req_fields,'password');
    		$val_field  = self::$validate_fields;
    		$val_field  = array_merge($val_field,array('email' => 'IsEmailUnique'));
		if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		   $password = ($dat['password'] != "")?Core::Encrypt($dat['password']):'';
		   $qry    = " INSERT INTO     `users` 
		                                (`id`, `first_name`, `last_name`, `email`, `password`,`added_at`, `updated_at`) 
		                VALUES          (NULL, '{$dat['first_name']}', '{$dat['last_name']}', '{$dat['email']}', 
								 '{$password}',NOW(),NOW()); ";
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
		
	    	$qry = "SELECT SQL_CALC_FOUND_ROWS * FROM users ORDER BY id DESC LIMIT {$offset},{$limit}";
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
		
	    	$qry = "SELECT * FROM users WHERE id='".$id."'";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultRow();
		return $result;
	}
	
	# Function to add FB User
	public function AddFBUser($dat)
	{
		self::getConnection();
		$dat['status']='Active';
		$req_fields = self::$required_fields;
    		$val_field  = self::$validate_fields;
		if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		  	$qry    = "INSERT INTO     `users` 
		                                (`id`, `first_name`, `last_name`, `email`,`fb_id`,`added_at`, `updated_at`) 
		                VALUES          (NULL, '{$dat['first_name']}', '{$dat['last_name']}', '{$dat['email']}','{$dat['fb_id']}',
								 NOW(),NOW()); ";
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
				$qry    = " UPDATE  `users` 
			                SET     `first_name`	= '{$dat['first_name']}',
			                        `last_name` 	= '{$dat['last_name']}',
			                        `email` 	= '{$dat['email']}',
						`password`	= '".md5(md5($dat['paswd']))."',
			                        `updated_at` 	= NOW()
			                WHERE   `users`.`id` 	= {$dat['user_id']}; ";
			} else {
				$qry    = " UPDATE  `users` 
			                SET     `first_name`	= '{$dat['first_name']}',
			                        `last_name` 	= '{$dat['last_name']}',
			                        `email` 	= '{$dat['email']}',
			                        `updated_at` 	= NOW()
			                WHERE   `users`.`id` 	= {$dat['user_id']}; ";
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
			$qry = "UPDATE 		`users`
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
	    	            FROM   users
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
	    	            FROM   users
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
	    	            FROM   users
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
		$qry    = " SELECT 	id, first_name, last_name
					FROM	users 
					WHERE	status = 'Active' 
							AND email = '{$dat['email']}'
							AND password LIKE BINARY '" . Core::Encrypt($dat['password']) . "' ";
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
			    FROM users
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
		            FROM    `users`
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
		
		    	$qry	= "SELECT id FROM users
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
	    	            FROM   users
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
			$qry = "UPDATE users
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
	    	            FROM   users
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
			$qry = "UPDATE users
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
		
		$qry = "DELETE FROM users WHERE id='".$id."'";
		self::$dbo->doQuery($qry);

        	return true;
    	}
	
	public function getUserSignatureCount($id)
	{
		self::getConnection();
	    	$qry = "SELECT COUNT(userPollingId) AS userSignatureCount
			FROM user_polls up
			LEFT JOIN users u ON up.userId=u.id
			WHERE up.userId='".$id."'
			LIMIT 1";
		self::$dbo->doQuery($qry);
		$resp = self::$dbo->getResultRow();

		return $resp['userSignatureCount'];
	}
	
}

?>
