<?php
/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: admin.php
-----------------------------------------------------
 Purpose: Enables the Control panel to be accessed
 from any directory.
 Put this file (and a copy of path.php) in any
 directory and the control panel will be accessible
 from there.  This allows people into the CP without
 knowing the location of your "system" directory
=====================================================
*/

//  DO NOT ALTER THIS FILE IN ANY WAY!!

$pathinfo = pathinfo(__FILE__);

$ext  = ( ! isset($pathinfo['extension'])) ? '.php' : '.'.$pathinfo['extension'];

$self = ( ! isset($pathinfo['basename'])) ? 'index'.$ext : $pathinfo['basename'];
	
unset($system_path);
unset($config_file);

require 'path'.$ext;

if ( ! ereg("/$", $system_path)) $system_path .= '/';

require $system_path.'core/core.system'.$ext;

?>