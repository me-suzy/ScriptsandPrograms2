<?
$program = "EZ php Form-Mailer v 1.21"; // do not change

/*
Written by Lev <lev@taintedthoughts.com>
Written & Released: December 4th 2004 (last revised: Feb. 21 2005)

Consult readme.txt for setup information.

For more scripts & free code visit:
http://www.pixelatedbylev.com
*/

$GLOBALS['template'] = "template.html"; // must be valid for script to work!!!

$thisprog = $_SERVER['SCRIPT_NAME']; // do not fuck with this
$requireemail = "n"; // y or n
$requirerefer = "n"; // y or n
$requireicode = "y"; // y or n
$validservers = "pixelatedbylev.com,www.pixelatedbylev.com,taintedthoughts.com,www.taintedthoughts.com";

// SET THIS TO THE DEFAULT EMAIL RECIPIENT
$defaultrecip = "change@this.now";

// SET THIS TO THE DEFAULT EMAIL RECIPIENT
$defaultsubject = "EZ php Form-Mail";

// SET THIS TO THE DEFAULT THANKS PAGE
$defaultpage = "$thisprog?thanks";


////////////////////////////////////////////////////
// YOU DON'T NEED TO CHANGE ANYTHING BELOW THIS LINE
////////////////////////////////////////////////////


$validemail = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";

// DISPLAY THE IMAGE CODE IMAGE (REQUIRES GD LIBRARY)
if ($_SERVER['QUERY_STRING'] == 'imagecode')
	{
	header("Content-type: image/png");
	$im = imagecreatetruecolor(60,20);
	$background = imagecolorallocate($im, 0, 0, 0);
	imagefill($im, 0, 0, $background);
	$fontcolor = imagecolorallocate($im, 255, 255, 255);
	imagestring ($im, 5, 7, 1, strtoupper(substr(md5(date("d",time())),0,5)), $fontcolor);
	imagepng($im);
	imagedestroy($im);
	exit;
	}

// IF YOU DON'T DEFINE A RECIPIENT IN THE FORM THEN USE DEFAULT
if ($_POST['recip'] == '')
	{
	$recipient = $defaultrecip;
	}
// IF A RECIPIENT WAS DEFINED IN THE FORM USE THAT INSTEAD
else
	{
	$recipient = $_POST['recip'];
	}

// IF YOU DON'T DEFINE A SUBJECT IN THE FORM THEN USE DEFAULT
if ($_POST['subject'] == '')
	{
	$subject = $defaultsubject;
	}
// IF A RECIPIENT WAS DEFINED IN THE FORM USE THAT INSTEAD
else
	{
	$subject = $_POST['subject'];
	}

// IF A NAME WAS NOT PROVIDED THEN SET IT TO UNKNOWN
if ($_POST['name'] == '')
	{
	$fromname = 'Unknown';
	}
// IF A RECIPIENT WAS DEFINED IN THE FORM USE THAT INSTEAD
else
	{
	$fromname = $_POST['name'];
	}

$footer = get_footer();

if ($_POST['message'] == '' && !$_SERVER['QUERY_STRING'])
	{
	if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg($GLOBALS['program'],$footer))
		{
		header("Location: $thisprog?invalidconfig");
		exit;
		}
	$data .= "<FORM ACTION=form_mailer.php METHOD=POST><CENTER><TABLE>";
	$data .= "<TR><TD WIDTH=150><FONT SIZE=2 FACE=verdana>your name:</TD><TD WIDTH=300>";
	$data .= "<INPUT TYPE=TEXT NAME=name SIZE=30></TD></TR>";
	$data .= "<TR><TD><FONT SIZE=2 FACE=verdana>your email</TD><TD><INPUT TYPE=TEXT NAME=email SIZE=30></TD></TR>";
	$data .= "<TR><TD><FONT SIZE=2 FACE=verdana>subject:</TD><TD><INPUT TYPE=TEXT NAME=subject SIZE=30></TD></TR>";
	if ($requireicode == 'y')
		{
		$data .= "<TR><TD><FONT SIZE=2 FACE=verdana>code ( <IMG SRC=$thisprog?imagecode ALIGN=MIDDLE> ):</TD><TD><INPUT TYPE=TEXT NAME=icode SIZE=30></TD></TR>";
		}
	$data .= "<TR><TD><FONT SIZE=2 FACE=verdana>message:</TD><TD><TEXTAREA COLS=30 ROWS=6 NAME=message>";
	$data .= "</TEXTAREA></TD></TR>";
	$data .= "<TR><TD>&nbsp;</TD><TD><INPUT TYPE=SUBMIT VALUE=\"SEND EMAIL!\"></TD></TR></TABLE><P>$footer</CENTER></FORM>";
	printpage($title,$data,"");
	}

// IF A RECIPIENT, SUBJECT AND MESSAGE FOUND THEN ATTEMPT TO SEND IT
if ($recipient != '' && $subject != '' && $_POST['message'] != '')
	{
	if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg($GLOBALS['program'],$footer))
		{
		header("Location: $thisprog?invalidconfig");
		exit;
		}
	if ($requireicode == 'y' && $_POST['icode'] != strtoupper(substr(md5(date("d",time())),0,5)))
		{
		header("Location: $thisprog?invalidcode");
		exit;
		}
	if ($_POST['email'] != '' && preg_match($validemail,$_POST['email']))
		{
		$headers .= "From: $fromname <" . $_POST['email'] . ">\r\n";
		$headers .= "Reply-to: $fromname <" . $_POST['email'] . ">\r\n";
		}
	else
		{
		if ($requireemail == 'y')
			{
			header("Location: $thisprog?emailreq");
			exit;
			}
		$headers = "";
		}

// IF SERVER VALIDATION IS REQUIRED CHECK REFERING SERVER AND REDIRECT TO ERROR PAGE IF INVALID
if ($requirerefer == 'y')
	{
	$validarray = explode(",",$validservers);
	foreach ($validarray as $valid)
		{
		if (ereg($valid,$_SERVER['HTTP_REFERER']))
			{
			$serverisvalid = 'y';
			}
		}
	if ($serverisvalid != 'y') {header("Location: $thisprog?invalidserver"); exit;}
	}

	$_POST['message'] = stripslashes($_POST['message']);
	$subject = stripslashes($subject);
	if ($_POST['email'] == '') {$_POST['email'] = "bounce@" . $_SERVER['SERVER_NAME'];}
	foreach ($_POST as $fname => $fvalue)
		{
		if ($fname != '' && $fname != 'email' && $fname != 'name' && $fname != 'recip' && $fname != 'subject' && $fname != 'message' && $fname != 'thankspage' && $fname != 'icode' && $fvalue != '')
			{
			$formelements .= strtoupper($fname) . ": $fvalue\n";
			}
		}
	if ($formelements != '') {$formelements = "\n" . $formelements;}
	$newmessage = $formelements . $_POST['message'];
	mail ($recipient,$subject,$newmessage,$headers);
	// IF A THANKS PAGE WAS PROVIDED IN THE FORM USE IT
	if ($_POST['thankspage'])
		{
		$_POST['thankspage'] = "http://" . str_replace("http://","",$_POST['thankspage']);
		$thankspage = $_POST['thankspage'];
		header("Location: $thankspage");
		exit;
		}
	// IF NO THANKS PAGE WAS PROVIDED IN THE FORM USE THE DEFAULT THANKS PAGE
	else
		{
		header("Location: $defaultpage");
		exit;
		}
	}
// DISPLAY THE DEFAULT THANKS PAGE - YOU SHOULDN'T NEED TO USE THIS!
elseif ($_SERVER['QUERY_STRING'] == 'thanks')
	{
	if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg($GLOBALS['program'],$footer))
		{
		header("Location: $thisprog?invalidconfig");
		exit;
		}
	$data .= "<CENTER>\n\n";
	$data .= "<BIG><B>Thanks for contacting me! I will get back to you shortly if necessary!</B></BIG>";
	$data .= $footer . "\n\n</CENTER>";
	printpage($title,$data,"");
	}
// DISPLAY THE ERROR IF AN EMAIL ADDRESS IS MISSING AND REQUIRED!
elseif ($_SERVER['QUERY_STRING'] == 'emailreq')
	{
	if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg($GLOBALS['program'],$footer))
		{
		header("Location: $thisprog?invalidconfig");
		exit;
		}
	$data = "";
	$data .= "<CENTER>\n\n";
	$data .= "<BIG><B>You need to enter <I>a valid</I> email address!</B></BIG>";
	$data .= $footer . "\n\n</CENTER>";
	printpage($title,$data,"");
	}
// DISPLAY THE INVALID SERVER REFERER ERROR
elseif ($_SERVER['QUERY_STRING'] == 'invalidserver')
	{
	if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg("$GLOBALS[program]",$footer))
		{
		header("Location: $thisprog?invalidconfig");
		exit;
		}
	$data .= "<CENTER>\n\n";
	$data .= "<BIG><B>Sorry, this server is not permitted to execute this script!</B></BIG>";
	$data .= $footer . "\n\n</CENTER>";
	printpage($title,$data,"");
	}
// DISPLAY THE ERROR FOR PEOPLE ATTEMPTING TO REMOVE THE FOOTER (WHO FAIL)
elseif ($_SERVER['QUERY_STRING'] == 'invalidconfig')
	{
	header("Location: http://www.pixelatedbylev.com/footer.php");
	exit;
	}
// DISPLAY THE ERROR FOR PEOPLE ENTER THE WRONG IMAGE-CODE (TO PREVENT SPAM!!!)
elseif ($_SERVER['QUERY_STRING'] == 'invalidcode')
	{
	if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg("$GLOBALS[program]",$footer))
		{
		header("Location: $thisprog?invalidconfig");
		exit;
		}
	$data .= "<CENTER>\n\n";
	$data .= "<BIG><B>Please make sure to enter the valid image code in order to prevent spam!</B></BIG><P><IMG SRC=\"$thisprog?imagecode\" ALT=\"FUCK SPAM\">";
	$data .= $footer . "\n\n</CENTER>";
	printpage($title,$data,"");
	}
// RETURN THE DEFAULT FORM IF NOTHING ELSE WAS DEFINED
else
	{
	header("Location: $thisprog");
	exit;
	}

function get_footer ()
	{
	$data = "\n<P>\n<SMALL>powered by $GLOBALS[program]! open source php scripting at ";
	$data .= "<A HREF=http://www.pixelatedbylev.com>www.pixelatedbylev.com</A>!</SMALL>";
	return $data;
	}

function printpage ($title,$data,$meta)
	{
	$data = "<BR>\n" . $data;
	$newtitle = $GLOBALS['program'] . " -> " . $title;
	$template = file_get_contents($GLOBALS['template']);
	$newdata = str_replace('$title',$newtitle,$template);
	$newdata = str_replace('$meta',$meta,$newdata);
	$newdata = str_replace('$data',$data,$newdata);
	echo $newdata;
	exit;
	}

?>