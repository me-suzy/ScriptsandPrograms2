<?php

include 'conection.php';

import_request_variables("gP", "r_");

$_SESSION["thispage"] = $_SERVER['SCRIPT_NAME'];



if (isset($r_username)){

$selog = mysql_query("SELECT * FROM $prefix"."users where username='$r_username' and password='$r_password'");

$num_rows = mysql_num_rows($selog);

if ($num_rows == 1){

$_SESSION["aut"] = 1 ;

$_SESSION["username"] = $r_username;

echo "Welcome $_SESSION[username]<br>";

//echo "<a href='userlogout.php'>Log out</a>";

}

else

{$_SESSION["aut"] = 0 ;

echo "Invalid username or password<br>";

echo "Login \n";

 echo "<form action=$_SERVER[SCRIPT_NAME]  method='post' \n>  ";

  echo "<input type='text' name='username' value='username'> <br>\n ";

 echo "<input type='text' name='password' value='password'> <br>\n";

 echo "<input type='submit' name='login' value='Submit'><br> \n";

 echo "</form>";

echo "<a href='register.php'>Register</a> \n";

$_SESSION["thispage"] = $_SERVER['SCRIPT_NAME'];

}

}

else{$_SESSION["aut"] = 0 ;

echo "Login \n";

 echo "<form action=$_SERVER[SCRIPT_NAME]  method='post' \n>  ";

  echo "<input type='text' name='username' value='username'> <br>\n ";

 echo "<input type='text' name='password' value='password'> <br>\n";

 echo "<input type='submit' name='login' value='Submit'><br> \n";

 echo "</form>";

echo "<a href='register.php'>Register</a> \n";



}

?>