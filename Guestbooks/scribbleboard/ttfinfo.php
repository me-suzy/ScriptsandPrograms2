<?
    // http://promoxy.mirrors.phpclasses.org/browse/package/2144.html
    // TTF Information Class - Freeware from PHPclasses by ZaraWebFX.
    // Cleaned up, stripped down, and modified for ScribbleBoard by Zabadab,
    // December 2005.
    //
    // Converted from a class to a simple include since this code just doesn't
    // deserve its own class. I'm guessing the author transformed it into a
    // class to get PHPclasses to host it. (PHPclasses' forced logins suck btw.)
	function dec2hex($dec){
		$hex=dechex($dec);
		return( str_repeat('0',2-strlen($hex)) . strtoupper($hex) );
	}
    function get_friendly_ttf_name($ttf_filename) {
		$text = file_get_contents($ttf_filename) or Error('Unable to open font file &quot;'. htmlspecialchars(ttf_filename) .'&quot; for reading.');
        $number_of_tabs = dec2hex(ord($text[4])) . dec2hex(ord($text[5]));
        for ($i=0;$i<hexdec($number_of_tabs);$i++){
			$tag = $text[12+$i*16].$text[12+$i*16+1].$text[12+$i*16+2].$text[12+$i*16+3];
			if ($tag == 'name') {
				$offset_name_table_hex = dec2hex(ord($text[12+$i*16+8])) . dec2hex(ord($text[12+$i*16+8+1])) . dec2hex(ord($text[12+$i*16+8+2])) . dec2hex(ord($text[12+$i*16+8+3]));
				$offset_name_table_dec = hexdec($offset_name_table_hex);
				$offset_storage_hex = dec2hex(ord($text[$offset_name_table_dec+4])) . dec2hex(ord($text[$offset_name_table_dec+5]));
				$offset_storage_dec = hexdec($offset_storage_hex);
				$number_name_records_hex = dec2hex(ord($text[$offset_name_table_dec+2])) . dec2hex(ord($text[$offset_name_table_dec+3]));
				$number_name_records_dec = hexdec($number_name_records_hex);
				break;
			}
		}
        $storage_dec = $offset_storage_dec
 + $offset_name_table_dec;
		$storage_hex = strtoupper(dechex($storage_dec));
        $fullfontname = '';
		for ($j=0;$j<$number_name_records_dec;$j++){
			$platform_id_hex = dec2hex(ord($text[$offset_name_table_dec+6+$j*12+0])) . dec2hex(ord($text[$offset_name_table_dec+6+$j*12+1]));
			$platform_id_dec = hexdec($platform_id_hex);
			$name_id_hex = dec2hex(ord($text[$offset_name_table_dec+6+$j*12+6])) . dec2hex(ord($text[$offset_name_table_dec+6+$j*12+7]));
			$name_id_dec = hexdec($name_id_hex);
			$string_length_hex = dec2hex(ord($text[$offset_name_table_dec+6+$j*12+8])) . dec2hex(ord($text[$offset_name_table_dec+6+$j*12+9]));
			$string_length_dec = hexdec($string_length_hex);
			$string_offset_hex = dec2hex(ord($text[$offset_name_table_dec+6+$j*12+10])) . dec2hex(ord($text[$offset_name_table_dec+6+$j*12+11]));
			$string_offset_dec = hexdec($string_offset_hex);
			if ($name_id_dec == 4 && $fullfontname == '') {
				for($l=0;$l<$string_length_dec;$l++){
					$fullfontname .= $text[$storage_dec+$string_offset_dec+$l];
				}
			}
			if ($fullfontname != '') {
				break;
			}
		}
		return $fullfontname;
	}
?>
