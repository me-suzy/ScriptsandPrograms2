<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: erek_basic_view_menu.php,v 1.6 2005/02/16 05:04:31 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_view_menu.php');

class erek_basic_view_menu extends basic_view_menu {

	function menuAdvanced() {
		parent::menuAdvanced();
		echo "{|".owDatatypeDesc('compunit').",gui.php?view=init&otype=compunit,content}\n";
		echo "{|".owDatatypeDesc('compcountry').",gui.php?view=init&otype=compcountry,content}\n";
		echo "{|".owDatatypeDesc('compdepartment').",gui.php?view=init&otype=compdepartment,content}\n";
		echo "{|".owDatatypeDesc('compcause').",gui.php?view=init&otype=compcause,content}\n";
		echo "{|".owDatatypeDesc('compdecision').",gui.php?view=init&otype=compdecision,content}\n";
		echo "{|".owDatatypeDesc('compsolution').",gui.php?view=init&otype=compsolution,content}\n";
	}
	
	function mainMenu() {        
		echo "{".$this->gl('menu_ecompcreate').",gui.php?view=create&otype=comp&_ret=combi,content}\n";
        $this->topitems++;
		echo "{".$this->gl('menu_ecompactive').",gui.php?view=listactive&otype=comp,content}\n";
        $this->topitems++;
		echo "{".$this->gl('menu_ecompawait').",gui.php?view=listawait&otype=comp,content}\n";
        $this->topitems++;
		echo "{".$this->gl('menu_ecompdone').",gui.php?view=listdone&otype=comp,content}\n";
        $this->topitems++;
		echo "{".$this->gl('menu_ecompclosed').",gui.php?view=listclosed&otype=comp,content}\n";
        $this->topitems++;
		echo "{".$this->gl('menu_ecompstat').",gui.php?view=search&otype=comp,content}\n";
        $this->topitems++;
		$this->menuEbusiness();
		$this->menuAccess();
		$this->menuAdvanced();
		$this->topitems = 9;
	}


}

?>