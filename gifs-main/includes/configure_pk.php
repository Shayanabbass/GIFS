<?php
date_default_timezone_set("Asia/Baghdad");

if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1')
{
	define('DB_NAME','gifs');
	define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
	define('DB_SERVER_USERNAME', 'root');
	define('DB_SERVER_PASSWORD', 'root');
	define('BASE_URL', 'http://localhost/gifs/');
}
else
{
	define('DB_NAME','schoolgr_db');	//thebaron_web19
	define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
	define('DB_SERVER_USERNAME', 'schoolgr_db');
	define('DB_SERVER_PASSWORD', 'eEIONFDCEV8M');
	define('BASE_URL', 'https://school.greenislandtrust.org/');
}
//Email SMTP
define("EMAIL_LOGIN_ID", "no-reply@school.greenislandtrust.org");
define("EMAIL_PASSWORD", "7R$2KV+y;r?W");
define("SITE_ADMIN_EMAIL", "hasnainwasaya@gmail.com, secretary@greenislandtrust.org");
define("SITE_ADMISSION_EMAIL", "hasnainwasaya@gmail.com");
define("DIR", "uploaded");
define("LINES_PER_SESSION", 500);

//Template
define("TEMPLATE_DIR", "templates/template2/");
define("TEMPLATE", TEMPLATE_DIR."index.php");
define("TEMPLATE_LOGIN", TEMPLATE_DIR."login.php");
define("SHOW_LOGIN", false);
define("TEMPLATE_SITE", TEMPLATE_DIR."forexschool/index.php");
//NOT_LOGGED_IN
define("NOT_LOGGED_IN", "loginfirst.php");
define("DATEFORMAT", 'd/m/Y');
$dont_show_forms = array(
);
//FB Application
define('FB_APP_ID', '2055492258033781');
define('FB_APP_SECRET', '8d102a566c5b6d7836039ed76127a69c');
//Google Recaptcha
define('reCAPTCHA_site_key', '6Ld2GYwUAAAAADIilWJ6IHcZb8-73Xo0JCruCvxb');
define('G_APP_SECRET', '6Ld2GYwUAAAAAHpuHijTpV7u7r9IbxxH4jFU2J4S');
//define("DEBUG", "1");
if($_SERVER["SERVER_ADDR"] == "127.0.0.1" ||  $_SERVER["SERVER_ADDR"] ==  "::1")
{
	define("DEBUG", false);
	//define("DEBUG", "1");
}
else
	define("DEBUG", false);
define("COMPANY_NAME", 'The Baron Hotels - Karbala');
define("DEFAULT_CURRENCY_ID", '2');
define('ARAFA_RANGE', '02/10/2020___13/10/2020');
define('ARBAEEN_RANGE', '02/10/2020___13/10/2020');
define('LMS_URL', 'https://lms.greenislandtrust.org/gifs/');
define('BLOG_URL', 'activity.php');
?>
