<?php
/**************************************************************
                         Search form
***************************************************************/
$search_form="<center>
  <form mehod='get' action='faq.php'>
    <input type='text' name='search'>
    Match <select name='match'>
      <option value='any'>Any word</option>
      <option value='all'>All words</option>
      </select>
    <input type='submit' value='Search'>
  </form>
</center>";

/**************************************************************
                       Subjects list  
***************************************************************/

// HTML printed before the subjects list
$html_subjects_start="<table align='center'><tr><td>";

// HTML printed before each subject
$html_subject_start="&#149 ";

// HTML printed after each subject
$html_subject_end="<br>";

// HTML separating subjects from each other
$html_subject_separator="<br>";

// HTML printed after the subjects list
$html_subjects_end="</td></tr></table>";

/**************************************************************
                       Questions list  
***************************************************************/

// HTML printed before the questions list
$html_questions_start="<a href='faq.php'>Return to subjects</a><br><table width='50%' align='center'><tr><td>";

// HTML printed before each question
$html_question_start="&#149; ";

// HTML printed after each question
$html_question_end="</br>";

// HTML separating  questions from each other
$html_question_separator="<br>";

// HTML printed after the questions list
$html_questions_end="</td></tr></table>";


/**************************************************************
                       Answers list  
***************************************************************/

// HTML separating  answers list from questions list
$html_questions_answers_separator="<br><br>";

// HTML printed before the answers list
$html_answers_start="<table width='60%' align='center'><tr><td>";

// HTML printed before each question
$html_answer_question_start="<big><b>";

// HTML printed after each question
$html_answer_question_end="</b></big><br><br>";

// HTML printed before each answer
$html_answer_start="";

// HTML printed after each answer
$html_answer_end="</b></big><br><br><a href='#'>Top</a><br>";

// HTML separating  answers from each other
$html_answers_separator="<br>";

// HTML printed after the answers list
$html_answers_end="</td></tr></table>";


?>