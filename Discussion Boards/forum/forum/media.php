<?php

$where = "<a href=\"index.php\">Home</a> > Media player Centre";

include("header.php");  //include the class
$media = new sdb();    //initiate the class
$playdir = "playlist"; //read this dir for music.
$allow = array(".wma", ".avi", ".mp3", ".mp4", ".m4a", ".mpg");   //add more extentions (4 chars only)

if (isset($_POST["newdir"])){
  $now = $_POST["newdir"];
  $playdir = $now;
};

$disallow = array("avatars", "db", "icon", "port", "skins", "smilies", "language", $playdir);

$lists = $media->readDir(".");
foreach ($lists as $file){
 if (is_dir($file)){
  if (in_array($file, $disallow)){
  } else {
  $dirs[] = $file;
  };
 }; //end is_dir
}; //end foreach


$playlist = $media->readDir("$playdir");

$num = @$_GET["song"];
$do = @$_POST["play"]["$num"];
$song = @$playlist[$do];
$playnow = "$playdir/$song";

$ns = $num += 1;

if (isset($_POST["doplay"])){
  if (isset($_POST["play"])){

echo ("<form method=\"post\" action=\"?song=$ns\">");
table_header("$playnow");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#000000\" width=\"$fwidth\" align=\"center\">");

echo("<tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"4\">");

      echo ("<input type=\"hidden\" name=\"newdir\" value=\"$playdir\" />");

  foreach ($_POST["play"] as $play){
    echo ("<input type=\"hidden\" name=\"play[]\" value=\"$play\" />");
  }; //end foreach

echo ("<center><embed src=\"$playnow\"></embed><br />");
if ($num < count($_POST["play"])){
echo ("<input type=\"submit\" name=\"doplay\" value=\"Next\" />");
};
echo ("</center>");

       echo ("</td>
   </tr>
</table>");
table_footer();
echo ("</form>");

  }; //end isset
}; //end isset


echo ("<form method=\"post\" action=\"?song=0\">");
table_header("Music Media Centre");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#000000\" width=\"$fwidth\" align=\"center\">
   <tr class=\"background\">
      <td width=\"5%\" colspan=\"1\"><b></b></td>
      <td width=\"55%\" colspan=\"1\"><b><font color=\"$fsubtitle\" size=\"$fsmall\" face=\"$fface\">Song Title</font></b></td>
      <td width=\"10%\" colspan=\"1\"><b><font color=\"$fsubtitle\" size=\"$fsmall\" face=\"$fface\">FileSize</font></b></td>
      <td width=\"30%\" colspan=\"1\"><b><font color=\"$fsubtitle\" size=\"$fsmall\" face=\"$fface\">Last Edited</font></b></td>
   </tr>");
   


echo("<tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"4\"><input type=\"submit\" name=\"doplay\" value=\"Play Now\" /> <select name=\"newdir\"><option value=\"$playdir\">$playdir</option>");

      for ($d = 0; $d < count($dirs); $d++){
        echo ("<option value=\"$dirs[$d]\">$dirs[$d]</option>");
      }; //end foreach

      echo ("</select></td>
   </tr>");
   
   
for ($m = 0; $m < count($playlist) -1; $m++){

$value = "unchecked";
$musicfile = $playlist["$m"];
$filesize = $media->getFileSize("$playdir/$musicfile", true);
$lastedit = $media->getEditTime("$playdir/$musicfile");

if (!is_dir("$playdir/$musicfile") and in_array((substr("$musicfile", -4)), $allow)){


if (isset($_POST["play"])){
  foreach ($_POST["play"] as $valid){
    if ($valid == $m){
      $value = "checked";
    }; //end if
  }; //end foreach
}; //end isset $play

echo("<tr bgcolor=\"$tbackground1\" onmouseover=\"this.bgColor='$tbackground2'\" onmouseout=\"this.bgColor='$tbackground1'\">");
echo ("<td width=\"5%\" colspan=\"1\" align=\"center\"><input type=\"checkbox\" name=\"play[]\" value=\"$m\" $value /></td>"); //select multiple
#echo ("<td width=\"5%\" colspan=\"1\" align=\"center\"><input type=\"radio\" name=\"play[0]\" value=\"$m\" $value /></td>"); //select one

echo ("<td width=\"55%\" colspan=\"1\"><font color=\"$fcolor\" size=\"$fsmall\" face=\"$fface\"><b>$m)</b> <label title=\"Media File\">$musicfile</label></font></td>
      <td width=\"10%\" colspan=\"1\"><font color=\"$fcolor\" size=\"$fsmall\" face=\"$fface\">$filesize</font></td>
      <td width=\"30%\" colspan=\"1\"><font color=\"$fcolor\" size=\"$fsmall\" face=\"$fface\">$lastedit</font></td>
   </tr>");
 } else {
  $dirs[] = $musicfile;
  }; //end !is_dir
}; //end for

if (count($playlist) <= "1"){
  echo("<tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"4\" align=\"center\"><b>NO SONGS FOUND IN THE FOLDER</b> ($playdir) <b>ABORTING...</b></td>
   </tr>");
}; //end if

echo("<tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"4\"><input type=\"submit\" name=\"doplay\" value=\"Play Now\" /></td>
   </tr>");
echo ("</table>");
table_footer();

include("footer.php");

?>
