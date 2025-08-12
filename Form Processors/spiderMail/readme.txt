spiderMail v2.2 README
	Script and README Author: Jon Thomas <http://www.fromthedesk.com/code>
	Script Last Modified: 10/15/2005
	README Last Modified: 11/05/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. Customization
5. How to Use
6. Change Log
7. Reporting Bugs
8. Acceptable Use
9. Donate

1. PURPOSE - Brief Explanation and Advantages
	spiderMail v2.2 is a complex form mailer.  All of the data entered in a form is sent to an e-mail address specified in the form or the script itself.  A subject and "from" address address may also be specified.  The user input is also printed to the screen.  The e-mail and screen presentation are formatted according to two separate templates completely customizable by the site owner.  The site owner may create an address book so that aliases can be used in place of e-mail addresses.  This will effectively hide your e-mail addresses from users and even malicious scripts which search Web pages for addresses.  Version 2.2 protects against off-site use of the script.

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip spidermail.zip.  You should now have the following files:
		* spidermail.php
		* form.html
		* addressbook.inc
		* is_email.inc
		* replaceArrStrs.inc
		* selectText.inc
		* tem_email.txt
		* tem_html.txt
		
	(2) Open spidermail.php for editing.  Edit the variables (found under "// define the variables").  You must also enter the URLs for every form you plan to use with spiderMail.  spiderMail will not process a form unless the form's URL matches one of these URLs.  Look for this section:
		// the URLs of the submission forms
		$formURL[0] = "";
		$formURL[1] = "";
	An example might be:
		// the URLs of the submission forms
		$formURL[0] = "http://www.john117.com/jp/contact.php";
	If you plan to use spiderMail with more than 2 forms, copy the above line as many times as needed and increment the bracketted number.  If you only plan to use spiderMail with one form, keep the second line provided above as it is.
	(3) Open tem_email.txt and tem_html.txt for editing.  Save your changes.  See Customization below for further information.
	(4) Open form.html for editing.  You may, of course, rename this file.  A test form is provided.  When you make your changes, you must at least keep the following line:
		<form action="spidermail.php" method="post">
	Also, keep in mind that "to" is the field name for the address the form is sent to, "from" is the field name for the address of the person sending the e-mail, and "subject" is the field name for the subject of the e-mail.  Save your changes.
	IMPORTANT: When you create forms with checkboxes or multiple select boxes, you must add "[]" to the end of the element name.  If you do not, the form processor will be unable to access all the selected values of that input element.  This is a limitation of PHP, not this script.  Follow this example:
		<input type=checkbox name=toppings[] value=sauce> Hot Chocolate Sauce
		<input type=checkbox name=toppings[] value=sprinkles> Sprinkles
		<input type=checkbox name=toppings[] value=cherry> Cherry
	(5) Upload the files.  Finish by testing.

4. CUSTOMIZATION
	You can completely customize tem_email.txt and tem_html.txt.  Using these codes in your templates:

	<!--TO--> - to address
	<!--FROM--> - from address
	<!--SUBJECT--> - e-mail subject
	<!--DATETIME--> - date and time of e-mail
	<!--FORM URL--> - URL of submission form
	<!--BEGIN BODY--> and <!--END BODY--> - these codes should appear around the part of the template which repeats once for each extra form field, form example around <tr> tags in an HTML table
	<!--NAME--> and <!--VALUE--> - these codes should appear within the body codes above to indicate the exact placement of the field name and its value for each form field

	Use tem_email.txt and tem_html.txt as your examples.

5. HOW TO USE
	5A) Address Book
	This section covers the use of address book aliases.
	Address book aliases are words that may represent e-mail addresses.  The words with the addresses they represent are stored in addressbook.inc.  This is the format:
	Alias user@domain.com

	Each alias and address pair belong on their own line.  Once aliases are set, they may be used in the to and from form fields instead of e-mail addresses.  spidermail.php will interpret the aliases accordingly.

	5B) Off-site Protection
	Off-site protection is required in v2.2 and must be configured prior to use.  See the Installation instructions above.

6. CHANGE LOG

4/23/2003: Version 2.1 allows optional protection from off-site use of the script.  Off-site protection prevents other Web sites from using your installation of spiderMail to process their forms.

10/15/2005: Version 2.2 requires off-site protection.  Off-site protection now supports the use of multiple forms.  Also, v2.2 allows the owner to decide whether HTML tags should be stripped from the user's form input.  By default, tags will be stripped.  For security reasons, this is the recommended setting.

7. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

8. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.

9. DONATE
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>