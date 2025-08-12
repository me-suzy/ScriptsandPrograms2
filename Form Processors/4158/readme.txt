EZ php Form-Mailer v 1.21 Read Me
================================


Written by Lev <lev@taintedthoughts.com>
Written & Released: February 20th 2005

This simple little script allows you to have forms on your site email you. It was designed to be used as a contact form script. In other words, you create a simple form on your site where users can contact you. The script is then executed and you are emailed if successful. This program has been designed to be used for as many forms as you desire. Additionally, it allows you to alter the recipient and subject through forms you create on your pages. By doing this, you can use this same one script for 1,000,000 different forms on your site if you desired in which you could change the recipient and subject for every form.

INSTALLATION GUIDE
 1) Requirements
 2) Customizing the script
 3) Creating the form elements
 4) History / Help / Troubleshooting


1)===========REQUIREMENTS==========

php 4 or > acess, access to mail server on your host, an email address ;P


2)===========CUSTOMIZING THE SCRIPT==========

First things first, you need to change a few things in the script. Open it up.

On line 14, you will see the variable $GLOBALS['template']; you ONLY need to change this if you are using a template named something other than "template.html". EZ php Form-Mailer v 1.1 and up all are packaged with a default template file which will work just fine. You do not have to use this file; you are free to design or use your own, but you must enter the correct path to a template which contains the text string $data. For every instance that $data appears in the HTML code of the template file, the form_mailer text data will be displayed. On line 17, you will see the variable $requireemail. The "n" between the quotes represents "no" and therefor this option is turned off. If you turn the "n" to "y" you are enabling this option. By enabling the $requireemail, the script now requires that every attempt to contact you MUST have a valid email address provided by the user! If you do not want to require that users provide you with their email, then keep it on "n". On line 18 you will see the variable $requirerefer. This is also set to "n" (no) by default. If you change this variable to "y" you are telling the script to require that any attempt to process the script MUST be coming from your servers you have validated! Line 19 contains the variable $requireicode and is disabled by default, but may become very handy to many of you out there who are sick of spam! If you change $requireicode to "y", then you are requiring that users enter a 5 character long code which is displayed as an image on the form when they try to write you. If they do not enter the code properly then the email will not be sent! The purpose of this feature is, of course, to prevent spam bots from processing your forms sending you junk you don't want. Only a real person is capable of telling what is displayed in the image itself, and since that code is not found anywhere in the HTML code itself, it prevents spam bots from processing the contact form! You are strongly encouraged to use this option if you don't want spam! If you enable $requirerefer (on line 18) then you will also need to set line 20, which contains the variable $validservers. Inside the quotes simply write as many servers you want to allow to contact this script. Each server should be separated by a comma. It is necessary to set line 23 ($defaultrecip). You should just set this to the main recipient who will be receiving the emails. Anytime a recipient isn't defined in the form it will go to the default recipient. On line 26 you can change the $defaultsubject variable. Whatever you set this to is what the subject will be for all emails sent to you that did not have a "subject" in the form. On line 29 is the variable $defaultpage. Write the URL of the thank you page a user will be directed to upon processing the form. You do not need to change this if you are happy with the thank you page that is provided by this script; if you want your own page they will go to write the URL of the page here.

NOTE: As of version 1.1, it is absolutely necessary that you use a template file. If this concept confuses you, simply make sure you store form_mailer.php in the same directory as template.html and you do not need to play with the $GLOBALS['template'] variable! It is merely there for advanced users who wish to play with their own templates.


3)==========CREATING THE FORM ELEMENTS==========

*NOTICE*
As of version 1.1, you no longer need to create your own form as a default form has been built
into the script. To view the form simply enter the URL of your form_mailer.php script.

Every form element field is optional; no fields are required. You may, however, require that a valid user email address is required. This can be acheived by setting $requireemail to "y". If you do not provide a recipient in the form than the default recipient in the script will be used. The same goes for subject and name. You should also obviously create a textarea and give it the name `message`. This can be done as seen below:

<TEXTAREA ROWS=6 COLS=50 NAME=message></TEXTAREA>

Allowing the user to write in their own subject is optional. You do not need to include this form element as the default subject in the script will be used instead. If you would like to allow users to include their own subject then use this:

<INPUT TYPE=TEXT NAME=subject>

To collect the users email address use:

<INPUT TYPE=TEXT NAME=email>

To get the users name use:

<INPUT TYPE=TEXT NAME=name>

If you would like to define a customized thanks page for that specific form than you would use:

<INPUT TYPE=HIDDEN NAME=thankspage VALUE="http://www.server.com/url.html">

Obviously, you would change http://www.server.com/url.html to the address of the page that you would like the users to be sent to upon executing the script. If you do not provide this element than the default thanks page from the script will used instead.

You may also throw in your own form fields and they will be processed in the mail you receive. For example you could add a field named "website" where the user can enter their website URL. Extra fields are processed and included in the email you will receive as of version 1.1!

So all in all, an example form could look like below:

<FORM ACTION=form_mailer.php METHOD=POST>

your name: <INPUT TYPE=TEXT NAME=name><BR>
your email: <INPUT TYPE=TEXT NAME=email><BR>
subject: <INPUT TYPE=TEXT NAME=subject><BR>

<TEXTAREA ROWS=6 COLS=30 NAME=message></TEXTAREA>

<INPUT TYPE=HIDDEN NAME=thankspage VALUE="thanks.html">

</FORM>

NOTICE: If you have enabled the image code option (line 19), and plan to create your own forms and don't want to use the pre-installed form that came with the script: KEEP SOMETHING IN MIND! You must also add a text field to your form called "icode", which represents the value of the image code that the user will enter. Then to make the image code itself appear on your form call the image like any other image using the IMG tag. (i.e.: <IMG SRC=form_mailer.php?imagecode> )

*NEW TO 1.1*
If you use form fields that EZ php Form-Mailer does not recognize (other than those such as "name", "subject"
and "message") the results of the user's input will be added into the email message as well (before the message text).


4)==========HISTORY / HELP / TROUBLESHOOTING==========

 Dec 4 2004 - released
 Dec 28 2004 - added default form page, processes all $_POST data; allowing users to use their own fields as well, fixed code with required email problem and replaced $_POST[name] with $_POST['name'] everywhere to insure compatibility
 Feb 20 2005 - fixed validating emails as to allow for international email addresses (peter.johanson@sub.domain.com) and added image code feature to prevent spam bots from processing your forms!
 Feb 21 2005 - no longer adds 'icode' field into emails that get sent

If you have any problems with the installation or use of this script you are more than welcome to contact lev@taintedthoughts.com. Likewise if you have ideas for improvement, suggestions, comments, complaints or concerns you are more than welcome to provide me with them.

Thanks for visiting www.pixelatedbylev.com!