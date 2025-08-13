<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //

//------------------------------------------------------------------//
// install.php
// Author: Carlos Sánchez
// Created: 11/09/02
//
// Description: Installation Script. Based on the 
// one created for PHPBB 2.x
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

// Include all the required files
// (The config.php file has NOT been generated yet)
$myng_file['templates']		= "template.inc.php";
$myng_file['login_class']	= "login.class.php";
$myng_file['standard_lib']	= "standard.lib.php";
$myng_file['db']			= "db_mysql.inc.php";
$myng_file['extended_class']	= "extended.class.php";
$myng_file['calendar_class'] = "calendar.class.php";

include("./include/".$myng_file['templates']);           // Template class
include("./class/".$myng_file['login_class']);           // Login class
include("./lib/".$myng_file['standard_lib']);            // Standard library
include("./include/".$myng_file['db']);                  // Database abstraction class
include("./class/".$myng_file['calendar_class']);           // Login class
include("./class/".$myng_file['extended_class']);        // Extended classes

// Set up the language
modules_get_language();


// First Step: The form has been submitted

if(isset($_POST['confirm'])){
		
	// The form has been Submitted	
	
	// Firstly, we have to Check if the user has submitted correct
	// entries in the form
	
	// -- Basic Configuration -- //
	
	if(!isset($_POST['server_host'])){
		echo "Error: Empty Host Name.";
		exit();		
	}
	
	if(!isset($_POST['script_path'])){
		echo "Error: Empty Script Prefix.";
		exit();		
	}
	
	if(!isset($_POST['conf_system_language'])){
		echo "Error: Empty Language.";
		exit();		
	}
	
	if(!isset($_POST['conf_system_zlib_yn'])){
		echo "Error: Empty Zlib configuration.";
		exit();		
	}
	
	// -- Database Configuration -- //
	
	if(!isset($_POST['db_host'])){
		echo "Error: Empty Database Host Name.";
		exit();		
	}

	if(!isset($_POST['db_name'])){
		echo "Error: Empty Database Name.";
		exit();		
	}
	
	if(!isset($_POST['db_user_name'])){
		echo "Error: Empty Database User Name.";
		exit();	
	}
	
	if(!isset($_POST['db_passwd'])){
		echo "Error: Empty Database User Password.";
		exit();		
	}
	
	// -- Admin Configuration -- //
	
	if(!isset($_POST['adm_login'])){
		echo "Error: Empty Administration Login.";
		exit();	
	}
	
	if(!isset($_POST['adm_passwd'])){
		echo "Error: Empty Administration Password.";
		exit();		
	}
	
	if(!isset($_POST['adm_email'])){
		echo "Error: Empty Administration Email.";
		exit();		
	}
	
	if($_POST['adm_passwd'] != $_POST['adm_passwd_2'] ){
		echo "Error: Administration Passwords don't match.";
	}

	$script_path = preg_replace('/install\.'."php".'/i', '', $_SERVER['PHP_SELF']);	
	
	// Try to create and populate with the default values the system tables
	
	$db=new My_db;
	// Get the data of the Database
	$db->Host = $_POST['db_host'];
	$db->Database = $_POST['db_name'];
	$db->User = $_POST['db_user_name'];
	$db->Password = $_POST['db_passwd'];
	// Connect to the database
	$db->connect();
		
	include("./install/sql.php");

	// Create the Admin User
	$sql_query= 
	
	"INSERT INTO myng_admin (	
		adm_login, 
		adm_passwd	
	) VALUES (	
		'".$_POST['adm_login']."',
		'".$_POST['adm_passwd']."'
	)";
	
	$db->query($sql_query);
	
	// Send the config.php File
	
	
	$config_data = '<?'."\n\n";
	$config_data .= "//\n// MyNewsGroups :) installation generated config file\n// Do not change anything in this file!\n//\n\n";		
	$config_data .= '$myng_db[\'host\'] = \'' . $_POST['db_host'] . '\';' . "\n";
	$config_data .= '$myng_db[\'database\'] = \'' . $_POST['db_name'] . '\';' . "\n";
	$config_data .= '$myng_db[\'user\'] = \'' . $_POST['db_user_name'] . '\';' . "\n";
	$config_data .= '$myng_db[\'password\'] = \'' . $_POST['db_passwd'] . '\';' . "\n\n";	
	$config_data .= '$myng_db[\'prefix\'] = \'myng_\''. ';' ."\n\n";	
	$config_data .= '$myng_root = \''.rtrim($_SERVER['DOCUMENT_ROOT'],'/').$_POST['script_path'].'\';'."\n";	
	$config_data .= '// Include the required files'."\n";	
	$config_data .= 'include(\''.rtrim($_SERVER['DOCUMENT_ROOT'],"/").$script_path.'include.php\')'. ';' ."\n\n";	
	$config_data .= '$start = start_time();'."\n\n";
	$config_data .= 'define(\'MYNG_INSTALLED\', true);'."\n\n";	
	$config_data .= 'define(\'MYNG_VERSION\', \'0.6\');'."\n\n";
	$config_data .= '?' . '>'; 

	
	// Prepare the second template (Download the config.php File)
	$t = new Template("./themes/standard/templates/install");
	$t->set_file("install","install_2.htm");
	
	// Links
	$t->set_var("home_dir",$script_path);
	// CSS
	$t->set_var("style_dir",$script_path."themes/standard/styles/");
	// Images
	$t->set_var("images_dir",$script_path."themes/standard/images");
	
	// Show the configuration file
	$t->set_var("config_text",$config_data);
	
	// Show the root
	$t->set_var("myng_root",rtrim($_SERVER['DOCUMENT_ROOT'],'/').$_POST['script_path']);
	
	$t->parse("out","install");
	// Show the web
	$t->p("out");		
	
	exit();
	

// Second Step: Configuration File Download
		
}elseif(isset($_POST['download'])){
	
	// Send the config.php File

	$config_data = $_POST['config'];
			
	header("Content-Type: text/x-delimtext; name=\"config.php\"");
	header("Content-disposition: attachment; filename=config.php");

	$config_data = stripcslashes($config_data);
	// O_O It's very important to put here the exit() function,
	// in order to stop the script execution and avoid the adding
	// of \n caracters to the file downloaded.
	echo $config_data;
	exit();
	
	
// Last Step: Congratulations

}elseif(isset($_POST['finish'])){
	
	// Check if config.php file exists
	if(file_exists("config.php")){

		// Prepare the final template (Download the config.php File)
		$t = new Template("./themes/standard/templates/install");
		$t->set_file("install","install_3.htm");
	
		// Links
		$t->set_var("home_dir",$script_path);
		// CSS
		$t->set_var("style_dir",$script_path."themes/standard/styles/");
		// Images
		$t->set_var("images_dir",$script_path."themes/standard/images");
	
		$t->set_var("version",MYNG_VERSION);
	
	
		$t->parse("out","install");
		// Show the web
		$t->p("out");		
	
		exit;
		
	}else{
		
		// Error, the config.php file has not been uploaded
		echo "Error: config.php file not uploaded.";
		exit();				

	}
	
	

	
}else{

	if(file_exists("config.php")){
		
		// MyNG has already been installed.
		// Redirect to the index.php
		header("Location: "."index.php", true);
		
	}else{
		
	// -------- Show the Installation Welcome Template ---------- //
	
	// Try to get the Prefix Path (Directory where MyNewsGroups :)
	// is currently running on) to show the required styles and images.
	// Script Path grab mini-script learnt from PHPBB2 :: phpbb.com
	$script_path = preg_replace('/install\.'."php".'/i', '', $_SERVER['PHP_SELF']);	
	$server_name = ( !empty($_SERVER['SERVER_NAME']) ) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];	
			
	// Show the form
	// Templates
	$t = new Template("./themes/standard/templates/install");
	$t->set_file("install","install.htm");
	$t->set_block("install","language_block","language_block_handle");
	$t->set_block("install","zlib_yes_block","zlib_yes_block_handle");
	$t->set_block("install","zlib_no_block","zlib_no_block_handle");

	// Links
	$t->set_var("home_dir",$script_path);
	// CSS
	$t->set_var("style_dir",$script_path."themes/standard/styles/");
	// Images
	$t->set_var("images_dir",$script_path."themes/standard/images");
	
	
	
	// Variables
	$t->set_var("script_path",$script_path);
	$t->set_var("server_name",$server_name);
	
	// Zlib Support??
	if(extension_loaded('zlib')){
		// ZLib Support is available!
		$t->parse("zlib_yes_block_handle","zlib_yes_block",true);            	
		
	}else{
		// Don't have Zlib support			
		$t->parse("zlib_no_block_handle","zlib_no_block",true);            	
	}
	
	
	
	// Try to read the available Languages and Themes
	if ($handle = opendir("./lang")) {
    	while (false !== ($file = readdir($handle))) { 
    		// Check if the directory name is '.','..','CVS' or if it's a directory.
       		if ($file != "." && $file != ".." && filetype("./lang/".$file) == "dir" && $file != "CVS") { 
 		        $t->set_var("language",$file);
 	            // Check for the current language
 	            if($file == $db->Record['conf_system_language'] ){ 	                	 	               
 	              	$t->set_var("language_is_selected","selected");
 	            }else{ 
 	              	$t->set_var("language_is_selected",""); 	                
 	            }
               	$t->parse("language_block_handle","language_block",true);            	
        	} 
    	}
    	closedir($handle); 
	}

	$t->parse("out","install");
	// Show the web
	$t->p("out");
	
	}

}


?>







