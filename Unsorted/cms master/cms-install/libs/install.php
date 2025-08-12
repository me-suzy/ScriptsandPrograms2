<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-install/install.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Install {

    function process_installation($frm) {
      global $CFG, $Lang_install, $ServerVars;
      error_reporting(0);
      $error = 0;
      if (!is_writable("$CFG->dir_root/.htaccess")) {
          $check["htaccess"] = 0;
          $error = 1;
      } else {
          $check["htaccess"] = 1;
      }
      if (!is_writable("$CFG->dir_root/cms-config.php")) {
          $check["cms_config"] = 0;
          $error = 1;
      } else {
          $check["cms_config"] = 1;
      }
      $Db = new DB($frm["db_host"], $frm["db_name"], $frm["db_user"], $frm["db_pass"]);
      if (!$Db->check()) {
          $check["db"] = 0;
          $error = 1;
      } else {
          $check["db"] = 1;
      }
      if (!is_writable("$CFG->dir_root/cms-images")) {
          $check["cms_images"] = 0;
          $error = 1;
      } else {
          $check["cms_images"] = 1;
      }
      if (!is_writable("$CFG->dir_root/cms-files")) {
          $check["cms_files"] = 0;
          $error = 1;
      } else {
          $check["cms_files"] = 1;
      }
      if (!is_writable("$CFG->dir_root/cms-pages")) {
          $check["cms_pages"] = 0;
          $error = 1;
      } else {
          $check["cms_pages"] = 1;
          $f=fopen("$CFG->dir_root/cms-install/main_page", "r");
          $tt = fread($f, filesize("$CFG->dir_root/cms-install/main_page"));
          fclose($f);
          $f=fopen("$CFG->dir_root/cms-pages/0", "w+");
          fwrite($f, $tt);
          fclose($f);
      }
      if ($error==0) {
          $f = fopen("$CFG->dir_root/cms-config.php", "r");
          $cms_config = fread($f,filesize("$CFG->dir_root/cms-config.php"));
          fclose($f);
          $cms_config = preg_replace("/db_host = \".*\"/","db_host = \"$frm[db_host]\"", $cms_config);
          $cms_config = preg_replace("/db_name = \".*\"/","db_name = \"$frm[db_name]\"", $cms_config);
          $cms_config = preg_replace("/db_user = \".*\"/","db_user = \"$frm[db_user]\"", $cms_config);
          $cms_config = preg_replace("/db_pass = \".*\"/","db_pass = \"$frm[db_pass]\"", $cms_config);
          $cms_config = preg_replace("/dir_root = \".*\"/","dir_root = \"$ServerVars->DOCUMENT_ROOT\"", $cms_config);
          $f = fopen("$CFG->dir_root/cms-config.php", "w");
          fwrite($f, $cms_config);
          fclose($f);
          $f = fopen("$CFG->dir_root/cms-install/cms-master.sql", "r");
          $content = fread($f, filesize("$CFG->dir_root/cms-install/cms-master.sql"));
          fclose($f);
          $sql = explode("\n", $content);
          $Db->connect();
          $n = 0;
          while($sql[$n]) {
        $Db->query($sql[$n]);
        $n++;
          }
          $Db->query("INSERT INTO `cms_admin`(`login`,`password`,`email`) VALUES('admin','".md5($frm["admin_pass"])."','$frm[admin_email]')");
          $Db->disconnect();
          $f = fopen("$CFG->dir_root/cms-install/lock", "w");
          fwrite($f,"");
          fclose($f);
      }
      include("$CFG->dir_root/cms-install/templates/install-process.php");
    }
        
    function print_options_form() {
      global $CFG, $Lang_install;
      include("$CFG->dir_root/cms-install/templates/install-form.php");
    }

    function print_license() {
      global $CFG, $Lang_install;
      $f = fopen("$CFG->dir_root/cms-install/license", "r");
      $license = fread($f, filesize("$CFG->dir_root/cms-install/license"));
      fclose($f);
    //	$license = nl2br($license);
      include("$CFG->dir_root/cms-install/templates/install-license.php");
    }

    function print_list() {
      global $CFG, $Lang_install;
      $component[0]["title"] = $Lang_install->component_title;
      $component[0]["description"] = $Lang_install->component_description;
      if (file_exists("$CFG->dir_root/cms-install/lock")) { 
          $component[0]["status"] = 1;
      } else {
          $component[0]["status"] = 0;
      }
      $component[0]["file"] = "index.php?mode=license";
      $n = 1;
      $dir_name = "$CFG->dir_root/cms-install/modules";
      $dir = dir("$dir_name/");
      $dir->read(); $dir->read();
      while (($module = $dir->read())) {
          if (file_exists("$CFG->dir_root/cms-install/modules/$module/lock")) { 
            $component[$n]["status"] = 1;
          } else {
            $component[$n]["status"] = 0;
          }
          $f = fopen("$CFG->dir_root/cms-install/modules/$module/description", "r");
          $content = fread($f, filesize("$CFG->dir_root/cms-install/modules/$module/description"));
          $desc = explode("\n", $content);
          fclose($f);
          $component[$n]["title"] = $desc[0];
          $component[$n]["description"] = $desc[1];
          $component[$n]["file"] = "modules/$module";
          $n++;
      }
      $dir->close();
      include("$CFG->dir_root/cms-install/templates/install-list.php");
    }

}

?>