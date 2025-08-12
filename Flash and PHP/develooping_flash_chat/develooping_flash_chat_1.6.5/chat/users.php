<?php 
header("Expires: ".gmdate("D, d M Y H:i:s")."GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
?>
<?php 
/*	Users management for develooping flash chat.	        */
/*	version 1.6.5 Created by Juan Carlos Pos	            */
/*  corrected bug deleting users                            */
/*	juancarlos@develooping.com	                            */
error_reporting(7);
require ('required/config.php');
$chat_file_ok = "required/msg.txt";//message file

$users_file = "required/users.txt";//The file where you save users and passwords.


/*	check the user  */
/*	--------------- */
$text_string = join ('', file ($users_file));
$valid_user =  "valid";
if (ereg("$person\n kicked\n", $text_string)){
$valid_user = "kicked";
}
if (ereg("$person\n banned\n", $text_string)){
$valid_user = "banned";
}

if ($bye!="bye"){
$person = trim(str_replace("\r\n", "", $person));
echo "action=";
echo $action;
echo "&password=";
echo $password;
echo "&person=";

if ($action =="delete"){

//delete user and password
//------------------------

$text_string = join ('', file ($users_file));
$new_list= ereg_replace ("$person\n $password\n", "", $text_string);
$fusers = fopen($users_file, "w");
$fwusers = fwrite($fusers, $new_list);
fclose($fusers);


if((strval(trim($new_list))=="") and ($delete_empty_room=="yes")){//if not users delete messages
$fuchat = fopen($chat_file_ok, "w");
$blanktext="";
$fwuchat = fwrite($fuchat, $blanktext);
fclose($fchat);
}

}

if ($action =="add"){
$number_to_add = 1;
$number_to_rest = 1;
$save_person_temp = $person;

//kick inactive users and delete kicked and banned users
//------------------------------------------------------

$actual_hour= date ("H");
settype($actual_hour,"integer");
$actual_minute= date ("i");
settype($actual_minute,"integer");
$actual_timing= (3600*$actual_hour)+(60*$actual_minute)+$correct_time;
settype($actual_timing,"integer");

$lines = file($users_file);
$a = count($lines);

for($i = $a; $i >= 0 ;$i=$i-2){
$each_user = strval($lines[$i]);//each connected user
$each_user = str_replace ("\n","", $each_user);
$each_password = strval($lines[$i+1]);
$each_password = str_replace ("\n","", $each_password);
$each_password = trim($each_password);
if (($each_password=="kicked") or($each_password=="banned")){
$text_string = join ('', file ($users_file));
$new_list= ereg_replace ("$each_user\n $each_password\n", "", $text_string);//delete kicked users
$fusers1 = fopen($users_file, "w");
$fwusers1 = fwrite($fusers1, $new_list);
fclose($fusers1);
if((strval(trim($new_list))=="") and ($delete_empty_room=="yes")){//if not users delete messages
$fuchat1 = fopen($chat_file_ok, "w");
$blanktext="";
$fwuchat1 = fwrite($fuchat1, $blanktext);
fclose($fuchat1);
}
}

$message_lines = file($chat_file_ok);	
$message_count = count($message_lines);

for($j = $message_count; $j >= 0 ;$j--){	
$eachmessage= strval($message_lines[$j]);

if (ereg ("\((.*):(.*):..\) $each_user : ", $eachmessage, $thetime)){// the last message this user wrote
$last_hour=strval($thetime[1]);
settype($last_hour,"integer");
$last_minute=strval($thetime[2]);
settype($last_minute,"integer");
$last_timing= (3600*$last_hour)+(60*$last_minute);
settype($last_timing,"integer");
$j=0;// finish the loop for this user
//if the last message is more than x minutes old, delete user
if(!$minutes_to_delete){$minutes_to_delete=15;}
if ((($actual_timing - $last_timing) > ($minutes_to_delete*60)) or ($last_timing > $actual_timing and $last_timing < (86400-($minutes_to_delete*60)) and $actual_timing > ($minutes_to_delete*60))){
$text_string = join ('', file ($users_file));
$new_list= ereg_replace ("$each_user\n $each_password\n", "", $text_string);//delete inactive user
$fusers2 = fopen($users_file, "w");
$fwusers2 = fwrite($fusers2, $new_list);
fclose($fusers2);
if((strval(trim($new_list))=="") and ($delete_empty_room=="yes")){//if not users delete messages
$fuchat2 = fopen($chat_file_ok, "w");
$blanktext="";
$fwuchat2 = fwrite($fuchat2, $blanktext);
fclose($fuchat2);
}
}
}
}	
}

//correct existing name adding a number
//-------------------------------------

$text_string = join ('', file ($users_file));

while (ereg ("$person(\n)?", $text_string)){//repeat while name is taken
$last_character = substr($person, -$number_to_rest);//look for the last characters in the name
$test_last_character = $last_character;
settype($test_last_character,"integer");

if (strval($test_last_character) == $last_character) {// last character is a number;
$save_person_temp = substr($person, 0, strlen($person)-$number_to_rest);
}

$person = $save_person_temp.$number_to_add;// replace last character;
$number_to_add++;
$number_to_rest = strlen($number_to_add);
}

//write entering message
//----------------------

$text_to_write = date ("(H:i:s)",time()+$correct_time)." ".$person." : ".$enter_string."\n";//compound single message
$fuchat3 = fopen($chat_file_ok, "a");
$fwuchat3 = fwrite($fuchat3, "$text_to_write");// message is appended to the msg.txt file
fclose($fuchat3);

//add user and password
//---------------------

$fusers3 = fopen($users_file, "a");
$fwusers3 = fwrite($fusers3, "$person\n $password\n");
fclose($fusers3);
}

echo $person;
echo "&usuarios=";

$lines = file($users_file);
$a = count($lines);
/*	render user list	*/
/*	-------------------	*/
//for($i = $a; $i >= 0 ;$i=$i-2){
//no invert with this line, comment this and uncomment the previous one to invert
for($i = 0; $i<$a+1 ;$i=$i+2){
$estate_to_see=	trim(strval($lines[$i+1]));	
if(($estate_to_see!="kicked") and ($estate_to_see!="banned")){
//create link for private message
echo "<a href='private_message.php?person=".$person."%26password=".trim(strval($password))."%26dest=".trim(strval($lines[$i]))."' target='private_messages'>".$lines[$i]."</a>";}
	}
	
}else{

//write exit user message
//-----------------------
// if it's not written yet???

$chatlines = file($chat_file_ok);
$acount = count($chatlines);
$the_line_value= trim(strval($chatlines[$acount-1]));
$trimmed_bye = substr ($bye_user, 1, -1); 
if(!ereg("\(..:..:..\) $person : .$trimmed_bye.", $the_line_value)){
$text_to_write = date ("(H:i:s)",time()+$correct_time)." ".$person." : ".$bye_user."\n";//compound single message
$fuchat4 = fopen($chat_file_ok, "a");
$fwuchat4 = fwrite($fuchat4, "$text_to_write");// message is appended to the msg.txt file
fclose($fuchat4);

}
	
//delete user and password
//------------------------

$text_string = join ('', file ($users_file));
$new_list= ereg_replace ("$person\n $password\n", "", $text_string);
$fusers4 = fopen($users_file, "w");
$fwusers4 = fwrite($fusers4, $new_list);
fclose($fusers4);

if((strval(trim($new_list))=="") and ($delete_empty_room=="yes")){//if not users delete messages
$fuchat5 = fopen($chat_file_ok, "w");
$blanktext="";
$fwuchat5 = fwrite($fuchat5, $blanktext);
fclose($fuchat5);
}

// window for dimissed user
//-------------------------

if($valid_user == "kicked"){
$bye_string=$bye_kicked_user;
}	

if($valid_user == "banned"){
$bye_string=$bye_banned_user;
}
?>	
<html><head><title><?echo htmlentities($bye_string)." ".$person;?>
</title></head><body bgcolor="#EEEEEE">
<script language="JavaScript">
// Set the following variable to the number of seconds the browser
// will wait before closing the window.
var gWindowCloseWait = 3;

function SetupWindowClose()
{
	window.setTimeout("window.close()",gWindowCloseWait*1000);
}

// Body onload utility (supports multiple onload functions)
var gSafeOnload = new Array();
function SafeAddOnload(f)
{
	isMac = (navigator.appVersion.indexOf("Mac")!=-1) ? true : false;
	IEmac = ((document.all)&&(isMac)) ? true : false;
	IE4 = ((document.all)&&(navigator.appVersion.indexOf("MSIE 4.")!=-1)) ? true : false;
	if (IEmac && IE4)  // IE 4.5 blows out on testing window.onload
	{
		window.onload = SafeOnload;
		gSafeOnload[gSafeOnload.length] = f;
	}
	else if  (window.onload)
	{
		if (window.onload != SafeOnload)
		{
			gSafeOnload[0] = window.onload;
			window.onload = SafeOnload;
		}		
		gSafeOnload[gSafeOnload.length] = f;
	}
	else
		window.onload = f;
}
function SafeOnload()
{
	for (var i=0;i<gSafeOnload.length;i++)
		gSafeOnload[i]();
}

// Call the following with your function as the argument
SafeAddOnload(SetupWindowClose);

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
<tr><td align="center" valign="middle">
<center><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><?echo htmlentities($bye_string)." ".$person;?></center>
</font>
</td></tr></table>
</body></html>
<?
}
?>	
	