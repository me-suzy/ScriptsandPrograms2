<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.ape.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getMonkeysAudioHeaderFilepointer(&$fd, &$MP3fileInfo) {
	// based loosely on code from TMonkey by Jurgen Faul
	// jfaul@gmx.de     http://jfaul.de/atl

	$MP3fileInfo['fileformat']   = 'mac';
	$MP3fileInfo['bitrate_mode'] = 'vbr';

	rewind($fd);
	$MACheaderData = fread($fd, 40);

	$MP3fileInfo['monkeys_audio']['raw']['header_tag']           =                  substr($MACheaderData, 0, 4);
	$MP3fileInfo['monkeys_audio']['raw']['nVersion']             = LittleEndian2Int(substr($MACheaderData, 4, 2));
	$MP3fileInfo['monkeys_audio']['raw']['nCompressionLevel']    = LittleEndian2Int(substr($MACheaderData, 6, 2));
	$MP3fileInfo['monkeys_audio']['raw']['nFormatFlags']         = LittleEndian2Int(substr($MACheaderData, 8, 2));
	$MP3fileInfo['monkeys_audio']['raw']['nChannels']            = LittleEndian2Int(substr($MACheaderData, 10, 2));
	$MP3fileInfo['monkeys_audio']['raw']['nSampleRate']          = LittleEndian2Int(substr($MACheaderData, 12, 4));
	$MP3fileInfo['monkeys_audio']['raw']['nWAVHeaderBytes']      = LittleEndian2Int(substr($MACheaderData, 16, 4));
	$MP3fileInfo['monkeys_audio']['raw']['nWAVTerminatingBytes'] = LittleEndian2Int(substr($MACheaderData, 20, 4));
	$MP3fileInfo['monkeys_audio']['raw']['nTotalFrames']         = LittleEndian2Int(substr($MACheaderData, 24, 4));
	$MP3fileInfo['monkeys_audio']['raw']['nFinalFrameSamples']   = LittleEndian2Int(substr($MACheaderData, 28, 4));
	$MP3fileInfo['monkeys_audio']['raw']['nPeakLevel']           = LittleEndian2Int(substr($MACheaderData, 32, 4));
	$MP3fileInfo['monkeys_audio']['raw']['nSeekElements']        = LittleEndian2Int(substr($MACheaderData, 38, 2));

	$MP3fileInfo['monkeys_audio']['flags']['8-bit']         = (bool) ($MP3fileInfo['monkeys_audio']['raw']['nFormatFlags'] & 0x0001);
	$MP3fileInfo['monkeys_audio']['flags']['crc-32']        = (bool) ($MP3fileInfo['monkeys_audio']['raw']['nFormatFlags'] & 0x0002);
	$MP3fileInfo['monkeys_audio']['flags']['peak_level']    = (bool) ($MP3fileInfo['monkeys_audio']['raw']['nFormatFlags'] & 0x0004);
	$MP3fileInfo['monkeys_audio']['flags']['24-bit']        = (bool) ($MP3fileInfo['monkeys_audio']['raw']['nFormatFlags'] & 0x0008);
	$MP3fileInfo['monkeys_audio']['flags']['seek_elements'] = (bool) ($MP3fileInfo['monkeys_audio']['raw']['nFormatFlags'] & 0x0010);
	$MP3fileInfo['monkeys_audio']['flags']['no_wav_header'] = (bool) ($MP3fileInfo['monkeys_audio']['raw']['nFormatFlags'] & 0x0020);
	$MP3fileInfo['monkeys_audio']['version']                = $MP3fileInfo['monkeys_audio']['raw']['nVersion'] / 1000;
	$MP3fileInfo['monkeys_audio']['compression']            = MonkeyCompressionLevelNameLookup($MP3fileInfo['monkeys_audio']['raw']['nCompressionLevel']);
	$MP3fileInfo['monkeys_audio']['samples_per_frame']      = MonkeySamplesPerFrame($MP3fileInfo['monkeys_audio']['raw']['nVersion'], $MP3fileInfo['monkeys_audio']['raw']['nCompressionLevel']);
	$MP3fileInfo['monkeys_audio']['bits_per_sample']        = ($MP3fileInfo['monkeys_audio']['flags']['24-bit'] ? 24 : ($MP3fileInfo['monkeys_audio']['flags']['8-bit'] ? 8 : 16));
	$MP3fileInfo['monkeys_audio']['channels']               = $MP3fileInfo['monkeys_audio']['raw']['nChannels'];
	$MP3fileInfo['channels']                                = $MP3fileInfo['monkeys_audio']['channels'];
	$MP3fileInfo['monkeys_audio']['frequency']              = $MP3fileInfo['monkeys_audio']['raw']['nSampleRate'];
	$MP3fileInfo['frequency']                               = $MP3fileInfo['monkeys_audio']['frequency'];
	$MP3fileInfo['monkeys_audio']['peak_level']             = $MP3fileInfo['monkeys_audio']['raw']['nPeakLevel'];
	$MP3fileInfo['monkeys_audio']['peak_ratio']             = $MP3fileInfo['monkeys_audio']['peak_level'] / pow(2, $MP3fileInfo['monkeys_audio']['bits_per_sample'] - 1);
	$MP3fileInfo['monkeys_audio']['frames']                 = $MP3fileInfo['monkeys_audio']['raw']['nTotalFrames'];
	$MP3fileInfo['monkeys_audio']['samples']                = (($MP3fileInfo['monkeys_audio']['frames'] - 1) * $MP3fileInfo['monkeys_audio']['samples_per_frame']) + $MP3fileInfo['monkeys_audio']['raw']['nFinalFrameSamples'];
	$MP3fileInfo['monkeys_audio']['playtime']               = $MP3fileInfo['monkeys_audio']['samples'] / $MP3fileInfo['monkeys_audio']['frequency'];
	$MP3fileInfo['playtime_seconds']                        = $MP3fileInfo['monkeys_audio']['playtime'];
	$MP3fileInfo['monkeys_audio']['compressed_size']        = $MP3fileInfo['filesize'];
	$MP3fileInfo['monkeys_audio']['uncompressed_size']      = $MP3fileInfo['monkeys_audio']['samples'] * $MP3fileInfo['monkeys_audio']['channels'] * ($MP3fileInfo['monkeys_audio']['bits_per_sample'] / 8);
	$MP3fileInfo['monkeys_audio']['compression_ratio']      = $MP3fileInfo['monkeys_audio']['compressed_size'] / ($MP3fileInfo['monkeys_audio']['uncompressed_size'] + $MP3fileInfo['monkeys_audio']['raw']['nWAVHeaderBytes']);
	$MP3fileInfo['monkeys_audio']['bitrate']                = (($MP3fileInfo['monkeys_audio']['samples'] * $MP3fileInfo['monkeys_audio']['channels'] * $MP3fileInfo['monkeys_audio']['bits_per_sample']) / $MP3fileInfo['monkeys_audio']['playtime']) * $MP3fileInfo['monkeys_audio']['compression_ratio'];
	$MP3fileInfo['bitrate_audio']                           = $MP3fileInfo['monkeys_audio']['bitrate'];

	return TRUE;
}

function getAPEtagFilepointer(&$fd, &$MP3fileInfo) {
	$id3v1tagsize     = 128;
	$apetagheadersize = 32;
	fseek($fd, 0 - $id3v1tagsize - $apetagheadersize, SEEK_END);
	$APEfooterID3v1 = fread($fd, $id3v1tagsize + $apetagheadersize);
	if (substr($APEfooterID3v1, 0, strlen('APETAGEX')) == 'APETAGEX') {

		// APE tag found before ID3v1
		$APEfooterData = substr($APEfooterID3v1, 0, $apetagheadersize);
		$APEfooterOffset = 0 - $apetagheadersize - $id3v1tagsize;

	} else if (substr($APEfooterID3v1, $id3v1tagsize, strlen('APETAGEX')) == 'APETAGEX') {

		// APE tag found, no ID3v1
		$APEfooterData = substr($APEfooterID3v1, $id3v1tagsize, $apetagheadersize);
		$APEfooterOffset = 0 - $apetagheadersize;

	} else {

		// APE tag not found
		return FALSE;

	}

	$MP3fileInfo['fileformat']    = 'ape';
	$MP3fileInfo['ape']['footer'] = parseAPEheaderFooter($APEfooterData);

	if (isset($MP3fileInfo['ape']['footer']['flags']['header']) && $MP3fileInfo['ape']['footer']['flags']['header']) {
		fseek($fd, $APEfooterOffset - $MP3fileInfo['ape']['footer']['raw']['tagsize'] + $apetagheadersize - $apetagheadersize, SEEK_END);
		$APEtagData = fread($fd, $MP3fileInfo['ape']['footer']['raw']['tagsize'] + $apetagheadersize);
	} else {
		fseek($fd, $APEfooterOffset - $MP3fileInfo['ape']['footer']['raw']['tagsize'] + $apetagheadersize, SEEK_END);
		$APEtagData = fread($fd, $MP3fileInfo['ape']['footer']['raw']['tagsize']);
	}
	$offset = 0;
	if (isset($MP3fileInfo['ape']['footer']['flags']['header']) && $MP3fileInfo['ape']['footer']['flags']['header']) {
		$MP3fileInfo['ape']['header'] = parseAPEheaderFooter(substr($APEtagData, 0, $apetagheadersize));
		$offset += $apetagheadersize;
	}

	$handykeys = array('title', 'artist', 'album', 'track', 'genre', 'comment', 'year');
	for ($i = 0; $i < $MP3fileInfo['ape']['footer']['raw']['tag_items']; $i++) {
		$value_size    = LittleEndian2Int(substr($APEtagData, $offset, 4));
		$offset       += 4;
		$item_flags    = LittleEndian2Int(substr($APEtagData, $offset, 4));
		$offset       += 4;
		$ItemKeyLength = strpos($APEtagData, chr(0), $offset) - $offset;
		$item_key      = substr($APEtagData, $offset, $ItemKeyLength);
		$offset       += $ItemKeyLength + 1; // skip 0x00 terminator
		$data          = substr($APEtagData, $offset, $value_size);
		$offset       += $value_size;

		$MP3fileInfo['ape']['items']["$item_key"]['raw']['value_size'] = $value_size;
		$MP3fileInfo['ape']['items']["$item_key"]['raw']['item_flags'] = $item_flags;
		if ($MP3fileInfo['ape']['footer']['tag_version'] >= 2) {
			$MP3fileInfo['ape']['items']["$item_key"]['flags']         = parseAPEtagFlags($item_flags);
		}
		$MP3fileInfo['ape']['items']["$item_key"]['data']              = $data;
		if (APEtagItemIsUTF8Lookup($item_key)) {
			$MP3fileInfo['ape']['items']["$item_key"]['data_ascii'] = RoughTranslateUnicodeToASCII($MP3fileInfo['ape']['items']["$item_key"]['data'], 3);
		}
		if (in_array(strtolower($item_key), $handykeys)) {
			$MP3fileInfo['ape'][strtolower($item_key)] = $MP3fileInfo['ape']['items']["$item_key"]['data_ascii'];
		}
	}

	return TRUE;
}

function parseAPEheaderFooter($APEheaderFooterData) {
	// http://www.uni-jena.de/~pfk/mpp/sv8/apeheader.html
	$headerfooterinfo['raw']['footer_tag']   =                  substr($APEheaderFooterData,  0, 8);
	$headerfooterinfo['raw']['version']      = LittleEndian2Int(substr($APEheaderFooterData,  8, 4));
	$headerfooterinfo['raw']['tagsize']      = LittleEndian2Int(substr($APEheaderFooterData, 12, 4));
	$headerfooterinfo['raw']['tag_items']    = LittleEndian2Int(substr($APEheaderFooterData, 16, 4));
	$headerfooterinfo['raw']['global_flags'] = LittleEndian2Int(substr($APEheaderFooterData, 20, 4));
	$headerfooterinfo['raw']['reserved']     =                  substr($APEheaderFooterData, 24, 8);

	$headerfooterinfo['tag_version']         = $headerfooterinfo['raw']['version'] / 1000;
	if ($headerfooterinfo['tag_version'] >= 2) {
		$headerfooterinfo['flags'] = parseAPEtagFlags($headerfooterinfo['raw']['global_flags']);
	}
	return $headerfooterinfo;
}

function parseAPEtagFlags($rawflagint) {
	// "Note: APE Tags 1.0 do not use any of the APE Tag flags.
	// All are set to zero on creation and ignored on reading."
	// http://www.uni-jena.de/~pfk/mpp/sv8/apetagflags.html
	$flags['header']            = (bool) ($rawflagint & 0x80000000);
	$flags['footer']            = (bool) ($rawflagint & 0x40000000);
	$flags['this_is_header']    = (bool) ($rawflagint & 0x20000000);
	$flags['item_contents_raw'] = ($rawflagint & 0x00000006) >> 1;
	$flags['item_contents']     = APEcontentTypeFlagLookup($flags['item_contents_raw']);
	$flags['read_only']         = (bool) ($rawflagint & 0x00000001);

	return $flags;
}

?>