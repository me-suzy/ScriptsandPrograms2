------------------------------------------------------------------------
Zomplog 3.4 release readme.txt  -  October 14th, 2005
created by Gerben Schmidt
updates: http://zomplog.zomp.nl
------------------------------------------------------------------------

READ ME

This is just a little readme to get you going.

INTRO

WHY ZOMPLOG?

There are many good weblog-systems around, why create yet another one? I've worked with many of the major weblog-systems around, and noticed they had a few flaws in common:

1. They required a lot of technical knowledge from users
2. They had too many bells and whistles that confused users into thinking it was something difficult they were doing.
3. Their interface wasn't logical (why upload a picture in a completely different part of the site, if you're gonna need it for creating a weblog-entry?)
4. They were ugly!

My aim with Zomplog was to create a simple weblog-script that was easy to install and use, even for people without any technical knowledge. A script that would work out-of-the-box, and would not require users to wade through endless instruction-files before they could do their first posting. I feel that anyone should be able to benefit from new technical possibilities, and thus tried to make Zomplog as easy and intutive as possible. Well, even my mom uses Zomplog now. So I guess I've reached my aim :)

Another problem I regularly came across is that open-source programmers are no graphical designers. Open-source scripts and systems might work fine, but they're often not really attractive to the eye (to say the least). Wouldn't it be much more fun to work with systems that are beautiful as well? Isn't that a vital part of the way we appreciate the things we see and work with?The open-source movement needs graphical designers! That was another aim I had with this script: make it something that's just attractive to look at!

WHAT IS ZOMPLOG?

Zomplog is an easy to use weblog script, that works out-of-the-box, while staying very flexible in the way it can be applied. You can use it as a news-script for your frontpage, as a full-fletched weblog-system, or you can even build your whole website around it. Through the use of skins, you can even easily let it fit in with your existing site. Zomplog has built-in language files (currently English, German and Dutch) so creating a localized version of Zomplog is a piece of cake. It supports all you would expect from a weblog-system: image upload, categories, comments, search, BBcode editor, multiple users and an rss feed, while staying easy to use for anyone. Starting from version 3.0 Zomplog also has built-in support for moblogging: posting to your site through email or even your mobile phone! All weblog settings can be managed through a control-panel. Zomplog has an active user-community that regularly releases new language files, and nice add-ons.

ABOUT THE DEVELOPER

I'm Gerben Schmidt, a free-lance php-programmer and graphic designer, situated in Utrecht, The Netherlands. During my time at the New Media Department of Utrecht University I've done a lot of research on open-source software and user-experience. My aim was to find ways to make open-source software better accessible to non-technical users. I've used a lot of my findings in the Zomplog project.

I use Zomplog for my weblog-art-magazine http://www.zomp.nl, for my band website http://dialprisko.nl, and as a basic CMS for much of the sites I make for clients.

Oh yeah, if you're looking for a php-programmer who's also into graphic design and video: I'm for hire!

SUPPORT AND BUGS

So you've bumped into a problem? Something didn't work as expected, or didn't work at all? There's a few things you can do:

1. Go to the Zomplog support forum at http://zomplog.zomp.nl and ask for help or report a bug.
2. Vist the support files on http://zomplog.zomp.nl
3. Mail me at the email-adress you can find on http://zomplog.zomp.nl

You as a user are whom I made this script for, so if you think something's wrong or have ideas to improve the script, please contact me in any way. I depend on your input! And your input is often directly translated into new releases of Zomplog: many of the improvements in this version were user-requests! You use it, so you should be able to have a big say. At Zomplog there's no waiting for helplines, or queing up. Speak out!

NEW FEATURES

I rebuilt the script from scratch for this version, to make the script more efficient in use of code, coherent (in functionality as well as coding-style) and to get some old problems out. And of course, because it's always good to start with a blank sheet!


v 3.0:
- added settings panel to configure overall zomplog settings like skin, language, maximum upload size, etc.
- added Moblog-functionality: now anyone can post to their website through email or even mobile phone. You can even send pictures!
- added BBcode and WYSIWYG-editor for BBcode (this was a much-heard request.)
- search function
- category stats
- added more consistent error checking system
- improved image upload-system
- added personal profile
- fixed bugs and shortcomings in user-and category-management
- updated language files
- added new skins
- added help area (for later use)
- cleaner code(!)

v 3.1:
- weblog-title is now defined in install-procedure
- improved skinning-system for more controll over lay-out and function!
- added content management system for static-pages
- added one-click email-form generator that you can add to any page!
- improved & easier BBcode editor created by Micha

v3.2:
- you can now add video and audio to your weblog-post! Support for the following formats: quicktime, realmedia, windows media and mp3's! Created a dynamic flash mp3 player.
- completely restyled admin-area: main menu is now accessible from every page, added fancy icons, site statistics and rrs reader!
- you can create welcome messages for your website and for the admin-area
- optionally you can allow users to register to your weblog
- Micha made the BBcode editor even fancier: now you can select the text you want to make bold, italic, etc, and tags are now inserted at the cursor-position!
- Micha improved the upload-script so a bug that occured in Windows IE is fixed
- improved skins

v3.3
- vastly improved thumbnailing system. In previous versions thumbnails were generated on the fly, once the page was called. From now on, a small and a big thumbnail are generated at the same time the picture is uploaded. Your pages will load much faster!
- Support for multiple uploads
- Optional use of custom upload tool for more controll over images and image placement.
- when a post is deleted, the images that belong to that post are also deleted from the server
- added much requested ip-ban feature for comments
- added archive (well, that was about time!)
- made date function much more flexible
- delete multiple entries/pages/comments at once
- changed BBcode editor to HTML-editor: now you can use all kinds of html in your posts again!
- several minor layout-improvements

v3.4 - 14th of October 2005
- Micha created a wonderful replacement for the image enlargement pop-ups, a very smooth image enlargement script. It resembles the Mac OSX Tiger widgets screen and was based on a script by Jonathan del Strother from steelskies.com
- extended the "change date" feature: now you can also edit hours and minutes
- fixed a bug in the moblog script
- fixed some security issues involving sql-injection and XSS-exploits

GNU

This program is open-source software and appears under the GNU license. You can do anything you want with it: modify it, change it around, make it more beautiful, make it better! Please let me know if you made any good improvements: that way I can incorporate them in future versions. If you use it, and like it, it would be kind of you to make a link back to http://zomplog.zomp.nl. Enjoy!

LOOK AND FEEL

Since version 2.3 Zomplog has support for skinning! How does it work? Just edit the default skin which can be found in the skins folder, or create a new folder within the skins-folder, give it an appropriate name, and create your own skin! To activate your skin, just selct it form the dropdown menu in the Zomplog settings panel.

Feel free to create your own skins! You could even mail them to me, so I can add them to the downloads-section of the site! A standard skin consists of the following: header.php, footer.php and style.css. If you use images in your skin, be sure to use the following path: skins/name_of_your_skin_directory/images/imagename.jpg

LANGUAGE

Choose your preferred language in the zomplog settings control panel, and feel free to create a language file in your own language!

If you want to create a new language, just copy an existing language file, and translate it to your preferred language, then upload it. To activate the language, go to the settings page in the admin control panel, and pick the language file you've just created form the language dropdown menu. Once you've translated Zomplog, don't hesitate to upload your language file to the forum: http://scripts.zomp.nl

SUPPORT FORUM

There's a support forum for Zomplog at http://www.zomp.nl/forums. Post your bug reports, questions, feature requests there. This is the central place for all discussion about zomplog. And if you've installed the script, go ahead and post a link to your site, so others can see what you've done with the script! You don't have to register, so go ahead and have a look!

REQUIREMENTS

php4 (3 might work as well, haven't tested)
MySQL

EASY INSTALL

I've tried to make the installation-process as simple as possible. Make sure the database exists before running the install script.

-unzip all files into one directory (you can rename it to whatever you like)
-change CONFIG.PHP (which is in the "admin"-folder) to fit your MySQL database (MySQL username, Db-name, password, etc.)
-upload the whole thing
-open your webbrowser and go to INSTALL.PHP. This file will automatiscally install all necessary tables in the database. If all went well, you will be able to create an administrator username & password.
- after you've logged in, delete INSTALL.PHP (important for security reasons).
-you're done!

Note: starting from version 3.0, all major settings of the zomplog system can be edited through the "settings"-panel! No need of getting your hands dirty anymore!


IMAGE UPLOAD (optional)
To use the image-upload function, just CHMOD the folders "thumbs" and "upload" to 777 and you're ready to roll! If you don't want to use image-upload, you can switch it off in the "settings"-panel.


CREDITS

All code & design by Gerben Schmidt. Special thanks to Jez Hancock for creating the wonderful script SimpleAuth, which saved me hours of work in not having to start from scratch. Thanks to Kristine of Movable Type, for all her coding suggestions when I made my first scripts in php, and thanks to Gramcracker, for his life-saving remark "Do you have any data in the table?". If it wasn't for him, Zomplog would probably never have existed.

A very special thanks to Micha, who tested the beta versions of Zomplog 3.0, 3.1, 3.2 and the current version and created the improved BBcode editor, suggested several code changes and created the German language file. Micha also created the wonderful image enlargemnt script, which was based on a script by Jonathan del Strother. The moblog system is based on the wonderfully simple Simsi by Marc Rohlfing. Special thanks to all dedicated Zomplog users who posted their requests, bug reports, new language files and additions on the Zomplog forum. This one's for you all!


CONTACT INFORMATION

Gerben Schmidt - http://zomplog.zomp.nl
Zomplog Forum: http://www.zomp.nl/forums