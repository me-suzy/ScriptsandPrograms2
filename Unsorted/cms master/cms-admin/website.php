<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/website.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
require("application.php");

//----- START INIT CLASSES -----
$ServerVars = new ServerVars();
$Db = new DB($CFG->db_host, $CFG->db_name, $CFG->db_user, $CFG->db_pass);
$Db->connect();
$Session = new Session();
$Base = new Base();
$Auth = new Auth();
$Website = new Website();
$Lang_website = new LangWebsite();
$Lang = new Lang();
//----- END INIT CLASSES -----

//----- START -----
ob_start();

//$Auth->require_login();
$mode = $Base->convert_post_get("mode");
include("$CFG->dir_admin_templates/header.php");
switch ($mode) {
    case "update_description":
      $Website->update_description($ServerVars->POST);
      $Website->print_current_options();
      break;
    case "edit_description":
      $Website->edit_description();
      break;
    case "update_keywords":
      $Website->update_keywords($ServerVars->POST);
      $Website->print_current_options();
      break;
    case "edit_keywords":
      $Website->edit_keywords();
      break;
    case "update_title":
      $Website->update_title($ServerVars->POST);
      $Website->print_current_options();
      break;
    case "edit_title":
      $Website->edit_title();
      break;
    default:
      $Website->print_current_options();
      break;
}
include("$CFG->dir_admin_templates/footer.php");
$Db->disconnect();

ob_end_flush();
//----- END -----

?>