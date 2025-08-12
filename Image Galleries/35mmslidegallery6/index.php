<?php
////////////////////////////////////////
//                                                              
//               35mm Slide Gallery 6.0               
//                                                             
//                          by                               
//                                                             
//    www.andymack.com/freescripts/     
////////////////////////////////////////

$imgdir = $_GET['imgdir'] ; 
$page = $_GET['page'];
$a_img = array();

include("header.inc");
require('config.php');

if ($rollover)
{
include('rollover.txt');
}

///// for captioning
function caption($filename) {
   $is_captioned = check_perms($filename);
    if ($is_captioned) {
print"<br><font face='Arial, Helvetica, sans-serif' size=2 color='#999999'>";
      include($filename);
print"</font>";
    }
}

///// for album description
function album($filename) {
   $is_captioned = check_perms($filename);
    if ($is_captioned) {
print"<font face='Arial, Helvetica, sans-serif' size=3 color='#cccccc'>";
      include($filename);
print"</font><br>";
    }
}


////check file permission
function check_perms($filename) {
	
if (! file_exists($filename)) return false;
	
  $fileperms = fileperms($filename);
  $isreadable = $fileperms & 4;
  if ( is_file($filename) ) {
    // pictures, thumbnails, config files and comments only need to be readable
    if (! $isreadable) {
      if (MODE_WARNING) print "$filename: wrong permission <br>";
    }
    return $isreadable;	
  }
  else if ( is_dir($filename) ) {
    // galleries need to be both readable and executable
    $isexecutable = $fileperms & 1;
    if (! $isreadable || ! $isexecutable)
      if (MODE_WARNING) print "$filename: wrong permission <br>";
    return ( $isreadable && $isexecutable); // ($dirperms & 5) == 5 ?
  }
  
  // default behavior: the filename does not exist
  return false;
}


$dh = opendir($dir);
 while($file = readdir($dh))
 {
if ($file != "." && $file != ".." && is_dir($file))   
{$dname[] = $file;
sort($dname);
reset ($dname);
 }
}


print "<script language=\"JavaScript\">";
print "function MM_jumpMenu(targ,selObj,restore){eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");";
print "  if (restore) selObj.selectedIndex=0;}";
print "</script>";
print "<form name=\"form1\">";
print "<select name=\"menu1\" onChange=\"MM_jumpMenu('parent',this,0)\">";
print "<option value=\"#\">Go to...</option><br>\n";
$u=0;
 foreach($dname as $key=>$val)
  {  if($dname[$u])   
{ print "<option value=\"index.php?imgdir=$dname[$u]\">$dname[$u]</option>\n";
$u++;
}
}
print "</select>";


if ($imgdir =="")
{$imgdir = $dname[0];
}

$dimg = opendir($imgdir);
 while($imgfile = readdir($dimg))
 {
 if( (substr($imgfile,-3)=="gif") || (substr($imgfile,-3)=="jpg")  || (substr($imgfile,-3)=="JPG") || (substr($imgfile,-3)=="GIF")  )
 {
   $a_img[count($a_img)] = $imgfile;
sort($a_img);
reset ($a_img);
 } 
}


print "<h2>$imgdir</h2>";

 $totimg = count($a_img); // total images number
 $totxpage = $col*$maxrow; // images x page
 $totpages = ($totimg%$totxpage==0)?((int)$totimg/$totxpage):((int)($totimg/$totxpage)+1); // number of total pages

 if($totimg == false)
   print "<br><font size=2 face=verdana>No Images available in your \"IMAGES\" directory yet!!</font><br>";
 else
 {


print "</form>";

///print album description
$album_name = "$imgdir/album.txt";
album($album_name);


print "<center><table width=700 bgcolor=#474747 border=0 bordercolor=#ffffff cellpadding=2 cellspacing=3>\n";

  // start page
  if($page=="" || $page==1)
  {
   $x=0;
   $page = 1;
  }
  else
   $x = (($page-1)*($totxpage));
  $r=0;

  // print of table
  foreach($a_img as $key=>$val)
  {



$caption_name = "$imgdir/$a_img[$x].txt";

   if(($x%$col)==0)
    print "<tr>\n";
   if($a_img[$x])
   {
$size = getimagesize ("$imgdir/$a_img[$x]");
$halfw = round($size[0]/2);
$halfh = round($size[1]/2);
$quarterw = round($size[0]/4);
$quarterh = round($size[1]/4);



if($size[1] < $size[0])
{
    $height = 86;
    $width = 130;
    $imgnumber = ($x+1);
    if("$imgdir/$a_img[$x]" !="")

if ($thumb){
$thumbnail = "thumbs.php?image=$imgdir/$a_img[$x]&newheight=86&newwidth=130&width=$size[0]&height=$size[1]";
}
else 
{
$thumbnail =  "$imgdir/$a_img[$x]";
}

print "<td align=center valign=top>";
print "<TABLE WIDTH=198 BORDER=0 CELLPADDING=0 CELLSPACING=0>";
print "<TR><TD COLSPAN=3><IMG SRC=\"$place/slide_01.gif\" WIDTH=198 HEIGHT=47></TD></TR>";
print "<TR><TD><IMG SRC=\"$place/slide_02.gif\" WIDTH=33 HEIGHT=86></TD>";
print "<TD><a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$size[0]&h=$size[1]&t=$imgdir $imgnumber','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\"><img src=\"$thumbnail\" height=$height width=$width border=0 alt='$a_img[$x]' style=\"filter:alpha(opacity=100)\" onmouseout=\"gradualfade(this,100,30,4)\" onmouseover=\"gradualfade(this,40,50,100)\"></a></TD>";
print "<TD><IMG SRC=\"$place/slide_04.gif\" WIDTH=35 HEIGHT=86></TD></TR><TR>";
print "<TD COLSPAN=3><IMG SRC=\"$place/slide_05.gif\" WIDTH=198 HEIGHT=56><br><font size=\"1\"><a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$size[0]&h=$size[1]&t=$imgdir $imgnumber','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">$size[0] x $size[1]</a> | <a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$halfw&h=$halfh&t=$imgdir $imgnumber','$x','width=$halfw,height=$halfh,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">1/2</a> | <a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$quarterw&h=$quarterh&t=$imgdir $imgnumber','$x','width=$quarterw,height=$quarterh,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">1/4 size</a></font>";
caption($caption_name);
print "</TD></TR>";
print "</TABLE></center>";
print "</td>\n";
}
else
{    $height = 130;
    $width = 86;

if ($thumb){
$thumbnail = "thumbs.php?image=$imgdir/$a_img[$x]&newheight=130&newwidth=86&width=$size[0]&height=$size[1]";
}
else 
{
$thumbnail =  "$imgdir/$a_img[$x]";
}

 $imgnumber = ($x+1);
    if("$imgdir/$a_img[$x]" !="")
print "<td align=center valign=top>";
print "<TABLE WIDTH=198 BORDER=0 CELLPADDING=0 CELLSPACING=0>";
print "<TR><TD COLSPAN=3><IMG SRC=\"$place/slidev_01.gif\" WIDTH=198 HEIGHT=28></TD></TR>";
print "<TR><TD><IMG SRC=\"$place/slidev_02.gif\" WIDTH=56 HEIGHT=130></TD>";
print "<TD><a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$size[0]&h=$size[1]&t=$imgdir $imgnumber','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\"><img src=\"$thumbnail\" height=$height width=$width border=0 alt='$a_img[$x]' style=\"filter:alpha(opacity=100)\" onmouseout=\"gradualfade(this,100,30,4)\" onmouseover=\"gradualfade(this,40,50,100)\"></a></TD>";
print "<TD><IMG SRC=\"$place/slidev_04.gif\" WIDTH=56 HEIGHT=130></TD></TR><TR>";
print "<TD COLSPAN=3><IMG SRC=\"$place/slidev_05.gif\" WIDTH=198 HEIGHT=31><br><font size=\"1\"><a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$size[0]&h=$size[1]&t=$imgdir $imgnumber','$x','width=$size[0],height=$size[1],directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">$size[0] x $size[1]</a> | <a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$halfw&h=$halfh&t=$imgdir $imgnumber','$x','width=$halfw,height=$halfh,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">1/2</a> | <a href='#' onclick=\"window.open('popup.php?img=$imgdir/$a_img[$x]&w=$quarterw&h=$quarterh&t=$imgdir $imgnumber','$x','width=$quarterw,height=$quarterh,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no');return false\" target=\"_blank\">1/4 size</a></font>";
caption($caption_name);
print "</TD></TR>";
print "</TABLE>";
print "</td>\n";
}   
}

   if(($x%$col) == ($col-1))
   {
    print "</tr>\n";
    $r++;
   }
  // print "r=$r - maxrow=$maxrow<br>";
   if($r==$maxrow)
   {
    break;
   }
   else
   $x++;
  }
  print "</table>\n";
 }
 // page break
 


$imgdir = str_replace(" ", "%20", $imgdir); 

//page number
print "<p><font size=2 face=verdana>";
 if($totimg>$totxpage)
 {
  if($totpages>$page)
  {
   $next = $page+1;
   $back = ($page>1)?($page-1):"1";
   if($page>1)
   {
    $back = $page-1;
    print "<a href=index.php?imgdir=$imgdir&page=1>first page</a> | <a href=index.php?imgdir=$imgdir&page=$back><< back </a>";
   }
   print " &nbsp;&nbsp; <b>page $page of $totpages</b> &nbsp;&nbsp;<a href=index.php?imgdir=$imgdir&page=$next>next >></a> | <a href=index.php?imgdir=$imgdir&page=$totpages>last page</a>";
  }
  else
  {
   $next = (($page-1)==0)?"1":($page-1);
   print "<a href=index.php?imgdir=$imgdir&page=1>first page</a> | <a href=index.php?imgdir=$imgdir&page=$next><< back</a>&nbsp;&nbsp; <b>page $page of $totpages</b> &nbsp;&nbsp;";

print "</center>";
  }
 }
include("footer.inc");
?>
