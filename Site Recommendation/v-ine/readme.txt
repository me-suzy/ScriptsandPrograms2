v-ine v1.5 README
	Script and README Author: <http://www.fromthedesk.com/code>
	Script Last Modified: 11/08/2005
	README Last Modified: 11/08/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. Customization
5. Other Issues
6. Change Log
7. Reporting Bugs
8. Acceptable Use
9. Donate

1. PURPOSE - Brief Explanation, Advantages, and Limitations
	v-ine v1.5 is a referral service. Users may recommend any number of friends to your site simultaneously using their own message. The webmaster can receive notices of all 

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip v-ine.zip.  You should now have the following files:
		* thanks.html
		* v-ine.html
		* v-ine.php
	NOTE: If you want to use another filename for thanks.html, you must open v-ine.php and change the "thanksURL" variable.
	(2) Open v-ine.html for editing.
		(a) Enter values for the hidden form field "url".  "url" is the URL that will be advertised by v-ine.
		(b) Two submission forms are provided.  The first has one friend field, the other has 10.  Whenever you provide only one referral field, set the INPUT field names to "friendName" and "friendEmail".  When you provide more than one referral field, set the INPUT field names to "friendName[0]", "friendEmail[0]", "friendName[1]", etc.  Examine the form examples in v-ine.html for help.
	(3) Open v-ine.php for editing.  Skip to the section header DEFINE THE VARIABLES.
		(a) $subject: Change the subject of the referral e-mails if you'd like.
		(b) $defaultMsg: Change the default message if you'd like to.  This is sent when the user does not supply his own.  You can use FRIEND_NAME to refer to the person receiving the message and $userName to refer to the person sending the message.
		(c) $formURL: Type in the URL of your submission form, the v-ine.html file (you may rename the file).  v-ine matches this against the URL posting to v-ine.php to protect against unauthorized use.
		(d) $thanksURL: Set this to the URL v-ine should transport users after v-ine has processed the referrals.
		(d) $webmasterEmail: Type in your e-mail address.  v-ine will send error reports and optional referral notices (see below).
		(e) $receiveNotices: Leave this set to 1 if you want v-ine to notify you about referrals.  Set it to 0 otherwise.
	(4) Upload the files.
	(5) You should finish by testing it.

4. CUSTOMIZATION
	You may completely change v-ine.html and thanks.html.  You may even change their filenames.  If you rename thanks.html, you must change the "thanksURL" variable in v-ine.php.  Be careful about changing the form in v-ine.html.  The field names (friendName[], friendEmail[], etc.) must remain the same.
	Unless you are comfortable with PHP, you can't customize v-ine.php.  Certain error messages may appear which are hard-coded into the script.

5. OTHER ISSUES
	(*) Security.  v-ine currently depends upon the browser to check if a submission is coming from the script owner's Web site.  The browser may not send this information or the information may be intentionally falsified.  The next version of v-ine should use a safer mechanism for preventing unauthorized use of the script.

6. CHANGE LOG

06/10/2002: v-ine v1.5 can now process multiple referrals simultaneously.
11/08/2005: v-ine now runs faster.

7. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

8. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.

9. DONATE
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>