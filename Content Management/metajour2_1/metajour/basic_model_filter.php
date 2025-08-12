<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once(dirname(__FILE__) . '/basic_model.php');

class basic_model_filter extends basic_model {

	function preparsertf($filename) {
		$content = file_get_contents($filename);
		$content=str_replace('<<<','¶',$content);
		$content=str_replace('\lquote ','\'',$content);
		$content=str_replace('\rquote ','\'',$content);
		$content=str_replace('>>>','§',$content);
		
		if (!unlink($filename)) {
			$this->errorhandler->seterror('filter_unlink');
			return;
		}
		
		if (($handle = @fopen($filename,"ab")) === false) {
			$this->errorhandler->seterror('filter_createfile');
			return;
		}
		
		if ((@fwrite($handle, $content)) === false) {
			$this->errorhandler->seterror('filter_writefile');
			return;
		}
		
		@fclose($handle);
	}

	/**
	 * Return all data in textual form
	 * @param objectid array of objectids
	 * @return array of objects as read by owReadExpand
	 * @see owReadExpand
	 */
	function getData($objectid) {
		$result = array();
		foreach ($objectid as $curid) {
			$result[] = owReadExpand($curid);
		}
		return $result;
	}
	
	function parsefilter($objectid, $filterid=0, $filterfilename='', $filterfiletype='') {		
		$smarty =& $this->userhandler->getSmarty();
		if (!$filterfilename) $smarty->default_resource_type = 'filter';
		if ($filterfiletype == 'rtf') {
			/**
			* @todo: fix me
			**/
			if ($filterfilename) { 
				$this->preparsertf($filterfilename);
				if ($this->errorhandler->haserror()) {
					// Bail out
					return;
				}
			}
			$smarty->left_delimiter = '¶';
			$smarty->right_delimiter = '§';
		}
		
		$result = $this->getData($objectid);
				
		$smarty->assign("user",$this->userhandler->GetSmartyVars());
		$smarty->assign("data",$result);
		if ($filterfilename) {
			return $smarty->fetch($filterfilename);
		} else {
			return $smarty->fetch($filterid);
		}
	}

	function model() {
		$this->context->clearall(); #TODO
		if ($this->data['filterid']) {
			
			$uploadfile = $this->userhandler->getDirFilterUpload() . $_FILES['userfile']['name'];	
			$obj = owRead($this->data['filterid']);
		
			include_once($this->userhandler->getSystemPath().'core/util/class.mimetypes.php');
			$mime =& new Mime_Types($this->userhandler->getSystemPath().'core/util/mime.types');

			$mimetype = $obj->elements[0]['mimetype'];
			if (!$mimetype) $mimetype = 'application/octetstream';
			$name = $obj->elements[0]['name'].".".$mime->get_extension($obj->elements[0]['mimetype']);
		
			if ($this->data['uploadfile']) {
				if ($obj->elements[0]['filterfiletype'] == 'rtf') $this->preparsertf($this->data['uploadfile']);
				$result = $this->parsefilter($this->objectid,$this->data['filterid'],$this->data['uploadfile'],$obj->elements[0]['filterfiletype']);
			} elseif($obj->elements[0]['filtertype'] == 5) {
				# TODO - no conversion on each use
				if ($obj->elements[0]['filterfiletype'] == 'rtf') $this->preparsertf($this->userhandler->getDirFilter().$obj->elements[0]['binfileid']);
				
				$result = $this->parsefilter($this->objectid,$this->data['filterid'],$this->userhandler->getDirFilter().$obj->elements[0]['binfileid'],$obj->elements[0]['filterfiletype']);
			} else {
				$result = $this->parsefilter($this->objectid,$this->data['filterid']);
			}
			
			if (!$this->errorhandler->haserror()) {
				header("Pragma: no-cache");
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
				header('Cache-Control: no-store, no-cache, must-revalidate');
				header('Cache-Control: pre-check=0, post-check=0, max-age=0');
				header('Content-Transfer-Encoding: none');
				header('Content-Type: '.$mimetype.'; name="' . $name . '"'); // This should work for IE & Opera
				header('Content-Disposition: inline; filename="' . $name . '"');
				echo $result;
			}
		}
	}	
}

?>