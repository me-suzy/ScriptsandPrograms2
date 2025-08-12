<?php 

// Secure multiple user Log-In script by Dave Lauderdale - Originally published at: www.digi-dl.com

$filename = "user.log";
$log = fopen($filename, "r");
$contents = fread($log, filesize($filename));
fclose($log);

//  Add usernames below
if ($contents == "name1" || $contents == "name2"  || $contents == "name3") {

$filename = "user.log";
$log = fopen($filename, "w+");
fputs($log,"");
fclose($log);
echo <<<EOF
<!-- #######################################################  -->
<!-- #######################################################  --> 
<!--                Begin your HTML below                     -->






<br><br>
<center>
<h1 style="font:12pt arial">THIS IS A SECRET PAGE</h1>









<!--                End your HTML above                       -->
<!-- #######################################################  -->
<!-- #######################################################  --> 
EOF;
}
else echo "<center><Br><font color=red face=arial size=3 >Error</font>...<font face=arial size=3 >You will have to log on via the log on form to view this page.<br><br><a href='login.html' style='color:black'>Click here</a> to try again.";
?>