<?php

/* ------------------------------------------------------------ */
/*								*/
/*	File Area Management System (FAMS)			*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 24.07.01		Version: Delete Comments 1.00	*/
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/



require("../config.php");
if(!$nick)
{
include("layout_top_e.php");
echo $loginfailed;
include("layout_down_e.php");
exit;
}
else
{
	if(!$passwort)
	{
	include("layout_top_e.php");
	echo $loginfailed;
	include("layout_down_e.php");
	exit;
	}
	else
	{

	$logon = mysql_query("SELECT * FROM dl_users WHERE (user_name = '$nick') && (user_pwd = '$passwort')");
	$loggedon=mysql_fetch_array($logon);	
	if (!$loggedon)
	{
	include("layout_top_e.php");
	echo $loginfailed;
	include("layout_down_e.php");
	exit;		
	}
	else
	{

		$getstatus = mysql_query("SELECT * FROM dl_users WHERE (user_name = '$nick') && (user_pwd = '$passwort')");
		while ($status1=mysql_fetch_array($getstatus))	
		{
		$level = $status1['user_level'];	

			if ($level <= '1')
			{
			include("layout_top_e.php");
			echo $nopermission;
			include("layout_down_e.php");
			exit;		
			}		
		}

$mode = "anzeigen";
$modefile = "del_comments.php";
$do = "Löschen";


if($sec) {
   switch($sec) {
	case 'del_entry':

include("./layout_top.php");

$result = "DELETE FROM dl_comments WHERE (EID = '$id2') AND (id = '$id')";
      	if(!$result = mysql_query($result))
	die("Fehler! Die Datenbank  konnte nicht aktualisiert werden!");


?>



Der Eintrag wurde erfolgreich gelöscht...<P> 
[ <A HREF=./index.php>Hauptauswahl</A> ]
<BR>

<?php

include("./layout_down.php");

	exit;
	break;
	case 'go':

include("./layout_top.php");

echo "Der folgende Kommentar kann jetzt bearbeitet werden:<P>";

$view = mysql_query("SELECT * FROM dl_comments WHERE (EID = '$id2') AND (id = '$id')");
while ($db=mysql_fetch_array($view))	
{

Echo "

<FORM METHOD=\"POST\" ACTION=$PHP_SELF?id=".$db['id']."&id2=".$db['EID'].">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% CLASS=tabelle-view>
<TR>
<TD WIDTH=150 CLASS=file>
<B>Name:</B> 
</TD>
<TD WIDTH=450 CLASS=file>
".$db['name']."
</TD>
</TR>
";

	$icq = "".$db['icq']."";
	if(empty($icq))
	{
	echo "";
	}
	else
	{
	Echo "
	<TR>
	<TD WIDTH=150 CLASS=file>
	<B>ICQ Nummer:</B>
	</TD>
	<TD WIDTH=450 CLASS=file>
	$icq
	</TD>
	</TR>
	";
	}

	$mail = "".$db['email']."";
	if(empty($mail))
	{
	echo "";
	}
	else
	{
	Echo "
	<TR>
	<TD WIDTH=150 CLASS=file>
	<B>E-Mail:</B>
	</TD>
	<TD WIDTH=450 CLASS=file>
	$mail
	</TD>
	</TR>
	";
	}

	$url = "".$db['url']."";
	if(empty($url))
	{
	echo "";
	}
	else
	{
	Echo "
	<TR>
	<TD WIDTH=150 CLASS=file>
	<B>Website:</B>
	</TD>
	<TD WIDTH=450 CLASS=file>
	$url
	</TD>
	</TR>
	";
	}

echo "
<TR>
<TD WIDTH=150 CLASS=file>
<B>Überschrift:</B>
</TD>
<TD WIDTH=450 CLASS=file>
".$db['headline']."
</TD>
</TR>
<TR>
<TD WIDTH=150 CLASS=file>
<B>Kommentar:</B> 
</TD>
<TD WIDTH=450 CLASS=file>
".$db['comment']."

</TD>
</TR>

<TR>
<TD WIDTH=150 CLASS=file VALIGN=top>
&nbsp;
</TD>
<TD WIDTH=450 CLASS=file>
<BR>
<INPUT TYPE=hidden NAME=sec VALUE=\"del_entry\">
<INPUT TYPE=\"submit\" VALUE=\"Kommentar löschen\">
</TD>
</TR>
</TABLE>
</FORM>



<BR>
";
}


include("./layout_down.php");

	exit;
	break;
	case 'show':

include("./layout_top.php");

include("showcomments.php");

include("./layout_down.php");

	exit;
	break;
	}
}

include("./layout_top.php");

include("selectcomments.php");

include("./layout_down.php");


}
}
}

?>

