<title>Animal Crossing Batch-Search</title>
<center>
<h1>Animal Crossing Batch-Search</h1>
<h3>Original by MooglyGuy / UltraMoogleMan</h3>
<h5>Ported to PHP by Gary Kertopermono</h5>
This project was made to generate codes for unknown items. You can just enter the offset (in decimal or in hex, with the leading 0x) and the ammount of results (default 100). This project is also included in the download.<p>
<a href="./ac_tradecode_php.zip">[[DOWNLOAD]]</a><p>
<form method="post">
Offset: <input type=text name="soffset" <? if($soffset) echo "value=\"$soffset\""?>><br>
Results (default 0): <input type=text name="results" <? if($soffset) echo "value=\"$results\""?>><br>
<input type=checkbox name="printable" <? if($soffset) echo "checked"?>>Printable version<br>
<input type=submit>
</form>
<p>
<?php
if(!$printable)
{
	?>
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
  <?php
}

include "itemlist.php";
include "tools_includer.php";

	$soff = $soffset;

	if($soff<0)$soff=0;

	if($soff>0xFFFF)
	{
		$soff = 0xFFFF - 100;
		$results = 100;
	}

	if($results<1)$results = 100;

	if($results > 0xFFFF-$soff)$results = 0xFFFF - $soff;

	for($idx = 0; $idx < $results ; $idx++)
	{
		$key = $soff+$idx;
		$keyh = str_pad( dechex( $key ),4 ,"0",STR_PAD_LEFT);
		$val = $itemlist[$key];
		if(!$val)$val = "???";

		$str1 = "";
		$str2 = "";

		$nintendocode = create_password("","Nintendo",$key,"U",0);
		$nintendocode = substr($nintendocode,0,14)."<br>\n".substr($nintendocode,14);
		$nintendopowercode = create_password("Power","Nintendo",$key,"U",1);
		$nintendopowercode = substr($nintendopowercode,0,14)."<br>\n".substr($nintendopowercode,14);
		if(!$printable) echo "<tr bgcolor='#FFFFFF'><td>$key</td><td>0x$keyh</td> <td>$val</td> <td><code><font size=2>" .$nintendocode. "</font></code></td> <td><code><font size=2>" .$nintendopowercode. "</font></code></td></tr>\n";
		else echo "<code>Item: $val\n<br>Number: 0x$keyh ($key)\n<br><b>Nintendo</b><br>$nintendocode\n<br><b>Nintendo Power</b><br>$nintendopowercode</code>\n</code><p>\n";
	}
if(!$printable)
{
?>
</table>
<?
}
?>
</center>