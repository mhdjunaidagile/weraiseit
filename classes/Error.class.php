<?php
class Error
{    
    public function AddError($str)
    {
	if (!empty($str))
        {
        	if(!isset($_SESSION["error"]))
        		$_SESSION["error"]    = array();
        	array_push($_SESSION["error"], $str);
        }
    }

    public function ResetError()
    {
        $_SESSION["error"]    = array();
    }
}
?>
