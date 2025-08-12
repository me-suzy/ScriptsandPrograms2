##########################
# qliteNews - Quick and Lite News
# Website: www.r2xdesign.net
# Support: www.r2xdesign.net/forums
##########################

1. Open admin folder which is inside the qlitenews folder and edit config.php file.

2. Upload the qlitenews folder in your site MAIN directory.

3. Go to www.yourwebsite.com/qlitenews/install/install.php
   - This will automatically create the the tables for you.
   - You can now delete the install folder.

4. To access your admin panel go to: www.yourwebsite.com/qlitenews/admin
   - The default username and password is "admin" (without the quotes).
   - This is where you can post,edit,delete news etc...

   IMPORTANT: Change the default username and password right away by going to "Options" section.

5. To include the news in your website use the php code below:
 
    <?php include("qlitenews/normalview.php"); ?>

GOOD LUCK!
