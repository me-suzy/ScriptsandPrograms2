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
include "config_inc.php";

//do md5 over input password
$password_enc=md5($_POST["password"]);

$query="SELECT username, password FROM ".$db_table_prefix."users WHERE username='".$_POST["username"]."' and password='$password_enc'";
$result=mysql_query($query);
$row = mysql_fetch_array($result); //wich row

$db_username=$row["username"];
$db_password=$row["password"];


if (($_POST["username"]<>$db_username) or ($password_enc<>$db_password)) 
	   {
       // username/password combination not found
		   //denie access
	       header("Location: access_denied.php");
	   }

       else
       {

           //Login ok
           session_start(); //start session

           // here we set the flag if user loged in successfully. we check for it on every page.
           $_SESSION["auth_ok_session"]="1";

           // here we set additional data in session if needed
		   $_SESSION["current_user_username"]=$db_username;
		   
		   //record last login
		   $date_time=time();
		   $query = "UPDATE ".$db_table_prefix."users SET last_login='".$date_time."' WHERE username='".$_SESSION["current_user_username"]."'";
		   mysql_query($query);

           header("Location: mainframe.php"); //enter application
           exit;


       }

?>