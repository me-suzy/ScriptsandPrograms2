<?php
include("connect.php");
if ($Con2!=false) {

$language = "english";	// 'czech-win1250' OR 'english' OR 'german' OR 'greek'

$tresult1 = MySQL_Query("CREATE TABLE $tblname_admin (admin_name varchar(255) NOT NULL, admin_pasw varchar(255) NOT NULL, admin_level TINYINT(1) NOT NULL, admin_control VARCHAR(128) DEFAULT 'xxx' NOT NULL, admin_time VARCHAR(15) NOT NULL)");
$dresult1 = MySQL_Query("INSERT INTO $tblname_admin VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3','1','xxx','')");
$tresult2 = MySQL_Query("CREATE TABLE $tblname_config (config_name varchar(255) NOT NULL, config_value varchar(255) NULL)");
$dresult2 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_title', 'UKiBoard')");
$dresult3 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_char', 'UKiOnet')");
$dresult4 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_email', 'ukio@centrum.cz')");
$dresult5 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_lang', '$language')");
$dresult6 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_themes', 'default')");
$dresult7 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_page', '15')");
$dresult8 = MySQL_Query("INSERT INTO $tblname_config VALUES ('board_admin', 'yes')");
$tresult3 = MySQL_Query("CREATE TABLE $tblname_head (head_id int(10) NOT NULL, head_name varchar(255) NOT NULL, head_order int(10) NOT NULL, head_number int(10) NOT NULL, head_char varchar(255) NULL, PRIMARY KEY (head_id))");
$tresult4 = MySQL_Query("CREATE TABLE $tblname_topic (topic_id int(10) NOT NULL, topic_head int(10) NOT NULL, topic_user varchar(255) NOT NULL, topic_email varchar(255) NULL, topic_time datetime NOT NULL, topic_title varchar(255) NULL, topic_text text, PRIMARY KEY  (topic_id))");
$tresult5 = MySQL_Query("ALTER TABLE $tblname_topic ADD FULLTEXT topic_full (topic_user,topic_title,topic_text)");
Header("Location: index.php");
}
?>