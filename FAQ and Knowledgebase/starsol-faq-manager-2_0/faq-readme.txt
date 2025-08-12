
Starsol FAQ Manager version 2.0.

Released 26th June 2005.

Written by and copyright Rupe Parnell trading as Starsol.co.uk.

Available free at Starsol Scripts at http://www.starsol.co.uk/scripts/

============================
INSTALLATION INSTRUCTIONS
============================

1. You will need a MySQL database setup in order to use this script. If you do not have one, create one before you begin the installation (make certain you note down your MySQL username, password and database name - you will need them in step 5). If you do not know how to create one, contact your website host.

2. Open up 'faq-template-global.txt' in a text editor such as Notepad. Add the HTML for the layout of your website around where it says [[[main_content]]]. Alternatively if you are using another script by Starsol Scripts that uses the same template format, you may change "file_get_contents('faq-template-global.txt')" in the ep() function in 'faq-functions.php' to the path of another template file.

3. Upload all files in this package (except the readme) via FTP in ASCII mode. They should all be in the same directory.

4. Chmod 'faq-variables.php' to 666.

5. Go to http://www.YOURSITE.tld/faq-install.php to enter your settings and create the MySQL database tables.

6. Delete 'faq-install.php' from your server.

7. Go to http://www.YOURSITE.tld/faq-admin.php to start adding your categories and questions. Send your website visitors to http://www.YOURSITE.tld/faq.php to read your F.A.Q.

============================
F.A.Q. (about the FAQ Manager Script)
============================

Q. How can I tell if I am using the latest version of the Starsol FAQ Manager?

A. Go to the 'version information' page in the admin area to find out if you are using the latest version.


Q. I am having trouble installing the FAQ manager script? Can you do it for me?

A. Yes, please see http://www.starsol.co.uk/scripts/script-installation.html for more information.


Q. Where can I find instruction on how to upgrade from a previous version?

A. Please see http://www.starsol.co.uk/scripts/upgrading-instructions.html


Q. I am having trouble upgrading from a previous version? Can you do it for me?

A. Yes, our installation service also counts as an upgrading service. Please see http://www.starsol.co.uk/scripts/script-installation.html for more information.


Q. I want to use mod_rewrite for all URLs in the FAQ. Is this possible?

A. Yes, you may either make the appropriate modifications yourself in 'faq.php', or you may order our mod_rewrite service at http://www.starsol.co.uk/scripts/mod-rewrite.html .


Q. When using version one, I used PHP code in the header and footer, but with these now using a .txt file I can no longer do this. What is the solution?

A. Take a look at the ep() function in 'faq-functions.php'. Add relevant extras to the before and after in str_replace() part. Alternatively, let us know what modifications you would like done at http://www.starsol.co.uk/support.php and we will get back to you with a quote.

============================
ANY PROBLEMS?
============================

If you are having any problems with using this script, please contact the author using the form at:

http://www.starsol.co.uk/support.php

============================
VERSION HISTORY
============================

2.0 (26th June 2005)

 - CSS files now included for admin area and FAQ (which naturally may be edited).
 - FAQ list in admin area now includes a filter (so if you have hundreds of FAQs they don't all load up each time you view the list).
 - faq_header.php and faq_footer.php replaced with faq-template-global.txt, allowing the FAQ manager to output variable title and meta tags.
 - faq_includes.php renamed faq-functions.php. Underscores changed to hyphens in other file names.
 - Optional FAQ rating system added to allow users to rate questions/answers as helpful or not helpful. Both cookies and IP addresses are used to try to prevent multiple ratings from the same person. IP addresses and HTTP user agents may be banned in faq-functions.php.
 - Admin colour scheme and layout changed again.
 - PHP and HTML cleaned up a bit to make them more human-readable.

1.1 (7th May 2004).
 - Admin area colour scheme changed to the same green and yellow as the Starsol website.
 - FAQs can no longer be added with blank questions or answers.
 - 'View/edit settings', 'version information' and 'contact support' added to the admin area.
 - The default action for 'faq_admin.php' is now the admin area index instead of the login page - so if you don't logout you will not need to login again when you go to 'faq_admin.php'.
 - Installation code separated from 'faq_admin.php' to a new file 'faq_install.php' which makes installation easier and can be deleted after use.
 - The file 'faq_variables.php' now needs to be chmoded to 666.
 - Query string variables have been shortened in 'faq.php' (any direct links to individual questions from older versions will need to be changed).
 - MySQL error handling in 'faq.php' is now neater and will not state anything along the lines of your MySQL database name etc.
 - The Starsol credit footer in 'faq.php' has been changed to a function.

1.02 (15th January 2004)
Support for servers with register globals set as off added.

1.01 (13th January 2004)
The cookie that contains the admin password is now MD5 hashed so anyone who looks at your raw cookie files will not be able to obtain your password.

1.0 (2nd December 2003)
The first version was released for public download today after appearing in use on Starsol Scripts on 1st December.
