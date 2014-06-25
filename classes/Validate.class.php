<?php

class Validate
{

	
	
    /**
     * Check for fields validity before database interaction
     */
    public static function ValidateFields($fieldsRequired, $fieldsSize, $fieldsValidate, $dat, $die = true)
    {
    	global $_error;

	$error = 0;
        foreach ($fieldsRequired as $field)
        {
            if (self::isEmpty($dat[$field]) AND (!is_numeric($dat[$field])))
            {
            	$field_name    =   Core::FormatFieldName($field);
                $_error->AddError($field_name . ' is empty.');
		$error = 1;
                //return false;
            }
        }
        foreach ($fieldsSize as $field => $size)
        {
            if (isset($dat[$field]) AND self::strlen($this->{$field}) > $size)
            {
                /*
                if ($die) die (Tools::displayError().' ('.get_class($this).' -> '.$field.' length > '.$size.')');
                */
		$_error->AddError("Maximum size of ".$field_name." is ".$size);
                //return false;
		$error = 1;
            }
        }

        foreach ($fieldsValidate as $field => $method)
        {
            if (self::isEmpty($dat[$field]))
            {
		$error = 1;
               // return FALSE;
            }
            elseif (function_exists($method))
            {
                if(!$method($dat[$field]))
                {
                	$field_name    =   Core::FormatFieldName($field);
                	$_error->AddError('Provide Valid '.$field_name);
			$error = 1;
                    	//return FALSE;
                }
            }
            else
            {
                if (!self::$method($dat[$field]))
                {
			$error = 1;
                    //return FALSE;
                }
            }
        }
/*
            if (!method_exists($validate, $method))
                die (Tools::displayError('validation function not found').' '.$method);
            elseif (!Tools::isEmpty($this->{$field}) AND !call_user_func(array('Validate', $method), $this->{$field}))
            {
                if ($die) die (Tools::displayError().' ('.get_class($this).' -> '.$field.' = '.$this->{$field}.')');
                return false;
            }
*/	if($error == 1)
		return false;

        return true;
    }

    public static function ValidateUserData($dat)
    {
    	global $_error;
        if (self::isUserName($dat['username']) && self::isPasswd($dat['password']))
        {
            return true;
        }
        return false;

    }
    
    public static function isEmpty($field)
    {
        return $field === '' OR $field === NULL;
    }
    
    public static function strlen($str)
    {
    	global $_error;
        if (is_array($str))
            return false;
        if (function_exists('mb_strlen'))
            return mb_strlen($str, 'utf-8');
        return strlen($str);
    }
    
    public static function NotEmpty($field)
    {
    	if ('' == $field || 0 == $field)
    	{
    		return FALSE;
    	}
    	return true;
    }
    
	public static function isValidURL($url)
	{
		global $_error;
	   if(preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url))
	   {
            return true;
        }
        else
        {
            $_error->AddError('Provide Valid Url.');
            return false;
        }
	}

	public static function isName($name, $size = 4)
	{
	    return true;
	}

    public static function isPasswd($passwd, $size = 6)
	{
		//return preg_match('/^[.a-z_0-9-]{'.$size.',32}$/ui', $passwd);
		global $_error;
		if(preg_match('/^(?=^.{8,}$)((?=.*[0-9])(?=.*[A-Za-z]))^.*$/',$passwd))
		{
			return true;
		}
		else
		{
			$_error->AddError('Password must be at least 8 characters and contain at least one letter and one number.');
			return false;
		}
	}
	
    public static function checkPasswdLength($passwd,$size=8)
    {
    	global $_error;
        if(strlen($passwd) >= $size)
        {
            return true;
        }
        else
        {
        	$_error->AddError('Password must be atleast 8 characters.');
            return false;
        }
    }
    
    public static function isEmail($email)
    {
    	global $_error;
        if(preg_match('/^[a-z0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z0-9]+[._a-z0-9-]*\.[a-z0-9]+$/ui', $email))
        {
        	return true;
        }
        else
        {
        	$_error->AddError('Provide Valid Email.');
        	return false;
        }
    }
    
    public static function IsEmailUnique($email)
    {
    	 global $_error;
    	 if(self::IsEmail($email))
    	 {	
		$User = new User();	
	    	if($User->CheckEmailUnique($email))
	        {
	            return true;
	        }
	        else
	        {
	            $_error->AddError('Email Already Exists.');
	            return false;
	        }
    	 }
    }
}


?>
