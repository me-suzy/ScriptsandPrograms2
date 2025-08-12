---------------------------------------------------------------
|                                                             |
|                     R E A D M E                             |
|                                                             |
|            I N S T A L L A T I O N   /                      |
|                       U S E                                 |
|                                                             |
|                                                             |
|                  O G  C M S  V 1 . 0                        |
---------------------------------------------------------------
---------------------------------------------------------------

TABLE OF CONTENTS:
1. INSTALLATION
2. USE
3. TERMS OF USE

4. CHANGELOG

_______________________________________________________________
1. INSTALLATION


========
CAUTION
========
IF YOU ARE UPDATING FROM A PREVIOUS VERSION, DO NOT RUN SETUP.PHP
AND DO NOT UPLOAD AND OVERWITE YOUR OLD STYLE.CSS IF YOU WANT TO
KEEP YOUR OLD STYLE SHEET SETTINGS.


---------------------------------------------------------------
nessesary to run script:
---------------------------------------------------------------

1.1
- Unzip ogcmsv101.zip

1.2
- Open connect.php and replace values for host, databasename, username and
password, with values from your provider.


1.3
- Upload the contents of the folder where you unzipped ogcmsv101
(including subfolders) to your site via FTP (or your html upload interface
if you've got one)

1.4
- If you want to use the scripts upload functions (strongly recommended for in-post
pictures, as the upload script automatically resizes your images to values
given by the user in the system page) you need to make the 'files (for uploading files)'
'images (for uploading images)' and 'static (for uploading static articles)' folders
writable with chmod.
In each of the upload folders there is a folder named 'upload_logs'.  The 'upload_logs'
folders needs to be made writeable aswell.

1.5
- run setup.php
this will create the needed tables in you database, aswell as set the initial values
needed for running OG CMS for the first time.

----------------------------------------------------------------
recommended but optional (and can be done later if you want to):
----------------------------------------------------------------
1.1.6
- edit language.php to suit your needs. Replace my poorly written english
phrases with your own that means the same as what I have written and you will
probably be happy.

1.1.7
- edit style.css to customize the look of your site.  This is easier if you
utilize a css editor like top style lite (http://www.bradsoft.com/download/index.asp)

1.1.7
- make your own header image.  It is possible to have a different header image for
use in the admin section. Header Image file name is set in the system folder.
And the header image must be uploaded to the images folder.




_______________________________________________________________
2. USE

Most of og cms is self explaining, but a short guide is never out of the way:

Once setup is done, you might want to add your own categories, and posts:
    
2.1 MANAGING CATEGORIES
- in the categories page (link to the left) you can add, eidt and delete categories.
Categories are what your posts will be sorted by when you post them.
For example: news, blog, and music. 
You can edit a category name without worrying about loosing some of your posts.
But when you delete a category, all your posts in that category will be deleted aswell, and there
is no undo on this!

2.2 ADDING POSTS
- The script will remove all html tags in the post, but it will encode linebreaks to <br>
tags, so that you structure is preserved.  
In addition to that it will allso encode all web adresses in the posts to become links. (will not 
encode e-mail adresses to mailto: links)


- When you add an image to a post it is HIGLY recommended that you use the upload funtion, to get resize
of the image to fit your wish, and an automatically created thumbnail, to show in the listing page.

If you however wish to upload you image with ftp, it is important to remember this:
    - image must be placed in the images folder.
    - image must have a thumbnail with the prefix: 'small_' to use in the listing (if you fail to provide this
    you will get a missing image in the listing.
    - you must resize the images to fit your needs yourself, the settings in the system page only applies to 
    uploaded images.
    - only write the filename in the image name box (i.e. picture.jpg), no paths!


- Attaching a file for download is not hard either.  If it is a small file, (like this script) it is ok
to use the upload file button to upload the file, and automatically add the filename.
But if you want to add a larger file, like an mp3, I don't recommend using the upload function due to 
it's lack of progress bar (it is not impossible though, and if you've got patience, it can be done.)

Uploding via ftp is the best way for larger files, upload the desired file to the 'files' folder.
and add the filename (and only the filename i.e. music.mp3) to the filname box.


2.3 STATIC PAGES
- Static pages are simply normal html or php files included in the centre of the page (the main contents coloumn)
This can be stuff like, a links page, about page, contact form etc.  Stuff you don't update that often.
Static pages are best added with the upload function, but pages can allso be uploaded to the 'static' folder.
Again, only type in the filename, NO path.

2.4 SYSTEM PAGE
- Header images must be placed in the 'images' folder, and type only filname, no path!

2.5 DOWNLOADS
- When you add your first file for download, a new link will appear amongst the navigation links; the 
donload link.  This link wil lead to a list of all posts with a file attached.

2.6 GENERAL INFO ABOUT ADMIN MODE:
- when you're logged in as administrator, you get access to a couple more features in the normal post list and 
post view pages:
- edit and delete options on every post
- edit and delete options on every comment
- comments e-mail adress is shown only to admin.
- Download statistics 



_______________________________________________________________
3. TERMS OF USE

I not a lawyer and I have not read the GNU licence (allthough I have agreed with it numerous times :-))
So I won't include it just for show. Therefor I will state the terms of use in another language:
PLAIN ENGLISH :-)
-OG CMS IS FREE
 for use, commercial or personal,
 for you to modify

But please, leave a link to http://www.soemme.no if you use it
and please send me a copy of it if you modify it.


______________________________________________________________
4. CHANGELOG

1.0  First release

1.01 Bugfix:
    site_functions.php:
            five last comments function link fixed.
            added missing .php in link to post_view.php

1.02 More Bugfixes:
    download.php:
        removed leading '/' from url passed to  
        the browser for downloading, thus fixing the bug causing
        download.php to fail passing the correct url if og cms
        is not placed in the site root folder.
    admin_system.php
        some minor fixing on the part that has to do with the rh 
        coloumn settings.  Didn't seem to hcange anything though...
        Still problems with mac explorer.  But it is just cosmetically
        functionally it is all ok.  
    language.php
        Changed some of the initial values, to hopefully make things
        a bit more understandable.
    readme.txt
        Added a bit more information about using the script 


    I would like to add that I am very thankful to Federico Cavaglià to help me 
    point out and fix these bugs! Thank you very much!

______________________________________________________________
author:
-Vidar Løvbrekke Sømme
aka Olegu
http://www.soemme.no
e-mail: olegu@soemme.no