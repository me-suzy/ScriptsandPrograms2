<?php
ob_start();

/* ------------------------------------------------------------ */
/*								*/
/*	File Area Management System (FAMS)			*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 03.05.01		Version: Admin Index 1.00	*/
/*	Geändert am: 04.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");


if($sec) {
   switch($sec) {

    case 'login_check':

	$user_pwd = md5($user_pass);

	$logon = mysql_query("SELECT * FROM dl_users WHERE (user_name = '$user_name') && (user_pwd = '$user_pwd')");
	$db3=mysql_fetch_array($logon);	
	if (!$db3)
	{

		if ($user_name == '')	
		{
		include("layout_top_e.php");
		Echo "<B>Fehler!</B><BR> Sie müssen einen Benutzernamen eingeben!<P>";
		Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
		include("layout_down_e.php");
		exit;
		} 

		if ($user_pass == '')	
		{
		include("layout_top_e.php");
		Echo "<B>Fehler!</B><BR> Sie müssen ein Passwort eingeben!<P>";
		Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
		include("layout_down_e.php");
		exit;
		} 

	include("layout_top_e.php");
	Echo "<B>Fehler!</B><BR> Die Zugangsdaten sind ungültig!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	include("layout_down_e.php");
	exit;		
	}
	else
	{

setcookie ("nick", "$user_name", time()+3000);
setcookie ("passwort", "$user_pwd", time()+3000);

header("location: ./index1.php");
	
	}


	exit;
	break;
	}
}


include("layout_top_e.php");
?>




Bitte geben Sie Ihren Benutzernamen und Ihr Passwort ein, um sich am System anzumelden.
<P>

<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=70 CLASS=file>
<B>Name:</B>
</TD>
<TD WIDTH=450>
<INPUT TYPE=text NAME=user_name SIZE=30>
</TD>
</TR>
<TR>
<TD WIDTH=70 CLASS=file>
<B>Passwort:</B>
</TD>
<TD WIDTH=450>
<INPUT TYPE=PASSWORD NAME=user_pass SIZE=30>
</TD>
</TR>
<TR>
<TD WIDTH=150 VALIGN=top CLASS=file>
</TD>
<TD CLASS=file>
<BR>

<INPUT TYPE=hidden NAME=sec VALUE="login_check">
<INPUT TYPE="submit" VALUE="Einloggen!">

</TD>
</TR>
</TABLE>
</FORM>


<?php
include("layout_down_e.php");
?>