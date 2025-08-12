<h2>Create New Account</h2>
<form action="<?php echo ($xfa['validator.example']); ?>" method="post">
<p>Username: <input type="text" name="user" value="<?php echo $user; ?>"></p>
<p>Password: <input type="password" name="pass" value="<?php echo $pass; ?>"></p>
<p>Confirm: <input type="password" name="conf" value="<?php echo $pass; ?>"></p>
<p>Email: <input type="text" name="email" value="<?php echo $email; ?>"></p>
<p><input type="submit" name="register" value=" Register "></p>
</form>