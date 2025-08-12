<?
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
//   User authorization / Log in user

//database connection
include "../100jancms/config_inc.php";

//do md5 over input password
$password_enc=md5($_POST["password"]);

$query="SELECT username, password FROM ".$db_table_prefix."visitors WHERE username='".$_POST["username"]."' and password='$password_enc'";
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

$db_username=$row["username"];
$db_password=$row["password"];


if (($_POST["username"]<>$db_username) or ($password_enc<>$db_password)) 
	   {
       // username/password combination not found
		   //denie access
	       header("Location: test_access_denied.php");
	   }

       else
       {

           //Login ok
				//setcookie
				setcookie ("website_member", $_POST["username"], time()+86400);
		   
		   //record last login
		   $date_time=time();
		   $query = "UPDATE ".$db_table_prefix."visitors SET last_login='".$date_time."' WHERE username='".$_POST["username"]."'";
		   mysql_query($query);

           header("Location: test_index.php");
           exit;


       }

?>