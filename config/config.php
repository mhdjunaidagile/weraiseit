<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(E_ALL);
//@ini_set('display_errors', 'on');

//enable mail.chang email addr.
//enable reset paswd -> random
//change pdf



define('DOC_ROOT', '../weraiseit/');

define('SITE_URL', 'http://www.agileblaze.com/dev/weraiseit/weraiseit');
define('ADMIN_URL', SITE_URL.'admin/');


// Database Constants
define("DB_HOST", "localhost");
define("DB_USER", "agility_weraise");
define("DB_PASS", "dwox~ckBcCT5");
define("DB_NAME", "agility_weraiseit");


define("LIMIT", "30");





/* Autoload */
function __autoload($className)
{
	//echo dirname(__FILE__).'/../classes/'.$className.'.class.php';
    if (!class_exists($className, false))
        include_once(DOC_ROOT.'classes/'.$className.'.class.php');
}

$_error    = new Error();

//$test=memcache_connect('127.0.0.1', 11211);
//$memcache = new Memcache;
//$cacheAvailable = $memcache->connect('localhost', 11211);

?>
