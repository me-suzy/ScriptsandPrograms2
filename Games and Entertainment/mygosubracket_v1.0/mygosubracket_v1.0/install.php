<?php
/**
* install.php
* ------------------------------------------------------------
* @project  MyGosuBracket
* @version  1.0
* @license  GPL
* @author   cagrET (Cezary Tomczak) <cagret@yahoo.com>
* @link     http://cagret.prv.pl
* ------------------------------------------------------------
*/

require 'kernel/config.php';

$query = @file('kernel/brackets.sql');
$query = implode('', $query);

$db->query($query);

echo "Installation succesfull.";

?>