<?PHP

/*
        |: MWChat (My Web based Chat)
        |: Web\HTTP based chat application
        |:
        |: Copyright (C) 2000, 2001, 2002, 2003
        |: Distributed under the terms of license provided.
        |: Available at http://www.appindex.net
        |: Authored by Appindex.net - <support@appindex.net>
*/


/* 

   The database type setting is the type of database you wish to use with MWChat. Valid options are
   mysql, postgres, sybase, and oracle. At the time of this writing, MySQL is the only database supported.
   Future version are expected to support postgres, oracle, sybase in that order. 

*/

$CONFIG[Database_Type] = "mysql";


/* 

   This the the DNS name or the IP address of the database server. MWChat provides the best performance
   when the database is on the same physical machine as the webserver (As do most applications).
   If your database is local, use "localhost", if not, it is recomended that you use the IP of the database
   server. 

*/

$CONFIG[Database_Host] = "localhost";


/* 

   This the the port number that database listens on. MySQL doesn't require this and most will default if 
   you leave this setting empty. If you do run your database on a non-standard port, you can adjust the 
   setting below.

*/

$CONFIG[Database_Port] = "";


/* 

   This is the name of the database you have or will setup for MWChat. It is recomended that for simple
   naming conventions, you keep the name as "mwchat", but you may use what ever you like. See the README
   file in the docs directory for information about the provided database table scripts. 

*/

$CONFIG[Database_Name] = "mwchat";


/* 

   This is the database username that will connect to the MWChat database. Do NOT use your root level
   database account, as that is VERY insecure.This user will need select, insert, update, and delete 
   permissions on all tables within the MWChat database. 

*/

$CONFIG[Database_Username] = "";


/* 

   This is the database password needed for proper conectivity to the MWChat database. 

*/

$CONFIG[Database_Password] = "";


/* 

   The database encryption setting is a very powerful option. In some cases databases can 
   be setup in such a way that they log every query made to the database. If your database
   is setup that way, (possibly without your knowledge) the admin of that database could in
   read this log and see each query made to the DB, allowing them to read any and all chat 
   conversations (very rough formatting) coming to and from the database. While this is a far 
   fetched possiblity, it could happen, so to ensure the privacy of others we've added this 
   feature to encrypt\decypt the chat conversations as they come in and out of the database.

   In order to make use of this feature, you MUST have libmcrypt(http://mcrypt.hellug.gr/) 
   installed and compiled into PHP (http://www.php.net). Should you enable this feature, 
   it's possible you'll notice a performance decrease depending on webserver load.

   If you enable this feature, be sure to check the MWChat's chat_log table, to make sure it's
   working correctly and not being disabled internally.


   If you enable database encryption the javascript text send feature will automatically be disabled. 
   The javascript text send feature prevents the dialog frame from reloading each time a user enters
   text. If the security of your users messages are a concern, you should enable this feature.

   IMPORTANT NOTE:
   
   Make sure that MWChat is not in use by any users while turning this feature on/off. Doing 
   so will cause all their messages will suddenly looked garbled. You can make use of the 
   "System Live" feature in the mwchat/config/system.php file to disable the chat while 
   you make modifications.

*/

$CONFIG[Database_Encryption] = "false";

?>
