<?php
//  +---------------------------------------------------------------------------+
//  | netjukebox, Copyright © 2001-2005  Willem Bartels                         |
//  |                                                                           |
//  | info@netjukebox.nl                                                        |
//  | http://www.netjukebox.nl                                                  |
//  |                                                                           |
//  | This file is part of netjukebox.                                          |
//  | netjukebox is free software; you can redistribute it and/or modify        |
//  | it under the terms of the GNU General Public License as published by      |
//  | the Free Software Foundation; either version 2 of the License, or         |
//  | (at your option) any later version.                                       |
//  |                                                                           |
//  | netjukebox is distributed in the hope that it will be useful,             |
//  | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
//  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
//  | GNU General Public License for more details.                              |
//  |                                                                           |
//  | You should have received a copy of the GNU General Public License         |
//  | along with this program; if not, write to the Free Software               |
//  | Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
//  +---------------------------------------------------------------------------+



//  +---------------------------------------------------------------------------+
//  | mysql.inc.php                                                             |
//  +---------------------------------------------------------------------------+
$cfg['mysql_required_version'] = 11;
//  Version 01 <=> netjukebox 2.02
//  Version 02 <=> netjukebox 3.00
//  Version 03 <=> netjukebox 3.50
//  Version 04 <=> netjukebox 3.55
//  Version 05 <=> netjukebox 3.61
//  Version 06 <=> netjukebox 3.67
//  Version 07 <=> netjukebox 3.69
//  Version 08 <=> netjukebox 3.70
//  Version 09 <=> netjukebox 3.73
//  Version 10 <=> netjukebox 3.74
//  Version 11 <=> netjukebox 3.76



//  +---------------------------------------------------------------------------+
//  | MySQL Connect                                                             |
//  +---------------------------------------------------------------------------+
@mysql_pconnect($cfg['mysql_host'], $cfg['mysql_user'], $cfg['mysql_password']) or message('error', '<strong>Can\'t connect to MySQL server on:</strong><br>' . $cfg['mysql_host']);
@mysql_select_db($cfg['mysql_db']) or createDB();

$query = mysql_query('SELECT version FROM configuration_database ORDER BY version DESC');
$cfg['mysql_current_version'] = @mysql_result($query, 'version');
if ($cfg['mysql_current_version'] != $cfg['mysql_required_version'])
	updateDB();



//  +---------------------------------------------------------------------------+
//  | Create Database                                                           |
//  +---------------------------------------------------------------------------+
function createDB()
{
global $cfg;
@mysql_query('CREATE DATABASE ' . $cfg['mysql_db']) or message('error', '<strong>Can\'t create database:</strong><br>' . $cfg['mysql_db']);
importDB();
}



//  +---------------------------------------------------------------------------+
//  | Import Database                                                           |
//  +---------------------------------------------------------------------------+
function importDB()
{
global $cfg;
$file = $cfg['home_dir'] . '/sql/netjukebox_' . str_pad($cfg['mysql_required_version'], 2, '0', STR_PAD_LEFT) . '.sql';
if (!file_exists($file)) message('error', '<strong>Can\'t open file:</strong><br>'. $file);
@mysql_select_db($cfg['mysql_db']) or message('error', '<strong>Can\'t select database:</strong><br>' . $cfg['mysql_db']);
QuerySqlFile($file);
message('ok', '<strong>Database ' . $cfg['mysql_db'] . '@' . $cfg['mysql_host'] . ' created successfully.</strong><br>
For security reason it is advisable to change the password.<br><br>
<a href="index.php?menu=browse&amp;authenticate=logout" target="_top"><strong>Login netjukebox:</strong></a>
<ul>
	<li><strong>username:</strong> admin</li>
	<li><strong>password:</strong> admin</li>
</ul>');
}



//  +---------------------------------------------------------------------------+
//  | Update Database                                                           |
//  +---------------------------------------------------------------------------+
function updateDB()
{
global $cfg;

$query = @mysql_query('SHOW TABLES') or message('error', '<strong>Can\'t show MySQL tables in:</strong><br>' . $cfg['mysql_db']);
$table = @mysql_result($query, 0);
if ($table == '')
	{
	importDB();
	}
if ($cfg['mysql_current_version'] < 1 || $cfg['mysql_current_version'] > $cfg['mysql_required_version'])
	{
	message('error', '<strong>MySQL update error</strong><br>
Incremental upgrade is not supported from this database version.<br>
<ul class="compact">
	<li>Delete your old database.</li>
	<li>On the next start netjukebox automatic creates a new MySQL database and table structure.</li>
</ul>');
	}
else
	{
	for($i = $cfg['mysql_current_version'] + 1; $i <= $cfg['mysql_required_version']; $i++)
		{
		$file = $cfg['home_dir'] . '/sql/incremental_upgrade_' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.sql';
		if (!file_exists($file)) message('error', '<strong>Can\'t open file:</strong><br>'. $file);
		QuerySqlFile($file);
		}
	message('ok', '<strong>Incremental database upgrade successfuly on ' . $cfg['mysql_db'] . '@' . $cfg['mysql_host'] . '</strong><br>
It is advisable to update de database now.<br>
<ul class="compact"><li><a href="update.php">Update database now</a></li></ul>');
	}
}



//  +---------------------------------------------------------------------------+
//  | Query SQL File                                                            |
//  +---------------------------------------------------------------------------+
function QuerySqlFile($file)
{
$fp = fopen($file, 'r');
$query = '';
while (!feof($fp))
	{
	$line = fgets ($fp, 1024);
	if (($line{0} != '#') && (trim($line) != ''))
		{
		$query .= $line;
		}
	if (strstr ($line, ';'))
		{
		$query = str_replace(';', '', $query);
		@mysql_query($query) or message('error', '<strong>MySQL create/upgarde error, Can\'t execute:</strong><br>' . $file . '<br>' . $query);
		$query = '';
		}
	}
}
?>
