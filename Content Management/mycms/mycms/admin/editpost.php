<?
include("conn.php");
require_once("cms.php");


$position = cmenupos($change,$position,$menu);
//echo $position;

//$name = strtolower($name);
$name  = ucfirst($name);

$catpos = catpos($cat);

if($cat == "home"){
$catplc = "home";
}else {
$catplc = catplace($cat);
}

//if news is no patch page summary
if($news == "no") {
$showsum = "[no pages]";
}



$sql = "UPDATE menu SET name ='$name',cat = '$cat', placement ='$catplc',enabled = '$enabled',news = '$news' ,show_n_page ='$showsum' ,poll = '$poll',position = '$position',catposition = '$catpos' WHERE id='$id'";
$query = mysql_query($sql) or die("Cannot update record.<br>" . mysql_error());

//show news info


if($news == "yes") {
$rpt = "show on $showsum page";

} else {

$rpt = " ";

}



//update entry

switch ($type) {

case "home":

$del = "none";

if($_POST['rmove']) {

foreach($_POST['rmove'] as $value) {
$sql2 = "UPDATE $type SET $value ='$del' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());
}//end if

}//end if


if($img1){

copy($_FILES['img1']['tmp_name'], "../images/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];

$sql7 = "UPDATE $type SET img1 ='$img1' WHERE id='$id'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());

}else{

//}
}// end if

if($img2) {
copy($_FILES['img2']['tmp_name'], "../images/".$_FILES['img2']['name']);
$img2 = $_FILES['img2']['name'];

$sql6 = "UPDATE $type SET img2 ='$img2' WHERE id='$id'";
$query6 = mysql_query($sql6) or die("Cannot update record.<br>" . mysql_error());

}else{


}//end if

if($img3) {

copy($_FILES['img3']['tmp_name'], "../images/".$_FILES['img3']['name']);
$img3 = $_FILES['img3']['name'];

$sql3 = "UPDATE $type SET img3 ='$img3' WHERE id='$id'";
$query3 = mysql_query($sql3) or die("Cannot update record.<br>" . mysql_error());

}else{


}//end if


if($img4) {

copy($_FILES['img4']['tmp_name'], "../images/".$_FILES['img4']['name']);
$img4 = $_FILES['img4']['name'];

$sql4 = "UPDATE $type SET img4 ='$img4' WHERE id='$id'";
$query4 = mysql_query($sql4) or die("Cannot update record.<br>" . mysql_error());

}else{


}//end if


if(!$lnk1){
$lnk1 = "#";
}//end if

if(!$lnk2){
$lnk2 = "#";
}//end if

if(!$lnk3){
$lnk3 = "#";
}//end if

if(!$lnk4){
$lnk4 = "#";
}//end if


$sql2 = "UPDATE $type SET title ='$title',content ='$content',heading ='$heading', lnk1 ='$lnk1', lnk2 ='$lnk2', lnk3 ='$lnk3', lnk4 ='$lnk4'  WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

break;













case "content":

$del = "none";

if($_POST['rmove']) {

foreach($_POST['rmove'] as $value) {
$sql2 = "UPDATE $type SET $value ='$del' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());
}//end if

}//end if


if($img1){

copy($_FILES['img1']['tmp_name'], "../images/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];

$sql7 = "UPDATE $type SET img1 ='$img1' WHERE id='$id'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());

}else{

//}
}// end if

if($img2) {
copy($_FILES['img2']['tmp_name'], "../images/".$_FILES['img2']['name']);
$img2 = $_FILES['img2']['name'];

$sql6 = "UPDATE $type SET img2 ='$img2' WHERE id='$id'";
$query6 = mysql_query($sql6) or die("Cannot update record.<br>" . mysql_error());

}else{


}//end if

if($img3) {

copy($_FILES['img3']['tmp_name'], "../images/".$_FILES['img3']['name']);
$img3 = $_FILES['img3']['name'];

$sql3 = "UPDATE $type SET img3 ='$img3' WHERE id='$id'";
$query3 = mysql_query($sql3) or die("Cannot update record.<br>" . mysql_error());

}else{


}//end if


if(!$lnk1){
$lnk1 = "#";
}//end if

if(!$lnk2){
$lnk2 = "#";
}//end if

if(!$lnk3){
$lnk3 = "#";
}//end if

$sql2 = "UPDATE $type SET title ='$title', heading = '$heading', content ='$content', lnk1 ='$lnk1', lnk2 ='$lnk2', lnk3 ='$lnk3' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

break;

case "contact":


$del = "none";

if($_POST['rmove']) {

foreach($_POST['rmove'] as $value) {
$sql2 = "UPDATE $type SET $value ='$del' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());
}//end if

}//end if



if($img1){

copy($_FILES['img1']['tmp_name'], "../images/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];

$sql7 = "UPDATE $type SET img1 ='$img1' WHERE id='$id'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());

}else{

//}
}// end if

if(!$lnk1){
$lnk1 = "#";
}//end if


$sql2 = "UPDATE $type SET title ='$title', email = '$email',  content ='$content',lnk1 ='$lnk1' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

break;

case "registration":

$sql2 = "UPDATE $type SET title ='$title', email = '$email',  content ='$content' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

break;



  
}


$sql3 = "SELECT * FROM menu  WHERE id ='$id'";
$query3 = mysql_query($sql3) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query3)) {
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
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body onunload="opener.location=('mainf.php')">
<center>

<table width="90%" border="0" cellSpacing=1 class=catTbl mm_noconvert="TRUE">
 <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Menu Name</td>
 <td width="81%" align = "left"><?=$name?></td>
  </tr>
 
 <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Page Type</td>
 <td width="81%" align = "left"><?=$type?></td>
  </tr>

 <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left" class = "leftform">Menu Placement</td>
 <td width="81%" align = "left"><b>[ <?=$placement?> ]
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Show on Menu</td>
 <td width="81%" align = "left"><?=$enabled?> </td>
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">News Summary</td>
 <td width="81%" align = "left"><?=$news?> (news summary)&nbsp; <?=$rpt?></td>
  </tr>

<tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Show Poll No.</td>
 <td width="81%" align = "left"><?=$poll?> (Show poll number )</td>
  </tr>
  
  <tr bgcolor="#FFFFFF">
 <td width="19%" align = "left"  class = "leftform">Menu Category</td>
 <td width="81%" align = "left"><?=$cat?> </td>
  </tr>

</table>
<br>



<?

sptype($type,$id);

?>



</center>

<br>
</body>
</html>

