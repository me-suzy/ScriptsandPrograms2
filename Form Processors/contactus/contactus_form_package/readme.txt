-------------------------------------------------
Product name: SpiceScript Contact Us Package
Web: http://www.spicescripts.com/contactus_script
Email: office@spicescripts.com
-------------------------------------------------

		Install instructions
		--------------------

The instructions are for contact_a01 form. If you choose
to use other look then you should replace 'a01' with
the desired number, for example 'a05'. There are 21 looks
from 'a01' to 'a21'

You will find the preview for all looks at:

http://www.spicescripts.com/contactus_script/demo.html


Steps:

1. Copy into your website directory the following files:

contact_a01.php		(or contact_a02....contact_a21)
config.php
submit_off.gif
reset_off.gif

2. Modify your contact us page inserting in it the following line
in the place you want the contact us form to appear.

<?php contact_a01.php ?>

3. Modify config.php acording to your settings.
The process is very simple.

You should modify:

$to="sales@spicescripts.com";

Instead of "sales@spicescripts.com" you shoud put your email.

$contact_us_text="Contact us form.";
$your_text="Put your own text here.";

These variables are used to display the text you want in the 
contact form. Modify it at your will.

$sent_message="Your message has been sent!";

This is the message that will apear after the form is sent.

$small_text="(fill in the fields and press submit)";

This is a small text that appear after the normal text. 
If you need it you will put to display not so important
informations.

$contact_page="test.php";

This last variable must have the name of your contact page,
the place where you inserted the code at the point 1.
If this name is not the same as your contact page (the page
where the form is inserted) after you press submit nothing
will happend.

---------------------------------------------------------------
If you encounter any difficulties please contact us and we will
solve quickly your problem.

SpiceScripts Team
