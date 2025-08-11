Chipmunk CMS version 1.3

To use this Script, you must have:
PHP 4.3.x
mysql 3.x or higher


Liscense: You may user this CMS and modify it however you want. However, under no circumstances are you allowed to take off any copyright link to chipmunk scripts.

1. Modify the 4 connect.php files. They are in the root directory, the board directory and the board/admin directory, and the catalog directory. Putyour database hostname where localhost is(most of the time just leave it as localhost), your database username , password, and database name where specified.

2. Run the install.php file in IE or another browser
3. Delete the install.php file
4. Run the register.php file in board/admin/ folder to register yourself an admin account and then delete the register.php and reguser.php in the board/admin/ folder.
5.Edit the $boardpath variable in admin/var.php to the path of your board
6. Edit the email variables in the admin/var.php to reflect your admin email. There are other variables in there that you may edit to suit your tastes
7. Your CMS should be set.

There are 3 style.css files in the script, once control the forums, and the other controls the site, and one to control the catalog section.

6. You should set on cron on /board/cron/cron.php to run every 24 hours to delete old RPS logs
7. You should set /board/cron/cron2.php to run every hour to delete old guest logs(this will not interefere with the count);

*If you add page categories but don't add any pages, there will a mysql warning, as soon as you add a page under a category, it will dissapear
* Pages won't appear unless they are part of a category
* Likewise if you have forum categories but no actual forums, there will be a warning, but as soon as you add a forum, it goes away

The main admin panel can be logged into at 

Features of Chipmunk CMS version 1.3:
Fully integrated forum and New publisher
Linking directory(entries ranked by users ratings on links)
site is controlled by 3 stylesheet so you can customize look at feel
All variable definition in board/admin/var.php and commented to what they represent
Display number of users online throught the CMS

