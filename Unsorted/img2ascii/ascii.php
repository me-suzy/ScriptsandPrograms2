<?php
/******************************************************************************************/
/*
    IMG2ASCII V 1.10
    
    Copyright 2003 by Ueli Weiss (gweilo83@hotmail.com)
    
    This program is free software; you can redistribute it and/or modify 
    it under the terms of the GNU General Public License as published by 
    the Free Software Foundation. This program is distributed in the hope that it will be useful, 
    but WITHOUT ANY WARRANTY; without even the implied warranty of 
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
    GNU General Public License for more details.
    
    This copyright text may not be edited or deleted.
    
    Best quality is achieved with font size 13 - 20 (since there is no anti-aliasing, which would 
    make the image too light). 
    
    You may use generated images on your websites, or wherever you like, but if you do, please
    add a link to http://sourceforge.net/projects/img2ascii as a return service. Thanks!
    
    Have fun!
                                                                                          */
/******************************************************************************************/

$db_server = "localhost";
$db_name = "db";
$db_user = "usr";
$db_passwort = "pwd";
               
$db = MYSQL_CONNECT($db_server,$db_user,$db_passwort);
$db_select = MYSQL_SELECT_DB($db_name);

$self=substr($_SERVER["PHP_SELF"],strrpos($_SERVER["PHP_SELF"],"/")+1);
$m_t = 10;
$m_l = 10;
$depth = 10;
$timelimit = 600; // Sets the max_execution_time for the running time of this script.
$htmlencoded = 0; // display the code correctly , for example Á is &#193; - default is disabled = 0; To enable change to = 1;


if (isset($_POST['type']))
  $type = $_POST['type'];

if (isset($_POST['send']) || isset($_GET['mode']))
{
  set_time_limit($timelimit);
  $file = $_FILES['userfile']['name'];
  $color = $_POST['color'];
  $inverse = $_POST['inverse'];
  $inverseascii = $_POST['inverseascii'];
  $art = $_POST['art'];
  $chars = $_POST['chars'];
  $nodispersion = $_POST['nodispersion'];
  $everypx = $_POST['everypx'];
  $fontsize = $_POST['fontsize'];
  $layer = $_POST['layers'];
  $ascii = $_POST['ascii'];
  $type = $_POST['type'];                     // type = 1 -> Table ; type = 2 -> ASCII
  $breite = $_POST['breite'];
  $hoehe = $_POST['hoehe'];
  $bgcolor =  $_POST['bgcolor'];
  $schriftfarbe = $_POST['schriftfarbe'];
  $lignheight = $_POST['lignheight'];
  $letterspacing = $_POST['letterspacing'];
  $fh = $_POST['fh'];                         // Flip Horizontally
  $fv = $_POST['fv'];                         // Flip Vertikally
  $fehler="";
  $inverter=0;
  $lastspan="c";
  if ($fontsize>=13)
    $size= "c".$fontsize;
  else
    $size = "c20"; // was c$fontsize before, but the c20 database just rendered better results.
  $countascii = strlen($ascii);
  $asciicounter = 0;  
  
  
  // predefined modes
  if (isset($_GET['mode']))
  {
    $mode = $_GET['mode'];
    if ($mode == "dot")
    {
      $ascii = ".";
      $color = 1;
      $fontsize = 13;
      $type = 2;
      $lignheight = -11;
      $letterspacing = -6;
      $fehler .= "Warning: The rendering in this mode could take a few minutes (be sure to set 
max_execution_time in php.ini), because every 4th pixel is rendered.";
    }
    if ($mode == "bw")
    {
      $ascii = ".";
      $color = 0;
      $fontsize = 13;
      $type = 2;
      $lignheight = -12;
      $letterspacing = -7;
      $fehler .= "Warning: The rendering in this mode could take a few minutes (be sure to set 
max_execution_time in php.ini), because every pixel is rendered.";
    }
    if ($mode == "block")
    {
      $ascii = "&#28;";
      $color = 1;
      $fontsize = 13;
      $type = 2;
      $lignheight = -4;
      $letterspacing = -5;
    }
    if ($mode == "square")
    {
      $ascii = "&#28;";
      $color = 1;
      $fontsize = 13;
      $type = 2;
      $lignheight = -10;
      $letterspacing = -5;
      $fehler .= "Warning: The rendering in this mode could take a few minutes (be sure to set 
max_execution_time in php.ini), because every 9th pixel is rendered.";
    }
    if ($mode == "matrix")
    {
      $fontsize = 13;
      $type = 2;
      $inverseascii = 1;
      $bgcolor =  "000000";
      $schriftfarbe = "00ff00";
    }
    if ($mode == "matrixsmall")
    {
      $fontsize = 6;
      $type = 2;
      $lignheight = -3;
      $letterspacing = -1;
      $inverseascii = 1;
      $bgcolor =  "000000";
      $schriftfarbe = "00ff00";
      $fehler .= "Warning: The rendering in this mode could take a few minutes (be sure to set 
max_execution_time in php.ini), because every 4th pixel is rendered.";
    }
    if ($mode == "scratchy")
    {
      $color = 1;
      $fontsize = 13;
      $type = 2;
      $inverseascii = 0;
      $lignheight = -10;
      $letterspacing = -5;
    }
  }
  
  if ($type==2)
  {
    if ($fontsize==4)
    {
      $px = 2+$letterspacing;
      $py = 4+$lignheight;
      // when printed $px should be 2.5+$letterspacing, otherwise it should be 2 (for the screen)
    }
    if ($fontsize==6)
    {
      $px = 4+$letterspacing;
      $py = 7+$lignheight;
    }
    if ($fontsize==8)
    {
      $px = 5+$letterspacing;
      $py = 8+$lignheight;
    }
    if ($fontsize==13)
    {
      $px = 8+$letterspacing;
      $py = 13+$lignheight;
      $vert = $m_t + 3;
      $horz = $m_l + 7;
      $vert2 = $m_t + 6;
      $horz2 = $m_l;
    }
    if ($fontsize==16)
    { 
      $px = 9+$letterspacing;
      $py = 16+$lignheight;
      $vert = $m_t + 3;
      $horz = $m_l + 8;
      $vert2 = $m_t + 6;
      $horz2 = $m_l;
    }
    if ($fontsize==20)
    { 
      $px = 12+$letterspacing;
      $py = 20+$lignheight;
      $vert = $m_t + 5;
      $horz = $m_l + 10;
      $vert2 = $m_t + 10;
      $horz2 = $m_l;
    }
    if ($px <= 0)   // to prevent an infinite loop
      $px = 1;
    if ($py <= 0)
      $py = 1;
  }
  if ($ascii) // $ascii -> $asciichars[]
  {
    $g=0;
    for ($i=0;$i<strlen($ascii);$i++)
    {
      $char = $ascii[$i];
      if ($char=="&")
      {
        $pos = strpos($ascii,";",$i);
        if ($pos)
        {
          $char="";
          for ($j=$i;$j<=$pos;$j++)
            $char.=$ascii[$j];
          $i=$j;
        }
      }
      $asciichars[$g] =  $char;
      $id = substr($char,1,-1);
      $result = mysql_query("SELECT $size FROM ascii WHERE (achar='$char' || id='$id') && type!='6'");
      $pts = mysql_fetch_row($result);   // result
      $asciipts[$g] = $pts[0];
      if ($g==1)  // first entry
      {
        $min = $pts[0];
        $max = $pts[0];
      }
      elseif ($pts[0] < $min)
        $min = $pts[0];
      elseif ($pts[0] > $max)
        $max = $pts[0];
      $g++;
    } // end foreach
  } // endif $ascii
}



?>
<html>
<head>
<TITLE>IMG2ASCII - &copy; Ueli Weiss</TITLE>
<style type="text/css">
body {
    font-family: verdana;
    font-size: 10;
    <?php if ($bgcolor == "000000") echo "color: #EEEEEE;";
    if (isset($bgcolor)) echo "background: #".$bgcolor.";";
    ?>
}
A,A:link,A:visited
{
    FONT-SIZE: 10px;
    COLOR: #054072;
    FONT-WEIGHT:normal;
    FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;
    TEXT-DECORATION: none
}
A:hover,A:active
{
    FONT-SIZE: 10px;
    FONT-WEIGHT:normal;
    COLOR: #1785E2;
    FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif;
    TEXT-DECORATION: none
}
table {
    font-family: verdana;
    font-size: 10;
    <?php if ($bgcolor == "000000") echo "color: #EEEEEE;";
    if (isset($bgcolor)) echo "background: #".$bgcolor.";";
    ?>
}
.titel{
    font-family: verdana;
    font-size: 11;
    font-weight: bold;
    color: #054072;
}
<?php if (isset($_POST['send']) || isset($_GET['mode'])) { ?>
.courier{
    font-family: <?php if ($fontsize>=9) echo "courier"; else echo "courier new";?>;
    font-size: <?php echo $fontsize;?>;
    color: #<?php echo $schriftfarbe?>;
    line-height     : <?php echo $py; ?>px;
    letter-spacing  : <?php echo $letterspacing; ?>px;
}
.inverse {
	font-family: <?php if ($fontsize>10) echo "courier"; else echo "courier new";?>;
	font-size: <?php echo $fontsize;?>;
	color: #<?php echo $bgcolor?>;
	background-color: #<?php echo $schriftfarbe?>;
}
<?php } ?>
</style>
</head>
<body marginwidth="<?php echo $m_l?>" marginheight="<?php echo $m_t?>" leftmargin="<?php echo $m_l?>" topmargin="<?php echo $m_l?>">
<?php


if (isset($_POST['send']) || isset($_GET['mode']))
{

if ($type==1){
  if ($breite<1)
    $fehler.="The width has to be at least 1.<br>";
  if ($breite<1)
    $fehler.="The height has to be at least 1.<br>";
}

$result = mysql_query("SELECT id,image FROM images");
$datei = mysql_fetch_row($result);
// suppr($datei[1]); // deletes the last file used. from the main folder
mysql_query("DELETE FROM images WHERE id='$datei[0]'");
mysql_query("INSERT images (image) VALUES ('$file')");

if (is_uploaded_file($_FILES['userfile']['tmp_name']) && $file) // upload img
  {
    copy($_FILES['userfile']['tmp_name'],$file);
    // $file = $_FILES['userfile']['tmp_name'];
  }
elseif (!isset($_GET['mode']))
  $fehler.="The file could not be uploaded!<br>";

if (!eregi("jpeg|gif|png",$_FILES['userfile']['type']) && $file)
  $fehler.="The file is not a supported image file! (jpg/gif/png)<br>";
if (!$file && !isset($_GET['mode']))
  $fehler.="No image was added!<br>";  
if (count($art)<1 && !isset($_GET['mode']) && $chars=="" && $type==2)
  $fehler.="Enter at least one type of letters.<br>";
  
if (!$fehler && !isset($_GET['mode']))
{
$vert = $m_t;
$horz = $m_l;
$vert2 = $m_t;
$horz2 = $m_l;

if ($type==1){
  $px = $breite;
  $py = $hoehe;
}

if (eregi("jpeg",$_FILES['userfile']['type']))
  $im = imagecreatefromjpeg($file);
if (eregi("gif",$_FILES['userfile']['type'])) // Newer PHP versions do not support GIF images anymore !
  $im = imagecreatefromgif($file);
if (eregi("png",$_FILES['userfile']['type']))
  $im = imagecreatefrompng($file);
  
$size_arr = getimagesize($file);
$width=$size_arr[0];
$height=$size_arr[1];

$where = "";
if ($type == 2)
{
  if (count($art)>=1) // if character from select list is selected, in addition then the chars below
  {
    foreach ($art as $temp)
    {
      if ($temp == 6) // inverse characters (bg-fg)
        $inverter = 1;
    }
    foreach ($art as $temp)
    {
      if ($inverter && $temp != 6) // inverse characters (bg-fg)
        $where .= " type='".$temp."' || type='6|".$temp."' ||";
      elseif ($temp != 6)
        $where .= " type='".$temp."' ||";   
    }
  }
  if ($chars)
  {
    if (!$inverter)
      $where .= "(";
    for ($i=0;$i<strlen($chars);$i++)
    { 
      $temp = $chars[$i];
      if ($temp == "&")
        $temp = "&amp;";
      if ($temp == " ")
        $temp = "&nbsp;";
      if ($temp == "<")
        $temp = "&lt;";
      if ($temp == ">")
        $temp = "&gt;";
      $where .= " achar='".$temp."' ||";
    }
    if (!$inverter)
    {
      $where = substr($where,0,-3);
      $where .= ") && id<=256 ||";
    }
  }
  
  $where = substr($where,0,-3);
  if ($art[0]==6 && !$chars) // blank letter inversed - only "inversed" was selected as a letter type
  {
    $where = "achar='&nbsp;'";
  }
  $white = mysql_query("SELECT $size FROM ascii WHERE $where ORDER BY $size ASC LIMIT 1");
  $white = mysql_fetch_row($white);   // minimum
  $black = mysql_query("SELECT $size FROM ascii WHERE $where ORDER BY $size DESC LIMIT 1");
  $black = mysql_fetch_row($black);   // maximum
}

/************************* TABLE, COLOR *****************************************************/
if ($type==1 && $color){
  echo '<table border="0" cellspacing="0" cellpadding="0">';
  for ($cy=0;$cy<$height;$cy+=$py) {
    echo "<tr>\n";
    for ($cx=0;$cx<$width;$cx+=$px) {
      $x = $cx;
      $y = $cy;
      if ($fh)  // Flip Horizontally
        $x = $width - $cx-1;
      if ($fv)  // Flip Horizontally
        $y = $height - $cy-1;
      if (!$everypx)
      { 
        $rgb = ImageColorAt($im, $x, $y);
        $col = imagecolorsforindex($im, $rgb);
        $rgb = explode("-",sprintf("%02d-%02d-%02d",$col["red"], $col["green"], $col["blue"]));
      }
      if ($everypx) // every pixel is rendered. $col & $rgb will be overwritten.
      {
        $r=0;
        $g=0;
        $b=0;
        $pixels=0;
        for ($x=$cx;$x<$cx+$px;$x++)
        {
          for ($y=$cy;$y<$cy+$py;$y++)
          {
            if ($y<$height && $x<$width)
            {
              $rgb = ImageColorAt($im, $x, $y);
              $col = imagecolorsforindex($im, $rgb);
              $rgb = explode("-",sprintf("%02d-%02d-%02d",$col["red"], $col["green"], $col["blue"]));
              $r += $rgb[0];
              $g += $rgb[1];
              $b += $rgb[2];
              $pixels++;
            }
          }
        }
        $rgb = array($r/$pixels,$g/$pixels,$b/$pixels);
        $farbe = ($rgb[0]+$rgb[1]+$rgb[2])/3;
      }
      if (!$inverse)
      {
        $hex = sprintf("%02x%02x%02x",$rgb[0],$rgb[1],$rgb[2]);
      }
      else
      {
        $hex = sprintf("%02x%02x%02x",255-$rgb[0],255-$rgb[1],255-$rgb[2]);
      }
    echo "<td width=\"".$px."\" height=\"".$py."\" bgcolor=\"#".$hex."\"></td>\n";
    }
  echo '</tr>';
  }
  echo '</table><br><br>';
}

/************************* TABLE BLACK WHITE ***********************************/
if ($type==1 && !$color){
  echo '<table border="0" cellspacing="0" cellpadding="0">';
  for ($cy=0;$cy<$height;$cy+=$py) {
    echo "<tr>\n";
    for ($cx=0;$cx<$width;$cx+=$px) {
      $x = $cx;
      $y = $cy;
      if ($fh)  // Flip Horizontally
        $x = $width - $cx -1;
      if ($fv)  // Flip Horizontally
        $y = $height - $cy -1;
      if (!$everypx)
      {
        $rgb = ImageColorAt($im, $x, $y);
        $col = imagecolorsforindex($im, $rgb);
        $rgb = explode("-",sprintf("%02d-%02d-%02d",$col["red"], $col["green"], $col["blue"]));
        $farbe = ($rgb[0]+$rgb[1]+$rgb[2])/3;
      }
      if ($everypx) // every pixel is rendered. $col & $rgb will be overwritten.
      {
        $r=0;
        $g=0;
        $b=0;
        $pixels=0;
        for ($x=$cx;$x<$cx+$px;$x++)
        {
          for ($y=$cy;$y<$cy+$py;$y++)
          {
            if ($y<$height && $x<$width)
            {
              $rgb = ImageColorAt($im, $x, $y);
              $col = imagecolorsforindex($im, $rgb);
              $rgb = explode("-",sprintf("%02d-%02d-%02d",$col["red"], $col["green"], $col["blue"]));
              $r += $rgb[0];
              $g += $rgb[1];
              $b += $rgb[2];
              $pixels++;
            }
          }
        }
        $rgb = array($r/$pixels,$g/$pixels,$b/$pixels);
        $farbe = ($rgb[0]+$rgb[1]+$rgb[2])/3;
      }
      if (!$inverse)
        $hex = sprintf("%02x%02x%02x",$farbe, $farbe, $farbe);
      else
        $hex = sprintf("%02x%02x%02x",255-$farbe, 255-$farbe, 255-$farbe);
      echo "<td width=\"".$px."\" height=\"".$py."\" bgcolor=\"#".$hex."\"></td>\n";
    }
    echo '</tr>';
  }
  echo '</table><br><br>';
}
/************************* ASCII *****************************************/
if ($type==2){
  $text = "";
  $text.= "<NOBR><span class=\"courier\">";
  srand((double)microtime() * 10000000);
  
  
  for ($cy=0;$cy<$height;$cy+=$py) {
    for ($cx=0;$cx<$width;$cx+=$px) {
      $x = $cx;
      $y = $cy;
      if ($fh)  // Flip Horizontally
        $x = $width - $cx-1;
      if ($fv)  // Flip Horizontally
        $y = $height - $cy-1;
      $text .= getchar($x,$y,$im);
    }
    $text.=  "<br />\n";
  }
  
  $text.= "</span></NOBR>\n\n";
  //$text = strtr($text, $mod); 
  echo $text;
  if ($layer>=2)
    echo "<DIV ID=\"2\" STYLE=\"position:absolute; z-index:1; left:".$vert."px; top:".$horz."px; width: ".$width."; height: ".$height."\">".$text."</DIV>\n\n";
  if ($layer>=3)
    echo "<DIV ID=\"3\" STYLE=\"position:absolute; z-index:2; left:".$vert2."px; top:".$horz2."px; width: ".$width."; height: ".$height."\">".$text."</DIV>\n\n";
  if ($layer>=4)
    echo "<DIV ID=\"4\" STYLE=\"position:absolute; z-index:3; left:".$vert."px; top:".$horz2."px; width: ".$width."; height: ".$height."\">".$text."</DIV>\n\n";
  if ($layer>=5)
    echo "<DIV ID=\"5\" STYLE=\"position:absolute; z-index:4; left:".$vert2."px; top:".$horz."px; width: ".$width."; height: ".$height."\">".$text."</DIV>\n\n";
  if ($layer>=6)
    echo "<DIV ID=\"6\" STYLE=\"position:absolute; z-index:5; left:".$m_t."px; top:".$horz."px; width: ".$width."; height: ".$height."\">".$text."</DIV>\n\n";
  ?><br><br><?
}
echo "<img src=\"".$file."\">";
  } // end if fehler
} // end show
if (isset($_POST['type']) || $fehler || isset($_GET['mode']))
{
  
?>
<br /><br />
<span class="titel">JPG 2 <?php if ($type==1) echo "HTML"; else echo "ASCII"; ?></span><br />
<?php if ($fehler) echo "<br><font color=\"#FF0000\" class=\"error\">".$fehler."</font>";?>
<form action="<?php echo $self?>" method="POST">
<table width="450" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="100">
      Image File
    </td>
    <td>
      <input type="file" name="userfile" />
      <DIV ID="hiden" STYLE="position:absolute; left:-100px; top:-100px; width:100; height: 100;">
      <input name="type" type="hidden" value="<?php echo $type?>"></DIV>
    </td>
  </tr>
  <tr>
    <td>
      Color
    </td>
    <td>
      <input type="checkbox" name="color" value="1"<?php if ($color==1) echo " checked";?>>
    </td>
  </tr>
  <tr>
    <td>
      Invert Color
    </td>
    <td>
      <input type="checkbox" name="inverse" value="1"<?php if ($inverse==1) echo " checked";?>>
    </td>
  </tr>
  <?php
  if ($type==2){
  ?>
  <tr>
    <td>
      Invert ASCII
    </td>
    <td>
      <input type="checkbox" name="inverseascii" value="1"<?php if ($inverseascii==1) echo " checked";?>>
    </td>
  </tr>
  <tr>
    <td>
      No Dispersion
    </td>
    <td>
      <input type="checkbox" name="nodispersion" value="1"<?php if ($nodispersion==1) echo " checked"; ?>>
    </td>
  </tr>
  <tr>
    <td>
      Anti-aliasing<br />
      <small>(very time-consuming)</small>
    </td>
    <td>
      <input type="checkbox" name="everypx" value="1"<?php if ($everypx==1) echo " checked"; ?>>
    </td>
  </tr>
  <tr>
    <td>
      Characters <br /><small>(deselect with CTRL + <br />left mouse button)</small>
    </td>
    <td>
      <select name="art[]" multiple size="6">
        <option value="1"<?php 
      $selected=0;
      if (isset($_POST['art']))
      {
      foreach ($art as $temp)
        if ($temp == 1)
          $selected = 1;}
      if ($selected || !isset($_POST['chars'])) echo " selected";?>>Alphabet</option>
        <option value="2"<?php 
        $selected=0;
      if (isset($_POST['art']))
      {
      foreach ($art as $temp)
        if ($temp == 2)
          $selected = 1;}
      if ($selected || !isset($_POST['chars'])) echo " selected";?>>Number</option>
        <option value="3"<?php 
        $selected=0;
        if (isset($_POST['art'])) {
      foreach ($art as $temp)
        if ($temp == 3)
          $selected = 1;}
      if ($selected || !isset($_POST['chars'])) echo " selected";?>>Punctuation marks</option>
        <option value="4"<?php 
        $selected=0;
        if (isset($_POST['art'])) {
      foreach ($art as $temp)
        if ($temp == 4)
          $selected = 1;}
      if ($selected || !isset($_POST['chars'])) echo " selected";?>>Exotic</option>
        <option value="5"<?php 
        $selected=0;
        if (isset($_POST['art'])) {
      foreach ($art as $temp)
        if ($temp == 5)
          $selected = 1;}
      if ($selected) echo " selected";?>>Blocks</option>
        <option value="6"<?php 
        $selected=0;
        if (isset($_POST['art'])) {
      foreach ($art as $temp)
        if ($temp == 6)
          $selected = 1;}
      if ($selected) echo " selected";?>>Inversed</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
      Additional characters<br /> to be used
    </td>
    <td>
      <input type="text" name="chars" value="<?php echo $chars;?>" />
    </td>
  </tr>
  <tr>
    <td>
      Font Size
    </td>
    <td>
      <select name="fontsize">
        <option value="4"<?php if ($fontsize==4) echo " selected";?>>4</option>
        <option value="6"<?php if ($fontsize==6) echo " selected";?>>6</option>
        <option value="8"<?php if ($fontsize==8) echo " selected";?>>8</option>
        <option value="13"<?php if ($fontsize==13 || !isset($fontsize)) echo " selected";?>>13</option>
        <option value="16"<?php if ($fontsize==16) echo " selected";?>>16</option>
        <option value="20"<?php if ($fontsize==20) echo " selected";?>>20</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
      Lign Height<br />
      <small>0 is normal</small>
    </td>
    <td>
      <input type="text" name="lignheight" size="2" maxlength="3" value="<?php if ($lignheight) echo $lignheight; else echo "0";?>"><br />
    </td>
  </tr>
  <tr>
    <td>
      Letter Spacing<br />
      <small>0 is normal</small>
    </td>
    <td>
      <input type="text" name="letterspacing" size="2" maxlength="3" value="<?php if ($letterspacing) echo $letterspacing; else echo "0";?>"><br />
    </td>
  </tr>
  <tr>
    <td>
      Nr. of Layers
    </td>
    <td>
      <select name="layers">
        <option value="1" selected>1</option>
        <option value="2"<?php if ($layers==2) echo " selected";?>>2</option>
        <option value="3"<?php if ($layers==3) echo " selected";?>>3</option>
        <option value="4"<?php if ($layers==4) echo " selected";?>>4</option>
        <option value="5"<?php if ($layers==5) echo " selected";?>>5</option>
        <option value="6"<?php if ($layers==6) echo " selected";?>>6</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
      Character(s) to use<br />
      <small>ie "hello"</small>
    </td>
    <td>
      <input type="text" name="ascii" size="6" maxlength="50" value="<?php if ($ascii) echo $ascii;?>">
    </td>
  </tr>
  <tr>
    <td>
      Font Color (HEX)
    </td>
    <td>
      <input type="text" name="schriftfarbe" size="6" maxlength="6" value="<?php if ($schriftfarbe) echo $schriftfarbe; else echo "000000";?>">
    </td>
  </tr>
  <?php
  }
  ?>
  <?php
  if ($type==1){
  ?>
  <tr>
    <td>
      Width of table
    </td>
    <td>
      <input type="text" name="breite" size="4" maxlength="4" value="<?php if ($breite) echo $breite; else echo "8";?>">
    </td>
  </tr>
  <tr>
    <td>
      Height of table
    </td>
    <td>
      <input type="text" name="hoehe" size="4" maxlength="4" value="<?php if ($hoehe) echo $hoehe; else echo "8";?>">
    </td>
  </tr>
  <tr>
    <td>
      Anti-aliasing<br />
      <small>(very time-consuming)</small>
    </td>
    <td>
      <input type="checkbox" name="everypx" value="1"<?php if ($everypx==1) echo " checked"; ?>>
    </td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td>
      Background Color (HEX)
    </td>
    <td>
      <input type="text" name="bgcolor" size="6" maxlength="6" value="<?php if ($bgcolor) echo $bgcolor; else echo "FFFFFF";?>">
    </td>
  </tr>
  <tr>
    <td>
      Flip Horizontally
    </td>
    <td>
      <input type="checkbox" name="fh" value="1"<?php if ($fh==1) echo " checked";?>>
    </td>
  </tr>
  <tr>
    <td>
      Flip Vertically
    </td>
    <td>
      <input type="checkbox" name="fv" value="1"<?php if ($fv==1) echo " checked";?>>
    </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;
      
    </td>
    <td>
      <input type="submit" value="Render" name="send">
    </td>
  </tr>
</table>
</form>
  <?php  
}

if (isset($_GET['modes']))
{
  ?>
<span class="titel">Select Mode</span><br />
<br />
<table width="800" border="0" cellpadding="5">
  <tr align="left" valign="top"> 
    <td height="28" colspan="2"><span class="titel">Dot</span></td>
  </tr>
  <tr align="center" valign="top"> 
    <td width="382" height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=dot"><img src="auto350dot.jpg" width="350" height="269" border="0"></a></div></td>
    <td width="392"> <div align="center"><a href="<?php echo $self;?>?mode=dot"><img src="car350.jpg" width="350" height="269" border="0"></a></div></td>
  </tr>
  <tr align="left" valign="top"> 
    <td height="28" colspan="2"><span class="titel">Block</span></td>
  </tr>
  <tr align="center" valign="top"> 
    <td height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=block"><img src="sunset350block.jpg" width="350" height="269" border="0"></a></div></td>
    <td> <div align="center"><a href="<?php echo $self;?>?mode=block"><img src="sunset350.jpg" width="350" height="269" border="0"></a></div></td>
  </tr>
  <tr align="left" valign="top"> 
    <td height="28" colspan="2"><span class="titel">Square</span></td>
  </tr>
  <tr align="center" valign="top"> 
    <td height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=square"><img src="sunset350square.jpg" width="350" height="269" border="0"></a></div></td>
    <td> <div align="center"><a href="<?php echo $self;?>?mode=square"><img src="sunset350.jpg" width="350" height="269" border="0"></a></div></td>
  </tr>
  <tr align="left" valign="top"> 
    <td height="28" colspan="2"><span class="titel">Scratchy (1:2)</span></td>
  </tr>
  <tr align="center" valign="top"> 
    <td height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=scratchy"><img src="car350scratchy.jpg" width="350" height="267" border="0"></a></div></td>
    <td> <div align="center"><a href="<?php echo $self;?>?mode=scratchy"><img src="car350.jpg" width="350" height="275" border="0"></a></div></td>
  </tr>
  <tr align="left" valign="top"> 
    <td height="28" colspan="2"><span class="titel">Matrix (1:3)</span></td>
  </tr>
  <tr align="center" valign="top"> 
    <td height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=matrix"><img src="matrix350.jpg" width="350" height="474" border="0"></a></div></td>
    <td> <div align="center"><a href="<?php echo $self;?>?mode=matrix"><img src="matrix350_1.jpg" width="350" height="474" border="0"></a></div></td>
  </tr>
  <tr align="left" valign="top"> 
    <td height="28" colspan="2"><span class="titel">Matrix Small (1:1)</span></td>
  </tr>
  <tr align="center" valign="top"> 
    <td height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=matrixsmall"><img src="matrixsm350.jpg" width="350" height="474" border="0"></a></div></td>
    <td> <div align="center"><a href="<?php echo $self;?>?mode=matrixsmall"><img src="matrix350_1.jpg" width="350" height="474" border="0"></a></div></td>
  </tr>
  <tr align="center" valign="top"> 
    <td height="279"> <div align="center"> <a href="<?php echo $self;?>?mode=bw"><img src="matrix350_4.gif" width="350" height="474" border="0"></a></div></td>
    <td> <div align="center"><a href="<?php echo $self;?>?mode=bw"><img src="matrix350_1.jpg" width="350" height="474" border="0"></a></div></td>
  </tr>
</table>
  <?php
}

if (!count($_POST) && !count($_GET)) // default
{
  ?>
<span class="titel">IMG2ASCII</span><br /><br />
<form action="<?php echo $self;?>" name="formular" method="POST">
<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="150" valign="top">
      Output
    </td>
    <td>
      <select name="type" onchange="window.document.formular.submit()">
        <option value="1" selected></option>
        <option value="1">Table</option>
        <option value="2">ASCII</option>
      </select>
    </td>
  </tr>
  <tr>
    <td valign="top">&nbsp;
      
    </td>
    <td>
      <input type="submit" value="Continue" name="send1">
    </td>
  </tr>
</table>
</form>
  <?php  
}
?>
<br /><br />
<a href="<?php echo $self;?>">Reload</a> | <a href="<?php echo $self;?>?modes=show">Predefined Modes</a> | <a href="mailto:gweilo83@hotmail.com">&copy; Ueli Weiss</a>
</body>
</html>

<?php

/*************************************************************************************************************/
/*************************************************** FUNCTIONS ***********************************************/
/*************************************************************************************************************/

function getchar($cx,$cy,$im) // Get the character at the current position of the image.
  {
    GLOBAL $ascii, $color, $inverse, $inverseascii, $art, $nodispersion, $fontsize, $size, $where, $black,
           $white, $depth, $lastspan, $htmlencoded, $countascii, $asciicounter, $asciichars, $min, $max, $asciipts, $px, $py,
           $everypx, $width, $height;
           
      if (!$everypx) // normal mode
      {
        $rgb = ImageColorAt($im, $cx, $cy);
        $col = imagecolorsforindex($im, $rgb);
        $rgb = explode("-",sprintf("%02d-%02d-%02d",$col["red"], $col["green"], $col["blue"]));
        $farbe = ($rgb[0]+$rgb[1]+$rgb[2])/3;
      }
      if ($everypx) // every pixel is rendered. $col & $rgb will be overwritten.
      {
        $r=0;
        $g=0;
        $b=0;
        $pixels=0;
        for ($x=$cx;$x<$cx+$px;$x++)
        {
          for ($y=$cy;$y<$cy+$py;$y++)
          {
            if ($y<$height && $x<$width)
            {
              $rgb = ImageColorAt($im, $x, $y);
              $col = imagecolorsforindex($im, $rgb);
              $rgb = explode("-",sprintf("%02d-%02d-%02d",$col["red"], $col["green"], $col["blue"]));
              $r += $rgb[0];
              $g += $rgb[1];
              $b += $rgb[2];
              $pixels++;
            }
          }
        }
        $rgb = array($r/$pixels,$g/$pixels,$b/$pixels);
        $farbe = ($rgb[0]+$rgb[1]+$rgb[2])/3;
      }
      if (!$ascii)  // normal ascii generation
      {  
        if ($inverseascii)
          $val = $farbe*($black[0]-$white[0])/255+$white[0];
        if (!$inverseascii)
          $val = $black[0]+$white[0]-($farbe*($black[0]-$white[0])/255+$white[0]);  // from light to dark
        $resulto = mysql_query("SELECT id,$size,type,achar FROM ascii WHERE ($where) && $size>=$val ORDER BY ($size-$val) ASC LIMIT $depth"); // results higher than value
        $resultu = mysql_query("SELECT id,$size,type,achar FROM ascii WHERE ($where) && $size<=$val ORDER BY ($val-$size) ASC LIMIT $depth"); // results lower than selection
        $resultm = mysql_query("SELECT id,$size,type,achar FROM ascii WHERE ($where) && $size<=$val ORDER BY abs($val-$size) ASC LIMIT $depth"); // nearest result - case: $nodispersion
        $array = array();
        $dist = array();  // distance from what it should be    
             
        for ($lauf=1;$lauf<=2;$lauf++)
        {
          if ($nodispersion)
            $result=$resultm;
          elseif ($lauf==1)
            $result=$resulto;
          elseif ($lauf==2)
            $result=$resultu;
          $i=-1;
  
          while ($row = mysql_fetch_row($result))
          {
            $i++;
            if ($i==0){
              array_push($array,$row[0]);
              array_push($dist,abs($row[1]-$val));
              }
            elseif ($row[1]==$last){
              array_push($array,$row[0]);
              array_push($dist,abs($row[1]-$val));
              }
            else
              break;
            $last = $row[1];
          }//endwhile
        }//endfor
      }//endif!$ascii
      if ($color)
      {
        if ($inverse)
        {
          $hex = sprintf("%02x%02x%02x",255-$rgb[0],255-$rgb[1],255-$rgb[2]);
        }
        else
        {
          $hex = sprintf("%02x%02x%02x",$rgb[0],$rgb[1],$rgb[2]);
        }
        $text.= "<font color=\"#".$hex."\">";
      }
      $char="";
      if ($nodispersion && !$ascii)
      {
        $char = $array[0];
        $charid = $char;
        if (!$htmlencoded)  // get actual character from database
        {
          $result2 = mysql_query("SELECT achar FROM ascii WHERE id='$charid' LIMIT 1");
          $row2 = mysql_fetch_row($result2);
          $char = $row2[0];
        }
      }
      elseif (!$ascii)  // $nodispersion not with constant char
      { 
        $p_tot=0;
        $p=0;
        $rand = rand(0,100000);
        $lastel = $dist[count($array)-1];
        $firstel = $dist[0];
        foreach ($array as $p1){
        //echo $p1." ";
        }
        //echo "<br>";
        foreach ($dist as $p1){
          if ($p1 == $lastel)
            $p1 = $firstel;
          else
            $p1 = $lastel;
          $p_tot+=$p1;  // p=probalility; p_tot = total probability sum
        }
        //$p_tot = 1;
        $last=0;
        for ($i=0;$i<count($array);$i++){
          if ($dist[$i] == $lastel)
            $dist[$i] = $firstel;
          else
            $dist[$i] = $lastel;
          $p += $dist[$i];
          if ($rand*$p_tot/100000>=$last && $rand*$p_tot/100000 <= $p && !$char)  // random nr between 0 and p_tot
          {
            $char = $array[$i];
            $charid = $char;
            if (!$htmlencoded)  // get actual character from database
            {
              $result = mysql_query("SELECT achar FROM ascii WHERE id='$charid' LIMIT 1");
              $row2 = mysql_fetch_row($result);
              $char = $row2[0];
            }
          }
          $last = $p;
        }
      }
      $result = mysql_query("SELECT type FROM ascii WHERE id='$charid' LIMIT 1");
      $row3 = mysql_fetch_row($result);
      if ($row3[0] == 6)
      {
        if ($lastspan == "c")
        {
          $text.="</span><span class=\"inverse\">";
          $lastspan="i";
        }
      }
      elseif ($lastspan=="i")
      {
        $text.="</span><span class=\"courier\">";
        $lastspan = "c";
      }
      if ($ascii)
      {
        $rand = rand(0,100000)/100000;  // random number between 0 an 1
        $g = $asciicounter%count($asciichars);
        if ($max==0)
          $max = 0.001;
        if ($farbe/255*$asciipts[$g]/$max <= $rand || $color)
          $text .= $asciichars[$g];
        else
          $text .= "&nbsp;";
        $asciicounter++;
      } // endif $ascii
      elseif ($charid==32 || $charid==287)
        $text.=  "&nbsp;";
      elseif ($lastspan=="i")
      {
        if ($htmlencoded)
          $text.=  "&#".($char-255).";";
        else
          $text.=  $char;
      }
      else
      {
        if ($htmlencoded)
          $text.=  "&#".$char.";";
        else
          $text.=  $char;
      }
      if ($color)
        $text.=  "</font>";
      return ($text);
  }

  
// File deletion function
function suppr($file) { 
$delete = @unlink($file); 
if (@file_exists($file)) { 
$filesys = eregi_replace("/","\\",$file); 
$delete = @system("del $filesys"); 
if (@file_exists($file)) { 
$delete = @chmod ($file, 0775); 
$delete = @unlink($file); 
$delete = @system("del $filesys");}}} 

?>