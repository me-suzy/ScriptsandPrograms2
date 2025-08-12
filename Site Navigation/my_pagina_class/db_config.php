<?php 
// modify these constants to fit your environment
if (!defined("DB_SERVER")) define("DB_SERVER", "localhost");
if (!defined("DB_NAME")) define("DB_NAME", "your_database");
if (!defined("DB_USER")) define ("DB_USER", "user_name");
if (!defined("DB_PASSWORD")) define ("DB_PASSWORD", "password_here");

// some external constants to controle the output
define("QS_VAR", "page"); // the variable name inside the query string (don't use this name inside other links)
define("NUM_ROWS", 5); // the number of records on each page
define("STR_FWD", "&gt;&gt;"); // the string is used for a link (step forward)
define("STR_BWD", "&lt;&lt;"); // the string is used for a link (step backward)
define("NUM_LINKS", 5); // the number of links inside the navigation (the default value)
?>