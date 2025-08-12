OrderSys 1.5 / October 23, 2005
PHP-MySQL-based ordering system
By Santosh Patnaik, MD, PhD
Free for modification and distribution
http://stanxterm.aecom.yu.edu/secondary/ordering/index.php

--------------------------------------------------------------------------------

1. About
2. Requirements
3. Installation - clean, and upgrades
4. Customization
5. Components
6. Maintenance
7. Release notes
8. Donate
9. Bugs and issues
10. Importing data from other database systems

Also see help/help.htm

--------------------------------------------------------------------------------
About

OrderSys is a 'shopping cart' system developed in a biomedical research laboratory for day to day use by members of the laboratory. The laboratory places order through a central office for items from many different vendors. The details on the items and the vendors are stored in OrderSys, and that allows the users to quickly select items they wish to order. An order form is then generated for passing on to the office. Past orders are stored too in the system and one can follow up on placed orders. OrderSys is highly customizable and can easily be used in other settings. IP-based access-restriction options are provided.

You may also be interested in the LabStoRe software for managing laboratory stocks and records - http://stanxterm.aecom.yu.edu/secondary/stocks/index.php.

--------------------------------------------------------------------------------
Requirements

1. A web server such as the free Apache server software
2. The free MySQL database system on the server computer - version 4 or above is recommended
3. The free PHP webserver module or engine - version 4.3 or above is recommended. Magic_quotes_gpc settings do not matter.

A plain text editor (not Word) or a good HTML/code editor such as BBEdit, JEdit (free - http://www.jedit.org/), etc., is needed to edit the PHP code for configuration or modification.

--------------------------------------------------------------------------------
Installation - clean, and upgrades

CLEAN

1. Move 'ordering' folder to the right place in your web directory. You may rename it if you wish. E.g., if placed at the root level as 'orders,' the front page of the system can be accessed at http://your.website.com/orders/index.php.

2. Next, set up an MySQL database. You may call it 'laborder'.

Use the provided laborder.sql file to populate the database.

Please look elsewhere for help with general MySQL database and user setup. The free, web-based application, phpMyAdmin (http://www.phpmyadmin.net), is a very useful utility for MySQL database administration. There are 'regular' applications too, such as Navicat (www.navicat.com - not free) and MySQL Query Browser (free; http://dev.mysql.com/downloads/query-browser/).

3. Open ordering/config.php and change the settings as per suggestions in those files. Preferably, use an HTML code editor such as BBEdit, JEdit, FrontPage (in code view mode), etc., but lacking one, a good simple text editor to do so.

4. Browse to yoursite.com/path/to/ordering/index.php. Click on the 'Admin' link at bottom. If prompted for login, use 'root' and 'letizia' (password). They are for an administrator account. You should change the values.

Now you can start adding items, vendors, etc., and placing orders!

5. For configurating the form fields, go to the Interface Creator section at ordering/interface_creator/admin.php. It has a help webpage and the folder has a readme file.

6. The ordering folder should have execute permission, and ordering/interface_creator/uploads should have write permission as well. If the website fails, check this aspect.

7. With settings in config.php, access to most of the site can be restricted by IP addresses.

Further restrictions, and access restriction to interface creator (as suggested above), can be set up through .htaccess files. See this site for more - http://stanxterm.aecom.yu.edu/wiki/index.php?page=Web_serving_-_access_control. 

A sample .htaccess file is inside 'ordering' as 'htaccess.' To use, please change the parameters and rename it as .htaccess. (Of course, the web server should have been configured to allow the use of .htaccess files.)

This section describes other security options that are available:

User system and authentication
------------------------------

The user system depends on the 'users' table (or a different table if defined so in config.php) that stores user identities, user-specific IDs (ID_user), encrypted passwords, user type (normal or administrator), etc. User authentication enabled to a not-maximal extent merely requires visitors to log in before they can insert entries, etc. With user authentication enabled to maximum extent, every new record that is created is assigned the ID_user value, and a logged-in user's ID_user value is matched against it to allow modification, deletion, etc.

Setting $enable_admin_authentication to 1 will restrict access to the admin page, and to the interface_creator admin page, to administrators only as defined in the MySQL 'users' table.

Setting $enable_authentication to 1 will require user login (both administrator and normal users) for inserting, modifying or deleting table entries. Note that this can also be accomplished through .htaccess files.

Because members of a research group are usually in an open-to-each-other community, further restrictions may not be necessary and can cause inconvenience and extra work. It can also defeat a major purpose of maintaining an inventory system (stocks wasting over time, stocks of members who leave the lab may be 'lost,' etc.)

With other authentication settings set to 0, a user can edit or delete other users' entries. With them set to 1, however, restrictions may be placed. For example, with $enable_browse_authorization set to 1, only the user who created the entry can view its details. He (even if an administrator) cannot view the details of entries created by other users or of entries that have empty ID_user values. It is easy to revert from a maximal authentication system to none but the other way may make some entries 'invisible.'

Through admin.php, it is possible to easily reassign ID_user values so a user may inherit another user's entries.

Currently, the only way to share entries is to share the same user name and password.

UPGRADING FROM VERSION 1.4

1. Backup etc. your current database and ordering directory.

2. Set parameters in config.php. Note that there are some new parameters.

3. Use phpmyadmin, Navicat, etc., to alter your MySQL database:

Delete users_tab table if it exists.

Load upgrade1415.sql to add new tables 'users' and 'dadabik_users' (required for authentication system) and modify dadabik_table_list (so the 'users' table is recognized as 'installed').

The two steps below are required if you want to enable 'full' authentication ( a user may affect only his/her records)

Modify tables 'item', 'vendor' and 'order' to add a field 'ID_user' (INT(11), NOT NULL).

Then, go to the interface_cxreator/admin.php page to 'add' the newly added 'ID_user' fields for each of the 3 tables, and configure the form for that field such that it is of type 'ID_user' and is not shown in insert/update forms.

UPGRADES FROM VERSION 1.3 OR VERSIONS OLDER THAN 1.3

1. Backup your old database. The free, web-based application, phpMyAdmin (http://www.phpmyadmin.net), is a very useful utility for MySQL database administration, backup, etc.

2. Make a copy of the old ordering folder.

3. Replace the entire old folder with the new ordering folder.

4. Then, use a MySQL editor (mysql command-line tool, application like Navicat or MacSQL, or a web-application like phpmyadmin [free]), to create a new table. The new table named order stores order history. The MySQL statement is provided below:

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `order` (
  `order_id` mediumint(10) NOT NULL auto_increment,
  `description` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL default '0.00',
  `ordered_by` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `cost_reduce` decimal(10,2) NOT NULL default '0.00',
  `cost_add` decimal(10,2) NOT NULL default '0.00',
  `status` varchar(255) NOT NULL default '',
  `modified_date` date NOT NULL default '0000-00-00',
  `ordered_date` date NOT NULL default '0000-00-00',
  `reception_status` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


SET FOREIGN_KEY_CHECKS = 1;

5. Now, edit the table named item. Choose field 'Price' and set its type to real or decimals or float. [Perhaps optional. I recommend you do this. But leave it if it is difficult. Then choose order_date and set its type to date with default as 0000-00-00. Then convert all row values (i.e., for all items, one by one, or using a PHP script) for this field to the 0000-00-00 (yyyy-mm-dd) date format.]

6. Now change all dadabik_ tables and the dadabik_table_list table, and create a new table - dadabik_order. Use the laborder1314.sql file. It basically upgrades the dadabik_ prefixed tables, and adds two tables (one dadabik_ prefixed) for order histories, leaving your item/vendor data intact. It also adds a 'users' table needed for authentication (see interface_creator/help.htm).

7. Then follow guidelines for 'UPGRADING FROM VERSION 1.4' above.

--------------------------------------------------------------------------------
Customization

See readme.txt and help.htm inside interface_creator for more

STYLE CHANGES

The CSS style-sheets that are used are in ordering/style.css and you can edit them to suit your needs.

HEADER AND FOOTER CUSTOMIZATION

By changing parameters in ordering/config.php, much customization can be achieved. If you wish you can still edit ordering/header.php, ordering/footer.php, ordering/top_part.php, ordering/bottom_part.php

CUSTOMIZING THE TABLES AND FORMS

You can add new fields for the MySQL tables (and thus the forms used to affect the data in the tables) as well as delete the default ones. However, some of the fields are required for the codes to work and if you remove them or substitute them you will have to modify the code.

Everytime a field is added or deleted or modified, you must go to the interface_creator/admin.php page to 'input' the changes into the system. Also, if you add a new field, you may want to configure the form interface by going to that page.

PRINTED ORDER

Edit print.php

--------------------------------------------------------------------------------
Components

1. The MySQL database and its tables - see ordering/help folder for more on the MySQL structure.
2. The interface_creator (ordering/interface_creator/admin.php) can be used to modify the insert/edit forms that users see. See readme.txt and help.htm inside that folder
3. MySQL connection settings and various other, editable parameters are in ordering/config.php.
4. Main page is index.php.
5. Help and similar information is in ordering/help.
6. Export support is provided by export.php.
7. Past orders are displayed using orders.php.
8. Items are displayed using items.php.
9. Printed form is generated using print.php.

--------------------------------------------------------------------------------
Maintenance

Over some years as newer PHP and MySQL versions are released and installed on your system, some of the code may fail. But, with little PHP and MySQL expertise, one may readily fix any problem.

--------------------------------------------------------------------------------
Release notes

Version 1.5
October 15, 2005
Added user-account system
Fixed minor interface_creator issues

Version 1.4
September 27, 2005
Fixed minor interface_creator issues

Version 1.3 
August 28, 2005
XHTML strict 1.0 compatible
Improved interface creator
More customizability
Order history now stored
Many small improvements

--------------------------------------------------------------------------------
Donate

If you would like to donate to appreciate and support this software, please use PayPal (www.paypal.com - works in over 50 countries) to send a payment to drpatnaik AT yahoo DOT com

--------------------------------------------------------------------------------
Bugs and issues

Most of the issues are likely to be platform (OS) / browser / MySQL / PHP / webserver-related, and not because of problems with the OrderSys scripts.

IE on Mac will not lay out the pages properly.

Old versions of IE, on PC, may also not lay out the pages properly.

Improper settings in config.php (check for missing slashes (/), commas (,), quotes ('), etc.) and improper or incompatible configuration of form fields (through ordering/interface_creator) may also give rise to issues.

For debugging, enable the right options in config.php. The error messages may help you troubleshoot the problem. For example, it could be because of your PHP settings and not because of the application logic.

The interface creator that is an integral part of OrderSys is derived from DaDaBik (www.dadabik.org), and the forums on that website may help you troubleshoot.

I apologize for any code problems in advance.

I may be releasing newer, better versions - at http://stanxterm.aecom.yu.edu/secondary/ordering/index.php. I also may release this software at a Sourceforge-like site where it can be collectively maintained.

--------------------------------------------------------------------------------
Importing data from other database systems

You may wish to import data from a different (than MySQL, or a MySQL system with a different table structure) storage system. To do so,

1. Note the structure of tables in OrderSys and in the other system
2. Modify OrderSys (MySQL tables, forms, etc.) - see CUSTOMIZING THE TABLES AND FORMS above, and/or your current storage system (getting rid of unnecessary fields, merging fields, etc.).
3. After you have matched the two structures, export data from your current system into a format that can be imported into MySQL. The format to choose depends on what software you have. E.g., Microsoft Access databases can be exported to an Excel file that can be imported into MySQL using the Navicat application in Windows.