<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-config.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================

class object {};

$CFG = new object;

$CFG->db_host = "";
$CFG->db_name = "";
$CFG->db_user = "";
$CFG->db_pass = "";

$CFG->dir_root = "";
$CFG->dir_images = "$CFG->dir_root/cms-images";
$CFG->dir_admin = "$CFG->dir_root/cms-admin";
$CFG->dir_admin_templates = "$CFG->dir_admin/templates";

$CFG->www_root = "";
$CFG->www_admin = "$CFG->www_root/cms-admin";
$CFG->www_images = "$CFG->www_root/cms-images";

?>