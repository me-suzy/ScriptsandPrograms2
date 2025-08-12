<?
ob_start();
include("conn.php");

// show news items

function gal($id, $action, $name, $gid, $heading, $title, $content, $rmove, $mimage, $s_image1, $s_image2, $s_image3, $select) {
global $id, $host, $username, $password, $database;

switch ($action) {
case 'show':

global $id;

$paging=new paging(10,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM gallery WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )


</td>
</table><br>";



echo " <table width='100%' border='1' cellpadding='1' cellspacing='1' >";
echo "<tr bgcolor='FFFFFF'>";

 //while($result = mysql_fetch_array($query9)) {
$row = 0;
$icount = 1;
while($result=$paging->result_assoc()) {
$gid = stripslashes($result["gid"]);
$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$title = substr($itemtitle, 0,30);

if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='../galimages/$mimage'>";
}


//if($icount == 2) {
if(($icount % 2) == 0) {
$brk = "</tr><tr>";
 //echo $row;
}else {
$brk = "";
}

echo "<td width='70%' align = 'center' valign = 'middle'  class = 'leftform' bgcolor='#ffffff'>$path1<br>$title <br><a href='modifygal.php?action=modify&gid=$gid&id=$id&name=$name&select=$select'>edit</a> | <a href='modifygal.php?action=delete&gid=$gid&id=$id&name=$name&select=$select'>delete</a></td>";
echo $brk;


 $row = $row + 1 ;
  $icount = $icount + 1 ;
}// end while

echo "</tr></table><br>";
echo $paging->print_link();

break;





case 'modify':


$sql9 = "SELECT * FROM gallery WHERE gid = '$gid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {
$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$s_image1 = stripslashes($result["s_image1"]);
$s_image2 = stripslashes($result["s_image2"]);
$s_image3 = stripslashes($result["s_image3"]);
$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);


echo "<form name='input' action='modifygal.php' method='post' enctype='multipart/form-data'>

<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Title</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='title' value = '$itemtitle'  style='background-color:#FFFFD7' size=45></td>
  </tr>

    <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Heading</b></td>
  </tr>

<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='3'><textarea name='heading' style='width:595; height:100'>$heading</textarea></td>
</tr>

<tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'><b>Main Image</b></td>
    <td width='60%' align = 'left'><input type= 'file' name= 'mimage' size='15'>
      &nbsp; [$mimage] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='m_image'>
      remove</td>
  </tr>

  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 1</td>
    <td width='60%' align = 'left'><input type= 'file' name= 's_image1' size='15'>
      &nbsp; [$s_image1] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='s_image1'>
      remove</td>
  </tr>   
  
   <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 2</td>
    <td width='60%' align = 'left'><input type= 'file' name= 's_image2' size='15'>
      &nbsp; [$s_image2] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='s_image2'>
      remove</td>
  </tr>

   <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 3</td>
    <td width='60%' align = 'left'><input type= 'file' name= 's_image3' size='15'>
      &nbsp; [$s_image3] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove[]' value='s_image3'>
      remove</td>
  </tr>


    <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='3'><textarea name='content' style='width:640; height:450'>$content</textarea></td>
  </tr>
  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='3' align = 'center' height = '30'><input type='submit' value='update >>'>
	    <input type='hidden' name='id' value = '$id'>
	     <input type='hidden' name='gid' value = '$gid'>
	    <input type='hidden' name='name' value = '$name'>
	    <input type='hidden' name='select' value = '$select'>
	     <input type='hidden' name='action' value = 'update'>
		</tr>

  </table>
    </form>

  ";

}



break;


case 'update':


//check deleted

$del = "none";

if($_POST['rmove']) {

foreach($_POST['rmove'] as $value) {
$sql2 = "UPDATE gallery SET $value ='$del' WHERE gid='$gid'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());
}//end if

}//end if



//upload

if($mimage){

copy($_FILES['mimage']['tmp_name'], "../galimages/".$_FILES['mimage']['name']);
$mimage = $_FILES['mimage']['name'];

$sql7 = "UPDATE gallery SET m_image ='$mimage' WHERE gid='$gid'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());
}else{

}// end if


if($s_image1){

copy($_FILES['s_image1']['tmp_name'], "../galimages/".$_FILES['s_image1']['name']);
$s_image1 = $_FILES['s_image1']['name'];

$sql7 = "UPDATE gallery SET s_image1 ='$s_image1' WHERE gid='$gid'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());
}else{

}// end if


if($s_image2){

copy($_FILES['s_image2']['tmp_name'], "../galimages/".$_FILES['s_image2']['name']);
$s_image2 = $_FILES['s_image2']['name'];

$sql7 = "UPDATE gallery SET s_image2 ='$s_image2' WHERE gid='$gid'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());
}else{

}// end if


if($s_image3){

copy($_FILES['s_image3']['tmp_name'], "../galimages/".$_FILES['s_image3']['name']);
$s_image3 = $_FILES['s_image3']['name'];

$sql7 = "UPDATE gallery SET s_image3 ='$s_image3' WHERE gid='$gid'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());
}else{

}// end if



//update gallery
$sql = "UPDATE gallery SET title ='$title', heading ='$heading',content = '$content' WHERE gid='$gid'";
$query = mysql_query($sql) or die("Cannot update record.<br>" . mysql_error());


$sql9 = "SELECT * FROM gallery WHERE gid = '$gid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {

$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$s_image1 = stripslashes($result["s_image1"]);
$s_image2 = stripslashes($result["s_image2"]);
$s_image3 = stripslashes($result["s_image3"]);
$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);


if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='../galimages/$mimage'>";
}

if($s_image1 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../galimages/$s_image1'>";
}

if($s_image2 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../galimages/$s_image2'>";
}


if($s_image3 == "none"){
$path4 = "";
} else {
$path4 = "<img src='../galimages/$s_image3'>";
}



}//end while


echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Story Title</td>
    <td width='75%' align = 'left'>$itemtitle</td>
  </tr>

  <tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Heading</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'>$heading</td>
      </tr>

 <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'><b>Main Image</b></td>
 <td width='85%' align = 'left'>$path1</td>
 </tr>
 
  <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 1</td>
 <td width='85%' align = 'left'>$path2</td>
 </tr>
 
   <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 2</td>
 <td width='85%' align = 'left'>$path3</td>
 </tr>
 
   <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 3</td>
 <td width='85%' align = 'left'>$path4</td>
 </tr>

 <tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'>$content</td>
      </tr>

<tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'>
      <b>content updated</b>
	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
  </tr>
  </table>";





break;



case 'delete':


$sql9 = "delete FROM gallery WHERE gid = '$gid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

 $paging=new paging(10,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM gallery WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )


</td>
</table><br>";



echo " <table width='100%' border='1' cellpadding='1' cellspacing='1' >";
echo "<tr bgcolor='FFFFFF'>";

 //while($result = mysql_fetch_array($query9)) {
$row = 0;
$icount = 1;
while($result=$paging->result_assoc()) {
$gid = stripslashes($result["gid"]);
$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$title = substr($itemtitle, 0,30);

if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='../galimages/$mimage'>";
}


//if($icount == 2) {
if(($icount % 2) == 0) {

$brk = "</tr><tr>";
 //echo $row;
}else {
$brk = "";
}

echo "<td width='70%' align = 'left'  class = 'leftform' bgcolor='#ffffff'>$path1<center><br>$title <br><a href='modifygal.php?action=modify&gid=$gid&id=$id&name=$name&select=$select'>edit</a> | <a href='modifygal.php?action=delete&gid=$gid&id=$id&name=$name&select=$select'>delete</a></center></td>";
echo $brk;


 $row = $row + 1 ;
  $icount = $icount + 1 ;
}// end while

echo "</tr></table><br>";
echo $paging->print_link();



break;





case 'view':
global $id;


$paging=new paging(10,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM gallery WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )


</td>
</table><br>";



echo " <table width='100%' border='1' cellpadding='1' cellspacing='1' >";
echo "<tr bgcolor='FFFFFF'>";

 //while($result = mysql_fetch_array($query9)) {
$row = 0;
$icount = 1;
while($result=$paging->result_assoc()) {
$gid = stripslashes($result["gid"]);
$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$title = substr($itemtitle, 0,30);

if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='../galimages/$mimage'>";
}


//if($icount == 2) {
if(($icount % 2) == 0) {

$brk = "</tr><tr>";
 //echo $row;
}else {
$brk = "";
}

echo "<td width='70%' align = 'center' valign = 'middle' class = 'leftform' bgcolor='#ffffff'>$path1<br>$title <br><a href='modifygal.php?action=viewgal&gid=$gid&id=$id&name=$name&select=$select'>view</a></td>";
echo $brk;


 $row = $row + 1 ;
  $icount = $icount + 1 ;
}// end while

echo "</tr></table><br>";
echo $paging->print_link();


break;


case 'viewgal':

$sql9 = "SELECT * FROM gallery WHERE gid = '$gid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {

$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$s_image1 = stripslashes($result["s_image1"]);
$s_image2 = stripslashes($result["s_image2"]);
$s_image3 = stripslashes($result["s_image3"]);
$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);


if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='../galimages/$mimage'>";
}

if($s_image1 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../galimages/$s_image1'>";
}

if($s_image2 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../galimages/$s_image2'>";
}


if($s_image3 == "none"){
$path4 = "";
} else {
$path4 = "<img src='../galimages/$s_image3'>";
}



}//end while


echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Story Title</td>
    <td width='75%' align = 'left'>$itemtitle</td>
  </tr>

  <tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Heading</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'>$heading</td>
      </tr>

 <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'><b>Main Image</b></td>
 <td width='85%' align = 'left'>$path1</td>
 </tr>
 
  <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 1</td>
 <td width='85%' align = 'left'>$path2</td>
 </tr>
 
   <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 2</td>
 <td width='85%' align = 'left'>$path3</td>
 </tr>
 
   <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 3</td>
 <td width='85%' align = 'left'>$path4</td>
 </tr>

 <tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'>$content</td>
      </tr>

<tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'>

	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
  </tr>
  </table>";




break;



 case 'add':


echo "<form name='input' action='modifygal.php' method='post' enctype='multipart/form-data'>

<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Title</td>
    <td width='75%'  align = 'left'><input type='text' name='title' value = ''  style='background-color:#FFFFD7' size=45></td>
  </tr>

    <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Heading</b></td>
  </tr>

<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='2'><textarea name='heading' style='width:595; height:100'>$heading</textarea></td>
</tr>

<tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'><b>Main Image</b></td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name= 'mimage' size='15'> 
       </td>

  </tr>

  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 1</td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name= 's_image1' size='15'>
      </td>

  </tr>   
  
   <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 2</td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name= 's_image2' size='15'>
       </td>

  </tr>

   <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image 3</td>
    <td width='60%' align = 'left' class = 'cmsdem'><input type= 'file' name= 's_image3' size='15'>
      </td>

  </tr>


    <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'><textarea name='content' style='width:640; height:450'>$content</textarea></td>
  </tr>
  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'><input type='submit' value='Submit >>'>
	    <input type='hidden' name='id' value = '$id'>
	     <input type='hidden' name='gid' value = '$gid'>
	    <input type='hidden' name='name' value = '$name'>
	    <input type='hidden' name='select' value = '$select'>
	     <input type='hidden' name='action' value = 'addimage'>
		</tr>

  </table>
    </form>";


break;



 case 'addimage':

// upload image
if($mimage){

copy($_FILES['mimage']['tmp_name'], "../galimages/".$_FILES['mimage']['name']);
$mimage = $_FILES['mimage']['name'];

}else{

$mimage = "none";
}// end if


if($s_image1){

copy($_FILES['s_image1']['tmp_name'], "../galimages/".$_FILES['s_image1']['name']);
$s_image1 = $_FILES['s_image1']['name'];

}else{

$s_image1 = "none";

}// end if


if($s_image2){

copy($_FILES['s_image2']['tmp_name'], "../galimages/".$_FILES['s_image2']['name']);
$s_image2 = $_FILES['s_image2']['name'];
}else{
$s_image2 = "none";

}// end if


if($s_image3){

copy($_FILES['s_image3']['tmp_name'], "../galimages/".$_FILES['s_image3']['name']);
$s_image3 = $_FILES['s_image3']['name'];

}else{
$s_image3 = "none";

}// end if


$thetime = date("H.i");
$thedate = date("Ymd");


$sql6 = "INSERT INTO gallery SET id = '$id', title ='$title', m_image = '$mimage',s_image1 = '$s_image1',s_image2 = '$s_image2',s_image3 = '$s_image3', heading ='$heading', content = '$content', detime='$thetime', dedate='$thedate'";
$query6 = mysql_query($sql6) or die("Cannot query the database.<br>" . mysql_error());

$gid = mysql_insert_id();


$sql9 = "SELECT * FROM gallery WHERE gid = '$gid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {

$itemtitle = stripslashes($result["title"]);
$mimage = stripslashes($result["m_image"]);
$s_image1 = stripslashes($result["s_image1"]);
$s_image2 = stripslashes($result["s_image2"]);
$s_image3 = stripslashes($result["s_image3"]);
$heading = stripslashes($result["heading"]);
$content = stripslashes($result["content"]);


if($mimage == "none"){
$path1 = "";
} else {
$path1 = "<img src='../galimages/$mimage'>";
}

if($s_image1 == "none"){
$path2 = "";
} else {
$path2 = "<img src='../galimages/$s_image1'>";
}

if($s_image2 == "none"){
$path3 = "";
} else {
$path3 = "<img src='../galimages/$s_image2'>";
}


if($s_image3 == "none"){
$path4 = "";
} else {
$path4 = "<img src='../galimages/$s_image3'>";
}



}//end while


echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'>
    <td width='25%' align = 'left'  class = 'leftform'>Story Title</td>
    <td width='75%' align = 'left'>$itemtitle</td>
  </tr>

  <tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Heading</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'>$heading</td>
      </tr>

 <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'><b>Main Image</b></td>
 <td width='85%' align = 'left'>$path1</td>
 </tr>
 
  <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 1</td>
 <td width='85%' align = 'left'>$path2</td>
 </tr>
 
   <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 2</td>
 <td width='85%' align = 'left'>$path3</td>
 </tr>
 
   <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Image 3</td>
 <td width='85%' align = 'left'>$path4</td>
 </tr>

 <tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'>$content</td>
      </tr>

<tr bgcolor='FFFFFF'>
 <td width='100%' height ='2'  colspan = '2' align = 'center'>

	   <br>
<form method='post'>
    <div align='center' valign = 'middle'>
      <input type='button' value='Close Window' onclick='window.close()'>
    </div>
  </form>
 </td>
  </tr>
  </table>";




 break;







  }//end switch

} //end function

?>

