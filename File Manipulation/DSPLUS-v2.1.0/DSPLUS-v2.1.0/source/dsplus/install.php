<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /install.php
|
|	
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

$version = 'v2.1.0'; 
global $errormess, $scriptname;
$errors = 0;
$errormess='';
$path='';
$site='';
$email='';
$dirpath = '';
$use_email = 0;
$use_file = 1;
$matchsecret='';
$htmlroot='';
$secret='';
$scriptname='';

if (!version_compare(PHP_VERSION, '4.3.0', '>=')) 
{
	writeXhtml ('Your php version <b>('.PHP_VERSION.')</b> is older then the minimum required version <b>(4.3.0)</b>. Please install the newer version and try again.','** ERROR **');
	exit;
}

@ini_set('session.use_trans_sid', false); // same as below... one of these aught to work.
@ini_set("url_rewriter.tags", "a=href,area=href,frame=src,input=src"); // fix for phpsessid  being inserted after form element causing invalid xhtml
session_start();

/* set up root path. If it can't be found stop the install */
$path = $_SERVER['DOCUMENT_ROOT'];
$htmlroot = explode('/', $path);
$htmlroot = end($htmlroot);
$path = ExtractString($path, "/", "/$htmlroot");
if($path == '') {errorReport('Critical Failure! Server document root was not found!');$errors++;}

if(preg_match('/^([A-Za-z0-9.:\/_\-]{1,255})$/', stripslashes($_SERVER['SERVER_NAME']), $matchserver))
{
	$site = $matchserver[0];
}
else
{
	errorReport('ERROR: Your server name did not pass the filter test.'); 
	$errors++;
}

if(preg_match('/^([A-Za-z0-9.:\/_\-]{1,90})$/', stripslashes($_SERVER['SCRIPT_NAME']), $matchscript))
{
	$scriptname = $matchscript[0];
}
else
{
	errorReport('ERROR: Script file name error.'); 
	$errors++;
}

/* check for valid overwrite request from admin page */
if (isset($_SESSION['owtoken']) && $_POST['owtoken'] == $_SESSION['owtoken']) 
{
	if (isset($_COOKIE['owctoken']) && $_POST['owtoken'] == $_COOKIE['owctoken']) 
	{
			installStart(); 
	}
}

/* Process Installation */
if (isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) 
{
	if (!empty($_POST['secret'])) 
	{
		if (isset($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) 
		{
			/* check for proper secret word */
			if(preg_match('/^([A-Za-z0-9]{8,20})$/', stripslashes($_POST['secret']), $matchsecret)) 
			{
				$secret = $matchsecret[0];
			} 
			else 
			{
				errorReport('ERROR: Your secret word must only be 8-20 characters in length, and use only letters or numbers.',$_SESSION['token'],2,$_POST['email'], $_POST['dirpath']);
			}
			
			/* check for email posted, and only use it if it is clean */
			if (!empty($_POST['email'])) 
			{
				if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', stripslashes($_POST['email']), $matchemail)) 
				{
					$email = $matchemail[0];
					$use_email = 1;
					$use_file = 1;
				}
				else 
				{
					errorReport('ERROR: Your Email did not match proper email formatting.',$_SESSION['token'],3,$secret, $_POST['dirpath']);
				}
			}
			else 
			{
				$email = $_SERVER['SERVER_ADMIN'];
				$use_email = 0;
				$use_file = 1;
			}
			
			if (!empty($_POST['dirpath']))
			{
				if(preg_match('/^([A-Za-z0-9.\/_\-\s]{1,255})$/', stripslashes($_POST['dirpath']), $matchdirpath))
				{
					$dirpath = $matchdirpath[0]; //  the directory written to for files, data and config.
					$endchr = substr($dirpath, -1);
					$startchr = mb_substr($dirpath, 0, 1);
					if($endchr != '/') $dirpath = $dirpath.'/';
					if($startchr == '/') $dirpath = substr($dirpath,1);
				}
				else
				{
					errorReport('ERROR: Your Directory Path did not match proper formatting.',$_SESSION['token'],4,$secret,$email);
				}
			}
			
			/*check the top level directories to see if they exist and have correct permissions */
			$chkd = checkDir($path,$dirpath.'ds_files'); 
			if($chkd == 1) 
			{
				$errors++; 
			}
			else 
			{
				$chkd = checkPerms($path,$dirpath.'ds_files'); 
				if($chkd == 1) 
				{
					$errors++;
				}
				else 
				{
					$sapi_type = php_sapi_name();
					if (substr($sapi_type, 0, 3) == 'cgi') 
					{
						if (!is_dir('/'.$path.'/'.$dirpath.'ds_files/scripts')) {$result = @makeDir('/'.$path.'/'.$dirpath.'ds_files/scripts'); if(!$result) {errorReport('Could not create directory!<br /><b> /'.$path.'/'.$dirpath.'ds_files/scripts</b><br /> Please create it manually and CHMOD it to 777<br />'); $errors++;}}
						if (!is_dir('/'.$path.'/'.$dirpath.'ds_files/data')) {$result = @makeDir('/'.$path.'/'.$dirpath.'ds_files/data'); if(!$result) {errorReport('Could not create directory!<br/><b> /'.$path.'/'.$dirpath.'ds_files/data</b><br /> Please create it manually and CHMOD it to 777<br />'); $errors++;}}
						if (!is_dir('/'.$path.'/'.$dirpath.'ds_files/files')) {$result = @makeDir('/'.$path.'/'.$dirpath.'ds_files/files'); if(!$result) {errorReport('Could not create directory!<br/><b> /'.$path.'/'.$dirpath.'ds_files/files</b><br /> Please create it manually and CHMOD it to 777<br />'); $errors++;}}
				
					} 
					else 
					{
						if (!is_dir('/'.$path.'/'.$dirpath.'ds_files/scripts')) {errorReport('Can not create directory!<br /><b> /'.$path.'/'.$dirpath.'ds_files/scripts</b><br /> Please create it manually and CHMOD it to 777<br />'); $errors++;}
						if (!is_dir('/'.$path.'/'.$dirpath.'ds_files/data')) {errorReport('Can not create directory!<br /><b> /'.$path.'/'.$dirpath.'ds_files/data</b><br /> Please create it manually and CHMOD it to 777<br />'); $errors++;}
						if (!is_dir('/'.$path.'/'.$dirpath.'ds_files/files')) {errorReport('Can not create directory!<br /><b> /'.$path.'/'.$dirpath.'ds_files/files</b><br /> Please create it manually and CHMOD it to 777<br />'); $errors++;}
					}
				}
			}
			
			$chkd = checkDir($path,$htmlroot.'/dsplus');
			if($chkd == 1) {
				$errors++;
			}else {
				$chkd = checkPerms($path,$htmlroot.'/dsplus');
				if($chkd == 1) $errors++;
			}
			
			if ($errors > 0) errorReport($email,$_SESSION['token'],1,$secret,$dirpath);
			
			/* check the subfolders to see if they exist and have correct permissions */
			$chkd = checkDir($path,$dirpath.'ds_files/scripts'); 
			if($chkd == 1) {
				$errors++; 
			}else {
				$chkd = checkPerms($path,$dirpath.'ds_files/scripts'); 
				if($chkd == 1) $errors++;
			}
			
			$chkd = checkDir($path,$dirpath.'ds_files/files'); 
			if($chkd == 1) {
				$errors++; 
			}else {
				$chkd = checkPerms($path,$dirpath.'ds_files/files'); 
				if($chkd == 1) $errors++;
			}
			
			$chkd = checkDir($path,$dirpath.'ds_files/data'); 
			if($chkd == 1) {
				$errors++;
			}else {
				$chkd = checkPerms($path,$dirpath.'ds_files/data');
				if($chkd == 1) $errors++;
			}
			
			if ($errors > 0) errorReport($email,$_SESSION['token'],1,$secret,$dirpath);
			
			$sapi_type = php_sapi_name();
			if (substr($sapi_type, 0, 3) == 'cgi') 
			{
				writeFileOne($htmlroot,$path,$version); //ds.css
				writeFileTwo($htmlroot,$path,$dirpath,$site,$email,$secret,$version,$use_email,$use_file); // ds_config.php
				writeFileThree($htmlroot,$path,$dirpath,$version); // 
				writeFileFour($htmlroot,$path,$dirpath,$site,$version); // m.php
				writeFileFive($htmlroot,$path); //ds.html
				writeFileSix($htmlroot,$path,$site); // m.html
				writeFileSeven($htmlroot,$path,$version); // m.css
				writeDataFiles($htmlroot,$path,$dirpath);
				writeLogo($htmlroot,$path);
				writeSmallLogo($htmlroot,$path);
				writeExampleFile($htmlroot,$path,$dirpath);
				writeMplusPhp($htmlroot,$path,$dirpath,$site,$version);
				writeMplusCss($htmlroot,$path,$version);
				writeMplusHtml($htmlroot,$path,$site);
				writePdeJs($htmlroot,$path);
				writeMinusGif($htmlroot,$path);
				writePlusGif($htmlroot,$path);
				writeHtaccess($htmlroot,$path,$dirpath);
				writeAdminFile($htmlroot,$path,$dirpath,$version);
			
				checkFiles($htmlroot,$path,$dirpath);
			}
			else 
			{
				checkFiles($htmlroot,$path,$dirpath,$function=0,$_SESSION['token'],$email,$secret);
				
				$chkd = checkPerms($path,"$htmlroot/dsplus/ds.css");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/ds.php");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/ds.html");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/m.css");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/m.php");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/m.html");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/ds_logo.png");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/ds_slogo.png");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/htaccess.txt');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_trlog.txt');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_bwlog.php');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_count.php');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_tokens.php');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_dllogarchive.txt');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_rptlog.txt');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_dllog.txt');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/files/amazed.png');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/scripts/ds_config.php');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_desc.php');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,$dirpath.'ds_files/data/ds_author.php');
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/mplus.css");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/mplus.php");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/mplus.html");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/pde.js");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/plus.gif");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/minus.gif");
				if($chkd == 1) $errors++;
				$chkd = checkPerms($path,"$htmlroot/dsplus/ds_admin.php");
				if($chkd == 1) $errors++;
				
				
				if ($errors > 0) errorReport($email,$_SESSION['token'],1,$secret,$dirpath);
				
				writeFileOne($htmlroot,$path,$version); //ds.css
				writeFileTwo($htmlroot,$path,$dirpath,$site,$email,$secret,$version,$use_email,$use_file); // ds_config.php
				writeFileThree($htmlroot,$path,$dirpath,$version); // ds.php
				writeFileFour($htmlroot,$path,$dirpath,$site,$version); // m.php
				writeFileFive($htmlroot,$path); //ds.html
				writeFileSix($htmlroot,$path,$site); // m.html
				writeFileSeven($htmlroot,$path,$version); // m.css
				writeDataFiles($htmlroot,$path,$dirpath);
				writeLogo($htmlroot,$path);
				writeSmallLogo($htmlroot,$path);
				writeExampleFile($htmlroot,$path,$dirpath);
				writeMplusPhp($htmlroot,$path,$dirpath,$site,$version);
				writeMplusCss($htmlroot,$path,$version);
				writeMplusHtml($htmlroot,$path,$site);
				writePdeJs($htmlroot,$path);
				writeMinusGif($htmlroot,$path);
				writePlusGif($htmlroot,$path);
				writeHtaccess($htmlroot,$path,$dirpath);
				writeAdminFile($htmlroot,$path,$dirpath,$version);
				
				checkFiles($htmlroot,$path,$dirpath);
			}
			
			$s_output= '<h3>Installation was successful!</h3><p><a href="http://'.$site.'/dsplus/m.php?p=amazed.png">Click here for a working example.</a></p>
			<p><a href="http://'.$site.'/dsplus/mplus.php">Or here for an example of the download manager.</a> <br />(looks more impressive if you first load in some files and sub-directories to your /files directory).</p><br />
			<p>Please CHMOD files and directories according to the guide below.</p><br />
			<p>The short version is: <b>All directories should be 755 and all static files and scripts should be 644. Data files should be 666.</b> The specifics are listed.</p><br />
			<p>Should the data files end up not writable due to an odd server config, try changing them back to 777.</p>
			<ul>
				<li>'.$dirpath.'ds_files/<b>scripts</b> CHMOD to <b>755</b></li>
				<li>'.$dirpath.'ds_files/scripts/<b>ds_config.php</b> CHMOD to <b>644</b></li>
				<li>'.$dirpath.'ds_files/<b>data</b> CHMOD to <b>755</b></li>
				<li>'.$dirpath.'ds_files/data/<b>ALL</b> CHMOD to <b>666</b></li>
				<li>'.$dirpath.'ds_files/<b>files</b> CHMOD to <b>755</b></li>
				<li>'.$dirpath.'ds_files/files/<b>ALL</b> CHMOD to <b>644</b></li>
				<li>/'.$htmlroot.'/<b>dsplus</b> CHMOD to <b>755</b></li>
				<li>/'.$htmlroot.'/dsplus/<b>ALL</b> CHMOD to <b>644</b></li>
			</ul>';	
			
			if (substr($sapi_type, 0, 3) == 'cgi') {
				$s_output = '<h3>Installation was successful!</h3><p><a href="http://'.$site.'/dsplus/m.php?p=amazed.png">Click here for a working example.</a></p>
				<p><a href="http://'.$site.'/dsplus/mplus.php">Or here for an example of the download manager.</a> <br />(looks more impressive if you first load in some files and sub-directories to your /files directory).</p><br />';
			}
			writeXhtml($s_output,'Success!'); 
			exit;
			
		}
		writeXhtml ('Session Failure','Error!'); 
		exit;
	}
}
else
{
	// if there are existing files, abort the install, redirect to the admin page
	if(checkFiles($htmlroot,$path,$dirpath,$function=1)) installStart(); 
	writeXhtml('Previous Installation detected, if you wish to overwrite, please use the admin page (ds_admin.php) located in /dsplus','**Warning**');
	
}

/* start installation */
function installStart()
{
	$token = md5(uniqid(rand(), true));
	$_SESSION['token'] = $token;
	
	$output = '<h3>Welcome to Download Sentinel++.</h3>
			<h3>If you have not already done so, read the <span class="filename">readme.txt</span> file for more information on this installation.
			You may forge recklessly ahead if you wish. The script will produce helpful messages at each step to deal with your lack of foresight.</h3>
			<h3>Please choose a secret word (admin password) to proceed with the installation.</h3>
			<form method="post" action="'.$scriptname.'">
			<p><input type="hidden" name="token" value="'.$token.'" /></p>
			<p>(Any combination of 8-20 letters or numbers):<span><input type="password" name="secret" /></span></p>
			<h3>Enter optional Email address for reporting errors (NOT recommended!).</h3>
			<p>Leave blank for file logging only.<span><input type="text" name="email" /></span></p>
			<h3>Enter optional directory path for download files and data (For most people this is NOT required!). For example you may need to put these files in a web accessible area. Leading forward slash is not required. 
			For more information on how and why, please read Appendix A in the readme.txt file</h3>
			<p>Leave blank for standard installation.<span><input type="text" name="dirpath" /></span></p>
			<p><input type="submit" value="Continue" /></p>
			</form>';
			
	writeXhtml($output,'Install');
	exit;
}

function makeTree($path)
{ 
	$list=array();
	$handle=opendir($path);
	while($a=readdir($handle)) {
		if(!preg_match('/^\./',$a)) {
			$full_path = $path.'/'.$a;
			if(is_file($full_path)) { $list[]=$full_path; }
			if(is_dir($full_path)) {
				$recursive=makeTree($full_path);
				for($n=0; $n<count($recursive); $n++) {
					$list[]=$recursive[$n];
				}
			}
		}
	}
	closedir($handle);
	return $list;
}

function after($this, $inthat)
{
	if (!is_bool(strpos($inthat, $this)))
	return substr($inthat, strpos($inthat,$this)+strlen($this));
}

function removeStringStart($str,$start)
{
	$str_low = strtolower($str);
	if (strpos($str_low, $start) !== false) {
		$startpos = strpos($str_low, $start) + strlen($start);
		return substr($str,$startpos);
	}
}

function ExtractString($str, $start, $end) 
{
	$str_low = strtolower($str);
	if (strpos($str_low, $start) !== false && strpos($str_low, $end) !== false) {
		$pos1 = strpos($str_low, $start) + strlen($start);
		$pos2 = strpos($str_low, $end) - $pos1;
		return substr($str, $pos1, $pos2);
	}
}

function errorReport($msg,$token=0,$action=0,$secret='',$dirpath='')
{
	global $errormess;
	
	if($action==1) {
		$output = $errormess.'<h3>Please correct the errors above and then click on continue.</h3>
		<form method="post" action="'.$scriptname.'">
		<p><input type="hidden" name="token" value="'.$token.'" /></p>
		<p><input type="hidden" name="email" value="'.$msg.'" /></p>
		<p><input type="hidden" name="secret" value="'.$secret.'" /></p>
		<p><input type="hidden" name="dirpath" value="'.$dirpath.'" /></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
		writeXhtml($output,'** ERROR **');
		exit;
	}
	if($action==2) {
		$output = $msg.'<h3>Please correct the errors above and then click on continue.</h3>
		<form method="post" action="'.$scriptname.'">
		<p><input type="hidden" name="token" value="'.$token.'" /></p>
		<p><input type="hidden" name="email" value="'.$secret.'" /></p>
		<p><input type="hidden" name="dirpath" value="'.$dirpath.'" /></p>
		<p>(Any combination of 8-20 letters or numbers):<span><input type="password" name="secret" /></span></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
		writeXhtml($output, '** ERROR **');
		exit;
	}
	if($action==3) {
		$output = $msg.'<h3>Please correct the errors above and then click on continue.</h3>
		<form method="post" action="'.$scriptname.'">
		<p><input type="hidden" name="token" value="'.$token.'" /></p>
		<p><input type="hidden" name="secret" value="'.$secret.'" /></p>
		<p><input type="hidden" name="dirpath" value="'.$dirpath.'" /></p>
		<p>Email address was not correctly formed (according to the program). Try again or leave blank and just continue:
		<span><input type="text" name="email" /></span></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
		writeXhtml($output, '** ERROR **');
		exit;
	}
	if($action==4) {
		$output = $msg.'<h3>Please correct the errors above and then click on continue.</h3>
		<form method="post" action="'.$scriptname.'">
		<p><input type="hidden" name="token" value="'.$token.'" /></p>
		<p><input type="hidden" name="email" value="'.$dirpath.'" /></p>
		<p><input type="hidden" name="secret" value="'.$secret.'" /></p>
		<p>Directory Path was not correctly formed (according to the program). Try again or leave blank and just continue:
		<span><input type="text" name="dirpath" /></span></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
		writeXhtml($output, '** ERROR **');
		exit;
	}
	$errormess .= '<br />ERROR: '.$msg;
	return;
}

function checkDir($path,$vardir)
{
	$dir = "/$path/$vardir";
	if (!@is_dir($dir)) {
		errorReport('/'.$path.'/<b>'.$vardir.'</b> directory does not exist, please create it before proceeding.');
		return 1;
	}
	return 0;
}

function checkPerms($path,$vardir)
{
	$dir = "/$path/$vardir";
	$chk =  substr(sprintf('%o', @fileperms($dir)), -4);// tells us what the actual perms are.
 
	if (!is_writable($dir)) {
		if (!@chmod($dir, 0777)) {
			errorReport("Cannot change the permissions of (<b>$vardir</b>).  It is currently <b>$chk</b>. Please manually adjust (CHMOD) to <b>0777</b><br />");
			return 1;
		}
	}
	return 0;
}

function makeDir($dir)
{
	if (@is_dir($dir)) {
		@chmod($dir, 0777);
		return 1;
	}
	$ret = (mkdir($dir) || mkdir(dirname($dir)));

	return $ret;
}

function checkFiles($htmlroot,$path,$dirpath,$function=0,$token='',$email='',$secret='')
{
	$altpath = $path;
	if ($dirpath != '') $altpath = substr($path.'/'.$dirpath,0,-1);
	
	$ok = 0;
	$big = 0;
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/ds.php</b><br />";} if(@filesize("/$path/$htmlroot/dsplus/ds.php") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds.css","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/ds.css</b><br />";} if(@filesize("/$path/$htmlroot/dsplus/ds.css") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds.html","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/ds.html</b><br />";} if(@filesize("/$path/$htmlroot/dsplus/ds.html") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/index.html","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/index.html</b><br />";} if(@filesize("/$path/$htmlroot/dsplus/index.html") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/m.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/m.php</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/m.php") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/m.css","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/m.css</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/m.css") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/m.html","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/m.html</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/m.html") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/mplus.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/mplus.php</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/mplus.php") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/mplus.css","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/mplus.css</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/mplus.css") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/mplus.html","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/mplus.html</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/mplus.html") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds_logo.png","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/ds_logo.png</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/ds_logo.png") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds_slogo.png","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/ds_slogo.png</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/ds_slogo.png") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/pde.js","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/pde.js</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/pde.js") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/plus.gif","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/plus.gif</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/plus.gif") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/minus.gif","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/minus.gif</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/minus.gif") >0) $big++;
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds_admin.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$path/$htmlroot/dsplus/ds_admin.php</b><br />";}  if(@filesize("/$path/$htmlroot/dsplus/ds_admin.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/scripts/ds_config.php","r");  if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/scripts/ds_config.php</b><br />";} if(@filesize("/$altpath/ds_files/scripts/ds_config.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_desc.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_desc.php</b><br />";}  if(@filesize("/$altpath/ds_files/data/ds_desc.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_author.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_author.php</b><br />";}  if(@filesize("/$altpath/ds_files/data/ds_author.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_count.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_count.php</b><br />";}  if(@filesize("/$altpath/ds_files/data/ds_count.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_tokens.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_tokens.php</b><br />";}  if(@filesize("/$altpath/ds_files/data/ds_tokens.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_dllog.txt","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_dllog.txt</b><br />";} if(@filesize("/$altpath/ds_files/data/ds_dllog.txt") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_dllogarchive.txt","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_dllogarchive.txt</b><br />";} if(@filesize("/$altpath/ds_files/data/ds_dllogarchive.txt") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_rptlogarchive.txt","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_rptlogarchive.txt</b><br />";} if(@filesize("/$altpath/ds_files/data/ds_rptlogarchive.txt") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_trlog.txt","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_trlog.txt</b><br />";} if(@filesize("/$altpath/ds_files/data/ds_trlog.txt") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_rptlog.txt","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_rptlog.txt</b><br />";} if(@filesize("/$altpath/ds_files/data/ds_rptlog.txt") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/data/ds_bwlog.php","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/data/ds_bwlog.php</b><br />";} if(@filesize("/$altpath/ds_files/data/ds_bwlog.php") >0) $big++;
	$f1 = @fopen("/$altpath/ds_files/files/amazed.png","r"); if($f1) {$ok++;}else{$offender .= "<b>/$altpath/ds_files/files/amazed.png</b><br />";} if(@filesize("/$altpath/ds_files/files/amazed.png") >0) $big++;
	
	

	
	if($function==0) {
		if($ok < 28) {
			$sapi_type = php_sapi_name();
			if (substr($sapi_type, 0, 3) == 'cgi') {
				writeXhtml('<h3>Not all files were created!! Installation failed!</h3><p>Manually upload the missing files listed below.</p><p><span class="filename">'.$offender.'</span></p>','** ERROR **'); 
				exit;
			}
			else {
				$output = '<h3>You need to upload the empty files in the <b>upload</b> folder first!<br /> Upload the files listed below.</h3><p><span class="filename">'.$offender.'</span></p>
				<form method="post" action="'.$scriptname.'">
				<p><input type="hidden" name="token" value="'.$token.'" /></p>
				<p><input type="hidden" name="email" value="'.$email.'" /></p>
				<p><input type="hidden" name="secret" value="'.$secret.'" /></p>
				<p><input type="hidden" name="dirpath" value="'.$dirpath.'" /></p>
				<p><input type="submit" value="Continue" /></p>
				</form>';
				writeXhtml($output,'** ERROR **');
				exit;
			}
		}
	}
	
	if($function==1) 
	{
		if($ok > 0 && $big > 1) 
		{
			return false;
		}
		return true;
	}
}

function writeXhtml($output,$heading='Download Sentinel++')
{
$poweredby = "iVBORw0KGgoAAAANSUhEUgAAAFIAAAAPCAYAAAB3PJiyAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQU
AAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAABUJJREFUWEftl02P01YYhfPp7+uv2M
7XJJNMZiYMBUppaUUpGypaCRV11XZVlrNA3VddtLuu+g/6AxEg4C+cnmMnozQYJrQIgUSkI8f2vdf2c8/73vc2T09P0fjw+/8
EBPL58+fvpd6Vdy/NuPkytwY5jlIXy8zgOItxlEVYJAHmkY9Z4GNufEw8t9S+72HGc13f96t788DD3HdxwPbqIx3E7M97al+p6
rvPtnuuU0rXNE41HseIDKXnVn11Xe1174uifzbpm+9OSymy3rpkwhdAXhgl6OcOBrmLQUH1PfTiDqKgA+O14TtNOFYDttWE3
W3wP4+dBtxuk/faCOwmDBW6rbJ9pRYCtw3XasFRH8rttuB2qr4WzzWeY7fgURrHeF2EXgeh30ZsLCSRAxN2EfB8ysldR1AdS
JLEpurg7tJm10mpBfnZYoj9SYS9kcFEGofIUguR0YcRFEG6BKWPtgjPWcERgGAN0mkRZAUx9DuEKDgVJFfASpgtQhTIJizCFEiX
/cvJKPvyeX6XE9jlRLropZzQnuTiZJidC3ITwhra+prOt+/vCq2uXS3I6wQ5GXkY910UiY2EAOXG2KcrAovHLkFWECWbEhyPgOV
Ws4Io6IIo+a7uCSRBsa9Aqm+XACWP/QNXwKkzF7dLiGnkIos99AlxWFS6std7LZD6+E142yBf5thdr9eCPCl6iPkBMaFNGOIfH3
q4OLMJN0KW+HRaF83VjLZaBNEmFMLwBYMg5cRYIblyZETwui6XCeTakQK4nozy2KFD23Qsnaq2gzzB8TTElZmLC2OPk8p3iq
iwjWVxfmhvQ9gGue3SV7U/z721IJd5rwwvfdx8avDrL9fw9183S+0zb44iC19djfHpxZhusQiSzqJ8AgitynGG/UO6SzAlnzn1X6F
N6F2O31FIs/0gtnCdwO5cdhi2LYzY98ZH+dlz793S5NqwOI7jNLDYIUe+DpiXQd6EvZ1TN/vUgrw07CFnTswY1imT/Mkyxf2fL
5UfdffbCX74foo/f/8cf/x2DffuTplDDZ0kRxIg5clRdJ5xlA7oILpT4S7AmiBP+bVMB3Qn2ylvXpwb/PTN6Azcd7eHuP/jQXn+9Y0h
pgMPec9HSiUJHdp/86G9a96sSwu1ID+Z9zEa+Bj1fRSZzUWGANwG8sLBYhHjcMZyZM9gPLQx5qo+7gfMnXKd8iNd6HTLo7Er
gBEdGQkmXVaClAg8YfooUgd9Ko8dDJlGlkcpTk5yHCxCDIcuktiGYVmUs/wa9A2yLEDEthcGb3ax2c6hdecvW6h0vRbkl8sZZtO
EpY/H0NViY5ULhkcoym/Kkb5WYbuBIrRQMCwjliSBQpr3lBON8iOl1dqUqv4LZCk5ltcS9s/oMB19PsNhG1uu5jihcZCkPrLcoMhD
5DxmmYuUJdClfnruYvOq8uZVYfpfVu9akFePxpjScWXJw4/TwpOyhlNNZyjVdLHhghI06aQOekz+oVet1nKdgAl8rHKJUFUH6lo
FdeXKVb2oulIONaoMOGk+29oMfZVBgccJ1LN4XSGd0rlJ0kHGCFkW4QsgHz169NYL8TX0WpC353u4PIr5sgYLfsCcNdzMcKfC
8FqkIXc6MY7pkKPMpwwO0qDcceyvdiXzkDsZtj3kgnDYC8v/81DyuMOpdid73NVMdNSOiPcWOZ836OGoSLmQxJixz0Gq54flO
McFd1g5d1isJQ+pm6P83d/ZvG977tfZaz979gxPnz6F3Kvjed/6+PFjPHz4EE+ePCmlcx01znbfsy2i/nzQiwwePHiwM5d/AMmlM
wxI/JtJAAAAAElFTkSuQmCC";


 echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
<title>Download Sentinel++</title>
<style type='text/css' media='screen'>
body {
	font:11px verdana, arial, helvetica, sans-serif;
	color:#333;
	background-color:white;
	margin:50px 0px; padding:0px; /* Need to set body margin and padding to get consistency between browsers. */
	text-align:center; /* Hack for IE5/Win */
}
h1 {color:#333;font:1.8em/1 Georgia, 'Times New Roman', Times, serif;font-weight:900;font-style:italic;}
h2 {color:#333;font:1.1em/1 Georgia, 'Times New Roman', Times, serif;font-weight:700;margin:2em 0 .25em;}
h3 {color:#666;font-size:1em;font-weight:800;margin:3em 0 1.5em;}
p {line-height:1.8;margin:0 0 1em;}
input {background-color:#F6FBFF;}
span {margin: 0 0 1em;}
#Content p+p {margin-top:-1em; text-indent:0.0em;}
ol, ul {margin-top:0;margin-bottom:1em;line-height:1.8;}
#Content p+ol, #Content p+ul {margin-top:0em;}
a {color:#09c;text-decoration:none;font-weight:600;}
a:link {color:#09c;}
a:visited {color:#07a;}
a:hover {background-color:white;}

#Footnotes p+p {margin-top:0; text-indent:0;}
.filename {color:#0000ff;}
.errormess {color:#ff0000;}
#Content {
	width:700px;
	margin:0px auto; /* Right and left margin widths set to 'auto' */
	text-align:left; /* Counteract to IE5/Win Hack */
	padding:15px;
	border:1px dashed #333;
	background-color:#eee;
}
</style>
<!-- Thanks to www.bluerobot.com for the great CSS examples -->
</head>
<body>

<div id='Content'>
	$output
</div>
<div id='poweredby'>poweredby:<br /><a href='http://scripts.ihostwebservices.com'><img src='data:image/png;base64,$poweredby' alt='Use a different browser!' /></a></div>
</body>
</html>";
}

/* ds.css */
function writeFileOne($htmlroot,$path,$version)
{
$write =
"/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds.css
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
body {
	font:11px verdana, arial, helvetica, sans-serif;
	color:#333;
	background-color:white;
	}

h1 {
	color:#333;
	font:1.8em/1 Georgia, 'Times New Roman', Times, serif;
	font-weight:900;
	font-style:italic;
	}
h2 {
	color:#333;
	font:1.1em/1 Georgia, 'Times New Roman', Times, serif;
	font-weight:700;
	margin:2em 0 .25em;
	}
h3 {
	color:#666;
	font-size:1em;
	font-weight:800;
	margin:3em 0 .35em;
	}
	
p {
	line-height:1.8;
	margin:0 0 1em;
	}
#Content p+p {margin-top:-1em; text-indent:2.7em;}

ol, ul {
	margin-top:0;
	margin-bottom:1em;
	line-height:1.8;
	}
#Content p+ol, #Content p+ul {margin-top:0em;}

a {
	color:#09c;
	text-decoration:none;
	font-weight:600;
	}
a:link {color:#09c;}
a:visited {color:#07a;}
a:hover {background-color:white;}

#Footnotes p+p {margin-top:0; text-indent:0;}

.keyPoint {
	margin:0 0 1.5em; padding:0;
	border:1px dashed #666;
	background-color:white;
	}
.keyPoint p {
	font:1.63em/1.8 Georgia, 'Times New Roman', Times, serif;
	margin:.5em 1em;
}
.keyPoint p+p {margin-top:-.5em;}

.markup {
	font:1em/1.25 monospace;
	border:1px dashed #999;
	text-indent:0 !important;
	padding:.3em;
}

body {
	margin:50px 0px; padding:0px; /* Need to set body margin and padding to get consistency between browsers. */
	text-align:center; /* Hack for IE5/Win */
}
	
#Content {
	width:500px;
	margin:0px auto; /* Right and left margin widths set to 'auto' */
	text-align:left; /* Counteract to IE5/Win Hack */
	padding:15px;
	border:1px dashed #333;
	background-color:#eee;
}
#sLogoContent {
	width:150px;
	margin:0px auto; /* Right and left margin widths set to 'auto' */
	text-align:left; /* Counteract to IE5/Win Hack */
	padding:15px;
	border:1px dashed #fff;
	background-color:#fff;
}";

	$f1 = @fopen("/$path/$htmlroot/dsplus/ds.css","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

/* ds_config.php */
function writeFileTwo($htmlroot,$path,$dirpath,$site,$email,$secret,$version,$use_email,$use_file)
{
	if ($dirpath != '') $path = substr($path.'/'.$dirpath,0,-1);
	$secret = sha1($secret);

$write = "<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds_config.php
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
error_reporting(0); // always have error reporting (to the browser) off on your server, if it isn't, then this line assures it is for this script. Use (E_ALL ^E_NOTICE) for debugging.

/* change to suit your site */
define('DS_URL', 'http://$site'); // Where people should be directed after a bandwidth theft attempt.
define('DS_EMAIL', '$email'); // Email address to send alerts to.
define('DS_DATADIR', '/$path/ds_files/data/'); // base path to the data files (should be outside visible web) 
define('DS_FILEPATH', '/$path/ds_files/files'); // Path to the your downloadable files. (should be outside visible web)
define('DS_DLLOG', DS_DATADIR.'ds_dllog.txt'); // Download log file
define('DS_DLLOGARC', DS_DATADIR.'ds_dllogarchive.txt'); // Archived Download log file
define('DS_RPTLOGARC', DS_DATADIR.'ds_rptlogarchive.txt'); // Archived Error report log file
define('DS_COUNTLOG', DS_DATADIR.'ds_count.php'); //Counter file, records quantity of file downloads in a text file.
define('DS_BWLOG', DS_DATADIR.'ds_bwlog.php'); //bandwidth recorder file
define('DS_RPTLOG', DS_DATADIR.'ds_rptlog.txt'); // text file for error log if option turned on below.
define('DS_TRLOG', DS_DATADIR.'ds_trlog.txt'); // text file for recording the token used and the http referer so they can be crossreferenced.
define('DS_TOKENS', DS_DATADIR.'ds_tokens.php'); // repository for unique tokens... future use.
define('DS_TRON', 1); // Set to 1 to have token / http_referer logging turned on. 0 to shut off. (a good way to catch a site stealing bandwidth)
define('DS_COUNTON', 1); // Set to 1 to record downloads in a txt file, 0 to shut off logging.
define('DS_DLON', 1); // Set to 1 to record download details. 0 to shut off logging.
define('DS_BWON', 1); // Set to 1 to turn on bandwidth checking, 0 to shut off.
define('DS_TFAIL', 1); // Set to 1 to have it report token failures. Set to 0 to shut off.
define('DS_BWALERT', 1); // Set to 1 to have it report BW alerts (when the bandwidth limit is reached). Set to 0 to shut off.
define('DS_REPORTON', 1); // Set to 1 to have it record errors. Set to 0 to shut off.
define('DS_RPTBYEMAIL', $use_email); // Set to 1 to have it send reports out via email. 0 to shut off.
define('DS_RPTBYFILE', $use_file); // Set to 1 to have a text file log of errors, 0 to shut off.
define('DS_TOKENON', 1); // Set to 1 to have the token check active. Set to 0 to shut off token checks. NOTE, Tokens help ensure downloads are only from approved sites.
define('DS_CTOKENON',1); // Set to 1 to enable cookie tokens which limits downloads to the same server and the browser that initiated it. Can be used to stop link sharing between browsers.
define('DS_STOKENON',1); // Set to 1 to enable session tokens which limits downloads to the same server. Use only if you do not wish to have ANY offsite downloads, even mirrors.
define('DS_UNQTKN', 0); // Set to 1 to have unique tokens for each file, this will use more resources so it could slow down script depending on the number of files.
define('DS_DLLOGSIZE', 1000000); // Size the download log file is allowed to get before archiving. Default is 1MB
define('DS_DLLOGARCSIZE', 3000000); // Size the archive file is allowed to get before being erased. A warning will be in the report file or email when it is reaching max.
define('DS_DLLOGARCWARN', 0.8); // The percentage the archive file can get before the warning is issued. 0.8 = 80%
define('DS_RPTLOGSIZE', 1000000); // Size the error log file is allowed to get before archiving. Default is 1MB
define('DS_RPTLOGARCSIZE', 3000000); // Size the error archive file is allowed to get before being erased. A warning will be in the report file or email when it is reaching max.
define('DS_ABLIMIT', 18000000000); //The absolute limit on bandwidth for the absolute limit of time. ie 18GB in a Month. Default setting is 18 GB.
define('DS_ATLIMIT', 2592000); // The absolute limit of time in seconds. Default is 1 month. A day would be 86400.
define('DS_INTLGTH', 10800); // The bandwidth interval length in seconds. Default is 10800 (3 hours) Which allows for 75 meg per interval with defaults. This spreads out the downloads over time. So that everyone has a chance to download a popular file.
define('DS_RATIO', 0.9); // the ratio of download size to actual bandwidth usage. Normally somewhat less than 1 normally due to people cancelling downloads after they are started. For example, use 0.5 if it's half.
define('DS_FTOKEN', '$secret'); // Your secret word. ($secret) Anyone with this word (and the right code) can download directly from your site. Change it to any alphanuneric sequence.
define('DS_ACTIVEDL', 30); // number of seconds the download token is good for.
define('DS_DLQTY', 3); // number of times someone can click on a download before a message pops up telling them to stop. This is automatically reset when the bandwidth limit is reached.

/* List of Tokens for sites that are allowed to link to your downloads. Disable token and that site cannot download. */
\$list[] = DS_FTOKEN; // your sites secret word. Change above with the define BWFTOKEN, no need to repeat yourself.

/* Commented out by default remove comment block to enable other sites and enter their secret word between the single quotes
\$list[] = 'some_other_sites_secret_word'; // A mirror's secret word. Record here the name of the site using it so you can check it in the token/referer log.
\$list[] = 'another_sites_secret_word'; // Another mirror. etc etc
*/

/* Defined list. Change List array above, do not change this define. */
define('DS_TOKENLIST', serialize(\$list));

/* Database info */
define('DB_ON', 0); // use database for logging downloads? 1 for on, 0 for off.
define ('DB_HOST', 'localhost'); // for most people this will be localhost, but it could be a sub-domain or IP
define ('DB_NAME', 'youraccount_dsplus'); // The name of your database.. for people on shared hosts, don't forget your account name in front ie \"accountname_mydbname\"
define ('DB_USER', 'youraccount_dbusername'); // User name for that database, also with a shared host the account is usually prepended. ie \"accountname_user\"
define ('DB_PASS', 'dbpassword'); // password for that user
define ('DB_TABLE', 'ds_filedata'); // change to the name of the table you are using (assuming you are logging with a database.
define ('DB_INCFIELD', 'downloads'); // change to the name of the field you are incrementing
define ('DB_CRITERIAFIELD', 'filename'); // change to the name of the field you are using to specify which field to increment.

/* Messages issued by the script to the browser */
define('DS_BMESS1', 'Invalid File Name!');
define('DS_BMESS2', 'Invalid Token!');
define('DS_BMESS3', 'That file was not found on the server.');
/*bandwidth reached message */
define('DS_BWMESS', 'The download limit for this time period has been reached (wait '.DS_INTLGTH.' seconds and try again). Sorry for the inconvenience.<br />'); // no need to change unless you wish to.
define('DS_BWMESSFULL', 'The total bandwidth limit for this time period has been reached (wait '.DS_ATLIMIT.' seconds and try again). Sorry for the inconvenience.<br />'); // no need to change unless you wish to.
/* Token failure message */
define('DS_TFAILMESS', \"<p>Your session has failed, please go back, reload the page, and try the link again.</p><p>Possible reasons for failure are: </p><p>1. Your session may have timed out.</p><p>2. You may need cookies enabled.</p><p>3. You may not be downloading from a valid download mirror.</p><p>Download from here instead <a href='\".DS_URL.\"'>\".DS_URL.\"</a></p>\");
define('DS_DLMESS', 'Stop Clicking me!'); // the message they would get for clicking too many times.


/* Messages issued by the script to the report file or email */
define('DS_EMESS1', 'File missing');
define('DS_EMESS2', 'Bandwidth Limit Reached');
define('DS_EMESS3', 'Token Failure');
define('DS_EMESS4', 'file was missing or has had permissions changed. Please check its status.');
define('DS_EMESS5', ' (download archive) is reaching the maximum size. Download it now if you wish to save the data. Once maximum size is reached it will be deleted');
define('DS_EMESS6', 'Automated error message'); // Email Subject
define('DS_EMESS7', 'An error has occured, message is - '); // Email message start.
define('DS_EMESS8', 'From: Download Sentinel++'); // Email From header
define('DS_EMESS9', 'Database failed to connect');
define('DS_EMESSA', 'Cannot find database');
define('DS_EMESSB', 'Invalid query');
/*
+
|  Security notes:
|  This file should be located outside the visible web (where most people will not be able to see your sensitive info).
|  People on the same server can see this file if the host has not disabled several commands from being used by php or perl, etc.
|  They will not be able to see this file if the server is using CGI PHP and it is properly configured and/or safemode is on and properly configured.
|  On a shared host that does not have safemode on, nor using CGI PHP (phpsuexec), nor disabled exec, backtick operator, etc, you may be out of luck. Get hosted elsewhere.
| 
|  Do NOT use the same password for your database as that used for your main account with your host. Always try to use a different password.
|  More than 10 lines are added at the begining of this script and at the end as a security measure. Some hosts forget to disable \"top\" and \"tail\" which will show the first and last 10 lines of any file.
| 
+
*/
?>";

	$f1 = @fopen('/'.$path.'/ds_files/scripts/ds_config.php','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

/* ds.php */
function writeFileThree($htmlroot,$path,$dirpath,$version)
{
	$altpath = $path;
	if ($dirpath != '') $altpath = substr($path.'/'.$dirpath,0,-1);
$write = "<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds.php
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

require_once ('/$altpath/ds_files/scripts/ds_config.php');

/* CHANGE NOTHING BELOW THIS LINE */
if(DS_CTOKENON ==1 || DS_STOKENON ==1) 
{
	session_start();
}

session_cache_limiter('none');

/* Clean them variables boys  (always clean variables at the start of your script to prevent injection attacks. Always limit input to expected chars and patterns.) */
if(preg_match('/^([A-Za-z0-9.?=_\-\/:\s(%20)]{1,255})$/', stripslashes(\$_SERVER['HTTP_REFERER']), \$matchref)) {\$tempvar = \$matchref[0];}else{\$tempvar='NoRef';}
define('HTTP_REF', \$tempvar);

if(preg_match('/^([0-9.]{7,24})$/', stripslashes(\$_SERVER['REMOTE_ADDR']), \$matchadd)) {\$tempvar = \$matchadd[0];}else{\$tempvar='1.1.1.1';}
define('DS_RADDR', \$tempvar);

if(preg_match('/^([A-Za-z0-9._\-\/:\s]{1,164})$/', stripslashes(\$_GET['p']), \$matches)) {\$p = \$matches[0];}else{sendToBrowser(DS_BMESS1);exit;}

if(preg_match('/^([A-Za-z0-9=]{100,300})$/', stripslashes(\$_GET['t']), \$matchtwo)) {\$t = \$matchtwo[0];}else{sendToBrowser(DS_BMESS2);exit;} 
/* All clean mom */


/* The magic begins */
if (checkToken(\$t) == true) 
{
		
	\$chkfil = makeTree(DS_FILEPATH); // recursive loop through the path, looking at all files in all subdirectories.
	\$found = 0;
	
	foreach (\$chkfil as \$val) // look for a match for the requested file
	{
		if(strstr(\$p, '/')) 
		{
			\$filter = after(DS_FILEPATH.'/', \$val); // get rid of the leading user directory info and focus on everything under ds_files/files
			if (\$p == \$filter) 
			{
				\$fil = \$val;
				\$found=1; 
				break;
			}
		}
		else
		{
			if(preg_match('/('.\$p.')/', \$val)) // must be a call from m.php rather than mplus.php or be in the top level directory.
			{
				\$fil = \$val;
				\$found=1; 
				break;
			}
		}	
	}
	
	/* file not found report error */
	If (\$found == 0) 
	{
		sendToBrowser(DS_BMESS3);
		reportError(0,DS_RADDR,\$p,DS_EMESS1);
		exit;
	}
	
	if(DS_BWON ==1) bandwidthLimit(\$fil,\$p); // run bandwidth checker function
	
	if (DS_DLON ==1) writeLog(\$p,\$fil); // Txt file detail logging.
	if (DS_COUNTON == 1) countLog(\$fil); // Text file qty logging.
	if (DB_ON == 1) dbLog(\$fil); // Database qty logging.
	
	session_write_close();
	
	/*IE Bug in download name workaround */
	if(isset(\$_SERVER['HTTP_USER_AGENT']) && preg_match('/MSIE/', \$_SERVER['HTTP_USER_AGENT'])) {
		@ini_set( 'zlib.output_compression','Off' );
	}
	   
	\$fn = basename(\$fil);
	header('Content-Type: application/octet-stream');
	header(\"Content-Disposition: attachment; filename=\\\"\$fn\\\"\");
	header('Content-Length: '.filesize(\$fil));
	@readfile(\$fil);
	
	exit();
	
} 
else 
{
	/* Tells people where to go, and reports the token failure. */
	sendToBrowser(DS_TFAILMESS);
	if(DS_TFAIL == 1) 
	{
		reportError(1,DS_RADDR,\$p,DS_EMESS3);
	}
	exit;
}

function after (\$this, \$inthat)
{
	if (!is_bool(strpos(\$inthat, \$this)))
	return substr(\$inthat, strpos(\$inthat,\$this)+strlen(\$this));
}

/* loop to cycle through list looking for good match */
function checkToken(\$passtoken='') 
{
	if(DS_TOKENON == 0) return true; // ignore token checks if the option is turned off
	
	\$currhour = date('Ymd');
	\$token['time'] = time();
	\$passtoken = unserialize(base64_decode(\$passtoken));
	
	if(DS_CTOKENON == 1) 
	{
		if (\$passtoken['ctoken'] != \$_COOKIE['ctoken'])
		{
			return false;
		}
	}
	
	if(DS_STOKENON == 1)
	{
		if (!isset(\$_SESSION['stoken']) && \$passtoken['stoken'] != \$_SESSION['stoken'])
		{
			return false;
		}
	}
	
	\$list = unserialize(DS_TOKENLIST);
	\$x=0;
	foreach(\$list as \$x=>\$val) 
	{
		\$token['hash'] = sha1(\$val.\$currhour);
		if (\$token['hash'] == \$passtoken['hash']) // check hashed token to see if it matches
		{ 
			/*open log file and record secret word and http referer. Only if it is not primary dl site */
			if((\$x > 0) && (DS_TRON == 1)) 
			{
				if (file_exists(DS_TRLOG)) 
				{
					\$fp = fopen(DS_TRLOG, 'a+');
					fwrite(\$fp, \$val.'|'.HTTP_REF.'|'.\$currhour.\"\\n\");
					fclose(\$fp);
				}
				else 
				{
					reportError(2,DS_RADDR,DS_TRLOG,DS_EMESS4);
				}
			}
			
			if ((\$token['time']-\$passtoken['time']) < DS_ACTIVEDL) // check if the time frame has expired
			{ 
				return true;
			}
		}
	}
	return false;
}

/* find all files in all subdirectories and put in array. */
function makeTree(\$path)
{ 
	\$list=array();
	\$handle=opendir(\$path);
	while(\$a=readdir(\$handle)) {
		if(!preg_match('/^\./',\$a)) {
			\$full_path = \$path.'/'.\$a;
			if(is_file(\$full_path)) { \$list[]=\$full_path; }
			if(is_dir(\$full_path)) {
				\$recursive=makeTree(\$full_path);
				for(\$n=0; \$n<count(\$recursive); \$n++) {
					\$list[]=\$recursive[\$n];
				}
			}
		}
	}
	closedir(\$handle);
	return \$list;
}

/* bandwidth limiter function */
function bandwidthLimit(\$fil,\$p)
{
	/* Initialize variables just in case they mysteriously go missing. It has happened before I tell you! */
	\$s = 0;
	\$t = time()-100;
	\$i = 1;
	\$r = 0;
	\$c = array();
	
	/* Load the dynamic memory */
	if(file_exists(DS_BWLOG)){
		include (DS_BWLOG); // variables in this file are s,t,i,r and c
	} 
	else {
		reportError(3,DS_RADDR,DS_BWLOG,DS_EMESS4);
	}
	
	/* setup a bunch of variables */
	\$tempaddr = DS_RADDR; // grab the ip address and stick it in a var because we can not use a constant below.
	\$foundit = 0; // used in checking the number of clicks per user below.
	\$pothole = 0; // flag for bandwidth limit check
	
	\$Addsize = @filesize(\$fil);
	\$CurrentTime = time();
	\$CurrentInterval = ceil((\$CurrentTime - \$t) / DS_INTLGTH);
	\$IntervalBandwidthRate = DS_ABLIMIT / DS_ATLIMIT * DS_INTLGTH;
	\$AvailableBandwidth = (\$CurrentInterval * \$IntervalBandwidthRate);
	
	/* Reset everything since the absolute time limit has passed */
	if (\$CurrentTime >= (DS_ATLIMIT + \$t)) {
		\$s = 0 + (\$Addsize * DS_RATIO);
		\$t = \$CurrentTime;
		\$i = 1;
		\$r = 0;
		\$CurrentInterval=1;
		\$c =array();
	}
	/* otherwise just update the bandwidth used */
	else {
		\$s = \$s + (\$Addsize * DS_RATIO);
	}
	
	/* Check the transfer thus far, make sure it's not over the limit.  */
	if((\$s >= DS_ABLIMIT) || (\$s >= \$AvailableBandwidth))  {
		if(\$s >= DS_ABLIMIT) {
			sendToBrowser(DS_BWMESSFULL);
			if (\$r == 0) {
				if (DS_BWALERT == 1) {
					reportError(4,DS_RADDR,\$p,DS_EMESS2);
					\$r = 1;
				}
			}
		} 
		else { 
			sendToBrowser(DS_BWMESS);
			\$pothole = 1;
		}
		\$s = \$s - (\$Addsize * DS_RATIO);
	}
	
	/* If we are currently in a new interval, reset userclicks */
	if ((\$CurrentInterval > \$i) && (\$r == 0)) {
		\$writevars = \"<?php\\n\";
		\$writevars .=\"\\\$s=\$s;\\n\";
		\$writevars .=\"\\\$t=\$t;\\n\";
		\$writevars .=\"\\\$i=\$CurrentInterval;\\n\";
		\$writevars .=\"\\\$r=\$r;\\n\";
		\$writevars .= \"\\\$c['\$tempaddr']['\$p']=1;\\n\";
		\$writevars .= \"?>\";
	}
	/* otherwise use this one which mantains userclicks */
	else {
		/* let us make sure they have not been mad clickers, if they have, then no download for you!! */
		\$writeuser='';
		foreach (\$c as \$k=>\$v) {
			foreach (\$v as \$a=>\$value) {
				if (\$k == \$tempaddr && \$a == \$p) { // see if there is a match to the user ip and the file
					if (\$value >= DS_DLQTY) { // check the count
						sendToBrowser(DS_DLMESS); 
						exit;
					}
					 \$value++;
					 \$foundit =1;
				}
				\$writeuser .= \"\\\$c['\$k']['\$a']=\$value;\\n\"; // add current users to array
			}
		}
		if(\$foundit == 0)  \$writeuser .= \"\\\$c['\$tempaddr']['\$p']=1;\\n\"; // add new user to array
		
		/* constuct the updated file */
		\$writevars = \"<?php\\n\";
		\$writevars .=\"\\\$s=\$s;\\n\";
		\$writevars .=\"\\\$t=\$t;\\n\";
		\$writevars .=\"\\\$i=\$i;\\n\";
		\$writevars .=\"\\\$r=\$r;\\n\";
		if(is_array(\$writeuser)) {
			foreach (\$writeuser as \$b) {
				\$writevars .= \$b;
			}
		}
		else {
			\$writevars .= \$writeuser;
		}
		\$writevars .= \"?>\";
	}
	
	\$f1 = fopen(DS_BWLOG,'w'); 
	fwrite (\$f1, \$writevars);
	fclose (\$f1);
	
	if((\$pothole == 1) || (\$r == 1)) {
		exit;
	}
	return;
}

/* error reporting to admin */
function reportError(\$errortype,\$userIP,\$pfile,\$msg)
{
	/* 
	first check the last 5 lines of the error log and the unix time stamp. 
	If there is a match within the last 5 entries, then check the time, 
	if the time is less than a day, then do not record, and do not email, 
	otherwise if there is a match and it is old, send email, but no sense recording error again. 
	This way you will get only one email a day with the same problem, and it keeps the error log smaller.
	*/
		
	if (file_exists(DS_RPTLOG)) 
	{
		\$datetime=date('Y-M-d H:i:s'); // human readable time.
		\$timenum =  time(); // unix time
		\$lines=5;
		\$reportok = 0;
		\$emailok = 0;
		\$pos = -2;
		\$buffer = '';
		\$where = 600; // arbitrary number, must be greater than 1 to start.
	
		if(filesize(DS_RPTLOG) > 4)
		{
			\$fp = @fopen(DS_RPTLOG, 'r+');
			for(\$i=0;\$i<\$lines;\$i++)
			{
				
				while (\$buffer != \"\\n\" && \$where > 1) 
				{
				     fseek(\$fp, \$pos, SEEK_END);
				     \$buffer = fgetc(\$fp);
				     \$pos = \$pos - 1;
				     \$where = ftell(\$fp);
				}
				\$buffer = fgets(\$fp);
				
				\$tmp = explode('|', \$buffer);
				 if(\$errortype == \$tmp[0] && \$where >1)
				{
					// it is the same.
					 if(isset(\$tmp[1]) && \$tmp[1] < \$timenum-43200) // current time minus one day
					{
						\$emailok = 1; // it is ok to send email again
					}
					break;
				}
				else
				{
					\$reportok = 1; // ok to update report file
				}
			}
			fclose(\$fp);
		}
		else
		{
			\$reportok = 1;
		}
		
		if(\$reportok == 1)
		{
			if(filesize(DS_RPTLOG) >= DS_RPTLOGSIZE) 
			{
				if (file_exists(DS_RPTLOGARC)) 
				{
					\$fp = fopen(DS_RPTLOG, 'r');
					\$contents = fread(\$fp,filesize(DS_RPTLOG));
					fclose(\$fp);
					
					if(filesize(DS_RPTLOGARC) >= DS_RPTLOGARCSIZE) 
					{
						\$fp = fopen(DS_RPTLOGARC, 'w');
						fwrite(\$fp,\$contents);
						fclose(\$fp);
					}
					else 
					{
						\$fp = fopen(DS_RPTLOGARC, 'a+');
						fwrite(\$fp,\$contents);
						fclose(\$fp);
					}
				}
				\$fp = fopen(DS_RPTLOG, 'w');
			}
			else 
			{
				\$fp = fopen(DS_RPTLOG, 'a+');
			}
			fwrite(\$fp,\$errortype.'|'.\$timenum.'|'.\$datetime.'|'.\$msg.'|'.\$pfile.\"\\n\");
			fclose(\$fp);
		}
		
		if(\$emailok == 1)
		{
			if(DS_RPTBYEMAIL==1) 
			{		
				sendAMail(DS_EMESS6,DS_EMESS7.\$msg);
			}
		}
	}
	return;
}

/* send mail function */
function sendAMail(\$subject,\$message)
{
	\$email = DS_EMAIL;
	\$from = DS_EMESS8;
	mail(DS_EMAIL, \$subject, \$message, \"\$from <\$email>\\nX-Mailer: PHP/ . \$phpversion()\", \"-f \$email\");
}

/* record download details */
function writeLog(\$p,\$fil)
{
	//Write download log
	\$datetime=date('Y-m-d H:i:s');
	if (file_exists(DS_DLLOG)) {
		if(filesize(DS_DLLOG) >= DS_DLLOGSIZE) {
			if (file_exists(DS_DLLOGARC)) {
				\$fp = fopen(DS_DLLOG, 'r');
				\$contents = fread(\$fp,filesize(DS_DLLOG));
				fclose(\$fp);
				
				if(filesize(DS_DLLOGARC) >= (DS_DLLOGARCSIZE*DS_DLLOGARCWARN)) {
					reportError(5,DS_RADDR,DS_DLLOGARC,DS_EMESS5);
				}
				
				if(filesize(DS_DLLOGARC) >= DS_DLLOGARCSIZE) {
					\$fp = fopen(DS_DLLOGARC, 'w');
					fwrite(\$fp,\$contents);
					fclose(\$fp);
				}
				else {
					\$fp = fopen(DS_DLLOGARC, 'a+');
					fwrite(\$fp,\$contents);
					fclose(\$fp);
				}
			}
			else {
				reportError(6,DS_RADDR,DS_DLLOGARC,DS_EMESS4);
				\$fp = fopen(DS_DLLOGARC, 'w');
				fwrite(\$fp,\$contents);
				fclose(\$fp);
			}
			\$fp = fopen(DS_DLLOG, 'w');
		}
		else {
			\$fp = fopen(DS_DLLOG, 'a+');
		}
		fwrite(\$fp,\$p.'|'.DS_RADDR.'|'.HTTP_REF.'|'.filesize(\$fil).'|'.\$datetime.\"\\n\");
		fclose(\$fp);
	} 
	else {
		reportError(7,DS_RADDR,DS_DLLOG.DS_EMESS4);
		\$fp = fopen(DS_DLLOG, 'w');
		fwrite(\$fp,\$p.'|'.DS_RADDR.'|'.HTTP_REF.'|'.filesize(\$fil).'|'.\$datetime.\"\\n\");
		fclose(\$fp);
	}
	return;
}

/* record the quantity downloads */
function countLog(\$p)
{	
	\$cnt = '';
	if(file_exists(DS_COUNTLOG)){
		include (DS_COUNTLOG);
	} else {
		reportError(8,DS_RADDR,DS_COUNTLOG,DS_EMESS4);
	}
	
	
	if(!isset(\$cnt[\$p])){\$cnt[\$p] = 0;}
	\$cnt[\$p]++;
	asort(\$cnt);
	
	\$writevars = \"<?php\\n\";
	foreach(\$cnt as \$k=>\$v) {
		\$writevars .= \"\\\$cnt['\$k'] = \$v;\\n\";
	}
	\$writevars .= \"?>\";
	
	\$f1 = fopen(DS_COUNTLOG,'w'); 
	fwrite (\$f1, \$writevars);
	fclose (\$f1);
	
	return;
}

/* future function - not currently used */
function fileWrite (\$filename,\$mode,\$data)
{
	\$f1 = @fopen(\$filename,\$mode.'b');
	if(!\$f1) {
		\$f1 = @fopen(\$filename,'wb');
	}
	@fwrite (\$f1, \$data);
	@fclose(\$f1);
}
	
/* record downloads in a database */
function dbLog (\$p)
{
	\$db = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!\$db) {
		reportError(DS_EMESS9);
		reportError(9,DS_RADDR,\$p,DS_EMESS9);
		
	}

	\$db_selected = @mysql_select_db(DB_NAME, \$db);
	if (!\$db_selected) {
		reportError(10,DS_RADDR,\$p,DS_EMESSA);
	}
	
	/* check to see if there is a record for this file in the database already */
	\$query = \"SELECT \".DB_CRITERIAFIELD.\" FROM \".DB_TABLE.\" WHERE \".DB_CRITERIAFIELD.\" = '\$p'\";
	\$result = @mysql_query(\$query);
	if (!\$result) {
		reportError(11,DS_RADDR,\$p,DS_EMESSB);
	}
	
	\$chkresult = @mysql_result(\$result,0);
	
	/* if there is a record, update the downloads quantity */
	if (\$chkresult == \$p) {
		\$query = \"UPDATE \".DB_TABLE.\" SET \".DB_INCFIELD.\" = \".DB_INCFIELD.\"+1 WHERE \".DB_CRITERIAFIELD.\" = '\$p'\";
		\$result = @mysql_query(\$query);
		if (!\$result) {
			reportError(11,DS_RADDR,\$p,DS_EMESSB);
		}
	}
	/* if there is not a record, insert a new one into the database */
	else {
		\$query = \"INSERT INTO \".DB_TABLE.\" ( \".DB_CRITERIAFIELD.\" , \".DB_INCFIELD.\" )VALUES ('\$p', 1)\";
		\$result = @mysql_query(\$query);
		if (!\$result) {
			reportError(11,DS_RADDR,\$p,DS_EMESSB);
		}
	}
	
	@mysql_free_result(\$result);
}

function sendToBrowser(\$output)
{
	\$path = \$_SERVER['DOCUMENT_ROOT'];
	\$file = \$path.'/dsplus/ds.html';
	\$data = file_get_contents(\$file);
	
	\$replace = str_replace (\"<output />\", \"\$output\", \$data);
	
	echo \$replace;
}
?>";

	$f1 = @fopen("/$path/$htmlroot/dsplus/ds.php","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);

	return;
}

/* m.php */
function writeFileFour($htmlroot,$path,$dirpath,$site,$version)
{
	$altpath = $path;
	if ($dirpath != '') $altpath = substr($path.'/'.$dirpath,0,-1);
$write="<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /m.php
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

if(file_exists('/$altpath/ds_files/scripts/ds_config.php')) {include_once('/$altpath/ds_files/scripts/ds_config.php');}
else {echo 'Config file missing, please re-install.';exit;}


if(DS_CTOKENON ==1 || DS_STOKENON ==1) 
{
	session_start();
}

/* use a browser cookie to disable link sharing between browsers */
if(DS_CTOKENON == 1) 
{
	\$ctoken = md5(uniqid(rand(), true));
	setcookie('ctoken', \$ctoken, time()+DS_ACTIVEDL);
	define('CTOKEN', \$ctoken);
}

/* set up session token to help prevent offsite robots from leeching. */
if(DS_STOKENON == 1)
{
	session_start();
	\$stoken = md5(uniqid(rand(), true));
	\$_SESSION['stoken'] = \$stoken;
	define('STOKEN', \$stoken);
}

function make_token() 
{
	\$currhour = date('Ymd');
	\$token['time'] = time();
	\$token['hash'] = sha1(DS_FTOKEN.\$currhour);
	if(DS_CTOKENON ==1) \$token['ctoken'] = CTOKEN;
	if(DS_STOKENON ==1) \$token['stoken'] = STOKEN;
	\$passtoken = base64_encode(serialize(\$token));
	return \$passtoken;
}
 
\$token = make_token();

if(preg_match('/^([A-Za-z0-9._\-\/:\s]{1,164})$/', stripslashes(\$_GET['p']), \$matches)) {\$p = \$matches[0];}else{echo 'Invalid File Name!';exit;}
//header(\"Refresh: 5; URL=http://$site/dsplus/ds.php?p=\$p&t=\$token\"); // using this causes http referer variable to be lost (in the logs), so it is off by default.

\$path = \$_SERVER['DOCUMENT_ROOT'];
\$file = \$path.'/dsplus/m.html';
\$data = file_get_contents(\$file);

\$replace = str_replace (\"<filename />\", \"\$p\", \$data);
\$replace = str_replace (\"<token />\", \"\$token\", \$replace);

echo \$replace;
?>";

	$f1 = @fopen("/$path/$htmlroot/dsplus/m.php","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}


/* ds.html */
function writeFileFive($htmlroot,$path)
{
$write = 
"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
<title>Download Sentinel++</title>
<style type='text/css' media='screen'>
@import 'ds.css';
</style>
<!-- Thanks to www.bluerobot.com for the great CSS examples -->
</head>

<body>

<div id='Content'>
	<h1>Message:</h1>
	<p>
		<output />
	</p>
</div>
<div id='sLogoContent'>
	<p>Powered by:
	<a href='http://scripts.ihostwebservices.com'><img src='ds_slogo.png' alt='ds_slogo' /></a>
	</p>
</div>
</body>
</html>";


	$f1 = @fopen("/$path/$htmlroot/dsplus/ds.html","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

/* m.html */
function writeFileSix($htmlroot,$path,$site) 
{
$write = 
"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
<title>$site download center  (Powered by Download Sentinel++)</title>
<style type='text/css' media='screen'>
@import 'm.css';
</style>
</head>
<body>

<div id='Content'>
	<h3>Your download powered by:</h3>
	<h2><a href='http://scripts.ihostwebservices.com'><img src='ds_logo.png' alt='ds_logo' /></a></h2>
	<h3>Requested File:<span class='filename'> <filename /></span></h3>
	<h3><a href='http://$site/dsplus/ds.php?p=<filename />&amp;t=<token />'>Click Here to start your download</a></h3>
</div>
</body>
</html>";

	$f1 = @fopen("/$path/$htmlroot/dsplus/m.html","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

/* m.css */
function writeFileSeven($htmlroot,$path,$version)
{
$write =
"/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /m.css
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
body {
	font:11px verdana, arial, helvetica, sans-serif;
	color:#333;
	background-color:white;
	text-align:center;
}

h1 {
	color:#333;
	font:1.8em/1 Georgia, 'Times New Roman', Times, serif;
	font-weight:900;
	font-style:italic;
	text-align:center;
}
h2 {
	color:#333;
	font:1.1em/1 Georgia, 'Times New Roman', Times, serif;
	font-weight:700;
	margin:2em 0 .25em;
	text-align:center;
}
h3 {
	color:#666;
	font-size:1em;
	font-weight:800;
	margin:3em 0 .35em;
	text-align:center;
}
	
p {
	line-height:1.8;
	margin:0 0 1em;
}
#Content p+p {margin-top:-1em; text-indent:2.7em;}

ol, ul {
	margin-top:0;
	margin-bottom:1em;
	line-height:1.8;
}
#Content p+ol, #Content p+ul {margin-top:0em;}

a {
	color:#09c;
	text-decoration:none;
	font-weight:600;
}
a:link {color:#09c;}
a:visited {color:#07a;}
a:hover {background-color:white;}

#Footnotes p+p {margin-top:0; text-indent:0;}

.keyPoint {
	margin:0 0 1.5em; padding:0;
	border:1px dashed #666;
	background-color:white;
}
.keyPoint p {
	font:1.63em/1.8 Georgia, 'Times New Roman', Times, serif;
	margin:.5em 1em;
}
.keyPoint p+p {margin-top:-.5em;}

.markup {
	font:1em/1.25 monospace;
	border:1px dashed #999;
	text-indent:0 !important;
	padding:.3em;
}

.filename {
	color:#ff0000;
}

body {
	margin:50px 0px; padding:0px; /* Need to set body margin and padding to get consistency between browsers. */
	text-align:center; /* Hack for IE5/Win */
}
	
#Content {
	width:500px;
	margin:0px auto; /* Right and left margin widths set to 'auto' */
	text-align:left; /* Counteract to IE5/Win Hack */
	padding:15px;
	border:1px dashed #333;
	background-color:#eee;
}";

	$f1 = @fopen("/$path/$htmlroot/dsplus/m.css","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeDataFiles($htmlroot,$path,$dirpath)
{
	$altpath = $path;
	if ($dirpath != '') $altpath = substr($path.'/'.$dirpath,0,-1);
	// Data files
	$write = '';
	
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_rptlog.txt','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_trlog.txt','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_dllog.txt','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_dllogarchive.txt','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_rptlogarchive.txt','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$f1 = @fopen('/'.$path.'/'.$htmlroot.'/dsplus/index.html','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$write = "<?php\n?>";
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_count.php','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_bwlog.php','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$write = "<?php\n?>";
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_author.php','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$write = "<?php\n?>";
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_desc.php','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	$write = "<?php\n?>";
	$f1 = @fopen('/'.$altpath.'/ds_files/data/ds_tokens.php','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);

	return;
}

function writeLogo($htmlroot,$path)
{
// Picture is next.
$write = "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";


	$write = base64_decode($write);
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds_logo.png","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeSmallLogo($htmlroot,$path)
{
$write = "iVBORw0KGgoAAAANSUhEUgAAAFIAAAAPCAYAAAB3PJiyAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQU
AAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAABUJJREFUWEftl02P01YYhfPp7+uv2M
7XJJNMZiYMBUppaUUpGypaCRV11XZVlrNA3VddtLuu+g/6AxEg4C+cnmMnozQYJrQIgUSkI8f2vdf2c8/73vc2T09P0fjw+/8
EBPL58+fvpd6Vdy/NuPkytwY5jlIXy8zgOItxlEVYJAHmkY9Z4GNufEw8t9S+72HGc13f96t788DD3HdxwPbqIx3E7M97al+p6
rvPtnuuU0rXNE41HseIDKXnVn11Xe1174uifzbpm+9OSymy3rpkwhdAXhgl6OcOBrmLQUH1PfTiDqKgA+O14TtNOFYDttWE3
W3wP4+dBtxuk/faCOwmDBW6rbJ9pRYCtw3XasFRH8rttuB2qr4WzzWeY7fgURrHeF2EXgeh30ZsLCSRAxN2EfB8ysldR1AdS
JLEpurg7tJm10mpBfnZYoj9SYS9kcFEGofIUguR0YcRFEG6BKWPtgjPWcERgGAN0mkRZAUx9DuEKDgVJFfASpgtQhTIJizCFEiX
/cvJKPvyeX6XE9jlRLropZzQnuTiZJidC3ITwhra+prOt+/vCq2uXS3I6wQ5GXkY910UiY2EAOXG2KcrAovHLkFWECWbEhyPgOV
Ws4Io6IIo+a7uCSRBsa9Aqm+XACWP/QNXwKkzF7dLiGnkIos99AlxWFS6std7LZD6+E142yBf5thdr9eCPCl6iPkBMaFNGOIfH3
q4OLMJN0KW+HRaF83VjLZaBNEmFMLwBYMg5cRYIblyZETwui6XCeTakQK4nozy2KFD23Qsnaq2gzzB8TTElZmLC2OPk8p3iq
iwjWVxfmhvQ9gGue3SV7U/z721IJd5rwwvfdx8avDrL9fw9183S+0zb44iC19djfHpxZhusQiSzqJ8AgitynGG/UO6SzAlnzn1X6F
N6F2O31FIs/0gtnCdwO5cdhi2LYzY98ZH+dlz793S5NqwOI7jNLDYIUe+DpiXQd6EvZ1TN/vUgrw07CFnTswY1imT/Mkyxf2fL
5UfdffbCX74foo/f/8cf/x2DffuTplDDZ0kRxIg5clRdJ5xlA7oILpT4S7AmiBP+bVMB3Qn2ylvXpwb/PTN6Azcd7eHuP/jQXn+9Y0h
pgMPec9HSiUJHdp/86G9a96sSwu1ID+Z9zEa+Bj1fRSZzUWGANwG8sLBYhHjcMZyZM9gPLQx5qo+7gfMnXKd8iNd6HTLo7Er
gBEdGQkmXVaClAg8YfooUgd9Ko8dDJlGlkcpTk5yHCxCDIcuktiGYVmUs/wa9A2yLEDEthcGb3ax2c6hdecvW6h0vRbkl8sZZtO
EpY/H0NViY5ULhkcoym/Kkb5WYbuBIrRQMCwjliSBQpr3lBON8iOl1dqUqv4LZCk5ltcS9s/oMB19PsNhG1uu5jihcZCkPrLcoMhD
5DxmmYuUJdClfnruYvOq8uZVYfpfVu9akFePxpjScWXJw4/TwpOyhlNNZyjVdLHhghI06aQOekz+oVet1nKdgAl8rHKJUFUH6lo
FdeXKVb2oulIONaoMOGk+29oMfZVBgccJ1LN4XSGd0rlJ0kHGCFkW4QsgHz169NYL8TX0WpC353u4PIr5sgYLfsCcNdzMcKfC
8FqkIXc6MY7pkKPMpwwO0qDcceyvdiXzkDsZtj3kgnDYC8v/81DyuMOpdid73NVMdNSOiPcWOZ836OGoSLmQxJixz0Gq54flO
McFd1g5d1isJQ+pm6P83d/ZvG977tfZaz979gxPnz6F3Kvjed/6+PFjPHz4EE+ePCmlcx01znbfsy2i/nzQiwwePHiwM5d/AMmlM
wxI/JtJAAAAAElFTkSuQmCC";

	$write = base64_decode($write);
	$f1 = @fopen("/$path/$htmlroot/dsplus/ds_slogo.png","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeExampleFile($htmlroot,$path,$dirpath)
{
	if ($dirpath != '') $path = substr($path.'/'.$dirpath,0,-1);
	
$write = "
iVBORw0KGgoAAAANSUhEUgAAABIAAAASCAMAAABhEH5lAAAAK3RFWHRDcmVhdGlvbiBUaW1lAFN1biA4I
ERlYyAyMDAyIDIwOjQ3OjU5IC0wMDAwpQ9CTAAAAAd0SU1FB9IMCBUIFFuI7YQAAAAJcEhZcwAACyEAA
AshAaRYbs8AAAAEZ0FNQQAAsY8L/GEFAAAAsVBMVEX/AP9ze1pSY0JCUjFSWjlrc1Jre1Jjc0pzhFKEnFqMp
WOEnGN7jFpre0pSYzlaa0qcvXMpOTGctWutxnsQKSl7Wlp7jFJ7jGOUrWu1zntja0p7lFq93oRaa0KMnHPG54
xre1q11oRaY0JzhFrG55SlvXNKWjmtznuElGu91oz///+crYStvYSMnGOcrXtzjFLO55yElGOlrYyltYS1xpTG3py
91pSUpXOltZSUpXuElHPI3QyaAAAAAXRSTlMAQObYZgAAAMpJREFUeNpVjwtzgjAQhFexFUUqB1WgUiIlLTa
+4rOP///DeiERpzuTnct3O5cLQATg++d3Va65MDcDjqfzRevr5quEBVgTkdaaPTkAliiltJbsopp0RCnnUcpoRw60
p9pzaPMvlW9TrD5l2xat5002RC2l5LeEEOxVk8QD1EIKp6LIk/fsA2ll2p3i4A1lLOi5E2X+ElgU1DGi8SvvlWb5jTHx
X8z6j3HCbDYzZN63nwyDyM4OQkc4N/RH42ASPk1xV6/veYMHW/8BnO4bM9y4KNQAAAAASUVORK5CYII=
";

	$write = base64_decode($write);
	$f1 = @fopen('/'.$path.'/ds_files/files/amazed.png','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeMplusPhp($htmlroot,$path,$dirpath,$site,$version)
{
	$altpath = $path;
	if ($dirpath != '') $altpath = substr($path.'/'.$dirpath,0,-1);
$write = "<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /mplus.php
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

/* grab the config file data */
if(file_exists('/$altpath/ds_files/scripts/ds_config.php')) {include_once('/$altpath/ds_files/scripts/ds_config.php');}
else {echo 'Config file missing, please re-install.';exit;}

if(DS_CTOKENON ==1 || DS_STOKENON ==1) 
{
	session_start();
}

/* use a browser cookie to disable link sharing between browsers */
if(DS_CTOKENON == 1) 
{
	\$ctoken = md5(uniqid(rand(), true));
	setcookie('ctoken', \$ctoken, time()+DS_ACTIVEDL);
	define('CTOKEN', \$ctoken);
}

/* set up session token to prevent offsite robots from leeching. */
if(DS_STOKENON == 1) 
{
	session_start();
	\$stoken = md5(uniqid(rand(), true));
	\$_SESSION['stoken'] = \$stoken;
	define('STOKEN', \$stoken);
}

/* parse the html file or the cache if there is one*/
\$data = @file_get_contents('mplus.html');
if(!\$data) {echo 'mplus.html file missing, please re-install.';exit;}

define('DIR_LINK', extract_string(\$data, '<dirlink>', '</dirlink>'));
define('FILE_LINK', extract_string(\$data, '<filelink>', '</filelink>'));
define('TOKEN', make_token());
\$preloop = extract_string(\$data, '<preloop>', '</preloop>');
\$postloop = extract_string(\$data, '<postloop>', '</postloop>');
\$head = before('<preloop>', \$data);
\$foot = after('</postloop>',\$data);

/* build the content */
\$list = makeTree(DS_FILEPATH);
\$list = array_reverse(\$list);
\$content = makeContent(\$list);
\$display = buildDisplay(\$content);

/* display the content */
echo \$head;
echo \$preloop;
echo \$display;
echo \$postloop;
echo \$foot;


function makeContent(\$list)
{
	\$cnt = '';
	\$desc = '';
	\$athr = '';
	@include_once (DS_DATADIR.'ds_count.php');
	@include_once (DS_DATADIR.'ds_desc.php');
	@include_once (DS_DATADIR.'ds_author.php');
	
	foreach(\$list as \$fileandpath)
	{
		\$size = get_file_size(filesize(\$fileandpath));
		\$mdate = date('d\-M\-Y\ ga ', filemtime(\$fileandpath)); // the file modified date, formatted.
		\$filter = after(DS_FILEPATH.'/', \$fileandpath); // get rid of the leading user directory info and focus on everything under ds_files
		\$splitdirs = explode('/',\$filter); // directories
		\$_file = end(\$splitdirs); // the file
		\$downloads = 0;
		\$description = '';
		\$author = '';	
	
		// find the download count for this file 
		foreach(\$cnt as \$kfile=>\$vcount)
		{
			if(\$kfile == \$fileandpath)
			{
				\$downloads = \$vcount;
				break;
			}
			else
			{
				\$downloads = 0;
			}
		}
		
		// find the description for this file 
		foreach(\$desc as \$kfile=>\$vdesc)
		{
			if(\$kfile == \$fileandpath)
			{
				\$description = \$vdesc;
				break;
			}
			else
			{
				\$description = '';
			}
		}
	
		// find the author for this file 
		foreach(\$athr as \$kfile=>\$vauthor)
		{
			if(\$kfile == \$fileandpath)
			{
				\$author = \$vauthor;
				break;
			}
			else
			{
				\$author = '';
			}
		}
	
		\$incr = 0; // need this added to the file name value to make every file name unique, so you can have the same file name in different directories
		foreach(\$splitdirs as \$dirlist_key=>\$dirlist_value)
		{
			\$incr++;
			if(\$dirlist_value == \$_file) 
			{
				\$store_dir_and_position[\$dirlist_value.':'.\$incr.':'.\$filter] = 'file:'.\$dirlist_key.':'.\$description.':'.\$author.':'.\$downloads.':'.\$size.':'.\$mdate;
			}
			else
			{
				\$store_dir_and_position[\$dirlist_value.':'.\$incr] = 'dir:'.\$dirlist_key.':::::'.\$mdate;
			}
		}
	}

	return \$store_dir_and_position;

}

function buildDisplay(\$store_dir_and_position)
{
	\$what_am_i_and_where_am_i = 'dir:0'; // intialize value
	foreach (\$store_dir_and_position as \$skey=>\$svalue)
	{
		\$skey_filename = explode(':', \$skey);
		\$skey = \$skey_filename[0]; // take off the extra number we added in loop above
		\$filepathlink =  \$skey_filename[2];
		
		\$endstring = '';
		\$startstring = '';
		\$where_was_i = \$what_am_i_and_where_am_i[1];
		
		\$what_am_i_and_where_am_i = explode(':',\$svalue); 
		\$description = \$what_am_i_and_where_am_i[2];
		\$author = \$what_am_i_and_where_am_i[3];
		\$downloads = \$what_am_i_and_where_am_i[4];
		\$size = \$what_am_i_and_where_am_i[5];
		\$mdate = \$what_am_i_and_where_am_i[6];
		
		if(DS_UNQTKN == 1)
		{
			\$token = make_token();
		}
		else
		{
			\$token = TOKEN;
		}
		
		// close previous one based on the difference between the levels. \$where_am_i minus \$what_am_i_and_where_am_i[1]
		\$qtyOfCloses = \$where_was_i - \$what_am_i_and_where_am_i[1];
		
		if(\$qtyOfCloses > 0 )
		{
			for(\$q=0; \$q<\$qtyOfCloses; \$q++)
			{
				\$endstring .= '</ul></li>';
			}
		}
		else
		{
			\$endstring .= '';
		}
		
		if(\$what_am_i_and_where_am_i [0] == 'dir')
		{
			\$replace = str_replace ('<dirname />', \$skey, DIR_LINK);
			\$startstring = '<li>'.\$replace.'<ul>';
		}
		else
		{
			\$replace = str_replace ('<filename />', \$skey, FILE_LINK);
			\$replace = str_replace ('<filepathlink />', \$filepathlink, \$replace);
			\$replace = str_replace ('<token />', \$token, \$replace);
			\$replace = str_replace ('<filesize />', \$size, \$replace);
			\$replace = str_replace ('<downloads />', \$downloads, \$replace);
			\$replace = str_replace ('<date />', \$mdate, \$replace);
			\$replace = str_replace ('<author />', \$author, \$replace);
			\$replace = str_replace ('<description />',\$description,\$replace);
			
			
			\$startstring = '<li>'.\$replace.'</li>';
		}
		
		\$display .= \$endstring.\$startstring;	
	}


	for(\$q=0; \$q<\$what_am_i_and_where_am_i[1]; \$q++)
	{
		\$endstring .= '</ul></li>';
	}
	
	\$display .= \$endstring;
	return \$display;
}
	
function extract_string(\$str, \$start, \$end) 
{
	\$str_low = strtolower(\$str);
	if (strpos(\$str_low, \$start) !== false && strpos(\$str_low, \$end) !== false) {
		\$pos1 = strpos(\$str_low, \$start) + strlen(\$start);
		\$pos2 = strpos(\$str_low, \$end) - \$pos1;
		return substr(\$str, \$pos1, \$pos2);
	}
} 

function before (\$this, \$inthat)
{
	return substr(\$inthat, 0, strpos(\$inthat, \$this));
}
 
function after (\$this, \$inthat)
{
	if (!is_bool(strpos(\$inthat, \$this)))
	return substr(\$inthat, strpos(\$inthat,\$this)+strlen(\$this));
}

function get_file_size(\$size) 
{
	\$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for (\$i = 0; \$size > 1024; \$i++) { \$size /= 1024; }
	return round(\$size, 2).\$units[\$i];
}

function make_token() 
{
	\$currhour = date('Ymd');
	\$token['time'] = time();
	\$token['hash'] = sha1(DS_FTOKEN.\$currhour);
	if(DS_CTOKENON ==1) \$token['ctoken'] = CTOKEN;
	if(DS_STOKENON ==1) \$token['stoken'] = STOKEN;
	\$passtoken = base64_encode(serialize(\$token));
	return \$passtoken;
}

function makeTree(\$path)
{ 
	\$list=array();
	\$handle=opendir(\$path);
	while(\$a=readdir(\$handle)) {
		if(!preg_match('/^\./',\$a)) {
			\$full_path = \$path.'/'.\$a;
			if(is_file(\$full_path)) { \$list[]=\$full_path; }
			if(is_dir(\$full_path)) {
				\$recursive=makeTree(\$full_path);
				for(\$n=0; \$n<count(\$recursive); \$n++) {
					\$list[]=\$recursive[\$n];
				}
			}
		}
	}
	closedir(\$handle);
	return \$list;
}
?>";
	$f1 = @fopen("/$path/$htmlroot/dsplus/mplus.php","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeMplusHtml($htmlroot,$path,$site)
{
$write = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
	<!--Uncomment the line below to stop token failures in the error log caused by robots -->
	<!--<meta name='robots' content='nofollow'>-->
	<title>www.ihostwebservices.com download center  (Powered by Download Sentinel++)</title>
	<style type='text/css' media='screen'>
	@import 'mplus.css'; 
	</style>
	<script type='text/javascript' src='pde.js'></script>
</head>
<body>
	<div id='Header'>
		<img src='ds_logo.png' alt='logo' />
		<h1>Welcome to My Downloads</h1>
	</div>
	<div id='Content'>
		<div id='poweredby'><a href='http://scripts.ihostwebservices.com'><img src='ds_slogo.png' alt='DS logo' /></a></div>
		<div class='links'>
		<preloop><ul id='nav'></preloop>
					<dirlink><a href='#'><dirname /></a></dirlink>
					<filelink><a href='http://$site/dsplus/ds.php?p=<filepathlink />&amp;t=<token />'><em><filename /></em>
							<span><br />
								<em>Author:</em> <author /><br />
								<em>Filesize:</em> <filesize /><br />
								<em>Downloads:</em> <downloads /><br />
								<em>Date:</em> <date /><br />
								<em>Description:</em> <description />
							</span>
						</a>
					</filelink>
		<postloop></ul></postloop>
		</div>
	</div>
</body>
</html>";

	$f1 = @fopen("/$path/$htmlroot/dsplus/mplus.html","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeMplusCss($htmlroot,$path,$version)
{
$write = 
"/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /mplus.css
|
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/
body {
	margin:50px 0px; padding:0px; /* Need to set body margin and padding to get consistency between browsers. */
	font:11px verdana, arial, helvetica, sans-serif;
	color:#777;
	background-color:black;
	text-align:center; /* Hack for IE5/Win */
}

h1 {
	color:#666;
	font-size:3em;
	font-weight:800;
	margin:0em 0 .35em;
	text-align:center;
}

p {
	color:#DDD;
	line-height:1.0;
	margin:0em 0 0 0em;
}
p+p {color:#EEE;}
#Content p+p {margin-top:0em; text-indent:0em;}


ol, ul {
	margin-top:0;
	margin-bottom:1em;
	line-height:1.8;
}
#Content p+ol, #Content p+ul {margin-top:0em;}

a {color:#000;text-decoration:none;font-weight:600; position: relative;}
a:link {color:#000;}
a:visited {color:#000;}

.links a:hover {color: #411; background: #AAA; position: relative;}
	   
.links a span {display: none;}

.links a:hover span {display: block;
   position: absolute; top: -6em; left: 30em; width: 300px;
   padding: 1px; margin: 1px; z-index: 100;
   color: #777; background: black;
   font: 1em Verdana, sans-serif; text-align: left;}
   
	
#Content {
	width:60em;
	margin:0px auto; /* Right and left margin widths set to 'auto' */
	text-align:left; /* Counteract to IE5/Win Hack */
	padding:15px;
	background-color:#000;
}

em {
	color:#777;
	font-style: normal;
}

span em {
	color:#888;
	font-weight:600;
}

/* main list without Javascript */
		ul#nav{
			width:4em;
			margin:0;
			background:#000;
			padding:.5em;
			list-style-type:square;
		}
		ul#nav ul{
			margin:0 0 0 1em;
			padding:0;
		}
		ul#nav a{
			text-decoration:none;
			color:#666;
		}
		
		ul#nav a,ul strong{
			width:20em;
			text-decoration:none;
			color:#FF0000;
			display:block;
			padding:0 0 0 1em;
		}

/* main list with Javascript */
		ul#nav.pde_nav{
			padding:.5em;
			list-style-type:none;
		}
		ul#nav.pde_nav ul{
			padding:0;
		}
		ul#nav.pde_nav li{
			margin:0;
			padding:0;
			height:1em;		
			list-style-type:none;
		}
		html>body ul#nav.pde_nav li{
			height:auto;
		}
		ul.pde_nav a,ul.pde_nav strong{
			width:20em;
			text-decoration:none;
			color:#FF0000;
			display:block;
			padding:0 0 0 1em;
		}
		
/* Classes added to show and hide and to indicate active state */
		.pde_hide{display:none;}
		.pde_show{display:block;}
		.pde_parent{background:url(plus.gif) 0 50% no-repeat transparent;}
		.pde_active{background:url(minus.gif) 0 50% no-repeat transparent;}
";
	$f1 = @fopen("/$path/$htmlroot/dsplus/mplus.css","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writePdeJs($htmlroot,$path)
{
$write =
"/*
	PureDOM explorer
	written by Christian Heilmann (http://icant.co.uk)
	Please refer to the pde homepage for updates: http://www.onlinetools.org/tools/puredom/
	Free for non-commercial use. Changes welcome, but no distribution without 
	the consent of the author.
*/
function pde_init()
{

/* CSS class names, change if needed */
	var mp='pde_nav';
	var hp='pde_hide';
	var sp='pde_show';
	var pp='pde_parent';
	var pa='pde_active';
	var cu='current';
	
	var d,uls,i;
	if(!document.getElementById && !document.createTextNode){return;}

/* navigation ID, change if needed */
	d=document.getElementById('nav');

	if (!d){return;}
	pde_addclass(d,mp)
	uls=d.getElementsByTagName('ul');
	for (i=0;i<uls.length;i++)
	{
		if(pde_checkcurrent(uls[i]))
		{
			pde_addclass(uls[i].parentNode.firstChild,pa);
		} else {
			pde_addclass(uls[i],hp);
			pde_addclass(uls[i].parentNode.firstChild,pp);
			uls[i].parentNode.firstChild.onclick=function()
			{
				pde_swapclass(this,pp,pa);
				pde_swapclass(this.parentNode.getElementsByTagName('ul')[0],hp,sp);
				return false;
			}
		}
	}
	function pde_checkcurrent(o){
		if(pde_check(o.parentNode,cu)){return true;}
		for(var i=0;i<o.getElementsByTagName('li').length;i++)
		{
			if(pde_check(o.getElementsByTagName('li')[i],cu)){return true;}
		}
		return false;
	}
	function pde_swapclass(o,c1,c2)
	{
		var cn=o.className
		o.className=!pde_check(o,c1)?cn.replace(c2,c1):cn.replace(c1,c2);
	}
	function pde_addclass(o,c)
	{
		if(!pde_check(o,c)){o.className+=o.className==''?c:' '+c;}
	}
	function pde_check(o,c)
	{
	 	return new RegExp('\\\\b'+c+'\\\\b').test(o.className);
	}
}
window.onload=function(){
	pde_init();
	// add other functions here.
}";

	$f1 = @fopen("/$path/$htmlroot/dsplus/pde.js","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeMinusGif($htmlroot,$path)
{
$write = "R0lGODlhCgAKAMQbAOOQI4BJAY5RAdp8AuGJFaReAdF3AuqqVq9kAo9SAdl8AuqrWL9tAsJvAp1ZAeedPuCEC8VwAs92Ar1sAoNLAaVeAeuuXtx+AuqrWeOQJN5/AgAAAAAAAAAAAAAAAAAAACH5BAEAABsALAAAAAAKAAoAAAUs4CaO5LZYWHo8kAhkb0ZEzqhd2sAI5H0ZlUBJo5jwhpKCsLRpJJgiBAUKDQEAOw==";

	$write = base64_decode($write);
	$f1 = @fopen("/$path/$htmlroot/dsplus/minus.gif","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writePlusGif($htmlroot,$path)
{
$write = "R0lGODlhCgAKAMQfAOGIFOacPOWYM2w9AeWWMOGGD+GJFbpqAq1jAuCDCeWXMuSVLdl8AumlTc92AqJcAd+AA45RAbhpAt+BBY9SAeKNHZtYAeiiRuGFDuabOdp8AuOQJOqtXLxrAn5IAQAAACH5BAEAAB8ALAAAAAAKAAoAAAUt4CeO5Mc15XhlQpAqFbC1JGFASQEUjGhrjs6DEhEtJgeLZ0DCIJYpiSdFJYUAADs=";
	
	$write = base64_decode($write);
	$f1 = @fopen("/$path/$htmlroot/dsplus/plus.gif","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeHtaccess($htmlroot,$path,$dirpath)
{
	if ($dirpath != '') $path = substr($path.'/'.$dirpath,0,-1);
	
$write = 
"Options -Indexes
deny from all
";
	$f1 = @fopen('/'.$path.'/ds_files/htaccess.txt','w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	@rename ('/'.$path.'/ds_files/htaccess.txt' , '/'.$path.'/ds_files/.htaccess');
	
	return;
}

function writeAdminFile($htmlroot,$path,$dirpath,$version)
{
	$altpath = $path;
	if ($dirpath != '') $altpath = substr($path.'/'.$dirpath,0,-1);

$write="<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds_admin.php
|	
|	Version: >>$version<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

/* grab the config file data */
if(file_exists('/$altpath/ds_files/scripts/ds_config.php')) {include_once('/$altpath/ds_files/scripts/ds_config.php');}
else {echo 'Config file missing, please re-install.';exit;}

@ini_set('session.use_trans_sid', false); // same as below... one of these aught to work.
@ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src'); // fix for phpsessid  being inserted after form element causing invalid xhtml

if(preg_match('/^([A-Za-z0-9.:\/_\-]{1,40})$/', stripslashes(\$_SERVER['SCRIPT_NAME']), \$matchscript))
{
	\$_SERVER['SCRIPT_NAME'] = \$matchscript[0];
}
else
{
	writeXhtml('Error: Script name has disallowed characters'); exit;
}

/* used for determining the user action. */
if(preg_match('/^([a-z]{4,12})$/', stripslashes(\$_POST['mode']), \$matchover)) \$act = \$matchover[0];


session_start();

switch (\$act) 
{
	case 'cancel':
	   cancel();
	   break;
	case 'overwrite':
	   overwrite();
	   break;
	case 'owconfirm':
	   owConfirm();
	   break;
	case 'adminenter':
	   adminEnter();
	   break;
	case 'config':
	   config();
	   break;
	case 'author':
	   author();
	   break;
	case 'writeauthor':
	   writeAuthor();
	   break;
	case 'desc':
	   description();
	   break;
	case 'writedesc':
	   writeDescription();
	   break;
	default:
	   entrance();
	   break;	  
}

function entrance()
{
	\$output = '<h3>Please enter your secret word to enter the Admin section.</h3>
		<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
		<p><input type=\"hidden\" name=\"mode\" value=\"adminenter\" /></p>
		<p><input type=\"password\" name=\"secretkey\" /></p>
		<p><input type=\"submit\" value=\"Continue\" /></p>
		</form>';
		writeXhtml(\$output,'** Admin Enter **');
		exit;
}

function cancel()
{
		writeXhtml('<b>Installation Cancelled</b>');
		exit;
}
	
function overwrite()
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) 
	{
		if(is_file('install.php')) 
		{
			\$output = '<h3>Download Sentinel++ Re-Install - Confirm!</h3>
					<h3>WARNING - This will overwrite all files! If you have made any changes or customizations, back them up now!</h3>
					<h3> Please enter your secret word to confirm the overwrite.</h3>
					<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
					<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>
					<p><input type=\"hidden\" name=\"mode\" value=\"owconfirm\" /></p>
					<p><input type=\"password\" name=\"secretkey\" /></p>
					<p><input type=\"submit\" value=\"Continue\" /></p>
					</form>';
			writeXhtml(\$output,'Re-Install');
			exit;
		}
		else {writeXhtml ('Install file missing, please upload it to the dsplus directory.');exit;}
	}
}

function owConfirm()
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) 
	{
		if(preg_match('/^([A-Za-z0-9]{8,20})$/', stripslashes(\$_POST['secretkey']), \$matchsecret)) 
		{
			\$secret = \$matchsecret[0];
			\$secret = sha1(\$secret);
		}

		if (DS_FTOKEN == \$secret) 
		{
			\$owtoken = md5(uniqid(rand(), true));
			\$_SESSION['owtoken'] = \$owtoken;
			setcookie('owctoken', \$owtoken, time()+600);
			
			\$output = '<h3>Download Sentinel++ Re-Install - Confirmed!</h3>
				<h3> Overwite authority confirmed. Click continue.</h3>
				<form method=\"post\" action=\"install.php\">
				<p><input type=\"hidden\" name=\"owtoken\" value=\"'.\$owtoken.'\" /></p>
				<p><input type=\"submit\" value=\"Continue\" /></p>
				</form>';
			writeXhtml(\$output,'Re-Install');
			exit;
		}
		else
		{
			\$output = '<h3>The secret word you entered was incorrect. Please try again.</h3>
			<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
			<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>
			<p><input type=\"hidden\" name=\"mode\" value=\"owconfirm\" /></p>
			<p><input type=\"password\" name=\"secretkey\" /></p>
			<p><input type=\"submit\" value=\"Continue\" /></p>
			</form>';
		
			writeXhtml(\$output,'**ERROR**');
			exit;
		}
	}
	writeXhtml('Session Failure');
	exit;
}

function adminEnter()
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) 
	{
		adminFunction(\$_SESSION['admintoken']);
	}
	else
	{
		if(preg_match('/^([A-Za-z0-9]{8,20})$/', stripslashes(\$_POST['secretkey']), \$matchsecret)) 
		{
			\$secret = \$matchsecret[0];
			\$secret = sha1(\$secret);
		}

		if (DS_FTOKEN == \$secret) 
		{
			\$admintoken = md5(uniqid(rand(), true));
			\$_SESSION['admintoken'] = \$admintoken;
			adminFunction(\$admintoken);
		}
		else
		{
			\$output = '<h3>The secret word you entered was incorrect. Please try again.</h3>
			<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
			<p><input type=\"hidden\" name=\"mode\" value=\"adminenter\" /></p>
			<p><input type=\"password\" name=\"secretkey\" /></p>
			<p><input type=\"submit\" value=\"Continue\" /></p>
			</form>';
		
			writeXhtml(\$output,'**ERROR**');
			exit;
		}
	}
}

function config() 
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) 
	{
		// display the config file for editing
		\$output = '<h3>In development.</h3>
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$token.'\" /></p>
				
				<p><input type=\"submit\" value=\"Continue\" /></p>
				</form>';
			
		writeXhtml(\$output,'**Config - Incomplete**');
		exit;
	}
	exit;
}

function author()
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) 
	{
		if(file_exists(DS_DATADIR.'/ds_author.php')) {include_once(DS_DATADIR.'/ds_author.php');}
		else {writeXhtml('Author file missing, please re-install.','** Error **');exit;}
		
		\$pr = '<h3>Author Names</h3>
			<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
			<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>
			<p><input type=\"hidden\" name=\"mode\" value=\"writeauthor\" /></p>';
		
		\$afilelist = makeTree(DS_FILEPATH);
		\$afilelist = array_reverse(\$afilelist);
		foreach(\$afilelist as \$fileandpath)
		{
			\$authorfound = 0;
			\$filter = after(DS_FILEPATH.'/', \$fileandpath);
			
			foreach(\$athr as \$kfile=>\$vdesc)
			{
				if(\$kfile == \$fileandpath)
				{
					\$authorfound = 1;
					break;
				}
			}
			
			if(1 == \$authorfound) 
			{
				\$pr .= '<p><span class=\"filename\">File: '.\$filter.'</span> <input type=\"text\" size=\"110\" maxlength=\"254\" name=\"pdata['.\$kfile.']\" value=\"'.\$vdesc.'\" /></p>';
			}
			else 
			{
				\$pr .= '<p><span class=\"filename\">File: '.\$filter.'</span> <input type=\"text\" size=\"110\" maxlength=\"254\" name=\"pdata['.\$fileandpath.']\" value=\"\" /></p>';
			}
			\$pr .= \"\\n\";
		}
		\$pr .= '<p><input type=\"submit\" value=\"Continue\" /></p>
			</form>';
			
		writeXhtml(\$pr,'**Authors**');
		exit;
		
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}

function writeAuthor()
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) {
		\$newauthor = '';
		
		\$output .= '<h3>Disallowed Character in author name - Please only use alphanumeric, dashes, underscores, dots, @, commas, or spaces.</h3>
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>
				<p><input type=\"hidden\" name=\"mode\" value=\"writeauthor\" /></p>';
			
		\$charfail = 0;
		foreach(\$_POST['pdata'] as \$postfile=>\$dwrite) 
		{
			if(preg_match('/^([a-zA-Z0-9_@,\-.\s]{1,255})$/', stripslashes(\$dwrite), \$matches) || empty(\$dwrite)) 
			{
				\$dwrite = \$matches[0];
			}
			else 
			{
				\$charfail = 1;
				\$output .= '<p><span class=\"errormess\">'.htmlspecialchars(\$postfile).'</span><span class=\"errormess\"><--Has bad author name, change below</span></p>';
				
			}
			if(preg_match('/^([A-Za-z0-9.\/:_\-\s]{1,255})$/', stripslashes(\$postfile), \$matches) || empty(\$postfile)) 
			{
				\$postfile = \$matches[0];
			}
			else 
			{
				\$charfail = 1;
				\$output .= '<p><span class=\"errormess\">'.htmlspecialchars(\$postfile).'</span><span class=\"errormess\"><--Has bad filename.</span></p>';
				
			}
			\$output .= '<p><span class=\"filename\">'.htmlspecialchars(\$postfile).'</span> <input type=\"text\" size=\"110\" maxlength=\"254\" name=\"pdata['.htmlspecialchars(\$postfile).']\" value=\"'.htmlspecialchars(\$dwrite).'\" /></p>';
			\$newauthor .= \"\\\$athr['\$postfile'] = '\$dwrite';\\n\";
			\$repostdata .= '<p><input type=\"hidden\" name=\"pdata['.\$postfile.']\" value=\"'.\$dwrite.'\" /></p>';
		}
		
		if (\$charfail==1) 
		{
			\$output .= '<p><input type=\"submit\" value=\"Continue\" /></p>
					</form>';
			writeXhtml(\$output, '**Error**');
			exit;
		}
		else
		{
			if(file_exists(DS_DATADIR.'/ds_author.php')) {include_once(DS_DATADIR.'/ds_author.php');}
			else {writeXhtml('Author file missing, please re-install.','** Error **');exit;}
			
			\$write = \"<?php\\n\";
			\$write .= \$newauthor;
			\$write .= '?>';
	
			\$f1 = @fopen(DS_DATADIR.'/ds_author.php','w');
			if (\$f1) {			
				@fwrite (\$f1, \$write);
				@fclose (\$f1);
				\$output = '<h3>Author File updated successfully
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"mode\" value=\"adminenter\" /></p>
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p> 
				<p><input type=\"submit\" value=\"Continue\" /></p>
				</form>';
				writeXhtml(\$output,'**Update Successful**');
				exit;
			}
			else {
				\$output = '<h3>File save failed. Please make sure data directory and file are writable</h3>
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>';
				\$output .= \$repostdata;
				\$output .= '<p><input type=\"hidden\" name=\"mode\" value=\"writeauthor\" /></p>
				<p><input type=\"submit\" value=\"Continue\" /></p>
				</form>';
				writeXhtml(\$output,'**Error**');
				exit;
			}
		}
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}

function description()
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) {
		
		if(file_exists(DS_DATADIR.'/ds_desc.php')) {include_once(DS_DATADIR.'/ds_desc.php');}
		else {writeXhtml('Description file missing, please re-install.','** Error **');exit;}
		
		\$pr = '<h3>File Descriptions</h3>
			<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
			<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>
			<p><input type=\"hidden\" name=\"mode\" value=\"writedesc\" /></p>';
		
		\$desclist = makeTree(DS_FILEPATH);
		\$desclist = array_reverse(\$desclist);
		foreach(\$desclist as \$fileandpath)
		{
			\$descfound = 0;
			\$filter = after(DS_FILEPATH.'/', \$fileandpath);
			
			foreach(\$desc as \$kfile=>\$vdesc)
			{
				if(\$kfile == \$fileandpath)
				{
					\$descfound = 1;
					break;
				}
			}
			
			if(1 == \$descfound) 
			{
				\$pr .= '<p><span class=\"filename\">File: '.\$filter.'</span> <input type=\"text\" size=\"110\" maxlength=\"254\" name=\"pdata['.\$kfile.']\" value=\"'.\$vdesc.'\" /></p>';
			}
			else 
			{
				\$pr .= '<p><span class=\"filename\">File: '.\$filter.'</span> <input type=\"text\" size=\"110\" maxlength=\"254\" name=\"pdata['.\$fileandpath.']\" value=\"\" /></p>';
			}
			\$pr .= \"\\n\";
		}
		\$pr .= '<p><input type=\"submit\" value=\"Continue\" /></p>
			</form>';
			
		writeXhtml(\$pr,'**Descriptions**');
		exit;
		
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}

function writeDescription() 
{
	if (isset(\$_SESSION['admintoken']) && \$_POST['admintoken'] == \$_SESSION['admintoken']) {
		\$author = '';
		\$output .= '<h3>Disallowed Character in description - Please only use alphanumeric, dashes, underscores, colons, forward slashes, or spaces.</h3>
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>
				<p><input type=\"hidden\" name=\"mode\" value=\"writedesc\" /></p>';
			
		\$charfail = 0;
		foreach(\$_POST['pdata'] as \$postfile=>\$dwrite) 
		{
			
			if(preg_match('/^([A-Za-z0-9.\/:,_\-\s]{1,255})$/', stripslashes(\$dwrite), \$matches) || empty(\$dwrite)) 
			{
				\$dwrite = \$matches[0];
			}
			else 
			{
				\$charfail = 1;
				\$output .= '<p><span class=\"errormess\">'.htmlspecialchars(\$postfile).'</span><span class=\"errormess\"><--Has bad description, change below</span></p>';
				
			}
			if(preg_match('/^([A-Za-z0-9.\/:_\-\s]{1,255})$/', stripslashes(\$postfile), \$matches) || empty(\$postfile)) 
			{
				\$postfile = \$matches[0];
			}
			else 
			{
				\$charfail = 1;
				\$output .= '<p><span class=\"errormess\">'.htmlspecialchars(\$postfile).'</span><span class=\"errormess\"><--Has bad filename.</span></p>';
				
			}
			\$output .= '<p><span class=\"filename\">'.htmlspecialchars(\$postfile).'</span> <input type=\"text\" size=\"110\" maxlength=\"254\" name=\"pdata['.htmlspecialchars(\$postfile).']\" value=\"'.htmlspecialchars(\$dwrite).'\" /></p>';
			\$newdesc .= \"\\\$desc['\$postfile'] = '\$dwrite';\\n\";
			\$repostdata .= '<p><input type=\"hidden\" name=\"pdata['.\$postfile.']\" value=\"'.\$dwrite.'\" /></p>';
		}
		
		if (\$charfail==1) 
		{
			\$output .= '<p><input type=\"submit\" value=\"Continue\" /></p>
					</form>';
			writeXhtml(\$output, '**Error**');
			exit;
		}
		else
		{
			if(file_exists(DS_DATADIR.'/ds_desc.php')) {include_once(DS_DATADIR.'/ds_desc.php');}
			else {writeXhtml('Description file missing.','** Error **');exit;}
			
			\$write = \"<?php\\n\";
			\$write .= \$newdesc;
			\$write .= \"?>\";

			\$f1 = @fopen(DS_DATADIR.'/ds_desc.php','w');
			if (\$f1) {			
				@fwrite (\$f1, \$write);
				@fclose (\$f1);
				\$output = '<h3>Description File updated successfully
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" />
				<p><input type=\"hidden\" name=\"mode\" value=\"adminenter\" /></p>
				<p><input type=\"submit\" value=\"Continue\" /></p>
				</form>';
				writeXhtml(\$output,'**Update Successful**');
				exit;
			}
			else {
				\$output = '<h3>File save failed. Please make sure data directory and file are writable</h3>
				<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
				<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$_POST[\"admintoken\"].'\" /></p>';
				\$output .= \$repostdata;
				\$output .= '<p><input type=\"hidden\" name=\"mode\" value=\"writedesc\" /></p>
				<p><input type=\"submit\" value=\"Continue\" /></p>
				</form>';
				writeXhtml(\$output,'**Error**');
				exit;
			}
		}
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}


function adminFunction(\$admintoken)
{
	// choose between editing config options, file descriptions, or author names. (config options still to-do)
	\$output = '<h3>Which would you like to do?</h3>
			<form method=\"post\" action=\"'.\$_SERVER[\"SCRIPT_NAME\"].'\">
			<p><input type=\"hidden\" name=\"admintoken\" value=\"'.\$admintoken.'\" /></p>
			<!--<p><input type=\"radio\" name=\"mode\" value=\"config\" /><span>Change Secret Word</span></p>-->
			<p><input type=\"radio\" name=\"mode\" value=\"desc\" /><span>Enter File Descriptions</span></p>
			<p><input type=\"radio\" name=\"mode\" value=\"author\" /><span>Enter Authors</span></p>
			<p><input type=\"radio\" name=\"mode\" value=\"overwrite\" /><span>Re-install</span></p>
			<p><input type=\"submit\" value=\"Continue\" /></p>
			</form>';
	writeXhtml(\$output, 'Admin Functions');
	exit;
}

function makeTree(\$path)
{ 
	\$list=array();
	\$handle=opendir(\$path);
	while(\$a=readdir(\$handle)) {
		if(!preg_match('/^\./',\$a)) {
			\$full_path = \$path.'/'.\$a;
			if(is_file(\$full_path)) { \$list[]=\$full_path; }
			if(is_dir(\$full_path)) {
				\$recursive=makeTree(\$full_path);
				for(\$n=0; \$n<count(\$recursive); \$n++) {
					\$list[]=\$recursive[\$n];
				}
			}
		}
	}
	closedir(\$handle);
	return \$list;
}

function after(\$this, \$inthat)
{
	if (!is_bool(strpos(\$inthat, \$this)))
	return substr(\$inthat, strpos(\$inthat,\$this)+strlen(\$this));
}

function writeXhtml(\$output,\$heading='Download Sentinel++')
{
\$poweredby = \"iVBORw0KGgoAAAANSUhEUgAAAFIAAAAPCAYAAAB3PJiyAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQU
AAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAABUJJREFUWEftl02P01YYhfPp7+uv2M
7XJJNMZiYMBUppaUUpGypaCRV11XZVlrNA3VddtLuu+g/6AxEg4C+cnmMnozQYJrQIgUSkI8f2vdf2c8/73vc2T09P0fjw+/8
EBPL58+fvpd6Vdy/NuPkytwY5jlIXy8zgOItxlEVYJAHmkY9Z4GNufEw8t9S+72HGc13f96t788DD3HdxwPbqIx3E7M97al+p6
rvPtnuuU0rXNE41HseIDKXnVn11Xe1174uifzbpm+9OSymy3rpkwhdAXhgl6OcOBrmLQUH1PfTiDqKgA+O14TtNOFYDttWE3
W3wP4+dBtxuk/faCOwmDBW6rbJ9pRYCtw3XasFRH8rttuB2qr4WzzWeY7fgURrHeF2EXgeh30ZsLCSRAxN2EfB8ysldR1AdS
JLEpurg7tJm10mpBfnZYoj9SYS9kcFEGofIUguR0YcRFEG6BKWPtgjPWcERgGAN0mkRZAUx9DuEKDgVJFfASpgtQhTIJizCFEiX
/cvJKPvyeX6XE9jlRLropZzQnuTiZJidC3ITwhra+prOt+/vCq2uXS3I6wQ5GXkY910UiY2EAOXG2KcrAovHLkFWECWbEhyPgOV
Ws4Io6IIo+a7uCSRBsa9Aqm+XACWP/QNXwKkzF7dLiGnkIos99AlxWFS6std7LZD6+E142yBf5thdr9eCPCl6iPkBMaFNGOIfH3
q4OLMJN0KW+HRaF83VjLZaBNEmFMLwBYMg5cRYIblyZETwui6XCeTakQK4nozy2KFD23Qsnaq2gzzB8TTElZmLC2OPk8p3iq
iwjWVxfmhvQ9gGue3SV7U/z721IJd5rwwvfdx8avDrL9fw9183S+0zb44iC19djfHpxZhusQiSzqJ8AgitynGG/UO6SzAlnzn1X6F
N6F2O31FIs/0gtnCdwO5cdhi2LYzY98ZH+dlz793S5NqwOI7jNLDYIUe+DpiXQd6EvZ1TN/vUgrw07CFnTswY1imT/Mkyxf2fL
5UfdffbCX74foo/f/8cf/x2DffuTplDDZ0kRxIg5clRdJ5xlA7oILpT4S7AmiBP+bVMB3Qn2ylvXpwb/PTN6Azcd7eHuP/jQXn+9Y0h
pgMPec9HSiUJHdp/86G9a96sSwu1ID+Z9zEa+Bj1fRSZzUWGANwG8sLBYhHjcMZyZM9gPLQx5qo+7gfMnXKd8iNd6HTLo7Er
gBEdGQkmXVaClAg8YfooUgd9Ko8dDJlGlkcpTk5yHCxCDIcuktiGYVmUs/wa9A2yLEDEthcGb3ax2c6hdecvW6h0vRbkl8sZZtO
EpY/H0NViY5ULhkcoym/Kkb5WYbuBIrRQMCwjliSBQpr3lBON8iOl1dqUqv4LZCk5ltcS9s/oMB19PsNhG1uu5jihcZCkPrLcoMhD
5DxmmYuUJdClfnruYvOq8uZVYfpfVu9akFePxpjScWXJw4/TwpOyhlNNZyjVdLHhghI06aQOekz+oVet1nKdgAl8rHKJUFUH6lo
FdeXKVb2oulIONaoMOGk+29oMfZVBgccJ1LN4XSGd0rlJ0kHGCFkW4QsgHz169NYL8TX0WpC353u4PIr5sgYLfsCcNdzMcKfC
8FqkIXc6MY7pkKPMpwwO0qDcceyvdiXzkDsZtj3kgnDYC8v/81DyuMOpdid73NVMdNSOiPcWOZ836OGoSLmQxJixz0Gq54flO
McFd1g5d1isJQ+pm6P83d/ZvG977tfZaz979gxPnz6F3Kvjed/6+PFjPHz4EE+ePCmlcx01znbfsy2i/nzQiwwePHiwM5d/AMmlM
wxI/JtJAAAAAElFTkSuQmCC\";


 echo \"
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
<title>Download Sentinel++</title>
<style type='text/css' media='screen'>
body {
	font:11px verdana, arial, helvetica, sans-serif;
	color:#333;
	background-color:white;
	margin:50px 0px; padding:0px; /* Need to set body margin and padding to get consistency between browsers. */
	text-align:center; /* Hack for IE5/Win */
}
h1 {color:#333;font:1.8em/1 Georgia, 'Times New Roman', Times, serif;font-weight:900;font-style:italic;}
h2 {color:#333;font:1.1em/1 Georgia, 'Times New Roman', Times, serif;font-weight:700;margin:2em 0 .25em;}
h3 {color:#666;font-size:1em;font-weight:800;margin:3em 0 1.5em;}
p {line-height:1.8;margin:0 0 1em;}
input {background-color:#F6FBFF;}
span {margin: 0 0 1em;}
#Content p+p {margin-top:-1em; text-indent:0.0em;}
ol, ul {margin-top:0;margin-bottom:1em;line-height:1.8;}
#Content p+ol, #Content p+ul {margin-top:0em;}
a {color:#09c;text-decoration:none;font-weight:600;}
a:link {color:#09c;}
a:visited {color:#07a;}
a:hover {background-color:white;}

#Footnotes p+p {margin-top:0; text-indent:0;}
.filename {color:#0000ff;}
.errormess {color:#ff0000;}
#Content {
	width:700px;
	margin:0px auto; /* Right and left margin widths set to 'auto' */
	text-align:left; /* Counteract to IE5/Win Hack */
	padding:15px;
	border:1px dashed #333;
	background-color:#eee;
}
</style>
<!-- Thanks to www.bluerobot.com for the great CSS examples -->
</head>
<body>

<div id='Content'>
	\$output
</div>
<div id='poweredby'>poweredby:<br /><a href='http://scripts.ihostwebservices.com'><img src='data:image/png;base64,\$poweredby' alt='Use a different browser!' /></a></div>
</body>
</html>\";
}
?>";

	$f1 = @fopen("/$path/$htmlroot/dsplus/ds_admin.php","w"); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}
?>
