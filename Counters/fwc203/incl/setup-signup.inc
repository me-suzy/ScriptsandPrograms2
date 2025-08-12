<?php
echo '<h1>Sign Up</h1><b>Step By Step</b><br>
To create an account, you need to do the following things: <ol>
<li>Enter your new '.$title.' username.
<li>Enter a password with a length greater than 4 characters.
<li>Fill out your Name and a valid Email address.
</ol>
<form method="post" action="setup.php?st=check">
  <table border="0" width="297" height="16">
    <tr>
      <td width="102" height="1" align="right" class="boldtitle"><b>Username:</b></td>
      <td width="179" height="1">'; if ($err==1) { echo '<font class="red">Missing fields</font><br>'; } elseif ($err==2) { echo '<font class="red">Username already exists</font><br>'; } elseif ($err==4) { echo '<font class="red">Invalid username</font><br>'; } echo '<input type="text" name="usr" size="24" value="'.$username.'"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="boldtitle"><b>Password:</b></td>
      <td width="179" height="1">'; if ($err==3) { echo '<font class="red">Invalid password</font><br>'; } echo'<input type="password" name="pass" size="24" value="'.$password.'"></td>
    </tr>
    <tr>
      <td height="20"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Name:</td>
      <td width="179" height="1"><input type="text" name="name" size="24" value="'.$name.'"></td>
    </tr>
    <tr>
      <td width="102" height="1" align="right" class="noboldtitle">Email:</td>
      <td width="179" height="1">'; if ($err==5) { echo '<font class="red">Invalid email address</font><br>'; } echo '<input type="text" name="email" size="24" value="'.$email.'"></td>
    </tr>
    <tr>
      <td width="100%" height="1" align="right" colspan="2">
        <p align="center"><input type="submit" class="button" value="Signup">&nbsp;<input type="reset" class="button" value="Reset"></td>
    </tr>
  </table>
</form>
<p>[<a href="index.php">Back to '.$title.' index</a>] [<a href="index.php?p=help" target="_blank">Help!</a>]</p>';
?>