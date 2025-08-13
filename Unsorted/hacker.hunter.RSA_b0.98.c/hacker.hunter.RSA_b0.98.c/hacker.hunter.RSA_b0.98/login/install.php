<?
$dump = "CREATE TABLE changes (
   id int(4) DEFAULT '0' NOT NULL,
   code text NOT NULL,
   dDate int(10) DEFAULT '0' NOT NULL,
   store text NOT NULL,
   email text NOT NULL
);
CREATE TABLE proxy (
   proxy_ip text NOT NULL,
   deny int(1) DEFAULT '1' NOT NULL
);
INSERT INTO proxy VALUES ( '148.74.255.104', '1');
INSERT INTO proxy VALUES ( '193.145.112.225', '1');
CREATE TABLE sessions (
   userid int(5) DEFAULT '0' NOT NULL,
   IP text NOT NULL,
   TPass text NOT NULL,
   TUser text NOT NULL,
   LTime int(10) DEFAULT '0' NOT NULL
);
CREATE TABLE users (
   userid int(5) NOT NULL auto_increment,
   username text NOT NULL,
   password text NOT NULL,
   email text NOT NULL,
   real_name text NOT NULL,
   dDate int(10) DEFAULT '0' NOT NULL,
   admin int(1) DEFAULT '0' NOT NULL,
   rotation int(2) DEFAULT '0' NOT NULL,
   rotation_count int(2) DEFAULT '0' NOT NULL,
   LLT int(10),
   LLIP text,
   PRIMARY KEY (userid)
);
INSERT INTO users VALUES ( '1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@site.com', 'Administrator', '1028640124', '1', '0', '0', NULL, NULL)";

include ("inc.php");
mysql_connect($db_host, $db_user, $db_password);
mysql_select_db($db_name);
$dump_array = explode(";", trim($dump));
foreach ($dump_array as $value) {
	$sql =trim ($value);
	echo $sql."<br>";
	mysql_query ($sql) or die ("<b>Bad query</b>");
}
mysql_close();
?><p><b>If you don't see error messages, try to log-in using "admin" as login and password :)</b>