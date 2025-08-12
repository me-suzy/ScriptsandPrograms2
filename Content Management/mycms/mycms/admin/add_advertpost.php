<?
include("conn.php");


//if(!$sitew){
//$sitew = 0;
//}
//echo $name;

if ($send == "image" ){


copy($_FILES['img1']['tmp_name'], "../adverts/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];

$imagesize = getimagesize($_FILES['img1']['tmp_name']);
$imagewidth = $imagesize[0]; // get width
$imageheight = $imagesize[1]; // get height

//echo "$imagewidth $imageheight"; 


$coding = "<a href=\"$link\"><img src=adverts/$img1 alt = \"advert\" border = \"0\"></a>";
$show = "<a href=\"$link\"><img src=../adverts/$img1 alt = \"advert\" border = \"0\"></a>";
//$show = "tes5";


$sql7 = "INSERT INTO adverts SET  pid ='$pid', gname = '$coding', pshow='$show', adtype = '$adtype'";
$query7 = mysql_query($sql7) or die("Cannot query the database.<br>" . mysql_error());

//echo " IMAGE ADVRT added";

}//



if ($send == "flash" ){


copy($_FILES['flashfile']['tmp_name'], "../adverts/".$_FILES['flashfile']['name']);
$img1 = $_FILES['flashfile']['name'];

$imagesize = getimagesize($_FILES['flashfile']['tmp_name']);
$imagewidth = $imagesize[0]; // get width
$imageheight = $imagesize[1]; // get height

echo $img1;

$coding = "
<OBJECT CLASSID=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"
 CODEBASE=\"http://active.macromedia.com
      /flash2/cabs/swflash.cab#version=4,0,0,0\"
 WIDTH=$imagewidth HEIGHT=$imageheight>
 <PARAM NAME=\"MOVIE\" VALUE=\"adverts/$img1\">
 <PARAM NAME=\"QUALITY\" VALUE=\"HIGH\">
 <PARAM NAME=\"PLAY\" VALUE=\"TRUE\">
 <PARAM NAME=\"LOOP\" VALUE=\"TRUE\">
 <PARAM NAME=\"BGCOLOR\" VALUE=\"#FFFFFF\">
   <EMBED SRC=\"adverts/$img1\" QUALITY=\"HIGH\" BGCOLOR=\"#FFFFFF\" 
      WIDTH=$imagewidth HEIGHT=$imageheight
      TYPE=\"application/x-shockwave-flash\"
      PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/
          index.cgi?P1_Prod_Version=ShockwaveFlash\">
   </EMBED>
</OBJECT>";


$show = "

<OBJECT CLASSID=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\"
 CODEBASE=\"http://active.macromedia.com
      /flash2/cabs/swflash.cab#version=4,0,0,0\"
 WIDTH=$imagewidth HEIGHT=$imageheight>
 <PARAM NAME=\"MOVIE\" VALUE=\"../adverts/$img1\">
 <PARAM NAME=\"QUALITY\" VALUE=\"HIGH\">
 <PARAM NAME=\"PLAY\" VALUE=\"TRUE\">
 <PARAM NAME=\"LOOP\" VALUE=\"TRUE\">
 <PARAM NAME=\"BGCOLOR\" VALUE=\"#FFFFFF\">
   <EMBED SRC=\"../adverts/$img1\" QUALITY=\"HIGH\" BGCOLOR=\"#FFFFFF\" 
      WIDTH=$imagewidth HEIGHT=$imageheight
      TYPE=\"application/x-shockwave-flash\"
      PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/
          index.cgi?P1_Prod_Version=ShockwaveFlash\">
   </EMBED>
</OBJECT>";




//$show = "tes5";


$sql7 = "INSERT INTO adverts SET  pid ='$pid', gname = '$coding', pshow='$show', adtype = '$adtype'";
$query7 = mysql_query($sql7) or die("Cannot query the database.<br>" . mysql_error());

//echo "FLASH avt added ";

}//

if ($send == "code" ){

$sql7 = "INSERT INTO adverts SET  pid ='$pid', gname = '$code', pshow='$code', adtype = '$adtype'";
$query7 = mysql_query($sql7) or die("Cannot query the database.<br>" . mysql_error());

//echo "Coded added ";


}


$str = "onunload=\"opener.location=('sponsors.php?pid=$pid&name=$name')\" ";

?>











<html>
<head>
<title>Advert Added</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body <? echo $str; ?>>
<form method="post">
    <div align="center">
<font size="2" face="Arial, Helvetica, sans-serif"><img src="tick.jpg" width="99" height="99"><br>Your 
Item has been added succesfuly</font> <br>
      <input type="button" value="Close Window" 
onclick="window.close()">
    </div>
  </form>

</body>
</html>
