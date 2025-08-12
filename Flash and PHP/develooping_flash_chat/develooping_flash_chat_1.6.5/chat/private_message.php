<html>
<head>
<title></title>
</head>
<body bgcolor="#EEEEEE">
<?php 
/*	Private messages page for develooping flash chat.   */
/*	version 1.6.5 Created by Juan Carlos Pos	            */
/*corrected bud deleting users                          */
/*	juancarlos@develooping.com	                        */
//error_reporting(7);
require ('required/config.php');


if ($dest!=""){
//inserta movie de flash si hay destinatario y el usuario esta en la lista

$users_file = "required/users.txt";
$text_string = join ('', file ($users_file));
if (ereg("$person\n $password\n", $text_string)){
	?>
	
<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0"
ID="private_messages" WIDTH=550 HEIGHT=50><PARAM NAME=movie VALUE="private_messages.swf?pre=<?=$before_name?>&post=<?=$after_name?>&dest=<?=$dest?>&person=<?=$person?>&password=<?=$password?>&private_message_to=<?=$private_message_to.' '.$dest?>"><PARAM
NAME=menu VALUE=false><PARAM NAME=quality VALUE=best><PARAM NAME=wmode
VALUE=transparent><EMBED name="private_messages" src="private_messages.swf?pre=<?=$before_name?>&post=<?=$after_name?>&dest=<?=$dest?>&person=<?=$person?>&password=<?=$password?>&private_message_to=<?=$private_message_to.' '.$dest?>"
quality=best menu=false wmode=transparent WIDTH=550 HEIGHT=50 TYPE="application/x-shockwave-flash"
PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"
swLiveConnect="true"></EMBED></OBJECT> 

<?php 
}
}	
?>
</body>
</html>