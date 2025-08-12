ArticleMentor is developed by Stefan Holmberg
http://www.aspcode.net/php/article/index.php

Usage of the script is totally free.


Installation:
*************
1. Create a database, call it for example article
2. Run the script database.sql
3. Edit incconfig.php, the first five lines to replect your installation

$incdbhost = "localhost";
$incdbuser = "uid";
$incdbpwd = "pwd";
$databasename = "article";
$sSiteTitle = "Your site name";

4. You might as well edit the inctemplate.php file to change the layout.
Style.css contains stylesheet info you might want to change.

5. Then browse to the admin  directory. ( For security reasons - PASSWORD protect
this direcory. For instructions on how to do it, see your webservers manual )

6. Now you can start adding categories/subcategories and articles.


7. NOTE THAT A CATEGORY ONLY SHOWS UP FOR YOUR USER WHEN IT HAS ARTICLES IN IT!



Version log:
2.0 PHP version released.

TODO:
- Password protection to admin part

