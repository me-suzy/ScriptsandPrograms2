<?php 
/**
* Administration Homepage - index.php
*
* This is the admin index page.
*   
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
* {@source}
*/
$GALLERY_SECTION = "gallery";
include ("../inc/config.php");
include ($cfg_admin_path."/lib/admin.functions.php");

/*
* Check to see that the admin path and congif file appear to be correct
*/
if ((!is_dir($cfg_admin_path)) || (!file_exists($cfg_app_path."/inc/config.php"))) {
	$path_error = 1;
	$is_error = 1;
} else {
	if (!@mysql_pconnect($cfg_database_host,$cfg_database_user,$cfg_database_pass))
			die('<span class="errortxt">FATAL ERROR: cannot connect to mySQL server <br>host: '.$cfg_database_host.' <br>user: '.$cfg_database_user.' <br>password: '.$cfg_database_pass.' </span>');
		if (!@mysql_select_db($cfg_database_name)) 
			die('<span class="errortxt">FATAL ERROR: I cant make up my mind!! Cannot select MySQL database "'.$cfg_database_name.'"</span>');

	/*
	* Check to see if the tables have been created
	*/
	if (!$result = mysql_list_tables($cfg_database_name)) {
	  $goto_install = 1;
	  echo mysql_error();
	  $is_error = 1;
	} 

	if (TableExists("snipe_gallery_cat", $cfg_database_name) === FALSE) {
		 echo mysql_error();
		 $goto_install = 1;	
		 $is_error = 1;
	}

	if (TableExists("snipe_gallery_data", $cfg_database_name) === FALSE) {
		 echo mysql_error();
		 $goto_install = 1;	
		 $is_error = 1;
	}
}


if ((isset($is_error)) && ($is_error == 1)) {
	include ("layout/admin.header.php");
	if ((isset($goto_install)) && ($goto_install == 1)) {
		
		echo "<p class=\"errortxt\">The database tables have not been created yet.  You must run the .sql file in /admin/docs/ to set up the tables before proceeding.</p>\n\n";
		
	} elseif ((isset($path_error)) && ($path_error == 1)) {
		echo "<p class=\"errortxt\">Something in your path settings doesn't seem right.  Please check the paths in your configuration file.</p>\n\n";

		/*
		* Let's see if we can offer a little more help....
		*/
		if (!file_exists($cfg_app_path."/inc/config.php")) {
			if (empty($cfg_app_path)) {
				echo "<li>The cfg_app_path variable does not appear to be set.  This most likely means that your config.php file has not been uploaded properly.   ";
			} else {
				echo "<li>The configuration file cannot be located at: ".$cfg_app_path."/inc/config.php";
			}
			
		} elseif (!is_dir($cfg_admin_path))  {
			echo "<li>The admin directory does not appear to be correctly set in your config.php file.  <br>cfg_admin_path: ".$cfg_admin_path." <br>Your admin path <i>appears</i> to be: ".dirname($_SERVER['PATH_TRANSLATED'])." - try changing this path in your config.php file and see if that helps.  ";
		} 
	}
	include ("layout/admin.footer.php"); 
/*
* Otherwise if everything checks out okay, take them to the gallery view page.
*/
} else {
	header("Location: gallery/"); 
	exit;
}
?>	
