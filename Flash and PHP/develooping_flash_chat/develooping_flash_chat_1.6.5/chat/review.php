<html>
<head>
<meta http-equiv="expires" content="31 Dec 1990">
<?php 
/*	Review messages for develooping flash chat.	    	*/
/*	by Juan Carlos Posé                                 */
/*	juancarlos@develooping.com	                        */
/*	version 1.6.5	                                        */
require ('required/config.php');
?>
<title><?php echo htmlentities($review_title);?></title>
<style type="text/css">
body {
background-color: #EEEEEE;
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;  
font-size : 10px;  
}
a:link{ color :#990000;text-decoration: none;}
a:active{ color :#FF9933;text-decoration: none;}
a:visited {  color :#CC6666;text-decoration: none;}
a:hover { text-decoration: underline; 
color : #990000;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<body bgcolor="#EEEEEE" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="285" cellspacing="3" cellpadding="3">
<tr><td width="285">
<font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#666666">
<?php 

$chat_file_ok = "required/msg.txt";//message file

$users_file = "required/users.txt";//The file where you save users and passwords

$person = str_replace ("\n"," ", $person);
$person = str_replace ("<", " ", $person);
$person = str_replace (">", " ", $person);
$person = trim ($person);
$person = stripslashes ($person);

/*	check the user  */
/*	--------------- */
$text_string = join ('', file ($users_file));
$valid_user=  "false";
if (ereg("$person\n $password\n", $text_string)){
$valid_user = "true";
}
if ($valid_user == "false"){
echo "<center>".htmlentities($kicked_user)."</center>";
}else{			
/*	reads the last $chat_lenght lines of $chat_file_ok */
/*	-------------------------------------------------- */

$lines = file($chat_file_ok);
$a = count($lines);
$u = $a - $review_lenght;

/*	check the text order */
/*	-------------------- */

if ($review_text_order == "up"){
for($i = $a; $i >= $u ;$i--){
$is_private = 0;
$show_message = 1;
$line_value= strval($lines[$i]);
$string_to_add="";
	//replace emoticons
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>b</font>", "<img src='graphics/b.gif' width='17' height='15'>",$line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>c</font>","<img src='graphics/c.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>d</font>","<img src='graphics/d.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>e</font>","<img src='graphics/e.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>f</font>","<img src='graphics/f.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>g</font>","<img src='graphics/g.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>h</font>","<img src='graphics/h.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>i</font>","<img src='graphics/i.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>j</font>","<img src='graphics/j.gif' width='17' height='15'>", $line_value);
//end replace emoticons



/*	output to the chat private and general messages */
/*	----------------------------------------------- */

if (ereg($private_message_expression, $line_value, $name) and ereg("\(de (.*) a (.*)$", $line_value, $pass)){// is a private message
	$senderpassword=strval($pass[1]);
	$receiverpassword=strval($pass[2]);
	$receivername=strval($name[1]);
	$is_private = 1;
	$show_message = 0;
	}
	if ($is_private == 1){
	if (($receivername==$person) and ereg("(.)?$password(\n)?", $receiverpassword)){//is the receiver
	$line_value = ereg_replace( "\(de (.*) a (.*)$", "", $line_value);
	echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#990000'><b>".$line_value."</b></font><br>";
	} elseif(ereg("\(..:..:..\) $person :", $line_value) and ereg("(.)?$password", $senderpassword)){//is the sender
	if (ereg("(.)?eztezamarchaoynoezta(\n)?", $receiverpassword)) {$string_to_add = $not_here_string;}
	$line_value = ereg_replace( "\(de (.*) a (.*)$", "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#990000'>$string_to_add</font><br>", $line_value);
	echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'><b>".$line_value."</b></font>";
	}else{
	$u--;
	$show_message = 0;
	} 
	}
	
	         
if (($show_message == 1) and ($line_value)){
{echo $line_value."<br>";}// is a general message
	}}
}
else{
for($i = $u; $i <= $a ;$i++){
$is_private = 0;
$show_message = 1;
$line_value= strval($lines[$i]);
$string_to_add="";

	//replace emoticons
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>b</font>", "<img src='graphics/b.gif' width='17' height='15'>",$line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>c</font>","<img src='graphics/c.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>d</font>","<img src='graphics/d.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>e</font>","<img src='graphics/e.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>f</font>","<img src='graphics/f.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>g</font>","<img src='graphics/g.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>h</font>","<img src='graphics/h.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>i</font>","<img src='graphics/i.gif' width='17' height='15'>", $line_value);
$line_value=str_replace ("<font face='Devemoticons' size='18' color='#990000'>j</font>","<img src='graphics/j.gif' width='17' height='15'>", $line_value);
//end replace emoticons

/*	output to the chat private and general messages */
/*	----------------------------------------------- */

if (ereg($private_message_expression, $line_value, $name) and ereg("\(de (.*) a (.*)$", $line_value, $pass)){// is a private message
	$senderpassword=strval($pass[1]);
	$receiverpassword=strval($pass[2]);
	$receivername=strval($name[1]);
	$is_private = 1;
	$show_message = 0;
	}
	if ($is_private == 1){
	if (($receivername==$person) and ereg("(.)?$password(\n)?", $receiverpassword)){//is the receiver
	$line_value = ereg_replace( "\(de (.*) a (.*)$", "", $line_value);
	echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#990000'><b>".$line_value."</b></font><br>";
	} elseif(ereg("\(..:..:..\) $person :", $line_value) and ereg("(.)?$password", $senderpassword)){//is the sender
	if (ereg("(.)?eztezamarchaoynoezta(\n)?", $receiverpassword)) {$string_to_add = $not_here_string;}
	$line_value = ereg_replace( "\(de (.*) a (.*)$", "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#990000'>$string_to_add</font><br>", $line_value);
	echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'><b>".$line_value."</b></font>";
	}else{
	$u--;
	$show_message = 0;
	} 
	}         
if (($show_message == 1) and ($line_value)){
{echo $line_value."<br>";}// is a general message
	}}
}
}

?>
</font></td></tr>
</table>
</body>
</html>