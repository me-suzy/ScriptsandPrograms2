<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.php";
include "templates/header.php";


if ($page == search) {

if (!$t_step) {$t_step = 0;}
if (!$from) {$from = 0;}

//$t_step=10;
$fromage = $toage = "";
if ($agefrom != "") $fromage = " AND age >= '".$agefrom."'";
if ($ageto != "") $toage = " AND age <= '".$ageto."'";
if ($photos == "on")
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." AND pic != '' order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." AND pic != ''";
}
else
{
$sql = "SELECT * FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage." order by imgtime DESC limit $from,$t_step";
$tsql = "SELECT count(*) as total FROM $mysql_table WHERE country LIKE '%".$country."%' AND gender LIKE '%".$gender."%' AND purposes LIKE '%".$purposes."%'".$fromage.$toage;
}
$result = mysql_db_query($mysql_base, $sql, $mysql_link)  or die(mysql_error());
if (mysql_fetch_array($result) == 0)
{
echo $err_mes_top.$lang[26].$err_mes_bottom;
include "templates/footer.php";
die;
}
else
{
$result = mysql_db_query($mysql_base, $sql, $mysql_link)  or die(mysql_error());
$tquery = mysql_db_query($mysql_base, $tsql, $mysql_link)  or die(mysql_error());
$trows = mysql_fetch_array($tquery);
$count = $trows[total];
echo "<br><center><span class=head>".$lang[27]." - ".$count."</span></center><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black width=740><tr class=desc align=center><td>".$lang[9]."</td><td>".$lang[14]."</td><td>".$lang[13]."</td><td>".$lang[15]."</td><td>".$lang[20]."</td><td>".$lang[21]."</td><td>".$lang[118]."</td></tr>";
$colorchange = 0;
while ($i = mysql_fetch_array($result)) {
if ($i[pic] == "")
{
$picav = $lang[84];
}
else
{
$picav = "<a href=view.php?l=".$l."&id=".$i[id].">".$lang[85]."</a>";
}
if ($colorchange == 0)
{
$data=date("d/m/Y", $i[imgtime] + $date_diff*60*60);
echo "<tr bgcolor=".$color1." align=center><td><a href=view.php?l=".$l."&id=".$i[id].">".$i[user]."</a></td><td>".$langgender[$i[gender]]." ".$langpurposes[$i[purposes]]."</td><td>".$i[country]."</td><td>".$i[city]."</td><td>".$i[age]."</td><td>".$picav."</td><td>".$data."</td></tr>";
$colorchange = 1;
}
else
{
echo "<tr bgcolor=".$color2." align=center><td><a href=view.php?l=".$l."&id=".$i[id].">".$i[user]."</a></td><td>".$langgender[$i[gender]]." ".$langpurposes[$i[purposes]]."</td><td>".$i[country]."</td><td>".$i[city]."</td><td>".$i[age]."</td><td>".$picav."</td><td>".$data."</td></tr>";
$colorchange = 0;
}
}

// Page generating
////////////////////////////////
if ($t_step < $count)
{
echo "<tr bgcolor=".$color1." align=center><td colspan=7>".$lang[86]." : ";

$mesdisp = $t_step;

	$max = $count;
	$from = ($from > $count) ? $count : $from;
	$from = ( floor( $from / $mesdisp ) ) * $mesdisp;

		if (($cpage % 2) == 1)	//1,3,5,...
			$pc = (int)(($cpage - 1) / 2);
		else
			$pc = (int)($cpage / 2);	

		if ($from > $mesdisp * $pc)	
			$str.= "<a href=\"?l=".$l."&page=search&from=0&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">1</a> ";

		if ($from > $mesdisp * ($pc + 1))
			$str.= "<B> . . . </B>";

		for ($nCont=$pc; $nCont >= 1; $nCont--)	// 1 & 2 before
			if ($from >= $mesdisp * $nCont) {
				$tmpStart = $from - $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"?l=".$l."&page=search&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		$tmpPage = $from / $mesdisp + 1;	// page to show
		$str.= " [<B>$tmpPage</B>] ";

		$tmpMaxPages = (int)(($max - 1) / $mesdisp) * $mesdisp;	// 1 & 2 after
		for ($nCont=1; $nCont <= $pc; $nCont++)
			if ($from + $mesdisp * $nCont <= $tmpMaxPages) {
				$tmpStart = $from + $mesdisp * $nCont;
				$tmpPage = $tmpStart / $mesdisp + 1;
				$str.= "<a href=\"?l=".$l."&page=search&from=".$tmpStart."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">".$tmpPage."</a> ";
			}

		if ($from + $mesdisp * ($pc + 1) < $tmpMaxPages)	
			$str.= "<B> . . . </B>";

		if ($from + $mesdisp * $pc < $tmpMaxPages)	{ 
			$tmpPage = $tmpMaxPages / $mesdisp + 1;
			$str.= "<a href=\"?l=".$l."&page=search&from=".$tmpMaxPages."&t_step=".$t_step."&country=".$country."&gender=".$gender."&purposes=".$purposes."&agefrom=".$agefrom."&ageto=".$ageto."&photos=".$photos."\">".$tmpPage."</a> ";
		}
echo $str;
echo "</td></tr></table>";
}
else
{
echo "</table><br>";
}
// end page generating

}


} 
else {
?>
<form action="search.php?l=<?php echo $l; ?>&page=search" method="post">
<center><span class=head><?php echo $lang[3]; ?></span>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black bgcolor=<?php echo $color3; ?>>
<tr><td width=200><span class=mes><?php echo $lang[13]; ?></td>
<td><select class=select name="country">
<option value=""><?php echo $lang[95]; ?>
<?php 
include "templates/countries.php";
?>
</td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[11]; ?></td>
       <td><select class=select name=gender>
<option value=""><?php echo $lang[95]; ?>
<OPTION value=1><?php echo $langgender[1]; ?>
<OPTION value=2><?php echo $langgender[2]; ?>
</select></td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[14]; ?></td>
       <td><select class=select name=purposes>
<option value=""><?php echo $lang[95]; ?>
<?php
$p = 1;
while ($langpurposes[$p]) 
{
echo "<OPTION value=".$p.">".$langpurposes[$p];
	$p++;
}
?>
</select></td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[20]; ?></td>
       <td><span class=mes><?=$lang[189];?> <select class=sinput name=agefrom>
<option value=""> ----
<?
for ($i=$age_s;$i<=$age_b;$i+=$age_between) {
echo "<OPTION value=\"".$i."\">".$i;
}
?>
</select>
<?=$lang[190];?>
<select class=sinput name=ageto>
<option value=""> ----
<?
for ($i=$age_s;$i<=$age_b;$i+=$age_between) {
echo "<OPTION value=\"".$i."\">".$i;
}
?>
</select>
</td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[87]; ?></td>
       <td><select class=select name=t_step>
<option selected value=10>10 <?php echo $lang[88]; ?>
<OPTION value=20>20 <?php echo $lang[88]; ?>
<OPTION value=30>30 <?php echo $lang[88]; ?>
<OPTION value=40>40 <?php echo $lang[88]; ?>
<OPTION value=50>50 <?php echo $lang[88]; ?>
</select></td>
</tr>
<tr>
       <td><span class=mes><?php echo $lang[51]; ?></td>
       <td><input type="checkbox" name="photos"></td>
</tr>
<tr>
       <td colspan=2 align=right><input class=button type="submit" Value="<?php echo $lang[3]; ?>"> <input class=button type="reset"></td>
</tr>
</table>
</form>
<?php
}
include "templates/footer.php";
?>