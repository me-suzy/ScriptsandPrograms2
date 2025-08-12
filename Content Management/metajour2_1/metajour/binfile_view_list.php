<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_list.php');

class binfile_view_list extends basic_view_list {

	var $limit = 20;
	function GetInfocolElement($arr,$colname,&$obj) {
		if ($colname == '_icon') {
			return '<div name="_icon" objectid="' . $arr['objectid'] . '"></div>';
		}
		$result = basic_view_list::GetInfocolElement($arr,$colname,$obj);
		return $result;
	}
	
	function getRowEventHandler() {
		$result = parent::getRowEventHandler();
		$result .= '
		<script type="text/javascript">
		function setThumbImage() {
			var elements = document.getElementsByTagName("DIV");
			for (var i = 0; i < elements.length; ++i) {
				var element = elements[i];
				if (element.name == "_icon") {
					var imageElement = document.createElement("IMG");
					imageElement.src = "getfilethumb.php?objectid=" + element.objectid;
					element.appendChild(imageElement);
				}
			}
		}
		</script>
		';
		
		$this->context->addOnload('setThumbImage');
		return $result;
	}
}
?>