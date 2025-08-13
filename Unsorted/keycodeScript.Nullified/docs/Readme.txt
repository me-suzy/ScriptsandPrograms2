==============================================
Installing the keycode script & Information
==============================================

This is a very simple script to install:
just open up the key.cgi, use like editpad or notepad
Leave these alone:
$keys = "keys.db";
$logs = "logs.db";

You only need to configure these areas: 

your main site: 
$home = "http://YOURDOMAIN.COM";

The login page for customers to go: 
$access = "http://YOURDOMAIN.COM/login.html";

Admin Email Address: (make sure the \@ stays in place)
$admin_email = "sales\@YOURDOMAIN.COM";

Your sendmail on the host = usually like this below or maybe /usr/bin/sendmail
$sendmail = "/usr/sbin/sendmail";

Your admin Password:
$password = "PASSWORD";

thats all done just save and close.
now upload to your cgi-bin and chmod the key.cgi to 755

You need to edit the login.html
open it up either with editpad or notepad and change this part to your own info:
<FORM ACTION=http://www.YOURDOMAIN.COM/cgi-bin/key.cgi METHOD=POST>

and near the bottom:
<a href="mailto:SUPPORT@YOURDOMAIN.COM?subject=keycode problem">
SUPPORT@YOURDOMAIN.COM</a>

upload this login.html in your public_html or www folder. (you can easily change the layout of the html to make it match your sites)

After someone purchases your software, Verify payment than go to the form to fill it out so they can get a email with the keycode in it. ( I just make a thankyou.html page after someone pays, and tell them that as soon as payment has been verified, they will recieve a keycode to their email address)

now using your browser just call the key.cgi script by going here - make sure you change the "YOURDOMAIN.COM" to your own and PASSWORD that you used on the key.cgi
http://YOURDOMAIN.COM/cgi-bin/key.cgi?user=PASSWORD

This is a form you need to fill out so the customer gets that special keycode:
* Product Name: Name of your product you are selling
* URL for Zip:  location of the zip you want customers to download
* Email: Customers email address
* KeyCode Assigned:  this is automated, so you do not haveto put anything into this area.

click on "submit your key for access" and the script will automactically send the customer a email with information on where to login and use the keycode.

===============================================================================
Information of Proof:
(you can easily download logs.db and open it up with notepad or editpad)
In your logs.db that is created,(in your cgi-bin - where you have the key.cgi at) you will see proof that the person downloaded your digitical download as specified below:

 68.55.57.58 - - [2004-01-01  10:46:00]  yourname | test@yourdomain.com | http://www.yourdomain.com/product.zip | 66611

68.55.57.58 = this is their IP address
[2004-01-01  10:46:00] = the exact date and time
yourname = the name of your customer
test@yourdomain.com = the customers email address
http://www.yourdomain.com/product.zip  = the url of where the download took place
66611 = this was the keycode that was assigned to them
Hope you enjoy this script. 

