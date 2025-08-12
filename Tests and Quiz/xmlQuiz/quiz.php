<?

////////////////////////////////////////////////////////////
//
// xmlQuiz v1.0 - a simple quiz script
//
////////////////////////////////////////////////////////////
//
// This script allows you to quiz users on any number of
// questions and calculate the number of correct answers.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 08/06/2003
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

//
// SET THE VARIABLES
//

$xmlFile = "quiz.xml";


//
// GET QUIZ DATA
//

// get XML data
$data = implode("", file($xmlFile));

// create XML parser
$parser = xml_parser_create();

// set parser options
xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

// parse XML data into arrays
xml_parse_into_struct($parser, $data, $values, $tags);

// free parser
xml_parser_free($parser);


//
// STRUCTURE XML DATA INTO ARRAY
//

// set counter variable for the to-be-created questions array
$questionNo = 0;

// cycle through parsed XML data to look for text and answer tags
foreach ($values as $key=>$val) {
	// if a TEXT tag, put the value in the newly created array
	if ($val[tag] == "TEXT") {
		$questions[$questionNo]['text'] = $val[value];
	}

	// if a CHOICES tag, put the value in the newly created array
	if ($val[tag] == "CHOICES") {
		$questions[$questionNo]['choices'] = $val[value];
	}

	// if an ANSWER tag, put the value in the newly created array
	if ($val[tag] == "ANSWER") {
		$questions[$questionNo]['answer'] = $val[value];

		// increment the question counter variable
		$questionNo++;
	}
}


//
// PRINT QUIZ QUESTIONS ONE AT A TIME
//

// print first question
if (!isset($answers)) {
	echo "<b>" . $questions[0]['text'] . "</b>\n";
	echo "<form action=\"" . $PHP_SELF . "\" method=\"post\">\n";

	// split choices into array
	$choices = explode(", ", $questions[0]['choices']);

	// print text field if there are no choices
	if (count($choices) == 1) {
		echo "<input type=\"text\" name=\"answers[0]\" size=10>\n";
	}

	// print radio fields if there are multiple choices
	else {
		// print a radio button for each choice
		for ($i = 0; $i < count($choices); $i++) {
			echo "<input type=\"radio\" name=\"answers[0]\" value=\"$choices[$i]\"> $choices[$i]<br>\n";
		}
	}

	echo "<input type=\"submit\" value=\"Next Question\">\n";
	echo "</form>\n";
}

// print next question
elseif (count($questions) > count($answers)) {
	// get number of next question
	$nextQuestion = count($answers);

	// print question
	echo "<b>" . $questions[$nextQuestion]['text'] . "</b>\n";
	echo "<form action=\"" . $PHP_SELF . "\" method=\"post\">\n";

	// print answers to previous questions as hidden form fields
	for ($i = 0; $i < count($answers); $i++) {
		echo "<input type=\"hidden\" name=\"answers[$i]\" value=\"$answers[$i]\">\n";
	}

	// split choices into array
	$choices = explode(", ", $questions[$nextQuestion]['choices']);

	// print text field if there are no choices
	if (count($choices) == 1) {
		echo "<input type=\"text\" name=\"answers[$nextQuestion]\" size=10>\n";
	}

	// print radio fields if there are multiple choices
	else {
		// print a radio button for each choice
		for ($i = 0; $i < count($choices); $i++) {
			echo "<input type=\"radio\" name=\"answers[$nextQuestion]\" value=\"$choices[$i]\"> $choices[$i]<br>\n";
		}
	}

	// determine button label
	if (count($questions) == count($answers) + 1) {
		echo "<input type=\"submit\" value=\"Calculate Score\">\n";
	}
	else {
		echo "<input type=\"submit\" value=\"Next Question\">\n";
	}

	echo "</form>\n";
}

// calculate and print score
else {
	// calculate score
	for ($i = 0; $i < count($questions); $i++) {
		// if correct answer matches user answer, increment score variable
		if ($questions[$i]['answer'] == $answers[$i]) {
			$score++;
		}
	}

	// print score
	if ($score == 0) {
		echo "You answered no questions correctly.  <a href=" . $PHP_SELF . ">Try again.</a>";
	}

	if ($score == 1) {
		echo "You answered 1 question correctly.  <a href=" . $PHP_SELF . ">Try again.</a>";
	}

	if ($score > 1 && $score < count($questions)) {
		echo "You answered $score questions correctly.  <a href=" . $PHP_SELF . ">Try again.</a>";
	}

	if ($score == count($questions)) {
		echo "You answered all questions correctly.";
	}
}

?>