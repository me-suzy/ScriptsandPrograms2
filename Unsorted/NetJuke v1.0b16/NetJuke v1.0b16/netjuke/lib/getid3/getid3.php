<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// Requires: PHP 4.1.0 (or higher)                             //
//           GD  <1.6 for GIF and JPEG functions               //
//           GD >=1.6 for PNG and JPEG functions               //
//           GD >=2.0 for BMP display function                 //
//                                                             //
// Please see getid3.readme.txt for more information           //
//                                                             //
/////////////////////////////////////////////////////////////////

define('GETID3VERSION', '1.5.4');
define('FREAD_BUFFER_SIZE', 16384); // number of bytes to read in at once

$includedfilepaths = get_included_files();
foreach ($includedfilepaths as $key => $val) {
	if (basename($val) == 'getid3.php') {
		define('GETID3_INCLUDEPATH', dirname($val).'/');
	}
}
if (!defined('GETID3_INCLUDEPATH')) {
	define('GETID3_INCLUDEPATH', '');
}


function GetAllMP3info($filename, $assumedFormat='', $allowedFormats=array()) {
	if (count($allowedFormats) < 1) {
		// Simply comment out any format listed here that you don't want parsed.
		// Commenting out 'aac' is recommended unless you expect to encounter
		// some AAC files, due to its similarity to MP3, as well as slow parsing

		//$allowedFormats[] = 'aac';       // audio       - Advanced Audio Coding
		$allowedFormats[] = 'asf';       // audio/video - Advanced Streaming Format, Windows Media Video, Windows Media Audio
		$allowedFormats[] = 'bmp';       // still image - Bitmap (Windows, OS/2; uncompressed, RLE8, RLE4)
		$allowedFormats[] = 'flac';      // audio       - Free Lossless Audio Codec
		$allowedFormats[] = 'gif';       // still image - Graphics Interchange Format
		$allowedFormats[] = 'jpg';       // still image - JPEG (Joint Photographic Experts Group)
		$allowedFormats[] = 'mac';       // audio       - Monkey's Audio [Compressor]
		$allowedFormats[] = 'midi';      // audio       - MIDI (Musical Instrument Digital Interface)
		$allowedFormats[] = 'mp3';       // audio       - MPEG-1 audio, Layer-3
		$allowedFormats[] = 'mpc';       // audio       - Musepack / MPEGplus
		$allowedFormats[] = 'mpeg';      // audio/video - MPEG (Moving Pictures Experts Group)
		$allowedFormats[] = 'ogg';       // audio       - Ogg Vorbis
		$allowedFormats[] = 'png';       // still image - Portable Network Graphics
		$allowedFormats[] = 'quicktime'; // audio/video - Quicktime
		$allowedFormats[] = 'real';      // audio/video - RealAudio, RealVideo
		$allowedFormats[] = 'riff';      // audio/video - RIFF (Resource Interchange File Format), WAV, AVI
		$allowedFormats[] = 'sdss';      // audio       - renamed RIFF WAV
		$allowedFormats[] = 'vqf';       // audio       - transform-domain weighted interleave Vector Quantization Format
		$allowedFormats[] = 'zip';       // data        - compressed data
	}
	include_once(GETID3_INCLUDEPATH.'getid3.lookup.php');    // Lookup tables
	include_once(GETID3_INCLUDEPATH.'getid3.functions.php'); // Function library

	$MP3fileInfo['getID3version'] = GETID3VERSION;
	$MP3fileInfo['fileformat']    = ''; // filled in later
	$MP3fileInfo['error']         = ''; // filled in later, unset if not used
	$MP3fileInfo['exist']         = FALSE;

	if (strstr($filename, 'http://') || strstr($filename, 'ftp://')) {
		// remote file - copy locally first and work from there

		$MP3fileInfo['filename'] = $filename;
		$localfilepointer = tmpfile();
		if ($fp = @fopen($filename, 'rb')) {
			$MP3fileInfo['exist'] = TRUE;
			$MP3fileInfo['filesize'] = 0;
			while ($buffer = fread($fp, FREAD_BUFFER_SIZE)) {
				$MP3fileInfo['filesize'] += fwrite($localfilepointer, $buffer);
			}
			fclose($fp);
		}

	} else {
		// local file

		if (!file_exists($filename)) {
			// this code segment is needed for the file browser demonstrated in check.php
			// but may interfere with finding a filename that actually does contain apparently
			// escaped characters (like "file\'name.mp3") and/or
			// %xx-format characters (like "file%20name.mp3")
			$filename = stripslashes($filename);
			if (!file_exists($filename)) {
				$filename = rawurldecode($filename);
			}
		}
		$MP3fileInfo['filename'] = basename($filename);
		if ($localfilepointer = @fopen($filename, 'rb')) {
			$MP3fileInfo['exist'] = TRUE;
			clearstatcache();
			$MP3fileInfo['filesize'] = filesize($filename);
		}
	}


	if ($MP3fileInfo['exist']) {
		rewind($localfilepointer);
		$formattest = fread($localfilepointer, FREAD_BUFFER_SIZE);

		if (ParseAsThisFormat('zip', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.zip.php');
			getZipHeaderFilepointer($filename, $MP3fileInfo);
		} else if (ParseAsThisFormat('ogg', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.ogg.php');
			getOggHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('riff', $assumedFormat, $allowedFormats, $formattest) || ParseAsThisFormat('sdss', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.riff.php');
			getRIFFHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('mpeg', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.mpeg.php');
			getMPEGHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('asf', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.asf.php');
			getASFHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('mac', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.ape.php');
			getMonkeysAudioHeaderFilepointer($localfilepointer, $MP3fileInfo);
			getAPEtagFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('mpc', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.mpc.php');
			getMPCHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('midi', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.midi.php');
			if ($assumedFormat === FALSE) {
				// do not parse all MIDI tracks - much faster
				getMIDIHeaderFilepointer($localfilepointer, $MP3fileInfo, FALSE);
			} else {
				getMIDIHeaderFilepointer($localfilepointer, $MP3fileInfo);
			}
		} else if (ParseAsThisFormat('jpg', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.jpg.php');
			getJPGHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('gif', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.gif.php');
			getGIFHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('png', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.png.php');
			getPNGHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('bmp', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.bmp.php');
			getBMPHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('real', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.real.php');
			getRealHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('flac', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.flac.php');
			getFLACHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('vqf', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.vqf.php');
			getVQFHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('quicktime', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.quicktime.php');
			getQuicktimeHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('aac', $assumedFormat, $allowedFormats, $formattest)) {
			include_once(GETID3_INCLUDEPATH.'getid3.aac.php');
			if (!getAACADIFheaderFilepointer($localfilepointer, $MP3fileInfo)) {
				$dummy = $MP3fileInfo;
				unset($dummy['error']);
				if (getAACADTSheaderFilepointer($localfilepointer, $dummy)) {
					$MP3fileInfo = $dummy;
				}
			}
		} else if (in_array('mp3', $allowedFormats) && ($allowedFormats !== FALSE) && (($assumedFormat == 'mp3') || (($assumedFormat == '') && ((substr($formattest, 0, 3) == 'ID3') || (substr(BigEndian2Bin(substr($formattest, 0, 2)), 0, 11) == '11111111111'))))) {
			// assume MP3 format (or possibly AAC)
			include_once(GETID3_INCLUDEPATH.'getid3.mp3.php');
			getMP3headerFilepointer($localfilepointer, $MP3fileInfo, TRUE);

			if (!isset($MP3fileInfo['audiodataoffset'])) {
				$MP3fileInfo['audiobytes'] = 0;
			} else {
				$MP3fileInfo['audiobytes'] = $MP3fileInfo['filesize'] - $MP3fileInfo['audiodataoffset'];
			}
			if (isset($MP3fileInfo['id3']['id3v1'])) {
				$MP3fileInfo['audiobytes'] -= 128;
			}
			if (isset($mp3info['lyrics3']['raw']['lyrics3tagsize'])) {
				$MP3fileInfo['audiobytes'] -= $mp3info['lyrics3']['raw']['lyrics3tagsize'];
			}
			if ($MP3fileInfo['audiobytes'] <= 0) {
				unset($MP3fileInfo['audiobytes']);
			}
			if (!isset($MP3fileInfo['playtime_seconds']) && isset($MP3fileInfo['audiobytes']) && isset($MP3fileInfo['bitrate_audio']) && ($MP3fileInfo['bitrate_audio'] > 0)) {
				$MP3fileInfo['playtime_seconds'] = ($MP3fileInfo['audiobytes'] * 8) / $MP3fileInfo['bitrate_audio'];
			}
		}
	}
	$CombinedBitrate  = 0;
	$CombinedBitrate += (isset($MP3fileInfo['bitrate_audio']) ? $MP3fileInfo['bitrate_audio'] : 0);
	$CombinedBitrate += (isset($MP3fileInfo['bitrate_video']) ? $MP3fileInfo['bitrate_video'] : 0);
	if (($CombinedBitrate > 0) && !isset($MP3fileInfo['bitrate'])) {
		$MP3fileInfo['bitrate'] = $CombinedBitrate;
	}

	if (isset($MP3fileInfo['playtime_seconds']) && ($MP3fileInfo['playtime_seconds'] > 0) && !isset($MP3fileInfo['playtime_string'])) {
		$MP3fileInfo['playtime_string'] = PlaytimeString($MP3fileInfo['playtime_seconds']);
	}
	if (isset($MP3fileInfo['error']) && !$MP3fileInfo['error']) {
		unset($MP3fileInfo['error']);
	}
	if (isset($MP3fileInfo['fileformat']) && !$MP3fileInfo['fileformat']) {
		unset($MP3fileInfo['fileformat']);
	}

	unset($SourceArrayKey);
	// these entries appear in order of precedence
	if (isset($MP3fileInfo['asf'])) {
		$SourceArrayKey = $MP3fileInfo['asf'];
	} else if (isset($MP3fileInfo['ape'])) {
		$SourceArrayKey = $MP3fileInfo['ape'];
	} else if (isset($MP3fileInfo['id3']['id3v2'])) {
		$SourceArrayKey = $MP3fileInfo['id3']['id3v2'];
	} else if (isset($MP3fileInfo['id3']['id3v1'])) {
		$SourceArrayKey = $MP3fileInfo['id3']['id3v1'];
	} else if (isset($MP3fileInfo['ogg'])) {
		$SourceArrayKey = $MP3fileInfo['ogg'];
	} else if (isset($MP3fileInfo['vqf'])) {
		$SourceArrayKey = $MP3fileInfo['vqf'];
	} else if (isset($MP3fileInfo['RIFF'])) {
		$SourceArrayKey = $MP3fileInfo['RIFF'];
	} else if (isset($MP3fileInfo['quicktime'])) {
		$SourceArrayKey = $MP3fileInfo['quicktime'];
	}
	if (isset($SourceArrayKey)) {
		$handyaccesskeystocopy = array('title', 'artist', 'album', 'year', 'genre', 'comment', 'track');
		foreach ($handyaccesskeystocopy as $keytocopy) {
			if (isset($SourceArrayKey["$keytocopy"])) {
				$MP3fileInfo["$keytocopy"] = $SourceArrayKey["$keytocopy"];
			}
		}
	}
	if (isset($MP3fileInfo['track'])) {
		$MP3fileInfo['track'] = (int) $MP3fileInfo['track'];
	}

	if (isset($localfilepointer) && is_resource($localfilepointer) && (get_resource_type($localfilepointer) == 'file')) {
		fclose($localfilepointer);
	}
	if (isset($localfilepointer)) {
		unset($localfilepointer);
	}
 	return $MP3fileInfo;
}

function ParseAsThisFormat($format, $assumedFormat, $allowedFormats, $formattest) {
	$FormatTestStrings['zip']  = 'PK';
	$FormatTestStrings['ogg']  = 'OggS';
	$FormatTestStrings['riff'] = 'RIFF';
	$FormatTestStrings['sdss'] = 'SDSS'; // simply a renamed RIFF-WAVE format, identical except for the 1st 4 chars, used by SmartSound QuickTracks (www.smartsound.com)
	$FormatTestStrings['mpeg'] = chr(0x00).chr(0x00).chr(0x01).chr(0xBA);
	$FormatTestStrings['midi'] = 'MThd';
	$FormatTestStrings['asf']  = chr(0x30).chr(0x26).chr(0xB2).chr(0x75).chr(0x8E).chr(0x66).chr(0xCF).chr(0x11).chr(0xA6).chr(0xD9).chr(0x00).chr(0xAA).chr(0x00).chr(0x62).chr(0xCE).chr(0x6C);
	$FormatTestStrings['mac']  = 'MAC ';
	$FormatTestStrings['mpc']  = 'MP+';
	$FormatTestStrings['bmp']  = 'BM';
	$FormatTestStrings['gif']  = 'GIF';
	$FormatTestStrings['jpg']  = chr(0xFF).chr(0xD8).chr(0xFF);
	$FormatTestStrings['png']  = chr(0x89).chr(0x50).chr(0x4E).chr(0x47).chr(0x0D).chr(0x0A).chr(0x1A).chr(0x0A);
	$FormatTestStrings['real'] = '.RMF';
	$FormatTestStrings['flac'] = 'fLaC';
	$FormatTestStrings['vqf']  = 'TWIN';
	$FormatTestStrings['aac']  = 'ADIF';

	if (in_array($format, $allowedFormats) && (($assumedFormat == $format) || (isset($FormatTestStrings["$format"]) && (substr($formattest, 0, strlen($FormatTestStrings["$format"])) == $FormatTestStrings["$format"]) && ($assumedFormat == '')))) {
		return TRUE;
	}
	if ($format == 'quicktime') {
		switch (substr($formattest, 4, 4)) {
			case 'cmov':
			case 'free':
			case 'mdat':
			case 'moov':
			case 'pnot':
			case 'skip':
			case 'wide':
				return TRUE;
				break;

			default:
				// not a recognized quicktime atom, disregard
				break;
		}
	}
	return FALSE;
}

?>