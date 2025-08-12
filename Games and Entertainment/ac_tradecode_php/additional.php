<?php

$usable_to_fontnum = Array(
	0x62, 0x4b, 0x7a, 0x35, 0x63, 0x71, 0x59, 0x5a, 0x4f, 0x64, 0x74, 0x36, 0x6e, 0x6c, 0x42, 0x79,
	0x6f, 0x38, 0x34, 0x4c, 0x6b, 0x25, 0x41, 0x51, 0x6d, 0x44, 0x50, 0x49, 0x37, 0x2d, 0x52, 0x73,
	0x77, 0x55, 0x23, 0x72, 0x33, 0x45, 0x78, 0x4d, 0x43, 0x40, 0x65, 0x39, 0x67, 0x76, 0x56, 0x47,
	0x75, 0x4e, 0x69, 0x58, 0x57, 0x66, 0x54, 0x4a, 0x46, 0x53, 0x48, 0x70, 0x32, 0x61, 0x6a, 0x68);

$mMpswd_select_idx_table = Array();

$mMpswd_select_idx_table[0] = Array( 0x11, 0x0b, 0x00, 0x0a, 0x0c, 0x06, 0x08, 0x04 );
$mMpswd_select_idx_table[1] = Array( 0x03, 0x08, 0x0b, 0x10, 0x04, 0x06, 0x09, 0x13 );
$mMpswd_select_idx_table[2] = Array( 0x09, 0x0e, 0x11, 0x12, 0x0b, 0x0a, 0x0c, 0x02 );
$mMpswd_select_idx_table[3] = Array( 0x00, 0x02, 0x01, 0x04, 0x12, 0x0a, 0x0c, 0x08 );
$mMpswd_select_idx_table[4] = Array( 0x11, 0x13, 0x10, 0x07, 0x0c, 0x08, 0x02, 0x09 );
$mMpswd_select_idx_table[5] = Array( 0x10, 0x03, 0x01, 0x08, 0x12, 0x04, 0x07, 0x06 );
$mMpswd_select_idx_table[6] = Array( 0x13, 0x06, 0x0a, 0x11, 0x03, 0x10, 0x08, 0x09 );
$mMpswd_select_idx_table[7] = Array( 0x11, 0x07, 0x12, 0x10, 0x0c, 0x02, 0x0b, 0x00 );
$mMpswd_select_idx_table[8] = Array( 0x06, 0x02, 0x0c, 0x01, 0x08, 0x0e, 0x00, 0x10 );
$mMpswd_select_idx_table[9] = Array( 0x13, 0x10, 0x0b, 0x08, 0x11, 0x03, 0x06, 0x0e );
$mMpswd_select_idx_table[10] = Array( 0x12, 0x0c, 0x02, 0x07, 0x0a, 0x0b, 0x01, 0x0e );
$mMpswd_select_idx_table[11] = Array( 0x08, 0x00, 0x0e, 0x02, 0x07, 0x0b, 0x0c, 0x11 );
$mMpswd_select_idx_table[12] = Array( 0x09, 0x03, 0x02, 0x00, 0x0b, 0x08, 0x0e, 0x0a );
$mMpswd_select_idx_table[13] = Array( 0x0a, 0x0b, 0x0c, 0x10, 0x13, 0x07, 0x11, 0x08 );
$mMpswd_select_idx_table[14] = Array( 0x13, 0x08, 0x06, 0x01, 0x11, 0x09, 0x0e, 0x0a );
$mMpswd_select_idx_table[15] = Array( 0x09, 0x07, 0x11, 0x0c, 0x13, 0x0a, 0x01, 0x0b );

$mMpswd_prime_number = Array(
	0x0011, 0x0013, 0x0017, 0x001d, 0x001f, 0x0025, 0x0029, 0x002b,
    0x002f, 0x0035, 0x003b, 0x003d, 0x0043, 0x0047, 0x0049, 0x004f,
    0x0053, 0x0059, 0x0061, 0x0065, 0x0067, 0x006b, 0x006d, 0x0071,
    0x007f, 0x0083, 0x0089, 0x008b, 0x0095, 0x0097, 0x009d, 0x00a3,
    0x00a7, 0x00ad, 0x00b3, 0x00b5, 0x00bf, 0x00c1, 0x00c5, 0x00c7,
    0x00d3, 0x00df, 0x00e3, 0x00e5, 0x00e9, 0x00ef, 0x00f1, 0x00fb,
    0x0101, 0x0107, 0x010d, 0x010f, 0x0115, 0x0119, 0x011b, 0x0125,
    0x0133, 0x0137, 0x0139, 0x013d, 0x014b, 0x0151, 0x015b, 0x015d,
    0x0161, 0x0167, 0x016f, 0x0175, 0x017b, 0x017f, 0x0185, 0x018d,
    0x0191, 0x0199, 0x01a3, 0x01a5, 0x01af, 0x01b1, 0x01b7, 0x01bb,
    0x01c1, 0x01c9, 0x01cd, 0x01cf, 0x01d3, 0x01df, 0x01e7, 0x01eb,
    0x01f3, 0x01f7, 0x01fd, 0x0209, 0x020b, 0x021d, 0x0223, 0x022d,
    0x0233, 0x0239, 0x023b, 0x0241, 0x024b, 0x0251, 0x0257, 0x0259,
    0x025f, 0x0265, 0x0269, 0x026b, 0x0277, 0x0281, 0x0283, 0x0287,
    0x028d, 0x0293, 0x0295, 0x02a1, 0x02a5, 0x02ab, 0x02b3, 0x02bd,
    0x02c5, 0x02cf, 0x02d7, 0x02dd, 0x02e3, 0x02e7, 0x02ef, 0x02f5,
    0x02f9, 0x0301, 0x0305, 0x0313, 0x031d, 0x0329, 0x032b, 0x0335,
    0x0337, 0x033b, 0x033d, 0x0347, 0x0355, 0x0359, 0x035b, 0x035f,
    0x036d, 0x0371, 0x0373, 0x0377, 0x038b, 0x038f, 0x0397, 0x03a1,
    0x03a9, 0x03ad, 0x03b3, 0x03b9, 0x03c7, 0x03cb, 0x03d1, 0x03d7,
    0x03df, 0x03e5, 0x03f1, 0x03f5, 0x03fb, 0x03fd, 0x0407, 0x0409,
    0x040f, 0x0419, 0x041b, 0x0425, 0x0427, 0x042d, 0x043f, 0x0443,
    0x0445, 0x0449, 0x044f, 0x0455, 0x045d, 0x0463, 0x0469, 0x047f,
    0x0481, 0x048b, 0x0493, 0x049d, 0x04a3, 0x04a9, 0x04b1, 0x04bd,
    0x04c1, 0x04c7, 0x04cd, 0x04cf, 0x04d5, 0x04e1, 0x04eb, 0x04fd,
    0x04ff, 0x0503, 0x0509, 0x050b, 0x0511, 0x0515, 0x0517, 0x051b,
    0x0527, 0x0529, 0x052f, 0x0551, 0x0557, 0x055d, 0x0565, 0x0577,
    0x0581, 0x058f, 0x0593, 0x0595, 0x0599, 0x059f, 0x05a7, 0x05ab,
    0x05ad, 0x05b3, 0x05bf, 0x05c9, 0x05cb, 0x05cf, 0x05d1, 0x05d5,
    0x05db, 0x05e7, 0x05f3, 0x05fb, 0x0607, 0x060d, 0x0611, 0x0617,
	0x061f, 0x0623, 0x062b, 0x062f, 0x063d, 0x0641, 0x0647, 0x0649,
	0x064d, 0x0653, 0x0655, 0x065b, 0x0665, 0x0679, 0x067f, 0x0683 );

$mMpswd_chg_code_table = Array(
	0xf0, 0x83, 0xfd, 0x62, 0x93, 0x49, 0x0d, 0x3e, 0xe1, 0xa4, 0x2b, 0xaf, 0x3a, 0x25, 0xd0, 0x82,
    0x7f, 0x97, 0xd2, 0x03, 0xb2, 0x32, 0xb4, 0xe6, 0x09, 0x42, 0x57, 0x27, 0x60, 0xea, 0x76, 0xab,
    0x2d, 0x65, 0xa8, 0x4d, 0x8b, 0x95, 0x01, 0x37, 0x59, 0x79, 0x33, 0xac, 0x2f, 0xae, 0x9f, 0xfe,
    0x56, 0xd9, 0x04, 0xc6, 0xb9, 0x28, 0x06, 0x5c, 0x54, 0x8d, 0xe5, 0x00, 0xb3, 0x7b, 0x5e, 0xa7,
    0x3c, 0x78, 0xcb, 0x2e, 0x6d, 0xe4, 0xe8, 0xdc, 0x40, 0xa0, 0xde, 0x2c, 0xf5, 0x1f, 0xcc, 0x85,
    0x71, 0x3d, 0x26, 0x74, 0x9c, 0x13, 0x7d, 0x7e, 0x66, 0xf2, 0x9e, 0x02, 0xa1, 0x53, 0x15, 0x4f,
    0x51, 0x20, 0xd5, 0x39, 0x1a, 0x67, 0x99, 0x41, 0xc7, 0xc3, 0xa6, 0xc4, 0xbc, 0x38, 0x8c, 0xaa,
    0x81, 0x12, 0xdd, 0x17, 0xb7, 0xef, 0x2a, 0x80, 0x9d, 0x50, 0xdf, 0xcf, 0x89, 0xc8, 0x91, 0x1b,
    0xbb, 0x73, 0xf8, 0x14, 0x61, 0xc2, 0x45, 0xc5, 0x55, 0xfc, 0x8e, 0xe9, 0x8a, 0x46, 0xdb, 0x4e,
    0x05, 0xc1, 0x64, 0xd1, 0xe0, 0x70, 0x16, 0xf9, 0xb6, 0x36, 0x44, 0x8f, 0x0c, 0x29, 0xd3, 0x0e,
    0x6f, 0x7c, 0xd7, 0x4a, 0xff, 0x75, 0x6c, 0x11, 0x10, 0x77, 0x3b, 0x98, 0xba, 0x69, 0x5b, 0xa3,
    0x6a, 0x72, 0x94, 0xd6, 0xd4, 0x22, 0x08, 0x86, 0x31, 0x47, 0xbe, 0x87, 0x63, 0x34, 0x52, 0x3f,
    0x68, 0xf6, 0x0f, 0xbf, 0xeb, 0xc0, 0xce, 0x24, 0xa5, 0x9a, 0x90, 0xed, 0x19, 0xb8, 0xb5, 0x96,
    0xfa, 0x88, 0x6e, 0xfb, 0x84, 0x23, 0x5d, 0xcd, 0xee, 0x92, 0x58, 0x4c, 0x0b, 0xf7, 0x0a, 0xb1,
    0xda, 0x35, 0x5f, 0x9b, 0xc9, 0xa9, 0xe7, 0x07, 0x1d, 0x18, 0xf3, 0xe3, 0xf1, 0xf4, 0xca, 0xb0,
    0x6b, 0x30, 0xec, 0x4b, 0x48, 0x1c, 0xad, 0xe2, 0x21, 0x1e, 0xa2, 0xbd, 0x5a, 0xd8, 0x43, 0x7a );

$chg_ptr = Array(
	"NiiMasaru",
	"KomatsuKunihiro",
	"TakakiGentarou",
	"MiyakeHiromichi",
	"HayakawaKenzo",
	"KasamatsuShigehiro",
	"SumiyoshiNobuhiro",
	"NomaTakafumi",
	"EguchiKatsuya",
	"NogamiHisashi",
	"IidaToki",
	"IkegawaNoriko",
	"KawaseTomohiro",
	"BandoTaro",
	"TotakaKazuo",
	"WatanabeKunio",
	"RichAmtower",
	"KyleHudson",
	"MichaelKelbaugh",
	"RaycholeLAneff",
	"LeslieSwan",
	"YoshinobuMantani",
	"KirkBuchanan",
	"TimOLeary",
	"BillTrinen",
	"nAkAyOsInoNyuuSankin",
	"zendamaKINAKUDAMAkin",
	"OishikutetUYOKUNARU",
	"AsetoAminofen",
	"fcSFCn64GCgbCGBagbVB",
	"YossyIsland",
	"KedamonoNoMori" );

$chg_len = Array(0x00000009, 0x0000000f, 0x0000000e, 0x0000000f, 0x0000000d, 0x00000012, 0x00000011, 0x0000000c, 0x0000000d, 0x0000000d, 0x00000008, 0x0000000d, 0x0000000e, 0x00000009, 0x0000000b, 0x0000000d, 0x0000000b, 0x0000000a, 0x0000000f, 0x0000000e, 0x0000000a, 0x00000010, 0x0000000c, 0x00000009, 0x0000000a, 0x00000014, 0x00000014, 0x00000013, 0x0000000d, 0x00000014, 0x0000000b, 0x0000000e);

$key_idx = Array(0x00000012, 0x00000009);

// DECODER: NO
// ENCODER: YES
function hex2dec( $numstr )
{
	$multiplier = 1;
	$finalval = 0;
	$tempstr[5];
	$convbytes = Array(
		'0', '1', '2', '3', '4', '5', '6', '7',
		'8', '9', 'A', 'B', 'C', 'D', 'E', 'F' );

	for( $idx = 0; $idx < 4; $idx++ )
	{
		if( $numstr[$idx] > 0x39 )
		{
			$tempstr[$idx] = $numstr[$idx] & 0xDF;
		}
		else
		{
			$tempstr[$idx] = $numstr[$idx];
		}
	}

	for( $idx = 3; $idx >= 0; $idx-- )
	{
		for( $idx2 = 0; $idx2 < 16; $idx2++ )
		{
			if( strtoupper($tempstr[$idx]) == $convbytes[$idx2] )
			{
				break;
			}
		}
		if( $idx2 < 16 )
		{
			$finalval += $multiplier * $idx2;
		}
		$multiplier *= 16;
	}
	return $finalval;
}

function all2int( $value )
{
	if(!is_string($value)) return $value;
	$tempval = (int) $value;
	if("".$tempval == $value) return $tempval;
	$tempval2 = $value;
	if(strtolower(substr($value,0,2)) == "0x")
	{
		$hexmod = strlen(substr($value,2));
		$tempval = hexdec(substr($value,2));
		$tempval2 = substr($value,2);
	}
	else $tempval = hexdec($value);
	if(str_pad(dechex($tempval),$hexmod,"0",STR_PAD_LEFT) == $tempval2) return $tempval;
	return 0;
}

// DECODER: NO
// ENCODER: NO
/*************************************************************\
*+-----------------------------------------------------------+*
*|                                                           |*
*|  This function was only added for testing reasons.        |*
*|  It basically prints out the hexadecimal values of the    |*
*|  current code status on screen.                           |*
*|                                                           |*
*+-----------------------------------------------------------+*
\*************************************************************/
function DisplayCode( $cCode, $nNumChars )
{
    for( $idx = 0; $idx < $nNumChars; $idx++ )
    {
        echo str_pad(dechex(ord($cCode[$idx])), 2, "0", STR_PAD_LEFT)." ";
        if( $idx == 24 ) { echo "<br>\n"; }
    }
    echo "<br>\n";
}

// DECODER: YES
// ENCODER: NO
function mMpswd_adjust_letter($passcode)
{
	$passarr = Array();
	for( $idx = 0; $idx < 28; $idx++ )
	{
		$passarr[$idx] = substr($passcode,$idx,1);
		if( $passarr[$idx] == '0' ) $passarr[$idx] = 'O';
		if( $passarr[$idx] == '1' ) $passarr[$idx] = 'l';
	}
	return implode("",$passarr);
}

// DECODER: YES
// ENCODER: NO
function mMpswd_chg_password_font_code_sub( $inbyte )
{
	global $usable_to_fontnum;
	for( $idx = 0; $idx < 64; $idx++ )
	{
		if( $usable_to_fontnum[$idx] == $inbyte ) return $idx;
	}
	return 0xFF;
}

// DECODER: YES
// ENCODER: NO
function mMpswd_chg_password_font_code( $passcode )
{
	$passarr = Array();
	$temparr = Array();

	for( $idx = 0; $idx < 28; $idx++ )
	{
		$passarr[$idx] = substr($passcode,$idx,1);
		$usablebyte = mMpswd_chg_password_font_code_sub( ord($passarr[$idx]) );
		if( $usablebyte == 0xff )
		{
			return Array(0,$passcode);
		}
		$temparr[$idx] = chr($usablebyte);
	}
	return Array(1,implode("",$temparr));
}

// DECODER: NO
// ENCODER: YES
function mMpswd_chg_common_font_code( $finalcode )
{
	global $usable_to_fontnum;

    for($idx = 0; $idx < 28; $idx++)
    {
        $finalcode[$idx] = chr($usable_to_fontnum[ ord($finalcode[$idx]) ]);
    }
	return $finalcode;
}

// DECODER: NO
// ENCODER: YES
function mMpswd_chg_6bits_code( $passcode )
{
    $code8bitsIndex = 0;
    $code6bitsIndex = 0;
    $passbyte = 0;
    $destbyte = 0;
    $bytectr = 0;
    $ctr8bits = 0;
    $ctr6bits = 0;
	$finalcode = "                            ";
    while( 1 )
    {
        $passbyte = ord($passcode[$code8bitsIndex]) >> $ctr8bits;
        $ctr8bits++;
        $passbyte = ( $passbyte & 0x00000001 ) << $ctr6bits;
        $ctr6bits++;
        $destbyte |= $passbyte;
        if( $ctr6bits == 6 )
        {
            $bytectr++;
            $finalcode[$code6bitsIndex] = chr($destbyte);
            $ctr6bits = 0;
            $code6bitsIndex++;
            if( $bytectr == 28 )
                return $finalcode;
            $destbyte = 0;
        }
        if( $ctr8bits == 8 )
        {
            $ctr8bits = 0;
            $code8bitsIndex++;
        }
    }
}

// DECODER: YES
// ENCODER: NO
function mMpswd_chg_8bits_code( $passcode )
{
	$bit6idx = 0;
	$bit8idx = 0;
	$byte6idx = 0;
	$byte8idx = 0;
	$inbit = 0;
	$outbyte = 0;
	
//	$passarr = Array();
	$passarr2 = Array();
//	$passarr3 = Array();

/*	for( $idx = 0; $idx < 28; $idx++ )
	{
//		$passarr[$idx] = substr($passcode,$idx,1);
		$passarr2[$idx] = "";
	}*/

	while( true )
	{
		$inbit = ( ord($passcode[$byte6idx]) >> $bit6idx ) & 0x01;
		$inbit <<= $bit8idx;
		$bit8idx++;
		$bit6idx++;
		$outbyte |= $inbit;
		
		if( $bit8idx == 8 )
		{
			$passarr2[$byte8idx] = chr($outbyte);
			$byte8idx++;
			if( $byte8idx == 21 ) { return implode("",$passarr2); }
			$bit8idx = 0;
			$outbyte = 0;
		}
		if( $bit6idx == 6 )
		{
			$bit6idx = 0;
			$byte6idx++;
		}
	}
}

// DECODER: YES
// ENCODER: YES
function mMpswd_transposition_cipher( $passcode, $negval, $keynum )
{
	global $key_idx, $chg_ptr, $chg_len;

    $chgstroffset=0;
	$chgstrnum=0;
	$chgstrlen=0;
	$chgstridx=0;

    $transdir=0;
	$transoffset=0;

    $chgstrptr="";

    if( $negval == 1 ) $transdir = -1;
    else $transdir = 1;

    $chgstroffset = ord($passcode[$key_idx[$keynum]]) & 0x0f;
    $chgstrnum    = $chgstroffset + ($keynum * 16);

    $chgstrptr = $chg_ptr[$chgstrnum];
    $chgstrlen = $chg_len[$chgstrnum];

    $chgstridx = 0;

	$passarr = Array();

    for($idx = 0; $idx < 21; $idx++)
    {
		$passarr[$idx] = $passcode[$idx];
        if( $key_idx[$keynum] != $idx )
        {
			$transoffset  = ord($chgstrptr[$chgstridx]) * $transdir;
			$tpasscode = ord($passcode[$idx]);
			$passarr[$idx] = chr($tpasscode+$transoffset);
            $chgstridx++;
            $chgstridx %= $chgstrlen;
        }
    }
	return implode("",$passarr);
}

// DECODER: NO
// ENCODER: YES
function mMpswd_bit_shuffle( $passcode, $keynum )
{
	global $mMpswd_select_idx_table;

	$charoffset=0;
	$numchars=0;
	$tablenum=0;

	$inbyte="";
	$outbyte="";
	$tempbyte="";
	$outoffset="";

	$tempbuf = "                     ";

    if( $keynum == 0 )
    {
        $charoffset = 13;
        $numchars = 19;
    }
    else
    {
        $charoffset = 2;
        $numchars = 20;
    }

	for( $idx = 0; $idx < $numchars; $idx++ )
	{
		$newbuf[$idx] = 0;
	}

	$tempbuf = substr($passcode,0,$charoffset).substr($passcode,$charoffset+1,20-$charoffset);

	$tablenum = ( ord($passcode[$charoffset]) << 2 ) & 0x0000000c;

    $idxPtr = $mMpswd_select_idx_table[$tablenum>>2];

    for($idx1 = 0; $idx1 < $numchars; $idx1++)
    {
        $tempbyte = ord($tempbuf[$idx1]);
        for($idx2 = 0; $idx2 < 8; $idx2++)
        {
            $outoffset = $idxPtr[$idx2] + $idx1;
            $outoffset %= $numchars;
            $inbyte = $tempbyte >> $idx2;
            $outbyte = $newbuf[$outoffset];
            $inbyte = $inbyte & 0x00000001;
            $inbyte = $inbyte << $idx2;
            $inbyte = $inbyte | $outbyte;
            $newbuf[$outoffset] = $inbyte;
        }
    }

	for($idx = 0; $idx < $numchars; $idx++ )
	{
		$newbuf[$idx] = chr($newbuf[$idx]);
	}

	$tempbuf3 = implode("",$newbuf);


	$passcode = substr($tempbuf3,0,$charoffset).$passcode[$charoffset].substr($tempbuf3,$charoffset,20-$charoffset);

	/*for($idx = 0; $idx < strlen($passcodet); $idx++)
	{
		$passcode[$idx] = $passcodet[$idx];
	}*/

	return $passcode;
}

function mMpswd_decode_bit_shuffle( $passcode, $keynum )
{
	global $mMpswd_select_idx_table;

	$tempbuf="";
	$tempbuf2=Array();

	$tempbufptr=0;
	$tablenum=0;

	$inbyte="";
	$outbyte="";
	$tempbyte="";
	$outoffset="";

	if( $keynum == 0 )
	{
		$charoffset = 13;
		$numchars = 19;
	}
	else
	{
		$charoffset = 2;
		$numchars = 20;
	}

	$tempbuf = substr($passcode,0,$charoffset).substr($passcode,$charoffset+1,20-$charoffset);

	for( $idx = 0; $idx < $numchars; $idx++ )
	{
		$tempbuf2[$idx] = 0;
	}

	$tablenum = ( ord($passcode[$charoffset]) << 2 ) & 0x0000000c;

	$idxPtr = $mMpswd_select_idx_table[$tablenum>>2];

	for( $idx = 0; $idx < $numchars; $idx++ )
	{
		for( $idx2 = 0; $idx2 < 8; $idx2++ )
		{
			$outoffset = $idxPtr[$idx2] + $idx;
			$outoffset %= $numchars;
			$inbyte = ord($tempbuf[$outoffset]);
			$inbyte = ( $inbyte >> $idx2 ) & 0x01;
			$inbyte <<= $idx2;
			$tempbuf2[$idx] |= $inbyte;
		}
	}

	for($idx = 0; $idx < $numchars; $idx++ )
	{
		$tempbuf2[$idx] = chr($tempbuf2[$idx]);
	}

	$tempbuf3 = implode("",$tempbuf2);

	$passcodet = substr($tempbuf3,0,$charoffset).$passcode[$charoffset].substr($tempbuf3,$charoffset,20-$charoffset);

	for($idx = 0; $idx < strlen($passcodet); $idx++)
	{
		$passcode[$idx] = $passcodet[$idx];
	}

	return $passcode;
}

/*************************************************************\
*+-----------------------------------------------------------+*
*|                                                           |*
*|  mMpswd_bit_shift is one evil, horrible routine that      |*
*|  caused me an inordinate amount of problems when I was    |*
*|  converting it over to my own code. In essence, it        |*
*|  performs a massive rotate operation on all of the        |*
*|  passcode buffer, with the exception of byte 1. A rotate  |*
*|  operation, for those who do not know, treats the entire  |*
*|  bit space as if it is circular. Using an alphabetical    |*
*|  example, ABCDEFGHIJKL when rotated four characters to    |*
*|  the left would become EFGHIJKLABCD. This function        |*
*|  operates on the same general principal, wherein the      |*
*|  individual bits are rotated by an amount specified by    |*
*|  the second parameter.                                    |*
*|                                                           |*
*+-----------------------------------------------------------+*
\*************************************************************/
// DECODER: YES
// ENCODER: YES
function mMpswd_bit_shift( $passcode, $shiftamt )
{
    $tempbuf = "";
    $tempbuf2 = "";

	$tempbuf = substr($passcode,0,1).substr($passcode,2,19);

    if( $shiftamt > 0 )
    {
        $destpos  = $shiftamt / 8;
        $destoffs = $shiftamt % 8;

        for($idx = 0; $idx < 20; $idx++)
        {
            if( $idx > 0 )
            {
                $tempbuf2[($idx+$destpos)%20] = chr( ( ord( $tempbuf[$idx] ) << $destoffs) | ( ord( $tempbuf[($idx-1)%20] ) >> (8-$destoffs) ) );
            }
            else
            {
                $tempbuf2[($idx+$destpos)%20] = chr((ord($tempbuf[$idx]) << $destoffs) | (ord($tempbuf[19]) >> (8-$destoffs)));
            }
        }

        for($idx = 0; $idx < 20; $idx++) {$tempbuf[$idx] = $tempbuf2[/*19-*/$idx];}
    }
    else if( $shiftamt < 0 )
    {
        for($idx = 0; $idx < 20; $idx++) {$tempbuf2[$idx] = $tempbuf[19-$idx];}

        $shiftamt = 0 - $shiftamt;

        $destpos  = $shiftamt / 8;
        $destoffs = $shiftamt % 8;

        for($idx = 0; $idx < 20; $idx++)
        {
            $tempbuf[( $idx + $destpos ) % 20] = $tempbuf2[$idx];
        }

        for($idx = 0; $idx < 20; $idx++)
        {
            if( $idx > 0 )
            {
                $tempbuf2[$idx] = chr( ( ord( $tempbuf[$idx] ) >> $destoffs ) | ( ord( $tempbuf[($idx-1)%20] ) << (8-$destoffs) ) );
            }
            else
            {
                $tempbuf2[$idx] = chr( ( ord($tempbuf[$idx] ) >> $destoffs ) | ( ord( $tempbuf[19] ) << (8-$destoffs) ) );
            }
        }

        for($idx = 0; $idx < 20; $idx++) {$tempbuf[$idx] = $tempbuf2[19-$idx];}
    }

	$passcode = substr($tempbuf,0,1).$passcode[1].substr($tempbuf,1,19);

	return $passcode;
}

// DECODER: YES
// ENCODER: YES
function mMpswd_bit_reverse( $passcode )
{
    for($idx = 0; $idx < 21; $idx++)
    {
        if($idx != 1)
            $passcode[$idx] = chr(ord($passcode[$idx]) ^ 0xff);
    }
	return $passcode;
}

// DECODER: YES
// ENCODER: YES
function mMpswd_bit_arrange_reverse( $passcode )
{
    $tempbuf = "";
	$tempbuf2 = " ";

	for( $idx = 0; $idx < 21 ; $idx++)
	{
		$tempbuf[$idx]= chr(0);
		$tempbuf2[$idx]= chr(0);
	}

    $srcbyte="";
	$destbyte="";

	$tempbuf = str_pad(substr($passcode,0,1).substr($passcode,2,19),21,chr(0));

    for($idx1 = 0; $idx1 <= 19; $idx1++)
    {
        $srcbyte = ord($tempbuf[19-$idx1]);
        $destbyte = 
			( ( $srcbyte & 0x80 ) >> 7 ) |
			( ( $srcbyte & 0x40 ) >> 5 ) |
			( ( $srcbyte & 0x20 ) >> 3 ) |
			( ( $srcbyte & 0x10 ) >> 1 ) |
			( ( $srcbyte & 0x08 ) << 1 ) |
			( ( $srcbyte & 0x04 ) << 3 ) |
			( ( $srcbyte & 0x02 ) << 5 ) |
			( ( $srcbyte & 0x01 ) << 7 );
        $tempbuf2[$idx1] = chr($destbyte);
    }

	$passcode = substr($tempbuf2,0,1).$passcode[1].substr($tempbuf2,1,19);

	return $passcode;
}

function mMpswd_decode_bit_code( $passcode )
{
	$codemethod = ord($passcode[1]) & 0x0000000f;
	if( $codemethod > 12 )
	{
		$passcode = mMpswd_bit_shift( $passcode, ( 0 - $codemethod ) * 3 );
		$passcode = mMpswd_bit_reverse( $passcode );
		$passcode = mMpswd_bit_arrange_reverse( $passcode );
	}
	else if( $codemethod > 8 )
	{
		$passcode = mMpswd_bit_shift( $passcode, $codemethod * 5 );
		$passcode = mMpswd_bit_arrange_reverse( $passcode );
	}
	else if( $codemethod > 4 )
	{
		$passcode = mMpswd_bit_reverse( $passcode );
		$passcode = mMpswd_bit_shift( $passcode, $codemethod * 5 );
	}
	else
	{
		$passcode = mMpswd_bit_arrange_reverse( $passcode );
		$passcode = mMpswd_bit_shift( $passcode, ( 0 - $codemethod ) * 3 );
	}

	return $passcode;
}

function mMpswd_get_RSA_key_code( $passcode )
{
	global $mMpswd_prime_number, $mMpswd_select_idx_table;

    $bit10 = 0;
	$bit32 = 0;
	$bytetable = 0;

    $bit10 = ord($passcode[15]) % 4;
    $bit32 = intval(( ord($passcode[15]) & 0x0000000f ) / 4);

    if( $bit10 == 3 )
    {
        $bit10 = ( $bit10 ^ $bit32 ) & 0x00000003;
        if( $bit10 == 3 ) $bit10 = 0;
    }

    if( $bit32 == 3 )
    {
        $bit32 = ($bit10 + 1) & 0x00000003;
        if( $bit32 == 3 ) $bit32 = 1;
    }

    if( $bit10 == $bit32 )
    {
        $bit32 = ($bit10 + 1) & 0x00000003;
        if( $bit32 == 3 ) $bit32 = 1;
    }

    $bytetable = ( ( ord($passcode[15]) >> 2 ) & 0x0000003c ) >> 2;

    $param1 = $mMpswd_prime_number[$bit10];
    $param2 = $mMpswd_prime_number[$bit32];
    $param3 = $mMpswd_prime_number[ord($passcode[5])];
    $param4 = $mMpswd_select_idx_table[$bytetable];

	return Array($param1,$param2,$param3,$param4);
}

function mMpswd_decode_RSA_cipher( $passcode )
{
	$modcount = 0;

	$tempprime = mMpswd_get_RSA_key_code( $passcode );

	$prime1 = $tempprime[0];
	$prime2 = $tempprime[1];
	$prime3 = $tempprime[2];
	$idxtableptr = $tempprime[3];

	//echo "$prime1 - $prime2 - $prime3!!!<br>";
	//print_r($idxtableptr);
	//echo "<p>";

	$primeproduct = $prime1 * $prime2;

	$lessproduct = ( $prime1 - 1 ) * ( $prime2 - 1 );

	do
	{
		$modcount++;
		$tempval = ( $modcount * $lessproduct + 1 ) % $prime3;
		$tempval2 = ( $modcount * $lessproduct + 1 ) / $prime3;
	} while( $tempval != 0 );

	for( $idx = 0; $idx < 8; $idx++ )
	{
		$newbyte = ord($passcode[ $idxtableptr[$idx] ]);
		//echo "<br> $newbyte - $idx<br>";
		$newbyte |= ( ( ord($passcode[20]) >> $idx ) << 8 ) & 0x00000100;
		$currentbyte = $newbyte;
		for( $idx2 = 0; $idx2 < $tempval2 - 1; $idx2++ )
		{
			$newbyte = ($newbyte * $currentbyte) % $primeproduct;
		//echo " |$newbyte| ";
		}
		$passcode[ $idxtableptr[$idx] ] = chr($newbyte);
		//echo "<br> $newbyte - $idx <br>";
	}

	return $passcode;
}

// DECODER: NO
// ENCODER: YES
function mMpswd_chg_RSA_cipher( $passcode )
{

	$tempprime = mMpswd_get_RSA_key_code( $passcode );

	$prime1 = $tempprime[0];
	$prime2 = $tempprime[1];
	$prime3 = $tempprime[2];
	$idxtableptr = $tempprime[3];

	//echo "$prime1 - $prime2 - $prime3!!!<br>";
	//print_r($idxtableptr);
	//echo "<p>";

    $checkbyte = 0;
    $primeproduct = $prime1 * $prime2;

    for($bytectr = 0; $bytectr < 8; $bytectr++)
    {
        $newbyte = ord($passcode[$idxtableptr[$bytectr]]);
		$currentbyte = $newbyte;
        for($idx = 0; $idx < $prime3-1; $idx++)
        {
            $newbyte = ($newbyte * $currentbyte) % $primeproduct;
        }

        $passcode[$idxtableptr[$bytectr]] = chr($newbyte);
        $newbyte = ($newbyte >> 8) & 0x00000001;
        $checkbyte |= ($newbyte << $bytectr);
    }
    $passcode[20] = chr($checkbyte);

	return $passcode;
}

function mMpswd_chg_RSA_cipher2( $passcode )
{
	$tempprime = mMpswd_get_RSA_key_code( $passcode );

	$prime1 = $tempprime[0];
	$prime2 = $tempprime[1];
	$prime3 = $tempprime[2];
	$idxtableptr = $tempprime[3];

	//echo "$prime1 - $prime2 - $prime3!!!<br>";
	//print_r($idxtableptr);
	//echo "<p>";

    $checkbyte = 0;
    $primeproduct = $prime1 * $prime2;

	$modcount = 0;

	$lessproduct = ( $prime1 - 1 ) * ( $prime2 - 1 );

	do
	{
		$modcount++;
		$tempval = ( $modcount * $lessproduct + 1 ) % $prime3;
		$tempval2 = ( $modcount * $lessproduct + 1 ) / $prime3;
	} while( $tempval != 0 );

    for($bytectr = 0; $bytectr < 8; $bytectr++)
    {
        $newbyte = ord($passcode[$idxtableptr[$bytectr]]);
		//echo "<br> $newbyte - $bytectr<br>";
		$currentbyte = $newbyte;
        for($idx = 0; $idx < $prime3-1; $idx++)
//        for($idx = 0; $idx < $modcount; $idx++)
        {
//			$newbyte = intval(($newbyte + intval(($newbyte * $currentbyte) / $primeproduct) * $primeproduct) / $currentbyte);
            $newbyte = ($newbyte * $currentbyte) % $primeproduct;
//            $newbyte = ($newbyte * $currentbyte) - intval(($newbyte * $currentbyte) / $primeproduct)*$primeproduct;
		//echo " |$newbyte| ";
        }

        $passcode[$idxtableptr[$bytectr]] = chr($newbyte);
		//echo "<br> $newbyte - $bytectr<br>";
        $newbyte = ($newbyte >> 8) & 0x00000001;
        $checkbyte |= ($newbyte << $bytectr);
    }
    $passcode[20] = chr($checkbyte);

	return $passcode;
}

// DECODER: NO
// ENCODER: YES
function mMpswd_substitution_cipher( $passcode )
{
	global $mMpswd_chg_code_table;

    for($idx = 0; $idx < 21; $idx++)
    {
        $passcode[$idx] = chr($mMpswd_chg_code_table[ ord($passcode[$idx]) ]);
    }
	
	return $passcode;
}

// DECODER: YES
// ENCODER: NO
function mMpswd_decode_substitution_cipher( $passcode )
{
	global $mMpswd_chg_code_table;

	for( $idx = 0; $idx < 21; $idx++ )
	{
		for( $idx2 = 0; $idx2 < 256; $idx2++ )
		{
			if( ord($passcode[$idx]) == $mMpswd_chg_code_table[$idx2] )
			{
				$passcode[$idx] = chr($idx2);
				break;
			}
		}
	}

	return $passcode;
}

// DECODER: NO
// ENCODER: YES
function mMpswd_bit_mix_code( $passcode )
{
    $switchbyte = ord($passcode[1]) & 0x0f;

	switch( $switchbyte )
    {
    case 13:
    case 14:
    case 15:
        $passcode = mMpswd_bit_arrange_reverse( $passcode );
        $passcode = mMpswd_bit_reverse( $passcode );
        $passcode = mMpswd_bit_shift( $passcode, $switchbyte * 3 );
        break;
    case 9:
    case 10:
    case 11:
    case 12:
        $passcode = mMpswd_bit_arrange_reverse( $passcode );
        $passcode = mMpswd_bit_shift( $passcode, ( 0 - $switchbyte ) * 5 );
        break;
    case 5:
    case 6:
    case 7:
    case 8:
        $passcode = mMpswd_bit_shift( $passcode, ( 0 - $switchbyte ) * 5 );
        $passcode = mMpswd_bit_reverse( $passcode );
        break;
    case 0:
    case 1:
    case 2:
    case 3:
    case 4:
        $passcode = mMpswd_bit_shift( $passcode, $switchbyte * 3 );
        $passcode = mMpswd_bit_arrange_reverse( $passcode );
        break;
    }
	return $passcode;
}

function mMpswd_chg_custom( $cCode, $codename, $secondary = "" )
{
	$tempcode = explode("\n",$cCode);

	$allowed = Array('&','|','^','<<','>>');
	$returner = "";

	for($idx = 0 ; $idx < sizeof($tempcode) ; $idx++)
	{
		if(substr($tempcode[$idx],0,5) == "byte=" && $secondary != "")
		{
				$tempcode2 = $tempcode[$idx];
				$tempcode2 = str_replace(" ","",$tempcode2);
				$tempval = substr($tempcode2,5);
				$returner.="!\$$secondary=".$tempval.";\n";
		}
		else
		{
			for($idx2 = 0; $idx2 < sizeof($allowed); $idx2++)
			{
				if(substr($tempcode[$idx],0,strlen($allowed[$idx2])+1) == $allowed[$idx2]."=")
				{
					$tempcode2 = $tempcode[$idx];
					$tempcode2 = str_replace(" ","",$tempcode2);
					$tempval = substr($tempcode2,strlen($allowed[$idx2])+1);
					$tempop = substr($tempcode2,0,strlen($allowed[$idx2])+1);
					$returner.="!\$$codename".$tempop."".$tempval.";\n";
					break;
				}
			}
		}
	}

	return $returner;
}

// DECODER: YES
// ENCODER: NO
function mMpswd_password( $passcode )
{

	$playername = substr($passcode,2,8);
	$townname = substr($passcode,10,8);

	$modbyte4 = ( ord($passcode[0]) & 0x18 ) >> 3;
	$modbyte2 = ( ord($passcode[0]) & 0xe0 ) >> 5;
	$itemnum = ( ord($passcode[18]) << 8 ) | ord($passcode[19]);

	switch( $modbyte2 )
	{
	case 0:
	case 4:
	case 5:
	case 6:
	case 7:
		$modbyte0 = 0xFF;
		$modbyte1 = 0xFF;
		$modbyte3 = ( ord($passcode[0]) & 0x06 ) >> 1;
		break;
	case 1:
	case 2:
		$modbyte0 = ord($passcode[0]) & 0x01;
		$modbyte1 = ord($passcode[1]);
		$modbyte3 = ( ord($passcode[0]) & 0x06 ) >> 1;
		break;
	case 3:
		$modbyte0 = 0xFF;
		$modbyte1 = 0xFF;
		$modbyte3 = ( ( ord($passcode[0]) & 0x06 ) >> 1 ) | ( ( ord($passcode[0]) & 0x01 ) << 2 );
		break;
	}

	$data = Array();
	
	for($idx = 0; $idx < strlen($passcode);$idx++)
	{
		$data[$idx] = ord($passcode[$idx]);
	}

	$playername = str_replace(chr(0)," ",$playername);
	$townname = str_replace(chr(0)," ",$townname);

	return Array(
		"itemnum" => $itemnum,
		"modbyte0" => $modbyte0,
		"modbyte1" => $modbyte1,
		"modbyte2" => $modbyte2,
		"modbyte3" => $modbyte3,
		"modbyte4" => $modbyte4,
		"playername" => $playername,
		"townname" => $townname,
		"data" => $data);
}

// DECODER: NO
// ENCODER: YES
function mMpswd_make_passcode( $paramR4, $paramR5,
                           $playername, $townname,
                           $itemnum, $paramR9, $codetype, $leadspace, $custom="" )
{
	$passcode = "                     ";
    $checksum=0;
	$checkbyte=0;
	$idx=0;

    $passcode[0] = chr(( ( $paramR4 & 0x00000007 ) << 5 ) | ( $paramR5 << 1 ) | ( $paramR9 & 0x00000001 ));
    $passcode[1] = chr(255);

    //memcpy( passcode+2, playername, 8 );
    //memcpy( passcode+10, townname, 8 );

	$plen = strlen($playername);
	$tlen = strlen($townname);

	$playername = str_pad($playername,8);
	$townname = str_pad($townname,8);

	$passcode = $passcode[0].$passcode[1].$playername.$townname;

	if($plen > 0 && $plen < 8 && $leadspace) $passcode[2+$plen]=chr(0);
	if($tlen > 0 && $tlen < 8 && $leadspace) $passcode[10+$tlen]=chr(0);

    $passcode[18] = chr(($itemnum >> 8) & 0x000000FF);
    $passcode[19] = chr($itemnum & 0x000000FF);

    $checksum = 0;

    for($idx = 0; $idx < 8; $idx++)
    {
        $checksum += ord($playername[$idx]);
        $checksum += ord($townname[$idx]);
    }

    $checksum += $itemnum;
    $checksum += 0x000000FF;

    $checkbyte = ord($passcode[0]) | ( ($checksum & 0x00000003) << 3 );
	$codebyte = 255;
    switch( $codetype )
    {
    case 'P':
        break;
    case 'N':
        $checkbyte &= 0x0000001f;
        break;
    case 'U':
        $checkbyte &= 0x00000018;
        $checkbyte |= 0x61;
	case 'C':
		$custom2 = mMpswd_chg_custom( $custom, "checkbyte", "codebyte" );
		eval($custom2);
        break;
    }
	$passcode[0] = chr($checkbyte);
	$passcode[1] = chr($codebyte);

	return $passcode;
}

// DECODER: NO
// ENCODER: YES
function mMpswd_make_passcode_from_byte( $paramR4, $paramR5,
                           $playername, $townname,
                           $itemnum, $paramR9, $leadspace, $byte1, $byte2 )
{
	$passcode = "                     ";
    $checksum=0;
	$checkbyte=0;
	$idx=0;

    $passcode[0] = chr(all2int($byte1));
    $passcode[1] = chr(all2int($byte2));

    //memcpy( passcode+2, playername, 8 );
    //memcpy( passcode+10, townname, 8 );

	$plen = strlen($playername);
	$tlen = strlen($townname);

	$playername = str_pad($playername,8);
	$townname = str_pad($townname,8);

	$passcode = $passcode[0].$passcode[1].$playername.$townname;

	if($plen > 0 && $plen < 8) $passcode[2+$plen]=chr(0);
	if($tlen > 0 && $tlen < 8) $passcode[10+$tlen]=chr(0);

    $passcode[18] = chr(($itemnum >> 8) & 0x000000FF);
    $passcode[19] = chr($itemnum & 0x000000FF);

	$checksum = 0;

	return $passcode;
}
function mMpswd_make_passcode_from_byte2( $paramR4, $paramR5,
                           $playername, $townname,
                           $itemnum, $paramR9, $leadspace, $byte1, $byte2, $byte3 )
{
	$passcode = mMpswd_make_passcode_from_byte( $paramR4, $paramR5,
                           $playername, $townname,
                           $itemnum, $paramR9, $leadspace, $byte1, $byte2 );

	$passcode[20] = chr(all2int($byte3));

	return $passcode;
}

function mMpswd_test_password( $paramR4, $paramR5,
                           $playername, $townname,
                           $itemnum, $paramR9, $byte0, $byte1 )
{
	$firstcode = ( ( $paramR4 & 0x00000007 ) << 5 ) | ( $paramR5 << 1 ) | ( $paramR9 & 0x00000001 );

    $checksum = 0;

    for($idx = 0; $idx < 8; $idx++)
    {
        $checksum += ord($playername[$idx]);
        $checksum += ord($townname[$idx]);
    }

    $checksum += $itemnum;
    $checksum += 0x000000FF;

    $checkbyte = $firstcode | ( ($checksum & 0x00000003) << 3 );

	if($byte1 == 255)
	{
		$cases = Array(0,1,2);
	}
	else
	{
		$cases = Array();
	}

	$breaker = false;
	for($idx = 0; $idx < 3; $idx++)
	{
		$iscertain = (in_array($idx,$cases) ? 1 : 0);
		$tester = $checkbyte;
		switch ($idx)
		{
		case 0:
			$codetype = "Player-to-Player (P)";
			break;
		case 1:
			$tester &= 0x0000001f;
			$codetype = "NES Contest (N)";
			break;
		case 2:
			$tester &= 0x00000018;
			$tester |= 0x61;
			$codetype = "Universal (U)";
			break;
		}
		if($tester == $byte0)
		{
			if(!$iscertain)$codetype = "Uncertain - $codetype";
			$breaker = true;
			break;
		}
	}
	if(!$breaker)
	{
		$codetype = "Unknown";
	}

	return $codetype;
}

function mMpswd_checksum_password( $paramR4, $paramR5,
                           $playername, $townname,
                           $itemnum, $paramR9, $byte0, $byte1 )
{
	$firstcode = ( ( $paramR4 & 0x00000007 ) << 5 ) | ( $paramR5 << 1 ) | ( $paramR9 & 0x00000001 );

    $checksum = 0;

    for($idx = 0; $idx < 8; $idx++)
    {
        $checksum += ord($playername[$idx]);
        $checksum += ord($townname[$idx]);
    }

    $checksum += $itemnum;
    $checksum += 0x000000FF;

    $checkbyte = $firstcode | ( ($checksum & 0x00000003) << 3 );
    $checkbyte2 = $firstcode | ( (($checksum-0x000000FF) & 0x00000003) << 3 );

	return Array($checksum,$checkbyte,$checksum-0x000000FF,$firstcode,$checkbyte2);
}

?>