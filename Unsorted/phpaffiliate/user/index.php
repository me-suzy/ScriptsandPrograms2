<?

session_start();

if ($userid && $password)
{
 include "../config.php";
	  // if the user has just tried to log in

  $db_conn = mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 11)"); 
  mysql_select_db($database, $db_conn);
  $query = "select * from affiliates where refid='$userid' and pass='$password'";
  $result = mysql_query($query, $db_conn);
  if (mysql_num_rows($result) >0 )
  {
    // if they are in the database register the user id
    $valid_user = $userid;
    session_register("valid_user");
  }
}
 
        include "header.290"; 
 

  if (session_is_registered("valid_user"))
  {
    echo "You are logged in as: $valid_user <br>";
    echo "<a href=\"logout.php\">Log out</a><br>";
  }
  else
  {
    if (isset($userid))
    {
      // if they've tried and failed to log in
      echo "Could not log you in";
    }
    else 
    {
      // they have not tried to log in yet or have logged out
      echo "You are not logged in.<br>";
    }

    // provide form to log in 
    echo "<form method=post action=\"index.php\">";
    echo "<table align=center border=0>";
    echo "<tr><td>Affiliate Id:</td>";
    echo "<td><input type=text name=userid></td></tr>";
    echo "<tr><td>Password:</td>";
    echo "<td><input type=password name=password></td></tr>";
    echo "<tr><td colspan=2 align=center>";
    echo "<input type=submit value=\"Log in\"></td></tr>";
    echo "</table></form>";
  }
?>
<br>
Logged In? <a href="members_only.php">Enter Here</a>

</p>
<br>
      
   
<?PHP         

include "footer.290"; 

?>