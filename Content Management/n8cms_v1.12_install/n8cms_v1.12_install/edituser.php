<?
session_start();  // Start Session
require ('_.php');
require ('functions.php');
//echo "userid=".$log_on_ID."<br>";
 if (!$usr_lvl)
	{
		echo"<h2>Access Denied</h2>";
		exit;
	}
//write control for individual user edit
//user userid from session to display individual user info
	
else
 {

	if ($usr_lvl ==1){editorpageheader();}
	if ($usr_lvl ==2){Adminpageheader ();}
	if	($usr_lvl ==3){Mastpageheader();}
	if 	($usr_lvl ==4){dietypageheader();}
	

$log_on_ID=$_SESSION[userid];
$edit=$_GET[edit];

}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>N8cms Edit User</title>

</head>

<body>
<SCRIPT language="JavaScript">
</SCRIPT>

<?
// need to make a fix
//if usr_lvl<3 cannot just enter a userid in the 
// browser address bar to edit anyone
//can only edit self

if ($edit == 1){
$userid=$_GET[userid];
$query="SELECT * FROM users WHERE userid=".$userid;
$result=mysql_query($query);
$usr_dat=mysql_fetch_array($result);
		
		if ( ($usr_lvl<3)&&($log_on_ID!=$usr_dat[userid])) {
			echo "<h2>access denied!</h2>IP# ".$_SERVER["REMOTE_ADDR"]." has attempted unauthorized access";
	 		exit;}

			echo"<form action=edituser.php method=POST>";
			echo"<input type=hidden name=edit value=2>";
			echo"<input type=hidden name=userid value=".$usr_dat[userid].">";
			echo"<table border=0><tr><td colspan=2>user id=".$usr_dat[userid]."</td></tr>";
			echo"<tr><td><b>First Name:</b></td><td><input type=text name=first_name value=".$usr_dat[first_name]."></td></tr>";
			echo"<tr><td><b>Last Name: &nbsp;</b></td><td><input type=text name=last_name value=".$usr_dat[last_name]."></td></tr>";
			if ($usr_lvl > 2){
			echo"<tr><td> User Level</td><td>";
	echo "<select name=\"user_level\">\n";
	for($i=$usr_dat[user_level];$i <= 4; $i++) {
		if ($i == $usr_dat[user_level]) {
			echo "	<option value=\"$i\" selected>$i</option>\n";
		} else {
			echo "	<option value=\"$i\">$i</option>\n";
		}
	}
	echo "</select>\n\n";
}else{echo "<input type=hidden name=user_level value=".$usr_dat[user_level].">";}
			echo"<tr><td>User e-mail</td><td><input type=text name=email_address size=25 value=".$usr_dat[email_address]."></td></tr>";
			echo"<tr><td colspan=2 align=center><input type=submit name=save value=save><input type=Reset></td></tr></table>";

	}
$edit=$_POST[edit];
if ($edit == 2){
	$first_name=$_POST[first_name];
	$last_name=$_POST[last_name];
	$user_level=$_POST[user_level];
	$email_address=$_POST[email_address];
	$log_on_ID=$_POST[userid];
	 $ins_query = "UPDATE users SET first_name='$first_name', last_name='$last_name', user_level='$user_level', email_address='$email_address'  WHERE userid=".$log_on_ID." LIMIT 1";
	
	mysql_query($ins_query) or die(mysql_error());
	echo "<script>document.location.replace('edituser.php');</script>";	
	
	}

if (!$edit){
	if ( ($usr_lvl == 3)||($usr_lvl==4) ){
	echo"<table border=0><th colspan=7 align=left><h3>Other Users</h3></th>";
	$query="SELECT * FROM users WHERE userid > 5 ORDER BY userid ASC" ;
	$result=mysql_query($query);
	$usr_dat=mysql_fetch_array($result);
	while($usr_dat=mysql_fetch_array($result)){
	echo "</TR><tr bgcolor=#cacaca>";
	if ( ($usr_lvl==4)&&($log_on_ID != $usr_dat[userid]) ){
	echo "<td rowspan=2><a href='?delete=1&userid=".$usr_dat[userid]."' >Delete</a></td>";}
	echo"<td><a href='edituser.php?edit=1&userid=".$usr_dat[userid]."'><b>Edit</b></a></td><td>first_name</td><td>last_name</td><td>username</td><td>email_address</td><td>user_level</td></tr>
	<tr bgcolor=#cacaca>
	<td>".$usr_dat[userid]."</td>
	<td>".$usr_dat[first_name]."</td>
	<td>".$usr_dat[last_name]."</td>
	<td>".$usr_dat[username]."</td>
	<td>".$usr_dat[email_address]."</td>
	<td>".$usr_dat[user_level]."</td>\n";  
	echo "</tr></td>\n";  
	echo"<tr> <td colspan=5>&nbsp;</td></tr>";
	}
}
	echo "<table border=0><tr>\n";
		$query="SELECT * FROM users WHERE userid=".$log_on_ID;
		$result=mysql_query($query);
		$usr_dat=mysql_fetch_array($result);
			echo "<td><a href='edituser.php?edit=1&userid=".$usr_dat[userid]."'><b>edit yourself</b></a></td>\n
				<td><a href='edituser.php?edit=1&userid=".$usr_dat[userid]."'>first_name</a></td><td>last_name</td><td>username</td><td>email_address</td><td>user_level</td></tr>\n
				<tr bgcolor=#cacaca>
				<td>".$usr_dat[userid]."</td>
				<td>".$usr_dat[first_name]."</td>
				<td>".$usr_dat[last_name]."</td>
				<td>".$usr_dat[username]."</td>
				<td>".$usr_dat[email_address]."</td>
				<td>".$usr_dat[user_level]."</td></tr>";

}
if ( $_GET[delete] == 1){
	//echo"<script>
	echo"delete will be done";
	$del_query= "DELETE FROM users WHERE userid=".$_GET[userid]." LIMIT 1";
	echo $del_query."<br>";
	mysql_query($del_query) or die (mysql_error());
echo"<script>document.location.replace('edituser.php');</script>";
}
else{header ("Location : exit.php?");}
?>
</table>


</body>
</html>
