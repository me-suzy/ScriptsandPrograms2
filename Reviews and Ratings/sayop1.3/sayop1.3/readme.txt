
/*******************\
|                   |
|     SayOp 1.3     |
|
     readme        |
|                   |
\*******************/

====================|
Tested on:

PHP 4.3.9 and 5.0.2
MySql 4.0.21

====================|
Requirements:

-PHP 4
-MySql database: Create a Mysql database on your server (UNLESS you already have one!) to store the necessary tables.
-Some HTML knowledge

====================|
Installation and Setup instructions:

1.	Unpack the script files to a directory on your hard disk.


2.	Open and edit db.php (com/db.php)
  	Modify the database location, username, password, name and table prefix. This will grant the script access to the database.


	Open and edit auth.php (inc/auth.php)
	Modify the administrator username and password that you will be using to access the SayOp admin control panel.
	Enter the full url to the script directory where you will upload the files.
	
	You can also enable email alerts with this file. (optional)

3.	Upload all the script files to a directory on your server 
	eg: /sayop 

	Make sure you keep the exact file structure.

	Open setup.php (found in the SayOp directory) and follow the instructions.

Thats it!

To show the comments on a page copy and paste the following code:


<?php
define(PATH, "Path_To_Sayop_Directory_Goes_Here");
define(ID, "ID_Goes_Here");

include(PATH."/sayop.php");
showComms(ID,PATH);
?>


Modify the values inisde the double quotes in the first two lines. 
Write the path to where the SayOp files are stored in the first line (NO trailing slash) and the ID of the article in the second line.
You can find the ID's of each article in the Control Panel.

Example:

<?php
define(PATH, "php/comments");
define(ID, "3");

include(PATH."/sayop.php");
showComms(ID,PATH);
?>


MAKE SURE YOU GET THE ID RIGHT AND THE FILE HAS A .PHP EXTENSION!

Also you need to include the SayOp stylesheet in the HEAD of every page you display the comments. Use:

      <link rel='stylesheet' type='text/css' href='path_to_sayop_dir/sayop_style_1.css' />



To show MORE then one set of comments on the same page, simply use the code above for the first set of comments and then use:

<?php
showComms(ID_Goes_Here , PATH);
?>

for the rest. (Just enter the ID).

====================|

Thank you and enjoy this script!

For support please visit www.devplant.com/sayop

Alex B (dirmass [ at ] devplant [ dot ] com) 