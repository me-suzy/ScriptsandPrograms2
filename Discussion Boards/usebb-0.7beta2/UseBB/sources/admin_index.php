<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/sources/admin_index.php,v 1.12 2005/09/15 15:46:45 pc_freak Exp $
	
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

/**
 * ACP index
 *
 * Shows the ACP index with general information.
 *
 * @author	UseBB Team
 * @link	http://www.usebb.net
 * @license	GPL-2
 * @version	$Revision: 1.12 $
 * @copyright	Copyright (C) 2003-2005 UseBB Team
 * @package	UseBB
 * @subpackage	ACP
 */

//
// Die when called directly in browser
//
if ( !defined('INCLUDED') )
	exit();

$content = '<p>'.$lang['IndexWelcome'].'</p>

<h2>'.$lang['IndexSystemInfo'].'</h2>
<ul>
	<li>'.$lang['IndexUseBBVersion'].': '.USEBB_VERSION.' (<a href="'.$functions->make_url('admin.php', array('act' => 'version')).'">'.$lang['Item-version'].'</a>)</li>
	<li>'.$lang['IndexPHPVersion'].': '.phpversion().'</li>
	<li>'.$lang['IndexSQLServer'].': '.join('/', $db->get_server_info()).'</li>
	<li>'.$lang['IndexHTTPServer'].': '.$_SERVER['SERVER_SOFTWARE'].'</li>
	<li>'.$lang['IndexOS'].': '.( ( array_key_exists('OS', $_ENV) ) ? $_ENV['OS'] : PHP_OS ).'</li>
</ul>

<h2>'.$lang['IndexLinks'].'</h2>
<ul>
	<li><a href="http://www.usebb.net/">UseBB Homepage</a></li>
	<li><a href="http://www.usebb.net/support/">UseBB Support</a></li>
	<li><a href="http://www.usebb.net/community/">UseBB Community</a></li>
	<li><a href="http://usebb.sourceforge.net/">UseBB Development</a></li>
</ul>
<p>Copyright &copy; 2003-2005 UseBB Team</p>';

$admin_functions->create_body('index', $content);

?>
