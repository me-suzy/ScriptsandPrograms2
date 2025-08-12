<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');
require_once('basic_field.php');

class sys_view_package extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('sys_view_package');
	}

	function titleBar() {
		return '<div class="metatitle">'.$this->shadowtext("Importer objektpakke").'</div>';
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

function unzip($file, $path) {
  $zip = zip_open($file);
  if ($zip) {
   while ($zip_entry = zip_read($zip)) {
     if (zip_entry_filesize($zip_entry) > 0) {
       // str_replace must be used under windows to convert "/" into "\"
       $complete_path = $path.str_replace('/','\\',dirname(zip_entry_name($zip_entry)));
       $complete_name = $path.str_replace ('/','\\',zip_entry_name($zip_entry));
       if(!file_exists($complete_path)) { 
         $tmp = '';
         foreach(explode('\\',$complete_path) AS $k) {
           $tmp .= $k.'\\';
           if(!file_exists($tmp)) {
             mkdir($tmp, 0777); 
           }
         } 
       }
       if (zip_entry_open($zip, $zip_entry, "r")) {
         $fd = fopen($complete_name, 'w');
         fwrite($fd, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)));
         fclose($fd);
         zip_entry_close($zip_entry);
       }
     }
   }
   zip_close($zip);
  }
}

function delDir($dirName) {
   if(empty($dirName)) {
       return false;
   }
   if(file_exists($dirName)) {
       $dir = dir($dirName);
       while($file = $dir->read()) {
           if($file != '.' && $file != '..') {
               if(is_dir($dirName.'/'.$file)) {
                   $this->delDir($dirName.'/'.$file);
               } else {
                   @unlink($dirName.'/'.$file) or die('File '.$dirName.'/'.$file.' couldn\'t be deleted!');
               }
           }
       }
       $dir->close();
       @rmdir($dirName) or die('Folder '.$dirName.' couldn\'t be deleted!');
   } else {
       return false;
   }
}

function copyr($source, $dest) {
	if (!file_exists($source)) return true;
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
			$this->copyr("$source/$entry", "$dest/$entry");
		}
	}
	$dir->close();
	return true;
}

	function view() {
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->beforeForm();
		$result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validateForm(this);" style="margin: 0px; padding: 0px;">';
		$result .= '<input type="hidden" name="_DONTCONVERT_" value="1">';
		$result .= $this->returnMePost();
		if(!isset($this->data['step'])) {
			$result .= '<input type="hidden" name="step" value="2">';
		}

		if ($this->data['step'] == '2') {
			if ($_FILES['__uploadfile__']['tmp_name']) {
				$r = pathinfo($_FILES['__uploadfile__']['tmp_name']);
				
				$keychars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$length = 40;
				
				// RANDOM KEY GENERATOR
				$randkey = "";
				$max=strlen($keychars)-1;
				for ($i=0;$i<=$length;$i++) {
				  $randkey .= substr($keychars, rand(0, $max), 1);
				}
				
				$tmpdir = dirname($_FILES['__uploadfile__']['tmp_name']).'/'.$randkey;
				if (mkdir($tmpdir,0777)) {
					$this->unzip($_FILES['__uploadfile__']['tmp_name'],$tmpdir.'/');
					owImportObjects($tmpdir.'/');
					$this->copyr($tmpdir.'/img',$this->userhandler->getViewerPath().'img/');
					$this->copyr($tmpdir.'/filter',$this->userhandler->getDirFilter());
					$this->deldir($tmpdir);
				}
			}			
		} elseif(!isset($this->data['step'])) {
			if (function_exists('zip_open')) {
				$result .= $this->makeField("Vælg objektpakke",'<input type="file" name="__uploadfile__">');
			} else {
				$result .= "ZIP extension not installed in your php.ini";
			}
		}
		$result .= $this->returnviewpost($this->view);
		if (function_exists('zip_open')) {
			$result .= '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="Næste ->">';
			$result .= '</div>';
		}
		$result .= '</form>';
		$result .= '<br><br><br>';
		$result .= $this->afterForm();
		$result .= $this->viewEnd();;
		return $result;
	}
}

?>