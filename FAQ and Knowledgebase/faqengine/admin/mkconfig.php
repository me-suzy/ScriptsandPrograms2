<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// begin user editable part
// Set to true, if you're using PHP version 4.1.0 or greater
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

echo "<html><body>";
if(!isset($cfglang))
{
?>
<form method="post" action="mkconfig.php">
<div align="center">Please select language/Bitte Sprache ausw&auml;hlen<br>
<input type="radio" name="cfglang" value="en">English<br>
<input type="radio" name="cfglang" value="de">Deutsch<br>
<input type="submit" value="submit"></div></form>
</body></html>
<?php
	exit;
}
if($cfglang=="en")
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
	$faqeurl=substr($act_script_url,0,strrpos($act_script_url,"/"));
	$faqeurl=substr($faqeurl,0,strrpos($faqeurl,"/"));
	if(count(language_list("./language"))<1)
		die($msgs["no_langs"]);
	if(count(language_list("../language"))<1)
		die($msgs["no_langs"]);
?>
<div align="center"><?php echo $msgs["prelude"]?></div>
<br>
<form method="post" action="mkconfig.php">
<input type="hidden" name="cfglang" value="<?php echo $cfglang?>">
<table align="center">
<tr><td align="right"><?php echo $msgs["dbhost"]?>:</td>
<td><input type="text" name="dbhost" size="40" maxlength="180" value="localhost"></td></tr>
<tr><td align="right"><?php echo $msgs["dbname"]?>:</td>
<td><input type="text" name="dbname" size="40" maxlength="180" value="faq"></td></tr>
<tr><td align="right"><?php echo $msgs["dbuser"]?>:</td>
<td><input type="text" name="dbuser" size="40" maxlength="180" value="root"></td></tr>
<tr><td align="right"><?php echo $msgs["dbpwd"]?>:</td>
<td><input type="text" name="dbpwd" size="40" maxlength="180"></td></tr>
<tr><td align="right"><?php echo $msgs["dbprefix"]?>:</td>
<td><input type="text" name="dbprefix" size="40" maxlength="180" value="faq"></td></tr>
<tr><td align="right"><?php echo $msgs["deflang"]?>:</td>
<td><?php echo language_select($cfglang,"deflang","../language")?></td></tr>
<tr><td align="right"><?php echo $msgs["admlang"]?>:</td>
<td><?php echo language_select($cfglang,"admlang","./language")?></td></tr>
<tr><td align="right"><?php echo $msgs["scripturl"]?>:</td>
<td><input type="text" name="scripturl" size="40" maxlength="180" value="<?php echo $faqeurl?>"></td></tr>
<tr><td align="right"><?php echo $msgs["scriptpath"]?>:</td>
<td>
<?php
$scriptpath=dirname(getcwd());
$scriptpath=str_replace("\\","/",$scriptpath);
?>
<input type="text" name="scriptpath" size="40" maxlength="180" value="<?php echo $scriptpath?>"></td></tr>
<tr><td align="right"><?php echo $msgs["lastvisitcookie"]?>:</td>
<td><input type="checkbox" name="lastvisitcookie" value="1"> <?php echo $msgs["enable"]?></td></tr>
<tr><td align="right"><?php echo $msgs["sesstype"]?>:</td>
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
<tr><td>&nbsp;</td><td><input type="checkbox" name="usingiis" value="1">&nbsp;<?php echo $msgs["using_iis"]?></td></tr>
<tr><td align="right" valign="top"><?php echo $msgs["php_installed"]?>:</td>
<td><select name="used_php_version">
<option value="-1" selected><?php printf($msgs["autodetect"],phpversion())?></option>
<option value="0">&lt;4.1.0</option>
<option value="1">&gt=4.1.0</option>
<option value="2">&gt;=4.2.0</option>
</select>
<tr><td align="right" valign="top"><?php echo $msgs["php_file_upload"]?>:</td>
<td><input type="radio" name="is_upload_avail" value="auto" checked><?php printf($msgs["autodetect"],$fuploadstring)?><br>
<input type="radio" name="is_upload_avail" value="true"><?php echo $msgs["enabled"]?><br>
<input type="radio" name="is_upload_avail" value="false"><?php echo $msgs["disabled"]?></td></tr>
<tr><td align="right"><?php echo $msgs["maxattach"]?>:</td>
<td><input type="text" name="maxattach" size="10" maxlength="20" value="1000000"> Bytes</td></tr>
<tr><td align="right"><?php echo $msgs["attachfs"]?>:</td>
<td><input type="checkbox" name="fsattach" value="1"><?php echo $msgs["enable"]?></td></tr>
<tr><td align="right"><?php echo $msgs["contentcharset"]?>:</td>
<td><input type="text" name="contentcharset" size="40" maxlength="240" value="iso-8859-1"></td></tr>
<tr><td align="right"><?php echo $msgs["encodecharset"]?>:</td>
<td><input type="text" name="encodecharset" size="40" maxlength="240" value="ISO-8859-1"></td></tr>
<tr><td align="right"><?php echo $msgs["smtpmail"]?>:</td>
<td><input type="checkbox" name="smtpmail" value="1"><?php echo $msgs["enabled"]?></td></tr>
<tr><td align="right"><?php echo $msgs["smtpserver"]?>:</td>
<td><input type="text" name="smtpserver" size="40" maxlength="240" value="localhost"></td></tr>
<tr><td align="right"><?php echo $msgs["smtpauth"]?>:</td>
<td><input type="radio" name="smtpauth" value="false" checked> <?php echo $msgs["notneeded"]?><br>
<input type="radio" name="smtpauth" value="true"> <?php echo $msgs["needed"]?></td></tr>
<input type="hidden" name="mode" value="writecfg">
<tr><td align="right"><?php echo $msgs["smtpuser"]?>:</td>
<td><input type="text" name="smtpuser" size="40" maxlength="240"></td></tr>
<tr><td align="right"><?php echo $msgs["smtppwd"]?>:</td>
<td><input type="text" name="smtppwd" size="40" maxlength="240"></td></tr>
<input type="hidden" name="mode" value="writecfg">
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
			if(!$db = @mysql_connect($dbhost,$dbuser,$dbpwd))
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
		$dbprefix=str_replace("-","_",$dbprefix);
		$dbprefix=str_replace("+","_",$dbprefix);
		$configfile=$scriptpath."/config.php";
		if(file_exists($configfile))
		{
			if(!is_writeable($configfile))
				die($msgs["cfgprotected"]);
		}
		$cfgfile=fopen($configfile,"w");
		if(!$cfgfile)
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"<?php\n// Edit this to fit your needs\n// Begin edit\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// hostname mysql running on\n	\$dbhost = \"$dbhost\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// name of database\n	\$dbname = \"$dbname\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// username for database\n	\$dbuser = \"$dbuser\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// password for databaseuser\n	\$dbpasswd = \"$dbpwd\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// prefix for tables, so you can have multiple instances of\n	// FAQEngine in one database (please set before calling install or update)\n	// Note for all prefix entries: only use a-z and 0-9, no special characters\n	// (especially -, +, /, \\)\n	\$tableprefix = \"$dbprefix\";\n"))
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
		if(!fwrite($cfgfile,"    // URL-Path for FAQEngine instance (no trailing slash !). If you use http://www.myhost.com/faq\n    // this is /faq\n	\$url_faqengine = \"$scripturl\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// URL-Path for graphics to include in FAQs (no trailing slash !)\n	\$url_gfx = \"".$scripturl."/gfx/inline\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing FAQEngine (without trailing slash)\n	\$path_faqe = \"".$scriptpath."\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing graphics to include in FAQs (without trailing slash)\n	\$path_gfx = \"".$scriptpath."/gfx/inline\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// complete path to directory containing log files (without trailing slash)\n	\$path_logfiles = \"".$scriptpath."/logs\";\n"))
			die($msgs["cfgferror"]);
		if(isset($lastvisitcookie))
			$lvcookie="true";
		else
			$lvcookie="false";
		if(!fwrite($cfgfile,"	// Set this to true, if last visitdate of user should be stored on his\n	// computer and only faqs newer than this date should be displayed\n	\$usevisitcookie = $lvcookie;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// It should be safe to leave this alone as well. But if you do change it\n	// make sure you don't set it to a variable already in use (use a seperate\n	// name for each instance of FAQEngine)\n	\$cookiename = \"faqengine\";\n	// It should be safe to leave these alone as well.\n	\$cookiepath = \$url_faqengine;\n	\$cookiesecure = false;\n	// This is the cookie name for the sessions cookie, you shouldn't have to change it\n	// (for multiple instances use different names)\n	\$sesscookiename = \"faqenginesession\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// This is the cookie name for storing persistent data for admin interface, you shouldn't have to change it\n	// (for multiple instances use different names)\n	\$admcookiename = \"faqengineadm\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// This is the number of seconds that a session lasts for, 3600 == 1 hour.\n	// The session will exprire if the user dosn't view a page on the admininterface\n	// in this amount of time.\n	\$sesscookietime = $sesscookietime;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// If there is a conflict with the HTTP variable \"lang\", because e.g. your CMS needs it\n	// set this to some other value. The calling URLs then not will be URL?lang=..., but\n	// URL?<setvalue>=...\n	\$langvar=\"lang\";\n"))
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
		if(!fwrite($cfgfile,"	// Please set this to the hostname, where your instance of FAQEngine is installed\n	\$faqsitename=\"$sitename\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Please provide a short description of the site where FAQEngine is installed on\n	\$faqsitedesc=\"$sitedesc\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Protocol used to access FAQEngine\n	\$faqe_prot=\"http\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// full url (incl. protocol) to main directory of FAQEngine\n	\$faqe_fullurl=\$faqe_prot.\"://\".\$faqsitename.\$url_faqengine;\n"))
			die($msgs["cfgferror"]);
		if(isset($pwrecov))
			$pwrecover="true";
		else
			$pwrecover="false";
		if(!fwrite($cfgfile,"	// Set to true if you want to have password recovery for admin interface enabled\n	\$enablerecoverpw=$pwrecover;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// maximal filesize for attachements by admin\n	\$maxfilesize=$maxattach;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// please enter all fileextension your server uses for PHP scripts here\n	\$php_fileext=array(\"php\",\"php3\",\"phtml\",\"php4\");\n"))
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
		if(!fwrite($cfgfile,"	// minimal fontsize to use for dropdown BBcode button\n	\$minfontsize=-10;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// maximal fontsize to use for dropdown BBcode button\n	\$maxfontsize=10;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set DB engine to be used:\n	// 1 = mySQL\n	\$dbengine=1;\n"))
			die($msgs["cfgferror"]);
		if(isset($smtpmail))
			$usesmtp="true";
		else
			$usesmtp="false";
		if(!fwrite($cfgfile,"    // For sending emails through SMTP server instead of PHP mail function set this to true\n	\$use_smtpmail = $usesmtp;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // SMTP Server to use\n    \$smtpserver = \"$smtpserver\";\n    // SMTP Port\n    \$smtpport = 25;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // SMTP Server needs authentication\n    \$smtpauth = $smtpauth;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // Authentication username\n    \$smtpuser = \"$smtpuser\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Authentication password\n	\$smtppasswd = \"$smtppwd\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to true, if you get cached pages in admin interface\n	\$admoldhdr=false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set this to true, if dropdown menu doesn't work in your browser (switching may\n	// resolve the problem on some browsers)\n	\$alt_admmenu=false;\n"))
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
		if(!fwrite($cfgfile,"	// Set this to true, if you're trying to access admin interface through a proxy cluster\n	// (so the acessing IP changes within session)\n	\$try_real_ip=false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Method for trying real IP (0=default or 1=alternate method)\n	// if determining IP with default mode fails within your server environment, maybe\n	// alternate method works\n	\$try_real_ip_mode=0;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// Set this to the time in sec., you'll allow for log running scripts until they should timeout\n	\$longrunner=1800;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// leaving this to false is the best\n	\$testmode = false;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"    // Type of new line to be used (\\r\\n = CRLF, \\r=CR, \\n=LF)\n	\$crlf=\"\\r\\n\";\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set this to true, if passwords for failed logins shouldn't be tracked\n	\$failednopw=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// set this to true, if you want to disable the security check (config.php writeable...)\n//	\$noseccheck=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// If you want to have the script remove the vars registered on register_globals=ob uncomment the next line\n//	\$dosafephp=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"	// If you want to override checking for register_globals=off uncomment the next line\n	// \$no_rgcheck=true;\n"))
			die($msgs["cfgferror"]);
		if(!fwrite($cfgfile,"// end edit\n// you are not allowed to edit beyond this point\n	require_once(\$path_faqe.\"/includes/global.inc\");\n?>"))
			die($msgs["cfgferror"]);
		fclose($cfgfile);
		$notes="";
		@chmod($configfile,0444);
		if(!file_exists($scriptpath."/gfx/inline"))
		{
			$notes.="<li>".sprintf($msgs["notexists"],$scriptpath."/gfx/inline");
			$notes.="<br><input type=\"checkbox\" name=\"dircreate[]\" value=\"".$scriptpath."/gfx/inline\"> ".$msgs["correct"];
		}
		else
		{
			if(!is_writeable($scriptpath."/gfx/inline"))
			{
				$notes.="<li>".sprintf($msgs["notwriteable"],$scriptpath."/gfx/inline");
				$notes.="<br>".sprintf($msgs["uploadnotworking"],$msgs["gfx"]);
				$notes.="<br><input type=\"checkbox\" name=\"dirmod[]\" value=\"".$scriptpath."/gfx/inline\"> ".$msgs["correct"];
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
			echo "<input type=\"hidden\" name=\"cfglang\" value=\"$cfglang\">";
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
		"prelude"=>"Create config.php for FAQEngine.<br>In order for this script to work, PHP has to have write access to the parent directory.<br>Please also note: Database you provide allready has to exists. For security reasons none of the scripts will create a database.",
		"dbhost"=>"Databasehost",
		"dbname"=>"Databasename (DB allready has to exist)",
		"dbuser"=>"Databaseuser",
		"dbpwd"=>"Databasepassword",
		"dbprefix"=>"Tableprefix (please only use a-z and 0-9, no special characters)",
		"deflang"=>"default language",
		"admlang"=>"default language for admin interface",
		"scripturl"=>"URL where scripts reside<br>no transfer protocoll (HTTP) and hostname; no trailing slashes !!",
		"scriptpath"=>"path where scripts reside",
		"sesstype"=>"type of sessionhandling",
		"sesscookie"=>"using cookie",
		"sessurl"=>"POST/GET",
		"htaccess"=>"by HTTPD (htaccess)",
		"sitename"=>"Website hostname",
		"cfgferror"=>"error creating config.php",
		"lastvisitcookie"=>"Last visit tracking by cookie",
		"enable"=>"enable",
		"sesscookietime"=>"Timeout for session cookie in admin interface",
		"sec"=>"seconds",
		"pwrecov"=>"password recovery",
		"maxattach"=>"max. filesize for attachements by admin",
		"contentcharset"=>"Charset for content encoding",
		"encodecharset"=>"Charset for HTML encoding (see <a href=\"http://http://www.php.net/manual/en/function.htmlentities.php\">htmlentities</a>)",
		"cfgwritten"=>"config.php generated",
		"nodbhost"=>"You have to provide a database hostname",
		"errors"=>"There were errors processing your data, please correct",
		"back"=>"back",
		"nodbname"=>"You have to provide a database name",
		"noscriptpath"=>"You have to provide the path where the scripts reside",
		"nositename"=>"You have to provide the hostname of the website",
		"smtpmail"=>"Send emails through SMTP server rather than PHP mail function",
		"smtpserver"=>"Name of SMTP server",
		"smtpauth"=>"Authentication to send emails through SMTP server",
		"needed"=>"needed",
		"notneeded"=>"not needed",
		"smtpuser"=>"username for SMTP server",
		"smtppwd"=>"password for SMTP server",
		"enabled"=>"enabled",
		"php_installed"=>"Installed version of PHP",
		"php_file_upload"=>"File upload throught PHP",
		"disabled"=>"disabled",
		"notes"=>"Notes",
		"notexists"=>"%s does not exist.",
		"notwriteable"=>"%s is not writeable by PHP.",
		"uploadnotworking"=>"Upload of %s will not work.",
		"correct"=>"try to correct problem",
		"cantcorrect"=>"Couldn't be corrected. Please correct manually.",
		"corrected"=>"Problem with %s corrected.",
		"cfgprotected"=>"config.php is write protected. Please change and run again.",
		"no_langs"=>"no language files found",
		"using_iis"=>"I'm using IIS on Windows as webserver",
		"sitedesc"=>"short description for website",
		"runinstall"=>"Now you can run install.php to create database tables",
		"nodbconnect"=>"PHP is unable to connect to database server, please check connection settings and try again",
		"dbnotexists"=>"Database <i>%s</i> does not exist (PHP was unable to open database)",
		"autodetect"=>"automatic detection (%s)",
		"attachfs"=>"Store attachements in filesystem and not DB",
		"attachements"=>"attachements"
	);
	return $msgs;
}
function get_de_msgs()
{
	$msgs=array(
		"prelude"=>"Erzeugen der config.php f&uuml;r FAQEngine.<br>Damit dieses Skript die Datei erstellen kann, ben&ouml;tigt PHP Schreibzugriff auf das &uuml;bergeordnete Verzeichnis.<br>Beachten Sie bitte auch, dass die angegebene Datenbank bereits existieren muss, da aus Sicherheitsgr&uuml;nden keines der Skripte eine Datenbank anlegt",
		"dbhost"=>"Hostname des Datenbankservers",
		"dbname"=>"Datenbankname (Datenbank muss bereits existieren)",
		"dbuser"=>"Benutzername f&uuml;r Datenbank",
		"dbpwd"=>"Passwort f&uuml;r Datenbank",
		"dbprefix"=>"Pr&auml;fix f&uuml;r Datenbanktabellen (bitte nur a-z und 0-9 verwenden, keine Sonderzeichen)",
		"deflang"=>"voreingestellte Sprache",
		"admlang"=>"voreingestellte Sprache f&uuml;r Administrationsoberfl&auml;che",
		"scripturl"=>"URL der Skripte<br>ohne Protokoll (HTTP) und Hostnamen; ohne Schr&auml;gstrich am Ende",
		"scriptpath"=>"Verzeichnis, in dem die Skripte liegen",
		"sesstype"=>"Art des Sessionhandlings",
		"sesscookie"=>"per Cookie",
		"sessurl"=>"POST/GET",
		"htaccess"=>"durch den Webserver (htaccess)",
		"sitename"=>"Hostname der Website",
		"cfgferror"=>"Fehler beim Erzeugen der config.php",
		"lastvisitcookie"=>"Merken des letzten Besuches per Cookie",
		"enable"=>"aktivieren",
		"sesscookietime"=>"Timeout f&uuml;r die Session in der Administrationsoberfl&auml;che",
		"sec"=>"Sekunden",
		"pwrecov"=>"Passwortermittlung",
		"maxattach"=>"max. Filegr&ouml;sse f&uuml;r Dateianh&auml;nge der Admins",
		"contentcharset"=>"Contentcharset",
		"encodecharset"=>"F&uuml;r HTML-Kodierung zu verwendender Zeichensatz (siehe <a href=\"http://http://www.php.net/manual/de/function.htmlentities.php\">htmlentities</a>)",
		"cfgwritten"=>"config.php erzeugt",
		"nodbhost"=>"Sie m&uuml;ssen einen Datenbanksever angeben.",
		"errors"=>"Bei der Verarbeitung der Daten sind Fehler aufgetreten, bitte korigieren",
		"back"=>"zur&uuml;ck",
		"nodbname"=>"Sie m&uuml;ssen einen Datenbanknamen angeben.",
		"noscriptpath"=>"Sie m&uuml;ssen das Verzeichnis angeben, in dem die Skripts liegen.",
		"nositename"=>"Sie m&uuml;ssen den Hostnamen der Website angeben.",
		"smtpmail"=>"E-Mails an Stelle der PHP Mailfunktion &uuml;ber SMTP-Server versenden",
		"smtpserver"=>"Name des SMTP-Servers",
		"smtpauth"=>"Authentifizierung, um E-Mails &uuml;ber den SMTP-Server zu versenden",
		"needed"=>"ben&ouml;tigt",
		"notneeded"=>"nicht ben&ouml;tigt",
		"smtpuser"=>"Benutzername zur Anmeldung am SMTP-Server",
		"smtppwd"=>"Passwort zur Anmledung am SMTP-Server",
		"enabled"=>"aktiviert",
		"php_installed"=>"Installierte PHP-Version",
		"php_file_upload"=>"Fileupload in PHP",
		"disabled"=>"deaktiviert",
		"notes"=>"Anmerkungen",
		"notexists"=>"%s existiert nicht.",
		"notwriteable"=>"PHP hat keine Schreibrechte auf %s.",
		"uploadnotworking"=>"Der Upload von %s funktioniert dann nicht.",
		"correct"=>"versuchen das Problem zu beheben",
		"cantcorrect"=>"Problem konnte nicht behoben werden. Bitte von Hand beheben",
		"corrected"=>"Problem mit %s behoben",
		"cfgprotected"=>"Die config.php ist schreibgesch&uuml;tzt. Bitte korrigieren und neu starten.",
		"no_langs"=>"Keine Sprachdateien vorhanden",
		"using_iis"=>"Es wird der MS IIS unter Windows als Webserver benutzt",
		"sitedesc"=>"Kurzbeschreibung der Website",
		"runinstall"=>"Nun k&ouml;nnen Sie install.php aufrufen, um die Datenbanktabellen zu erzeugen",
		"nodbconnect"=>"PHP konnte keine Verbindung zum Datenbankserver herstellen. Bitte &uuml;berpr&uuml;fen Sie die Verbindungseinstellungen.",
		"dbnotexists"=>"Datenbank <i>%s</i> existiert nicht (PHP konnte die Datenbank nicht &ouml;ffnen)",
		"autodetect"=>"automatische Ermittlung (%s)",
		"attachfs"=>"Dateianh&auml;nge im Dateisystem und nicht in der Datenbank ablegen",
		"attachements"=>"Dateianh&auml;ngen"
	);
	return $msgs;
}

function language_select($default, $name="language", $dirname="language/", $class="")
{
	$dir = opendir($dirname);
	$lang_select = "<SELECT NAME=\"$name\"";
	if($class)
		$lang_select.=" class=\"$class\"";
	$lang_select.=">\n";
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			$file == $default ? $selected = " SELECTED" : $selected = "";
			$lang_select .= "  <OPTION value=\"$file\"$selected>$file\n";
		}
	}
	$lang_select .= "</SELECT>\n";
	closedir($dir);
	return $lang_select;
}
function language_list($dirname="language/")
{
	$langs = array();
	$dir = opendir($dirname);
	while($file = readdir($dir))
	{
		if (ereg("^lang_",$file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			array_push($langs,$file);
		}
	}
	closedir($dir);
	return $langs;
}

?>