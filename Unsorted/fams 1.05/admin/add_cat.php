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
/*	Date: 04.05.01		Version: Add File 1.03		*/
/*	Geändert am: 25.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");
require("./show_cats.php");
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

$pagetyp = "admin";

if($sec) {
   switch($sec) {

    case 'addcat_check':
	
include("./layout_top.php");

if ($cat_names == '')
	{
	Echo "<B>Fehler!</B><BR> Sie müssen einen Namen für die Kategorie eingeben!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	exit;
	} 

$result = mysql_query("SELECT * FROM dl_categories");
while ($db=mysql_fetch_array($result))
{
	$new_cat = "".$db['cat_names']."";
	if ($cat_names == $new_cat)
	{
	Echo "<B>Fehler!</B><BR> Eine Kategorie mit diesem Namen ist bereits in der Datenbank vorhanden! Bitte wählen Sie einen anderen Namen.<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	exit;
	}
}

$upfiles = mysql_query("INSERT INTO dl_categories (cat_names) VALUES ('$cat_names')");

echo "Die Kategorie wurde erfolgreich hinzugefügt!";

include("./layout_down.php");
	
	exit;
	break;
	}
}

include("./layout_top.php");


?>

Bitte geben Sie den von Ihnen geünschten Namen für die neue Download Kategorie ein:

<P>


<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=150 CLASS=file>
Name der Kategorie:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=cat_names SIZE=20>
</TD>
</TR>
<TR>
<TD WIDTH=150 VALIGN=top CLASS=file>
</TD>
<TD CLASS=file>
<BR>


<INPUT TYPE=hidden NAME=sec VALUE="addcat_check">
<INPUT TYPE="submit" VALUE="Kategorie hinzufügen">

</TD>
</TR>
</TABLE>
</FORM>



<?php

include("./layout_down.php");

}
}
}
?>