<?php

/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

define('KELLELE', 'lauri.kasvandik@gmail.com');// kellele mail saadetakse

require_once "classes/tpl.class.php";

$teade = '';

if(isset($_POST['submit']))
{
	$vead = array();

	if(strpos($_POST['email'], '@')<2)	{ $vead[] = 'Incorrect e-mail address!'; }
	if(!strlen(trim($_POST['body'])))	{ $vead[] = 'Missing mail text!'; }
	if(empty($_POST['name']))			{ $_POST['name'] = $_POST['email']; }

	if(count($vead))
	{
		$teade = "<p><b>There was error(s):</b></p>\n<ul>\n";
		foreach($vead as $viga)
		{
			$teade .= '<li>'.$viga."</li>\n";
		}
		$teade .="</ul>";
	}
	else
	{
		$headers  = 'From: '.$_POST['name'].' <'.$_POST['email'].">\r\n";
		$headers .= "X-Mailer: mailform\r\n";
		$headers .= "Content-type: text/plain; charset=ISO-8859-1";

		$kirjasisu = $_POST['body'];
		$kirjasisu .= "\n\nIP:".$_SERVER['REMOTE_ADDR'];

		#echo $kirjasisu;

		@mail(KELLELE, 'XLquiz - http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'], $kirjasisu, $headers);
		header('Location:' . $_SERVER['SCRIPT_NAME'] . '?sent=1');
	}
}
else
{
	$_POST['name'] = $_POST['email'] = $_POST['body'] = '';
}

$mailform = <<<html
<h2>Feedback</h2>
<div class="feedbackForm">
%TEADE%

<form action="{$_SERVER['SCRIPT_NAME']}" method="post">

	<label class="feedback" for="name">Name</label> <input type="text" id="name" class="input-box" name="name" value="{$_POST['name']}" /><br />

	<label class="feedback" for="e-mail">E-mail (*)</label> <input type="text" id="e-mail" name="email" class="input-box"  value="{$_POST['email']}" /><br />

	Message: <br />

	<textarea cols="40" rows="6" id="body" name="body">{$_POST['body']}</textarea><br/>

	<input type="submit" value="Send" name="submit" class="submit-button" />
	<!--input type="button" value="Back" name="back" onclick="window.location='index.php'" /-->
	<a href="index.php">Back</a>
</form>

</div>
html;

if(isset($_GET['sent']))
{
	$mailform = '<h1>Thanks!</h1><p><b>e-mail is sent.</b></p><p><a href="index.php">Back to mainpage</a></p>';
}


$design = str_replace('%TEADE%', $teade, $mailform);

#$design = file_get_contents('tagasiside2.html');
#$design = str_replace('%SISU%',  $mailform, $design);

$tpl['title'] = "Feedback";
$tpl['body'] = $design;

tpl::out('body.php');

?>