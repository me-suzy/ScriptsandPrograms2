   Copyright (C) 2004-2005 SunFrogServices.com. All rights reserved.

   PHPeasy-form version 2.0
   Released 2005-05-16

   This file is part of PHPeasy-form.

   PHPeasy-form is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

    PHPeasy-form is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PHPeasy-form; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	Contact SunFrogServices.com at:
	http://www.SunFrogServices.com
	
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	PHPeasy-form was written as an alternative to CGI form handlers.  We
	realize the vast threat of hackers and spoofers and wanted to offer 
	a safer way for site owners to receive feedback.

	This form handler will send the feedback to an email address you 
	specify and will also write the results to a file on your website.
	
	This readme file should provide everything you need to implement this 
	form handler.  Customization and installation services are available 
	for a nominal fee.  For more information, visit our website at
	http://www.SunFrogServices.com.
	
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	BEFORE YOU BEGIN
	
	Be sure the web host you have selected supports PHP.  We have included a
	phpinfo file to help you with this.  Upload phpinfo.php to your web
	directory.  Point your browser to http://domain.com/phpinfo.php 
	(replacing domain.com with your own domain).  If a PHP information page is
	returned, you are good to go.  If you receive an error message, stop now --
	your web host does not support PHP and you will not be able to use this
	script.
	
	CREATE YOUR FORM
	
	Included with this archive is a basic feedback form called "web-form.htm".
	If you are familiar with web forms, you can customize the input fields as
	necessary to meet your needs.  Be sure to give each input field a unique
	name (label).  For the purpose of this readme file, we will assume you
	are using the web-form.htm file.  If you use your own form, simply
	copy/paste the form tag below near the top of your webpage.
	
	<form action="form-send.php" method="post">
	

	CONFIGURE THE WRITE-TO-FILE RESULTS
	
	If you are using web-form.htm and made no changes to the fields, you
	may skip this section and continue with "UPLOAD THE FILES".
	
	If you are using a custom form or if you made changes to web-form.htm,
	open the form-send.php file.  Starting on line 34, you will need to update
	the field names to match those used on your web form.  The input fields
	begin follow the examples given - including the $_POST and must match the 
	label you gave your input field exactly.

	For example, if you have a field named fax, the line in form-send.php
	should be Fax: $_POST['fax'].  You must have the following at the end of
	each field:	. "," .

	SET YOUR RECIPIENT AND SUBJECT
	
	You will need to open form-send.php to set the recipient and subject.
	Update the email address in the "recipient" field on line 46 to be
	the email address where these form results should be sent.  Update the
	"subject" field on line 47 to have the text you want the email subject
	line to contain.
	
	CONFIGURE THE EMAIL RESULTS
	
	If you are using web-form.htm and made no changes to the fields, you
	may skip this section and continue with "UPLOAD THE FILES".
	
	If you are using a custom form or if you made changes to web-form.htm,
	open the form-send.php file.  Starting on line 49, you will need to update
	the field names to match those used on your web form.  The input fields
	begin follow the examples given - including the $_POST and must match the 
	label you gave your input field exactly.

	For example, if you have a field named fax, the line in form-send.php
	should be Fax: $_POST['fax'].  You must have the following at the end of
	each field:	. "\r" .

	
	UPLOAD THE FILES
	
	Upload your form file, form-send.php and a blank text file to record your
	form results.  A sample file named form-results.txt has been included here.
	I strongly urge you to change the name of this file for security.  Be sure
	to change the name in your form-send.php file to match.  Test your form to 
	confirm the results are displayed correctly and the information is being
	stored in the online file.
	
	That's it.  You are now ready to accept feedback through your web form.

	VIEW RESULTS FILE

	To view the results online, point your browser to
	http://path.to.file/form-results.txt

