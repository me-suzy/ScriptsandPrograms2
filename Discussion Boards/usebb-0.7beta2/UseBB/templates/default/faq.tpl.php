<?php

/*
	Copyright (C) 2003-2005 UseBB Team
	http://www.usebb.net
	
	$Header: /cvsroot/usebb/UseBB/templates/default/faq.tpl.php,v 1.6 2005/05/05 23:40:52 pc_freak Exp $
	
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
// FAQ templates
//

$templates['contents_header'] = '
	<table class="maintable">
		<tr>
			<th>{l_FAQ}</th>
		</tr>
		<tr>
			<td id="faq-contents">
';

$templates['contents_cat_header'] = '
				<h3>{cat_name}</h3>
				<ul>
';

$templates['contents_question'] = '
					<li><a href="{question_link}">{question_title}</a></li>
';

$templates['contents_cat_footer'] = '
				</ul>
';

$templates['contents_footer'] = '
			</td>
		</tr>
	</table>
';

$templates['question'] = '
	<table class="maintable">
		<tr>
			<th>{question_title}</th>
		</tr>
		<tr>
			<td id="question">{question_answer}</td>
		</tr>
	</table>
';

?>
