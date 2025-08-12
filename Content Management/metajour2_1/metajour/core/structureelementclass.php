<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('basicclass.php');

class structureelement extends basic {

	function structureelement() {
		$this->basic();
		$this->setsupertype('structure');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('pageid',F_REL,'relationcreate','document');
		$this->addcolumn('url',F_LITERAL,'string');
		$this->addcolumn('binfile1',F_REL,'splitselect','binfile');
		$this->addcolumn('binfile2',F_LITERAL,'hidden');
		$this->addcolumn('binfile3',F_LITERAL,'hidden');
		$this->addcolumn('showtype',F_COMBO,'combo');
		$this->addcolumn('target',F_LITERAL,'string');
		$this->addcolumn('image1',F_LITERAL,'hidden'); # compatibility
		$this->addcolumn('image2',F_LITERAL,'hidden'); # compatibility
		$this->addcolumn('image3',F_LITERAL,'hidden'); # compatibility
		$this->addcolumn('templateid',F_LITERAL,'hidden'); #compatibility - relation to template
		$this->addcolumn('structureid',F_LITERAL,'hidden'); #compatibility - relation to structure

		$this->removeview('category');
	}

	function initLayout() {
		parent::initLayout();
		$this->addcolumnstyle('showtype','width: 357px;');
	}
	
}
