RTGuestbook ver 0.3

This is my first script; it's a very simple guestbook that requires MySql.

INSTALLATION

1. Put your mysql params and change the html template in the file config.php;
2. In the page where you want to run your script write the following line of code as show in index.php:
   <?php include("/path_to/guestbook.php"); ?> on the top of the page 
   <?php read_guestbook() ?> where you want to show your guestbook
   <?php guestbook_form() ?> where you want to show the form for signing the guestbook
3. Upload all files
4. Run from your browser install.php and set your password.
5. Delete install.php.

Ok, your script is ready, if you want to delete entries from the guestbook or change
your password, run admin.php from your browser.

If you have any comment or critic please write me(webmaster@toldo.info)