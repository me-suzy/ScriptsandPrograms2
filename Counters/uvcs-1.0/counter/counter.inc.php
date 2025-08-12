<?php
/*
  +-----------------------------------------------------------------------------+
  | [counter.inc.php]                                                           |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  |               U V C S   1 . 0                                               |
  |               ===============                                               |
  |                                                                             |
  |               Urkburk Visitor Counting System                               |
  |                                                                             |
  |                                                                             |
  |               By:       Simon Lundmark                                      |
  |               Website:  http://www.urkburk.com/                             |
  |               Email:    simon@urkburk.com                                   |
  |               MSN:      simon@urkburk.com                                   |
  |               ICQ:      29537272                                            |
  |                                                                             |
  +-----------------------------------------------------------------------------+
  | LICENSE                                                                     |
  +-----------------------------------------------------------------------------+
  |                                                                             |
  | Urkburk Visitor Counting System 1.0                                         |
  | Copyright (C) 2004  Simon Lundmark                                          |
  |                                                                             |
  | This program is free software; you can redistribute it and/or               |
  | modify it under the terms of the GNU General Public License                 |
  | as published by the Free Software Foundation; either version 2              |
  | of the License, or (at your option) any later version.                      |
  |                                                                             |
  | This program is distributed in the hope that it will be useful,             |
  | but WITHOUT ANY WARRANTY; without even the implied warranty of              |
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
  | GNU General Public License for more details.                                |
  |                                                                             |
  | You should have received a copy of the GNU General Public License           |
  | along with this program; if not, write to the Free Software                 |
  | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
  |                                                                             |
  +-----------------------------------------------------------------------------+
*/



// VARIABLES
// ==============================================================================

$UVCS['variables']['file'] = './counter/data/counter.txt';



// DO NOT EDIT BELOW
// ==============================================================================

/* Read the file */
$UVCS['count'] = @file_get_contents($UVCS['variables']['file']) or die('Could not read file: "' . $UVCS['variables']['file'] . '". Please contact webmaster.');

/* Start a new session */
session_start();

/* Check session */
if (!isset($_SESSION['uvcs_count'])) {
	
	/* Set session */
	$_SESSION['uvcs_count'] = '';
	
	/* Increase count */
	$UVCS['count']++;
	
	/* Open file for writing */
	$UVCS['handle'] = @fopen($UVCS['variables']['file'], 'w') or die('Could not open file for writing: "' . $UVCS['variables']['file'] . '". Please contact webmaster.');
	
	/* Write to file */
	@fwrite($UVCS['handle'], $UVCS['count']) or die('Could not write to file: "' . $UVCS['variables']['file'] . '". Please contact webmaster.');
	
	/* Close the file */
	fclose($UVCS['handle']);
}
?>