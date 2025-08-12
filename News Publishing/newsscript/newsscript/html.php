<?

$top= <<< HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title>$topbeskr</title>
	<link rel="stylesheet" href="$css" type="text/css">
</head>

<body bgcolor="#5984D2">
<table cellspacing="0" cellpadding="0" border="0" width="669">
<tr>
    <td colspan="3" background="top.gif" height="97" bgcolor="#5984D2" valign="top">
	<Table><Tr height="13"></Tr><Tr><Td width="130"></Td><Td width="225"><br><h3>$name</h3></Td><Td width="95"></Td><Td width="111">$topbeskr</Td></Tr></Table>
	
	</td>
</tr>
<tr>
    <td background="venstre.gif" width="95" valign="top">
	<Table>
<Td width="10"></Td><Td width="93" valign="top">
<a href='?valg=skriv'>Write new story</a><br>
<a href='?valg=endre'>Change stories</a><br>
<a href='?valg=endrekat'>Change categories</a><br>
<a href='?valg=endrebruk'>Change users</a><br>
<a href='?valg=endreinfo'>Change user settings</a><br>
<a href='?valg=lastopp'>Upload pictures</a><br>
<a href='?valg=slettbilde'>Show and delete pictures</a><br>
<a href='?valg=annet'>Additional</a><br>
<a href='?valg=logout'>Log out</a>


</Td>
</Table>
	
	</td>
    <td width="479" valign="top" align="center">

HTML;


$newsform= <<< HTML
<form action=$PHP_SELF?valg=leggtil  method='post'>
<b>
$headl
:</b><br>
<input type='text' name='tittel' value=''><br>
<b>
$introl
:</b><br>
<textarea rows='4' cols='50' name='innledning' wrap='hard'>
</textarea><br>
<br>

<b>
$hovedl
:</b><br>
<textarea rows='8' cols='50' name='tekst' wrap='hard'>
</textarea>
<br>
<b>
$bildel
?</b>
<input type='checkbox' name='brukbilde'><br>
$urll
:&nbsp&nbsp<input type='text' name='url' value=''><br><br>

$katl
:&nbsp&nbsp<select name='katval'>\n
$katval
<br>
<input type='submit' value='$send'>
</form>
HTML;







$bottom= <<< HTML
</td>
    <td background="hoyre.gif" width="95" valign="top"></td>
</tr>
<tr>
    <td colspan="3" background="bunn.gif" height="98">
	<center><input type="Button" value="New story" onclick="self.location.href ='?valg=skriv'">
<input type="Button" value="Change news" onclick="self.location.href ='?valg=endre'">
<input type="Button" value="Change cat." onclick="self.location.href ='?valg=endrekat'"><br>
<input type="Button" value="Change users" onclick="self.location.href ='?valg=endrebruk'">
<input type="Button" value="User settings" onclick="self.location.href ='?valg=endreinfo'">
<input type="Button" value="Upload" onclick="self.location.href ='?valg=lastopp'">
<input type="Button" value="Show/delete pic." onclick="self.location.href ='?valg=slettbilde'">
<input type="Button" value="Log out" onclick="self.location.href ='?valg=logout'">
</center>
	</td>
</tr>
</table>



</body>
</html>
HTML;

$lastop= <<< HTML
<form action="$PHP_SELF?valg=lastopp2" method="post" ENCTYPE="multipart/form-data">
Here you can upload pictures for news stories
<br>
<br>
File name:&nbsp;&nbsp;<input type="file" name="superdat">
<br>
<br>
<input type="submit" value="Upload">
HTML;









$introside= <<< HTML
<?
include ("../settings.php");
\$filnavn = "$katval";
\$data = file("../news.dat");
echo ("<table border=\"\$news_border\" cellspacing=\"\$news_cellspacing\" cellpadding=\"\$news_cellpadding\" align=\"\$news_align\">\\n");
for(\$i = 0;\$i <= count (\$data);\$i++)
{
\$del = explode ("<~>", \$data[\$i]);
	\$overskrift = \$del[0];
	\$katvalg = \$del[1];
	\$bilde = \$del[2];
	\$innledning = \$del[3];
	\$hoveddel = \$del[4];
	\$bruker = \$del[5];
	\$navn = \$del[6];
	\$tal = \$del[7];

\$tall = ereg_replace ("]","",\$tal);
\$innled = ereg_replace ("<nl>\<nl>","<br>",\$innledning);
\$hoveddl = ereg_replace ("<nl>\<nl>","<br>",\$hoveddel);	

if (\$katvalg == \$filnavn)
{
echo ("<tr>");
echo ("<td valign='top' width='\$news_width' bgcolor=\"\$news_bgcolor\" valign=\"top\"><div class=\"introtop\" align=\"\left\">\\n");
echo (\$overskrift);
echo ("</div>\\n");
if (\$bilde=="IKKEBILDE")
{
}
else
{
echo ("<img src='");
echo (\$bilde);
echo ("' valign='top' align='left'>\\n");
}
if (\$navn=="INGEN")
{
}
else
{
echo \$ns_writtenby;
echo \$navn;
echo "<br>";
echo "<br>";
}
echo (\$innled);
if (\$hoveddl == "")
{
}
else
{
echo ("<br>\\n");
echo ("<br>\\n");
echo ("<div align=\"absleft\">");
echo ("<a href='");
echo (\$tall);
echo (".php'>");
echo (\$leshele);
echo ("</a>\\n");
echo ("</div>");
}
echo ("</td>");
echo ("</tr>");
}
}
echo ("</table>");
?>
HTML;



?>