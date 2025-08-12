<?php 
setcookie ("email", "none", time() - 3600);
setcookie ("pass", "none", time() - 3600);

setcookie ("email");
setcookie ("pass");

setcookie ("email");
setcookie ("pass");

print "<a href='/logout.php'>( Click here if you are not automatically redirected....</a> )";
print "<meta http-equiv='refresh' content='0; url=/logout.php'>";
?>