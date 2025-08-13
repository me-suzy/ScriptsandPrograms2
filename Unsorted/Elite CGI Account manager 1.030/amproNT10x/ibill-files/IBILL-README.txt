Account Manager 1.028 - UNIX
IBILL - Recurring and Non-Recurring Pincode Interface README
(Internet Billing Company:  Real-time credit card authorization)
- Beta 1.0
- 9/29/98

This READM file is to assist you in converting your present version of
Account Manager to this Beta 1.0 of our IBILL interface for
recurring and non-recurring billing of up to three account types.

Here are the things that you will need in order to successfully use
real-time credit card billing through IBILL - http://www.ibill.com

1)  An account with IBILL.  We are not IBILL representatives, nor
can we create accounts for you with IBILL.  You will need to contact
IBILL on your own.

You can reach the IBILL website at:  http://www.ibill.com or contact
them by phone at:  (888) 237-1764 or U.S. (954) 724-0644.

This beta offers an interface to the IBILL account called the
"Recurrnig Pincode" and "Non-recurring pincode" billing accounts.
You'll find more information about this type of account at:

http://www.ibill.com/setopcc.html

2 ) The three new lines added to your configuration files are marked
as the example below.  Do not edit anything in this README file.
The information below also appears in your config.pl file.  You
will need to make the proper changes in your config.pl file.  We
have included these IBILL USERS ONLY configurations here, just
for your information:

#################### IBILL USERS ONLY ############################
##################################################################

# If you are using IBILL's pincoding, enter the number "1" between
# the quotations below.  Otherwise, leave it empty.
# Example:  $IBILL = "1"; for IBILL pincoding turned on
# Exmaple:  $IBILL = "";  for IBILL pincoding turned off

$IBILL = "";

# If you wish to redirect those that are denied an account due to
# a non matching Pincode, place the full url of where you would
# like this person directed.
# Example:  $Idenyurl = "http://www.yourserver.com/deniedaccess.htm";

$Idenyurl = "";"

# Enter the full directory path to the pincode file provided you by
# IBILL here.  Be sure to include the name of the file itself.
#
# Account Manager is presently designed to offer up to three account
# types.  Using IBILL, each account will have its own set of
# authorization pincodes. Enter the path for up to 3 separate pin
# code text files here.  Make sure to place these pin codes in a
# directory that is not accessible by the web, if possible. Most
# ISP's will offer their customers at least one directory for this.
# Make sure to set permissions on the files for read and write.
# 744-766.  Some servers will require as high as 777. Contact your
# server administration for specific settings on your server.
# Example:  /home/httd/yourserver/directory/1pincode.txt";


$act1pincodes = "/full/directory/path/to/your/account1pincodes.txt";
$act2pincodes = "/full/directory/path/to/your/account2pincodes.txt";
$act3pincodes = "/full/directory/path/to/your/account3pincodes.txt";

####################################################################

Once again, you do not need to edit anything in this README file.
It is simply here for your information.

3) You will need to edit your amform.htm file (ibilltest.hmf - 
one sample is included) to match the IBILL requirements of a "Webgood"
page.   You'll find the specifics that IBILL requires and how to upload
your "Webgood" pages to IBILL's servers on the following web page:

http://www.ibill.com/setopcc.html  (look for "Step 5 on this page)

Follow these directions to set up the files needed by IBILL to interface
with your Account Manager.

You'll find a sample amform.htm (modified to the IBILL format) in this
zip, called ibilltest.htm.  NOTE:  IBILL will require you to rename
all your "Webgood" pages.  Instructions are available on the web page
above.

To enable real-time credit card processing, you will also need to set
your Account Manager to "Instant Access" option, found in your config.pl
file.  This will allow those that successfully register through IBILL to
gain immediate access to your private area.

4)  Use your Account Manager Administration utility to delete users
that IBILL notifies you of, when subscription accounts are deactivated
by customer or credit card is no longer valid.

Search by IBILL Subscription code to find the user in question, then
simply delete the user with the push of a button.  Access codes
are deleted from the .htpasswd file as well.

If you have IBILL-specific questions, PLEASE contact IBILL, and not us,
as we are NOT IBILL representatives, nor do we have all of the
information you may require about billing through IBILL.

This README documentation for this Beta release of the Account Manager
IBILL interface will be updated as users present more questions, and
as we expand our IBILL support.

Please direct your questions to our Account Manager bulletin board,
located in our Registered Users Area.  We WILL NOT answer emails
regarding this Beta unless they are posted to our bulletin boards.
This will allow our Technical Support or other Account Manager owners
to assist with your questions.

Thank you for using Account Manager and testing our new IBILL interface.

Account Manager Development Team
http://www.cgiscriptcenter.com
support@cgiscriptcenter.com
 
