<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /upgrade210.php
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

$path = $_SERVER['DOCUMENT_ROOT'];
$root = explode('/', $path);
$grr = array_pop($root);
$woof = implode('/',$root);



if(preg_match('/^([A-Za-z0-9.\/_\-]{1,40})$/', stripslashes($_SERVER['SCRIPT_NAME']), $matchscript))
{
	$_SERVER['SCRIPT_NAME'] = $matchscript[0];
}
else
{
	writeXhtml('Error: Script name has disallowed characters');
	exit;
}


/* EDIT THE LINE BELOW IF THE SCRIPT COULD NOT FIND YOUR CONFIG FILE */
/* For example: $path = '/home/youraccount/html/ds_files/scripts'; */
$configfile = $woof.'/ds_files/scripts/ds_config.php';

if(!file_exists($configfile))
{
	$configfile = $path.'/ds_files/scripts/ds_config.php';
	if(!file_exists($configfile))
	{
		writeXhtml('Could not find ds_config.php file. Please enter the correct path directly into the upgrade script.');
		exit;
	}
}
include_once($configfile);

/* used for determining the user action. */
$act = '';
if(preg_match('/^([a-z]{4,12})$/', stripslashes($_POST['mode']), $matchover)) $act = $matchover[0];

session_start();

switch ($act) 
{
	case 'cancel':
	   cancel();
	   break;
	case 'upgrade':
	   upgrade($path,$woof,$configfile);
	   break;
	case 'adminenter':
	   adminEnter();
	   break;
	default:
	   entrance();
	   break;	  
}

function entrance()
{
	$output = '<h3>Please enter your secret word to enter upgrade area.</h3>
		<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
		<p><input type="hidden" name="mode" value="adminenter" /></p>
		<p><input type="password" name="secretkey" /></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
		writeXhtml($output,'** Admin Enter **');
		exit;
}

function adminEnter()
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) 
	{
		adminFunction($_SESSION['admintoken']);
	}
	else
	{
		if(preg_match('/^([A-Za-z0-9]{8,90})$/', stripslashes($_POST['secretkey']), $matchsecret)) 
		{
			$secret = $matchsecret[0];
			//$secret = sha1($secret);
		}

		if (DS_FTOKEN == $secret) 
		{
			$admintoken = md5(uniqid(rand(), true));
			$_SESSION['admintoken'] = $admintoken;
			adminFunction($admintoken);
		}
		else
		{
			$output = '<h3>The secret word you entered was incorrect. Please try again.</h3>
			<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
			<p><input type="hidden" name="mode" value="adminenter" /></p>
			<p><input type="password" name="secretkey" /></p>
			<p><input type="submit" value="Continue" /></p>
			</form>';
		
			writeXhtml($output,'**ERROR**');
			exit;
		}
	}
}

function adminFunction($admintoken)
{
	$output = '<h3>Which would you like to do?</h3>
			<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
			<p><input type="hidden" name="admintoken" value="'.$admintoken.'" /></p>
			<p><input type="radio" name="mode" value="upgrade" /><span>Upgrade ds_config.php and ds_count.php to 2.1.0</span></p>
			<p><input type="submit" value="Continue" /></p>
			</form>';
	writeXhtml($output, 'Admin Functions');
	exit;
}

function upgrade($path,$woof,$configfile)
{
	/* EDIT THE LINE BELOW IF THE SCRIPT COULD NOT FIND YOUR COUNT FILE */
	/* For example: $path = '/home/youraccount/html/ds_files/data'; */

	$countfile = $woof.'/ds_files/data/ds_count.php';
	if (!file_exists($countfile))
	{
		$countfile = $path.'/ds_files/data/ds_count.php';
		if (!file_exists($countfile))
		{
			writeXhtml('Could not find ds_count.php file. Please enter the correct path directly into the upgrade script.');
			exit;
		}
	}
	
	writeFileOne($path,$countfile,$woof);
	writeFileTwo($configfile);
	
	$owtoken = md5(uniqid(rand(), true));
	$_SESSION['owtoken'] = $owtoken;
	setcookie('owctoken', $owtoken, time()+600);
			
	$output = '<h3>Upgrade to 2.1.0 complete.</h3><br /><br />
		<p>--<b>Copy</b> <span class="filename">ds_config.php</span> and <span class="filename">ds_count.php</span> to a safe location.<br /> 
		--<b>Upload</b> install.php (place in /dsplus directory)<br />
		--<b>Click</b> continue to install 2.1.0.<br /><br />
		After the install is complete;<br />
		--<b>Copy</b> <span class="filename">ds_config.php</span> and <span class="filename">ds_count.php</span> to their original location.<br /><br /></p>
		<form method="post" action="install.php">
		<p><input type="hidden" name="owtoken" value="'.$owtoken.'" /></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
	writeXhtml($output,'Re-Install');
	exit;
}
/*------------------------------------------------------*/


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

/* ds_config.php */
function writeFileTwo($path)
{
	$ds_url = DS_URL;
	$ds_email = DS_EMAIL;
	$ds_datadir = DS_DATADIR;
	$ds_filepath = DS_FILEPATH;
	$ds_dllog = DS_DLLOG;
	$ds_dllogarc = DS_DLLOGARC;
	$ds_rptlogarchive = 'ds_dllogarchive.txt';
	$ds_countlog = DS_COUNTLOG;
	$ds_bwlog = DS_BWLOG;
	$ds_rptlog = DS_RPTLOG;
	$ds_trlog = DS_TRLOG;
	$ds_tokens = 'ds_tokens.php';
	$ds_counton = DS_COUNTON;
	$ds_tron = DS_TRON;
	$ds_dlon = DS_DLON;
	$ds_bwon = DS_BWON;
	$ds_tfail = DS_TFAIL;
	$ds_bwalert = DS_BWALERT;
	$ds_reporton = DS_REPORTON;
	$ds_rptbyemail = DS_RPTBYEMAIL;
	$ds_rptbyfile = DS_RPTBYFILE;
	$ds_tokenon = DS_TOKENON;
	$ds_ctokenon = 1;
	$ds_stokenon = 1;
	$ds_unqtkn = 0;
	$ds_dllogsize = DS_DLLOGSIZE;
	$ds_dllogarcsize = DS_DLLOGARCSIZE;
	$ds_dllogarcwarn = DS_DLLOGARCWARN;
	$ds_rptlogsize = 1000000;
	$ds_rptlogarcsize = 3000000;
	$ds_ablimit = DS_ABLIMIT;
	$ds_atlimit = DS_ATLIMIT;
	$ds_intlgth = DS_INTLGTH;
	$ds_ratio = DS_RATIO;
	$ds_ftoken = sha1(DS_FTOKEN);
	$ds_activedl = DS_ACTIVEDL;
	$ds_dlqty = DS_DLQTY;
	$db_on = DB_ON;
	$db_host = DB_HOST;
	$db_name = DB_NAME;
	$db_user = DB_USER;
	$db_pass = DB_PASS;
	$db_table = DB_TABLE;
	$db_incfield = DB_INCFIELD;
	$db_criteriafield = DB_CRITERIAFIELD;
	$ds_bmess1 = 'Invalid File Name!';
	$ds_bmess2 = 'Invalid Token!';
	$ds_bmess3 = 'That file was not found on the server.';
	$ds_bwmess = DS_BWMESS;
	$ds_bwmessfull = DS_BWMESSFULL;
	$ds_tfailmess = DS_TFAILMESS;
	$ds_dlmess = DS_DLMESS;
	$ds_emess1 =DS_EMESS1;
	$ds_emess2 =DS_EMESS2;
	$ds_emess3 = DS_EMESS3;
	$ds_emess4 = DS_EMESS4;
	$ds_emess5 = DS_EMESS5;
	$ds_emess6 = DS_EMESS6;
	$ds_emess7 = DS_EMESS7;
	$ds_emess8 = DS_EMESS8;
	$ds_emess9 = DS_EMESS9;
	$ds_emessa = DS_EMESSA;
	$ds_emessb = DS_EMESSB;
	
$write = "<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds_config.php
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
error_reporting(0); // always have error reporting (to the browser) off on your server, if it isn't, then this line assures it is for this script. Use (E_ALL ^E_NOTICE) for debugging.

/* change to suit your site */
define('DS_URL', '$ds_url'); // Where people should be directed after a bandwidth theft attempt.
define('DS_EMAIL', '$ds_email'); // Email address to send alerts to.
define('DS_DATADIR', '$ds_datadir'); // base path to the data files (should be outside visible web) 
define('DS_FILEPATH', '$ds_filepath'); // Path to the your downloadable files. (should be outside visible web)
define('DS_DLLOG', '$ds_dllog'); // Download log file
define('DS_DLLOGARC', '$ds_dllogarc'); // Archived Download log file
define('DS_RPTLOGARC', DS_DATADIR.'$ds_rptlogarchive'); // Archived Error report log file
define('DS_COUNTLOG', '$ds_countlog'); //Counter file, records quantity of file downloads in a text file.
define('DS_BWLOG', '$ds_bwlog'); //bandwidth recorder file
define('DS_RPTLOG', '$ds_rptlog'); // text file for error log if option turned on below.
define('DS_TRLOG', '$ds_trlog'); // text file for recording the token used and the http referer so they can be crossreferenced.
define('DS_TOKENS', DS_DATADIR.'$ds_tokens'); // repository for unique tokens... future use.
define('DS_TRON', $ds_tron); // Set to 1 to have token / http_referer logging turned on. 0 to shut off. (a good way to catch a site stealing bandwidth)
define('DS_COUNTON', $ds_counton); // Set to 1 to record downloads in a txt file, 0 to shut off logging.
define('DS_DLON', $ds_dlon); // Set to 1 to record download details. 0 to shut off logging.
define('DS_BWON', $ds_bwon); // Set to 1 to turn on bandwidth checking, 0 to shut off.
define('DS_TFAIL', $ds_tfail); // Set to 1 to have it report token failures. Set to 0 to shut off.
define('DS_BWALERT', $ds_bwalert); // Set to 1 to have it report BW alerts (when the bandwidth limit is reached). Set to 0 to shut off.
define('DS_REPORTON', $ds_reporton); // Set to 1 to have it record errors. Set to 0 to shut off.
define('DS_RPTBYEMAIL', $ds_rptbyemail); // Set to 1 to have it send reports out via email. 0 to shut off.
define('DS_RPTBYFILE', $ds_rptbyfile); // Set to 1 to have a text file log of errors, 0 to shut off.
define('DS_TOKENON', $ds_tokenon); // Set to 1 to have the token check active. Set to 0 to shut off token checks. NOTE, Tokens help ensure downloads are only from approved sites.
define('DS_CTOKENON', $ds_ctokenon); // Set to 1 to enable cookie tokens which limits downloads to the same server and the browser that initiated it. Can be used to stop link sharing between browsers.
define('DS_STOKENON', $ds_stokenon); // Set to 1 to enable session tokens which limits downloads to the same server. Use only if you do not wish to have ANY offsite downloads, even mirrors.
define('DS_UNQTKN', $ds_unqtkn); // Set to 1 to have unique tokens for each file, this will use more resources so it could slow down script depending on the number of files. --Future use--
define('DS_DLLOGSIZE', $ds_dllogsize); // Size the download log file is allowed to get before archiving. Default is 1MB
define('DS_DLLOGARCSIZE', $ds_dllogarcsize); // Size the archive file is allowed to get before being erased. A warning will be in the report file or email when it is reaching max.
define('DS_DLLOGARCWARN', $ds_dllogarcwarn); // The percentage the archive file can get before the warning is issued. 0.8 = 80%
define('DS_RPTLOGSIZE', $ds_rptlogsize); // Size the error log file is allowed to get before archiving. Default is 1MB
define('DS_RPTLOGARCSIZE', $ds_rptlogarcsize); // Size the error archive file is allowed to get before being erased. A warning will be in the report file or email when it is reaching max.
define('DS_ABLIMIT', $ds_ablimit); //The absolute limit on bandwidth for the absolute limit of time. ie 18GB in a Month. Default setting is 18 GB.
define('DS_ATLIMIT', $ds_atlimit); // The absolute limit of time in seconds. Default is 1 month. A day would be 86400.
define('DS_INTLGTH', $ds_intlgth); // The bandwidth interval length in seconds. Default is 10800 (3 hours) Which allows for 75 meg per interval with defaults. This spreads out the downloads over time. So that everyone has a chance to download a popular file.
define('DS_RATIO', $ds_ratio); // the ratio of download size to actual bandwidth usage. Normally somewhat less than 1 normally due to people cancelling downloads after they are started. For example, use 0.5 if it's half.
define('DS_FTOKEN', '$ds_ftoken'); // Your secret word. ($secret) Anyone with this word (and the right code) can download directly from your site. Change it to any alphanuneric sequence.
define('DS_ACTIVEDL', $ds_activedl); // number of seconds the download token is good for.
define('DS_DLQTY', $ds_dlqty); // number of times someone can click on a download before a message pops up telling them to stop. This is automatically reset when the bandwidth limit is reached.

/* List of Tokens for sites that are allowed to link to your downloads. Disable token and that site cannot download. */
\$list[] = DS_FTOKEN; // your sites secret word. Change above with the define BWFTOKEN, no need to repeat yourself.

/* Commented out by default remove comment block to enable other sites and enter their secret word between the single quotes
\$list[] = 'some_other_sites_secret_word'; // A mirror's secret word. Record here the name of the site using it so you can check it in the token/referer log.
\$list[] = 'another_sites_secret_word'; // Another mirror. etc etc
*/

/* Defined list. Change List array above, do not change this define. */
define('DS_TOKENLIST', serialize(\$list));

/* Database info */
define('DB_ON', $db_on); // use database for logging downloads? 1 for on, 0 for off.
define ('DB_HOST', '$db_host'); // for most people this will be localhost, but it could be a sub-domain or IP
define ('DB_NAME', '$db_name'); // The name of your database.. for people on shared hosts, don't forget your account name in front ie \"accountname_mydbname\"
define ('DB_USER', '$db_user'); // User name for that database, also with a shared host the account is usually prepended. ie \"accountname_user\"
define ('DB_PASS', '$db_pass'); // password for that user
define ('DB_TABLE', '$db_table'); // change to the name of the table you are using (assuming you are logging with a database.
define ('DB_INCFIELD', '$db_incfield'); // change to the name of the field you are incrementing
define ('DB_CRITERIAFIELD', '$db_criteriafield'); // change to the name of the field you are using to specify which field to increment.

/* Messages issued by the script to the browser */
define('DS_BMESS1', '$ds_bmess1');
define('DS_BMESS2', '$ds_bmess2');
define('DS_BMESS3', '$ds_bmess3');
/*bandwidth reached message */
define('DS_BWMESS', '$ds_bwmess'); // no need to change unless you wish to.
define('DS_BWMESSFULL', '$ds_bwmessfull'); // no need to change unless you wish to.
/* Token failure message */
define('DS_TFAILMESS', \"$ds_tfailmess\");
define('DS_DLMESS', '$ds_dlmess'); // the message they would get for clicking too many times.


/* Messages issued by the script to the report file or email */
define('DS_EMESS1', '$ds_emess1');
define('DS_EMESS2', '$ds_emess2');
define('DS_EMESS3', '$ds_emess3');
define('DS_EMESS4', '$ds_emess4');
define('DS_EMESS5', '$ds_emess5');
define('DS_EMESS6', '$ds_emess6'); // Email Subject
define('DS_EMESS7', '$ds_emess7'); // Email message start.
define('DS_EMESS8', '$ds_emess8'); // Email From header
define('DS_EMESS9', '$ds_emess9');
define('DS_EMESSA', '$ds_emessa');
define('DS_EMESSB', '$ds_emessb');
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

	$f1 = @fopen($path,'w'); 
	@fwrite ($f1, $write);
	@fclose ($f1);
	
	return;
}

function writeFileOne($path,$countfile,$woof)
{
	include_once ($countfile);
		
	$filesdir = $woof.'/ds_files/files';
	if(!is_dir($filesdir))
	{
		$filesdir = $path.'/ds_files/files';
		if (!is_dir($countfile))
		{
			writeXhtml('Could not find file directory. Please enter the correct path directly into the upgrade script.');
			exit;
		}
	}
	
	$allfiles = makeTree($filesdir);
	
	$writevars = "<?php\n";
	foreach($cnt as $k=>$v) 
	{
		$foundm = 0;
		foreach($allfiles as $vall)
		{
			if(end(explode('/',$vall)) == $k || $vall == $k)
			{
				$foundm = 1;
				$value = $vall;
				break;
			}
		}
		if($foundm == 1)
		{
			$writevars .= "\$cnt['$value'] = $v;\n";
		}
		else
		{
			$writevars .= "\$cnt['MISSING/$k'] = $v;\n";
		}
	}
	
	$writevars .= "?>";
	$f1 = fopen($countfile,'w'); 
	fwrite ($f1, $writevars);
	fclose ($f1);
	
}
?>