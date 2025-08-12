<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>powered by PHP Pic-o-matic 2.0 Free</title>
<base target=_self>
<style type="text/css">
   body 	{ table-align: center; font-family: Trebuchet MS, Verdana, Arial; font-size: 10px; }
   a 		{ text-decoration: none; color: #000000; }
   img 		{ border-color: #000000; }
   img.albumtn 	{ margin-left:13px; margin-top:10px; margin-right:10px; margin-bottom:10px; float:right; }
   .linktd td a	{ display:block; width:100%; }
   td		{ text-align:center; font-family: Trebuchet MS, Tahoma; font-size: 9pt; border: 1px solid #000000; }
   td.previmg	{ width:92px; border:0px }
   td.prevbtn	{ width:10px; border:0px }
   p		{ margin-left:5px; margin-top:6px; margin-bottom:6px; text-align:justify; }
   p.navigation	{ margin-top:0px; margin-right:5px; margin-bottom:0px; }
   p.albuminfo	{ margin-left:8px; margin-top:8px; margin-right:10px; margin-bottom:8px; }
</style>
</head>

<body>

<?php
  if (isset($_GET['album']))
     $album = $_GET['album'];
  else
     $album = "";

  // Properties
  $headline = "PHP Pic-o-matic 2.0 Free";
  $subheadline = "Example picture gallery";
  $showroomwidth = 500;
  $showdesc = 1;

  // Headline
  print("<div align=center><font size=\"+2\"><b>$headline</b></a></font>
	<br><font size=2>$subheadline</font><br><br>");

  // Index
  if ($album == "" && $filehandle = opendir("."))
  {
    print("<table><tr><td width=$showroomwidth><p class=navigation>&nbsp;Navigation:&nbsp;&nbsp;
    	  <a href=\"pic-o-matic.php\"><b>Index</b></a></p></td></tr></table><table>");
    while (false !== ($albumcheck = readdir($filehandle)))
    {
      if ($albumcheck != "." && $albumcheck != ".." && is_dir($albumcheck) && substr($albumcheck,0,1) != "_")
      {
      	$albums[] = $albumcheck;
      }
    }
    closedir($filehandle);
    natcasesort($albums);

    foreach ($albums as $album)
    {
        if (file_exists("./$album/info.txt"))
           include("./$album/info.txt");
        else
        {
           $albumname = $album;
           $albuminfo = "";
        }

        print ("<tr><td width=$showroomwidth>");
        if (file_exists("./$album/thumbnail.jpg"))
           print ("<a href=\"pic-o-matic.php?album=$album\"><img border=1 class=albumtn src=\"$album/thumbnail.jpg\"></a>");
        print ("<p class=albuminfo><a href=\"pic-o-matic.php?album=$album\"><b>$albumname</b></a>");
        print ("<br>$albuminfo");
        print ("</p></td></tr>");
    }
    print("</table>");
  }

  // Creating the albums
  else
  {
    if(is_dir($album))
    {
      if (file_exists("./$album/info.txt"))
      {
      	include("./$album/info.txt");
      }
      else
      {
      	$albumname = $album;
         $albuminfo = "";
      }

      if (isset($_GET['show']))
         $show = $_GET['show'];
      else
      	$show = 1;
      $img_dir="./$album";
      $dir=opendir($img_dir);
      $thumbstring = "|";

   // Reading and checking images in the folder of the album
      while ($file=readdir($dir))
      {
         if ($file != "." && $file != ".." && $file != "thumbnail.jpg")
         {
           $extension=substr($file,-4);
	  if(($extension == ".JPG") || ($extension == ".jpg"))
             $thumbstring .= "$file|";
         }
      }
      $arry_txt = explode("|" , $thumbstring);
      natcasesort($arry_txt);

      if ($show >= (sizeof($arry_txt) - 1) || $show=="")  $show = 1;
      if ($show < 1)  $show = sizeof($arry_txt)-2;
      $img = "".$arry_txt[$show]."";

   // Setting the beginning and the end of the album
      if ($show == 1)
         $back = "<td width=75><font size=0><a href=\"pic-o-matic.php?album=$album&amp;show=" . (sizeof($arry_txt)-2) . "\">back</a></font>";
      else
         $back = "<td width=75><font size=0><a href=\"pic-o-matic.php?album=$album&amp;show=" . ($show - 1) . "\">back</a></font>";
      if ($show == sizeof($arry_txt) - 2)
      	$forward = "<td width=75><font size=0><a title=forward href=\"pic-o-matic.php?album=$album&amp;show=1\">forward</a></font>";
      else
      	$forward = "<td width=75><font size=0><a title=forward href=\"pic-o-matic.php?album=$album&amp;show=" . ($show + 1) . "\">forward</a></font>";

   // Reading the images' width and adjusting the size of a cell
      $imgsize = (getimagesize(rtrim($album . "/" . $img)));
      $midtdsize = ($imgsize[0] - 162);
      if ($midtdsize < 370)   $midtdsize = 370;
      print("<table><tr><td colspan=3 width=" . ($midtdsize + 162) . "><p class=navigation>&nbsp;Navigation:&nbsp;
             <a href=\"pic-o-matic.php\">Index</a> > <a href=\"pic-o-matic.php?album=$album\"><b>$albumname</b></a></p>
             </td></tr></table>");
      print("<table class=\"linktd\"><tr>");
      print($back);
      print("</td><td width=\"" . $midtdsize . "\"><font size=\"0\"><b>");
      print("Image " . ($show) . " of " . (sizeof($arry_txt)-2));
      print("</b></font></td>");
      print($forward);
      print("</td></tr></table><table><tr>");
      print("<td colspan=3><a title=\"forward\" href=\"pic-o-matic.php?album=$album&amp;show=" . ($show + 1) . "\"><img border=0 src=\"" . $img_dir . "/" . $img . "\"></a></td>");
      print("</tr>");
      print("</table><table class=\"linktd\"><tr>");
      if($showdesc == 1)
       	print("<td width=$imgsize[0] colspan=3><font size=1><b>Filename:</b> '" . $img . "'&nbsp;&nbsp;&nbsp;<b>Filesize:</b> " . (round((filesize($img_dir . "/" . $img)/1024), 1)) . " KB &nbsp;&nbsp;&nbsp;<b>Imagesize:</b> " . $imgsize[0] . "x" . $imgsize[1] . " px</font></td></tr>");

      $imginfo = substr($img, 0, -4) . '_info';

      if (isset($$imginfo) && $$imginfo != "")
         print("<td width=$imgsize[0] colspan=3><font size=1><b>Image description:</b> " . $$imginfo . "</font></td></tr>");

      print($back);
      print("</td><td width=" . $midtdsize . "><font size=0><b>");
      print("Image " . ($show) . " of " . (sizeof($arry_txt)-2));
      print("</b></font></td>");
      print($forward);
      print("</td></tr>");

   // Preview
      print("<tr><td colspan=3 valign=top height=116><div align=center><table><tr>");
      if (($show - 3) > 0)
         print("<td class=prevbtn><a href=\"pic-o-matic.php?album=$album&amp;show=" . ($show - 4) . "\"><br><<br><br><br><<br></a></td>");
      else  print("<td class=prevbtn>&nbsp;</td>");

      for ($i = -2; $i < 3; $i++)
      {
         if (!isset($arry_txt[$show + $i]) || $arry_txt[$show + $i] == null)
             $link = "&nbsp;";
         else
         {
            $imgsize = getimagesize(rtrim($album . "/" . ($arry_txt[$show + $i])));
            if (($imgsize[0]) > ($imgsize[1]))
               $img  = "<img border=1 width=85 src=\"" . $img_dir . "/" . ($arry_txt[$show + $i]) . "\">";
            else
               $img  = "<img border=1 height=80 src=\"" . $img_dir . "/" . ($arry_txt[$show + $i]) . "\">";
            if ($i == 0)
               $link = "<a href=\"pic-o-matic.php?album=$album&amp;show=" . ($show) . "\">current<br>$img</a>";
            else
               $link = "<a href=\"pic-o-matic.php?album=$album&amp;show=" . ($show + $i) . "\">" . ($show + $i) . "/" . (sizeof($arry_txt)-2) . "<br>$img</a>";
         }
         print("<td class=previmg>");
         print($link);
         print("</td>");
      }

      if (($show + 4) < sizeof($arry_txt))
         print("<td class=prevbtn><a href=\"pic-o-matic.php?album=$album&amp;show=" . ($show + 4) . "\"><br>><br><br><br>><br></a></td>");
      else
      	print("<td class=prevbtn>&nbsp;</td>");
      print("</tr></table></div></td></tr></table>");
    }
  }
?>

<font size=0><a href="http://www.popwars.de/Pic-o-matic/">powered by PHP Pic-o-matic &copy;</a></font>
</div>
</body>

</html>