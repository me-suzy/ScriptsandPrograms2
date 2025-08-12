<?php
// check if user is allowed to view this page

//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.

if($userpref->getPref($user->getUsername(), 'lvl') < ADMIN_LVL){
	echo "You are not allowed to view this page";
	return;
}

// requests
if($_GET['action'] == 'delete'){
	// delete the requested user
	// check user level
	if($userpref->getPref($user->getUsername(), 'lvl') < $userpref->getPref($_GET['user'], 'lvl')){
		echo "You can't delete an user with higher level than you self";
	}elseif($user->getUsername() == $_GET['user']){
		echo "You can't delete your own user";
	}else{
		// send mail when account is removed?
		sendAccountDeleteMail($_GET['user']);
		
		if(!$user->removeUser($_GET['user'])){
			echo "Failed to delete user: ".$_GET['user'];
		}elseif(!$userpref->deletePref($_GET['user'], 'lvl')){
			echo "Failed to delete preference 'lvl' from user: ".$_GET['user'];
		}elseif(!$userpref->deletePref($_GET['user'], 'email')){
			echo "Failed to delete preference 'email' from user: ".$_GET['user'];
		}elseif(deleteProjects($_GET['user'])){
			echo "Failed to delete the users projects";
		}else{
			echo "User ".$_GET['user']." deleted";
		}
	}
}

if($_GET['action'] == 'edit'){
	// show edit user form
	if($userpref->getPref($user->getUsername(), 'lvl') < $userpref->getPref($_GET['user'], 'lvl')){
		echo "You can't edit an user with higher level than you self";
	}else{
		// get user info
		$username 	= $_GET['user'];
		$lvl 		= $userpref->getPref($_GET['user'], 'lvl');
		$email 		= $userpref->getPref($_GET['user'], 'email');
		$projectamount = $userpref->getPref($_GET['user'], 'projectamount');
		$pollamount = $userpref->getPref($_GET['user'], 'pollamount');
		$lvls		= getUserLvls();
		?>
		<form action="?page=users&amp;action=usersave&amp;username=<?php echo $username; ?>" method="post">
			Username:<br/>
			<input disabled="disabled" type="text" name="user" value="<?php echo $username; ?>"/><br/>
			
			Email: <br/>
			<input name="email" type="text" value="<?php echo $email; ?>"/><br/>
			
			Password: <br/>
			<input name="password" type="text"/><br/>
			
			Project amount: <br/>
			<input name="projectamount" type="text" value="<?php echo $projectamount; ?>"/><br/>
			
			Poll amount: <br/>
			<input name="pollamount" type="text" value="<?php echo $pollamount; ?>"/><br/>
			
			User level: <br/>
			<select name="lvl">
				<?php
				foreach($lvls as $i){
					if($i == $lvl){
						echo '<option selected="selected" value="'.$i.'">'.getLvlName($i).'</option>';
					}else{
						echo '<option value="'.$i.'">'.getLvlName($i).'</option>';
					}
				}
				?>
			</select><br/>
			<input type="submit" name="saveuser" value="Save"/>
	 	</form>
	<?php
	}
}
// save the user
if($_GET['action'] == 'usersave'){
	// update user data
	if($userpref->getPref($user->getUsername(), 'lvl') < $userpref->getPref($_GET['user'], 'lvl')){
		echo "You can't edit an user with higher level than you self";
	}else{
		$userpref->setPref($_GET['username'], 'email', $_POST['email']);
		$userpref->setPref($_GET['username'], 'lvl', $_POST['lvl']);
		$userpref->setPref($_GET['username'], 'projectamount', $_POST['projectamount']);
		$userpref->setPref($_GET['username'], 'pollamount', $_POST['pollamount']);
		if(!empty($_POST['password']) && $_POST['password'] != ''){
			$user->changePassword($_GET['username'], $_POST['password']);
			$password = $_POST['password'];
		}else{
			$password = '-- No change --';
		}
		echo "User data updated";
		// send email
		sendAccountChangeMail($_GET['username'], $password);
	}
}
// list the users in the system 
$users = $user->listUsers();
echo '<h1>Users</h1><hr/>';
echo '<table><tr><td>Username</td><td>Level</td><td>Actions</td></tr>';
foreach($users as $i){
	if($i['username'] == $user->getUsername()) // hide own user
		continue;
	if($userpref->getPref($i['username'], 'lvl') > $userpref->getPref($user->getUsername(), 'lvl')) // hide users with higher lvl
		continue;
	echo "<tr><td>".$i['username']."</td>";
	echo "<td>".getLvlName($userpref->getPref($i['username'], 'lvl'))."</td>";
	echo '<td><a href="?page=users&amp;action=edit&amp;user='.$i['username'].'">[edit]</a>';
	echo ' <a href="?page=users&amp;action=delete&amp;user='.$i['username'].'">[delete]</a></td></tr>';
}
echo '</table>';

//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
?>