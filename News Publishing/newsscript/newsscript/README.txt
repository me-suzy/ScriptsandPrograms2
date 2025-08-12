NEWS SCRIPT
Written by Erlend Berge
erlend.berge@student.uib.no

Tested with IE 6.0, Apache and PHP 4.01

This is my first real PHP-project, so don't complain!!! ;)

Installation:
1.Upload to server

2. CHMOD (to 777)
brukere.dat
headlines.dat
katdat.dat
katdata.dat
news.dat
news   (directory)
pictures   (subdirectory in "news")

THAT'S IT! Now login as admin, password admin, then add another user and delete user:admin (make sure that the new user is admin)


OPTIONAL:
Most of the settings are changeable in "settings.php"
In "settings.php" you can also translate the system into another language, but you have to go through html.php and login.php and translate some here also...
(for a norwegian version, send me an email)

WARNING: You cannot change $ns_user_normal and $ns_user_admin. If you do so, I can not guarantee that the system will work. I will fix this in another version.

Where is the news located?
In the news directory, each category has its own file. For example, the category sports has a file called sports.php This will show the introduction to all the sports news.

You can see the script in action at www.auf.no/hordaland   (it's in norwegian)