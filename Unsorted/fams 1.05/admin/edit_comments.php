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
/*	Date: 24.07.01		Version: Edit Comments 1.00	*/
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
$modefile = "edit_comments.php";
$do = "Editieren";



if($sec) {
   switch($sec) {
	case 'edit_entry':

include("./layout_top.php");

	if (empty($name) || empty($headline) || empty($comment))
	{
	Echo "

	<B>Fehler!</B><BR> Es müssen die Felder: Name, Überschrift und Kommentar ausgefüllt sein, damit der Kommentar editiert werden kann!<P>
	[ <A HREF=javascript:history.back()>Zurück</A> ]

	";
	include("./layout_down.php");
	exit;
	} 

$result = "UPDATE dl_comments SET name='$name', icq='$icq', email='$email', url='$url', headline='$headline', comment='$comment' WHERE (EID = '$id2') AND (id = '$id')";
if(!$result = mysql_query($result)) 
	{
	die("Fehler! Die Datenbank konnte nicht aktualisiert werden!");
     	}

?>



Der Eintrag wurde erfolgreich aktualisiert...<P> 

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
<INPUT TYPE=text NAME=name SIZE=20 VALUE=\"".$db['name']."\">
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
	<INPUT TYPE=text NAME=icq SIZE=20 VALUE=\"$icq\">
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
	<INPUT TYPE=text NAME=email SIZE=30 VALUE=\"$mail\">
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
	<INPUT TYPE=text NAME=url SIZE=30 VALUE=\"$url\">
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
<INPUT TYPE=text NAME=headline SIZE=60 VALUE=\"".$db['headline']."\">
</TD>
</TR>
<TR>
<TD WIDTH=150 CLASS=file VALIGN=top>
<BR>
<B>Kommentar:</B> 
<P>
</TD>
<TD WIDTH=450 CLASS=file>
<TEXTAREA NAME=comment ROWS=8 COLS=60>".$db['comment']."
</TEXTAREA>
</TD>
</TR>

<TR>
<TD WIDTH=150 CLASS=file VALIGN=top>
&nbsp;
</TD>
<TD WIDTH=450 CLASS=file>
<BR>
<INPUT TYPE=hidden NAME=sec VALUE=\"edit_entry\">
<INPUT TYPE=\"submit\" VALUE=\"Änderungen speichern\">
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

