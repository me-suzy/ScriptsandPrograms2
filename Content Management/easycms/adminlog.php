<?php
     import_request_variables("gP", "r_");
   session_start() ;
   
  include 'conection.php';




   if (isset($r_submit))
    {

   $check=mysql_query("select username, password from $prefix"."users where id=1");
   $checkrow = mysql_fetch_array($check)  ;
   if  ($r_username<> $checkrow['username'] or $r_password<> $checkrow['password']){
   echo "Incorrect username or password<br>";}
   else {
   $_SESSION["aut"] = 1  ;

     header("Location: admin.php");
  //echo "<a href=admin.php?aut=1>ENTER</a>";

   } }
   
   else{

echo"<html>";
echo"<head><title>Administration Login</title>";
echo"<body bgcolor='#66CCFF' >";

echo"<link rel='stylesheet' type='text/css'  href='style.css' /> </head>\n";


   echo "<form action=adminlog.php method='post'> \n";
   echo "<input type='text' name='username' >Username<br>";
   echo "<input type='password' name='password' >Password<br>";
   echo "<input type='submit' name='submit' value='Submit'> \n";
   echo "</form>";
echo "</body>";
echo"  </html>";


  }

 ?>
