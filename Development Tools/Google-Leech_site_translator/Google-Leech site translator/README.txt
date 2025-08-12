Requirements: php enabled server


Edit the value $myurl to match your http://www.domain.com address in the form offered below and the file named "language"
make any subdirectory you want the files in. place the files .htaccess and language in that directory.
Do not add theses files to your root directory.
This script is complient with standards of search engines that follow drop down menus.

Your form to add to the site - you can add ther form on any page you want.
***************************************************************
<?
// edit this line for your domain ex: http://www.domain.com
$myurl = "http://www.domain.com"; 
// No need to edit anything else
echo "<font size=\"1\"><B>Select Your language</B></font><BR>
<form name=\"mnfrm\">
<select name=\"tlang\" onChange=location.href=mnfrm.tlang.options[selectedIndex].value>
<option value=\"#\" selected>Choose</option>
<option value=\"$myurl/translate/language/German\">German</option>
<option value=\"$myurl/translate/language/Spanish\">Spanish</option>
<option value=\"$myurl/translate/language/French\">French</option>
<option value=\"$myurl/translate/language/Italian\">Italian</option>
<option value=\"$myurl/translate/language/Portuguese\">Portuguese</option>
<option value=\"$myurl/translate/language/Japanese\">Japanese </option>
<option value=\"$myurl/translate/language/Korean\">Korean</option>
<option value=\"$myurl/translate/language/Chinese-Simplified\">Chinese&nbsp;(Simplified) </option>
</select><BR>
</form>";
?>
