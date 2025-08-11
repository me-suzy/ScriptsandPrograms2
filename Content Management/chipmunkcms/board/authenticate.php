<?PHP
session_start();
?>
<?PHP
include "connect.php";
if (isset($_POST['submit'])) // name of submit button
{
    $username=$_POST['user'];
    $password=$_POST['password'];
    $password=md5($password);
    $query = "select * from b_users where username='$username' and password='$password' and validated='1'"; 
    $result = mysql_query($query) or die("Could not query") ;
    $result2=mysql_fetch_array($result);
    if($result2)
    {         
      $_SESSION['user']=$username;
      if($_POST['remember'])
      {
        $memberid=$result2[userID];
        $passkey=$result2[password];
        include "admin/var.php";
        $cookie1="[0]";
        $cookie2="[1]";
        $cookie3="$cookiename$cookie1";
        $cookie4="$cookiename$cookie2";
        setcookie("$cookie3","$memberid",time()+7776000) ;
        setcookie("$cookie4","$passkey",time()+7776000) ;
      }
      print "<center>";
      print "<link rel='stylesheet' href='style.css' type='text/css'>";
      print "<table class='maintable'>";
      print "<tr class='headline'><td><center>Logging In</center></td></tr>";
      print "<tr class='forumrow'><td><center>";           
      print "logged in successfully.";
      print "Redirecting to forum Index <META HTTP-EQUIV = 'Refresh' Content = '2; URL =index.php'></center>";
      print "</td></tr></table></center>";
    }
    else
    { 
      print "<center>";
      print "<link rel='stylesheet' href='style.css' type='text/css'>";
      print "<table class='maintable'>";
      print "<tr class='headline'><td><center>Logging In</center></td></tr>";
      print "<tr class='forumrow'><td><center>";   
      print "Wrong username or password or unactivated account, redirecting back to login page... <META HTTP-EQUIV = 'Refresh' Content = '2; URL =login.php'></center>";
      print "</td></tr></table></center>";
    }
}

?>