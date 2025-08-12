/*******************************************
* (C) Software Copyright Acumen Software
* Mail us at support@scalebrain.com for any help.
* Visit http://www.scalebrain.com for more info. 
********************************************/

/********************************************
* This Version allows you to install Standard Edition on your localhost.
* Details of the Personal Edition can be found at : 
* http://www.scalebrain.com/standard_edition.htm
* To get your license, please mail us at support@scalebrain.com .
*********************************************/

Copy all the files in desired folder of web server.

Update 2 files with database details and server path. These 2 files and there content to modify are as following -

Root/config.php -
$apppath="/myexam"; 
- Assign the path of folder from root in which you copied the files.

$webpath="http://192.168.0.21"; 
$webpath1="http://www.192.168.0.21";
- The above 2 variable holds the site url. Set the exact website url in $webpath variable and other in $webpath1 variable.

$DBHost = "192.168.0.21";
- Assign database host here.
$DBUser = "root";
- Database username here.
$DBPassword = "";
- Database password here.
$DBName = "db_name";
- Database name here.

Root/examination/exam_config.php -
Here you will get all the variables set in config.php, so do as you did in config.php.

Root represents the folder in which you copy the files

Give permission to the folder as following -

root/upload - 777
root/temp - 777


To test that you set up correctly - 
Do a registration with some valid mail id.
Send a feedback from header or footer with attachment.

StartUp -
---------

You need to login as superuser/superuser. This is a super user to access the site. Please change the password after login for security purpose. 
After login you can create exam, users and other things.

You need to create pool, skill and topic before actually starting creating the exam.