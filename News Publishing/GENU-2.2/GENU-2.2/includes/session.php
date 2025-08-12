<?php
// -------------------------------------------------------------
//
// $Id: session.php,v 1.8 2005/04/03 14:16:41 raoul Exp $
//
// Copyright:	(C) 2003-2005 Raoul Proença <raoul@genu.org>
// License:	GNU GPL (see COPYING)
// Website:	http://genu.org/
//
// -------------------------------------------------------------

function sess_open()
{
	return true;
}

function sess_close()
{
	return true;
}

function sess_read($id)
{
	global $sql;
	$sql->query('SELECT session_value
			FROM ' . TABLE_SESSIONS . '
			WHERE session_id = \'' . $id . '\' AND session_expiry > \'' . time() . '\'');
	$table_sessions = $sql->fetch();
	if ($table_sessions['session_value'])
	{
		return $table_sessions['session_value'];
	}
	else
	{
		return 'user_id|s:1:"0";';
	}
}

function sess_write($id, $sess_data)
{
	global $sql;
	$sql->query('SELECT session_id
			FROM ' . TABLE_SESSIONS . '
			WHERE session_id = \'' . $id . '\'');
	$table_sessions = $sql->fetch();
	if ($table_sessions['session_id'])
	{
		$sql->query('UPDATE ' . TABLE_SESSIONS . '
				SET session_value = \'' . $sess_data . '\'
				WHERE session_id = \'' . $id . '\'');
	}
	else
	{
		$sql->query('INSERT INTO ' . TABLE_SESSIONS . ' (session_id, session_expiry)
				VALUES (\'' . $id . '\', \'' . (time() + 3600) . '\')');
	}
	return true;
}

function sess_destroy($id)
{
	global $sql;
	$sql->query('UPDATE ' . TABLE_USERS . '
			SET user_lastvisit = \'' . time() . '\'
			WHERE user_id = \'' . $_SESSION['user_id'] . '\'');
	$sql->query('DELETE FROM ' . TABLE_SESSIONS . '
			WHERE session_id = \'' . $id . '\'');
	return true;
}

function sess_gc($maxlifetime)
{
	global $sql;
	$sql->query('DELETE FROM ' . TABLE_SESSIONS . '
			WHERE session_expiry < \'' . (time() + $maxlifetime) . '\'');
	return true;
}

session_set_save_handler('sess_open', 'sess_close', 'sess_read', 'sess_write', 'sess_destroy', 'sess_gc');
session_start();

?>