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

   This option blocks certain words from appearing in the chat room by vulgar users. This option is
   very useful for chats in use my minors or children. When enabled (set to false), any words flagged 
   as profanity are replaced with a [frustrated] smily face. If you have logging enabled, and entry 
   will also be recorded that a user used profanity.

*/

$CONFIG[Chat_Profanity] = "false";


/* 
  
   This is a list of words to consider as profanity. All words listed here are replaced as users chat in the room.
   A default list of words is already provided for you, and will probally work well in most cases. Remember that 
   by blocking the word 'ass' you will also block 'assasin'. To fix this incorrect match, use 'ass ' with a trailing
   space. 

*/

$CONFIG[Chat_Profanity_Words] = array(
                                          "fuck", 
                                          "bitch", 
                                          "whore", 
                                          "pussy", 
                                          "dick", 
                                          "cock", 
                                          "cunt", 
                                          "damn ", 
                                          "dammit", 
                                          "shit", 
                                          " ass ", 
                                          "penis"
                                     );


/* 

   This option allows an admin to login to the chat at anytime. Under normal operation, if a username is in use, or if a user idle's
   out, that same username will be unavailable for use untill thier timestamp expires. With this option enabled, administrators
   listed in the mwchat/config/system.php will be able to login at anytime, booting any users currently logged in with that name,
   or automatically expiring the existing timestamp. 
 
*/

$CONFIG[Chat_Admin_Force] = "true";


/* 

   This option sets the number of queries to issue before a window refresh. This setting is best left untouched and can increase
   database load if it is set to low. If you do notice problems with the chat window stopping abnormally, try gradually lowering
   this number.
 
*/

$CONFIG[Chat_Window_Timeout] = "60";


/* 

   This option tells mwchat when to refresh the MWChat window. The recommeneded value for this entry is 300 seconds, or 5 minutes.
   If you experience problems with the chat window "stalling" try setting this number down. The default value is probally correct
   for most cases. The value below MUST be in seconds. 

*/

$CONFIG[Chat_Refresh_Time] = "300";


/* 

   This option toggles the users ability to see and make use of graphical smily faces in the chat room. If your users are on VERY 
   low bandwidth lines, you may want to disable graphical smiles and use plain text ones in place.

*/

$CONFIG[Chat_Smiles] = "true";


/* 

   The auto operator setting automatically ops users based on senority. When set to true, the first person to arrive
   in any room is automatically opped. Should that person leave the room, the second person that arrived into
   that room is opped and so on. Much like IRC operators. Any administrators listed in the admin section of 
   mwchat/config/system.php will ALWAYS get operator privs when entering a room. 

*/

$CONFIG[Chat_Operator] = "true";


/* 

   The motd setting displays the message of the day (MOTD) each time a user enters a chat room. This option is commonly 
   used for instructional or informational purposes. The MOTD file is located in the mwchat/config directory.
   
   IMPORTANT NOTE:

   If you disabled this option (false) users cannot use the /motd command.

*/

$CONFIG[Chat_MOTD] = "true";


/* 

   The kick operator setting specifies if room operators can use the /kick command to kick off another operator.
   When set to true, operators can kick other operators.  Becareful not to give end users to much power. 

*/

$CONFIG[Chat_Operator_Kill] = "false";



/* 

   This option enables the users ability to send and receive files. When enabled, users on the chat system may send
   and receive files from other users. If you do not wish to share this ability with all users, disable it here. 

   IMPORTANT NOTE: 

   This option requires an MWChat PRO license to function correctly. You may get errors if you have this enabled
   and do not have a pro license.

*/

$CONFIG[Chat_Upload] = "false";

/* 

   This is the total ammount of storage space each user gets for thier files. This value is in bytes
   and the default is 5 megs of disk space.

*/

$CONFIG[Chat_Upload_Limit] = "5000000";


/* 

   This option gives users the ability to register thier usernames. This is useful for regular chatters
   who always prefer to use the same name. This feature also gives the user the ability to set a password
   to protect thier account. Registers users have extra chatting abilities like file sharing, profiles,
   custom preferences, buddy lists, personal greetings, and more. All of the features listed above are NOT
   available if this option is not enabled. 

   IMPORTANT NOTE: 

   This option requires an MWChat PRO license to function correctly. This option also provides end users with
   many extra chat advantages. You may get errors if you have this enabled and do not have a pro license.

*/

$CONFIG[Chat_Register_Allow] = "false";

?>
