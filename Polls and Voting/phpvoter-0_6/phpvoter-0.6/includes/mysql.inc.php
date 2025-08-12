<?php
# declare some relevant variables for MySQL
$config['mysql_hostname'] = "localhost";
$config['mysql_username'] = "";
$config['mysql_password'] = "";
$config['mysql_dbName'] = "";

# MySQL tables created to store the data
$tables['question'] = "Question";
$tables['answer'] = "Answer";
$tables['voted'] = "Voted";

# Seed the random number generator.
mt_srand((double)microtime()*1000000);

# Connect to MySQL
$config['dbconn'] = MYSQL_CONNECT($config['mysql_hostname'], $config['mysql_username'], $config['mysql_password']) OR DIE("Unable to connect to database");

@mysql_select_db($config['mysql_dbName']) or die("Unable to select database"); 
?>
