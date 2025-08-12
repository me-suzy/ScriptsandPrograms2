<?
include("header.inc.php");

$result = mysql_query("SELECT name, email, ort, tel, banner, bild, ziel, besucher, seite FROM `demo_a_buchen` LIMIT 0, 10");
$myrow = mysql_fetch_row($result);
$name = $myrow[0];
$email = $myrow[1];
$ort = $myrow[2];
$tel = $myrow[3];
$banner = $myrow[4];
$bild = $myrow[5];
$ziel = $myrow[6];
$besucher = $myrow[7];
$seite = $myrow[8];
?>
<?
include("../templates/admin-header.txt");
?>
<table width="100%" border="1" cellspacing="1" cellpadding="0" bordercolor="#000000">
  <tr align="center">
    <td width="20"><b>Name</b></td>
    <td width="25"><b>E-Mail</b></td>
    <td width="30"><b>Ort</b></td>
    <td width="10"><b>Tel</b></td>
    <td width="10"><b>Banner</b></td>
    <td width="30"><b>Bild</b></td>
    <td width="30"><b>Ziel</b></td>
    <td width="10"><b>Besucher</b></td>
    <td width="30"><b>Seite</b></td>
    <td width="20"><b>Erledigt</b></td>
  </tr>



  <tr align="center">
    <td width="20"><? echo "$name"; ?></td>
    <td width="25"><? echo "$email"; ?></td>
    <td width="30"><? echo "$ort"; ?></td>
    <td width="10"><? echo "$tel"; ?></td>
    <td width="10"><? echo "$banner"; ?></td>
    <td width="30"><? echo "$bild"; ?></td>
    <td width="30"><? echo "$ziel"; ?></td>
    <td width="10"><? echo "$besucher"; ?></td>
    <td width="30"><? echo "$seite"; ?></td>
    <td width="20"><form method="post" action="./erledigt.php"><input type="hidden" name="name" value="Done"><input type="submit" value="Done"></form></td>
  </tr>



</table>
<?
include("../templates/admin-footer.txt");
?>