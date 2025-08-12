<?php
// check if user is allowed to view this page
//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
if($userpref->getPref($user->getUsername(), 'lvl') < USER_LVL){
	echo "You are not allowed to view this page";
	return;
}

if($_GET['action'] == 'usersave'){
	// did the user request changes in there account
	// validate input data
	if(!preg_match("/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/", $_POST['email'])){
		$error['email'] = true;
	}
	
	if(empty($error)){
		// update user data
		$username 	= $user->getUsername();
		$userpref->setPref($username, 'email', $_POST['email']);
		if(!empty($_POST['password']) && $_POST['password'] != ''){
			$user->changePassword($username, $_POST['password']);
			$password = $_POST['password'];
		}else{
			$password = '-- No change --';
		}
		echo "User data updated";
		// send email
		sendAccountChangeMail($username, $password);
		
	}
}

echo '<h1>User account</h1>';

// get user info
$username 	= $user->getUsername();
$lvl 		= $userpref->getPref($username, 'lvl');
$email 		= $userpref->getPref($username, 'email');
$projectamount = $userpref->getPref($username, 'projectamount');
$pollamount = $userpref->getPref($username, 'pollamount');
$lvls		= getUserLvls();

// show the account form
// all disabled input fields will not be processed, they are there only for user info.
?>
<form action="?page=account&amp;action=usersave" method="post">
	Username:<br/>
	<input disabled="disabled" type="text" name="user" value="<?php echo $username; ?>"/><br/>
	
	<?php
	if($error['email']){
		echo '<span style="color:red">';
	}else{
		echo '<span>';
	}
	?>
	Email: </span><br/>
	<input name="email" type="text" value="<?php echo $email; ?>"/><br/>
	
	Password: <br/>
	<input name="password" type="text"/><br/>
	
	Project amount: <br/>
	<input name="projectamount" disabled="disabled" type="text" value="<?php echo $projectamount; ?>"/><br/>
	
	Poll amount: <br/>
	<input name="pollamount" disabled="disabled" type="text" value="<?php echo $pollamount; ?>"/><br/>
	
	User level: <br/>
	<select disabled="disabled" name="lvl">
		<?php
		foreach($lvls as $i){
			if($i == $lvl){
				echo '<option selected="selected" value="'.$i.'">'.getLvlName($i).'</option>';
			}else{
				echo '<option value="'.$i.'">'.getLvlName($i).'</option>';
			}
		}
		
		//Copyright 2005 Fungl.com Do not resells or redistribute.
// 
// see http://fung.com or http://fungl.com/download/ for details
// Oh and Dont resell or redistribute this software.
		?>
	</select><br/>
	<input type="submit" name="saveuser" value="Save"/>
</form>