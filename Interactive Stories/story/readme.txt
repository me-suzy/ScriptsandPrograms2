Read for Chipmunk stories 1.0

Note: You can use this script and modify it in any way as long as you do not take off the copyright link to chipmunk scripts

Edit admin/connect.php and then upload everything besides this readme file
Run admin/install.php and then delete install.php
Run admin/register.php and register an admin name, then delete admin/register and admin/reguser
The login to the admin panel is at admin/login.php
There is a bad words filter function in index.php, to add words to the filter just add according to the two examples shown there.


From Non-hashed password to hashed password(Sept 28, 2003)
----------------------------------------------------------
1. Go into phpMyAdmin and find the table s_logintable table and empty it also the password field from length 30 to length 255
2. Upload admin/register.php and admin/reguser.php and register yourself a new admin name
3. delete admin/regiser.php and admin.reguser.php 
4. upload the new authenticate.php
5. Its done

If you have any questions about this script please post in the support forums at http://www.chipmunk-scripts.com/board/