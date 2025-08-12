<?php
// database connect script.
require 'db_connect.php';
 


?>

<html>
<head>

<title>Login</title>
</head>
<body>
<p><font color="#D68960"></font></p>
<body bgcolor="#48498C">
<p align="center"><img src="gift.jpg" 
<br>
 

<?php

if (isset($_POST['submit'])) { // if form has been submitted


	/* check they filled in what they were supposed to and authenticate */
	if(!$_POST['uname'] | !$_POST['passwd']) {
		die('You did not fill in a required field.');
	}

	// authenticate.

	if (!get_magic_quotes_gpc()) {
		$_POST['uname'] = addslashes($_POST['uname']);
	}

	$check = $db_object->query("SELECT username, password FROM users WHERE username = '".$_POST['uname']."'");

	if (DB::isError($check) || $check->numRows() == 0) {
		die('That username does not exist in our database.');
	}

	$info = $check->fetchRow();

	// check passwords match

	$_POST['passwd'] = stripslashes($_POST['passwd']);
	$info['password'] = stripslashes($info['password']);
	$_POST['passwd'] = md5($_POST['passwd']);

	if ($_POST['passwd'] != $info['password']) {
		die('Incorrect password, please try again.');
	}

	// if we get here username and password are correct, 
	//register session variables and set last login time.

	
	$_POST['uname'] = stripslashes($_POST['uname']);
	$_SESSION['username'] = $_POST['uname'];
	$_SESSION['password'] = $_POST['passwd'];

?>

<center>

<a href="login.php"><font color="#D68960">Login</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="addgift.php"><font color="#D68960">Add gift</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="displaymylist.php"><font color="#D68960">My Giftlist</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="buylist.php"><font color="#D68960">My Buy List</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="update.php"><font color="#D68960">My Profile</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="profilelist.php"><font color="#D68960">All User Profiles</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="showfiles.php"><font color="#D68960">View Other Giftlists</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="logout.php"><font color="#D68960">Logout</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<br><br>

</head>

<br>
<center>
  <table border="2" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFFFFF" width="75%" id="AutoNumber1" height="144">
    <tr>
      <td width="100%" height="144">
      <p align="center"><font color="#FFFFFF" size="4">Welcome</font>&nbsp&nbsp<font size='4' color='#ffffff'><? echo "$_SESSION[username]" ?></p>
      
<p align="center"><font size="4" color="#FFFFFF">Points to remember:</font></p>
<p align="center"><font size="3" color="#FFFFFF"> Always keep your gift list up to date - delete unwanted items !</p>
<p align="center"><font size="3" color="#FFFFFF"> Keep an eye on your `buy` list, deselect items you no longer intend to buy, this will return them to the users wanted list.</p>


      <p align="center">&nbsp;</td>
    </tr>
  </table>
  </center>
</div>

</body>

<br>

<p align="center"><font size="4" color="#FFFFFF"> Latest additions to users gift lists:</p>


<?php


$dbQuery = "SELECT gift_id, username, gift_name, gift_price, gift_description, gift_url_store, give_date, buyer, del_gift, buyable "; 

$dbQuery .= "FROM gifts WHERE del_gift = 'no' AND buyer='no' "; 

$dbQuery .= "ORDER BY gift_id DESC LIMIT 0,10";

$result = mysql_query($dbQuery) or die("Couldn't get file list");

?>


<table align="center" border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="90%">

<tr>

<td width="16%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift List Owner</font></b></td>


<td width="20%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#FFFFFF"> 

Gift Name</font></b></td>


<td width="10%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Price</font></b></td>


<td width="13%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Gift Comments</font></b></td>



<td width="43%" bgcolor="#FF9900" height="21"> 

<p style="margin-left: 10"><b><font face="Verdana" size="2" color="#FFFFFF"> 

Store / Web site URL</font></b></td>



</tr> 

<?php

while($row = mysql_fetch_array($result)) 

{ 

?>

<tr> 

<td width="16%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<?php echo $row["username"]; ?> 

</font> 

</td> 



<td width="20%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<?php echo $row["gift_name"]; ?> 

</font> 

</td> 




<td width="10%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo '$',$row["gift_price"]; ?> 


</a></font> 

</td> 



<td width="30%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo $row["gift_description"]; ?> 


</a></font> 

</td> 



<td width="50%" bgcolor="#FFDCA8" height="21"> 

<p style="margin-left: 10"><font face="Verdana" size="2"> 

<?php echo $row["gift_url_store"]; ?> 


</a></font> 

</td> 




</tr>

<?php 

}

echo "</table>"; 

?>

<?php


} else {	// if form hasn't been submitted

?>


<br><br>

<div align="center">
  <center>
  <table border="2" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#FFFFFF" width="85%" id="AutoNumber1" height="1">
    <tr>
      <td width="90%" height="1">
      <p align="center"><b><font color="#FFFFFF" size="5">Welcome To Our&nbsp; 
      Family Gift List Site.</font></b></p>
      <p align="center"><font color="#FFFFFF" size="2" face="Courier New"><b>
      This site can be used to keep track of individuals gift lists, such as 
      Christmas or Birthday lists. Any family/friends are welcome to use this site, if you only want to view other users lists, simply create an account and login.</b></font></p>
      <p align="center"><font color="#FFFFFF" size="2" face="Courier New"><b>
      Features implemented so far:</b></font></p>
      <ul style="color: #FF0000">
        <li>
        <p align="left"><font color="#FFFFFF" size="2" face="Courier New"><b>
        Users can create an account and add / modify / delete gifts / view other users lists</b></font></li>
        <li>
        <p align="left"><font color="#FFFFFF" size="2" face="Courier New"><b>
        Preferences for types of gifts such as Movies / Music / Books etc can be 
        added to your profile. This provides general gift ideas instead of 1 
        particular item.</b></font></li>
        <li>
        <p align="left"><font color="#FFFFFF" size="2" face="Courier New"><b>
        Users may view other peoples lists and select to `buy` an item. The item 
        is then removed from the `wanted` list. (the owner of the gift list is 
        unable to view which items have been bought)</b></font></li>
        <li>
        <p align="left"><font color="#FFFFFF" size="2" face="Courier New"><b>A 
        `give` date is specified when buying a gift so the item is not removed 
        from the owners gift list until after that date. This prevents the 
        receiver of the gift being able to see which items have been purchased.</b></font></li>
        <li>
        <p align="left"><font color="#FFFFFF" size="2" face="Courier New"><b>A 
        password is required to view other users gift lists, to prevent users 
        from viewing each others bought items (basically to prevent children 
        viewing each others lists!)</b></font></li>
      </ul>
      <p align="center">&nbsp;</td>
    </tr>
  </table>
  </center>
</div>


<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
<table align="center" border="1" cellspacing="0" cellpadding="3">
<tr><td><font color="#ffffff">Username:</td><td>
<input type="text" name="uname" maxlength="40">
</td></tr>
<tr><td><font color="#ffffff">Password:</td><td>
<input type="password" name="passwd" maxlength="50">
</td></tr>
<tr><td colspan="2" align="right">
<font color="#D68960">Need an account? <a href="register.php"><font color="#ffffff">Sign up here</font>

<input type="submit" name="submit" value="Login">
</td></tr>
</table>
</form>

<?php
}
?>
</body>
</html>