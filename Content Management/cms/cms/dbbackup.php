<?php
// ----------------------------------------------------------------------
// Khaled Content Management System
// Copyright (C) 2004 by Khaled Al-Shamaa.
// GSIBC.net stands behind the software with support, training, certification and consulting.
// http://www.al-shamaa.com/
// ----------------------------------------------------------------------
// LICENSE

// This program is open source product; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Filename: db.php
// Original  Author(s): Khaled Al-Sham'aa khaled@al-shamaa.com
// Purpose:  Database setup/configuration
// ----------------------------------------------------------------------

/*
Supported databse drivers includes:
MySQL, PostgreSQL, Interbase, Firebird, Informix, Oracle, MS SQL, Foxpro,
Access, ADO, Sybase, FrontBase, DB2, SAP DB, SQLite, Netezza, LDAP,
and generic ODBC, ODBTP
*/
?>
<?php include_once ("db.php") ?>
<?php include_once ("config.php") ?>
<?php include_once ("lang.php") ?>
<?php include_once ("security.inc.php") ?>
<?php
      session_start();
      if (@$_SESSION["status"] <> "login" || ($useSSL && $_SERVER['HTTPS'] != 'on')) header("Location: login.php");
?>
<?php if ($_SESSION["ip"] != getip()) header("Location: login.php") ?>
<?php require_once('class.ADODB_XML.php'); ?>
<?php require_once('pclzip.lib.php'); ?>
<?
// create an object instance
// configure library for a MySQL connection
$db = NewADOConnection(DBTYPE);

// open connection to database
$db->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

// get resultset as associative array
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

if(!($_SESSION["privilege"] & 2)){
   $db->Close();
   noPrivilege();
}

$db->Close();

$objConnection = &ADONewConnection(DBTYPE);
$objConnection->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

$adodbXML = new ADODB_XML("1.0", "ISO-8859-1");
$adodbXML->ConvertToXML($objConnection, "SELECT * FROM pages", "uploads/pages.xml");
$adodbXML->ConvertToXML($objConnection, "SELECT * FROM users", "uploads/users.xml");
$adodbXML->ConvertToXML($objConnection, "SELECT * FROM interactive", "uploads/interactive.xml");
$adodbXML->ConvertToXML($objConnection, "SELECT * FROM invoice", "uploads/invoice.xml");
$adodbXML->ConvertToXML($objConnection, "SELECT * FROM invoice_details", "uploads/invoice_details.xml");

$objConnection->Execute("OPTIMIZE TABLE pages") or die("Error in optimization of table pages " . $db->ErrorMsg());
$objConnection->Execute("OPTIMIZE TABLE users") or die("Error in optimization of table users " . $db->ErrorMsg());
$objConnection->Execute("OPTIMIZE TABLE interactive") or die("Error in optimization of table interactive " . $db->ErrorMsg());
$objConnection->Execute("OPTIMIZE TABLE invoice") or die("Error in optimization of table invoice " . $db->ErrorMsg());
$objConnection->Execute("OPTIMIZE TABLE invoice_details") or die("Error in optimization of table invoice_details " . $db->ErrorMsg());

$archive = new PclZip('uploads/backup_'.date('dFY').'.zip');
$archive->add('uploads/pages.xml', PCLZIP_OPT_REMOVE_PATH, 'uploads/');
$archive->add('uploads/users.xml', PCLZIP_OPT_REMOVE_PATH, 'uploads/');
$archive->add('uploads/interactive.xml', PCLZIP_OPT_REMOVE_PATH, 'uploads/');
$archive->add('uploads/invoice.xml', PCLZIP_OPT_REMOVE_PATH, 'uploads/');
$archive->add('uploads/invoice_details.xml', PCLZIP_OPT_REMOVE_PATH, 'uploads/');
?>
<?php
unlink("uploads/pages.xml");
unlink("uploads/users.xml");
unlink("uploads/interactive.xml");
unlink("uploads/invoice.xml");
unlink("uploads/invoice_details.xml");
header('Location: uploads/backup_'.date('dFY').'.zip');
?>
