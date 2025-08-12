<?php
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
require("includes/config.php");
?><LINK href="includes/style.css" rel="stylesheet" type="text/css"><?
$subDir = $_GET['subDir'];

?>
<table width="974" align="center" cellpadding="3">
	<tr><td><b>Welcome <?=$userFullName?></b></td><td align="right"><? 
	if($userType=="admin"){
		echo "<a href=\"admin/addUser.php\">Add User</a> | <a href=\"admin/editUser.php\">Edit User</a> | ";
	}?><a href="admin/changePassword.php">Change Password</a> | <a href="logout.php">Logout</a></td>
	</tr>
</table>
<table class="tblBody" cellpadding="3" align="center">
<tr><td colspan="2" class="tblTitle" align="center"><?=$theTitle?></td></tr>
<?
if($subDir){
	if(strpos($subDir, "\'")){
		$subDir = str_replace("\'", "'", $subDir);
	}
	if(is_dir($baseDir.$subDir."/")) {
		?><tr><td><iframe frameborder="0" style="border-style:dashed; border-width:thin; border-color:#999999" width="200" height="470" name="albumFrame" src="albumList.php?subDir=<?=$subDir?>"></iframe></td><?
		?><td><iframe frameborder="0" width="750" height="470" name="picsFrame" src="picsList.php?subDir=<?=$subDir?>"></iframe></td></tr><?
	}
	else{
		echo $baseDir.$subDir." does not exist";
	}
}
else{ 
	if(is_dir($baseDir)) {
		?><tr><td><iframe frameborder="0" style="border-style:dashed; border-width:thin; border-color:#999999" width="200" height="470" name="albumFrame" src="albumList.php"></iframe></td><?
		?><td><iframe frameborder="0" width="750" height="470" name="picsFrame" src="picsList.php"></iframe></td></tr><?
	}
	else{
		echo $baseDir." does not exist";
	}
}
?>
<tr><td colspan="2" class="tblHead2" align="right">Created by Ariful Islam</td></tr>
</table>