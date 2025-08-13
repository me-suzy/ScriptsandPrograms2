<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.real.php - part of getID3()                          //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getRealHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat'] = 'real';

	rewind($fd);
	$ChunkCounter = 0;
	while (ftell($fd) < $MP3fileInfo['filesize']) {
		$ChunkData  = fread($fd, 8);
		$ChunkName  = substr($ChunkData, 0, 4);
		$ChunkSize  = BigEndian2Int(substr($ChunkData, 4, 4));

		$MP3fileInfo['real']["$ChunkCounter"]['name']   = $ChunkName;
		$MP3fileInfo['real']["$ChunkCounter"]['offset'] = ftell($fd) - 8;
		$MP3fileInfo['real']["$ChunkCounter"]['length'] = $ChunkSize;
		$ChunkData .= fread($fd, $ChunkSize - 8);
		$offset = 8;

		switch ($ChunkName) {

			case '.RMF': // RealMedia File Header
				$MP3fileInfo['real']["$ChunkCounter"]['object_version']    = BigEndian2Int(substr($ChunkData, $offset, 2));
				$offset += 2;
				if ($MP3fileInfo['real']["$ChunkCounter"]['object_version'] == 0) {
					$MP3fileInfo['real']["$ChunkCounter"]['file_version']  = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['headers_count'] = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
				}
				break;


			case 'PROP': // Properties Header
				$MP3fileInfo['real']["$ChunkCounter"]['object_version']              = BigEndian2Int(substr($ChunkData, $offset, 2));
				$offset += 2;
				if ($MP3fileInfo['real']["$ChunkCounter"]['object_version'] == 0) {
					$MP3fileInfo['real']["$ChunkCounter"]['max_bit_rate']            = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate']            = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['max_packet_size']         = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['avg_packet_size']         = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['num_packets']             = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['duration']                = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['preroll']                 = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['index_offset']            = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['data_offset']             = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['num_streams']             = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['flags_raw']               = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;

					$MP3fileInfo['bitrate']                                          = $MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate'];
					$MP3fileInfo['playtime_seconds']                                 = $MP3fileInfo['real']["$ChunkCounter"]['duration'] / 1000;
					$MP3fileInfo['real']["$ChunkCounter"]['flags']['save_enabled']   = (bool) ($MP3fileInfo['real']["$ChunkCounter"]['flags_raw'] & 0x0001);
					$MP3fileInfo['real']["$ChunkCounter"]['flags']['perfect_play']   = (bool) ($MP3fileInfo['real']["$ChunkCounter"]['flags_raw'] & 0x0002);
					$MP3fileInfo['real']["$ChunkCounter"]['flags']['live_broadcast'] = (bool) ($MP3fileInfo['real']["$ChunkCounter"]['flags_raw'] & 0x0004);
				}
				break;

			case 'MDPR': // Media Properties Header
				$MP3fileInfo['real']["$ChunkCounter"]['object_version']         = BigEndian2Int(substr($ChunkData, $offset, 2));
				$offset += 2;
				if ($MP3fileInfo['real']["$ChunkCounter"]['object_version'] == 0) {
					$MP3fileInfo['real']["$ChunkCounter"]['stream_number']      = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['max_bit_rate']       = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate']       = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['max_packet_size']    = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['avg_packet_size']    = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['start_time']         = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['preroll']            = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['duration']           = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['stream_name_size']   = BigEndian2Int(substr($ChunkData, $offset, 1));
					$offset += 1;
					$MP3fileInfo['real']["$ChunkCounter"]['stream_name']        = substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['stream_name_size']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['stream_name_size'];
					$MP3fileInfo['real']["$ChunkCounter"]['mime_type_size']     = BigEndian2Int(substr($ChunkData, $offset, 1));
					$offset += 1;
					$MP3fileInfo['real']["$ChunkCounter"]['mime_type']          = substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['mime_type_size']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['mime_type_size'];
					$MP3fileInfo['real']["$ChunkCounter"]['type_specific_len']  = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['type_specific_data'] = substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['type_specific_len']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['type_specific_len'];

					if (strstr($MP3fileInfo['real']["$ChunkCounter"]['mime_type'], 'audio')) {
						$MP3fileInfo['bitrate_audio'] = (isset($MP3fileInfo['bitrate_audio']) ? $MP3fileInfo['bitrate_audio'] + $MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate'] : $MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate']);
					} else if (strstr($MP3fileInfo['real']["$ChunkCounter"]['mime_type'], 'video')) {
						$MP3fileInfo['bitrate_video'] = (isset($MP3fileInfo['bitrate_video']) ? $MP3fileInfo['bitrate_video'] + $MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate'] : $MP3fileInfo['real']["$ChunkCounter"]['avg_bit_rate']);
					}
				}
				break;

			case 'CONT': // Content Description Header
				$MP3fileInfo['real']["$ChunkCounter"]['object_version']    = BigEndian2Int(substr($ChunkData, $offset, 2));
				$offset += 2;
				if ($MP3fileInfo['real']["$ChunkCounter"]['object_version'] == 0) {
					$MP3fileInfo['real']["$ChunkCounter"]['title_len']     = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['title']         = (string) substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['title_len']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['title_len'];

					$MP3fileInfo['real']["$ChunkCounter"]['author_len']    = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['author']        = (string) substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['author_len']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['author_len'];

					$MP3fileInfo['real']["$ChunkCounter"]['copyright_len'] = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['copyright']     = (string) substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['copyright_len']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['copyright_len'];

					$MP3fileInfo['real']["$ChunkCounter"]['comment_len']   = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['comment']       = (string) substr($ChunkData, $offset, $MP3fileInfo['real']["$ChunkCounter"]['comment_len']);
					$offset += $MP3fileInfo['real']["$ChunkCounter"]['comment_len'];

					if ($MP3fileInfo['real']["$ChunkCounter"]['author']) {
						$MP3fileInfo['artist']                             = $MP3fileInfo['real']["$ChunkCounter"]['author'];
					}
					if ($MP3fileInfo['real']["$ChunkCounter"]['title']) {
						$MP3fileInfo['title']                              = $MP3fileInfo['real']["$ChunkCounter"]['title'];
					}
					if ($MP3fileInfo['real']["$ChunkCounter"]['comment']) {
						$MP3fileInfo['comment']                            = $MP3fileInfo['real']["$ChunkCounter"]['comment'];
					}
				}
				break;


			case 'DATA': // Data Chunk Header
				// do nothing
				break;

			case 'INDX': // Index Section Header
				$MP3fileInfo['real']["$ChunkCounter"]['object_version']        = BigEndian2Int(substr($ChunkData, $offset, 2));
				$offset += 2;
				if ($MP3fileInfo['real']["$ChunkCounter"]['object_version'] == 0) {
					$MP3fileInfo['real']["$ChunkCounter"]['num_indices']       = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;
					$MP3fileInfo['real']["$ChunkCounter"]['stream_number']     = BigEndian2Int(substr($ChunkData, $offset, 2));
					$offset += 2;
					$MP3fileInfo['real']["$ChunkCounter"]['next_index_header'] = BigEndian2Int(substr($ChunkData, $offset, 4));
					$offset += 4;

					if ($MP3fileInfo['real']["$ChunkCounter"]['next_index_header'] == 0) {
						// last index chunk found, ignore rest of file
						return TRUE;
					} else {
						// non-last index chunk, seek to next index chunk (skipping actual index data)
						fseek($fd, $MP3fileInfo['real']["$ChunkCounter"]['next_index_header'], SEEK_SET);
					}
				}
				break;

			default:
				$MP3fileInfo['error'] .= "\n".'Unhandled RealMedia chunk "'.$ChunkName.'" at offset '.$MP3fileInfo['real']["$ChunkCounter"]['offset'];
				break;
		}
		$ChunkCounter++;
	}

	return TRUE;
}

?>