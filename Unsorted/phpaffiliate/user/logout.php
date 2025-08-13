<?
  session_start();

  $old_user = $valid_user;  // store  to test if they *were* logged in
  $result = session_unregister("valid_user");
  session_destroy();

  
          include "header.290"; 
          include "../config.php"; 
   
  if (!empty($old_user))
  {
    if ($result)
    { 
      // if they were logged in and are not logged out 
      echo "Logged out.<br>";
    }
    else
    {
     // they were logged in and could not be logged out
      echo "Could not log you out.<br>";
    } 
  }
  else
  {
    // if they weren't logged in but came to this page somehow
    echo "You were not logged in, and so have not been logged out.<br>"; 
  }

 echo "<a href=\"index.php\">Back to main page</a>";
 
         include "footer.290"; 
 
 ?>

