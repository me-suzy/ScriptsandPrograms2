<?php
require('db_connect.php');
?>

<html>
<head>
<p><font color="#D68960"></font></p>
<body bgcolor="#48498C">
<p align="center"><img src="gift.jpg" 
<br><br><br>
<?php

echo "<center>"; echo "<font color='#ffffff'>Logged in as: "; echo "$_SESSION[username]";?> &nbsp;&nbsp;&nbsp <? echo date(" d M Y");

?>
<br>
</head>
<body>




<br>


<a href="login.php"><font color="#D68960">Login</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="index.php"><font color="#D68960">Main Page</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="addgift.php"><font color="#D68960">Add gift</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="displaymylist.php"><font color="#D68960">My Giftlist</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="buylist.php"><font color="#D68960">My Buy List</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="update.php"><font color="#D68960">My Profile</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="profilelist.php"><font color="#D68960">All User Profiles</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="showfiles.php"><font color="#D68960">View Other Giftlists</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="logout.php"><font color="#D68960">Logout</font></a><font color="#D68960">&nbsp;&nbsp;&nbsp;&nbsp
</font>
<br><br>
 


</body>
</html>