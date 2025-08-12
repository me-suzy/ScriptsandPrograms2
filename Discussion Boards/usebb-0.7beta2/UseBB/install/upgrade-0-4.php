<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/install/upgrade-0-4.php,v 1.24 2005/08/22 17:52:17 pc_freak Exp $
	
	This file is part of UseBB.
	
	UseBB is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	UseBB is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with UseBB; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('INCLUDED', true);
include('../config.php');

class functions {
	
	function usebb_die($errno, $error, $file, $line) {
		
		global $connerror;
		
		//
		// Don't show various errors on PHP5
		//
		if ( intval(substr(phpversion(), 0, 1)) > 4 ) {
			
			$ignore_warnings = array(
				'var: Deprecated. Please use the public/private/protected modifiers',
				'Trying to get property of non-object',
			);
			if ( in_array($error, $ignore_warnings) )
				return;
			
		}
		
		$connerror = $error;
		
	}
	
	function get_config($setting) {
		
		global $conf;
		
		if ( isset($conf[$setting]) )
			return $conf[$setting];
		else
			return '';
		
	}
	
}

$functions = new functions;

function error_handler($errno, $error, $file, $line) {
	
	global $functions;
	$functions->usebb_die($errno, $error, $file, $line);
	
}
set_error_handler('error_handler');

if ( !empty($_POST['step']) && intval($_POST['step']) > 1 ) {
	
	include('../sources/db_'.$dbs['type'].'.php');
	$db = new db;
	$db->connect($dbs);
	
}

function to_step($step) {
	
	return '<form action="'.$_SERVER['PHP_SELF'].'" method="post"><p><input type="hidden" name="step" value="'.$step.'" /><input type="submit" value="' . ( ( $_POST['step'] == $step ) ? 'Retry step '.$step : 'Continue to step '.$step ) . '" /></p></form>';
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>UseBB Upgrade 0.4.x</title>
<style type="text/css">
	body {
		font-family: sans-serif;
		text-align: center;
		font-size: 10pt;
	}
	#logo {
		margin-bottom: 0px;
	}
	h1 {
		color: #336699;
		font-size: 18pt;
		font-weight: bold;
		margin-top: 0px;
	}
	#wrap {
		width: 600px;
		margin: 0px auto 0px auto;
		background-color: #EFEFEF;
		border: 1px solid silver;
		padding: 10px;
		text-align: left;
	}
	h2 {
		color: #336699;
		font-size: 12pt;
		font-weight: bold;
		text-align: center;
	}
	form {
		text-align: center;
	}
	address {
		color: #333333;
		margin: 10px 0px 0px 0px;
	}
</style>
</head>
<body>
<p id="logo"><img src="../templates/default/gfx/usebb.png" alt="" /></p>
<h1>Upgrade 0.4.x</h1>
<div id="wrap">
<?php

if ( empty($_POST['step']) ) {
	
	echo '<h2>Welcome</h2>';
	echo '<p>Welcome to the UseBB upgrade 0.4.x wizard. This wizard will help you upgrade UseBB <strong>0.4.x</strong> to version <strong>0.5</strong>.</p>';
	echo to_step(1);
	
} elseif ( intval($_POST['step']) === 1 ) {
	
	echo '<h2>Step 1</h2>';
	
	if ( !function_exists('version_compare') ) {
		
		echo '<p>We\'re sorry. UseBB does not work on the PHP version running on this server (PHP '.phpversion().'). You need at least <strong>4.1.0</strong>. Get a recent version from <a href="http://www.php.net/downloads.php" target="_blank">PHP.net</a>.</p>';
		
	} else {
		
		echo '<p>First, upload UseBB 0.5 to the same location as 0.4.x, overwriting the old files. Then edit the database configuration values in <code>config.php</code>. Make sure the database settings match with those for your host. If in doubt, please contact your web host for information regarding accessing databases.</p>';
		echo '<p><strong>Tip:</strong> if you already use MySQL 4.1, it might be interesting to set <code>$dbs[\'type\']</code> to <code>\'mysqli\'</code>. If you don\'t know which version you are running, leave the default value.</p>';
		echo '<p><strong>Another tip:</strong> you might want to check <a href="http://usebb.sourceforge.net/docs/doku.php?id=configuration:config.php_guide" target="_blank">this document</a> out to change config.php.</p>';
		echo to_step(2);
		
	}
	
} elseif ( intval($_POST['step']) === 2 ) {
	
	echo '<h2>Step 2</h2>';
	if ( !empty($connerror) ) {
		
		echo '<p>An error was encountered while trying to access the database. The error was:</p>';
		echo '<code>'.$connerror.'</code>';
		echo '<p>Please check your database settings in <code>config.php</code>!</p>';
		echo to_step(2);
		
	} else {
		
		echo '<p>The database settings are OK!</p>';
		echo to_step(3);
		
	}
	
} elseif ( intval($_POST['step']) === 3 ) {
	
	echo '<h2>Step 3</h2>';
	
	$result = $db->query("SELECT regdate FROM ".$dbs['prefix']."members ORDER BY regdate ASC LIMIT 1");
	$userinfo = $db->fetch_result($result);
	
	$queries = array(
		"ALTER TABLE `".$dbs['prefix']."members` ADD `displayed_name` VARCHAR( 255 ) NOT NULL AFTER `avatar_remote`",
		"UPDATE ".$dbs['prefix']."members SET displayed_name = name WHERE displayed_name = ''",
		"CREATE TABLE `".$dbs['prefix']."badwords` ( `word` VARCHAR( 255 ) NOT NULL , `replacement` VARCHAR( 255 ) NOT NULL , PRIMARY KEY ( `word` ) )",
		"ALTER TABLE `".$dbs['prefix']."members` ADD `auto_subscribe_topic` INT( 1 ) NOT NULL AFTER `hide_signatures`, ADD `auto_subscribe_reply` INT( 1 ) NOT NULL AFTER `auto_subscribe_topic`",
		"ALTER TABLE `".$dbs['prefix']."forums` ADD `hide_mods_list` INT( 1 ) NOT NULL",
		"ALTER TABLE `".$dbs['prefix']."members` ADD `birthday` INT( 8 ) NOT NULL AFTER `signature`",
		"DELETE FROM ".$dbs['prefix']."stats WHERE name = 'forums'",
		"INSERT INTO ".$dbs['prefix']."stats VALUES ('started', '".$userinfo['regdate']."')",
		"CREATE TABLE ".$dbs['prefix']."searches ( sess_id varchar(32) NOT NULL default '', time int(10) NOT NULL default '0', results text NOT NULL, PRIMARY KEY  (sess_id) )"
	);
	
	$error = false;
	foreach ( $queries as $query ) {
		
		if ( !($db->query($query)) ) {
			
			$error = true;
			break;
			
		}
		
	}
	
	$db->disconnect();
	
	if ( $error ) {
		
		echo '<p>An error occured while executing the SQL queries.</p>';
		echo to_step(3);
		
	} else {
		
		echo '<p>The update queries have been executed succesfully!</p>';
		echo '<p><strong>Important note:</strong> since UseBB 0.5, some important changes have happened to the member system. From now on, a username can only contain alphanumeric characters (a-zA-Z0-9), &quot;_&quot; and &quot;-&quot;. Any other characters will be stripped out of the username (spaces will be transformed to &quot;_&quot;). If no characters are left after stripping, the username will be set to &quot;<em>user</em>&quot;. If any duplicate usernames are created during this process, the duplicate username will be followed by a <em>2</em>, the next one by a <em>3</em>, and so on.</p>';
		echo '<p>Please inform your users if this change affects the users on your board! You might want to change usernames afterwards via your database management tool. Also note a user can now set a publicly displayed name which can contain any characters, including Cyrillic ones. Each user\'s displayed name has already been set to his original username. A user\'s real username (with which he/she needs to log in and which is not shown publicly on the board) can be found between brackets on his/her profile when looking with an admin account.</p>';
		echo to_step(4);
		
	}
	
} elseif ( intval($_POST['step']) === 4 ) {
	
	echo '<h2>Step 4</h2>';
	
	set_magic_quotes_runtime(0);
	$usernames = array();
	$result = $db->query("SELECT id, name FROM ".$dbs['prefix']."members ORDER BY id ASC");
	while ( $out = $db->fetch_result($result) ) {
		
		$original_name = $out['name'];
		$out['name'] = preg_replace('#[^A-Za-z0-9_\-]#', '', str_replace(' ', '_', $out['name']));
		$out['name'] = ( !empty($out['name']) ) ? $out['name'] : 'user';
		
		if ( in_array($out['name'], $usernames) ) {
			
			for ( $i = 2; ; $i++ ) {
				
				$testname = $out['name'].$i;
				
				if ( !in_array($testname, $usernames) ) {
					
					$usernames[] = $out['name'] = $testname;
					break;
					
				}
				
			}
			
		} else {
			
			$usernames[] = $out['name'];
			
		}
		
		if ( $original_name != $out['name'] )
			$db->query("UPDATE ".$dbs['prefix']."members SET name = '".addslashes($out['name'])."' WHERE id = ".$out['id']);
		
	}
	
	$db->disconnect();
	
	echo '<p>All SQL queries have been executed. Your board has now been updated to version 0.5. If the latest release is newer, you might need to run other wizards too. See the UPGRADE document. Otherwise, please delete the directory <code>install/</code> for security reasons. You can now go to <a href="../">your UseBB board</a> and continue using it.</p>';
	echo '<p><strong>Tip:</strong> you might want to use <a href="http://usebb.sourceforge.net/docs/doku.php?id=configuration:administration_without_acp" target="_blank">this manual</a> to further set up your forum.</p>';
	echo '<p>Thanks for choosing UseBB! We wish you a lot of fun with your board!</p>';
	
}

?>
</div>
<address>Copyright &copy; 2003-2005 UseBB Team</address>
</body>
</html>