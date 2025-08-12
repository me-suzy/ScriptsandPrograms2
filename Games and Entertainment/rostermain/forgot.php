<?
echo '<form method="post" action="index.php?log=forgot&&user='.$_GET['user'].'">';
echo '<input type="text" name="mailpass" style="font-size:10px;border:solid 1px;">';
echo '<input type=image src="buttons\send.gif" value="send" style="font-size:10px";>';
echo '</form>';

//script written by ryan marshall of irealms.co.uk
if (!$_POST['mailpass'])
{
echo "<div class=\"log\">please enter your email address.</div>";
}
elseif (isset($_POST['mailpass'])) 
{ 
   $mailuser = $_GET['user'];
   $mailpass = $_POST['mailpass'];
  //extract email address and password 
   $query = "select email,passwd from users where email='$mailpass' AND username='$mailuser'"; 
   $result = mysql_query($query, $db_conn) or die('query failed'); 
   $row = mysql_fetch_assoc($result);
//mail user password 
   if (mysql_num_rows($result) >0 ) 
   { 
     // $email = mysql_result($result, 'email'); 
	  $email = $row['email'];
	  $pass = $row['passwd'];
      $from = "from: osm@opensourcemanagement.com \r\n"; 
      $mesg = "your password is $pass \r\n";
	  echo '<p class="main">Mail sent</p>'; 
      mail($email, $from, $mesg) or die('mail not sent'); 
      
   } 
   else 
      echo '<p class="main">record not found</p>'; 
} 
else 
   echo 'invalid request'; 
?> 
