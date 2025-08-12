<?PHP

//-- convert strange characters
function rieplees($rp){
	//$rp = ereg_replace(";", "&#59;", $rp);
$rp = ereg_replace('%', '&#37;', $rp);
$rp = ereg_replace('ü', '&uuml;', $rp);
$rp = ereg_replace('ï', '&iuml;', $rp);
$rp = ereg_replace('ë', '&euml;', $rp);
$rp = ereg_replace('à', '&agrave;', $rp);
$rp = ereg_replace('é', '&eacute;', $rp);
$rp = ereg_replace('è', '&egrave;', $rp);
$rp = ereg_replace('ç', '&ccedil;', $rp);
$rp = ereg_replace("'", "&#39;", $rp);
$rp = ereg_replace("", "&#8364;", $rp);
$rp = ereg_replace("ê", "&ecirc;", $rp);

$rp = ereg_replace('', '&#156;', $rp);
$rp = ereg_replace('À', '&#192;', $rp);
$rp = ereg_replace('Æ', '&#198;', $rp);
$rp = ereg_replace('Ì', '&#204;', $rp);
$rp = ereg_replace('Ò', '&#210;', $rp);
$rp = ereg_replace('ä', '&#228;', $rp);
$rp = ereg_replace('ö', '&#246;', $rp);
$rp = ereg_replace('Á', '&#193;', $rp);
$rp = ereg_replace('Ç', '&#199;', $rp);
$rp = ereg_replace('Í', '&#205;', $rp);
$rp = ereg_replace('Ó', '&#211;', $rp);
$rp = ereg_replace('Ù', '&#217;', $rp);
$rp = ereg_replace('ß', '&#223;', $rp);
$rp = ereg_replace('', '&#140;', $rp);
$rp = ereg_replace('Â', '&#194;', $rp);
$rp = ereg_replace('È', '&#200;', $rp);
$rp = ereg_replace('Î', '&#206;', $rp);
$rp = ereg_replace('Ô', '&#212;', $rp);
$rp = ereg_replace('Ú', '&#218;', $rp);
$rp = ereg_replace('æ', '&#230;', $rp);
$rp = ereg_replace('ì', '&#236;', $rp);
$rp = ereg_replace('ò', '&#242;', $rp);
$rp = ereg_replace('É', '&#201;', $rp);
$rp = ereg_replace('Ï', '&#207;', $rp);
$rp = ereg_replace('Û', '&#219;', $rp);
$rp = ereg_replace('á', '&#225;', $rp);
$rp = ereg_replace('í', '&#237;', $rp);
$rp = ereg_replace('ó', '&#243;', $rp);
$rp = ereg_replace('ù', '&#249;', $rp);
$rp = ereg_replace('Ä', '&#196;', $rp);
$rp = ereg_replace('Ê', '&#202;', $rp);
$rp = ereg_replace('Ö', '&#214;', $rp);
$rp = ereg_replace('Ü', '&#220;', $rp);
$rp = ereg_replace('â', '&#226;', $rp);
$rp = ereg_replace('î', '&#238;', $rp);
$rp = ereg_replace('ô', '&#244;', $rp);
$rp = ereg_replace('ú', '&#250;', $rp);
$rp = ereg_replace('Ë', '&#203;', $rp);
$rp = ereg_replace('û', '&#251;', $rp);

	return $rp;
}

function timestamp2datime($timestamp){
	$jaar = substr($timestamp, 0, 4);
	$maand = substr($timestamp, 4, 2);
	$dag = substr($timestamp, 6, 2);
	$uur = substr($timestamp, 8, 2);
	$min = substr($timestamp, 10, 2);		
	$datime .= "$maand/$dag/$jaar - {$uur}:$min";

	return $datime;
}

function timestamp2date($timestamp){
	$jaar = substr($timestamp, 0, 4);
	$maand = substr($timestamp, 4, 2);
	$dag = substr($timestamp, 6, 2);		
	$date .= "$maand/$dag/$jaar";

	return $date;
}



?>