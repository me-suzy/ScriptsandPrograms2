<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-install/index.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
require("../cms-libs/servervars.php");

$ServerVars = new ServerVars();

class object {};

$CFG = new object;

$CFG->db_host = "";
$CFG->db_name = "";
$CFG->db_user = "";
$CFG->db_pass = "";

$CFG->dir_root = $ServerVars->DOCUMENT_ROOT;

require("$CFG->dir_root/cms-libs/database.php");
require("$CFG->dir_root/cms-admin/libs/base.php");

$Base = new Base();

//----- START INCLUDE INSTALL LIBS ---------------------------------------------
$dir_name = "$CFG->dir_root/cms-install/libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE INSTALL LIBS -----------------------------------------------

$Install = new Install();
$Lang_install = new LangInstall();
$Lang = new Lang();

//----- START -----
ob_start();

$mode = $Base->convert_post_get("mode");
include("$CFG->dir_root/cms-install/templates/header.php");
switch ($mode) {
    case "install":
      $Install->process_installation($ServerVars->POST);
      break;
    case "license":
      $Install->print_license();
      break;
    case "options":
      $Install->print_options_form();
      break;
    default:
      $Install->print_list();
      break;
}
include("$CFG->dir_root/cms-install/templates/footer.php");
ob_end_flush();
//----- END -----

?>