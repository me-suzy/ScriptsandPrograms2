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
/*	Date: 23.07.01		Version: Show Images		*/		
/*	Geändert am: 23.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");

$pagetyp = "main";


?>

<HTML>
<HEAD>
<TITLE>
Screenshot
</TITLE>

<link rel="stylesheet" href="./popup_style.css">

</HEAD>

<BODY>
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=100% HEIGHT=100% VALIGN=top CLASS=popup>

<CENTER>
<IMG BORDER=0 SRC="<?php echo $image; ?>" ALT="">
<P>

<?php

if ($s == '1')
{
    	$result = mysql_fetch_array(mysql_query("SELECT * FROM dl_files WHERE (EID = '$id')"));
    	$image1hits = $result['image1hits'];
    	$image1hits++;
    	$res = mysql_query("UPDATE dl_files SET image1hits='$image1hits' WHERE (EID = '$id')");

	$result1 = mysql_query("SELECT * FROM dl_files WHERE (EID = '$id') ORDER BY EID DESC");
	$db1=mysql_fetch_array($result1);	
	echo "[ Bildaufrufe: ".$db1['image1hits']." | <A HREF=javascript:window.close()>Fenster schliessen</A> ]";
}
elseif ($s == '2')
{
    	$result = mysql_fetch_array(mysql_query("SELECT * FROM dl_files WHERE (EID = '$id')"));
    	$image2hits = $result['image2hits'];
    	$image2hits++;
    	$res = mysql_query("UPDATE dl_files SET image2hits='$image2hits' WHERE (EID = '$id')");

	$result1 = mysql_query("SELECT * FROM dl_files WHERE (EID = '$id') ORDER BY EID DESC");
	$db1=mysql_fetch_array($result1);	
	echo "[ Bildaufrufe: ".$db1['image2hits']." | <A HREF=javascript:window.close()>Fenster schliessen</A> ]";
}
elseif ($s == '3')
{
    	$result = mysql_fetch_array(mysql_query("SELECT * FROM dl_files WHERE (EID = '$id')"));
    	$image3hits = $result['image3hits'];
    	$image3hits++;
    	$res = mysql_query("UPDATE dl_files SET image3hits='$image3hits' WHERE (EID = '$id')");

	$result1 = mysql_query("SELECT * FROM dl_files WHERE (EID = '$id') ORDER BY EID DESC");
	$db1=mysql_fetch_array($result1);	
	echo "[ Bildaufrufe: ".$db1['image3hits']." | <A HREF=javascript:window.close()>Fenster schliessen</A> ]";
}
elseif ($s == '4')
{
    	$result = mysql_fetch_array(mysql_query("SELECT * FROM dl_files WHERE (EID = '$id')"));
    	$image4hits = $result['image4hits'];
    	$image4hits++;
    	$res = mysql_query("UPDATE dl_files SET image4hits='$image4hits' WHERE (EID = '$id')");

	$result1 = mysql_query("SELECT * FROM dl_files WHERE (EID = '$id') ORDER BY EID DESC");
	$db1=mysql_fetch_array($result1);	
	echo "[ Bildaufrufe: ".$db1['image4hits']." | <A HREF=javascript:window.close()>Fenster schliessen</A> ]";
}
elseif ($s == '5')
{
    	$result = mysql_fetch_array(mysql_query("SELECT * FROM dl_files WHERE (EID = '$id')"));
    	$image5hits = $result['image5hits'];
    	$image5hits++;
    	$res = mysql_query("UPDATE dl_files SET image5hits='$image5hits' WHERE (EID = '$id')");

	$result1 = mysql_query("SELECT * FROM dl_files WHERE (EID = '$id') ORDER BY EID DESC");
	$db1=mysql_fetch_array($result1);	
	echo "[ Bildaufrufe: ".$db1['image5hits']." | <A HREF=javascript:window.close()>Fenster schliessen</A> ]";
}

?>

</TD>
</TR>
</TABLE>

</BODY>
</HTML>
