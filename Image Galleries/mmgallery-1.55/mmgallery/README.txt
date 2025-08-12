mmgallery v1.55
info@mmgallery.net
http://www.mmgallery.net


Introduction
------------

mmgallery is a simple PHP based images slideshow. It is licensed under the GNU Public License (see enclosed LICENSE.txt).
If you use this script commercially, please make a little donation (not mandatory).

Installation
------------

*** ATTENTION *** IF YOU ARE UPGRADING from a previous version: some function names have changed, a new page was added and other code was modified. So upgrade is not automatic! (i.e. you can't simply update functions.inc) You have to work a bit on it.

1) Create as many subdirectories as you want to place the images. The directories names can't have spaces in them, use "_" instead.
   For example:

                |-- My_photos_1/
	             |        |--------- thumb.jpg
                |        |--------- thumbs/
   mmgallery/ --|-- My_photos_2/
                |        |--------- thumb.jpg
	             |        |--------- thumbs/
                |-- My_photos_3/
	             |        |--------- thumb.jpg
                |        |--------- thumbs/
                |-- ...

2) For each subdirectory create a thumbnail and put in it. The thumbnail must be named 'thumb.jpg'. This thumb will be used as representative for the entire gallery (directory) in the main page.
The reason because mmgallery doesn't automatically pick a thumb from the "thumbs" directory is that you could want to use a particular image.
   
3) For each subdirectory create a directory "thumbs" and put in it all the thumbnails for the current gallery.

4) In the main directory ("mmgallery" in the example) put the script files.

5) Modify 'index.php' line 50. The function show_thumbs($cols, $th_width, $th_height, $cellpadding, $cellspacing) shows the thumbs in a table with '$cols' columns. '$th_width' and '$th_height' are the dimensions of the thumbnails. '$cellpadding' and '$cellspacing' refer to the table properties.
Ditto for show_thumbs(...) in 'thumbs.php' at line 50. 

6) Customize 'index.php', 'thumbs.php" and 'show.php' as you like.

7) When you show a full size image, automatic resize will decrease it if its dimensions are bigger than browser/screen's one.
If you don't want this open 'resize.js' and at line 26 change "yes" with "no".

8) Set options at line 25 (and following) of 'functions.inc' or leave as they are if don't need them.

9) Enjoy it!

Changelog
---------

* v 1.01 (5 June 2003)
Now you can leave any file in the image directory (e.g. thumb.db) without the problem of blank images in the slideshow.
It recognizes and shows only JPGs, GIFs, PNGs and BMPs.

* v 1.22 (7 December 2003)
Now directories and files listing are in alphabetical order.
Some minor fixes.

* v 1.50 (2 January 2004)
Added thumbnails showing for each gallery. You can click on them to obtain full size image and click again to return to the thumbnails.
Added black border to images and thumbnails.
Changed some function names.
Some minor fixes.
Now automatic resize works also with Mozilla.

* v 1.55 (14 September 2004)
Added some options (show filenames under thumbs, hide some directories with specified prefix).

Help
----

You can contact us at the following address: info@mmgallery.net
We can't assure help using or installing the script. Try to write us, we'll help you if we have time.

Thanks
------

Johny Åkerlund
