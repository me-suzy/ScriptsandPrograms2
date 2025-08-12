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
<div class="lefthead"><a NAME="new">What's New</a></div> 
<ul>
<li>Completely redesigned user interface</li>
<li>Language support</li>
<li>Pseudo templating and skinning support</li>
<li>Default template is more neutral in color</li>
<li>Enhanced statistics</li>
<li>Category support</li>
<li>Mailing list support with included mailing list manager</li>
<li>Exchange ratio support (exchange wide)</li>
<li>Simplified administration control panel</li>
<li>Enhanced anti-cheat functions</li>
<li>DB-based anti-cheat logging</li>
<li>Session-based authentication and user tracking</li>
<li>MD5 password encryption support</li>
<li>Integrated help file</li>
<li>Banner upload facility for hosting banners locally</li>
<li>Plus much more...</li>
</ul>
[<a href="install.php#top">top</a>]<p>
<div class="lefthead"><a NAME="quickstart">Quick Start Guide (new install)</a></div> 
If you are upgrading your script from phpBannerExchange 1.x, see the section regarding <a href="install.php#upgrading">Upgrading</a> towards the end of this document.<ol>
<li>Unzip the distribution binary.</li>
<li>Upload the files to your Banner Exchange directory on your web server. There are no binary files, so insure the files are uploaded as ASCII.</li>
<li>chmod config.php to allow read/write access to it (755 or 777).</li>
<li>chmod manifest.php to allow read/write access to it (755 or 777).</li>
<li>chmod css.php to allow read/write access to it (755 or 777).</li>
<li>chmod your upload directory for global read/write access (777).</li>
<li>chmod your template directory for global read/write access (777).</li>
<li>chmod <b>admin/db</b> for global read/write access and set up an .htaccess file to password protect it.</li>
<li>Navigate to <i>http://yourdomain.com/exchange_directory/install/install.php</i>.</li>
<li>Follow the directions provided in the install script.</li>
<li><b>DELETE THE ENTIRE INSTALL DIRECTORY AFTER SUCCESSFULLY INSTALLING THE SCRIPT.</b></li></ol>
[<a href="install.php#top">top</a>]<p>
<div class="lefthead"><a NAME="detailed">Install.php</a></div>
phpBannerExchange 2.0 comes with an automatic installer script that allows you to set the appropriate variables, create the tables and insert your primary administrator account automatically. Before running the install script, insure all files included in the distribution archive have been uploaded to your server with the directory structure intact.<p>

To access the installation script, you will need to go to <i>http://www.yourdomain.com/root_bannerexchange_directory/install/install.php</i> in your web browser. Replace "yourdomain.com" with your domain name and "root_bannerexchange_directory" with the directory path you have uploaded the software to (eg. http://www.eschew.net/exchange/install/install.php).<p>

Once the files have been uploaded, you will need to configure several files and directories for write permissions. Because the installer writes directly to "config.php", "css.php", and "manifest.php" as well as the templates folder, you will need to insure that you have appropriate permissions to write to these files/directories. This is done by using "chmod" to change the permissions. Most FTP clients allow you to chmod a file by right clicking on it. Set the permissions to "755". In DOS/*nix FTP, use the following command:<p>

<i>quote site chmod 755 config.php</i><p>

If you are planning to allow your users to upload files, you will also need to create an upload directory (such as "banners") and chmod this directory to "777". The admin/db folder will also need to be chmod to 777. This folder contains database backups created by the database backup feature.<p>

<table class="tablebody" width="55%">
<tr>
<td valign="top"><b>File/Directory</b></td><td><b>Permission Required</b></td>
</tr>
<tr>
<td valign="top">/config.php</td><td>755 or 777</td>
</tr>
<tr>
<td valign="top">/manifest.php</td><td>755 or 777</td>
</tr>
<tr>
<td valign="top">/css.php</td><td>755 or 777</td>
</tr>
<tr>
<td valign="top">/templates/*</td><td>777</td>
</tr>
<tr>
<td valign="top">/admin/db/</td><td>777</td>
</tr>
<tr>
<td valign="top">/upload_dir/</td><td>777</td>
</tr>
</table>
<p>
[<a href="install.php#top">top</a>]<p>

<div class="lefthead"><a NAME="vars">Variables</a></div>
The following is a list of variables phpBannerExchange asks for during the installation process and a detailed description of what each variable does in the script.<p>

<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td class="tablebody" width=130 valign="top"><b>Name</b></td><td class="tablebody"><b>config.php Value</b></td><td class="tablebody"><b>Description</b></td>
</tr>
<tr>
<td class="tablebody" width=130 valign="top">Database Host:</b></td><td class="tablebody" valign="top">$dbhost</td><td class="tablebody">The host name of your database. This is most likely <i>localhost</i>.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Database Login:</td><td class="tablebody" valign="top">$dblogin</td><td class="tablebody">The username you use to connect to your database.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Database Password:</td><td class="tablebody" valign="top">$dbpass</td><td class="tablebody">The password you use to connect to your database.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Database Name:</td><td class="tablebody" valign="top">$dbname</td><td class="tablebody">The name of the database you wish to use to store your user data.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Base Exchange URL:</td><td class="tablebody" valign="top">$baseurl</td><td class="tablebody">The path on the Internet to which you have phpBannerExchange installed. For example, if you have installed the software to the directory "exchange" just off your root directory, the Base Exchange URL would be <i>http://www.yourdomain.com/exchange</i>. Do not include a trailing slash after the directory name.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Base Path:</td><td class="tablebody" valign="top">$basepath</td><td class="tablebody">The path (<b>NOT</b> the URL) of where the exchange script is installed. This should be entered automatically by the installation/upgrade script. If it is not, enter the base path (eg: <i>/home/www/exchange</i>). Do not include a trailing slash after the directory name.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Exchange Name:</td><td class="tablebody" valign="top">$exchangename</td><td class="tablebody" valign="top">The name of your exchange, as you would like it to appear in the exchange script. This value is displayed under the banner that is served as well as in e-mail and control panel pages. Examples: "The 1:1 Banner Exchange", "Commander Skippy's Banner Exchange"</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Site Name:</td><td class="tablebody">$sitename</td><td class="tablebody">The name of your site. This is used for the legalese in the "Conditions of Use".</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Admin Name:</td><td class="tablebody" valign="top">$adminname</td><td class="tablebody">Your name or alias. It is primarily used in the footer of each page.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Admin Email:<td class="tablebody" valign="top">$ownermail</td><td class="tablebody">The e-mail address you would like to use to receive exchange related questions, new account notifications, etc.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Banner Width:</td><td class="tablebody" valign="top">$bannerwidth</td><td class="tablebody">The width, in pixels, of the banners you would like to support with your exchange. Standard banners are 468 pixels wide.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Banner Height:</td><td class="tablebody" valign="top">$bannerheight</td><td class="tablebody">The height, in pixels, of the banners you would like to support with your exhange. Standard banners are 60 pixels wide.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Default Ratio:</td><td class="tablebody" valign="top">$steexp, $banexp</td><td class="tablebody">This is your default exchange ratio..sort of. The way this works is the first number is how many exposures are required to have one display. For example, setting this value to "3" will require 3 exposures before the account becomes eligible to display a banner. The second number is how many credits the script should take away when the banner is being displayed. So setting this option to "3" will take away 3 credits when the banner is displayed. Some common ratios are listed below:<p>
<b>1</b> banner display allows <b>1</b> banner to be displayed: <b>1:1</b><br>
<b>2</b> banner displays allows <b>1</b> banner to be displayed: <b>1:2</b><br>
<b>3</b> banner displays allows <b>2</b> banners to be displayed: <b>1:1.5</b></td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Show Exchange Image:</td><td class="tablebody" valign="top">$showimage</td><td class="tablebody">This will allow you to specify if you wish to display a small image to the left of the banner. Typically, this is a 60x60 image in a 468x60 banner exchange. It will link back to your Banner Exchange main page.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Exchange Image Position:</td><td class="tablebody" valign="top">$imagepos</td><td class="tablebody">The postion of the exchange image (as described above) relative to the banner itself. Options are Top, Bottom, Left or Right. Selecting Bottom for example, will display the Exchange Image (see below) under the banner.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Exchange Image URL:</td><td class="tablebody" valign="top">$imageurl</td><td class="tablebody">If you have decided to show an exchange image above, enter the full URL to the image here (eg: <i>http://www.yourdomain.com/image.gif</i>). Otherwise, you may leave this value blank.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Show Exchange Link:</td><td class="tablebody" valign="top">$showtext, $exchangetext</td><td class="tablebody">If you wish to show a brief exchange text link under the image, set this option to yes, and enter the text you wish to display here. You should keep this brief, something like "Join the (your exchange's name)!" or some such verbage.
</td>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Require Banner Approval:</td><td class="tablebody" valign="top">$reqbanapproval</td><td class="tablebody">This option controls how the banners are validated. You can set this option to <b>No</b> and the account and URL only need be approved, not the banners. setting this option to <b>Yes</b> will force the account into unvalidated mode when a banner is added or deleted.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Allow Uploads:</td><td class="tablebody" valign="top">$allow_upload</td><td class="tablebody">This option allows you to create an upload folder for your user's banners. Setting this option to <b>No</b> will require the user to his his or her own webspace and bandwidth to serve the banner for the exchange.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Maximum Filesize:</td> <td class="tablebody" valign="top">$max_filesize</td><td class="tablebody">The maximum filesize you wish to allow your users to upload. A 30K banner would be expressed as "30000", for example. You may leave this value blank if you do not wish to use the upload feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Upload Path:</td><td class="tablebody" valign="top">$upload_path</td><td class="tablebody">The *nix/Windows path to the upload directory. Typically, this will be something like <i>/home/username/public_html/exchange/upload</i> or similar. Do not include a trailing slash after the path. This path must be globally readable and writable (<i>chmod 777</i> upload_directory). Contact your hosting provider if you are unsure of the path to your html directory. Do not include a trailing slash in the path. You may leave this value blank if you do not wish to use the upload feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Upload directory URL:</td><td class="tablebody" valign="top">$banner_dir_url</td><td class="tablebody">This is the URL to your upload directory specified in the Upload Path variable above. An example of this would be <i>http://www.yourdomain.com/exchange/upload</i>. Do not include a trailing slash in your URL. You may leave this value blank if you do not wish to use the upload feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Maximum Banners:</td><td class="tablebody" valign="top">$maxbanners</td><td class="tablebody">This is the maximum amount of banners you wish to allow your users to add to their account. If you would like your users to have the ability to add an unlimited number of banners to their account, set this value to 0 (zero). This feature works for both uploaded AND remotely hosted banners.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Anti-Cheat method:</td><td class="tablebody" valign="top">$anticheat</td><td class="tablebody">Select the type of anti-cheat method you would like to use. Valid options are <b>DB</b>, <b>Cookie</b>, and <b>None</b>.<p>
The <b>Cookies</b> anti-cheat measure that uses cookies to store timeout data for a specific computer. Only after the timeout has expired will credits continue to add up for the account displaying the banner (the timeout value is expressed in a later variable).
<p>
The <b>DB</b> method is the same as the Use Cookies variable except it does not use cookies: all data is stored in a database and automatically purged when the timeout expires.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Duration:</td><td class="tablebody" valign="top">$expiretime</td><td class="tablebody">This is the timeout value for the two anti-cheat methods, expressed in seconds. This feature controls how long a client on the banner exchange member's page is not counted as an additional visit and subsequently an additional credit for the exchange member. It is recommended you set this value to a low number, perhaps <b>20 or 30 seconds</b>.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Referral Program:</td><td class="tablebody" valign="top">$referral_program</td><td class="tablebody">This option allows you to grant your users extra credits for signing up new users. By linking to your Banner Exchange main directory, (eg: <i>http://www.somesite.com/exchange/index.php</i>) with a special code, your end users can earn extra credits. For anti-cheat reasons, the credit is only granted after the created account is validated.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Referral Bounty:</td><td class="tablebody" valign="top">$referral_bounty</td><td class="tablebody">This option is the amount of credits you wish to reward your users with for referring users for the exchange.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Starting Credits:</td><td class="tablebody" valign="top">$startcredits</td><td class="tablebody">If you wish to provide new users with a set amount of free credits, enter this value here. When validating the account, you may change this amount for any individual account.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Sell Credits:</td><td class="tablebody" valign="top">$sellcredits</td><td class="tablebody">Enables the online store. The credit store will not be visible to end users unless this option is activated, even if there are items available for purchase. See the Online Store section for more details about the online store and configuration instructions.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Top x will display X accounts:</td><td class="tablebody" valign="top">$topnum</td><td class="tablebody">This value is used for the "top accounts" feature on the main client login page. Specify the maximum number of accounts you would like to display on this page.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Send Admin Email:</td><td class="tablebody" valign="top">$sendemail</td><td class="tablebody">If you would like to be notified when a new user signs up to the exchange, click the "Yes" radio button.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Use MD5 Encrypted Passwords:</td><td class="tablebody" valign="top">$usemd5</td><td class="tablebody">For security purposes, you may encrypt your passwords with the MD5 encryption routine, which is a 32 character hash of the password. Note that passwords will become unreadable (but will still work) when you use this feature.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Use gZip/Zend code:</td><td class="tablebody" valign="top">$use_gzhandler</td><td class="tablebody">If you wish to compress the pages and send the output as a gZip file, enable this option. This saves some bandwidth, but slows down the server.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Log Clicks:</td><td class="tablebody" valign="top">$log_clicks</td><td class="tablebody">Stores each click's date & time and IP address to the database for your end users. Note that on large exchanges, this could cause the database to get very large.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Use mySQL4 rand():</td><td class="tablebody" valign="top">$use_dbrand</td><td class="tablebody">Enables the mySQL rand() function in the view.php queries. This method is better than the exchange's built-in random algorythm, but requres mySQL 4.x or greater.</td>
</tr>
<tr class="tablebody">
<td class="tablebody" width=130 valign="top">Date Format:</td><td class="tablebody" valign="top">$date_format</td><td class="tablebody">Select the date style to use. (mm/dd/yyyy or dd/mm/yyyy).</td>
</tr>
<table>

[<a href="install.php#top">top</a>]<p>

<div class="lefthead"><a NAME="dbstructure">Database Structure</a></div>
Below is a dump of the database structure.<p>
<pre>
-- 
-- Table structure for table `banneradmin
-- 

CREATE TABLE banneradmin (
  id int(11) NOT NULL auto_increment,
  adminuser varchar(15) NOT NULL default '',
  adminpass varchar(255) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id,adminuser)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannercats`
-- 

CREATE TABLE bannercats (
  id int(7) NOT NULL auto_increment,
  catname varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerclicklog`
-- 

CREATE TABLE bannerclicklog (
  id int(11) NOT NULL auto_increment,
  siteid int(11) NOT NULL default '0',
  clickedtosite int(11) NOT NULL default '0',
  bannerid int(11) NOT NULL default '0',
  ip varchar(255) NOT NULL default '',
  page int(11) NOT NULL default '0',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannercommerce`
-- 

CREATE TABLE bannercommerce (
  productid int(11) NOT NULL auto_increment,
  productname text NOT NULL,
  credits decimal(14,0) NOT NULL default '0',
  price decimal(12,2) NOT NULL default '0.00',
  purchased int(11) NOT NULL default '0',
  UNIQUE KEY productid (productid)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerconfig`
-- 

CREATE TABLE bannerconfig (
  name varchar(255) NOT NULL default '',
  data longtext NOT NULL,
  PRIMARY KEY  (name)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerfaq`
-- 

CREATE TABLE bannerfaq (
  id int(11) NOT NULL auto_increment,
  question longtext NOT NULL,
  answer longtext NOT NULL,
  UNIQUE KEY id (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerlogs`
-- 

CREATE TABLE bannerlogs (
  uid int(11) NOT NULL default '0',
  ipaddr text NOT NULL,
  page int(11) NOT NULL default '0',
  timestamp text NOT NULL
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerpromologs`
-- 

CREATE TABLE bannerpromologs (
  id int(11) NOT NULL auto_increment,
  uid int(11) NOT NULL default '0',
  promoid int(11) NOT NULL default '0',
  usedate int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerpromos`
-- 

CREATE TABLE bannerpromos (
  promoid int(11) NOT NULL auto_increment,
  promoname varchar(255) NOT NULL default '',
  promocode varchar(255) NOT NULL default '',
  promotype int(11) NOT NULL default '0',
  promonotes text,
  promovals decimal(11,2) NOT NULL default '0.00',
  promocredits int(11) NOT NULL default '0',
  promoreuse tinyint(4) NOT NULL default '0',
  promoreuseint int(11) NOT NULL default '0',
  promousertype tinyint(4) NOT NULL default '0',
  ptimestamp int(11) NOT NULL default '0',
  promostatus tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (promoid)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerrefs`
-- 

CREATE TABLE bannerrefs (
  id int(11) NOT NULL auto_increment,
  uid int(11) NOT NULL default '0',
  refid tinyint(4) NOT NULL default '0',
  given tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannersales`
-- 

CREATE TABLE bannersales (
  invoice int(11) NOT NULL default '0',
  uid int(11) NOT NULL default '0',
  item_number int(11) NOT NULL default '0',
  payment_status text NOT NULL,
  payment_gross text NOT NULL,
  payer_email varchar(200) NOT NULL default '',
  timestamp int(14) NOT NULL default '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerstats`
-- 

CREATE TABLE bannerstats (
  uid int(11) NOT NULL default '0',
  category int(11) NOT NULL default '0',
  exposures int(11) NOT NULL default '0',
  credits int(11) NOT NULL default '0',
  clicks int(11) NOT NULL default '0',
  siteclicks int(11) NOT NULL default '0',
  approved tinyint(4) NOT NULL default '0',
  defaultacct tinyint(4) NOT NULL default '0',
  histexposures int(11) NOT NULL default '0',
  raw tinyint(4) NOT NULL default '0',
  startdate int(11) NOT NULL default '0',
  PRIMARY KEY  (uid)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `bannerurls`
-- 

CREATE TABLE bannerurls (
  id int(11) NOT NULL auto_increment,
  bannerurl varchar(200) NOT NULL default '',
  targeturl varchar(255) NOT NULL default '',
  clicks tinyint(4) NOT NULL default '0',
  views int(11) NOT NULL default '0',
  uid int(11) NOT NULL default '0',
  pos int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `banneruser`
-- 

CREATE TABLE banneruser (
  id int(11) NOT NULL auto_increment,
  login varchar(20) NOT NULL default '',
  pass varchar(255) NOT NULL default '',
  name varchar(200) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  newsletter tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY id (id,login)
) TYPE=MyISAM;

</pre>        
[<a href="install.php#top">top</a>]<p>

<div class="lefthead"><a NAME="upgrading">Upgrading</a></div>
Upgrading your script is almost as easy as installing it. There is an upgrade script located in the /install directory that will make the necessary table and config.php changes.<p>

<div class="lefthead">Upgrading from 1.x to 2.0 Instructions</div>
<ol>
<li>BACKUP YOUR ORIGINAL EXCHANGE DIRECTORY AND DATABASE ENTRIES FOR THE SCRIPT. This is extremely important unless you want to gamble and play the numbers for losing data.</li>
<li>Upload all the files to your Banner Exchange directory on your web server. There are no binary files, so insure the files are uploaded as ASCII.</li>
<li>chmod config.php to allow read/write access to it (755 or 777).</li>
<li>chmod manifest.php to allow read/write access to it (755 or 777).</li>
<li>chmod css.php to allow read/write access to it (755 or 777).</li>
<li>chmod your upload directory for global read/write access (777).</li>
<li>chmod your template directory for global read/write access (777).</li>
<li>Navigate to <i>http://yourdomain.com/exchange_directory/install/install.php</i>.</li>
<li>Choose the "1.x to 2.0 upgrade" option and follow the directions provided in the install script. If you need help with what the variables do, <a href="install.php?#vars">look here</a>.</li>
<li><b>DELETE THE ENTIRE INSTALL DIRECTORY AFTER SUCCESSFULLY INSTALLING THE SCRIPT.</b></li></ol>

<div class="lefthead">Upgrading from 2.0 RCx to 2.0 Gold Instructions</div>
<ol>
<li>BACKUP YOUR ORIGINAL EXCHANGE DIRECTORY AND DATABASE ENTRIES FOR THE SCRIPT. This is extremely important unless you want to gamble and play the numbers for losing data.</li>
<li>Upload all the files to your Banner Exchange directory on your web server. There are no binary files, so insure the files are uploaded as ASCII.</li>
<li>chmod config.php to allow read/write access to it (755 or 777).</li>
<li>chmod manifest.php to allow read/write access to it (755 or 777).</li>
<li>chmod css.php to allow read/write access to it (755 or 777).</li>
<li>chmod your upload directory for global read/write access (777).</li>
<li>chmod your template directory for global read/write access (777).</li>
<li>Navigate to <i>http://yourdomain.com/exchange_directory/install/install.php</i>.</li>
<li>Follow the directions provided in the install script. If you need help with what the variables do, <a href="install.php?#vars">look here</a>.</li>
<li><b>DELETE THE ENTIRE INSTALL DIRECTORY AFTER SUCCESSFULLY INSTALLING THE SCRIPT.</b></li></ol>
[<a href="install.php#top">top</a>]<p>

<div class="lefthead"><a NAME="gethelp">Getting Help</a></div>
There is no formal support for this script. I neither have the time nor the inclination to provide technical support for this script. You should be OK provided you read the documentation and carefully follow the instructions provided therein.<p>
If you run in to a problem with the script and do need help, you can go to the <a href="http://www.eschew.net/forums/">Support Forums</a> and ask a question.<p>
<div class="lefthead">Bugs and Bug Reporting</div>
Please let me know if you find a bug in the software. I spent a considerable amount of time debugging the script however I can not check everything on my own. If you happen to run across a bug, please report it in the <a href="http://www.eschew.net/forums/">Support Forums</a>. Be sure to include a detailed description of the bug you encountered, the error message, or the result condition so I can address the problem.<p>
<div class="lefthead">Security Issues</div>
If you run across a security issue/vulnerability, please report it directly to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a> so I can make an announcement on the forums once the script has been fixed. These types of problems usually require some sort of exploit and could compromise the security of thousands of machines. If you happen to run across a bug of this magnitude, it is essential that I be notified first so I can protect the security of these machines. Please be aware that support issues sent to this address will be ignored. You will NOT receive a reply support issues if e-mailed to me.<p>
<div class="lefthead">Mods, Localization and Templates</div>
If you change something in the script or add a new feature, I encourage you to share it with the other users. My vision of what makes a good Banner Exchange script as far as features are concerned might be vastly different than yours, so if you make a modification to the script, I encourage you to share it with me. I plan to make all working mods to the script available for download as an add-on to the base script. If the modification is good enough, I will even include it in a future version of the script and you will receive credit for the code you have submitted or changed. You may e-mail your mods to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a>.<p>
I am seeking assistance with translating the software into different languages. All language files for the script are located in the <b>lang/</b> directory under your root banner exchange directory. If you translate the files, please provide me with a copy and I will make it available for all users. You may e-mail the translated language files to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a>.<p>
The templates are located in the <b>template/</b> directory. If you alter the templates or style.css file, please send me your new files and I will make them available to all users. You may email them to me at <a href="mailto:darkrose@eschew.net">darkrose@eschew.net</a>.<br>
[<a href="install.php#top">top</a>]<p>
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
<? include("installmenu.php"); ?>