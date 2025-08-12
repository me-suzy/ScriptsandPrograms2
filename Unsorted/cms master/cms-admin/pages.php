<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/pages.php
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
$Pages = new Pages();
$Lang_pages = new LangPages();
$Lang = new Lang();
//----- END INIT CLASSES -----

//----- START -----
ob_start();

$Auth->require_login();
$mode = $Base->convert_post_get("mode");
include("$CFG->dir_admin_templates/header.php");
switch ($mode) {
    case "delete_page":
      $Pages->delete_page($ServerVars->GET["id"]);
      $Pages->print_page_list($Pages->get_page_list());
      break;
    case "update_page":
      $Pages->update_page($ServerVars->POST);
      $Pages->print_page_list($Pages->get_page_list());
      break;
    case "update_page_options":
      $Pages->update_page_options($ServerVars->POST);
      $Pages->print_page_list($Pages->get_page_list());
      break;
    case "edit_page_options":
      $Pages->edit_page_options($ServerVars->GET["id"]);
      break;
    case "move_page_down":
      $Pages->move_page_down($ServerVars->GET["id"]);
      $Pages->print_page_list($Pages->get_page_list());
      break;
    case "move_page_up":
      $Pages->move_page_up($ServerVars->GET["id"]);
      $Pages->print_page_list($Pages->get_page_list());
      break;
    case "add_new_page":
      $Pages->add_new_page();
      break;
    case "insert_new_page":
      $Pages->insert_new_page($ServerVars->POST);
      $Pages->print_page_list($Pages->get_page_list());
      break;
    default:
      $Pages->print_page_list($Pages->get_page_list());
      break;
}
include("$CFG->dir_admin_templates/footer.php");
$Db->disconnect();

ob_end_flush();
//----- END -----

?>