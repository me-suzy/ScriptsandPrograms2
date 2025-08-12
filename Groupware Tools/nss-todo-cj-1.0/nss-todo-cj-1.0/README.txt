NSS-TODO-CJ 1.0 18:17 12/09/2005
=============================
http://poff.sixbit.org

INSTALLATION
============

-Unzip files to your todo directory.
-Make sure todo.list and categories.list are writeable by your web server (chmod 777)
-Open and modify settings.inc to suit your needs
-Add a cron job for the execution of "cron.php" once per day eg:

10 5 * * *	/path/to/php4/executable -f /path/to/todo/list/cron.php

TIPS
====

-You can change the visibility of all those extra options in settings.inc
-You can modify a TODO item once you've added it by clicking to the left of the checkbox for that item
-You can remove a category by clicking on it's name on the front page
-You can rename/recolour a category by simply adding it again on the front page - you will be asked how you wish to proceed after that

HELP
====

http://poff.sixbit.org/discuss