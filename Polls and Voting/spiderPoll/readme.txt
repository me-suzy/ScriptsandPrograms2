spiderPoll v1.1 README
	Script and README Author: Jon Thomas <http://www.fromthedesk.com/code>
	Script Last Modified: 02/17/2003
	README Last Modified: 11/05/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. Customization
5. Getting Support and Reporting Bugs
6. Acceptable Use
7. Donate

1. PURPOSE - Brief Explanation, Advantages, and Limitations
	spiderPoll v1.1 is a voting poll.  Users may vote once and view the poll any number of times.  The webmaster sets a closing date.  Vote choices are set using a text file.

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.  You must be able to set file permissions.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip poll.zip.  You should now have the following files:
		* poll.html
		* poll.jpg
		* poll.php
		* poll.txt
		* ips.txt
	NOTE: You can use any filename for poll.html as long as its form points to poll.php.  You may also change the filenames for poll.txt and ips.txt but you must change the appropriate variable in poll.php.
	(2) Upload the files.  They already contain test information.
	(3) CHMOD poll.txt and ips.txt 666 (read and write).
	(4) You should finish by voting.
	(5) If you encountered no problems, open poll.php for editing.  (Otherwise, see Section 5.)  Change the title and closing date (also the filenames if you renamed them).  Save your changes.
	(6) Open poll.txt for editing.  Follow the example below for the poll "What is your favorite color?"
		Red:0|Orange:0|Yellow:0|Green:0|Blue:0|Purple:0
	    You may add as few or as many vote choices as you like.  The vote choice is always listed followed by a colon (":"), followed by 0, and finally a "|".  There should be no spaces: each vote choice immediately follows the previous choice.  You should not edit this file after the voting has begun.
	(7) Open poll.html for editing and create several radio input fields with the name "vote" and the values corresponding to what you entered in poll.txt (the order does not matter, but the words must match exactly).


4. CUSTOMIZATION
	The easiest way to customize spiderPoll is to adapt poll.html to the look of your site.  You can rename this file or simply cut and paste the form into another file (maybe your main page).
	poll.php outputs some simple HTML code which you can customize using a CSS stylesheet.  If you choose to do so, you must change the "styleSheet" variable in poll.php to the full path of your stylesheet.  Another way to customize the poll is by changing the poll.jpg image.  It is currently red, however any color or pattern will work (but do not rename or change the image type of poll.jpg).

5. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

6. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.  You may remove notices on the script files.

7. DONATE
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>