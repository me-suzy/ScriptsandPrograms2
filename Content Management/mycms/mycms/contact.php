<?
include("conn.php");
global $id, $action, $nid, $n_name, $type, $email, $comments, $frmemail, $subject, $name;

if(!$action){

$sql2 = "SELECT * FROM $type WHERE id = '$id'";
$query2 = mysql_query($sql2) or die("Cannot query the database.<br>" . mysql_error());

while($result = mysql_fetch_array($query2)) {
$title = stripslashes($result["title"]);

$content = stripslashes($result["content"]);
$email = stripslashes($result["email"]);
$img1 = stripslashes($result["img1"]);
$lnk1 = stripslashes($result["lnk1"]);

}

if($img1 == "none"){
$path1 = "";
} else {

if($lnk1 == "#"){
$path1 = "<img src='images/$img1'>";

}else {//end bif

$path1 = " <a href='$lnk1'><img src='images/$img1' border='0' ></a>";
}
}

echo "<table border='0' cellpadding='1' cellspacing='1' width='490'>
	<tr>
	<td  class = 'frntbox12' width = '550'> 



<b><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>$title</font></b>";
 
 echo "<span class='floatimgleft'>$path1</span>"; 

echo"<br>
$content
<form name='input' action='index.php' method='post' >

  <table width='50%' border='0' cellpadding='1' cellspacing='1' class=catTbl mm_noconvert='TRUE'>
 <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Name: </td>
 <td width='75%' align = 'left'><input type='text' name='name' style='background-color:#F2F2F2'  ></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Email: </td>
 <td width='75%' align = 'left'><input type='text' name='frmemail' style='background-color:#F2F2F2'></td>
  </tr>
  
 
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Subject: </td>
 <td width='75%' align = 'left'><input type='text' name='subject' style='background-color:#F2F2F2' ></td>
  </tr>
  
  <tr bgcolor='FFFFFF'>
 <td width='25%' align = 'left'  class = 'leftform'>Comment: </td>
 <td width='75%' align = 'left'><TEXTAREA NAME='comments' COLS=30 ROWS=6 style='background-color:#F2F2F2'></TEXTAREA>
  </td>
  </tr>
  
       <tr bgcolor='FFFFFF'>
	  <td width='25%' align = 'center'  class = 'leftform' colspan ='2'><input type='reset' name='Reset' value='Reset'>
  &nbsp; <input type='submit' name='Submit' value='Submit Enquiry'></td>
	  </tr>
  
 </table>

  <input type='hidden' name='id' value = '$id'>
  <input type='hidden' name='type' value = '$type'>
   <input type='hidden' name='email' value = '$email'>
   <input type='hidden' name='action' value = 'sendmail'>
 </form>
 ";
 
 echo "
</td>
</tr>
</table>";
 


} else {

if($action == "sendmail"){

$msg = "Name: $name \nComments: $comments ";

mail($email, $subject, $msg, "From: $frmemail");



echo "
<br>
<br>
<br>
<table border='0' cellpadding='1' cellspacing='1' width='490' class = 'frntbox15' >
 <tr>

 <td  class = 'frntbox12' width = '550' align = 'center'>
<font size='2' face='Verdana, Arial, Helvetica, sans-serif'> Thank you your mail have been sent<br> and we will get back to you within 24hrs</font>
 </td>
</tr>
</table>";


}

}

?>
