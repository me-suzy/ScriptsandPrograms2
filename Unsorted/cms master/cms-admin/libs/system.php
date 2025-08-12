<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/system.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class System {

    function update_user($frm) {
      global $CFG, $Db, $Base, $Lang_system;
      if ($frm["password"]=="") {
          $Db->query("UPDATE `cms_admin` SET `email`='$frm[email]' WHERE `login`='admin'");
      } else {
          $Db->query("UPDATE `cms_admin` SET `email`='$frm[email]', `password`='".md5($frm["password"])."' WHERE `login`='admin'");
      }
      $Base->msg_js_show($Lang_system->msg_update_user_ok);
    }

    function edit_user() {
      global $CFG, $Db, $Lang_system;
      $frm = $Db->fetch_array($Db->query("SELECT * FROM `cms_admin` WHERE `login`='admin'"));
      $frm["mode"] = "update_user";
      $frm["button"] = $Lang_system->button_update;
      include("$CFG->dir_admin_templates/system-user-form.php");
    }

    function print_system_stat() {
      global $CFG, $Lang_system, $Db;
      $q_pages = $Db->query("SELECT * FROM `cms_pages`");
      $count_pages = $Db->num_rows($q_pages);
      $q_pages = $Db->query("SELECT * FROM `cms_pages` WHERE `is_visible`=1");
      $count_visible_pages = $Db->num_rows($q_pages);
      $q_pages = $Db->query("SELECT * FROM `cms_pages` WHERE `is_visible`=0");
      $count_hidden_pages = $Db->num_rows($q_pages);
      $path = "$CFG->dir_root/cms-images/";
      $dir = dir($path);
      $n = 0; $images_size = 0;
      while($image = $dir->read()) {
          if(is_file($path.$image)) {
            $images_size = $images_size + filesize($path.$image);
            $n++;
          }
      }
      $count_images = $n;
      $path = "$CFG->dir_root/cms-files/";
      $dir = dir($path);
      $n = 0; $files_size = 0;
      while($file = $dir->read()) {
          if(is_file($path.$file)) {
            $files_size = $files_size + filesize($path.$file);
            $n++;
          }
      }
      $count_files = $n;
      include("$CFG->dir_admin_templates/system-stat.php");	
    }

}

?>