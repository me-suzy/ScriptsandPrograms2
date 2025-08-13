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

   This option toggles the admin's ability to see a full list of PRIVATE rooms in the
   lobby. When enabled, and admin can see and join private rooms.

*/

$CONFIG[Room_Admin_ShowPrivate] = "false";


/* 

   This is a list of all DEFAULT ROOMS that will be available via the lobby. 

   You can optionally add a colon (:) and a default room topic for any room listed.

*/

$CONFIG[Room_Defaults] = array(
   
                                 "MWChat:The Ultimate Chat",
                                 "Flirts Nook:Flirting Welcome",
                                 "Romance and Love:Romance",
                                 "Christian Friends:Friends and Family",
                                 "The Zone:Get in the ZONE",
                                 "TV",
                                 "Music",
                                 "Movies",
                                 "News",
                                 "Travel",
                                 "Family and Home",
                                 "Health and Wellness",
                                 "International",
                                 "Lifestyles",
                                 "Workplace",
                                 "The Abyss"

                              );


/* 

   This option when enabled, allows users to choose a pre-defined public room or create thier
   own public room. When set to false, users can only choose from pre-defined rooms listed above. 


   IMPORTANT NOTE:

   If this option is disabled (false), and all pre-defined rooms are full, users will not be able
   to login to the chat system.

*/

$CONFIG[Room_MultiplePublic] = "true";


/* 

   This option when enabled, allows users to enter private rooms or create private rooms. 

*/

$CONFIG[Room_MultiplePrivate] = "true";


/* 

   This option when enabled, will only allow users to enter private rooms or create thier
   own private room. No public rooms are available for the user to choose from.

   IMPORTANT NOTE:

   IF you enable this option, it WILL override the public room settings above.

*/

$CONFIG[Room_PrivateOnly] = "false";


/* 
   
   This option sets the maximum number of users that may be allowed into a single room.

   It's possible that allowing to many users into a single room may decrease system performance.

   It is recommenede that you leave this setting as is.

*/

$CONFIG[Room_Visitors] = "20";


/* 

   The room expire settings are very important. These times you specify dictate how long to keep 
   an "idle" login in the database. First, an idle login is a login that does not have any activity 
   for a period of time. The chat is stateless, meaning that we have no idea when a user just isnt 
   typing and has the browser window still open, and when a user just closed there browser without 
   clicking the logout button. Either way they will still appear as logged in. So say another user 
   wishes to login as that same user who logged in yesterday, they can't because we can't tell if
   the user logged out or if he's just not typing. They way to track this is to keep a timestamp 
   associated with the username. Then each time the users says something, we update the timestamp. 
   We then can do a time comparison on the timestamp and see when the last time they chatted was. 

   For example, lets set the settings to:

     $CONFIG[Chat_Rooms_Expire_Hours]    = "1";
     $CONFIG[Chat_Rooms_Expire_Minutes]  = "15";
     $CONFIG[Chat_Rooms_Expire_Seconds]  = "5";

   Given these settings if user joe was logged into the chat but did not say anything for 1 hour 15 
   minutes and 5 seconds, he would be logged out. OR if the user just closed there browser window 
   without clicking logout, no other users will be able to login using the username joe untill the
   current timestamp expires. In that case it's 1 hour, 15 minutes and 5 seconds.

   The default values provided below are probally good. But you may adjust these as needed.

*/

$CONFIG[Room_Expire_Hours]    = "0";
$CONFIG[Room_Expire_Minutes]  = "10";
$CONFIG[Room_Expire_Seconds]  = "0";
   
?>
