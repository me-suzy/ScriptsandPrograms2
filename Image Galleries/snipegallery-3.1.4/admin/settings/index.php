<?php 
/**
* Settings display
*
* This file was created to help quickly and easily identify potential 
* installation and configuration problems.
*     
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*/



$GALLERY_SECTION = "settings";
$PAGE_TITLE = "Gallery Settings";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");


include ("../layout/admin.header.php");  

?>
<h3>Script and Server Settings</h3>
<p>Below are some of your configuration, PHP and server settings which may come in handy while trying to troubleshoot your gallery installation or decide what options you can implement with your server's configuration. For details about warnings you find here, please <b><a href="../faq/">see the FAQ</a></b>.</p>

<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#999999" width="85%">
<tr>
	<td class="resultline-alt">Snipe Gallery: </td>
	<td class="resultline-light">version <?php echo $cfg_program_version; ?></td>
</tr>
<tr>
	<td colspan="2" class="resultline"><b>Gallery Options</b></td>
</tr>
<tr>
	<td class="resultline-alt">Filenames:</td>
	<td class="resultline-alt">
	<?php if ($cfg_orig_filenames==1) {
		echo "Keep original filename"; 
	} else {
		echo "Generate new filenames";
	}
	?></td>
</tr>

<tr>
	<td class="resultline-alt">Images Per Page: </td>
	<td class="resultline-light"><?php echo $cfg_per_page_limit; ?></td>
</tr>
<tr>
	<td class="resultline-alt" nowrap="nowrap">Number of Columns: </td>
	<td class="resultline-light"><?php echo $cfg_num_columns; ?></td>
</tr>
<tr>
	<td class="resultline-alt">Use Dropshadow: </td>
	<td class="resultline-light"><?php if ($cfg_use_dropshadow > 0) echo "Yes"; else echo "No"; ?></td>
</tr>
<tr>
	<td class="resultline-alt">Use Photo Frame: </td>
	<td class="resultline-light"><?php if ($cfg_use_frame > 0) echo "Yes"; else echo "No"; ?></td>
</tr>
<tr>
	<td class="resultline-alt">Use Watermarking: </td>
	<td class="resultline-light">
	<?php 
	if ($cfg_enable_watermark > 0) {
		echo "Yes"; 
		if (file_exists($cfg_font_path."/".$cfg_font_name)) {
			echo ' (using '.$cfg_font_name.')';
		} else {
			echo "<span class=\"smerrortxt\">WARNING: the font file cannot be located at ".$cfg_font_path."/".$cfg_font_name." </span>";
		}
	} else { 
	echo "No"; 
	}
	?></td>
</tr>
<tr>
	<td class="resultline-alt">Max Upload Width:</td>
	<td class="resultline-alt">
	<?php if (($cfg_use_fullsize_ceil==1) && ($cfg_max_fullsize_width!="")) {
		echo "Set to ".$cfg_max_fullsize_width." pixels"; 
	} else {
		echo "Disabled or no width set";
	}
	?></td>
</tr>
<tr>
	<td class="resultline-alt">Max Upload Height:</td>
	<td class="resultline-alt">
	<?php if (($cfg_use_fullsize_hceil==1) && ($cfg_max_fullsize_height!="")) {
		echo "Set to ".$cfg_max_fullsize_height." pixels"; 
	} else {
		echo "Disabled or no height set";
	}
	?></td>
</tr>
<tr>
	<td colspan="2" class="resultline"><b>Server Information</b></td>
</tr>
<tr>
	<td class="resultline-alt">Path to Web Root: </td>
	<td class="resultline-light"><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
</tr>
<tr>
	<td colspan="2" class="resultline"><b>Config.php  Settings</b>
	<br>(These are the settings you specified in the config.php file)
	</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Gallery Tables: </td>
	<td class="resultline-light">
	<?php 
	if (TableExists("snipe_gallery_cat", $cfg_database_name) == false) {
	 echo "<img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> Table 'snipe_gallery_cat' does not exist in database '$cfg_database_name'</span>";
	} else {
		echo "category tables exists";
	}

	if (TableExists("snipe_gallery_data", $cfg_database_name) == false) {
	 echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> Table 'snipe_gallery_data' does not exist in database '$cfg_database_name'</span>";
	} else {
		echo "<br>data table exists";
	}
	if (TableExists("snipe_gallery_frames", $cfg_database_name) == false) {
	 echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> Table 'snipe_gallery_frames' does not exist in database '$cfg_database_name'</span>";
	} else {
		echo "<br>frame table exists";
	}
 
	?>
	</td>
</tr>

<tr>
	<td class="resultline-alt">Script Path: </td>
	<td class="resultline-light"><?php echo $cfg_app_path; ?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Syntax Check: </td>
	<td class="resultline-light">
	<?php
	$linux_op = "/";
	$win32_op = "\\";
	$path_error = 0;
	
	if ((strpos($cfg_app_path, $linux_op)) !== FALSE) {
		echo "Path looks like linux";
		if ((strpos($_SERVER['DOCUMENT_ROOT'], $linux_op)) === FALSE) {
			$path_error = 1;
		} 
	} elseif ((strpos($cfg_app_path, $win32_op)) !== FALSE) {
		echo "Path looks like Windows";
		if ((strpos($_SERVER['DOCUMENT_ROOT'], $win32_op)) === FALSE) {
			$path_error = 2;
		} 
	}
	
		
		if ((strpos($cfg_app_path, $_SERVER['DOCUMENT_ROOT'])) === FALSE) {
			$path_error = 3;
		}
	

	if (($path_error == 1) ||  ($path_error == 2))  {
		echo "<br><span class=\"smerrortxt\"><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><b>WARNING! The path you have specified in your config.php file looks incorrect.</b></span><br><b>config.php says:</b> <i>".$cfg_app_path."</i><br><b>Server says:</b> <i>".$_SERVER['DOCUMENT_ROOT']."</i> is your web root";
	} elseif ($path_error == 3) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> Something looks a little fishy with your pathname: $cfg_app_path</span><br><b>config.php says:</b> <i>".$cfg_app_path."</i><br><b>Server says:</b> <i>".$_SERVER['DOCUMENT_ROOT']."</i> is your web root";
	} else {
		echo " - Syntax looks OK";
	}
	?>
</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Gallery Image Path: </td>
	<td class="resultline-light"><?php 
	echo $cfg_pics_path; 	
	clearstatcache();
	if (is_dir($cfg_pics_path) === FALSE) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The image directory \"".$cfg_pics_path."\" does not exist</span>";
	} else {
		if (!is_writable($cfg_pics_path)) {
			echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The image directory \"".$cfg_pics_path."\" is not writeable.  You will need to change the permissions on this directory to allow it to be writable in order to upload images.</span>";
		}
	}
	
	?></td>
</tr>

<tr>
	<td class="resultline-alt" valign="top">Thumbnail Path: </td>
	<td class="resultline-light">
	<?php echo $cfg_thumb_path;
	if (is_dir($cfg_thumb_path) === FALSE) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The thumbnail directory \"".$cfg_thumb_path."\" does not exist</span>";
	} else {
		if (!is_writable($cfg_thumb_path)) {
			echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The thumbnail directory \"".$cfg_thumb_path."\" is not writeable. You will need to change the permissions on this directory to allow it to be writable in order to upload images.</span>";
		}
	}
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Cache Path: </td>
	<td class="resultline-light">
	<?php 
	if ($cfg_use_cache ==1) {
		echo $cfg_cache_path;
		if (is_dir($cfg_cache_path) === FALSE) {
			echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The cache directory \"".$cfg_cache_path."\" does not exist</span>";
		} else {
			if (!is_writable($cfg_cache_path)) {
				echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The cache directory \"".$cfg_cache_path."\" is not writeable. You will need to change the permissions on this directory to allow it to be writable in order to upload images.</span>";
			}
		}
	} else {
		echo "cache disabled";
	}
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Local Import Path: </td>
	<td class="resultline-light">
	<?php 
		echo $cfg_local_import_dir;
		if (!is_dir($cfg_local_import_dir)) {
			echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The local import directory \"".$cfg_local_import_dir."\" does not exist</span>";
		} 

	
	?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Frame Image Path: </td>
	<td class="resultline-light"><?php 
	echo $cfg_frames_path; 	
	clearstatcache();
	if (is_dir($cfg_frames_path) === FALSE) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The image directory \"".$cfg_frames_path."\" does not exist</span>";
	} else {
		if (!is_writable($cfg_frames_path)) {
			echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The image directory \"".$cfg_frames_path."\" is not writeable.  You will need to change the permissions on this directory to allow it to be writable in order to upload images.</span>";
		}
	}
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Font Path: </td>
	<td class="resultline-light"><?php 
	echo $cfg_font_path; 	
	clearstatcache();
	if (is_dir($cfg_font_path) === FALSE) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The font directory \"".$cfg_font_path."\" does not exist</span>";
	}
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt">Public URL: </td>
	<td class="resultline-light"><?php echo $cfg_app_url; ?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Admin URL: </td>
	<td class="resultline-light">
	<?php echo $cfg_admin_url; 
	$path = $cfg_admin_path."/.htaccess"; 
		if (file_exists($path)) {			
			echo "password protected";
		} else {
			echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The admin directory does not appear to be password protected!  This means that anyone may add, edit or delete your gallery images. ($path)</span>";
		}		
		

	
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Install Script: </td>
	<td class="resultline-light">
	<?php 
	if (file_exists($cfg_admin_path."/install.php")){

		echo "<img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> The install.php script should be deleted once your install is complete.</span>";
	} else {	
		echo "deleted";
	}
	
	?></td>
</tr>

<tr>
	<td colspan="2" class="resultline"><b>PHP Information</b></td>
</tr>
<tr>
	<td class="resultline-alt">PHP Version: </td>
	<td class="resultline-light">
	<?php echo phpversion(); 
	if (phpversion() < $min_php_version) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> You are using a version of PHP that may not be compatible with this software.  Upgrading PHP to version ".$min_php_version." or higher is recommended.</span>";
	}
	 ?>
	
	</td>
</tr>
<tr>
	<td class="resultline-alt">Error Reporting: </td>
	<td class="resultline-light">
	<?php 
	$error_reporting_level = ini_get('error_reporting');
	echo $error_reporting_level;

	if ($error_reporting_level == 2048) {
		echo " (Strict)";		
	} elseif ($error_reporting_level == 2047) {
		echo " (Show All Errors)";	
	
	} elseif ($error_reporting_level == 2) {
		echo " (Show Warnings)";
	
	} 		
	?>
	
	</td>
</tr>

<tr>
	<td class="resultline-alt" valign="top">Globals:</td>
	<td class="resultline-light">
	<?php echo show_friendly_ini(ini_get('register_globals'));
		if (ini_get('register_globals')==1) {
			echo " <br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> It is recommended that register_globals is <b>disabled</b> for <b>security reasons</b>.  Don't say we didn't warn you.</span>";
		}
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt">Safe Mode:</td>
	<td class="resultline-light"><?php echo show_friendly_ini(ini_get('safe_mode')) ; ?></td>
</tr>
<tr>
	<td colspan="2" class="resultline"><b>GDlib Information</b></td>
</tr>
<tr>
	<td class="resultline-alt">GDlib Version:</td>
	<td class="resultline-light"><?php 
	echo $gd_info_array["GD Version"]; 
	preg_match('%\d+(\.\d+)*%', $gd_info_array["GD Version"], $m); 
	$current_gd_version =  $m[0];
	if ($current_gd_version < $min_gd_version) {
		echo "<br><img src=\"".$cfg_admin_url."/images/icons/exl.gif\" border=\"0\" alt=\"ERROR\" hspace=\"2\"><span class=\"smerrortxt\"><b>WARNING!</b> You are using a version of gdlib (version ".$current_gd_version.") that may not be compatible with this software.  Upgrading to gdlib ".$min_gd_version." or higher is recommended.</span>";
	}	elseif ($current_gd_version < $rec_gd_version) {
		echo " - version looks OK - lower quality thumbnails (<a href=\"http://www.boutell.com/gd/\" target=\"_new\">upgrade gdlib</a> to ".$rec_gd_version." for better thumbnailing)";
	}	else {
		echo " - version looks OK for best quality thumbnail";
	}

	?></td>
</tr>
<tr>
	<td class="resultline-alt">JPG Support:</td>
	<td class="resultline-light"><?php 
	if ($gd_info_array["JPG Support"]== true) {
		echo "YES";
	} else {
		echo "NO";	}
	
	?></td>
</tr>
<tr>
	<td class="resultline-alt">PNG Support:</td>
	<td class="resultline-light"><?php 
	if ($gd_info_array["PNG Support"]== true) {
		echo "YES";
	} else {
		echo "NO";
	}
	?></td>
</tr>
<tr>
	<td class="resultline-alt">GIF Support:</td>
	<td class="resultline-light">
	<?php 
	if ($gd_info_array["GIF Read Support"]== true) {
		echo "Can Read, ";
	} else {
		echo "Cannot Read, ";
	}
	if ($gd_info_array["GIF Create Support"]== true) {
		echo "Create";
	} else {
		echo "Cannot Create";
	}
	?></td>
</tr>
<tr>
	<td class="resultline-alt">Freetype Support:</td>
	<td class="resultline-light">
	<?php 
	if ($gd_info_array["FreeType Support"]== true) {
		echo "YES";
	} else {
		echo "NO";
	}
	?></td>
</tr>
</table></center>
	
<?php include ("../layout/admin.footer.php");   ?>	