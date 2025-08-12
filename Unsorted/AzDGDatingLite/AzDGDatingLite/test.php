<?php
include "config.inc.php";
@header ("Pragma: no-cache");
$mode = $HTTP_POST_VARS['mode'];
if ($mode == "upload_file" )
{
	/*
	File Name $attachment_name
	File Size $attachment_size
	File Type $attachment_type
	*/
	$MaxSize 	= 100000;
	$servertime 	= time();
	$second 	= date("s", ($servertime));
	$minute 	= date("i", ($servertime));
	$hour 		= date("H", ($servertime));
	$day		= date("d", ($servertime));
	$month 		= date("m", ($servertime));
	$year 		= date("Y", ($servertime));
	$picdate = "$year-$month-$day-$hour$minute$second";
    function getextension($filename)
{
	$filename 	= strtolower($filename);
	$extension 	= split("[/\\.]", $filename);
	$n 		= count($extension)-1;
	$extension 	= $extension[$n];
	return $extension;
}

	if(!empty($HTTP_POST_FILES['attachment']))
	{
		$file_type 	= getextension($HTTP_POST_FILES['attachment']['name']); 
		if($file_type=="gif"){
			$pic_name ="$picdate.gif";
		}elseif($file_type=="jpg" or $file_type=="jpeg"){
			$pic_name ="$picdate.jpg";
		}elseif($file_type=="swf"){
			$pic_name ="$picdate.swf";
		}else{
			$pic_name ="";
		} 
		if($pic_name =="$picdate." or $pic_name =="")
		{
			// Not valide file
			echo "error1";
		}
		if($HTTP_POST_FILES['attachment']['size'] > $MaxSize)
		{
        echo "error2";
		}
		if($safeupload == 1)
		{
			if(function_exists("is_uploaded_file"))
			{
				if(is_uploaded_file($HTTP_POST_FILES['attachment']['tmp_name']))
				{
					if(move_uploaded_file($HTTP_POST_FILES['attachment']['tmp_name'], $int_path."/members/uploads/092002/".$pic_name))
					{
					}
				}
			}
		}else{
			@copy($HTTP_POST_FILES['attachment']['tmp_name'], $int_path."/members/uploads/092002/".$pic_name) 
	        	or die("No copy! Verify permission of your Uploads directory!"); 
		}
        }
	echo "<div align=\"center\">";
	echo "Downloaded:<br><img src=\"".$url."/members/uploads/092002/".$pic_name."\"></div>";
	
	
}else{

?>

<table width="90%" cellspacing="4" align="center">
<FORM METHOD="post" ACTION="test.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="mode" value="upload_file">safe, set 1 or 0
<input type="text" name="safeupload" value="1">
<tr>
	<td>file</td>
	<td><INPUT TYPE="file" NAME="attachment" SIZE="30"></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="submit" name="submit" value="submit" width="150"></td>
</tr>
</table>


<?php
}
?>
