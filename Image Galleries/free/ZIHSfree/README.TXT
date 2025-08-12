Installation Instructions

1)  Upload everything in the "upload" directory onto your server

2)  Change the nextimid.log file permissions to 755.

3)  Chmod the "uploads" folder (careful not to mix up with the "upload" folder) to writable.

4)  Install the Database.sql file.

5)  Edit config.php with the appropriate Database settings (Username, Host, Password, Database)

6)  Edit settings.php variables to change output on site (Site Title, Description, Max Upload File Size)

7)  Edit add_admin.php and change  $email="your email address"; and password = "your password";

8) Run add_admin.php to create a user with your email and password that has Admin Privlidges.  
Then delete this file off of the server because once the first Admin is created, then you can add any number of users.

9) Chomd the function.php file to 755

10) Change faq.php to your liking

Smarty Template is pretty flexible.  Feel free to email me and I will show you how to change the template.