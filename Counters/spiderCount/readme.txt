spiderCount v1.1 README
	Script and README Author: Jon Thomas <http://www.fromthedesk.com/code>
	Script Last Modified: 11/3/2005
	README Last Modified: 11/3/2005

TABLE OF CONTENTS
1. Purpose
2. Requirements
3. Installation
4. Customization
5. How to Use
6. Change Log
7. Reporting Bugs
8. Acceptable Use
9. Donate

1. PURPOSE - Brief Explanation, Advantages, and Disadvantages
	spiderCount v1.1 is a simple image-based hits count.  This script increments a hits count every time it is called.  It also outputs the count as a JPEG image.  The hits count is stored in a text file.  The JPEG image is generated using images of the digits 0-9.  The user can begin the counter at any number.  The user may also specify the minimum number of digits to display.  Until that number is reached by the hits count, the script will append leading zeroes.  spiderCount is hard-coded to support the JPEG image format only.  However, it is easy to modify the script to work with another format.

2. REQUIREMENTS - Necessary Software and Server Access
	PHP must be installed on your Web server.  In addition, it must be compiled with the GD library of image functions.

3. INSTALLATION - Step-by-Step
	(1) Download and unzip spidercount.zip.  You should now have the following files:
		* counter.php
		* counter.txt
		* images/

		The images/ directory includes images for the digits 0-9.
		
	(2) Open counter.php for editing.  Edit the variables (found under "// SET VARIABLES").  If you intend to use your own digit images, set the $digits_location variable to the path to these images, including the trailing forward slash.  Also set the $digit_width and $digit_height variables.  Note that all digit images should have the same dimensions.  Save your changes.
	(3) If you wish to begin the counter at a number other than 0, open counter.txt for editing, enter your preferred number, and save your changes.
	(4) Upload the files.  Give counter.txt read and write permissions.
	(5) Finish by testing.  Simply visit the URL for counter.php.

4. CUSTOMIZATION
	As explained above, you may supply your own images for the digits 0-9.

5. HOW TO USE
	You will likely want to embed counter.php within an existing Web page.  Simply add this HTML tag to your page:
		<img src="counter.php">

6. CHANGE LOG

11/3/2005: Version 1.1 now outputs a single JPEG image.

6. REPORTING BUGS
	Visit <http://www.fromthedesk.com/code> for updates and bug reporting.

7. ACCEPTABLE USE
	You may freely use, modify, and distribute this script.

8. DONATE
	Found this script useful?  Donate a couple of bucks!  Donations pay for my Web site.  You can donate in two easy ways:

Amazon Honor System: <http://s1.amazon.com/exec/varzea/pay/TRYEUATI4836V>

PayPal: <https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=jp%40john117%2ecom&item_name=The%20JPT%20Web%20site&amount=2%2e00&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8>