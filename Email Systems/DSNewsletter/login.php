<?php 
  if(empty($_POST['usr']) || empty($_POST['pass'])) 
  {   
  include("header.php");?> 
      <b>Fill All Details </b> 
                <?php 
  } 
  else 
  { 
      //Colllect the details and validate 
      $time = time(); 
      $usr = $_POST['usr']; 
      $pass = $_POST['pass']; 
      include("config.php"); 
  if ($pass == $pass1){ 
  $auth777 = "1"; 
  }else{ 
  $auth777 = "0"; 
  } 
  if ($usr == $usr1) { 
  $auth778 = "1"; 
  }else{ 
  $auth778 = "0"; 
  } 
  if ($auth777 == "1") { 
   if ($auth778 == "1") {
  $auth779 = "1"; 
  } 
  }
       
          $cookie_data = $usr.'-'.$pass;   
                   if($auth779 == "1") {
              if(setcookie ("cookie_info",$cookie_data, $time+3600)==TRUE) 
              { 
              include("header.php"); 
                  echo "Logged in. <br> <br><a href=admin.php>Do Stuff</a>"; 
                  }else{ 
  include("header.php"); 
  echo "Authentication Failed."; 
          } 
   
                    }else{ 
  include("header.php"); 
  echo "Authentication Failed."; 
     }  
   
   }
   
  include("footer.php"); 
  ?>