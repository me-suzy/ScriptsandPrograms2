<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /ds_admin.php
|	
|	Version: >>2.1.0<<
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
if(file_exists('/home/example/ds_files/scripts/ds_config.php')) {include_once('/home/example/ds_files/scripts/ds_config.php');}
else {echo 'Config file missing, please re-install.';exit;}

@ini_set('session.use_trans_sid', false); // same as below... one of these aught to work.
@ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src'); // fix for phpsessid  being inserted after form element causing invalid xhtml

if(preg_match('/^([A-Za-z0-9.:\/_\-]{1,40})$/', stripslashes($_SERVER['SCRIPT_NAME']), $matchscript))
{
	$_SERVER['SCRIPT_NAME'] = $matchscript[0];
}
else
{
	writeXhtml('Error: Script name has disallowed characters'); exit;
}

/* used for determining the user action. */
if(preg_match('/^([a-z]{4,12})$/', stripslashes($_POST['mode']), $matchover)) $act = $matchover[0];


session_start();

switch ($act) 
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
	$output = '<h3>Please enter your secret word to enter the Admin section.</h3>
		<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
		<p><input type="hidden" name="mode" value="adminenter" /></p>
		<p><input type="password" name="secretkey" /></p>
		<p><input type="submit" value="Continue" /></p>
		</form>';
		writeXhtml($output,'** Admin Enter **');
		exit;
}

function cancel()
{
		writeXhtml('<b>Installation Cancelled</b>');
		exit;
}
	
function overwrite()
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) 
	{
		if(is_file('install.php')) 
		{
			$output = '<h3>Download Sentinel++ Re-Install - Confirm!</h3>
					<h3>WARNING - This will overwrite all files! If you have made any changes or customizations, back them up now!</h3>
					<h3> Please enter your secret word to confirm the overwrite.</h3>
					<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
					<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>
					<p><input type="hidden" name="mode" value="owconfirm" /></p>
					<p><input type="password" name="secretkey" /></p>
					<p><input type="submit" value="Continue" /></p>
					</form>';
			writeXhtml($output,'Re-Install');
			exit;
		}
		else {writeXhtml ('Install file missing, please upload it to the dsplus directory.');exit;}
	}
}

function owConfirm()
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) 
	{
		if(preg_match('/^([A-Za-z0-9]{8,20})$/', stripslashes($_POST['secretkey']), $matchsecret)) 
		{
			$secret = $matchsecret[0];
			$secret = sha1($secret);
		}

		if (DS_FTOKEN == $secret) 
		{
			$owtoken = md5(uniqid(rand(), true));
			$_SESSION['owtoken'] = $owtoken;
			setcookie('owctoken', $owtoken, time()+600);
			
			$output = '<h3>Download Sentinel++ Re-Install - Confirmed!</h3>
				<h3> Overwite authority confirmed. Click continue.</h3>
				<form method="post" action="install.php">
				<p><input type="hidden" name="owtoken" value="'.$owtoken.'" /></p>
				<p><input type="submit" value="Continue" /></p>
				</form>';
			writeXhtml($output,'Re-Install');
			exit;
		}
		else
		{
			$output = '<h3>The secret word you entered was incorrect. Please try again.</h3>
			<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
			<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>
			<p><input type="hidden" name="mode" value="owconfirm" /></p>
			<p><input type="password" name="secretkey" /></p>
			<p><input type="submit" value="Continue" /></p>
			</form>';
		
			writeXhtml($output,'**ERROR**');
			exit;
		}
	}
	writeXhtml('Session Failure');
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
		if(preg_match('/^([A-Za-z0-9]{8,20})$/', stripslashes($_POST['secretkey']), $matchsecret)) 
		{
			$secret = $matchsecret[0];
			$secret = sha1($secret);
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

function config() 
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) 
	{
		// display the config file for editing
		$output = '<h3>In development.</h3>
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="admintoken" value="'.$token.'" /></p>
				
				<p><input type="submit" value="Continue" /></p>
				</form>';
			
		writeXhtml($output,'**Config - Incomplete**');
		exit;
	}
	exit;
}

function author()
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) 
	{
		if(file_exists(DS_DATADIR.'/ds_author.php')) {include_once(DS_DATADIR.'/ds_author.php');}
		else {writeXhtml('Author file missing, please re-install.','** Error **');exit;}
		
		$pr = '<h3>Author Names</h3>
			<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
			<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>
			<p><input type="hidden" name="mode" value="writeauthor" /></p>';
		
		$afilelist = makeTree(DS_FILEPATH);
		$afilelist = array_reverse($afilelist);
		foreach($afilelist as $fileandpath)
		{
			$authorfound = 0;
			$filter = after(DS_FILEPATH.'/', $fileandpath);
			
			foreach($athr as $kfile=>$vdesc)
			{
				if($kfile == $fileandpath)
				{
					$authorfound = 1;
					break;
				}
			}
			
			if(1 == $authorfound) 
			{
				$pr .= '<p><span class="filename">File: '.$filter.'</span> <input type="text" size="110" maxlength="254" name="pdata['.$kfile.']" value="'.$vdesc.'" /></p>';
			}
			else 
			{
				$pr .= '<p><span class="filename">File: '.$filter.'</span> <input type="text" size="110" maxlength="254" name="pdata['.$fileandpath.']" value="" /></p>';
			}
			$pr .= "\n";
		}
		$pr .= '<p><input type="submit" value="Continue" /></p>
			</form>';
			
		writeXhtml($pr,'**Authors**');
		exit;
		
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}

function writeAuthor()
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) {
		$newauthor = '';
		
		$output .= '<h3>Disallowed Character in author name - Please only use alphanumeric, dashes, underscores, dots, @, commas, or spaces.</h3>
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>
				<p><input type="hidden" name="mode" value="writeauthor" /></p>';
			
		$charfail = 0;
		foreach($_POST['pdata'] as $postfile=>$dwrite) 
		{
			if(preg_match('/^([a-zA-Z0-9_@,\-.\s]{1,255})$/', stripslashes($dwrite), $matches) || empty($dwrite)) 
			{
				$dwrite = $matches[0];
			}
			else 
			{
				$charfail = 1;
				$output .= '<p><span class="errormess">'.htmlspecialchars($postfile).'</span><span class="errormess"><--Has bad author name, change below</span></p>';
				
			}
			if(preg_match('/^([A-Za-z0-9.\/:_\-\s]{1,255})$/', stripslashes($postfile), $matches) || empty($postfile)) 
			{
				$postfile = $matches[0];
			}
			else 
			{
				$charfail = 1;
				$output .= '<p><span class="errormess">'.htmlspecialchars($postfile).'</span><span class="errormess"><--Has bad filename.</span></p>';
				
			}
			$output .= '<p><span class="filename">'.htmlspecialchars($postfile).'</span> <input type="text" size="110" maxlength="254" name="pdata['.htmlspecialchars($postfile).']" value="'.htmlspecialchars($dwrite).'" /></p>';
			$newauthor .= "\$athr['$postfile'] = '$dwrite';\n";
			$repostdata .= '<p><input type="hidden" name="pdata['.$postfile.']" value="'.$dwrite.'" /></p>';
		}
		
		if ($charfail==1) 
		{
			$output .= '<p><input type="submit" value="Continue" /></p>
					</form>';
			writeXhtml($output, '**Error**');
			exit;
		}
		else
		{
			if(file_exists(DS_DATADIR.'/ds_author.php')) {include_once(DS_DATADIR.'/ds_author.php');}
			else {writeXhtml('Author file missing, please re-install.','** Error **');exit;}
			
			$write = "<?php\n";
			$write .= $newauthor;
			$write .= '?>';
	
			$f1 = @fopen(DS_DATADIR.'/ds_author.php','w');
			if ($f1) {			
				@fwrite ($f1, $write);
				@fclose ($f1);
				$output = '<h3>Author File updated successfully
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="mode" value="adminenter" /></p>
				<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p> 
				<p><input type="submit" value="Continue" /></p>
				</form>';
				writeXhtml($output,'**Update Successful**');
				exit;
			}
			else {
				$output = '<h3>File save failed. Please make sure data directory and file are writable</h3>
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>';
				$output .= $repostdata;
				$output .= '<p><input type="hidden" name="mode" value="writeauthor" /></p>
				<p><input type="submit" value="Continue" /></p>
				</form>';
				writeXhtml($output,'**Error**');
				exit;
			}
		}
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}

function description()
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) {
		
		if(file_exists(DS_DATADIR.'/ds_desc.php')) {include_once(DS_DATADIR.'/ds_desc.php');}
		else {writeXhtml('Description file missing, please re-install.','** Error **');exit;}
		
		$pr = '<h3>File Descriptions</h3>
			<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
			<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>
			<p><input type="hidden" name="mode" value="writedesc" /></p>';
		
		$desclist = makeTree(DS_FILEPATH);
		$desclist = array_reverse($desclist);
		foreach($desclist as $fileandpath)
		{
			$descfound = 0;
			$filter = after(DS_FILEPATH.'/', $fileandpath);
			
			foreach($desc as $kfile=>$vdesc)
			{
				if($kfile == $fileandpath)
				{
					$descfound = 1;
					break;
				}
			}
			
			if(1 == $descfound) 
			{
				$pr .= '<p><span class="filename">File: '.$filter.'</span> <input type="text" size="110" maxlength="254" name="pdata['.$kfile.']" value="'.$vdesc.'" /></p>';
			}
			else 
			{
				$pr .= '<p><span class="filename">File: '.$filter.'</span> <input type="text" size="110" maxlength="254" name="pdata['.$fileandpath.']" value="" /></p>';
			}
			$pr .= "\n";
		}
		$pr .= '<p><input type="submit" value="Continue" /></p>
			</form>';
			
		writeXhtml($pr,'**Descriptions**');
		exit;
		
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}

function writeDescription() 
{
	if (isset($_SESSION['admintoken']) && $_POST['admintoken'] == $_SESSION['admintoken']) {
		$author = '';
		$output .= '<h3>Disallowed Character in description - Please only use alphanumeric, dashes, underscores, colons, forward slashes, or spaces.</h3>
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>
				<p><input type="hidden" name="mode" value="writedesc" /></p>';
			
		$charfail = 0;
		foreach($_POST['pdata'] as $postfile=>$dwrite) 
		{
			
			if(preg_match('/^([A-Za-z0-9.\/:,_\-\s]{1,255})$/', stripslashes($dwrite), $matches) || empty($dwrite)) 
			{
				$dwrite = $matches[0];
			}
			else 
			{
				$charfail = 1;
				$output .= '<p><span class="errormess">'.htmlspecialchars($postfile).'</span><span class="errormess"><--Has bad description, change below</span></p>';
				
			}
			if(preg_match('/^([A-Za-z0-9.\/:_\-\s]{1,255})$/', stripslashes($postfile), $matches) || empty($postfile)) 
			{
				$postfile = $matches[0];
			}
			else 
			{
				$charfail = 1;
				$output .= '<p><span class="errormess">'.htmlspecialchars($postfile).'</span><span class="errormess"><--Has bad filename.</span></p>';
				
			}
			$output .= '<p><span class="filename">'.htmlspecialchars($postfile).'</span> <input type="text" size="110" maxlength="254" name="pdata['.htmlspecialchars($postfile).']" value="'.htmlspecialchars($dwrite).'" /></p>';
			$newdesc .= "\$desc['$postfile'] = '$dwrite';\n";
			$repostdata .= '<p><input type="hidden" name="pdata['.$postfile.']" value="'.$dwrite.'" /></p>';
		}
		
		if ($charfail==1) 
		{
			$output .= '<p><input type="submit" value="Continue" /></p>
					</form>';
			writeXhtml($output, '**Error**');
			exit;
		}
		else
		{
			if(file_exists(DS_DATADIR.'/ds_desc.php')) {include_once(DS_DATADIR.'/ds_desc.php');}
			else {writeXhtml('Description file missing.','** Error **');exit;}
			
			$write = "<?php\n";
			$write .= $newdesc;
			$write .= "?>";

			$f1 = @fopen(DS_DATADIR.'/ds_desc.php','w');
			if ($f1) {			
				@fwrite ($f1, $write);
				@fclose ($f1);
				$output = '<h3>Description File updated successfully
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" />
				<p><input type="hidden" name="mode" value="adminenter" /></p>
				<p><input type="submit" value="Continue" /></p>
				</form>';
				writeXhtml($output,'**Update Successful**');
				exit;
			}
			else {
				$output = '<h3>File save failed. Please make sure data directory and file are writable</h3>
				<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
				<p><input type="hidden" name="admintoken" value="'.$_POST["admintoken"].'" /></p>';
				$output .= $repostdata;
				$output .= '<p><input type="hidden" name="mode" value="writedesc" /></p>
				<p><input type="submit" value="Continue" /></p>
				</form>';
				writeXhtml($output,'**Error**');
				exit;
			}
		}
	}
	writeXhtml('Sessions Tokens did not match','**ERROR**');
	exit;
}


function adminFunction($admintoken)
{
	// choose between editing config options, file descriptions, or author names. (config options still to-do)
	$output = '<h3>Which would you like to do?</h3>
			<form method="post" action="'.$_SERVER["SCRIPT_NAME"].'">
			<p><input type="hidden" name="admintoken" value="'.$admintoken.'" /></p>
			<!--<p><input type="radio" name="mode" value="config" /><span>Change Secret Word</span></p>-->
			<p><input type="radio" name="mode" value="desc" /><span>Enter File Descriptions</span></p>
			<p><input type="radio" name="mode" value="author" /><span>Enter Authors</span></p>
			<p><input type="radio" name="mode" value="overwrite" /><span>Re-install</span></p>
			<p><input type="submit" value="Continue" /></p>
			</form>';
	writeXhtml($output, 'Admin Functions');
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
?>