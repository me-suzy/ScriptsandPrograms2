<?
$include = <<< HTML

Legg til i "Fra pressen"<br><br><form name='add' method='post' action='link.php'>
<table frame='0'>
<tr>
<td valign='top'>Avis:</td><td valign='top'><input type='text' name='avis' size='40'></td>
</tr>
<tr>
<td valign='top'>URL:</td><td valign='top'><input type='text' name='url' size='40' value='http://'></td>
</tr>
<tr>
<td valign='top'>Overskrift:</td><td valign='top'><input type='text' name='overskrift' size='40'></td>
</tr>
</table>
<input type='submit' value='legg til link'>
</form>
<br><br><br>Endre avstemningen:<br><br><a href="news/admin.php">Klikk her</a><br><br><br><br><br>



HTML;
echo $include;
?>