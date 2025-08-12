<?

//$passcode = "NrsjkdgonJ@rXk%8MeiwwLAZeka8";

if($passcode)
{
	$returnmessage = "";
	include "additional.php";
	$passcode2 = $passcode;
	$passcode2 = str_replace("\n","",$passcode2);
	$passcode2 = str_replace(chr(0),"",$passcode2);
	$passcode2 = str_replace(chr(9),"",$passcode2);
	$passcode2 = str_replace(chr(10),"",$passcode2);
	$passcode2 = str_replace(chr(13),"",$passcode2);
	$passcode2 = str_replace("&",chr(0x2D),$passcode2);
	$passcode2 = str_replace(" ","",$passcode2);

	/*for($idx = 0; $idx < strlen($passcode); $idx++)
	{
		echo $passcode[$idx].":".ord($passcode[$idx])."<br>";
	}
	echo "<p>";*/

	$tempcode = $passcode2;
	$tempcode = mMpswd_adjust_letter($tempcode);
	$tempcode2 = mMpswd_chg_password_font_code( $tempcode );

	if($tempcode2[0])
	{
		$tempcode3 = mMpswd_chg_8bits_code( $tempcode2[1] );
		$outcode = mMpswd_transposition_cipher( $tempcode3,1,1);
		$outcode2 = mMpswd_decode_bit_shuffle( $outcode, 1 );
		$outcode3 = mMpswd_decode_bit_code( $outcode2 );
		$outcode4 = mMpswd_decode_RSA_cipher( $outcode3 );
		$outcode5 = mMpswd_decode_bit_shuffle( $outcode4, 0 );
		$outcode6 = mMpswd_transposition_cipher( $outcode5, 0 ,0 );
		$outcode7 = mMpswd_decode_substitution_cipher( $outcode6, 0 ,0 );
		$outputcode = mMpswd_password( $outcode7 );

		//echo "<p>$tempcode3<p>$outcode<p>$outcode2<p>$outcode3<p>$outcode4<p>$outcode5<p>$outcode6<p>$outcode7<p>$testput1<p>";

		$itemnum = $outputcode["itemnum"];
		$codebyte0 = ord($outcode7[0]);
		$codebyte1 = ord($outcode7[1]);
		$modbyte0 = $outputcode["modbyte0"];
		$modbyte1 = $outputcode["modbyte1"];
		$modbyte2 = $outputcode["modbyte2"];
		$modbyte3 = $outputcode["modbyte3"];
		$modbyte4 = $outputcode["modbyte4"];
		$playername = $outputcode["playername"];
		$townname = $outputcode["townname"];

		$trcode = str_replace(chr(0x2D),"&",$tempcode);
		$returnmessage.="Original code:\n<br>";
		$returnmessage.=substr($trcode,0,14)."\n<br>".substr($trcode,14)."\n\n<br>&nbsp;<br>";
		
		$returnmessage.="Item Number: 0x".str_pad( dechex( $itemnum ),4 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.="Code Byte 0: 0x".str_pad( dechex( $codebyte0 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.="Code Byte 1: 0x".str_pad( dechex( $codebyte1 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.=" - Code Descriptor 0: 0x".str_pad( dechex( $modbyte0 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.=" - Code Descriptor 1: 0x".str_pad( dechex( $modbyte1 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.=" - Code Descriptor 2: 0x".str_pad( dechex( $modbyte2 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.=" - Code Descriptor 3: 0x".str_pad( dechex( $modbyte3 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.=" - Code Descriptor 4: 0x".str_pad( dechex( $modbyte4 ),2 ,"0",STR_PAD_LEFT)."\n<br>";
		$returnmessage.="Town name: $townname\n\n<br>";
		$returnmessage.="Player name: $playername\n<br>&nbsp;<br>";

		include "itemlist.php";
		include "nothinglist.php";
		include "glitchlist.php";
		$itemname = $itemlist[$itemnum];
		if(!$itemname)$itemname = "???";
		$returnmessage.="Item name: $itemname<br>&nbsp;<br>";

		$passtype = mMpswd_test_password( 4, 1, $playername, $townname, $itemnum, 0, $codebyte0, $codebyte1);
		$checksum = mMpswd_checksum_password( 4, 1, $playername, $townname, $itemnum, 0, $codebyte0, $codebyte1);
		//$checksum = mMpswd_checksum_password( 4, 1, "uuuuuuuu", "hhhhhhhh", $itemnum, 0, $codebyte0, $codebyte1);

		$returnmessage.= "Code type: $passtype<br>&nbsp;<br>";

		$returnmessage.= "Standard Code Byte 0: 0x".str_pad( dechex( $checksum[1] ),2 ,"0",STR_PAD_LEFT)."<br>&nbsp;<br>";

		$returnmessage.="Output data hex:\n<br>";

		for($idx = 0; $idx < sizeof($outputcode["data"]); $idx++)
		{
			$returnmessage.= str_pad(dechex($outputcode["data"][$idx]),2,"0",STR_PAD_LEFT)." ";
		}

	}
	else
	{
		$returnmessage.= "<b>The code seems to be invalid. Make sure you typed in the code right.\n<p>";
		$returnmessage.= "$passcode<p>";
		$returnmessage.= "Please try again.</b>";
	}
}
else
{
	$returnmessage = "<b>No data collected.</b>";
}
?>
<title>Animal Crossing Trade Code Decoder</title>
<center>
<h1>Animal Crossing Trade Code Decoder</h1>
<h3>Original by MooglyGuy / UltraMoogleMan</h3>
<h5>Ported to PHP by Gary Kertopermono</h5>
This is the Trade Code Decoder for Animal Crossing. With it you can identify which item you recieved from your friend. You can also check what kind of code it is.<p>
<b>Usage:</b><br>
Just enter the code in the text area. It will output the data.<p>
You can download this script, along with the code generator, here:<p>
<a href="./ac_tradecode_php.zip">[[DOWNLOAD]]</a><p>
You can view the latest changes here:<p>
<a href="./changes_decoder.txt" target="_blank">Decoder</a><br>
<a href="./changes_codegen.txt" target="_blank">Code Generator</a><p>
<form method="post">
<textarea cols=28 rows=3 name="passcode"><? if($passcode)echo $passcode;?></textarea><br>
<input type=submit>
</form>
<p>
<table width=50%><tr><td bgcolor="#CECECE"><?php echo "<code>".$returnmessage."</code><p>"; ?></td></tr></table></center>