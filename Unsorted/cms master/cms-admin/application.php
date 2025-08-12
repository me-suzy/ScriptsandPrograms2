<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/application.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
require("../cms-config.php");

//----- START INCLUDE COMMON LIBS --------------------------------------------
$dir_name = "$CFG->dir_root/cms-libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (false !== ($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE COMMON LIBS ----------------------------------------------

//----- START INCLUDE ADMIN LIBS ---------------------------------------------
$dir_name = "$CFG->dir_admin/libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (false !== ($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE ADMIN LIBS -----------------------------------------------

?>