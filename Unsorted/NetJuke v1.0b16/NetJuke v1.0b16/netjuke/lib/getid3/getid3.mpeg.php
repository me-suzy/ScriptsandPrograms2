<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.mpeg.php - part of getID3()                          //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getMPEGHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat'] = 'mpg';
	if (!$fd) {
		$MP3fileInfo['error'] .= "\n".'Could not open file';
		return FALSE;
	} else {
		// Start code                       32 bits
		// horizontal frame size            12 bits
		// vertical frame size              12 bits
		// pixel aspect ratio                4 bits
		// frame rate                        4 bits
		// bitrate                          18 bits
		// marker bit                        1 bit
		// VBV buffer size                  10 bits
		// constrained parameter flag        1 bit
		// intra quant. matrix flag          1 bit
		// intra quant. matrix values      512 bits (present if matrix flag == 1)
		// non-intra quant. matrix flag      1 bit
		// non-intra quant. matrix values  512 bits (present if matrix flag == 1)

		rewind($fd);
		$MPEGvideoHeader = fread($fd, FREAD_BUFFER_SIZE);
		$offset = 0;
		// MPEG video information is found as $00 $00 $01 $B3
		$matching_pattern = chr(0x00).chr(0x00).chr(0x01).chr(0xB3);
		while (substr($MPEGvideoHeader, $offset++, 4) !== $matching_pattern) {
			if ($offset >= (strlen($MPEGvideoHeader) - 12)) {
				$MPEGvideoHeader .= fread($fd, FREAD_BUFFER_SIZE);
				$MPEGvideoHeader = substr($MPEGvideoHeader, $offset);
				$offset = 0;
				if (strlen($MPEGvideoHeader) < 12) {
					$MP3fileInfo['error'] = "\n".'Could not find start of video block before end of file';
					return FALSE;
				} else if (ftell($fd) >= 100000) {
					$MP3fileInfo['error'] = "\n".'Could not find start of video block in the first 100,000 bytes (this might not be an MPEG-video file?)';
					unset($MP3fileInfo['fileformat']);
					return FALSE;
				}
			}
		}
		$offset += strlen($matching_pattern) - 1;

		$FrameSizeAspectRatioFrameRateDWORD = BigEndian2Int(substr($MPEGvideoHeader, $offset, 4));
		$offset += 4;

		$assortedinformation = BigEndian2Int(substr($MPEGvideoHeader, $offset, 4));
		$offset += 4;

		$MP3fileInfo['mpeg']['video']['raw']['framesize_horizontal'] = ($FrameSizeAspectRatioFrameRateDWORD & 0xFFF00000) >> 20; // 12 bits for horizontal frame size
		$MP3fileInfo['mpeg']['video']['raw']['framesize_vertical']   = ($FrameSizeAspectRatioFrameRateDWORD & 0x000FFF00) >> 8;  // 12 bits for vertical frame size
		$MP3fileInfo['mpeg']['video']['raw']['pixel_aspect_ratio']   = ($FrameSizeAspectRatioFrameRateDWORD & 0x000000F0) >> 4;
		$MP3fileInfo['mpeg']['video']['raw']['frame_rate']           = ($FrameSizeAspectRatioFrameRateDWORD & 0x0000000F);

		$MP3fileInfo['mpeg']['video']['framesize_horizontal'] = $MP3fileInfo['mpeg']['video']['raw']['framesize_horizontal'];
		$MP3fileInfo['mpeg']['video']['framesize_vertical']   = $MP3fileInfo['mpeg']['video']['raw']['framesize_vertical'];
		$MP3fileInfo['resolution_x'] = $MP3fileInfo['mpeg']['video']['framesize_horizontal'];
		$MP3fileInfo['resolution_y'] = $MP3fileInfo['mpeg']['video']['framesize_vertical'];

		$MP3fileInfo['mpeg']['video']['pixel_aspect_ratio']        = MPEGvideoAspectRatioLookup($MP3fileInfo['mpeg']['video']['raw']['pixel_aspect_ratio']);
		$MP3fileInfo['mpeg']['video']['pixel_aspect_ratio_text']   = MPEGvideoAspectRatioTextLookup($MP3fileInfo['mpeg']['video']['raw']['pixel_aspect_ratio']);
		$MP3fileInfo['mpeg']['video']['frame_rate']                = MPEGvideoFramerateLookup($MP3fileInfo['mpeg']['video']['raw']['frame_rate']);

		$MP3fileInfo['mpeg']['video']['raw']['bitrate']   = ($assortedinformation & 0xFFFFC000) >> 14;
		if ($MP3fileInfo['mpeg']['video']['raw']['bitrate'] == 0x3FFFF) { // 18 set bits
			$MP3fileInfo['mpeg']['video']['bitrate_type'] = 'variable';
			$MP3fileInfo['bitrate_mode']                  = 'vbr';
		} else {
			$MP3fileInfo['mpeg']['video']['bitrate_type'] = 'constant';
			$MP3fileInfo['bitrate_mode']                  = 'cbr';
			$MP3fileInfo['mpeg']['video']['bitrate_bps']  = $MP3fileInfo['mpeg']['video']['raw']['bitrate'] * 400;
			$MP3fileInfo['bitrate_video']                 = $MP3fileInfo['mpeg']['video']['bitrate_bps'];
		}
		$MP3fileInfo['mpeg']['video']['raw']['marker_bit']             = ($assortedinformation & 0x00002000) >> 14;
		$MP3fileInfo['mpeg']['video']['raw']['vbv_buffer_size']        = ($assortedinformation & 0x00001FF8) >> 13;
		$MP3fileInfo['mpeg']['video']['raw']['constrained_param_flag'] = ($assortedinformation & 0x00000004) >> 2;
		$MP3fileInfo['mpeg']['video']['raw']['intra_quant_flag']       = ($assortedinformation & 0x00000002) >> 1;

		return TRUE;
	}
}

?>