<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// begin user editable part
// Set to true, if you're useing PHP version 4.1.0 or greater
// uncomment the following line to override automatic detection
$new_global_handling=true;
// end user editable part
//
$booleans=array("false","true");
if(!isset($new_global_handling))
{
	if(phpversion() >= '4.1.0')
		$new_global_handling=true;
	else
		$new_global_handling=false;
}
if(phpversion() < '4.1.0')
{
	$_POST    = &$HTTP_POST_VARS;
	$_GET     = &$HTTP_GET_VARS;
	$_COOKIE  = &$HTTP_COOKIE_VARS;
	$_REQUEST = array_merge($HTTP_COOKIE_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS);
}
while( list($var, $param) = @each($_POST) )
{
	if(!isset($$var))
	{
		$$var=$_POST[$var];
		if(!get_magic_quotes_gpc())
			$$var=addslashes($$var);
	}

}
while( list($var, $param) = @each($_GET) )
{
	if(!isset($$var))
	{
		$$var=$_GET[$var];
		if(!get_magic_quotes_gpc())
			$$var=addslashes($$var);
	}
}
if(!isset($REMOTE_ADDR))
{
	if($new_global_handling)
		$REMOTE_ADDR=$_SERVER["REMOTE_ADDR"];
}
if(!isset($HTTP_USER_AGENT))
{
	if($new_global_handling)
		$HTTP_USER_AGENT=$_SERVER["HTTP_USER_AGENT"];
}
error_reporting  (E_ERROR | E_PARSE); // This will NOT report uninitialized variables
$fileuploadon=true;
$upload_ini=@ini_get('file_uploads');
if(( $upload_ini == '0') || !$upload_ini)
	$fileuploadon=false;
if(strtolower($upload_ini) == 'off')
	$fileuploadon=false;
if(phpversion() == '4.0.4pl1')
	$fileuploadon=false;
if((phpversion() < '4.0.3') && (@ini_get('open_basedir') != ''))
	$fileuploadon=false;
if(extension_loaded("gd"))
	$gdavail=true;
else
	$gdavail=false;
echo "<html><body>";
if(!isset($cfg_lang))
{
?>
<form method="post" action="mkconfig.php">
<div align="center">Please select language/Bitte Sprache ausw&auml;hlen<br>
<input type="radio" name="cfg_lang" value="en">English<br>
<input type="radio" name="cfg_lang" value="de">Deutsch<br>
<input type="submit" name="submit" value="submit"></div></form>
</body></html>
<?php
	exit;
}
if($cfg_lang=="en")
	$msgs=get_en_msgs();
else
	$msgs=get_de_msgs();
if($fileuploadon)
	$fuploadstring=$msgs["enabled"];
else
	$fuploadstring=$msgs["disabled"];
if(!isset($mode))
{
	if($new_global_handling)
		$act_script_url=$_SERVER["PHP_SELF"];
	else
		$act_script_url=$PHP_SELF;
	$snurl=substr($act_script_url,0,strrpos($act_script_url,"/"));
	$snurl=substr($snurl,0,strrpos($snurl,"/"));
?>
<div align="center"><?php echo $msgs["prelude"]?></div>
<br>
<form method="post" action="mkconfig.php">
<input type="hidden" name="cfg_lang" value="<?php echo $cfg_lang?>">
<table align="center" border="1" width="80%">
<tr><td align="right"><?php echo $msgs["dbhost"]?>:</td>
<td><input type="text" name="dbhost" size="40" maxlength="180" value="localhost"></td></tr>
<tr><td align="right"><?php echo $msgs["dbname"]?>:</td>
<td><input type="text" name="dbname" size="40" maxlength="180" value="news"></td></tr>
<tr><td align="right"><?php echo $msgs["dbuser"]?>:</td>
<td><input type="text" name="dbuser" size="40" maxlength="180" value="root"></td></tr>
<tr><td align="right"><?php echo $msgs["dbpwd"]?>:</td>
<td><input type="text" name="dbpasswd" size="40" maxlength="180"></td></tr>
<tr><td align="right"><?php echo $msgs["dbprefix"]?>:</td>
<td><input type="text" name="dbprefix" size="40" maxlength="180" value="simpnews"></td></tr>
<tr><td align="right"><?php echo $msgs["deflang"]?>:</td>
<td><select name="deflang">
<option value="en" <?php if($cfg_lang=="en") echo "selected"?>>English</option>
<option value="de" <?php if($cfg_lang=="de") echo "selected"?>>Deutsch</option>
</select></td></tr>
<tr><td align="right"><?php echo $msgs["admlang"]?>:</td>
<td><select name="admlang">
<option value="en" <?php if($cfg_lang=="en") echo "selected"?>>English</option>
<option value="de" <?php if($cfg_lang=="de") echo "selected"?>>Deutsch</option>
</select></td></tr>
<tr><td align="right"><?php echo $msgs["scripturl"]?>:</td>
<td><input type="text" name="scripturl" size="40" maxlength="180" value="<?php echo $snurl?>"></td></tr>
<tr><td align="right"><?php echo $msgs["scriptpath"]?>:</td>
<td>
<?php
$scriptpath=dirname(getcwd());
$scriptpath=str_replace("\\","/",$scriptpath);
?>
<input type="text" name="scriptpath" size="40" maxlength="180" value="<?php echo $scriptpath?>"></td></tr>
<tr><td align="right" width="40%"><?php echo $msgs["sesstype"]?>:</td>
<td><select name="sesstype">
<option value="cookie"><?php echo $msgs["sesscookie"]?></option>
<option value="url"><?php echo $msgs["sessurl"]?></option>
<option value="htaccess"><?php echo $msgs["htaccess"]?></option>
</select></td></tr>
<tr><td align="right"><?php echo $msgs["sesscookietime"]?>:</td>
<td><input type="text" name="sesscookietime" size="5" maxlength="10" value="600"> <?php echo $msgs["sec"]?></td></tr>
<tr><td align="right"><?php echo $msgs["pwrecov"]?>:</td>
<td><input type="checkbox" name="pwrecov" value="1" checked> <?php echo $msgs["enable"]?></td></tr>
<tr><td align="right"><?php echo $msgs["sitename"]?>:</td>
<?php
if($new_global_handling)
	$myserver=$_SERVER["HTTP_HOST"];
else
	$myserver=$HTTP_SERVER_VARS["HTTP_HOST"];
?>
<td><input type="text" name="sitename" size="40" maxlength="180" value="<?php echo $myserver?>"></td></tr>
<tr><td align="right"><?php echo $msgs["sitedesc"]?>:</td>
<td><input type="text" name="sitedesc" size="40" maxlength="180"></td></tr>
<tr><td align="right"><?php echo $msgs["maxattach"]?>:</td>
<td><input type="text" name="maxattach" size="10" maxlength="20" value="1000000"> Bytes</td></tr>
<tr><td align="right"><?php echo $msgs["attachfs"]?>:</td>
<td><input type="checkbox" name="fsattach" value="1"><?php echo $msgs["enable"]?></td></tr>
<tr><td align="right"><?php echo $msgs["openbasedir"]?>:</td>
<td><input type="checkbox" name="openbasedir" value="1" <?php if(@ini_get('open_basedir') != '') echo "checked"?>><?php echo $msgs["enabled"]?></td></tr>
<tr><td>&nbsp;</td><td><input type="checkbox" name="usingiis" value="1">&nbsp;<?php echo $msgs["using_iis"]?></td></tr>
<tr><td align="right" valign="top"><?php echo $msgs["php_installed"]?>:</td>
<td><select name="used_php_version">
<option value="-1" selected><?php printf($msgs["autodetect"],phpversion())?></option>
<option value="0">&lt;4.1.0</option>
<option value="1">&gt=4.1.0</option>
<option value="2">&gt;=4.2.0</option>
</select></td></tr>
<tr><td align="right" valign="top"><?php echo $msgs["php_file_upload"]?>:</td>
<td><input type="radio" name="is_upload_avail" value="auto" checked><?php printf($msgs["autodetect"],$fuploadstring)?><br>
<input type="radio" name="is_upload_avail" value="true"><?php echo $msgs["enabled"]?><br>
<input type="radio" name="is_upload_avail" value="false"><?php echo $msgs["disabled"]?></td></tr>
<tr><td align="right" valign="top"><?php echo $msgs["haslibgd"]?>:</td>
<td><input type="radio" name="haslibgd" value="-1" checked>
<?php
if($gdavail)
	$tmptxt=$msgs["yes"];
else
	$tmptxt=$msgs["no"];
printf($msgs["autodetect"],$tmptxt)
?>
<br><input type="radio" name="haslibgd" value="1"><?php echo $msgs["yes"]?><br>
<input type="radio" name="haslibgd" value="0"><?php echo $msgs["no"]?></td></tr>
<tr><td align="right" valign="top"><?php echo $msgs["thumbdir"]?>:</td>
<td><input type="text" name="thumbdir" value="thumbs" size="40" maxlength="40"?></td></tr>
<tr><td align="right" valign="top"><?php echo $msgs["srvos"]?>:</td>
<td><input type="radio" name="srvos" value="0" checked> Unix<br>
<input type="radio" name="srvos" value="1">Windows</td></tr>
<tr><td align="right"><?php echo $msgs["realip"]?>:</td>
<td><input type="checkbox" name="realip" value="1"> <?php echo $msgs["enable"]?><br>
<tr><td align="right"><?php echo $msgs["contentcharset"]?>:</td>
<td><input type="text" name="contentcharset" size="40" maxlength="240" value="iso-8859-1"></td></tr>
<tr><td align="right"><?php echo $msgs["encodecharset"]?>:</td>
<td><input type="text" name="encodecharset" size="40" maxlength="240" value="ISO-8859-1"></td></tr>
<tr><td align="right"><?php echo $msgs["smtpmail"]?>:</td>
<td><input type="checkbox" name="smtpmail" value="1"><?php echo $msgs["enabled"]?></td></tr>
<tr><td align="right"><?php echo $msgs["smtpserver"]?>:</td>
<td><input type="text" name="smtpserver" size="40" maxlength="240" value="localhost"></td></tr>
<tr><td align="right"><?php echo $msgs["smtpport"]?>:</td>
<td><input type="text" name="smtpport" size="10" maxlength="10" value="25"></td></tr>
<tr><td align="right"><?php echo $msgs["smtpauth"]?>:</td>
<td><input type="radio" name="smtpauth" value="false" checked> <?php echo $msgs["notneeded"]?><br>
<input type="radio" name="smtpauth" value="true"> <?php echo $msgs["needed"]?></td></tr>
<input type="hidden" name="mode" value="writecfg">
<tr><td align="right"><?php echo $msgs["smtpuser"]?>:</td>
<td><input type="text" name="smtpuser" size="40" maxlength="240"></td></tr>
<tr><td align="right"><?php echo $msgs["smtppwd"]?>:</td>
<td><input type="text" name="smtppwd" size="40" maxlength="240"></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="submit"></tr>
</table></form></body></html>
<?php
}
else
{
	if($mode=="writecfg")
	{
		$errmsg="";
		$dbdataok=true;
		if(!$dbhost)
		{
			$errmsg.="<li> ".$msgs["nodbhost"];
			$dbdataok=false;
		}
		if(!$dbname)
		{
			$errmsg.="<li> ".$msgs["nodbname"];
			$dbdataok=false;
		}
		if(!$scripturl)
		{
			$errmsg.="<li> ".$msgs["noscripturl"];
		}
		if(!$scriptpath)
		{
			$errmsg.="<li> ".$msgs["noscriptpath"];
		}
		if(!$sitename)
		{
			$errmsg.="<li> ".$msgs["nositename"];
		}
		if(!$sesscookietime)
			$sesscookietime=600;
		if(!$contentcharset)
			$contentcharset="iso-8859-1";
		if(!$encodecharset)
			$encodecharset="ISO-8859-1";
		$encodecharset=strtoupper($encodecharset);
		if(!$maxattach)
			$maxattach=1000000;
		if($dbdataok)
		{
			if(!$db = @mysql_connect($dbhost,$dbuser,$dbpasswd))
				$errmsg.="<li>".$msgs["nodbconnect"]."<br>".mysql_error();
			else if(!@mysql_select_db($dbname,$db))
				$errmsg.="<li>".sprintf($msgs["dbnotexists"],$dbname);
		}
		if($errmsg)
		{
			echo "<div align=\"center\">";
			echo $msgs["errors"];
			echo ":<ul>";
			echo $errmsg;
			echo "</ul></div>";
			echo "<div align=\"center\">";
			echo "<a href=\"javascript:history.back()\">".$msgs["back"]."</a>";
			echo "</div></body></html>";
			exit;
		}
		$configfile=$scriptpath."/config.php";
		if(file_exists($configfile))
		{
			if(!is_writeable($configfile))
			{
				printf($msgs["notwriteable"],$configfile);
				echo "<br>".$msgs["correctfirst"];
				exit;
			}
		}
		$cfgfile=fopen($configfile,"w");
		if(!$cfgfile)
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"<?php\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"/***************************************************************************\n * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)\n ***************************************************************************/\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Edit this to fit your needs\n// Begin edit\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// hostname mysql running on\n	\$dbhost = \"$dbhost\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// name of database\n	\$dbname = \"$dbname\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// username for database\n	\$dbuser = \"$dbuser\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// password for database\n	\$dbpasswd = \"$dbpasswd\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// prefix for tables, so you can have multiple instances of\n	// SimpNews in one database (please set before calling install or update)\n	\$tableprefix = \"$dbprefix\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// prefix for hostcache table, if you also use one of our other PHP scripts\n	// you can set all hostcache tables to 1 prefix, so you only have 1 hostcache\n	\$hcprefix= \"$dbprefix\";\n	// prefix for ip banlist table, if you also use one of our other PHP scripts\n	// you can set all banlist tables to 1 prefix, so you only have 1 banlist\n	\$banprefix= \"$dbprefix\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// prefix for badword table, if you also use one of our other PHP scripts\n	// you can set all badword tables to 1 prefix, so you only have 1 badwordlist\n	\$badwordprefix= \"$dbprefix\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// prefix for website leacher table, if you also use one of our other PHP scripts\n	// you can set all leacher tables to 1 prefix, so you only have 1 leacherlist\n	\$leacherprefix= \"$dbprefix\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// default language, you can create your own languagefile\n	// for other languages in ./language\n    \$default_lang = \"$deflang\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // language to use for admininterface\n    \$admin_lang = \"$admlang\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // URL-Path for SimpNews instance. If you use http://www.myhost.com/simpnews\n    // this is /simpnews\n	\$url_simpnews = \"$scripturl\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// URL-Path for graphics (no trailing slash)\n	\$url_gfx = \"".$scripturl."/gfx\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// URL-Path for emoticons (no trailing slash)\n	\$url_emoticons = \"".$scripturl."/gfx/emoticons\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// URL-Path for icons (no trailing slash)\n	\$url_icons = \"".$scripturl."/gfx/icons\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// URL-Path for inline graphics (no trailing slash)\n	\$url_inline_gfx=\"".$scripturl."/gfx/inline\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing simpnews (without trailing slash)\n	\$path_simpnews = \"".$scriptpath."\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing graphics (without trailing slash)\n	\$path_gfx = \"".$scriptpath."/gfx\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing emoticons (without trailing slash)\n	\$path_emoticons = \"".$scriptpath."/gfx/emoticons\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing icons (without trailing slash)\n	\$path_icons = \"".$scriptpath."/gfx/icons\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing inline graphics (without trailing slash)\n	\$path_inline_gfx = \"".$scriptpath."/gfx/inline\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing log files (without trailing slash)\n	\$path_logfiles = \"".$scriptpath."/logs\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// This is the cookie name for storing persistent data for admin interface, you shouldn't have to change it\n	// (for multiple instances use different names)\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	\$admcookiename = \"simpnewsadm\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// It should be safe to leave this alone as well. But if you do change it\n	// make sure you don't set it to a variable already in use (use a seperate\n	// name for each instance of SimpNews)\n	\$cookiename = \"simpnews\";\n	// It should be safe to leave these alone as well.\n	\$cookiepath = \$url_simpnews;\n	\$cookiesecure = false;\n	// This is the cookie name for the sessions cookie, you shouldn't have to change it\n	// (for multiple instances use different names)\n	\$sesscookiename = \"simpnewssession\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// This is the number of seconds that a session lasts for, 3600 == 1 hour.\n	// The session will exprire if the user dosn't view a page on the admininterface\n	// in this amount of time.\n	\$sesscookietime = $sesscookietime;\n"))
			die($msgs["cfgferror"]);
		if($sesstype=="htaccess")
			$htaccess="true";
		else
			$htaccess="false";
		if(!fwrite($cfgfile,"	// Set this to true if you want to use authentication by htacces rather then the\n	// internal version (for details reffer to readme.txt)\n	\$enable_htaccess=$htaccess;\n"))
			die($msgs["cfgferror"]);
		if($sesstype=="url")
			$urlsess="true";
		else
			$urlsess="false";
		if(!fwrite($cfgfile,"	// Set this to true if you want to use sessionid passed throught get and put requests\n	// rathern than by cookie (for details reffer to readme.txt)\n	\$sessid_url=$urlsess;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Please set this to the hostname, where your instance of SimpNews is installed\n	\$simpnewssitename=\"$sitename\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Please provide a short description of the site where SimpNews is installed on\n	\$simpnewssitedesc=\"$sitedesc\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Full URL for SimpNews with trailing slash\n		\$simpnews_fullurl = \"http://\".\$simpnewssitename.\$url_simpnews.\"/\";\n"))
			die($msgs["cfgferror"]);
		if(isset($pwrecov))
			$pwrecover="true";
		else
			$pwrecover="false";
		if(!fwrite($cfgfile,"	// Set to true if you want to have password recovery for admin interface enabled\n	\$enablerecoverpw=$pwrecover;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// maximal filesize for attachements by admin\n	\$maxfilesize=$maxattach;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to the charset to be used as content encoding\n	\$contentcharset=\"$contentcharset\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to the charset to be used for encoding. For available charsets see \"htmlentities\" in PHP manual\n	\$encodecharset=\"$encodecharset\";\n"))
			die($msgs["cfgferror"]);
		if(isset($fsattach))
			$attachfs="true";
		else
			$attachfs="false";
		if(!fwrite($cfgfile,"	// Set this to true, if you want to have attachements stored in file system instead of DB\n	// WARNING: directory with attachements must be world writeable (chmod 777) on most servers\n	// to allow webserver to write to it. Also mention:\n	// * if you delete the attachement only in filesystem,\n	//   the reference in the DB still will be present and point to nothing\n	// * Don't switch between true and false, while there are attachements in database\n	//   migrate them in admin interface first and then switch here\n	\$attach_in_fs=$attachfs;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to true, if you have permission problems with uploaded attachements\n	\$attach_do_chmod=false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to the permission mask to use, if \$attach_do_chmod is enabled\n	\$attach_fmode=0644;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory, where attachements should be stored\n	\$path_attach = \"".$scriptpath."/attachements\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// url to directory with attachements\n	\$url_attach = \"".$scripturl."/attachements\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory, where uploaded files should temporary be copied to\n	// (PHP has to have write permissions on this directory)\n	// Needed if open_basedir restriction is in effect. If your PHP instance can\n	// access the default upload directory, you don't need to set it\n"))
			die($msgs["cfgferror"]);
		if(isset($openbasedir))
		{
			if(!fwrite($cfgfile,"	\$path_tempdir = \"".$scriptpath."/admin/temp\";\n"))
				die($msgs["cfgferror"]);
		}
		else
		{
			if(!fwrite($cfgfile,"//	\$path_tempdir = \"".$scriptpath."/admin/temp\";\n"))
				die($msgs["cfgferror"]);
		}
		if(isset($realip))
			$userealip="true";
		else
			$userealip="false";
		if($haslibgd<0)
			$libgdavail=$booleans[$gdavail];
		else if($haslibgd==1)
			$libgdavail="true";
		else
			$libgdavail="false";
		if(!fwrite($cfgfile,"	// set this to true, if the installation of PHP used provides support for libGD\n	// uncomment next line, if you want to override autodetection for this setting\n"))
			die($msgs["cfgferror"]);
		if($haslibgd<0)
			$tmpline="//";
		else
			$tmpline="";
		$tmpline.="	\$gdavail=$libgdavail;\n";
		if(!fwrite($cfgfile,$tmpline))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// subdirname used for storing thumbnails for inline gfx\n	\$thumbdir=\"$thumbdir\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set to true if you want to try to get the real IP of the user.\n	// Please note: This may not work for all HTTPDs.\n	\$try_real_ip=$userealip;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Method for trying real IP (0=default or 1=alternate method)\n	// if determining IP with default mode fails within your server environment, maybe\n	// alternate method works\n	\$try_real_ip_mode=0;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// please enter all fileextension your server uses for PHP scripts here\n	\$php_fileext=array(\"php\",\"php3\",\"phtml\",\"php4\");\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Here the association between used language names and system locales are defined.\n	// If you add an additional language, also provide the appropriate locale here\n	// Windows systems\n"))
			die($msgs["cfgferror"]);
		if($srvos==1)
		{
			if(!fwrite($cfgfile,"	\$def_locales=array(\"de\"=>\"german\",\"en\"=>\"english\");\n"))
				die($msgs["cfgferror"]);
		}
		else
		{
			if(!fwrite($cfgfile,"	//	\$def_locales=array(\"de\"=>\"german\",\"en\"=>\"english\");\n"))
				die($msgs["cfgferror"]);
		}
		if(!fwrite($cfgfile,"	// Unix systems\n"))
			die($msgs["cfgferror"]);
		if($srvos==0)
		{
			if(!fwrite($cfgfile,"	\$def_locales=array(\"de\"=>\"de_DE\",\"en\"=>\"en_EN\");\n"))
				die($msgs["cfgferror"]);
		}
		else
		{
			if(!fwrite($cfgfile,"//	\$def_locales=array(\"de\"=>\"de_DE\",\"en\"=>\"en_EN\");\n"))
				die($msgs["cfgferror"]);
		}
		if(isset($smtpmail))
			$usesmtp="true";
		else
			$usesmtp="false";
		if(!fwrite($cfgfile,"    // For sending emails through SMTP server instead of PHP mail function set this to true\n	\$use_smtpmail = $usesmtp;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // SMTP Server to use\n    \$smtpserver = \"$smtpserver\";\n    // SMTP Port\n    \$smtpport = $smtpport;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // SMTP Server needs authentication\n    \$smtpauth = $smtpauth;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // Authentication username\n    \$smtpuser = \"$smtpuser\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Authentication password\n	\$smtppasswd = \"$smtppwd\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// minimal fontsize to use for dropdown BBcode button\n	\$minfontsize=-10;\n	// maximal fontsize to use for dropdown BBcode button\n	\$maxfontsize=10;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set to false if file upload is disabled in php.ini\n	// uncomment next line, if you want to override autodetection for this setting\n"))
			die($msgs["cfgferror"]);
		if($is_upload_avail=="auto")
			$tmpline="//	\$upload_avail=".$booleans[$fileuploadon].";\n";
		else
			$tmpline="	\$upload_avail=$is_upload_avail;\n";
		if(!fwrite($cfgfile,$tmpline))
			die($msgs["cfgferror"]);
		if($used_php_version<0)
			$input_new_global_handling=$booleans[$new_global_handling];
		else if($used_php_version>0)
			$input_new_global_handling="true";
		else
			$input_new_global_handling="false";
		if($used_php_version<0)
		{
			if(phpversion() >= '4.2.0')
				$input_has_fileerrors="true";
			else
				$input_has_fileerrors="false";
		}
		else if($used_php_version>1)
			$input_has_fileerrors="true";
		else
			$input_has_fileerrors="false";
		if(!fwrite($cfgfile,"	// set this to true, if you are using PHP 4.1.0 or greater (has to be set to true for\n	// PHP 4.2.0 or greater)\n	// uncomment next line, if you want to override autodetection for this setting\n"))
			die($msgs["cfgferror"]);
		if($used_php_version<0)
			$tmpline="//";
		else
			$tmpline="";
		$tmpline.="	\$new_global_handling=$input_new_global_handling;\n";
		if(!fwrite($cfgfile,$tmpline))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set this to true, if you are using PHP 4.2.0 or greater\n	// uncomment next line, if you want to override autodetection for this setting\n"))
			die($msgs["cfgferror"]);
		if($used_php_version<0)
			$tmpline="//";
		else
			$tmpline="";
		$tmpline.="	\$has_file_errors=$input_has_fileerrors;\n";
		if(!fwrite($cfgfile,$tmpline))
			die($msgs["cfgferror"]);
		if(isset($usingiis))
			$iisworkaround="true";
		else
			$iisworkaround="false";
		if(!fwrite($cfgfile,"	// set this to true, if you are using PHP with MS IIS on Windows\n	\$iis_workaround=$iisworkaround;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// If there is a conflict with the HTTP variable \"lang\", because e.g. your CMS needs it\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set this to some other value. The calling URLs then not will be URL?lang=..., but\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// URL\?<setvalue>=...\n	\$langvar=\"lang\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // Type of new line to be used (\\r\\n = CRLF, \\r=CR, \\n=LF)\n	\$crlf=\"\\r\\n\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to the time in sec., you'll allow for log running scripts until they should timeout\n	\$longrunner=1800;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to true, if userview should send HTTP headers to prevent caching of pages\n	\$nocaching=false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to true, if you get cached pages in admin interface\n	\$admoldhdr=false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set this to true, if you want to disable the security check (config.php writeable...)\n//	\$noseccheck=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// leaving this to false is the best\n    \$testmode = false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// If you want to have the script remove the vars registered on register_globals=ob uncomment the next line\n//	\$dosafephp=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// If you want to override checking for register_globals=off uncomment the next line\n	// \$no_rgcheck=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"// end edit\n// you are not allowed to edit beyond this point\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	\$fid=\"b073e76b30344b970271303a892b1d91\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	require_once(\$path_simpnews.'/includes/global.inc');\n?>"))
			die($msgs["cfgferror"]);
		fclose($cfgfile);
		@chmod($configfile,0444);
		$notes="";
		if(!file_exists($scriptpath."/gfx/emoticons"))
		{
			$notes.="<li>".sprintf($msgs["notexists"],$scriptpath."/gfx/emoticons");
			$notes.="<br><input type=\"checkbox\" name=\"dircreate[]\" value=\"".$scriptpath."/gfx/emoticons\"> ".$msgs["correct"];
		}
		else
		{
			if(!is_writeable($scriptpath."/gfx/emoticons"))
			{
				$notes.="<li>".sprintf($msgs["notwriteable"],$scriptpath."/gfx/emoticons");
				$notes.="<br>".sprintf($msgs["uploadnotworking"],"Smilies");
				$notes.="<br><input type=\"checkbox\" name=\"dirmod[]\" value=\"".$scriptpath."/gfx/emoticons\"> ".$msgs["correct"];
			}
		}
		if(!file_exists($scriptpath."/gfx/icons"))
		{
			$notes.="<li>".sprintf($msgs["notexists"],$scriptpath."/gfx/icons");
			$notes.="<br><input type=\"checkbox\" name=\"dircreate[]\" value=\"".$scriptpath."/gfx/icons\"> ".$msgs["correct"];
		}
		else
		{
			if(!is_writeable($scriptpath."/gfx/icons"))
			{
				$notes.="<li>".sprintf($msgs["notwriteable"],$scriptpath."/gfx/icons");
				$notes.="<br>".sprintf($msgs["uploadnotworking"],"icons");
				$notes.="<br><input type=\"checkbox\" name=\"dirmod[]\" value=\"".$scriptpath."/gfx/icons\"> ".$msgs["correct"];
			}
		}
		if(isset($fsattach))
		{
			if(!file_exists($scriptpath."/attachements"))
			{
				$notes.="<li>".sprintf($msgs["notexists"],$scriptpath."/attachements");
				$notes.="<br>".sprintf($msgs["uploadnotworking"],$msgs["attachements"]);
				$notes.="<br><input type=\"checkbox\" name=\"dircreate[]\" value=\"".$scriptpath."/attachements\"> ".$msgs["correct"];
			}
			else
			{
				if(!is_writeable($scriptpath."/attachements"))
				{
					$notes.="<li>".sprintf($msgs["notwriteable"],$scriptpath."/attachements");
					$notes.="<br>".sprintf($msgs["uploadnotworking"],$msgs["attachements"]);
					$notes.="<br><input type=\"checkbox\" name=\"dirmod[]\" value=\"".$scriptpath."/attachements\"> ".$msgs["correct"];
				}
			}
		}
		if(isset($openbasedir))
		{
			if(!file_exists($scriptpath."/admin/temp"))
			{
				$notes.="<li>".sprintf($msgs["notexists"],$scriptpath."/admin/temp");
				$notes.="<br>".sprintf($msgs["uploadnotworking"],$msgs["files"]);
				$notes.="<br><input type=\"checkbox\" name=\"dircreate[]\" value=\"".$scriptpath."/admin/temp\"> ".$msgs["correct"];
			}
			else
			{
				if(!is_writeable($scriptpath."/admin/temp"))
				{
					$notes.="<li>".sprintf($msgs["notwriteable"],$scriptpath."/admin/temp");
					$notes.="<br>".sprintf($msgs["uploadnotworking"],$msgs["files"]);
					$notes.="<br><input type=\"checkbox\" name=\"dirmod[]\" value=\"".$scriptpath."/admin/temp\"> ".$msgs["correct"];
				}
			}
		}
		echo "<div align=\"center\">";
		echo $msgs["cfgwritten"];
		echo "</div>";
		if($notes)
		{
			echo "<br><br><div align=\"center\"><form method=\"post\" action=\"mkconfig.php\">";
			echo "<input type=\"hidden\" name=\"cfg_lang\" value=\"$cfg_lang\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"correct\">";
			echo $msgs["notes"].":<ul>";
			echo $notes;
			echo "</ul>";
			echo "<input type=\"submit\" value=\"OK\"></form>";
			echo "</div>";
		}
		echo "<div align=\"center\">";
		echo $msgs["runinstall"];
		echo "</div>";
		echo "</body></html>";
	}
	if($mode=="correct")
	{
		echo "<div align=\"center\"><ul>";
		if(isset($dirmod))
		{
			while(list($null, $actdir) = each($_POST["dirmod"]))
			{
				chmod($actdir,0777);
				if(!$testfile=fopen($actdir."/tst.txt","w"))
				{
					echo "<li>".sprintf($msgs["notwriteable"],$actdir);
					echo "<br>".$msgs["cantcorrect"];
				}
				else
				{
					fclose($testfile);
					unlink($actdir."/tst.txt");
					echo "<li>".sprintf($msgs["corrected"],$actdir);
				}
			}
		}
		if(isset($dircreate))
		{
			while(list($null, $actdir) = each($_POST["dircreate"]))
			{
				mkdir($actdir,0777);
				if(!is_writeable($actdir))
				{
					echo "<li>".sprintf($msgs["notwriteable"],$actdir);
					echo "<br>".$msgs["cantcorrect"];
				}
				else
				{
					echo "<li>".sprintf($msgs["corrected"],$actdir);
				}
			}
		}
		echo "</ul></div>";
	}
}
function get_en_msgs()
{
	$msgs=array(
		"prelude"=>"Create config.php for SimpNews.<br>In order for this script to work, PHP has to have write access to the parent directory.",
		"dbhost"=>"Databasehost",
		"dbname"=>"Databasename (DB allready has to exist)",
		"dbuser"=>"Databaseuser",
		"dbpwd"=>"Databasepassword",
		"dbprefix"=>"Tableprefix",
		"deflang"=>"default language",
		"admlang"=>"default language for admin interface",
		"scripturl"=>"URL where scripts reside",
		"scriptpath"=>"path where scripts reside",
		"sesstype"=>"type of sessionhandling",
		"sesscookie"=>"using cookie",
		"sessurl"=>"POST/GET",
		"htaccess"=>"by HTTPD (htaccess)",
		"sitename"=>"Website name",
		"cfgferror"=>"error creating config.php",
		"enable"=>"enable",
		"sesscookietime"=>"Timeout for session cookie in admin interface",
		"sec"=>"seconds",
		"pwrecov"=>"password recovery",
		"maxattach"=>"max. filesize for attachements by admin",
		"contentcharset"=>"Charset for content encoding",
		"encodecharset"=>"Charset for HTML encoding (see <a href=\"http://http://www.php.net/manual/en/function.htmlentities.php\">htmlentities</a>)",
		"cfgwritten"=>"config.php generated",
		"attachfs"=>"Store attachements in filesystem and not DB",
		"openbasedir"=>"open_basedir restriction for PHP is",
		"enabled"=>"enabled",
		"smtpmail"=>"Send emails through SMTP server rather than PHP mail function",
		"smtpserver"=>"Name of SMTP server",
		"smtpport"=>"Port for SMTP connection",
		"smtpauth"=>"Authentication to send emails through SMTP server",
		"needed"=>"needed",
		"notneeded"=>"not needed",
		"smtpuser"=>"username for SMTP server",
		"smtppwd"=>"password for SMTP server",
		"notes"=>"Notes",
		"notexists"=>"%s does not exist.",
		"notwriteable"=>"%s is not writeable by PHP.",
		"uploadnotworking"=>"Upload of %s will not work.",
		"correct"=>"try to correct problem",
		"cantcorrect"=>"Couldn't be corrected. Please correct by hand.",
		"corrected"=>"Problem with %s corrected.",
		"files"=>"files",
		"attachements"=>"attachements",
		"correctfirst"=>"Please correct this and submit form again.",
		"realip"=>"Try do determine real IP of users",
		"php_installed"=>"Installed version of PHP",
		"php_file_upload"=>"File upload throught PHP",
		"disabled"=>"disabled",
		"srvos"=>"OS on server",
		"haslibgd"=>"Support for libGD available",
		"thumbdir"=>"Subdirectory for storing thumbnails",
		"sitedesc"=>"short description for website",
		"yes"=>"yes",
		"no"=>"no",
		"runinstall"=>"Now you can run <a href=\"install.php\">install.php</a> to create database tables",
		"nodbconnect"=>"PHP is unable to connect to database server, please check connection settings and try again",
		"dbnotexists"=>"Database <i>%s</i> does not exist (PHP was unable to open database)",
		"autodetect"=>"automatic detection (%s)",
		"nodbhost"=>"You have to provide a database hostname",
		"errors"=>"There were errors processing your data, please correct",
		"back"=>"back",
		"nodbname"=>"You have to provide a database name",
		"noscripturl"=>"You have to provide the URL where scripts reside",
		"noscriptpath"=>"You have to provide the path where the scripts reside",
		"nositename"=>"You have to provide the hostname of the website",
		"cfgprotected"=>"config.php is write protected. Please change and run again.",
		"using_iis"=>"I'm using IIS on Windows as webserver"
	);
	return $msgs;
}
function get_de_msgs()
{
	$msgs=array(
		"prelude"=>"Erzeugen der config.php f&uuml;r SimpNews.<br>Damit dieses Skript die Datei erstellen kann, ben&ouml;tigt PHP Schreibzugriff auf das &uuml;bergeordnete Verzeichnis.",
		"dbhost"=>"Hostname des Datenbankservers",
		"dbname"=>"Datenbankname (Datenbank muss bereits existieren)",
		"dbuser"=>"Benutzername f&uuml;r Datenbank",
		"dbpwd"=>"Passwort f&uuml;r Datenbank",
		"dbprefix"=>"Pr&auml;fix f&uuml;r Datenbanktabellen",
		"deflang"=>"voreingestellte Sprache",
		"admlang"=>"voreingestellte Sprache f&uuml;r Administrationsoberfl&auml;che",
		"scripturl"=>"URL der Skripte",
		"scriptpath"=>"Verzeichnis, in dem die Skripte liegen",
		"sesstype"=>"Art des Sessionhandlings",
		"sesscookie"=>"per Cookie",
		"sessurl"=>"POST/GET",
		"htaccess"=>"durch den Webserver (htaccess)",
		"sitename"=>"Name der Website",
		"cfgferror"=>"Fehler beim Erzeugen der config.php",
		"enable"=>"aktivieren",
		"sesscookietime"=>"Timeout f&uuml;r das Sessioncookie in der Administrationsoberfl&auml;che",
		"sec"=>"Sekunden",
		"pwrecov"=>"Passwortermittlung",
		"maxattach"=>"max. Filegr&ouml;sse f&uuml;r Dateianh&auml;nge der Admins",
		"contentcharset"=>"Contentcharset",
		"encodecharset"=>"F&uuml;r HTML-Kodierung zu verwendender Zeichensatz (siehe <a href=\"http://http://www.php.net/manual/de/function.htmlentities.php\">htmlentities</a>)",
		"cfgwritten"=>"config.php erzeugt",
		"attachfs"=>"Dateianh&auml;nge im Dateisystem und nicht in der Datenbank ablegen",
		"openbasedir"=>"Die open_basedir Einschr&auml;nkung f&uuml;r PHP ist",
		"enabled"=>"aktiviert",
		"smtpmail"=>"E-Mails an Stelle der PHP Mailfunktion &uuml;ber SMTP-Server versenden",
		"smtpserver"=>"Name des SMTP-Servers",
		"smtpport"=>"Port f&uuml;r SMTP-Verbindung",
		"smtpauth"=>"Authentifizierung, um E-Mails &uuml;ber den SMTP-Server zu versenden",
		"needed"=>"ben&ouml;tigt",
		"notneeded"=>"nicht ben&ouml;tigt",
		"smtpuser"=>"Benutzername zur Anmeldung am SMTP-Server",
		"smtppwd"=>"Passwort zur Anmledung am SMTP-Server",
		"notes"=>"Anmerkungen",
		"notexists"=>"%s existiert nicht.",
		"notwriteable"=>"PHP hat keine Schreibrechte auf %s.",
		"uploadnotworking"=>"Der Upload von %s funktioniert dann nicht.",
		"correct"=>"versuchen das Problem zu beheben",
		"cantcorrect"=>"Problem konnte nicht behoben werden. Bitte von Hand beheben",
		"corrected"=>"Problem mit %s behoben",
		"files"=>"Dateien",
		"attachements"=>"Dateianh&auml;ngen",
		"correctfirst"=>"Bitte beheben und Formular erneut senden.",
		"realip"=>"Versuchen die echte IP-Adresse von Benutzern zu ermitteln",
		"php_installed"=>"Installierte PHP-Version",
		"php_file_upload"=>"Fileupload in PHP",
		"disabled"=>"deaktiviert",
		"srvos"=>"Serverplattform",
		"haslibgd"=>"LibGD verf&uuml;gbar",
		"thumbdir"=>"Unterverzeichnis f&uuml;r Thumbnails",
		"sitedesc"=>"Kurzbeschreibung der Website",
		"yes"=>"ja",
		"no"=>"nein",
		"autodetect"=>"automatische Ermittlung (%s)",
		"using_iis"=>"Es wird der MS IIS unter Windows als Webserver benutzt",
		"runinstall"=>"Nun k&ouml;nnen Sie <a href=\"install.php\">install.php</a> aufrufen, um die Datenbanktabellen zu erzeugen",
		"nodbconnect"=>"PHP konnte keine Verbindung zum Datenbankserver herstellen. Bitte &uuml;berpr&uuml;fen Sie die Verbindungseinstellungen.",
		"dbnotexists"=>"Datenbank <i>%s</i> existiert nicht (PHP konnte die Datenbank nicht &ouml;ffnen)",
		"cfgprotected"=>"Die config.php ist schreibgesch&uuml;tzt. Bitte korrigieren und neu starten.",
		"errors"=>"Bei der Verarbeitung der Daten sind Fehler aufgetreten, bitte korigieren",
		"back"=>"zur&uuml;ck",
		"nodbname"=>"Sie m&uuml;ssen einen Datenbanknamen angeben.",
		"noscripturl"=>"Sie m&uuml;ssen die URL der Skripts angeben.",
		"noscriptpath"=>"Sie m&uuml;ssen das Verzeichnis angeben, in dem die Skripts liegen.",
		"nositename"=>"Sie m&uuml;ssen den Hostnamen der Website angeben.",
		"nodbhost"=>"Sie m&uuml;ssen einen Datenbanksever angeben."
	);
	return $msgs;
}
?>