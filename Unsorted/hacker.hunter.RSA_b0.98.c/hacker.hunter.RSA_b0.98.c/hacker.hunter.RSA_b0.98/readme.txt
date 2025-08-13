Hacker Hunter beta 0.96
-----------------

Installation:
-----------------
-create ant chmod to 666 .htaccess and .passwd files in protected folder
-edit inc.php and please set $dn_name variable to existing DB name
-upload login folder on server, chmod folder and everything inside to 644 and open in browser install.php
-----------------

Templates:
-----------------
You may edit template files (all .htm files) using any HTML or text editor
In templates code you may use #variable_name# combinations. For example if inside login.php there is $user_ip variable, it is possible to display value of this variable in any page generated from templates by login.php. It is enough to insert  
#user_ip# in any part of HTML code for displaying :) 


index.php use:
	-login.htm
	Page with main form for logging
	-login_js.htm
	Page with main form for logging using password JS encoding 


login.php use:
	-welcome_member.htm
	success page with change e-mail/password links and image from protected folder called using temp username and password
	-proxy_deny.htm
	proxy deny error page

user.php use:
	-change.htm
	Page with "thanks", "mail send" or "please check mail box" messages (inside pop-up window after change e-mail/password 		request) 
	-something_wrong.htm
	Wrong input or missing session data error messages.

activate.php use:
	-something_wrong.htm
	Wrong input error message.
	-password_changed.htm
	Success and "new password was mailed you" messages
	-email_changed.htm
	Success and "new e-mail address stored" messages
-----------------

LICENSE
-----------------
Scripts, ideas, software name and logo are created by "Polar Lights Labs" Co. and Ilya Rudev. 
You can use this software for non-commercial use for free.
Beta versions of this software only can be used for non-commercial use.
If you want to use this scripts while making money, please contact Victor <sensei@polar-lights.com>. 
You can modify and distribute this software as you like, but please leave links to http://www.polar-lights.com/hackerhunter/ in this file :)
The authors CAN NOT BE and WILL NOT be responsible for any direct or indirect damages caused by the using of this software.
-----------------

Take new versions at http://www.polar-lights.com/hackerhunter/
Support forum -> http://www.polar-lights.com/forums/

Team:
PHP/JS/Flash coding and idea:
Ilya Rudev
Please send questions and suggestions to Ilya <www@polar-lights.com> 

PHP/Java coding and English translating:
Victor Zlavski -> Victor <sensei@polar-lights.com>

PHP/Java coding
Alex Shurchin -> Alex <alex@polar-lights.com>
Victor Nesterov -> Victor <viks@polar-lights.com>
Boromir Svatnov -> Boromir <nox@polar-lights.com>

HTML, design and Spanish translating:
Alena Shurchina -> Alena <star@polar-lights.com>

Grammar error reports will be greatly appreciated. 
