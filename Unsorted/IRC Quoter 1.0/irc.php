<?php
// Dux0rz IRC quote input and display script
// http://www.wasterized.net/ - php section.
// Keeps a text file of IRC quotes, new quotes go to the top.
// Tags are replaced with HTML appropriate ascii chars so they are displayed correctly.
// Minor colour additions are made.


// ============SETUP OPTIONS============
// Enter a maximum amount of allowed characters in quotes. 0 = No maximum amount.
$max=1000;

// Enter the path to the .txt file  you're using to store the quotes.
// REMEMBER to CHMOD this file to 777 (Right click - select CHMOD, in most FTP clients)
$path="quotes.txt";

// Create a seperator for your quotes (how they will be displayed seperately on the HTML page).
$seperator="<BR><BR><HR color=#000000><BR>";

// If colour is enabled (1), it will add colour to any instances of "quit","Quit","join" and "joined" in the quote
// in the quote. 0 = disabled.
$colour="1";

// If date stamping is enabled (1), it adds a heading with the date added to all quotes.
$date_stamp="1";
// =========END OF SETUP OPTIONS========

if(!isset($submit)){ 
?>
<HTml><head><title>IRC Quotes</title></head>
<BODY topmargin=0 leftmargin=0 rightmargin=0  bgcolor="#004D71" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">

<B>Enter your own IRC quote:
<?php if($max>0){echo"(Under $max characters please).";}?></B><BR><BR>

<form method=post action=<?=$PHP_SELF?>>
<textarea rows=10 cols=35 name=txt></textarea>

<input name=submit type=submit value="Submit Quote"><BR><BR>
</form></body></html>

<?php
$fp=fopen($path,"r")or die("Cannot read quotes file, please check CHMOD values.");
$oink=fread($fp,filesize($path));
echo nl2br($oink);
fclose($fp);
}else{
?>

<HTml><head><title>IRC Quotes</title></head>
<BODY topmargin=0 leftmargin=0 rightmargin=0  bgcolor="#004D71" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">

<?php

$count=strlen($txt);
if($count>$max&&$max>0){echo"Quotes must be a maximum of $max characters. Your quote has $count.";exit;}

$fp=fopen($path,"r")or die("Cannot read quotes file, please check CHMOD values.");
$oink=fread($fp,filesize($path));
fclose($fp);

$txt=str_replace("<","&#60;",$txt);
$txt=str_replace(">","&#62;</B>",$txt);
$txt=str_replace("&#60;","<B>&#60;",$txt);

$txt=date("F d Y")."<BR><BR>".$txt."\n".$seperator;

if($colour==1){
$txt=str_replace("quit","<font color=red>quit</font>",$txt);
$txt=str_replace("Quit","<font color=red>Quit</font>",$txt);
$txt=str_replace("joined","<font color=green>joined</font>",$txt);
$txt=str_replace("join","<font color=green>join</font>",$txt);
}
$whole=$txt.$oink;
// add new text
$fp=fopen($path,"wb")or die("Cannot open quotes file for writing, please check CHMOD values.");
fwrite($fp,$whole);
fclose($fp);

echo "<B>Your quote has been added successfully. Thank you.</b><BR><BR><BR>";
echo nl2br($txt).nl2br($oink);
}
?>