<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_combi.php');

class binfile_view_combi extends basic_view_combi {

	function customFields() {
		$result .= '<div class="mformfieldset" style=""><div class="mformlabel" style="">' . $this->gl('label_upload') . '</div><div class="mformfield" style="">';
		$result .= '<input type="file" name="__uploadfile__" style="width: 400px;">';
		$result .= '</div></div>';
		$result .= '<div class="mformfieldset"><div class="mformlabel">' . $this->gl('label_file') .'</div>';
		$result .= '<div class="mformfield"><a href="getfiledl.php?objectid=' . $this->objectid[0] . '" target="_blank"><img src="getfilethumb.php?objectid=' . $this->objectid[0] . '" border="0"></a></div></div>';
		return $result;
	}
	
	function afterForm() {
		$obj = owRead($this->objectid[0]);
		$arr = $obj->GetRevisions();
		$result .= '<div class="mformfieldset" style=""><div class="mformlabel" style="">'.$this->gl('text_1').'</div><div class="mformfield" style="">';
		foreach ($arr as $curr) {
			$result .= '<A HREF="getfiledl.php?objectid='.$this->objectid[0].'&rev='.$curr.'" target="_blank"><img src="getfileicon.php?objectid='.$this->objectid[0].'&rev='.$curr.'" border="0"><strong>&nbsp;'.$this->gl('label_revision').' '.$curr.'</strong></A><BR>';
		}
		$result .= '</div></div>';
		return $result;
	}
		
}
?>