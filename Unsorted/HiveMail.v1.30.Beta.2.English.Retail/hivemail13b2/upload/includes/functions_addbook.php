<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: functions_addbook.php,v $ - $Revision: 1.3 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// Takes the $contact array from HiveMail and returns a vCard for that contact
function contact_to_vcard($contact) {
	global $_phonetypes;

	$contact['emailinfo'] = unserialize($contact['emailinfo']);
	$contact['nameinfo'] = unserialize($contact['nameinfo']);
	$contact['addressinfo'] = unserialize($contact['addressinfo']);
	$contact['phoneinfo'] = unserialize($contact['phoneinfo']);
	extract($contact);

	$vCard =	"begin: vcard\n".
				iif($name != $email, "fn: $name\n").
				iif(($n = "$nameinfo[first];$nameinfo[last];$nameinfo[middle];$nameinfo[prefix]") != ';;;', "n: $n\n").
				iif(!empty($nameinfo['nickname']), "nickname: $nameinfo[nickname]\n").
				iif($birthday != '0000-00-00', "bday: $birthday\n").
				iif($timezone > -13, "tz: ".($timezone < 0 ? '-' : '+').floor(abs($timezone)).':'.((abs($timezone) - floor(abs($timezone))) * 60)."\n").
				iif(!empty($webpage), "url: $webpage\n").
				iif(!empty($notes), "note: ".str_replace("\n", '\n', $notes)."\n").
				"email;TYPE=internet,pref: $email\n";
	foreach ($emailinfo as $email) {
		$vCard .= "email;TYPE=internet: $email\n";
	}
	foreach ($addressinfo as $address) {
		$vCard .= "adr".iif($address['default'], ';TYPE=pref').": ;;".str_replace("\n", '\n', $address['street']).";$address[city];$address[state];$address[zip];$address[country]\n";
	}
	foreach ($phoneinfo as $phone) {	
		$phone['types'] = array();
		foreach ($_phonetypes as $typebit => $typename) {
			if ($phone['type'] & $typebit) {
				$phone['types'][] = $typename;
			}
		}
		if ($phone['default']) {
			$phone['types'][] = 'pref';
		}
		$vCard .= "tel".iif(!empty($phone['types']), ';TYPE='.implode(',', $phone['types'])).": $phone[phone]\n";
	}
	$vCard .=	'end: vcard';

	return $vCard;
}

// ############################################################################
// Takes a vCard and returns the $contact array for HiveMail
function vcard_to_contact($vCard) {
	global $_phonetypes;
	$phonetypes = array_flip($_phonetypes);

	$vCard = preg_replace("#\r?\n#", "\n", $vCard);
	$vCard = preg_replace("#\n\s+#", '', $vCard);
	$vCard = explode("\n", $vCard);

	// Explode each line to type and value
	foreach ($vCard as $key => $line) {
		unset($vCard[$key]);
		list($type, $value) = explode(':', $line, 2);
		list($mainType, $typeBits) = explode(';', strtolower($type), 2);

		// Skip unnecessary types
		switch ($mainType) {
			case 'fn':
			case 'n':
			case 'nickname':
			case 'bday':
			case 'tz':
			case 'url':
			case 'email':
			case 'tel':
			case 'adr':
			case 'note':
				break;
			default:
				continue 2;
		}

		$subType = array();
		if ($typeBits !== null) {
			$typeBits = explode(';', $typeBits);

			if (count($typeBits) > 1) {
				$subType = array();
				foreach ($typeBits as $subTypeBit) {
					list($subTypeType, $subTypeValue) = explode('=', $subTypeBit);
					$subTypeValueBits = explode(',', $subTypeValue);
					if (count($subTypeValueBits) == 1) {
						$subTypeValueBits = $subTypeValueBits[0];
					}
					if (isset($subType["$subTypeType"])) {
						$subType["$subTypeType"] = array($subType["$subTypeType"], $subTypeValueBits);
					} else {
						$subType["$subTypeType"] = $subTypeValueBits;
					}
				}
			} elseif (count($typeBits) > 0) {
				list($subTypeType, $subTypeValue) = explode('=', $typeBits[0]);
				$subTypeValueBits = explode(',', $subTypeValue);
				if (count($subTypeValueBits) == 1) {
					$subTypeValueBits = $subTypeValueBits[0];
				}
				$subType = array($subTypeType => $subTypeValueBits);
			}
		}

		$infoArray = array('subtypes' => $subType, 'value' => trim($value));

		if (!isset($vCard["$mainType"])) {
			$vCard["$mainType"] = array();
		}
		$vCard["$mainType"][] = $infoArray;
	}

	// Process each line
	$vCardInfo = array(
		'email' => '',
		'emailinfo' => array(),
		'name' => '',
		'nameinfo' => array(),
		'birthday' => '0000-00-00',
		'timezone' => '-13',
		'webpage' => '',
		'notes' => '',
		'addressinfo' => array(),
		'phoneinfo' => array(),
	);
	foreach ($vCard as $type => $mainvalues) {
		foreach ($mainvalues as $mainvalue) {
			extract($mainvalue);
			$value = str_replace('\n', "\n", trim($value));
			switch ($type) {
				case 'fn':
					$vCardInfo['name'] = $value;
					break;

				case 'n':
					list($vCardInfo['nameinfo']['last'],
						 $vCardInfo['nameinfo']['first'],
						 $vCardInfo['nameinfo']['middle'],
						 $vCardInfo['nameinfo']['prefix']) = explode_bs(';', $value);
					break;

				case 'nickname':
					$vCardInfo['nameinfo']['nickname'] = $value;
					break;

				case 'bday':
					$vCardInfo['birthday'] = parse_iso_datetime($value, true);
					break;

				case 'tz':
					$vCardInfo['timezone'] = parse_iso_timezone($value);
					break;

				case 'url':
					$vCardInfo['webpage'] = $value;
					break;

				case 'email':
					if (empty($vCardInfo['email']) and isset($subtypes['type']) and ($subtypes['type'] == 'pref' or array_contains('pref', $subtypes['type']))) {
						$vCardInfo['email'] = $value;
					} else {
						$vCardInfo['emailinfo'][] = $value;
					}
					break;

				case 'tel':
					$default = false;
					if (!is_array($subtypes['type'])) {
						$subtypes['type'] = array($subtypes['type']);
					}
					$phonetype = 0;
					foreach ($subtypes['type'] as $subtype) {
						if ($subtype == 'pref') {
							$default = true;
						} elseif (isset($phonetypes["$subtype"])) {
							$phonetype += $phonetypes["$subtype"];
						}
					}
					$vCardInfo['phoneinfo'][] = array('default' => $default, 'phone' => $value, 'type' => $phonetype);
					break;

				case 'adr':
					if (isset($subtypes['type']) and ($subtypes['type'] == 'pref' or array_contains('pref', $subtypes['type']))) {
						$default = true;
					} else {
						$default = false;
					}
					$thisItem = &$vCardInfo['addressinfo'][];
					$thisItem['default'] = $default;
					list(,, $thisItem['street'], $thisItem['city'], $thisItem['state'], $thisItem['zip'], $thisItem['country']) = explode_bs(';', $value);
					break;

				case 'note':
					$vCardInfo['notes'] = $value;
					break;
			}
		}
	}

	return $vCardInfo;
}

// ############################################################################
// Explodes $value by $delim, but allows escaping of the $delim character
function explode_bs($delim, $value) {
	$valueBits = explode($delim, $value);
	for ($i = 0; $i < count($valueBits); $i++) {
		if (substr($valueBits[$i], -1) == '\\') {
			$valueBits[$i] .= ';'.$valueBits[$i+1];
			unset($valueBits[$i+1]);
			$valueBits = array_values($valueBits);
		}
	}
	return $valueBits;
}

// ############################################################################
// Parses any given ISO timezone into readable format
function parse_iso_timezone($tzone) {
	$zones = array(
		'Z'  => '',
		'A'  => '+01:00', 'B'  => '+02:00', 'C'  => '+03:00', 'C*' => '+03:30',
		'D'  => '+04:00', 'D*' => '+04:30', 'E'  => '+05:00', 'E*' => '+05:30',
		'F'  => '+06:00', 'F*' => '+06:30', 'G'  => '+07:00', 'H'  => '+08:00',
		'I'  => '+09:00', 'I*' => '+09:30', 'K'  => '+10:00', 'K*' => '+10:30',
		'L'  => '+11:00', 'L*' => '+11:30', 'M'  => '+12:00', 'M*' => '+13:00',
		'M±' => '+14:00', 'N'  => '-01:00', 'O'  => '-02:00', 'P'  => '-03:00',
		'P*' => '-03:30', 'Q'  => '-04:00', 'R'  => '-05:00', 'S'  => '-06:00',
		'T'  => '-07:00', 'U'  => '-08:00', 'U*' => '-08:30', 'V'  => '-09:00',
		'V*' => '-09:30', 'W'  => '-10:00', 'X'  => '-11:00', 'Y'  => '-12:00',
	);

	if (isset($zones["$tzone"])) {
		$tzone = $zones["$tzone"];
	} else {
		if (strlen($tzone) < 4) {
			$tzone .= ':00';
		}
		if (strpos($tzone, ':') == false) {
			$tzone = substr($tzone, 0, -2).':'.substr($tzone, -2);
		}
	}

	return $tzone;
}

// ############################################################################
// Parses any given ISO date/time stamp and returns array of date and time
function parse_iso_datetime($value, $dateonly = false) {
	list($date, $ttime) = preg_split('#[T ]#', strtoupper($value), 2);

	if (preg_match('#(\d{4})(-?)(\d{2})\2(\d{2})#', $date, $datebits)) {
	} elseif (preg_match('#(\d{2})(-?)(\d{2})\2(\d{2})#', $date, $datebits)) {
	} elseif (preg_match('#(\d{4})(-?)(\d{2})#', $date, $datebits)) {
	} elseif (preg_match('#(\d{4})#', $date, $datebits)) {
	}
	if ($datebits[1] < 1900) {
		$datebits[1] += 1900;
		if ($datebits[1] < 1930) {
			$datebits[1] += 100;
		}
	}
	if ($datebits[3] < 1) {
		$datebits[3] = 1;
	}
	if ($datebits[4] < 1) {
		$datebits[4] = 1;
	}

	if (!empty($ttime)) {
		list($time, $tzone1, $tzone2) = preg_split('#([A-Z+-])#', $ttime, 2, PREG_SPLIT_DELIM_CAPTURE);

		$tzone = $tzone1.$tzone2;
		if (!empty($tzone)) {
			$tzone = parse_iso_timezone($tzone);
		} else {
			$tzone = '';
		}

		$time = preg_replace('#\.\d+$#', '', $time); // lose second fractions
		if (preg_match('#(\d{2})(:?)(\d{2})\2(\d{2})#', $time, $timebits)) {
		} elseif (preg_match('#(\d{2})(:?)(\d{2})#', $time, $timebits)) {
		} elseif (preg_match('#(\d{2})#', $time, $timebits)) {
		} elseif (preg_match('##', $time, $timebits)) {
		}
		if ($timebits[3] < 0) {
			$timebits[3] = 0;
		}
		if ($timebits[4] < 0) {
			$timebits[4] = 0;
		}
		
		if (!empty($tzone)) {
			$sign = substr($tzone, 0, 1);
			$hours = substr($tzone, 1, 2);
			$minutes = substr($tzone, 3, 2);
		} else{
			$sign = '-';
			$hours = $minutes = 0;
		}

		if ($sign == '-') {
			$timebits[3] += $minutes;
			if ($timebits[3] > 59) {
				$timebits[3] -= 60;
				$timebits[1]++;
			}
			$timebits[1] += $hours;
			if ($timebits[1] > 24) {
				$timebits[1] -= 24;
				$datebits[4]++;
				if (!checkdate($datebits[3], $datebits[4], $datebits[1])) {
					$datebits[3]++;
					if ($datebits[3] > 12) {
						$datebits[3] = 1;
						$datebits[1]++;
					}
					$datebits[4] = 1;
				}
			}
		} else {
			$timebits[3] -= $minutes;
			if ($timebits[3] < 0) {
				$timebits[3] += 60;
				$timebits[1]--;
			}
			$timebits[1] -= $hours;
			if ($timebits[1] < 0) {
				$timebits[1] += 24;
				$datebits[4]--;
				if (!checkdate($datebits[3], $datebits[4], $datebits[1])) {
					$datebits[3]--;
					if ($datebits[3] < 1) {
						$datebits[3] = 12;
						$datebits[1]--;
					}
					$datebits[4] = 32;
					do {
						$datebits[4]--;
					} while (!checkdate($datebits[3], $datebits[4], $datebits[1]));
				}
			}
		}

		$time = str_pad($timebits[1], 2, '0', STR_PAD_LEFT).':'.str_pad($timebits[3], 2, '0', STR_PAD_LEFT).':'.str_pad($timebits[4], 2, '0', STR_PAD_LEFT);
	} else {
		$time = '';
	}
	$date = $datebits[1].'-'.str_pad($datebits[3], 2, '0', STR_PAD_LEFT).'-'.str_pad($datebits[4], 2, '0', STR_PAD_LEFT);

	if ($dateonly) {
		return $date;
	} else {
		return array('date' => $date, 'time' => $time);
	}
}

?>