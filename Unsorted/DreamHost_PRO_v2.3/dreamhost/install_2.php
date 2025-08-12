<html>
<head>
<title>DreamHost Installer Step 2.</title>
</head>
<body>
<?
/* This software is developed & licensed by Dreamcost.com.
Unauthorized distribution, sales, or use of any of the code, in part or in whole, is
strictly prohibited and will be prosecuted to the full extent of the law. */

require("setup.php");
define("DB_HOST", "$host");
define("DB_NAME", "$database");
define("DB_USER", "$user");
define("DB_PWD", "$pass");
require("db.conf");

$db = new ps_DB;
$q = "CREATE TABLE account (
   account_id int(11) NOT NULL auto_increment,
   account_auth_id int(11) DEFAULT '0' NOT NULL,
   account_membership_id int(11) DEFAULT '0' NOT NULL,
   account_affiliate_id int(11) DEFAULT '0' NOT NULL,
   account_status tinyint(4) DEFAULT '0' NOT NULL,
   account_password varchar(32),
   account_email varchar(32) NOT NULL,
   account_phone varchar(12) NOT NULL,
   account_fax varchar(12) NOT NULL,
   account_name varchar(32) NOT NULL,
   account_company varchar(32) NOT NULL,
   account_address varchar(32) NOT NULL,
   account_city varchar(16) NOT NULL,
   account_state varchar(16) NOT NULL,
   account_zip varchar(10) NOT NULL,
   account_country varchar(32) NOT NULL,
   PRIMARY KEY (account_id),
   UNIQUE account_email (account_email),
   KEY account_email_2 (account_email),
   KEY account_name (account_name)
);"; $db->query($q);



$db = new ps_DB;
$q = "CREATE TABLE notes (
   note_id int(11) NOT NULL auto_increment,
   note_account_id int(11) DEFAULT '0' NOT NULL,
   note_domain_id int(11) DEFAULT '0' NOT NULL,
   note_order_id int(11) DEFAULT '0' NOT NULL,
   note_message text NOT NULL,
   PRIMARY KEY (note_id),
   UNIQUE note_id (note_id)
);"; $db->query($q);




$db = new ps_DB;
$q = "CREATE TABLE affiliate (
   affiliate_id int(11) NOT NULL auto_increment,
   affiliate_account_id int(11) DEFAULT '0' NOT NULL,
   affiliate_date varchar(12) NOT NULL,
   affiliate_type char(1) NOT NULL,
   affiliate_name varchar(32) NOT NULL,
   affiliate_address varchar(64) NOT NULL,
   affiliate_city varchar(32) NOT NULL,
   affiliate_state varchar(32) NOT NULL,
   affiliate_zip varchar(12) NOT NULL,
   affiliate_country varchar(12) NOT NULL,
   PRIMARY KEY (affiliate_id),
   UNIQUE affiliate_account_id (affiliate_account_id)
);"; $db->query($q);



$db = new ps_DB;
$q = "CREATE TABLE attributes (
   attribute_id int(11) NOT NULL auto_increment,
   attribute_name varchar(64) NOT NULL,
   attribute_desc varchar(255) NOT NULL,
   attribute_type char(1) NOT NULL,
   attribute_value varchar(32) NOT NULL,
   attribute_active char(1) NOT NULL,
   attribute_1 varchar(32) NOT NULL,
   attribute_2 varchar(32) NOT NULL,
   attribute_3 varchar(32) NOT NULL,
   attribute_4 varchar(32) NOT NULL,
   attribute_5 varchar(32) NOT NULL,
   attribute_6 varchar(32) NOT NULL,
   attribute_7 varchar(32) NOT NULL,
   attribute_8 varchar(32) NOT NULL,
   attribute_9 varchar(32) NOT NULL,
   attribute_10 varchar(32) NOT NULL,
   PRIMARY KEY (attribute_id)
);"; $db->query($q);




$db = new ps_DB;
$q = "CREATE TABLE billed (
   billed_id int(11) NOT NULL auto_increment,
   billed_account_id varchar(12) NOT NULL,
   billed_membership_id int(11) DEFAULT '0' NOT NULL,
   billed_date varchar(32) NOT NULL,
   billed_amount varchar(32) DEFAULT '0' NOT NULL,
   billed_type int(11) DEFAULT '0' NOT NULL,
   billed_order_id int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (billed_id)
);"; $db->query($q);



$db = new ps_DB;
$q = "CREATE TABLE billing (
   billing_id int(11) NOT NULL auto_increment,
   billing_account_id varchar(12) NOT NULL,
   billing_cc_num text NOT NULL,
   billing_cc_exp varchar(16) NOT NULL,
   billing_order_id int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (billing_id)
);"; $db->query($q);







$db = new ps_DB;
$q = "CREATE TABLE country (
   country_id int(11) DEFAULT '0' NOT NULL,
   country_name varchar(64),
   country_3_code char(3),
   country_2_code char(2),
   PRIMARY KEY (country_id)
);"; $db->query($q);

$db = new ps_DB; $q = "INSERT INTO country VALUES ( '32', 'ARGENTINA', 'ARG', 'AR');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '36', 'AUSTRALIA', 'AUS', 'AU');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '40', 'AUSTRIA', 'AUT', 'AT');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '44', 'BAHAMAS', 'BHS', 'BS');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '84', 'BELIZE', 'BLZ', 'BZ');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '76', 'BRAZIL', 'BRA', 'BR');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '124', 'CANADA', 'CAN', 'CA');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '136', 'CAYMAN ISLANDS', 'CYM', 'KY');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '156', 'CHINA', 'CHN', 'CN');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '192', 'CUBA', 'CUB', 'CU');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '214', 'DOMINICAN REPUBLIC', 'DOM', 'DO');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '250', 'FRANCE', 'FRA', 'FR');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '276', 'GERMANY', 'DEU', 'DE');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '300', 'GREECE', 'GRC', 'GR');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '304', 'GREENLAND', 'GRL', 'GL');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '308', 'GRENADA', 'GRD', 'GD');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '312', 'GUADELOUPE', 'GLP', 'GP');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '316', 'GUAM', 'GUM', 'GU');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '332', 'HAITI', 'HTI', 'HT');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '356', 'INDIA', 'IND', 'IN');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '360', 'INDONESIA', 'IDN', 'ID');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '368', 'IRAQ', 'IRQ', 'IQ');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '372', 'IRELAND', 'IRL', 'IE');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '376', 'ISRAEL', 'ISR', 'IL');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '380', 'ITALY', 'ITA', 'IT');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '388', 'JAMAICA', 'JAM', 'JM');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '392', 'JAPAN', 'JPN', 'JP');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '400', 'JORDAN', 'JOR', 'JO');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '414', 'KUWAIT', 'KWT', 'KW');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '458', 'MALAYSIA', 'MYS', 'MY');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '484', 'MEXICO', 'MEX', 'MX');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '566', 'NIGERIA', 'NGA', 'NG');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '578', 'NORWAY', 'NOR', 'NO');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '586', 'PAKISTAN', 'PAK', 'PK');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '604', 'PERU', 'PER', 'PE');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '608', 'PHILIPPINES', 'PHL', 'PH');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '616', 'POLAND', 'POL', 'PL');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '620', 'PORTUGAL', 'PRT', 'PT');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '630', 'PUERTO RICO', 'PRI', 'PR');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '642', 'ROMANIA', 'ROM', 'RO');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '643', 'RUSSIAN FEDERATION', 'RUS', 'RU');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '702', 'SINGAPORE', 'SGP', 'SG');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '710', 'SOUTH AFRICA', 'ZAF', 'ZA');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '752', 'SWEDEN', 'SWE', 'SE');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '756', 'SWITZERLAND', 'CHE', 'CH');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '764', 'THAILAND', 'THA', 'TH');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '792', 'TURKEY', 'TUR', 'TR');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '826', 'UNITED KINGDOM', 'GBR', 'GB');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO country VALUES ( '840', 'UNITED STATES', 'USA', 'US');"; $db->query($q);

$db = new ps_DB; $q = "CREATE TABLE credit (
   credit_id int(11) NOT NULL auto_increment,
   credit_account_id int(11) DEFAULT '0' NOT NULL,
   credit_order_id int(11) DEFAULT '0' NOT NULL,
   credit_domain_id int(11) DEFAULT '0' NOT NULL,
   credit_type char(1) NOT NULL,
   credit_amount varchar(16) NOT NULL,
   credit_amount_applied varchar(16) NOT NULL,
   credit_date_added varchar(12) NOT NULL,
   credit_date_applied varchar(12) NOT NULL,
   credit_notes text NOT NULL,
   credit_status char(1) DEFAULT '0' NOT NULL,
   PRIMARY KEY (credit_id)
);"; $db->query($q);



$db = new ps_DB; $q = "CREATE TABLE domain_type (
   domain_type_id int(11) NOT NULL auto_increment,
   domain_type_name varchar(128) NOT NULL,
   domain_type_extension varchar(12) NOT NULL,
   domain_type_status char(1) NOT NULL,
   domain_type_url varchar(128) NOT NULL,
   domain_type_response varchar(128) NOT NULL,
   domain_type_p1 int(11),
   domain_type_p2 int(11),
   domain_type_p3 int(11),
   domain_type_p4 int(11),
   domain_type_p5 int(11),
   domain_type_p6 int(11),
   domain_type_p7 int(11),
   domain_type_p8 int(11),
   domain_type_p9 int(11),
   domain_type_p10 int(11),
   domain_type_auto char(1) NOT NULL,
   PRIMARY KEY (domain_type_id),
   UNIQUE domain_type_id (domain_type_id)
);"; $db->query($q);


$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '1', 'USA .COM', 'com', 'Y', 'whois.networksolutions.com', 'NO MATCH', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 'Y');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '2', 'NET (USA)', 'net', 'Y', 'whois.internic.net', 'No match for', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '3', 'ORG (USA)', 'org', 'Y', 'whois.internic.net', 'No match for', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '4', 'EDU (USA)', 'edu', '', 'whois.internic.net', 'No match for', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '7', 'CA (CANADA)', 'ca', '', 'whois.cira.ca', 'AVAIL', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '6', 'DE (GERMANY)', 'de', '', 'whois.ripe.net', 'No entries found for the selected source', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '8', 'Ascension Domain', 'ac', '', 'whois.nic.ac', 'No match', '25', '25', '25', '25', '25', '25', '25', '25', '25', '25', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '9', 'UK (United Kingdom)', 'co.uk', 'Y', 'whois.nic.uk', 'No match for', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', 'Y');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '10', 'MX (Mexico)', 'com.mx', '', 'whois.nic.mx', 'Referencias de Organization No Encontradas', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '11', 'JP (Japan)', 'jp', '', 'whois.nic.ad.jp', 'No match!!', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '12', 'FR (France)', 'fr', '', 'whois.nic.fr', 'No entries', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '13', 'IL (?)', 'il', '', 'whois.internic.il', 'No Match', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '16', 'Belgium', 'be', '', 'whois.dns.be', 'No Match', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '19', 'Brasil', 'br', '', 'whois.nic.br', 'No match', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '21', 'Pakistan', 'com.pk', '', 'whois.pknic.net.pk', 'No Match', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '22', 'Denmark', 'dk', '', 'whois.dk-hostmaster.dk', 'No Match', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '24', 'TR', 'com.tr', '', 'whois.metu.edu.tr', 'No Match', '25', '23', '23', '22', '22', '21', '21', '20', '20', '19', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '25', 'Norway', 'no', '', 'whois.norid.no', 'No Match', '20', '0', '0', '0', '0', '0', '0', '0', '0', '0', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '26', 'Info', 'info', '', 'whois.cgi', 'No Domain', '30', '0', '0', '0', '0', '0', '0', '0', '0', '0', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '27', 'Dutch', 'nl', '', 'whois.nic.nl', 'No Match', '10', '10', '10', '0', '0', '0', '0', '0', '0', '0', '');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '28', 'Canada Alberta Domain', 'ab.ca', 'Y', 'whois.cira.ca', 'AVAIL', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', 'Y');"; $db->query($q);
$db = new ps_DB; $q = "INSERT INTO domain_type VALUES ( '29', 'American Samoa Domain', 'as', '', 'whois.nic.as', 'Domain Not Found', '25', '25', '25', '25', '25', '25', '25', '25', '25', '25', '');"; $db->query($q);

$db = new ps_DB; $q = "CREATE TABLE domains (
   domain_id int(11) NOT NULL auto_increment,
   domain_account_id int(11) DEFAULT '0' NOT NULL,
   domain_billing_id int(11) DEFAULT '0' NOT NULL,
   domain_type_id int(11) DEFAULT '0' NOT NULL,
   domain_name varchar(64) NOT NULL,
   domain_start_date varchar(12) NOT NULL,
   domain_host_id int(11) DEFAULT '0' NOT NULL,
   domain_host_periods int(11) DEFAULT '0' NOT NULL,
   domain_host_last_billed varchar(12) NOT NULL,
   domain_host_status char(1) NOT NULL,
   domain_misc varchar(32) NOT NULL,
   domain_years int(11) DEFAULT '0' NOT NULL,
   domain_order_id int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (domain_id),
   UNIQUE domain_id (domain_id)
);"; $db->query($q);

$db = new ps_DB; $q = "CREATE TABLE email (
   email_id int(11) NOT NULL auto_increment,
   email_name varchar(64) NOT NULL,
   email_description varchar(255) NOT NULL,
   email_template text NOT NULL,
   email_shortcuts text NOT NULL,
   PRIMARY KEY (email_id)
);"; $db->query($q);

$db = new ps_DB; $q = "INSERT INTO email VALUES ( '1', 'New Account Registration (<EMAIL>)', 'This template is to notify the administrator of a new account registration.', 'This is an automatic notification from <company>.

A new customer registered at <company>\'s website on <date>.

Customer Info:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email: <EMAIL>
Password: <PW>

Account Admin Area: <url>

---------------------------------------------
This message was sent by DreamHost Pro.

You can see the Shopping Cart & Member Account mangagement functions in action at:
http://www.dreamcost.com/dreamhost/

If you would like to test the demo area, the url is:
http://www.dreamcost.com/dreamhost/admin/
username: admin
password: admin

You can change your account information at this area if you are bothered by these emails.

Thank you,
Sales Department
Dreamcost.com
http://dreamcost.com/', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);








$db = new ps_DB; $q = "INSERT INTO email VALUES ( '2', 'New Order', 'This template is to notify the administrator of a new order.', 'This is an automatic notification from <company>.

A new customer placed Order Id. <ID> at <company>\'s website on <date>.

Order Info:
Amount: <currency><AMOUNT>

<NEWDOMAINS>

<TRANSFERDOMAINS>


Customer Info:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email: <EMAIL>
Password: <PW>

---------------------------------------------
This message was sent by DreamHost Pro.

You can see the Shopping Cart & Member Account mangagement functions in action at:
http://www.dreamcost.com/dreamhost/

If you would like to test the demo area, the url is:
http://www.dreamcost.com/dreamhost/admin/
username: admin
password: admin

You can change your account information at this area if you are bothered by these emails.

Thank you,
Sales Department
Dreamcost.com
http://dreamcost.com/
---------------------------------------------
', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;ID&gt;     = Order ID 
&lt;AMOUNT&gt; = Order Amount 

&lt;NEWDOMAINS&gt;      = New Domains 
&lt;TRANSFERDOMAINS&gt; = Transfered Domains');"; $db->query($q);






$db = new ps_DB; $q = "INSERT INTO email VALUES ( '3', 'New Affiliate', 'This template is to notify the administrator of a new affiliate registration.', 'This is an automatic affiliate signup notification.

<NAME> joined our affiliate program on <date>.

Affiliate Info:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email: <EMAIL>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);



$db = new ps_DB; $q = "INSERT INTO email VALUES ( '4', 'Domain Renewal', 'This template is to notify the administrator of a domain being renewed by a customer online..', 'This is an automatic domain renewal notification.

<NAME> renewed a domain on <date>.

Domain Informaion:
Domain Name: <DOMAIN>
Hosting Plan: <PLANNAME>

Customer Info:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email: <EMAIL>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;DOMAIN&gt;   = Name of Domain
&lt;PLANNAME&gt; = Name of hosting plan');"; $db->query($q);







$db = new ps_DB; $q = "INSERT INTO email VALUES ( '5', 'Hosting Option Updated', 'This template is to notify the administrator of a hosting plan change made  by a customer online..', 'This is an automatic hosting plan modification request.

<NAME> requested a hosting plan change on <date>.

Domain Informaion:
Domain Name: <DOMAIN>
New Hosting Plan: <PLANNAME>

Customer Info:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email: <EMAIL>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password


&lt;DOMAIN&gt;   = Name of Domain
&lt;PLANNAME&gt; = Name of hosting plan');"; $db->query($q);


$db = new ps_DB; $q = "INSERT INTO email VALUES ( '8', 'Affiliate Registration', 'This template is to notify the affiliate when they signup for an affiliate account.', 'Hello <NAME>,

This is an automatic message from <company>.

You have been registered for an affiliate account successfully.

Your account information:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email Address: <EMAIL>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password
');"; $db->query($q);





$db = new ps_DB; $q = "INSERT INTO email VALUES ( '9', 'New Affiliate Credit', 'This template is to notify the affiliate when they recieve a credit to their account.', 'Hello <NAME>,

This is an automatic message from <company>.

You have just recieved a credit to you affiliate account.

To view the details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);




$db = new ps_DB; $q = "INSERT INTO email VALUES ( '10', 'New Affiliate Referral', 'This template is to notify the affiliate when someone they referred places an order.', 'Hello <NAME>,

This is an automatic message from <company>.

Someone you referred to <company> has just placed an order in the amount of <currency><AMOUNT>.

To view the details, log into your account <url>members.php?

Once the order is done processing, we will issue a credit to your account.

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);








$db = new ps_DB; $q = "INSERT INTO email VALUES ( '11', 'Account Registration', 'This template is to notify the customer after they sign up.', 'Hello <NAME>,

This is an automatic message from <company>.

Thank you for registering online with us.

Username: <EMAIL>
Password: <PW>

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);


$db = new ps_DB; $q = "INSERT INTO email VALUES ( '12', 'Thank you for your order!', 'This template is to notify the customer after they sign up.', 'Hello <NAME>,

This is an automatic notification from <company>.

Thank you for your new order!

You placed Order Id. <ID> at <company>\'s website on <date>.

Order Info:
Amount: <currency><AMOUNT>

<NEWDOMAINS>

<TRANSFERDOMAINS>


Billing Info:
<NAME>
<ADDRESS>
<CITY>, <STATE> <ZIP>

Email: <EMAIL>
Password: <PW>

---------------------------------------------
This message was sent by DreamHost Pro.

You can see the Shopping Cart & Member Account mangagement functions in action at:
http://www.dreamcost.com/dreamhost/

If you would like to test the demo area, the url is:
http://www.dreamcost.com/dreamhost/admin/
username: admin
password: admin

You can change your account information at this area if you are bothered by these emails.

Thank you,
Sales Department
Dreamcost.com
http://dreamcost.com/
---------------------------------------------
', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;ID&gt;     = Order ID 
&lt;AMOUNT&gt; = Order Amount 

&lt;DOMAIN&gt;   = Name of Domain
&lt;PLANNAME&gt; = Name of hosting plan');"; $db->query($q);









$db = new ps_DB; $q = "INSERT INTO email VALUES ( '13', 'Account Updated', 'This template is to notify the customer after they update their account info..', 'Hello <NAME>,

This is an automatic message from <company>.

Your account information has been updated.

Username: <EMAIL>
Password: <PW>

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);







$db = new ps_DB; $q = "INSERT INTO email VALUES ( '14', 'Your Credit Card Updated', 'This template is to notify the customer after they update CC info.', 'Hello <NAME>,

This is an automatic message from <company>.

Your Credit Card information has been updated.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);



$db = new ps_DB; $q = "INSERT INTO email VALUES ( '15', 'New Account Credit', 'This template is to notify the customer after there account is credited', 'Hello <NAME>,

This is an automatic message from <company>.

Your account has just been issued a credit.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);








$db = new ps_DB; $q = "INSERT INTO email VALUES ( '16', 'Billing Notice', 'This template is to notify the customer that they will be bill in the next few days.', 'Hello <NAME>,

This is an automatic message from <company>.

Your account is sceduled to be billed in 5 days.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);




$db = new ps_DB; $q = "INSERT INTO email VALUES ( '17', 'Billed Notice', 'This template is to notify the customer that they will be bill in the next few days.', 'Hello <NAME>,

This is an automatic message from <company>.

Your account has just been billed for any due charges.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);








$db = new ps_DB; $q = "INSERT INTO email VALUES ( '18', 'Domain Expiring Soon', 'This template is to notify the customer that there domain is expiring soon.', 'Hello <NAME>,

This is an automatic message from <company>.

Your have a domain that is expiring within the next 30 days.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;DOMAIN&gt;   = Name of Domain
&lt;PLANNAME&gt; = Name of hosting plan');"; $db->query($q);







$db = new ps_DB; $q = "INSERT INTO email VALUES ( '19', 'Card Declined', 'This template is to notify the customer that their card declined', 'Hello <NAME>,

This is an automatic message from <company>.

We attempted to bill your card on file for current charges and it declined.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password');"; $db->query($q);







$db = new ps_DB; $q = "INSERT INTO email VALUES ( '20', 'Domain Renewal', 'This template is to notify the customer that their domain has been renewed.', 'Hello <NAME>,

This is an automatic message from <company>.

Your domain (<DOMAIN>) has been renewed as you requested.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;DOMAIN&gt;   = Name of Domain
&lt;PLANNAME&gt; = Name of hosting plan');"; $db->query($q);







$db = new ps_DB; $q = "INSERT INTO email VALUES ( '21', 'Hosting Plan Update', 'This template is to notify the customer that their hosting plan will be updated', 'Hello <NAME>,

This is an automatic message from <company>.

Your hosting plan for (<DOMAIN>) has been schedule for modification as you requested.

The new hosting plan is <PLANNAME>.

To view your account details, log into your account <url>members.php?

Thanks!
<company>', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;DOMAIN&gt;   = Name of Domain
&lt;PLANNAME&gt; = Name of hosting plan');"; $db->query($q);







$db = new ps_DB; $q = "INSERT INTO email VALUES ( '22', 'New Domain Registration', 'This template is to notify the registrar to enter new domains into the DNS', 'Dear Registrar,

This is an automatic notification from <company>.

A customer placed Order Id. <ID> at <company>\'s website on <date>.

Please register the following domain(s):

<NEWDOMAINS>

Customer Info:
Email:   <EMAIL>
Name:    <NAME>
Address: <ADDRESS>
City:    <CITY>
State:   <STATE>
Zip:     <ZIP>


---------------------------------------------
This message was sent by DreamHost Pro.

You can see the Shopping Cart & Member Account mangagement functions in action at:
http://www.dreamcost.com/dreamhost/

If you would like to test the demo area, the url is:
http://www.dreamcost.com/dreamhost/admin/
username: admin
password: admin

You can change your account information at this area if you are bothered by these emails.

Thank you,
Sales Department
Dreamcost.com
http://dreamcost.com/
---------------------------------------------
', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email
&lt;PW&gt;     = Customer Password

&lt;NEWDOMAINS&gt;      = New Domains 
&lt;TRANSFERDOMAINS&gt; = Transfered Domains');"; $db->query($q);










$db = new ps_DB; $q = "INSERT INTO email VALUES ( '23', 'Renew Domain Registration', 'This template is to notify the registrar to update new domains into the DNS', 'Dear Registrar,

This is an automatic notification from <company>.

Please renew the following domain:

DOMAIN INFORMATION:
<DOMAINDETAILS>

CUSTOMER INFORMATION:
--------------------------------------- 
Email:        <EMAIL>
Name:         <NAME>
Address:      <ADDRESS>
City:         <CITY>
State:        <STATE>
Zip:          <ZIP>
--------------------------------------- 

Thank you!
<company>
<email>


---------------------------------------------
This message was sent by DreamHost Pro.

You can see the Shopping Cart & Member Account mangagement functions in action at:
http://www.dreamcost.com/dreamhost/

If you would like to test the demo area, the url is:
http://www.dreamcost.com/dreamhost/admin/
username: admin
password: admin

You can change your account information at this area if you are bothered by these emails.

Thank you,
Sales Department
Dreamcost.com
http://dreamcost.com/
---------------------------------------------
', '&lt;NAME&gt;   = Customer Name
&lt;ADDRESS&gt;= Customer Address 
&lt;CITY&gt;   = Customer City
&lt;STATE&gt;  = Customer Sate
&lt;ZIP&gt;    = Customer ZIP
&lt;EMAIL&gt;  = Customer Email

&lt;DOMAINDETAILS&gt; = Domain details');"; $db->query($q);







$db = new ps_DB; $q = "CREATE TABLE login (
   login_id varchar(32) NOT NULL,
   login_logged char(1) NOT NULL,
   login_member_id int(11) DEFAULT '0' NOT NULL,
   login_affiliate_id int(11) DEFAULT '0' NOT NULL,
   login_date timestamp(14),
   PRIMARY KEY (login_id),
   UNIQUE login_id (login_id)
);"; $db->query($q);

$db = new ps_DB; $q = "CREATE TABLE membership (
   membership_id int(11) NOT NULL auto_increment,
   membership_name varchar(64) NOT NULL,
   membership_desc text NOT NULL,
   membership_price varchar(32) DEFAULT '0' NOT NULL,
   membership_setup varchar(12) DEFAULT '0' NOT NULL,
   membership_recurring tinytext NOT NULL,
   membership_frequency int(11) DEFAULT '0' NOT NULL,
   membership_periods int(11) DEFAULT '0' NOT NULL,
   membership_approval tinyint(4) DEFAULT '0' NOT NULL,
   membership_url varchar(64) NOT NULL,
   membership_active char(1) NOT NULL,
   PRIMARY KEY (membership_id)
);"; $db->query($q);

$db = new ps_DB; $q = "CREATE TABLE orders (
   order_id int(11) DEFAULT '0' NOT NULL,
   order_account_id int(11) DEFAULT '0' NOT NULL,
   order_billing_id int(11) DEFAULT '0' NOT NULL,
   order_affiliate_id int(11) DEFAULT '0' NOT NULL,
   order_amount varchar(32) DEFAULT '0' NOT NULL,
   order_setup varchar(32) DEFAULT '0' NOT NULL,
   order_date varchar(32) NOT NULL,
   order_status tinyint(4) DEFAULT '0' NOT NULL,
   order_attr_name_1 varchar(32) NOT NULL,
   order_attr_value_1 varchar(64) NOT NULL,
   order_attr_name_2 varchar(32) NOT NULL,
   order_attr_value_2 varchar(64) NOT NULL,
   order_attr_name_3 varchar(32) NOT NULL,
   order_attr_value_3 varchar(64) NOT NULL,
   order_attr_name_4 varchar(32) NOT NULL,
   order_attr_value_4 varchar(64) NOT NULL,
   order_attr_name_5 varchar(32) NOT NULL,
   order_attr_value_5 varchar(64) NOT NULL,
   order_attr_name_6 varchar(32) NOT NULL,
   order_attr_value_6 varchar(64) NOT NULL,
   order_attr_name_7 varchar(32) NOT NULL,
   order_attr_value_7 varchar(64) NOT NULL,
   order_attr_name_8 varchar(32) NOT NULL,
   order_attr_value_8 varchar(64) NOT NULL,
   order_attr_name_9 varchar(32) NOT NULL,
   order_attr_value_9 varchar(64) NOT NULL,
   order_attr_name_10 varchar(32) NOT NULL,
   order_attr_value_10 varchar(64) NOT NULL,
   UNIQUE order_id_2 (order_id),
   KEY order_id (order_id)
);"; $db->query($q);

$db = new ps_DB; $q = "CREATE TABLE sessions (
   session_id int(11) NOT NULL auto_increment,
   session_affiliate_id int(11) DEFAULT '0' NOT NULL,
   session_ip varchar(32) NOT NULL,
   session_domain varchar(128) NOT NULL,
   session_1 tinyint(4) DEFAULT '0' NOT NULL,
   session_2 tinyint(4) DEFAULT '0' NOT NULL,
   PRIMARY KEY (session_id)
);"; $db->query($q);




$db = new ps_DB; $q = "CREATE TABLE setup (
   setup_id tinyint(4) DEFAULT '0' NOT NULL,
   setup_login varchar(32) DEFAULT '0' NOT NULL,
   setup_password varchar(32) DEFAULT '0' NOT NULL,
   setup_superuser varchar(32) DEFAULT '0' NOT NULL,
   setup_path text NOT NULL,
   setup_url varchar(64) DEFAULT '0' NOT NULL,
   setup_email varchar(32) DEFAULT '0' NOT NULL,
   setup_email_signup varchar(32) DEFAULT '0' NOT NULL,
   setup_email_admin varchar(32) DEFAULT '0' NOT NULL,
   setup_currency varchar(5) DEFAULT '0' NOT NULL,
   setup_tax varchar(4) DEFAULT '0' NOT NULL,
   setup_tax_rate varchar(10) DEFAULT '0' NOT NULL,
   setup_max_results varchar(5) DEFAULT '0' NOT NULL,
   setup_domain_suggest char(1) NOT NULL,
   setup_company varchar(64) NOT NULL,
   setup_affiliate char(1) NOT NULL,
   setup_aff_type char(1) NOT NULL,
   setup_aff_pay_1 char(1) NOT NULL,
   setup_aff_pay_1a varchar(16) NOT NULL,
   setup_aff_pay_2 char(1) NOT NULL,
   setup_aff_pay_2a varchar(16) NOT NULL,
   setup_aff_pay_2b char(3) NOT NULL,
   setup_aff_pay_2c varchar(12) NOT NULL,
   setup_curl varchar(200) NOT NULL,
   setup_gateway char(1) NOT NULL,
   setup_gw_userid varchar(50) NOT NULL,
   setup_gw_password varchar(50) NOT NULL,
   setup_gw_1 varchar(64) NOT NULL,
   setup_gw_2 varchar(64) NOT NULL,
   setup_gw_3 varchar(64) NOT NULL,
   setup_gw_4 varchar(64) NOT NULL,
   setup_header text NOT NULL,
   setup_footer text NOT NULL,
   setup_faq text NOT NULL,
   setup_company_info text NOT NULL,
   setup_contact_info text NOT NULL,
   setup_acceptable_use text NOT NULL,
   setup_privacy_policy text NOT NULL,
   setup_topmenu_bg varchar(7) NOT NULL,
   setup_topmenu_font varchar(7) NOT NULL,
   setup_leftmenu_bg varchar(7) NOT NULL,
   setup_leftmenu_font varchar(7) NOT NULL,
   setup_leftmenu_width char(3) NOT NULL,
   setup_leftmenu_cart char(1) NOT NULL,
   setup_leftmenu_search char(1) NOT NULL,
   setup_registrar varchar(32) NOT NULL,
   setup_email_1 char(1) NOT NULL,
   setup_email_2 char(1) NOT NULL,
   setup_email_3 char(1) NOT NULL,
   setup_email_4 char(1) NOT NULL,
   setup_email_5 char(1) NOT NULL,
   setup_email_6 char(1) NOT NULL,
   setup_email_7 char(1) NOT NULL,
   setup_email_8 char(1) NOT NULL,
   setup_email_9 char(1) NOT NULL,
   setup_email_10 char(1) NOT NULL,
   setup_email_11 char(1) NOT NULL,
   setup_email_12 char(1) NOT NULL,
   setup_email_13 char(1) NOT NULL,
   setup_email_14 char(1) NOT NULL,
   setup_email_15 char(1) NOT NULL,
   setup_email_16 char(1) NOT NULL,
   setup_email_17 char(1) NOT NULL,
   setup_email_18 char(1) NOT NULL,
   setup_email_19 char(1) NOT NULL,
   setup_email_20 char(1) NOT NULL,
   setup_email_21 char(1) NOT NULL,
   setup_email_22 char(1) NOT NULL,
   setup_email_23 char(1) NOT NULL,
   KEY setup_id (setup_id)
);"; $db->query($q);

$db = new ps_DB; $q = "INSERT INTO setup VALUES ( '1', 'admin', 'admin', 'admin', '', 'http://localhost/billing/', '', 'Y', 'Y', '$', 'Y', '0.05', '40', 'Y', '', 'Y', '0', '0', '.10', '0', '.10', '31', '', '', '0', '', '', '', '', '', '', '', '', '<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b><font face=\"Arial, Helvetica, sans-serif\" color=\"#000099\">DreamHost 
  FAQ Section ></font></b></font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: Do I need 
  an account to order domains & hosting?</b><br>
  A: Yes, and you will be able to log in at a later time and view your orders, 
  billing history, domains & hosting plans, and update your billing and credit 
  card information. Also, you will be able to edit your hosting options and renew 
  your domains if they are about to expire.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  register for a new account?</b><br>
  A: Click on \"My Account\". You will be given the option to register 
  for a new account?</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  find if my domain is available?</b><br>
  A: You will need to enter the name of the domain in question in the \'Domain 
  Search\' box on the left. </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: I already 
  have a domain registered, I just need hosting. What must I do?</b><br>
  A: You will need to click the \'Domain Transfer\' button in the menu on the left. 
  You can then enter your domain name and sign up for hosting.<br>
  <br>
  <b>Q: I am interested in signing up as and affiliate. How can I find more information 
  about your affiliate options?</b><br>
  A: Click the \'Affiliates\' button in the left menu. You can find more information 
  there.<br>
  <br>
  <b>Q: I have a question that is not answered on this page, what do I do?</b><br>
  A: If you are already registered with us, log in and send us a message from 
  the \'Customer Support\' section for a faster responce. Alternatively, you may 
  contact us using the information on the \'Contact\' page.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: Where can 
  I find more information about your company?</b><br>
  A: Check out the \"Company Information\' page.<br>
  <br>
  <b>Q: Where can I view a list of your hosting plans so I can compare them?</b><br>
  A: Click on the \'Hosting Details\' in the menu on the left.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  change my current hosting plan to another plan?</b><br>
  A: Log into your account, go to \'My Account\', and select the domain from the 
  domain list on your account page. Then, click view and you will be able to see 
  the current hosting plan and update it to another if you wish.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How long 
  will it take for my hosting plan to be upgraded.</b><br>
  A: This depends on the time of day you submit your request, but it generally 
  takes place within 24-48 hours.<br>
  <br>
  <b>Q: How can I see when my domains are set to be billed?</b><br>
  A: Log into your account, go to \'My Account\', and select the domain from the 
  domain list on your account page. Then, click view and you will be able to see 
  the billing status.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  view my order history?</b><br>
  A: Log into your account, go to \'My Account\', and select the order you wish 
  to view from the orders list on your account page. Then, click view and you 
  will be able to see the order details.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  view my billing history?</b><br>
  A: Log into your account, go to \'My Account\', and select the billing record 
  you wish to view from the billing history list on your account page. Then, click 
  view and you will be able to see the billing details.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  check if I have domains expiring soon?</b><br>
  A: Log into your account, go to \'My Account\', and look at the \'Domains Expiring 
  Soon\' section. If there are any domains expiring in the next 30 days, they will 
  be displayed in the menu, and you will be able to select them to view more details. 
  </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  renew domains?</b><br>
  A: Log into your account, go to \'My Account\', and look at the \'Domains Expiring 
  Soon\' section. If there are any domains expiring in the next 30 days, they will 
  be displayed in the menu, and you will be able to select them to view more details 
  and renew them. </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  update my account information?</b><br>
  A: Log into your account, go to \'My Account\'. Your account information will 
  be displayed at the top of the page, and you will be able to edit it at that 
  point. </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: How do I 
  renew domains?</b><br>
  A: Log into your account, go to \'My Account\', and look at the \'Your Credit Cards 
  On File\' section. You will be able to select a card from the list to view/edit 
  its details, or replace it with another card.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Q: What if somebody 
  else gains access to my account, will they be able to view my credit card numbers?</b><br>
  A: No. Only the last 4 digits and the expiration date of your credit card number 
  will be displayed online.<br>
  <br>
  <b>Q: What if somebody hacks into your server, will they be able to access my 
  credit card information?</b><br>
  A: No. All the credit card information is encrypted, the hacker would need the 
  de-encryption code.<br>
  </font> <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><br>
  </font></p>
<p> </p>
', '<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b><font face=\"Arial, Helvetica, sans-serif\" color=\"#000099\">Company 
  Information </font><font face=\"Arial, Helvetica, sans-serif\" color=\"#000099\"> 
  Section ></font></b></font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b><br>
  <br>
  </b>This is just a sample static page.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">You can change 
  it to say whatever you like through the administration area once you have purchased 
  DreamHost!<br>
  </font></p>', '<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b><font face=\"Arial, Helvetica, sans-serif\" color=\"#000099\">Company 
  Contact Information ></font></b></font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b><br>
  <br>
  </b>This is just a sample static page.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">You can change 
  it to say whatever you like through the administration area once you have purchased 
  DreamHost!<br>
  </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b>Dreamcost.com</b><br>
  <br>
  Hours: 9-5 EST Mon-Fri<br>
  </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">Orders: 864.898.2737<br>
  Tech: 864.898.2737<br>
  Fax: 530.689.7970<br>
  <br>
  Dreamcost.com<br>
  1027 S Pendleton ST<br>
  Suite B 162<br>
  Easley, SC 2962<br>
  <br>
  </font></p>', '<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b><font face=\"Arial, Helvetica, sans-serif\" color=\"#000099\">Acceptable 
  Use Policy ></font></b></font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b><br>
  <br>
  </b>This is just a sample static page.</font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">You can change 
  it to say whatever you like through the administration area once you have purchased 
  DreamHost!<br>
  </font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><br>
  <br>
  </font></p>', '<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"3\"><b><font face=\"Arial, Helvetica, sans-serif\" color=\"#000099\">Our Privacy Policy ></font></b></font></p>
<p><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\"><b><br>
  <br>
  </b><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\"><b><font face=\"Arial, Helvetica, sans-serif\">Registration</font></b><font face=\"Arial, Helvetica, sans-serif\"><br>
  In order to use portions of this website, a user must first complete the registration 
  form.<span style=\"mso-spacerun: yes\">  </span>During registration a user 
  is required to give their contact information (such as name and email address).<span style=\"mso-spacerun: yes\">  
  </span>This information is used to contact the user about the services on our 
  site for which they have expressed interest.<span style=\"mso-spacerun: yes\"> </span><b><br>
  Order</b><br>
  We request information from the user on our order form.<span style=\"mso-spacerun:
yes\">  </span>Here a user must provide contact information (like name and 
  shipping address) and financial information (like credit card number, expiration 
  date).<span style=\"mso-spacerun: yes\">  </span>This information is used 
  for billing purposes and to fill customer&#8217;s orders.<span style=\"mso-spacerun: yes\">  
  </span>If we have trouble processing an order, this contact information is used 
  to get in touch with the user.<o:p> </o:p> <br>
  <b>Cookies</b><br>
  Our site does not use cookies to track you.<o:p> </o:p> <br>
  <b>Log Files</b><br>
  We use IP addresses to analyze trends, administer the site, track user&#8217;s movement, 
  and gather broad demographic information for aggregate use.<span style=\"mso-spacerun: yes\">  
  </span>IP addresses are not linked to personally identifiable information.<o:p> 
  </o:p> <br>
  <b>Sharing<br>
  </b>We will share aggregated demographic information with our partners and advertisers. 
  This is not linked to any personal information that can identify any individual 
  person.<o:p> </o:p> We use an outside shipping company to ship orders, and a 
  credit card processing company to bill users for goods and services. These companies 
  do not retain, share, store or use personally identifiable information for any 
  secondary purposes.<o:p> </o:p> We partner with another party to provide specific 
  services. When the user signs up for these services, we will share names, or 
  other contact information that is necessary for the third party to provide these 
  services.<o:p> </o:p> These parties are not allowed to use personally identifiable 
  information except for the purpose of providing these services.<o:p> </o:p> 
  <br>
  <b>Links<br>
  </b>This web site contains links to other sites. Please be aware that we Dreamcost.com 
  are not responsible for the privacy practices of such other sites.<span style=\"mso-spacerun: yes\">  
  </span>We encourage our users to be aware when they leave our site and to read 
  the privacy statements of each and every web site that collects personally identifiable 
  information.<span style=\"mso-spacerun:
yes\">  </span>This privacy statement applies solely to information collected 
  by this Web site.<o:p> </o:p> <br>
  <b>Newsletter<br>
  </b>If a user wishes to subscribe to our newsletter, we ask for contact information 
  such as name and email address. <o:p> </o:p> </font></span><font size=\"1\" face=\"Arial, Helvetica, sans-serif\"><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt;layout-grid-mode:
line\"><br>
  <b>Supplementation of Information<br>
  </b></span><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt;
layout-grid-mode:line\">In order for this website to properly fulfill its obligation 
  to our customers, it is necessary for us to supplement the information we receive 
  with information from 3rd party sources.<o:p> </o:p> For example, if you place 
  an order using your credit card, we may need to submit your billing information 
  to a 3rd party for billing or verification.</span><b style=\"mso-bidi-font-weight:normal\"><span style=\"font-size: 11.0pt; mso-bidi-font-size: 10.0pt\"><br>
  Special Offers<br>
  </span></b><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\">We send 
  all new members a welcoming email to verify password and username. Established 
  members will occasionally receive information on products, services, special 
  deals, and a newsletter. Out of respect for the privacy of our users we present 
  the option to not receive these types of communications. Please see our choice 
  and opt-out below.<o:p> </o:p> </span><b style=\"mso-bidi-font-weight:normal\"><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\"> <br>
  </span><span style=\"font-size: 11.0pt; mso-bidi-font-size: 10.0pt\">Site and 
  Service Updates<br>
  </span></b><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\">We also 
  send the user site and service announcement updates. Members are not able to 
  un-subscribe from service announcements, which contain important information 
  about the service. We communicate with the user to provide requested services 
  and in regards to issues relating to their account via email or phone.<o:p> 
  </o:p> <br>
  </span><b style=\"mso-bidi-font-weight:normal\"><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\">Correction/Updating 
  Personal Information:<br>
  </span></b><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\">If a user&#8217;s 
  personally identifiable information changes (such as your zip code), or if a 
  user no longer desires our service, we will endeavor to provide a way to correct, 
  update or remove that user&#8217;s personal data provided to us.<span style=\"mso-spacerun: yes\">  
  </span>This can usually be done at the member information page<b style=\"mso-bidi-font-weight:normal\"> 
  </b>or by emailing our Customer Support<b style=\"mso-bidi-font-weight:normal\">.</b></span><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt;font-weight:normal\"><br>
  </span><span style=\"font-size:11.0pt;mso-bidi-font-size:10.0pt\"><b>Choice/Opt-out<o:p> 
  </o:p> <br>
  </b>Our users are given the opportunity to &#8216;opt-out&#8217; of having their information 
  used for purposes not directly related to our site at the point where we ask 
  for the information.<span style=\"mso-spacerun: yes\">  </span>For example, 
  our order form has an &#8216;opt-out&#8217; mechanism so users who buy a product from us, 
  but don&#8217;t want any marketing material, can keep their email address off of our 
  lists.<span style=\"mso-spacerun: yes\">  </span><o:p> </o:p> Users who no 
  longer wish to receive our newsletter or promotional materials from our partners 
  may opt-out of receiving these communications by replying to unsubscribe in 
  the subject line in the email or email us at <a href=\"mailto:design@dreamcost.com\">design@dreamcost.com</a><span style=\"mso-spacerun: yes\">  
  </span>[Some sites are able to offer opt-out mechanisms on member information 
  pages and also supply a telephone or postal option as a way to opt-out.]<o:p> 
  </o:p> Users of our site are always notified when their information is being 
  collected by any outside parties.<span style=\"mso-spacerun: yes\">  </span>We 
  do this so our users can make an informed choice as to whether they should proceed 
  with services that require an outside party, or not.<o:p> </o:p> <br>
  <b>Notification of Changes<br>
  </b>If we decide to change our privacy policy, we will post those changes on 
  our Homepage so our users are always aware of what information we collect, how 
  we use it, and under circumstances, if any, we disclose it.<span style=\"mso-spacerun: yes\">  
  </span>If at any point we decide to use personally identifiable information 
  in a manner different from that stated at the time it was collected, we will 
  notify users by way of an email.<span style=\"mso-spacerun: yes\">  </span>Users 
  will have a choice as to whether or not we use their information in this different 
  manner. We will use information in accordance with the privacy policy under 
  which the information was collected. <o:p> </o:p>  <o:p> </o:p></span></font></font><font face=\"Arial, Helvetica, sans-serif\" size=\"1\"><br>
  <br>
  </font></p>
', '#999999', '#FFFFFF', '#000000', '#CFCFCF', '200', 'Y', 'Y', '', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y');"; $db->query($q);

?>
<p><font face="Verdana, Arial, Helvetica, sans-serif"><b>DreamHost Database Tables 
  Created.</b></font></p>
<p><font face="Verdana, Arial, Helvetica, sans-serif">Please close this window 
  and proceed to Installation Step 3.</font></p>
</body>
</html>