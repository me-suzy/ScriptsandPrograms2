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

class absfile extends basic {

	function absfile() {
		$this->basic();
	}

	function geticon() {
		switch ($this->elements[0]['mimetype']) {
			case "image/bmp":
			case "image/gif":
			case "image/jpeg":
			case "image/pjpeg":
			case "image/png":
			case "image/x-png":
			case "image/tiff":
				return $this->userhandler->getSystemUrl().'image/mimetype/image.png';
			default:
				return $this->userhandler->getSystemUrl().'image/mimetype/document.png';
		}
	}
	
	function getthumb($w=100,$h=0,$auto=0) {
		switch ($this->elements[0]['mimetype']) {
			case "image/bmp":
			case "image/gif":
			case "image/jpeg":
			case "image/pjpeg":
			case "image/png":
			case "image/x-png":
			case "image/tiff":
				$ext = "_".$w."x".$h."x".$auto;
				$cachedir = $this->userhandler->getDirBinfileCache();
				$binfiledir = $this->getRoot();
				$result = '';
				if (!file_exists($cachedir)) {
					if(!@mkdir($cachedir, 0755)) {
						$this->errorcode = $this->ERR_CANNOTMAKEDIR;
						return $this->errorcode;
					}
				}
				$objectid = $this->elements[0]['objectid'];
				## $binfiledir . substr($objectid,-2).'/'.$objectid
				if ( !file_exists($cachedir . $objectid.$ext) ||
				filemtime($this->getPhysicalFile()) > filemtime($cachedir . $objectid . $ext)) {
					require_once($this->userhandler->getSystemPath().'core/util/class.imgresize.php');
					$thumb = new imgresize($this->getPhysicalFile(),$this->elements[0]['mimetype']);
					if ($thumb->img['ok']) {
						if ($auto > 0) {
							$thumb->size_auto($auto);
						} else {
							if ($w > 0) $thumb->size_width($w);
							if ($h > 0) $thumb->size_height($h);
						}
						$thumb->jpeg_quality(100);
						$thumb->save($cachedir . $objectid.$ext);
						$result = $cachedir . $objectid.$ext;
					}
				} else {
					$result = $cachedir . $objectid.$ext;
				}
				
				if (!function_exists("imagegif") && $this->elements[0]['mimetype'] == 'image/gif')
					$result = $this->userhandler->getSystemPath().'image/mimetype/image.png';
				
				return $result;
				break;
		
			default:
				return $this->userhandler->getSystemPath().'image/mimetype/document.png';
		}
	}

}