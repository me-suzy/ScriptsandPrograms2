<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/templates/default/activetopics.tpl.php,v 1.6 2005/05/05 19:16:32 pc_freak Exp $
	
	This file is part of UseBB.
	
	UseBB is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	UseBB is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with UseBB; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA	02111-1307	USA
*/

//
// Die when called directly in browser
//
if ( !defined('INCLUDED') )
	exit();

//
// Active topics templates
//

$templates['header'] = '
	<table class="maintable">
		<tr>
			<th></th>
			<th>{l_Forum}</th>
			<th>{l_Topic}</th>
			<th>{l_Author}</th>
			<th>{l_Replies}</th>
			<th>{l_Views}</th>
			<th>{l_LatestPost}</th>
		</tr>
';

$templates['topic'] = '
		<tr>
			<td class="icon"><img src="{img_dir}{topic_icon}" alt="{topic_status}" /></td>
			<td class="atforum">{forum}</td>
			<td class="attopic">{topic_name}<div class="topicpagelinks">{topic_page_links}</div></td>
			<td class="author">{author}</td>
			<td class="count">{replies}</td>
			<td class="count">{views}</td>
			<td class="lastpostinfo">{by_author} <a href="{last_post_url}">&gt;&gt;</a><br />{on_date}</td>
		</tr>
';

$templates['footer'] = '
	</table>
';

?>
