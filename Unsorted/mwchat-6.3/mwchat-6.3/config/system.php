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

   This value dictates if your MWChat system is live and available for connections. When true, any and all
   users may login and use all available features of the chat. When false, noone can login into the system.

*/

$CONFIG[System_Live] = "false";

/* 

   This is a list of administrative usernames and passwords. Users listed here are automatically given 
   operator privledges upon entrance into a chat room. Admins can unregister any user they like, they can
   also view chat logs and any other administration tasks that are available. It is a good idea to keep 
   the users "admin", "administrator", and "moderator" listed here so that basic users can't try to 
   log in as them. You need to add a password to each one below before they can be used. If you do not set
   a password, the account is disabled for everyone. If you have a custom username you would like to use, 
   you can add that here to. The format is user:password. Do not that non-alpha numeric characters will NOT
   work here.

   IMPORTANT NOTE:

   Remeber that the users you add to this are NOT registered, so if you have the registering feature enabled, 
   then it's probally a good idea to take a few moments to login as each admin user listed below and register 
   that username. Usernames are not case sensitive, while passwords are. Also, the password you provide below 
   OVERRIDES any password you set for the registered user.

   Please use *only* alpha-numeric passwords. 

*/

$CONFIG[System_Admins] = array( 

                                 "moderator:", 
                                 "admin:", 
                                 "administrator:"

                                );


/* 

   This is a list of users that will always get operator status when entering a room. Operators can set room
   topics, and kick users. This features allows you to specify operators, but without giving them full admin
   control. The same rules apply here, just as they do above. It is a good idea to keep the user "operator"
   listed here so that basic users can't try to log in as them. You need to add a password to each one below 
   before they can be used. If you do not set a password, the account is disabled for everyone. If you have 
   a custom username you would like to use, you can add that here to. The format is user:password. Do not 
   that non-alpha numeric characters will NOT work here.


   IMPORTANT NOTE:

   Remeber that the users you add to this are NOT registered, so if you have the registering feature enabled, 
   then it's probally a good idea to take a few moments to login as each operator user listed below and register 
   that username. Usernames are not case sensitive, while passwords are. Also, the password you provide below 
   OVERRIDES any password you set for the registered user.

   Please use *only* alpha-numeric passwords. 

*/

$CONFIG[System_Operators] = array( 

                                 "op:",
                                 "operator:"

                                );


/* 

   The system proxy setting if for admins running this chat behind caching servers or some types of proxies. 
   When this feature is enabled, MWChat sends pragma-nocache headers on each page. If you are unsure of weather 
   or not your behind a proxy or cache, leave this option disabled. If you notice problems with pages not updating,
   enable this feature and see if your problems are solved. 

*/

$CONFIG[System_Proxy] = "false";


/* 

   The setting enables the ability to log most of the major chat activity. When enabled, MWChat will track logins
   errors, ip addresses, end user envoirment information, etc. This feature will NOT log actual chat conversations. 
   This feature is available for troubleshooting purposes or in the event you might have a trouble maker in your 
   chat. 

*/

$CONFIG[System_Log] = "true";

?>
