Unzip everything and open the admin/connect.php file. Modify username, password, and database_name
to the corresponding values on your server.

Run install.php and then delete it.
Run admin/register.php and create a username for yourself then delete register and reguser.php in the admin folder.
To change colors, allowing user submission, and number of links to display, you can modift admin/var.php 

The actual links display is on link.php 


From Non-hashed password to hashed password(Sept 28, 2003)
----------------------------------------------------------
1. Go into phpMyAdmin and find the table rl_admins table and empty it
2. Upload admin/register.php and admin/reguser.php and register yourself a new admin name
3. delete admin/regiser.php and admin.reguser.php 
4. upload the new authenticate.php
5. Its done


You may use this script on any site as long as the copyright to chipmunk scripts stays intact.