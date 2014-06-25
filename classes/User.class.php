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



   ############################ Function to get All Live Events################
   ################################################### 
        public function getLiveEvents($data)
 
       	{

         	self::getConnection();
 
                if ($data['type']=="today")
			{

			 $qry = "SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE date(`start_date`) = CURDATE()";

			}
        	else if ($data['type']=="oneweek")
			{

			$qry="SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE DATE_ADD(CURDATE(),INTERVAL 1 WEEK) <= `start_date`";
		
			}
		else if ($data['type']=="twoweek")
			{

			$qry="SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE DATE_ADD(CURDATE(),INTERVAL 2 WEEK) <= `start_date`";
		
			}
		else if ($data['type']=="threeweek")
			{

			$qry="SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE DATE_ADD(CURDATE(),INTERVAL 3 WEEK) <= `start_date`";
		
			}
		else if ($data['type']=="onemonth")
			{
			
			$qry="SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE DATE_ADD(CURDATE(),INTERVAL 30 DAY) <= `start_date`";
		
			}
		else if ($data['type']=="allevents")
			{

			$qry = "SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE `end_date`> NOW()";
		
			}
		else if ($data['type']=="span")
			{
			
			$qry = "SELECT id,merchant_id,event_name, city, start_date, end_date,summary FROM  `events`  WHERE start_date BETWEEN  '".$data['from']."' AND  '".$data['to']."'";
		
			}

		else if($data['eventname'])
  			{

			$qry = "SELECT id,merchant_id,event_name,city,start_date,end_date,summary FROM `events` WHERE `end_date`> NOW() AND event_name LIKE '%".$data['eventname']."%'";
  
  	       		 }

		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
	
	}  

#################################
############Get All Live Events When event name is passed as parameter ie event search based on Event Name###########
#####################################################   

       public function getEvents($data)
 
      		 {

         	self::getConnection();
		if ($data['eventname']=='' ) return FALSE;
       	        $qry =" SELECT AI.title,E.id FROM  `auction_items` AS AI INNER JOIN  `events` AS E ON E.id = AI.event_id WHERE E.event_name like '%".$data['eventname']."%' AND E.end_date<NOW()"; 
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   		
	        }




#####################################################
 ############### Function to get All Item based Event List - ie when Item name is passed 
#####################################################
        public function getItemEventsList($data)
 
       {

         self::getConnection();
	
        $qry = "SELECT E.id,E.merchant_id,E.event_name,E.city,E.start_date,E.end_date,E.summary FROM `events` E INNER JOIN `auction_items` AI ON E.id = AI.event_id WHERE AI.`title` LIKE '%".$data['Item']."%'";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   
   
      }





#####################################################
 ############### Function to get All Auctions List - ie sorting, search
#####################################################

       public function getAuctions($data)
 
       {

               self::getConnection();
 
                if ($data['type']=="today")
		{

		 $qry = "SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE date(`start_date`) = CURDATE()";

		}
        	else if ($data['type']=="oneweek")
		{

		$qry="SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE DATE_SUB(CURDATE(),INTERVAL 1 WEEK) <= `start_date`";
		
		}
		else if ($data['type']=="twoweek")
		{

		$qry="SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE DATE_SUB(CURDATE(),INTERVAL 2 WEEK) <= `start_date`";
		
		}
		else if ($data['type']=="threeweek")
		{

		$qry="SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE DATE_SUB(CURDATE(),INTERVAL 3 WEEK) <= `start_date`";
		
		}
		else if ($data['type']=="onemonth")
		{
		
		$qry="SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= `start_date`";
		
		}
		else if ($data['type']=="allevents")
		{

		$qry = "SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE `end_date`> NOW()";
		
		}
		else if ($data['type']=="span")
		{
		$qry = "SELECT id,auction_name,start_date,end_date FROM `online_auctions`  WHERE start_date BETWEEN  '".$data['from']."' AND  '".$data['to']."'";
		}
		else if($data['auctionname'])
  		{

		$qry = "SELECT id,auction_name,start_date,end_date FROM `online_auctions` WHERE `end_date`> NOW() AND auction_name LIKE '%".$data['auctionname']."%'";
  
  	        }



		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
	} 

###########################
######## All Items List based on Event Id
#########################################


      public function getItemsList($data)
  
         {
           self::getConnection();
           $qry="SELECT M.domain,AIP.id,title,opening_bid, retail_value, available_num   FROM `merchant_portals` as M,`auction_items` AI INNER JOIN `events` E ON E.id = AI.event_id INNER JOIN `auction_item_photos` AS AIP ON AIP.auction_item_id=AI.id WHERE E.id='".$data['eventid']."' AND AI.title like '%".$data['title']."%'";
           self::$dbo->doQuery($qry);
	   $result = self::$dbo->getResultSet();
	   return $result;   
         }


#####################
#############Get Images from Event Id
#####################################

      public function getImagesList($data)
 
       {

         self::getConnection();

	$qry="select M.legal_name,AIP.id from `merchants` as M,`events` as E INNER JOIN `auction_items` AI ON E.id=AI.event_id INNER JOIN `auction_item_photos` AS AIP ON AIP.auction_item_id=AI.id where E.id='".$data['eventid']."' and M.id=AI.merchant_id";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   
   
      }


###########################
######## All Auction Items List
#########################################
            
       public function getAuctionsItems($data)
 
       {

                self::getConnection();
 		if ($data['auctiontitle'])
		{

		$qry="select AI.id,AI.title,MP.domain,AIP.name,AIP.id as image_id,AI.description,TIMESTAMPDIFF(SECOND,NOW(),OA.end_date) AS RemainingTime,(select amount from bids where auction_item_id=AI.id order by time desc limit 0,1 ) AS current_bid,OA.auction_name from auction_items as AI inner join online_auctions as OA on AI.`online_auction_id`=OA.`id` 
		inner join merchant_portals as MP on AI.merchant_id =MP.merchant_id left join auction_item_photos as AIP on AIP.auction_item_id=AI.id where AI.title LIKE '%".$data['auctiontitle']."%'";

		}
		
		else if($data['onlineauctionid'])
		{
		$qry="select AI.id,AIP.id as image_id, AIP.name,AI.title,MP.domain,AI.description, TIMESTAMPDIFF(SECOND,NOW(),OA.end_date) AS RemainingTime,OA.auction_name,(select amount from bids where auction_item_id=AI.id order by time desc limit 0,1 ) AS current_bid  
		from auction_items as AI inner join online_auctions as OA on AI.`online_auction_id`=OA.`id` inner join merchant_portals as MP on AI.merchant_id =MP.merchant_id left join auction_item_photos as AIP on AIP.auction_item_id=AI.id  
		where AI.`online_auction_id`='".$data['onlineauctionid']."'";
		     
		}
	      
	    	else if($data['onlineauctionidetails'])
		{

		$qry="select AI.*,(select amount from bids where auction_item_id=AI.id order by time desc limit 0,1 ) AS current_bid ,AI.id,OA.auction_name,AIP.id as image_id ,MP.domain,AIP.name from auction_items as AI inner join online_auctions as OA on AI.`online_auction_id`=OA.`id`inner join merchant_portals as MP on MP.merchant_id=AI.merchant_id
		left join auction_item_photos as AIP on AIP.auction_item_id=AI.id where AI.`online_auction_id`='".$data['onlineauctionidetails']."'";	
	      
		}
		else if ($data['auctionitemdetails'])
	      {
	      $qry="select AIP.name,AI.id,AIP.id as image_id, AI.* ,MP.domain from merchant_portals as MP inner join auction_items as AI on AI.merchant_id=MP.merchant_id 
	      left join auction_item_photos as AIP on AIP.auction_item_id=AI.id where AI.id  ='". $data['auctionitemdetails']."'";
	      }
    		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   
      }
      
      ################
      ##check items
      #########
      
      	public function checkItemExists($auctionitem='')
 	{
 		self::getConnection();
 	//	echo $bidritmid;echo $eventid;echo $userid;exit();
 	  	
 	   $qry   =  	  	"SELECT id
  	    	            FROM   watchlist
 	    	            WHERE  ((bidder_item_id = '".$auctionitem."' AND bidder_item_id != ''))";
 		//SELECT id FROM watchlist WHERE ((bidder_item_id = '1d3a' ) OR  (event_id = '435453') OR (user_id = 'das13' ))
 		self::$dbo->doQuery($qry); //exit;
	    	$value =   self::$dbo->getResultRow();//print_r($value);exit;
 		return $value;
 	}
      
###################
#######auctioniddetails
##########
       public function getAuctionsItemsDetails($data)
 
       {
     //  echo $data['auctionitem'];
//        		$currUserId = self ::checkItemExists( $data['auctionitem']);print_r($currUserId );//exit;
// 		if($currUserId > 0) 
// 		{//echo "dsadsa";
// 		      $text=array("error"=>'Already exist', "id"=>$currUserId);//print_r($text);exit;
// 		    return $text;
// 		} 

                self::getConnection();
	      {
	      $qry="select W.flag,(select amount from bids where auction_item_id=AI.id order by time desc limit 0,1 ) AS current_bid ,AIP.name,AI.id as auction_item_id,AIP.id as image_id,W.id as watchlist_id, AI.* ,MP.domain from merchant_portals as MP inner join auction_items as AI on AI.merchant_id=MP.merchant_id 
	      left join auction_item_photos as AIP on AIP.auction_item_id=AI.id left join watchlist as W on ( W.bidder_item_id=AI.id AND  W.user_id ='".$data['userid']."' ) where  AI.id ='". $data['auctionitem']."'";
	      }
    		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();//print_r($qry);exit;
		return $result;
   
      }


##################################################
###########################################
# Register Merchant with Mobile Users-  Multiple MErchants Register
########################################
#################################################
        public function AddMerchantUserRelation($merchant_id,$userid)
 
       {
		self::getConnection();
                // Loop Through All Mwerchants and check if already registered              
		//for ($i = 1; $i <= $data['totalmerchants']; $i++) 
			//{
			//$merchant_id = $data['merchant'.$i.'id'];
			$qry="SELECT * FROM `mobile_merchant_user` WHERE `merchant_id` ='".$merchant_id."'  and `user_id`='".$userid."' "; 
		        self::$dbo->doQuery($qry);
			$results = self::$dbo->getResultRow();
			
			if($results >0) 
				{

	    			 return 0;     
				}
			else
				{
                                
				  $qry    = " INSERT INTO     `mobile_merchant_user` 
		                           (`id`, `user_id`, `merchant_id`) 
		                         VALUES          (NULL, '".$userid."', '". $merchant_id."'); ";
		                         self::$dbo->doQuery($qry);
		                         $insId = self::$dbo->getInsertID();
                                         
				}

			//}
                 return 1;
       }



################################################
########################################
#GET ALL BIDS UNDER AN USER
############################################
      
	public function getBids($data)
 		 {

        	 self::getConnection();

     		 $qry="SELECT MMU.merchant_id,MU.email FROM  `mobile_merchant_user` AS MMU INNER JOIN  `mobile_users` AS MU ON MU.id = MMU.user_id Where MU.id='".$data['id']."'";
		 self::$dbo->doQuery($qry);
		 $result['data'] = self::$dbo->getResultSet();
	
        if (($result['data']) != "No result.")
                   {
		  
                    for ($i = 0; $i < count($result['data']); $i++) 
		    {
		  	  $query  = "SELECT  B.*  FROM `bids` AS B INNER JOIN `users` AS U ON U.id = B.`user_id` where U.`merchant_id`= '". $result['data'][$i] ['merchant_id']."' AND U.username ='".$result['data'][$i] ['email']."'" ;
		    	  self::$dbo->doQuery($query);
		    	  $results = self::$dbo->getResultSet();  //print_r($results);
      			  if($results != "No result.")
      			  {
				for($j=0; $j < count($results); $j++)
				 {
 		
 					$qry2=" Select OA.id as OnlineAuctionId,(select domain from `merchant_portals` where merchant_id= '".$result['data'][$i]['merchant_id']."') as Domain,AIP.id as ImageId,AI.title, TIMESTAMPDIFF(SECOND,NOW(),OA.end_date) AS RemainingTime,(select amount from bids where auction_item_id=AI.id order by time desc limit 0,1 ) 
 					AS current_bid from `auction_items` as AI inner join `bids` as B on B.auction_item_id=AI.id inner join online_auctions as 
 					OA on AI.online_auction_id=OA.id left join auction_item_photos as AIP on B.auction_item_id=AIP.auction_item_id where B.id='".$results[$j]['id']."' GROUP BY AI.id";
 	
 					self::$dbo->doQuery($qry2);
 					$resultsdb = self::$dbo->getResultSet();
 					$results1[] = $resultsdb[0];
 			 	}
			   }
 			  else
 			   {
 	
				$results1[] = "No Result.";
 			    }
		     //$resp[]=$results1;
		   	 
		   
                  	 
		    }//print_r($results1); exit();
	           return $results1;
                }

	    }


##############################################
############## Get All Merchant ID Under the email ID of Mobile User
####################################

        public function VerifyMerchantUser($data)
 		{
          	       self::getConnection(); 
                             $count_failure =0;
	  	       for ($i = 1; $i <= $data['totalmerchants']; $i++) 
			{
           		       $merchant_id = $data['merchant'.$i.'id'];   
	     		       $password1 = $data['merchant'.$i.'password'];   
	    		      
               			$password= $password1;
	        		$qry="SELECT merchant_id from users WHERE merchant_id = '".$merchant_id."' and password = '".$password."' "; 
	        		self::$dbo->doQuery($qry);
				$status = self::$dbo->getResultRow();
                		if($status == null)
                		  {
						$results[$i-1]['MerchantId'] = $merchant_id;
             					$results[$i-1]['Status'] = "Failure";
						$results[$i-1]['Merchant'] = "Not Added";
                                         $count_failure++;
               			  }
                                else
                                   {          
                                         $results[$i-1]['MerchantId'] = $merchant_id;
 					 $results[$i-1]['Status'] = "Success";
					  $added =$this->AddMerchantUserRelation($merchant_id,$data['userid']);
					 if($added == 1)
                                          {
					  $results[$i-1]['Status'] = "Failure";
                                          $results[$i-1]['Merchant'] = "Added";
                                          }
                                         else
						{
						 $results[$i-1]['Merchant'] = "Already Added";
 						
						}
					}
	 		}
                       if($count_failure == $data['totalmerchants'])
                        {
                               return 0;
                         }
         	     return $results;
		    
         	}

###################################
#################################################
###### Get Merchant Details and ID and Website
#############################################
##################################

        public function getMerchants($email)
 	       {
	         
		 self::getConnection();
	         $qry = "SELECT DISTINCT U.merchant_id,M.domain FROM users U INNER JOIN merchant_portals M ON U.merchant_id=M.merchant_id WHERE username='".$email."'";
	       	 self::$dbo->doQuery($qry);
		 $result = self::$dbo->getResultSet();
		 return $result;
  	
       		}

###################################
#################################################
###### Add Mobile Users
#############################################
##################################
	public function AddUser($dat)
	{
		
		self::getConnection();
		
		$req_fields = self::$required_fields;
    		array_push($req_fields,'password');
    		$val_field  = self::$validate_fields;
    		$val_field  = array_merge($val_field,array('email' => 'IsEmailUnique'));
		if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		   
		   $qry    = " INSERT INTO     `mobile_users` 
		                                (`id`, `first_name`, `last_name`, `email`, `password`) 
		                VALUES          (NULL, '{$dat['first_name']}', '{$dat['last_name']}', '{$dat['email']}', 
								 '{$dat['password']}'); ";
		self::$dbo->doQuery($qry);
		$insId = self::$dbo->getInsertID();
		$userEmail = $dat['email'];

			
			
		return $insId;
		}
        	return FALSE;
	}
	

############################################
###########	WeraiseIt Facebook USers Register
#############################
	
        public function AddFBUser($dat)
	{
		self::getConnection();
		$req_fields = self::$required_fields;
    		$val_field  = self::$validate_fields;
		if (Validate::ValidateFields($req_fields, self::$fields_size, $val_field, $dat))
		{
		  	$qry    = "INSERT INTO     `mobile_users` 
		                                (`id`, `first_name`, `last_name`, `email`,`fb_id`,`regd_id`,`app_type`,`channel`) 
		                VALUES          (NULL, '{$dat['first_name']}', '{$dat['last_name']}', '{$dat['email']}','{$dat['fb_id']}','".$dat['regd_id']."','".$dat['app_type']."','".$dat['channel']."') ";
		    	self::$dbo->doQuery($qry);
			$insId = self::$dbo->getInsertID();//print_r($qry);exit;

			$userEmail = $dat['email'];


		    	return $insId;
		}
        	return FALSE;
	}


################################
#################Function to update User
###############################
	
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
						`password`	= '{$dat['paswd']}',
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
	

################################
#################Function to update Facebook User
###############################


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



################################
#################Check if email being registering is Unique
###############################
	
	
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



################################
#################User Id From FB ID
###############################
	
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
	

####################################################
#########Get User Id from Email email
###############################################
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

###########
##validating for Fb users signin
####

// 	public function checkFbEmailExists($email='')
//  	{
//  		self::getConnection();
//  	//	echo $bidritmid;echo $eventid;echo $userid;exit();
//  	  	$qry   =   "SELECT fb_id
//  	    	            FROM   mobile_users
//  	    	            WHERE  ((email = '".$email."' AND email != ''))";
//  		//SELECT id FROM watchlist WHERE ((bidder_item_id = '1d3a' ) OR  (event_id = '435453') OR (user_id = 'das13' ))
//  		self::$dbo->doQuery($qry); //exit;
// 	    	$value =   self::$dbo->getResultRow();//print_r($value);exit;
//  		return $value;
//  	}
// 	
	
	
	


######################################
######function to authenticate user
#################################
	public function Authenticate($dat)
    	{
		global $_error;
		self::getConnection();
		if (!self::ValidateUserData($dat))
		{
		    return false;
		}
// 		$currUserId = $this->checkFbEmailExists( $dat['email']);
// 		if($currUserId > 0) 
// 		{
// 		      $text=array("error"=>'U have logged in through FB ', "id"=>$currUserId);//print_r($text);exit;
// 		    return $text;
// 		} 
// 	/*	else 
	//     $query="SELECT id FROM `mobile_users` WHERE fb_id !='' AND email='".$dat['email']."'";
	    //  self::$dbo->doQuery($query);print_r($query);exit;
	      
		//$pwd= md5($dat['password']);
		$pwd= $dat['password'];
		
		$qry    = " SELECT 	id, first_name, last_name
					FROM	mobile_users 
					WHERE	email = '{$dat['email']}'
							AND password ='".$pwd."' ";
                 
		self::$dbo->doQuery($qry);
		$rst = self::$dbo->getResultRow();//print_r($qry);exit;
		if($rst)
		{
		//echo $rst['id'];exit();
		$query="update mobile_users set regd_id='".$dat['regd_id']."',
		  app_type='".$dat['app_type']."' 
		  where id=".$rst['id']."";//print_r($query);exit;
		self::$dbo->doQuery($query);
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
    

###########################
	/* checks email availability before update */
##############################NOT USED PLZ UPDATE LATER
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
	
	{if ($email!='')
	      {
		self::getConnection();
	    	$qry   =   "SELECT id
	    	            FROM   mobile_users
	    	            WHERE  email='{$email}' AND password<>''";
		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->FetchValue();
		return $value;
		}
		else return 0;
	}
	
	public function updateUserTempPassword($userId, $tempPaswd, $userEmail)
	{
		self::getConnection();
		if(!Validate::isEmpty($tempPaswd))
		{
			$qry = "UPDATE mobile_users
				SET password = '".md5($tempPaswd)."'
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
	    	            WHERE  id='".$userId."' AND password='".$currPaswd."'";
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
				SET password = '".$newPaswd."'
				WHERE id = '".$userId."' AND password='".$currPaswd."'";
		
			self::$dbo->doQuery($qry);
			if(!empty($userEmail))
			{


				return TRUE;
			}				
		}
		return FALSE;
	}
###########################
#####################Integrate Users from Fb and Twitter

	
	public function AddUserSocial($dat)
	{
		
		self::getConnection();
		if ($dat['email']=='' && $dat['twitter_id']=='' && $dat['channel']!=='') return FALSE;
		$req_fields = self::$required_fields;
    		$val_field  = self::$validate_fields;
    		
		
		$currUserId = $this->checkTwitterUserExists($dat['channel'], $dat['email'], $dat['twitter_id']);
		if($currUserId > 0) {
		      $text=array("error"=>'Already exist', "id"=>$currUserId);
		    return $text;

		} 
		
	      else {
		   
		     $qry 	= " INSERT INTO mobile_users 
				  (username, first_name, last_name, email, channel,twitter_id,regd_id,app_type) VALUES
				  ('".$dat['username']."', '".$dat['first_name']."', '".$dat['last_name']."', '".$dat['email']."', '".$dat['channel']."', '".$dat['twitter_id']."', '".$dat['regd_id']."','".$dat['app_type']."')";

		    self::$dbo->doQuery($qry);
		    $insId = self::$dbo->getInsertID();//print_r($qry);exit;
		$text=array("error"=>'', "id"=>$insId);
		    return $text;
		}
		
        	return FALSE;
	}

#################################
	#function to get userid with email
#####################################
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



#function to get userid for Twitter 

 public function checkTwitterUserExists($channel, $email='', $twitter_id='')
 	{
 		self::getConnection();
 		
 	    	$qry   =   "SELECT `id`
 	    	            FROM   mobile_users
 	    	            WHERE  ((twitter_id = '".$twitter_id."' AND twitter_id != '') OR (email = '".$email."' AND email != '')) AND channel = '".$channel."'";
 		self::$dbo->doQuery($qry);
 	    	$value =   self::$dbo->FetchValue();
 		return $value;
 	}
	
###############################
############Order  For a Donation
#################################
   	public function MakeDonation($data)
   	{
   	self::getConnection();
   	$qry1="SELECT merchant_id,user_id from events where id='".$data['eventid']."'";
   	self::$dbo->doQuery($qry1);
	$result = self::$dbo->getResultSet();
	
	//if($result != 'No result.') {
   	$qry="insert into orders (transaction_id,event_id,amount,payment_method_id,merchant_id,user_id)
   	values ('".$data['tid']."','".$data['eventid']."','".$data['amt']."','".$data['method']."','".$result[0]['merchant_id']."','".$result[0]['user_id']."')";//print_r($qry);exit;
   	self::$dbo->doQuery($qry);//exit;
   	 $insId = self::$dbo->getInsertID();//print_r($insId
   	 // }
   	// if($insId) {
   	$qry2="insert into donations (order_id,event_id,user_id,amount,recurring,donation_type_id)
   	values ('".$data['orderid']."','".$data['eventid']."','".$result[0]['user_id']."','".$data['amt']."','".$data['rec']."','".$data['type']."')";
			  self::$dbo->doQuery($qry2);
		return	 $insId1 = self::$dbo->getInsertID();
		//}
   	}



###############added of Sprint II
#######################################Bid Now
#########################

         public function updateItemBid($data)


         {
		self::getConnection();


 $qry="select `first_name`,`id`,`username`,`last_name`,`email`,
	      `mobile`,`country`,`billing_address`,`billing_state`,`billing_city`,`billing_zip`
		  from mobile_users where id ='".$data['user_id']."' ";
      	
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultRow();
      	
		//print_r($result); exit();
                 if($result['first_name'] != null && $result['last_name'] != null && $result['billing_state'] != null && $result['billing_zip'] != null && $result['billing_city'] != null && $result['country'] != null)
                {
             
		$qry    = "INSERT INTO     `bids` 
		                                ( `auction_item_id`, `user_id`, `payment_method_id`,`amount`,`paid`,`order_id`,`time`) 
		                VALUES          ('".$data['item_id']."', '".$data['user_id']."', '".$data['method']."','".$data['amount']."',0,NULL,NOW()); ";
		    	self::$dbo->doQuery($qry);
		    	 if ($insId > 0)
		    	 {
		    	 $this->pushNotification($data);
		    	 }
		    
			return $insId = self::$dbo->getInsertID();
		}
                 else
			{
			return 2;
			}
	

	 }

#####
#########function to make donation payment
 
  	public function MakePayment($data)
   
	{
   	
	self::getConnection();
   
		$qry = "INSERT INTO 
auction_bidder_payments(event_id,auction_bidder_id,wcc,payment_type,card_type,last_digits,amount,settled_flag,settled_code,status,date_assigned,time_assigned,payment_detail,account_name,account_number) 
VALUES
('".$data['event_id']."','".$data['auction_bidder_id']."','".$data['wcc']."','".$data['payment_type']."','".$data['card_type']."','".$data['last_digits']."','".$data['amount']."','".$data['settled_flag']."','".$data['settled_code']."','".$data['status']."','".$data['date_assigned']."','".$data['time_assigned']."','".$data['payment_detail']."','".$data['account_name']."','".$data['account_number']."');";
   
		
   		self::$dbo->doQuery($qry);
   
		$paymentId = self::$dbo->getInsertID();
	   
 	return $paymentId;
   		
 
  	}
   	
   

#######################
##########################
	public function DonateAmount($data,$order_id)
  
 	{
   		
self::getConnection();
   	
	$qry = "INSERT INTO donations(event_id,user_id,order_id,amount,recurring) VALUES('".$data['event_id']."','13','".$order_id."','".$data['amount']."','NO');";
   
		self::$dbo->doQuery($qry);
   	
	$donationId = self::$dbo->getInsertID();
   
		//echo $donationId;
	    
	return $donationId;
   
	}
   

	public function UpdateProfile($data)
  
 	{
   	
	self::getConnection();
   
		$qry = "UPDATE mobile_users SET first_name='".$data['first_name']."', last_name='".$data['last_name']."', mobile='".$data['mobile']."', email='".$data['email']."', billing_address='".$data['billing_address']."', billing_city='".$data['billing_city']."', billing_state='".$data['billing_state']."', billing_zip='".$data['billing_zip']."',country='".$data['country']."' WHERE id = '".$data['user_id']."'";
   	
	self::$dbo->doQuery($qry);
   	$result[]=self::$dbo->getResultNumRows();
   
		if($result > 0)
	
		    {
				
		return TRUE;
			   
 		} 
   		
		//echo $userId;
   
	}





#################################
	#function to get Donation Types for an event
#####################################
	public function getDonationTypes($data)
	{
		self::getConnection();
		
	    	$qry   =   "SELECT `id`,`description`
	    	            FROM   auction_donations_types
	    	            WHERE  event_id = '".$data['eventid']."'";
		self::$dbo->doQuery($qry);
	    	  $results = self::$dbo->getResultSet();
		return $results;
	}
#################################
	#function to get Donation Types for an event
#####################################
	public function getMidTid($data)
	{
		self::getConnection();
		
	    	$qry   =   "SELECT `mid`,`tid`,tcid,gatewayid,processor
	    	            FROM   merchants
	    	            WHERE  id = '".$data['merchantid']."'";
		self::$dbo->doQuery($qry);
	    	  $results = self::$dbo->getResultRow();
		return $results;
	}
	
########################
#User info
########################

     	    public function InfoMobileUser($data)
 
       {

         self::getConnection();
     
       $qry="select `first_name`,`id`,`username`,`last_name`,`email`,
	      `mobile`,`country`,`billing_address`,`billing_state`,`billing_city`,`billing_zip`
		  from mobile_users where id ='".$data['id']."' ";
      	
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		return $result;
   
   
	}
	
	

#####################
    #Function to add Credit card user
####################
	public function AddCardUser($dat)
	{
		
		self::getConnection();
		
		$req_fields = self::$required_fields;
    		$val_field  = self::$validate_fields;
		if ($dat['cardtype'] !==''&& $dat['cc_number'] !=''&& $dat['expiration_date']!=='' && $dat['mobile_user_id']!=='' )
		{
		  	$currUserId = $this->checkCardUserExists($dat['cc_number']);  
		if($currUserId > 0) 
		{
		      $text=array("error"=>'Already exist', "id"=>$currUserId);//print_r($text);exit;
		    return $text;
		} 
		
	    else {


                  if($dat['default'] == '1')
			{


                        $qry="Update payment_methods set defaultcard='0'
 			 where mobile_user_id=".$dat['mobile_user_id']."";//echo $qry ;exit;
  	                $result=self::$dbo->updateQuery($qry);
                         

			
			}
                       
		  $qry= "INSERT INTO     `payment_methods`  (`cardtype`, `cc_number`, `expiration_date`,`mobile_user_id`, `defaultcard`)  
		  VALUES  ('{$dat['cardtype']}','{$dat['cc_number']}','{$dat['expiration_date']}','{$dat['mobile_user_id']}','{$dat['default']}')";

		self::$dbo->doQuery($qry);
		$insId = self::$dbo->getInsertID();//print_r($insId);exit;
			//$userEmail = $dat['email'];

			
			
			return $insId;
		
        	return FALSE;
        	}
		}
	}
	
	 	public function checkCardUserExists($cc_number='')
 	{
 		self::getConnection();
 	  	$qry   =   "SELECT id
 	    	            FROM   payment_methods
 	    	            WHERE  ((cc_number = '".$cc_number."' AND cc_number != '') )";
 		self::$dbo->doQuery($qry);
	    	$value =   self::$dbo->getResultRow();
 		return $value;
 		
 	}
	
#####################
    #Function to update Credit card user
###################

	public function UpdateCardUser($dat)
 	{
 	
 	self::getConnection();
  	    	global $_error;
	    	$req_fields = self::$required_fields;
  		$val_field  = self::$validate_fields;
 		
		if ($dat['cardtype'] !==''&& $dat['cc_number'] !=''&& $dat['expiration_date']!=='')
		{

                  if($dat['default'] == '1')
			{


                       $qryD="Update payment_methods set defaultcard='0'
 			 where mobile_user_id=".$dat['user_id']."";//echo $qry ;exit;
  	                $result=self::$dbo->updateQuery($qryD);
                         

			
			}
 		$qry="Update payment_methods set cardtype='".$dat['cardtype']."',
 			cc_number=".$dat['cc_number'].",defaultcard=".$dat['default'].",
 			expiration_date= '".$dat['expiration_date']."'
 							  where id=".$dat['card_id']."";//echo $qry ;exit;
  	
 			
 		$result=self::$dbo->updateQuery($qry);
 	
 	
		  if ($result==1)
		  { 
		  return $dat['card_id'];
		  }
		  else return FALSE;
 		 
	      } 
	}
	
#####################
    #Function to Delete Credit card user
###################

 	    	public function DeleteCardUser($data)
    	{
		self::getConnection();
		
	 	 $qry = "DELETE FROM payment_methods WHERE id= ".$data['card_id']." ";
		$status=self::$dbo->updateQuery($qry);
		if ($status==1)
        	return $data['card_id'];
        	else 
        	return false;
        	
    	}
    	
#####################
    #Function to List Credit card user
###################

	    public function ListCardUser($data)
 
       {

         self::getConnection();
     
       $qry="select id,cardtype,cc_number,expiration_date,defaultcard FROM payment_methods where mobile_user_id='".$data['id']."' ";
      	
		self::$dbo->doQuery($qry);
		$result[] = self::$dbo->getResultSet();//echo "<pre>";print_r($result);exit; 
		return $result;
   
   
      }
      ###############################
	#check watchlist user Exists
	###############
	
 	public function checkWatchlistExists($userid='',$bidritmid='')
 	{
 		self::getConnection();
 	  	$qry   =   "SELECT id,flag
 	    	            FROM   watchlist
 	    	            WHERE  (user_id = '".$userid."') AND (bidder_item_id='".$bidritmid."')";
		self::$dbo->doQuery($qry); //exit;
	    	$value =   self::$dbo->getResultRow();

	    	if(count($value) < 1)
	    	{
		  $value['flag'] =2;
	    	//return $value;
	    	}
	    //	print_r($value);exit();
 		return $value;
 	}
 	#############################
 	##fUNCTION TO ADD Watchlist User
 	#############################
 	
	  public function AddWatchlistUser($dat)
	{
		
		self::getConnection();
		if ($dat['bidritmid']==''  && $dat['userid']=='') return FALSE;
		$req_fields = self::$required_fields;
    		$val_field  = self::$validate_fields;
		
		$currUserId = $this->checkWatchlistExists( $dat['userid'],$dat['bidritmid']);//print_r($currUserId);exit;
		
		//print_r($currUserId);exit;
		
		if($currUserId['flag'] == 1) 
		{
		      $text=array("error"=>'Already exist', "id"=>$currUserId);//print_r($text);exit;
		    return $text;
		} 		
		else 
		{
	   
		  if($currUserId['flag']== 2)
		  {
		    //  echo 'inif';exit;  
		    $qry= "INSERT INTO     `watchlist`  (`bidder_item_id`,  `user_id`)  
			    VALUES  ('".$dat['bidritmid']."','".$dat['userid']."')"; 
		    self::$dbo->doQuery($qry);
		    $insId = self::$dbo->getInsertID();
			return $insId;
	      
		  }	      
		   else
		  {
		  
		       $qry = "update watchlist set flag = 1 where `bidder_item_id`='".$dat['bidritmid']."' AND `user_id`='".$dat['userid']."'";
		  //  echo 'else';exit;
		    self::$dbo->doQuery($qry);
		      $status=self::$dbo->updateQuery($qry);//echo $status;exit();
			if ($status==1)
			{
			return $status;
			}
			else
			{
			return FALSE;
			 }
	      
		  }
		  return FALSE;
        	
		}
		
	}
	##################
	##### delete watchlist users from Watchlist
	###################
	    	public function DeleteWatchlistUser($data)
    	{
		self::getConnection();
		if($data['bidderitemid']=='' || $data['userid']=='')return False;
	
		$query="select  bidder_item_id,user_id from `watchlist` where bidder_item_id='".$data['bidderitemid']."' AND user_id= '".$data['userid']."'";
		self::$dbo->doQuery($query);
		$result = self::$dbo->getResultSet();
		if($result =="No result.") return False;
		
	 	 $qry = "update watchlist set flag = 0 where `bidder_item_id`='".$data['bidderitemid']."' AND `user_id`='".$data['userid']."'";

 		$status=self::$dbo->updateQuery($qry);//echo $status;exit();
 		if ($status==1)
         	return true;
         	else 
          	return False;
        } 

	##################
	##### List watchlist users from Watchlist
	###################
         
    	    public function listwatchlistuser($data)
 
       {

		self::getConnection();
     
		$qry="select AIP.id as image_id,AIP.name,MP.domain,W.bidder_item_id,AI.title,TIMESTAMPDIFF(SECOND,NOW(),OA.end_date) AS RemainingTime,
		(select amount from bids where auction_item_id=AI.id order by time desc limit 0,1 ) as current_bid from watchlist as W inner join auction_items as AI on AI.id=W.bidder_item_id 
		inner join online_auctions as OA on AI.online_auction_id =OA.id left join auction_item_photos as AIP on AI.id=AIP.auction_item_id inner join merchant_portals as MP on MP.merchant_id =OA.merchant_id 
		where W.user_id ='".$data['id']."' AND W.flag='".$data['flag']."' group by AIP.auction_item_id  ";
      	
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();//print_r($result);exit;
		return $result;
		
		return False;
   
   
	}
      ########################################
         ########push notification For Android and Iphone Users
         #############################
    	  public function pushNotification($data)
   
	{
		self::getConnection();
		$qry="SELECT user_id FROM `watchlist` where flag='1' AND bidder_item_id='".$data['item_id']."' ";
		self::$dbo->doQuery($qry);
		$result = self::$dbo->getResultSet();
		
		//echo count($result); exit;
		if ($result !="No result.")
		 {
		 for ($i=0;$i< count($result);$i++)
		 {
		$query="select regd_id,app_type from mobile_users where id=".$result[$i]['user_id']."";//print_r($query);exit;
		self::$dbo->doQuery($query);//print_r($query);exit;
		$results = self::$dbo->getResultRow();
		//echo 	count($results);exit;
	        if($results['app_type'] == 'Android')
	        {
	        $chk=$this->sendNotificationsAndroid($results['regd_id']);
	        
	        }
	        else
	        {
		$chk=$this->sendNotificationsIOS($results['regd_id']);
		}
		if($i==(count($result)-1))
		{
		return "Push Notification Sent";
		}
		
		//	echo $chk; exit();
		}
		return "Push Notifications Not sent";
    
		 }
	}
	
	########################
	##############Function to Send Notification FOr Android
	########################
	
	public function sendNotificationsAndroid($data)
	{
		//  $registrationIds = array( $_GET['regd_id'] );
		  $message= "Welcome To WRI ";
		//  $regdid=
		  $message = array
		  (
		    'message' => $message 
		);
		$gcm_array[]=$data;// echo "<pre>" ;print_r($gcm_array);exit;
		  // Set POST variables
		  $url = 'https://android.googleapis.com/gcm/send';

		   $fields = array
		      (
		   'registration_ids' => $gcm_array,
		   'data' => $message,
		      );
		    $headers = array
		    (
		    'Authorization: key=AIzaSyCXNSoR_7G1XWHUwDNTFi-mWmwte8XX0CA',
		    'Content-Type: application/json'
		    );

		      // 	print_r($message);exit;
		      // Open connection
		      $ch = curl_init();
 
		    // Set the url, number of POST vars, POST data
		    curl_setopt($ch, CURLOPT_URL, $url);
 
		    curl_setopt($ch, CURLOPT_POST, true);
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
		    // Disabling SSL Certificate support temporarly
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
		     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
		    // Execute post
		    $result = curl_exec($ch);
		    if ($result === FALSE) 
		    {
		    die('Curl failed: ' . curl_error($ch));
		    }
 
		      // Close connection
		      curl_close($ch);//print_r($result);exit;
		      echo $result;
	}
	
	##############################
	####Function to Send Notification FOr IOS
	##############################
	
   	public function sendNotificationsIOS($data)
   	
   	{
   	
		// Put your device token here (without spaces):
		//$deviceToken = 'fb5e1b4bdae898a5618551a30ed4e2eb0ea12c2f1207665e6d798f0e429ae3d9';
		$deviceToken=$data;
		  // Put your private key's passphrase here:
		 $passphrase = '';

		// Put your alert message here:
		  $message = 'Hello Welcome to WeRaiseIt';

		  ////////////////////////////////////////////////////////////////////////////////

		  $ctx = stream_context_create();
		  stream_context_set_option($ctx, 'ssl', 'local_cert', 'WeRaiseItCertificate.pem');
		  stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		  // Open a connection to the APNS server
		  $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		  if (!$fp)
		  exit("Failed to connect: $err $errstr" . PHP_EOL);

		  'Connected to APNS' . PHP_EOL;

		  // Create the payload body
		  $body['aps'] = array
		    (
		  'alert' => array(
		  'body' => $message,
		  'action-loc-key' => 'Bango App',
		    ),
		    'badge' => 1,
		    'sound' => 'oven.caf',
		    );

		    // Encode the payload as JSON
		    $payload = json_encode($body);

		      // Build the binary notification
		    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		      // Send it to the server
		      $result = fwrite($fp, $msg, strlen($msg));

		      if (!$result)
		      return 'Message not delivered' . PHP_EOL;
		      else
		      return  'Message successfully delivered' . PHP_EOL;

		      // Close the connection to the server
			fclose($fp);
   	
   	
	}
	
	

	
	
}

?>
