#####################################################################################
##                                                                                 ##
##                                                                                 ##
##  FILE: readme1_1.txt                                                            ##
##                                                                                 ##
##  Novice Form                                         Version 1.1                ##
##  © Copyright 2000-2004+ Seth Michael Knorr           mail@sethknorr.com         ##
##                                                                                 ##
##                     http://www.noviceform.com/                                  ##
##      Please contact me with any bugs found, or any bug fixes.                   ##
##                                                                                 ##
#####################################################################################
##                                                                                 ##
##  There is no email support provided for this script,                            ##
##  the only support can be found at                                               ##
##  our web site: http://www.noviceform.com/                                       ##
##                                                                                 ##
##                                                                                 ##
##                                                                                 ##
##  ANY PERSON(S) MAY USE AND MODIFY THESE SCRIPT(S)                               ##
##  FREE OF CHARGE FOR EITHER BUSINESS                                             ##
##  OR PERSONAL, HOWEVER AT ALL TIMES HEADERS                                      ##
##  AND COPYRIGHT MUST ALWAYS REMAIN IN TACT.                                      ##
##                                                                                 ##
##  REDISTRIBUTION OF ANY KIND IS PROHIBITED                                       ##
##  WITH OUT THE CONSENT OF SETH KNORR.                                            ##
##                                                                                 ##
##  By using this code you agree to indemnify Seth M. Knorr                        ##
##  from any liability that might arise from its use.                              ##
##                                                                                 ##
##                                                                                 ##
#####################################################################################
##                                                                                 ##
##  This script is FREE, however if you use the script and                         ##
##  find it useful, I would appreciate you rating it.                              ##
##                                                                                 ##
##  TO RATE IT SIMPLY DOUBLE CLICK OPEN THE rateit.html                            ##
##  FILE LOCATED IN THIS ZIP FILE!                                                 ##
##                                                                                 ##
#####################################################################################
##                                                                                 ##
##               T H A N K   Y O U   I N   A D V A N C E   F O R                   ##
##                 S U P P O R T I N G   M Y   S P O N S O R S                     ##
##                                                                                 ##
#####################################################################################


                           ===========================
                                     S E T U P 
                           ===========================          

TABLE OF CONTENTS:

(1) CONFIGURING THE FILES

......(1A) nvform.php settings
......(1B) Creating your forms html page or configuring the nvform.html Template File

(2) UPLOADING FILES                                 
==========================================================


    (1)  C O N F I G U R A T I O N   S E T T I N G S
                           

                           ===========================
		(1A) Set the variable values in nvform.php as follows. 
                           ===========================

*** For added privacy and security the $sendto and $ccto reply emails are set in the actual 
nvform.php script, preventing un-authorized person(s) from using your script to post 
their own forms to send spam. 

-----------------------------
$sendto


$sendto is the email address the data from form submissions will be sent to,

Example:
$sendto = "sendto@yourdomain.com";

-----------------------------
$ccto


$ccto is the email address the data from form submissions can be carbon copied to.
If you don't want to carbon copy the data to a second email just ignore this, and leave blank.

Example:
$ccto = "ccto@yourdomain.com";

------------------------------

    O P T I O N A L   V A R I A B L E S

$setokurl

to use $okurls to verify the URL the form is submitted by; set $setokurl = "1"; and 
set $setokurl = "0"; if you do not want to use $okurls to verify form submission URL.
Example:
$setokurl = "0"; /* WOULD NOT REQUIRE URL VALIDATION */
$setokurl = "1"; /* WOULD REQUIRE URL VALIDATION */

Only web address's entered below will be allowed to process the form if 
$setokurl = "1";

$okurls = "http://www.yourdomain.com,http://yourdomain.com,34.344.344.344";

Note: you must include the http:// or https://


                           ===========================
                      (1B) Creating your forms html page 
                           or configuring the nvform.html Template File
	     (This is the page your visitors will see and fill out on your site.)
                           ===========================

<!----- START FORM WITH THE BELOW LINE, THIS CALLS THE SCRIPT IN YOUR HTML DIRECTORY ---->

<form method="post" action="nvform.php">
<!----- OR ----->
<form method="post" action="http://www.yourdomain.com/nvform.php">
<!--- Replace: http://www.yourdomain.com/nvform.php with the URL to nvform.php --->


<!--- "subject" IS THE SUBJECT LINE YOU WILL SEE WHEN YOU RECEIVE THE FORM DATA ON YOUR EMAIL---->

<input type="hidden" name="subject" value="Novice Form Submission">


<!--- "success_page" IS WEB SITE THE PERSON WILL BE SENT TO AFTER SUCCESSFULLY COMPLETING THE FORM --->
<!--- LEAVE FIELD BLANK OR OUT OF FORM TO USE THE DEFAULT nvform.php SUCCESS PAGE. --->

<input type="hidden" name="success_page" value="http://www.yoursuccesspage.com/success.html">


<!-----  "required" ARE ALL THE FIELDS YOU WANT TO FORCE TO BE FILLED IN.  ---->

<input type="hidden" name="required" value="email,firstname,nextrequiredfield,etc">


<!-----  "sort" is another optional field and SHOULD ONLY BE IN THE FORM if 
you want to sort the field order in the results email you will receive.
You can also leave fields out of the form by not including them in this field.
If using this option, enter all the fields you want to be added to the results 
email starting with the first you want to apear and ending with the last you want
to appear and separate each field with a coma. 
Example:  value="email,firstname,lastname,phone"   ----->

<!--- NOTE: The date and IP Address will automatically be added to the top of every
form and does not need to be configured in the sort field.      
AS SEEN IN EXAMPLE BELOW    ----->

<input type="hidden" name="sort" value="email,firstname,etc">



<!----- THE "email" FIELD IS A FIELD THAT IS OPTIONAL. HOWEVER CAN BE USED TO SPECIFY POSTERS EMAIL. --->

<input type="text" name="email" value=""> 



	THEN SIMPLY ADD OTHER FIELDS THAT YOU WANT THE "FORM POSTER" TO FILL IN, AND A SUBMIT
	AND YOU ARE ALL SET. YOU CAN ALSO USE THE nvform.html AS AN EXAMPLE FORM.


                           ===========================
                                (2)  UPLOAD FILES
                           ===========================

                  Upload ALL FILES to a html readable directory.
                  Upload ALL FILES to the SAME directory.

SIDE NOTE: For Perl programmers new to PHP; permissions should be left set to 644.

                           ===========================
                                       D O N E ! ! ! 
                           ===========================

                            THAT'S IT YOUR DONE, 
                            IT IS VERY SIMPLE TO SET UP. 
                            FOR HELP INSTALLING GO TO: 
                            http://www.noviceform.com/

                           ===========================

                            This script is FREE, however 
                            if you use the script and 
                            find it useful, I would 
                            appreciate you rating it.

                           TO RATE IT SIMPLY DOUBLE 
                           CLICK OPEN THE rateit.html 
                           LOCATED IN THE DOWNLOAD ZIP FILE.

                           =========================
