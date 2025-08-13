<?php
/***************************************************************************
 *                      Olate Download v2 - Download Manager
 *
 *                           http://www.olate.com
 *                            -------------------
 *   author                : David Mytton
 *   copyright             : (C) Olate 2003 
 *
 *   Support for Olate scripts is provided at the Olate website. Licensing
 *   information is available in the license.htm file included in this
 *   distribution and on the Olate website.                  
 ***************************************************************************/

// Create $config array
$config = array();
$config['language']				= get_config('language');
$config['urlpath']				= get_config('urlpath');
$config['version']				= get_config('version');

$config['alldownloads']			= get_config('alldownloads');
$config['notopdownloads']		= get_config('notopdownloads');
$config['pages']				= get_config('pages');
$config['ratings']				= get_config('ratings');
$config['searchlink']			= get_config('searchlink');
$config['sorting']				= get_config('sorting');
$config['topdownloadslink']		= get_config('topdownloadslink');


?>