Content:
	1. Summary
	2. Frequently asked questions section
	3. Changelog
--------

	1. Summary 

EncapsGallery is an image gallery, 
supports different independent layouts/themes.
Web-design based on native php+html templates.
Has predefined 4 web-themes (acd,raym,expo,light)
Key features:
- mysql/pgsql support
- create unlimited folders
- upload unlimited photos
- automatic thumbnail creation
- easy to install and customize
- web-admin presented
- large images number will be spleted by pages (number of images by one page 
	may be configured from web-admin)

	2. Frequently asked questions section. 

Q: How to install and run?
A: You can start installer manually: run admin/install.php.
In any case, you need to provide full access for web-server user to the "rwx_gallery" folder:
 chmod 777 rwx_gallery  (unix)
or
 change file attributes to "rwx rwx rwx" for "rwx_gallery" folder (windows)

To get front-end (user) access, run gallery.php.
To get back-end (admin) interface, run admin/gallery.php.

Q: How to upload images with ftp?
A: You should upload images to the "rwx_gallery" folder. Do not forget to chmod -R 777 for rwx_gallery
It also possible to upload images with web-admin.

Q: How to change/add new theme/web-design?
A: Take a look at the folder "themes": there is sample templates ("raym", "light", "expo").
You may edit html-forms with your preferred html-editor.

Q: What does mean parameters from config.ini.php?
A:
$config["db_host"] = "localhost"; 	//dns-name or ip-address of database server
$config["db_user"] = "root"; 		//username which have access to the database
$config["db_pass"] = "";		//password for username
$config["db_name"] = "test"; 		//database name
$config["db_type"] = "pgsql"; 		//database type: pgsql - PostgreSQL, mysql - MySQL
$config["debug"] = "0"; 		//switch off debug information
$config["demo"] = "0"; 			//switch off "demo"-mode
$config["admin_uname"] = "demo"; 
$config["admin_pass"] = "demo";
$config["theme"] = "light"; 	//which theme(layout,design) will be shown for users
$config["pager_items_per_page"] = "9";  //how much thumbnails to show per page
$config["action"] = "config_update";

Q: How to compile PHP with GD support (EncapsGallery requires GD support with PHP)?
A: Brief manual you can find there:
	http://www.onlamp.com/pub/a/php/2003/03/27/php_gd.html
	http://www.phpmac.com/articles.php?view=96


	3. Changelog: 

--------------
Version 1.0.0:

- sort order for new uploaded images (ASC, DESC)

--------------
Version 0.5.0:

- web-config implemented

--------------
Version 0.4.1:

bugs fixed:
- "the gallery that's sorted to be the first in order is not selectable when uploading new images, nor are you able to put images in other galleries into that gallery.
check in the demo gallery - the gallery called melon (which is first in order) isn't selectable."

new features:
- new theme "acd" implemented

--------------
Version 0.4.0:

Bugs fixed:
- when admin updates theme content, the theme-list can be changed and items moved to the next  theme
- remote file inclusion via the root parameter is disabled.

New features added:
- admin area protected with login/password

--------------
Version 0.3.3:

- basic functions realised and debugged