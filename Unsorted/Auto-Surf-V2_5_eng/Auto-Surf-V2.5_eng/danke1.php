<?php
require('./prepend.inc.php');

mail("$emailadresse", "Contact to webmaster", "\nThis e-mail was sent by the contact-form at $seitenname\n\nwritten by $name\n\n $text","From: $seitenname <$email>");
?>


<?
include("./templates/main-header.txt");
?>


<br><br><center>Your text was submitted.<br>It will be answered asap.<br><br><br>

<?
include("./templates/main-footer.txt");
?>