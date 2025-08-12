/*


IF YOU ARE UPGRADING FROM 0.9 TO 0.9.1:

YOU ONLY NEED TO OVERWRITE INDEX.PHP AND MIDDLE.PHP



*/

/*

* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9.1
* Date:         2005/08/05 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* 0.9.1 Update:
* Fixed the XSS security problem with $cat, $snip and $show on the querystring
*
* Updates:
* Fixed file storage/retreival problem which caused the  + sign to be removed.
* new version now stores file as is (no reverse/encoded storage).
* Session management has been introduced to allow any user to browse any Library -
* this will not effect the default library.
* An update file (convert08to09.php) should be used to allow the new code to
* read snippets created using version 0.8.
*
*
* This version has been tested on:
* PHP 4.1.2 to 4.3.4
* Apache and ISS
* Linux amd Windows
* Register Globals on and off
* 
*/


******************** Features/Fixes ********************

--------------------
New Features/Fixes in 0.9:
--------------------

1. All Libraries viewable to all users - using sessions its
   now possible for all users to view all libraries. The admin
   still has control over the default library which is loaded.
2. Code Line Numbers - All code displayed now has line number.
3. Plus Sign Problem Fixed - it was found that the old version
   lost all + signs in the saving process. This was due to the
   poor encryption method used. The new version has dropped the
   encyption and now stores files as is (directory permissions 
   should be adopted instead). This has fixed the + 
   sign problem.


--------------------
New Features/Fixes in 0.8:
--------------------

1. Multi-Library support. Using an admin function,  
   new libraries can be added. Existing ones can be renamed 
   or deleted. Switching between libraries is easy using 
   an admin function.
2. All Add, Edit, Rename and Delete functions are password 
   protected. These functions are available for Libraries, 
   Categories and Snippets.
3. All javascript pop-up notices removed.
4. Config options available in the config.php file include:
   * Register Global on/off.
   * Iframe on/off for Snippets and Descriptions.
   * Admin Menu viewable to non admins (users not logged in)
   * Site Name and URL to be displayed in as the title and head.
5. Allow for HTML Special Characters in the description (&nbsp; etc..)
6. Interface enhancements with nice new icon graphics and link colours.
7. License file built into the General Menu.
8. PHP Links option built into the General Menu.



------------------------
Other New(ish) Features:
------------------------
1. Printer Friendly option for Snippets.
2. Password Protection for all add, edit and delete options.
3. Increased character length on Category and Snippet names.
4. Category and Snippet links added to the main screen.


******************** Notes ********************

For installing/upgrade information, see install.txt.

Feel free to use and edit the source code as you see fit.
However I do ask a few small things.... Please leave all 
Original Comments at the top of each page. Do not resell 
as a package or within an other package without permission 
from the original author.
Do not claim to be the Author of any of this code.
Please leave at least one link to http://www.php-csl.com -
somewhere viewable in the frontend (even the links page is fine!!).

That's about it.. I think. Although you should read the GPL 
License anyway (In the General Options Menu) and license.txt file.