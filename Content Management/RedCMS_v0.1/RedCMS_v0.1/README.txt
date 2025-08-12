This script was written by Sam "Rederovski"

email: sam@rederovski.co.uk
url: www.rederovski.co.uk | www.redcms.co.uk

GNU General Public License details http://www.gnu.org/copyleft/gpl.html

This means that you can customize your website as much as you want, as
long as you keep a link to http://redcms.co.uk on every page.

Version: v0.1

Desc: RedCMS is a Content Management Script, see the site: 
(www.redcms.co.uk) for more info.

#-------------------------------------------------------------------------
# SETUP.
#-------------------------------------------------------------------------
 
1. Upload all of the files.
2. CHMOD config.php, setup.php and redStyles to 777.
3. Go to http://yourdomain.co.uk/install.php and follow the instructions.
4. Delete install.php.
5. Modify top.php and bottom.php to acomadate your website theme.

#-------------------------------------------------------------------------
# MANUAL
#-------------------------------------------------------------------------

Contents:
  1. To secure a page.
  2. Displaying the login form on other pages.

#-------------------------------------------------------------------------
# 1. TO SECURE A PAGE.
#-------------------------------------------------------------------------

To restrict users from viewing pages you have 3 main user levels, 
registered (1 or 0), medium (5) and admin (10). You can add more levels by
going on the levels page in the admin section of your website.

If you want to make sure that the user is logged in before they view a page
then at the top of the page add:

<?php

  isLoggedIn();

?>

If the user isnt logged in then a message will be displayed telling them to
login, and they will be redirected to the login page.

To restrict access by user levels at the top of the page you want to 
protect add:

<?php

  access(5);

?>

to allow only users with level 5 access or higher to view the page OR

<?php

  access(10);

?>

... to allow users with level 10 access or higher to view the page.

#-------------------------------------------------------------------------
# 2. DISPLAYING THE LOGIN FORM ON OTHER PAGES.
#-------------------------------------------------------------------------

Make sure that top.php is included on the page. Then add:

<?php loginForm(); ?>

Which will display the form and allow users to login.

#-------------------------------------------------------------------------
# BUGS / PROBLEMS / SUGGESTIONS.
#-------------------------------------------------------------------------

Please use the forums at http://redcms.co.uk to ask questions, if you have
any problems please contact:

sam@rederovski.co.uk

#-------------------------------------------------------------------------
# CHANGELOG:
#-------------------------------------------------------------------------