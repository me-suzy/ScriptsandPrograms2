Digital Scribe Release 1.4.1
--------------------------------------
The Digital Scribe is an intuitive system designed to let you easily publish student work and homework assignments.
 Copyright (C) 2002-2005 - By Jeff Conor
 http://www.digital-scribe.org
--------------------------------------

To install:
Visit http://www.digital-scribe.org/faq.php for detailed instructions.
Quick Version:
0) Create or select a MySQL database.
1) Put your login name, password, and database name for the MySQL database in access.inc.php.
2) Upload the Digital Scribe files to your server.
3) Go to run_first.php via a browser and follow the instructions on the page.
4) Delete run_first.php.

--------------------------------------

To upgrade from version 1.3.1 or 1.3:
1) Download the following files from your server, header1.php, header2.php, style.php, footer.php, and all the images in the DigitalScribe/images folder.
2) Upload all the Digital Scribe files to your server and replace the duplicates.
3) Upload the files that you had downloaded during step 1.
4) Modify access.inc.php to have your MySQL login name, password, and database name. You can't use the old version as this file has changed.
4) Go to upgrade.php via a browser.
5) Delete upgrade.php.

--------------------------------------

Common Pitfalls
If you get an error when you try to edit a file in the templates page of the administration section you need to change the file permissions to writable (chmod 666) for the files style.css, header1.php, header2.php, and footer.php - all of which are in your main Digital Scribe folder.

--------------------------------------

License
This software is distributed under GPL(General Public License).
See license.txt for more information.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

--------------------------------------

See changes.txt for version history.