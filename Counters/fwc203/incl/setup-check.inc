<?php
echo '<h1>New account created!</h1>
Thank you <b>'.$name.'</b>!<br>You have successfully created an account. You can now login and manage your counters.<br><br>
Your account username and password have been emailed to <b>'.$email.'</b>. It is crucial that you remember your password. Forgotten passwords CANNOT be retrieved.

<p><input type="button" class="button" value="Click here to Login Now!" onclick="javascript:window.location=\'login.php\'"></p>
<p>[<a href="index.php">Back to '.$title.' index</a>] [<a href="index.php?p=help" target="_blank">Help!</a>]</p>';
?>