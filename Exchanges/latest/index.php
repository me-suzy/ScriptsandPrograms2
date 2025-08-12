<?php
/*
=====================================================================
SCRIPT :: Made by Xan Manning - http://www.knoxious.co.uk/
xan.manning@knoxious.co.uk

This script is released under GPL, please refer to the GNU General 
Public License included in this .zip file.

Installation: 1. CHMOD banned_ip.db.php and plugs.db.php file to 0666
2. Change config values below accordingly.
=====================================================================

=====================================================================
CONFIGURATION
=====================================================================
*/

// Plug Name - Returns "$config['name'] Plug".
$config['name'] = "Knoxious";
// Plugs shown on page - E.g. 10.
$config['plugs'] = 10;
// Plugs per person - E.g. 3. [0 is unlimited].
$config['plugpperson'] = 3;
// When to trim the database (Number of posts to be stored).
$config['trim'] = 120;
// Max Length for Plug text.
$config['maxlength'] = 12;
// Path to Plug script [If included in another page]. (Remember trailing slash /)
$config['path'] = ""; # e.g. path/to/this/
// Bad Words (Separated by commas ,).
$config['badwords'] = "sex,porn,xxx,warez,cracks,keygen,crax0r,p0rn,s3x,hardcore,hardc0re,drugs,fuck,shit";
// Additional Banned IPs (Separated by commas ,).
$config['ip_banned'] = "192.168.0.1,127.0.0.1,192.168.0.2,66.66.66.66";
// Banned IP Database File.
$config['banfile'] = "banned_ip.db.php";
// Plug Database File.
$config['plugfile'] = "plugs.db.php";
// Link format. {LINK} = Link, {NAME} = Sitename.
$config['format'] = "<img src=\""
					.$config['path']
					."images/pip.gif\" alt=\"\" title=\"{NAME}\" />
					<a href=\"{LINK}\" class=\"link\" title=\"{NAME} // Posted {DATETIME}\" target=\"_blank\">{NAME}</a>
					<br />";
// DateTime format.
$config['dtformat'] = "m/d/Y - H:i:s";
// Stylesheet Configuration.
$config['style'] = ".plug_table {
	border: 1px solid #999999;
	background-color: #FFFFFF;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: x-small;
	color: #666666;
	width: 120px;
}
.plug_title {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: dashed;
	border-left-style: none;
	border-top-color: #999999;
	border-right-color: #999999;
	border-bottom-color: #999999;
	border-left-color: #999999;
}
.plug_text {
	background-color: #F5F5F5;
}
.plug_textfield {
	background-color: #F5F5F5;
	border: 1px solid #999999;
	width: 110px;
}
.plug_button {
	background-color: #F5F5F5;
	border: 1px solid #999999;
	width: 50px;
}
.copyright
	{
		color: #CCCCCC;
	}
a:link {
	color: #990000;
	text-decoration: none;
}
a:visited {
	color: #990000;
	text-decoration: none;
}
a:hover {
	color: #990000;
	text-decoration: underline overline;
}
a:active {
	color: #990000;
}";

// Finish Configuration.

// Functions

// checkBan Function : Checks to see if user banned.
error_reporting(0);

function checkBan($ip, $ban)
	{
	global $config;
	$ban = explode(",", $ban);
	foreach($ban as $banip)
		{
			if($ip == $banip)
				{
					$msg = "NotOk";
					break;
				} else {
					$msg = "Aok";
				}
		}
		return $msg;
	}
	
// banCurrent Function : Bans given IP.
function banCurrent($ip, $banfile)
	{
		$fopen = fopen($banfile, "a+");
		fwrite($fopen, ",".$ip);
		fclose($fopen);
		return true;
	}

// getBannedIPs Function : Gets Banned IPs from File
function getBannedIPs($file)
	{
		$fopen = fopen($file, "r");
		$fread = fread($fopen, filesize($file)+1);
		fclose($fopen);
		return $fread;
	}
	
// echoPlug Function : Prints on page formatted plugs.
function echoPlug($plugfile, $format, $total)
	{
		$fopen = fopen($plugfile, "r");
		$fread = fread($fopen, filesize($plugfile)+1);
		fclose($fopen);
		$line = explode("\n", $fread);
			for ($n = 0; $n < $total; $n++)
				{ 
  					$plug = explode("|", $line[$n]); 
						if (isset($plug[1])) 
   							{
								$search = array("{LINK}", "{NAME}", "{DATETIME}");
								$revise = array($plug[2], $plug[3], date($config['dtformat'], $plug[4]));
								$export = str_replace($search, $revise, $format);
								echo $export."\n";
							}
				}
				return TRUE;
	}
	
// checkPlugCount Function : Checks how many plugs a person has left.
function checkPlugCount($yourip, $plugpperson, $plugfile)
	{
		$fopen = fopen($plugfile, "r");
		$fread = fread($fopen, filesize($plugfile)+1);
		fclose($fopen);
		$line = explode("\n", $fread); 
		$i = count($line)+1; 
			for ($n=0 ; $n < $i-1 ; $n++)
				{ 
  					$ip = explode("|", $line[$n]);
					$ip = $ip[1];
					if($yourip == $ip)
						{
							$count[] = $ip;
						}
				}
				$plugs_made = count($count);
				if($plugpperson > 0)
					{
						$plugs_left = $plugpperson - $plugs_made;
					} elseif($plugpperson == 0) {
							$plugs_left = "Unlimited";
						}
				return $plugs_left;
	}
// formatAddress Function : Makes address valid.
function formatAddress($address)
	{
	global $config;
	$pattern = "|[^a-z0-9-_.:/]|i";
	$replace = "";
	$address = preg_replace($pattern, $replace, $address);
	$address = preg_replace('/\s\s+/', ' ', $address);
	$search = stristr($address, 'http://');
	if($search == FALSE)
		{
			$address = "http://"
						.$address;
		}
	if($address != "" && $address != " " && $address != NULL && eregi("^(http|https)+(:\/\/)+[a-z0-9_-]+\.+[a-z0-9_-]", $address))
		{
				$badword = explode(",", $config['badwords']);
				foreach($badword as $nothere)
					{
						$badwordsearch = stristr($address, $nothere);
						if($badwordsearch == TRUE)
							{
								return false;
							} else {
								return $address;
								}
					}
				}
		else {
			return false;
			}
	}	

// formatName Function : Makes address valid.
function formatName($name)
	{
	global $config;
	$pattern = "|[^a-z0-9-_.!]|i";
	$replace = " ";
	$name = preg_replace($pattern, $replace, $name);
	$name = preg_replace('/\s\s+/', ' ', $name);
	if($name != "" || $name != " " || $name != NULL)
		{
		$badword = explode(",", $config['badwords']);
				foreach($badword as $nothere)
					{
						$badwordsearch = stristr($name, $nothere);
						if($badwordsearch == TRUE)
							{
								return false;
							} else {
							return $name;
							}
					}
				
			} else {
			return false;
			}
	}	


// writePlug Function : Writes plugdata to database.
function writePlug($ip, $date, $address, $name)
	{
	global $config;
	$plugs_left = checkPlugCount($_SERVER['REMOTE_ADDR'], $config['plugpperson'], $config['plugfile']);
	$plugfile = $config['plugfile'];
	$name = formatName($name);
	$address = formatAddress($address);
	if($name == TRUE && $address == TRUE)
		{
			if(($plugs_left >= 1 || $plugs_left == "Unlimited") && !($plugs_left < 1))
				{
					$origdata = file($config['plugfile']);
					$string = "<?die ('Access Denied')?>|".$ip."|".$address."|".$name."|".date("U")."\n";
					$fopen = fopen($plugfile, "w");
					fwrite($fopen, $string);
					for ($i = 0; $i < $config['trim'] - 1 ; $i++)
    					{
    						@fwrite($fopen, $origdata[$i]);
    					}
					fclose($fopen);
					$is_error = 0;
				} elseif($plugs_left <= 0)
					{
						$is_error = 2;
					}
		} elseif($name == FALSE || $address == FALSE)
			{
				$is_error = 1;
			}
		return $is_error;
	}

if(@$_POST['plugit'])
	{
		$plug = writePlug($_SERVER['REMOTE_ADDR'], date("d/m/Y H:i:s"), $_POST['address'], $_POST['sitename']);
	}

$thisfile = basename($_SERVER['PHP_SELF']);
$yourip = $_SERVER['REMOTE_ADDR'];
$banfromfile = getBannedIPs($config['banfile']);
$checkban = checkBan($yourip, $config['ip_banned'].",".$banfromfile);
$plugs_left = checkPlugCount($yourip, $config['plugpperson'], $config['plugfile']);


switch($checkban)
	{
	case "Aok":
echo "<style type=\"text/css\">".$config['style']."</style>";
echo "<table class=\"plug_table\" border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"2\">
  <tr>
    <td class=\"plug_title\"><strong>".$config['name']." Plug";
	if(@isset($_POST['plugit']) && $plug == 2) {
			echo "<br />&nbsp;:: <strong><img src=\"".$config['path']."images/ex.gif\" alt=\"\" title=\"Exclamation!\" /> HACKING ATTEMPT.</strong> - Banned.(<a href=\"#\" onclick=\"alert('You attempted to double post your entry against the administrators wishes, you are therefore banned from attempting to hack us again.');\">?</a>)<br />";
			banCurrent($_SERVER['REMOTE_ADDR'], $config['banfile']);
			}
	elseif(@isset($_POST['plugit']) && $plug == 1)
			{
				echo "<br />&nbsp;:: <strong><img src=\"".$config['path']."images/ex.gif\" alt=\"\" title=\"Exclamation!\" /> Invalid! (<a href=\"#\" onclick=\"alert('The information you entered was invalid, this is because of either: Your URL was not in the form http://www.yourname.tld/ or Your Sitename or URL contained Banned Words.');\">?</a>)</strong>";
				echo "<script type=\"text/javascript\">setTimeout(\"window.location = '".$thisfile."';\", 3000);</script>";
			} elseif(@isset($_POST['plugit']) && $plug == 0) {
			echo "<br />&nbsp;:: <strong><img src=\"".$config['path']."images/ex.gif\" alt=\"\" title=\"Exclamation!\" /> Plugged.</strong><br />";
			echo "<script type=\"text/javascript\">setTimeout(\"window.location = '".$thisfile."';\", 3000);</script>";
			} 
echo "</strong></td>
  </tr>
  <tr>
    <td class=\"plug_text\"><div align=\"left\">";
	echoPlug($config['plugfile'], $config['format'], $config['plugs']);
	if($plugs_left <= 0)
		{
			$disable = " disabled";
		}
		if($plugs_left == 0)
			{
				$disable = " disabled";
			}
		if($plugs_left >= 1)
			{
				$disable = "";
			}
			if($plugs_left == "Unlimited" && $plugs_left != "0")
				{
					$disable = "";
				} 
	echo "</div></td>
  </tr>
  <tr>
    <td><div align=\"center\">
	Greetings, Plugs left: <strong>".$plugs_left."</strong><br />
      <form name=\"plug\" id=\"plug\" method=\"post\" action=\"\">
        <input name=\"sitename\"".$disable." maxlength=\"".$config['maxlength']."\" type=\"text\" value=\"Site Name\" class=\"plug_textfield\" id=\"sitename\" onfocus=\"if(this.value=='Site Name')this.value='';\" onblur=\"if(this.value=='')this.value='Site Name';\" />
        <br />
        <input name=\"address\"".$disable." type=\"text\" value=\"URL\" class=\"plug_textfield\" id=\"address\" onfocus=\"if(this.value=='URL')this.value='';\" onblur=\"if(this.value=='')this.value='URL';\" />
        <br />
        <input name=\"plugit\"".$disable." type=\"submit\" class=\"plug_button\" id=\"submit\" value=\"Plug\" />
        <input class=\"plug_button\"".$disable." type=\"reset\" name=\"Reset\" value=\"Reset\" />
      </form><span class=\"copyright\"><a href=\"http://www.knoxious.co.uk/\" target=\"_blank\" title=\"Knoxious.co.uk, Open Source PHP.\">KnoxiousPlug</a>.</span>
    </div></td>
  </tr>
</table>";
break;
case "NotOk":
echo "<img src=\"".$config['path']."images/ex.gif\" alt=\"\" title=\"Exclamation!\" /> Sorry, You are banned from this Plug! (<a href=\"#\" onclick=\"alert('You are banned because you either have a bad IP, or have attempted to hack the plug board in the past.');\">?</a>)";
break;
}
?>