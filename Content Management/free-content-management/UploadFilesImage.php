<?php
session_start();
include("Includes/PortalConection.php");
include("Includes/Database.php");
$strRootpath= "";
include_once ("Includes/validsession.php");
print "<HTML><HEAD><TITLE>Upload Files</TITLE>";
include ("Includes/Styles.php");
?>
</HEAD>
<BODY>
<h3><img src="Includes/image.gif" width="36" height="36" alt="Upload Image Files"> <img src="Includes/upload.gif" alt="upload" width="23" height="26" border="0"> Upload Image Files</h3>
<form method=post
      enctype="multipart/form-data"
      action="">
Your File:<BR><input type=file name=YourFile size=50><BR><BR>
<input  class=button onmouseover="this.className='buttonover'" onmouseout="this.className='button'"  type=submit name=submit value="Upload">

</form>
<?php
// In PHP earlier then 4.1.0, $HTTP_POST_FILES should be used instead of $_FILES. 
if(!empty($_FILES["YourFile"])) { 
    $uploaddir = ImageUploadPathRelative;//FileUploadPath; // set this to wherever 
    //copy the file to some permanent location 
    if (move_uploaded_file($_FILES["YourFile"]["tmp_name"], $uploaddir . $_FILES["YourFile"]["name"])) 
    {
        echo("file uploaded");
    } else 
    {
        echo ("error!");
    }
} 
print "</BODY></HTML>";
?>


