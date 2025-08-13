dB Masters' PHP FormMailer 3.0.1

#########################################################
#                                                       #
# PHPSelect Web Development Division                    #
#                                                       #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are distributed through         #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated.                                     #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the distriuters      #
# at admin@phpselect.com                                #
#                                                       #
#########################################################

INSTALLATION AND CONFIGURATION

** One major change in version 3.0. You no longer link to emailer.php. You link to whatever form you want to process.
   Now, the action of that form has to direct to emailer.php as shown in the sample form.

1- Edit emailer_form.php (or any other form for that matter) by building the form you desire. See reference below for hidden field options.
2- Be sure the action of the form is aimed at the emailer.php file
3- Build the "Thanks" and/or "Error" page(s) if you choose the custom option
4- Edit the top few variable at the top of emailer.php to suit your needs, the available variables are as follows:

// recipient configuration - this is the array of recipients for the form, the number in the brackets 
// must match the number in the "recipient_group" hidden field explained below
	$tomail[0]="";
	$cc_tomail[0]="";
	$bcc_tomail[0]="";
	$tomail[1]="";
	$cc_tomail[1]="";
	$bcc_tomail[1]="";
	$tomail[2]="";
	$cc_tomail[2]="";
	$bcc_tomail[2]="";
// General Variables
// whether or not to check the referrer, 1 for yes, 0 for no
	$check_referrer=1;
// domains allowed to use the emailer, with and without www
	$referring_domains="http://domain.com/,http://www.domain.com/";
// Default Error and Success Page Variables
	$error_page_title="Error - Missed Fields";
	$error_page_text="Please use your browser's back button to return to the form and complete the required fields.";
	$thanks_page_title="Message Sent";
	$thanks_page_text="Thank you for your inquiry";

HIDDEN FIELD OPTIONS
subject - The subject of the email to be sent
reply_to_field - The name of the field that should be put in the "from" field of the email
required_fields - a comma separated list of fieldname to be validated for content
required_email_fields - a comma delimited list of fields to be validated for valid email address syntax
recipient_group - the number that refers to the number in the brackets of the list of recipients to recieve this email
error_page - the filename (and path if necessary) to a custom error page, if missing or blank default text in variable above will be used
thanks_page - the filename (and path if necessary) to a custom thanks page, if missing or blank default text in variable above will be used
send_copy - whether or not to send a copy of the mail to the sender
copy_subject - subject of sent copy
copy_tomail_field - field of email address to the sender for the sent copy
mail_type - layout of email options are "vert_table" or "horz_table", anything else will result in plain text email
mail_priority - 1 is high, 3 is normal
return_ip -  do you want the IP of the sender returned with the email if available? 1 is yes, 0 is no

NOTES:
> To call the form link to the emailer.php file NOT the emailer_form.php!
> DO NOT use spaces in the field names of your form. A good option is to use an underscore ("_") instead.
> When making the form, always include the <?php echo $fieldname; ?> as the value of each field. This will make the field populate with the previously entered information should someone not fill out all required fields and see the default error page.
> the recipients_group hidden field may seem confusing, but it is done this way to prevent spambots from harvesting actual email addresses from hidden fields the way many scripts do

VERSION HISTORY

3.0.1 - Now configurable to use any field for the "from" field in the resulting email set with a hidden field

3.0 - Added most of the variable from previous version to hidden fields for easier management of multiple forms
      Added multiple recipient groups to allow emailer.php to handle multiple forms
      List of recipients to be used is sent via a hidden field as a reference number, NOT the actual email addresses to prevent email harvesting
      Removed footer and header includes to make the emailer a stand-alone script to work with any and all forms on your site
		Made the domain referrer security check optional
		
2.9.1 - Added CC and BCC header option
        Added option to email results of form back to the sender

2.9 - Any URL's are now made to clickable links in any HTML-based email

2.8 - Added the option of returning the users IP address as well, if it is available

2.7 - Now it supresses the submit button from the form results
      Added a horizontal tabling option

2.6 - Added the ability to filter email fields for valid email addresses

2.5 - Added the ability to filter requests to your form from other domains
      Added XHTML 1.1 validated coding (sent emails are HTML 4.01)

2.2 - Added mail priority option to flag form mailing s as "high-priority" or "normal-priority"
      Added header and fotter includes for easier customizing of the look and feel.
      Fixed bug in validation error messages.

2.1 - Added ability to you custom made or the default error and success pages.

2.0 - Rewritten for easier configuration
      Easier setup of validated fields
      Included form file added

1.0 - Initial release

