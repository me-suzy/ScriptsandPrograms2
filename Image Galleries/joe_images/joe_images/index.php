<?php
// IMAGE GALLERY ADMIN PAGE
require "config_connect_functions.php";

auth();

headerPrint();

if($fuse == "del"){

   if($_SERVER[REQUEST_METHOD] == "POST"){
      
      $del = mysql_query("DELETE FROM joe_images WHERE id = '$id'");
      successMsg("The record has been deleted successfully");
      
   }else{
   
	  print "<form method='post'>";
	  print "Are you sure you want to delete this record?<br><input type='submit' value='Yes'> <input type='button' value='Cancel' onclick='javascript:history.back()'>";
 	  print "</form>";
 	  
   }

   footerPrint();
   exit;

}

if($fuse == "add" OR $fuse == "mod"){
   
   if($_SERVER[REQUEST_METHOD] == "POST"){
      
       if($fuse == "mod"){
          $ins = mysql_query("UPDATED joe_images SET status = '$r[status]', name = '$r[name]', info = '$r[info]'") or die("died");  
          successMsg("The record has been updated successfully!");
       }
       if($fuse == "add"){
   
          errorCheck($r, $image);
          $ins = mysql_query("INSERT INTO joe_images SET status = '$r[status]', name = '$r[name]', info = '$r[info]'") or die("died");  
          $lastID = mysql_insert_id();
          
          createThumb($image,"".$image_config[path]."".$lastID."LG.jpg",$image_config[large_w],$image_config[quality]);
          sleep(1);
          createThumb($image,"".$image_config[path]."$lastID.jpg",$image_config[thumb_w],$image_config[quality]);
          
          successMsg("The record has been added successfully!");
       }
      
   }else{
      form($id, $image_config);
   }

}else{
   listTable();
}

footerPrint();

?>