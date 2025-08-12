<?php

if (file_exists("config.inc.php")) {
  require_once("config.inc.php");

  if ($confirm == '1') {
    $mysql = mysql_connect($tinybb_mysql_host,$tinybb_mysql_user,$tinybb_mysql_password);
    mysql_select_db($tinybb_mysql_db,$mysql);
    $sql_posts = "CREATE TABLE `tinybb_posts` (`id` int(12) NOT NULL auto_increment,`topicid` int(5) NOT NULL default '0',`date` int(12) NOT NULL default '0',`author` varchar(250) NOT NULL default '',`text` mediumtext NOT NULL,PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=5;";
    $sql_topics = "CREATE TABLE `tinybb_topics` (`id` int(5) NOT NULL auto_increment,`name` varchar(150) NOT NULL default '',`author` varchar(150) NOT NULL default '',`lastpost` int(12) NOT NULL default '',`lastpostid` int(12) NOT NULL default '',PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=2;";
    $sql_members = "CREATE TABLE `tinybb_members` (`id` int(5) NOT NULL auto_increment,`flag` enum('0','1') NOT NULL default '0',`username` varchar(50) NOT NULL default '',`password` varchar(50) NOT NULL default '',`email` varchar(250) NOT NULL default '',`firstname` varchar(150) NOT NULL default '',`surname` varchar(150) NOT NULL default '',PRIMARY KEY  (`id`)) TYPE=MyISAM AUTO_INCREMENT=6;";
    if (mysql_query($sql_posts)) {
      echo "<p><b>tinybb_posts</b> table set up successfully.</p>\n";
      if (mysql_query($sql_topics)) {
        echo "<p><b>tinybb_topics</b> table set up successfully.</p>\n";
        if (mysql_query($sql_members)) {
          echo "<p><b>tinybb_members</b> table set up successfully.</p>\n";
          echo "<p><b>tinybb $tinybb_release has been set up successfully.</b></p>\n<p>you must delete this <b>install.php</b> file before proceeding to use tinybb.</p>\n";
          mail("tinybb@epicdesigns.co.uk","tinybb installation $tinybb_release","URL: $tinybb_url","From: $tinybb_email\n");
        }
        else {
          echo "<p><b>There has been an error creating the table</b> tinybb_members:<br />\n".mysql_error()."</p>\n";
        }
      }
      else {
        echo "<p><b>There has been an error creating the table</b> tinybb_topicss:<br />\n".mysql_error()."</p>\n";
      }
    }
    else {
      echo "<p><b>There has been an error creating the table</b> tinybb_posts:<br />\n".mysql_error()."</p>\n";
    }
  }
  else {
    echo "<h1>tinybb $tinybb_release Installation</h1>\n";
    if (strlen($tinybb_title) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_title</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_url) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_url</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_folder) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_folder</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_email) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_email</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_mysql_host) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_mysql_host</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_mysql_user) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_mysql_user</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_mysql_password) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_mysql_password</b>file could not be found.</p>\n"; }
    elseif (strlen($tinybb_mysql_db) <= 0) { echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> does not contain an entry for <b>&dollar;tinybb_mysql_db</b>file could not be found.</p>\n"; }
    else {
      $mysql = mysql_connect($tinybb_mysql_host,$tinybb_mysql_user,$tinybb_mysql_password);
      if ($mysql == 0) {
        echo "<p><span class=error\">ERROR:</span><br />Could not connect to the mysql server at <b>$tinybb_mysql_host</b>.</p>\n";
      }
      else {
        mysql_select_db($tinybb_mysql_db,$mysql);
        echo "<p><b>tinybb is ready to install.</b></p>\n<p><a href=\"install.php?confirm=1\">Complete the installation ...</a></p>\n";
        mysql_close($mysql);
      }
    }
  }
}
else {
  echo "<p><span class=error\">ERROR:</span><br />The <b>config.inc.php</b> file could not be found.</p>\n";
}


// if (file_exists(install.php)) { }
?>