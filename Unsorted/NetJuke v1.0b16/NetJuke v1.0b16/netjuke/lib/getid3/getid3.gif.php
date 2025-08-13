<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.gif.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getGIFHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat'] = 'gif';

	rewind($fd);
	$GIFheader = fread($fd, 13);
	$offset = 0;

	$MP3fileInfo['gif']['header']['raw']['identifier']            =                  substr($GIFheader, $offset, 3);
	$offset += 3;
	$MP3fileInfo['gif']['header']['raw']['version']               =                  substr($GIFheader, $offset, 3);
	$offset += 3;
	$MP3fileInfo['gif']['header']['raw']['width']                 = LittleEndian2Int(substr($GIFheader, $offset, 2));
	$offset += 2;
	$MP3fileInfo['gif']['header']['raw']['height']                = LittleEndian2Int(substr($GIFheader, $offset, 2));
	$offset += 2;
	$MP3fileInfo['gif']['header']['raw']['flags']                 = LittleEndian2Int(substr($GIFheader, $offset, 1));
	$offset += 1;
	$MP3fileInfo['gif']['header']['raw']['bg_color_index']        = LittleEndian2Int(substr($GIFheader, $offset, 1));
	$offset += 1;
	$MP3fileInfo['gif']['header']['raw']['aspect_ratio']          = LittleEndian2Int(substr($GIFheader, $offset, 1));
	$offset += 1;

	$MP3fileInfo['resolution_x']                                  = $MP3fileInfo['gif']['header']['raw']['width'];
	$MP3fileInfo['resolution_y']                                  = $MP3fileInfo['gif']['header']['raw']['height'];
	$MP3fileInfo['gif']['version']                                = $MP3fileInfo['gif']['header']['raw']['version'];
	$MP3fileInfo['gif']['header']['flags']['global_color_table']  = (bool) ($MP3fileInfo['gif']['header']['raw']['flags'] & 0x80);
	if ($MP3fileInfo['gif']['header']['raw']['flags'] & 0x80) {
		// Number of bits per primary color available to the original image, minus 1
		$MP3fileInfo['gif']['header']['flags']['bits_per_pixel']  = 3 * ((($MP3fileInfo['gif']['header']['raw']['flags'] & 0x70) >> 4) + 1);
	} else {
		$MP3fileInfo['gif']['header']['flags']['bits_per_pixel']  = 0;
	}
	$MP3fileInfo['gif']['header']['flags']['global_color_sorted'] = (bool) ($MP3fileInfo['gif']['header']['raw']['flags'] & 0x40);
	if ($MP3fileInfo['gif']['header']['flags']['global_color_table']) {
		// the number of bytes contained in the Global Color Table. To determine that
		// actual size of the color table, raise 2 to [the value of the field + 1]
		$MP3fileInfo['gif']['header']['flags']['global_color_size'] = pow(2, ($MP3fileInfo['gif']['header']['raw']['flags'] & 0x07) + 1);
	} else {
		$MP3fileInfo['gif']['header']['flags']['global_color_size'] = 0;
	}
	if ($MP3fileInfo['gif']['header']['raw']['aspect_ratio'] != 0) {
		// Aspect Ratio = (Pixel Aspect Ratio + 15) / 64
		$MP3fileInfo['gif']['header']['aspect_ratio']             = ($MP3fileInfo['gif']['header']['raw']['aspect_ratio'] + 15) / 64;
	}

	if ($MP3fileInfo['gif']['header']['flags']['global_color_table']) {
		$GIFcolorTable = fread($fd, 3 * $MP3fileInfo['gif']['header']['flags']['global_color_size']);
		$offset = 0;
		for ($i = 0; $i < $MP3fileInfo['gif']['header']['flags']['global_color_size']; $i++) {
			//$MP3fileInfo['gif']['global_color_table']['red']["$i"]   = LittleEndian2Int(substr($GIFcolorTable, $offset++, 1));
			//$MP3fileInfo['gif']['global_color_table']['green']["$i"] = LittleEndian2Int(substr($GIFcolorTable, $offset++, 1));
			//$MP3fileInfo['gif']['global_color_table']['blue']["$i"]  = LittleEndian2Int(substr($GIFcolorTable, $offset++, 1));
			$red   = LittleEndian2Int(substr($GIFcolorTable, $offset++, 1));
			$green = LittleEndian2Int(substr($GIFcolorTable, $offset++, 1));
			$blue  = LittleEndian2Int(substr($GIFcolorTable, $offset++, 1));
			$MP3fileInfo['gif']['global_color_table']["$i"] = (($red << 16) | ($green << 8) | ($blue));
		}
	}

	return TRUE;
}

?>