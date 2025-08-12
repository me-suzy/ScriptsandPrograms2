<?php

include("atom_db.class.php");
include("db/config.php");
$install = new sdb();

$date = date("dS F, Y");

if (isset($_POST["a"])){


//create db file

if (!file_exists($_POST["file"])){
    echo ("
<table border=\"1\" cellspacing=\"1\" bgcolor=\"#000000\" bordercolor=\"#FFFFFF\" cellpadding=\"3\" width=\"550\" align=\"center\">
   <tr bgcolor=\"#224466\">
      <td width=\"100%\">
          <center><font size=\"2\" face=\"Verdana\" color=\"#6699CC\"><b>Initializing Instalation Process...</b><br />Created a DB File</font></center>
      </td>
   </tr>
</table>");


$install->createDB($_POST["file"], $_POST["dbuser"], $_POST["dbpass"], $_POST["dbname"], $_POST["dbmail"]); //create the dbfile
$install->selectDB($_POST["file"], $_POST["dbuser"], $_POST["dbpass"]); //select the dbfile
$install->createTable("cats", array("name", "date_added", "primarykey")); //add db cats
$install->addRow("cats", array("Discussion", $date, "0")); //add catagory Discussion to the db cats

$install->createTable("alerts", array("User/IP", "Date Started", "Message")); //add db filter
$install->createTable("whos_online", array("username", "ipaddress", "referer", "minute")); //add db filter
$install->createTable("filter", array("bad_word", "word_new")); //add db filter
$install->createTable("forums", array("name", "sub_name", "topic_count", "post_count", "Moderators", "cat_id", "link_to_forum", "primarykey", "view_group", "post_group", "reply_group")); //add db forums
$install->addRow("forums", array("General Discussion", "", "0", "0", "", "0", "", "0", "guests", "members", "members")); //add forum General Discussion to forums db

$install->createTable("users", array("username", "password", "e-mail", "power", "posts", "avatar", "alias", "website", "bonusstatus", "AOL", "Aim", "ICQ", "MSN", "Yahoo!", "XFire")); //add db users
$install->addRow("users", array("Admin", "admin", "noreply@admin.com", "1", "0", "avatars/admin.gif", "Administrator", "", "", "", "", "", "", "", "")); //add user admin to the users db

$install->createTable("livechat", array("from", "time", "message"));
$install->createTable("password", array("forum_id", "password"));
$install->createTable("topics", array("icon", "subject", "poster", "date", "message", "forum_id", "locked", "primarykey", "sticky")); //add db topics
$install->createTable("posts", array("icon", "subject", "poster", "date", "message", "topic_primarykey")); //add db posts
$install->createTable("postcount", array("posts", "alias")); //add db key
$install->createTable("key", array("2")); //add db key
$install->addRow("key", array("1")); //add a key
$install->reBuild(true); //save all the tables.



echo ("<meta http-equiv=\"refresh\" content=\"2;url=index.php\">");

} else {
    echo ("
<table border=\"1\" cellspacing=\"1\" bgcolor=\"#000000\" bordercolor=\"#FFFFFF\" cellpadding=\"3\" width=\"550\" align=\"center\">
   <tr bgcolor=\"#224466\">
      <td width=\"100%\">
          <center><font size=\"2\" face=\"Verdana\" color=\"#6699CC\"><b>Initializing Instalation Process...</b><br />Error: Database already exists. Aborting...</font></center>
      </td>
   </tr>
</table>");
}; //end if file_exists
}; //end isset

echo ("<form method=\"post\" action=\"\">
<table border=\"1\" cellspacing=\"1\" bgcolor=\"#000000\" bordercolor=\"#FFFFFF\" cellpadding=\"3\" width=\"550\" align=\"center\">
   <tr bgcolor=\"#BED4EF\">
      <td width=\"100%\">
          <center><font size=\"2\" face=\"Verdana\"><b>Forum Instalation Process</b></font></center>
      </td>
   </tr>
   <tr bgcolor=\"#DDDDDD\">
      <td width=\"100%\"><font size=\"1\" face=\"Verdana\">If you do not have a database already created then you should create one
                        here now. enter the required details and this install will do the work. (The information already filled in has been taken from the config file so you can use the forum without any further haslte)<br />
                        <b>The DB username and DB password MUST correspond with the UN and PW in the config.php file</b><br /><br /></font>

         <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" align=\"center\">
            <tr>
               <td width=\"20%\"><font size=\"1\" face=\"Verdana\"><b>DB File</b></font></td>
               <td width=\"30%\"><input type=\"text\" name=\"file\" value=\"$dbfile\"></td>
               <td width=\"50%\"><font size=\"1\" face=\"Verdana\">This DBFile will create your DBFile</font></td>
            </tr>
            <tr>
               <td width=\"20%\"><font size=\"1\" face=\"Verdana\"><b>DB Username</b></font></td>
               <td width=\"30%\"><input type=\"text\" name=\"dbuser\" value=\"$dbuser\"></td>
               <td width=\"50%\"><font size=\"1\" face=\"Verdana\">Desired Username for the DBFile</font></td>
            </tr>
            <tr>
               <td width=\"20%\"><font size=\"1\" face=\"Verdana\"><b>DB Password</b></font></td>
               <td width=\"30%\"><input type=\"password\" name=\"dbpass\" value=\"$dbpass\"></td>
               <td width=\"50%\"><font size=\"1\" face=\"Verdana\">Desired Password for the DBFile</font></td>
            </tr>
            <tr>
               <td width=\"20%\"><font size=\"1\" face=\"Verdana\"><b>DB Alias Name</b></font></td>
               <td width=\"30%\"><input type=\"text\" name=\"dbname\"></td>
               <td width=\"50%\"><font size=\"1\" face=\"Verdana\">Desired Name (owner) for the DBFile</font></td>
            </tr>
            <tr>
               <td width=\"20%\"><font size=\"1\" face=\"Verdana\"><b>DB Alias E-Mail</b></font></td>
               <td width=\"30%\"><input type=\"text\" name=\"dbmail\"></td>
               <td width=\"50%\"><font size=\"1\" face=\"Verdana\">Desired E-mail for the DBFile</font></td>
            </tr>
         </table>

      </td>
   </tr>
   <tr bgcolor=\"#CCCCCC\">
      <td width=\"100%\"><font size=\"1\" face=\"Verdana\">Now you have filled out the above, go back and doubble-check
                                                          they are what they should be, if they are correct then do press the button below to continue<br />
                                                          <font color=\"#BB0000\"><b>If this is your first time running install just press the button</b></font><br /><br /></font>

         <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" align=\"center\">
            <tr>
               <td width=\"20%\"></td>
               <td width=\"30%\"></td>
               <td width=\"50%\" align=\"right\"><input type=\"submit\" name=\"a\" value=\"Install Now\"></td>
            </tr>
         </table>

      </td>
   </tr>
</table>
</form>");

?>
