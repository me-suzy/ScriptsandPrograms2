<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/files.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Files {

    function upload_file($file) {
      global $CFG, $Base, $Lang_files;
      if (file_exists("$CFG->dir_root/cms-files/$file[name]")) {
          $Base->msg_js_show($Lang_files->msg_file_exists);
      } else {
          copy("$file[tmp_name]", "$CFG->dir_root/cms-files/$file[name]");
          $Base->msg_js_show($Lang_files->msg_upload_file_ok);
      }
    }
    
    function delete_file($file) {
      global $CFG, $Base, $Lang_files;
      unlink("$CFG->dir_root/cms-files/$file");
      $Base->msg_js_show($Lang_files->msg_delete_file_ok);
    }

    function print_files_list() {
      global $CFG, $Lang_files;
      $path = "$CFG->dir_root/cms-files/";
      $dir = dir($path);
      $n = 0;
      while($file = $dir->read()) {
          if(is_file($path.$file)) {
        $files[$n]["name"] = $file;
        $files[$n]["size"] = filesize($path.$file);
        $files[$n]["date"] = date("d:m:Y", filemtime($path.$file));
        $n++;
          }
      }
      $count_files = $n;
      include("$CFG->dir_admin_templates/files-list.php");
    }

}

?>