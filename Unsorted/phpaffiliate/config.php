<?PHP 

$domain				= "www.yourdomain.com"; // Your domain name (include www. if used BUT NOT http://) 
$server         	= "localhost"; // Your MySQL server address (usually 'localhost') 
$db_user        	= "username"; // Your MySQL database username 
$db_pass        	= "password"; // Your MySQL database password 
$database       	= "database"; // Your MySQL database name 
$currency			= "UK Pounds"; // The currency that your affiliates will be paid in
$emailinfo          = "test@email.com"; // Your email address
$yoursitename		= "Your Site Name"; // Your sites name
 


 
$clientdate         = (date ("Y-m-d")); // Do Not Touch 
$clienttime			= (date ("H:i:s")); // Do Not Touch 
$clientbrowser		= getenv("HTTP_USER_AGENT"); // Do Not Touch 
$clientip			= getenv("HTTP_CLIENT_IP"); // Do Not Touch 
$clienturl			= getenv("HTTP_REFERER"); // Do Not Touch 

  
?> 