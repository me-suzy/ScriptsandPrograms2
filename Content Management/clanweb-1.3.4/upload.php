<?php
//	-----------------------------------------
// 	$File: upload.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-03-22
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------
  	require ('auth.php');
	require ('_inc/top.inc.php');
    	

	// top.inc
	require ('_inc/top.inc.php');

	// Content
  echo"<div class=\"welcome\">";

  

if(isset($_FILES['userfile']))
{

  $upload_dir = 'files/';

  $filetypes = 'jpg,jpeg,gif,png,tif';

  $maxsize = (1024*500);
  

  if(empty($_FILES['userfile']['name']))
    die('You can\'t upload air you know.');
  

  if($_FILES['userfile']['size'] > $maxsize)
    die('The file is to big. Max size '.round($maxsize / 1024).' kB.');
  

  $types = explode(',',$filetypes);
  $file = explode('.',$_FILES['userfile']['name']);
  $extension = $file[sizeof($file)-1];

  if(!in_array($extension,$types))
    die('Only '.$filetypes.' are allowed.');
  

  if(is_uploaded_file($_FILES['userfile']['tmp_name']))
    move_uploaded_file($_FILES['userfile']['tmp_name'],$upload_dir.$_FILES['userfile']['name']);

  echo "".$_FILES['userfile']['name']." was uploaded without problems!";
}
else
{
  
  echo "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" enctype=\"multipart/form-data\"/>\n";
  echo "<input type=\"file\" name=\"userfile\"/><br/>\n";
  echo "<input type=\"submit\" value=\"Upload file\"/ class=\"button\">\n";
  echo "</form>\n";
}


    
	echo"</div>";
// bottom.inc
require ('_inc/bottom.inc.php');

?>

