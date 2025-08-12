<?

//ob_start();
include("conn.php");
require_once("cms.php");


switch ($type) {
case 'content':
global $img1,$img2,$img3;

if($img1){

copy($_FILES['img1']['tmp_name'], "../images/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];


}//end if


if($img2) {
copy($_FILES['img2']['tmp_name'], "../images/".$_FILES['img2']['name']);
$img2 = $_FILES['img2']['name'];

//}else{
//$img2 = "";

}//end if




if($img3) {

copy($_FILES['img3']['tmp_name'], "../images/".$_FILES['img3']['name']);
$img3 = $_FILES['img3']['name'];

//}else{
//img3 = "";

}//end if


if(!$img1){

$img1 = "none";
}

if(!$img2){

$img2 = "none";
}

if(!$img3){
$img3 = "none";
}



if(!$lnk1){
$lnk1 = "#";
}//end if

if(!$lnk2){
$lnk2 = "#";
}//end if

if(!$lnk3){
$lnk3 = "#";
}//end if

$sql7 = "INSERT INTO content SET  id ='$id', title ='$title', img1= '$img1',img2= '$img2',img3= '$img3', lnk1 ='$lnk1', lnk2 ='$lnk2', lnk3 ='$lnk3', heading = '$heading', content ='$content'";
$query7 = mysql_query($sql7) or die("Cannot query the database.<br>" . mysql_error());

break;

case 'contact':

if($img1){

copy($_FILES['img1']['tmp_name'], "../images/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];


}//end if

if(!$img1){

$img1 = "none";
}

if(!$lnk1){
$lnk1 = "#";
}//end if



$sql8 = "INSERT INTO contact SET  id ='$id', title ='$title', email ='$email',img1= '$img1',lnk1 ='$lnk1', content ='$content'";
$query8 = mysql_query($sql8) or die("Cannot query the database.<br>" . mysql_error());

break;


case 'registration':

$sql8 = "INSERT INTO registration SET  id ='$id', title ='$title', email ='$email', content ='$content'";
$query8 = mysql_query($sql8) or die("Cannot query the database.<br>" . mysql_error());

break;


}



$sql2 = "SELECT * FROM menu  WHERE id ='$id'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$name = stripslashes($result["name"]);
$cat = stripslashes($result["cat"]);
$placement = stripslashes($result["placement"]);
$news = stripslashes($result["news"]);
$poll = stripslashes($result["poll"]);
$enabled = stripslashes($result["enabled"]);
}

?>


<HTML>
<HEAD>
<TITLE>Content Management </TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
<center>

<table width="90%" border="0" cellSpacing=1 class=catTbl mm_noconvert="TRUE">
 <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Menu Name</td>
 <td width="81%" align = "left"><b></b><?=$name?></b></td>
  </tr>
 
 <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Page Type</td>
 <td width="81%" align = "left" bgcolor="#F9F0CC"><?=$type?></td>
  </tr>

 <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left" class = "leftform">Menu Placement</td>
 <td width="81%" align = "left" bgcolor="#EAFDEB"><b>[ <?=$placement?> ]
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Show on Menu</td>
 <td width="81%" align = "left"><?=$enabled?> </td>
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">News Summary</td>
 <td width="81%" align = "left"><?=$news?> (news summary)</td>
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Show Poll No.</td>
 <td width="81%" align = "left"><?=$poll?> (Show poll number )</td>
  </tr>
</td>
</table>
<br>



<?

sptype($type,$id);

?>



</center>

<br>
</body>
</html>
