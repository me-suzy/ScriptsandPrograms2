Easy Peasy Image Gallery (EPIG) version 1.0.8

Copyright(c) 2005 Apostolos (Soumis) Dountsis
e-mail: epig@gamecycle.net


Description
-----------
Ever had a collection of photos from your holidays or parties that you wanted to make them available on the web for your friends and relatives to enjoy? With EPIG, you can do just that. All that you need is web space to host your EPIG album with PHP & GD support. Simply upload the EPIG files on the same folder where your photos are and you are done!

EPIG displays an image gallery with automatically generated thumbnails for all the image files (supports JPEG, GIF and PNG files). You can then click on the thumbnails to see the actual images. You can navigate page-by-page or by page numbers. 


Customisation
-------------
EPIG has been designed to work without any modification. However, it is very simple to customise it. Edit the provided XML file to set a title and a description. 

You can also customise the dimensions of the generated thumbnails and/or their quality (applies only to JPG images). If you want to customise it further, you can modify the supplied template or create your own, based on the instructions below.


Requirements
------------
EPIG requires PHP4 with GD support. It does not require the presence of a database.


Installation
------------
EPIG comprises of the following files:

    * index.php is the EPIG kernel. You simply need to upload this file to your web server. No 	modification is required on this file.

    * config.xml is the configuration file. Edit this XML file with a text editor (like notepad or textedit) to personalise your EPIG album. Save your changes and upload config.xml file on the same location where the EPIG kernel (index.php) resides.

    * template.html is the default template. You can simply upload the file as provided or you can edit it first. You can always point to a different template by amending the <template> tag value in the config.xml.

After you have dealt with the EPIG files as described above, upload all your photos in the same location as the EPIG files.


Update Instructions
-------------------
If you are updating your version of EPIG then you need to upload and overwrite *only* the index.php to your photo album on the web server. 
Note that if you update and overwrite config.xml and/or template.html then you will lose your settings.


Custom Template Instructions
----------------------------
You can create your own templates by creating an html file and applying the EPIG markers on it.

Do not forget to change the value for the <template> tag in config.xml if the filename is other than 
"template.html". The EPIG markers are:

	{gallery}	: Your thumbnails
	{next}		: The link to the next page
	{back}		: The link to the back page
	{pages}		: A list with all your pages as links
	{title}		: The title of your album (optional)
	{description}	: A description for your album (optional)
	{author}	: Your Name (optional)

EPIG warps the images around a CSS class "thumbnail". This class allows you to redefine the margins and padding of the thumbnails, or provide a user-made border around them.


Feedback & Suggestions
----------------------
I welcome your feedback and suggestions on EPIG. My goal was to develop an image gallery which would be easy to setup and maintain.

You can send me any ideas on improvements and/or problems that you may encounter with EPIG emailing me at epig@gamecycle.net.


EPIG License
------------
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details (http://www.gnu.org/licenses/gpl.html).

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.