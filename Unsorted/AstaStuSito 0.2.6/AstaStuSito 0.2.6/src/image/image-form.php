<?php

# Include

include("image-conf.php");

# Print page

print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">
<html>
<head>
<title>Putting Images</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"$CSS\"></link>
</head>
<body>
<br />
<h1 align=\"center\">Putting Images</h1>
<br />
<br />
<div align=\"center\">
<form enctype=\"multipart/form-data\" method=\"post\" action=\"astaimage.php\">
<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"800000\"></input>
<p align=\"center\">Image Path</p>
<input type=\"file\" name=\"Image\"></input><br />
<p align=\"center\">Comment</p>
<input type=\"text\" name=\"ImageComment\" size=\"20\" maxlength=\"25\"></input><br />
<br />
<br />
<br />
<p align=\"center\">Login</p>
<input type=\"text\" name=\"User\" size=\"20\" maxlength=\"20\"></input><br />
<p align=\"center\">Password</p>
<input type=\"password\" name=\"Pass\" size=\"20\" maxlength=\"20\"></input><br />
<br /><br />
<input type=\"submit\" value=\"Send\"></input>
</form>
</div>
<br />
<br />
</body>
</html>");

exit;

?>
