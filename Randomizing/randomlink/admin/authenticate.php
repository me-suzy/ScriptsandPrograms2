<?php

include "connect.php";

if (isset($_POST['submit'])) // name of submit button
{
    $linkadmin=$_POST['linkadmin'];
    $password=$_POST['password'];
    $password=md5($password);
    $query = "select * from rl_admins where username='$linkadmin' and password='$password'"; 
    $result = mysql_query($query) ;
    
    $isAuth = false; //set to false originally
    
    while($row = mysql_fetch_array($result))
    {
        if($row['username'] === $linkadmin) 
        {
            $isAuth = true;
             session_start();
             $_SESSION['linkadmin']=$linkadmin;
        }  
    }  
    
    if($isAuth)
    {
                print "logged in successfully<br><br>";
                print "<A href='index.php'>Go to Admin Panel</a>";
}
else
{
  print "Wrong username or password";
}
}

?>