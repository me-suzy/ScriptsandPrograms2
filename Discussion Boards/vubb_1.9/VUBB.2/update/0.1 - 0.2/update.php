<?php
// Get required files
require_once('./includes/functions.php');
require_once('.config.php');

mysql_query("ALTER TABLE `forum_topics` ADD `views` INT( 11 ) NOT NULL AFTER `replies` ;") or die(mysql_error());
mysql_query("ALTER TABLE `permissions` ADD `creply` INT( 1 ) NOT NULL AFTER `cpost` ;") or die(mysql_error());

echo "VUBB updated to 0.2";
?>