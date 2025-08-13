<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.mpc.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getMPCHeaderFilepointer(&$fd, &$MP3fileInfo) {
	// http://www.uni-jena.de/~pfk/mpp/sv8/header.html

	$MP3fileInfo['fileformat']   = 'mpc';
	$MP3fileInfo['bitrate_mode'] = 'vbr';
	$MP3fileInfo['channels']                                = 2; // the format appears to be hardcoded for stereo only

	rewind($fd);
	$MP3fileInfo['mpc']['header']['size']                   = 30;
	$MPCheaderData = fread($fd, $MP3fileInfo['mpc']['header']['size']);
	$offset = 0;

	$MP3fileInfo['mpc']['header']['raw']['preamble']        =                  substr($MPCheaderData, $offset, 3); // should be 'MP+'
	$offset += 3;
	$StreamVersionByte                                      = LittleEndian2Int(substr($MPCheaderData, $offset, 1));
	$offset += 1;
	$MP3fileInfo['mpc']['header']['stream_major_version']   = ($StreamVersionByte & 0x0F);
	$MP3fileInfo['mpc']['header']['stream_minor_version']   = ($StreamVersionByte & 0xF0) >> 4;
	$MP3fileInfo['mpc']['header']['frame_count']            = LittleEndian2Int(substr($MPCheaderData, $offset, 4));
	$offset += 4;

	switch ($MP3fileInfo['mpc']['header']['stream_major_version']) {
		case 7:
			$MP3fileInfo['fileformat'] = 'SV7';
			break;

		default:
			$MP3fileInfo['error'] .= "\n".'Only MPEGplus/Musepack SV7 supported';
			return FALSE;
	}

	$FlagsByte1                                             = LittleEndian2Int(substr($MPCheaderData, $offset, 4));
	$offset += 4;
	$MP3fileInfo['mpc']['header']['intensity_stereo']       = (bool) (($FlagsByte1 & 0x80000000) >> 31);
	$MP3fileInfo['mpc']['header']['mid_side_stereo']        = (bool) (($FlagsByte1 & 0x40000000) >> 30);
	$MP3fileInfo['mpc']['header']['max_subband']            = ($FlagsByte1 & 0x3F000000) >> 24;
	$MP3fileInfo['mpc']['header']['raw']['profile']         = ($FlagsByte1 & 0x00F00000) >> 20;
	$MP3fileInfo['mpc']['header']['begin_loud']             = (bool) (($FlagsByte1 & 0x00080000) >> 19);
	$MP3fileInfo['mpc']['header']['end_loud']               = (bool) (($FlagsByte1 & 0x00040000) >> 18);
	$MP3fileInfo['mpc']['header']['raw']['frequency']       = ($FlagsByte1 & 0x00030000) >> 16;
	$MP3fileInfo['mpc']['header']['max_level']              = ($FlagsByte1 & 0x0000FFFF);

	$MP3fileInfo['mpc']['header']['raw']['title_peak']      = LittleEndian2Int(substr($MPCheaderData, $offset, 2));
	$offset += 2;
	$MP3fileInfo['mpc']['header']['raw']['title_gain']      = LittleEndian2Int(substr($MPCheaderData, $offset, 2), TRUE);
	$offset += 2;

	$MP3fileInfo['mpc']['header']['raw']['album_peak']      = LittleEndian2Int(substr($MPCheaderData, $offset, 2));
	$offset += 2;
	$MP3fileInfo['mpc']['header']['raw']['album_gain']      = LittleEndian2Int(substr($MPCheaderData, $offset, 2), TRUE);
	$offset += 2;

	$FlagsByte2                                             = LittleEndian2Int(substr($MPCheaderData, $offset, 4));
	$offset += 4;
	$MP3fileInfo['mpc']['header']['true_gapless']           = (bool) (($FlagsByte2 & 0x80000000) >> 31);
	$MP3fileInfo['mpc']['header']['last_frame_length']      = ($FlagsByte2 & 0x7FF00000) >> 20;


	$offset += 3;  // unused?
	$MP3fileInfo['mpc']['header']['raw']['encoder_version'] = LittleEndian2Int(substr($MPCheaderData, $offset, 1));
	$offset += 1;

	$MP3fileInfo['mpc']['header']['profile']                = MPCprofileNameLookup($MP3fileInfo['mpc']['header']['raw']['profile']);
	$MP3fileInfo['mpc']['header']['frequency']              = MPCfrequencyLookup($MP3fileInfo['mpc']['header']['raw']['frequency']);
	$MP3fileInfo['frequency']                               = $MP3fileInfo['mpc']['header']['frequency'];
	$MP3fileInfo['mpc']['header']['samples']                = ((($MP3fileInfo['mpc']['header']['frame_count'] - 1) * 1152) + $MP3fileInfo['mpc']['header']['last_frame_length']) * $MP3fileInfo['channels'];
	$MP3fileInfo['playtime_seconds']                        = ($MP3fileInfo['mpc']['header']['samples'] / $MP3fileInfo['channels']) / $MP3fileInfo['frequency'];
	$MP3fileInfo['bitrate_audio']                           = (($MP3fileInfo['filesize'] - $MP3fileInfo['mpc']['header']['size']) * 8) / $MP3fileInfo['playtime_seconds'];
	$MP3fileInfo['mpc']['header']['title_peak']             = $MP3fileInfo['mpc']['header']['raw']['title_peak'];
	$MP3fileInfo['mpc']['header']['title_peak_db']          = MPCpeakDBLookup($MP3fileInfo['mpc']['header']['title_peak']);
	$MP3fileInfo['mpc']['header']['title_gain_db']          = $MP3fileInfo['mpc']['header']['raw']['title_gain'] / 100;
	$MP3fileInfo['mpc']['header']['album_peak']             = $MP3fileInfo['mpc']['header']['raw']['album_peak'];
	$MP3fileInfo['mpc']['header']['album_peak_db']          = MPCpeakDBLookup($MP3fileInfo['mpc']['header']['album_peak']);
	$MP3fileInfo['mpc']['header']['album_gain_db']          = $MP3fileInfo['mpc']['header']['raw']['album_gain'] / 100;;
	$MP3fileInfo['mpc']['header']['encoder_version']        = MPCencoderVersionLookup($MP3fileInfo['mpc']['header']['raw']['encoder_version']);

	if ($MP3fileInfo['mpc']['header']['title_peak_db']) {
		$MP3fileInfo['replay_gain']['radio']['peak']            = $MP3fileInfo['mpc']['header']['title_peak'];
		$MP3fileInfo['replay_gain']['radio']['adjustment']      = $MP3fileInfo['mpc']['header']['title_gain_db'];
	} else {
		$MP3fileInfo['replay_gain']['radio']['peak']            = CastAsInt(round($MP3fileInfo['mpc']['header']['max_level'] * 1.18)); // why? I don't know - see mppdec.c
		$MP3fileInfo['replay_gain']['radio']['adjustment']      = 0;
	}
	if ($MP3fileInfo['mpc']['header']['album_peak_db']) {
		$MP3fileInfo['replay_gain']['audiophile']['peak']       = $MP3fileInfo['mpc']['header']['album_peak'];
		$MP3fileInfo['replay_gain']['audiophile']['adjustment'] = $MP3fileInfo['mpc']['header']['album_gain_db'];
	}

	return TRUE;
}

?>