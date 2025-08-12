<?
/**************************************************************
 *                                                            *
 *                     Dellary                                *
 *            An easy to use Gallery Script                   *
 *                                                            *
 *              (c) 2001 by Marcus Proest                     *
 *                                                            *
 *   The script may be used and changed for private usage     *
 *         but this Header may not be removed                 *
 *                                                            *
 *                                                            *
 *                Component: $PHP_SELF                        *
 *                                                            *
 **************************************************************/
 if (!is_file("config.inc")) die("Configurationfile config.inc not found. Please re-install Dellary.");
 require("config.inc");                     //Configurationsdatei benötigt.
 if (!is_file("lang/$config[lang].inc")) die("Languagefile for \"$config[lang]\" not found. Please check config.inc");
 require ("lang/$config[lang].inc");
 if (!is_dir("./$config[dir]")) die ("$li016");

 echo "<html>\n<head>\n";
 echo "<title>$config[title]";                         //title der HTML Datei
 echo "</title>\n</head>\n<body bgcolor=\"$config[bgcolor]\" text=\"$config[textcolor]\" link=\"$config[linkcolor]\" alink=\"$config[alinkcolor]\" vlink=\"$config[vlinkcolor]\">\n<h1 align=center>$config[title]</h1>\n";

 $handle=opendir("./$config[dir]/");            //Bilder dir wird geöffnet

 while ($file = readdir($handle)) {             //und file fuer file eingelesen
   if ($file!="." and $file!="..") $filelisting[]="$file";
   $number=sizeof($filelisting);                 //Array wird gefuellt und die
 };                                             //Anzahl der Elemente bestimmt.

 $h = 1;
 $html_alb = "<center>\n$ls001 <form action=\"$PHP_SELF\" method=\"get\">\n<select name=\"aname\">\n";
                                                 //Album Auswahlfeld wird gestrickt.
 for ($i = 0 ; $i < $number ; $i++) {            //Dateinamen werden durchlaufen
     $album[$i] = substr($filelisting[$i],0,strpos($filelisting[$i],"~"));
     if ($album[$i] == "") $album[$i] = "$ls002";
 }                                                //Der Albumname wird extrahiert

 if (isset($album)) {                              //Wenn es den ein album gibt wird es sortiert und auf Anfang gesetzt.
     sort($album);
     reset($album);
 }

 for ($i = 0 ; $i < count($album) ; $i++) {         //Nun werden die alben durchlaufen
     $next = $album[$i+1];                        //Hier wird festgestellt, wann ein
     if ($next != $album[$i]) {                   //neues Album anfaengt.
         $alben[$album[$i]] = $h;                 //Ein Array ala
                                                  //Albumname --> Anzahl Files
         $html_alb .= "<option value=\"$album[$i]\">$album[$i] ($h)\n";
         $h=1;
     }                                            //wird erzeugt.
     else $h++;
 }                                                 //Es wird weiter gestrickt....

 $html_alb .= "</select>\n";
 $html_alb .= "<input type=\"hidden\" value=\"1\" name=\"pic\">\n";
 $html_alb .= "<input type=\"submit\" value=\"$ls003\">\n</form>\n</center>\n";

 if (count($album) != 0) echo $html_alb;
 else echo "<center>$ls004</center>\n";
                                                  //Was nicht ist das kann nicht sein...
 echo "<center>\n";                                 //Dann laufen wir da mal durch.
 for ($j=1;$j<=$alben[$aname];$j++) {
     echo "&nbsp;&nbsp;";
     if ($j!=$pic) echo "<a href=\"$PHP_SELF?aname=$aname&pic=$j\">[$j]<a>\n";
     else echo "[<b>$j</b>]\n";
 }                                             //und erzeugen viele Links zu den Pix
 echo "<hr>\n";

 if(file_exists("$config[dir]/$aname~$pic.jpg")) {
     echo "<img src=\"$config[dir]/$aname~$pic.jpg\" title=\"$pic\">\n";
     echo"</center>\n";
 }
 include "footer.inc";

?>

</body>

</html>