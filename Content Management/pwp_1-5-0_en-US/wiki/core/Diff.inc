<?php

/**
 * A diff over two multi line text strings. Shows new lines and removed lines.
 * A change in a line will result in a new/removed combination.
 * The report consists of formatted HTML.
 * Since PWP 1.5.0
 *
 * @status
 * @author Lars Ackermann
 * $Id: $
 */
class Diff {


	/**
	 * Expects two multi line strings. "\n" acts as line
	 * separator. Returns a HTML formatted report reference.
	 * $isForEdit = FALSE : Prepend each new or removed line with an indicator
	 * $isForEdit = TRUE  : Leave lines unchanged, indsert comments above and
	 *                      below new/removed sections. Suitable for edit boxes.
	 */
	function diffStrings( &$new, &$old, $isForEdit = FALSE ) {
		$arrayNew = explode("\n", $new);
		$arrayOld = explode("\n", $old);

		$maxNew = sizeof( $arrayNew );
		$maxOld = sizeof( $arrayOld );

		$lineNewCurrentMatch    = 0;
		$lineOldCurrentMatch    = 0;
		$res       = '';

		//cycle through both arrays until one end is reached
		//the other array gets completed in following "for" loops
		while(($lineNewCurrentMatch < $maxNew) and ($lineOldCurrentMatch < $maxOld)) {

			//memorize the following lines of the last match (or 0 at the beginning)
			$lineNewPreviousMatch = $lineNewCurrentMatch;
			$lineOldPreviousMatch = $lineOldCurrentMatch;

			//find the next matching matching line A vs. B and B vs. A
			list($newVsOldLine1, $newVsOldLine2, $lengthNewVsOld) = Diff::_getNextMatch($arrayNew, $arrayOld, $lineNewCurrentMatch, $lineOldCurrentMatch);
			list($oldVsNewLine1, $oldVsNewLine2, $lengthOldVsNew) = Diff::_getNextMatch($arrayOld, $arrayNew, $lineOldCurrentMatch, $lineNewCurrentMatch);

			//longest match wins; in case of equal length
			//smallest pair (sum of line numbers) wins
			if ($lengthNewVsOld > $lengthOldVsNew) { //new wins because of length
				$lineNewCurrentMatch   = $newVsOldLine1;
				$lineOldCurrentMatch   = $newVsOldLine2;
			} elseif ($lengthNewVsOld < $lengthOldVsNew) { //old wins because of length
				$lineOldCurrentMatch   = $oldVsNewLine1;
				$lineNewCurrentMatch   = $oldVsNewLine2;
			} else { //decide on line distance
				if ( ($newVsOldLine1+$newVsOldLine2) < ($oldVsNewLine1+$oldVsNewLine2) ) {
					$lineNewCurrentMatch   = $newVsOldLine1;
					$lineOldCurrentMatch   = $newVsOldLine2;
				} else {
					$lineOldCurrentMatch   = $oldVsNewLine1;
					$lineNewCurrentMatch   = $oldVsNewLine2;
				}
			}

			//found next match or no match at all; other lines in between
			// in the "new" array were added, lines in the "old" array were removed
			if ($isForEdit) {
				if ($lineNewPreviousMatch < $lineNewCurrentMatch) {
					$res .= "!-- _____+ONLY IN MY VERSION BEGIN+_____ --!\n";
					for ($i=$lineNewPreviousMatch; $i<$lineNewCurrentMatch; $i++) {
						$res .= "$arrayNew[$i]\n";
					}
					$res .= "!-- _____|ONLY IN MY VERSION END|_______ --!\n";
				} elseif ($lineOldPreviousMatch < $lineOldCurrentMatch) {
					$res .= "!-- _____-NOT IN MY VERSION BEGIN-_____ --!\n";
					for ($i=$lineOldPreviousMatch; $i<$lineOldCurrentMatch; $i++) {
						$res .= "$arrayOld[$i]\n";
					}
					$res .= "!-- _____|NOT IN MY VERSION END|_______ --!\n";
				}
			} else {
				for ($i=$lineNewPreviousMatch; $i<$lineNewCurrentMatch; $i++) {
					$res .= Diff::_formatLine( 1, $arrayNew[$i] );
				}
				for ($i=$lineOldPreviousMatch; $i<$lineOldCurrentMatch; $i++) {
					$res .= Diff::_formatLine( -1, $arrayOld[$i] );
				}
			}
			//store the matching line pair in the result
			if ($lineNewCurrentMatch < $maxNew) {
				if ($isForEdit) {
					$res .= "$arrayNew[$lineNewCurrentMatch]\n";
				} else {
					$res .= Diff::_formatLine( 0, $arrayNew[$lineNewCurrentMatch] );
				}
			}

			//increase the counters
			++$lineNewCurrentMatch;
			++$lineOldCurrentMatch;
		}

		//add the remaining lines if any; there are no matches so the lines
		//have benn added or removed; there is no "equal" any more
		if ($isForEdit) {
			if ($lineNewCurrentMatch<$maxNew) {
				$res .= "!-- _____+ONLY IN MY VERSION END+_____ --!\n";
				for ($i=$lineNewCurrentMatch; $i<$maxNew; $i++) {
					$res .= "$arrayNew[$i]\n";
				}
			} elseif ($lineOldCurrentMatch<$maxOld) {
				$res .= "!-- _____-NOT IN MY VERSION UNTIL END-_____ --!\n";
				for ($i=$lineOldCurrentMatch; $i<$maxOld; $i++) {
					$res .= "$arrayOld[$i]\n";
				}
			}
			return $res;
		} else {
			for ($i=$lineNewCurrentMatch; $i<$maxNew; $i++) {
				$res .= Diff::_formatLine( 1, $arrayNew[$i] );
			}
			for ($i=$lineOldCurrentMatch; $i<$maxOld; $i++) {
				$res .= Diff::_formatLine( -1, $arrayOld[$i] );
			}
			return "<pre>$res</pre>\n";
		}
	}

	/**
	 * Search for the first line of array1 that matches array2.
	 * Start in line1 resp. line2 in the arrays.
	 * Returns the matching line or sizeof( array1 ) along with
	 * the length of the matching lines -1, 0, N.
	 */
	function _getNextMatch( &$array1, &$array2, $line1, $line2 ) {

		$max1 = sizeof( $array1 );
		$max2 = sizeof( $array2 );
		$l2o  = $line2;

		$continue  = true;

		while( ($line1 < $max1) and $continue) {
			while( ($line2 < $max2) and $continue ) {
				if ($array1[$line1] == $array2[$line2]) {
					$continue = false;
				} else {
					++$line2;
				}
			}
			if ($continue) {
				++$line1;
				$line2 = $l2o;
			}
		}

		//calculate line length, out of array range = -1 (end of loop situation)
		//use array1 as array2 gets resetted within the outer while loop
		if ($line1 >= $max1) {
			$length = -1;
		} else {
			$length = strlen($array1[$line1]);
		}
		return array($line1, $line2, $length);
	}

	/**
	 * Callback function to format the difference lines with your 'style'.
	 */
	function _formatLine( $stat, &$value ) {
		global $gConfig;

		//word wrap and indentation
		$value = wordwrap( $value, $gConfig->mProp['EditColumns'] -3 );
		$value = str_replace("\n", "\n  ", $value);

		switch ( $stat ) {
			case 0:
				return "  $value\n";
				break;
			case 1:
				return "<strong>+ $value</strong>\n";
				break;
			case -1:
				return "<em class='gray'>- $value</em>\n";
				break;
			default:
				return "<strong class='error'>?</strong> $value\n";
		}
	}
}
?>