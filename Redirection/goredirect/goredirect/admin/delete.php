<?php

/*********************************************

Go Redirector PHP Configuration Script
Version 0.4
Copyright (c) 2003-2004, StudentPlatinum.com and
the Edvisors Network

Provided under BSD license located at
http://www.studentplatinum.com/scripts/license.php

It is a violation of the license to distribute
this file without the accompanying license and
copyright information.

You may obtain the latest version of this software
at http://www.studentplatinum.com/scripts/

Please visit our corporate page at:
http://www.edvisorsnetwork.com/

*********************************************/

require("../goconfig.php");

/*********************************************
database connection section
*********************************************/
dbinit();

/********************************************
quick check to see if anything has been
defined yet
*********************************************/

if($deleteid!="")
{
// delete the ID if deleteid is not null
$sqldelete="DELETE FROM redirs WHERE id=$deleteid";
$result=mysql_query($sqldelete) or die(mysql_error());
if(mysql_affected_rows()) 
{
echo "<p>Deletion successful. <a href=\"$PHP_SELF\"></p><p>Delete another?</a></p>";
exit;
} 
else 
{
echo "<p>Link $deleteid was not successfully deleted.</p><p>Please try again.</p>";
}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Go! Redirector Administration page</title>
</head>
<body>
<p><strong>Remove a Link</strong></p>

  <?php if ($msg!=""){ echo $msg; }?>

<p>WARNING: Removal is permanent and non-reversible. Once it's gone, it's <strong>gone.</strong></p>
<form action="<?php echo $PHP_SELF;?>" method="post" name="deletelink" id="deletelink">
  Link ID: 
  <input name="deleteid" type="text" id="deleteid" value="<?php echo stripslashes($deleteid); ?>">
  <input type="submit" name="Submit2" value="Annihilate this link">
</form>
</body>
</html>
