<?

// select text from a file
function selectText($file, $beginMarker, $endMarker, $includeMarkers) {
	// read lines from file into an array
	$lines = file($file);

	// for each line
	for ($i = 0; $i < count($lines); $i++) {
		// if this line matches the end marker
		if (ereg($endMarker, $lines[$i])) {
			// if includeMarkers is on, include this line in the selection
			if ($includeMarkers) {
				$text .= $lines[$i];
			}

			// stop reading lines
			break;
		}

		// if read is on, include this line in the selection
		if ($read) {
			$text .= $lines[$i];
		}

		// if this line matches the begin marker
		if (ereg($beginMarker, $lines[$i])) {
			// if includeMarkers is on, include this line in the selection
			if ($includeMarkers) {
				$text .= $lines[$i];
			}

			// start reading lines
			$read = 1;
		}

		// if this is the last line and read is still on, return an error msg
		if ($i == (count($lines) - 1) && $read) {
			return "<b>Error</b>: End marker not found.";
		}
	}

	// return the text selection
	return $text;
}

?>