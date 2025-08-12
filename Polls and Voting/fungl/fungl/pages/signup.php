<?php
// check if user is allowed to view this page
if($user->checkAuth()){
	echo "You allready have an account.";
	return;
}

if(isset($_POST['signup'])){
	//validate input data
	// username must only contain a-z0-9 and -_ characters
	if(!ereg("[a-z0-9\-_]+", $_POST['username_signup'])){
		$error['username'] = true;
	}elseif(usernameInUse($_POST['username_signup'])){
		$error['username'] = true;
		$error['usernameUse'] = true;
	}
	if(!preg_match("/^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/", $_POST['email'])){
		$error['email'] = true;
	}
	if($_POST['password'] != $_POST['password2']){
		$error['password'] = true;
	}
}
if(isset($_POST['signup']) && empty($error)){
	// create user
	$user->addUser($_POST['username_signup'], $_POST['password']);
	$userpref->setPref($_POST['username_signup'], 'lvl', USER_LVL);
	$userpref->setPref($_POST['username_signup'], 'email', $_POST['email']);
	$userpref->setPref($_POST['username_signup'], 'projectamount', 1);
	$userpref->setPref($_POST['username_signup'], 'pollamount', 1);
	echo "You user has now been created. Login with the supplied info by clicking the 'Login' link in the menu.";
	// send welcome mail
	sendAccountWelcomeMail($_POST['username_signup'], $_POST['password']);
}else{
	// SHOW SIGNUP FORM
?>
<h1>Signup is free and easy!</h1>
Registration provides instant access to creating polls that you can use on your own website.
<hr/>
<form action="" method="post">
<?php
if($error['username']){
	echo '<span style="color:red">';
}else{
	echo '<span>';
}
?>
Username:</span><br/>
<input name="username_signup" type="text" value="<?php echo $_POST['username_signup']; ?>"/>
<?php if($error['usernameUse']){ echo "The username is allready taken"; }?>
<br/>

<?php
if($error['email']){
	echo '<span style="color:red">';
}else{
	echo '<span>';
}
?>
Email: </span><br/>
<input name="email" type="text" value="<?php echo $_POST['email']; ?>"/><br/>

<?php
if($error['password']){
	echo '<span style="color:red">';
}else{
	echo '<span>';
}
?>
Password: </span><br/>
<input name="password" type="password"/><br/>
<?php
if($error['password']){
	echo '<span style="color:red">';
}else{
	echo '<span>';
}
?>
Password again: </span><br/>
<input name="password2" type="password"/><br/>
<input type="submit" name="signup" value="Signup"/>
</form>

<?php
}
?>