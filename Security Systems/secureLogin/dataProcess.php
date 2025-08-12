<?php

$namePassCombos = array (      

	"name1" => "pass1",     //  Add as many usernames and passwords  as you wish here
	"name2" => "pass2",     //  Be sure to add these usernames to "secure.php" page also
	"name3" => "pass3"   

);

// Secure multiple user Log-In script by Dave Lauderdale - Originally published at: www.digi-dl.com

$loginName = $_POST['loginName']; $passWord = $_POST['passWord'];
$passWord = preg_replace ("/</","[",$passWord); $loginName = preg_replace ("/>/","]",$loginName); 
$loginName = str_replace(".."," ",$loginName); $passWord = str_replace(".."," ",$passWord);        
$loginName=trim($loginName); $passWord=trim($passWord);
 
function success($loginName)
{
        $filename = "user.log";
	$log=fopen($filename, "w+");
     	fputs($log, $loginName);
	fclose($log);
	Header("Location: secure.php");
}

function error($notice){
if($notice=="0"){
	echo"
	<title>Log-In Error: Absent username / password</title>
	<body><br><p style=\"font:11pt arial\"><font color=red>NOTICE:</font> Log-In Error: Absent username / password.
	<br><br><br>Click <a href=\"javascript:history.back(1)\" style=\"color:black\">here</a> to try again.
	</body></html>
	"; exit;
	}

if($notice=="1"){
	echo"
	<title>Log-In Error: Incorrect username / password</title>
	<body><br><p style=\"font:11pt arial\"><font color=red>NOTICE:</font> Log-In Error: Incorrect username / password.
	<br><br><br>Click <a href=\"javascript:history.back(1)\" style=\"color:black\">here</a> to try again.
	</body></html>
	"; exit;
	}
}

if (($loginName == "") || ($passWord == "")) { $notice=0; error($notice); }
else if (strcmp($namePassCombos[$loginName],$passWord) != 0) { $notice=1; error($notice); }
else if (strcmp($namePassCombos[$loginName],$passWord) == 0) { success($loginName); }
?>