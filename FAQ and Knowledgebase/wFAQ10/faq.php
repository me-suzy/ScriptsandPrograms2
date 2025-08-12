<?

// ##########################################################################
// ##########################################################################
//
//     wFAQ v1.0 - Created by the Webligo Group
//                 http://www.webligo.com
//
//  YOUR USE OR DISTRIBUTION OF THIS SOFTWARE CONSTITUTES YOUR ACCEPTANCE
//  OF THE FOLLOWING LICENSE AGREEMENT: 
//
//  THIS SCRIPT AND ALL OF ITS CONTENTS ARE LICENSED UNDER THE
//  GPL FREEWARE LICENSE. IT MAY NOT BE RESOLD OUR USED COMMERCIALLY
//  WITHOUT EXPRESSED PERMISSION OF THE WEBLIGO GROUP. IT MAY, HOWEVER,
//  BE DISTRIBUTED FREELY WITHOUT CHARGE. THIS SOFTWARE IS INTELLECTUAL
//  PROPERTY OF THE WEBLIGO GROUP. ANYONE VIOLATING THIS AGREEMENT WITHOUT
//  THE EXPRESSED PERMISSION OF THE WEBLIGO GROUP MAY BE LEGALLY PROSECUTED.
//  YOUR DOWNLOAD AND USE OF THIS SOFTWARE ALSO SIGNIFIES THAT YOU UNDERSTAND
//  AND AGREE TO THE DOWNLOAD AGREEMENT YOU READ UPON DOWNLOAD.
//
//  The Webligo Group, its management, or any of its employees, associates, or 
//  partners cannot be held liable for any damages that this software may cause. 
//  As the Licensee and user of the software, you agree to accept full liability 
//  for any damages or risk involved with using this software.
//
//  If you need help installing or using this software, please
//  read the readme.txt file that was provided with it.
//
//  This file and all related content are the intellectual
//  property of the Webligo Group and are under copyright.
//
//  If you plan to use this script for your clients, sell it as a service,
//  or utilize it in any other commercial manner, you must purchase a commercial
//  license. Please see this page for more information:
//  http://www.webligo.com/products_wfaq.php
//
//  We do not provide support for this script, unless you have purchased a
//  commercial license.
//
//  Feel free to visit our website (http://www.webligo.com)
//  if you wish to send us any comments, etc.
//
// ###########################################################################
// ###########################################################################























include "faq_config.php";

// SHOW ADMIN HEADER
echo $admin_info[header];






















// IF USER HAS CLICKED ON A QUESTION
if(isset($_GET['q_id'])) { $q_id = $_GET['q_id']; }

if($q_id != "") {
$question_query = mysql_query("SELECT * FROM faq_questions WHERE q_id='$q_id'");
$question = mysql_fetch_assoc($question_query);
if(mysql_num_rows($question_query) == 0) {
echo "<h2>An error has occurred:</h2>
You are attempting to view an FAQ question that does not exist.
";
}

echo "
<h2>$question[question]</h2>
$question[answer]
<form action='faq.php' method='POST'>
<input type='submit' value='Back to FAQ'>
</form>
";









} else {








// IF USER IS LOOKING AT THE MAIN PAGE
$totalcount = 0;
$faq_questions = mysql_query("SELECT * FROM faq_questions");

$faqcat = mysql_query("SELECT * FROM faq_categories ORDER BY c_order");
$num_of_kitties = mysql_num_rows($faqcat);
while($faqcat_info = mysql_fetch_assoc($faqcat)) {
$questions = mysql_query("SELECT q_id, c_id, question FROM faq_questions WHERE c_id='$faqcat_info[c_id]' ORDER BY q_order");


// SHOW CATEGORY NAME, IF IT CONTAINS QUESTIONS AND CATEGORY NAMES ARE TURNED ON
if(mysql_num_rows($questions) > 0) {
if($admin_info[showcats] == 1) {
echo "
<h2>$faqcat_info[category]</h2>
";
}
}


// SHOW QUESTIONS
$count = 0;
while($question = mysql_fetch_assoc($questions)) {
$totalcount++;
$count++;

// SHOW NUMBERS IF ENABLED
if($admin_info[shownumbers] == 1) {
if($admin_info[showcats] == 1) { echo "$count. "; } else { echo "$totalcount. "; }
}

echo "<a href='faq.php?q_id=$question[q_id]'>$question[question]</a><br>";
}


// ADD SPACE AFTER THIS CATEGORY
if(mysql_num_rows($questions) != 0 AND $admin_info[showcats] == 1) {
echo "<br>";
}

}
}





// SHOW HTML FOOTER
echo $admin_info[footer];



?>