<?php


header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i ") . " GMT");
                                                      // always modified
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");


include("atom_db.class.php"); //the engine of the forum.
include("db/config.php");  //variable configuration
$usecookies = false;     //use cookies?

$config_file = "db/config.php";


$dbfile_true = $dbfile; //origional db file.

if (isset($_GET["forum"])){
  if (file_exists($_GET["forum"])){
  $_SESSION["forum"] = $_GET["forum"];
  }; //end file_exists
}; //end isset $forum

if (isset($_SESSION["forum"])){
if (file_exists($_SESSION["forum"].".php")){
  include($_SESSION["forum"].".php");
  $config_file = $_SESSION["forum"].".php";
}; //end if
}; //end if

$f_version = "1.11";
include("func.inc.php"); //algorithmic functions to be used on the forum.

if (!is_writable("db/config.php")){

     die("
     <table width=\"500\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#000000\">
     <tr>
        <td width=\"100%\">
     <table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"#FFFFFF\">
     <tr>
     <td width=\"20%\"><img src=\"icon/error.jpg\" alt=\"Error Found!\" /></td>
     <td width=\"80%\">The Configuration file (\"<b>db/config.php</b>\") needs chmoding to either 0666 or 0777!.
     </td>
     </tr>
     </table>
        </td>
     </tr>
     </table>");

}; //end !is_writable

if (file_exists("$lingo_file")){
  include("$lingo_file");
} else {
     die("
     <table width=\"500\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#000000\">
     <tr>
        <td width=\"100%\">
     <table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"#FFFFFF\">
     <tr>
     <td width=\"20%\"><img src=\"icon/error.jpg\" alt=\"Error Found!\" /></td>
     <td width=\"80%\">We could not locate the language file (\"$lingo_file\") please correct this in order to continue.<br />Either modify config.php file manualy or place the file at that location to work.
     </td>
     </tr>
     </table>
        </td>
     </tr>
     </table>");
     };


	// Begin timer
	$m_time = explode(" ",microtime());
	$m_time = $m_time[0] + $m_time[1];
	$starttime = $m_time;
	
	
	/* If the user has logged in once then we need to make sure that
     user is remembered on the forum so this prevents the user
     having to login upon each visit.
  */
  

  if (!isset($_SESSION["last"])){
if (isset($_COOKIE["lastv"])){
  $_SESSION["last"] = $_COOKIE["lastv"];
} else {
  $_SESSION["last"] = date("$pfdate");
  };
};

  @setcookie("lastv", date($pfdate), time()+3600*24*7); //set timestamp for last on



  if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
  if ($usecookies == true){
  @setcookie("user", $_SESSION["user"], time()+3600*24*7); //upon each visit, remember the user for a week
  @setcookie("pass", $_SESSION["pass"], time()+3600*24*7); //upon each visit, remember the pass for a week
  }; //end if
  }; //end is logged in
  

  if (isset($_COOKIE["user"]) and isset ($_COOKIE["pass"])){

  $_SESSION["user"] = $_COOKIE["user"];
  $_SESSION["pass"] = $_COOKIE["pass"];
  };


  /* END REMEMBER LOGIN SEQUENCE */

  $laston = substr($_SESSION["last"], 0, 2);

    if (!file_exists("$dbfile")){
     die("
     <table width=\"500\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#000000\">
     <tr>
        <td width=\"100%\">
     <table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"#FFFFFF\">
     <tr>
     <td width=\"20%\"><img src=\"icon/error.jpg\" alt=\"Error Found!\" /></td>
     <td width=\"80%\">Database <strong>($dbfile)</strong> could not be found, check your config file.<br />
     If the install file exists i recommend you run it, default is <a href=\"install.php\">Install.php</a>.
     </td>
     </tr>
     </table>
        </td>
     </tr>
     </table>");
    };

if (!is_writable("$dbfile")){

     die("
     <table width=\"500\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#000000\">
     <tr>
        <td width=\"100%\">
     <table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"#FFFFFF\">
     <tr>
     <td width=\"20%\"><img src=\"icon/error.jpg\" alt=\"Error Found!\" /></td>
     <td width=\"80%\">The database (\"<b>$dbfile</b>\") needs chmoding to either 0666 or 0777!.
     </td>
     </tr>
     </table>
        </td>
     </tr>
     </table>");

}; //end !is_writable


$db = new sdb(); //initiate the class
$db->debug = false;  //debug of general things off
$db->constatus = false; //debug of connection status off

###########LL

if (isset($_SESSION["forum"])){
  $dbfile = $_SESSION["forum"];
}; //end isset

if (!file_exists($dbfile)){
  $dbfile = $dbfile_true;
};

$db->selectDB("$dbfile", "$dbuser", "$dbpass"); //select the database
$db->getClientStats(); //retrieve the clients status.


//verify the user truly exists in the db
if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

$logged_key = $db->query("users", "0", $_SESSION["user"]);

if (@$db->data["_DB"]["users"]["$logged_key"]["0"] != @$_SESSION["user"] and @$db->data["_DB"]["users"]["$logged_key"]["1"] != @$_SESSION["pass"]){
  unset($_SESSION["user"]);
  unset($_SESSION["pass"]);
}; //end if

}; //end is_logged_in
for ($badword = 2; $badword < count(@$db->data["_DB"]["filter"]); $badword++){
   $malword = $db->data["_DB"]["filter"]["$badword"][0];
   $newword = $db->data["_DB"]["filter"]["$badword"][1];
   $badinput["$malword"] = $newword;
}; //end for $badword


function message($string, $binput=array()){  //replace bad words for good words
$binput[] = "";
foreach ($binput as $badword => $goodword){
  $string = str_replace("$badword", "$goodword", $string);
}; //end foreach
$string = preg_replace("/\[url=(http:\/\/)?(.*?)\](.*?)\[\/url\]/si", "<a href=\"\\1\\2\" target='_blank'>\\3</a>", $string);
$string = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", "<font color=\"\\1\">\\2</font>", $string);
$string = preg_replace("/\[email=(.*?)\](.*?)\[\/email\]/si", "<a href=\"mailto:\\1\">\\2</a>", $string);
$string = preg_replace("/\[(.*?)\](.*?)\[\/(.*?)\]/si", "<\\1>\\2</\\3>", $string);
$string = preg_replace("/\[img\](.*?)\[\/img\]/si", "<img src=\"\\1\" alt=\"img\" />", $string);
return $string;

}; //end function message



//validity on user auth

$idkeyuser = $db->query("users", "0", @$_SESSION["user"]);
$idkeyline = @$db->data["_DB"]["users"]["$idkeyuser"];
if ($idkeyline["0"] != @$_SESSION["user"] or $idkeyline["1"] != @$_SESSION["pass"]){
  unset($_SESSION["user"]);
  unset($_SESSION["pass"]);
}; //end if.

//validity on user auth


//core signup

if ($core_signup == "true"){

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

if (!isset($reg)){
  echo ("<meta http-equiv=\"refresh\" content=\"0;url=register.php\">");
}; //end if

}; //end !logged in

}; //end if $core_signup

//core signup


$user_power = "0"; //set power to null
$status = array(); //allow for view forum, post forum, reply forum
$status[] = "guests";   //allow for guest status

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
  $status[] = "members"; //allow for member status
  $power = $db->query("users", "0", @$_SESSION["user"]);
  $user_power = $db->data["_DB"]["users"]["$power"]["3"];
  
  if ($user_power == "1"){
    $status[] = "moders"; //allow admin have mods status
    $status[] = "admins"; //allow for admin status
  }; //end if
}; //end if


  $month = date("m");
switch($month){
case 1:
  $month = "January";
  break;
case 2:
  $month = "February";
  break;
case 3:
  $month = "March";
  break;
case 4:
  $month = "April";
  break;
case 5:
  $month = "May";
  break;
case 6:
  $month = "June";
  break;
case 7:
  $month = "July";
  break;
case 8:
  $month = "August";
  break;
case 9:
  $month = "September";
  break;
case 10:
  $month = "October";
  break;
case 11:
  $month = "November";
  break;
case 12:
  $month = "December";
  break;

}; //end switch

#echo $month;

include("whos_online.php"); //begin whos online.

$flood = "30"; //seconds
$stamp = date("dHis"); //timestamp for flood controll.
$stamp_p = date("dHis") + $flood; //timestamp for flood controll.

if (isset($_SESSION["flood"]) and $stamp >= $_SESSION["flood"]){
  unset($_SESSION["flood"]);
}; //end





echo ("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>$title</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\r\n\r\n");


  //check for the style.css and if it exists, parse it...
  if (file_exists("$skins/style.css")){
 echo ("<link href=\"$skins/style.css\" rel=\"stylesheet\" type=\"text/css\"></link>\r\n\r\n");
 };
 
  //check for the style.css and if it exists, parse it...
  if (file_exists("$skins/style.php")){
 include("$skins/style.php");
 };
 
echo ("</head>");

        if (file_exists("$skins/other/background.jpg")){
        echo ("<body background=\"$skins/other/background.jpg\">\r\n\r\n");
        } else {
        echo ("<body bgcolor=\"$bgcolor\">");
        };


echo ("
<table border=\"0\" cellspacing=\"1\" class=\"bordercolor\" cellpadding=\"0\" width=\"$width\" align=\"center\">
   <tr bgcolor=\"$tbackground3\">
      <td width=\"100%\">

      <img src=\"$skins/banner.jpg\" alt=\"$title: Banner\" /><br />\r\n ");
      

include("locale.php");
      
echo ("<div align=\"center\">\r\n");

echo ("<a href=\"index.php\"><img src=\"$skins/buttons/home.gif\" alt=\"$title: Home\" border=\"0\" /></a>");

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("<a href=\"login.php\"><img src=\"$skins/buttons/login.gif\" alt=\"$title: Login\" border=\"0\" /></a>");
};

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("<a href=\"profile.php\"><img src=\"$skins/buttons/usercp.gif\" alt=\"$title: User CP\" border=\"0\" /></a>");
};

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("<a href=\"members.php\"><img src=\"$skins/buttons/members.gif\" alt=\"$title: Members\" border=\"0\" /></a>");
};

echo ("<a href=\"faq.php\"><img src=\"$skins/buttons/faq.gif\" alt=\"$title: FaQ\" border=\"0\" /></a>");

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("<a href=\"register.php\"><img src=\"$skins/buttons/register.gif\" alt=\"$title: Register\" border=\"0\" /></a>");
};

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("<a href=\"logout.php\"><img src=\"$skins/buttons/logout.gif\" alt=\"$title: Logout\" border=\"0\" /></a>");
};

echo ("\r\n</div>");

//announcement

if ($announcement != ""){

if (file_exists("$skins/table/tl2.gif")){
  announce_header("$_LANG[7]");
} else {
table_header("<b>$_LANG[7]</b>");
}; //end if

echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\">
      <td width=\"100%\" colspan=\"1\">

 <table border=\"0\" cellspacing=\"0\" cellpadding=\"3\"  width=\"100%\" align=\"center\">
   <tr>
      <td width=\"10%\" colspan=\"1\"><img src=\"icon/announce.gif\" alt=\"Announcement Image\" /></td>


      <td width=\"90%\" colspan=\"1\">
      <font size=\"$fmedium\" color=\"$fcolor\" face=\"$fface\">".message("$announcement")."</font>
      </td>
   </tr>
</table>



    </td>
   </tr>
</table>");

if (file_exists("$skins/table/tl2.gif")){
  announce_footer();
} else {
  table_footer();

};

}; //end $announcement

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

$hitch = $db->query("users", "0", $_SESSION["user"]);
$hitch = $db->data["_DB"]["users"]["$hitch"]["2"];

  if ($hitch == ""){
  //email hitch warning
echo ("<table border=\"0\" cellspacing=\"1\" bgcolor=\"$tborder_color2\" cellpadding=\"3\"  width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"1\" align=\"center\"><b>$_LANG[8]</b></td>
   </tr>
</table><font size=\"1\"><br /></font>");
  };  //end the hitch warning
}; //end is logged in


    for ($i = 2; $i < count(@$db->data["_DB"]["alerts"]); $i++){
       $item = $db->data["_DB"]["alerts"]["$i"];
       $ip = $db->data["_CLIENT"]["IP"];
       if ($item[0] == @$_SESSION["user"] or $item[0] == $ip){
echo ("<table border=\"0\" cellspacing=\"1\" bgcolor=\"$tborder_color2\" cellpadding=\"3\"  width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\">
      <td width=\"100%\" colspan=\"1\">");

       echo ("<font size=\"$fsmall\" color=\"$fcfade\" face=\"$fface\"><b>$_LANG[9]</b> <i>$item[1]</i> (<i>$item[0]</i>)</font> <font color=\"$fcolor\" size=\"$fmedium\"  face=\"$fface\">$item[2]</font><br />");

      echo ("</td>
   </tr>
</table><font size=\"1\"><br /></font>");
};
      }; //end $i;

  echo ("<font size=\"1\"><br /></font>");
  
  /* $status array now holds the value of, guests, members and admins if the user should have it,
      example: a non-logged in user will have just 'guests' status. a logged in user will have
      'guests' and 'members' and an admin will have those aswell as 'admins' */
      $mid = false;

      if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
      for ($m = 2; $m < count($db->data["_DB"]["forums"]); $m++){
     $mods = explode(", ", $db->data["_DB"]["forums"]["$m"][4]); //explode the mods table
      if (in_array($_SESSION["user"], $mods)){
        $mid = true;
      }; //end if
      
       }; //end for $m
      }; //end is_logged_in
      
      if ($mid == true){
        $status[] = "moders";
      }; //end if
      
      if (!$db->is_connected()){
           echo("
     <table width=\"500\" border=\"0\" align=\"center\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#000000\">
     <tr>
        <td width=\"100%\">
     <table width=\"100%\" border=\"0\" align=\"center\" bgcolor=\"#FFFFFF\">
     <tr>
     <td width=\"20%\"><img src=\"icon/error.jpg\" alt=\"Error Found!\" /></td>
     <td width=\"80%\">You are not connected to the database properly. This could be due to your username or password in your config.php file do NOT match the one for the database file!<br /><br /><b>To Fix:</b> Open your config.php file located in the db
     dir and also have open your database file. make sure the variables dbuser and dbpass match ADMIN_UN and ADMIN_PW.
     </td>
     </tr>
     </table>
        </td>
     </tr>
     </table>");
      }; //end if;


?>
