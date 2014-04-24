<?php


class Core
{

	public static $dbo;
	public static function connection()
	{
	    self::$dbo = Database::getInstance();
	    self::$dbo->connect(); 
	}

    /*
     * Converts a query result set to JSON
     */
    public static function ResultsetToJSON($dat)
    {
        return json_encode($dat);
    }

    public static function Encrypt($str)
    {
        return md5($str);
    }

    /* function to make first_name as First Name */
    public static function FormatFieldName($fieldname)
    {
        $name          =   str_replace('_',' ',$fieldname);
        $name          =   ucwords($name);
        return $name;
    } 

    /**
		 * function returns cleansed values 
		 * to prevent SQL Injection
		 * can accept a single value, an array, array of array, array of array of array, ..........
    */
    public static function SanitizeData($data)
    {
		self::connection();
		if (is_array($data))
		{
			foreach ($data as $k=>$d)
			{
				if (is_array($d))
				{
					$s[$k]	= self::SanitizeData($d);
				}
				else
				{
					$s[$k]  = self::$dbo->escapeString(trim($d));
				}
			}
			return $s;
		}
		else
		{
			return self::$dbo->escapeString(trim($data));
		}
    }

}

?>
