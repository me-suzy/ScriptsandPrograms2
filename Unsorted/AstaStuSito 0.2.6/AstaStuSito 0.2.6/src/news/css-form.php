<?php

print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/transitional.dtd\">
<html>
<head>
<title>News Style</title>
</head>
<body>
<br />
<h1 align=\"center\">Creating CSS</h1>
<br />
<br />
<div align=\"center\">
<form method=\"post\" action=\"css-create.php\">
<p align=\"center\">Background Color (hexadecimal)</p>
<input type=\"text\" name=\"BackColor\" size=\"7\" maxlength=\"7\"></input><br />
<p align=\"center\">Background Image (absolute path)</p>
<input type=\"text\" name=\"BackImage\" size=\"25\" maxlength=\"50\"></input><br />
<p align=\"center\">Title Font</p>
<input type=\"text\" name=\"TitleFont\" size=\"25\" maxlength=\"35\"></input><br />
<p align=\"center\">Title Color (hexadecimal)</p>
<input type=\"text\" name=\"TitleColor\" size=\"7\" maxlength=\"7\"></input><br />
<p align=\"center\">Paragraph Font</p>
<input type=\"text\" name=\"ParaFont\" size=\"25\" maxlength=\"35\"></input><br />
<p align=\"center\">Paragraph Color (hexadecimal)</p>
<input type=\"text\" name=\"ParaColor\" size=\"7\" maxlength=\"7\"></input><br />
<p align=\"center\">Link Font</p>
<input type=\"text\" name=\"LinkFont\" size=\"25\" maxlength=\"35\"></input><br />
<p align=\"center\">Link Color (hexadecimal)</p>
<input type=\"text\" name=\"LinkColor\" size=\"7\" maxlength=\"7\"></input><br />
<p align=\"center\">Link Style</p>
<select multiple=\"false\" name=\"LinkStyle\" size=\"2\">
<option value=\"underline\">Underline</option>
<option value=\"none\">Nothing</option>
</select><br />
<br />
<br />
<p align=\"center\">Login</p>
<input type=\"text\" name=\"User\" size=\"20\" maxlength=\"20\"></input><br />
<p align=\"center\">Password</p>
<input type=\"password\" name=\"Pass\" size=\"20\" maxlength=\"20\"></input><br />
<br /><br />
<input type=\"submit\" value=\"Invia\"></input>
<input type=\"reset\" value=\"Cancella\"></input>
</form>
</div>
</body>
</html>");

exit;

?>
