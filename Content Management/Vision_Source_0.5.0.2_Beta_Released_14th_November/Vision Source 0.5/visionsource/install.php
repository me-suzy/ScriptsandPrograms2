<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: install.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/
error_reporting(E_ERROR | E_PARSE | E_WARNING);

//set up so direct file access is disabled.
define ( 'DIRECT', 1 );

if (@file_exists('install.lock')) {

	echo "Im sorry, install.lock still exists on the server so you can not install Vision Source.";
	exit;
}

if (isset($_GET['new'])) {

	if ($_GET['new'] == 1)
	{
	
	 $dbhost		= $_POST['dbhost']; 
 	 $dbuser		= $_POST['dbuser']; 
 	 $dbpass		= $_POST['dbpass']; 
 	 $dbname		= $_POST['dbname'];
	 $url			= $_POST['url'];
	 $adminemail	= $_POST['adminemail'];
	 $title			= $_POST['title'];
	 $username		= $_POST['username'];
	 $password		= $_POST['password'];
	 $email			= $_POST['email'];
	 
	 	if (empty($dbhost) || empty($dbuser) || empty($dbpass) || empty($dbname) || empty($url) || empty($adminemail) || empty($title) || empty($username) || empty($email) || empty($password))
		{
			echo "You have not filled in the details correctly";
			exit;
		}
		
		if ($password !== $_POST['password2'])
		{
			echo "Your password did not match.";
			exit();
		}
		
		if (empty($_POST['prefix']))
		{
			$prefix = "vsource";
		}
		
		else
		{
			$pefix = $_POST['prefix'];
		}
		
	 
	 $writefile = '<?php 
/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: config.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( "DIRECT" ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

 $info["dbhost"]		= "'.$dbhost.'"; 
 $info["dbuser"]		= "'.$dbuser.'"; 
 $info["dbpass"]		= "'.$dbpass.'"; 
 $info["dbname"]		= "'.$dbname.'"; 
 $info["prefix"]		= "'.$prefix.'"; 
 $info["title"]			= "'.$title.'";
 $info["base_url"]		= "'.$url.'"; 
 $info["email"] 		= "'.$adminemail.'";
 $info["news_limit"] 	= "10"
  
?>';
   
   $fileopen		= @fopen('includes/config.php','w');
   	if (!$fileopen) {
		echo "Can't write to file. Please check permisions.";
		exit;
	}
	
	else {
		@fwrite($fileopen,$writefile);
		@fclose($fileopen);
	}
	
	require_once ("includes/config.php");
	require_once ("classes/class_db.php");
	require_once ("includes/error_handler.php");
	$db 	= new db;
	$error  = new error_handler;
	$db->connect();

echo <<<EOT
<html>
<head>
<title>Installing Vision Source</title>
</head>
<body>
Starting installation....Please wait. <p>

EOT;

		$sql = 'CREATE TABLE `'.$prefix.'_links` (
  		`name` varchar(200) NOT NULL default "",
  		`link` varchar(255) NOT NULL default "",
  		`catid` tinyint(6) NOT NULL default "0",
		`id` tinyint(6) NOT NULL auto_increment,
  		`hits` tinyint(10) NOT NULL default "0",
 		 PRIMARY KEY  (`id`)
		) TYPE=MyISAM AUTO_INCREMENT=2;';

		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_links created, moving onto next table.<br />';
 		} else {
    		 echo '<p>Im sorry, there was an error: <br />' .
        		 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'INSERT INTO `'.$prefix.'_links` VALUES ("Vision Source", "http://www.visionsource.org", 1, 1, 0);';
		
		if (@mysql_query($sql)) {
   		  echo 'Link created.<br />';
 		} else {
    		 echo '<p>Im sorry, there was an error: <br />' .
        		 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'CREATE TABLE `'.$prefix.'_comments` (
  				`id` int(11) NOT NULL auto_increment,
  				`name` varchar(100) NOT NULL default "",
  				`email` varchar(100) NOT NULL default "",
  				`comment` mediumtext NOT NULL,
  				`newsid` smallint(11) NOT NULL default "0",
  				`is_guest` tinyint(1) NOT NULL default "0",
  				`mid` tinyint(11) NOT NULL default "0",
  				PRIMARY KEY  (`id`)
  				) TYPE=MyISAM';
				
		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_comments created, moving onto next table.<br />';
 		} else {
    		 echo '<p>Im sorry, there was an error: <br />' .
        		 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'CREATE TABLE `'.$prefix.'_custom` (
			  `id` tinyint(6) NOT NULL auto_increment,
			  `pageid` varchar(50) NOT NULL default "",
			  `html` text NOT NULL,
			  `title` varchar(50) NOT NULL default "",
			  `view` tinyint(1) NOT NULL default "0",
			  `mem_only` tinyint(1) NOT NULL default "0",
			  PRIMARY KEY  (`id`)
			  ) TYPE=MyISAM';
			  
		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_custom created, moving onto next table.<br />';
 		} else {
    		 echo '<p>Im sorry, there was an error: <br />' .
        		 mysql_error() . '</p></body></html>';
		exit;
    	}
   
   		$sql = 'CREATE TABLE `'.$prefix.'_links_cat` (
  		`id` tinyint(6) NOT NULL auto_increment,
  		`cat` varchar(150) NOT NULL default "",
  		`about` mediumtext NOT NULL,
 		 PRIMARY KEY  (`id`)
		) TYPE=MyISAM AUTO_INCREMENT=2;';
		
		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_links_cat created, moving onto next table.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'INSERT INTO `'.$prefix.'_links_cat` VALUES (1, "Vision Source", "A CMS system that this website is running");';
		
		if (@mysql_query($sql)) {
   		  echo 'Links catagory created.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		
   		$sql = 'CREATE TABLE `'.$prefix.'_about` (
 		 `startdate` smallint(8) NOT NULL default "0",
 		 `content` longtext NOT NULL
		) TYPE=MyISAM;';
		
		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_about created, moving onto next table.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'INSERT INTO `'.$prefix.'_about` VALUES ("'.date("l dS \of F Y") .'", "Welcome to the About Us module. Here you can put information about your website.");';
		
		if (@mysql_query($sql)) {
   		  echo 'About text created.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
   		$sql = 'CREATE TABLE `'.$prefix.'_news` (
  		`id` smallint(6) NOT NULL auto_increment,
  		`newstitle` tinytext NOT NULL,
  		`newstext` longtext NOT NULL,
  		 `poster` varchar(50) NOT NULL default "",
  		`thedate` varchar(40) NOT NULL default "",
  		PRIMARY KEY  (`id`)
		) TYPE=MyISAM AUTO_INCREMENT=2;';
		
		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_news created, moving onto next table.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'INSERT INTO `'.$prefix.'_news` VALUES (1, "Welcome to Vision Source", "Welcome to the Vision Source CMS. <p>Here is where all the news is posted. <br />
		You can go to the Admin Control Panel by <a href=\''.$url.'/admin.php\' target=\'_blank\'>clicking here</a>. </p>
		<p>
		If you need help with Vision Source please visit us by going to <a href=\'http://www.visionsource.org\'>www.visionsource.org</a> where you can find all
		the help you need, as well as mods, skin plus much more.
		</p>
		<p>
		Thanks for using Vision Source, <br />
		The Vision Source Team.
		</p> ", "The Vision Source Team", "'.date("F j, Y, g:i a").'");';

		if (@mysql_query($sql)) {
   		  echo 'News post created.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}  
		
		$password = md5($password);
		
		 $sql = 'CREATE TABLE `'.$prefix.'_users` (
  				`id` int(11) NOT NULL auto_increment,
  				`username` varchar(20) NOT NULL default "",
 				`password` varchar(32) NOT NULL default "",
  				`admin` tinyint(1) NOT NULL default "0",
  				`session` varchar(32) NOT NULL default "",
  				`ip` varchar(15) NOT NULL default "",
  				`email` varchar(255) NOT NULL default "",
  			  	`reg_ip` varchar(15) NOT NULL default "",
  			  	`skinid` varchar(150) NOT NULL default "",
  				PRIMARY KEY  (`id`)
				) TYPE=MyISAM;';
				

		if (@mysql_query($sql)) {
   		  echo ''.$prefix.'_users created, moving onto next table.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'INSERT INTO `'.$prefix.'_users` VALUES (1, "'.$username.'", "'.$password.'", 1, "", "'.$_SERVER['REMOTE_ADDR'].'", "'.$email.'", "'.$_SERVER['REMOTE_ADDR'].'", "vsource");';
	
		if (@mysql_query($sql)) {
   		  echo 'Admin account created.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'CREATE TABLE `'.$prefix.'_skin` (
			  `id` tinyint(6) NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL default "",
			  `directory` varchar(255) NOT NULL default "",
			  `view` tinyint(1) NOT NULL default "0",
			  `default_skin` tinyint(1) NOT NULL default "0",
			  PRIMARY KEY  (`id`)
			  ) TYPE=MyISAM AUTO_INCREMENT=2 ;';
			  
		if (@mysql_query($sql) || @mysql_query($sql1)) {
   		  echo ''.$prefix.'_skin created, moving onto next table.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}
		
		$sql = 'INSERT INTO `'.$prefix.'_skin` VALUES (1, "Vision Source", "vsource", 1, 1);';
		
		if (@mysql_query($sql) || @mysql_query($sql1)) {
   		  echo 'Skin entry created.<br />';
 		} else {
   		  echo '<p>Im sorry, there was an error: <br />' .
        	 mysql_error() . '</p></body></html>';
		exit;
    	}

		 
		$create = @fopen('install.lock','w');
		@fwrite($create,'install.lock');
		@fclose($create);
		echo "</p>SQL Installtion complete, You can view your website by <a href='".$url."/index.php'>clicking here</a> or go to the ACP by <a href='".$url."/admin.php'>clicking here</a>.</body></html>";
		exit;


	 }
	 
$currenturl	= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
$fullurl 	= str_replace( "/install.php"  , "", $currenturl);
	 
echo <<<EOT
<html>
<head>
<title>Installing Vision Source</title>

<script type="text/javascript">

function checkform(thisform) {

	if (thisform.dbhost.value == "") {
		window.alert("Please enter in your database host details.");
		thisform.dbhost.focus();
		return false;
	}
	
	else if (thisform.dbname.value == "") {
		window.alert("Please enter in your database name details.");
		thisform.dbname.focus();
		return false;
	}
	
	else if (thisform.dbuser.value == "") {
		window.alert("Please enter in your database user details.");
		thisform.dbuser.focus();
		return false;
	}
	
	else if (thisform.dbpass.value == "") {
		window.alert("Please enter in your database password.");
		thisform.dbpass.focus();
		return false;
	}
	
	else if (thisform.url.value == "") {
		window.alert("Please enter in the url of your website.");
		thisform.url.focus();
		return false;
	}
	
	else if (thisform.adminemail.value == "" || thisform.adminemail.value.indexOf("@",0) == -1 || thisform.adminemail.value.lastIndexOf('.') < thisform.adminemail.value.length - 4) {
		window.alert("The email address " + thisform.adminemail.value + " is not correct. Please try again");
		thisform.adminemail.focus();
		return false;
	}
	
	else if (thisform.title.value == "") {
		window.alert("Please enter in the title of your board.");
		thisform.title.focus();
		return false;
	}
	
	else if (thisform.username.value == "") {
		window.alert("Please enter in an admin username.");
		thisform.username.focus();
		return false;
	}
	
	else if (thisform.password.value == "") {
		window.alert("Please enter in an admin password.");
		thisform.password.focus();
		return false;
	}
	
	else if (thisform.password2.value == "") {
		window.alert("Please enter in the admin password again.");
		thisform.password2.focus();
		return false;
	}
	
	else if (thisform.email.value == "") {
		window.alert("Please enter in a email for admin account.");
		thisform.email.focus();
		return false;
	}
	
	else {
		return true;
	}
	
}

</script>
</head>
<body>
<form onsubmit="return checkform(this)" action="install.php?new=1" method="post" name='install' id='install'>
DB Details:
<p>
db host: <input type="text" name="dbhost" value="localhost" /><br />
db name: <input type="text" name="dbname" /><br />
db user: <input type="text" name="dbuser" /> <br />
db pass: <input type="text" name="dbpass" /><br />
Prefix*: <input type="text" name="prefix" /><br />
<span style="font-size:11px; color: #999">This is an optional field. If left blank the prefix will be vsource_. <br />
If you need help about the prefix please read the help file in the database section.</span></p>

User Detials:
<p>
URL: <input type="text" name="url" value="{$fullurl}" /><br />
Admin Email: <input type="text" id="adminemail" name="adminemail" /> <br />
Title: <input type="text" name="title" />
</p>

Admin Account:
<p>
Usernmae: <input type="text" name="username" /><br />
Password: <input type="password" name="password" /> <br />
Password Again: <input type="password" name="password2" /> <br />
Email: <input type="text" id="email" name="email" /> <br />
</p>

<input type="submit" value="Continue.." />
</form>
</body>
</html>
EOT;

}

else { 

	echo "Please click <a href='install.php?new'>here</a> to install Vision Source";
	
}

?>