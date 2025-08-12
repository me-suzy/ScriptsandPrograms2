PHPSlideShow v0.9.2 written by Greg Lawler
download the latest version from http://www.zinkwazi.com/scripts

PHPSlideshow is relesed under the GPL
See the license at http://www.gnu.org/licenses/gpl.txt
Feel free to use/modify this little script

Download PHPSlideshow.zip from http://www.zinkwazi.com/scripts and
unzip the contents into a folder of images.
That's it, your slideshow is ready, simply navigate to the phpslideshow.php
script in your browser.

NOTE: Be sure that you are loading phpslideshow.php in your browser and 
NOT the template.html file (you'll see a strange brokem page if you do this)

Q: How do I give each directory of images it's own page heading?
A: Place a text file called heading.txt in the images  directory with the page heading
on the first line of this file.

Q: How do I add image comments/descriptions to the slideshow?
A: There are two ways to do this, 
  1) add an EXIF comment to each image.
     A google search will return many free tools that enable you to edit the EXIF comments section of a JPEG. 
     Use the <EXIF_COMMENTS> in the template.html file to display this data.
  2) Create a pics.txt file.
     Create a text file that lists each image name and description on a new line separated by a semi colon.
     for example each line would look like this: 
         my_house.JPG;This is my house.
     NO BLANK LINES!

     A quick way to generate a pics.txt file with image names is to use the command prompt.
     ls *.jpg > pics.txt in linux or OS X
     dir /b *.jpg > pics.txt at a dos prompt in windows
     You will need edit this in a text editor to add the semi colon and
     desription.

Q: How do I change other settings like the number of thumbnails displayed,
image sort order and default directory names?
A: For these and other more advanced options, You will need to edit the phpslideshow.php file

Q: How do I customize the look and feel of my PHPSlideshow installation?
A: There is a template.html which is the file you edit to change the layout, colors etc of your slide show.
There are a number of "tags" that you can use in the template.html file to customize your PHPslideshow.
Following is the list of availabls tags:
   <SHOW_TITLE> : the slideshow title from the heading.txt file.
   <BACK> : navigation button to go back one image.
   <NEXT> : navigation button to go forward one image.
   <POSITION> : displays position in the slideshow e.g. "2 of 6" 
   <IMAGE_TITLE> : the image title if you used a pics.txt file.
   <EXIF_COMMENT> : information from the EXIF Comment field if it exists.
   <THUMBNAIL_ROW> : output the thumbnails if the "thumbnails" dir exists
   <META_REFRESH> : this needs to go on the <head> section of your template
   <AUTO_SLIDESHOW_LINK> : displays start and stop slideshow link (SEE <META_REFRESH>)
   <IMAGE> : displays the current image
   <IMAGE_FILENAME> : displays the image file name
   <CURRENT_SHOW> : displays path to the current slideshow.

some additional CSS class info:
class='thumbnail_center' : allows to you customize the middle thumbnail image
class='thumbnail' : affect all thumbnail images except the center one

Q: how do i use one slideshow for multiple different directories of images?
A: all you need to do to enable this is to call the script and pass it the 
directory path.
For example:
pictures_directory -> phpslideshow.php
                   -> dog_pics
                   -> cat_pics
If you have a directory called pictures_directory that contains your 
phpslideshow.php and two directories containing pictures of your pets...
in order to access the shows:
http://yourserver.com/pictures_directory/phpslideshow.php?directory=dog_pics
http://yourserver.com/pictures_directory/phpslideshow.php?directory=cat_pics

if there were images in the pictures_directory, you'd see them like this:
http://yourserver.com/pictures_directory/phpslideshow.php

these three examples will run phpslideshow but each one will load a different
set of images and descriptions located in the directories shown

EXAMPLE pics.txt file contents:
greg.jpg;Me
dog.png;My dog John
cat;
tux.jpg;My friend Tux
(Not all pics need a description)

NOTE: for security, you can only access directories within the same dir as
the phpslideshow.php script...


