<?php

if (isset($_POST["a"])){

$_POST["announcement"] = str_replace("\r\n", "<br>", $_POST["announcement"]);
$_POST["announcement"] = addslashes($_POST["announcement"]);
$_POST["announcement"] = str_replace("\'", "'", $_POST["announcement"]);

$_POST["pfdate"] = $pfdate;
$new = fopen("$config_file", "w");
fwrite($new, "<?php\r\n\r\n");

foreach ($_POST as $key => $val){
  fwrite($new, "\$$key = \"$val\";\r\n");
}; //end foreach

fwrite($new, "\r\n\r\n?>");
fclose($new);

}; //end isset $a

$announcement = str_replace("<br>", "\r\n", $announcement);
$announcement = stripslashes($announcement);

$alert_color = "#FF0000";
echo ("<form method=\"post\" action=\"\">");
table_header("Forum Configuration");
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
  <tr align=\"center\">
     <td  colspan=\"2\" width=\"100%\" class=\"background\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Database Connection</b></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Database File</b><br />Usually located within the DB folder.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"dbfile\" size=\"30\" value=\"$dbfile\" style=\"border: 1px solid $alert_color\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Database Username</b><br />Required for accessing the database</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"dbuser\" size=\"30\" value=\"$dbuser\" style=\"border: 1px solid $alert_color\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Database Password</b><br />Required for accessing the database</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"password\" name=\"dbpass\" size=\"30\" value=\"$dbpass\" style=\"border: 1px solid $alert_color\"></td>
  </tr>
  <tr align=\"center\">
     <td  colspan=\"2\" width=\"100%\" class=\"background\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Forum Settings</b></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Forum Header Title</b><br />Places the title into the header of the browser agent</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"title\" size=\"30\" value=\"$title\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Forum Table Width</b><br />This sets the width of the forum table.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"width\" size=\"30\" value=\"$width\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Forums Width</b><br />This sets the width of everything inside the forum table</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"fwidth\" size=\"30\" value=\"$fwidth\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Body Background Colour</b><br />This sets the colour of the page's background.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"bgcolor\" size=\"30\" value=\"$bgcolor\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Horizontal Rule Coloir</b><br />This sets the colour of the horizontal rule.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"hrcolor\" size=\"30\" value=\"$hrcolor\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Skins Directory</b><br />The location of the skins folder of which everyone will load.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"skins\" size=\"30\" value=\"$skins\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Time Stamping</b><br />Will be used on everyone's post stating when posted. ("); echo date("$pfdate"); echo (")</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"pfdate\" size=\"30\" value=\"$pfdate\" disabled></td>
  </tr>
  <tr align=\"center\">
     <td  colspan=\"2\" width=\"100%\" class=\"background\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>General Configuration</b></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Forum Language</b><br />Point to the language file which you would like to use.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"lingo_file\" size=\"30\" value=\"$lingo_file\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Allow Registration</b><br />If <b>false</b> then registration requests will be denied.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"allow_register\" size=\"30\" value=\"$allow_register\"></td>
  </tr>
   <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Core-Signup</b><br />If <b>true</b> then people must signup in order to view posts!</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"core_signup\" size=\"30\" value=\"$core_signup\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Announcements</b><br /> To be shown on everypage so everyone can view this.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><textarea name=\"announcement\" rows=\"4\" cols=\"29\">$announcement</textarea></td>
  </tr>
  <tr align=\"center\">
     <td  colspan=\"2\" width=\"100%\" class=\"background\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Font Settings</b></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Font Face</b><br />The Face or Family of the font to be loaded.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"fface\" size=\"30\" value=\"$fface\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Font Small</b><br />The small font size</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"fsmall\" size=\"30\" value=\"$fsmall\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Font Medium</b><br />The medium font size</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"fmedium\" size=\"30\" value=\"$fmedium\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Font Colour</b><br />The colour of standard font on all pages.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"fcolor\" size=\"30\" value=\"$fcolor\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Font Colour Fade</b><br />A faded type colour of font.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"fcfade\" size=\"30\" value=\"$fcfade\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Title Colour</b><br />Colour for Header Titles.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"ftitle\" size=\"30\" value=\"$ftitle\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Sub Title Colour</b><br />Colour for sub-titles.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"fsubtitle\" size=\"30\" value=\"$fsubtitle\"></td>
  </tr>
  <tr align=\"center\">
     <td  colspan=\"2\" width=\"100%\" class=\"background\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Table Settings</b></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Border Colour</b><br />The colour of the border</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"tborder_color\" size=\"30\" value=\"$tborder_color\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Forums Border Colour</b><br />The colour of the border on the forums</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"tborder_color2\" size=\"30\" value=\"$tborder_color2\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Background Colour 1</b><br />Background colour 1 of tables</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"tbackground1\" size=\"30\" value=\"$tbackground1\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Background Colour 2</b><br />Background colour 2 of tables</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"tbackground2\" size=\"30\" value=\"$tbackground2\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Background Colour 3</b><br />The background colour behind the forums. Inside the table.</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"tbackground3\" size=\"30\" value=\"$tbackground3\"></td>
  </tr>
  <tr align=\"center\">
     <td  colspan=\"2\" width=\"100%\" class=\"background\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>Other General Settings</b></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Admin Colour</b><br />The colour of the Admin Title</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground2\" align=\"center\"><input type=\"text\" name=\"cadmin\" size=\"30\" value=\"$cadmin\"></td>
  </tr>
  <tr>
     <td  colspan=\"1\" width=\"65%\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>Member Border</b><br />The colour of the Member Title</td>
     <td  colspan=\"1\" width=\"35%\" bgcolor=\"$tbackground1\" align=\"center\"><input type=\"text\" name=\"cmember\" size=\"30\" value=\"$cmember\"></td>
  </tr>
  <tr align=\"right\">
     <td  colspan=\"2\" width=\"100%\" bgcolor=\"$tbackground1\"><input type=\"submit\" name=\"a\" value=\"Modify Configuration\"></td>
  </tr>
</table>");
table_footer();

echo ("</form>");

?>
