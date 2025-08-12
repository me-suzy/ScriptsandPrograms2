<?php
echo "<p style=\"font-size:80%;\">powered by <a href=\"http://www.epicdesigns.co.uk/projects/tinybb/\">tinybb $tinybb_release</a></p><p></p>\n";
if (strlen($tinybb_footers) > 0) { require_once($tinybb_footers); }
mysql_close($mysql);
?>