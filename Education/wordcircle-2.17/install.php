<?php ob_start() ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>wordcircle</title>
	<style>td,body{font-family:Verdana, Arial, sans-serif;font-size:11px;background-color:#ededed;}</style>
</head>
<body><br>
<br>
<br>

<?php

include ("settings.php");
if ($GLOBALS['configured'] == true){
echo('
<br>
<br>
<table align="center" width="100%"><tr><td align="center">This wordcircle installation has already been configured</td></tr></table>

</body>
</html>');
exit;
}

if (isset($_POST['submit']) and strlen($_POST['email'])>3){
	$db = mysql_connect($_POST['dbserver'], $_POST['dbuname'], $_POST['dbpass']) or die("Could not connect to database</body></html>");
	if(!$db) 
		die("Could not connect to database</body></html>");
if(!mysql_select_db($_POST['dbname'],$db))
 	die("Could not connect to database</body></html>");
	
	$createtables[0]="drop table if exists calendar";
	$createtables[1]="CREATE TABLE calendar (                                                                                                                                                                                      
            calendar_id int(11) NOT NULL auto_increment,                                                                                                                                                               
            group_id int(11) default NULL,                                                                                                                                                                             
            calendar text,                                                                                                                                                                                             
            dateOf datetime default NULL,                                                                                                                                                                              
            PRIMARY KEY  (calendar_id)                                                                                                                                                                                 
          ) ";
		  
$createtables[2] ="drop table if exists categories";
$createtables[3] ="CREATE TABLE categories (                                                                                                                                                                                                                    
              category_id int(11) NOT NULL auto_increment,                                                                                                                                                                                               
              category_name varchar(120) default NULL,                                                                                                                                                                                                   
              group_id int(11) default NULL,                                                                                                                                                                                                             
              order_number int(11) default '0',                                                                                                                                                                                                          
              PRIMARY KEY  (category_id)                                                                                                                                                                                                                 
            )";  

$createtables[4] ="drop table if exists discussions";
$createtables[5] ="CREATE TABLE discussions (                                                                                                                                                                                                                                                                                                       
               discussion_id int(11) NOT NULL auto_increment,                                                                                                                                                                                                                                                                                 
               discussion_name varchar(100) default NULL,                                                                                                                                                                                                                                                                                     
               group_id int(11) default NULL,                                                                                                                                                                                                                                                                                                 
               last_message date default NULL,                                                                                                                                                                                                                                                                                                
               total_messages int(11) default NULL,                                                                                                                                                                                                                                                                                           
               category_id int(11) default NULL,                                                                                                                                                                                                                                                                                              
               PRIMARY KEY  (discussion_id)                                                                                                                                                                                                                                                                                                   
             )";

$createtables[6] = "drop table if exists documents";
$createtables[7] ="CREATE TABLE documents (                                                                                                                                                                                                                                          
             id int(11) unsigned NOT NULL auto_increment,                                                                                                                                                                                                                    
             name varchar(255) NOT NULL default '',                                                                                                                                                                                                                          
             group_id int(11) default NULL,                                                                                                                                                                                                                                  
             user_id int(11) default NULL,                                                                                                                                                                                                                                   
             descr varchar(255) default NULL,                                                                                                                                                                                                                                
             PRIMARY KEY  (id)                                                                                                                                                                                                                                               
           )  ";

$createtables[8] = "drop table if exists group_message";
$createtables[9] ="CREATE TABLE group_message (                                                                                                             
                 group_id int(11) NOT NULL default '0',                                                                                                 
                 group_message text,                                                                                                                    
                 PRIMARY KEY  (group_id)                                                                                                                
               )";


$createtables[10] = "drop table if exists groups;";
$createtables[11] ="CREATE TABLE groups (
  group_id int(11) NOT NULL auto_increment,
  group_name varchar(130) NOT NULL default '',
  owner_id int(11) NOT NULL default '0',
  code varchar(130) default NULL,
  public int(11) default '0',
  PRIMARY KEY  (group_id))";
  
  $createtables[12] = "drop table if exists messages";
  $createtables[13] = "CREATE TABLE messages (                                                                                                                                                                                                                                                                                                                                                                                                                                                        
            message_id int(11) NOT NULL auto_increment,                                                                                                                                                                                                                                                                                                                                                                                                                                  
            message text NOT NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            created_on datetime NOT NULL default '0000-00-00 00:00:00',                                                                                                                                                                                                                                                                                                                                                                                                                  
            discussion_id int(11) default '0',                                                                                                                                                                                                                                                                                                                                                                                                                                           
            group_id int(11) default NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                               
            topic_id int(11) default NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                               
            created_by int(11) default NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                             
            created_for int(11) default NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                            
            privateYN char(2) default NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                              
            project_id int(11) default NULL,                                                                                                                                                                                                                                                                                                                                                                                                                                             
            PRIMARY KEY  (message_id)                                                                                                                                                                                                                                                                                                                                                                                                                                                    
          ) ";

$createtables[14] = "drop table if exists project_action";
$createtables[15] = "CREATE TABLE project_action (                                                                                                                                                                                                                                                           
                  action_id int(11) NOT NULL auto_increment,                                                                                                                                                                                                                                            
                  project_id int(11) NOT NULL default '0',                                                                                                                                                                                                                                              
                  user_id int(11) NOT NULL default '0',                                                                                                                                                                                                                                                 
                  last_action datetime default NULL,                                                                                                                                                                                                                                                    
                  group_id int(11) default NULL,                                                                                                                                                                                                                                                        
                  PRIMARY KEY  (action_id)                                                                                                                                                                                                                                                              
                ) ";	
				
$createtables[16] = "drop table if exists projects";
$createtables[17] = "CREATE TABLE projects (                                                                                                                                                                                                                                                                                                         
            project_id int(11) NOT NULL auto_increment,                                                                                                                                                                                                                                                                                   
            project_name varchar(255) default NULL,                                                                                                                                                                                                                                                                                       
            project text,                                                                                                                                                                                                                                                                                                                 
            group_id int(11) default NULL,                                                                                                                                                                                                                                                                                                
            globalYN char(2) default 'Y',                                                                                                                                                                                                                                                                                                 
            last_action datetime default NULL,                                                                                                                                                                                                                                                                                            
            owner_id int(11) default NULL,                                                                                                                                                                                                                                                                                                
            PRIMARY KEY  (project_id)                                                                                                                                                                                                                                                                                                     
          ) ";						  
		  
$createtables[18] = "drop table if exists thoughts";
$createtables[19] = "CREATE TABLE thoughts (
  thoughts_id int(11) NOT NULL auto_increment,
  thoughts text,
  created_on date default NULL,
  group_id int(11) default NULL,
  PRIMARY KEY  (thoughts_id)
) ";

$createtables[20] = "drop table if exists user_projects";
$createtables[21] = "CREATE TABLE user_projects (                                                                                                                                             
                 project_id int(11) NOT NULL default '0',                                                                                                                               
                 user_id int(11) NOT NULL default '0',                                                                                                                                  
                 PRIMARY KEY  (project_id,user_id)                                                                                                                                    
               )";

 $createtables[22] = "drop table if exists topics";
 $createtables[23] = "CREATE TABLE topics (
  topic_id int(11) NOT NULL auto_increment,
  topic text,
  created_by int(11) default NULL,
  discussion_id int(11) default NULL,
  last_message date default NULL,
  group_id int(11) default NULL,
  PRIMARY KEY  (topic_id)
) ";


$createtables[24] = "drop table if exists user_discussions";
$createtables[25] = "CREATE TABLE user_discussions (
  user_id int(11) NOT NULL default '0',
  discussion_id int(11) NOT NULL default '0',
  PRIMARY KEY  (user_id,discussion_id)
) ";

$createtables[26] = "drop table if exists user_groups";
$createtables[27] = "CREATE TABLE user_groups (
  user_id int(11) NOT NULL default '0',
  group_id int(11) NOT NULL default '0',
  PRIMARY KEY  (user_id,group_id)
)"; 

$createtables[28] = "drop table if exists users";
$createtables[29] = "CREATE TABLE users (
  user_id int(11) NOT NULL auto_increment,
  first_name varchar(80) NOT NULL default '',
  last_name varchar(80) NOT NULL default '',
  email varchar(80) NOT NULL default '',
  pword varchar(80) NOT NULL default '',
  user_level int(11) NOT NULL default '0',
  question int(11) NOT NULL default '0',
  answer varchar(120) NOT NULL default '',
  security_code varchar(50) NOT NULL default '',
  PRIMARY KEY  (user_id)
) ";

$createtables[30] = "INSERT INTO users VALUES 
(1,'".$_POST['fname']."','".$_POST['lname']."','".$_POST['email']."','".$_POST['pword']."',0,0,'','')";

for ($x=0;$x<31;$x++){
mysql_query($createtables[$x]) or die($x." Could not create database tables</body></html>");
}


$filename = 'settings.php';
$somecontent = "<?php \n
\$GLOBALS['configured'] = true;//WARNING IF THIS IS FALSE SOMEONE CAN RUN THE INSTALL\n 
\$GLOBALS['dbServer'] = '".$_POST['dbserver']."';\n
\$GLOBALS['dbName'] = '".$_POST['dbname']."';\n
\$GLOBALS['dbUser'] = '".$_POST['dbuname']."';\n
\$GLOBALS['dbPass'] = '".$_POST['dbpass']."';\n
\$GLOBALS['admin_email'] = '".$_POST['email']."';\n
\n?>
";

// Let's make sure the file exists and is writable first.
if (is_writable($filename)) {

   // In our example we're opening $filename in append mode.
   // The file pointer is at the bottom of the file hence 
   // that's where $somecontent will go when we fwrite() it.
   if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open file ($filename) </body></html>";
         exit;
   }

   // Write $somecontent to our opened file.
   if (fwrite($handle, $somecontent) === FALSE) {
       echo "Cannot write to file ($filename) </body></html>";
       exit;
   }
   
   echo "<table width='100%' align='center'><tr><td align='center'>Successful Installation <br>
   <br>
   <a href='index.php'>Click here to begin</a></td></tr></table></body></html>";
   exit;
   
   fclose($handle);
                   
} else {
   echo "The file $filename is not writable </body></html>";
}

}

?>
<form action="install.php" method="post">

<table align="center" width="600">
	<tr><td colspan="2" align="center">To install wordcircle, enter the appropriate information below
	<div align='left'>
<strong><ul><li> You must have an existing (empty)
 database created before installation</li><p><li>If you have already installed wordcircle, please read the <a href="e_help.html">help file</a> for information on upgrading.  If you install over an existing copy, you will overwrite the database</li></ul></strong></div><br>
       
 </td></tr>
	<tr><td align="right">database server name:</td><td><input type="text" name="dbserver"></td></tr>
	<tr><td align="right">database name:</td><td><input type="text" name="dbname"></td></tr>
	<tr><td align="right">database username:</td><td><input type="text" name="dbuname"></td></tr>
	<tr><td align="right">database password:</td><td><input type="text" name="dbpass"></td></tr>
	<tr><td align="right">admin's first name (required):</td><td><input type="text" name="fname"></td></tr>
	<tr><td align="right">admin's last name (required):</td><td><input type="text" name="lname"></td></tr>
	<tr><td align="right">admin's email (required):</td><td><input type="text" name="email"></td></tr>
	<tr><td align="right">admin's password (required):</td><td><input type="text" name="pword"></td></tr>
<tr><td colspan="2" align="center"><input name="submit" type="submit" value=" install "></td></tr>
</table>
</form>