<?
if($itemnum)
{
	include "additional.php";

	if(!$playername)$playername = "";
	if(!$townname)$townname = "";

	$itemnum2 = $itemnum;

	if(strtolower(substr($itemnum2,0,2)) == "0x")$itemnum2 = hex2dec("".substr($itemnum2,2));

	if(!is_string($itemnum2))
	{
		if($codetype == "C") $passcode = mMpswd_make_passcode( 4, 1, $playername, $townname, $itemnum2, 0, $codetype, $leadspace, $custom );
		else $passcode = mMpswd_make_passcode( 4, 1, $playername, $townname, $itemnum2, 0, $codetype, $leadspace );
		$passcode = mMpswd_substitution_cipher( $passcode );
		$passcode = mMpswd_transposition_cipher( $passcode, 1, 0 );
		$passcode = mMpswd_bit_shuffle( $passcode, 0 );
		$passcode = mMpswd_chg_RSA_cipher( $passcode );
		$passcode = mMpswd_bit_mix_code( $passcode );
		$passcode = mMpswd_bit_shuffle( $passcode, 1 );
		$passcode = mMpswd_transposition_cipher( $passcode, 0, 1 );
		$finalcode = mMpswd_chg_6bits_code( $passcode );
		$finalcode = mMpswd_chg_common_font_code( $finalcode );

		$finalcode = str_replace("-","&",$finalcode);

		$returnmessage = "Trade Code:<br>\n";
		$returnmessage.= substr($finalcode,0,14)."<br>\n".substr($finalcode,14)."\n\n<br>&nbsp;<br>";
		$picpass = $finalcode;
		$picpass = str_replace("&","%26",$picpass);
		$picpass = str_replace("#","%D1",$picpass);
		$returnmessage.= "<img src='./stringtotext.php?getstring=$picpass&limit=14' border=0><br>&nbsp;<br>";

	}
	else
	{
		$returnmessage = "<b>The item number you've entered is not valid. Please enter a valid decimal number, or a hexadecimal number preceded by 0x.</b>";
	}
}
else if($playername || $townname)
{
		$returnmessage = "<b>The item number was missing.</b>";
}
else
{
	$returnmessage = "<b>No data collected.</b>";
}

?>
<title>Animal Crossing Trade Code Generator+</title>
<center>
<h1>Animal Crossing Trade Code Generator</h1>
<h3>Original by MooglyGuy / UltraMoogleMan</h3>
<h5>Ported to PHP by Gary Kertopermono</h5>
This is the online version of the Code Generator. With it you can generate several codes. However, some codes currently don't work. This version has aditional information.<p>
<b>Usage:</b><br>
Just choose what type of code you want. Then type the item number. You can find all the item numbers here:<p>
<a href="./itemlistingp.php" target="_blank">Item list</a><p>
You can download this script, along with the code generator, here:<p>
<a href="./ac_tradecode_php.zip">[[DOWNLOAD]]</a><p>
You can view the latest changes here:<p>
<a href="./changes_decoder.txt" target="_blank">Decoder</a><br>
<a href="./changes_codegen.txt" target="_blank">Code Generator</a><p>
<p>
<table border=0><tr><td>
<form method="post" name="codegen">
Code type:
<select name="codetype">
<option value="P" <? if($codetype == "P")echo "selected";?>>Player-to-Player</option>
<option value="N" <? if($codetype == "N")echo "selected";?>>NES Contest</option>
<option value="U" <? if($codetype == "U")echo "selected";?>>Universal</option>
<option value="C" <? if($codetype == "C")echo "selected";?>>Custom</option>
</select><br>
Item number: <input type=text name="itemnum" <? if($itemnum)echo "value=$itemnum";?>><br>
Townname: <input type=text name="townname" maxlength=8 <? if($townname)echo "value=$townname";?>><br>
Playername: <input type=text name="playername" maxlength=8 <? if($playername)echo "value=$playername";?>><p>
<input type=checkbox name="leadspace" <? if($leadspace)echo "value=$checked";?>>Add leading space.<p>
*Custom algorithm (expert, only used with Custom):<br>
<textarea name="custom" cols=40 rows=5><? if($custom)echo "$custom";?></textarea><br>
<input type=submit>
</form></td><td>
<script language="JavaScript">
<!--

function AddChar( $value )
{
	if(document.plus.typ[0].checked) document.codegen.townname.value+=String.fromCharCode($value);
	if(document.plus.typ[1].checked) document.codegen.playername.value+=String.fromCharCode($value);
}
//-->
</script>

<?php
for($idx=0;$idx<0x100;$idx++)
{
	if($idx % 16 == 0) echo "<br>";
	echo "<a href='JavaScript:AddChar($idx)'><img src='./ripchar.php?char=$idx' border=0></a>";
}

?><p>
<form name="plus"><input type=radio name="typ" value=0 checked>Add to townname<br><input type=radio name="typ" value=1>Add to playername</form>
</td></tr></table><p>

<table width=50%><tr><td bgcolor="#CECECE"><?php echo "<code>".$returnmessage."</code><p>"; ?></td></tr></table><hr><h3>Custom algorithm usage</h3>
&lt;bit operator&gt;=&lt;numeric value&gt;<p>
&lt;bit operator&gt;: The following bit operators are supported:<br>
& = AND<br>
| = OR<br>
^ = XOR<br>
&lt;&lt; = Shift left<br>
&gt;&gt; = Shift right<p>

&lt;numeric value&gt;: A decimal or hexadecimal (preceded with 0x).<p>

Example:<p>

<code><pre>&=0x00000018
|=0x61</pre></code>
</center>