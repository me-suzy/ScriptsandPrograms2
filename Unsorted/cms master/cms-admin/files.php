<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/files.php
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
$Files = new Files();
$Lang_files = new LangFiles();
$Lang = new Lang();
//----- END INIT CLASSES -----

//----- START -----
ob_start();

//$Auth->require_login();
$mode = $Base->convert_post_get("mode");
include("$CFG->dir_admin_templates/header.php");
switch ($mode) {
    case "upload_file":
      $Files->upload_file($ServerVars->FILES["file"]);
      $Files->print_files_list();
      break;
    case "delete_file":
      $Files->delete_file($ServerVars->GET["name"]);
      $Files->print_files_list();
      break;
    default:
      $Files->print_files_list();
      break;
}
include("$CFG->dir_admin_templates/footer.php");
$Db->disconnect();

ob_end_flush();
//----- END -----

?>