<?php
session_start();
include("../../Includes/PortalConection.php");
include("../../Includes/Database.php");
$strRootpath= "../../";
include_once ("../../Includes/validsession.php");

$strError="";
// In PHP earlier then 4.1.0, $HTTP_POST_FILES should be used instead of $_FILES. 
if(!empty($_FILES["YourFile"])) { 
    $uploaddir = ImageUploadPathRelative;//FileUploadPath; // set this to wherever 
    //copy the file to some permanent location 
    if (move_uploaded_file($_FILES["YourFile"]["tmp_name"], $uploaddir . $_FILES["YourFile"]["name"])) 
    {
        //echo("file uploaded");
        Redirect('ListImages.php');
    } else 
    {
        $strError="Could not upload the file<BR>";
        //echo ("error!");
    }
} 

print "<HTML><HEAD><TITLE>Upload Files</TITLE>";
include ("../../Includes/Styles.php");

print "</HEAD><BODY>";
print "<TABLE border=0>";
print "<TR><TD WIDTH=15% VALIGN=TOP>";
include_once ("../../navigation.php");
print "</TD></TR>";

print "<TR><TD>";
print $strError;
?>
<form method=post
      enctype="multipart/form-data"
      action="">
Your File:<BR><input type=file name=YourFile size=50 class=cssborder><BR><BR>
<input  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type=submit name=submit value="Upload">

</form>
<?php
print "</TD></TR>";
print "</TABLE>";
?>

<? include("../../Includes/data-t.php"); ?>

<?
print "</BODY></HTML>";
?>


