<? include ("../css.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>phpBannerExchange 2.0 Documentation</title>
<link rel="stylesheet" href="../template/css/<? echo "$css"; ?>" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" 
  marginheight="0" >
<div id="content">
<div class="main">
<table border="0" cellpadding="1" width="650" cellspacing="0">
<tr>
<td>
<table cellpadding="5" border="1" width="100%" cellspacing="0">
<tr>
<td colspan="2" class="tablehead"><center><div class="head">phpBannerExchange 2.0 Documentation</center></div></td>
</tr>
<td class="tablebody" colspan="2">
<div class="mainbody">
<table border="0" cellpadding="1" cellspacing="1" style="border-collapse: collapse"  width="90%">
  <tr>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" width="90%" >
<tr>
<div class="lefthead"><a NAME="introduction">Introduction</a></div>
<p>
phpBannerExchange 2.0 is a powerful, feature-rich banner exchange script written in PHP4 and mySQL for the Apache web server running on Linux. While it is likely the script will work on other platforms such as Microsoft SQL and Internet Information Server (IIS), the software has not been tested on these platforms.<p>
This guide is intended to assist you with the features included with the script and to guide you through the every day administration tasks required to run a thriving banner exchange. This guide assumes you have already successfully installed the script according to the <a href="install.php">Installation instructions</a>.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>

<div class="lefthead"><a NAME="stats">Logging In and Viewing Statistics</a></div>
<p>
The Administrative Control Panel allows you to control nearly every aspect of the Banner Exchange such as validating accounts, adding/removing/editing categories, deleting accounts, administering banners, and the like. To access the Control Panel, you will need to point your web browser to the following URL:

http://www.<i>yourdomain.com</i>/<i>exchange_directory</i>/admin/<p>

Replace <i>yourdomain.com</i> with your domain name (e.g.: eschew.net) and <i>exchange_directory</i> with the path to your root Banner Exchange directory (e.g.: bannerex). You will be presented with a login prompt. Log in with the Administrator username and password you created when you installed the script.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead">The Stats Window</div><p>
The Administrative Statistics window shows a variety of information. This is your main administrative page, nearly all options are available from here. Below is a description of the statistics table:
<table class="tablebody" width="100%">
<tr>
<td width=180 valign="top"><b>Validated Users:</b></td><td>The number of users who currently have a banner in rotation.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Total Exposures:</b></td><td>The total number of exposures for all users.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Loose Credits:</b></td><td>The amount of credits currently eligible to be redeemed.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Total Banners Served:</b></td><td>The total amount of banners that have been served. This value is Exposures + Loose Credits.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Pending Users:</b></td><td>The number of users currently awaiting validation.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Total Clicks to Sites:</b></td><td>The number of times a banner has been clicked on.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Total Clicks from Sites:</b></td><td>This value should be the same as the Total Clicks to Sites. If it is not, you likely have a cheater.</td>
</tr>
<tr>
<td width=180 valign="top"><b>Overall Exchange Ratio:</b></td><td>This is the exchange-wide click-thru ratio.</td>
</tr>
</table>

<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="validate">Validating Accounts</a></div>
<p>
One of the most important feature in the script is the ability for the Administrator to validate accounts. This allows the Administrator to control the content that is served to the clients. For example, it would be a Really Bad Idea&#0153; for a church/christian related banner exchange to host an account for a porn site: Validating accounts allows the Administrator to remove accounts that may be offensive or beyond the scope of the exchange BEFORE they are inserted into the exchange rotation.<p>
Accounts are also moved back into the validation queue whenever anything important is changed such as the Site URL and the addition or removal of banners.<p>
Accounts waiting to be validated are listed on the "Validate Accounts" page. To validate an account, simply click on the account name. You will then be presented with a page that allows you to alter every aspect of the account. Below is a description of the options available to you:<p>

<table class="tablebody" width="100%">
<tr>
<td width=130 valign="top"><b>Real Name:</b></td><td>This is the user's real name.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Login ID:</b></td><td>This is the unique username chosen by the user.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Password:</b></td><td>This field displays the user's password. If you are using MD5 encrypted passwords, the MD5 hash of the password is listed here. Changing this value will allow you to change the users password, even if you are using MD5 encryption.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Email Address:</b></td><td>This is the e-mail address the user entered when creating the account.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Category:</b></td><td>The chosen category of the user's site.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Exposures:</b></td><td>The current number of exposures the user's account has been given.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Credits:</b></td><td>The amount of credits the user has amassed.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Clicks to site:</b></td><td>The number of clicks on this user's banner.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Clicks from site:</b></td><td>The number of clicks originating from this user's site.</td>
</tr>
<tr>
<td width=100 valign="top"><b>RAW Mode:</b></td><td>RAW mode can be used to create an account on a secondary exchange from another site and include it on your exchange. To utilize RAW mode, you would simply enter the HTML provided by the second banner exchange in the field provided. RAW Mode overrides the exchange settings in "view.php".</td>
</tr>
<tr>
<td width=100 valign="top"><b>Account Status:</b></td><td>To validate accounts, insure that the "Approved" radio button is selected.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Default Account:</b></td><td>Default Accounts are accounts that are displayed ONLY when there are no eligible banners with sufficient credit to be displayed. Generally, these accounts should be used to advertise your exchange, your web page, or another exchange (using RAW Mode). Every single banner exchange needs at least one default account. If you plan on displaying a Default Account's banner exchange code on a web page, you will need at least TWO (2) default accounts.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Send Newsletter:</b></td><td>This option is for the user to opt-in to the newsletter/mailing list that is controlled through the <a href="admin.php#mailer">Mailer Manager</a> feature.</td>
</tr>
<tr>
<td width=100 valign="top"><b>Banners:</b></td><td>Banner display is suppressed in phpBannerExchange 2.0. To view the account's banners, click on <b>View/edit banners</b>. Options available on this page are sufficiently self-explanatory.
</td>
</tr>
<table>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="addacct">Add an Account</a></div><p>
This feature allows you to add an account directly into the exchange. This feature uses the same form as the <a href="admin.php#validate">Validation routine</a> listed above.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="listacct">List All Accounts</a></div><p>
This page allows you to list all active accounts in alphabetical order. The accounts are listed by type (Default or Normal) in alphabetical order for ease of navigation. Clicking on a specific account name will allow you to edit the account properties. This feature uses the same form as the <a href="admin.php#validate">Validation routine</a> listed above.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="defbanner">Default Banner</a></div><p>
The Default Banner setting allows you to specify a specific banner to be displayed when no other banner is eligible for display and a default account is not eligible for display for whatever reason. Default banners should be uploaded to the server separately. Define the banner URL and target URL in the appropriately titled text fields.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="mailer">Mailer Manager</a></div><p>
<p>
The Mailer Manager is a simple mass-mailer utility that allows you to mail all users of the exchange. This feature includes some macros to make your life a little easier:<p>
<table class="tablebody" width="100%">
<tr>
<td valign="top">
<b>%username%</b></td><td> Echos the user's Real Name.</td>
</tr>
<tr>
<td valign="top"><b>%login%</b></td><td> Echos the user's Login ID.</td>
</tr>
<tr><td valign="top"><b>%email%</b></td><td> Echos the user's e-mail address.</td>
</tr>
<tr><td valign="top"><b>%statstable%</b></td><td> Echos the default exchange URL so the users can access their statistics.</td>
</tr>
<tr>
</table>
<p>
The form supports standard Unix linefeeds (\n) for page breaks as well as most common HTML commands. The mailing manager automatically identifies HTML text and sends the message as a mime encoded message. This allows users to read the document in an HTML-enabled mail client.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="cats">Categories</a></div><p>
The Category Management menu allows you to add, delete, and edit categories. Categories are used by your exchange clients to assist them in targeting their banners to specific pages. Every account should be categorized, even if the category is "Default" or "No Category"<p>
Clicking the category name lists the accounts that currently belong to that specific category.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="admin">Add/Remove Admin</a></div><p>
This feature allows you to add or remove administrators. Each added administrator has the rights to do everything the original administrator does (everything). This feature is useful if you have multiple users who approve banners, etc.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="pw">Change Password</a></div><p>
This feature allows you to change your password for the currently logged in Administrator.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="dbtools">Database Tools</a></div><p>
A database backup and recovery tool is included with the script. It is recommended you run this tool often, to insure data integrity. Essentially, the database backup and recovery tool uses mysqldump to create a file in the <b>admin/db</b> folder that allows you to recover should the database ever become corrupt.<p>
<b>WARNING: IT IS STRONGLY RECOMMENDED THAT YOU SET UP A .HTACCESS FILE TO PASSWORD PROTECT THIS DIRECTORY!</b><p>
It is a security risk otherwise, since this directory is globally writable. Contact your hosting provider for assistance with creating an .htaccess file if you do not know how to do this. More often than not, your "Control Panel" feature that comes with your hosting will do this for you.<p>
If you wish to view a dump of the file and save it to your local computer, you may do so by clicking on the filename. This file can then be uploaded should the database ever need to be recovered.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="template">Templating System</a></div><p>
phpBannerExchange 2.0 RC3 and higher contains a simple, yet powerful templating script that allows you to edit the default template to your liking. This templating system uses the "standard" <b>{item}</b> style tags. to echo variables from the main processing script to the HTML file the user actually sees. A template editor is included within the Admin interface to facilitate the editing of the template files.<p>
Standard tags include:<p>
<table class="tablebody" width="100%">
<tr>
<td width=130 valign="top"><b>{title}</b></td><td>Echos the "name of your exchange - name of the page". Example: "MyExchange - Admin Control Panel Login".</td>
</tr>
<tr>
<td width=100 valign="top"><b>{shortitle}</b></td><td>Echos the "name of the page". Example: "Admin Control Panel Login".</td>
</tr>
<tr>
<td width=100 valign="top"><b>{menu}</b></td><td>calls the menu file for display on the page.</td>
</tr>
<tr>
<td width=100 valign="top"><b>{msg}</b></td><td>Echos a message of some kind, such as "The banner has been edited." or other, similar verbiage.</td>
</tr>
<tr>
<td width=100 valign="top"><b>{footer}</b></td><td>Echos the footer file.</td></tr></table>
<p>
Additionally, there may be tags unique to the specific file you are editing. These tags are relatively self-explanatory.<p>

Every effort has been made to include as much HTML as possible in the template files, however some special functions (such as <b>while()</b>'d statements) have basic table tags included in the PHP scripts.

<p>The script does not currently support creating new files for security reasons.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="variables">Variable Editor</a></div><p>
This feature allows you to change things in your config.php file. If you decide, for example, to begin selling credits after you have installed the script, you may enable the store through this feature.<p>
An explanation of the variables:

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="tablebody" width=130 valign="top"><b>Database Host:</b></td><td class="tablebody">The host name of your database. This is most likely <i>localhost</i>.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Database Login:</b></td><td class="tablebody">The username you use to connect to your database.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Database Password:</b></td><td class="tablebody">The password you use to connect to your database.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Database Name:</b></td><td class="tablebody">The name of the database you wish to use to store your user data.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Base Exchange URL:</b></td><td class="tablebody">The path on the Internet to which you have phpBannerExchange installed. For example, if you have installed the software to the directory "exchange" just off your root directory, the Base Exchange URL would be <i>http://www.yourdomain.com/exchange</i>. Do not include a trailing slash after the directory name.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Base Path:</b></td><td class="tablebody">The path (<b>NOT</b> the URL) of where the exchange script is installed. This should be entered automatically by the installation/upgrade script. If it is not, enter the base path (eg: <i>/home/www/exchange</i>). Do not include a trailing slash after the directory name.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Exchange Name:</b></td><td class="tablebody">The name of your exchange, as you would like it to appear in the exchange script. This value is displayed under the banner that is served as well as in e-mail and control panel pages. Examples: "The 1:1 Banner Exchange", "Commander Skippy's Banner Exchange"</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Site Name:</b></td><td class="tablebody">The name of your site. This is used for the legalese in the "Conditions of Use".</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Admin Name:</b></td><td class="tablebody">Your name or alias. It is primarily used in the footer of each page.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Admin Email:</b></td><td class="tablebody">The e-mail address you would like to use to receive exchange related questions, new account notifications, etc.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Banner Width:</b></td><td class="tablebody">The width, in pixels, of the banners you would like to support with your exchange. Standard banners are 468 pixels wide.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Banner Height:</b></td><td class="tablebody">The height, in pixels, of the banners you would like to support with your exhange. Standard banners are 60 pixels wide.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Default Ratio:</b></td><td class="tablebody">This is your default exchange ratio..sort of. The way this works is the first number is how many exposures are required to have one display. For example, setting this value to "3" will require 3 exposures before the account becomes eligible to display a banner. The second number is how many credits the script should take away when the banner is being displayed. So setting this option to "3" will take away 3 credits when the banner is displayed. Some common ratios are listed below:<p>
<b>1</b> banner display allows <b>1</b> banner to be displayed: <b>1:1</b><br>
<b>2</b> banner displays allows <b>1</b> banner to be displayed: <b>1:2</b><br>
<b>3</b> banner displays allows <b>2</b> banners to be displayed: <b>1:1.5</b></td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Show Exchange Image:</b></td><td class="tablebody">This will allow you to specify if you wish to display a small image to the left of the banner. Typically, this is a 60x60 image in a 468x60 banner exchange. It will link back to your Banner Exchange main page.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Exchange Image Position:</b></td><td class="tablebody">The postion of the exchange image (as described above) relative to the banner itself. Options are Top, Bottom, Left or Right. Selecting Bottom for example, will display the Exchange Image (see below) under the banner.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Exchange Image URL:</b></td><td class="tablebody">If you have decided to show an exchange image above, enter the full URL to the image here (eg: <i>http://www.yourdomain.com/image.gif</i>). Otherwise, you may leave this value blank.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Show Exchange Link:</b></td><td class="tablebody">If you wish to show a brief exchange text link under the image, set this option to yes, and enter the text you wish to display here. You should keep this brief, something like "Join the (your exchange's name)!" or some such verbage.
</td>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Require Banner Approval:</b></td><td class="tablebody">This option controls how the banners are validated. You can set this option to <b>No</b> and the account and URL only need be approved, not the banners. setting this option to <b>Yes</b> will force the account into unvalidated mode when a banner is added or deleted.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Allow Uploads:</b></td><td class="tablebody">This option allows you to create an upload folder for your user's banners. Setting this option to <b>No</b> will require the user to his his or her own webspace and bandwidth to serve the banner for the exchange.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Maximum Filesize:</b></td class="tablebody"><td class="tablebody">The maximum filesize you wish to allow your users to upload. A 30K banner would be expressed as "30000", for example. You may leave this value blank if you do not wish to use the upload feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Upload Path:</b></td><td class="tablebody">The *nix/Windows path to the upload directory. Typically, this will be something like <i>/home/username/public_html/exchange/upload</i> or similar. Do not include a trailing slash after the path. This path must be globally readable and writable (<i>chmod 777</i> upload_directory). Contact your hosting provider if you are unsure of the path to your html directory. Do not include a trailing slash in the path. You may leave this value blank if you do not wish to use the upload feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Upload directory URL:</b></td><td class="tablebody">This is the URL to your upload directory specified in the Upload Path variable above. An example of this would be <i>http://www.yourdomain.com/exchange/upload</i>. Do not include a trailing slash in your URL. You may leave this value blank if you do not wish to use the upload feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Maximum Banners:</b></td><td class="tablebody">This is the maximum amount of banners you wish to allow your users to add to their account. If you would like your users to have the ability to add an unlimited number of banners to their account, set this value to 0 (zero). This feature works for both uploaded AND remotely hosted banners.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Anti-Cheat method:</b></td><td class="tablebody">Select the type of anti-cheat method you would like to use. Valid options are <b>DB</b>, <b>Cookie</b>, and <b>None</b>.<p>
The <b>Cookies</b> anti-cheat measure that uses cookies to store timeout data for a specific computer. Only after the timeout has expired will credits continue to add up for the account displaying the banner (the timeout value is expressed in a later variable).
<p>
The <b>DB</b> method is the same as the Use Cookies variable except it does not use cookies: all data is stored in a database and automatically purged when the timeout expires.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Duration:</b></td><td class="tablebody">This is the timeout value for the two anti-cheat methods, expressed in seconds. This feature controls how long a client on the banner exchange member's page is not counted as an additional visit and subsequently an additional credit for the exchange member. It is recommended you set this value to a low number, perhaps <b>20 or 30 seconds</b>.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Referral Program: </b></td><td class="tablebody">This option allows you to grant your users extra credits for signing up new users. By linking to your Banner Exchange main directory, (eg: <i>http://www.somesite.com/exchange/index.php</i>) with a special code, your end users can earn extra credits. For anti-cheat reasons, the credit is only granted after the created account is validated.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Referral Bounty:</b></td><td class="tablebody">This option is the amount of credits you wish to reward your users with for referring users for the exchange.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Starting Credits: </b></td><td class="tablebody">If you wish to provide new users with a set amount of free credits, enter this value here. When validating the account, you may change this amount for any individual account.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Sell Credits: </b></td><td class="tablebody">Enables the online store. The credit store will not be visible to end users unless this option is activated, even if there are items available for purchase. See the Online Store section for more details about the online store and configuration instructions.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Top x will display X accounts: </b></td><td class="tablebody">This value is used for the "top accounts" feature on the main client login page. Specify the maximum number of accounts you would like to display on this page.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Send Admin Email: </b></td><td class="tablebody">If you would like to be notified when a new user signs up to the exchange, click the "Yes" radio button.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Use MD5 Encrypted Passwords: </b></td><td class="tablebody">For security purposes, you may encrypt your passwords with the MD5 encryption routine, which is a 32 character hash of the password. Note that passwords will become unreadable (but will still work) when you use this feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Use gZip/Zend code: </b></td><td class="tablebody">If you wish to compress the pages and send the output as a gZip file, enable this option. This saves some bandwidth, but slows down the server.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Log Clicks: </b></td><td class="tablebody">Stores each click's date & time and IP address to the database for your end users. Note that on large exchanges, this could cause the database to get very large.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Use mySQL4 rand(): </b></td><td class="tablebody">Enables the mySQL rand() function in the view.php queries. This method is better than the exchange's built-in random algorythm, but requres mySQL 4.x or greater.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top"><b>Date Format: </b></td><td class="tablebody">Select the date style to use. (mm/dd/yyyy or dd/mm/yyyy).</td>
</tr>
<table>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="cssedit">Edit Style Sheet</a></div><p>
The Style Sheet Editor allows you to both select and edit style sheets/skins for the script. A few sheets are included by default.<p>

To add new style sheets, you may simply upload them to the <b>template/css</b> directory within your Banner Exchange directory on your server. Style sheets for phpBannerExchange 2.0 will be available from the phpBannerExchange 2.0 download site (<a href="http://www.eschew.net/scripts/">http://www.eschew.net/scripts/</a>). If you wish to edit these files, make sure you set the CSS files in the template/css directory to be world writable (chmod 777). Also, if you do create a new skin, please send it to me so I can share it with the other users.<p>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="faq">FAQ Manager</a></div><p>
This feature allows you to edit the questions in the Frequently Asked Questions area.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="dbtools">Banner Check Utility</a></div><p>
If you are allowing your users to host their own banners rather than uploading them to your server, the Banner Check Utility will save you a lot of time tracking down dead accounts. This tool automatically checks all the banners in the exchange for dead banners, which usually mean a dead account. When this tool runs, any dead banners it runs across are not deleted: the account is returned to an unvalidated state. An administrator can then check the account to insure it is indeed dead.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="cou">Edit COU/Rules</a></div><p>
The Conditions of Use/Terms of Service document (COU) is there to protect you. The default COU indemnifies you as a Banner Exchange Administrator from any loss the end user incurs. In other words if a user breaks the rules and you decide to delete their account, you can do that because of the COU. Likewise, if your server goes down for a month or similar, the COU protects you from being sued and prevents a lot of general end user whining. It's a good idea to keep the COU as it is, but you may edit it if you wish.<p>

Rules define what your exchange considers appropriate. For example, if you are running a banner exchange for gaming sites, an adult site would probably be inappropriate in your banner rotation. The rules clarify this to the end user.<p>

With phpBannerExchange 2.0 RC2, the COU and Rules are both stored in the database.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead"><a NAME="promo">Promo Manager</a></div><p>
The Promo Manager allows you to add coupons for your users to redeem for credits, a percentage off an item, or a special item that is not listed in the online store. Coupons are CaSe SeNsItIvE and supports most special characters (it has been tested with the usual (!@#$%, etc characters). Additionally, clicking on any promotion that is currently active will allow you to see the accounts that have used this promotion. An explanation of the fields:

<table class="tablebody" width="100%">
<tr>
<td valign="top" width="130">
<b>Promotion Name:</b></td><td>The name of the promotion. This is more for your reference than anything.</td>
</tr>
<tr>
<td valign="top">
<b>Code:</b></td><td>The coupon code users will need to enter to recieve the promotional item/credits.</td>
</tr>
<tr>
<td valign="top">
<b>Type: </b></td><td>There are three types of promotion types available:<p>
<b>Mass Credits:</b> A coupon of this type can be redeemed for an amount of credits. You can use this to encourage your users to visit a particular site, provide incentive to sign up for an account, etc. This is the only type of promotion that will work without the Commerce module enabled.<p>
<b>XX% off item:</b> Provides a discount expressed in a percentage for any item in the online store.<p>
<b>Special Item:</b> Provides a special item in the online store that is only available after the code has been entered.</td>
</tr>
<tr>
<td valign="top">
<b>Value:</b></td><td>This field expresses different things for different coupon types. For the <b>Mass Credits</b> type, it does nothing. For the <b>XX% off item</b> type, it expresses the percentage value you would like to use (for example, entering "50" into this field will provide a 50% off coupon). For the <b>Special Item</b> type, it expresses the <b>price</b> of the item.</td>
</tr>
<tr>
<td valign="top">
<b>Credits:</b></td><td>The amount of credits associated with the offer. This field is used for all coupon types.</td>
</tr>
<tr>
<td valign="top">
<b>Users can re-use coupon code:</b></td><td>Enabling this option will allow the user to re-use the coupon code as many times as he or she likes. No checkmark in the checkbox means that the coupon can only be used one time per user. See the <b>Re-use interval</b> field for more information regarding this.</td>
</tr>
<tr>
<td valign="top">
<b>Reuse Interval:</b></td><td>This is an expression in days to define how often a user can reuse a coupon. This is especially useful for the <b>Mass Credits</b> coupon type. You may wish to grant a certain amount of credits every month, for example. If the <b>Users can re-use coupon code</b> option is NOT enabled, this field is not necessary. Setting this option to zero (0) allows the user to reuse the coupon immediately.</td>
</tr>
<tr>
<td valign="top">
<b>Eligible User Type:</b></td><td>The type of user that can use the coupon. Valid options are <b>New Users Only</b> and <b>All Users</b>.</td>
</tr>
</table>
<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="update">Update Manager</a></div><p>
The Update Manager is an easy way to check for updates to the script from the main phpBannerExchange site. This will also help me, the developer, roll out bug fixes at a faster pace. As files are updated with bug fixes, feature enhancements, and security updates, they will be made available on the phpBannerExchange web site. The script identifies which files are a different version from the currently installed files, and makes them available for you to download. The script <b>DOES NOT</b> install them for you. The only files that are not updated via this feature at this time are template files. Should a template file need to be updated or provided with a new feature, a link to the file will be available via the release notes. The decision was made to not include Template files via the Update Manager due to the fact that many exchange admins change their templates.<p>

This feature requires that the file "manifest.php", which is located in the root phpBannerExchange directory, is world writable (chmod 777).<p>

<b>Detailed information on how this feature works</b><br>
At the top of each file is a version number. Since I last updated most of the files (with this feature) on April 13, 2005, I chose the string 041305 (mmddyy). The script first opens each file and assigns the version number to an appropriate variable ($FILE_location_filename, so for example, the "addadmin.php" file would be "$FILE_admin_addadmin"), and then output to the "manifest.php" file along with a timestamp and the URL for the master list, which is an XML feed hosted on the eschew.net server.<p>

The master list contains all the current version numbers for the files, so when I upload a new file, the version number in the master list will also be updated. The script downloads the master list, parses it, and extrapulates the version numbers from the XML feed. It then compares the version numbers from the master list with the manifest list version numbers. If they are different, then it offers the files for download as a .txt file. If they are the same, the script does nothing. If there are new updates, the release notes are provided as a link as well.<p>

If you find a new update, right click then choose save link as, or save target as in your browser. Rename the .txt file to .php, then upload the file to your server, taking note to store it in the same place as specified in the link on the update page. Then, run the "Update Only" or "Complete refresh/update" link on the update page to insure the files have been successfully updated.
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="pause">Pause Exchange</a></div><p>
Pausing the Exchange allows you to "turn off" the exchange. When the exchange is paused, only default banners and banners from default accounts will be displayed and users will still amass credits.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>


<div class="lefthead"><a NAME="commerce">Selling Credits</a></div><p>
If you wish to sell credits for your exchange to your end users, you may do so with phpBannerExchange RC3 and higher. This version of the script introduces a simple e-commerce system that allows administrators to receive payment via PayPal for users who wish to purchase them. This feature uses the PayPal IPN system to instantly verify payment and grant credits.<p>
Be aware that I have NOT tested this feature extensively (or at all with PayPal for that matter) and can not be responsible for malfunction should the IPN feature not work properly (The verification script is a third party freeware script). If all else fails, use the tried and true method of adding the credits to the user's account manually when you recieve notification from PayPal that the payment has been processed.<p>
The only thing that needs to be changed is the "paypal.config.php" file in the "/lib/commerce/" directory. An explanation of the variables are as follows:<p> 
<table class="tablebody" width="100%">
<tr>
<td valign="top" width="130">
<b>$businessname:</b></td><td>Your paypal account e-mail address. This is the same e-mail address you use to login to PayPal or someone would use to send you money.</td>
</tr>
<tr>
<td valign="top">
<b>$ipn_page:</b></td><td>This variable should not be changed in any way. It is reserved for future use, when phpBannerExchange supports more services.</td>
</tr>
<tr>
<td valign="top">
<b>$propername:</b></td><td>The proper name of the service you are using. Right now, "PayPal" is the only service that will work with phpBannerExchange.</td>
</tr>
<tr>
<td valign="top">
<b>$payment_currency:</b></td><td>The currency type you work with through PayPal. Valid Currency types are: US.</td>
</tr>
<tr>
<td valign="top">
<b>$currency_sign:</b></td><td>The currency sign you wish to use. (Note: use a backslash (\) when using a dollar sign ($)).</td>
</tr>
<tr>
<td valign="top">
<b>$currency_int:</b></td><td>The currency type expressed as a suffix. For example. 25 us dollars is expressed as $25 USD.</td>
</tr>
<tr>
<td valign="top">
<b>$decimal_separator:</b></td><td>The separator you would like to use in the decimal place, usually, this is a period (.) but can also be a comma (,).</td>
</tr>
<tr>
<td valign="top">
<b>$thousands_separator:</b></td><td>The separator used to separate thousands places. Usually, this is a comma (,) but can also be a period (.).</td>
</tr>
<tr>
<td valign="top">
<b>$places:</b></td><td>The "cent" places in your currency type. If you use US dollars, it's 2 places.</td>
</tr>
</table>
<p>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="nav">Navigation</a></div><p>
These features help you get along in the software.<p>
Clicking <b>Home</b> takes you to the stats page.<p>
Clicking <b>Logout</b> logs you out of the administrative panel and destroys your session. It is important to do this every time you are finished administering the exchange.<p>
Clicking <b>Help</b> takes you to this document.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>

<div class="lefthead"><a NAME="gethelp">Getting Help</a></div><p>
There is no formal support for this script. I neither have the time nor the inclination to provide technical support for this script. You should be OK provided you read the documentation and carefully follow the instructions provided therein.<p>
If you run in to a problem with the script and do need help, you can go to the <a href="http://www.eschew.net/forums/">Support Forums</a> and ask a question.<p>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead">Bugs and Bug Reporting</div><p>
Please let me know if you find a bug in the software. I spent a considerable amount of time debugging the script however I can not check everything on my own. If you happen to run across a bug, first check to see if it has already been reported and fixed by checking for updates using the Update Manager. If no files are available or updating does not fix the problem, please report the issue in the <a href="http://www.eschew.net/forums/">Support Forums</a>. Be sure to include a detailed description of the bug you encountered, the error message, or the result condition so I can address the problem.<p>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead">Security Issues</div><p>
If you run across a security issue/vulnerability, please report it directly to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a> so I can make an announcement on the forums once the script has been fixed. These types of problems usually require some sort of exploit and could compromise the security of thousands of machines. If you happen to run across a bug of this magnitude, it is essential that I be notified first so I can protect the security of these machines. Please be aware that support issues sent to this address will be ignored. You will NOT receive a reply support issues if e-mailed to me.<p>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
<div class="lefthead">Mods, Localization and Templates</div><p>
If you change something in the script or add a new feature, I encourage you to share it with the other users. My vision of what makes a good Banner Exchange script as far as features are concerned might be vastly different than yours, so if you make a modification to the script, I encourage you to share it with me. I plan to make all working mods to the script available for download as an add-on to the base script. If the modification is good enough, I will even include it in a future version of the script and you will receive credit for the code you have submitted or changed. You may e-mail your mods to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a>.<p>
I am seeking assistance with translating the software into different languages. All language files for the script are located in the <b>lang/</b> directory under your root banner exchange directory. If you translate the files, please provide me with a copy and I will make it available for all users. You may e-mail the translated language files to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a>.<p>
The templates are located in the <b>template/</b> directory. If you alter the templates or style.css file, please send me your new files and I will make them available to all users. You may email them to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a>.<br>
<p>
[<a href="admin.php#top">top</a>]<p>
<hr>
<p>
</td>
</tr>
</table>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
</div>
<center>
<div class="footer"><a href="http://www.eschew.net/scripts/">phpBannerExchange 2.0</a> is an original work and is Copyright &copy; 2002-2005 by eschew.net Productions. phpBannerExchange 2.0 is distributed at no charge in accordance with the <a href="http://www.gnu.org/licenses/lgpl.html">GNU General Public License</a> (GPL). Removal of any copyright notices from this script or any of the files included with this script constitutes a violation of this license. Please do not steal my work.
<? include("adminmenu.php"); ?>
