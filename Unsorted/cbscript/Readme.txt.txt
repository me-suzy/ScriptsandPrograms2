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

Readme.txt
----------
This Readme file will guide you thru the steps required to configure
and install the Perl Script (thanks.cgi) on your web site. Please 
make sure that your Server supports Perl, cgi-bin, and SendMail Program.

STEP:1 - Software Configuration.

Just open the Perl Script on any Text Editor like Notepad. then make 
necessary changes to the following variables. Start from the top 
of the page;

A) Location of Perl Script - Default value: 

#!/usr/bin/perl

Generally this is the location in almost all servers. 

In some cases this may be;

#!/usr/sbin/perl

If you are not sure, verify it with your server administrator then 
make necessary changes. Please don't remove the '#' sign at the 
beginning. You don't have to change the default value if the Script 
works properly. You can skip to next step. 

B) Location of SendMail Program - Default value:

/usr/lib/sendmail

In some cases this may be;

/usr/sbin/sendmail

You don't have to change the default value if the Script works 
properly. If it doesn't work, please contact your server 
administrator and ask the location of SendMail Program.

C) Auth Code. A 10 characters code to verify whether the buyer is paid.

$AuthCode = "Zb34Ri9jW4";

You can type anything instead of the default value "Zb34Ri9jW4". 
Use a combination of alphabets in Upper and Lower cases with numbers 
to make it difficult for people to guess.

Example:    $AuthCode = "Qb74Ji4jW2";

Important: You have to write down this Code in a safe place. 
When you assign the location of 'Thank-You Page' in ClickBank's 
Control Panel, you need to mention this Code. More explanation follows.

D) Enter your Email Address. 

$EmailAddress = "email\@YourDomain.com";

Note:- Please Don't remove the '\' before the '@' sign.

Example:- $EmailAddress = "ebooks\@eth.net";

E) Enter your Name

$YourName = "Ben Johnson";

F) Enter Name of your Company/Firm/Website

$Company = "ABC Publishing";

G) Name of your Product

$Product = "Internet Marketing Ebook";

H) Price of the Product.

$Price = "\$49.95";

Note:-   The '\' is Required before the '$' sign.


I) Download Page URL.  Starts with http://

$DownloadURL = "http://www.YourSite.com/download/download.html";

The Buyer will be forwarded to this URL to download your Product. 
You can specify direct Link to an HTML page that contain links to 
each product. If you provide only one item, give direct link to that 
product in EXE or ZIP format. If it is a PDF document, I advice you 
to zip it and provide that link.

I suggest you not to name your Download Page simply 'download.html'. 
Name it some thing like Dload342xy.html or something difficult to hackers.


J) Whether to Activate Autoresponder. 

$AutoResponder = 0;

If you want to add the buyer into your autoresponder, Change the 
value to 1 (one).  Otherwise keep the Default value 0 (zero). 
Autoresponder system will start sending follow-up emails to 
promote your other products, Newsletter etc.

K) Your Autoresponder Email Address.  Keep default value if you 
don't have one. You can try this FREE Autoresponder: http://www.freeautobot.com

$AutoAddress = "autoresponder\@YourServiceProvider.com";

Examples:- apm\@freeautobot.com,  clsads\@freeautobot.com

Note:- The '\' is Required before the '@' sign.

If you don't use this facility, just leave this field blank as follows:

$AutoAddress = " ";

L) Whether to send you a Sales Report for each sale.

$SalesReport = 1;

If you want the sales report for each sales, keep the default value 1.
Sales report will contain Buyer's name, email, IP Address, 
Item Purchased, Price etc.

Change it to 0 (zero) if you do not want sales report.


M) URL of this Script. Starts with http://

$ScriptURL = "http://www.YourDomain.com/cgi-bin/thanks.cgi";

This will be the URL of your modified Script (Thank-You Page Script).
You have to specify it's here. Your thank-You page script must have 
a .cgi extension and to be uploaded to your server to this location 
on your server. 

Example:- If you entered http://www.yoursite.com/cgi-bin/thanks.cgi
on this field, you must save the script as thanks.cgi and to be
uploade to http://www.yoursite.com/cgi-bin/ directory. For security 
reasons, I advice you not to name your script simply 'thanks.cgi'. 
Name it some thing like thank342xy.cgi or something. When you save 
the file, some times it will save the file as thanks.cgi.txt insted 
of thanks.cgi  In that case, your have to rename it.

STEP:2 - Uploading this Script to your Server.

Upload the modified Script to your server into a directory that is 
allowed to run Perl CGI programs ( Generally /cgi-bin/ ) as ASCII 
(PLAIN TEXT) Mode. If you uploaded in Binary mode by mistake, it will 
not work. Set CHMOD (global execute permissions) to 755. If you don't 
find cgi-bin directory, ask your server administrator to create one for you.

STEP:3 - Make Changes in ClickBank Control Panel

This is the last and the Important Step.

As you know, the URL of this script will be the URL of your "Thank You page".
Login to ClickBank with your user ID and password and make a simple 
change there. In your ClickBank Control Panel, just type the URL of 
this script as Thank-You Page as follows;

http://www.YourSite.com/cgi-bin/thanks.cgi?auth=Zb34Ri9jW4

the last part ?auth=Zb34Ri9jW4   is very important.

Remember - the 'auth' value should be the same one we have given in 
the Script as mentioned in STEP:1-C. This value must me same in both 
places. When this value forwarded to the Script, it assumes that the 
buyer is already paid. This system is to protect your product from 
unauthorized downloads.


STEP:4 - Testing Testing Testing

After you uploaded the Script and Download page you have to verify 
whether it works properly.

Just type the URL of the script in your browser. It will display a 
message saying "You are not authorized to access this Script" . 
If you got this message with your contact details at the bottom, 
your Script works perfectly.

Now, try to access it through clickbank. Just make a $0.00 purchase 
and you will be forwarded to your script. You can see a page with 
order details etc. Just type your REAL Name and Email as your 
Buyers do.  You should type your own details, Click on 'Continue' 
button. then you will be forwarded to the download page.  Check your 
Inbox to make sure that you have received a sales report and a 
'Thank you' message. Also you have to verify whether the given name 
and email address has added to your Autoresponder system.

