<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
 
include ("config.inc.php");

$root_path = preg_replace("/install\.php$/is", "", $GLOBALS["SCRIPT_FILENAME"]);

function check_permission($filename) {
/*
	    S_IRWXU 0000700     RWX mask for owner 
	    S_IRUSR 0000400     R for owner 
	    S_IWUSR 0000200     W for owner 
	    S_IXUSR 0000100     X for owner 

	    S_IRWXG 0000070     RWX mask for group 
	    S_IRGRP 0000040     R for group 
	    S_IWGRP 0000020     W for group 
	    S_IXGRP 0000010     X for group 

	    S_IRWXO 0000007     RWX mask for other 
	    S_IROTH 0000004     R for other 
	    S_IWOTH 0000002     W for other 
	    S_IXOTH 0000001     X for other 

	    S_ISUID 0004000     set user id on execution 
	    S_ISGID 0002000     set group id on execution 
	    S_ISVTX 0001000     save swapped text even after use 
*/
$fstats= @stat($filename);

if ( ($fstats[2] & 0000200) AND ($fstats[2] & 0000020) AND ($fstats[2] & 0000002) )
return true;  else return false; 
}

// Startup 
include($root_path.'include/startup/debug.inc.php');
include($root_path.'include/startup/common_functions.inc.php');
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
if(! include($root_path_admin.'lang/'.$default_language.'.inc.php') ) die("Can't include ".$root_path.'lang/'.$default_language.'.inc.php');

if($_POST["http_path"]) {
	include($root_path.'system/image_config.inc.php');
	$template_content = preg_replace(
					array("/&http_path;/is", "/&admin_subdomain;/is", "/&admin_login;/is", "/&admin_password;/is"),
					array($_POST["http_path"], $_POST["admin_subdomain"], $_POST["admin_login"], $_POST["admin_password"]),
					$template_content
					);
	@unlink("config.inc.bak.php");
	@rename("config.inc.php", "config.inc.bak.php");
	@unlink("config.inc.php");
	
	if($_POST["flag_rewrite"]=="disable")  $template_content = str_replace('$MODREWRITE = "enable"','$MODREWRITE = "disable"', $template_content);
	
	$fp = @fopen("config.inc.php", "wb");
	@fwrite($fp, $template_content);
	@fclose($fp);
		
	define("CONFIGCHANGES", 1);
	
	include ("config.inc.php");
}


$http_path = $GLOBALS["HTTP_HOST"]."/";
if(!preg_match("/^http:/is", $http_path)) $http_path = "http://".$http_path;

// Check VirtualHost
$request = $GLOBALS["REQUEST_URI"];
$request = preg_replace("/^http:\/\//is", "", $request);
$request = preg_replace("/^\//is", "", $request);

if( count(split("/", $request)) > 1 ) { 
	$http_path .= preg_replace("/install\.php$/is", "", $request);
	$flag_virtualhost = false; } else  $flag_virtualhost = true;

function check_modrewrite() {
	global $http_path;
	$out = false;
	$fp = @fsockopen ($GLOBALS["HTTP_HOST"], 80, $errno, $errstr, 30);
	if ($fp) {
	    fputs ($fp, "GET ".$http_path."test/ / HTTP/1.0\r\n\r\n");
	    while (!feof($fp)) {
	        $out .= fgets ($fp,128);
	    }
	    fclose ($fp);
	}
	if(preg_match("/200 OK/", $out)) return true; else return false;
}

function put_into_file($filename, $content) {
	global $root_path;
	$fp = @fopen ($root_path.$filename, "wb");
	@fwrite($fp, $content);
	@fclose($fp);
}

// Open access to files/folders
@exec("chmod 777 ".$root_path.".htaccess");
@exec("chmod 777 ".$root_path."scripts/structure.inc.php");
@exec("chmod 777 ".$root_path."dcontent");
@exec("chmod 777 ".$root_path."log");
@exec("chmod 777 ".$root_path."backup");


// Checking SABLOTRON
if( extension_loaded("xslt") ) { define("SABLOTRON", true);  }

$flag_rewrite = check_modrewrite();

if(!$flag_rewrite) {
	$htaccess = '
Options +Followsymlinks
DirectoryIndex index.php 

RewriteEngine On

RewriteCond   %{REQUEST_FILENAME} !-f 
RewriteRule    (.*)/$  '.$http_path.'index.php?%{QUERY_STRING}

ErrorDocument 404 /404error/
';
	put_into_file(".htaccess", $htaccess);
	
	$flag_rewrite = check_modrewrite();
		if(!$flag_rewrite) {
		$htaccess = '
Options +Followsymlinks
DirectoryIndex index.php 
ErrorDocument 404 '.$http_path.'
'; 
		put_into_file(".htaccess", $htaccess);
		}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=$default_charset?>">
    <title>Install</title>
    <LINK REL="stylesheet" TYPE="text/css" HREF="system/default.css.php" TITLE="Style" />
  </head>
  <body bgcolor="#F6F7F7" leftmargin="0" topmargin="0" marginwidth="0">
  
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    	<tr>
    		<td background="system/install_bg.gif"><IMG SRC="system/install_title.gif" WIDTH="225" HEIGHT="51" ALT="BCWB Install" /></td>
    	</tr>

<?

$denied_files = false;

if( !check_permission($root_path.".htaccess") ) $denied_files .= "<tr><td >".$root_path.".htaccess</td><td>777</td></tr>";
if( !check_permission($root_path."scripts/structure.inc.php") ) $denied_files .= "<tr><td >".$root_path."scripts/structure.inc.php</td><td>777</td></tr>";
if( !check_permission($root_path."dcontent") )  $denied_files .= "<tr><td >".$root_path."dcontent</td><td>777</td></tr>";
if( !check_permission($root_path."log") )   $denied_files .= "<tr><td >".$root_path."log</td><td>777</td></tr>";
if( !check_permission($root_path."backup") ) $denied_files .= "<tr><td >".$root_path."backup</td><td>777</td></tr>";
if( !check_permission($root_path."config.inc.php") ) $denied_files .= "<tr><td >".$root_path."config.inc.php</td><td>777</td></tr>";

if($denied_files AND !$_POST) {
?>

    	<tr>
	    	<td align="center">	
	    	<br /><br /><br /><br />
	    	<table border="0" cellspacing="0" cellpadding="2" class="install">
	    		<tr><td colspan="2"><h1><?=$lang["Need_set_permissions"]?></h1><br /></td></tr>
	    	    <?=$denied_files?>
				</table>	    	
	    	</td>
	    </tr>

<?
} elseif (defined("CONFIGCHANGES")) {
?>
    	<tr>
	    	<td align="center">	
	    	<br /><br /><br /><br /><form name="install" method="POST">
	    	<table border="0" cellspacing="0" cellpadding="2" class="install">
	    	    <tr>
	    			<td >	
	    			<h1><?=$lang["Status"]?></h1>
	    			</td>
	    		</tr>
	    	    <tr>
	    			<td align="left" class="install">	
	    			<?=$lang["System_instaled"]?><br />
	    			=> <a href="<?=$http_path.$admin_subdomain?>/"><?=$http_path.$admin_subdomain?>/</a>
	    			</td>
	    		</tr>
    				<tr>
	    			<td >	
	    			<IMG SRC="system/install_line.gif" WIDTH="381" HEIGHT="36" ALT="" />
	    			</td>
	    		</tr>	    		
	    		</table>
	    	</form>
	    	</td>
    	</tr>
<?

} else {
?>
    	<tr>
	    	<td align="center">	
	    	<br /><br /><br /><br /><form name="install" method="POST">
	    	<input type="hidden" name="flag_rewrite" value="<? print ($flag_rewrite?"enable":"disable"); ?>" />
	    	<table border="0" cellspacing="0" cellpadding="2" class="install">
	    	    <tr>
	    			<td colspan="3">	
	    			<h1><?=$lang["Status"]?></h1>
	    			</td>
	    		</tr>
	    	    <tr>
	    			<td  width="190" align="right" class="install">	
	    			Sablotron:
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="<?=(defined("SABLOTRON")?"bold":"alert")?>">	
	    			<?=(defined("SABLOTRON")?$lang["Enable"]:$lang["Disable"])?>
	    			</td>
	    		</tr>

	    	    <tr>
	    			<td  width="190" align="right" class="install">	
	    			ModRewrite:
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="<?=($flag_rewrite?"bold":"alert")?>">	
	    			<? print ($flag_rewrite?$lang["Enable"]:$lang["Disable"]); ?>
	    			</td>
	    		</tr>
	    		
				<tr>
	    			<td  width="190" align="right" class="install">	
	    			VirtualHost:
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="<?=($flag_virtualhost?"bold":"alert")?>">	
	    			<?=($flag_virtualhost?$lang["Enable"]:$lang["Disable"])?>
	    			</td>
	    		</tr>	    			    		
	    	    <tr>
	    			<td colspan="3">	
	    			<IMG SRC="system/install_line.gif" WIDTH="381" HEIGHT="36" ALT="" />
	    			</td>
	    		</tr>
	    	    <tr>
	    			<td colspan="3">	
	    			<h1><?=$lang["Installations"]?></h1>
	    			</td>
	    		</tr>
	    		
	    	    <tr>
	    			<td  width="190" align="right" class="install">	
	    			<?=$lang["URI"]?>
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="bold">	
	    			<input name="http_path" value="<?=$http_path?>" type="text" class="text" />
	    			</td>
	    		</tr>

 	    		<tr>
	    			<td  width="190" align="right" class="install">	
	    			<?=$lang["Admin_area_folder"]?>
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="bold">	
	    			<input name="admin_subdomain" value="<?=$admin_subdomain?>" type="text" class="text" />
	    			</td>
	    		</tr>
	    		

 	    		<tr>
	    			<td  width="190" align="right" class="install">	
	    			Login
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="bold">	
	    			<input name="admin_login" value="<?=$admin_login?>" type="text" class="text" />
	    			</td>
	    		</tr>	    		
	    		
	    		

 	    		<tr>
	    			<td  width="190" align="right" class="install">	
	    			Password
	    			</td>
	    			<td>&#xA0;&#xA0;</td>
	    			<td  width="190" align="left" class="bold">	
	    			<input name="admin_password" value="<?=$admin_password?>" type="password" class="text" />
	    			</td>
	    		</tr>

	    	    <tr>
	    			<td colspan="3" align="center">	
	    				<br />
	    		    	<table border="0" cellspacing="0" cellpadding="0">
	    		    		<tr>
				    			<td style="cursor:hand" onclick="document.install.submit()"><IMG SRC="system/btn101_l.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    			<td align="center" class="install" style="cursor:hand" background="system/btn101_c.gif"><a class="install" href="#" onclick="document.install.submit()"><?=$lang["Save"]?></a></td>
				    			<td style="cursor:hand" onclick="document.install.submit()"><IMG SRC="system/btn101_r.gif" WIDTH="18" HEIGHT="34" ALT="" /></td>
				    		</tr>
				    	</table>
				    	
	    			
	    			</td>
	    		</tr>
	    			    		
	    	    <tr>
	    			<td colspan="3">	
	    			<IMG SRC="system/install_line.gif" WIDTH="381" HEIGHT="36" ALT="" />
	    			</td>
	    		</tr>

	    		</table>
	    	</form>
	    	</td>
    	</tr>   		
<?
}
?>	    		
	    		
    </table>
  </body>
</html>