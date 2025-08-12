<?php
/* 
	CascadianFAQ v4.1 - Last Updated: November 2003
	Summer S. Wilson, Eclectic Designs, http://eclectic-designs.com
	cfaq@eclectic-designs.com
*/

// Include configuration and database files

include ("config.php");
include ("functions.php");
ConnectToDatabase();

$query = "CREATE TABLE cfaq_admin (
  username varchar(15) PRIMARY KEY,
  password varchar(15),
  firstname varchar(25),
  lastname varchar (25),
  email varchar(255),
  accesslevel INT2
) COMMENT='Holds administrator information, including logins'";
$update = run_my_query( $query, "Error creating the cfaq_admin table");

echo("cfaq_admin table created!");

$query = "INSERT INTO cfaq_admin (username, password, firstname, lastname, email, accesslevel)
VALUES ('admin','admin','Demo','Demo','cfaq@eclectic-designs.com','1');";
$update = run_my_query( $query, "Error inserting the initial admin");

// Create admin to cat table
$query = "CREATE TABLE cfaq_admintocats (
	connectionid int4 PRIMARY KEY AUTO_INCREMENT,
	username varchar(15),
	catid int4
);";
$update = run_my_query( $query, "Error creating the cfaq_admintocats table");

$query = "CREATE TABLE cfaq_qandas (
	qid int4 PRIMARY KEY AUTO_INCREMENT,
	question varchar(255),
	answer blob,
	dateadded date,
	viewed int4 DEFAULT 0
) COMMENT='Questions and answers in FAQ'";
$update = run_my_query( $query, "Error creating the cfaq_qandas table");

echo("cfaq_qandas table created!");

$query = "CREATE TABLE cfaq_submissions (
	submissionid int4 PRIMARY KEY AUTO_INCREMENT,
	question varchar(255),
	datesubmitted date,
	suggestedcat int4 DEFAULT 0, 
	submittername varchar(100), 
	submitteremail varchar(255), 
	submitterip varchar(20) 
) COMMENT='Questions submitted to the FAQ'";
$update = run_my_query( $query, "Error creating the cfaq_submissions table");

echo("cfaq_submissions table created!");

$query = "CREATE TABLE cfaq_cats (
	catid int4 PRIMARY KEY AUTO_INCREMENT,
	cat varchar(25),
	description varchar(255),
	maincat int4 DEFAULT 0
) COMMENT='Category lists; maincat enables multiple levels of cats'";
$update = run_my_query( $query, "Error creating the cfaq_cats table");

echo("cfaq_cats table created!");

$query = "CREATE TABLE cfaq_whichcats (
	connectionid int4 PRIMARY KEY AUTO_INCREMENT,
	catid int4,
	qid int4
) COMMENT='Connector table that links questions to categories'";
$update = run_my_query( $query, "Error creating the cfaq_whichcats table");

echo("cfaq_whichcats table created!");
?>

Installation complete.  You should now login to the <a href="admin.php">CascadianFAQ admin</a> area and change the initial login of admin/admin to something more secure.  You should also delete this file from your server!