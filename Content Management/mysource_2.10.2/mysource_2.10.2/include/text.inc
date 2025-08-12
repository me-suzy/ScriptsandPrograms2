<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/text.inc
## Desc: General functions for processing text and strings
## $Source: /home/cvsroot/mysource/include/text.inc,v $
## $Revision: 2.17 $
## $Author: dofford $
## $Date: 2004/01/20 04:04:36 $
#######################################################################

#---------------------------------------------------------------------#


  ############################################################################
 # Truncates or pads a string to fit a certain width (can also right-align) #
############################################################################

function fit_to_width($string='', $length=0, $right=false) {
	if ($length <= 0) $i++;
	$string = ereg_replace("\\\'","'",$string); 
	$string = ereg_replace("\\\\\"","\"",$string); 
	$result = substr(trim($string), 0, $length);
	if (!$result) $result = " ";
	while (strlen($result) < $length) {
		if ($right) $result = " " . $result;
		else        $result .= " ";
	}
	return $result;
}

  ######################################################################
 # Indents a string of text a number of spaces, outdents if negative  #
######################################################################

function indent($string='', $indent_amount=0) {
	if  (!$string) return "";
	$result = "";
	$spaces = "";
	for ($i = 0; $i < $indent_amount; $i++) $spaces .= " "; # Indenting
	$lines = explode("\n",$string);
	while (list($key, $line) = each($lines)) {
		for ($i = 0; $i > $indent_amount && ($line[-$i] == " "); $i--); # Outdenting
		if (-$i)     $line = substr($line,-$i);
		if ($spaces) $result .= $spaces . $line . "\n";
		else         $result .= $line . "\n";
	}
	return $result;
}


   ##################################################################
  # Chops a string at a certain maximum length and appends an ellipsis
 ######################################################################

 function ellipsisize($string='', $length=0) {
	$full_length = strlen($string);
	$length += 2; # add 2 because an elipsis is 3 chars long
	if ($length > 0) {
		$on = true;
		$j = 0;
		for($i = 0; $i < $full_length; $i++) {
			if ($j == $length - 2)	$chophere = $i;
			if ($on) {
				if ($string[$i] == "<") {$on = false;}
				else $j++;
			} else {
				if ($string[$i] == ">") $on = true;
			}
		}
		if ($length < $j) {
			$string = substr($string, 0, $chophere) . "...";
		}
	}
	return $string;
 }

   ##################################################################
  # Chops a string at a certain maximum number of words. 
 # A word is defined by white space
######################################################################
function wordchop($string='', $word_count=0) {
	$words = preg_split('/(\\s+)/', $string, $word_count+1, PREG_SPLIT_DELIM_CAPTURE);
	array_pop($words); # remove the rest of string
	array_pop($words); # remove last white space
	return implode('', $words).'...';
}

 ##########################################
# create keywords 
function retrieve_keywords($words='',$extra_ignores='') {
	if (!is_array($extra_ignores)) {
		$extra_ignores = preg_split("/[\b]+/",$extra_ignores);
	}
	$ignored_keywords = array('the','and','you','that','for','was','are','with','his','they','this','from','have','one','had','not','but','what','all','were','when','there','can','your','which','their','said','will','each','about','how','them','then','she','these','would','other','into','has','her','him','could','make','than','been','its','who','now','made','did','only','way','find','use','may','long','very','after','words','just','where','most','get','back','much','new','our','man','too','any','day','same','right','look','think','also','other','came','come','does','well','such','here','take','why','put','more','some','page','nbsp');
	foreach($extra_ignores as $w) $ignored_keywords[] = strtolower($w);

	$words = ereg_replace("[\\]","",$words);

	# Split the output into "words"
	$word_array = preg_split("/[\b]+/",strtolower($words),10000);
	$words = ""; # Clear
	# Loop through words and keep a count of each word's occurances
	$order = Array();
	if (!empty($word_array)) {
		foreach($word_array as $w) {
			# Reject one-character words or common words or words with no letters
			$l = strlen($w); 
			if ($l < 3 || in_array($w,$ignored_keywords) || !ereg("[a-z0-9]",$w)) continue;
			# Strip fullstops from words at the end of sentences
			if ($w[$l-1] == ".") $w = substr($w,0,-1);
			$order[$w]++;
		}
	}

	# Sort the words in order from most common to least common
	arsort($order);
	# Now put them all in a big string
	$result = '';
	if (!empty($order)) {
		for(reset($order); NULL !== ($w = key($order)); next($order)) {
			$result .= "$w ";
		}
	}

	return $result;
}

 
 ##########################################
# Merges two sets of keywords together
function merge_keywords($a='',$b='') {
	$bs = preg_split('/[\b]+/',$b);
	$a = strtolower($a);
	$bs_size = count($bs);
	for($i = 0; $i < $bs_size; $i++) {
		$w = strtolower($bs[$i]);
		if ((strlen($w) > 1) && (strpos($a,$w) === false)) {
			if ($a) $a .= " $w";
			else    $a  = $w;
		}
	}
	return $a;
}


 ##########################################
# Strips tags after badding them with spaces
# Good for keyword extraction from HTML
function strip_tags_padded($str='',$allowed='') {
	return strip_tags(str_replace("<"," <",str_replace(">","> ",$str)),$allowed);
}


  #####################################################
 # performs a strpos and sets the passed var $set_pos 
# and returns boolean indicating whether something was found
function set_strpos(&$set_pos, &$haystack, $needle='', $offset=0) {
	$set_pos = strpos ($haystack, $needle, $offset);
	return !($set_pos === false);
}

 ###############################################################
# returns true if the whole string is whitespace of somekind
function is_whitespace($str='') {
	return ereg("^[[:space:]]*$", $str);
}

 #################################################################
# Takes a string an associative array of keyword => replacements
# Replaces any occurance of "%keyword%" with the replacements text
function replace_keywords($text='', &$replacements) {
	if(is_array($text)) {
		$r = array();
		foreach ($text as $key => $value) {
			$key = replace_keywords($key, $replacements);
			$r[$key] = replace_keywords($value, $replacements);
		}
		return $r;
	}
	if (!empty($replacements)) {
		foreach($replacements as $keyword => $replacement) {
			if($keyword == "") continue;
			$text = str_replace("%$keyword%","$replacement",$text);
		}
	}
	return $text;
}

 #################################################################
# Takes a string and returns all occurances of "%keyword%" 
# with the replacements text
function retrieve_keywords_replacements($text='') {
	$matches = Array();
	preg_match_all ('/%([a-zA-Z() ?_\-0-9]+)%/', $text, $matches, PREG_PATTERN_ORDER);
	return (count($matches)) ? $matches[1] : Array();
}

/**
* A straight filtering algorithm that returns a score depending on the type of straight match it get
* and what score you assign for such a match
*
* @param   string $body_text		A string of text to filter
* @param   array $list_words_and_scores		words/phrases to be filtered with score as the data
* @param   array $char_replace_array		words/characters and their common replacements
* @Param   array $SMmultiplier_array		An array of score/(number of chars in a word) multiplier
* @access  public
* @returns int
*/
function filter_string_straight_match($body_text='', $list_words_and_scores=array(), $char_replace_array=array(), $SMmultiplier_array=array(1,1,1,1,1,1,1,1,1,1,1)) {
	#to store a word that has been detected. If it was detected once we should not increase the score
	$detected_array = array();
	$score = 0;
	#kill white spaces
	$body_text = str_replace(' ', '', $body_text);
	#kill enters
	$body_text = str_replace("\n", '', $body_text);
	$body_text = strtolower($body_text);

	#evaluate the $char_replace_array with the $list_words_and_scores and add words with extra replacements
	$additional_words_array = array();

	#straight matching
	if (!empty($list_words_and_scores)) {
		foreach($list_words_and_scores as $word_or_phrase => $value) {
			$word_or_phrase = str_replace(' ', '', $word_or_phrase);
			#kill enters
			$word_or_phrase = str_replace("\n", '', $word_or_phrase);
			$word_or_phrase = strtolower($word_or_phrase);
			if(!in_array($word_or_phrase, $detected_array)) {
				#match code
				if (preg_match("/$word_or_phrase/m", $body_text)) {
					#match found
					switch(strlen($word_or_phrase)) {
						case 0:
							$multiplier = $SMmultiplier_array[0];
							break;
						case 1:
							$multiplier = $SMmultiplier_array[1];
							break;
						case 2:
							$multiplier = $SMmultiplier_array[2];
							break;
						case 3:
							$multiplier = $SMmultiplier_array[3];
							break;
						case 4:
							$multiplier = $SMmultiplier_array[4];
							break;
						case 5:
							$multiplier = $SMmultiplier_array[5];
							break;
						case 6:
							$multiplier = $SMmultiplier_array[6];
							break;
						case 7:
							$multiplier = $SMmultiplier_array[7];
							break;
						case 8:
							$multiplier = $SMmultiplier_array[8];
							break;
						case 9:
							$multiplier = $SMmultiplier_array[9];
								break;
						default:
							$multiplier = $SMmultiplier_array[10];
							break;
					}
					$score = $score + $value*$multiplier;#as it's a weak match with possible errors
					array_push($detected_array, $word_or_phrase);
				}
			}
		}
	}

	#weaker match because say you may want a replaced with @ and you are looking for array it will match if they go @rr@y but not if the go @rray. It could be made stronger BUT it would be getting slow. I would build in a strength factor if you make it stronger
	if (!empty($char_replace_array)) {
		foreach($char_replace_array as $letter=>$replacement) {
			$body = str_replace($letter, $replacement, $body_text);
			if (!empty($list_words_and_scores)) {
				foreach($list_words_and_scores as $word_or_phrase => $value) {
					if(!in_array($word_or_phrase, $detected_array)) {
						#match code
						if (preg_match("/$word_or_phrase/m", $body_text)) {
							#match found
							switch(strlen($word_or_phrase)) {
								case 0:
									$multiplier = $SMmultiplier_array[0];
									break;
								case 1:
									$multiplier = $SMmultiplier_array[1];
									break;
								case 2:
									$multiplier = $SMmultiplier_array[2];
									break;
								case 3:
									$multiplier = $SMmultiplier_array[3];
									break;
								case 4:
									$multiplier = $SMmultiplier_array[4];
									break;
								case 5:
									$multiplier = $SMmultiplier_array[5];
									break;
								case 6:
									$multiplier = $SMmultiplier_array[6];
									break;
								case 7:
									$multiplier = $SMmultiplier_array[7];
									break;
								case 8:
									$multiplier = $SMmultiplier_array[8];
									break;
								case 9:
									$multiplier = $SMmultiplier_array[9];
										break;
								default:
									$multiplier = $SMmultiplier_array[10];
									break;
							}
							$score = $score + $value*$multiplier/2;#as it's a weak match with possible errors
							array_push($detected_array, $word_or_phrase);
						}
					}
				}
			}
		}
	}
	return $score;
}

/**
* Window match is a different algorithm from straight matchting but is also for the purpose of calculating
* a filtering score. This algorithm works by creating a WINDOW which is 2 times the size of the word and
* looks for a match there. The reason for the window is it puts star reg exp operators in between each
* letter of the word to increase the chances of finding a match if someone tried to disguise their word
* by padding with characters. EG w1o2r3d equals WORD
*
* @param   string $body_text		A string of text to filter
* @param   array $list_words_and_scores		words/phrases to be filtered with score as the data
* @param   array $char_replace_array		words/characters and their common replacements
* @Param   array $WMmultiplier_array		An array of score/(number of chars in a word) multiplier
* @access  public
* @returns int
*/
function filter_string_window_match($body_text='', $list_words_and_scores=array(), $char_replace_array=array(), $WMmultiplier_array=array(1,1,1,1,1,1,1,1,1,1,1)) {

	#to store a word that has been detected. If it was detected once we should not increase the score
	$detected_array = array();
	$score = 0;
	#kill white spaces
	$body_text = str_replace(' ', '', $body_text);
	#kill enters
	$body_text = str_replace("\n", '', $body_text);
	$body_text = strtolower($body_text);

	#window matching
	if (!empty($list_words_and_scores)) {
		foreach($list_words_and_scores as $word_or_phrase => $value) {
			$word_or_phrase = str_replace(' ', '', $word_or_phrase);
			#kill enters
			$word_or_phrase = str_replace("\n", '', $word_or_phrase);
			$word_or_phrase = strtolower($word_or_phrase);
			$window_size = ceil(strlen($word_or_phrase) * 2);
			$reg_word = '';
			#test this to make sure the word changes right
			$c = strlen($word_or_phrase);
			for($i=0;$i<strlen($word_or_phrase); $i++) {
				if ($i != (strlen($word_or_phrase)-1)) {
					$reg_word .= $word_or_phrase[$i].'.*';
				}
				else {
					$reg_word .= $word_or_phrase[$i];
				}
			}

			#do window matching. Still room for errors but since the window we are looking at is reduced as a function of the word size and the value of this type of match is reduced it may have some merit
			for($i=0; ($i<strlen($body_text)-$window_size+1); $i++) {
				$subtext = substr($body_text, $i, $window_size);
				if(!in_array($word_or_phrase, $detected_array)) {
					if (preg_match("/$reg_word/m", $subtext)) {
						#match found
						switch(strlen($word_or_phrase)) {
							case 0:
								$multiplier = $WMmultiplier_array[0];
								break;
							case 1:
								$multiplier = $WMmultiplier_array[1];
								break;
							case 2:
								$multiplier = $WMmultiplier_array[2];
								break;
							case 3:
								$multiplier = $WMmultiplier_array[3];
								break;
							case 4:
								$multiplier = $WMmultiplier_array[4];
								break;
							case 5:
								$multiplier = $WMmultiplier_array[5];
								break;
							case 6:
								$multiplier = $WMmultiplier_array[6];
								break;
							case 7:
								$multiplier = $WMmultiplier_array[7];
								break;
							case 8:
								$multiplier = $WMmultiplier_array[8];
								break;
							case 9:
								$multiplier = $WMmultiplier_array[9];
									break;
							default:
								$multiplier = $WMmultiplier_array[10];
								break;
						}
						$score = $score + $value*$multiplier;#as it's a weak match with possible errors
						$add = $value*$multiplier;
						array_push($detected_array, $word_or_phrase);
					}
				}
			}
		}
	}

	#weaker match because say you may want a replaced with @ and you are looking for array it will match if they go @rr@y but not if the go @rray. It could be made stronger BUT it would be getting slow. I would build in a strength factor if you make it stronger
	if (!empty($char_replace_array)) {
		foreach($char_replace_array as $letter=>$replacement) {
			$body = str_replace($letter, $replacement, $body_text);
			#window matching
			if (!empty($list_words_and_scores)) {
				foreach($list_words_and_scores as $word_or_phrase => $value) {
					$word_or_phrase = str_replace(' ', '', $word_or_phrase);
					#kill enters
					$word_or_phrase = str_replace("\n", '', $word_or_phrase);
					$word_or_phrase = strtolower($word_or_phrase);
					$window_size = ceil(strlen($word_or_phrase) * 2);
					$reg_word = '';
					#test this to make sure the word changes right
					$c = strlen($word_or_phrase);
					if (!in_array($word_or_phrase, $detected_array)) {
						for($i=0;$i<strlen($word_or_phrase); $i++) {
							if ($i != (strlen($word_or_phrase)-1)) {
								$reg_word .= $word_or_phrase[$i].'.*';
							}
							else {
								$reg_word .= $word_or_phrase[$i];
							}
						}

						#do window matching. Still room for errors but since the window we are looking at is reduced as a function of the word size and the value of this type of match is reduced it may have some merit
						for($i=0; ($i<strlen($body_text)-$window_size+1); $i++) {
							$subtext = substr($body_text, $i, $window_size);
							if (preg_match("/$reg_word/m", $subtext)){
								#match found
								switch(strlen($word_or_phrase)) {
									case 0:
										$multiplier = $WMmultiplier_array[0];
										break;
									case 1:
										$multiplier = $WMmultiplier_array[1];
										break;
									case 2:
										$multiplier = $WMmultiplier_array[2];
										break;
									case 3:
										$multiplier = $WMmultiplier_array[3];
										break;
									case 4:
										$multiplier = $WMmultiplier_array[4];
										break;
									case 5:
										$multiplier = $WMmultiplier_array[5];
										break;
									case 6:
										$multiplier = $WMmultiplier_array[6];
										break;
									case 7:
										$multiplier = $WMmultiplier_array[7];
										break;
									case 8:
										$multiplier = $WMmultiplier_array[8];
										break;
									case 9:
										$multiplier = $WMmultiplier_array[9];
											break;
									default:
										$multiplier = $WMmultiplier_array[10];
										break;
								}
								$score = $score + $value*$multiplier/2;#as it's a weak match with possible errors
								array_push($detected_array, $word_or_phrase);
												$add = $value*$multiplier;
							}
						}
					}
				}
			}
			return $score;
		}
	}
}
?>