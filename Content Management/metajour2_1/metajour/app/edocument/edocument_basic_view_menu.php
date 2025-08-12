<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: edocument_basic_view_menu.php,v 1.3 2005/04/29 05:23:52 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_view_menu.php');

class edocument_basic_view_menu extends basic_view_menu {

	function menuAdvanced() {
		parent::menuAdvanced();
		echo "{|".owDatatypeDesc('edocerrorcode').",gui.php?view=init&otype=edocerrorcode,content}\n";
		echo "{|".owDatatypeDesc('edocresponsible').",gui.php?view=init&otype=edocresponsible,content}\n";
		echo "{|".owDatatypeDesc('edoccorrection').",gui.php?view=init&otype=edoccorrection,content}\n";
        
        #echo "{|".owDatatypeDesc('stiholt_department').",gui.php?view=init&otype=stiholt_department,content}\n";
        #echo "{|".owDatatypeDesc('stiholt_fieldoffice').",gui.php?view=init&otype=stiholt_fieldoffice,content}\n";
        #echo "{|".owDatatypeDesc('stiholt_receivedtype').",gui.php?view=init&otype=stiholt_receivedtype,content}\n";
        #echo "{|".owDatatypeDesc('stiholt_reporttype').",gui.php?view=init&otype=stiholt_reporttype,content}\n";
        #echo "{|".owDatatypeDesc('stiholt_error').",gui.php?view=init&otype=stiholt_error,content}\n";
        #echo "{|".owDatatypeDesc('stiholt_errorgroup').",gui.php?view=init&otype=stiholt_errorgroup,content}\n";
	}

	function menuGeneral() {
		parent::menuGeneral();
		echo "{|-}";
		echo "{|".$this->gl('menu_edocnonapperror').",gui.php?view=listactive&otype=edocform,content}\n";
		echo "{|".$this->gl('menu_edocapperror').",gui.php?view=listclosed&otype=edocform,content}\n";
		echo "{|-}";
		echo "{|".$this->gl('menu_edocstat').",gui.php?view=search&otype=edocform,content}\n";
	}

}

?>