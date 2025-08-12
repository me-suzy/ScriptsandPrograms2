MDW SaferMail-PHP 0.7
By Mike DeWolfe (mikedewolfe@gmail.com)


---------------------- README.TXT ----------------------

The zip file contains all of the components needed to install
and use this gallery application.

1. Terms And Conditions
2. Included Files
3. Prerequisites
4. Use
5. Digital Alms


1. Terms And Conditions
-----------------------------------------------------------------
These programs are shareware, they may be used for an UNLIMITED period 
of evaluation free of charge, by private users only. They are not 
Freeware and are not allowed to be used in a commercial or government 
environment. If you like them you should register in order to gain all 
the benefits. The included files are are created, owned and licensed 
exclusively by Mike DeWolfe, Web: mike.dewolfe.bc.ca . Reverse-engineering 
or modifying this software with the exception of HTML modifications is 
strictly prohibited.
 
LIMITED WARRANTY:

THESE PROGRAMS AND ACCOMPANYING WRITTEN MATERIALS ARE PROVIDED "AS IS" 
WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, 
BUT NOT LIMITED TO THE IMPLIED WARRANTIES OF MERCHANTABILITY OR FITNESS 
FOR A PARTICULAR PURPOSE. NEITHER THE AUTHOR NOR ANYONE ELSE WHO HAS 
BEEN INVOLVED IN THE CREATION, PRODUCTION OR DELIVERY OF THIS PRODUCT 
SHALL BE LIABLE FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL OR INCIDENTAL 
DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE SUCH PRODUCT EVEN 
IF THE AUTHOR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.

BY USING THIS APPLICATION AND/OR ANY ONE OF THESE ACTIVE SERVER PAGES,
YOU EXPLICITLY AGREE TO THESE TERMS AND CONDITIONS.


2. Included Files
-----------------------------------------------------------------
readme.txt
---	The readme file that you reading.

safermail.php
---	The core of the functionality. This script assesses whether the request is
legitimate. If it appears legitimate, it will provide a mailto link. 

safermailconfig.php
---	This script lists the connection settings for accessing your database connection.
In the interest of future functionality, these variables reside in a separate page.

example.html
---	An example of script in action. 

empty.html
---	The placeholder for the safermail.php.

safermail.sql
---	SQL statements to be executed when you install these scripts.

alms.html
---	Digital Alms. I am not beyond the need for money. If you feel that 
this application is worthwhile, feel free to open up the alms.html,
click on the Amazon link and gift to me anything from $5 to $10 for the
use of this application. In return, support for a year and product updates into 
perpetuity.


3. Prerequisites
-----------------------------------------------------------------
These are the prerequsities:
- MySQL 3+
- MySQL client (to execute SQL statements, review database tables and insert new records)
- Capability to run PHP and access MySQL via PHP


4. Use
-----------------------------------------------------------------

4.1 Installation
------------------------------
- The first thing you need to do is UNZIP the safermail-php.zip file.
- Change the safermailconfig.php.

edit these lines:

$dbhost = " ";
$dbuser = " ";
$dbname = " ";
$dbpass = " ";

to hold variables for a connection to your MySQL database.

- Take safermail.php and safermailconfig.php; upload both to the root of your web directory.

- Use your MySQL client. Take the lines of SQL code from safermail.sql and run them to 
build the required tables for use by SaferMail. 

4.2 Adding Addresses
------------------------------
Using your MySQL client, insert legitimate email addresses into your database. 

The key is the reference you will pass from your HTML/hyperlink to retrieve the desired email address

e.g.
INSERT INTO `SaferMailAddresses` (`key`,`email`) VALUES ('joe','joe@test.com');

Your key can only use alpha-numeric HTML safe characters. If you include spaces, ticks (') and the
like, the script will alter them into safe characters and make them different from their intended and
not provide a match.


4.3 Using SaferMail
------------------------------
On any page where you wish to use safermail, do two things:
First: add this piece of code:

	<IFRAME src="/empty.html" width="1" height="1" frameborder="0" name="safemailer"></IFRAME>

Make sure to have this on your page only once.

Second, refer to SaferMail with this code:

	<a href="/safermail.php?m=mike" target="safemailer">Email Mike</a>

The "/safermail.php" calls to the top of your web directory where your safermail script resides. Naming "safemailer" as
your target means that the results (good or bad) get dumped into your iframe. If the browser cannot handle the
iframe it will spawn a new blank window.



5. Digital Alms
-----------------------------------------------------------------
If you feel that this application is worth enough to you, feel free
to donate a small sum. Go the page entitled "alms.html" and click on
the Amazon link. 
