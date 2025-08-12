<?php

class Configurator {

function checkTyped($nameToCheck,$errorMessage){
  if (strlen($nameToCheck) < 1){
  	array_push($GLOBALS['error'],$errorMessage);
	return false;
   }else{
   return true;
  }
 }
  
   	function compareTwo($nameToCheck,$nameToCheck2,$errorMessage){
  	if (trim($nameToCheck) <> trim($nameToCheck2)){
  	array_push($GLOBALS['error'],$errorMessage);
	return false;
   }else{
   return true;
   }
  } 
  
function configure(){

	$errorCode = 0;

	$this->checkTyped($_POST['dbserver'],"You must enter a database server");
	$this->checkTyped($_POST['dbname'],"You must enter a database name");
	$this->checkTyped($_POST['dbuser'],"You must enter a database user");
	$this->checkTyped($_POST['dbpass'],"You must enter a database password");
	$this->checkTyped($_POST['school'],"You must enter a school name");
	$this->checkTyped($_POST['first_name'],"You must enter a database name");
	$this->checkTyped($_POST['last_name'],"You must enter a database name");
	$this->checkTyped($_POST['email'],"You must enter a database name");
	$this->checkTyped($_POST['pass'],"You must enter a database name");
	$this->compareTwo($_POST['pass'],$_POST['pass2'],"Passwords do not match");
	$this->compareTwo($_POST['email'],$_POST['email2'],"Emails do not match");
	
	if(count($GLOBALS['error'])==0)
	{
	$db1 = mysql_connect($_POST['dbserver'],$_POST['dbuser'],$_POST['dbpass']) or $errorCode="could not connect to database";
	if(!$db1) 
	$errorCode="could not connect to database";
	if(!mysql_select_db($_POST['dbname'],$db1))
	$errorCode="could not connect to database";
	
	mysql_query("DROP TABLE IF EXISTS archives") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE archives (
  archive_id int(10) unsigned NOT NULL auto_increment,
  archive_name char(255) default NULL,
  file_name char(255) default NULL,
  PRIMARY KEY  (archive_id)
)") or $errorCode = mysql_error();

	mysql_query("DROP TABLE IF EXISTS disciplines") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE disciplines (
  discipline_id int(11) NOT NULL auto_increment,
  discipline varchar(200) NOT NULL default '',
  PRIMARY KEY  (discipline_id)
)") or $errorCode = mysql_error();



mysql_query("DROP TABLE IF EXISTS floodlog") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE floodlog (
  ip_address char(100) NOT NULL default '',
  flood_stamp datetime NOT NULL default '0000-00-00 00:00:00',
  flooding char(10) default NULL,
  flood_id int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (flood_id)
)") or $errorCode = mysql_error();

mysql_query("DROP TABLE IF EXISTS floodlog") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE grades (
  bottom_grade int(11) NOT NULL default '6',
  top_grade int(11) NOT NULL default '8',
  PRIMARY KEY  (bottom_grade,top_grade)
)") or $errorCode = mysql_error();


mysql_query("DROP TABLE IF EXISTS reports") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE reports (
  report_id int(11) NOT NULL auto_increment,
  week date NOT NULL default '0000-00-00',
  student_id int(11) NOT NULL default '0',
  semester_id int(11) default NULL,
  access_code varchar(200) default NULL,
  parent_sent_date date default NULL,
  viewed date default '0000-00-00',
  teacher_sent_date date default NULL,
  PRIMARY KEY  (report_id)
)") or $errorCode = mysql_error();


mysql_query("DROP TABLE IF EXISTS segments") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE segments (
  segment_id int(11) NOT NULL auto_increment,
  discipline_id int(11) NOT NULL default '0',
  teacher_id int(11) NOT NULL default '0',
  assignmentsYN char(1) default NULL,
  behaviorYN char(1) default NULL,
  comments text,
  report_id int(11) NOT NULL default '0',
  access_code varchar(200) default NULL,
  parent_comments text,
  PRIMARY KEY  (segment_id)
)") or $errorCode = mysql_error();

mysql_query("DROP TABLE IF EXISTS semesters") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE semesters (
  semester_id int(11) NOT NULL auto_increment,
  begins_on date default NULL,
  ends_on date default NULL,
  name varchar(200) default NULL,
  PRIMARY KEY  (semester_id)
)") or $errorCode = mysql_error();


mysql_query("DROP TABLE IF EXISTS students") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE students (
  student_id int(11) NOT NULL auto_increment,
  last_name varchar(200) NOT NULL default '',
  first_name varchar(200) NOT NULL default '',
  grade int(11) default NULL,
  details text,
  parent_emails text,
  inactive int(10) default NULL,
  security_code varchar(200) default NULL,
  PRIMARY KEY  (student_id)
)") or $errorCode = mysql_error();

mysql_query("DROP TABLE IF EXISTS students_subjects") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE students_subjects (
  student_id int(11) NOT NULL default '0',
  discipline_id int(11) NOT NULL default '0',
  teacher_id int(11) NOT NULL default '0',
  PRIMARY KEY  (student_id,discipline_id,teacher_id)
)") or $errorCode = mysql_error();

mysql_query("DROP TABLE IF EXISTS teachers") or $errorCode = mysql_error();
	
	mysql_query("CREATE TABLE teachers (
  teacher_id int(11) NOT NULL auto_increment,
  email varchar(200) NOT NULL default '',
  passcode varchar(200) NOT NULL default '',
  first_name varchar(200) NOT NULL default '',
  last_name varchar(200) default NULL,
  security_code varchar(200) default NULL,
  PRIMARY KEY  (teacher_id)
)") or $errorCode = mysql_error();

$length=7;
$list="23456789ABCEFGHJKLMNPQRSTUVWXYZ";
mt_srand((double)microtime()*1000000);
		$thoughtstring="";
		if($length>0){
		while(strlen($thoughtstring)<$length){
		$thoughtstring.=$list[mt_rand(0, strlen($list)-1)];
		}
		}
	
mysql_query("INSERT INTO teachers (first_name,last_name,email,passcode,security_code) VALUES 
('".trim($_POST['first_name'])."','".trim($_POST['last_name'])."','".trim($_POST['email'])."','".trim($_POST['pass'])."','".$thoughtstring."')") or $errorCode = mysql_error();
	
	if ($errorCode <> "0"){
	   $error = $errorCode;
	  $GLOBALS['error'][0] = $error;
	 }
	
$filename = 'settings.php';
$somecontent = "<?php \n
\$GLOBALS['configured'] = true;//WARNING IF THIS IS FALSE SOMEONE CAN RUN THE INSTALL\n 
\$GLOBALS['dbserver'] = '".$_POST['dbserver']."';\n
\$GLOBALS['dbname'] = '".$_POST['dbname']."';\n
\$GLOBALS['dbuser'] = '".$_POST['dbuser']."';\n
\$GLOBALS['dbpass'] = '".$_POST['dbpass']."';\n
\$GLOBALS['school'] = '".$_POST['school']."';\n
\$GLOBALS['admin'] = '".$_POST['email']."';\n
\n?>
";

// Let's make sure the file exists and is writable first.
if(count($GLOBALS['error'])==0){
if (is_writable($filename)) {

   // In our example we're opening $filename in append mode.
   // The file pointer is at the bottom of the file hence 
   // that's where $somecontent will go when we fwrite() it.
   if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open file ($filename)";
         exit;
   }

   // Write $somecontent to our opened file.
   if (fwrite($handle, $somecontent) === FALSE) {
       echo "Cannot write to file ($filename)";
       exit;
   }
   }//en is writeable line 102
	}//end embedded no errors line 101
   }//end if no errors
		
	}//end function configure
	
}
?>
