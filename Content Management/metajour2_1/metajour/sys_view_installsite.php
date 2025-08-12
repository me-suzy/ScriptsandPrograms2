<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

function copyr($source, $dest) {
	if (is_file($source)) {
		return copy($source, $dest);
	}
	if (!is_dir($dest)) {
		mkdir($dest);
	}
	$dir = dir($source);
	while (false !== $entry = $dir->read()) {
		if ($entry == '.' || $entry == '..') {
			continue;
		}
		if ($dest !== "$source/$entry") {
			copyr("$source/$entry", "$dest/$entry");
		}
	}
	$dir->close();
	return true;
}

require_once('basic_view.php');
require_once('basic_field.php');

class sys_view_installsite extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
	}
	
	function titleBar() {
		return '<div class="metatitle">'.$this->shadowtext('Install default site').'</div>';
	}

	function viewStart() {
		return '<div class="metawindow">';
	}
	
	function viewEnd() {
		return '</div>';
	}

	function beforeForm() {
		return '<div class="metabox">';
	}
	
	function afterForm() {
		return '</div>';
	}

	function view() {
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->beforeForm();
		
		if ($this->data['step'] == '2') {
			$obj = owNew('structure');
			$id = $obj->locateByName('main');
			if ($id) {
				$obj->readObject($id);
				$obj->deleteObject();
			}

			$obj = owNew('profile');
			$obj->createObject(array("name" => "standard"));
			$obj->setStandard();
		
			$obj = owNew('stylesheet');
			$obj->createObject(array("name" => "standard"));
			$obj->setStandard();
		
			$obj = owNew('metadata');
			$obj->createObject(array("name" => "standard"));
			$obj->setStandard();
		
			$obj = owNew('currency');
			$obj->createObject(array("name" => "DKK", "currency" => 1));
			$obj->setStandard();
		
			$obj = owNew('vat');
			$obj->createObject(array("name" => "0%", "vat" => 0));
		
			$obj = owNew('vat');
			$obj->createObject(array("name" => "25%", "vat" => 25));
			$obj->setStandard();

			if ($this->data['instsite'] == 'defaultdanish') {
				owImportObjects($this->userhandler->getSystemPath().'standard/defaultdanish/');
				//copyr($this->userhandler->getSystemPath().'standard/defaultdanish/img/',
				//	$this->userhandler->getViewerPath().'img/');
				copyr($this->userhandler->getSystemPath().'standard/defaultdanish/filter/',
					$this->userhandler->getDirFilter());
			}
			if ($this->data['instsite'] == 'defaultenglish') {
				owImportObjects($this->userhandler->getSystemPath().'standard/defaultenglish/');
				//copyr($this->userhandler->getSystemPath().'standard/defaultenglish/img/',
				//	$this->userhandler->getViewerPath().'img/');
				copyr($this->userhandler->getSystemPath().'standard/defaultenglish/filter/',
					$this->userhandler->getDirFilter());
			}
			$result .= '<BR><BR><b>Done!</b>';
		}
		
		if ($this->data['step'] != '2') {
			$result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">';
			$result .= '<input type="hidden" name="_DONTCONVERT_" value="1">';
			$result .= '<input type="hidden" name="step" value="2">';
			$result .= $this->returnMePost();
			$s = '<select name="instsite">
			<option value="defaultenglish">Default english website</option>
			<option value="defaultdanish">Default danish website</option>
			</select>';
			$result .= $this->makeField('Select site to install',$s);
			$result .= $this->returnviewpost($this->view);
			$result .= '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="Create">';
			$result .= '</div>';
			$result .= '</form>';
		}
		$result .= '<br><br><br>';
		$result .= $this->afterForm();
		$result .= $this->viewEnd();;
		return $result;
	}
}

?>