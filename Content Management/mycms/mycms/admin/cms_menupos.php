<?

ob_start();
include("conn.php");

// show change menu position on edit page
function menupos($id) {


$sql9 = "SELECT * FROM menu WHERE deleted='0' and id !=$id  ORDER BY catposition ASC, position ASC";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

//<td width='75%' align = 'left'><input type='radio' name='change' value ='no' checked><b>Unchanged</b>|&nbsp; <input type='radio' name='change' value ='beg'><b>To Beg</b>| <input type='radio' name='change' value ='end'><b>To End</b>| <input type='radio' name='change' value ='after'> <b>after:</b>

echo  "<tr bgcolor='#FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Move Menu Position  </td>
 <td width='75%' align = 'left'><input type='radio' name='change' value ='no' checked><b>Unchanged</b>|&nbsp;| <input type='radio' name='change' value ='end'><b>To End</b>| <input type='radio' name='change' value ='after'> <b>after:</b>
 <select name='menu'>";
     
while($result = mysql_fetch_array($query9)) {

$name = stripslashes($result["name"]);
$pos = stripslashes($result["position"]);

echo  "<option value='$pos'>$name</option>";
 
}
 
echo "</select>
 
  </td>
  </tr>";

}// end function




// show change menu position on addpage page
function menupos2() {


$sql9 = "SELECT * FROM menu WHERE deleted='0' ORDER BY catposition ASC, position ASC";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());


echo  "<tr bgcolor='#FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Place Menu  </td>
 <td width='75%' align = 'left'> <input type='radio' name='change' value ='end'><b>At End</b>| <input type='radio' name='change' value ='after' checked> <b>After:</b> 
 <select name='menu'>";
     
while($result = mysql_fetch_array($query9)) {

$name = stripslashes($result["name"]);
$pos = stripslashes($result["position"]);

echo  "<option value='$pos'>$name</option>";
 
}
 
echo "</select>
 
  </td>
  </tr>";

}// end function



//show change position on edit category items

function menupos3($id) {


$sql9 = "SELECT * FROM category  ORDER BY position ASC";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());


echo  "<tr bgcolor='#FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Move Cat Position  </td>
 <td width='75%' align = 'left'><input type='radio' name='change' value ='no' checked><b>Unchanged</b>|&nbsp; <input type='radio' name='change' value ='beg'><b>To Beg</b>| <input type='radio' name='change' value ='end'><b>To End</b>| <input type='radio' name='change' value ='after'> <b>after:</b> 
 <select name='menu'>";
     
while($result = mysql_fetch_array($query9)) {

$name = stripslashes($result["name"]);
$pos = stripslashes($result["position"]);

echo  "<option value='$pos'>$name</option>";
 
}
 
echo "</select>
 
  </td>
  </tr>";

}// end function









 // show change menu position on addcategory page
function menupos4() {


$sql9 = "SELECT * FROM category  ORDER BY position ASC";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());


echo  "<tr bgcolor='#FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Cat Position  </td>
 <td width='75%' align = 'left'><input type='radio' name='change' value ='beg'><b>At Beg</b>| <input type='radio' name='change' value ='end'><b>At End</b>| <input type='radio' name='change' value ='after' checked> <b>After:</b> 
 <select name='menu'>";
     
while($result = mysql_fetch_array($query9)) {

$name = stripslashes($result["name"]);
$pos = stripslashes($result["position"]);

echo  "<option value='$pos'>$name</option>";
 
}
 
echo "</select>
 
  </td>
  </tr>";

}// end function




















//--------------change postion

function cmenupos($change,$position,$menu) {

switch ($change) {

case 'no':
$nid = $position;
break;

case 'beg':

$sql5 = "SELECT * FROM menu ";
$query5 = mysql_query($sql5) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query5)) {

$id = stripslashes($result["id"]);
$pos = stripslashes($result["position"]);

$npos = $pos + 1;

$sql2 = "UPDATE menu SET position ='$npos' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

}//end while

$nid = 0;
break;



case 'end':

$sql = "SELECT * FROM menu WHERE deleted='0' ORDER BY position DESC LIMIT 1";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query)) {
$pos = stripslashes($result["position"]);
}
$nid = $pos + 1;

break;

case 'after':

$sql5 = "SELECT * FROM menu where position > '$menu'";
$query5 = mysql_query($sql5) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query5)) {

$id = stripslashes($result["id"]);
$pos = stripslashes($result["position"]);

$npos = $pos + 1;

$sql2 = "UPDATE menu SET position ='$npos' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

}//end while

$nid = $menu + 1;



break;
}// end switch

return $nid;

}// end function







//--------------change postion category

function cmenupos2($change,$position,$menu) {

switch ($change) {



case 'no':
$nid = $position;
break;

case 'beg':

$sql5 = "SELECT * FROM category ";
$query5 = mysql_query($sql5) or die("Cannot query the database.<br>" . mysql_error());





while($result = mysql_fetch_array($query5)) {

$id = stripslashes($result["id"]);
$pos = stripslashes($result["position"]);

$npos = $pos + 1;

$sql2 = "UPDATE category SET position ='$npos' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());






}//end while

$nid = 0;
break;



case 'end':

$sql = "SELECT * FROM category ORDER BY position DESC LIMIT 1";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query)) {
$pos = stripslashes($result["position"]);
}
$nid = $pos + 1;

break;

case 'after':

$sql5 = "SELECT * FROM category where position > '$menu'";
$query5 = mysql_query($sql5) or die("Cannot query the database.<br>" . mysql_error());
while($result = mysql_fetch_array($query5)) {

$id = stripslashes($result["id"]);
$pos = stripslashes($result["position"]);

$npos = $pos + 1;

$sql2 = "UPDATE category SET position ='$npos' WHERE id='$id'";
$query2 = mysql_query($sql2) or die("Cannot update record.<br>" . mysql_error());

}//end while

$nid = $menu + 1;



break;
}// end switch

return $nid;

}// end function






// return category position

function catpos($cat) {


$sql6 = "SELECT * FROM category where name = '$cat'";
$query6 = mysql_query($sql6) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query6)) {

$pos = stripslashes($result["position"]);

}

return $pos;

}//end function




// return category placement
function catplace($cat) {


$sql9 = "SELECT * FROM category where name = '$cat'";
$query9 = mysql_query($sql9) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query9)) {

$placement = stripslashes($result["placement"]);

}

return $placement;

}//end function




?>
