<?php
/*
By using this script you will be able to upload as many files as you want.
The code will check if file existes, limited extensions, file size, file selected ..etc.
For Q. E-mail/MSN: m(at)maaking.com.
*/
###########################################
#----------Upload Multiple Files----------#
#----------Multi-files Uploader-----------#
#-------------Multi-Uploader -------------#
###########################################
/*=========================================\
Author      :  Mohammed Ahmed(M@@king)    \\
Version     :  1.0                        \\
Date Created:  Aug 20  2005               \\
----------------------------              \\
Last Update:   Aug 31 2005                \\
----------------------------              \\
Country    :   Palestine                  \\
City       :   Gaza                       \\
E-mail     :   m@maaking.com              \\
MSN        :   m@maaking.com              \\
AOL-IM     :   maa2pal                    \\
WWW        :   http://www.maaking.com     \\
Mobile/SMS :   00972-599-622235           \\
                                          \\
===========================================\
------------------------------------------*/
//upload directory.
//change to fit your need eg. files, upload .... etc.
$upload_dir = "images/";
//number of files to upload.
$num_files = 5;
//the file size in bytes.
$size_bytes =51200; //51200 bytes = 50KB.
//Extensions you want files uploaded limited to.
$limitedext = array(".gif",".jpg",".jpeg",".png",".txt",".nfo",".doc",".rtf",".htm",".dmg",".zip",".rar",".gz",".exe");


   //check if the directory exists or not.
   if (!is_dir("$upload_dir")) {
      die ("Error: The directory <b>($upload_dir)</b> doesn't exist");
   }
   //check if the directory is writable.
   if (!is_writeable("$upload_dir")){
      die ("Error: The directory <b>($upload_dir)</b> is NOT writable, Please CHMOD (777)");
   }


//if the form has been submitted, then do the upload process
//infact, if you clicked on (Upload Now!) button.
if (isset($_POST['upload_form'])){

       echo "<h3>Upload results:</h3>";

       //do a loop for uploading files based on ($num_files) number of files.
       for ($i = 1; $i <= $num_files; $i++) {

           //define variables to hold the values.
           $new_file = $_FILES['file'.$i];
           $file_name = $new_file['name'];
           //to remove spaces from file name we have to replace it with "_".
           $file_name = str_replace(' ', '_', $file_name);
           $file_tmp = $new_file['tmp_name'];
           $file_size = $new_file['size'];

           #-----------------------------------------------------------#
           # this code will check if the files was selected or not.    #
           #-----------------------------------------------------------#

           if (!is_uploaded_file($file_tmp)) {
              //print error message and file number.
              echo "File $i: Not selected.<br>";
           }else{
                 #-----------------------------------------------------------#
                 # this code will check file extension                       #
                 #-----------------------------------------------------------#

                 $ext = strrchr($file_name,'.');
                 if (!in_array(strtolower($ext),$limitedext)) {
                    echo "File $i: ($file_name) Wrong file extension. <br>";
                 }else{
                       #-----------------------------------------------------------#
                       # this code will check file size is correct                 #
                       #-----------------------------------------------------------#

                       if ($file_size > $size_bytes){
                           echo "File $i: ($file_name) Faild to upload. File must be <b>". $size_bytes / 1024 ."</b> KB. <br>";
                       }else{
                             #-----------------------------------------------------------#
                             # this code check if file is Already EXISTS.                #
                             #-----------------------------------------------------------#

                             if(file_exists($upload_dir.$file_name)){
                                 echo "File $i: ($file_name) already exists.<br>";
                             }else{
                                   #-----------------------------------------------------------#
                                   # this function will upload the files.  :) ;) cool          #
                                   #-----------------------------------------------------------#
                                   if (move_uploaded_file($file_tmp,$upload_dir.$file_name)) {
                                       echo "File $i: ($file_name) Uploaded.<br>";
                                   }else{
                                        echo "File $i: Faild to upload.<br>";
                                   }#end of (move_uploaded_file).

                             }#end of (file_exists).

                       }#end of (file_size).

                 }#end of (limitedext).

           }#end of (!is_uploaded_file).

       }#end of (for loop).
       # print back button.
       echo "»<a href=\"$_SERVER[PHP_SELF]\">back</a>";
////////////////////////////////////////////////////////////////////////////////
//else if the form didn't submitted then show it.
}else{
    echo " <h3>Select files to upload!.</h3>
           Max file size = ". $size_bytes / 1024 ." KB";
    echo " <form method=\"post\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";
           // show the file input field based on($num_files).
           for ($i = 1; $i <= $num_files; $i++) {
               echo "File $i: <input type=\"file\" name=\"file". $i ."\"><br>";
           }
    echo " <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$size_bytes\">
           <input type=\"submit\" name=\"upload_form\" value=\"Upload Now!\">
           </form>";
}

//print copyright ;-)
echo"<p align=\"right\"><br>Script by: <a href=\"http://www.maaking.com\">maaking.com</a></p>";
?>
