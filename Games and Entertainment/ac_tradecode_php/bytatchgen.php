<center>
<center>
<h1>Animal Crossing Bytatch</h1>
<h3>Original by MooglyGuy / UltraMoogleMan</h3>
<h5>Ported to PHP by Gary Kertopermono</h5>
With this you can create a code based on two bytes. It is hard to explain, but to put it easy, the second byte is, for the most codes, 0xFF. The first byte depends on the checksum, and the algorithms used on it. This is to test with these codes. This project is also included in the download.<p>
<a href="./ac_tradecode_php.zip">[[DOWNLOAD]]</a><p>
<form method="post">
Item number: <input type=text name="itemnum" <? if($itemnum)echo "value=$itemnum";?>><br>
Player name: <input type=text name="playername" maxlength=8 <? if($playername)echo "value=$playername";?>><br>
Town name: <input type=text name="townname" maxlength=8 <? if($townname)echo "value=$townname";?>><p>
Constant byte: <input type=text name="cbyte" <? if($cbyte)echo "value=$cbyte";?>><br>
Changing byte:
<select name="changer">
<option value="1" <? if($changer == 1)echo "selected";?>>Byte 1</option>
<option value="2" <? if($changer == 2)echo "selected";?>>Byte 2</option>
</select><br>
<input type=checkbox name="leadspace" <? if($leadspace)echo "value=$checked";?>>Add leading space.<p>
<input type=submit>
</form>
<p>
<?php
if($cbyte != NULL)
{
if($itemnum != NULL)
{
	include "itemlist.php";
	include "tools_includer.php";

	echo "Player name: $playername\n<br>";
	echo "Town name: $townname\n<br>";
	$itemname = $itemlist[$itemnum];
	if(!$itemname)$itemname = "Unknown";
	echo "Item name: $itemname\n\n<p>";
?>
 <table width=90% bgcolor="#CECECE">
  <tr bgcolor="#EAEAEA">
   <td>
    <b>Byte 1</b>
   </td>
   <td>
    <b>Byte 2</b>
   </td>
   <td>
    <b>Codes</b>
   </td>
  </tr>
<?
	for($idx = 0; $idx < 256;$idx++)
	{
		$byte1 = $cbyte;
		$byte2 = $cbyte;

		if($changer==1)$byte1 = $idx;
		else $byte2 = $idx;

		$code = create_password_from_byte($playername,$townname,$itemnum,$leadspace,$byte1,$byte2);
		$code = substr($code,0,14)."<br>\n".substr($code,14);
		$byte1h = str_pad( dechex( all2int($byte1) ),2 ,"0",STR_PAD_LEFT);
		$byte2h = str_pad( dechex( all2int($byte2) ),2 ,"0",STR_PAD_LEFT);
		echo "<tr bgcolor='#FFFFFF'><td>0x$byte1h</td><td>0x$byte2h</td><td><code><font size=1>" .$code. "</font></code></td></tr>\n";
	}
}
else
{
	echo "<b>Item number is missing!</b>";
}
?>
</table>
<?php } ?>
</center>