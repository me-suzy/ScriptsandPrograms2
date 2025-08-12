<?php
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";
?>


<center>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
    <Tr>
		<Td Width="728" align=left class=mes bgcolor=<?php echo $color3; ?> valign=top><?php echo $lang[75]; ?><br>

<?php
$sql1 = "SELECT count(*) as total1 FROM ".$mysql_table." where gender = '1'";
$sql2 = "SELECT count(*) as total2 FROM ".$mysql_table." where gender = '2'";
$result1 = mysql_db_query($mysql_base, $sql1, $mysql_link) or die(mysql_error());
$result2 = mysql_db_query($mysql_base, $sql2, $mysql_link) or die(mysql_error());

// counting
$trows = mysql_fetch_array($result1);
$malenum = $trows[total1];
$trows = mysql_fetch_array($result2);
$femalenum = $trows[total2];
$total = $malenum + $femalenum;

if ($total != 0)
{

$procm = $malenum * 160 / $total; 
$procf = $femalenum * 160 / $total; 
SetType($procm,"integer");
SetType($procf,"integer");


echo "<br><center><span class=head>".$lang[166]."</span><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black>
<tr class=desc>
<td colspan=3 align=center>".$lang[96]." (".$lang[11].")</td></tr>
<tr class=desc align=center>
<td width=130>".$lang[97]."</td>
<td width=160>".$lang[98]."</td>
<td width=30>".$lang[99]."</td></tr>
<tr class=desc>
<td width=130>".$lang[100]."</td>
<td width=160><img src=".$url."/images/1.jpg height=12 width=160></td>
<td width=30 align=center>".$total."</td></tr>
<tr class=desc>
<td width=130>".$langgender[1]."</td>
<td width=160><img src=".$url."/images/2.jpg height=12 width=".$procm."></td>
<td width=30 align=center>".$malenum."</td></tr>
<tr class=desc>
<td width=130>".$langgender[2]."</td>
<td width=160><img src=".$url."/images/3.jpg height=12 width=".$procf."></td>
<td width=30 align=center>".$femalenum."</td></tr>
</table></center>
<br>";
}
?>

</Td>
	</Tr>
</Table>

<?php
include "templates/footer.php";
?>
</body>
</html>