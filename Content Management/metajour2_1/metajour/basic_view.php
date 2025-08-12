<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */
require_once('basic_error.php');
require_once('basic_user.php');

class basic_view {
	var $context;
	var $otype;
	var $errorhandler;
	var $userhandler;
	var $data;
	var $objectid;
	var $ret;
	var $parentid;
	var $relcol;
	var $relval;
	var $view;
	var $lang = array();
	
	function basic_view() {
		$this->errorhandler =& GetErrorHandler();
		$this->userhandler =& GetUserHandler();
		$this->classviews = array();
	}

	function glGetLabel($column) {
		return owLabel($this->otype,$column);
	}

	/**
	 * @return string with text from the language file
	 * @param $index is name of the string from the language file
	 */	
	function gl($index) {
		return $this->lang[$index];
	}

	function loadLangFile($file) {
		$langfiles = locateLangFiles($file);
		foreach ($langfiles as $langfile) {
			$LANG = array();
			include($langfile);
			$this->lang = array_merge($this->lang, $LANG);
		}
	}
	
	function loadLanguage() {
		$this->loadLangFile('basic_view');
		if (!empty($this->otype)) $this->loadLangFile($this->otype);
	}
	
	
	/**
	 * @return bool true if the current user has access to the view defined by $view
	 * @param $view string with name of view
	 */
	function canView($view) {
		if ($view == 'tree' || $view == 'menu') $view = 'list';
		if (empty($this->classviews)) {
			$obj = owNew($this->otype);
			if ($obj) $this->classviews = $obj->getviews();
		}
		return in_array($view, $this->classviews) && ($this->userhandler->GetLevel() >= 40 || $this->userhandler->GetProfileView($this->otype,$view));
	}
	

	/**
	 * @return string with semicolon separeted list of objectids
	 * @param $objectid array of or single objectid
	 */
	function objectidString($objectid) {
		if (!is_array($objectid)) $objectid = array($objectid);
		if (!empty($objectid)) return implode(',',$objectid);
	}
	
	/**
	 * @return string javascript with modeless dialog call
	 */
	function ModelessDialog($otype,$objectid,$cmd,$view,$ret='',$parentid='',$data='', $width=450, $height=130) {
		return "launchModeless('".$this->userhandler->getSystemUrl()."dialogwrapper.php?" . $this->callgui($otype,$objectid,$cmd,$view,'dialog',$ret,$parentid,$data) . "', $width, $height)";
	}

	function ListDialog($otype,$objectid,$cmd,$view,$ret='',$parentid='',$data='', $win='window') {
		return $win.".showModalDialog('".$this->userhandler->getSystemUrl()."dialogwrapper.php?".$this->callgui($otype,$objectid,$cmd,$view,'',$ret,$parentid,$data)."&__list=1'," . $win . ",
                             'font-family:Verdana; font-size:12; dialogWidth:1000px; dialogHeight:700px; help: no; status: no; scroll: no');";
	}

	/**
	 * @return string javascript with modal WINDOW call
	 */
	function ModalWindowLarge($otype,$objectid,$cmd,$view,$ret='',$parentid='',$data='', $width=1000, $height=700) {
		return "showModalDialog('".$this->userhandler->getSystemUrl()."dialogwrapper.php?".$this->callgui($otype,$objectid,$cmd,$view,'largedialog',$ret,$parentid,$data)."&__list=1',window,
		'font-family:Verdana; font-size:12; dialogWidth:${width}px; dialogHeight:${height}px; help: no; status: no; scroll: no');";
	}

	/**
	 * @return string javascript with modal DIALOG call
	 */
	function ModalDialog($otype,$objectid,$cmd,$view,$ret='',$parentid='',$data='', $width=500, $height=250) {
		return "showModalDialog('".$this->userhandler->getSystemUrl()."dialogwrapper.php?".$this->callgui($otype,$objectid,$cmd,$view,'dialog',$ret,$parentid,$data)."',window,
		'font-family:Verdana; font-size:12; dialogWidth:${width}px; dialogHeight:${height}px; help: no; status: no; scroll: no');";
	}


	/**
	 * @return string javascript with modal dialog call where the objectid string is based on the o_id javascript variable
	 */
	function ModalDialogDynamic($cmd,$view,$ret='',$parentid='',$data='', $width=500, $height=250, $scroll='no') {
		return "showModalDialog('".$this->userhandler->getSystemUrl()."dialogwrapper.php?".$this->callguidynamic($cmd,$view,'dialog',$ret,$parentid,$data)."',window,'font-family:Verdana; font-size:12; dialogWidth:${width}px; dialogHeight:${height}px; help: no; status: no; scroll: ${scroll}');";
	}

#################### NEW GENERIC METHODS

	function getGuiDynamic($arr) {
		$result = $this->getGuiUrl().'objectid=\'+o_id+\'&';
		if (isset($arr['cmd'])) $result .= "cmd=".$arr['cmd']."&";
		if (isset($arr['view'])) $result .= "view=".$arr['view']."&";
		if (isset($arr['context'])) $result .= "_context=".$arr['context']."&";
		if (isset($arr['ret'])) $result .= "_ret=".$arr['ret']."&";
		if (isset($arr['parentid'])) $result .= "_parentid=".$arr['parentid']."&";
		if (isset($arr['data'])) $result .= $arr['data'];
		return $result;
	}

	function getModalDynamic($arr) {
		if (!isset($arr['width'])) $arr['width'] = 500;
		if (!isset($arr['height'])) $arr['height'] = 250;
		if (!isset($arr['scroll'])) $arr['scroll'] = 'no';
		return "showModalDialog('dialogwrapper.php?".$this->getGuiDynamic($arr).
						"_width=".($arr['width']-12)."&_height=".($arr['height']-35)."&_scroll=".$arr['scroll'].
						"',window,'font-family:Verdana; font-size:12; dialogWidth:".$arr['width']."px; dialogHeight:".$arr['height']."px; help: no; status: no; scroll: no;');";
	}

#####################

	/**
	 * @return string javascript with modeless dialog call where the objectid string is based on the o_id javascript variable
	 */
	function ModelessDialogDynamic($cmd,$view,$ret='',$parentid='',$data='') {
		return "launchModeless('dialogwrapper.php?".$this->callguidynamic($cmd , $view, 'dialog' , $ret, $parentid, $data) . "', 450, 130)";
	}

	/**
	 * @return string javascript with modeless dialog call where the objectid string is based on the o_id javascript variable
	 */
	function ModelessDialogDynamicLarge($cmd,$view,$ret='',$parentid='',$data='') {
		return "launchModeless('dialogwrapper.php?".$this->callguidynamic($cmd , $view, 'dialog' , $ret, $parentid, $data) . "', 450, 300)";
	}

	/**
	 * @return string javascript with modeless dialog call where the objectid string is based on the o_id javascript variable
	 */
	function ModelessDialogDynamicVeryLarge($cmd,$view,$ret='',$parentid='',$data='') {
		return "launchModeless('dialogwrapper.php?".$this->callguidynamic($cmd , $view, 'dialog' , $ret, $parentid, $data) . "', 450, 400)";
	}


	/**
	 * @return string with image tag with correct title and onclick event defined by $event
	 * @param $event is often a referer to this->callgui or this->callguidynamic
	 */
	function ButtonOnclick($image, $title, $event) {
	return '<img src="image/view/'.$image.'" title="'.$title.'" onclick="' . $event . '" 
	onmouseover="this.style.padding=\'5px\';this.style.borderBottom= \'buttonshadow solid 2px\'; 
	this.style.borderLeft=\'buttonhighlight solid 2px\';
	this.style.borderRight=\'buttonshadow solid 2px\';
	this.style.borderTop=\'buttonhighlight solid 2px\';"
	onmouseout="this.style.borderBottom=\'none\';
	this.style.padding=\'7px\';
	this.style.borderLeft=\'none\';
	this.style.borderRight=\'none\';
	this.style.borderTop=\'none\'"
	style="padding: 7px">';
	}

	/**
	 * @return string with image tag with correct title and an onclick event that will set the location.href property to the value of $url
	 * @param $url is often a referer to this->callgui or this->callguidynamic
	 */
	function Button($image, $title, $url) {
	return '<img src="image/view/'.$image.'" title="'.$title.'" onclick="location.href=\''.$url.'\'" 
	onmouseover="this.style.padding=\'5px\';this.style.borderBottom= \'buttonshadow solid 2px\'; 
	this.style.borderLeft=\'buttonhighlight solid 2px\';
	this.style.borderRight=\'buttonshadow solid 2px\';
	this.style.borderTop=\'buttonhighlight solid 2px\';"
	onmouseout="this.style.borderBottom=\'none\';
	this.style.padding=\'7px\';
	this.style.borderLeft=\'none\';
	this.style.borderRight=\'none\';
	this.style.borderTop=\'none\'"
	style="padding: 7px">';
}

	/**
	 * @return html with shadowed text
	 */
	function ShadowText($text) {
		return '<div class="metashadow">'.$text.'<div class="metatext">'.$text.'</div></div>';
	}

	function getGuiUrl() {
		if ($this->userhandler->getWebUser()) {
			return $_SERVER['PHP_SELF'].'?pageid='.$_REQUEST['pageid']."&";
		} else {
			return $this->userhandler->getSystemUrl().'gui.php?w=1&';
		}
	}	
	/**
	 * @return complete url to the gui with the supplied effect, used heavily troughout the program
	 * @param $otype is the desired classtype of the new gui
	 * @param $objectid is the objectid(s) that the gui should be based upon
	 * @param $cmd is the control(s) that should be performed (before the view(s))
	 * @param $view is the view(s) that the gui will print
	 * @param $context is the context wherein the view(s) will be printed, usually either 'normal' (default) or 'dialog'
	 * @param $ret is the views that should be carried out in response to the called view (usually to return from a dialog and refresh the opener window)
	 * @param $data is any valid URI string to pass to the gui
	 * Either $otype or $objectid has to be non-empty
	 * $view should usually be non-empty
	 */
	function CallGui($otype,$objectid,$cmd,$view,$context='',$ret='',$parentid='',$data='') {
		if (empty($otype) && empty($objectid)) die('callgui: both otype and objectid empty');
		$result = $this->getGuiUrl();
		if (!empty($otype)) $result .= "otype=$otype&";
		if (!empty($objectid)) $result .= "objectid=".$this->objectidstring($objectid)."&";
		if (!empty($cmd)) $result .= "cmd=$cmd&";
		if (!empty($view)) $result .= "view=$view&";
		if (!empty($context)) $result .= "_context=$context&";
		if (!empty($ret)) $result .= "_ret=$ret&";
		if (!empty($parentid)) $result .= "_parentid=$parentid&";
		if (!empty($data)) $result .= $data;
		return $result;
	}

	/**
	 * @return string complete url to the gui with the supplied effect on the objectid(s) defined by the javascript variable o_id
	 * @param $cmd is the control(s) that should be performed (before the view(s))
	 * @param $view is the view(s) that the gui will print
	 * @param $context is the context wherein the view(s) will be printed, usually either 'normal' (default) or 'dialog'
	 * @param $ret is the view(s) that should be carried out in response to the called view (usually to return from a dialog and refresh the opener window)
	 * @param $data is any valid URI string to pass to the gui
	 * Can only be called from views that incorporates the use of the o_id javascript variable, usually the list/hierarchy views
	 */
	function CallGuiDynamic($cmd,$view,$context='',$ret='',$parentid='',$data='') {
		$result = $this->getGuiUrl().'objectid=\'+o_id+\'&';
		if (!empty($cmd)) $result .= "cmd=$cmd&";
		if (!empty($view)) $result .= "view=$view&";
		if (!empty($context)) $result .= "_context=$context&";
		if (!empty($ret)) $result .= "_ret=$ret&";
		if (!empty($parentid)) $result .= "_parentid=$parentid&";
		if (!empty($data)) $result .= $data;
		return $result;
	}
	
	function ReturnViewGet($view) {
		if (!empty($this->ret)) return 'view='.$this->ret;
		return 'view='.$view;
	}
	
	function ReturnViewPost($view) {
		if (!empty($this->ret)) return '<input type="hidden" name="view" value="'.$this->ret.'">';
		return '<input type="hidden" name="view" value="'.$view.'">';
	}

	/**
	 * @return string form fields for returning to the current view with all necessary initiating data
	 * Usually used for multi-step dialog boxes
	 * Notice that the returned html does NOT include the form tag (and the action attribute)
	 * Additional fields can be added by the view
	 */
	function ReturnMePost() {
		$result = '';
		if ($this->userhandler->getWebUser()) {
			if ($_REQUEST['pageid']) $result .= '<input type="hidden" name="pageid" value="'.$_REQUEST['pageid'].'">';
		}
		if ($this->otype) $result .= '<input type="hidden" name="otype" value="'.$this->otype.'">';
		if ($this->view) $result .= '<input type="hidden" name="view" value="'.$this->view.'">';
		if ($this->context->name) $result .= '<input type="hidden" name="_context" value="'.$this->context->name.'">';
		if ($this->ret) $result .= '<input type="hidden" name="_ret" value="'.$this->ret.'">';
		if ($this->objectid) $result .= '<input type="hidden" name="objectid" value="'.$this->objectidstring($this->objectid).'">';
		if ($this->parentid) $result .= '<input type="hidden" name="_parentid" value="'.$this->parentid.'">';
		if (!empty($this->relcol)) $result .= '<input type="hidden" name="_relcol" value="'.$this->relcol.'">';
		if (!empty($this->relval)) $result .= '<input type="hidden" name="_relval" value="'.$this->relval.'">';
		return $result;
	}
	
	/**
	 * @return string GET parameters for returning to the current view with all necessary initiating data
	 * Notice that the returned html does NOT include the script name (use ReturnMeURL() method for a complete URI)
	 * Additional GET parameters can be added by the view
	 */
	function ReturnMeGet() {
		$result = '';
		if ($this->otype) $result .= 'otype='.$this->otype.'&';
		if ($this->view) $result .= 'view='.$this->view.'&';
		if ($this->context->name) $result .= '_context='.$this->context->name.'&';
		if ($this->ret) $result .= '_ret='.$this->ret.'&';
		if ($this->objectid) $result .= 'objectid='.$this->objectidstring($this->objectid).'&';
		if ($this->parentid) $result .= '_parentid='.$this->parentid.'&';
		if (!empty($this->relcol)) $result .= "_relcol=".$this->relcol."&";
		if (!empty($this->relval)) $result .= "_relval=".$this->relval."&";
		return $result;
	}

	/**
	 * @return string complete URL for returning to the current view with all necessary initiating data
	 * Additional GET parameters can be added by the view
	 * Ie. used by list views to add sorting parameters on new calls to the gui
	 */
	function ReturnMeUrl() {
		$result = $this->getGuiUrl().'&'.$this->returnMeGet();
		return $result;
	}

	function ReturnMeUrlMod($objectid='') {
		$result = $this->getGuiUrl();
		if ($this->otype) $result .= 'otype='.$this->otype.'&';
		if ($this->view) $result .= 'view='.$this->view.'&';
		if ($this->context->name) $result .= '_context='.$this->context->name.'&';
		if ($this->ret) $result .= '_ret='.$this->ret.'&';
		if ($objectid == '') {
			if ($this->objectid) $result .= 'objectid='.$this->objectidstring($this->objectid).'&';
		} else {
			$result .= 'objectid='.$objectid.'&';
		}
		if ($this->parentid) $result .= '_parentid='.$this->parentid.'&';
		if (!empty($this->relcol)) $result .= "_relcol=".$this->relcol."&";
		if (!empty($this->relval)) $result .= "_relval=".$this->relval."&";
		return $result;
	}

	/**
	 * @return string partial HTML for inserting a field in a form
	 */
	function makeField($label, $field) {
		$result .= '<div class="mformfieldset" style=""><div class="mformlabel" style="">'.$label.'</div><div class="mformfield" style="">';
		$result .= $field;
		$result .= '</div></div>';
		return $result;		
	}	
	
	function relatedFieldsHeader() {
		$arr = owDatatypeColsDesc($this->otype);
		$supported_types = array(UI_RELATION, UI_RELATION_MULTIPLE, UI_RELATION_NODEFAULT);
		foreach ($arr as $cur) {
			if (isset($cur['detailfield']) && ($cur['inputtype'] == UI_RELATION || $cur['inputtype'] == UI_RELATION_MULTIPLE || $cur['inputtype'] == UI_RELATION_NODEFAULT)) {
				if (in_array($arr[$cur['detailfield']]['inputtype'], $supported_types)) {
					$result .= 'var '.$cur['name'].'_data = new Object();'."\n";
					$obj = owNew($arr[$cur['detailfield']]['relation']);
					$obj->setsort_col($cur['foreigncolumn']);
					$obj->listobjects();
					$curkey = '';
					$objarray = '';
					
					foreach ($obj->elements as $elem) {
						if ($elem[$cur['foreigncolumn']] != $curkey) {
							$curkey = $elem[$cur['foreigncolumn']];
							$result .= $cur['name'].'_data[\''.$curkey.'\'] = [];'."\n";
							$result .= 'var x = ' . $cur['name'] . '_data[\'' . $curkey . "'];\n";
							
						}
						$result .= 'x.push([\''.$elem['objectid'].'\', \''.addslashes($elem['name']).'\']);'."\n";
					}
					
				}
			}
		}
		return $result;


/*var fieldname_data = new Object();
fieldname_data[<myobject1>] = new Array();
fieldname_data[<myobject1>][fieldname_data[<myobject1>.length] = new Array('<foreignobjectid1>', 'text');
fieldname_data[<myobject1>] = '<foreignobjectid2>';
fieldname_data[<myobject1>] = '<foreignobjectid3>';
fieldname_data[<myobject1>] = '<foreignobjectid4>';
fieldname_data[<myobject2>] = new Array();
fieldname_data[<myobject2>] = '<foreignobjectid1>';
fieldname_data[<myobject2>] = '<foreignobjectid2>';*/
		
	}
}

?>
