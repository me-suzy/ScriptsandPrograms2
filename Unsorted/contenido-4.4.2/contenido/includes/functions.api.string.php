<?php

/*****************************************
* File      :   $RCSfile: functions.api.string.php,v $
* Project   :   Contenido
* Descr     :   Contenido String API functions
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   08.08.2003
* Modified  :   $Date: 2003/08/11 10:16:34 $
*
* © four for business AG, www.4fb.de
*
* $Id: functions.api.string.php,v 1.1 2003/08/11 10:16:34 timo.hummel Exp $
******************************************/

/**
 * Info:
 * This file contains Contenido String API functions.
 *
 * If you are planning to add a function, please make sure that:
 * 1.) The function is in the correct place
 * 2.) The function is documented
 * 3.) The function makes sense and is generically usable
 *
 */


/**
 * capiStrTrimAfterWord: Trims a string to a given
 * length and makes sure that all words up to
 * $maxlen are preserved, without exceeding $maxlen.
 *
 * Warning: Currently, this function uses a regular
 * ASCII-Whitespace to do the seperation test. If
 * you are using '&nbsp' to create spaces, this
 * function will fail.
 *
 * Example:
 * $string = "This is a simple test";
 * echo capiStrTrimAfterWord ($string, 15);
 *
 * This would output "This is a", since 
 * this function respects word boundaries
 * and doesn't operate beyond the limit given
 * by $maxlen.
 *
 * @param $string string The string to operate on
 * @param $maxlen int The maximum number of characters 
 *
 * @return string The resulting string
 */
function capiStrTrimAfterWord ($string, $maxlen)
{
	/* If the string is smaller than the maximum
       lenght, it makes no sense to process it any
       further. Return it. */
	if (strlen($string) < $maxlen)
	{
		return $string;
	}

	/* If the character after the $maxlen
       position is a space, we can return
       the string until $maxlen */	
	if (substr($string, $maxlen,1) == ' ')
	{
		return substr($string, 0, $maxlen);
	}
	
	/* Cut the string up to $maxlen so we can
       use strrpos (reverse str position) */
	$cutted_string = substr($string, 0, $maxlen);
	
	/* Extract the end of the last word */
	$last_word_position = strrpos($cutted_string, ' ');
	
	return (substr($cutted_string, 0, $last_word_position));
}

/**
 * capiStrTrimHard: Trims a string to a specific
 * length. If the string is longer than $maxlen,
 * dots are inserted ("...") right before $maxlen.
 *
 * Example:
 * $string = "This is a simple test";
 * echo capiStrTrimHard ($string, 15);
 *
 * This would output "This is a si...", since 
 * the string is longer than $maxlen and the
 * resulting string matches 15 characters including
 * the dots.
 *
 * @param $string string The string to operate on
 * @param $maxlen int The maximum number of characters 
 *
 * @return string The resulting string
 */
function capiStrTrimHard ($string, $maxlen, $fillup = "...")
{
	/* Our fillup string */
	$fillup = "...";

	/* If the string is smaller than the maximum
       lenght, it makes no sense to process it any
       further. Return it. */	
	if (strlen($string) < $maxlen)
	{
		return $string;
	}
	
	/* Calculate the maximum text length */
	$maximum_text_length = $maxlen - strlen($fillup);
	
	/* Cut it */
	$cutted_string = substr($string, 0, $maximum_text_length);
	
	/* Append the fillup string */
	$cutted_string .= $fillup;
	 
	return ($cutted_string);
}

/**
 * capiStrTrimSentence: Trims a string to a 
 * approximate length. Sentence boundaries are
 * preserved.
 *
 * The algorythm inside calculates the sentence
 * length to the previous and next sentences.
 * The distance to the next sentence which is
 * smaller will be taken to trim the string
 * to match the approximate length parameter.
 *
 * Example:
 *
 * $string  = "This contains two sentences. ";
 * $string .= "Lets play around with them. ";
 *
 * echo capiStrTrimSentence($string, 40);
 * echo capiStrTrimSentence($string, 50);
 *
 * The first example would only output the first sentence,
 * the second example both sentences.
 *
 * Explanation:
 *
 * To match the given max length closely, 
 * the function calculates the distance to
 * the next and previous sentences. Using
 * the maxlength of 40 characters, the
 * distance to the previous sentence would
 * be 8 characters, and to the next sentence
 * it would be 19 characters. Therefore,
 * only the previous sentence is displayed. 
 *
 * The second example displays the second
 * sentence also, since the distance to the
 * next sentence is only 9 characters, but
 * to the previous it is 18 characters.
 *
 * If you specify the boolean flag "$hard",
 * the limit parameter creates a hard limit
 * instead of calculating the distance.
 *
 * This function ensures that at least one
 * sentence is returned.
 *
 * @param $string string The string to operate on
 * @param $approxlen int The approximate number of characters 
 * @param $hard boolean If true, use a hard limit for the number of characters (default: false)
 * @return string The resulting string
 */
function capiStrTrimSentence ($string, $approxlen, $hard = false)
{

	/* If the string is smaller than the maximum
       lenght, it makes no sense to process it any
       further. Return it. */		
	if (strlen($string) < $approxlen)
	{
		return $string;
	}
	
	/* Find out the start of the next sentence */
	$next_sentence_start = strpos($string, '.', $approxlen);

	/* If there's no next sentence (somebody forgot the dot?),
       set it to the end of the string. */	
	if ($next_sentence_start === false)
	{
		$next_sentence_start = strlen($string);	
	} 
	
	/* Cut the previous sentence so we can use strrpos */
	$previous_sentence_cutted = substr($string, 0, $approxlen);
	
	/* Get out the previous sentence start */
	$previous_sentence_start = strrpos($previous_sentence_cutted, '.');
	
	/* If the sentence doesn't contain a dot, use the text start. */
	if ($previous_sentence_start === false)
	{
		$previous_sentence_start = 0;
	}
	
	/* If we have a hard limit, we only want to process
       everything before $approxlen */
	if (($hard == true) && ($next_sentence_start > $approxlen))
	{
		return (substr($string, 0, $previous_sentence_start+1));
	}  
	
	/* Calculate next and previous sentence distances */
	$distance_previous_sentence = $approxlen - $previous_sentence_start;
	$distance_next_sentence = $next_sentence_start - $approxlen;

	/* Sanity: Return at least one sentence. */
	$sanity = substr($string, 0, $previous_sentence_start + 1);
	
	if (strpos($sanity,'.') === false)
	{
		return (substr($string, 0, $next_sentence_start + 1));
	}
	
	/* Decide wether the next or previous sentence is nearer */	
	if ($distance_previous_sentence > $distance_next_sentence)
	{
		return (substr($string, 0, $next_sentence_start+1));
	} else {
		return (substr($string, 0, $previous_sentence_start+1));
	}
}