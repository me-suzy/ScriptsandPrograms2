<?php


/***************************** ADMIN AREA *************************************/
$id="admin";
$pw="admin";


/************************ Poll4All relative path ******************************/
// set poll4all folder path from page will display poll.
// this is the same path used in include statement for poll.php and check.php file.
// example: $scriptPath = "script/poll4all/";
$scriptPath = "";



/**************************** POLL STYLE **************************************/

// General
$border = 1;                      // poll border
$width = 300;                     // poll table width
$cellpadding = 5;                 // poll table padding
$bgColor = '#ECECEC';             // poll background color
$font = 'Verdana';                // poll font (question & answers)

// Question
$questionAlign = 'center';        // question text align
$questionColor = '#000000';       // question text color
$questionBgColor = '#C3D3E2';     // question background color
$questionSize = '16';             // question font size (in pixel)

// Answers
$optionsAlign = 'left';           // answers text align
$optionColor = '#000000';         // answers, vote and percentage text color
$oddBgColor = '#EBEAE0';          // odd answer background color
$evenBgColor = '#E0E7EB';         // even answer background color
$answerSize = '12';               // answers,vote and percentage text size
$barBg = '#F6F6F6';               // progress bar background color
$percentageBg = '#ECECEC';        // votes and percentage text background color

// Random Colors Mode ( 0:disable , 1:enable )
$RandomColors=1;


?>
