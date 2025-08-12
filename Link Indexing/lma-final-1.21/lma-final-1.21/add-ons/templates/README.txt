COPYRIGHT
=======================

These templates are protected by International Copyright laws and are intended only for users of Duncan carver's "Link Management Assistant" (http://www.onlinemarketingtoday.com/software/link-management/)


ZIP CONTENTS
=======================
1. README (please read once through)
2. 3 screenshots (the respective template layouts)
3. 5 HTML files (add.html, search.html, category,html, subcategory.html, main.html)
4. 3 CSS files (minimal.css, green.css, red.css)
5. 'images' folder containing 8 files (green-body-bg.jpg, green-bullet.jpg, green-header.jpg, minimal-bullet.gif, minimal-header.jpg, minimal-wrapper-bg.jpg, red-bullet.gif, read-header.jpg)


INSTALLATION
=======================
Unzip files and upload images folder (and contents) and css files to the lma installation directory on your server.

Open main.html, add.html, category.html, subcategory.html, and search.html with a text editor (or your html editor, make sure it's in code view) and change the following line in each one:

' <link rel="stylesheet" media="screen" type="text/css" href="http://www.yourwebsite.com/lma/minimal.css"> '

Change youwebsite.com/lma/ to the installation directory of lma on your website

After you've made the necessary changes, make a backup of your original LMA templates directory files, and upload your new "main.html, add.html, search.html, category.html, and subcategory.html" to the directory (overwriting the old files).

The default template is minimal, if you wish to change this, change the reference of minimal.css to red.css or green.css

Make sure to do this on each file.

* On the add.html page, there is also a reference to return to the home directory, make sure to change:
http://www.yourwebsite.com/lma/directory/ to your website installation directory of LMA



COLOR SCHEME FOR MINIMAL
========================
You can change the color scheme on minimal by opening the file called minimal.css with a text editor and changing the hex value of links and heading to suit your website color scheme

Example:
/* ===== edit hex code to change color ==== */
a, #header h1 {color: #4277C1;}
a:hover {color: #093879;} /* sets link hover color */
/* ===== end editing ====== */

Change 4277C1 and 093879 to your preferred color, save the file, and upload it to the server. You will not need to change any other elements of the stylesheet although you can play around with it if you know what you're doing.



CUSTOMIZATION
=======================
Notes have been placed throughout the HTML coding of the file. Customization and integration should be relatively simple, be careful not to edit outside of the designated "editable" regions as this may throw the layout off. When customizing the layout with a WYSIWYG editor, you may not see what the final results will look like, it's best to test it on your server.



GOOD TO KNOW
=======================
Backing up your files is always good thing.
Green is a fluid layout - it will automatically resize itself to fit the moniter.
The pages will look "unstyled" until the reference (see installation) has been changed to point to the desired stylesheet.