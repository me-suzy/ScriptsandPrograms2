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

class basic_view_filter extends basic_view {
	
	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_filter');
	}

	function view() {
		$uploaddir = $this->userhandler->getDirFilterUpload();
		$uploadfile = $uploaddir . $_FILES['userfile']['name'];	
		$obj = owRead($this->data['filterid']);
		if ($obj->elements[0]['filtertype'] == '10') { #userfile
			if ($_POST['do'] == 'upload' && move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
				$newwindow = $this->callgui('',$this->objectid,'filter','','','','','filterid='.$this->data['filterid'].'&uploadfile='.$uploadfile);
				?>
				<SCRIPT LANGUAGE="JavaScript">
				top.window.dialogArguments.open('<?php echo $newwindow ?>','window3','');
				window.close();
				</script>
				<?php
			} else {
				?>
				<BR><BR>
				<form enctype="multipart/form-data" method="post">
				<?php echo $this->ReturnMePost(); ?>
				 <input type="hidden" name="do" value="upload" />
				 <input type="hidden" name="filterid" value="<?php echo $this->data['filterid'] ?>" />
				 &nbsp;&nbsp;<?php echo $this->gl('text_1') ?><input name="userfile" type="file" style="width: 300px;"/>
				 <input type="submit" value="Upload" />
				</form>
				<?php
			}
			
		} else {
			$newwindow = $this->callgui('',$this->objectid,'filter','','','','','filterid='.$this->data['filterid']);
			?>
			<SCRIPT LANGUAGE="JavaScript">
			top.window.dialogArguments.open('<?php echo $newwindow ?>','window3','');
			window.close();
			</script>
			<?php
		}
	}
}

?>