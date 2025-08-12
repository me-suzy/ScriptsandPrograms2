<?php
	if($user->getAuth()){
		echo "<h1>Login ok</h1> You can use the system by clicking the buttons in the top menu.";
	}else{
		if(AUTH_WRONG_LOGIN == $user->getStatus()){
			echo "User login failed.";
		}
?>
<div class="center" style="width:200px;">
	<form action="?page=login" method="post" style="width: 200px;">
	Username:<br/>
	<input name="username" type="text"/><br/>
	Password:<br/>
	<input name="password" type="password"/>
	<br/>
	<input name="submit" type="submit" value="Login"/>
	</form>
</div>
<?php
	}
?>