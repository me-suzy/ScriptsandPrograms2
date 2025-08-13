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
/*	Date: 25.07.01		Version: Add User 1.03		*/
/*	Geändert am: 25.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");
require("./lang.php");

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

			if ($level <= '2')
			{
			include("layout_top_e.php");
			echo $nopermission;
			include("layout_down_e.php");
			exit;		
			}		
		}

if($sec) {
   switch($sec) {
	case 'add_user':

include("layout_top.php");

	if (empty($user_name) || empty($user_pass))
	{
	Echo "

	<B>Fehler!</B><BR> Es müssen alle Felder ausgefüllt sein, damit der User erstellt werden kann!<P>
	[ <A HREF=javascript:history.back()>Zurück</A> ]

	";
	include("layout_down.php");
	exit;
	}


	
	$check1 = mysql_query("SELECT * FROM dl_users ORDER BY EID DESC");
	while ($db6=mysql_fetch_array($check1))	
	{

	$usercheck = trim(strtolower($db6['user_name']));
	$username = trim(strtolower($user_name));

		if ($username == $usercheck)
		{
		Echo "
		<B>Fehler!</B><BR> Ein Benutzer mit dem gewünschten Namen ist bereits vorhanden und kann nicht noch einmal erstellt werden!<P>
		[ <A HREF=javascript:history.back()>Zurück</A> ]

		";
		include("layout_down.php");
		exit;
		}
	}

		$user_pwd = md5($user_pass);

		$result = mysql_query("INSERT INTO dl_users (user_name, user_level, user_pwd) VALUES ('$user_name', '$user_level', '$user_pwd')");



?>

Der Benutzer wurde unter dem Namen <B><?php echo $user_name; ?></B> erfolgreich erstellt!
<P>
[ <A HREF=./index1.php>Hauptauswahl</A> ]

<?php

include("layout_down.php");

	exit;
	break;
	}
}






include("layout_top.php");

?>


<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>
<TR>
<TD WIDTH=600 COLSPAN=2 CLASS=file>
Es müssen alle Felder ausgefüllt sein, um einen neuen User zu erstellen!
<P>

<B>Userlevel 1:</B> Der Benutzer kann Files hinzufügen und editieren.
<P>
<B>Userlevel 2:</B> Der Benutzer kann Files hinzufügen, editieren und löschen. Weiterhin kann er das Kommentar System verwalten und dort
entsprechend editieren oder löschen.
<P>
<B>Userlevel 3:</B> Der Administrator hat Zugriff auf alle Einstellungen. Er kann neue User und Kategorien einrichten, editieren und löschen sowie alle anderen Funktionen
des System nutzen.<P>




</DIV>
</TD>
</TR>
<TR>
<TD WIDTH=150 CLASS=file>
<B>Name:</B>
</TD>
<TD WIDTH=450 CLASS=file>
<INPUT TYPE=text NAME=user_name SIZE=20>
</TD>
</TR>
<TR>
<TD WIDTH=150 CLASS=file>
<B>Passwort:</B>
</TD>
<TD WIDTH=450 CLASS=file>
<INPUT TYPE=password NAME=user_pass SIZE=20>
</TD>
</TR>
<TR>
<TD WIDTH=150 CLASS=file>
<B>User Level:</B>
</TD>
<TD WIDTH=450 CLASS=file>

<SELECT NAME=user_level CLASS=menu>
<OPTION VALUE="1">1</OPTION>
<OPTION VALUE="2" selected>2</OPTION>
<OPTION VALUE="3">3</OPTION>
</SELECT>

</TD>
</TR>
<TR>
<TD WIDTH=150 CLASS=file VALIGN=top>
&nbsp;
</TD>
<TD WIDTH=450 CLASS=file>
<BR>
<INPUT TYPE=hidden NAME=sec VALUE="add_user">
<INPUT TYPE="submit" VALUE="User erstellen">
</TD>
</TR>
</TABLE>
</FORM>

<BR>



<?php
include("layout_down.php");


}
}
}

?>