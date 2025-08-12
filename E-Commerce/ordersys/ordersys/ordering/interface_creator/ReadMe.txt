Interface Creator
(10 Oct 2005; based on the excellent DaDaBik 3.2 by Eugenio Tacchini - http://www.dadabik.org; modifications by Santosh Patnaik)

Follow up for fixes and improvements at the DaDaBik Sourceforge project tracker -  http://sourceforge.net/tracker/index.php?func=detail&aid=1292462&group_id=39649&atid=425835 and DaDaBik forums (www.dadabik.org)

WHAT IS THIS

The (DaDaBik) Interface Creator allows one to configure the way HTML forms used for interactions with MySQL databases appear. It also allows one to browse and interact (search, edit, delete, export or insert) with the MySQL data itself. This version is largely based on DaDaBik, version 3.2.

WHAT ARE THOSE MODIFICATIONS

1. XHTML 1.0 strict compatibility (UTF-8 encoding)
2. magic quotes ON not required any more
3. Possibility to have fields of multiple-select or multiple-checkboxes type
4. Bugfixes - field of file type can be a required field; attached files are deleted when records are deleted; a few minor ones
5. Table-specific file upload subdirectories
6. Simplified file structure, smaller number of files, and less non-redundant code
7. Ability to pass extra SELECT clause when retrieving values from a foreign table for building select menu options
8. Can be used as a 'backend' by using popup links from your custom frontend for index_short.php (the regular file is index.php). Index_short.php does not have headers or footers.
9. Minor improvements to style, redirections after login.
10. Option to view details, edit, etc., (not insert) for a record in pop-up windows.
11. Protection of admin pages irrespective of other authentication.
12. Auto-login
13. User (ID_user) re-assignment

BUG REPORTS

Please post bug reports and fixes on www.dadabik.org or under the right posting on http://sourceforge.net/tracker/?group_id=39649&atid=425835

REQUIREMENTS

Webserver with PHP 4.3 or higher (tested with 5.04) and MySQL 3.23 or higher (tested with 4.13 and 5.0)

SUPPORT

Almost all support knowledge available on www.dadabik.org should apply.

FRESH INSTALLATION

1. Please check elsewhere on how to set up a MySQL account and to use it to design and generate your database tables. ONLY after the tables are set up, even if they do not contain any data, can the DaDaBik interface creator system be set up. Please check elsewhere on how to do so. You may want to use the free web application - phpmyadmin - www.phpmyadmin.net.

2. Open config.php and insert the right values as described there. Language-specific settings from the include/languages/english.php (in unmodified DaDaBik), etc., files are in this file now.

3. Move the interface_creator folder to the right place in your web directory. If you want, you can rename the folder. The web address of the interface creator depends on the name you give to the folder, as wel as on where it is situated in the web directory. If named as 'interface_creator' in, say, a folder named 'stocks' inside the root directory, the address will be

http://your.domain.com/stocks/interface_creator

4. Browse to the administration section at http://your.domain.com/stocks/interface_creator/admin.php to add tables whose data you wish to work with using the HTML forms. As mentioned earlier, these tables need to already be there in the MySQL database. Once you add the tables to the interface creator, the interface creator will generate new MySQL tables (one for each added table) named with the 'dadabik_' prefix (e.g., 'dadabik_cells' for the table 'cells'; a different prefix value can be set in config.php.). Once the tables have been added ('installed'), you can start configuring the forms using the links provided.

5. Read help.htm for help and securing the Interface Creator.

UPGRADING

1. Note settings in include/config.php inside current dadabik/ folder in your web directory. Make a copy of the 'uploads' directory inside dadabik/ if you have one. Also back up the entire dadabik/ folder in case you want to revert.

2. Replace the old dadabik folder with this one, replacing the 'uploads' folder with the copy. Create subdirectories named after the MySQL tables inside uploads/ and move your previous uploaded files to the right subdirectory (depending on the table they are for). I hope you do not have too many files. (In case you have any problems later on in uploading files, make sure that the folders are writable  by chmod-ing them.).

This is not important if you have never used the system for uploading files.

3. Set up parameters in the new config.php file as per your old settings. A few new parameters are used by this modified version.

4. Edit your MySQL dadabik_-prefixed tables (those installed by DaDaBik earlier). Please check elsewhere on how to do so. You may want to use the free web application - phpmyadmin - www.phpmyadmin.net.

For EACH of those tables, edit the field 'type_field' so that it has 'select_multiple','select_multiple_checkbox'. It should end up looking like

ENUM ('text','textarea','rich_editor','password','insert_date','update_date','date','select_single','select_multiple','select_multiple_checkbox','generic_file','image_file','ID_user','unique_ID')

Then, add a new field - VARCHAR, 255, NOT NULL - named

linked_fields_extra_mysql

after the field 'linked_fields_order_type_field'




