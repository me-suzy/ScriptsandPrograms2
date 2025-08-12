<?
/******************************************
* File      :   include.newsletter_send.php
* Project   :   Contenido
* Descr     :   Newsletter Send Function
*
* Author    :   Timo A. Hummel
* Created   :   10.05.2003
* Modified  :   10.05.2003
*
* © four for business AG
*****************************************/



    
if(!$perm->have_perm_area_action($area))
{
  $notification->displayNotification("error", i18n("Permission denied"));
} else {

if ( !isset($newsid))
{
} else {

$sql = "SELECT * FROM ".$cfg["tab"]["news"] ." WHERE idnews='$newsid'";
$db->query($sql);
$db->next_record();

$from = $db->f("newsfrom");
$subject     = $db->f("subject");
$message     = $db->f("message");
$date	    = $db->f("newsdate");
$dateday     = $date[8].$date[9].".".$date[5].$date[6].".".$date[0].$date[1].$date[2].$date[3];
$time	    = $date[11].$date[12].":".$date[14].$date[15].":".$date[17].$date[18];


$sql = "SELECT * FROM ". $cfg["tab"]["news_rcp"] ." WHERE deactivated=0 AND idclient='$client'";
$db->query($sql);
$i = 0;
$number	= $db->num_rows();
$message = str_replace("MAIL_NUMBER", "$number", $message);
$message = str_replace("MAIL_DATE", "$dateday", $message);
$message = str_replace("MAIL_TIME", "$time", $message);
//$path 	= $c

$sql = "SELECT
                idclient,
                frontendpath,
                htmlpath,
                errsite_cat,
                errsite_art
            FROM
            ".$cfg["tab"]["clients"] ." WHERE idclient='$client'";
    
$db2 = new DB_Contenido;            
$db2->query($sql);
$db2->next_record();

$path = $db2->f("htmlpath"). "news.php?";

while ($db->next_record()) {
	$to 	= $db->f("email");
	$name 	= $db->f("name");
	$message2 = str_replace("MAIL_NAME", "$name", $message);
	$message3 = str_replace("MAIL_UNSUBSCRIBE", $path."unsubscribe=".md5($to), $message2);
	$message4 = str_replace("MAIL_STOP", $path."stop=".md5($to), $message3);
	$message5 = str_replace("MAIL_GOON", $path."goon=".md5($to), $message4);
    

	if (!mail("$to", "$subject", "$message5\n\n$foot", 'From: '.$from."\n"."X-Mailer: Contenido [PHP/" . phpversion())) {
		$notsend .= $lngNews["mailcouldnotbesend1"].$to.$lngNews["mailcouldnotbesend2"] . "<br>";
	} else {
		$i = $i +1;
	}


}

    $notification->displayNotification("info", $notsend .  sprintf(i18n("Newsletter was sent to %s recipient(s)"), $i)."<br>");

}
}
?>
