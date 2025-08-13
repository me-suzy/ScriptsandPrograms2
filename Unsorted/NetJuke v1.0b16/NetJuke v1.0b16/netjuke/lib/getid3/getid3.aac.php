<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.aac.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getAACADIFheaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat']      = 'aac';
	$MP3fileInfo['audiodataoffset'] = 0; // should be overridden below

	rewind($fd);
	$AACheader = fread($fd, 1024);
	$offset    = 0;

	if (substr($AACheader, 0, 4) == 'ADIF') {

		// http://faac.sourceforge.net/wiki/index.php?page=ADIF

		// http://libmpeg.org/mpeg4/doc/w2203tfs.pdf
		// adif_header() {
		//     adif_id                                32
		//     copyright_id_present                    1
		//     if( copyright_id_present )
		//         copyright_id                       72
		//     original_copy                           1
		//     home                                    1
		//     bitstream_type                          1
		//     bitrate                                23
		//     num_program_config_elements             4
		//     for (i = 0; i < num_program_config_elements + 1; i++ ) {
		//         if( bitstream_type == '0' )
		//             adif_buffer_fullness           20
		//         program_config_element()
		//     }
		// }

		$AACheaderBitstream = BigEndian2Bin($AACheader);
		$bitoffset          = 0;

		$MP3fileInfo['aac']['header_type']                   = 'ADIF';
		$bitoffset += 32;
		$MP3fileInfo['aac']['header']['mpeg_version']        = 4;

		$MP3fileInfo['aac']['header']['copyright']           = (bool) (substr($AACheaderBitstream, $bitoffset, 1) == '1');
		$bitoffset += 1;
		if ($MP3fileInfo['aac']['header']['copyright']) {
			$MP3fileInfo['aac']['header']['copyright_id']    = Bin2String(substr($AACheaderBitstream, $bitoffset, 72));
			$bitoffset += 72;
		}
		$MP3fileInfo['aac']['header']['original_copy']       = (bool) (substr($AACheaderBitstream, $bitoffset, 1) == '1');
		$bitoffset += 1;
		$MP3fileInfo['aac']['header']['home']                = (bool) (substr($AACheaderBitstream, $bitoffset, 1) == '1');
		$bitoffset += 1;
		$MP3fileInfo['aac']['header']['is_vbr']              = (bool) (substr($AACheaderBitstream, $bitoffset, 1) == '1');
		$bitoffset += 1;
		if ($MP3fileInfo['aac']['header']['is_vbr']) {
			$MP3fileInfo['bitrate_mode']                     = 'vbr';
			$MP3fileInfo['aac']['header']['bitrate_max']     = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 23));
			$bitoffset += 23;
		} else {
			$MP3fileInfo['bitrate_mode']                     = 'cbr';
			$MP3fileInfo['aac']['header']['bitrate']         = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 23));
			$bitoffset += 23;
			$MP3fileInfo['bitrate_audio']                    = $MP3fileInfo['aac']['header']['bitrate'];
		}
		$MP3fileInfo['aac']['header']['num_program_configs'] = 1 + Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
		$bitoffset += 4;

		for ($i = 0; $i < $MP3fileInfo['aac']['header']['num_program_configs']; $i++) {
			// http://www.audiocoding.com/wiki/index.php?page=program_config_element

			// buffer_fullness                       20

			// element_instance_tag                   4
			// object_type                            2
			// sampling_frequency_index               4
			// num_front_channel_elements             4
			// num_side_channel_elements              4
			// num_back_channel_elements              4
			// num_lfe_channel_elements               2
			// num_assoc_data_elements                3
			// num_valid_cc_elements                  4
			// mono_mixdown_present                   1
			// mono_mixdown_element_number            4   if mono_mixdown_present == 1
			// stereo_mixdown_present                 1
			// stereo_mixdown_element_number          4   if stereo_mixdown_present == 1
			// matrix_mixdown_idx_present             1
			// matrix_mixdown_idx                     2   if matrix_mixdown_idx_present == 1
			// pseudo_surround_enable                 1   if matrix_mixdown_idx_present == 1
			// for (i = 0; i < num_front_channel_elements; i++) {
			//     front_element_is_cpe[i]            1
			//     front_element_tag_select[i]        4
			// }
			// for (i = 0; i < num_side_channel_elements; i++) {
			//     side_element_is_cpe[i]             1
			//     side_element_tag_select[i]         4
			// }
			// for (i = 0; i < num_back_channel_elements; i++) {
			//     back_element_is_cpe[i]             1
			//     back_element_tag_select[i]         4
			// }
			// for (i = 0; i < num_lfe_channel_elements; i++) {
			//     lfe_element_tag_select[i]          4
			// }
			// for (i = 0; i < num_assoc_data_elements; i++) {
			//     assoc_data_element_tag_select[i]   4
			// }
			// for (i = 0; i < num_valid_cc_elements; i++) {
			//     cc_element_is_ind_sw[i]            1
			//     valid_cc_element_tag_select[i]     4
			// }
			// byte_alignment()                       VAR
			// comment_field_bytes                    8
			// for (i = 0; i < comment_field_bytes; i++) {
			//     comment_field_data[i]              8
			// }

			if (!$MP3fileInfo['aac']['header']['is_vbr']) {
				$MP3fileInfo['aac']['program_configs']["$i"]['buffer_fullness']        = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 20));
				$bitoffset += 20;
			}
			$MP3fileInfo['aac']['program_configs']["$i"]['element_instance_tag']       = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
			$bitoffset += 4;
			$MP3fileInfo['aac']['program_configs']["$i"]['object_type']                = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
			$bitoffset += 2;
			$MP3fileInfo['aac']['program_configs']["$i"]['sampling_frequency_index']   = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
			$bitoffset += 4;
			$MP3fileInfo['aac']['program_configs']["$i"]['num_front_channel_elements'] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
			$bitoffset += 4;
			$MP3fileInfo['aac']['program_configs']["$i"]['num_side_channel_elements']  = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
			$bitoffset += 4;
			$MP3fileInfo['aac']['program_configs']["$i"]['num_back_channel_elements']  = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
			$bitoffset += 4;
			$MP3fileInfo['aac']['program_configs']["$i"]['num_lfe_channel_elements']   = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
			$bitoffset += 2;
			$MP3fileInfo['aac']['program_configs']["$i"]['num_assoc_data_elements']    = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 3));
			$bitoffset += 3;
			$MP3fileInfo['aac']['program_configs']["$i"]['num_valid_cc_elements']      = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
			$bitoffset += 4;
			$MP3fileInfo['aac']['program_configs']["$i"]['mono_mixdown_present']       = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
			$bitoffset += 1;
			if ($MP3fileInfo['aac']['program_configs']["$i"]['mono_mixdown_present']) {
				$MP3fileInfo['aac']['program_configs']["$i"]['mono_mixdown_element_number']    = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			$MP3fileInfo['aac']['program_configs']["$i"]['stereo_mixdown_present']             = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
			$bitoffset += 1;
			if ($MP3fileInfo['aac']['program_configs']["$i"]['stereo_mixdown_present']) {
				$MP3fileInfo['aac']['program_configs']["$i"]['stereo_mixdown_element_number']  = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			$MP3fileInfo['aac']['program_configs']["$i"]['matrix_mixdown_idx_present']         = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
			$bitoffset += 1;
			if ($MP3fileInfo['aac']['program_configs']["$i"]['matrix_mixdown_idx_present']) {
				$MP3fileInfo['aac']['program_configs']["$i"]['matrix_mixdown_idx']             = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
				$bitoffset += 2;
				$MP3fileInfo['aac']['program_configs']["$i"]['pseudo_surround_enable']         = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
				$bitoffset += 1;
			}
			for ($j = 0; $j < $MP3fileInfo['aac']['program_configs']["$i"]['num_front_channel_elements']; $j++) {
				$MP3fileInfo['aac']['program_configs']["$i"]['front_element_is_cpe']["$j"]     = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
				$bitoffset += 1;
				$MP3fileInfo['aac']['program_configs']["$i"]['front_element_tag_select']["$j"] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			for ($j = 0; $j < $MP3fileInfo['aac']['program_configs']["$i"]['num_side_channel_elements']; $j++) {
				$MP3fileInfo['aac']['program_configs']["$i"]['side_element_is_cpe']["$j"]     = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
				$bitoffset += 1;
				$MP3fileInfo['aac']['program_configs']["$i"]['side_element_tag_select']["$j"] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			for ($j = 0; $j < $MP3fileInfo['aac']['program_configs']["$i"]['num_back_channel_elements']; $j++) {
				$MP3fileInfo['aac']['program_configs']["$i"]['back_element_is_cpe']["$j"]     = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
				$bitoffset += 1;
				$MP3fileInfo['aac']['program_configs']["$i"]['back_element_tag_select']["$j"] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			for ($j = 0; $j < $MP3fileInfo['aac']['program_configs']["$i"]['num_lfe_channel_elements']; $j++) {
				$MP3fileInfo['aac']['program_configs']["$i"]['lfe_element_tag_select']["$j"] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			for ($j = 0; $j < $MP3fileInfo['aac']['program_configs']["$i"]['num_assoc_data_elements']; $j++) {
				$MP3fileInfo['aac']['program_configs']["$i"]['assoc_data_element_tag_select']["$j"] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}
			for ($j = 0; $j < $MP3fileInfo['aac']['program_configs']["$i"]['num_valid_cc_elements']; $j++) {
				$MP3fileInfo['aac']['program_configs']["$i"]['cc_element_is_ind_sw']["$j"]          = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
				$bitoffset += 1;
				$MP3fileInfo['aac']['program_configs']["$i"]['valid_cc_element_tag_select']["$j"]   = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
				$bitoffset += 4;
			}

			$bitoffset = ceil($bitoffset / 8) * 8;

			$MP3fileInfo['aac']['program_configs']["$i"]['comment_field_bytes'] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 8));
			$bitoffset += 8;
			$MP3fileInfo['aac']['program_configs']["$i"]['comment_field']       = Bin2String(substr($AACheaderBitstream, $bitoffset, 8 * $MP3fileInfo['aac']['program_configs']["$i"]['comment_field_bytes']));
			$bitoffset += 8 * $MP3fileInfo['aac']['program_configs']["$i"]['comment_field_bytes'];


			$MP3fileInfo['aac']['header']['profile_text']                      = AACprofileLookup($MP3fileInfo['aac']['program_configs']["$i"]['object_type'], $MP3fileInfo['aac']['header']['mpeg_version']);
			$MP3fileInfo['aac']['program_configs']["$i"]['sampling_frequency'] = AACsampleRateLookup($MP3fileInfo['aac']['program_configs']["$i"]['sampling_frequency_index']);
			$MP3fileInfo['frequency']                                          = $MP3fileInfo['aac']['program_configs']["$i"]['sampling_frequency'];
			$MP3fileInfo['channels']                                           = $MP3fileInfo['aac']['program_configs']["$i"]['num_front_channel_elements'] + $MP3fileInfo['aac']['program_configs']["$i"]['num_side_channel_elements'] + $MP3fileInfo['aac']['program_configs']["$i"]['num_back_channel_elements'] + $MP3fileInfo['aac']['program_configs']["$i"]['num_lfe_channel_elements'];
			if ($MP3fileInfo['aac']['program_configs']["$i"]['comment_field']) {
				$MP3fileInfo['comment']                                        = (isset($MP3fileInfo['comment']) ? $MP3fileInfo['comment'].$MP3fileInfo['aac']['program_configs']["$i"]['comment_field'] : $MP3fileInfo['aac']['program_configs']["$i"]['comment_field']);
			}
		}
		$MP3fileInfo['audiodataoffset']  = CastAsInt(ceil($bitoffset / 8))	;
		$MP3fileInfo['playtime_seconds'] = (($MP3fileInfo['filesize'] - $MP3fileInfo['audiodataoffset']) * 8) / $MP3fileInfo['bitrate_audio'];

		return TRUE;

	} else {

		unset($MP3fileInfo['fileformat']);
		unset($MP3fileInfo['audiodataoffset']);
		unset($MP3fileInfo['aac']);
		$MP3fileInfo['error'] .= "\n".'AAC-ADIF synch not found (expected "ADIF", found "'.substr($AACheader, 0, 4).'" instead)';
		return FALSE;

	}

}


function getAACADTSheaderFilepointer(&$fd, &$MP3fileInfo, $MaxFramesToScan=100) {
	// based loosely on code from AACfile by Jurgen Faul
	// jfaul@gmx.de     http://jfaul.de/atl


	// http://faac.sourceforge.net/wiki/index.php?page=ADTS
	
	// * ADTS Fixed Header: these don't change from frame to frame
	// syncword                                       12    always: '111111111111' 
	// ID                                              1    0: MPEG-4, 1: MPEG-2 
	// layer                                           2    always: '00' 
	// protection_absent                               1    
	// profile                                         2    
	// sampling_frequency_index                        4    
	// private_bit                                     1    
	// channel_configuration                           3    
	// original/copy                                   1    
	// home                                            1    
	// emphasis                                        2    only if ID == 0 

	// * ADTS Variable Header: these can change from frame to frame 
	// copyright_identification_bit                    1    
	// copyright_identification_start                  1    
	// aac_frame_length                               13    length of the frame including header (in bytes) 
	// adts_buffer_fullness                           11    0x7FF indicates VBR 
	// no_raw_data_blocks_in_frame                     2    

	// * ADTS Error check 
	// crc_check                                      16    only if protection_absent == 0 

	$byteoffset  = 0;
	$framenumber = 0;

	while (TRUE) {
		// breaks out when end-of-file encountered, or invalid data found,
		// or MaxFramesToScan frames have been scanned

		fseek($fd, $byteoffset, SEEK_SET);
		$AACheaderBitstream = BigEndian2Bin(fread($fd, 10));
		$bitoffset          = 0;
	
		$synctest                                               = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 12));
		$bitoffset += 12;
		if ($synctest != 0x0FFF) {
			$MP3fileInfo['error'] .= "\n".'Synch pattern (0xFFF) not found (found 0x'.dechex($synctest).' instead)';
			return FALSE;
		}
		$MP3fileInfo['aac']['header_type']                      = 'ADTS';
		$MP3fileInfo['aac']['header']['synch']                  = $synctest;
		$MP3fileInfo['fileformat']                              = 'aac';
	
		$MP3fileInfo['aac']['header']['mpeg_version']           = ((substr($AACheaderBitstream, $bitoffset, 1) == '0') ? 4 : 2);
		$bitoffset += 1;
		$MP3fileInfo['aac']['header']['layer']                  = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
		$bitoffset += 2;
		if ($MP3fileInfo['aac']['header']['layer'] != 0) {
			$MP3fileInfo['error'] .= "\n".'Layer error - expected 0x00, found 0x'.dechex($MP3fileInfo['aac']['header']['layer']).' instead';
			return FALSE;
		}
		$MP3fileInfo['aac']['header']['crc_present']            = ((substr($AACheaderBitstream, $bitoffset, 1) == '0') ? TRUE : FALSE);
		$bitoffset += 1;
		$MP3fileInfo['aac']['header']['profile_id']             = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
		$bitoffset += 2;
		$MP3fileInfo['aac']['header']['profile_text']           = AACprofileLookup($MP3fileInfo['aac']['header']['profile_id'], $MP3fileInfo['aac']['header']['mpeg_version']);
	
		$MP3fileInfo['aac']['header']['sample_frequency_index'] = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 4));
		$bitoffset += 4;
	 	$MP3fileInfo['aac']['header']['sample_frequency']       = AACsampleRateLookup($MP3fileInfo['aac']['header']['sample_frequency_index']);
	 	$MP3fileInfo['frequency']                               = $MP3fileInfo['aac']['header']['sample_frequency'];
	
		$MP3fileInfo['aac']['header']['private']                = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
		$bitoffset += 1;
		$MP3fileInfo['aac']['header']['channel_configuration']  = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 3));
		$bitoffset += 3;
	 	$MP3fileInfo['channels']                                = $MP3fileInfo['aac']['header']['channel_configuration'];
		$MP3fileInfo['aac']['header']['original']               = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
		$bitoffset += 1;
		$MP3fileInfo['aac']['header']['home']                   = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
		$bitoffset += 1;
	
		if ($MP3fileInfo['aac']['header']['mpeg_version'] == 4) {
			$MP3fileInfo['aac']['header']['emphasis']           = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
			$bitoffset += 2;
		}

		$MP3fileInfo['aac']["$framenumber"]['copyright_id_bit']       = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
		$bitoffset += 1;
		$MP3fileInfo['aac']["$framenumber"]['copyright_id_start']     = (bool) Bin2Dec(substr($AACheaderBitstream, $bitoffset, 1));
		$bitoffset += 1;
		$MP3fileInfo['aac']["$framenumber"]['aac_frame_length']       = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 13));
		$bitoffset += 13;
		$MP3fileInfo['aac']["$framenumber"]['adts_buffer_fullness']   = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 11));
		$bitoffset += 11;
		if ($MP3fileInfo['aac']["$framenumber"]['adts_buffer_fullness'] == 0x07FF) {
			$MP3fileInfo['bitrate_mode']                        = 'vbr';
		} else {
			$MP3fileInfo['bitrate_mode']                        = 'cbr';
		}
		$MP3fileInfo['aac']["$framenumber"]['num_raw_data_blocks']    = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 2));
		$bitoffset += 2;
		
		if ($MP3fileInfo['aac']['header']['crc_present']) {
			$MP3fileInfo['aac']["$framenumber"]['crc']                = Bin2Dec(substr($AACheaderBitstream, $bitoffset, 16));
			$bitoffset += 16;
		}
		
		$byteoffset += $MP3fileInfo['aac']["$framenumber"]['aac_frame_length'];
		if ((++$framenumber < $MaxFramesToScan) && (($byteoffset + 10) < $MP3fileInfo['filesize'])) {

			// keep scanning

		} else {

			$MP3fileInfo['playtime_seconds'] = ($MP3fileInfo['filesize'] / $byteoffset) * (($framenumber * 1024) / $MP3fileInfo['aac']['header']['sample_frequency']);
			$MP3fileInfo['bitrate_audio']    = ($MP3fileInfo['filesize'] * 8) / $MP3fileInfo['playtime_seconds'];
			return TRUE;

		}
	}
	// should never get here.
}

?>