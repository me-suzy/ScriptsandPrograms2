<?php
/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/
if($_POST["download_config"]){
header('Content-Type: text/x-delimtext; name="config.php"');
			header('Content-disposition: attachment; filename="config.php"');
		echo stripslashes($_POST["download_config"]);
}

function config_screen(){
	$Status = true;
?>	
	<table border=0>
		<tr><td align="center" valign="middle">
				<?php blocktitle("Config"); ?>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
				<table border="1" height="100%" width="45%" cellpadding="1" cellspacing="1">
					<tr><td align="center" valign="middle">
							<table border="0" height="100%" width="100%" cellpadding="0" cellspacing="2">
								<?php 
								if(!is_writable("../include/config.php") or !file_exists("../include/config.php")){
									install_message('red','Configuration file is not writable.'.chr(10).'Please upload config.php manually to /include');
									?>
									<form name="download_form" method="POST" action="writeinstallation.php">
									<?= write_config_file($Status); ?>
									<input type="submit" name="submit" value="Download config.php"/>
									</form>
									<?php
								}
								else {
									// writing configuration file
									$Status = write_config_file($Status);
									if (!$Status) { install_message('red','Failed writing the configuration file.'); }
									else{install_message('green','Configuration file created succesfully.');}
									//writing htaccess files
									$Status = write_htaccess_files(&$Status);
									if (!$Status) { install_message('red','Failed writing the .htaccess files.'); }
									else{install_message('green','.htaccess Files created succesfully');}
								}
								?>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td align="center" valign="middle">
						<input type="button" class="ip_text" value="Goto iziAdmin" onClick="location.href = '../admin/index.php'">
		</td></tr>
	</table>
<?php
//rename_file("writeinstallation.php","writeinstallation.php.complete");
}// function config_screen

function write_config_file(&$Status)
{
	global $_POST, $_SERVER;

		$server = $_SERVER['PHP_SELF']; 
		$Apathweb = explode("/", $server); 
		$myFileName = array_pop($Apathweb); 
		$pathweb = implode("/", $Apathweb); 
		$pathweb = str_replace( "/izi_install", "", $pathweb );
		$url = "http://".$_SERVER['SERVER_NAME'].$pathweb; 
		
		//getting absolute Path
		$base_dir = dirname(__FILE__);
		if (eregi("WIN",PHP_OS)) {
		    $base_dir = str_replace("\\","/",$base_dir);
		}

		$base_dir = str_replace( "/izi_install", "", $base_dir );

	 // setting up configdata for config.php
		$config_file = '<?php '."\n\n";
		$config_file .= '/***************************************************************************'."\n\n";
		$config_file .=' config.php'."\n";
		$config_file .=' -----------'."\n";
		$config_file .= ' copyright : (C) 2005 The iziContents Development Team'."\n"."\n";
		$config_file .=' ***************************************************************************/'."\n"."\n";
		$config_file .='/***************************************************************************'."\n";
		$config_file .=' The iziContents Development Team offers no warranties on this script.'."\n";
		$config_file .=' The owner/licensee of the script is solely responsible for any problems'."\n";
		$config_file .=' caused by installation of the script or use of the script.'."\n"."\n";
		$config_file .=' All copyright notices regarding iziContents must remain intact on the'."\n";
		$config_file .=' scripts and in the HTML for the scripts.'."\n"."\n";
		$config_file .=' For more info on iziContents,'."\n";
		$config_file .=' visit http://www.izicontents.com/'."\n";
		$config_file .=' ***************************************************************************/'."\n"."\n";
		$config_file .='/***************************************************************************'."\n";
		$config_file .=' *'."\n";
		$config_file .=' *	This program is free software; you can redistribute it and/or modify'."\n";
		$config_file .=' *	it under the terms of the License which can be found within the'."\n";
		$config_file .=' *	zipped package.'."\n";
		$config_file .=' *'."\n";
		$config_file .=' ***************************************************************************/'."\n"."\n";
		$config_file .='$GLOBALS["gsAdminStyle"]							= "pixel";'."\n";
		$config_file .='// Database Configuration'."\n";
		$config_file .='$GLOBALS["dbPersistent"]							= "'.$_POST["DBPersistent"].'";		//	Persistent database connections, \'Y\'es or \'N\'o'."\n";
		$config_file .='$GLOBALS["ezContentsDB"]							= "'.$_POST["DBType"].'";					//	Database server type'."\n";
		$config_file .='$GLOBALS["ezContentsDBServer"]				= "'.$_POST["DBServer"].'";				//	IP Address of the database server'."\n";
		$config_file .='$GLOBALS["ezContentsDBName"]					= "'.$_POST["DBName"].'";					//	Database name'."\n";
		$config_file .='$GLOBALS["ezContentsDBLogin"]					= "'.$_POST["DBLogin"].'";				//	Database system login'."\n";
		$config_file .='$GLOBALS["ezContentsDBPassword"]			= "'.$_POST["DBPassword"].'";			//	Database system password'."\n"."\n"."\n";
		$config_file .='$GLOBALS["admin_home"]								= "admin/";												// admin directory'."\n";
		$config_file .='$GLOBALS["language_home"]							= "languages/";										// language base directory'."\n";
		$config_file .='$GLOBALS["image_home"]								= "contentimage/";								// user images'."\n";
		$config_file .='$GLOBALS["downloads_home"]						= "downloads/";										// user downloads'."\n";
		$config_file .='$GLOBALS["style_home"]								= "admin/styles/";								// Extra external style sheet'."\n";
		$config_file .='$GLOBALS["font_home"]									= "admin/styles/fonts/";					// Extra fonts'."\n";
		$config_file .='$GLOBALS["script_home"]								= "scripts/";											// external php script files'."\n";
		$config_file .='$GLOBALS["icon_home"]									= "images/";											// ezContents internal icons'."\n";
		$config_file .='$GLOBALS["modules_home"]							= "modules/";											// add-in modules base directory'."\n";
		$config_file .='$GLOBALS["backup_home"]								= "backup/";											// ezContents database backup directory'."\n";
		$config_file .='$GLOBALS["include_home"]							= "include/";											// include file base directory'."\n";
		$config_file .='$GLOBALS["sites_home"]								= "sites/";												// multi-site base directory'."\n";
		$config_file .='$GLOBALS["themes_home"]								= "themes/";											// multi-theme base directory'."\n"."\n";
		$config_file .='// ezContents Table Names'."\n";
		$config_file .='$GLOBALS["eztbPrefix"]								= \''.$_POST["DBPrefix"].'\';'."\n";
		$config_file .='$GLOBALS["eztbMasterPrefix"]					= $GLOBALS["eztbPrefix"];'."\n";
		$config_file .='$GLOBALS["eztbSessions"]							= $GLOBALS["eztbMasterPrefix"]."sessions";'."\n";
		$config_file .='$GLOBALS["eztbSites"]									= $GLOBALS["eztbMasterPrefix"]."sites";'."\n";
		$config_file .='$GLOBALS["eztbVisitorstats"]					= $GLOBALS["eztbMasterPrefix"]."visitorstats";'."\n";
		$config_file .='$GLOBALS["eztbFunctiongroups"]				= $GLOBALS["eztbMasterPrefix"]."functiongroups";'."\n";
		$config_file .='$GLOBALS["eztbFunctions"]							= $GLOBALS["eztbMasterPrefix"]."functions";'."\n";
		$config_file .='$GLOBALS["eztbUsergroups"]						= $GLOBALS["eztbMasterPrefix"]."usergroups";'."\n";
		$config_file .='$GLOBALS["eztbPrivileges"]						= $GLOBALS["eztbMasterPrefix"]."userprivileges";'."\n";
		$config_file .='$GLOBALS["eztbCountries"]							= $GLOBALS["eztbMasterPrefix"]."countries";'."\n";
		$config_file .='$GLOBALS["eztbContinents"]						= $GLOBALS["eztbMasterPrefix"]."continents";'."\n";
		$config_file .='$GLOBALS["eztbLanguages"]							= $GLOBALS["eztbMasterPrefix"]."languages";'."\n";
		$config_file .='$GLOBALS["eztbModules"]								= $GLOBALS["eztbMasterPrefix"]."modules";'."\n";
		$config_file .='$GLOBALS["eztbAuthors"]								= $GLOBALS["eztbMasterPrefix"]."authors";'."\n";
		$config_file .='$GLOBALS["eztbThemes"]								= $GLOBALS["eztbMasterPrefix"]."themes";'."\n";
		$config_file .='$GLOBALS["eztbSettings"]							= $GLOBALS["eztbMasterPrefix"]."settings";'."\n";
		$config_file .='$GLOBALS["eztbUserdata"]							= $GLOBALS["eztbMasterPrefix"]."userdata";'."\n";
		$config_file .='$GLOBALS["eztbBanners"]								= $GLOBALS["eztbMasterPrefix"]."banners";'."\n";
		$config_file .='$GLOBALS["eztbTopgroups"]							= $GLOBALS["eztbMasterPrefix"]."topgroups";'."\n";
		$config_file .='$GLOBALS["eztbGroups"]								= $GLOBALS["eztbMasterPrefix"]."groups";'."\n";
		$config_file .='$GLOBALS["eztbSubgroups"]							= $GLOBALS["eztbMasterPrefix"]."subgroups";'."\n";
		$config_file .='$GLOBALS["eztbTagCategories"]					= $GLOBALS["eztbMasterPrefix"]."tagcategories";'."\n";
		$config_file .='$GLOBALS["eztbTags"]									= $GLOBALS["eztbMasterPrefix"]."tags";'."\n";
		$config_file .='$GLOBALS["eztbSidebartemplates"]			= $GLOBALS["eztbMasterPrefix"]."sidebartemplates";'."\n";
		$config_file .='$GLOBALS["eztbImageformattemplates"]	= $GLOBALS["eztbMasterPrefix"]."imageformattemplates";'."\n";
		$config_file .='$GLOBALS["eztbContents"]							= $GLOBALS["eztbMasterPrefix"]."contents";'."\n";
		$config_file .='$GLOBALS["eztbRatings"]								= $GLOBALS["eztbMasterPrefix"]."ratings";'."\n";
		$config_file .='$GLOBALS["eztbSpecialcontents"]				= $GLOBALS["eztbMasterPrefix"]."specialcontents";'."\n";
		$config_file .='$GLOBALS["eztbModuleSettings"]				= $GLOBALS["eztbMasterPrefix"]."modulesettings";'."\n";
		$config_file .='$GLOBALS["eztbFiletypes"]							= $GLOBALS["eztbMasterPrefix"]."filetypes";'."\n"."\n";
		$config_file .='// Other Configuration Variables'."\n";
		$config_file .='$GLOBALS["RECORDS_PER_PAGE"]					= 8;			// number of lines of content on admin list pages'."\n";
		$config_file .='// Tag block controls'."\n";        	
		$config_file .='$GLOBALS["tqBlock"]										= \'[]\';'."\n";
		$config_file .='$GLOBALS["tqBlock1"]									= substr($GLOBALS["tqBlock"], 0, 1);'."\n";
		$config_file .='$GLOBALS["tqBlock2"]									= substr($GLOBALS["tqBlock"], -1, 1);'."\n";
		$config_file .='$GLOBALS["tqCloseBlock"]							= \'/\';'."\n";
		$config_file .='$GLOBALS["tqSeparator"]								= \',\';'."\n"."\n";
		$config_file .='$GLOBALS["locale"]										= \'\';							// Unix locale code if not using the machine default value'."\n"."\n";
		$config_file .='$GLOBALS["ShowFilePermissions"]				= \'N\';'."\n";
		$config_file .='$GLOBALS["uploadmethod"]							= \'http:\';				// Valid values are \'http:\' and \'ftp:\''."\n";
		$config_file .='$GLOBALS["ftp"]["server"]							= \'localhost\';'."\n";
		$config_file .='$GLOBALS["ftp"]["port"]								= 21;'."\n";
		$config_file .='$GLOBALS["ftp"]["username"]						= \'anonymous\';'."\n";
		$config_file .='$GLOBALS["ftp"]["password"]						= \'secret password\';'."\n";
		$config_file .='$GLOBALS["ftp"]["ezContents_root"]		= \'/iziC\';'."\n"."\n"."\n";
		$config_file .='$imagedir 														= "/".$GLOBALS["image_home"];'."\n";
		$config_file .='$imageurl 														= "/".$GLOBALS["image_home"];'."\n";
		$config_file .='$scriptdir 														= "/include/htmlarea2Modified/popups/";'."\n";
		$config_file .='$scripturl 														= "/include/htmlarea2Modified/popups/";'."\n"."\n";
		$config_file .='$download 														= "downloads";'."\n"."\n";
		$config_file .='// Filesystem, /home/web/www (your web path)'."\n";
		$config_file .='// Site URL and absolute Path'."\n";
		$config_file .='$GLOBALS["websiteurl"] 								= "'.$url.'";'."\n";
		$config_file .='$GLOBALS["basedir"] 									= "'.$base_dir.'";'."\n";
		$config_file .='$basedir 															= "'.$base_dir.'";'."\n"."\n";
		$config_file .='$GLOBALS["bDebug"] = False;'."\n"."\n";
		$config_file .='if ($GLOBALS["bDebug"]) { error_reporting( E_ALL & ~ E_NOTICE) ; }'."\n";
		$config_file .='else { error_reporting( E_ERROR | E_PARSE) ; }'."\n"."\n";
		$config_file .='?>';
		
		// verifying rights for config.php
		//  Write the config file
		$fp = fopen("../include/config.php", "wb");
		if(!$fp){
			return '<input type="hidden" class="ip_text" name="download_config" value="' . htmlspecialchars($config_file) . '" />';	
		}
		else{
		@fputs($fp, $config_file, strlen($config_file));	
		fclose($fp);
			if ($GLOBALS["OS"] == "Windows") { chmod("../include/config.php", 666); }
			else { chmod("../include/config.php", 0666); }
		}
	
return $Status;
	
} // function write_config_file()


function write_htaccess_files(&$Status)
{
	global $_SERVER;

	$Status = True;
	// .htaccess files are only written for Apache
	if (strpos($_SERVER["SERVER_SOFTWARE"], 'Apache') !== FALSE) {
		$dir_array = explode('/',$_SERVER["REQUEST_URI"]);
		$discard = array_pop($dir_array);
		$rewritebase = implode('/',$dir_array).'/';
		$rewritebase = str_replace("izi_install", "", $rewritebase);
		//  Write the single site .htaccess file
		$fp = fopen("../singlesite.htaccess", "wb");
		if ($fp) {
			fwrite($fp,'#  Options +FollowSymlinks'.chr(10));
			fwrite($fp,'RewriteEngine on'.chr(10));
			fwrite($fp,'RewriteBase '.$rewritebase.chr(10));
			fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)$'.chr(10));
			fwrite($fp,'RewriteRule ^(.*) vscms.php?p1=%1&p2=%2&p3=%3&p4=%4 [L]'.chr(10));
			fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)$'.chr(10));
			fwrite($fp,'RewriteRule ^(.*) vscms.php?p1=%1&p2=%2&p3=%3 [L]'.chr(10));
			fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)$'.chr(10));
			fwrite($fp,'RewriteRule ^(.*) vscms.php?p1=%1&p2=%2 [L]'.chr(10));
			fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)$'.chr(10));
			fwrite($fp,'RewriteRule ^(.*) vscms.php?p1=%1 [L]'.chr(10));
			fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc$'.chr(10));
			fwrite($fp,'RewriteRule ^(.*) index.php'.chr(10));
			fclose($fp);
			if ($GLOBALS["OS"] == "Windows") { chmod("../singlesite.htaccess", 666); } else { chmod("../singlesite.htaccess", 0666); }
		} else { $Status = False; }

		if ($Status) {
			//  Write the multi-site .htaccess file
			$fp = fopen("../multisite.htaccess", "wb");
			if ($fp) {
				fwrite($fp,'#  Options +FollowSymlinks'.chr(10));
				fwrite($fp,'RewriteEngine on'.chr(10));
				fwrite($fp,'RewriteBase '.$rewritebase.chr(10));
				fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)$'.chr(10));
				fwrite($fp,'RewriteRule ^(.*) vscms.php?Site=%1&p1=%2&p2=%3&p3=%4 [L]'.chr(10));
				fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)$'.chr(10));
				fwrite($fp,'RewriteRule ^(.*) vscms.php?Site=%1&p1=%2&p2=%3 [L]'.chr(10));
				fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)/([A-Za-z0-9_]*)$'.chr(10));
				fwrite($fp,'RewriteRule ^(.*) vscms.php?Site=%1&p1=%2 [L]'.chr(10));
				fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc/([A-Za-z0-9_]*)$'.chr(10));
				fwrite($fp,'RewriteRule ^(.*) vscms.php?Site=%1 [L]'.chr(10));
				fwrite($fp,'RewriteCond %{REQUEST_URI} /ezc$'.chr(10));
				fwrite($fp,'RewriteRule ^(.*) index.php'.chr(10));
				fclose($fp);
				if ($GLOBALS["OS"] == "Windows") { chmod("../multisite.htaccess", 666); } else { chmod("../multisite.htaccess", 0666); }
			} else { $Status = False; }
		}
	}

	return $Status;
} // function write_htaccess_files()

?>