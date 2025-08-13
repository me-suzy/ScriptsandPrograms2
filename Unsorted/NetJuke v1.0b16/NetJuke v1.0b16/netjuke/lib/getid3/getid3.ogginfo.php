<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.ogginfo.php - part of getID3()                       //
// See getid3.readme.txt for more details                      //
//                                                             //
// getid3.ogginfo.php  is included in getID3() with the        //
// author's permission.                                        //
// Originally written by Chris Bolt <chris-getid3@bolt.cx>     //
//                                                             //
// edited for integration into getID3() by James Heinrich      //
//                                                             //
/////////////////////////////////////////////////////////////////
//                                                             //
// OGG Information function                                    //
//                                                             //
// References:                                                 //
//   http://www.xiph.org/ogg/vorbis/doc/v-comment.html         //
//   http://www.xiph.org/ogg/vorbis/doc/framing.html           //
//   http://www.mathdogs.com/vorbis-illuminated/               //
//     bitstream-appendix.html                                 //
//   ftp://ftp.rocksoft.com/papers/crc_v3.txt                  //
//                                                             //
// Todo:                                                       //
//   http://www.xiph.org/archives/vorbis/200202/0189.html      //
//                                                             //
// Copyright (c) 2001, Chris Bolt. All rights reserved.        //
//                                                             //
/////////////////////////////////////////////////////////////////


function OggCompatibleCRCarray() {
	// Why not use php's crc32() function? It uses a polynomial
	// of 0xEDB88320, and the ogg specification requires a
	// polynomial of 0x04C11DB7.
	// ftp://ftp.rocksoft.com/papers/crc_v3.txt
	static $crc_table = array();
	if (count($crc_table) < 1) {
		$crc_table = array(
			0x00000000, 0x04C11DB7, 0x09823B6E, 0x0D4326D9, 0x130476DC, 0x17C56B6B, 0x1A864DB2, 0x1E475005,
			0x2608EDB8, 0x22C9F00F, 0x2F8AD6D6, 0x2B4BCB61, 0x350C9B64, 0x31CD86D3, 0x3C8EA00A, 0x384FBDBD,
			0x4C11DB70, 0x48D0C6C7, 0x4593E01E, 0x4152FDA9, 0x5F15ADAC, 0x5BD4B01B, 0x569796C2, 0x52568B75,
			0x6A1936C8, 0x6ED82B7F, 0x639B0DA6, 0x675A1011, 0x791D4014, 0x7DDC5DA3, 0x709F7B7A, 0x745E66CD,
			0x9823B6E0, 0x9CE2AB57, 0x91A18D8E, 0x95609039, 0x8B27C03C, 0x8FE6DD8B, 0x82A5FB52, 0x8664E6E5,
			0xBE2B5B58, 0xBAEA46EF, 0xB7A96036, 0xB3687D81, 0xAD2F2D84, 0xA9EE3033, 0xA4AD16EA, 0xA06C0B5D,
			0xD4326D90, 0xD0F37027, 0xDDB056FE, 0xD9714B49, 0xC7361B4C, 0xC3F706FB, 0xCEB42022, 0xCA753D95,
			0xF23A8028, 0xF6FB9D9F, 0xFBB8BB46, 0xFF79A6F1, 0xE13EF6F4, 0xE5FFEB43, 0xE8BCCD9A, 0xEC7DD02D,
			0x34867077, 0x30476DC0, 0x3D044B19, 0x39C556AE, 0x278206AB, 0x23431B1C, 0x2E003DC5, 0x2AC12072,
			0x128E9DCF, 0x164F8078, 0x1B0CA6A1, 0x1FCDBB16, 0x018AEB13, 0x054BF6A4, 0x0808D07D, 0x0CC9CDCA,
			0x7897AB07, 0x7C56B6B0, 0x71159069, 0x75D48DDE, 0x6B93DDDB, 0x6F52C06C, 0x6211E6B5, 0x66D0FB02,
			0x5E9F46BF, 0x5A5E5B08, 0x571D7DD1, 0x53DC6066, 0x4D9B3063, 0x495A2DD4, 0x44190B0D, 0x40D816BA,
			0xACA5C697, 0xA864DB20, 0xA527FDF9, 0xA1E6E04E, 0xBFA1B04B, 0xBB60ADFC, 0xB6238B25, 0xB2E29692,
			0x8AAD2B2F, 0x8E6C3698, 0x832F1041, 0x87EE0DF6, 0x99A95DF3, 0x9D684044, 0x902B669D, 0x94EA7B2A,
			0xE0B41DE7, 0xE4750050, 0xE9362689, 0xEDF73B3E, 0xF3B06B3B, 0xF771768C, 0xFA325055, 0xFEF34DE2,
			0xC6BCF05F, 0xC27DEDE8, 0xCF3ECB31, 0xCBFFD686, 0xD5B88683, 0xD1799B34, 0xDC3ABDED, 0xD8FBA05A,
			0x690CE0EE, 0x6DCDFD59, 0x608EDB80, 0x644FC637, 0x7A089632, 0x7EC98B85, 0x738AAD5C, 0x774BB0EB,
			0x4F040D56, 0x4BC510E1, 0x46863638, 0x42472B8F, 0x5C007B8A, 0x58C1663D, 0x558240E4, 0x51435D53,
			0x251D3B9E, 0x21DC2629, 0x2C9F00F0, 0x285E1D47, 0x36194D42, 0x32D850F5, 0x3F9B762C, 0x3B5A6B9B,
			0x0315D626, 0x07D4CB91, 0x0A97ED48, 0x0E56F0FF, 0x1011A0FA, 0x14D0BD4D, 0x19939B94, 0x1D528623,
			0xF12F560E, 0xF5EE4BB9, 0xF8AD6D60, 0xFC6C70D7, 0xE22B20D2, 0xE6EA3D65, 0xEBA91BBC, 0xEF68060B,
			0xD727BBB6, 0xD3E6A601, 0xDEA580D8, 0xDA649D6F, 0xC423CD6A, 0xC0E2D0DD, 0xCDA1F604, 0xC960EBB3,
			0xBD3E8D7E, 0xB9FF90C9, 0xB4BCB610, 0xB07DABA7, 0xAE3AFBA2, 0xAAFBE615, 0xA7B8C0CC, 0xA379DD7B,
			0x9B3660C6, 0x9FF77D71, 0x92B45BA8, 0x9675461F, 0x8832161A, 0x8CF30BAD, 0x81B02D74, 0x857130C3,
			0x5D8A9099, 0x594B8D2E, 0x5408ABF7, 0x50C9B640, 0x4E8EE645, 0x4A4FFBF2, 0x470CDD2B, 0x43CDC09C,
			0x7B827D21, 0x7F436096, 0x7200464F, 0x76C15BF8, 0x68860BFD, 0x6C47164A, 0x61043093, 0x65C52D24,
			0x119B4BE9, 0x155A565E, 0x18197087, 0x1CD86D30, 0x029F3D35, 0x065E2082, 0x0B1D065B, 0x0FDC1BEC,
			0x3793A651, 0x3352BBE6, 0x3E119D3F, 0x3AD08088, 0x2497D08D, 0x2056CD3A, 0x2D15EBE3, 0x29D4F654,
			0xC5A92679, 0xC1683BCE, 0xCC2B1D17, 0xC8EA00A0, 0xD6AD50A5, 0xD26C4D12, 0xDF2F6BCB, 0xDBEE767C,
			0xE3A1CBC1, 0xE760D676, 0xEA23F0AF, 0xEEE2ED18, 0xF0A5BD1D, 0xF464A0AA, 0xF9278673, 0xFDE69BC4,
			0x89B8FD09, 0x8D79E0BE, 0x803AC667, 0x84FBDBD0, 0x9ABC8BD5, 0x9E7D9662, 0x933EB0BB, 0x97FFAD0C,
			0xAFB010B1, 0xAB710D06, 0xA6322BDF, 0xA2F33668, 0xBCB4666D, 0xB8757BDA, 0xB5365D03, 0xB1F740B4
		);
	}
	return $crc_table;
}

function OggWrite($filename, $comments) {
	$crc_table = OggCompatibleCRCarray();
	$info = array();
	$fp = fopen($filename, 'rb');
	if (!$fp || !flock($fp, LOCK_SH)) {
		return FALSE;
	}
	$outfile = $filename.'.new';
	for ($i = 1; ($i <= 10) && file_exists($outfile); $i++) {
		$outfile = $filename.'.new.'.$i;
	}
	if ($i == 11) {
		return FALSE;
	}
	$of = fopen($outfile, 'wb');
	if (!$of || !flock($of, LOCK_EX)) {
		return FALSE;
	}
	while (TRUE) {
		if (fread($fp, 4) != 'OggS') {
			break;
		}
		$packetstart = ftell($fp) - 4;
		fseek($fp, 10, SEEK_CUR);
		$info['serial'] = LittleEndian2Int(fread($fp, 4));
		fseek($fp, 8, SEEK_CUR);
		$segmentcount = ord(fgetc($fp));
		$segmentstart = ftell($fp) + $segmentcount;
		$segmentpos = $segmentstart;
		$segmentinfo = array();
		while ($segmentcount-- > 0) {
			$segment = ord(fgetc($fp));
			$segmentpos += $segment;
			if ($segment < 255) {
				$segmentinfo["$segmentstart"] = $segmentpos;
				$segmentstart = $segmentpos;
			}
		}
		$packetend = $segmentpos;
		$modifypacket = FALSE;
		foreach ($segmentinfo as $segmentstart => $segmentend) {
			fseek($fp, $segmentstart);
			switch (ord(fgetc($fp))) {
				case 1:
				case 2:
				case 4:
				case 5:
					if (fread($fp, 6) != 'vorbis') {
						return FALSE;
					}
					break;

				case 3: // Comment header packet
					if (fread($fp, 6) != 'vorbis') {
						return FALSE;
					}
					$modifypacket = TRUE;
					$comments['vorbis_vendor'] = fread($fp, LittleEndian2Int(fread($fp, 4)));
					$commentcount = LittleEndian2Int(fread($fp, 4));
					while ($commentcount-- > 0) {
						fseek($fp, LittleEndian2Int(fread($fp, 4)), SEEK_CUR);
					}
					if (ord(fgetc($fp)) != 1) {
						return FALSE;
					}
					break;

				default:
					// We're not in the headers anymore
					break(3);
			}
		}
		fseek($fp, $packetstart);
		if (!$modifypacket) {
			fwrite($of, fread($fp, $packetend - $packetstart));
		} else {
			unset($packet);
			if (is_array($comments) && (count($comments) > 0)) {
				$ch  = chr(3);
				$ch .= 'vorbis';
				$ch .= pack('V', strlen($comments['vorbis_vendor']));
				$ch .= $comments['vorbis_vendor'];
				$commentcount = 0;
				foreach ($comments as $commentname => $commentvalue) {
					if (ereg('^vorbis_', $commentname)) {
						continue;
					}
					if (is_array($commentvalue)) {
						$commentcount += count($commentvalue);
					} else {
						$commentcount++;
					}
				}
				$ch .= pack('V', $commentcount);
				foreach ($comments as $commentname => $commentvalue) {
					$commentname = CleanOggCommentName($commentname);
					if ($commentname == 'VORBIS_VENDOR') {
						continue;
					}
					if (is_array($commentvalue)) {
						foreach ($commentvalue as $curvalue) {
							$curvalue = utf8_encode($curvalue);
							$ch .= pack('V', strlen($commentname) + strlen($curvalue) + strlen('='));
							$ch .= $commentname.'='.$curvalue;
						}
					} else {
						$commentvalue = utf8_encode($commentvalue);
						$ch .= pack('V', strlen($commentname) + strlen($commentvalue) + strlen('='));
						$ch .= $commentname.'='.$commentvalue;
					}
				}
				$ch .= chr(1);
			}
			$packet  = fread($fp, 22);
			$packet .= str_repeat(chr(0), 4);
			$segments = array();
			foreach ($segmentinfo as $segmentstart => $segmentend) {
				fseek($fp, $segmentstart);
				if (ord(fgetc($fp)) == 3) {
					$segments[] = $ch;
				} else {
					fseek($fp, -1, SEEK_CUR);
					$segments[] = fread($fp, $segmentend - $segmentstart);
				}
			}
			$segmenttable = '';
			foreach ($segments as $segment) {
				$segmenttable .= str_repeat(chr(255), floor(strlen($segment) / 255));
				$segmenttable .= chr(strlen($segment) % 255);
			}
			$packet .= chr(strlen($segmenttable)).$segmenttable;
			foreach ($segments as $segment) {
				$packet .= $segment;
			}
			for ($crc = 0, $n = 0; $n < strlen($packet); $n++) {
				$crc = ($crc << 8) ^ $crc_table[(($crc >> 24) & 0xFF) ^ ord($packet["$n"])];
			}
			$packet = substr($packet, 0, 22).pack('V', $crc).substr($packet, 26);
			fwrite($of, $packet);
			fseek($fp, $packetend);
			$modifypacket = FALSE;
			break;
		}
	}
	fseek($fp, $segmentend);
	while (!feof($fp)) {
		fwrite($of, fread($fp, 8192));
	}
	fclose($of);
	fclose($fp);
	if (!unlink($filename)) {
		return FALSE;
	}
	if (!rename($outfile, $filename)) {
		return FALSE;
	}
	return TRUE;
}

function CleanOggCommentName($originalcommentname) {
	// A case-insensitive field name that may consist of ASCII 0x20 through 0x7D, 0x3D ('=') excluded.
	// ASCII 0x41 through 0x5A inclusive (A-Z) is to be considered equivalent to ASCII 0x61 through
	// 0x7A inclusive (a-z).

	// replace invalid chars with a space, return uppercase text
	// Thanks Chris Bolt <chris-getid3@bolt.cx> for improving this function
	return strtoupper(ereg_replace('[^ -<>-}]', ' ', $originalcommentname));

}

?>