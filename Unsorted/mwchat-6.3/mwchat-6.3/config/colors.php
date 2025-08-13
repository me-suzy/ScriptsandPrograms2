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


   NOTE: ALL COLORS MUST BE IN #000000 FORMAT!
   *******************************************

   
   This is the background color for all windows including lobby, login, rooms, etc.

*/

$CONFIG[Color_Background] = "#D3D4C5";


/* 

   This is the foreground TEXT color for all windows including lobby, login, rooms, etc.   

*/

$CONFIG[Color_Foreground] = "#000000";


/* 

   This is the background color of the chat window.

*/

$CONFIG[Color_Window] = "#D3D4C5";


/* 
  
   This is the color of the users listed in your buddy list. This color applies only to BUDDIES.

*/

$CONFIG[Color_BuddyList] = "#54569C";


/* 
  
   This is the color of the regular room users on your buddy list.

*/

$CONFIG[Color_RoomList] = "#FF381D";


/* 

   This is the color of the text that appears in the information box in the upper right
   hand corner of the screen.

*/

$CONFIG[Color_StatusValues] = "#C000C0";


/* 

   This is the color of the text descriptions that appear in the information box in the upper
   right hand corner of the screen.

*/

$CONFIG[Color_StatusNames] = "#000000";


/* 

   This is the color of the other user's usernames in the actual chat window.

*/

$CONFIG[Color_RoomUsers_Other] = "#0000FF";


/* 

   This is the color of your username in the chat window.

*/

$CONFIG[Color_RoomUsers_You] = "#FF0000";


/* 

   This is the color of the system message title that are local to you or that have syntax errors.

*/

$CONFIG[Color_SystemSyntaxName] = "#4040FF";


/* 

   This is the color of the system messages text that is local to you or that have syntax errors.

*/

$CONFIG[Color_SystemSyntaxMessage] = "#800080";


/* 

   This is the color of the global system message title that all online users see.

*/

$CONFIG[Color_SystemGlobalName] = "#000000";


/* 

   This is the color of the global system message text that all online users see.

*/

$CONFIG[Color_SystemGlobalMessage] = "#C00000";


/* 

   This is the color of the system message title that all users in a given room see.

*/

$CONFIG[Color_SystemLocalName] = "#800000";


/* 

   This is the color of the system message text that all users in a given room see.

*/

$CONFIG[Color_SystemLocalMessage] = "#404040";


/* 

   This is the default text color all users will display.

*/

$CONFIG[Color_TextDefault] = "#000000";


/* 

   This is the color a username on a buddy list will change to when the user is away.

*/

$CONFIG[Color_Away] = "#999999";


/* 

    This is a list of HTML colors that the user can choose from in the chat. You can add new colors if need 
    be, but the defaults should work for almost everyone.

*/

$CONFIG[Color_Text] = array(

                             "#000000","#ffffff","#e48c0b","#dc9907","#d3a503","#c9b201","#bdbd00","#b2c901",
                             "#a5d303","#99dc07","#8ce40b","#7eec11","#71f219","#64f621","#58fa2a","#4bfc34",
                             "#40fc3f","#34fc4b","#2afa59","#21f664","#19f271","#11ec7e","#0be48c","#07dc99",
                             "#03d3a5","#01c9b2","#00bebd","#01b2c9","#03a5d3","#0799dc","#0b8ce4","#117fec",
                             "#1971f2","#2164f6","#2a58fa","#344bfc","#3f40fc","#4b34fc","#582afa","#6421f6",
                             "#7119f2","#7e11ec","#8c0be4","#9907dc","#a503d3","#b201c9","#bd00be","#c901b2",
                             "#d303a5","#dc0799","#e40b8c"

                           );


?>
