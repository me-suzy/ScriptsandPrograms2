<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/modules.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Modules {

    function print_modules_list() {
      global $CFG, $Lang_modules, $Db;
      $q_module = $Db->query("SELECT * FROM `cms_modules`");
      $count_modules = $Db->num_rows($q_module);
      $n = 0;
      while ($module[$n] = $Db->fetch_array($q_module)) { $n++; }
      include("$CFG->dir_admin_templates/modules-list.php");	
    }

}

?>