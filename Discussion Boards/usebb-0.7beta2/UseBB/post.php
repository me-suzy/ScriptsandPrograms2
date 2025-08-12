<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/post.php,v 1.9 2005/09/15 15:46:45 pc_freak Exp $
	
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
 * Post doorway
 *
 * Forms the doorway to posting topics and posts.
 *
 * @author	UseBB Team
 * @link	http://www.usebb.net
 * @license	GPL-2
 * @version	$Revision: 1.9 $
 * @copyright	Copyright (C) 2003-2005 UseBB Team
 * @package	UseBB
 */

define('INCLUDED', true);
define('ROOT_PATH', './');

//
// Include usebb engine
//
require(ROOT_PATH.'sources/common.php');

//
// Include the right file for either topic or reply posting...
//
if ( !empty($_GET['topic']) && valid_int($_GET['topic']) ) {
	
	require(ROOT_PATH.'sources/post_reply.php');
	
} elseif ( !empty($_GET['forum']) && valid_int($_GET['forum']) ) {
	
	require(ROOT_PATH.'sources/post_topic.php');
	
} else {
	
	//
	// There's no ID! Get us back to the index...
	//
	$functions->redirect('index.php');
	
}

?>