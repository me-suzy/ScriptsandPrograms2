<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
include "../config.inc.php";
include "../templates/secure.php";
include "user.php";
include "../templates/header.php";
$t = new Template;
$t->set_file(array("error"=>"../templates/".$template_name."/error.html","member_upload_pic"=>"../templates/".$template_name."/members_upload_pic.html"));
$sql = "SELECT * FROM $mysql_table WHERE user = '$username'";
$result = mysql_query($sql);
while ($i = mysql_fetch_array($result)) {
$imgname = $i[imgname];
$id = $i[id];
$img = $i[pic];
}
if ($page == update) {
if (isset($HTTP_POST_FILES['file1']['name'])) $file1_name = $HTTP_POST_FILES['file1']['name'];
	else $file1_name = "";
if (isset($HTTP_POST_FILES['file1']['size'])) $file1_size = $HTTP_POST_FILES['file1']['size'];
	else $file1_size = "";
if (isset($HTTP_POST_FILES['file1']['tmp_name'])) $file1_tmp = $HTTP_POST_FILES['file1']['tmp_name'];
	else $file1_tmp = "";
    
if (($file1_name == "")||($file1_size == "")||($file1_tmp == "")) {
$t->set_var("ERROR", W_BADPHOTO);
$t->pparse("error");
include "../templates/footer.php";
die;
}

      function getextension($filename)
      {
      	$filename 	= strtolower($filename);
	    $extension 	= split("[/\\.]", $filename);
	    $n 		= count($extension)-1;
	    $extension 	= $extension[$n];
	    return $extension;
        }

		$file_type 	= getextension($file1_name);
   		if( $file_type!="gif" && $file_type!="jpg" ){
            $t->set_var("ERROR", W_BADPHOTOEXT);
            $t->pparse("error");
            include "../templates/footer.php";
            die;
        }
        $MaxSize1000 	= $MaxSize*1000;

		if($file1_size > $MaxSize1000)
		{
            $t->set_var("ERROR", W_BADPHOTOS);
            $t->pparse("error");
            include "../templates/footer.php";
            die;
        }
$time = time();
if (!empty($imgname))
{
// Delete file
unlink ($int_path."/members/uploads/".$imgname);
}

$dir = date("mY", $time);
if (!is_dir($int_path.'/members/uploads/'.$dir))
{
umask(0);
mkdir ("uploads/".$dir, 0777);
}
$fileb = date("dHis", $time);
$filee = rand(0, 999);
$fn = $fileb."-".$filee;

$pic = $url."/members/uploads/".$dir."/".$fn.".".$file_type;
$intpic = $dir."/".$fn.".".$file_type;
if(function_exists("is_uploaded_file"))
  {
  if(is_uploaded_file($HTTP_POST_FILES['file1']['tmp_name']))
	{
	if(move_uploaded_file($HTTP_POST_FILES['file1']['tmp_name'], $int_path."/members/uploads/".$intpic))
		{
		}
	}
}

$sql2 = "UPDATE ".$mysql_table." SET imgname='".$intpic."', pic='".$pic."' WHERE id = '".$id."'";
mysql_query($sql2);

// mail to admin for view change profile
         if ($up_prof == "1")
         {
         $headers="Content-Type: text/html; charset=".$langcharset."\n";
         $headers.="From: $adminmail\nX-Mailer: AzDGDatingGold v3.0.5";
         $mh="User change own photo";
         $mmes = "User change own photo<br><br><a href=".$url."/view.php?id=".$id." target=_blank>View this user - id = ".$id."</a>";
         @mail($adminmail,$mh,$mmes,$headers);
         }   



$sql = "SELECT count(*) as total FROM ".$mysql_hits." WHERE id = '".$id."'";
$result = mysql_query($sql);
$trows = mysql_fetch_array($result);
$count = $trows[total];
if ($count == 1)
{
$sql2 = "UPDATE ".$mysql_hits." SET pic='".$pic."' WHERE id = '".$id."'";
mysql_query($sql2);
}
            $t->set_var("ERROR", W_PHOTO_UPL);
            $t->pparse("error");
            include "../templates/footer.php";
            die;
} else {
$t->set_var("URL","pic.php?l=".$l."&page=update&username=".$username."&password=".$password);
$t->set_var("W_UPL_PHOTO", W_UPL_PHOTO);
$t->set_var("W_YOUR_PHOTO", W_YOUR_PHOTO);
if ($img != "") $t->set_var("PHOTO", '<img src='.$img.' border=0>');
else $t->set_var("PHOTO", W_YOU_HAVENT_PHOTO);
$t->set_var("W_PHOTO", W_PHOTO);
$t->pparse("members_upload_pic");
}
include "../templates/footer.php";
?>
