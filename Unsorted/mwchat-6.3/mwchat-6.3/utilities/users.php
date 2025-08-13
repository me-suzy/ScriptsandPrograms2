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

$CONFIG[MWCHAT_Root] = "/path/to/mwchat/root/directory";

require_once("$CONFIG[MWCHAT_Root]/config/database.php");

if (!is_callable(db_connect))
{

  require_once("$CONFIG[MWCHAT_Root]/libs/db/$CONFIG[Database_Type].php");

}

require_once("$CONFIG[MWCHAT_Root]/libs/db_open.php");

$Select = db_query("SELECT id from chat_users", $CONN);
$szUsers = db_numrows($Select);

echo "There are currently $szUsers user(s) chatting online.";
