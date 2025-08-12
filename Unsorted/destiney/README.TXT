
Destiney.com Scripts v0.3 README.TXT:

License is in admin/LICENSE.TXT

Install instructions are in admin/INSTALL.TXT

Upgrade instructions are in admin/UPGRADE.TXT



Notes:
------------------------------------------------------------------------

About User Images:

	You may want to move your user images path to a directory above
	your actual web directory so the images are not individually
	available via the web.  The image.php script is a `pass-thru'
	script which can read the images from most anywhere on your
	system.  Moving the directory is recommended.


About Image Watermarks:
	
	To get watermark support for your images, you will need to have
	the following support built into your PHP:

	--with-gd
	--with-jpeg-dir
	--with-ttf
	--with-freetype-dir

	Newer PHP has most all of this support available without
	requiring you go out, find, and install external libraries.
	The `image.php' script supports .png, .jpg, .gif files without
	any changes.

	Due to licensing restrictions, GIF support has been removed
	from newer GD libraries and as a result from PHP as well.  GIF
	support is included in `image.php' but you may find it doesn't
	work simply because your PHP install doesn't support it by
	default.  Support may be added to your PHP install, but that is
	beyond the scope of this document.

	You will also need to upload a font of your choosing to the
	fonts directory.  The image.php script is coded for use with the
	`arial.ttf' font, but can easily be adjusted for use with other
	fonts.  The `arial.ttf' font is not included due to licensing
	restrictions.  You can find the `arial.ttf' font on any windows
	computer you currently have a license for.

	If you want to use watermark support, don't forget to turn it on
	in your admin/config.php file, it is off by default.

	* Windows users will need to load the `php_gd2.dll` extension
	which is available at php.net.


About stats/* and graphs

	To get your stats graphs working you will need to aquire the
	free version of the JPGraph software located at:
	http://www.aditus.nu/jpgraph/.  The graphs option is
	turned off by default and must be enabled in admin/config.php.

	It is expected all of the jpgraph files will be placed in a
	folder called 'jpgraph' parallel to the 'stats' directory.
	
	Your directory structure should look like this:

	admin/
	include/
	stats/
	jpgraph/*

	You need to place into the `jpgraph' directory all of the *.php
	and *.inc files from the original `jpgraph/src/*' directory.
	The other jpgraph files aren't required.

	Installing and configuring JPGraph is beyond the scope of this
	document.


About include/cache:

	To speed up page load time and to lessen load on MySQL, parts of
	the site content is cached in include/cache.  These files are
	recreated once every hour as required.  The recreation process
	is invoked by visitors to your site and requires no attention by
	you.  If the *.htm files in include/cache are old, they are
	automatically rebuilt on the fly.  Meanwhile the visitor to your
	site who caused the rebuild is sent dynamic content so he
	doesn't have to wait on the required rebuild time.

	This type of build system is very efficient, but you have to
	remember that if you make changes to any of the following files:
		
		include/right.php
		include/site_menu.php
		include/stats.php

	you have to delete the include/cache/*.htm files so they will be
	automatically rebuilt.

	You can increase or decrease the frequency of the rebuild time
	by editing the mktime() call near the top of each of the files
	listed above.

	Some web servers have been found to produce harmless errors the
	first time the cached .htm files are built.  This is due to more
	restrictive web server permissions than normal being set.  You
	can prevent these one-time errors by creating the initial files
	in advance:

		cd include/
		touch right.htm site_menu.htm stats.htm
		chmod 775 *.htm


About web forums

	If you delete a web forum in admin, by default all threads,
	thread views, and thread post in the forum are also deleted.
	There are no warnings or second chances, so be very careful when
	working on the web forums setup.
	
	Currently there is no way to manage threads and posts, only the
	forums themselves.  The code is written for a single top level
	of forums with a second sub level of forums that actually
	support the threads and posts.  There is no support for top
	level forum threads, you must create sub forums.
