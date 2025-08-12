<?php

include "additional.php";

function create_password( $playername, $townname, $itemnum, $codetype, $leadspace, $custom="" )
{
	if(strtolower(substr($itemnum,0,2)) == "0x")$itemnum = hex2dec("".substr($itemnum,2));
	$passcode = mMpswd_make_passcode( 4, 1, $playername, $townname, $itemnum, 0, $codetype, $leadspace, $custom );
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

	return $finalcode;
}

function create_password_from_byte( $playername, $townname, $itemnum, $leadspace, $byte1, $byte2 )
{
	if(strtolower(substr($itemnum,0,2)) == "0x")$itemnum = hex2dec("".substr($itemnum,2));
	$passcode = mMpswd_make_passcode_from_byte( 4, 1, $playername, $townname, $itemnum, 0, $leadspace, $byte1, $byte2 );
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

	return $finalcode;
}
function decode_password( $passcode)
{
	$passcode2 = $passcode;
	$passcode2 = str_replace("\n","",$passcode2);
	$passcode2 = str_replace(chr(0),"",$passcode2);
	$passcode2 = str_replace(chr(9),"",$passcode2);
	$passcode2 = str_replace(chr(10),"",$passcode2);
	$passcode2 = str_replace(chr(13),"",$passcode2);
	$passcode2 = str_replace("&",chr(0x2D),$passcode2);
	$passcode2 = str_replace(" ","",$passcode2);

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

		$itemnum = $outputcode["itemnum"];
		$codebyte0 = ord($outcode7[0]);
		$codebyte1 = ord($outcode7[1]);
		$modbyte0 = $outputcode["modbyte0"];
		$modbyte1 = $outputcode["modbyte1"];
		$modbyte2 = $outputcode["modbyte2"];
		$modbyte3 = $outputcode["modbyte3"];
		$modbyte4 = $outputcode["modbyte4"];
		$townname = $outputcode["townname"];
		$playername = $outputcode["playername"];
		$data = $outputcode["data"];

		return Array("itemnum"    => $itemnum,
			         "codebyte0"  => $codebyte0,
			         "codebyte1"  => $codebyte1,
			         "modbyte0"   => $modbyte0,
			         "modbyte1"   => $modbyte1,
			         "modbyte2"   => $modbyte2,
			         "modbyte3"   => $modbyte3,
			         "modbyte4"   => $modbyte4,
			         "townname"   => $townname,
			         "playername" => $playername,
			         "data"       => $data,
			         "outputcode" => $outputcode);
	}
	return null;
}
?>