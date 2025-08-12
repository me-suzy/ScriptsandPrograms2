LabStoRe 1.4 / 2 November 2005
PHP-MySQL-based stock and record inventory system for biomedical research laboratories
By Santosh Patnaik, MD, PhD
Free for modification and distribution
http://stanxterm.aecom.yu.edu/secondary/stocks/index.php

--------------------------------------------------------------------------------

1. About
2. Requirements
3. Installation - clean, and upgrades
4. Customization and modules
5. User system and authentication
6. Maintenance
7. Release notes
8. Donate
9. Bugs and issues
10. Importing data from other database systems

Also see help/help.htm

--------------------------------------------------------------------------------
About

LabStoRe or Laboratory Stocks and Records is a modular, web-based software to assist biomedical research laboratories in building and maintaining a database of stocks and records. See help/help.htm for its rationale.

It is based on the MySQL database system. The database holds tables for items such as proteins (antibodies, enzymes, etc.), plasmids, chemical reagents, etc. A web-based interface is used to access (populate and modify) the database.

LabStoRe has two components. The modules (proteins.php, cell-lines.php, etc., inside stocks/modules) are the pages most users will see. They are used to browse, search, delete, insert, edit, etc., the data, that uses forms. How these forms appear to the users is configured by the second component, the interface creator (stocks/interface creator). The interface creator also has ability to let the user browse, search, delete, insert, edit, etc., the data. But its main purpose is to configure the forms. Access to this sub-site can be restricted using .htaccess files lest users change the configurations for worse.

The files in the LabStoRe folder dictate the application logic and interface, but the real data, including information on how the form fields appear, are stored in the MySQL database. For each module, there is a table (e.g., 'proteins' for the Protein module) that stores the actual data, and another table (with a 'dadabik_' prefix; e.g., 'dadabik_proteins') that holds information, such as which options to present, about the forms that are used to insert or update an entry for that module. A corresponding .php file, e.g., 'proteins.php,' is used to generate the web pages for that module.

--------------------------------------------------------------------------------
Installing LabStoRe requires

1. A web server such as the free Apache server software
2. The free MySQL database system on the server computer - version 4 or above is recommended
3. The free PHP webserver module or engine - version 4.3 or above is recommended. Magic_quotes_gpc settings do not matter.

A plain text editor (not Word) or a good HTML/code editor such as BBEdit, Text Wrangler (free), JEdit (free - http://www.jedit.org/), etc., is needed to edit the PHP code for configuration or modification.

--------------------------------------------------------------------------------
Clean installation

1. To install, move the folder named 'stocks' to an appropriate place inside the web server root folder.

If you wish, you can rename this folder. The address to access the inventory system will depend on the name and the placing of this folder. E.g., if placed at the root level as 'stocks,' the front page of the system can be accessed at http://your.website.com/stocks/index.php.

2. Next, set up an MySQL database. You may call it 'labstock'.

Use the labstock.sql file to populate the database.

Please look elsewhere for help with general MySQL database and user setup. The free, web-based application, phpMyAdmin (http://www.phpmyadmin.net), is a very useful utility for MySQL database administration. There are 'regular' applications too, such as Navicat (www.navicat.com - not free) and MySQL Query Browser (free; http://dev.mysql.com/downloads/query-browser/).

3. Open stocks/config.php and change the settings as per suggestions in those files. Preferably, use an HTML code editor such as BBEdit, JEdit, FrontPage (in code view mode), etc., but lacking one, a good simple text editor to do so.

4. The stocks folder should have execute permission, and stocks/interface_creator/uploads should have write permission as well. If the website fails, check this aspect.

5. With settings as they are, access to most of the site is restricted by IP addresses (you can remove this restriction as well as specify the IP addresses by changing parameters in stocks/config.php).

Further restrictions, and access restriction to interface creator (as suggested above), can be set up through .htaccess files. See this site for more - http://stanxterm.aecom.yu.edu/wiki/index.php?page=Web_serving_-_access_control. A sample .htaccess file is inside stocks/ as 'htaccess.' To use, please change the parameters and rename it as .htaccess. (Of course, the web server should have been configured to allow the use of .htaccess files.).

6. To further restrict the system, for example to enable user based system, alter the settings in config.php. See 'User system and authentication' below. Also, to know more, see interface_creator/help.htm and readme.txt.

7. Use an internet browser to browse to the administration page at http://your.website.com/stocks/admin.php to change the default administrator password, to add users, etc.

The default administrator username is root and password is letizia. You should change them.

8. Then browse to the front page (http://your.website.com/stocks/index.php) to start adding entries under the various categories.

9. You will most likely need to change some options in the 'insert (add) / update (edit)' forms. E.g., the lab may have four different -80 degree refrigerators, and you may want to provide each of them as an option for the 'fridge' field of a form (that specifies where an item is stored). The interface creator makes this possible. These changes can be done through links provided on admin.php page or through interface_creator/admin.php.

Go to http://yourwebsite.com/stocks/interface_creator/admin.php and choose the table you want to configure. Then, click on the link to the configuration page. There, each form field can be configured. In fact, each of the dadabik_ prefixed table (every data table has a specific dadabik_ table) stores precisely such configuration data. For screenshots, see http://stanxterm.aecom.yu.edu/secondary/stocks/help/screenshot.htm.

10. For debugging purposes, for both MySQL and PHP, turn on (or off) the appropriate parameters inside stocks/config.php.

--------------------------------------------------------------------------------
Upgrading

The way you upgrade from previous versions depends on how much data you already have in the tables and how much customization has been done. Before upgrading, you should make a copy of the existing LabStoRe folder as well as dump the existing labstore MySQL database. (Please check on the internet on how to do so if you are not familiar.).

The new website php files can replace the old ones unless they have been altered. Copy settings from the old config.php to the new config.php file, and put in settings for the new parameters.

The files in the LabStoRe folder dictate the application logic and interface, but the real data, including information on how the form fields appear, are stored in the MySQL database. For each module, there is a table (e.g., 'proteins' for the Protein module) that stores the actual data, and another table (with a 'dadabik_' prefix; e.g., 'dadabik_proteins') that holds information, such as which options to present, about the forms that are used to insert or update an entry for that module. A corresponding .php file, e.g., 'proteins.php,' is used to generate the web pages for that module.

Upgrading from version 1.3
--------------------------

Following are needed to implement the user-based LabStoRe (new feature in 1.4; see note below) -

1. Using phpMyAdmin, Navicat, etc., add a field named 'ID_user' (INT 11, NOT NULL) to each module table.
*** optional, see below ***
2. Change 'id' field name to 'ID_user' for table 'users.'
3. Add field named 'md5_password' (VARCHAR 32, NOT NULL) to table 'users.'
4. Change parameters in config.php to reflect these changes.

The following is needed to add the new tables for the new modules added in version 1.4 ('parts' for equipments and accessories, and 'others' for miscellanea such as books, software, etc.). This will also delete users_tab and users tables installed previously. Also, a new users table will be created with just one user account: login - root / password - letizia; administrator. If you do not want the new users table, i.e., if you have already added many users to current users table, you may edit upgrade.sql to remove the lines concerning the users table, and then do steps 2-4 above.

1. Load data in upgrade.sql (with command-line MySQL or through phpMyAdmin, etc.).

You may then wish to browse to stocks/admin.php to assign passwords, etc.

Upgrading from versions older than 1.3
--------------------------------------

Do the following and then carry out the steps in 'Upgrading from version 1.3'

Edit all dadabik_ prefixed tables in the MySQL database. The idea is to add a new field to all those tables and to edit another field in all of them.

New field to add: linked_fields_extra_mysql. Of type varchar(255), not null, utf-8_general_ci

Field to edit: type_field. Put 'select_multiple_menu' and 'select_multiple_checkbox' in it. It should look like this:

ENUM ('text','textarea','rich_editor','password','insert_date','update_date','date','select_single','select_multiple_menu','select_multiple_checkbox','generic_file','image_file','ID_user','unique_ID')

--------------------------------------------------------------------------------
Customization

ADDING A NEW MODULE

The system comes with seven 'modules,' one each for proteins (enzymes, lectins, antibodies, etc.), chemicals (RNA, chemical reagents, etc.), plasmids, cell-lines and records (freezer box records, protocols, other documents, etc.), parts (for equipments and accessories) and others (for miscellanea). Each 'module' has a php page to display the database entries (stocks/proteins.php for proteins, and so on), and has a table (proteins for proteins) in the database. A second table (dadabik_proteins for proteins) enables customization of insert/edit forms.

Some of the modules can be disabled by changing parameters in config.php.

These steps describe how a new module may be added -

1. Create a new MySQL table - use an application like Navicat or the free web-based phpMyAdmin software (www.phpmyadmin.net). You may name it, for example, for seed strains, 'seeds.'

The table should have a column named 'ID_user' for authentication (if you enable it in config.php) to work. This column stores values from the ID_user column of the users table.

It also should have a column named 'name,' though it can store information for not just name but description too (for example). If you cannot, you will have to modify top_part.php (look for the code for the default sorting clause).

2. Then browse to yoursite.com/path/to/stocks/interface_creator/admin.php and install the table by clicking the right link (towards the bottom).

3. You can then configure the form for the table - use the link provided on the page (above). When configuring, for field 'ID_user,' make it not to be shown in the insert/update/search forms or results, and set it to field type 'ID_user' - authentication system for the table will fail otherwise. See the interface creator help section.

4. You will now have to create a .php file for the module. Use a code editor such as BBEdit or TextWrangler (free) or a plain text editor (such as TextEdit or NotePad, but not MS Word). You can use the code in, say, 'chemicals.php,' as a guide. When done, name it, say, for seed strains, 'seeds.php.'

5. Alter the parameters in config.php to have the module included.

MODIFYING EXISTING MODULES

You may modify existing modules. For example, you may want to add a new field (to the table, and thus to the web form). Adding or deleting MySQL table fields (or renaming them) has to be done through a separate application. Afterwards, you should go to stocks/interface_creator/admin.php to 'input' the changes into the system. If you created a new field, you will also want to configure the corresponding web form field.

It is recommended that the fields 'id', 'name', 'added_by', 'modified_by', 'added_on' and 'modified_on' not be affected. You can change them but you will then have to change many lines of the PHP code too.

You can also 'break' a module into two, by creating a new module (as described above) and moving some of the 'purpose' of the old module to the new one. E.g., the 'proteins' module can be broken into 'antibodies' and 'other proteins' modules.

STYLE CHANGES

The CSS style-sheets that are used are in stocks/style.css and you can edit them to suit your needs.

HEADER AND FOOTER CUSTOMIZATION

By changing parameters in stocks/config.php, much customization can be achieved. If you wish you can still edit stocks/header.php, stocks/footer.php, stocks/top_part.php, stocks/bottom_part.php, stocks/interface_creator/header.php, stocks/interface_creator/footer.php, 
stocks/interface_creator/header_admin.php, 
stocks/interface_creator/footer_admin.php 

--------------------------------------------------------------------------------
User system and authentication

Besides the IP-address-based restrictions that may be set through config.php, and any .htaccess file based restrictions you set up, LabStoRe also has an integrated authentication system that may be enabled to various degrees by affecting parameters in config.php. While the IP-system is a 'restrictive' one, the user system is a 'permissive' one, meaning that the former if set will apply on top of the latter - a user with the right username and password will be rejected if visiting from the wrong IP address.

The user system depends on the 'users' table (or a different table if defined so in config.php) that stores user identities, user-specific IDs (ID_user), encrypted passwords, user type (normal or administrator), etc. User authentication enabled to a not-maximal extent merely requires visitors to log in before they can insert entries, etc. With user authentication enabled to maximum extent, every new record that is created is assigned the ID_user value, and a logged-in user's ID_user value is matched against it to allow modification, deletion, etc.

Setting $enable_admin_authentication to 1 will restrict access to the admin page, and to the interface_creator admin page, to administrators only as defined in the MySQL 'users' table.

Setting $enable_authentication to 1 will require user login (both administrator and normal users) for inserting, modifying or deleting table entries. Note that this can also be accomplished through .htaccess files.

Because members of a research group are usually in an open-to-each-other community, further restrictions may not be necessary and can cause inconvenience and extra work. It can also defeat a major purpose of maintaining an inventory system (stocks wasting over time, stocks of members who leave the lab may be 'lost,' etc.)

With other authentication settings set to 0, a user can edit or delete other users' entries. With them set to 1, however, restrictions may be placed. For example, with $enable_browse_authorization set to 1, only the user who created the entry can view its details. He (even if an administrator) cannot view the details of entries created by other users or of entries that have empty ID_user values. It is easy to revert from a maximal authentication system to none but the other way may make some entries 'invisible.'

Through admin.php, it is possible to easily reassign ID_user values so a user may inherit another user's entries.

Currently, the only way to share entries is to share the same user name and password.

--------------------------------------------------------------------------------
The components of the system are -

1. The MySQL database and its tables - see stocks/help folder for more on the MySQL structure.
2. The interface_creator (stocks/interface_creator/admin.php) can be used to modify the insert/edit forms that users see.
3. MySQL connection settings and various other, editable parameters are in stocks/config.php.
4. Main page is index.php.
5. Help and similar information is in stocks/help.
6. Export support is provided by export.php.
7. Tables are displayed using modules/proteins.php, etc.
8. Administration is through admin.php

--------------------------------------------------------------------------------
Over some years as newer PHP and MySQL versions are released and installed on your system, some of the code may fail. But, with little PHP and MySQL expertise, one may readily fix any problem.

--------------------------------------------------------------------------------
Release notes

October 23, 2005 - Version 1.4 released
> Two new modules added - parts and others
> Ability to remove modules
> User system
> Enhanced interface creator subpart

August 20, 2005 - Version 1.3 released
> Extra form configuring capabilities
> More easily customizable
> Some important interface creator bugs fixed
> Restructured files

August 12, 2005 - Version 1.2 released
> Interface creator modified
> XHTML 1.0 Strict compatibility expanded to the interface creator folder

July 9, 2005 - Version 1.1 released
> Better sorting and searching
> XHTML 1.0 Strict compatibility

May 25, 2005 - Version 1.0.2 released
> Better sorting and searching

May 12, 2005 - Version 1.0.1 released
> Lab title and url now specified as variables (in header.php and in interface_creator/config.php and config_short.php)
> Sanitized Updated date and Added date display (not seen if value is 0000-00-00 or empty)

April 27, 2005 - Version 1.0 released

--------------------------------------------------------------------------------
Donate

If you would like to donate to appreciate and support this software (I have so far spent over 150 hours developing and testing LabStoRe), please use PayPal (www.paypal.com - works in over 50 countries) to send a payment to drpatnaik AT yahoo DOT com

--------------------------------------------------------------------------------
Bugs and issues

I apologize for any bugs in the code.

Most of the issues are likely to be platform (OS) / browser / MySQL / PHP / webserver-related, and not because of problems with the LabStoRe scripts.

IE on Mac will not lay out the pages properly. Old versions of IE, on PC, may also not lay out the pages properly.

Improper settings in config.php (check for missing slashes (/), commas (,), quotes ('), etc.) and improper or incompatible configuration of form fields (through stocks/interface_creator) may also give rise to issues.

For debugging, enable the right options in config.php. The error messages may help you troubleshoot the problem. For example, it could be because of your PHP settings and not because of the application logic.

The interface creator that is an integral part of LabStoRe is derived from DaDaBik (www.dadabik.org), and the forums on that website may help you troubleshoot.

I may be releasing newer, better versions - at http://stanxterm.aecom.yu.edu/secondary/stocks/index.php. I also may release this software at a Sourceforge-like site where it can be collectively maintained.

--------------------------------------------------------------------------------
Importing data from other database systems

You may wish to import data from a different (than MySQL, or a MySQL system with a different table structure) storage system. To do so,

1. Note the structure of tables in LabStoRe and in the other system
2. Modify OrderSys (MySQL tables, forms, etc.) - see Customization above, and/or your current storage system (getting rid of unnecessary fields, merging fields, etc.).
3. After you have matched the two structures, export data from your current system into a format that can be imported into MySQL. The format to choose depends on what software you have. E.g., Microsoft Access databases can be exported to an Excel file that can be imported into MySQL using the Navicat application in Windows.