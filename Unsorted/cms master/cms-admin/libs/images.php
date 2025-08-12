<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-admin/libs/images.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class Images {

    function upload_image($image) {
      global $CFG, $Base, $Lang_images;
      if (file_exists("$CFG->dir_root/cms-images/$image[name]")) {
          $Base->msg_js_show($Lang_images->msg_image_exists);
      } else {
          copy("$image[tmp_name]", "$CFG->dir_root/cms-images/$image[name]");
          $Base->msg_js_show($Lang_images->msg_upload_image_ok);
      }
    }
    
    function delete_image($image) {
      global $CFG, $Base, $Lang_images;
      unlink("$CFG->dir_root/cms-images/$image");
      $Base->msg_js_show($Lang_images->msg_delete_image_ok);
    }

    function print_images_list() {
      global $CFG, $Lang_images;
      $path = "$CFG->dir_root/cms-images/";
      $dir = dir($path);
      $n = 0;
      while($image = $dir->read()) {
          if(is_file($path.$image)) {
        $images[$n]["name"] = $image;
        $images[$n]["size"] = getimagesize($path.$image);
        $images[$n]["date"] = date("d:m:Y", filemtime($path.$image));
        $n++;
          }
      }
      $count_images = $n;
      include("$CFG->dir_admin_templates/images-list.php");
    }

}

?>