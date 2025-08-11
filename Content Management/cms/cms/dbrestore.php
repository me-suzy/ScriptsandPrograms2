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

if(!($_SESSION["privilege"] & 1)){
   $db->Close();
   noPrivilege();
}

$db->Close();

if (isset($_POST['submit'])){
   $objConnection = &ADONewConnection(DBTYPE);
   $objConnection->Connect(HOST, USER, PASS, DB) or die("Unable to connect!");

   $objConnection->BeginTrans();
   $ok = 1;

   $strsql = "DELETE FROM `pages`";
   $objConnection->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
   $strsql = "DELETE FROM `users`";
   $objConnection->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
   $strsql = "DELETE FROM `interactive`";
   $objConnection->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
   $strsql = "DELETE FROM `invoice`";
   $objConnection->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());
   $strsql = "DELETE FROM `invoice_details`";
   $objConnection->Execute($strsql) or die("Error in query: $strsql. " . $db->ErrorMsg());

   $archive = new PclZip($_FILES["xml_file"]["tmp_name"]);
   $archive->extract(PCLZIP_OPT_PATH, "uploads/", PCLZIP_OPT_REMOVE_ALL_PATH);

   $adodbXML = new ADODB_XML("1.0", "ISO-8859-1");
   $adodbXML->InsertIntoDB($objConnection, "uploads/pages.xml", "pages");
   $adodbXML->InsertIntoDB($objConnection, "uploads/users.xml", "users");
   $adodbXML->InsertIntoDB($objConnection, "uploads/interactive.xml", "interactive");
   $adodbXML->InsertIntoDB($objConnection, "uploads/invoice.xml", "invoice");
   $adodbXML->InsertIntoDB($objConnection, "uploads/invoice_details.xml", "invoice_details");

   unlink($_FILES["xml_file"]["tmp_name"]);
   unlink("uploads/pages.xml");
   unlink("uploads/users.xml");
   unlink("uploads/interactive.xml");
   unlink("uploads/invoice.xml");
   unlink("uploads/invoice_details.xml");

   if ($ok) $objConnection->CommitTrans();
   else $objConnection->RollbackTrans();

   $objConnection->Close();

   header("Location: index.php");
}
?>
<?php include_once ("lang.php") ?>
<?php include_once ("header.php") ?>
<form  action="dbrestore.php" method="post" name="restore" enctype="multipart/form-data">
<table border="0" align="center" cellpadding="5" cellspacing="2" width="100%">
<tr><td colspan=2><img src="cmsimages/dbrestore.png" width="48" height="48" border="0" alt="<?php echo LBL_RESTORE; ?>"></td></tr>
<tr><td valign="top" class="pageTitle">Zip File:</td>
<td class="bodyBlock"><input type="file" name="xml_file"></td></tr>
<tr><td colspan=2 class="bodyBlock" align="center">
 <input type="submit" name="submit">
</td>
</tr>
</table>
</form>

<?php include_once ("footer.php"); ?>
