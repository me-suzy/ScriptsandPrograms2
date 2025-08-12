<?
ob_start();
include("conn.php");

// show news items	

function news($id, $action, $name, $nid, $heading, $title, $content, $rmove, $rmove2, $img1, $audio, $select) {
global $id, $host, $username, $password, $database;

switch ($action) {
case 'show':

$paging=new paging(10,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM news WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )


</td>
</table><br>";



echo " <table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >";
 
 //while($result = mysql_fetch_array($query9)) {
$row = 0;
while($result=$paging->result_assoc()) {
$nid = stripslashes($result["nid"]);
$itemtitle = stripslashes($result["itemtitle"]);
$title = substr($itemtitle, 0,70); 

echo "<tr bgcolor='FFFFFF'>
<td width='70%' align = 'left'  class = 'leftform' bgcolor='#E7FCFE'>$title</td>
 <td width='15%' align = 'right' bgcolor='#F9F0CC'><a href='modifynews.php?action=modify&nid=$nid&id=$id&name=$name&select=$select'>modify story</a></td>
  <td width='15%' align = 'right' bgcolor='#F9F0CC'><a href='modifynews.php?action=delete&nid=$nid&id=$id&name=$name&select=$select'>delete story</a></td>
 </tr> ";
 
 $row = $row + 1 ;
}// end while

echo "</table><br>";
echo $paging->print_link();

break;

case 'modify':

$sql9 = "SELECT * FROM news WHERE nid = '$nid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {
$itemtitle = stripslashes($result["itemtitle"]);
$img1 = stripslashes($result["img1"]);
$audio = stripslashes($result["audio"]);
$heading = stripslashes($result["itemheading"]);
$content = stripslashes($result["content"]);



echo "<form name='input' action='modifynews.php' method='post' enctype='multipart/form-data'>

<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Story Title</td>
    <td width='75%' colspan='2' align = 'left'><input type='text' name='title' value = '$itemtitle'  style='background-color:#FFFFD7' size=45></td>
  </tr>
  
    <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '3' align = 'center'><b>Heading</b></td>
  </tr>
  
<tr bgcolor='FFFFFF'> <td width='100%' height ='2'  colspan ='3'><textarea name='heading' style='width:595; height:100'>$heading</textarea></td>
</tr>

<tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image </td>
    <td width='60%' align = 'left'><input type= 'file' name= 'img1' size='15'> 
      &nbsp; [$img1] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove' value='img1'>
      remove</td>
  </tr>
  
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Audio</td>
    <td width='60%' align = 'left'><input type= 'file' name= 'audio' size='15'> 
      &nbsp; [$audio] &nbsp; </td>
    <td width='15%' align = 'left'><INPUT type='checkbox' name='rmove2' value='audio'>
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
	     <input type='hidden' name='nid' value = '$nid'>
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

if($rmove == "img1") {
$sql2 = "UPDATE news SET $rmove ='$del' WHERE nid='$nid'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());


}



if($rmove2 == "audio") {

$sql2 = "UPDATE news SET $rmove2 ='$del' WHERE nid='$nid'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());


}


//upload

if($img1){

copy($_FILES['img1']['tmp_name'], "../newsimages/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];

$sql7 = "UPDATE news SET img1 ='$img1' WHERE nid='$nid'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());

}else{

}// end if





//upload audio
if($audio){

copy($_FILES['audio']['tmp_name'], "../newsimages/".$_FILES['audio']['name']);
$audio = $_FILES['audio']['name'];

$sql7 = "UPDATE news SET audio ='$audio' WHERE nid='$nid'";
$query7 = mysql_query($sql7) or die("Cannot update record.<br>" . mysql_error());

}else{

}// end if






  $today = date("l dS of F Y h:i:s A");
//update news
$sql = "UPDATE news SET itemtitle ='$title', itemheading ='$heading',content = '$content', modified= '$today' WHERE nid='$nid'";
$query = mysql_query($sql) or die("Cannot update record.<br>" . mysql_error());


$sql9 = "SELECT * FROM news WHERE nid = '$nid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {

$itemtitle = stripslashes($result["itemtitle"]);
$img1 = stripslashes($result["img1"]);
$audio = stripslashes($result["audio"]);
$heading = stripslashes($result["itemheading"]);
$content = stripslashes($result["content"]);

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../newsimages/$img1'>";
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
 <td width='15%' align = 'left'>Image 1 </td>
 <td width='85%' align = 'left'>$path1</td>
 </tr>


 <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Audio</td>
 <td width='85%' align = 'left'>$audio</td>
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
$sql9 = "delete FROM news WHERE nid = '$nid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

 $paging=new paging(10,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM news WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )


</td>
</table><br>";



echo " <table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >";
 
 //while($result = mysql_fetch_array($query9)) {
$row = 0;
while($result=$paging->result_assoc()) {
$nid = stripslashes($result["nid"]);
$itemtitle = stripslashes($result["itemtitle"]);
$title = substr($itemtitle, 0, 70);

echo "<tr bgcolor='FFFFFF'>
<td width='70%' align = 'left'  class = 'leftform' bgcolor='#E7FCFE'>$title</td>
 <td width='15%' align = 'right' bgcolor='#F9F0CC'><a href='modifynews.php?action=modify&nid=$nid&id=$id&name=$name&select=$select'>modify story</a></td>
  <td width='15%' align = 'right' bgcolor='#F9F0CC'><a href='modifynews.php?action=delete&nid=$nid&id=$id&name=$name&select=$select'>delete story</a></td>
 </tr> ";
 
 $row = $row + 1 ;
}// end while

echo "</table><br>";
echo $paging->print_link();


break;





case 'view':

$paging=new paging(10,5);
$paging->db("$host","$username","$password","$database");


//$sql9 = "SELECT * FROM news WHERE id = '$id'";
//$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

$paging->query("SELECT * FROM news WHERE id = '$id' ORDER BY dedate DESC,detime DESC");
$page=$paging->print_info();

echo "<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE' >
<tr bgcolor='FFFFFF'>
<td width='70%' align = 'right'  class = 'leftform' bgcolor='#E7FCFE'>
Record $page[start] - $page[end] of $page[total] ( total $page[total_pages] pages )


</td>
</table><br>";



echo " <table width='100%' border='0' cellpadding='1' cellspacing='1'' class=catTbl mm_noconvert='TRUE' >";

//while($result = mysql_fetch_array($query9)) {

 $row = 0;
while($result=$paging->result_assoc()) {

$nid = stripslashes($result["nid"]);
$itemtitle = stripslashes($result["itemtitle"]);
$title = substr($itemtitle, 0, 70);

echo "<tr bgcolor='FFFFFF'>
<td width='70%' align = 'left'  class = 'leftform' bgcolor='#E7FCFE'>$title</td>
 <td width='15%' align = 'left' bgcolor='#F9F0CC'><a href='modifynews.php?action=viewstory&nid=$nid&id=$id&name=$name&select=$select'>view Story</a></td>

 </tr> ";
 
}// end while

 
echo "</table><br>";
 echo $paging->print_link();

break;


case 'viewstory':

$sql9 = "SELECT * FROM news WHERE nid = '$nid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {

$itemtitle = stripslashes($result["itemtitle"]);
$img1 = stripslashes($result["img1"]);
$heading = stripslashes($result["itemheading"]);
$content = stripslashes($result["content"]);

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../newsimages/$img1'>";
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
 <td width='15%' align = 'left'>Image 1 </td>
 <td width='85%' align = 'left'>$path1</td>
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


 echo "<form name='input' action='modifynews.php' method='post' enctype='multipart/form-data'>

<table width='100%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Story Title</td>
    <td width='75%' align = 'left'><input type='text' name='title' value = ''  style='background-color:#FFFFD7' size=45></td>
  </tr>
  
    <tr bgcolor='FFFFFF'> 
    <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Heading</b></td>
  </tr>
  
<tr bgcolor='FFFFFF'> 
<td width='100%' height ='2'  colspan ='2'><textarea name='heading' style='width:595; height:100'></textarea></td>
</tr>

<tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Image </td>
    <td width='60%' align = 'left'><input type= 'file' name= 'img1' size='15'> </td>

  </tr>
  
  
  <tr bgcolor='FFFFFF'> 
    <td width='25%' align = 'left'  class = 'leftform'>Upload Audio</td>
    <td width='60%' align = 'left'><input type= 'file' name= 'audio' size='15'> </td>

  </tr>
  
    <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan = '2' align = 'center'><b>Content</b></td>
  </tr>
  <tr bgcolor='FFFFFF'>
    <td width='100%' height ='2'  colspan ='2'><textarea name='content' style='width:640; height:450'></textarea></td>
  </tr>
  <tr bgcolor='FFFFFF'>
       <td width='75%' colspan ='2' align = 'center' height = '30'><input type='submit' value='submit>>'>
	    <input type='hidden' name='id' value = '$id'>
	     <input type='hidden' name='nid' value = '$nid'>
	    <input type='hidden' name='name' value = '$name'>
	    <input type='hidden' name='select' value = '$select'>
	     <input type='hidden' name='action' value = 'addstory'>
		</tr>

  </table>
    </form>";


break;

 
 
 case 'addstory':
 
// upload image
if($img1){

copy($_FILES['img1']['tmp_name'], "../newsimages/".$_FILES['img1']['name']);
$img1 = $_FILES['img1']['name'];

}else{

$img1 = "none";

}// end if




//upload audio
if($audio){

copy($_FILES['audio']['tmp_name'], "../newsimages/".$_FILES['audio']['name']);
$audio = $_FILES['audio']['name'];

}else{

$audio = "none";

}// end if


$thetime = date("H.i");
$thedate = date("Ymd");
$today = date("l dS of F Y h:i:s A");

$sql6 = "INSERT INTO news SET id = '$id', itemtitle ='$title', itemheading ='$heading', img1 = '$img1', audio = '$audio', content = '$content', today = '$today',detime='$thetime', dedate='$thedate'";
$query6 = mysql_query($sql6) or die("Cannot query the database.<br>" . mysql_error());

$nid = mysql_insert_id();


$sql9 = "SELECT * FROM news WHERE nid = '$nid'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query9)) {

$itemtitle = stripslashes($result["itemtitle"]);
$img1 = stripslashes($result["img1"]);
$heading = stripslashes($result["itemheading"]);
$content = stripslashes($result["content"]);

if($img1 == "none"){
$path1 = "";
} else {
$path1 = "<img src='../newsimages/$img1'>";
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
 <td width='15%' align = 'left'>Image 1 </td>
 <td width='85%' align = 'left'>$path1</td>
 </tr>
 
 
 <tr bgcolor='FFFFFF'>
 <td width='15%' align = 'left'>Audio </td>
 <td width='85%' align = 'left'>$audio</td>
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







  }//end switch

} //end function

?>

