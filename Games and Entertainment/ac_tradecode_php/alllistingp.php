<title>Animal Crossing - All Item Listing+</title>
<center>
<h1>All item list+</h1>
This item list contains every item currently known, plus every offset which has been proven either nothing or glitched. This also shows under what condition the item works, or just special notes.
 <table width=90% bgcolor="#CECECE">
  <tr bgcolor="#EAEAEA">
   <td width=20%>
    <b>Item number (dec)</b>
   </td>
   <td width=20%>
    <b>Item number (hex)</b>
   </td>
   <td>
    <b>Description</b>
   </td>
   <td width=35%>
    <b>Notes</b>
   </td>
  </tr>
<?

include "itemlist.php";
include "nothinglist.php";
include "glitchlist.php";
include "speciallist.php";

ksort($itemlist);
reset($itemlist);
while (list($key, $val) = each($itemlist))
{
	echo "<tr bgcolor='#FFFFFF'><td>$key</td><td>0x".str_pad( dechex( $key ),4 ,"0",STR_PAD_LEFT)."</td><td>$val</td><td>$speciallist[$key]</td></tr>\n";
}
?>
</table>
</center>