<?php

  session_start();

  $username = $_POST['qname'];
  $password = $_POST['qpass'];

if (isset($_POST['qlogin'])) {
  if  ((!$username) || (!$password))  {
    echo "Please enter all information.<br/><a href=\"index.php\">Click Here</a>to continue.";
  } 
  else {
    include("config.php");
    $db = mysql_connect($dbhost,$dbuser,$dbpass); 
    mysql_select_db($dbname) or die("Cannot connect to database");
    $password = md5($password);
    $query = mysql_query("SELECT * FROM qlitenews_users WHERE user='$username' AND password='$password'");
    $check = mysql_num_rows($query);
    
    if ($check > 0) {
      while ($row = mysql_fetch_array($query)) {
        foreach($row AS $key => $val) {
          $$key = stripslashes($val);
        }
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        echo "You have login succesfully.<br/><a href=\"index.php\">Click Here</a> to continue.";
      }
    }
    else { echo "You could not be logged in! Make sure you enter the right username and password.<br/> <a href=\"index.php\">Click Here</a>to continue."; }
  }
}

?>