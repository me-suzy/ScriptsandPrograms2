<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
session_start();
include_once('loginfunction.php');
include_once('../inc/UIfunctions.php');
include_once('../config.php');
include_once('../inc/formFunctions.php');
$page = new pagebuilder('../', 'admin.php');
include_once('../inc/setLang.php');
checkUser();
$page->showHeader($nav05);

if (!filledForm('cardname, catid', $_POST))
{
	echo "$upload09. $nav11";
	$page->showFooter();
	exit;
}

$cardname = checkAddSlashes($_POST['cardname']);
$MAX_FILE_SIZE = $_POST['MAX_FILE_SIZE'];
$catid = (int)$_POST['catid'];

// Set variables for uploaded file name and resized file name
$imagebasename = time().$_FILES['userfile']['name'];
$original_path = "../images/".$imagebasename;
$resized_path = "../images/small_".$imagebasename;


// If an image wasn't uploaded, exit
if (!is_uploaded_file($_FILES['userfile']['tmp_name']))
{
	echo $upload01;
	$page->showFooter();
	exit;
}

// Check to see whether a thumbnail was uploaded
if (is_uploaded_file($_FILES['userthumb']['tmp_name'])) $thumbnailUploaded = true; else $thumbnailUploaded = false;

// Throw error if image isn't JPG and thumbnail not uploaded
$acceptedTypes = array('image/jpeg', 'image/jpg', 'image/pjpeg');  
if ((!in_array($_FILES['userfile']['type'], $acceptedTypes)) && ($thumbnailUploaded != 'yes'))
	{
		echo "<span class='error'>$upload08 $nav11</span><br>";
		$page->showFooter();
		exit;
	} 

// Move uploaded image to the images directory
if (!(move_uploaded_file($_FILES['userfile']['tmp_name'], $original_path))) 
	{
		echo "<span class='error'>$upload02 $nav11</span>";
		$page->showFooter();
		exit;
	}
echo "$upload03<br>";


// If thumbnail uploaded move thumbnail to the images directory
if ($thumbnailUploaded)
{
	if (!(move_uploaded_file($_FILES['userthumb']['tmp_name'], $resized_path))) 
	{
		echo "<span class='error'>$upload04 $nav11</span>";
		$page->showFooter();
		exit;
	}
	echo "$upload05<br>";
	$success = true;
} 

// 
if (!$thumbnailUploaded)
{
	$dimensions = GetImageSize($original_path);
	// If the uploaded image is shorter than the resize height, just use the uploaded image as the thumbnail	
	if ($dimensions[1] <= $resize_height)
		{
			if (!(move_uploaded_file($_FILES['userfile']['tmp_name'], $resized_path))) 
				{
					echo "<span class='error'>$upload04</span>";
					$page->showFooter();
					exit;
				}
			else $success = true;
			echo "$upload06<br>";
		}
	
	// If image needs to be resized, use imageResizer class to resize image
	if ($dimensions[1] > $resize_height)
		{	
			include_once('../inc/imageResizer/hft_image.php');
			include_once('../inc/imageResizer/hft_image_errors.php');
			$image = new hft_image($original_path);
			if ($imageLibrary == 'GD') $useGD2 = false; else $useGD2 = true;
			$image->set_parameters($imagequality, $useGD2);
			$image->resize('*', $resize_height);
			if ($image->output_resized($resized_path, "JPG")) 	$success = true; else $success = false;
			echo "$upload07<br>";
		}
}	

@chmod($original_path, 0777);
@chmod($resized_path, 0777);

// If image uploading/resizing went ok, insert new card into database and show the thumbnail
if ($success)
{	
	include_once('../inc/adodb/adodb.inc.php');
	$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
	$conn = &ADONewConnection('mysql');
	$conn->Connect($dbhost,$dbuser,$dbpass,$dbdatabase);
	$sqlstmt = "INSERT INTO ".$tablePrefix."cardinfo (cardname, catid, imagepath, thumbpath) VALUES ('$cardname', $catid, '$imagebasename', 'small_$imagebasename')";
	$conn->Execute($sqlstmt);	
	?>
	<br>
	<img src="<? echo $resized_path; ?>" border="0">
	<?
	echo "<br><br>"; $page->showLink('cards.php',"$nav12 $admin03");
}

$page->showFooter();
?>

