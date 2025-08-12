<?
if($ns=="logged")
{
################################################################################################################


###################################################################
#                                                                 #
#                                                                 #
#                       ***********************                   #
#                       *     NEWS-Script     *                   #
#                       *      v. 0.1         *                   #
#                       ***********************                   #
#                  Made By: Erlend Berge                          #
#                           erlend.berge@student.uib.no           #
#                           University of Bergen, Norway          #
#                                                                 #
#                                                                 #
###################################################################


###############################
#    SETTINGS                 #
###############################

// Let's include our settings!
include ("settings.php");
include ("html.php");


########--------WRITES THE NEWS-----------########
if($valg == leggtil)
{
//makes the news id
$tall = date ("HidmY");

$innledning = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=new>\\0</a>", $innledning);
$tekst = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=new>\\0</a>", $tekst);

$innledning = ereg_replace("\\\'", "'", $innledning);
$innledning = ereg_replace('\\\"', "\"", $innledning);
$tekst = ereg_replace("\\\'", "'", $tekst);
$tekst = ereg_replace('\\\"', "\"", $tekst);


//writes the news-data file
$data = fopen ("news.dat", "a+");
fclose ($data);
$datainnhold = join ( '', file ( "news.dat")); 
$data = fopen ("news.dat", "w");
fwrite ($data, $tittel);
fwrite ($data, "<~>");
fwrite ($data, $katval);
fwrite ($data, "<~>");
if ($brukbilde == "on")
{
fwrite ($data, $url);
}
else
{
fwrite ($data, "IKKEBILDE");
}
fwrite ($data, "<~>");
$innled = ereg_replace("[\r\n]","<nl>",$innledning);
fwrite ($data, $innled);
fwrite ($data, "<~>");
$te = ereg_replace("[\n\r]","<nl>",$tekst);
fwrite ($data, $te);
fwrite ($data, "<~>");
fwrite ($data, $nsbruker);
fwrite ($data, "<~>");
if ($skriverfor == "on")
{
fwrite ($data, $skriverfornavn);
}
else
{
fwrite ($data, $nsnavn);
}
fwrite ($data, "<~>]");
fwrite ($data, $tall);
fwrite ($data, "\n");
fwrite ($data, $datainnhold);
fclose ($data);

##############################################
# Writes the "full news"                     #
##############################################

if ($tekst == "")
{
}
else
{
$innled = ereg_replace("\n","<br>",$innledning);
$teks = ereg_replace("\n","<br>",$tekst);



if ($brukbilde =="on")
{
$fnewstop = "
<?
include ('top.html');
?>
<table border=\"0\" cellspacing=\"5\" cellpadding=\"2\" align=\"$news_align\">
<tr><td valign='top' width='$news_width' bgcolor=\"#80CFE2\" valign=\"top\"><div class=\"introtop\" align=\"left\">
$tittel</div>
<img src='$url' valign='top' align='left'>
";
if ($skriverfor == "on")
{
if ($skriverfornavn == "INGEN")
{
}
else
{
$fnewsmiddle = "<?
include ('../settings.php');
echo \$ns_writtenby;
?>
$skriverfornavn
<br><br>
";

}
}
else
{
$fnewsmiddle = "<?
include ('../settings.php');
echo \$ns_writtenby;
?>
$nsnavn
<br>
<br>";
}
$fnewsbottom = "
<b>$innled</b><br>
<br>
$teks</td></tr>
<?
include ('bottom.html');
?>
<!--Made by Erlend Berge-->";
}
else
{
$fnewstop = "
<?
include ('top.html');
?>
<table border=\"0\" cellspacing=\"5\" cellpadding=\"2\" align=\"$news_align\">
<tr><td valign='top' width='$news_width' bgcolor=\"#80CFE2\" valign=\"top\"><div class=\"introtop\" align=\"left\">
$tittel</div>
";
if ($skriverfor == "on")
{
if ($skriverfornavn == "INGEN")
{
}
else
{
$fnewsmiddle = "<?
include ('../settings.php');
echo \$ns_writtenby;
?>
$skriverfornavn
<br><br>
";

}
}
else
{
$fnewsmiddle = "<?
include ('../settings.php');
echo \$ns_writtenby;
?>
$nsnavn
<br><br>
";
}
$fnewsbottom = "
<b>$innled</b><br>
<br>
$teks</td></tr>
<?
include ('bottom.html');
?>
<!--Made by Erlend Berge-->";}

$fnews = "$fnewstop
$fnewsmiddle
$fnewsbottom";
$skriv = fopen ("news/$tall.php", "w");
fputs ($skriv, $fnews);
fclose ($skriv);
}

##############################################
# Writes the introduction page               #
##############################################

// OPENS FILE, CREATES NEW ONE IF NECESSARY
$write = fopen ("news/$katval.php", "w");
fwrite ($write, $introside);
fclose ($write);

echo $top;
echo $ns_newsadded;
echo $bottom;
}
##############################################
#  Changes categories                        #
##############################################

elseif ($valg == endrekat2)
{
$ekat = fopen ("katdata.dat", "w");
fwrite ($ekat,$kat);
fclose ($ekat);
echo $top;
echo $ns_categorychanged;
echo $bottom;

}


##################################################
#                                                #
#         CHANGE NEWS                            #
#                                                #
##################################################



elseif ($valg == endre3)
{
$write = fopen ("news/$katval.php", "w");
fwrite ($write, $introside);
fclose ($write);


$innledning = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=new>\\0</a>", $innledning);
$tekst = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\" target=new>\\0</a>", $tekst);

$innledning = ereg_replace("\\\'", "'", $innledning);
$innledning = ereg_replace('\\\"', "\"", $innledning);
$tekst = ereg_replace("\\\'", "'", $tekst);
$tekst = ereg_replace('\\\"', "\"", $tekst);

$i = 0;
$data = file ("news.dat");
$antall = count ($data);
for ($i = 0; $i <= $antall; $i++) 
{
	$part = explode ("\n", $data[$i]);
	$linje = $part[0];

$s = strstr($linje, '<~>]');
$t = ereg_replace ("<~>]","",$s);
$t = ereg_replace ("[\r\n]","",$t);

if ($t == $id)
{
$del = explode ("<~>", $linje);
	$goverskrift = $del[0];
	$gkat = $del[1];
	$gbilde = $del[2];
	$ginnledning = $del[3];
	$gtekst = $del[4];
	$user = $del[5];
	$gnavn = $del[6];
	$gtal = $del[7];
	

$ggintro = ereg_replace ("<nl>\<nl>","\n",$ginnledning);
$ggtekst = ereg_replace ("<nl>\<nl>","\n",$gtekst);	

unlink ("news/$id.php");
$innled = ereg_replace("\n","<br>",$innledning);
$teks = ereg_replace("\n","<br>",$tekst);

if ($slett == "on")
{
$fny="";
}
else
{
$innled = ereg_replace("\n","<br>",$innledning);
$teks = ereg_replace("\n","<br>",$tekst);

if ($brukbilde == "on")
{
$fnytop = "
<?
include ('top.html');
?>
<table border=\"0\" cellspacing=\"5\" cellpadding=\"2\" align=\"$news_align\">
<tr><td valign='top' width='$news_width' bgcolor=\"#80CFE2\" valign=\"top\"><div class=\"introtop\" align=\"left\">
$overskrift</div>
<img src='$url' valign='top' align='left'>
";
if($gnavn="INGEN")
{
$fnymiddle="";
}
else
{
$fnymiddle="
<?
include ('../settings.php');
echo \$ns_writtenby;
?>
$gnavn
<br>
<br>";
}
$fnybottom="
<b>$innled</b><br>
<br>
$teks</td></tr>
<?
include ('bottom.html');
?>
<!--Made by Erlend Berge-->";
}
else
{
$fnytop = "
<?
include ('top.html');
?>
<table border=\"0\" cellspacing=\"5\" cellpadding=\"2\" align=\"$news_align\">
<tr><td valign='top' width='$news_width' bgcolor=\"#80CFE2\" valign=\"top\"><div class=\"introtop\" align=\"left\">
$overskrift</div>";

if($gnavn="INGEN")
{
$fnymiddle="";
}
else
{
$fnymiddle="
<?
include ('../settings.php');
echo \$ns_writtenby;
?>
$gnavn
<br>
<br>";
}
$fnybottom="<b>$innled</b><br>
<br>
$teks</td></tr>
<?
include ('bottom.html');
?>
<!--Made by Erlend Berge-->";
}
}
$fny = "$fnytop
$fnymiddle
$fnybottom";
$skriv = fopen ("news/$id.php", "w");
fputs ($skriv, $fny);
fclose ($skriv);


// updates the datafile



$gammel = "$goverskrift<~>$gkat<~>$gbilde<~>$ginnledning<~>$gtekst<~>$user<~>$gnavn<~>$gtal\n";
$innled = ereg_replace("[\r\n]","<nl>",$innledning);
$te = ereg_replace("[\r\n]","<nl>",$tekst);
if ($brukbilde == "on")
{
$bilde = $url;
}
else
{
$bilde = "IKKEBILDE";
}

if ($slett == "on")
{
$ny ="";
}
else
{

$ny = "$overskrift<~>$katval<~>$bilde<~>$innled<~>$te<~>$user<~>$gnavn<~>]$id\n";
}

$skriv = fopen("news.dat",r);
$datafil = fread($skriv, filesize("news.dat"));
fclose ($skriv);
$erstatt = str_replace($gammel, $ny, $datafil);

$thefile = fopen("news.dat",w);
fwrite($thefile, $erstatt);
fclose($thefile);
}

}
$write = fopen ("news/$katval.php", "w");
fwrite ($write, $introside);
fclose ($write);


if ($slett == "on")
{
unlink ("news/$id.php");
}



echo $top;
echo "<h2>$ns_editnews_thestory $overskrift</h2>\n";
echo "<br><br>";
if ($slett == "on")
{
echo $ns_editnews_wasdeleted;
}
else
{
echo $ns_editnews_wasupdated;
}

echo $bottom;



}
####################################
#    UPLOADING PICTURES            #
####################################

elseif ($valg == lastopp2)
{
if ($superdat_name != "") {
copy("$superdat", "news/pictures/$superdat_name") or 
die("$ns_upload_error");

} else {
	die("$ns_upload_errorfile");
}
echo $top;
echo "$ns_upload_pictureuploaded";
echo "<br><br><img src=\"news/pictures/$superdat_name\">";
echo $bottom;

}

######################################
#                                    #
#        View and delete pictures    #
#                                    #
######################################



elseif ($valg== slettbilde2)
{
unlink ("news/$slettnavn");
echo $top;
echo $slettnavn;
echo " $ns_picture_deleted";

$handle=opendir('news/pictures');
echo "<table frame=0>\n";
while (false !== ($file = readdir($handle))) { 
    if ($file == ".")
	{
	}
    elseif ($file == "..")
	{
	}
	else
	{
	echo "<tr><td valign=\"top\"><form  action=$PHP_SELF?valg=slettbilde2 method=\"post\"><input type=\"text\" name=\"slettnavn\" value=\"pictures/$file\" size=\"50\" readonly></td>\n<td valign=\"top\"><a href=\"news/pictures/$file\" target=new>$ns_picture_showpicture</a>\n</td><td valign=\"top\">";
  if($nsniva==$ns_user_admin)
	{
	echo "<input type=\"submit\" value=\"$ns_picture_delete\">";
  }
	echo "</form></td></tr>";


}
}
closedir($handle); 
echo "</table>\n";
echo $bottom;
}

elseif ($valg == slettbilde)
{
$handle=opendir('news/pictures');
echo $top;
echo "<table frame=0>\n";
while (false !== ($file = readdir($handle))) { 
    if ($file == ".")
	{
	}
    elseif ($file == "..")
	{
	}
	else
	{
echo "<tr><td valign=\"top\"><form  action=$PHP_SELF?valg=slettbilde2 method=\"post\"><input type=\"text\" name=\"slettnavn\" value=\"pictures/$file\" size=\"50\" readonly></td>\n<td valign=\"top\"><a href=\"news/pictures/$file\" target=new>$ns_picture_showpicture</a>\n</td><td valign=\"top\">";
  if($nsniva==$ns_user_admin)
	{
	echo "<input type=\"submit\" value=\"$ns_picture_delete\">";
  }
	echo "</form></td></tr>";

}
}
closedir($handle); 
echo "</table>\n";
echo $bottom;
}


################################
# ADD USERS                    #
################################
elseif($valg==leggtilbr)
{
if ($ny_niva== $ns_user_normal)
{
$ny_niva = "1";
}
elseif ($ny_niva==$ns_user_admin)
{
$ny_niva = "2";
}
$tfile = fopen ("brukere.dat","a+");
fwrite ($tfile, $ny_brnavn);
fwrite ($tfile, "<~>");
fwrite ($tfile, $ny_passord);
fwrite ($tfile, "<~>");
fwrite ($tfile, $ny_epost);
fwrite ($tfile, "<~>");
fwrite ($tfile, $ny_navn);
fwrite ($tfile, "<~>");
fwrite ($tfile, $ny_niva);
fwrite ($tfile, "\n");
fclose($tfile);
echo $top;
echo "$ns_user_user $ny_brnavn $ns_user_added";
echo $bottom;
}


################################
# DELETE USERS                 #
################################


elseif($valg== slettbruker)
{
$data = file ("brukere.dat");
$antall = count ($data);
$i = 0;
while ($i < $antall) {
	$del = explode ("<~>", $data[$i]);
	$brnav = $del[0];
	$brpassord = $del[1];
	$bremail = $del[2];
	$brnavn = $del[3];
	$brniva = $del[4];
if ($brnav==$slettbru)
{
$gammel = "$brnav<~>$brpassord<~>$bremail<~>$brnavn<~>$brniva";
$ny = "";


$skriv = fopen("brukere.dat",r);
$datafil = fread($skriv, filesize("brukere.dat"));
fclose ($skriv);
$erstatt = str_replace($gammel, $ny, $datafil);

$thefile = fopen("brukere.dat",w);
fwrite($thefile, $erstatt);
fclose($thefile);


}
$i=$i + 1;

}


echo $top;
echo "$ns_user_user $ny_brnavn $ns_user_deleted";
echo $bottom;
}
#############################
#   Change user info        #
#############################


elseif($valg== endreinfo2)
{
$data = file ("brukere.dat");
$antall = count ($data);
$i = 0;
while ($i < $antall) {
	$del = explode ("<~>", $data[$i]);
	$brnav = $del[0];
	$brpassord = $del[1];
	$bremail = $del[2];
	$brnavn = $del[3];
	$brniva = $del[4];
if ($nsbruker == $brnav)
{
$gammel = "$brnav<~>$brpassord<~>$bremail<~>$brnavn<~>$brniva";
$ny = "$endrebrbrukernavn<~>$endrebrpassord<~>$endrebrepost<~>$endrebrnavn<~>$brniva";


$skriv = fopen("brukere.dat",r);
$datafil = fread($skriv, filesize("brukere.dat"));
fclose ($skriv);
$erstatt = str_replace($gammel, $ny, $datafil);

$thefile = fopen("brukere.dat",w);
fwrite($thefile, $erstatt);
fclose($thefile);


}
$i=$i + 1;

}
echo $top;
echo "$ns_user_usersettingsupdated";
echo $bottom;

}




#######################################################################
#                ########   #####     ###     #     #    ####         #
#                #         #     #    #  #    # # # #    #            #
#                ####      #     #    ###     #  #  #      #          #
#                #         #     #    #  #    #     #       #         #
#                #          #####     #   #   #     #    ####         #
#######################################################################

//  -New story
//  -Change story
//  -Change users
//  -Change categories
//  -Front page




################################
#  NEW STORY                   #
################################


elseif ($valg == skriv)
{
echo $top;

echo"<form action=$PHP_SELF?valg=leggtil  method='post'>";
echo"<h1>";
echo $newstory;
echo"</h1>";
echo"<b>";
echo $headl;
echo":</b><br>";
echo"<input type='text' name='tittel' value=''><br>";


echo"<b>";
echo $introl;
echo ":</b><br>";
echo"<textarea rows='4' cols='50' name='innledning' wrap='virtual'>";
echo"</textarea><br>";
echo"<br>";

echo"<b>";
echo $hovedl;
echo":</b><br>";
echo"<textarea rows='8' cols='50' name='tekst' wrap='virtual'>";
echo"</textarea>";
echo"<br>";
echo"<b>";
echo $bildel;
echo"?</b>";
echo"<input type='checkbox' name='brukbilde'><br>";
echo $urll;
echo":&nbsp&nbsp<input type='text' name='url' value=''><br>";
if($nsniva==$ns_user_admin)
{
echo"$ns_new_doyouwrite <input type=\"Checkbox\" name=\"skriverfor\"><br>";
echo"$ns_new_nameofauthor <input type='text' name='skriverfornavn' value=''><br><br>";
}
else
{
}
echo $katl;
echo(":&nbsp&nbsp<select name='katval'>\n");
$kat_data = file("katdata.dat");
for($i = 0; $i < count($kat_data); $i++)
    {
      $kat_emne_data = explode("<~>",$kat_data[$i]);
      echo("<option>" . htmlentities($kat_emne_data[0]) . "</option>\n");
			}
echo"<br>";

echo"<input type='submit' value='";
echo $send;
echo"'>";
echo"</form>";

echo $bottom;
}

################################
#  CHANGE NEWS (select)        #
################################

elseif ($valg == endre)
{
echo $top;
echo "<h2>$ns_change_choose</h2>";
echo ("<TABLE align=\"center\" BORDER=\"0\">");

$data = file ("news.dat");
$antall = count ($data);
$i = 0;
while ($i < $antall) {
	$del = explode ("<~>", $data[$i]);
	$overskrift = $del[0];
	$katvalg = $del[1];
	$bilde = $del[2];
	$innledning = $del[3];
	$hoveddel = $del[4];
	$bruker = $del[5];
	$navn = $del[6];
	$tal = $del[7];
$tall = ereg_replace ("]","",$tal);
$innled = ereg_replace ("<nl>\<nl>","\n",$innledning);
$hoveddl = ereg_replace ("<nl>\<nl>","\n",$hoveddel);
if($nsniva==vanlig)
{
if($nsbruker==$bruker)
{
echo "<tr><td><b>$headl</b></td><td><a href='$PHP_SELF?valg=endre2&&verdi=$tall'>$overskrift</a></td><td><b>$ns_category</b></td><td>$katvalg</td></tr>";
}
}
elseif($nsniva==admin)
{
echo "<tr><td><b>$headl</b></td><td><a href='$PHP_SELF?valg=endre2&&verdi=$tall'>$overskrift</a></td><td><b>$ns_category</b></td><td>$katvalg</td><td><b>$ns_writtenby</b></td><td>$navn ($bruker)</td></tr>";
}

$i=$i + 1;
}

echo ("</TABLE>");
echo $bottom;

}


###############################
#   CHANGE NEWS 2             #
###############################







elseif($valg==endre2)
{
echo $top;
$data = file ("news.dat");
$antall = count ($data);
if ($antall == "0")
{
echo $ns_change_nonews;
}



$i = 0;
while ($i < $antall) {
	$del = explode ("<~>", $data[$i]);
	$overskrift = $del[0];
	$katvalg = $del[1];
	$bilde = $del[2];
	$innledning = $del[3];
	$hoveddel = $del[4];
	$bruker = $del[5];
	$navn = $del[6];
	$tal = $del[7];
$tall = ereg_replace ("]","",$tal);
$innled = ereg_replace ("<nl>\<nl>","\n",$innledning);
$hoveddl = ereg_replace ("<nl>\<nl>","\n",$hoveddel);	
$verdi= ereg_replace (" ","", $verdi);
$ta = ereg_replace ("\n","",$tall);

if($ta==$verdi)
{
echo"<h2>$ns_change_change \"$overskrift\"</h2>";
echo"<form action=$PHP_SELF?valg=endre3  method='post'>";
echo"<b>";
echo $headl;
echo":</b><br>";
echo"<input type='text' name='overskrift' value='";
echo $overskrift;
echo"'><br>";
echo"<input type='text' name='id' value='";
echo $tall;
echo "' readonly><br>$ns_change_written_by $bruker <br><br>";


echo"<b>";
echo $introl;
echo ":</b><br>";
echo"<textarea rows='4' cols='50' name='innledning' wrap='virtual'>";
echo $innled;
echo"</textarea><br>";
echo"<br>";

echo"<b>";
echo $hovedl;
echo":</b><br>";
echo"<textarea rows='8' cols='50' name='tekst' wrap='virtual'>";
echo $hoveddl;
echo"</textarea>";
echo"<br>";
echo"<b>";
if ($bilde == "IKKEBILDE")
{
$bilde = "";
$hfh ="";
}
else
{
$hfh = "checked";
}
echo $bildel;
echo"?</b>";
echo"<input type='checkbox' name='brukbilde'";
echo $hfh;
echo"><br>";
echo $urll;
echo":&nbsp&nbsp<input type='text' name='url' value='";
echo $bilde;
echo"'><br><br>";

echo "$ns_change_presentcat &nbsp;&nbsp;<b>";
echo $katvalg;
echo"</b><br>";

echo $katl;
echo(":&nbsp&nbsp<select name='katval'>\n");
$kat_data = file("katdata.dat");
for($j = 0; $j < count($kat_data); $j++)
    {
      $kat_emne_data = explode("<~>",$kat_data[$j]);
      echo("<option>" . htmlentities($kat_emne_data[0]) . "</option>\n");
			}

echo"</select><br>";
echo"<br>";

echo"$ns_change_delete <input type=\"checkbox\" name=\"slett\">";
echo"<input type='submit' value='";
echo $send;
echo"'>";
echo"</form>";




}
$i=$i + 1;

}





echo $bottom;

}







################################
#  CHANGE CATEGORIES           #
################################
elseif ($valg == endrekat)
{
if($nsniva == $ns_user_admin)
{
echo $top;
echo "<form action=\"$PHP_SELF?valg=endrekat2\" method=\"post\">";
echo "<textarea cols=\"40\" rows=\"10\" name=\"kat\">";
include ("katdata.dat");
echo "</textarea><br>";
echo"<input type=\"submit\" value=\"";
echo $endrekatl;
echo"\">"; 
echo"</form>";
echo $bottom;
}
else
{
echo $top;
echo $ns_cat_adminonly;
echo $bottom;
}

}
################################
#  CHANGE USERS                #
################################

elseif ($valg == endrebruk)
{
if($nsniva == $ns_user_admin)
{
echo $top;
echo "$ns_user_adduser \n";
echo "<form action=$PHP_SELF?valg=leggtilbr method=\"post\">\n";
echo "$ns_user_addname <input type=\"text\" name=\"ny_navn\"><br>\n";
echo "$ns_user_addemail <input type=\"text\" name=\"ny_epost\"><br>\n";
echo "$ns_user_addusername <input type=\"text\" name=\"ny_brnavn\"><br>\n";
echo "$ns_user_addpassword <input type=\"text\" name=\"ny_passord\"><br>\n";
echo "$ns_user_addlevel: <select name=\"ny_niva\">\n";
echo "<option>$ns_user_normal</option>";
echo "<option>$ns_user_admin</option>";
echo "</select><br><br>";
echo "<input value=\"$ns_user_adduser\" type=\"submit\">";
echo "</form>";

echo "<br><br>";
echo "$ns_user_deleteuser";

$data = file ("brukere.dat");
$antall = count ($data);
$i = 0;
echo "<form action=$PHP_SELF?valg=slettbruker method=\"post\">\n";
echo "<select name=\"slettbru\">";
while ($i < $antall) {
	$del = explode ("<~>", $data[$i]);
	$brnav = $del[0];
	$brpassord = $del[1];
	$bremail = $del[2];
	$brnavn = $del[3];
	$brniva = $del[4];
echo "<option>$brnav</option>\n";
$i=$i + 1;

}
echo "</select><br><br>";
echo "<input type=\"submit\" value=\"$ns_user_deleteuser\">";
echo "</form>";
echo $bottom;
}
elseif($nsniva == "vanlig")
{
echo $top;
echo $ns_user_adminonly;
echo $bottom;
}

}

###############################
#  CHANGE USER INFO           #
###############################
elseif($valg == endreinfo)
{
echo $top;
$data = file ("brukere.dat");
$antall = count ($data);
$i = 0;
while ($i < $antall) {
	$del = explode ("<~>", $data[$i]);
	$brnav = $del[0];
	$brpassord = $del[1];
	$bremail = $del[2];
	$brnavn = $del[3];
	$brniva = $del[4];
if ($nsbruker == $brnav)
{
echo "
	<form action=$PHP_SELF?valg=endreinfo2 method=\"post\">
$ns_user_addname <input type=\"text\" name=\"endrebrnavn\" value=\"$brnavn\" align=\"right\"><br>
$ns_user_addusername <input type=\"text\" name=\"endrebrbrukernavn\" value=\"$brnav\" readonly><br>
$ns_user_addemail <input type=\"text\" name=\"endrebrepost\" value=\"$bremail\"><br>
$ns_user_addpassword <input type=\"password\" name=\"endrebrpassord\" value=\"$brpassord\"><br>
<input type=\"submit\" value=\"$ns_user_changeaccount\">
</form>";
}
$i=$i + 1;

}




echo $bottom;
}




################################
#  UPLOADING FILES             #
################################

elseif ($valg == lastopp)
{
echo $top;
echo $lastop;
echo $bottom;
}

##################################
#  Other forms                   #
##################################

elseif ($valg == annet)
{
echo $top;
include("other.php");
echo $bottom;
}



##################################
#                                #
#         LOG OUT                #
#                                #
##################################
if($valg == logout)
{
setcookie("ns", "logged", time()-36000);
setcookie("nsbruker", $bbruker, time()-36000);
setcookie("nsemail", $bemail, time()-36000);
setcookie("nsnavn", $bnavn, time()-36000);
if($bniva==2)
{
setcookie("nsniva", "admin", time()-36000);
}
echo $top;
echo $ns_loggedout;
echo $bottom;
}

################################
#  MAIN MENU                   #
################################

elseif($valg == main)
{
echo $top;
echo"$ns_main_welcome , $nsnavn <br><br>";
echo"$ns_main_username $nsbruker $ns_main_email $nsemail <br>";
echo $nsniva;
echo $bottom;
}







################################################################################################################################
}


elseif($ns!="logged")
{
header("Location: login.php");
exit;
}
?>

