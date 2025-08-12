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

class basic_view_properties extends basic_view {

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_properties');
	}

	function view() {
		$this->_obj = owRead($this->objectid[0]);
		$result .= '<div class="metawindow">';
		ob_start();
		?>
		<div class="metafieldset"><div class="metalabel">PROPERTY</div><div class="metafield"><strong>VALUE</strong></div></div>

		<div class="metafieldset"><div class="metalabel">objectid	  </div><div class="metafield"><?php echo $this->_obj->getobjectid()?></div></div>
		<div class="metafieldset"><div class="metalabel">site		  </div><div class="metafield"><?php echo $this->_obj->getsite()?></div></div>
		<div class="metafieldset"><div class="metalabel">type</div><div class="metafield"><?php echo $this->_obj->gettype()?></div></div>
		<div class="metafieldset"><div class="metalabel">createdby	  </div><div class="metafield"><?php echo $this->_obj->getcreatedby()?></div></div>
		<div class="metafieldset"><div class="metalabel">created	  </div><div class="metafield"><?php echo $this->_obj->getcreated()?></div></div>
		<div class="metafieldset"><div class="metalabel">changedby	  </div><div class="metafield"><?php echo $this->_obj->getchangedby()?></div></div>
		<div class="metafieldset"><div class="metalabel">changed	  </div><div class="metafield"><?php echo $this->_obj->getchanged()?></div></div>
		<div class="metafieldset"><div class="metalabel">checkedby	  </div><div class="metafield"><?php echo $this->_obj->getcheckedby()?></div></div>
		<div class="metafieldset"><div class="metalabel">checked	  </div><div class="metafield"><?php echo $this->_obj->getchecked()?></div></div>
		<div class="metafieldset"><div class="metalabel">publish	  </div><div class="metafield"><?php echo $this->_obj->getpublish()?></div></div>
		<div class="metafieldset"><div class="metalabel">expire		  </div><div class="metafield"><?php echo $this->_obj->getexpire()?></div></div>
		<div class="metafieldset"><div class="metalabel">childorder	  </div><div class="metafield"><?php echo $this->_obj->getchildorder()?></div></div>
		<div class="metafieldset"><div class="metalabel">parentid	  </div><div class="metafield"><?php echo $this->_obj->getparentid()?></div></div>
		<div class="metafieldset"><div class="metalabel">language	  </div><div class="metafield"><?php echo $this->_obj->getlanguage()?></div></div>
		<div class="metafieldset"><div class="metalabel">approved	  </div><div class="metafield"><?php echo $this->_obj->isapproved()?></div></div>
		<div class="metafieldset"><div class="metalabel">active		  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['active']?></div></div>
		<div class="metafieldset"><div class="metalabel">readonly	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['readonly']?></div></div>
		<div class="metafieldset"><div class="metalabel">haschild	  </div><div class="metafield"><?php echo $this->_obj->haschild()?></div></div>
		<div class="metafieldset"><div class="metalabel">haspermission	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['haspermission']?></div></div>
		<div class="metafieldset"><div class="metalabel">deleted	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['deleted']?></div></div>
		<div class="metafieldset"><div class="metalabel">futurerevisionof </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['futurerevisionof']?></div></div>
		<div class="metafieldset"><div class="metalabel">hasfuturerevision</div><div class="metafield"><?php echo $this->_obj->elements[0][object]['hasfuturerevision']?></div></div>
		<div class="metafieldset"><div class="metalabel">hascategory	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['hascategory']?></div></div>
		<div class="metafieldset"><div class="metalabel">hasextradata	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['hasextradata']?></div></div>
		<div class="metafieldset"><div class="metalabel">hasrules	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['hasrules']?></div></div>
		<div class="metafieldset"><div class="metalabel">hasvariant	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['hasvariant']?></div></div>
		<div class="metafieldset"><div class="metalabel">variantof	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['variantof']?></div></div>
		<div class="metafieldset"><div class="metalabel">oldrevisionof  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['oldrevisionof']?></div></div>
		<div class="metafieldset"><div class="metalabel">hasoldrevision	  </div><div class="metafield"><?php echo $this->_obj->elements[0][object]['hasoldrevision']?></div></div>
		<div class="metafieldset"><div class="metalabel">standard</div><div class="metafield"><?php echo $this->_obj->elements[0][object]['standard']?></div></div>
		<div class="metafieldset"><div class="metalabel">webhidden</div><div class="metafield"><?php echo $this->_obj->elements[0][object]['webhidden']?></div></div>
		<div class="metafieldset"><div class="metalabel">syshidden</div><div class="metafield"><?php echo $this->_obj->elements[0][object]['syshidden']?></div></div>
		<div class="metafieldset"><div class="metalabel">useapp</div><div class="metafield"><?php echo $this->_obj->elements[0][object]['useapp']?></div></div>
		<br><br>
<?php
		$result .= ob_get_contents();
		ob_end_clean();
		$result .= '</div>';
		return $result;
	}

}

?>