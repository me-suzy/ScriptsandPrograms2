<?php 
//if username(.num) and password is taken go back
$users_file = "required/users.txt";
$text_string = join ('', file ($users_file));
if (ereg ("$person(0|[1-9][0-9]*)*\n $password\n", $text_string)){
header("location:../intro.php?nametaken=1&self=".$PHP_SELF);
}

/*	develooping flash chat	  	          */
/*	by Juan Carlos Posé                 */
/*	juancarlos@develooping.com	            */
/*	version 1.6.5	                    */

if (!ereg("intro\.php",$self)){
header("Location: ../index.php");
    exit();
}

require ('required/config.php');

$number_of_bad_words = count($words_to_filter);
for($i = 0; $i <= $number_of_bad_words ;$i++){
if (strval($words_to_filter[$i])!=""){
if(eregi(strval($words_to_filter[$i]), $person)){
header("Location: ../intro.php?nametaken=1&self=".$PHP_SELF);
}
}
}


if ($password_system=="ip"){
if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP"); 
else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR"); 
else $ip = "UNKNOWN"; 
if (($password != $ip) or ($person=="")){
header("location:../intro.php?self=".$PHP_SELF);
}
}else{
if (($person=="") or($password == "")) {
header("location:../intro.php?self=".$PHP_SELF);
}
}
?>
<html>
<head>
<title>Develooping Flash Chat</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
<!--

<?php 


if(eregi("win", $HTTP_USER_AGENT) and eregi("MSIE", $HTTP_USER_AGENT)){
$browser= "explorerwin";
}
else{
$browser= "";
}

?>


function errorsuppressor(){
return true;
}
window.onerror=errorsuppressor;


function MM_openBrWindow(theURL,winName,features) {
window.open(theURL,winName,features);
}

var deleted=0;
var esapersona = '<?php echo $person;?>';


function addperson(unapersona){
var esapersona = unapersona;
}

function deleteuser(lapersona){
var deleted=1;
var laurl='users.php?person='+lapersona+'&password=<?php echo $password;?>&bye=bye';
window.open(laurl,'Bye','toolbar=no,scrollbars=no,width=300,height=100');
location.replace('../intro.php?self=<?php echo $PHP_SELF;?>');
}

function borraunload(){
document.borratipo.submit();
if(deleted!=1){
deleteuser(esapersona);
deleted=1;
}

}

//-->
</script>
<style type="text/css">
body {
background-color: #EEEEEE;
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;  
font-size : 10px;  
margin : 0px 0px 0px 0px;
}
</style>
</head>
<body onUnload="borraunload();" bgcolor="#FFFFFF"> 

<iframe name="private_messages" src="private_message.php"
					  width="550" height="50"
					  scrolling="No" frameborder="0" marginwidth="0" marginheight="0">
					  </iframe>   
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0"
 ID="chat" WIDTH=550 HEIGHT=300>
 <PARAM NAME=movie VALUE="chat.swf?person=<?php echo $person;?>&password=<?php echo $password;?>&browser=<?php echo $browser;?>"><PARAM NAME=menu VALUE=false><PARAM NAME=quality VALUE=best><PARAM NAME=wmode VALUE=transparent><EMBED name="chat" src="chat.swf?person=<?php echo $person;?>&password=<?php echo $password;?>&browser=<?php echo $browser;?>" menu=false quality=best wmode=transparent WIDTH=550 HEIGHT=300 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" swLiveConnect="true"></EMBED>
</OBJECT>
<form name="borratipo" method="post" action="users.php">
<input type="hidden" name="person" value="<?php echo $person;?>"><input type="hidden" name="password" value="<?php echo $password;?>"><input type="hidden" name="bye" value="bye">
</form>
</body>
</html>
