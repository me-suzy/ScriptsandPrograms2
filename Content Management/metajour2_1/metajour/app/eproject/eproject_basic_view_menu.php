<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage view
 * $Id: eproject_basic_view_menu.php,v 1.4 2005/02/16 05:03:47 jan Exp $
 */

require_once($system_path.'basic_view_menu.php');

class eproject_basic_view_menu extends basic_view_menu {

	function menuAdvanced() {
		parent::menuAdvanced();
		echo "{|".owDatatypeDesc('layoutelement').",gui.php?view=init&otype=layoutelement,content}";
	}
	
	function mainMenu() {
		echo "{".$this->gl('menu_eprojectcreate').",gui.php?view=create&otype=project&_ret=combi,content}";
		echo "{".$this->gl('menu_eprojectactive').",gui.php?view=listactive&otype=project,content}";
		echo "{".$this->gl('menu_eprojectclosed').",gui.php?view=listclosed&otype=project,content}";
		$this->topitems += 3;
		$this->menuAccess();
		echo "\n";
		$this->menuEbusiness();
		echo "\n";
		$this->menuAdvanced();
		echo "\n";
	}


}

?>