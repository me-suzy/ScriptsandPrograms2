<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.quicktime.php - part of getID3()                     //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function QuicktimeParseAtom($atomname, $atomsize, $atomdata, &$MP3fileInfo, $baseoffset, &$atomHierarchy) {
	// http://developer.apple.com/techpubs/quicktime/qtdevdocs/APIREF/INDEX/atomalphaindex.htm
	
	array_push($atomHierarchy, $atomname);
	$atomstructure['hierarchy'] = implode(' ', $atomHierarchy);
	$atomstructure['name']      = $atomname;
	$atomstructure['size']      = $atomsize;
	$atomstructure['offset']    = $baseoffset;
	switch ($atomname) {
		case 'moov': // MOVie container atom
		case 'trak': // TRAcK container atom
		case 'clip': // CLIPping container atom
		case 'matt': // track MATTe container atom
		case 'edts': // EDiTS container atom
		case 'tref': // Track REFerence container atom
		case 'mdia': // MeDIA container atom
		case 'minf': // Media INFormation container atom
		case 'dinf': // Data INFormation container atom
		case 'udta': // User DaTA container atom
		case 'stbl': // Sample TaBLe container atom
		case 'cmov': // Compressed MOVie container atom
		case 'rmra': // Reference Movie Record Atom
		case 'rmda': // Reference Movie Descriptor Atom
			$atomstructure['subatoms'] = QuicktimeParseContainerAtom($atomdata, $MP3fileInfo, $baseoffset + 8, $atomHierarchy);
			break;
		

		case '©cpy':
		case '©day':
		case '©dir':
		case '©ed1':
		case '©ed2':
		case '©ed3':
		case '©ed4':
		case '©ed5':
		case '©ed6':
		case '©ed7':
		case '©ed8':
		case '©ed9':
		case '©fmt':
		case '©inf':
		case '©prd':
		case '©prf':
		case '©req':
		case '©src':
		case '©wrt':
		case '©nam':
		case '©cmt':
		case '©wrn':
		case '©hst':
		case '©mak':
		case '©mod':
		case '©PRD':
		case '©swr':
		case '©aut':
		case '©ART':
		case '©trk':
		case '©alb':
		case '©com':
		case '©gen':
		case '©ope':
		case '©url':
		case '©enc':
			$atomstructure['data_length'] = BigEndian2Int(substr($atomdata,  0, 2));
			$atomstructure['language_id'] = BigEndian2Int(substr($atomdata,  2, 2));
			$atomstructure['data']        =               substr($atomdata,  4);

			$atomstructure['language']    = QuicktimeLanguageLookup($atomstructure['language_id']);
			$handyatomtranslatorarray['©cpy'] = 'copyright';
			$handyatomtranslatorarray['©day'] = 'creation_date';
			$handyatomtranslatorarray['©dir'] = 'director';
			$handyatomtranslatorarray['©ed1'] = 'edit1';
			$handyatomtranslatorarray['©ed2'] = 'edit2';
			$handyatomtranslatorarray['©ed3'] = 'edit3';
			$handyatomtranslatorarray['©ed4'] = 'edit4';
			$handyatomtranslatorarray['©ed5'] = 'edit5';
			$handyatomtranslatorarray['©ed6'] = 'edit6';
			$handyatomtranslatorarray['©ed7'] = 'edit7';
			$handyatomtranslatorarray['©ed8'] = 'edit8';
			$handyatomtranslatorarray['©ed9'] = 'edit9';
			$handyatomtranslatorarray['©fmt'] = 'format';
			$handyatomtranslatorarray['©inf'] = 'information';
			$handyatomtranslatorarray['©prd'] = 'producer';
			$handyatomtranslatorarray['©prf'] = 'performers';
			$handyatomtranslatorarray['©req'] = 'system_requirements';
			$handyatomtranslatorarray['©src'] = 'source_credit';
			$handyatomtranslatorarray['©wrt'] = 'writer';

			// http://www.geocities.com/xhelmboyx/quicktime/formats/qtm-layout.txt
			$handyatomtranslatorarray['©nam'] = 'title';
			$handyatomtranslatorarray['©cmt'] = 'comment';
			$handyatomtranslatorarray['©wrn'] = 'warning';
			$handyatomtranslatorarray['©hst'] = 'host_computer';
			$handyatomtranslatorarray['©mak'] = 'make';
			$handyatomtranslatorarray['©mod'] = 'model';
			$handyatomtranslatorarray['©PRD'] = 'product';
			$handyatomtranslatorarray['©swr'] = 'software';
			$handyatomtranslatorarray['©aut'] = 'author';
			$handyatomtranslatorarray['©ART'] = 'artist';
			$handyatomtranslatorarray['©trk'] = 'track';
			$handyatomtranslatorarray['©alb'] = 'album';
			$handyatomtranslatorarray['©com'] = 'comment';
			$handyatomtranslatorarray['©gen'] = 'genre';
			$handyatomtranslatorarray['©ope'] = 'composer';
			$handyatomtranslatorarray['©url'] = 'url';
			$handyatomtranslatorarray['©enc'] = 'encoder';
			if (isset($handyatomtranslatorarray["$atomname"])) {
				$MP3fileInfo['quicktime'][$handyatomtranslatorarray["$atomname"]] = $atomstructure['data'];
			}
			break;


		case 'play': // auto-PLAY atom
			$atomstructure['autoplay']            = (bool) BigEndian2Int(substr($atomdata,  0, 1));
			
			$MP3fileInfo['quicktime']['autoplay'] = $atomstructure['autoplay'];
			break;


		case 'WLOC': // Window LOCation atom
			$atomstructure['location_x']  = BigEndian2Int(substr($atomdata,  0, 2));
			$atomstructure['location_y']  = BigEndian2Int(substr($atomdata,  2, 2));
			break;


		case 'LOOP': // LOOPing atom
		case 'SelO': // play SELection Only atom
		case 'AllF': // play ALL Frames atom
			$atomstructure['data'] = BigEndian2Int($atomdata);
			break;


		case 'name': // 
		case 'MCPS': // Media Cleaner PRo
		case '@PRM': // adobe PReMiere version
		case '@PRQ': // adobe PRemiere Quicktime version
			$atomstructure['data'] = $atomdata;
			break;


		case 'cmvd': // Compressed MooV Data atom
			$MP3fileInfo['error'] .= "\n".'Compressed Quicktime MOOV Data atoms ("cmvd") not supported';
			break;


		case 'dcom': // Data COMpression atom
			$atomstructure['compression_id']   = $atomdata;
			$atomstructure['compression_text'] = QuicktimeDCOMLookup($atomdata);
			break;


		case 'rdrf': // Reference movie Data ReFerence atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3));
			$atomstructure['flags']['internal_data']                                = (bool) ($atomstructure['flags_raw'] & 0x000001);
			
			$atomstructure['reference_type_name']                               =               substr($atomdata,  4, 4);
			$atomstructure['reference_length']                                  = BigEndian2Int(substr($atomdata,  8, 4));
			switch ($atomstructure['reference_type_name']) {
				case 'url ':
					$atomstructure['url']                                       =  NoNullString(substr($atomdata, 12));
					break;

				case 'alis':
					$atomstructure['file_alias']                                =               substr($atomdata, 12);
					break;

				case 'rsrc':
					$atomstructure['resource_alias']                            =               substr($atomdata, 12);
					break;

				default:
					$atomstructure['data']                                      =               substr($atomdata, 12);
					break;
			}
			break;


		case 'rmqu': // Reference Movie QUality atom
			$atomstructure['movie_quality']                                         = BigEndian2Int($atomdata);
			break;


		case 'rmcs': // Reference Movie Cpu Speed atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['cpu_speed_rating']                                      = BigEndian2Int(substr($atomdata,  4, 2));
			break;


		case 'rmvc': // Reference Movie Version Check atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['gestalt_selector']                                      =               substr($atomdata,  4, 4);
			$atomstructure['gestalt_value_mask']                                    = BigEndian2Int(substr($atomdata,  8, 4));
			$atomstructure['gestalt_value']                                         = BigEndian2Int(substr($atomdata, 12, 4));
			$atomstructure['gestalt_check_type']                                    = BigEndian2Int(substr($atomdata, 14, 2));
			break;


		case 'rmcd': // Reference Movie Component check atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['component_type']                                        =               substr($atomdata,  4, 4);
			$atomstructure['component_subtype']                                     =               substr($atomdata,  8, 4);
			$atomstructure['component_manufacturer']                                =               substr($atomdata, 12, 4);
			$atomstructure['component_flags_raw']                                   = BigEndian2Int(substr($atomdata, 16, 4));
			$atomstructure['component_flags_mask']                                  = BigEndian2Int(substr($atomdata, 20, 4));
			$atomstructure['component_min_version']                                 = BigEndian2Int(substr($atomdata, 24, 4));
			break;


		case 'rmdr': // Reference Movie Data Rate atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['data_rate']                                             = BigEndian2Int(substr($atomdata,  4, 4));

			$atomstructure['data_rate_bps']                                         = $atomstructure['data_rate'] * 10;
			break;


		case 'rmla': // Reference Movie Language Atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['language_id']                                           = BigEndian2Int(substr($atomdata,  4, 2));

			$atomstructure['language']                                              = QuicktimeLanguageLookup($atomstructure['language_id']);
			break;


		case 'rmla': // Reference Movie Language Atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['track_id']                                              = BigEndian2Int(substr($atomdata,  4, 2));
			break;


		case 'stsd': // Sample Table Sample Description atom
			$atomstructure['version']                                                = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                              = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries']                                         = BigEndian2Int(substr($atomdata,  4, 4));
			$stsdEntriesDataOffset = 8;
			for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
				$atomstructure['sample_description_table']["$i"]['size']             = BigEndian2Int(substr($atomdata, $stsdEntriesDataOffset, 4));
				$stsdEntriesDataOffset += 4;
				$atomstructure['sample_description_table']["$i"]['data_format']      =               substr($atomdata, $stsdEntriesDataOffset, 4);
				$stsdEntriesDataOffset += 4;
				$atomstructure['sample_description_table']["$i"]['reserved']         = BigEndian2Int(substr($atomdata, $stsdEntriesDataOffset, 6));
				$stsdEntriesDataOffset += 6;
				$atomstructure['sample_description_table']["$i"]['reference_index']  = BigEndian2Int(substr($atomdata, $stsdEntriesDataOffset, 2));
				$stsdEntriesDataOffset += 2;
				$atomstructure['sample_description_table']["$i"]['data']             =               substr($atomdata, $stsdEntriesDataOffset, ($atomstructure['sample_description_table']["$i"]['size'] - 4 - 4 - 6 - 2));
				$stsdEntriesDataOffset += ($atomstructure['sample_description_table']["$i"]['size'] - 4 - 4 - 6 - 2);
				
				$atomstructure['sample_description_table']["$i"]['encoder_version']  = BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'],  0, 2));
				$atomstructure['sample_description_table']["$i"]['encoder_revision'] = BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'],  2, 2));
				$atomstructure['sample_description_table']["$i"]['encoder_vendor']   =               substr($atomstructure['sample_description_table']["$i"]['data'],  4, 4);
				
				if ($atomstructure['sample_description_table']["$i"]['encoder_vendor'] == chr(0).chr(0).chr(0).chr(0)) {

					// audio atom
					$atomstructure['sample_description_table']["$i"]['audio_channels']       =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'],  8,  2));
					$atomstructure['sample_description_table']["$i"]['audio_bit_depth']      =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 10,  2));
					$atomstructure['sample_description_table']["$i"]['audio_compression_id'] =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 12,  2));
					$atomstructure['sample_description_table']["$i"]['audio_packet_size']    =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 14,  2));
					$atomstructure['sample_description_table']["$i"]['audio_sample_rate']    = FixedPoint16_16(substr($atomstructure['sample_description_table']["$i"]['data'], 16,  4));
					
					$MP3fileInfo['quicktime']['audio']['codec']                              = QuicktimeAudioCodecLookup($atomstructure['sample_description_table']["$i"]['data_format']);
					$MP3fileInfo['quicktime']['audio']['frequency']                          = $atomstructure['sample_description_table']["$i"]['audio_sample_rate'];
					$MP3fileInfo['frequency']                                                = $atomstructure['sample_description_table']["$i"]['audio_sample_rate'];
					$MP3fileInfo['quicktime']['audio']['channels']                           = $atomstructure['sample_description_table']["$i"]['audio_channels'];
					$MP3fileInfo['channels']                                                 = $atomstructure['sample_description_table']["$i"]['audio_channels'];
					$MP3fileInfo['quicktime']['audio']['bit_depth']                          = $atomstructure['sample_description_table']["$i"]['audio_bit_depth'];
					
				} else {

					// video atom
					$atomstructure['sample_description_table']["$i"]['video_temporal_quality']  =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'],  8,  4));
					$atomstructure['sample_description_table']["$i"]['video_spatial_quality']   =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 12,  4));
					$atomstructure['sample_description_table']["$i"]['video_frame_width']       =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 16,  2));
					$atomstructure['sample_description_table']["$i"]['video_frame_height']      =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 18,  2));
					$atomstructure['sample_description_table']["$i"]['video_resolution_x']      = FixedPoint16_16(substr($atomstructure['sample_description_table']["$i"]['data'], 20,  4));
					$atomstructure['sample_description_table']["$i"]['video_resolution_y']      = FixedPoint16_16(substr($atomstructure['sample_description_table']["$i"]['data'], 24,  4));
					$atomstructure['sample_description_table']["$i"]['video_data_size']         =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 28,  4));
					$atomstructure['sample_description_table']["$i"]['video_frame_count']       =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 32,  2));
					$atomstructure['sample_description_table']["$i"]['video_encoder_name_len']  =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 34,  1));
					$atomstructure['sample_description_table']["$i"]['video_encoder_name']      =                 substr($atomstructure['sample_description_table']["$i"]['data'], 35, $atomstructure['sample_description_table']["$i"]['video_encoder_name_len']);
					$atomstructure['sample_description_table']["$i"]['video_pixel_color_depth'] =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 66,  2));
					$atomstructure['sample_description_table']["$i"]['video_color_table_id']    =   BigEndian2Int(substr($atomstructure['sample_description_table']["$i"]['data'], 68,  2));
					
					$atomstructure['sample_description_table']["$i"]['video_pixel_color_type']  = (($atomstructure['sample_description_table']["$i"]['video_pixel_color_depth'] > 32) ? 'grayscale' : 'color');
					$atomstructure['sample_description_table']["$i"]['video_pixel_color_name']  = QuicktimeColorNameLookup($atomstructure['sample_description_table']["$i"]['video_pixel_color_depth']);
					//$MP3fileInfo['quicktime']['video']['codec']                                 = QuicktimeVideoCodecLookup($atomstructure['sample_description_table']["$i"]['data_format']);
					$MP3fileInfo['quicktime']['video']['codec']                                 = $atomstructure['sample_description_table']["$i"]['video_encoder_name'];
					$MP3fileInfo['quicktime']['video']['color_depth']                           = $atomstructure['sample_description_table']["$i"]['video_pixel_color_depth'];
					$MP3fileInfo['quicktime']['video']['color_depth_name']                      = $atomstructure['sample_description_table']["$i"]['video_pixel_color_name'];
				
				}
				unset($atomstructure['sample_description_table']["$i"]['data']);
			}
			break;


		case 'stts': // Sample Table Time-to-Sample atom
			$atomstructure['version']                                           = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                         = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries']                                    = BigEndian2Int(substr($atomdata,  4, 4));
			$sttsEntriesDataOffset = 8;
			for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
				$atomstructure['time_to_sample_table']["$i"]['sample_count']    = BigEndian2Int(substr($atomdata, $sttsEntriesDataOffset, 4));
				$sttsEntriesDataOffset += 4;
				$atomstructure['time_to_sample_table']["$i"]['sample_duration'] =               substr($atomdata, $sttsEntriesDataOffset, 4);
				$sttsEntriesDataOffset += 4;
			}
			break;


		case 'stss': // Sample Table Sync Sample (key frames) atom
			$atomstructure['version']                        = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                      = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries']                 = BigEndian2Int(substr($atomdata,  4, 4));
			$stssEntriesDataOffset = 8;
			for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
				$atomstructure['time_to_sample_table']["$i"] = BigEndian2Int(substr($atomdata, $stssEntriesDataOffset, 4));
				$stssEntriesDataOffset += 4;
			}
			break;


		case 'stsc': // Sample Table Sample-to-Chunk atom
			$atomstructure['version']                                               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                                             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries']                                        = BigEndian2Int(substr($atomdata,  4, 4));
			$stscEntriesDataOffset = 8;
			for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
				$atomstructure['sample_to_chunk_table']["$i"]['first_chunk']        = BigEndian2Int(substr($atomdata, $stscEntriesDataOffset, 4));
				$stscEntriesDataOffset += 4;
				$atomstructure['sample_to_chunk_table']["$i"]['samples_per_chunk']  = BigEndian2Int(substr($atomdata, $stscEntriesDataOffset, 4));
				$stscEntriesDataOffset += 4;
				$atomstructure['sample_to_chunk_table']["$i"]['sample_description'] = BigEndian2Int(substr($atomdata, $stscEntriesDataOffset, 4));
				$stscEntriesDataOffset += 4;
			}
			break;


		case 'stsz': // Sample Table SiZe atom
			$atomstructure['version']                         = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                       = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['sample_size']                     = BigEndian2Int(substr($atomdata,  4, 4));
			$atomstructure['number_entries']                  = BigEndian2Int(substr($atomdata,  8, 4));
			$stszEntriesDataOffset = 12;
			if ($atomstructure['sample_size'] == 0) {
				for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
					$atomstructure['sample_size_table']["$i"] = BigEndian2Int(substr($atomdata, $stszEntriesDataOffset, 4));
					$stszEntriesDataOffset += 4;
				}
			}
			break;


		case 'stco': // Sample Table Chunk Offset atom
			$atomstructure['version']                          = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']                        = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries']                   = BigEndian2Int(substr($atomdata,  4, 4));
			$stcoEntriesDataOffset = 8;
			for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
				$atomstructure['chunk_offset_table']["$i"] = BigEndian2Int(substr($atomdata, $stcoEntriesDataOffset, 4));
				$stcoEntriesDataOffset += 4;
			}
			break;


		case 'dref': // Data REFerence atom
			$atomstructure['version']        = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']      = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries'] = BigEndian2Int(substr($atomdata,  4, 4));
			$drefDataOffset = 8;
			for ($i = 0; $i < $atomstructure['number_entries']; $i++) {
				$atomstructure['data_references']["$i"]['size']                    = BigEndian2Int(substr($atomdata, $drefDataOffset, 4));
				$drefDataOffset += 4;
				$atomstructure['data_references']["$i"]['type']                    =               substr($atomdata, $drefDataOffset, 4);
				$drefDataOffset += 4;
				$atomstructure['data_references']["$i"]['version']                 = BigEndian2Int(substr($atomdata,  $drefDataOffset, 1));
				$drefDataOffset += 1;
				$atomstructure['data_references']["$i"]['flags_raw']               = BigEndian2Int(substr($atomdata,  $drefDataOffset, 3)); // hardcoded: 0x000
				$drefDataOffset += 3;
				$atomstructure['data_references']["$i"]['data']                    =               substr($atomdata, $drefDataOffset, ($atomstructure['data_references']["$i"]['size'] - 4 - 4 - 1 - 3));
				$drefDataOffset += ($atomstructure['data_references']["$i"]['size'] - 4 - 4 - 1 - 3);
				
				$atomstructure['data_references']["$i"]['flags']['self_reference'] = (bool) ($atomstructure['data_references']["$i"]['flags_raw'] & 0x001);
			}
			break;


		case 'gmin': // base Media INformation atom
			$atomstructure['version']                = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']              = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['graphics_mode']          = BigEndian2Int(substr($atomdata,  4, 2));
			$atomstructure['opcolor_red']            = BigEndian2Int(substr($atomdata,  6, 2));
			$atomstructure['opcolor_green']          = BigEndian2Int(substr($atomdata,  8, 2));
			$atomstructure['opcolor_blue']           = BigEndian2Int(substr($atomdata, 10, 2));
			$atomstructure['balance']                = BigEndian2Int(substr($atomdata, 12, 2));
			$atomstructure['reserved']               = BigEndian2Int(substr($atomdata, 14, 2));
			break;


		case 'smhd': // Sound Media information HeaDer atom
			$atomstructure['version']                = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']              = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['balance']                = BigEndian2Int(substr($atomdata,  4, 2));
			$atomstructure['reserved']               = BigEndian2Int(substr($atomdata,  6, 2));
			break;


		case 'vmhd': // Video Media information HeaDer atom
			$atomstructure['version']                = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']              = BigEndian2Int(substr($atomdata,  1, 3));
			$atomstructure['graphics_mode']          = BigEndian2Int(substr($atomdata,  4, 2));
			$atomstructure['opcolor_red']            = BigEndian2Int(substr($atomdata,  6, 2));
			$atomstructure['opcolor_green']          = BigEndian2Int(substr($atomdata,  8, 2));
			$atomstructure['opcolor_blue']           = BigEndian2Int(substr($atomdata, 10, 2));
			
			$atomstructure['flags']['no_lean_ahead'] = (bool) ($atomstructure['flags_raw'] & 0x001);
			break;


		case 'hdlr': // HanDLeR reference atom
			$atomstructure['version']                = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']              = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['component_type']         =               substr($atomdata,  4, 4);
			$atomstructure['component_subtype']      =               substr($atomdata,  8, 4);
			$atomstructure['component_manufacturer'] =               substr($atomdata, 12, 4);
			$atomstructure['component_flags_raw']    = BigEndian2Int(substr($atomdata, 16, 4));
			$atomstructure['component_flags_mask']   = BigEndian2Int(substr($atomdata, 20, 4));
			$atomstructure['component_name']         = Pascal2String(substr($atomdata, 24));
			break;


		case 'mdhd': // MeDia HeaDer atom
			$atomstructure['version']               = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']             = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['creation_time']         = BigEndian2Int(substr($atomdata,  4, 4));
			$atomstructure['modify_time']           = BigEndian2Int(substr($atomdata,  8, 4));
			$atomstructure['time_scale']            = BigEndian2Int(substr($atomdata, 12, 4));
			$atomstructure['duration']              = BigEndian2Int(substr($atomdata, 16, 4));
			$atomstructure['language_id']           = BigEndian2Int(substr($atomdata, 20, 2));
			$atomstructure['quality']               = BigEndian2Int(substr($atomdata, 22, 2));

			$atomstructure['creation_time_unix']    = DateMac2Unix($atomstructure['creation_time']);
			$atomstructure['modify_time_unix']      = DateMac2Unix($atomstructure['modify_time']);
			$atomstructure['playtime_seconds']      = $atomstructure['duration'] / $atomstructure['time_scale'];
			$atomstructure['language']              = QuicktimeLanguageLookup($atomstructure['language_id']);
			break;


		case 'pnot': // Preview atom
			$atomstructure['modification_date']      = BigEndian2Int(substr($atomdata,  0, 4)); // "standard Macintosh format"
			$atomstructure['version_number']         = BigEndian2Int(substr($atomdata,  4, 2)); // hardcoded: 0x00
			$atomstructure['atom_type']              =               substr($atomdata,  6, 4);        // usually: 'PICT'
			$atomstructure['atom_index']             = BigEndian2Int(substr($atomdata, 10, 2)); // usually: 0x01
	
			$atomstructure['modification_date_unix'] = DateMac2Unix($atomstructure['modification_date']);
			break;


		case 'crgn': // Clipping ReGioN atom
			$atomstructure['region_size']   = BigEndian2Int(substr($atomdata,  0, 2)); // The Region size, Region boundary box,
			$atomstructure['boundary_box']  = BigEndian2Int(substr($atomdata,  2, 8)); // and Clipping region data fields
			$atomstructure['clipping_data'] =               substr($atomdata, 10);           // constitute a QuickDraw region.
			break;


		case 'load': // track LOAD settings atom
			$atomstructure['preload_start_time']             = BigEndian2Int(substr($atomdata,  0, 4));
			$atomstructure['preload_duration']               = BigEndian2Int(substr($atomdata,  4, 4));
			$atomstructure['preload_flags_raw']              = BigEndian2Int(substr($atomdata,  8, 4));
			$atomstructure['default_hints_raw']              = BigEndian2Int(substr($atomdata, 12, 4));
			
			$atomstructure['default_hints']['double_buffer'] = (bool) ($atomstructure['default_hints_raw'] & 0x0020);
			$atomstructure['default_hints']['high_quality']  = (bool) ($atomstructure['default_hints_raw'] & 0x0100);
			break;


		case 'tmcd': // TiMe CoDe atom
		case 'chap': // CHAPter list atom
		case 'sync': // SYNChronization atom
		case 'scpt': // tranSCriPT atom
		case 'ssrc': // non-primary SouRCe atom
			for ($i = 0; $i < (strlen($atomdata) % 4); $i++) {
				$atomstructure['track_id']["$i"] = BigEndian2Int(substr($atomdata, $i * 4, 4));
			}
			break;


		case 'elst': // Edit LiST atom
			$atomstructure['version']        = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']      = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['number_entries'] = BigEndian2Int(substr($atomdata,  4, 4));
			for ($i = 0; $i < $atomstructure['number_entries']; $i++ ) {
				$atomstructure['edit_list']["$i"]['track_duration'] = BigEndian2Int(substr($atomdata, 8 + ($i * 12) + 0, 4));
				$atomstructure['edit_list']["$i"]['media_time']     = BigEndian2Int(substr($atomdata, 8 + ($i * 12) + 4, 4));
				$atomstructure['edit_list']["$i"]['media_rate']     = BigEndian2Int(substr($atomdata, 8 + ($i * 12) + 8, 4));
			}
			break;


		case 'kmat': // compressed MATte atom
			$atomstructure['version']        = BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']      = BigEndian2Int(substr($atomdata,  1, 3)); // hardcoded: 0x000
			$atomstructure['matte_data_raw'] =               substr($atomdata,  4);
			break;


		case 'ctab': // Color TABle atom
			$atomstructure['color_table_seed']   = BigEndian2Int(substr($atomdata,  0, 4)); // hardcoded: 0x00000000
			$atomstructure['color_table_flags']  = BigEndian2Int(substr($atomdata,  4, 2)); // hardcoded: 0x8000
			$atomstructure['color_table_size']   = BigEndian2Int(substr($atomdata,  6, 2)) + 1;
			for ($colortableentry = 0; $colortableentry < $atomstructure['color_table_size']; $colortableentry++) {
				$atomstructure['color_table']["$colortableentry"]['alpha'] = BigEndian2Int(substr($atomdata, 8 + ($colortableentry * 8) + 0, 2));
				$atomstructure['color_table']["$colortableentry"]['red']   = BigEndian2Int(substr($atomdata, 8 + ($colortableentry * 8) + 2, 2));
				$atomstructure['color_table']["$colortableentry"]['green'] = BigEndian2Int(substr($atomdata, 8 + ($colortableentry * 8) + 4, 2));
				$atomstructure['color_table']["$colortableentry"]['blue']  = BigEndian2Int(substr($atomdata, 8 + ($colortableentry * 8) + 6, 2));
			}
			break;


		case 'mvhd': // MoVie HeaDer atom
			$atomstructure['version']               =   BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']             =   BigEndian2Int(substr($atomdata,  1, 3));
			$atomstructure['creation_time']         =   BigEndian2Int(substr($atomdata,  4, 4));
			$atomstructure['modify_time']           =   BigEndian2Int(substr($atomdata,  8, 4));
			$atomstructure['time_scale']            =   BigEndian2Int(substr($atomdata, 12, 4));
			$atomstructure['duration']              =   BigEndian2Int(substr($atomdata, 16, 4));
			$atomstructure['preferred_rate']        = FixedPoint16_16(substr($atomdata, 20, 4));
			$atomstructure['preferred_volume']      =   FixedPoint8_8(substr($atomdata, 24, 2));
			$atomstructure['reserved']              =                 substr($atomdata, 26, 10);
			$atomstructure['matrix_a']              = FixedPoint16_16(substr($atomdata, 36, 4));
			$atomstructure['matrix_b']              = FixedPoint16_16(substr($atomdata, 40, 4));
			$atomstructure['matrix_u']              = FixedPoint16_16(substr($atomdata, 44, 4));
			$atomstructure['matrix_c']              = FixedPoint16_16(substr($atomdata, 48, 4));
			$atomstructure['matrix_v']              = FixedPoint16_16(substr($atomdata, 52, 4));
			$atomstructure['matrix_d']              = FixedPoint16_16(substr($atomdata, 56, 4));
			$atomstructure['matrix_x']              =  FixedPoint2_30(substr($atomdata, 60, 4));
			$atomstructure['matrix_y']              =  FixedPoint2_30(substr($atomdata, 64, 4));
			$atomstructure['matrix_w']              =  FixedPoint2_30(substr($atomdata, 68, 4));
			$atomstructure['preview_time']          =   BigEndian2Int(substr($atomdata, 72, 4));
			$atomstructure['preview_duration']      =   BigEndian2Int(substr($atomdata, 76, 4));
			$atomstructure['poster_time']           =   BigEndian2Int(substr($atomdata, 80, 4));
			$atomstructure['selection_time']        =   BigEndian2Int(substr($atomdata, 84, 4));
			$atomstructure['selection_duration']    =   BigEndian2Int(substr($atomdata, 88, 4));
			$atomstructure['current_time']          =   BigEndian2Int(substr($atomdata, 92, 4));
			$atomstructure['next_track_id']         =   BigEndian2Int(substr($atomdata, 96, 4));

			$atomstructure['creation_time_unix']    = DateMac2Unix($atomstructure['creation_time']);
			$atomstructure['modify_time_unix']      = DateMac2Unix($atomstructure['modify_time']);
			$MP3fileInfo['quicktime']['time_scale'] = $atomstructure['time_scale'];
			$MP3fileInfo['playtime_seconds']        = $atomstructure['duration'] / $atomstructure['time_scale'];
			break;


		case 'tkhd': // TracK HeaDer atom
			$atomstructure['version']             =   BigEndian2Int(substr($atomdata,  0, 1));
			$atomstructure['flags_raw']           =   BigEndian2Int(substr($atomdata,  1, 3));
			$atomstructure['creation_time']       =   BigEndian2Int(substr($atomdata,  4, 4));
			$atomstructure['modify_time']         =   BigEndian2Int(substr($atomdata,  8, 4));
			$atomstructure['trackid']             =   BigEndian2Int(substr($atomdata, 12, 4));
			$atomstructure['reserved1']           =   BigEndian2Int(substr($atomdata, 16, 4));
			$atomstructure['duration']            =   BigEndian2Int(substr($atomdata, 20, 4));
			$atomstructure['reserved2']           =   BigEndian2Int(substr($atomdata, 24, 8));
			$atomstructure['layer']               =   BigEndian2Int(substr($atomdata, 32, 2));
			$atomstructure['alternate_group']     =   BigEndian2Int(substr($atomdata, 34, 2));
			$atomstructure['volume']              =   FixedPoint8_8(substr($atomdata, 36, 2));
			$atomstructure['reserved3']           =   BigEndian2Int(substr($atomdata, 38, 2));
			$atomstructure['matrix_a']            = FixedPoint16_16(substr($atomdata, 40, 4));
			$atomstructure['matrix_b']            = FixedPoint16_16(substr($atomdata, 44, 4));
			$atomstructure['matrix_u']            = FixedPoint16_16(substr($atomdata, 48, 4));
			$atomstructure['matrix_c']            = FixedPoint16_16(substr($atomdata, 52, 4));
			$atomstructure['matrix_v']            = FixedPoint16_16(substr($atomdata, 56, 4));
			$atomstructure['matrix_d']            = FixedPoint16_16(substr($atomdata, 60, 4));
			$atomstructure['matrix_x']            =  FixedPoint2_30(substr($atomdata, 64, 4));
			$atomstructure['matrix_y']            =  FixedPoint2_30(substr($atomdata, 68, 4));
			$atomstructure['matrix_w']            =  FixedPoint2_30(substr($atomdata, 72, 4));
			$atomstructure['width']               = FixedPoint16_16(substr($atomdata, 76, 4));
			$atomstructure['height']              = FixedPoint16_16(substr($atomdata, 80, 4));

			$atomstructure['flags']['enabled']    = (bool) ($atomstructure['flags_raw'] & 0x0001);
			$atomstructure['flags']['in_movie']   = (bool) ($atomstructure['flags_raw'] & 0x0002);
			$atomstructure['flags']['in_preview'] = (bool) ($atomstructure['flags_raw'] & 0x0004);
			$atomstructure['flags']['in_poster']  = (bool) ($atomstructure['flags_raw'] & 0x0008);
			$atomstructure['creation_time_unix']  = DateMac2Unix($atomstructure['creation_time']);
			$atomstructure['modify_time_unix']    = DateMac2Unix($atomstructure['modify_time']);

			if (!isset($MP3fileInfo['resolution_x']) || !isset($MP3fileInfo['resolution_y'])) {
				$MP3fileInfo['resolution_x']      = 0;
				$MP3fileInfo['resolution_y']      = 0;
			}
			$MP3fileInfo['resolution_x']                       = max($MP3fileInfo['resolution_x'], $atomstructure['width']);
			$MP3fileInfo['resolution_y']                       = max($MP3fileInfo['resolution_y'], $atomstructure['height']);
			$MP3fileInfo['quicktime']['video']['resolution_x'] = $MP3fileInfo['resolution_x'];
			$MP3fileInfo['quicktime']['video']['resolution_y'] = $MP3fileInfo['resolution_y'];
			break;


		case 'mdat': // Media DATa atom
		case 'free': // FREE space atom
		case 'skip': // SKIP atom
		case 'wide': // 64-bit expansion placeholder atom
			// When writing QuickTime files, it is sometimes necessary to update an atom's size.
			// It is impossible to update a 32-bit atom to a 64-bit atom since the 32-bit atom
			// is only 8 bytes in size, and the 64-bit atom requires 16 bytes. Therefore, QuickTime
			// puts an 8-byte placeholder atom before any atoms it may have to update the size of.
			// In this way, if the atom needs to be converted from a 32-bit to a 64-bit atom, the
			// placeholder atom can be overwritten to obtain the necessary 8 extra bytes.
			// The placeholder atom has a type of kWideAtomPlaceholderType ( 'wide' ).

			// 'mdat' data is too big to deal with, contains no useful metadata
			// 'free', 'skip' and 'wide' are just padding, contains no useful data at all
			break;


		default:
			$MP3fileInfo['error'] .= "\n".'Unknown QuickTime atom type: "'.$atomname.'" at offset '.$baseoffset;
			$atomstructure['data'] = $atomdata;
			break;
	}
	array_pop($atomHierarchy);
	return $atomstructure;
}

function QuicktimeParseContainerAtom($atomdata, &$MP3fileInfo, $baseoffset, &$atomHierarchy) {
	$atomstructure  = FALSE;
	$subatomoffset  = 0;
	$subatomcounter = 0;
	if ((strlen($atomdata) == 4) && (BigEndian2Int($atomdata) == 0x00000000)) {
		return FALSE;
	}
	while ($subatomoffset < strlen($atomdata)) {
		$subatomsize = BigEndian2Int(substr($atomdata, $subatomoffset + 0, 4));
		$subatomname =               substr($atomdata, $subatomoffset + 4, 4);
		$subatomdata =               substr($atomdata, $subatomoffset + 8, $subatomsize - 8);
		if ($subatomsize == 0) {
			// Furthermore, for historical reasons the list of atoms is optionally
			// terminated by a 32-bit integer set to 0. If you are writing a program
			// to read user data atoms, you should allow for the terminating 0.
			return $atomstructure;
		}

		$atomstructure["$subatomcounter"] = QuicktimeParseAtom($subatomname, $subatomsize, $subatomdata, $MP3fileInfo, $baseoffset + $subatomoffset, $atomHierarchy);

		$subatomoffset += $subatomsize;
		$subatomcounter++;
	}
	return $atomstructure;
}


function getQuicktimeHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat'] = 'quicktime';

	$offset      = 0;
	$atomcounter = 0;
	
	while ($offset < $MP3fileInfo['filesize']) {
		fseek($fd, $offset, SEEK_SET);
		$AtomHeader = fread($fd, 8);
		
		$atomsize = BigEndian2Int(substr($AtomHeader, 0, 4));
		$atomname =               substr($AtomHeader, 4, 4);
		$MP3fileInfo['quicktime']["$atomname"]['name']   = $atomname;
		$MP3fileInfo['quicktime']["$atomname"]['size']   = $atomsize;
		$MP3fileInfo['quicktime']["$atomname"]['offset'] = $offset;

		if (($offset + $atomsize) > $MP3fileInfo['filesize']) {
			$MP3fileInfo['error'] .= "\n".'Atom at offset '.$offset.' claims to go beyond end-of-file (length: '.$atomsize.' bytes)';
			return FALSE;
		}

		switch ($atomname) {
			case 'mdat': // Media DATa atom
			case 'free': // FREE space atom
			case 'skip': // SKIP atom
			case 'wide': // 64-bit expansion placeholder atom
				// 'mdat' data is too big to deal with, contains no useful metadata
				// 'free', 'skip' and 'wide' are just padding, contains no useful data at all
				break;

			default:
				$atomHierarchy = array();
				$MP3fileInfo['quicktime']["$atomname"] = QuicktimeParseAtom($atomname, $atomsize, fread($fd, $atomsize), $MP3fileInfo, $offset, $atomHierarchy);
				break;
		}

		$offset += $atomsize;
		$atomcounter++;
	}
	
	if (!isset($MP3fileInfo['bitrate']) && isset($MP3fileInfo['quicktime']['mdat']['size']) && isset($MP3fileInfo['playtime_seconds'])) {
		$MP3fileInfo['bitrate'] = ($MP3fileInfo['quicktime']['mdat']['size'] * 8) / $MP3fileInfo['playtime_seconds'];
	}

	return TRUE;
}


?>