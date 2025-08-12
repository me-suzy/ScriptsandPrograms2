<center>
<center>
<h1>Animal Crossing Batch-Gen</h1>
<h3>Original by MooglyGuy / UltraMoogleMan</h3>
<h5>Ported to PHP by Gary Kertopermono</h5>
This is actually my little project. This converts every known object to a Universal Code. I added multiple ones, to make it more fun to compare. This project is also included in the download.<p>
<a href="./ac_tradecode_php.zip">[[DOWNLOAD]]</a><p>
 <table width=90% bgcolor="#CECECE">
  <tr bgcolor="#EAEAEA">
   <td>
    <b>Item number (dec)</b>
   </td>
   <td>
    <b>Item number (hex)</b>
   </td>
   <td>
    <b>Description</b>
   </td>
   <td>
    <b>Nintendo Universal Codes</b>
   </td>
   <td>
    <b>Nintendo Power Universal Codes</b>
   </td>
  </tr>
<?

include "itemlist.php";
include "tools_includer.php";

reset($itemlist);
while (list($key, $val) = each($itemlist))
{
	$nintendocode = create_password("","Nintendo",$key,"U",0);
	$nintendocode = substr($nintendocode,0,14)."<br>\n".substr($nintendocode,14);
	$nintendopowercode = create_password("Power","Nintendo",$key,"U",1);
	$nintendopowercode = substr($nintendopowercode,0,14)."<br>\n".substr($nintendopowercode,14);
	echo "<tr bgcolor='#FFFFFF'><td>$key</td><td>0x" . str_pad( dechex( $key ),4 ,"0",STR_PAD_LEFT) . "</td> <td>$val</td> <td><code><font size=1>" .$nintendocode. "</font></code></td> <td><code><font size=1>" .$nintendopowercode. "</font></code></td></tr>\n";
}
?>
</table>
</center>