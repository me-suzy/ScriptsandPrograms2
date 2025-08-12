//---------------------------------------------------------
// EVENTIVE
// Version v0.1
//
// Written by Andrew Whitehead
// (c) Andrew Whitehead 2004
//
// e-mail: 	me@andrew-w.co.uk
// website: 	http://www.andrew-w.co.uk
//
// THANKS TO:
// Parag0n of thinkl33t.com - For thinking of the name
// JamesM (jamesm.com) - for trying to break it (BETA tester)
//
//---------------------------------------------------------

Before doing anything you should read the licence.txt

FEATURES:

	- Yesterday, today and tomorrow event detection
	- Checking for date validity
	- Ability to select number formatting
	- Alternating row colours
	- Easily changeble colours (CSS)


REQUIREMENTS:

	- PHP & MySQL

WARNING:

	Although I have tested Eventive there may still be bugs
	and errors. If you find any then please contact me and
	I will do my best to fix them. If you have any problems
	using the script then please let me know and I will do 
	my best to help you, although this is my first proper
	script and I'm pretty new to PHP. :P


INSTALLATION:

	STEP 1:	Unzip the zip file and go to your webspace control panel
	
	STEP 2: Create a MySQL database to be used by Eventive

	STEP 3: Open config.php and edit the variables

	STEP 4: Upload the contents of the folder into a directory in
		your webspace called eventive or similar

	STEP 5: In your browser go to 

			http://www.yoursite.com/eventive/install.php

		(assuming you installed into /eventive/ )

	STEP 6: If you get the success message then delete the install file,
		If not, then check your settings and try again, or you could
		run the MySQL query directly in PHPmyAdmin or similar:

CREATE TABLE events (id int(6) NOT NULL auto_increment,PRIMARY KEY (id),event varchar(40),date varchar(255))

	STEP 7: You're almost done! To put Eventive on your website you can
		either link directly to the folder you installed it in
		or use a iframe. An example of this is shown in iframe.html
		You can of course change the height and width to your liking

	STEP 8: You can customise the look of Eventive by changing the
		CSS style sheet.

	STEP 9: You're done!