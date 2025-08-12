<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                         |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//check login:-------------------------------
require_once 'header_footer.php';
if (!$_SESSION['authorized']) {
   include 'admin.php';
   die;
   }
//------------------------------------------

//include required files-----------------
require_once 'upload_class.php';
require_once 'image_resize2.php';
//-------------------------------------

//get system variables from db
$sysvars = GetSysVar();
//set upload_ok to 0
$upload_ok = 0;
//create form function
function upload_form () {
         //get language variables
         global $lang;
         print "<form action=\"";
         print $_SERVER['PHP_SELF'];
         print "\" method=\"post\" enctype=\"multipart/form-data\">
              {$lang[upload_image_form_header]}<br>
              <input type=\"file\" name=\"upload\"><br>
              <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000000\"><br>
              <input type=\"Submit\" name = \"Submit\" value=\"{$lang[upload_image_button]}\">
              </form>";
         }




//call file handler
if ($_POST['Submit'] == "{$lang[upload_image_button]}") {
   $upload_class = new Upload_Files;
   $upload_class->temp_file_name = trim($_FILES['upload']['tmp_name']); 
   $upload_class->file_name = trim(strtolower($_FILES['upload']['name']));
   $upload_class->upload_dir = "images/";
   $upload_class->upload_log_dir = "images/upload_logs/";
   $upload_class->max_file_size = 5242880;
   $upload_class->banned_array = array(""); 
   $upload_class->ext_array = array(".jpg",".jpeg",".gif",".png");

   $valid_ext = $upload_class->validate_extension();
   $valid_size = $upload_class->validate_size(); 
   $valid_user = $upload_class->validate_user(); 
   $max_size = $upload_class->get_max_size(); 
   $file_size = $upload_class->get_file_size(); 
   $file_exists = $upload_class->existing_file(); 

       if (!$valid_ext) {
           $result = "{$lang[upload_image_only]}";
       }
       elseif (!$valid_size) {
           $result = "{$lang[upload_size_error1]} $max_size {$lang[upload_size_error2]} $file_size";
       } 
       elseif (!$valid_user) { 
           $result = "You have been banned from uploading to this server."; 
       } 
       elseif ($file_exists) { 
           $result = "{$lang[upload_excist_error]}";
       } else { 
           $upload_file = $upload_class->upload_file_with_validation(); 
           if (!$upload_file) { 
               $result = "{$lang[upload_failed]}";
           } else { 
               $result = "{$lang[upload_success1]}";
               $upload_ok = 1;
           } 
       }






}


if ($upload_ok) {
   print "<div id=\"upload\">";
   print "{$lang[upload_success2]}<br>";
   $small = image_createThumb("images/" . $_FILES['upload']['name'], "images/small_" . $_FILES['upload']['name'], $sysvars['small_image_width'], $sysvars['small_image_height']);
   $resize = image_createThumb("images/" . $_FILES['upload']['name'], "images/" . $_FILES['upload']['name'], $sysvars['image_width'], $sysvars['image_height']);

   //$small = create_small ("images/",$_FILES['upload']['name'], $sysvars['small_image_width'], $sysvars['small_image_height']);
   //$resize = imageresize ("images/",$_FILES['upload']['name'], $sysvars['image_width'] ,$sysvars['image_height']);
   if (!$small) {
      print "<br>{$lang[upload_small_resize_failed]}<br>";
      }
   if (!$resize) {
      print "<br>{$lang[upload_resize_failed]}<br>";
      }
   else {
        print "{$lang[upload_resize_success]}<br>";
        print "<a href=\"javascript:window.opener.dialogArguments.value = '";
        print $_FILES['upload']['name'];
        print "'; window.close()\">{$lang[upload_continue]}</a>";
        print "</div>";

   }
}
else {
print "<div id=\"upload\">";
print $result;
upload_form ();
print "</div>";
}
?>