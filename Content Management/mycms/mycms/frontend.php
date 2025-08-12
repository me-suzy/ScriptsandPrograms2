<?
ob_start();
include("conn.php");
include("inc.php");

//menu functions
//------------------------------------------------------------
// show home page


function dmenu() {

$sql = "SELECT distinct cat FROM menu WHERE deleted='0' and placement = 'home' ORDER BY catposition ASC, position ASC";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$cat = stripslashes($result["cat"]);


$sql2 = "SELECT * FROM menu WHERE cat = '$cat' and deleted='0' ORDER BY  position ASC";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);
$type = stripslashes($result["type"]);
$name = stripslashes($result["name"]);

$link_h = "page$id-$type.htm";

echo "<table border='0' cellpadding='0' cellspacing='0'  width='100%'>
	<tr>
	<td   class = 'catmenu'><a href='index.php' class = 'homelink'>$name</a></td>
	</tr>
	</table><br>";

} // end while
}
}// end function






// show primary menu
function dmenu_p() {

$sql = "SELECT distinct cat FROM menu WHERE deleted='0' and placement = 'primary' and enabled = 'yes'  ORDER BY catposition ASC, position ASC";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$cat = stripslashes($result["cat"]);


 //echo "<b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$cat</font></b><br>";
  //echo  "<span class='catmenu';'>$cat</span><br>";
  echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr>
	<td  class = 'catmenu' >$cat </td>
	</tr>
	</table>
  
  ";
 
$sql2 = "SELECT * FROM menu WHERE cat = '$cat' and deleted='0' and enabled = 'yes' ORDER BY  position ASC";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

$num_rows = mysql_num_rows($query2);
$ic = 0;

while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);
$type = stripslashes($result["type"]);
$name = stripslashes($result["name"]);

//echo "<br>";
$name = ucfirst($name); 
$ic = $ic + 1;

if($ic == $num_rows ){ 
$brkk2 = "<br>";
} else { 
$brkk2 = "";
}//end if

$link = "page$id-$type.htm";
//echo "&nbsp;&nbsp;&nbsp;<a href='index.php?id=$id&type=$type'><font size=1' face='Verdana, Arial, Helvetica, sans-serif'>$name</font></a>";
echo "&nbsp;&nbsp;&nbsp;<a href='index.php?id=$id&type=$type' class = 'menulink' >$name</a>";
echo "<br>";
echo $brkk2;

} // end while
}
}// end function



// show secondary menu
function dmenu_s() {

$sql = "SELECT distinct cat FROM menu WHERE deleted='0' and placement = 'secondary' and enabled = 'yes'  ORDER BY catposition ASC, position ASC";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$cat = stripslashes($result["cat"]);


 //echo "<b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$cat</font></b><br>";
  //echo  "<span class='catmenu';'>$cat</span><br>";
  echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr>
	<td  class = 'catmenu' >$cat </td>
	</tr>
	</table>
  
  ";
 
$sql2 = "SELECT * FROM menu WHERE cat = '$cat' and deleted='0' and enabled = 'yes' ORDER BY  position ASC";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

$num_rows = mysql_num_rows($query2);
$ic = 0;

while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);
$type = stripslashes($result["type"]);
$name = stripslashes($result["name"]);

//echo "<br>";
$name = ucfirst($name); 
$ic = $ic + 1;

if($ic == $num_rows ){ 
$brkk2 = "<br>";
} else { 
$brkk2 = "";
}//end if

$link = "page$id-$type.htm";
//echo "&nbsp;&nbsp;&nbsp;<a href='index.php?id=$id&type=$type'><font size=1' face='Verdana, Arial, Helvetica, sans-serif'>$name</font></a>";
echo "&nbsp;&nbsp;&nbsp;<a href='index.php?id=$id&type=$type' class = 'menulink' >$name</a>";
echo "<br>";
echo $brkk2;

} // end while
}
}// end function




// show Tertiary menu
function dmenu_t() {

$sql = "SELECT distinct cat FROM menu WHERE deleted='0' and placement = 'Tertiary'  ORDER BY catposition ASC, position ASC";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$cat = stripslashes($result["cat"]);

echo "<b><font size='1' face='Verdana, Arial, Helvetica, sans-serif'>$cat</font></b><br>";

$sql2 = "SELECT * FROM menu WHERE cat = '$cat' and deleted='0' and enabled = 'yes' ORDER BY  position ASC";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

$num_rows = mysql_num_rows($query2);
$ic = 0;

while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);
$type = stripslashes($result["type"]);
$name = stripslashes($result["name"]);

$name = ucfirst($name); 
$ic = $ic + 1;

if($ic == $num_rows ){ 
$brkk = "<br>";
} else { 
$brkk = "";
}//end if

//echo "<br>";
echo "&nbsp;&nbsp;&nbsp;<a href='index.php?id=$id&type=$type'><font size=1' face='Verdana, Arial, Helvetica, sans-serif'>$name</font></a>";
echo "<br>";
echo $brkk;

} // end while
}
}// end function





// show quaternary menu
function dmenu_q() {

$sql = "SELECT distinct cat FROM menu WHERE deleted='0' and placement = 'Quaternary'  ORDER BY catposition ASC, position ASC";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$cat = stripslashes($result["cat"]);

 echo "<b><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>$cat</font></b><br>";

$sql2 = "SELECT * FROM menu WHERE cat = '$cat' and deleted='0' and enabled = 'yes' ORDER BY  position ASC";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$id = stripslashes($result["id"]);
$type = stripslashes($result["type"]);
$name = stripslashes($result["name"]);

echo "<img src='images/ico3.gif'>&nbsp;<a href='index.php?id=$id&type=$type'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>$name</font></a>";
echo "<br>";

} // end while
}
}// end function







//end menu functions
//--------------------------------------------------------------------------


//show content

function scontent($id,$type) {
 global $type, $id;
 

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$content = stripslashes($result["content"]);

}

echo $content;

}//end function


//show title

function stitle($id,$type) {
 global $type, $id;
 

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$title = stripslashes($result["title"]);

}

echo $title;

}//end function




//show heading

function sheading($id,$type) {
 global $type, $id;
 

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$heading = stripslashes($result["heading"]);

}

echo $heading;

}//end function




//show img1
function simg1($id,$type) {
//global $type, $id;


$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$img1 = stripslashes($result["img1"]);
$lnk1 = stripslashes($result["lnk1"]);

}

if($img1 == "none"){
$path1 = "";
} else {

if($lnk1 == "#"){
$path1 = "<img src='images/$img1'>";

}else {//end bif
$path1 = "<a href='$lnk1'><img src='images/$img1' border = '0' alt = '$img1'></a>";
}
}

echo $path1;

}//end function

//-------------------------------------------------



//show img2
function simg2($id,$type) {
 //global $type, $id;
 
//echo "$type $id";
$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$img2 = stripslashes($result["img2"]);
$lnk2 = stripslashes($result["lnk2"]);

}

if($img2 == "none"){
$path2 = "";
} else {


if($lnk2 == "#"){
$path2 = "<img src='images/$img2'>";

}else {//end bif

$path2 = "<a href='$lnk2'><img src='images/$img2' border = '0' alt = '$img2'></a>";
}
}
echo $path2;

}//end function


//show img3
function simg3($id,$type) {
 global $type, $id;
//echo $id;
$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$img3 = stripslashes($result["img3"]);
$lnk3 = stripslashes($result["lnk3"]);
}

if($img3 == "none"){
$path3 = "";
} else {


if($lnk3 == "#"){
$path3 = "<img src='images/$img3'>";

}else {//end bif
$path3 = "<a href='$lnk3'><img src='images/$img3' border = '0' alt = '$img3'></a>";
}
}
echo $path3;

}//end function




//show img4
function simg4($id,$type) {
 //global $type, $id;

$sql = "SELECT * FROM $type WHERE id ='$id'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$img4= stripslashes($result["img4"]);
$lnk4 = stripslashes($result["lnk4"]);
}

if($img4 == "none"){
$path4 = "";
} else {

if($lnk4 == "#"){
$path4 = "<img src='images/$img4'>";

}else {//end bif

$path4 = "<a href='$lnk4'><img src='images/$img4' border='0' alt = '$img4'></a>";
}
}
echo $path4;

}//end function




//show audio
function saud($nid,$type) {
 //global $type, $id;

$sql = "SELECT * FROM news WHERE nid ='$nid'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query)) {
$audio = stripslashes($result["audio"]);

}

if($audio == "none"){
$alink = "";
} else {
$alink = "<a href='newsimages/$audio' class = 'gallink'>download Audio</a>";
}

echo $alink;

}//end function






//add type 1
function s_sponsors1($type,$id) {
$adtype = 1;
$sql58 = "SELECT * FROM adverts where pid = '$id' and adtype = '$adtype' ";
$query58 = mysql_query($sql58) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query58)) {
$printad = stripslashes($result["gname"]);

echo $printad."<br>";
}

}//end function





//Add type 2
function s_sponsors2($type,$id) {


echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr>";
	
	
$adtype = 0;
$sql58 = "SELECT * FROM adverts where pid = '$id' and adtype = '$adtype' ";
$query58 = mysql_query($sql58) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query58)) {
$printad = stripslashes($result["gname"]);

echo "<td align = 'center' >$printad </td>
	 </tr>
	 <tr>
	 <td height = '4' ></td>
	 </tr>
	 ";
}
echo "
	</table>";

}//end function







//Add type 3
function s_sponsors3($type,$id) {


echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>
	<tr>";
	
	
$adtype = 2;
$sql58 = "SELECT * FROM adverts where pid = '$id' and adtype = '$adtype' ";
$query58 = mysql_query($sql58) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query58)) {
$printad = stripslashes($result["gname"]);

echo "<td align = 'center' >$printad </td>
	 </tr>
	 <tr>
	 <td height = '4' ></td>
	 </tr>
	 ";
}
echo "
	</table>";

}//end function



?>
