<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.ogg.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function ParseOggPageHeader(&$fd) {
	// http://xiph.org/ogg/vorbis/doc/framing.html
	$oggheader['page_start_offset'] = ftell($fd); // where we started from in the file

	$filedata = fread($fd, FREAD_BUFFER_SIZE);
	$filedataoffset = 0;
	while ((substr($filedata, $filedataoffset++, 4) != 'OggS')) {
		if ((ftell($fd) - $oggheader['page_start_offset']) >= 10000) {
			// should be found before here
			return FALSE;
		}
		if (strlen($filedata) < 1024) {
			if (feof($fd) || (($filedata .= fread($fd, FREAD_BUFFER_SIZE)) === FALSE)) {
				// get some more data, unless eof, in which case fail
				return FALSE;
			}
		}
	}
	$filedataoffset += strlen('OggS') - 1; // page, delimited by 'OggS'

	$oggheader['stream_structver'] = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
	$filedataoffset += 1;
	$oggheader['flags_raw']            = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
	$filedataoffset += 1;
	$oggheader['flags']['fresh']    = (bool) ($oggheader['flags_raw'] & 0x01); // fresh packet
	$oggheader['flags']['bos']      = (bool) ($oggheader['flags_raw'] & 0x02); // first page of logical bitstream (bos)
	$oggheader['flags']['eos']      = (bool) ($oggheader['flags_raw'] & 0x04); // last page of logical bitstream (eos)

	$oggheader['pcm_abs_position']  = LittleEndian2Int(substr($filedata, $filedataoffset, 8));
	$filedataoffset += 8;
	$oggheader['stream_serialno']   = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
	$filedataoffset += 4;
	$oggheader['page_seqno']        = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
	$filedataoffset += 4;
	$oggheader['page_checksum']     = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
	$filedataoffset += 4;
	$oggheader['page_segments']     = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
	$filedataoffset += 1;
	$oggheader['page_length'] = 0;
	for ($i = 0; $i < $oggheader['page_segments']; $i++) {
		$oggheader['segment_table']["$i"] = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
		$filedataoffset += 1;
		$oggheader['page_length'] += $oggheader['segment_table']["$i"];
	}
	$oggheader['header_end_offset'] = $oggheader['page_start_offset'] + $filedataoffset;
	$oggheader['page_end_offset']   = $oggheader['header_end_offset'] + $oggheader['page_length'];
	fseek($fd, $oggheader['header_end_offset'], SEEK_SET);

	return $oggheader;
}

function getOggHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat'] = 'ogg';
	if (!$fd) {
		$MP3fileInfo['error'] = "\n".'Could not open file';
		return FALSE;
	} else {
		// Page 1 - Stream Header

		$MP3fileInfo['bitrate_mode'] = 'abr'; // overridden if actually vbr

		rewind($fd);
		//$MP3fileInfo['ogg']['pageheader'][0] = ParseOggPageHeader($fd);
		$oggpageinfo = ParseOggPageHeader($fd);
		$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']] = $oggpageinfo;

		if (ftell($fd) >= 10000) {
			$MP3fileInfo['error'] = "\n".'Could not find start of Ogg page in the first 10,000 bytes (this might not be an Ogg-Vorbis file?)';
			unset($MP3fileInfo['fileformat']);
			unset($MP3fileInfo['ogg']);
			return FALSE;
		}

		$filedata = fread($fd, 30);
		$filedataoffset = 0;

		$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']]['packet_type'] = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
		$filedataoffset += 1;
		$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']]['stream_type'] = substr($filedata, $filedataoffset, 6); // hard-coded to 'vorbis'
		$filedataoffset += 6;
		$MP3fileInfo['ogg']['bitstreamversion'] = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
		$filedataoffset += 4;
		$MP3fileInfo['ogg']['numberofchannels'] = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
		$filedataoffset += 1;
		$MP3fileInfo['channels']                = $MP3fileInfo['ogg']['numberofchannels'];
		$MP3fileInfo['ogg']['samplerate']       = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
		$filedataoffset += 4;
		$MP3fileInfo['frequency']               = $MP3fileInfo['ogg']['samplerate'];
		$MP3fileInfo['ogg']['samples']          = 0; // filled in later
		$MP3fileInfo['ogg']['bitrate_average']  = 0; // filled in later
		$MP3fileInfo['ogg']['bitrate_max']      = LittleEndian2Int(substr($filedata,  $filedataoffset, 4));
		$filedataoffset += 4;
		$MP3fileInfo['bitrate_mode'] = 'vbr';
		$MP3fileInfo['ogg']['bitrate_nominal']  = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
		$filedataoffset += 4;
		$MP3fileInfo['ogg']['bitrate_min']      = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
		$filedataoffset += 4;
		$MP3fileInfo['bitrate_mode'] = 'vbr';
		$MP3fileInfo['ogg']['blocksize_small']  = pow(2,  LittleEndian2Int(substr($filedata, $filedataoffset, 1)) & 0x0F);
		$MP3fileInfo['ogg']['blocksize_large']  = pow(2, (LittleEndian2Int(substr($filedata, $filedataoffset, 1)) & 0xF0) >> 4);
		$MP3fileInfo['ogg']['stop_bit']         = LittleEndian2Int(substr($filedata, $filedataoffset, 1)); // must be 1, marks end of packet

		if ($MP3fileInfo['ogg']['bitrate_max'] == 0xFFFFFFFF) {
			unset($MP3fileInfo['ogg']['bitrate_max']);
			$MP3fileInfo['bitrate_mode'] = 'abr';
		}
		if ($MP3fileInfo['ogg']['bitrate_nominal'] == 0xFFFFFFFF) {
			unset($MP3fileInfo['ogg']['bitrate_nominal']);
		}
		if ($MP3fileInfo['ogg']['bitrate_min'] == 0xFFFFFFFF) {
			unset($MP3fileInfo['ogg']['bitrate_min']);
			$MP3fileInfo['bitrate_mode'] = 'abr';
		}


		// Page 2 - Comment Header

		$oggpageinfo = ParseOggPageHeader($fd);
		$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']] = $oggpageinfo;
		$filebaseoffset = ftell($fd);
		$filedata = fread($fd, $MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']]['page_length']);
		$filedataoffset = 0;

		$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']]['packet_type'] = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
		$filedataoffset += 1;
		$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']]['stream_type'] = substr($filedata, $filedataoffset, 6); // hard-coded to 'vorbis'
		$filedataoffset += 6;

		ParseVorbisComments(substr($filedata, $filedataoffset), $MP3fileInfo, $filebaseoffset);

		// Last Page - Number of Samples

		fseek($fd, max($MP3fileInfo['filesize'] - FREAD_BUFFER_SIZE, 0), SEEK_SET);
		$LastChunkOfOgg = strrev(fread($fd, FREAD_BUFFER_SIZE));
		if ($LastOggSpostion = strpos($LastChunkOfOgg, 'SggO')) {
			fseek($fd, 0 - ($LastOggSpostion + strlen('SggO')), SEEK_END);
			$MP3fileInfo['ogg']['pageheader']['eos'] = ParseOggPageHeader($fd);
			$MP3fileInfo['ogg']['samples']   = $MP3fileInfo['ogg']['pageheader']['eos']['pcm_abs_position'];
			$MP3fileInfo['ogg']['bitrate_average'] = ($MP3fileInfo['filesize'] * 8) / ($MP3fileInfo['ogg']['samples'] / $MP3fileInfo['ogg']['samplerate']);
		}

		if (isset($MP3fileInfo['ogg']['bitrate_average']) && ($MP3fileInfo['ogg']['bitrate_average'] > 0)) {
			$MP3fileInfo['bitrate_audio'] = $MP3fileInfo['ogg']['bitrate_average'];
		} else if (isset($MP3fileInfo['ogg']['bitrate_nominal']) && ($MP3fileInfo['ogg']['bitrate_nominal'] > 0)) {
			$MP3fileInfo['bitrate_audio'] = $MP3fileInfo['ogg']['bitrate_nominal'];
		} else if (isset($MP3fileInfo['ogg']['bitrate_min']) && isset($MP3fileInfo['ogg']['bitrate_max'])) {
			$MP3fileInfo['bitrate_audio'] = ($MP3fileInfo['ogg']['bitrate_min'] + $MP3fileInfo['ogg']['bitrate_max']) / 2;
		}
		if (isset($MP3fileInfo['bitrate_audio']) && !isset($MP3fileInfo['playtime_seconds'])) {
			$MP3fileInfo['playtime_seconds'] = (float) (($MP3fileInfo['filesize'] * 8) / $MP3fileInfo['bitrate_audio']);
		}

	}
	return TRUE;
}

function ParseVorbisComments($commentdata, &$MP3fileInfo, $filebaseoffset) {
	$commentdataoffset = 0;

	$MP3fileInfo['ogg']['vendor_size'] = LittleEndian2Int(substr($commentdata, $commentdataoffset, 4));
	$commentdataoffset += 4;
	$MP3fileInfo['ogg']['vendor'] = substr($commentdata, $commentdataoffset, $MP3fileInfo['ogg']['vendor_size']);
	$commentdataoffset += $MP3fileInfo['ogg']['vendor_size'];
	$MP3fileInfo['ogg']['comments_count'] = LittleEndian2Int(substr($commentdata, $commentdataoffset, 4));
	$commentdataoffset += 4;
	$basicfields = array('TITLE', 'ARTIST', 'ALBUM', 'TRACKNUMBER', 'GENRE', 'DATE', 'DESCRIPTION', 'COMMENT');
	for ($i = 0; $i < $MP3fileInfo['ogg']['comments_count']; $i++) {
	    $MP3fileInfo['ogg']['comments']["$i"]['size'] = LittleEndian2Int(substr($commentdata, $commentdataoffset, 4));
		$commentdataoffset += 4;
		$MP3fileInfo['ogg']['comments']["$i"]['dataoffset'] = $filebaseoffset + $commentdataoffset;
		while ((strlen($commentdata) - $commentdataoffset) < $MP3fileInfo['ogg']['comments']["$i"]['size']) {
			if (($MP3fileInfo['ogg']['comments']["$i"]['size'] > $MP3fileInfo['filesize']) || ($MP3fileInfo['ogg']['comments']["$i"]['size'] < 0)) {
				$MP3fileInfo['error'] .= "\n".'Invalid Ogg comment size (comment #'.$i.', claims to be '.number_format($MP3fileInfo['ogg']['comments']["$i"]['size']).' bytes) - aborting reading comments';
				break 2;
			}
			fseek($fd, $oggpageinfo['page_end_offset'], SEEK_SET);
			$oggpageinfo = ParseOggPageHeader($fd);
			$MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']] = $oggpageinfo;
			$commentdata .= fread($fd, $MP3fileInfo['ogg']['pageheader'][$oggpageinfo['page_seqno']]['page_length']);

			$filebaseoffset += $oggpageinfo['header_end_offset'] - $oggpageinfo['page_start_offset'];
		}
	    $commentstring = substr($commentdata, $commentdataoffset, $MP3fileInfo['ogg']['comments']["$i"]['size']);
	    $commentdataoffset += $MP3fileInfo['ogg']['comments']["$i"]['size'];
	    if (!$commentstring) {

			// no comment?
			$MP3fileInfo['error'] .= "\n".'Blank Ogg comment ['.$i.']';

		} else if (strstr($commentstring, '=')) {

			$commentexploded = explode('=', $commentstring, 2);
			$MP3fileInfo['ogg']['comments']["$i"]['key']   = strtoupper($commentexploded[0]);
			$MP3fileInfo['ogg']['comments']["$i"]['value'] = ($commentexploded[1] ? utf8_decode($commentexploded[1]) : '');
			if (in_array($MP3fileInfo['ogg']['comments']["$i"]['key'], $basicfields)) {
				$MP3fileInfo['ogg'][strtolower($MP3fileInfo['ogg']['comments']["$i"]['key'])] = $MP3fileInfo['ogg']['comments']["$i"]['value'];
			}
			$MP3fileInfo['ogg']['comments']["$i"]['data'] = base64_decode($MP3fileInfo['ogg']['comments']["$i"]['value']);
			include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
			$imagechunkcheck = GetDataImageSize($MP3fileInfo['ogg']['comments']["$i"]['data']);
			$MP3fileInfo['ogg']['comments']["$i"]['image_mime'] = image_type_to_mime_type($imagechunkcheck[2]);
			if (!$MP3fileInfo['ogg']['comments']["$i"]['image_mime']) {
				unset($MP3fileInfo['ogg']['comments']["$i"]['image_mime']);
			}

		} else {

			$MP3fileInfo['error'] .= "\n".'[known problem with CDex >= v1.40, < v1.50b7] Invalid Ogg comment name/value pair ['.$i.']: '.$commentstring;

		}
	}
	if (isset($MP3fileInfo['ogg']['tracknumber'])) {
		$MP3fileInfo['ogg']['track'] = $MP3fileInfo['ogg']['tracknumber'];
		unset($MP3fileInfo['ogg']['tracknumber']);
	}
	$MP3fileInfo['ogg']['comments_offset_end'] = $commentdataoffset;


	// Replay Gain Adjustment
	// http://privatewww.essex.ac.uk/~djmrob/replaygain/
	if (isset($MP3fileInfo['ogg']['comments']) && is_array($MP3fileInfo['ogg']['comments'])) {
		foreach ($MP3fileInfo['ogg']['comments'] as $index => $keyvaluepair) {
			if (isset($keyvaluepair['key'])) {
				switch ($keyvaluepair['key']) {
					case 'RG_AUDIOPHILE':
					case 'REPLAYGAIN_ALBUM_GAIN':
						$MP3fileInfo['replay_gain']['audiophile']['adjustment'] = (double) $keyvaluepair['value'];
						break;

					case 'RG_RADIO':
					case 'REPLAYGAIN_TRACK_GAIN':
						$MP3fileInfo['replay_gain']['radio']['adjustment'] = (double) $keyvaluepair['value'];
						break;

					case 'REPLAYGAIN_ALBUM_PEAK':
						$MP3fileInfo['replay_gain']['audiophile']['peak'] = (double) $keyvaluepair['value'];
						break;

					case 'RG_PEAK':
					case 'REPLAYGAIN_TRACK_PEAK':
						$MP3fileInfo['replay_gain']['radio']['peak'] = (double) $keyvaluepair['value'];
						break;


					default:
						// do nothing
						break;
				}
			}
		}
	}
}

?>