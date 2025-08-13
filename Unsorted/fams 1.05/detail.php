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
/*	Date: 24.07.01		Version: Detail 1.10		*/		
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");


$pagetyp = "main";


if($sec) {
   switch($sec) {
	case 'new_wertung':

include("main_layout_head.php");



	if ($wertung == -1)
	{
	Echo "<B>Fehler!</B><BR> Bitte wählen Sie eine Bewertungs-Option aus!!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	include("main_layout_down.php");
	exit;
	} 


	$stimmen = "1";

	$res = mysql_query("INSERT INTO dl_wertung (wertung, stimmen, id) VALUES ('$wertung', '$stimmen', '$id')");

	header("location: ./detail.php?id=$id");

	exit;
	break;

	case 'upd_wertung':

include("main_layout_head.php");



	if ($wertung == -1)
	{
	Echo "<B>Fehler!</B><BR> Bitte wählen Sie eine Bewertungs-Option aus!!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	include("main_layout_down.php");
	exit;
	} 


    	$result = mysql_fetch_array(mysql_query("SELECT * FROM dl_wertung WHERE (id = '$id')"));
    	$stimmen = $result['stimmen'];
    	$stimmen++;

	$wertung1 = $result['wertung'];
	$neue_wertung = $wertung1 + $wertung;


    	$res = mysql_query("UPDATE dl_wertung SET wertung='$neue_wertung', stimmen='$stimmen' WHERE (id = '$id')");


	header("location: ./detail.php?id=$id");

	exit;
	break;

	case 'add_comment':

include("main_layout_head.php");

	if (empty($name) || empty($headline) || empty($comment))
	{
	Echo "<B>Fehler!</B><BR> Bitte füllen Sie die Felder Name, Überschrift und Kommentar aus!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	include("main_layout_down.php");
	exit;
	} 


$date = date( "d.m.Y" );
$time = date( "H:i" );
$sec2 = time();
$ip = $REMOTE_ADDR;


$spam = mysql_query("SELECT * FROM dl_comments WHERE (id = '$id') ORDER BY EID DESC LIMIT 1");
while ($db=mysql_fetch_array($spam))	
{
$spammingtime = "".$db['sec2']."";
$spamtime = $spammingtime + 180;
$spamdate = "".$db['date']."";
$spamip = "".$db['ip']."";



if ($ip == $spamip)
{
	if ($date == $spamdate)
	{
		if ($sec2 <= $spamtime)
		{
		echo "<B>Fehler!</B><BR> Das System hat nicht erlaubte Mehrfacheintragungen geloggt... sollte das Problem dauerhaft auftreten wenden Sie sich bitte an den Administrator der Seite!<P>Ihre IP Nummer, sowie Datum und Zeit wurden geloggt...<P>";
		echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
		include("main_layout_down.php");
		exit;
		}
	}
}
}


$result = mysql_query("INSERT INTO dl_comments (name, email, url, icq, headline, date, time, comment, id, ip, sec2) VALUES ('$name', '$email', '$url', '$icq', '$headline', '$date', '$time', '$comment', '$id', '$ip', '$sec2')");

header("location: ./detail.php?id=$id");


	exit;
	break;
	}
}



include("main_layout_head.php");
	

		$result = mysql_query("SELECT * FROM dl_files WHERE (EID = '$id') ORDER BY EID DESC");
		while ($db=mysql_fetch_array($result))	
		{
		
		echo "Detail-Ansicht für die Datei <B>".$db['file_name']."</B><P>";
		echo "Auf dieser Seite können Sie Ihren Kommentar zu dem File abgeben oder den Download bewerten.<P>";


		echo "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 CLASS=tabledownload WIDTH=425>";
		echo "<TR>";
		echo "<TD CLASS=downloadheader WIDTH=225><A HREF=\"./file.php?id=".$db['EID']."\"><B>".$db['file_name']."</B></A></TD>";
		echo "<TD CLASS=downloadheader WIDTH=200 ALIGN=right>";
		$autor_email = "".$db['autor_contact']."";
		if($autor_email == '')
		{
		echo "<B>Autor:</B> ".$db['autor']."";
		}
		else
		{
			$autor_con = $autor_email;
			$autor_con = trim($autor_con);
			if(substr(strtolower($autor_con), 0, 7) != "http://")
			{
				if(substr(strtolower($autor_con), 0, 4) == "www.")
				{
					$autor_con = "http://$autor_con";
				}
				else
				{
					$autor_con = "mailto:$autor_con";
				}
			}
			echo "<B>Autor:</B> <A HREF=$autor_con>".$db['autor']."</A>";	
		}
		echo "</TD>";
		echo "</TR>";
		echo "</TABLE>";
		echo "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 CLASS=tabledownload WIDTH=425>";
		echo "<TR>";
		echo "<TD CLASS=download WIDTH=425 COLSPAN=3><BR>".$db['file_description']."<BR><BR></TD>";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=download WIDTH=100><B>Download:</B> <A HREF=\"./file.php?id=".$db['EID']."\">Hier!</A></TD>";
		echo "<TD CLASS=download WIDTH=150>";

		$screen1 = $db['image1'];
		$screen2 = $db['image2'];
		$screen3 = $db['image3'];
		$screen4 = $db['image4'];
		$screen5 = $db['image5'];
		$id = $db['EID'];

		if (empty($screen1))
		{
		echo "<B>Screens:</B> ---";
		} 
		else
		{
		$size1 = GetImageSize("$screen1");
		$height1 = $size1[1] + 100;
		$width1 = $size1[0] + 40;
		echo "<B>Screens:</B> <A HREF=\"./show_image.php?id=$id&s=1&image=$screen1\" onClick=\"window.open('./show_image.php?id=$id&s=1&image=$screen1','Show_1','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height1,width=$width1');return false\">1</A>\n";		
		
		// scr1 bleibt offen somit kann keiner nur scr 2 nutzen 

		if (empty($screen2))
		{
		echo "";
		} 
		else
		{
		$size2 = GetImageSize("$screen2");
		$height2 = $size2[1] + 100;
		$width2 = $size2[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=2&image=$screen2\" onClick=\"window.open('./show_image.php?id=$id&s=2&image=$screen2','Show_2','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height2,width=$width2');return false\">2</A>\n";
		}

		if (empty($screen3))
		{
		echo "";
		} 
		else
		{
		$size3 = GetImageSize("$screen3");
		$height3 = $size3[1] + 100;
		$width3 = $size3[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=3&image=$screen3\" onClick=\"window.open('./show_image.php?id=$id&s=3&image=$screen3','Show_3','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height3,width=$width3');return false\">3</A>\n";
		}

		if (empty($screen4))
		{
		echo "";
		} 
		else
		{
		$size4 = GetImageSize("$screen4");
		$height4 = $size4[1] + 100;
		$width4 = $size4[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=4&image=$screen4\" onClick=\"window.open('./show_image.php?id=$id&s=4&image=$screen4','Show_4','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height4,width=$width4');return false\">4</A>\n";
		}

		if (empty($screen5))
		{
		echo "";
		} 
		else
		{
		$size5 = GetImageSize("$screen5");
		$height5 = $size5[1] + 100;
		$width5 = $size5[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=5&image=$screen5\" onClick=\"window.open('./show_image.php?id=$id&s=5&image=$screen5','Show_5','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height5,width=$width5');return false\">5</A>\n";
		}
		} // Close screen 1

		echo "</TD>";
		echo "<TD CLASS=download WIDTH=90><B>Hits:</B> ".$db['hits']."</TD>";
		echo "</TR>";
		echo "<TR>";	
		echo "<TD CLASS=download WIDTH=100>";		
		$mirror = "".$db['mirror']."";
		if($mirror == '')
		{
		echo "<B>Alternativ:</B> ---";
		}
		else
		{
		echo "<B>Alternativ:</B> <A HREF=\"".$db['mirror']."\">Hier!</A>";
		}
		echo "</TD>";
		echo "<TD CLASS=download WIDTH=140><B>Added:</B> ".$db['date_added']."</TD>";
		echo "<TD CLASS=download WIDTH=90><B>Size:</B> ".$db['file_size']."</TD>";
		echo "</TR>";
		echo "<TR>";
		echo "<TD WIDTH=280 CLASS=download COLSPAN=2><B><A HREF=./detail.php?id=$id>File bewerten:</A></B>\n";


		$getem = mysql_query("SELECT * FROM dl_wertung WHERE (id = '$id') ORDER BY EID DESC");
		$ch=mysql_fetch_array($getem);
		if (!$ch)
		{
		echo "Bisher keine Bewertung\n";
		}
		else
		{

			$result2 = mysql_query("SELECT * FROM dl_wertung WHERE (id = '$id') ORDER BY EID DESC");
			while ($db2=mysql_fetch_array($result2))	
			{
			$stimmen = $db2['stimmen'];
			$wertung = $db2['wertung'];	

			$bewertung = bcdiv($wertung, $stimmen, "1");

				if ($stimmen == '1')
				{			
				echo "Bisher $bewertung / 10.0 bei $stimmen Stimme";
				}
				elseif ($stimmen >= '2')
				{
				echo "Bisher $bewertung / 10.0 bei $stimmen Stimmen";
				}				
		
			}

		}

		$comments = mysql_numrows(mysql_query("SELECT EID FROM dl_comments WHERE (id = '$id')"));

		echo "</TD>";
		echo "<TD WIDTH=90 CLASS=download><B><A HREF=./detail.php?id=$id>Kommentare:</A></B> $comments </TD>";
		echo "</TR>";

		echo "</TABLE><P>";
	   	}

?>

<P>

Bitte bewerten Sie den Download: 10.0 = beste Wertung, 1.0 = schlechteste Wertung! 
<P>


<FORM METHOD="POST" ACTION="<?echo "$PHP_SELF?id=$id"; ?>">

Download-Bewertung: <SELECT NAME="wertung" CLASS="menu">

<OPTION VALUE="-1">Wertung</OPTION>
<OPTION VALUE="-1">----------</OPTION>

<OPTION VALUE="1">1.0</OPTION>
<OPTION VALUE="2">2.0</OPTION>
<OPTION VALUE="3">3.0</OPTION>
<OPTION VALUE="4">4.0</OPTION>
<OPTION VALUE="5">5.0</OPTION>
<OPTION VALUE="6">6.0</OPTION>
<OPTION VALUE="7">7.0</OPTION>
<OPTION VALUE="8">8.0</OPTION>
<OPTION VALUE="9">9.0</OPTION>
<OPTION VALUE="10">10.0</OPTION>

</SELECT>

<P>

<?php

if(!$ch)
{
echo "<INPUT TYPE=hidden NAME=sec VALUE=\"new_wertung\">";
}
else
{
echo "<INPUT TYPE=hidden NAME=sec VALUE=\"upd_wertung\">";
}

?>

<INPUT TYPE=submit VALUE="Bewertung speichern">

</FORM>

<HR WIDTH=100% COLOR=#FFFFFF SIZE=1 NOSHADOW>

<P>

<?php
	$result2 = mysql_query("SELECT * FROM dl_comments WHERE (id = $id)");
	while ($db2=mysql_fetch_array($result2))	
	{
	echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>";
	echo "<TR><TD WIDTH=70% COLSPAN=2 HEIGHT=15 CLASS=file><A NAME=\"".$db2['EID']."\"></A><B>".$db2['headline']."</B></TD><TD WIDTH=30% CLASS=file ALIGN=right>";
	
		$autor_email = "".$db2['email']."";
		if($autor_email == '')
		{
		echo "Posted by: ".$db2['name']."";
		}
		else
		{
		echo "Posted by: <A HREF=mailto:$autor_email>".$db2['name']."</A>";	
		}

	echo "</TD></TR>";
	echo "<TR><TD WIDTH=40% HEIGHT=20 CLASS=file VALIGN=top>".$db2['date']." um ".$db2['time']." Uhr</TD><TD WIDTH=60% HEIGHT=20 COLSPAN=2 CLASS=file VALIGN=top ALIGN=right>";

		$icq = "".$db2['icq']."";
		if($icq == '')
		{
		echo "";
		}
		else
		{
			$www = "".$db2['url']."";
			if($www == '')
			{
			echo "";
			}
			else
			{
				$www = trim($www);
				if(substr(strtolower($www), 0, 7) != "http://")
				{
				$www = "http://$www";
				}
			echo "<A HREF=$www TARGET=new><I>Homepage</I></A>&nbsp;|&nbsp;";	
			}
		echo "<A HREF=\"http://wwp.icq.com/scripts/search.dll?to=$icq\"><I>ICQ Message</I></A>";	
		}


	echo "</TD></TR><TR><TD WIDTH=100% HEIGHT=70 CLASS=file VALIGN=top COLSPAN=3><DIV ALIGN=justify>";
	$message = "".$db2['comment']."";
	$message = htmlspecialchars($message);
	$message = nl2br($message);

	echo $message;
	echo "</DIV></TD></TR>";
	echo "</TABLE><HR COLOR=#FFFFFF SIZE=1 WIDTH=100% NOSHADOW><BR>";
	}
?>

Wenn Sie Ihre Meinung zu diesen News abgeben wollen, können Sie dies hier tun. <P>

<form action="./detail.php?id=<?php echo $id; ?>" method="POST">

<TABLE CELLPADDING=0 CELLSPACING=0 BORDER=0>
<TR>
<TD WIDTH=90 CLASS=file>
<B>Name:</B>
</TD>
<TD>
<INPUT TYPE=text NAME=name SIZE=30> 
</TD>
</TR>
<TR>
<TD WIDTH=90 CLASS=file>
<B>eMail:</B> 
</TD>
<TD>
<INPUT TYPE=text NAME=email SIZE=30> 
</TD>
</TR>
<TR>
<TD WIDTH=90 CLASS=file>
<B>Homepage:</B> 
</TD>
<TD>
<INPUT TYPE=text NAME=url SIZE=30> 
</TD>
</TR>
<TR>
<TD WIDTH=90 CLASS=file>
<B>ICQ Nummer:</B> 
</TD>
<TD>
<INPUT TYPE=text NAME=icq SIZE=30> 
</TD>
</TR>
<TR>
<TD WIDTH=90 CLASS=file>
<B>Überschrift:</B> 
</TD>
<TD>
<INPUT TYPE=text NAME=headline SIZE=61> 
</TD>
</TR>
<TR>
<TD WIDTH=90 VALIGN=top CLASS=file>
<BR><B>Kommentar:</B>
<P>
</TD>
<TD>
<TEXTAREA NAME=comment cols=60 rows=7></TEXTAREA>
</TD>
</TR>

<TR>
<TD WIDTH=90 VALIGN=top>
<BR>
</TD>
<TD>
<BR>
<INPUT TYPE=hidden NAME=sec VALUE="add_comment">
<INPUT TYPE=submit VALUE="Kommentar posten!">
</TD>
</TR>
</TABLE>
</FORM>
<P>

[ <A HREF=javascript:history.back()>Zurück zur Übersicht...</A> ]
<P>


<?php

include("main_layout_down.php");

?>
