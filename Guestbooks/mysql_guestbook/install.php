<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
       "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
  <head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Revisit-After" content="5 Days">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>PHP Installer</title>
    <style type="text/css">
     #centent {
      width:100%;
      padding-top: 3em;
      clear: both;
      font: 13px sans-serif;
      background: #fff;
      color: #000;
      text-decoration: none;
      text-align: center;
     }
    </style>
  </head>
  <body bgcolor="#FFFFFF" text="#000000" link="#0000FF" alink="#0000FF" vlink="#0000FF">
   <div id="centent">
    <h1>PHP Installer</h1>
<?php
if($_GET['part']==1) {
    $link = mysql_connect($_POST['server'].":".$_POST['port'], $_POST['username'],$_POST['password']) or die('ERROR: Sorry could not make a connection to mysql database.');
    print "Connected to MySQL successfully<br>\n";
    function make_query_safe( $value )
    {
       if ( get_magic_quotes_gpc() )
       {
           $value = stripslashes( $value );
       }
       $value = mysql_real_escape_string( $value );
       return $value;
    }
    mysql_select_db ( $_POST['db'] ) or die( "ERROR: Sorry cannot select database." );
    $sql = "CREATE TABLE `".$_POST['banedstuff']."` (
      `WORD` char(50) NOT NULL default '',
      `REPLACEMENT` char(50) NOT NULL default '',
      `IP` char(30) NOT NULL default ''
    ) TYPE=MyISAM";
    mysql_query( $sql ) or die ( "ERROR: Cannot make banstuff table." );
    print "MySQL ban stuff table made successfully<br>\n";
    $sql = "CREATE TABLE `".$_POST['guestbook']."` (
      `ID` int(10) unsigned NOT NULL auto_increment,
      `NAME` varchar(30) NOT NULL default '',
      `EMAILORURL` varchar(50) NOT NULL default '',
      `IP` varchar(30) NOT NULL default '',
      `COMMENTS` text NOT NULL,
      `DATE` datetime NOT NULL default '0000-00-00 00:00:00',
      PRIMARY KEY  (`ID`)
    ) TYPE=MyISAM";
    mysql_query( $sql ) or die ( "ERROR: Cannot make guestbook table." );
    print "MySQL guestbook table made successfully<br>\n";
    $sql = "CREATE TABLE `".$_POST['login']."` (
      `id` int(11) NOT NULL auto_increment,
      `username` char(20) NOT NULL default '',
      `password` char(32) NOT NULL default '',
      `cookie` char(32) NOT NULL default '',
      `session` char(32) NOT NULL default '',
      `ip` char(15) NOT NULL default '',
      PRIMARY KEY  (`id`),
      UNIQUE KEY `username` (`username`)
    ) TYPE=MyISAM";
    mysql_query( $sql ) or die ( "ERROR: Cannot make login table." );
    print "MySQL login table made successfully<br>\n";
    $sql = "INSERT INTO `".$_POST['login']."` VALUES ('', '".make_query_safe( $_POST['auser'] )."', '".make_query_safe( $_POST['apass'] )."', '', '', '')";
    mysql_query( $sql ) or die ( "ERROR: Cannot inset admin pass and username." );
    print "admin pass and username inserted successfully<br>\n";
    $fp = fopen( "config.php", "a" ) or die ( "ERROR: cannot make config file" );
    $config .= '<?php'."\n";
    $config .= '$config = array('."\n";
    $config .= '"admintpl" => "admin.tpl", // admin template'."\n";
    $config .= '"signguestbook" => "signguestbook.tpl", // sign guestbook template'."\n";
    $config .= '"guestbookpost" => "guestbookpost.tpl", // post guestbook template'."\n";
    $config .= '"guestbook" => "guestbook.tpl", // guestbook template'."\n";
    $config .= '"guestbooksignerror" => "error.tpl", // error template'."\n";
    $config .= '"guestbookthankyou" => "thankyou.tpl", // thank you template'."\n";
    $config .= '"mysqlpass" => "'.$_POST["password"].'", // mysql pass'."\n";
    $config .= '"mysqlusername" => "'.$_POST["username"].'", // msql username'."\n";
    $config .= '"mysqllogintable" => "'.$_POST["login"].'", // mysql login table'."\n";
    $config .= '"mysqhost" => "'.$_POST["server"].'", // mysql host'."\n";
    $config .= '"mysqlguestbooktable" => "'.$_POST["guestbook"].'", // mysql guestbook table'."\n";
    $config .= '"mysqlbantable" => "'.$_POST["banedstuff"].'", // mysql ban stuff table'."\n";
    $config .= '"mysqldb" => "'.$_POST["db"].'", // mysql db'."\n";
    $config .= '"mysqlport" => "'.$_POST["port"].'", // mysql port'."\n";
    $config .= '"numpostsperpage" => "10", // number of post per page'."\n";
    $config .= '"newestfirst" => "yes" // newest posts first?'."\n";
    $config .= ');'."\n\n";
    $config .= '$smile        = "<img src=\'smile.gif\' alt=\'\' />";'."\n";
    $config .= '$sad          = "<img src=\'sad.gif\' alt=\'\' />";'."\n";
    $config .= '$disappointed = "<img src=\'disappointed.gif\' alt=\'\' />";'."\n";
    $config .= '$confused     = "<img src=\'confused.gif\' alt=\'\' />";'."\n";
    $config .= '$thumbdown    = "<img src=\'thumbdown.gif\' alt=\'\' />";'."\n";
    $config .= '$thumbup      = "<img src=\'thumbup.gif\' alt=\'\' />";'."\n\n";
    $config .= '$smiles = array('."\n";
    $config .= '"/:\)/i",'."\n";
    $config .= '"/:\(/i",'."\n";
    $config .= '"/:\|/i",'."\n";
    $config .= '"/:S/i",'."\n";
    $config .= '"/:\-\)/i",'."\n";
    $config .= '"/:\-\(/i",'."\n";
    $config .= '"/:\-\|/i",'."\n";
    $config .= '"/:\-S/i",'."\n";
    $config .= '"/=\)/i",'."\n";
    $config .= '"/=\(/i",'."\n";
    $config .= '"/=\|/i",'."\n";
    $config .= '"/=S/i",'."\n";
    $config .= '"/\(Y\)/i",'."\n";
    $config .= '"/\(N\)/i"'."\n";
    $config .= ');'."\n\n";
    $config .= '$rsmiles = array('."\n";
    $config .= '$smile,'."\n";
    $config .= '$sad,'."\n";
    $config .= '$disappointed,'."\n";
    $config .= '$confused,'."\n";
    $config .= '$smile,'."\n";
    $config .= '$sad,'."\n";
    $config .= '$disappointed,'."\n";
    $config .= '$confused,'."\n";
    $config .= '$smile,'."\n";
    $config .= '$sad,'."\n";
    $config .= '$disappointed,'."\n";
    $config .= '$confused,'."\n";
    $config .= '$thumbup,'."\n";
    $config .= '$thumbdown'."\n";
    $config .= ');'."\n\n";
    $config .= 'if ( !isset($_SERVER) ) {'."\n";
    $config .= '   $_GET        = &$HTTP_GET_VARS;'."\n";
    $config .= '   $_POST       = &$HTTP_POST_VARS;'."\n";
    $config .= '   $_ENV        = &$HTTP_ENV_VARS;'."\n";
    $config .= '   $_SERVER     = &$HTTP_SERVER_VARS;'."\n";
    $config .= '   $_COOKIE     = &$HTTP_COOKIE_VARS;'."\n";
    $config .= '   $_REQUEST    = array_merge( $_GET, $_POST, $_COOKIE );'."\n";
    $config .= '}'."\n\n";
    $config .= '$MySQL = @mysql_connect ( $config["mysqhost"].":".$config["mysqlport"], $config["mysqlusername"], $config["mysqlpass"] ) or die ( \'ERROR: Sorry cannot connect to database.\' );'."\n";
    $config .= 'mysql_select_db ( $config["mysqldb"] ) or die( "ERROR: Sorry cannot select database." );'."\n\n";
    $config .= 'function make_query_safe( $value )'."\n";
    $config .= '{'."\n";
    $config .= '   if ( get_magic_quotes_gpc() )'."\n";
    $config .= '   {'."\n";
    $config .= '       $value = stripslashes( $value );'."\n";
    $config .= '   }'."\n";
    $config .= '   $value = mysql_real_escape_string( $value );'."\n";
    $config .= '   return $value;'."\n";
    $config .= '}'."\n";
    $config .= '?>'."\n";
    fwrite( $fp, $config ) or die ( "ERROR: Cannot inset admin pass and username." );
    fclose ($fp);
    print "made config.php successfully<br>";
    print "Thank you the guestbook was installed successfully.<br />\n";
    print "<h3>DELETE THIS FILE NOW.</h3>";
    mysql_close($link);
} else {
    print "<form action=\"install.php?part=1\" method=\"post\">\n";
    print "MySQL Server: <br><input type=\"text\" size=\"30\" name=\"server\" value=\"localhost\"><br><br>\n";
    print "MySQL Server Port: <br><input type=\"text\" size=\"4\" name=\"port\" value=\"3306\"><br><br>\n";
    print "MySQL Database: <br><input type=\"text\" size=\"30\" name=\"db\" value=\"dbname\"><br>\n";
    print "Note: some web host make your MySQL databases and database username have your username<br>before them E.G. usrename_dbname. So you need to type the full name<br>of the database with your username if your host does this.<br><br>\n";
    print "MySQL Username: <br><input type=\"text\" size=\"30\" name=\"username\"><br><br>\n";
    print "MySQL Password: <br><input type=\"text\" size=\"30\" name=\"password\"><br><br>\n";
    print "MySQL baned stuff table name: <br><input type=\"text\" size=\"30\" name=\"banedstuff\"><br><br>\n";
    print "MySQL guestbook table name: <br><input type=\"text\" size=\"30\" name=\"guestbook\"><br><br>\n";
    print "MySQL login table name: <br><input type=\"text\" size=\"30\" name=\"login\"><br><br>\n";
    print "Guestbook admin password: <br><input type=\"text\" size=\"30\" name=\"apass\"><br><br>\n";
    print "Guestbook admin username: <br><input type=\"text\" size=\"30\" name=\"auser\"><br><br>\n";
    print "<input type=\"submit\" value=\"Install\">\n";
    print "</form>\n";
}
?>
  </div>
 </body>
</html>