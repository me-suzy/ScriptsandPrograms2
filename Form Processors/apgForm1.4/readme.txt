apgForm 1.4
------------------------------------------------------------------------------------------------------------
Site: http://www.apg88.com/apgForm/
Email: apgForm@apg88.com
-------------------------------------------------------------------------------------------------------------

You will need:
   1. A PHP enabled host (running PHP 4)
   2. A folder in your server with read/write permissions to work. (CHMOD 777)
   3. Microsoft Excel or a similar program that can open xls files.

Installation:
   1. make any changes you need to apgform.php
   2. upload it to your server and CHMOD the folder its in to 777
   3.  That's it!

apgForm is a PHP file capable of processing web forms and saving them directly into an Excel file.
apgForm receives any form with any number of textboxes, radio buttons, drop-down menus, hidden fields, and password boxes and saves them to an Excel file. 
apgForm can receive the form regardless of the way it was sent (POST, or GET.) 
The file is named form.xls by default, but can be specified by sending a hidden field with the name of the file. For example, putting this on the form would make the file test.xml 
<input type="hidden" name="filename" value="test">.
apgForm is completely free! 

apgForm 1.4 Release Notes:
apgForm now works with tabs in the text fields without altering the cells in the excel file.

apgForm 1.3 Release Notes:
Fixed bugs in the Title and Filename exceptions.

apgForm 1.2 Release Notes:
Added feature that removes the escape character "\" from the posted data.

apgForm 1.1 Release Notes:
apgForm now receives the page title to be received when the form is loaded. The title is what goes in the <title></title> tags in html.
The excel file doesnt mess up with line breaks (enter, new line, etc). It replaces the line brakes with a user defined character or characters; it is a space by default.


Current Issue(s):
apgForm does not support the use of checkboxes. I am(still) trying to fix that, and will update as soon as I can.



Copyright © 2004 apg88.