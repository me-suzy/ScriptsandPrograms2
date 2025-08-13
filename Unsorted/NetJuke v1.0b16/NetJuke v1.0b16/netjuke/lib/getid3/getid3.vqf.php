<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.vqf.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getVQFHeaderFilepointer(&$fd, &$MP3fileInfo) {
	// based loosely on code from TTwinVQ by Jurgen Faul
	// jfaul@gmx.de     http://jfaul.de/atl

	$MP3fileInfo['fileformat']      = 'vqf';
	$MP3fileInfo['bitrate_mode']    = 'cbr';
	$MP3fileInfo['audiodataoffset'] = 0; // should be overridden below

	rewind($fd);
	$VQFheaderData = fread($fd, 16);

	$offset = 0;
	$MP3fileInfo['vqf']['raw']['header_tag']     =               substr($VQFheaderData, $offset, 4);
	$offset += 4;
	$MP3fileInfo['vqf']['raw']['version']        =               substr($VQFheaderData, $offset, 8);
	$offset += 8;
	$MP3fileInfo['vqf']['raw']['size']           = BigEndian2Int(substr($VQFheaderData, $offset, 4));
	$offset += 4;

	while (ftell($fd) < $MP3fileInfo['filesize']) {

		$ChunkBaseOffset = ftell($fd);
		$chunkoffset = 0;
		$ChunkData = fread($fd, 8);
		$ChunkName = substr($ChunkData, $chunkoffset, 4);
		if ($ChunkName == 'DATA') {
			$MP3fileInfo['audiodataoffset'] = $ChunkBaseOffset;
			break;
		}
		$chunkoffset += 4;
		$ChunkSize = BigEndian2Int(substr($ChunkData, $chunkoffset, 4));
		$chunkoffset += 4;
		if ($ChunkSize > ($MP3fileInfo['filesize'] - ftell($fd))) {
			$MP3fileInfo['error'] .= "\n".'Invali chunk size '.$ChunkSize.' for chunk "'.$ChunkName.'" at offset '.$ChunkBaseOffset;
			break;
		}
		$ChunkData .= fread($fd, $ChunkSize);

		switch ($ChunkName) {
			case 'COMM':
				$MP3fileInfo['vqf']["$ChunkName"]['channel_mode']   = BigEndian2Int(substr($ChunkData, $chunkoffset, 4));
				$chunkoffset += 4;
				$MP3fileInfo['vqf']["$ChunkName"]['bitrate']        = BigEndian2Int(substr($ChunkData, $chunkoffset, 4));
				$chunkoffset += 4;
				$MP3fileInfo['vqf']["$ChunkName"]['sample_rate']    = BigEndian2Int(substr($ChunkData, $chunkoffset, 4));
				$chunkoffset += 4;
				$MP3fileInfo['vqf']["$ChunkName"]['security_level'] = BigEndian2Int(substr($ChunkData, $chunkoffset, 4));
				$chunkoffset += 4;

				$MP3fileInfo['channels']                            = $MP3fileInfo['vqf']["$ChunkName"]['channel_mode'] + 1;
				$MP3fileInfo['frequency']                           = VQFchannelFrequencyLookup($MP3fileInfo['vqf']["$ChunkName"]['sample_rate']);
				$MP3fileInfo['bitrate_audio']                       = $MP3fileInfo['vqf']["$ChunkName"]['bitrate'] * 1000;
				break;

			case 'NAME':
			case 'AUTH':
			case '(c) ':
			case 'FILE':
			case 'COMT':
				$MP3fileInfo['vqf']["$ChunkName"]                   = substr($ChunkData, 8);
				break;


			default:
				$MP3fileInfo['error'] .= "\n".'Unhandled chunk type "'.$ChunkName.'" at offset '.$ChunkBaseOffset;
				break;
		}
	}

	$MP3fileInfo['playtime_seconds'] = (($MP3fileInfo['filesize'] - $MP3fileInfo['audiodataoffset']) * 8) / $MP3fileInfo['bitrate_audio'];

	$handytranslationkeys = array('NAME'=>'title', 'AUTH'=>'artist', 'COMT'=>'comment');
	foreach ($handytranslationkeys as $vqfkey => $standardkey) {
		if (isset($MP3fileInfo['vqf']["$vqfkey"])) {
			$MP3fileInfo['vqf']["$standardkey"] = $MP3fileInfo['vqf']["$vqfkey"];
		}
	}

	return TRUE;
}

?>