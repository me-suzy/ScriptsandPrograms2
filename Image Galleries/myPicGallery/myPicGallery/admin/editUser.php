<?php
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
?><LINK href="../includes/style.css" rel="stylesheet" type="text/css"><?
require("../includes/config.php");

$submit = $_POST['submit'];
$edit = $_GET['edit'];
$delete = $_GET['delete'];
$getUser = $_GET['user'];
$getDir = $_GET['dir'];

if($submit == "Update"){
	$fullName = $_POST['fullName'];
	$email = $_POST['email'];
	$uType = $_POST['uType'];
	$active = $_POST['active'];
	$postUserName = $_POST['userName'];
	$directory = $_POST['directory'];
	
	mysql_query("update album_users set fullName = '$fullName', email = '$email', userType = '$uType' where userID = '$postUserName'")or die(mysql_error());
	mysql_query("delete from album_permission where userAllowed = '$postUserName'") or die(mysql_error());
	
	foreach($directory as $DIR){
		mysql_query("insert into album_permission (dir, userAllowed) values ('$DIR', '$postUserName')")or die(mysql_error());
	}
	$edit = true;
	$getUser = $postUserName;
	$message = "User permission has been successfully updated...";
}
if($edit){
	$submit = "editing";
	$dirArray = array(0 => 1);
	$myUser = mysql_query("select * from album_users where userID = '$getUser'") or die(mysql_error());
	$rowMyUser = mysql_fetch_array($myUser);
	
	$dirSql = mysql_query("select * from album_permission where userAllowed = '$getUser'")or die(mysql_error());
	while($rowDirSql = mysql_fetch_array($dirSql)){
		array_push($dirArray, $rowDirSql['dir']);
	}
	if($dh = opendir($baseDir)){
		?>
		<form action="editUser.php" method="post">
		<table width="700" align="center" cellpadding="3"><tr><td align="right"><a href="editUser.php">User List</a> | <a href="../gallery.php">Picture Gallery</a></td></tr></table>
		<table align="center" width="700" cellpadding="3" class="tblBody">
		<tr><td colspan="4" class="tblTitle">Editing Privileges for the user -- <?=$getUser?></td></tr>
		<tr>
			<td align="right" width="100"><b>Full Name: </b></td><td width="100"><input type="text" name="fullName" size="25" value="<?=$rowMyUser['fullName']?>"></td><td width="500" colspan="2"></td>
		</tr>
		<tr>
			<td align="right" width="100"><b>Email: </b></td><td><input type="text" name="email" size="25" value="<?=$rowMyUser['email']?>"></td><td colspan="2"></td>
		</tr>
		<tr>
			<td align="right" width="100"><b>User Type: </b></td><td><select name="uType">
										<option value="regular" <? if($rowMyUser['uesrType']=="regular")echo "selected";?>>Regular</option>
										<option value="admin" <? if($rowMyUser['userType']=="admin")echo "selected";?>>Admin</option>
										</select></td><td colspan="2"></td>
		</tr>
		<tr><td colspan="4"><hr></td></tr>
		</table>
		<table align="center" width="700" cellpadding="3" class="tblBody">
		<?
		if($rowMyUser['userType']=="admin"){
			?><tr><td align="center"><b>User of ADMIN type has access to all the albums</b></td></tr><?
		}
		else{
			$i=0;
			while (($file = readdir($dh)) !== false) {
				if(array_search($file, $dirArray)){
					$checked = "checked";
				}
				else{
					$checked = "";
				}
				if(filetype($baseDir.$file)=="dir"){
					if(($i == 0) || ($i%4 == 0))echo "<tr>";
					if(($file!=".") && ($file!="..") && ($file!="Picasa Edits")){
						?><td><input type="checkbox" name="directory[]" value="<?=$file?>" <?=$checked?>> <?=$file?></td><?
						$i++;
					}
					if($i%4==0)echo "</tr?>";
				}
			}
		}
		?>
		<tr><td colspan="4" align="center"><input type="submit" name="submit" value="Update"></td></tr>
		<tr><td colspan="4" align="center"><font color="#0000FF"><?=$message?></font></td></tr>
		<input type="hidden" name="userName" value="<?=$getUser?>">
		</table></form><?
		closedir($dh);
	}
}
if($delete){
	mysql_query("delete from album_users where userID = '$getUser'")or die(mysql_error());
	mysql_query("delete from album_permission where userAllowed = '$getUser'")or die(mysql_error());
}
if($submit == ""){
	$rsUser = mysql_query("select * from album_users")or die(mysql_error());
	?>
	<table width="700" align="center" cellpadding="3"><tr><td align="right"><a href="../gallery.php">Picture Gallery</a></td></tr></table>
	<table width="700" align="center" cellpadding="3" class="tblBody">
	<tr><td colspan="4" class="tblTitle" align="center">Edit User Privileges</td></tr>
	<tr><td class="tblHead"><b>User Name</b></td><td class="tblHead"><b>Full Name</b></td><td class="tblHead"><b>Email</b></td>
	<td class="tblHead"><b>Delete</b></td></tr>
	<?
	while($lsUser = mysql_fetch_array($rsUser)){
		if($lsUser['userID'] != "admin"){
			?>
			<tr><td><a href="editUser.php?edit=true&user=<?=$lsUser['userID']?>"><?=$lsUser['userID']?></a></td>
			<td><?=$lsUser['fullName']?></td>
			<td><?=$lsUser['email']?></td>
			<td><a href="editUser.php?delete=true&user=<?=$lsUser['userID']?>">Delete</a></td></tr>
			<?
		}
	}
	?></table><?
}
?>