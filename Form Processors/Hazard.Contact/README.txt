Hazard Contact - Written by Joseph George Jacobs (joe@hazardcell.com) Version 2.0

====================================

HOW IT CAME ABOUT!

v1.0-This is the contact form script I use at hazardcell.com. Pretty simple to understand and use. Nothing fancy. Definately 
could use some modification such as email validator or something. Please post suggestions at hazardcell.com forums.

v2.0-I have made 3 main modifications. The script now uses the POST method, not GET, to specify the page to display. The 
script also now checks that all form fields are complete and makes sure the email address in valid before sending the email. 
The template is now is also more complete than before.

====================================

HOW IT WORKS!

Basic, when the php file is called, it displays a contact form. The visitor types in the details then clicks send. The form 
is then checked for errors. If there are errors, the visitor is taken back to undo the errors. Otherwise, the message is sent
to your email addy.

====================================

WHAT YOU NEED!

Basically, all you need is a web server with PHP installed and of course an email address. 

This script was tested with RedHat(tm) Linux and Windows(tm) both with Apache as the webserver. The PHP version in use was
PHP 4.3.9

====================================

HOW TO INSTALL IT!

First, customize the 2 templates to taste(contact.html and thanks.html) Then, open Hazard.Contact.php and edit the 3 
variables on the top ($adminemail, $contactpage, $ thankspage, etc.) and you're ready. Upload the files to your server and 
call the .php file using a web browser. Note that the user does not see the templates throughout the process. He only sees 
the .php file.

If you change the name of the .php file, which I think you should, please also edit the contact.html file. Change

<form method=post action=Hazard.Contact.php>

to 

<form method=post action=filename.php>

If you have already created your own contact form and want to use it, here are a few pointers:

1) In 1.0 you had to set the action to filename.php?do=send. From version 2.0 onwards, just link to filename.php only.
2) Do not leave out the hidden input in your contact form. If you do the form won't send.
3) For the errors, follow the example I've given. It's simple to understand

====================================

WHAT CAN I DO WITH THE SCRIPT?

Well, duh, use it as your contact form script.

====================================

LICENSE

This work is licensed under the Creative Commons Attribution-ShareAlike License. To view a copy of this license, visit
 
http://creativecommons.org/licenses/by-sa/2.0/ 

or send a letter to 

Creative Commons, 
559 Nathan Abbott Way, 
Stanford, 
California 94305, 
USA.

====================================

There are currently no questions associated with the script, however if you have a problem, feel free to stop by the site and 
let me know or post a question at the forum (http://www.hazardcell.com/forum)

-- Joe