===================================
Atrise PHP Script Debugger 1.1.0
Copyright (C) 2005, Atrise Software
===================================

===============
Version history
===============

Version 1.1.0 - Nov 26, 2005

*Restriction by IP is added; 
*HTML source code does not show debug code now; 
*HTML source code highlighting corrected; 
*Minor changes and bugfixes. 

Version 1.0.0 - Oct 01, 2005

*The first public release.
  

==========
Files List
==========

readme.txt - this file
license.txt - license agreement
debug.php - the debugger include file
debug.css - CSS file
debug.js - JavaScript file
demo.php, demo.html - Example of using PHP Script Debugger


============
Requirements
============

PHP 4 and later is required on any Unix- or Windows- PHP-enabled web host


==============
How to install
==============

Place debug.php, debug.css and debug.js to your web project.
Open debug.php to edit and corect a path to debug.css and debug.js files.
Correct another debugger settings.


==========
How to use
==========

Open your PHP page to edit and insert this line to the begin of your script file:

    <?php include 'debug.php';?>

This include must be the first in your includes.

Insert this line to your <head>...</head> section:

	<?php debug_head();?>

Insert this line to the end of your script file:

    <?php debug_foot();?>


You can show special debug labels in the generated HTML code.    

To show the execution time insert this line to the correspondent place of your script:

    <?php debug_point('');?>
	
To show the execution time and a php variable or an array use this line:

    <?php debug_point('your_variable_name');?>

You can show different php variables. Simply type your list as example:

    <?php debug_point('your_variable_name,another_variable,more_variables');?>

To show debug message insert this line:

    <?php debug('Your message here');?>


===============
Support Service
===============

Atrise Software Co. home page:
      http://www.atrise.com/

Product home page:
      http://www.atrise.com/php-script-debugger/

Product support page:
      http://www.atrise.com/php-script-debugger/support.html

Product full version order page:
      http://www.atrise.com/php-script-debugger/buy.html

