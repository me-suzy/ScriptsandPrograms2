<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.flac.php - part of getID3()                          //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getFLACHeaderFilepointer(&$fd, &$MP3fileInfo) {
	// http://flac.sourceforge.net/format.html
	$MP3fileInfo['fileformat']   = 'flac';
	$MP3fileInfo['bitrate_mode'] = 'vbr';

	rewind($fd);
	$StreamMarker = fread($fd, 4);
	if ($StreamMarker != 'fLaC') {
		$MP3fileInfo['error'] .= "\n".'Invalid stream_marker - expected "fLaC", found "'.$StreamMarker.'"';;
		return FALSE;
	}

	do {

		$METAdataBlockOffset            = ftell($fd);
		$METAdataBlockHeader            = fread($fd, 4);
		$METAdataLastBlockFlag          = (bool) (BigEndian2Int(substr($METAdataBlockHeader, 0, 1)) & 0x80);
		$METAdataBlockType              = BigEndian2Int(substr($METAdataBlockHeader, 0, 1)) & 0x7F;
		$METAdataBlockLength            = BigEndian2Int(substr($METAdataBlockHeader, 1, 3));
                                        
		$METAdataBlockTypeText          = FLACmetaBlockTypeLookup($METAdataBlockType);
		$METAdataBlockData              = fread($fd, $METAdataBlockLength);
		$MP3fileInfo['audiodataoffset'] = ftell($fd);
		$offset = 0;

		$MP3fileInfo['flac']["$METAdataBlockTypeText"]['raw']['offset']          = $METAdataBlockOffset;
		$MP3fileInfo['flac']["$METAdataBlockTypeText"]['raw']['last_meta_block'] = $METAdataLastBlockFlag;
		$MP3fileInfo['flac']["$METAdataBlockTypeText"]['raw']['block_type']      = $METAdataBlockType;
		$MP3fileInfo['flac']["$METAdataBlockTypeText"]['raw']['block_type_text'] = $METAdataBlockTypeText;
		$MP3fileInfo['flac']["$METAdataBlockTypeText"]['raw']['block_length']    = $METAdataBlockLength;
		$MP3fileInfo['flac']["$METAdataBlockTypeText"]['raw']['block_data']      = $METAdataBlockData;

		switch ($METAdataBlockType) {

			case 0: // STREAMINFO
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['min_block_size']  = BigEndian2Int(substr($METAdataBlockData, $offset, 2));
				$offset += 2;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['max_block_size']  = BigEndian2Int(substr($METAdataBlockData, $offset, 2));
				$offset += 2;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['min_frame_size']  = BigEndian2Int(substr($METAdataBlockData, $offset, 3));
				$offset += 3;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['max_frame_size']  = BigEndian2Int(substr($METAdataBlockData, $offset, 3));
				$offset += 3;
				$SampleRateChannelsSampleBitsStreamSamples                        = BigEndian2Bin(substr($METAdataBlockData, $offset, 8));
				$offset += 8;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['sample_rate']     = Bin2Dec(substr($SampleRateChannelsSampleBitsStreamSamples,  0, 20));
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['channels']        = Bin2Dec(substr($SampleRateChannelsSampleBitsStreamSamples, 20,  3)) + 1;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['bits_per_sample'] = Bin2Dec(substr($SampleRateChannelsSampleBitsStreamSamples, 23,  5)) + 1;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['samples_stream']  = Bin2Dec(substr($SampleRateChannelsSampleBitsStreamSamples, 28, 36));
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]['audio_signature'] = substr($METAdataBlockData, $offset, 16);
				$offset += 16;

				$MP3fileInfo['frequency']                     = $MP3fileInfo['flac']["$METAdataBlockTypeText"]['sample_rate'];
				$MP3fileInfo['channels']                      = $MP3fileInfo['flac']["$METAdataBlockTypeText"]['channels'];
				$MP3fileInfo['playtime_seconds']              = $MP3fileInfo['flac']["$METAdataBlockTypeText"]['samples_stream'] / $MP3fileInfo['flac']["$METAdataBlockTypeText"]['sample_rate'];
				$MP3fileInfo['bitrate_audio']                 = ($MP3fileInfo['filesize'] * 8) / $MP3fileInfo['playtime_seconds'];
				break;

			case 1: // PADDING
				// ignore
				break;

			case 2: // APPLICATION
				$ApplicationID                                                           = BigEndian2Int(substr($METAdataBlockData, $offset, 4));
				$offset += 4;
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]["$ApplicationID"]['name'] = FLACapplicationIDLookup($ApplicationID);
				$MP3fileInfo['flac']["$METAdataBlockTypeText"]["$ApplicationID"]['data'] = substr($METAdataBlockData, $offset);
				$offset = strlen($METAdataBlockData);
				break;

			case 3: // SEEKTABLE
				while ($offset < strlen($METAdataBlockData)) {
					if (substr($METAdataBlockData, $offset, 8) == str_repeat(chr(0xFF), 8)) {

						// placeholder point
						$MP3fileInfo['flac']["$METAdataBlockTypeText"]['placeholders'] = (isset($MP3fileInfo['flac']["$METAdataBlockTypeText"]['placeholders']) ? $MP3fileInfo['flac']["$METAdataBlockTypeText"]['placeholders'] + 1 : 1);
						$offset += 18;

					} else {

						$SampleNumber                                                              = BigEndian2Int(substr($METAdataBlockData, $offset, 8));
						$offset += 8;
						$MP3fileInfo['flac']["$METAdataBlockTypeText"]["$SampleNumber"]['offset']  = BigEndian2Int(substr($METAdataBlockData, $offset, 8));
						$offset += 8;
						$MP3fileInfo['flac']["$METAdataBlockTypeText"]["$SampleNumber"]['samples'] = BigEndian2Int(substr($METAdataBlockData, $offset, 2));
						$offset += 2;

					}
				}
				break;

			case 4: // VORBIS_COMMENT
				include_once(GETID3_INCLUDEPATH.'getid3.ogg.php');
				ParseVorbisComments($METAdataBlockData, $MP3fileInfo, $METAdataBlockOffset);
				break;

			default:
				$MP3fileInfo['error'] .= "\n".'Unhandled METADATA_BLOCK_HEADER.BLOCK_TYPE ('.$METAdataBlockType.') at offset '.$METAdataBlockOffset;
				break;
		}

	} while ($METAdataLastBlockFlag === FALSE);


	if (isset($MP3fileInfo['flac']['STREAMINFO'])) {
		$MP3fileInfo['flac']['compressed_audio_bytes']   = $MP3fileInfo['filesize'] - $MP3fileInfo['audiodataoffset'];
		$MP3fileInfo['flac']['uncompressed_audio_bytes'] = $MP3fileInfo['flac']['STREAMINFO']['samples_stream'] * $MP3fileInfo['flac']['STREAMINFO']['channels'] * ($MP3fileInfo['flac']['STREAMINFO']['bits_per_sample'] / 8);
		$MP3fileInfo['flac']['compression_ratio']        = $MP3fileInfo['flac']['compressed_audio_bytes'] / $MP3fileInfo['flac']['uncompressed_audio_bytes'];
	}

	return TRUE;
}

?>