<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/logout.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
require("application.php");

//----- START INIT CLASSES ---------------------------------------------------
$ServerVars = new ServerVars();
$Db = new DB($CFG->db_host, $CFG->db_name, $CFG->db_user, $CFG->db_pass);
$Db->connect();
$Session = new Session();
$Base = new Base();
$Auth = new Auth();
//----- END INIT CLASSES -----------------------------------------------------

//----- START ----------------------------------------------------------------
ob_start();

$Auth->logout();
$Base->redirect("$CFG->www_admin/pages.php");
$Db->disconnect();

ob_end_flush();
//----- END ------------------------------------------------------------------

?>