<?php
include("./header.inc.php");
require('./prepend.inc.php');

$result = mysql_query("SELECT name, konummer, banklz, bname FROM `demo_a_bank`");
$myrow = mysql_fetch_row($result);
$inhaber = $myrow[0];
$nummer = $myrow[1];
$blz = $myrow[2];
$bname = $myrow[0];

global $rechnung , $name , $email , $password , $points , $url ;
$eins=rand(1,99);

$rechnungg = $eins + $rechnung;

$query="INSERT INTO demo_a_bebuchen (name, email, password, points, url, rechnung) VALUES ('$name', '$email', '$password', '$points', '$url', '$rechnungg');";
        mysql_query($query);

mail("$email", "Invoice from $seitenname", "\nDear customer \n\nThank you for ordering at $seitenname\n \n Here is, where to transfer the money:\n\nAccount owner = $inhaber \nAccount no = $nummer \nBank no = $blz \nBank = bname \nIntended purpose = $rechnungg \n\n\nFor alternative payments, e.g. via cheque, please contact us: $emailadresse. Your campaign starts as soon as payment is completed. \n\n\nYours $seitenname ","From: $seitenname <$emailadresse>");
mail("$emailadresse", "Advertising order at $seitenname", "\n$points visits have been ordered.\n\nSponsor's e-mail = $email\n\nURL = $url\n\Invoice no = $rechnungg\n\n","From: $seitenname <$emailadresse>");

?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><TABLE bgcolor="#FFFFFF" bordercolor="#00FF00" border="0" align="center">
<TR>
  <TD><br><center>Thank you for ordering<br>at <? echo "$seitenname"; ?><br><br>
  <b>Here you can see your data as it was submitted</b><br>Same data was also sent to your e-mail!<br>
  <TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center" width="98%">
<TR>
  <TD bgcolor="#E4E4E4" width="200"><b>Name:</b></TD>
  <TD bgcolor="#E4E4E4"><b><? echo "$name"; ?></b></TD>
</TR>
<TR>
  <TD width="200"><b>e-mail:</b></TD>
  <TD><b><? echo "$email"; ?></b></TD>
</TR>
<TR>
  <TD bgcolor="#E4E4E4" width="200"><b>Ordered no. of visits:</b></TD>
  <TD bgcolor="#E4E4E4"><b><? echo "$points"; ?></b></TD>
</TR>
<TR>
  <TD width="200" bgcolor="#E4E4E4"><b>URL:</b></TD>
  <TD bgcolor="#E4E4E4"><b><? echo "$url"; ?></b></TD>
</TR>
<TR>
  <TD bgcolor="#E4E4E4" width="200"><b>Invoice no:</b></TD>
  <TD bgcolor="#E4E4E4"><b><? echo "$rechnungg"; ?></b></TD>
</TR>
</TABLE>

<?
include("./templates/main-footer.txt");
?>