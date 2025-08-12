<?php
//user defines variables YOU MUST SET IN ORDER FOR SCRIPT TO WORK!!!!
$usrName=databaseusername;//database user name
$dataBase=databasename;//name of database
$pwd=databasepassword;//database user password


if ($_POST[setup]){//script is being submitted, setup tables
$conn = mysql_connect ("localhost", $usrName, $pwd)//connect function
              or die(mysql_error());
    mysql_select_db($dataBase, $conn) or die(mysql_error());

//create our table structures
$sql1 = 'CREATE TABLE `linkInformation` ( `id` INT NOT NULL AUTO_INCREMENT, `user` VARCHAR( 10 ) NOT NULL , `pageName` VARCHAR( 30 ) NOT NULL , `link` VARCHAR( 75 ) NOT NULL , `description` VARCHAR( 254 ) NOT NULL , `switch` TINYINT( 1 ) DEFAULT \'0\' NOT NULL , PRIMARY KEY ( `id` ) ) '; 
$sql2 = 'CREATE TABLE `userData` ( `id` INT NOT NULL AUTO_INCREMENT, `userName` VARCHAR( 16 ) NOT NULL , `userEmail` VARCHAR( 75 ) NOT NULL , PRIMARY KEY ( `id` ) , UNIQUE ( `userName` , `userEmail` ) ) '; 
$sql3 = 'CREATE TABLE `replyComments` ( `id` INT NOT NULL AUTO_INCREMENT, `usrId` INT NOT NULL , `threadId` INT NOT NULL , `comment` TEXT NOT NULL , PRIMARY KEY ( `id` ) ) '; 
$sql4 = 'ALTER TABLE `linkInformation` ADD UNIQUE ( `link` )';  
$sql5 = 'ALTER TABLE `linkInformation` ADD UNIQUE ( `pageName` )';  



//create tables
$verify_result1 = mysql_query($sql1, $conn) or die (mysql_error());
$verify_result2 = mysql_query($sql2, $conn) or die (mysql_error());
$verify_result3 = mysql_query($sql3, $conn) or die (mysql_error());
$verify_result4 = mysql_query($sql4, $conn) or die (mysql_error());
$verify_result5 = mysql_query($sql5, $conn) or die (mysql_error());

//success
print "<html><head></head></html><body>Database has been set up<br>Please confirm that new tables exist and immediately delete this script from the server</body></html>";
}
else{//this is the first time user is visiting page
print'
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>linkShare by Richard B Mowatt::http://www.rmowatt.lbilocal.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form action="sqlSetup.php" method="post" >
<p>Welcome to the data base setup script</p>
<p>Before using this script you must create the database that this script will reference<br>
(this script does not create the database, just the tables)<br>
You must open this script and customize the information before it will work<br>
Script must reside on the same server as the database!
</p>
<p>
If for any reason you recieve an error message such as (Table \'linkInformation\' already exists),<br> open your database and drop tables named
"linkInformation", "userData" or "replyComments" and run this script again!</p>
Upon pressing the submit button below the tables neccessary for this script to run will be created in your database.<br>
It is highly recomended that after this script is run and you have confirmed its success you delete this file from your server.<br>
Failure to do so could result in data loss if any other user calls and submits this page.<br>
<input name="setup" type="hidden" value="1">
<input type ="submit" name = "submit" value = "Create Tables">
</form>
<p>
      <a href="http://validator.w3.org/check?uri=referer"><img border="0"
          src="http://www.w3.org/Icons/valid-html401"
          alt="Valid HTML 4.01!" height="31" width="88"></a>
    </p>
</body>
</html>';}
//were outta here!!!!!
?>