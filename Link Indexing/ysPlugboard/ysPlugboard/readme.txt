YsPlugboard Installation


1.
Go to your website's control panel, create a MySQL database and a user and ensure the user
is given permission to the database.


2.
Edit plug_settings.php and enter your mysql database user name, password and database name.  Do not 
delete the ""'s.  You can also change the admin panel password, number of buttons to be shown and 
the height/width of buttons.


3.
Upload all files (plug.php, plugboard.php, plug_admin.php, plug_settings.php and install.php) to your 
server.


4.
Launch install.php (eg. http://www.yoursite.com/install.php)


5.
If you get confirmation that the plugboard is set up, you can delete install.php and start using your 
plugboard!  Just go to plugboard.php.
If you get any errors, check your database username/password/database name/host in plug_settings.php


6.
To put your plugboard on your page, simply add this line of code to wherever you want it:
<?php include("plugboard.php"); ?>
Note the page must be in php format (ie .php not .html)
You can edit the look and setup of the plugboard by editing plugboard.php, this is where you can
change the iframe size and add any text formatting.  
If you want to have more buttons in each row, make the iframe wider.


7.
You can manage buttons and ban ips by going to http://www.yoursite.com/plug_admin.php and enter the
password that you have set in plug_settings.php.  


Please visit http://www.yoursite.nu/mbforum.php?id=8 if you have any trouble that you can't figure out.