<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds.php
|
|	Version: >>v2.1.0<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

require_once ('/home/example/ds_files/scripts/ds_config.php');

/* CHANGE NOTHING BELOW THIS LINE */
if(DS_CTOKENON ==1 || DS_STOKENON ==1) 
{
	session_start();
}

session_cache_limiter('none');

/* Clean them variables boys  (always clean variables at the start of your script to prevent injection attacks. Always limit input to expected chars and patterns.) */
if(preg_match('/^([A-Za-z0-9.?=_\-\/:\s(%20)]{1,255})$/', stripslashes($_SERVER['HTTP_REFERER']), $matchref)) {$tempvar = $matchref[0];}else{$tempvar='NoRef';}
define('HTTP_REF', $tempvar);

if(preg_match('/^([0-9.]{7,24})$/', stripslashes($_SERVER['REMOTE_ADDR']), $matchadd)) {$tempvar = $matchadd[0];}else{$tempvar='1.1.1.1';}
define('DS_RADDR', $tempvar);

if(preg_match('/^([A-Za-z0-9._\-\/:\s]{1,164})$/', stripslashes($_GET['p']), $matches)) {$p = $matches[0];}else{sendToBrowser(DS_BMESS1);exit;}

if(preg_match('/^([A-Za-z0-9=]{100,300})$/', stripslashes($_GET['t']), $matchtwo)) {$t = $matchtwo[0];}else{sendToBrowser(DS_BMESS2);exit;} 
/* All clean mom */


/* The magic begins */
if (checkToken($t) == true) 
{
		
	$chkfil = makeTree(DS_FILEPATH); // recursive loop through the path, looking at all files in all subdirectories.
	$found = 0;
	
	foreach ($chkfil as $val) // look for a match for the requested file
	{
		if(strstr($p, '/')) 
		{
			$filter = after(DS_FILEPATH.'/', $val); // get rid of the leading user directory info and focus on everything under ds_files/files
			if ($p == $filter) 
			{
				$fil = $val;
				$found=1; 
				break;
			}
		}
		else
		{
			if(preg_match('/('.$p.')/', $val)) // must be a call from m.php rather than mplus.php or be in the top level directory.
			{
				$fil = $val;
				$found=1; 
				break;
			}
		}	
	}
	
	/* file not found report error */
	If ($found == 0) 
	{
		sendToBrowser(DS_BMESS3);
		reportError(0,DS_RADDR,$p,DS_EMESS1);
		exit;
	}
	
	if(DS_BWON ==1) bandwidthLimit($fil,$p); // run bandwidth checker function
	
	if (DS_DLON ==1) writeLog($p,$fil); // Txt file detail logging.
	if (DS_COUNTON == 1) countLog($fil); // Text file qty logging.
	if (DB_ON == 1) dbLog($fil); // Database qty logging.
	
	session_write_close();
	
	/*IE Bug in download name workaround */
	if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
		@ini_set( 'zlib.output_compression','Off' );
	}
	   
	$fn = basename($fil);
	header('Content-Type: application/octet-stream');
	header("Content-Disposition: attachment; filename=\"$fn\"");
	header('Content-Length: '.filesize($fil));
	@readfile($fil);
	
	exit();
	
} 
else 
{
	/* Tells people where to go, and reports the token failure. */
	sendToBrowser(DS_TFAILMESS);
	if(DS_TFAIL == 1) 
	{
		reportError(1,DS_RADDR,$p,DS_EMESS3);
	}
	exit;
}

function after ($this, $inthat)
{
	if (!is_bool(strpos($inthat, $this)))
	return substr($inthat, strpos($inthat,$this)+strlen($this));
}

/* loop to cycle through list looking for good match */
function checkToken($passtoken='') 
{
	if(DS_TOKENON == 0) return true; // ignore token checks if the option is turned off
	
	$currhour = date('Ymd');
	$token['time'] = time();
	$passtoken = unserialize(base64_decode($passtoken));
	
	if(DS_CTOKENON == 1) 
	{
		if ($passtoken['ctoken'] != $_COOKIE['ctoken'])
		{
			return false;
		}
	}
	
	if(DS_STOKENON == 1)
	{
		if (!isset($_SESSION['stoken']) && $passtoken['stoken'] != $_SESSION['stoken'])
		{
			return false;
		}
	}
	
	$list = unserialize(DS_TOKENLIST);
	$x=0;
	foreach($list as $x=>$val) 
	{
		$token['hash'] = sha1($val.$currhour);
		if ($token['hash'] == $passtoken['hash']) // check hashed token to see if it matches
		{ 
			/*open log file and record secret word and http referer. Only if it is not primary dl site */
			if(($x > 0) && (DS_TRON == 1)) 
			{
				if (file_exists(DS_TRLOG)) 
				{
					$fp = fopen(DS_TRLOG, 'a+');
					fwrite($fp, $val.'|'.HTTP_REF.'|'.$currhour."\n");
					fclose($fp);
				}
				else 
				{
					reportError(2,DS_RADDR,DS_TRLOG,DS_EMESS4);
				}
			}
			
			if (($token['time']-$passtoken['time']) < DS_ACTIVEDL) // check if the time frame has expired
			{ 
				return true;
			}
		}
	}
	return false;
}

/* find all files in all subdirectories and put in array. */
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

/* bandwidth limiter function */
function bandwidthLimit($fil,$p)
{
	/* Initialize variables just in case they mysteriously go missing. It has happened before I tell you! */
	$s = 0;
	$t = time()-100;
	$i = 1;
	$r = 0;
	$c = array();
	
	/* Load the dynamic memory */
	if(file_exists(DS_BWLOG)){
		include (DS_BWLOG); // variables in this file are s,t,i,r and c
	} 
	else {
		reportError(3,DS_RADDR,DS_BWLOG,DS_EMESS4);
	}
	
	/* setup a bunch of variables */
	$tempaddr = DS_RADDR; // grab the ip address and stick it in a var because we can not use a constant below.
	$foundit = 0; // used in checking the number of clicks per user below.
	$pothole = 0; // flag for bandwidth limit check
	
	$Addsize = @filesize($fil);
	$CurrentTime = time();
	$CurrentInterval = ceil(($CurrentTime - $t) / DS_INTLGTH);
	$IntervalBandwidthRate = DS_ABLIMIT / DS_ATLIMIT * DS_INTLGTH;
	$AvailableBandwidth = ($CurrentInterval * $IntervalBandwidthRate);
	
	/* Reset everything since the absolute time limit has passed */
	if ($CurrentTime >= (DS_ATLIMIT + $t)) {
		$s = 0 + ($Addsize * DS_RATIO);
		$t = $CurrentTime;
		$i = 1;
		$r = 0;
		$CurrentInterval=1;
		$c =array();
	}
	/* otherwise just update the bandwidth used */
	else {
		$s = $s + ($Addsize * DS_RATIO);
	}
	
	/* Check the transfer thus far, make sure it's not over the limit.  */
	if(($s >= DS_ABLIMIT) || ($s >= $AvailableBandwidth))  {
		if($s >= DS_ABLIMIT) {
			sendToBrowser(DS_BWMESSFULL);
			if ($r == 0) {
				if (DS_BWALERT == 1) {
					reportError(4,DS_RADDR,$p,DS_EMESS2);
					$r = 1;
				}
			}
		} 
		else { 
			sendToBrowser(DS_BWMESS);
			$pothole = 1;
		}
		$s = $s - ($Addsize * DS_RATIO);
	}
	
	/* If we are currently in a new interval, reset userclicks */
	if (($CurrentInterval > $i) && ($r == 0)) {
		$writevars = "<?php\n";
		$writevars .="\$s=$s;\n";
		$writevars .="\$t=$t;\n";
		$writevars .="\$i=$CurrentInterval;\n";
		$writevars .="\$r=$r;\n";
		$writevars .= "\$c['$tempaddr']['$p']=1;\n";
		$writevars .= "?>";
	}
	/* otherwise use this one which mantains userclicks */
	else {
		/* let us make sure they have not been mad clickers, if they have, then no download for you!! */
		$writeuser='';
		foreach ($c as $k=>$v) {
			foreach ($v as $a=>$value) {
				if ($k == $tempaddr && $a == $p) { // see if there is a match to the user ip and the file
					if ($value >= DS_DLQTY) { // check the count
						sendToBrowser(DS_DLMESS); 
						exit;
					}
					 $value++;
					 $foundit =1;
				}
				$writeuser .= "\$c['$k']['$a']=$value;\n"; // add current users to array
			}
		}
		if($foundit == 0)  $writeuser .= "\$c['$tempaddr']['$p']=1;\n"; // add new user to array
		
		/* constuct the updated file */
		$writevars = "<?php\n";
		$writevars .="\$s=$s;\n";
		$writevars .="\$t=$t;\n";
		$writevars .="\$i=$i;\n";
		$writevars .="\$r=$r;\n";
		if(is_array($writeuser)) {
			foreach ($writeuser as $b) {
				$writevars .= $b;
			}
		}
		else {
			$writevars .= $writeuser;
		}
		$writevars .= "?>";
	}
	
	$f1 = fopen(DS_BWLOG,'w'); 
	fwrite ($f1, $writevars);
	fclose ($f1);
	
	if(($pothole == 1) || ($r == 1)) {
		exit;
	}
	return;
}

/* error reporting to admin */
function reportError($errortype,$userIP,$pfile,$msg)
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
		$datetime=date('Y-M-d H:i:s'); // human readable time.
		$timenum =  time(); // unix time
		$lines=5;
		$reportok = 0;
		$emailok = 0;
		$pos = -2;
		$buffer = '';
		$where = 600; // arbitrary number, must be greater than 1 to start.
	
		if(filesize(DS_RPTLOG) > 4)
		{
			$fp = @fopen(DS_RPTLOG, 'r+');
			for($i=0;$i<$lines;$i++)
			{
				
				while ($buffer != "\n" && $where > 1) 
				{
				     fseek($fp, $pos, SEEK_END);
				     $buffer = fgetc($fp);
				     $pos = $pos - 1;
				     $where = ftell($fp);
				}
				$buffer = fgets($fp);
				
				$tmp = explode('|', $buffer);
				 if($errortype == $tmp[0] && $where >1)
				{
					// it is the same.
					 if(isset($tmp[1]) && $tmp[1] < $timenum-43200) // current time minus one day
					{
						$emailok = 1; // it is ok to send email again
					}
					break;
				}
				else
				{
					$reportok = 1; // ok to update report file
				}
			}
			fclose($fp);
		}
		else
		{
			$reportok = 1;
		}
		
		if($reportok == 1)
		{
			if(filesize(DS_RPTLOG) >= DS_RPTLOGSIZE) 
			{
				if (file_exists(DS_RPTLOGARC)) 
				{
					$fp = fopen(DS_RPTLOG, 'r');
					$contents = fread($fp,filesize(DS_RPTLOG));
					fclose($fp);
					
					if(filesize(DS_RPTLOGARC) >= DS_RPTLOGARCSIZE) 
					{
						$fp = fopen(DS_RPTLOGARC, 'w');
						fwrite($fp,$contents);
						fclose($fp);
					}
					else 
					{
						$fp = fopen(DS_RPTLOGARC, 'a+');
						fwrite($fp,$contents);
						fclose($fp);
					}
				}
				$fp = fopen(DS_RPTLOG, 'w');
			}
			else 
			{
				$fp = fopen(DS_RPTLOG, 'a+');
			}
			fwrite($fp,$errortype.'|'.$timenum.'|'.$datetime.'|'.$msg.'|'.$pfile."\n");
			fclose($fp);
		}
		
		if($emailok == 1)
		{
			if(DS_RPTBYEMAIL==1) 
			{		
				sendAMail(DS_EMESS6,DS_EMESS7.$msg);
			}
		}
	}
	return;
}

/* send mail function */
function sendAMail($subject,$message)
{
	$email = DS_EMAIL;
	$from = DS_EMESS8;
	mail(DS_EMAIL, $subject, $message, "$from <$email>\nX-Mailer: PHP/ . $phpversion()", "-f $email");
}

/* record download details */
function writeLog($p,$fil)
{
	//Write download log
	$datetime=date('Y-m-d H:i:s');
	if (file_exists(DS_DLLOG)) {
		if(filesize(DS_DLLOG) >= DS_DLLOGSIZE) {
			if (file_exists(DS_DLLOGARC)) {
				$fp = fopen(DS_DLLOG, 'r');
				$contents = fread($fp,filesize(DS_DLLOG));
				fclose($fp);
				
				if(filesize(DS_DLLOGARC) >= (DS_DLLOGARCSIZE*DS_DLLOGARCWARN)) {
					reportError(5,DS_RADDR,DS_DLLOGARC,DS_EMESS5);
				}
				
				if(filesize(DS_DLLOGARC) >= DS_DLLOGARCSIZE) {
					$fp = fopen(DS_DLLOGARC, 'w');
					fwrite($fp,$contents);
					fclose($fp);
				}
				else {
					$fp = fopen(DS_DLLOGARC, 'a+');
					fwrite($fp,$contents);
					fclose($fp);
				}
			}
			else {
				reportError(6,DS_RADDR,DS_DLLOGARC,DS_EMESS4);
				$fp = fopen(DS_DLLOGARC, 'w');
				fwrite($fp,$contents);
				fclose($fp);
			}
			$fp = fopen(DS_DLLOG, 'w');
		}
		else {
			$fp = fopen(DS_DLLOG, 'a+');
		}
		fwrite($fp,$p.'|'.DS_RADDR.'|'.HTTP_REF.'|'.filesize($fil).'|'.$datetime."\n");
		fclose($fp);
	} 
	else {
		reportError(7,DS_RADDR,DS_DLLOG.DS_EMESS4);
		$fp = fopen(DS_DLLOG, 'w');
		fwrite($fp,$p.'|'.DS_RADDR.'|'.HTTP_REF.'|'.filesize($fil).'|'.$datetime."\n");
		fclose($fp);
	}
	return;
}

/* record the quantity downloads */
function countLog($p)
{	
	$cnt = '';
	if(file_exists(DS_COUNTLOG)){
		include (DS_COUNTLOG);
	} else {
		reportError(8,DS_RADDR,DS_COUNTLOG,DS_EMESS4);
	}
	
	
	if(!isset($cnt[$p])){$cnt[$p] = 0;}
	$cnt[$p]++;
	asort($cnt);
	
	$writevars = "<?php\n";
	foreach($cnt as $k=>$v) {
		$writevars .= "\$cnt['$k'] = $v;\n";
	}
	$writevars .= "?>";
	
	$f1 = fopen(DS_COUNTLOG,'w'); 
	fwrite ($f1, $writevars);
	fclose ($f1);
	
	return;
}

/* future function - not currently used */
function fileWrite ($filename,$mode,$data)
{
	$f1 = @fopen($filename,$mode.'b');
	if(!$f1) {
		$f1 = @fopen($filename,'wb');
	}
	@fwrite ($f1, $data);
	@fclose($f1);
}
	
/* record downloads in a database */
function dbLog ($p)
{
	$db = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
	if (!$db) {
		reportError(DS_EMESS9);
		reportError(9,DS_RADDR,$p,DS_EMESS9);
		
	}

	$db_selected = @mysql_select_db(DB_NAME, $db);
	if (!$db_selected) {
		reportError(10,DS_RADDR,$p,DS_EMESSA);
	}
	
	/* check to see if there is a record for this file in the database already */
	$query = "SELECT ".DB_CRITERIAFIELD." FROM ".DB_TABLE." WHERE ".DB_CRITERIAFIELD." = '$p'";
	$result = @mysql_query($query);
	if (!$result) {
		reportError(11,DS_RADDR,$p,DS_EMESSB);
	}
	
	$chkresult = @mysql_result($result,0);
	
	/* if there is a record, update the downloads quantity */
	if ($chkresult == $p) {
		$query = "UPDATE ".DB_TABLE." SET ".DB_INCFIELD." = ".DB_INCFIELD."+1 WHERE ".DB_CRITERIAFIELD." = '$p'";
		$result = @mysql_query($query);
		if (!$result) {
			reportError(11,DS_RADDR,$p,DS_EMESSB);
		}
	}
	/* if there is not a record, insert a new one into the database */
	else {
		$query = "INSERT INTO ".DB_TABLE." ( ".DB_CRITERIAFIELD." , ".DB_INCFIELD." )VALUES ('$p', 1)";
		$result = @mysql_query($query);
		if (!$result) {
			reportError(11,DS_RADDR,$p,DS_EMESSB);
		}
	}
	
	@mysql_free_result($result);
}

function sendToBrowser($output)
{
	$path = $_SERVER['DOCUMENT_ROOT'];
	$file = $path.'/dsplus/ds.html';
	$data = file_get_contents($file);
	
	$replace = str_replace ("<output />", "$output", $data);
	
	echo $replace;
}
?>