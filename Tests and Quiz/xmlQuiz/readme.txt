xmlQuiz v1.0 README
	Script and README Author: <http://www.fromthedesk.com/code>
	Script Last Modified: 08/06/2003
	README Last Modified: 11/05/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. How to Use
5. Reporting Bugs
6. Acceptable Use
7. Donate

1. PURPOSE - Brief Explanation, Advantages, and Limitations
	xmlQuiz v1.0 is a simple quiz script.  Using an XML file, you can easily add, edit, and delete questions.  Questions can be "short answer" or multiple choice.  Short answer questions must be answered exactly as they are in the XML file.  After all questions have been answered, the quiz script calculates the number of correct answers.  v1.0 does not allow a random order of questions; the order of questions from the XML file is preserved.

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip quiz.zip.  You should now have the following files:
		* quiz.php
		* quiz.xml
	(2) Upload the files.
	(3) You should finish by testing it.  An example quiz is set up for "out of the box" testing.
	(4) After you have tested xmlQuiz, begin to set up your own quiz.  Open quiz.php.  To add your own questions, see section 4, "HOW TO USE," below.
	(5) Upload the edited files and test your new quiz.

4. HOW TO USE
	To begin adding your own questions, open quiz.xml for editing.  If you know XML, you will quickly understand how to use xmlQuiz.  If not, read on.
	You'll notice a structure like this:

<QUIZ>

<QUESTION>
<TEXT>Who was the fifth president of the United States?</TEXT>
<CHOICES>Monroe, Madison, John Quincy Adams</CHOICES>
<ANSWER>Monroe</ANSWER>
</QUESTION>

</QUIZ>

	Also notice that the XML code looks much like HTML code except the tag names are unfamiliar.  XML allows you to create your own tags.  Just like HTML, XML must have opening and closing tags and data can be stored within the tags.
	You can easily add images by creating a new <QUESTION> entry.  Just copy an existing one (or type from scratch) and modify the <TEXT>, <CHOICES>, and <ANSWER> content.
	The <CHOICES> tag determines whether the question is "short answer" or multiple choice.  If there is only one choice, xmlQuiz will print a small text field for that question and the user must submit the answer exactly as it appears in the <ANSWER> tag.  If there are more choices, xmlQuiz prints each choice as a radio button.  Each choice should be separated by a comma and a space.
	Remember to upload quiz.xml to your site after you make changes.  Always load quiz.xml in a Web browser to make sure you didn't make any mistakes.  XML is pickier than HTML!

5. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

6. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.

7. DONATE 
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>