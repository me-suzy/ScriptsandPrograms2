<?
// ************************************************************************************
// Program File Name : config.inc.php
// Programmer 		 : Benjamin Lim 
// Email             : ben@benruth.com
//
// Purpose :
// Configuration settings for the instant report
//
// Date Completed : 13 Jul 2004
//
// Copyright 2004 BENRUTH SOFTWARE CONSULTANCY ( www.benruth.com )
// ************************************************************************************

// DEBUG MODE
// **********
// 1 is for debug mode, anything else is normal run
// gets to see the complete SQL query
$iDebug = 0; 

// DATABASE SETTINGS
// *****************
$db_host = "localhost";
$db_name = "";
$db_user = "";
$db_password = "";

// SQL QUERY
// *********
//
// Assuming the table structure looks like this
//
// tblStudent
// - ID
// - FirstName
// - LastName
// - Address
// - ContactNo
//
// tblClass
// - ID
// - ClassName
// - StudentID
//
// The above is for a Student-Class entity relationship.

// How the query is constructed
// ****************************
// "SELECT ".$SELECT." FROM ".$FROM." WHERE ".$WHERE." " . $OTHERS;

// Sample Usage
// $SELECT = "*";
// $SELECT = "A.ID, `FirstName`, `LastName`, `Address`, `ContactNo`, B.ClassName";
$SELECT = "";

// Sample Usage
// $FROM = "tblStudent";
// $FROM = "tblStudent A, tblClass B";
$FROM = "";

// Sample Usage
// $WHERE = "1"; 
// $WHERE = "B.StudentID = A.ID";
$WHERE = "";

// Sample Usage
// $OTHERS = "ORDER BY FirstName ASC, LastName ASC";
$OTHERS = "";

// REPORT FORMATTING
// *****************

// 1 is for show, anything else is no show
$iShowHeader = 0;

// just fill in and expand more if needed

// Sample Usage
// $saHeaders = array("ID","First Name","Last Name","Address","Contact No.","Class Name"); 
$saHeaders = ""; // array("","","","","",""); 

// filename without the extension, default is 'instantreport'
$sFileName = "";

// 1 is for to stamp the date to the file, anything else is no stamp, shows something like filenameDD_MM_YYYY.xls
$iDateStampToFile = 1;

// "EXCEL", "HTML", default is EXCEL if left blank, funny entries will get normal HTML
$sType = "EXCEL";

?>