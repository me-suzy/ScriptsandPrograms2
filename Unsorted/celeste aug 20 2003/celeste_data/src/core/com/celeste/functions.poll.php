<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */


function voted($pollid, $userid) {
	$fp = fopen(set_invisible_path . '/poll/'. $pollid . '.poll', 'r');
	$voters = explode("\n", fread($fp, filesize(set_invisible_path . '/poll/'. $pollid . '.poll')));
	fclose($fp);
	
	$len = strlen($userid . '|');
	foreach($voters as $voter) {
		if(substr($voter, 0, $len) == $userid.'|') return true;
	}
	return false;
}
