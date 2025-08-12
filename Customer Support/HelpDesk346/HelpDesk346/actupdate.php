<?
include("checksession.php");
include_once("config.php")  
?>
<HTML>
<HEAD>
<TITLE>Help Desk Account Managment</TITLE>
<link href="style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></HEAD>
<BODY BGCOLOR="#FFFFFF" TEXT="000000" >
<?  
include_once("config.php");
                                                                              
function Secure() { 

   //vars section 
//end vars section

//MYSQL DataBase Connection Sectionrequire("config.php");
	   $cnx = mysql_connect($server,$database,$databasePassword); 
	   mysql_select_db($databaseName, $cnx);		//This statement is required to select the database from the mysql server
//END Database Connection Section

	$formNameVar = $_POST["userName"];
	$userNameVar = $_POST["userName"];
	$password = $_POST["password"];
	$passwordB =$_POST["password2"];
	$Computer =$_POST["compName"];
	
	$cur= mysql_query("SELECT ID,User,Pass,ComputerName,HelpDeskAddress FROM ".DB_PREFIX."accounts WHERE User ='$userNameVar'" ) or die("Error");


        // fetch the succesive result rows 
    while( $row=mysql_fetch_row( $cur ) ) 
		{ 
			$ID= $row[0]; // get the field "UserName" 
        	$UserNameVarPlus= $row[1]; // get the field "UserName" 
        	$PasswordVarPlus= $row[2]; // get the field "Password" 
			$webpage= $row[4];
		}
		
	if ($password == "")
	{
			$password = "Wrong No Way ERROR eRoR OPS DID IT AGAIN Hopefully not one more time one two";
	}
			
			if ($Computer !== "")
			{
				$cur= mysql_query("UPDATE ".$databasePrefix."accounts SET ComputerName = '$Computer' WHERE User ='$userNameVar'" );
			}
		
//	var_dump($password);
//	var_dump($PasswordVarPlus);
//	var_dump($formNameVar);
//	var_dump($UserNameVarPlus);
//	exit;
	
  	if (( $password == $PasswordVarPlus ) && ( $formNameVar == $UserNameVarPlus))                                    //Set the Password and Username here -
	   		{
				$cur= mysql_query("UPDATE ".DB_PREFIX."accounts SET Pass = '$passwordB' WHERE User ='$userNameVar'" )
			  		or die("Invalid : " . mysql_error());
			  print "<CENTER>Password has been updated!<br></CENTER>";
		/*	$fp = fopen( $webpage, "r" ) or die ("Couldn't open $webpage") ;   //Open the page
           while ( ! feof( $fp ) )                                                             //
          	{  
			print fgets( $fp, 1024 ) ; 
		    }//Display page in browser
  			print " ";*/
			header("location:view_users.php");
			exit;
			}
			else                                                                                            //If password or Username incorrect
   			{
   				echo "<br><br>";                                                                        //Display and error message and
   				echo "<p align=center>" ;                                                            //instructions
   				echo "<b>Authentication Failed<br><br>Please hit your 'Back' button and try again.</b>" ; 
   				echo "</p>" ;
   			}
			mysql_close( $cnx);
		
                           //Chosen web page

			}

Secure();
?>
</BODY>
</HTML>