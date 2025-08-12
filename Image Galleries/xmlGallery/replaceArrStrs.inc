<?

// replace strings within an array with a new string
function replaceArrStrs($array, $beginMarker, $endMarker, $replaceMarkers, $replacementString) {
	// get the number of strings in the array
	$noStrings = count($array);

	// for each string
	for ($i = 0; $i < $noStrings; $i++) {
		// if this string matches the end marker
		if (ereg($endMarker, $array[$i])) {
			// if replaceMarkers is on, remove this string
			if ($replaceMarkers) {
				unset($array[$i]);
			}

			// stop scanning the strings
			break;
		}

		// if remove is on, remove this string
		if ($remove) {
			unset($array[$i]);
		}

		// if nextString is on
		if ($nextString) {
			// replace this string
			$array[$i] = $replacementString;

			// turn off nextString
			unset($nextString);

			// start removing strings
			$remove = 1;
		}

		// if this string matches the begin marker
		if (ereg($beginMarker, $array[$i])) {
			// if replaceMarkers is on
			if ($replaceMarkers) {
				// replace this string
				$array[$i] = $replacementString;

				// start removing strings
				$remove = 1;
			}

			// otherwise, replace the next string
			else {
				$nextString = 1;
			}
		}

		// if this is the last string and remove is still on, return an error msg
		if ($i == ($noStrings - 1) && $remove) {
			return "<b>Error</b>: End marker not found.";
		}
	}

	// sort the modified array by key value
	ksort($array);

	// return the modified array
	return $array;
}

?>