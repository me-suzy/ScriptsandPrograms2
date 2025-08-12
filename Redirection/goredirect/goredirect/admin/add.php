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

if($URL!="")
{
// add a new URL if URL is not null
$sqlinsert="INSERT INTO redirs (redirect) VALUES ('$URL')";
$result=mysql_query($sqlinsert) or die(mysql_error());
if(mysql_affected_rows()) 
{
echo "<p>Addition successful. <a href=\"$PHP_SELF\"></p><p>Add another?</a></p>";
exit;
} 
else 
{
echo "<p>Link $URL was not successfully added.</p><p>Please try again.</p>";
}

}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Go! Redirector Administration page</title>
</head>
<body>
<p><strong>Add a Link for Redirection</strong></p>
<?php if ($msg!=""){ echo $msg; }?>
<form action="<?php echo $PHP_SELF;?>" method="post" name="addlink" id="addlink">
  <p>URL: 
    <input name="URL" type="text" id="URL" size="50" value="<?php echo stripslashes($URL); ?>">
    <input type="submit" name="Submit" value="Add link">
  </p>
</form>
</body>
</html>