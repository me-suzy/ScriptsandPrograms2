<?php
	/*	
	Copyright (C) 2005  Phillip Berry (Bliss Webhosting)
	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	session_start() ;
	require_once("./cms_class.inc") ;
	require_once("./authentication_class.inc") ;

	$content = new cms_class("./xml/content.xml") ;	
	$authentication = new authentication("./xml/users.xml") ;
	$user_administration = new cms_class("./xml/users.xml") ;	

	if(!$authentication->check_authentication()){
		$content->login_box() ;
		exit() ;
	}
	elseif(!$authentication->check_is_admin()){
		echo "Access Denied\n".$content->display_auth_link() ;
		exit() ;
	}
	else{		
		$users = $user_administration->extract_all_data() ;
		$content_data = $content->retrieve_all_data() ;
?>
<html>
<head>
	<title>LittleBlissCMS | Administration</title>
	<script type="text/javascript">
		function check_password_match(){
			var pass = document.password_change.new_password.value ;
			var pass1 = document.password_change.new_password1.value ;
			if(pass != pass1){
				alert("Password fields do not match") ;
				document.password_change.new_password.focus() ;
				document.password_change.new_password.select() ;
				return false ;
			}
			else{
				return true ;
			}
		}	
	</script>
	<link rel="stylesheet" type="text/css" href="./admin.css">
</head>


<div class="navigation">
	<?php	echo $content->display_static_section("admin_navigation") ;?>
</div>
<div  class="content">
	<h1>LittleBlissCMS Administration</h1>
	<div class="usercontainer">
		<div class="userform">
		<h2>New User</h2>
		
		<table>
			<tr>
				<td>
					Username 
				</td>
				<td>
					<form action="processor.php" method="POST">
					<input type="hidden" name="action" value="new_user">
					<input type="text" name="username">
				</td>
			</tr>
			<tr>
				<td>			
					Password 
				</td>
				<td>
					<input type="text" name="password">
				</td>
			</tr>	
			<tr>
				<td colspan="2" align="center">
					<input type="submit">
					</form>
				</td>
			</tr>
		</table>
		</div>
		<div width="50%" class="users">
		<h2>Users</h2>
		<table class="admin">
		<tr>
			<th>User name</th>	
			<th>Options</th>
		</tr>
		<?
		foreach($users as $key => $data){
		?>
		<tr>
			<td><?= $key?></td>	
			<td><a href="processor.php?delete_user=<?= $key?>">Delete</a> | <a href="admin.php?change_password=<?= urlencode($key)?>">Change Password</a></td>		
		</tr>
			<?if($_GET['change_password'] == $key){?>
			<tr>
				<td>
					<form action="processor.php" method="POST" name="password_change" onsubmit="return check_password_match()">
					<input type="hidden" name="user_name" value="<?php echo $_GET['change_password']?>">
					New Password <input type="text" name="new_password"><br />
					New Password <input type="text" name="new_password1"><br />
					<input type="submit">
					</form>
				</td>
			</tr>
			<?}?>
		<?}?>
		</table>
		</div>
		</div>
		<br />
		<br />
		<h2>Content file</h2>
		<table class="admin">
			<?foreach($content_data as $key => $data){?>
			<tr><td>&nbsp;</td></tr>
			<tr bgcolor="#cccccc" align="center">
				<th width="10%">Section</th>	
				<?if(is_array($data)){?>
					<?foreach($data as $data_key => $data_row){?>	
						<th><?= ucfirst($data_key);?></th>
					<?}?>
				<?}?>
				<th>
					Options
				</td>
			</tr>
			<tr align="center">
				<td><b><?= $key?></b></td>
				<?if(is_array($data)){?>
					<?foreach($data as $data_key => $data_row){?>
						<td>
							<?if($data_key == "content") $data_row = substr(strip_tags($data_row),0,300) ;
								if(is_array($data_row)){
								foreach($data_row as $manager){?>
									<?= $manager?><br />
								<?}?>
							<?}else{?>
								<?= $data_row?>
							<?}?>
						</td>
					<?}?>
				<?}else{?>
					<tr><td><?= $data?></td></tr>
				<?}?>	
				<td align="left" class="option">
					<form action="processor.php" method="POST">
						<input type="hidden" name="section" value="<?= $key?>">
						<input type="radio" name="administrate" value="delete_section">Delete Section</a>
						<br />
						<input type="radio" name="administrate" value="remove"> Remove User 
						<br />
						<input type="radio" name="administrate" value="add"> Add User 
						<br />
						<select name="username">
							<option>Select User</option>
							<?foreach($users as $key => $data){?>
							<option><?= $key?></option>
							<?}?>
						</select>
						<br />
						<input type="submit">
					</form>
				</td>	
			</tr>
		<?}?>
		<?}?>
		</table>
	</div>
</body>
</html>