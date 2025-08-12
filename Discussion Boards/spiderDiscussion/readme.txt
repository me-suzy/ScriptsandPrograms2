spiderDiscussion v1.3 README
	Script and README Author: <http://www.fromthedesk.com/code>
	Script Last Modified: 07/18/2003
	README Last Modified: 11/05/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. Customization
5. Other Issues
6. Reporting Bugs
7. Acceptable Use
8. Donate

1. PURPOSE - Brief Explanation, Advantages, and Limitations
	spiderDiscussion v1.3 is a simple threaded discussion board.  Users may post and reply to posts.  The webmaster can easily customize the layout and design.  He may also set the posting order as most to least recent or least to most recent.  There is no administrative interface for the webmaster or Web site owner to easily remove posts or block users.

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.  You must be able to set file permissions.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip board.zip.  You should now have the following files:
		* index.html
		* messages (directory)
		* noposts.txt
		* post.php
		* style.css
		* template.txt
	NOTE: If you want to use other filenames for index.html, noposts.txt, or template.txt, you must open post.php and change the appropriate variables.
	(2) Create a new directory and upload the files.
	(3) CHMOD messages 777 (read, write, and execute); index.html and noposts.txt 666 (read and write).
	(4) You should finish by posting a test.

4. CUSTOMIZATION
	post.php outputs only the most basic HTML code.  Therefore you can easily change the layout and design without opening post.php.  There are three files you can edit, two with knowledge of HTML and another with knowledge of CSS:
	(1) index.html - Change anything you'd like but do not remove this code:
		<ul>
		<!--POST NEW HERE-->
		</ul>
	    and unless you know what you're doing, do not change the form.
	(2) template.txt - You can modify or completely recode this file.  Use the following comments to place the post data in your template:
		* <!--SUBJECT-->
		* <!--MESSAGE-->
		* <!--NAME AND DATE-->
		* <!--MESSAGE NUMBER--> - this must be included in the reply form as a hidden form field as the value and with the name "reply"
	(3) style.css - If you know CSS, you can change this stylesheet to alter the appearance of the little HTML code that is output by post.php - anchors and unordered list items - as well as everything else in your index and post files.  If you already use a stylesheet on your site, you must change the path in index.html, post.php (for the error message), and template.txt.
	(4) post.php - By default, spiderDiscussion allows only <a>, <b>, <i>, and <u> tags in the post.  However, you may open post.php and make the desired changes to this line:
		$message = strip_tags($message, "<a><b><i><u>");
	    You may add or subtract to the list of permitted HTML tags inside the quotations.  If you remove this line completely, all HTML tags will be permitted.  Be warned, however, that users may then use malicious HTML commands, like the meta refresh which will redirect users to another Web page.

5. OTHER ISSUES
	Replies are not listed on individual post pages as they are in most similar discussion board scripts.  I intend to remedy this in the future.

6. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

7. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.

8. DONATE
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>