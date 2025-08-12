<?php

// $Id: index.php 10 2005-09-04 08:59:31Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'addons');

// Setup template object
$template = new Template(ADMIN_PATH.'/addons');
$template->set_file('page', 'template.html');
$template->set_block('page', 'main_block', 'main');

// Insert values into the template object
$template->set_var(array(
								'ADMIN_URL' => ADMIN_URL,
								'WB_URL' => WB_URL
								)
						);

// Insert permission values into the template object
if($admin->get_permission('modules') != true) {
	$template->set_var('DISPLAY_MODULES', 'none');
}
if($admin->get_permission('templates') != true) {
	$template->set_var('DISPLAY_TEMPLATES', 'none');
}
if($admin->get_permission('languages') != true) {
	$template->set_var('DISPLAY_LANGUAGES', 'none');
}

// Insert section names and descriptions
$template->set_var(array(
								'MODULES' => $MENU['MODULES'],
								'TEMPLATES' => $MENU['TEMPLATES'],
								'LANGUAGES' => $MENU['LANGUAGES'],
								'MODULES_OVERVIEW' => $OVERVIEW['MODULES'],
								'TEMPLATES_OVERVIEW' => $OVERVIEW['TEMPLATES'],
								'LANGUAGES_OVERVIEW' => $OVERVIEW['LANGUAGES']
								)
						);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>