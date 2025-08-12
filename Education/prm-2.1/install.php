<?php ob_start() ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>progress report manager installation</title>
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
<table align="center" width="100%"><tr><td align="center">Installation has already been configured</td></tr></table>

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
	$createtables[0]="DROP table if exists grades,disciplines,reports,segments,semesters, students, teachers, students_subjects";
	$createtables[1]="CREATE TABLE disciplines (
  discipline_id int(11) NOT NULL auto_increment,
  discipline varchar(200) NOT NULL default '',
  PRIMARY KEY  (discipline_id))";
	$createtables[2]="CREATE TABLE grades(
  bottom_grade int(11) NOT NULL default '6',
  top_grade int(11) NOT NULL default '8',
  PRIMARY KEY  (bottom_grade,top_grade))";
		  
$createtables[3] ="CREATE TABLE reports (
  report_id int(11) NOT NULL auto_increment,
  week date NOT NULL default '0000-00-00',
  student_id int(11) NOT NULL default '0',
  semester_id int(11) default NULL,
  access_code varchar(200) default NULL,
  parent_sent_date date default NULL,
  viewed date default '0000-00-00',
  teacher_sent_date date default NULL,
  PRIMARY KEY  (report_id))";
$createtables[4] ="CREATE TABLE segments (
  segment_id int(11) NOT NULL auto_increment,
  discipline_id int(11) NOT NULL default '0',
  teacher_id int(11) NOT NULL default '0',
  assignmentsYN char(1) default NULL,
  behaviorYN char(1) default NULL,
  comments text,
  report_id int(11) NOT NULL default '0',
  access_code varchar(200) default NULL,
  PRIMARY KEY  (segment_id))";  

$createtables[5] ="CREATE TABLE semesters (
  semester_id int(11) NOT NULL auto_increment,
  begins_on date default NULL,
  ends_on date default NULL,
  name varchar(200) default NULL,
  PRIMARY KEY  (semester_id))";
$createtables[6] ="CREATE TABLE students (
  student_id int(11) NOT NULL auto_increment,
  last_name varchar(200) NOT NULL default '',
  first_name varchar(200) NOT NULL default '',
  grade int(11) default NULL,
  details text,
  parent_emails text,
  PRIMARY KEY  (student_id))";

$createtables[7] = "CREATE TABLE students_subjects (
  student_id int(11) NOT NULL default '0',
  discipline_id int(11) NOT NULL default '0',
  teacher_id int(11) NOT NULL default '0',
  PRIMARY KEY  (student_id,discipline_id,teacher_id))";
$createtables[8] ="CREATE TABLE teachers (
  teacher_id int(11) NOT NULL auto_increment,
  email varchar(200) NOT NULL default '',
  passcode varchar(200) NOT NULL default '',
  first_name varchar(200) NOT NULL default '',
  last_name varchar(200) default NULL,
  security_code varchar(200) default NULL,
  PRIMARY KEY  (teacher_id))";


$createtables[9] = "INSERT INTO teachers VALUES 
(1,'".$_POST['email']."','".$_POST['pword']."','".$_POST['fname']."','".$_POST['lname']."',0)";

$createtables[10] = "INSERT INTO grades VALUES (6,8)";

for ($x=0;$x<11;$x++){
mysql_query($createtables[$x]) or die($x." Could not create database tables</body></html>");
}


$filename = 'settings.php';
$somecontent = "<?php \n
\$GLOBALS['configured'] = true;//WARNING IF THIS IS FALSE SOMEONE CAN RUN THE INSTALL\n 
\$GLOBALS['dbServer'] = '".$_POST['dbserver']."';\n
\$GLOBALS['dbName'] = '".$_POST['dbname']."';\n
\$GLOBALS['dbUser'] = '".$_POST['dbuname']."';\n
\$GLOBALS['dbPass'] = '".$_POST['dbpass']."';\n
\$GLOBALS['title'] = '".$_POST['title']."';\n
\$GLOBALS['rootURL'] = '".$_POST['root']."';\n
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
	<tr><td colspan="2" align="center">To install enter the appropriate information below
	<div align='left'>
<strong><ul><li> You must have an existing (empty)
 database created before installation</li><p><li>Please read the <a href="readme.txt">help file</a> for information on installation.  If you install over an existing copy, you will overwrite the database</li></ul></strong></div><br>
       
 </td></tr>
	<tr><td align="right">database server name:</td><td><input type="text" name="dbserver"></td></tr>
	<tr><td align="right">database name:</td><td><input type="text" name="dbname"></td></tr>
	<tr><td align="right">database username:</td><td><input type="text" name="dbuname"></td></tr>
	<tr><td align="right">database password:</td><td><input type="text" name="dbpass"></td></tr>
	<tr><td align="right">your first name (required):</td><td><input type="text" name="fname"></td></tr>
	<tr><td align="right">your last name (required):</td><td><input type="text" name="lname"></td></tr>
	<tr><td align="right">your email (required):</td><td><input type="text" name="email"></td></tr>
	<tr><td align="right">your password (required):</td><td><input type="text" name="pword"></td></tr>
	<tr><td align="right">website title:</td><td><input type="text" name="title" value="[your school] progress report manager" size=50"></td></tr>
	<tr><td align="right">website root:</td><td><input type="text" name="root" value="http://[enter url]" size="50"></td></tr>
<tr><td colspan="2" align="center"><input name="submit" type="submit" value=" install "></td></tr>
</table>
</form>