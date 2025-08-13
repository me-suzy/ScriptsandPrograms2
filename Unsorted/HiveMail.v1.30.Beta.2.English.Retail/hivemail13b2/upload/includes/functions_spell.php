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
// | $RCSfile: functions_spell.php,v $ - $Revision: 1.6 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
@set_time_limit(0);

// ############################################################################
// Calculates the LCS between the two strings and return the number of similar chars
function spell_similar($A, $B) {
	$L = array();
	$m = strlen($A);
	$n = strlen($B);
	for ($i = $m; $i >= 0; $i--) {
		for ($j = $n; $j >= 0; $j--) {
			if ($i >= $m or $j >= $n) {
				$L[$i][$j] = 0;
			} elseif ($A[$i] == $B[$j]) {
				$L[$i][$j] = 1 + $L[$i+1][$j+1];
			} else {
				$L[$i][$j] = max($L[$i+1][$j], $L[$i][$j+1]);
			}
		}
	}
	return $L[0][0] / max($m, $n);
}

// ############################################################################
// Processes input for fields
function spell_process($text, $useHtml, $sendAfter) {
	global $DB_site, $hiveuser, $_meta_markers;

	$starttime = microdiff(STARTTIME);
	$intMaxSuggestions = 6;
	$showback = 6;
	$showforward = 6;
	$userDictionaryAvailable = false;

	$userWords = $realWords = $misspelledArray = $deadKeys = $tagKeys = $wordKeys = $sxValues = array();
	$jsOutput = '';
	//echo '<!-- ';
	if (!empty($text)) {
		// Operate on the HTML text so it's split correctly
		$strDictArray = preg_split("#\r?\n#", readfromfile('../dict/dictionaries/dict-large.txt'));
		echo "Words: ".(microdiff(STARTTIME) - $starttime)."<br />";
		$strDictArray = array_flip($strDictArray);
		echo "Flip: ".(microdiff(STARTTIME) - $starttime)."<br />";
		$originalString = preg_replace("#\r?\n#", "\n", $text);
		$words = preg_split('#([^a-z\'])#i', $originalString, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		echo "Split: ".(microdiff(STARTTIME) - $starttime)."<br />";
		$tagOpen = false;
		$realKey = $demoKey = 0;
		if ($useHtml) {
			while (list($key, $value) = each($words)) {
				if ($value == '<') {
					$tagOpen = true;
				} elseif ($value == '>' or $tagOpen) {
					$demoKey--;
					$words[$demoKey] .= array_extract($words, $key);
					$deadKeys[] = $key;
					if ($value == '>') {
						$tagKeys[] = $realKey;
						$realKey++;
						$demoKey = $key;
						$tagOpen = false;
					}
				} else {
					$realWords[$realKey] = $value;
					$realKey++;
				}
				$demoKey++;
			}
		} else {
			$realWords = $words;
		}
		$words = array_values($words);
		$wordCount = count($words);
		//print_R($realWords);
		echo "HTML: ".(microdiff(STARTTIME) - $starttime)."<br />";

		// Tracks original index of word in $words array
		$oi = 0;

		// Get user's dictionary
		$getUserWords = $DB_site->query("
			SELECT word
			FROM hive_word
			WHERE userid = $hiveuser[userid]
		");
		while ($userWord = $DB_site->fetch_array($getUserWords)) {
			$userWords[strtolower($userWord['word'])] = true;
		}
		echo "User: ".(microdiff(STARTTIME) - $starttime)."<br />";

		// Loop over each word in string
		$checks = 0;
		foreach ($realWords as $strWordKey => $strWordIn) {
			//if (in_array($strWordKey, $tagKeys)) {
				// This is an HTML tag or a word that was already checked this word
				// Increment original index and move along
				//continue;
			//}
			$oi = $strWordKey;

			// Remove invalid characters from the word
			$strWordOut = $strWordIn;
			$strWordOut = preg_replace('#[^a-z\']#i', '', $strWordOut);
			if (substr_count($strWordOut, "'") > 1) {
				$strWordOut = str_replace("'", '', $strWordOut);
			}

			// Remove 's at the end of the word
			if (substr($strWordOut, -2) == "'s") {
				$strWordOut = substr($strWordOut, 0, strlen($strWordOut) - 2);
			}
			$strWordOut = trim($strWordOut, "' \t\n\r\0\x0B");

			// Nothing left...
			if (empty($strWordOut)) {
				continue;
			}

			// Store the word's key
			$wordKeys[] = $oi;

			// Word need not have capitals
			$checkWord = strtolower($strWordOut);

			// Search main dictionary
			$spellOk = isset($strDictArray["$checkWord"]);

			// Check user's dictionary
			if (!$spellOk and !empty($userWords)) {
				$spellOk = isset($userWords["$checkWord"]);
			}

			// If word is spelled wrong, store in array
			if (!$spellOk) {
				// Calculate soundex/metaphone using PHP and create the misspelled array entry
				$sndx = metaphone($strWordOut);
				if (!array_contains($sndx, $sxValues)) {
					$sxValues[] = $sndx;
				}
				$misspelledArray[] = array(
					'word' => $strWordIn,
					'sndx' => $sndx,
					'possugs' => array(),
					'realsugs' => array(),
					'oi' => $oi,
				);
			}
		}
		$lastOi = $oi;
		echo "Spell: ".(microdiff(STARTTIME) - $starttime)."<br />";
		$wordKeysFlipped = array_flip($wordKeys);
		echo "Flip: ".(microdiff(STARTTIME) - $starttime)."<br />";

		// Total count of misspelled words in the array
		$misspelledWordCount = count($misspelledArray);
		if ($misspelledWordCount > 0) {
			// Execute query
			$strSuggArray = preg_split("#\r?\n#", readfromfile('../dict/dictionaries/dict-metaphone-sort.txt'));
			echo "Suggest: ".(microdiff(STARTTIME) - $starttime)."<br />";

			// JavaScript variables that will store all information
			$jsOutput .= "var msWc = $misspelledWordCount;\n";
			$jsOutput .= "var msMissWordAr = new Array($misspelledWordCount);\n";
			$jsOutput .= "var msOrigWordAr = new Array($wordCount);\n";
			$jsOutput .= "var lastOrigWord;\n";

			// Loop over array to get suggestions
			$doneSugsFor = array();
			for ($x = 0; $x < $misspelledWordCount; $x++) {
				$subAr3 = &$misspelledArray[$x];
				$intWordLen = strlen($subAr3['word']);
				$subArPoss3 = &$subAr3['possugs'];

				// Create context string
				$oi = $subAr3['oi'];
				$oiStart = $wordKeys[(($wordKeysFlipped[$oi] - $showback) >= 0) ? ($wordKeysFlipped[$oi] - $showback) : 0];
				$oiEnd = $wordKeys[(($wordKeysFlipped[$oi] + $showforward + 1) <= count($wordKeys) - 1) ? ($wordKeysFlipped[$oi] + $showforward + 1) : count($wordKeys) - 1];
				$context = '';
				if ($oi > $showback) {
					$context .= '...';
				}
				for (; $oiStart != $oiEnd + 1; $oiStart++) {
					if ($oiStart == $oi) {
						$context .= '<font color="red"><b>'.$words[$oi].'</b></font>';
					} else {
						$context .= $words[$oiStart];
					}
				}
				if ($oi < ($wordCount - 1 - $showforward)) {
					$context .= '...';
				}

				// Loop over similarities (possible suggestions), score and get top (real) suggestions
				// Check if we already did suggestions for this word and if so, load that
				$dblSimilarityArray = array();
				if (false and isset($doneSugsFor["$subAr3[word]"])) {
					$dblSimilarityArray = $misspelledArray[$doneSugsFor["$subAr3[word]"]]['possugs'];
				} else {
					// Binary sort to find word in dict array
					$low = $_meta_markers[substr($subAr3['sndx'], 0, 3)][0] - 1;
					$high = $_meta_markers[substr($subAr3['sndx'], 0, 3)][1] + 3;
					$foundSndx = false;
					//echo "Low: $low; High: $high;<br />";
					while ($low <= $high) {
						$mid = floor(($low + $high) / 2);
						$checks++;
						//$check = strcasecmp($subAr3['sndx'].'|', substr($strSuggArray[$mid], 0, strlen($subAr3['sndx']) + 1));
						$check = strcasecmp($subAr3['sndx'], preg_replace('#^([^|]+)\|.*$#', '$1', $strSuggArray[$mid]));
						if ($check == 0) {
							$foundSndx = true;
							break;
						} elseif ($check < 0) {
							$high = $mid - 1;
						} else {
							$low = $mid + 1;
						}
					}
					if ($foundSndx) {
						$subArPoss3 = explode('|', substr($strSuggArray[$mid], strlen($subAr3['sndx']) + 1));
					} else {
						$subArPoss3 = array();
					}
					$subCount = count($subArPoss3);

					if ($subCount <= 1) {
						$dblSimilarityArray = $subArPoss3;
					} else {
						for ($y = 0; $y < $subCount; $y++) {
							$strSimilarWord = $subArPoss3[$y];
							$intSimilarWordLen = strlen($strSimilarWord);
							$maxBonus = 3;
							$maxScore = ($intWordLen * 2) + $maxBonus;
							$LCS = spell_similar($subAr3['word'], $strSimilarWord);
							$score = ($maxBonus - abs($intWordLen - $intSimilarWordLen) + $LCS * $maxBonus) / ($maxScore);
							$dblSimilarity = round($score, 10);
							while (array_key_exists("$dblSimilarity", $dblSimilarityArray)) {
								$dblSimilarity += .0000000001;
							}
							$dblSimilarityArray["$dblSimilarity"] = $strSimilarWord;
						}
						$subArPoss3 = $dblSimilarityArray;

						// Sort array by key value (score)
						krsort($dblSimilarityArray);
						reset($dblSimilarityArray);
					}
				}

				// Perpare JavaScript variables
				$jsOutput .= "msMissWordAr[$x] = new Array(4);\n";
				$jsOutput .= "msMissWordAr[$x][0] = '".str_replace("\n", '', addslashes($subAr3['word']))."';\n";
				$jsOutput .= "msMissWordAr[$x][1] = $oi;\n";
				$jsOutput .= "msMissWordAr[$x][2] = '".str_replace(array('\n', "\n"), '<br />\n', trim(addslashes($context)))."';\n";

				// Build suggestions array
				$sugCount = iif($intMaxSuggestions < count($dblSimilarityArray), $intMaxSuggestions, count($dblSimilarityArray));
				echo 'Suggs: '.$sugCount.'<br />';
				$jsOutput .= "msMissWordAr[$x][3] = new Array($sugCount);\n";
				for ($l = 0; $l < $sugCount; $l++) {
					$jsOutput .= "msMissWordAr[$x][3][$l] = '".str_replace("\n", '', addslashes(current($dblSimilarityArray)))."';\n";
					next($dblSimilarityArray);
				}

				// Cache this word
				if (!isset($doneSugsFor["$subAr3[word]"])) {
					$doneSugsFor["$subAr3[word]"] = $oi;
				}
			}

			// Build array of *ALL* words in text
			for ($x = 0; $x < count($words); $x++) {
				$jsOutput .= "msOrigWordAr[$x] = '".str_replace("\n", '\n', addslashes($words[$x]))."';\n";
			}

			// Javascript: reload content frame with new frameset
			$jsOutput .= "parent.content.location = 'compose.spell.php?cmd=suggestions&sendafter=$sendAfter';\n";
		}
	}

	// Javascript: if no words are misspelled, reload content frame with message page
	if (empty($jsOutput)) {
		$jsOutput .= "parent.content.location = 'compose.spell.php?cmd=noerrors&sendafter=$sendAfter';\n";
	}

	//echo ' -->';
	echo "End: ".(microdiff(STARTTIME) - $starttime)."<br />";
	echo "Binary checks: $checks";
	return $jsOutput;
}

?>