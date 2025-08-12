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
 *                Component: insert.php3                      *
 *                                                            *
 **************************************************************/
 if (!is_file("config.inc")) die("Configurationfile config.inc not found. Please re-install Dellary.");
  require("config.inc");
 if (!is_file("lang/$config[lang].inc")) die("Languagefile for \"$config[lang]\" not found. Please check config.inc");
  require ("lang/$config[lang].inc");

  if (!is_dir("./$config[dir]")) die ("$li016");

  $handle=opendir("./$config[dir]/");
  while ($file = readdir($handle))
  {
   if ($file=="." or $file=="..")
   {} else $filelisting[]="$file";

   $imCountber=sizeof($filelisting);
  };

$h = 1;
$g=0;

$html_alb = "<select name=\"aname\">\n";
$html_alb .= "<option value=\"\">$li003\n";

for ($i = 0 ; $i < $imCountber ; $i++)
{
     $album[$i] = substr($filelisting[$i],0,strpos($filelisting[$i],"~"));
}

if (isset($album))
{
 sort($album);
 reset($album);
}

for ($i = 0 ; $i < count($album) ; $i++)
{
     $next = $album[$i+1];
     if ($next != $album[$i])
     {
         $alben[$album[$i]] = $h;

         $html_alb .= "<option value=\"$album[$i]\">$album[$i] ($h)\n";
         $h=1;
     }
     else $h++;
}
$html_alb .= "</select>\n";
$html_alb .= "<input type=\"text\" name=\"aname_new\" value=\"\">\n";

echo "<html>\n<head>\n<title>$config[title]</title>\n</head>\n\n";
echo "<body bgcolor=\"$config[bgcolor]\" text=\"$config[textcolor]\" link=\"$config[linkcolor]\" alink=\"$config[alinkcolor]\" vlink=\"$config[vlinkcolor]\">\n<h1 align=center>$config[title]</h1>\n";

if ($aname == "")
    $aname = $aname_new;

     if($doupload) {
         if (!empty($aname)) {
              if($config[NeedPass]) {
                  if($password != "$config[Password]") {
                      echo("$li007<br>");
                      echo "<a href=\"$PHP_SELF\">$li008</a>";
                      exit();
                  }
              }
              $imCount = 0;
              if (isset($alben[$aname]))
                  $h = $alben[$aname] +1;
              else $h=1;
              while($imCount < $config[files]) {
                     $imCount++;

                     $images = "toupload$imCount"."_name";
                     $images1 = $$images;
                     $images2 = "toupload$imCount";
                     $images3 = $$images2;
                     if($images3 != "none") {
                         $filesizebytes = filesize($images3);
                         if ($filesizebytes < 15) {
                             $error .= sprintf("$li012<BR>", $images1);
                         }
                         elseif(file_exists("$config[dir]/$aname~$h.jpg")) {
                            $error .= sprintf("$li013<br>",$aname."-".$h);
                         }
                         else {
                            copy ($images3, "$config[dir]/$aname~$h.jpg");
                            $error .= sprintf("$li014<br>",$images1,$h);
                            $h++;
                         }
                     clearstatcache();
                     }
               }
          }
         else $error .= "$li015";
         if(!$error) {
         $error .= "$li009";
     }
     echo("<h3>$li011</h3>$error\n");
     echo "<br>\n<a href=\"$PHP_SELF\">$li008</a>\n";

     include "footer.inc";
     exit();
} else {
     $imCount = 0;
     while($imCount < $config[files]) {

       $imCount++;
       $html .= "<tr>\n<td bgcolor=\"$config[tablebgcolor]\">\n<font color=\"$config[tablefontcolor]\">$li004 $imCount</font>\n</td>\n
          <td align=right>\n<input name=\"toupload$imCount\" type=\"file\" size=\"50\" value=\"$li005\">\n</td>\n";

     }

     if($config[NeedPass] == "Yes") {

        $passhtml = "<table>\n<tr>\n<td>\n
         <table>\n<tr>\n<td>$li010:</td>\n
         <td><input name=\"password\" type=\"password\" size=\"25\"></td>\n</tr>\n</table>\n
         </td>\n</tr>\n</table>\n";
     }



     echo("<form enctype=\"multipart/form-data\" action=\"$PHP_SELF\" method=\"post\">\n
     <h2 align=center>$li001</h2>\n
     <table width=99% border=0><tr><td width=99% align=center>\n
     <table width=80% border=0>\n
     <tr>\n<td width=90% align=center colspan=2 bgcolor=\"$config[tablebgcolor]\"><font color=\"$config[tablefontcolor]\">$li002</font> $html_alb</td>\n</tr>\n
     $html
     \n</table>\n</td>\n</tr>\n
     </table>\n$passhtml
     <center>
     <input name=\"doupload\" type=\"submit\" value=\"$li006\">
     </center>\n
     </form>\n
     ");
     include "footer.inc";
     echo ("</body>\n</html>\n");
     exit();

}

php?>