  Copyright (C) 2004 CentralFloridaVA.com. All rights reserved.
  
  Released 2004-10-02

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
	
	Contact CentralFloridaVA.com at:
	http://www.CentralFloridaVA.com
	
	++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	PHPeasy-form was written as an alternative to CGI form handlers.  We
	realize the vast threat of hackers and spoofers and wanted to offer 
	a safer way for site owners to receive feedback.
	
	This readme file should provide everything you need to implement this 
	form handler.  Customization and installation services are available 
	for a nominal fee.  For more information, visit our website at
	http://www.CentralFloridaVA.com.
	
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
	
	SET YOUR RECIPIENT AND SUBJECT
	
	You will need to open form-send.php to set the recipient and subject.
	Update the email address in the "recipient" field on line 28 to be
	the email address where these form results should be sent.  Update the
	"subject" field on line 29 to have the text you want the email subject
	line to contain.
	
	CONFIGURE THE EMAIL RESULTS
	
	If you are using web-form.htm and made no changes to the fields, you
	may skip this section and continue with "UPLOAD THE FILES".
	
	If you are using a custom form or if you made changes to web-form.htm,
	open the form-send.php file.  Starting on line 31, you will need to update
	the field names to match those used on your web form.  The input fields
	begin with $ and must match the label you gave your input field exactly.
	For example, if you have a field named fax, the line in form-send.php
	should be Fax: $fax\n.  You should keep the \n at the end of each row.
	The \n  tells the script to start a new line before the next entry.
	
	UPLOAD THE FILES
	
	Upload your form file and form-send.php to your web server.  Be sure both
	files are in the same folder.  Test your form to be sure the results
	are displayed correctly.
	
	That's it.  You are now ready to accept feedback through your web form.