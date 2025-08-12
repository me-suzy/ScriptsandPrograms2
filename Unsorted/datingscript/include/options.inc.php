<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               options.inc.php                  #
# File purpose            Additional configuration file    #
# File created by         AzDG <support@azdg.com>          #
############################################################

### Templates 
define('C_TEMP','default'); // Template name
define('C_FROMM','noreply@your_site.com'); // "Email From" for forgot password
define('C_LASTREG','10');// Last C_LASTREG registered users
define('C_APAGE','10');// Count of users on default in admin page
define('C_CPAGE','5');// Search page links number 12345...

### Profiles automatic allows
define('C_CHECK_REGISTER','0'); 
/* 
'0' - Automatically allow all profiles to database
'1' - Add profiles after confirm from user email
'2' - Add profiles after admin checking
'3' - Add profiles after confirm from user email and after admin checking 
*/

define('C_REG_DAYS','7'); // Number of days for waiting user mail autorization (If C_CHECK_REGISTER '2' and '4')
define('C_UPDATE_ALLOW','1'); // Allow profile after update? 0 - Don`t allow - send for admin check, 1 - allow
define('C_REMOVE_ALLOW','1'); 
/* 
'0' - Disable remove profile for user
'1' - Remove profile after admin check (Show profile while admin don`t checked)
'2' - Remove profile after admin check (Don`t show profile while admin don`t checked)
'3' - Automatically remove profile from database 
*/

define('C_SHOW_LANG','2'); // Show other languages???
/*
0 - Don`t show - use only default language
1 - Don`t show - use user browser language
2 - Show languages only if language don`t selected by user - else don`t show // Recommended
3 - Always show languages
*/

define('C_MULTLANG_DEF','1'); // Default language for multilanguage (C_SHOW_LANG = 2 or 3) work 
/*
0 - Use language from default file
1 - detect user language from browser and use it (use default language if language no detect, or have`nt this language dir)
*/
 
### Firstname biggest and smaller length, and requirements
define('C_FIRSTNB','16'); // Max chars in firstname(no more 16)
define('C_FIRSTNS','2'); // Min chars in firstname

### Lastname biggest and smaller length, and requirements
define('C_LASTNB','30'); // Max chars in lastname(no more 30)
define('C_LASTNS','2'); // Min chars in lastname

### Biggest and smaller ages
define('C_AGES','14'); // Age from!
define('C_AGEB','60'); // Age to!

### Password largest and smaller length
define('C_PASSB','16'); // Max chars in password(no more 16)
define('C_PASSS','2'); // Min chars in password

### Other require questions!
define('C_FIRSTNR','1'); // Firstname require? Recommended set to '1'
define('C_LASTNR','1'); // Lastname require? Recommended set to '1'
define('C_BIRTHR','1'); // Birthday require? Recommended set to '1'
define('C_GENDR','1'); // Gender require? Recommended set to '1'
define('C_CNTRR','1'); // Country require?
define('C_CITYR','0'); // City require?
define('C_PURPR','1'); // Purpose require? Recommended set to '1'
define('C_MARSR','0'); // Marital status require?
define('C_HOBBR','0'); // Hobby require?
define('C_WGHTR','0'); // Require weight?
define('C_HGHTR','0'); // Require height?
define('C_CHILDR','0'); // Children require?
define('C_HAIRR','0'); // Hair color require?
define('C_EYER','0'); // Eyes color require?
define('C_ETNR','0'); // Etnicity require?
define('C_RELR','0'); // Religion require?
define('C_SMOKER','0'); // Smoke require?
define('C_DRINKR','0'); // Drink require?
define('C_EDUCR','0'); // Education require?
define('C_JOBR','0'); // Job require?
define('C_PHOTOR','0'); // Photo require?
define('C_PHONER','0'); // Phone require?
define('C_HDYFUR','0'); // "How did you find us?" require?
define('C_SETNR','0'); // Seeking for etnicity require?
define('C_SGENDR','0'); // Seeking for gender require?
define('C_SRELR','0'); // Seeking for religion require?
define('C_SAGER','0'); // age require seek from
define('C_SWGHTR','0'); // Require seek weight?
define('C_SHGHTR','0'); // Require seek height?
define('C_AGR','1'); // Agreement is require?

// Show in view profile? [view.php]
define('C_HOROSH','1'); // Show horoscope?
define('C_EMAILSH','0'); // Show email? - Not recommended - set to 0
define('C_URLSH','1'); // Show webpage?
define('C_ICQSH','1'); // Show icq?
define('C_AIMSH','1'); // Show AIM?
define('C_PHONESH','1'); // Show phone number?
define('C_REGDATE','1'); // Show register date?
define('C_ACCDATE','1'); // Show update info date?
define('C_MAILSH','1'); // Show "mail to user" link?
### City largest length
define('C_CITYB','32'); // No more 32

### Hobby largest word size and length, and require
define('C_HOBBW','40');
define('C_HOBBB','255');

### Description largest word size and length
define('C_DESCW','40');
define('C_DESCB','2000');

### Max image size (kb), image width and height
define('C_MAXSZ','100');
define('C_MAXWD','600');
define('C_MAXHG','600');
define('C_IMG_ERR','1');



### AntiHack checks - recommend set to '1' for secure reasons
define('C_HACK1', '1'); // Check for numeric select
define('C_HACK2', '1'); // Check for numeric select for normal value
define('C_HACK3', '0'); // Check for image weight and height (set to 0 if give Warning: 'open_basedir restriction in effect')

### Members features
define('C_FEEDBACK','0'); // Feedback only for members?
define('C_FEEDBACK_MAIL','1'); // Can registered user specify another email for reply in send feedback?
define('C_SEARCH','0'); //Search only for members?
define('C_VIEW','0'); //View users only for members?
define('C_MAIL','1'); //Mail to users only for members?
define('C_ANOTHER_MAIL','1'); // Can registered user specify another email for reply in send mail?


### Login 
define('C_ID','2'); // Login by ID?
/*
0 - Login by username [Only for Platinum version]
1 - Login by ID
2 - Login by email
*/

 
### Don`t change anything below
### Other - DON`T change for secure reasons!!!
define('C_MUST','1'); // Must be login!
define('C_SESS','0'); // Don`t working if cookies disabled!
define('C_UNICM','1'); // Use unique email for each user? Very recommended set it to 1 - Don`t change
?>