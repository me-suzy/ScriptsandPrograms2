<?php

# Include

include("news-conf.php");

# Print page

print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">
<html>
<head>
<title>Inserting articles</title>
<link rel=\"stylesheet\" type=\"text/css\" href=\"$CSS\"></link>
</head>
<body>
<br />
<h1 align=\"center\">Insert News</h1>
<br />
<br />
<div align=\"center\">
<form method=\"post\" action=\"astapage.php\">
<p align=\"center\">Title</p>
<input type=\"text\" name=\"Title\" size=\"25\" maxlength=\"100\"></input><br />
<p align=\"center\">Page's Name (.html)</p>
<input type=\"text\" name=\"Page\" size=\"25\"  maxlength=\"30\"></input><br />
<p align=\"center\">Article</p>
<textarea rows=\"20\" cols=\"80\" name=\"Article\" wrap=\"physical\"></textarea><br />
<p align=\"center\">Author</p>
<input type=\"text\" name=\"Author\" value=\"Anonymous\" size=\"25\" maxlength=\"30\"></input><br /><br /><br />
<p align=\"center\">Login</p>
<input type=\"text\" name=\"User\" size=\"20\" maxlength=\"20\"></input><br />
<p align=\"center\">Password</p>
<input type=\"password\" name=\"Pass\" size=\"20\" maxlength=\"20\"></input><br />
<br /><br />
<input type=\"submit\" value=\"Send\"></input>
<input type=\"reset\" value=\"Cancel\"></input>
</form>
</div>
<br />
<br />
</body>
</html>");

exit;

?>
