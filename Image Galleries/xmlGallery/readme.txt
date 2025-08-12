xmlGallery v1.0 README
	Script and README Author: <http://www.fromthedesk.com/code>
	Script Last Modified: 04/26/2003
	README Last Modified: 11/04/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. Customization
5. How to Use
6. Reporting Bugs
7. Acceptable Use
8. Donate

1. PURPOSE - Brief Explanation, Advantages, and Limitations
	xmlGallery v1.0 is a one-page image gallery.  Using an XML file, you can easily add, edit, and delete images with captions.  The owner may choose between a table and a linear presentation.  Both presentations are completely customizable using a template.

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip gallery.zip.  You should now have the following files:
		* gallery.php
		* images.xml
		* template.txt
		* replaceArrStrs.inc
		* selectText.inc
	(2) Upload the files.
	(3) You should finish by testing it.  An example gallery is set up for "out of the box" testing.
	(4) After you have tested xmlGallery, begin to set up your own gallery.  Open gallery.php.  Scroll down to the "DEFINE VARIABLES" section header.  Decide between the HTML and linear presentations.  Set $displayOption to 1 or 2, depending on your decision.  The default is 1, which is a table presentation.  If you have chosen the table presentation, you will also need to edit the $imagesPerRow variable.  Save your changes.
	(5) Open images.xml.  To add your own images, see section 5, "HOW TO USE," below.
	(6) Open template.txt.  See the notes in the section below, "CUSTOMIZATION."
	(7) Upload the edited files and test your new gallery.

4. CUSTOMIZATION
	The template is completely customizable.  The code between the <!--BEGIN--> and <!--END--> markers (each marker MUST be kept on separate lines) is the template for each image.  Keep in mind the presentation type you have chosen - table or linear - when editing this code.  If you chose a table presentation, for example, this is the code that will appear in each table cell.

5. HOW TO USE
	To begin adding your own images, open images.xml for editing.  If you know XML, you will quickly understand how to use xmlGallery.  If not, read on.
	Notice that the file is heavily tabbed.  You'll notice a structure like this:

<IMAGES>

<IMAGE>
<FILENAME>image1.jpg</FILENAME>
<CAPTION>View from the hotel balcony.</CAPTION>
</IMAGE>

</IMAGES>

	Also notice that the XML code looks much like HTML code except the tag names are unfamiliar.  XML allows you to create your own tags.  Just like HTML, XML must have opening and closing tags and data can be stored within the tags.
	You can easily add images by creating a new <IMAGE> entry.  Just copy an existing one (or type from scratch) and modify the <FILENAME> and <CAPTION> content.  Editing and deleting should be self-explanatory now.
	Remember to upload gallery.xml to your site after you make changes.  Always refresh the calendar to make sure you didn't make any mistakes in gallery.xml.  XML is pickier than HTML!

6. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

7. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.

8. DONATE
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>