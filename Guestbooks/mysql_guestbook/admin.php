<?php
require_once("config.php");
if ( !file_exists($config["admintpl"]) )
{
   die( "The file ".$config["admintpl"]." does not exists." );
}

if( !isset( $_COOKIE['SID'] ) )
{
    $sid = md5( uniqid( rand(), true ) );
    setcookie( "SID", $sid, time()+3600 );
}

function check_loggedin( $sessionid, $ip, $table )
{
    $check = mysql_query( "SELECT * FROM `".$table."` WHERE `session` = '".make_query_safe( $sessionid )."' AND `ip` = '".make_query_safe( $ip )."'" ) or die( "ERROR: Cannot query database." );
   if( mysql_num_rows($check) === 1 )
   {
       return true;
   }
   return false;
}

if( $config["newestfirst"] === "yes" ) {
   $order = " ORDER BY `ID` DESC ";
}
else
{
   $order = " ORDER BY `ID` ASC ";
}

$homelink = $_SERVER['PHP_SELF']."?action=home";
$veiwlink = $_SERVER['PHP_SELF']."?action=veiw";
$editlink = $_SERVER['PHP_SELF']."?action=edit";
$dellink = $_SERVER['PHP_SELF']."?action=del";
$banlink = $_SERVER['PHP_SELF']."?action=ban";
$helplink = "http://www.free-php.org.uk/help/?guestbook";

if( !isset( $_GET['action'] ) || $_GET['action'] === "home" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      $title = "Guestbook admin area";
      $subtitle = "Welocome";
      $text = "Thank you for using our guestbook script. We hope you like it.<br />";
      $text .= "If you can think of anything that would be good to have in this script please email us at admin@free-php.org.uk.\n";
   }
   else
   {
      $title = "Welcome to your guestbook admin area";
      $subtitle = "Login";
      $text = "</p><form action=\"".$_SERVER['PHP_SELF']."\" method=\"GET\"><p>\n";
      $text .= "<input type=\"hidden\" name=\"action\" value=\"checklogin\" />\n";
      $text .= "Username: <input type=\"text\" name=\"user\" size=\"20\" /><br />\n";
      $text .= "Password: <input type=\"text\" name=\"pass\" size=\"20\" /><br />\n";
      $text .= "<input type=\"Submit\" value=\"Login\" />\n";
      $text .= "</p></form><p>\n";
   }
}
elseif ( $_GET['action'] === "checklogin" )
{
   $query = mysql_query( "SELECT * FROM `".$config["mysqllogintable"]."` WHERE `username` = '".make_query_safe( $_GET['user'] )."'" ) or die( "ERROR: Cannot query database." );
   if( mysql_num_rows($query) != 1 )
   {
      $title = "Guestbook admin area";
      $subtitle = "Login";
      $text = "Sorry wrong username and/or password.";
   }
   else
   {
      $user = mysql_fetch_array($query, MYSQL_ASSOC);
      if( $user['password'] === $_GET['pass'] )
      {
         if( !isset( $_COOKIE['SID'] ) )
         {
             $title = "Guestbook admin area";
             $subtitle = "Login";
             $text = "Sorry it looks like cookies are not enabled.";
         }
         else
         {
             mysql_query( "UPDATE `".$config['mysqllogintable']."` SET `session` = '".make_query_safe( $_COOKIE['SID'] )."', `ip` = '".make_query_safe( getenv( 'REMOTE_ADDR' ) )."' WHERE `id` = ".$user['id']." LIMIT 1 " ) or die( "ERROR: Cannot query database." );
             $title = "Guestbook admin area";
             $subtitle = "Login";
             $text = "You are now logged in.";
         }
      }
      else
      {
         $title = "Guestbook admin area";
         $subtitle = "Login";
         $text = "Sorry wrong username and/or password.";
      }
   }
}
elseif ( $_GET['action'] === "veiw" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      if( !isset($_GET['num']) || !is_numeric($_GET['num']) )
      {
          $start = 0;
          $end = $config["numpostsperpage"];
      }
      else
      {
         $start = $_GET['num'] * $config["numpostsperpage"];
         $end = $start + $config["numpostsperpage"];
      }
      $title = "Guestbook admin area";
      $subtitle = "Veiw Posts";
      $query = mysql_query( "SELECT * FROM ".$config["mysqlguestbooktable"]."".$order."LIMIT ".$start.",".$end."" );
      $text = "<hr />";
      $isresults = FALSE;
      $i = 0;
      while( $posts = mysql_fetch_array( $query, MYSQL_ASSOC ) )
      {
         $text .= "<br />ID: ".$posts["ID"]."<br />\n";
         $text .= "NAME: ".$posts["NAME"]."<br />\n";
         $text .= "EMAIL OR URL: ".$posts["EMAILORURL"]."<br />\n";
         $text .= "COMMENTS: ".$posts["COMMENTS"]."<br />\n";
         $text .= "DATE: ".$posts["DATE"]."<br />\n";
         $text .= "IP: ".$posts["IP"]."<br /><br /><hr />\n";
         $i++;
      }
      $text .= "<br /><br />";
      if( $i >= $config["numpostsperpage"] )
      {
         $isresults = TRUE;
      }
      if( $start >= $config["numpostsperpage"] )
      {
         $prev = ($start / $config["numpostsperpage"]) - 1;
         $text .= "<a href=\"".$veiwlink."&num=".$prev."\">Prev.</a> ";
      }
      if( $isresults )
      {
         $next = ($start / $config["numpostsperpage"]) + 1;
         $text .= " <a href=\"".$veiwlink."&num=".$next."\">Next</a>\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "edit" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      if( !isset($_GET['num']) || !is_numeric($_GET['num']) )
      {
          $start = 0;
          $end = $config["numpostsperpage"];
      }
      else
      {
         $start = $_GET['num'] * $config["numpostsperpage"];
         $end = $start + $config["numpostsperpage"];
      }
      $title = "Guestbook admin area";
      $subtitle = "Edit Posts";
      $query = mysql_query( "SELECT * FROM ".$config["mysqlguestbooktable"]."".$order."LIMIT ".$start.",".$end."" );
      $text = "<hr />";
      $isresults = FALSE;
      $i = 0;
      while( $posts = mysql_fetch_array( $query, MYSQL_ASSOC ) )
      {
         $text .= "<br />ID: ".$posts["ID"]."<br />\n";
         $text .= "NAME: ".$posts["NAME"]."<br />\n";
         $text .= "EMAIL OR URL: ".$posts["EMAILORURL"]."<br />\n";
         $text .= "COMMENTS: ".$posts["COMMENTS"]."<br />\n";
         $text .= "DATE: ".$posts["DATE"]."<br />\n";
         $text .= "IP: ".$posts["IP"]."<br />\n";
         $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=doedit&id=".$posts["ID"]."\">Edit this post.</a><br /><br /><hr />\n";
         $i++;
      }
      $text .= "<br /><br />";
      if( $i >= $config["numpostsperpage"] )
      {
         $isresults = TRUE;
      }
      if( $start >= $config["numpostsperpage"] )
      {
         $prev = ($start / $config["numpostsperpage"]) - 1;
         $text .= "<a href=\"".$editlink."&num=".$prev."\">Prev.</a> ";
      }
      if( $isresults )
      {
         $next = ($start / $config["numpostsperpage"]) + 1;
         $text .= " <a href=\"".$editlink."&num=".$next."\">Next</a>\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "doedit" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      if( !isset( $_GET['id'] ) || empty( $_GET['id'] ) )
      {
         $title = "Guestbook admin area";
         $subtitle = "Error";
         $text = "Sorry there was an error trying to edit that post.";
      }
      else
      {
         $query = mysql_query( "SELECT * FROM ".$config["mysqlguestbooktable"]."".$order."WHERE `id` = '".make_query_safe( $_GET['id'] )."' LIMIT 1" );
         $post = mysql_fetch_array( $query, MYSQL_ASSOC );
         $title = "Guestbook admin area";
         $subtitle = "Edit post";
         $text = "</p><form action=\"".$_SERVER['PHP_SELF']."?action=finedit\" method=\"POST\"><p>\n";
         $text .= "<input type=\"hidden\" name=\"id\" value=\"".$post['ID']."\" />\n";
         $text .= "Name:<br /><input type=\"text\" name=\"name\" size=\"20\" value=\"".$post['NAME']."\" /><br /><br />\n";
         $text .= "Email or URL:<br /><input type=\"text\" name=\"emailorurl\" size=\"20\" value=\"".$post['EMAILORURL']."\" /><br /><br />\n";
         $text .= "Comments:<br /><textarea rows=\"5\" cols=\"20\" name=\"comments\">".$post['COMMENTS']."</textarea><br /><br />\n";
         $text .= "Date:<br /><input type=\"text\" name=\"date\" size=\"20\" value=\"".$post['DATE']."\" /><br /><br />\n";
         $text .= "IP:<br /><input type=\"text\" name=\"ip\" size=\"20\" value=\"".$post['IP']."\" /><br /><br />\n";
         $text .= "<input type=\"Submit\" value=\"Save Edit\" />\n";
         $text .= "</p></form><p>\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "finedit" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      mysql_query( "UPDATE `".$config["mysqlguestbooktable"]."` SET `NAME` = '".make_query_safe( $_POST['name'] )."', `DATE` = '".make_query_safe( $_POST['date'] )."', `EMAILORURL` = '".make_query_safe( $_POST['emailorurl'] )."', `COMMENTS` = '".make_query_safe( $_POST['comments'] )."', `IP` = '".make_query_safe( $_POST['ip'] )."' WHERE `id` = ".make_query_safe( $_POST['id'] )." LIMIT 1 " ) or die( "ERROR: Cannot query database." );
      $title = "Guestbook admin area";
      $subtitle = "Edited";
      $text = "That post was edited.";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "del" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      if( !isset($_GET['num']) || !is_numeric($_GET['num']) )
      {
          $start = 0;
          $end = $config["numpostsperpage"];
      }
      else
      {
         $start = $_GET['num'] * $config["numpostsperpage"];
         $end = $start + $config["numpostsperpage"];
      }
      $title = "Guestbook admin area";
      $subtitle = "Delete Posts";
      $query = mysql_query( "SELECT * FROM ".$config["mysqlguestbooktable"]."".$order."LIMIT ".$start.",".$end."" );
      $text = "<hr />";
      $isresults = FALSE;
      $i = 0;
      while( $posts = mysql_fetch_array( $query, MYSQL_ASSOC ) )
      {
         $text .= "<br />ID: ".$posts["ID"]."<br />\n";
         $text .= "NAME: ".$posts["NAME"]."<br />\n";
         $text .= "EMAIL OR URL: ".$posts["EMAILORURL"]."<br />\n";
         $text .= "COMMENTS: ".$posts["COMMENTS"]."<br />\n";
         $text .= "DATE: ".$posts["DATE"]."<br />\n";
         $text .= "IP: ".$posts["IP"]."<br />\n";
         $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=dodel&id=".$posts["ID"]."\">Delete this post.</a><br /><br /><hr />\n";
         $i++;
      }
      $text .= "<br /><br />";
      if( $i >= $config["numpostsperpage"] )
      {
         $isresults = TRUE;
      }
      if( $start >= $config["numpostsperpage"] )
      {
         $prev = ($start / $config["numpostsperpage"]) - 1;
         $text .= "<a href=\"".$dellink."&num=".$prev."\">Prev.</a> ";
      }
      if( $isresults )
      {
         $next = ($start / $config["numpostsperpage"]) + 1;
         $text .= " <a href=\"".$dellink."&num=".$next."\">Next</a>\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "dodel" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      mysql_query( "DELETE FROM `".$config["mysqlguestbooktable"]."` WHERE `ID` = ".make_query_safe( $_GET['id'] )." LIMIT 1 " ) or die( "ERROR: Cannot query database." );
      $title = "Guestbook admin area";
      $subtitle = "Delete";
      $text = "That post was deleted.";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "ban" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      $title = "Guestbook admin area";
      $subtitle = "Ban stuff";
      $text = "<a href=\"".$_SERVER['PHP_SELF']."?action=banip\">Ban IP</a> \n";
      $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=banword\">Ban Word</a> \n";
      $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=unbanip\">Unban IP</a> \n";
      $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=unbanword\">Unban Word</a> \n";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "banip" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      $title = "Guestbook admin area";
      $subtitle = "Ban IP";
      $text = "</p><form action=\"".$_SERVER['PHP_SELF']."\" method=\"GET\"><p>\n";
      $text .= "<input type=\"hidden\" name=\"action\" value=\"dobanip\" />\n";
      $text .= "IP to ban: <input type=\"text\" name=\"IP\" size=\"20\" /><br />\n";
      $text .= "<input type=\"Submit\" value=\"BAN!!!\" />\n";
      $text .= "</p></form><p>\n";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
/*
Well here's a joke for you (not mine)

An elderly man was headed home in his car one evening, swerving and weaving on the road, when he was stopped by a policeman.
"Have you been drinking tonight, sir?" the policeman asked.
"Well, I may have had a pint or two." The man replied, smiling. "Why do you ask?"
"Sir, your wife fell out of the car about a mile back."
"Oh, thank goodness," the man exclaimed. "I thought I'd gone deaf!"
*/
elseif ( $_GET['action'] === "dobanip" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      if( !isset( $_GET['IP'] ) || empty( $_GET['IP'] ) )
      {
         $title = "Guestbook admin area";
         $subtitle = "Ban IP Error";
         $text = "Cannot get the IP you want to ban.\n";
      }
      else
      {
         mysql_query( "INSERT INTO `".$config["mysqlbantable"]."` ( `WORD` , `REPLACEMENT` , `IP` ) VALUES ( '', '', '".make_query_safe( $_GET['IP'] )."' )" ) or die( "ERROR: Cannot query database." );
         $title = "Guestbook admin area";
         $subtitle = "Ban IP";
         $text = "That IP has now been baned from posting.\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "banword" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      $title = "Guestbook admin area";
      $subtitle = "Ban Word";
      $text = "</p><form action=\"".$_SERVER['PHP_SELF']."\" method=\"GET\"><p>\n";
      $text .= "<input type=\"hidden\" name=\"action\" value=\"dobanword\" />\n";
      $text .= "Word to ban:<br /><input type=\"text\" name=\"word\" size=\"20\" /><br /><br />\n";
      $text .= "Replacment:<br /><input type=\"text\" name=\"replacement\" size=\"20\" /><br /><br />\n";
      $text .= "<input type=\"Submit\" value=\"BAN!!!\" />\n";
      $text .= "</p></form><p>\n";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "dobanword" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      if( !isset( $_GET['word'] ) || empty( $_GET['word'] ) || !isset( $_GET['replacement'] ) || empty( $_GET['replacement'] ) )
      {
         $title = "Guestbook admin area";
         $subtitle = "Ban Word Error";
         $text = "Cannot get the word and/or replacement you want to ban.\n";
      }
      else
      {
         mysql_query( "INSERT INTO `".$config["mysqlbantable"]."` ( `WORD` , `REPLACEMENT` , `IP` ) VALUES ( '".make_query_safe( $_GET['word'] )."', '".make_query_safe( $_GET['replacement'] )."', '' )" ) or die( "ERROR: Cannot query database." );
         $title = "Guestbook admin area";
         $subtitle = "Ban Word";
         $text = "That word has now been baned.\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "unbanip" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      $title = "Guestbook admin area";
      $subtitle = "Unban IP's";
      $query = mysql_query( "SELECT * FROM `".$config["mysqlbantable"]."` WHERE `IP` != ''" );
      $text = "<hr />";
      while( $posts = mysql_fetch_array( $query, MYSQL_ASSOC ) )
      {
         $text .= "IP: ".$posts["IP"]."<br />\n";
         $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=dounbanip&ip=".$posts["IP"]."\">Unban this IP</a><br /><br /><hr />\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "dounbanip" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      mysql_query( "DELETE FROM `".$config["mysqlbantable"]."` WHERE `IP` = '".make_query_safe( $_GET['ip'] )."' LIMIT 1" ) or die( "ERROR: Cannot delete from database" );
      $title = "Guestbook admin area";
      $subtitle = "Unban IP'S";
      $text = "That IP has now been up baned.";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "unbanword" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      $title = "Guestbook admin area";
      $subtitle = "Unban Word's";
      $query = mysql_query( "SELECT * FROM `".$config["mysqlbantable"]."` WHERE `WORD` != ''" );
      $text = "<hr />";
      while( $posts = mysql_fetch_array( $query, MYSQL_ASSOC ) )
      {
         $text .= "WORD: ".$posts["WORD"]."<br />\n";
         $text .= "REPLACEMENT: ".$posts["REPLACEMENT"]."<br />\n";
         $text .= "<a href=\"".$_SERVER['PHP_SELF']."?action=dounbanword&word=".$posts["WORD"]."&replacement=".$posts["REPLACEMENT"]."\">Unban this IP</a><br /><br /><hr />\n";
      }
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
elseif ( $_GET['action'] === "dounbanword" )
{
   if( check_loggedin( $_COOKIE['SID'], getenv( 'REMOTE_ADDR' ), $config['mysqllogintable'] ) )
   {
      mysql_query( "DELETE FROM `".$config["mysqlbantable"]."` WHERE `WORD` = '".make_query_safe( $_GET['word'] )."' AND `REPLACEMENT` = '".make_query_safe( $_GET['replacement'] )."' LIMIT 1" ) or die( "ERROR: Cannot delete from database" );
      $title = "Guestbook admin area";
      $subtitle = "Unban Word'S";
      $text = "That word has now been up baned.";
   }
   else
   {
      $title = "Guestbook admin area";
      $subtitle = "Error";
      $text = "Sorry you need to login.";
   }
}
else
{
   $title = "Guestbook admin area";
   $subtitle = "Error";
   $text = "Unknown action. Please try again. If you keep getting this meassage please tell us what you did to get this message so we can fix it thank you.";
}
$fp = fopen( $config["admintpl"], "r" );
$tpl = fread( $fp, filesize($config["admintpl"]) );
fclose( $fp );
$toreplace = array(
"/{title}/",
"/{homelink}/",
"/{veiwlink}/",
"/{editlink}/",
"/{dellink}/",
"/{banlink}/",
"/{helplink}/",
"/{subtitle}/",
"/{text}/"
);
$replace = array(
$title,
$homelink,
$veiwlink,
$editlink,
$dellink,
$banlink,
$helplink,
$subtitle,
$text
);
$tpl= preg_replace($toreplace, $replace, $tpl);
echo "".$tpl."";
?>