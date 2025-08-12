<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */
require_once('basic_collection.php');
require_once('basic_field.php');

class basic_view_list extends basic_collection {
	
	var $_listobj;
	var $totalcount;
	var $offset = 0;
	var $limit = 100;
	var $standardsortcol = 'name';
	var $standardsortway = 'ASC';
	var $showinfocols = true;
	var $_preset = false;

	function basic_view_list() {
		$this->basic_collection();
	}

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('basic_view_list');
	}
	
	function contextmenu_single() {
		$result .= basic_collection::contextmenu_single();
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_recycle').'", "garbage", "code:window.location.href=\''.$this->callGui($this->otype,'','','listdeleted').'\'"));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_category').'", "category", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_filter').'", "filter", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_advanced').'", "advanced", ""));';
		if ($this->otype == 'document') 
			$result .= 'addMenuItem(new menuItem("'.$this->gl('context_statistics').'", "statistics", "code:location.href=\''.$this->callgui('sys','','','statistics','','','','stat=stat&groupby=userid&sumwhat=pageid&wherecol=pageid&whereval=\'+o_id+\'').'\'"));';

		if ($this->otype == 'user') 
			$result .= 'addMenuItem(new menuItem("'.$this->gl('context_statistics').'", "statistics", "code:location.href=\''.$this->callgui('sys','','','statistics','','','','stat=stat&groupby=pageid&sumwhat=pageid&wherecol=userid&whereval=\'+o_id+\'').'\'"));';

		if ($this->otype == 'binfile') 
			$result .= 'addMenuItem(new menuItem("'.$this->gl('context_statistics').'", "filestatistics", "code:location.href=\''.$this->callgui('sys','','','filestatistics','','','','stat=stat&groupby=userid&sumwhat=pageid&wherecol=pageid&whereval=\'+o_id+\'').'\'"));';

		if ($this->CanView('properties')) 
			$result .= 'addMenuItem(new menuItem("'.$this->gl('context_properties').'", "properties", "code:'
			.$this->getModalDynamic(array('view'=>'properties','width'=>600,'height'=>600,'scroll'=>'yes')).'"));';

		return $result;
	}
	
	function contextmenu_multiple() {
		$result .= basic_collection::contextmenu_multiple();

		if ($this->CanView('delete')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_delete').'", "delete", "code:'.$this->modelessdialogdynamic('','delete','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('createcopy')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createcopy').'", "createcopy", "code:'.$this->modelessdialogdynamic('','createcopy','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('createvariant')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createvariant').'", "createvariant", "code:'.$this->modelessdialogdynamic('','createvariant','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('createrevision')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createrevision').'", "createrevision", "code:'.$this->modelessdialogdynamic('','createrevision','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('requestapproval')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_requestapproval').'", "requestapproval", "code:'.$this->modelessdialogdynamic('','requestapproval','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('approvepublish')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_approvepublish').'", "approvepublish", "code:'.$this->modelessdialogdynamic('','approvepublish','jscallerreload,jswindowclose').'"));';

		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_recycle').'", "garbage", "code:window.location.href=\''.$this->callGui($this->otype,'','','listdeleted').'\'"));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_category').'", "category", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_filter').'", "filter", ""));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_advanced').'", "advanced", ""));';
		return $result;
	}

	function getColHeaderSortSymbols($colname) {
		return ' [<A HREF="'.$this->ReturnMeURL().'&sortby='.$colname.'&sortway=ASC">+</A>/<A HREF="'.$this->ReturnMeURL().'&sortby='.$colname.'&sortway=DESC">-</A>]';
	}
	
	function getColHeader($colname,&$obj) {
		if (method_exists($this,'getcolheader_'.$colname)) {
			$methodname = 'getcolheader_'.$colname;
			return $this->$methodname($colname,$obj);
		}
		$txt =  $this->gl('label_'.$colname);
		if (!$txt) $txt = $colname;
		return $txt . $this->getColHeaderSortSymbols($colname);
	}

	function getColFooter($colname,&$obj) {
		if (method_exists($this,'getcolfooter_'.$colname)) {
			$methodname = 'getcolfooter_'.$colname;
			return $this->$methodname($colname,$obj);
		}
		return '&nbsp;';
	}
	
	function getColFooter_Name($colname,&$obj) {
		$result = $this->gl('footer_count') . ' ' . $this->totalcount;
		return $result;
	}
		
	function getInfocolElement($arr,$colname,&$obj) {
		if ($colname == 'variant' && $arr['object']['hasvariant']) {
			return '<img src="image/view/infocol_variant.gif" alt="'.$this->gl('alt_variant').'">';
		}
		if ($colname == 'permission' && $arr['object']['haspermission']) {
			return '<img src="image/view/infocol_access.gif" alt="'.$this->gl('alt_access').'">';
		}
		if ($colname == 'active' && !$arr['object']['active']) {
			return '<img src="image/view/infocol_inactive.gif" alt="'.$this->gl('alt_inactive').'">';
		}
		if ($colname == 'default' && $arr['object']['standard']) {
			return '<img src="image/view/infocol_default.gif" alt="'.$this->gl('alt_default').'">';
		}
		if ($colname == 'locked' && $arr['object']['hasfuturerevision']) {
			return '<img src="image/view/infocol_lock.gif">';
		}
		if ($colname == '_image1') {
			if ($arr['image1']) return '<img src="getfilethumb.php?objectid='.$arr['image1'].'">';
		}
		return "&nbsp;";
	}
	
	function getInfocolHeader($colname,&$obj) {
		return '<img src="image/empty.gif" width="16">';
	}

	function getInfocolFooter($colname,&$obj) {
		return '&nbsp;';
	}
	
	function setInfocols() {
		$obj = owNew($this->otype);
		$_SESSION['gui'][$this->otype]['infocols'] =  $obj->stdListInfocol();
	}
		
	function setCols($colsetid) {
		unset($_SESSION['gui'][$this->otype]['cols']);
		if ($colsetid > 0) {
			$obj = owRead($colsetid);
			foreach ($obj->elements[0]['fieldname'] as $cur) 
				$_SESSION['gui'][$this->otype]['cols'][] = $cur;
		} else {
			$obj = owNew($this->otype);
			$_SESSION['gui'][$this->otype]['cols'] = $obj->stdListCol();
		}
	}

	function setColsByArray($colset) {
		unset($_SESSION['gui'][$this->otype]['cols']);
		if (is_array($colset)) {
			foreach ($colset as $cur) 
				$_SESSION['gui'][$this->otype]['cols'][] = $cur;
		}
	}

	function title() {
		$result = '<div class="metatitle">';
		
		$result .= '
		<div style="float: right"><a href="'.$this->returnMeUrl().'&view=listprint" target="_blank"><img src="image/view/title_print.gif" border="0"></A></div>
		<div style="float: right">&nbsp;&nbsp;<a href="'.$this->returnMeUrl().'" target="_blank"><img src="image/view/title_detach.gif" border="0"></A></div>
		<div style="float: right">'.$this->shadowtext($this->userhandler->getPrgName()).'</div>
		';

		if ($this->_preset) {
			if ($this->userhandler->getGuiLanguage() == 'da') {
				$text = base64_decode('QmVncuZuc2V0IGRlbW91ZGdhdmU=').'&nbsp;-&nbsp;';
			} else {
				$text = base64_decode('TGltaXRlZCBkZW1vIGVkaXRpb24=').'&nbsp;-&nbsp;';
			}
			$result .= '<div style="float: right">'.$this->shadowtext($text).'</div>';
		}
		if (isset($_SESSION['gui'][$this->otype]['search']) && is_array($_SESSION['gui'][$this->otype]['search'])) {
			$text = '';
			foreach ($_SESSION['gui'][$this->otype]['search'] as $cur) {
				if ($cur) {
					if ($text != '') {
						$text .= ' + '.$cur;
					} else {
						$text = $cur;
					}
				}
				
			}
			$result .= '<div style="float: right">'.$this->shadowtext('[Søg: '.$text.']&nbsp;&nbsp;').'</div>';
		}
		$result .= $this->shadowtext($this->gl('title').' :: '.$this->gl('name')).'</div>';
		
		return $result;
	}

	function searchBar($index=0,$colfname='searchcol', $compfname='searchtype', $textfname='search') {
		$dtcols = owDatatypeColsDesc($this->otype);
		$result = '<select name="'.$colfname.'" style="width:150px;">';
		$obj = owNew($this->otype);
		$l = $index;
		foreach ($dtcols as $cur) {
			$s = '';
			#$l = sizeof($_SESSION['gui'][$this->otype]['searchcol']) - 1;
			if ($cur['name'] == $_SESSION['gui'][$this->otype]['searchcol'][$l]) $s = ' SELECTED';
			$result .= '<option value="'.$cur['name'].'" '.$s.'>'.$this->gl('tool_field').' '.$cur['label'].'</option>';
		}
		$result .= '</select>';
		#$l = sizeof($_SESSION['gui'][$this->otype]['searchtype']) - 1;
		$l = $index;
		$result .= '<select name="'.$compfname.'" style="width:150px;">';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == LIKE) ? ' SELECTED' : '';
		$result .= '<option value="'.LIKE.'"'.$s.'>'.$this->gl('tool_like').'</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == LIKESTART) ? ' SELECTED' : '';
		$result .= '<option value="'.LIKESTART.'"'.$s.'>'.$this->gl('tool_likestart').'</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == LIKEEND) ? ' SELECTED' : '';
		$result .= '<option value="'.LIKEEND.'"'.$s.'>'.$this->gl('tool_likeend').'</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == GREATER) ? ' SELECTED' : '';
		$result .= '<option value="'.GREATER.'"'.$s.'>&gt;</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == GREATEREQUAL) ? ' SELECTED' : '';
		$result .= '<option value="'.GREATEREQUAL.'"'.$s.'>&gt;=</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == LESS) ? ' SELECTED' : '';
		$result .= '<option value="'.LESS.'"'.$s.'>&lt;</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == LESSEQUAL) ? ' SELECTED' : '';
		$result .= '<option value="'.LESSEQUAL.'"'.$s.'>&lt;=</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == NOTEQUAL) ? ' SELECTED' : '';
		$result .= '<option value="'.NOTEQUAL.'"'.$s.'>&lt;&gt;</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == EQUAL) ? ' SELECTED' : '';
		$result .= '<option value="'.EQUAL.'"'.$s.'>=</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == SOUNDSLIKE) ? ' SELECTED' : '';
		#$result .= '<option value="'.SOUNDSLIKE.'"'.$s.'>Lyder som</option>';
		$s = ($_SESSION['gui'][$this->otype]['searchtype'][$l] == NOTSOUNDSLIKE) ? ' SELECTED' : '';
		#$result .= '<option value="'.NOTSOUNDSLIKE.'"'.$s.'>Lyder ikke som</option>';
		$result .= '</select>';
		$result .= '<input type="text" name="'.$textfname.'" value="'.$_SESSION['gui'][$this->otype]['search'][$l].'" style="width: 144px;">';
		return $result;
	}
	
	function categoryBar() {
		echo '<select name="categoryid" style="width:150px;" onChange="location.href = \''.$this->ReturnMeUrl().'&categoryid=\' + this.options[this.selectedIndex].value;">';
		$fieldobj = new basic_field($this);
		echo $fieldobj->listallcategories(@$_SESSION['gui'][$this->otype]['category']);
		echo '</select>';

		$listcolobj = owNew('listcol');
		$listcolobj->setlistaccess(true);
		$listcolobj->setfilter_search('name',$this->otype,EQUAL);
		$listcolobj->listobjects();
		echo '<select name="setlistcol" style="width:150px;" onChange="location.href = \''.$this->ReturnMeUrl().'&setlistcol=\' + this.options[this.selectedIndex].value;">';
		echo '<option value="">'.$this->gl('tool_standardcol').'</option>';
		if ($listcolobj->elementscount > 0) {
			foreach ($listcolobj->elements as $cur) {
				if (is_array($cur)) {
					$s = '';
					if ($cur['objectid'] == $_SESSION['gui'][$this->otype]['listcol']) $s = ' SELECTED';
					echo '<option value="'.$cur['objectid'].'"'.$s.'>';
					
					if ($cur['pname'] != '') {
						echo $cur['pname'];
					} else {
						foreach ($cur['fieldname'] as $curfield) {
							echo $curfield.',';
						}
					}
				}
				echo '</option>';
			}
		}
		echo '</select>';

		echo '<select name="setlistsort" style="width:150px;" onChange="location.href = \''.$this->ReturnMeUrl().'&setlistsort=\' + this.options[this.selectedIndex].value;">';
		echo '<option value="">'.$this->gl('tool_standardsort').'</option>';
		$sortcolobj = owNew('sortcol');
		$sortcolobj->setlistaccess(true);
		$sortcolobj->setfilter_search('name', $this->otype, EQUAL);
		$sortcolobj->listobjects();
		$columnlabels = owDatatypeColsDesc($this->otype);
		if ($sortcolobj->elementscount > 0) {
			foreach ($sortcolobj->elements as $cur) {
				$s = '';
				if ($cur['objectid'] == $_SESSION['gui'][$this->otype]['sortcol']) $s = ' SELECTED';
				echo '<option value="'.$cur['objectid'].'"'.$s.'>';
				foreach ($cur['fieldname'] as $curfield) {
					echo $columnlabels[$curfield]['label'].',';
				}
				echo '</option>';
			}
		}
		echo '</select><br>';
	}
	
	function toolBar() {
		ob_start();
		echo '<table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top">';
		echo '<form name="listsearch" method="POST" style="margin:0px; spacing:0px; clear: none;" action="'.$this->ReturnMeUrl().'">';

		$this->categoryBar();
		
		echo $this->searchBar();

		echo '<input type="hidden" name="otype" value="'.$this->otype.'">';
		echo '</form>';
		echo '</td><td valign="top" style="padding-left: 5px;">';
		echo $this->buttonOnClick('search_small.png',$this->gl('img_search'),'document.listsearch.submit()');
		echo $this->button('list_small.png',$this->gl('img_listall'),$this->ReturnMeUrl().'&clearall=1');
		$relationurl = '';
		if ($this->relcol) $relationurl = '_relcol=' . $this->relcol . '&_relval=' . $this->relval;
		if ($this->canView('create')) echo $this->button('create_small.png',$this->gl('img_create'),$this->callgui($this->otype,'','','create','',$this->view,$this->parentid, $relationurl));
		if ($this->canView('list') && empty($this->parentid) && empty($this->relcol)) echo $this->button('tree_small.png',$this->gl('img_hierarchy'),$this->callgui($this->otype,'','','split'));
 		echo $this->button('listcsv_small.png','CSV export',$this->callgui($this->otype,'','','listcsv', '', '', '', $relationurl));
		if ($this->offset > 0) echo $this->button('prev_small.png', 'prev', $this->returnMeUrl() . '&offset=' . ($this->offset - $this->limit) . '&count=' . $this->limit);
		if ($this->offset + $this->limit < $this->totalcount) echo $this->button('next_small.png', 'next', $this->returnMeUrl() . '&offset=' . ($this->offset + $this->limit) . '&count=' . $this->limit);
		echo '</td></tr></table>';
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}

	function setFilters() {
		$obj =& $this->_listobj;
		if (@$_REQUEST['clearall'] == '1') {
			/**
			 *@todo: hov det går ikke - fjerner oplysninger om user hidden og secret
			 */
			unset($_SESSION['gui'][$this->otype]);
		}
		$guisession =& $_SESSION['gui'][$this->otype];
		
		if (!isset($guisession['listcol'])) {
			$listcolobj = owNew('listcol');
			$listcolobj->setlistaccess(true);
			$listcolobj->setfilter_search('name',$this->otype,EQUAL);
			$listcolobj->setfilter_search('makedefault','1',EQUAL);
			$listcolobj->listobjects();
			if ($listcolobj->elementscount > 0) {
				$guisession['listcol'] = $listcolobj->elements[0]['objectid'];
			} else {
				$guisession['listcol'] = 0;
			}
			$this->setcols($guisession['listcol']);
		}
		
		if (isset($_REQUEST['setlistcol'])) {
			$guisession['listcol'] = $_REQUEST['setlistcol'];
			$this->setcols($guisession['listcol']);
		}

		if (isset($_REQUEST['setlistcolarray'])) {
			$guisession['listcolarray'] = $_REQUEST['setlistcolarray'];
			$this->setcolsByArray($guisession['listcolarray']);
		}

		if (isset($_REQUEST['createdfrom'])) {
			$guisession['createdfrom'] = $_REQUEST['createdfrom'];
		}
		
		if (isset($_REQUEST['createdto'])) {
			$guisession['createdto'] = $_REQUEST['createdto'];
		}

		if (isset($_REQUEST['stattype'])) {
			$guisession['stattype'] = $_REQUEST['stattype'];
		}

		if (isset($_REQUEST['groupby1'])) {
			$guisession['groupby1'] = $_REQUEST['groupby1'];
		}
		
		if (isset($_REQUEST['groupby2'])) {
			$guisession['groupby2'] = $_REQUEST['groupby2'];
		}
		
		if ($guisession['stattype'] == 'optael') {
			$obj->setfilter_groupby(array($guisession['groupby1'],$guisession['groupby2']));
		}
			
		if (isset($guisession['createdfrom']) && trim($guisession['createdfrom'] != '')) {
			$obj->setfilter_search('created',$guisession['createdfrom']." 00:00:00",GREATEREQUAL);
		}

		if (isset($guisession['createdto']) && trim($guisession['createdto'] != '')) {
			$obj->setfilter_search('created',$guisession['createdto']." 23:59:29",LESSEQUAL);
		}

		if (isset($_REQUEST['setlistsort'])) {
			$guisession['listsort'] = $_REQUEST['setlistsort'];
		}
		###
		if (@$_REQUEST['categoryid'] != '')
			$guisession['category'] = $_REQUEST['categoryid'];
			
		if (isset($guisession['category'])) 
			$obj->setfilter_category($guisession['category']);
		
		###
		if (isset($_REQUEST['search']) && is_array($_REQUEST['search'])) {
				$guisession['search'] = $_REQUEST['search'];
				$guisession['searchcol'] = $_REQUEST['searchcol'];
				$guisession['searchtype'] = $_REQUEST['searchtype'];
		} elseif (isset($_REQUEST['search'])) {
			if (@$_REQUEST['search'] != '')
				$guisession['search'][0] = $_REQUEST['search'];
			if (@$_REQUEST['searchcol'] != '')
				$guisession['searchcol'][0] = $_REQUEST['searchcol'];
			if (@$_REQUEST['searchtype'] != '')
				$guisession['searchtype'][0] = $_REQUEST['searchtype'];
		}
		if (isset($guisession['searchcol']) && $guisession['searchcol'][0] != '') {
			$count = 0;
			foreach ($guisession['searchcol'] as $c) {
				if (!empty($guisession['search'][$count]))
					$obj->setfilter_advsearch($guisession['searchcol'][$count],$guisession['search'][$count],$guisession['searchtype'][$count]);
				$count++;
			}
		} else {
			if (isset($guisession['search'])) 
				$obj->setfilter_name($guisession['search']);
		}

	
		###
		if (isset($_REQUEST['sortby']))
			$guisession['sortby'] = $_REQUEST['sortby'];
	
		if (isset($_REQUEST['sortway']))
			$guisession['sortway'] = $_REQUEST['sortway'];
	
		if (isset($guisession['sortby'])) {
			$obj->setsort_col($guisession['sortby']);
			$obj->setsort_way($guisession['sortway']);
		} else {
			// setting $this->standardsortcol has higher preference
			// than $obj->standard_sort_colname on the class
			// For backwards compatibility only
			// Use $obj->standard_sort_colname in the future!
			if ($this->standardsortcol != 'name') {
				$obj->setsort_col($this->standardsortcol);
			} else {
				$obj->setsort_col($obj->standard_sort_colname);
			}
			
			// setting $this->standardsortway has higher preference
			// than $obj->standard_sort_way on the class
			// For backwards compatibility only
			// Use $obj->standard_sort_way in the future!
			if ($this->standardsortway != 'ASC') {
				$obj->setsort_way($this->standardsortway);
			} else {
				$obj->setsort_way($obj->standard_sort_way);
			}
		}
		###	
		if (isset($_SESSION[$this->otype]['filter_approved'])) $obj->setfilter_approved($_SESSION[$this->otype]['filter_approved']);
		if (isset($_SESSION[$this->otype]['filter_datacol'])) $obj->setfilter_data($_SESSION[$this->otype]['filter_datacol'],$_SESSION[$this->otype]['filter_datavalue']);
		
		$obj->setfilter_getname(true);
		
		/**
		 * @todo: make setfilter_data work on multiple calls 
		 */
		if ($this->data['_relcol']) $obj->setfilter_data($this->data['_relcol'],$this->data['_relval']);

		if (isset($guisession['variantcompare'])) $obj->filter_listvariants = true;		
	}
	
	/**
	 * Limit the display to LIMIT rows starting from $_REQUEST['offset']
	 */
	function setLimits() {
		if (isset($_REQUEST['offset'])) {
			$this->offset = $_REQUEST['offset'];
		} else {
			$this->offset = 0;
		}
	}
	

	function groupList() {
		$obj =& $this->_listobj;
		$guisession =& $_SESSION['gui'][$this->otype];

		$z = 0;
		$res = array();
		$elementscount = $obj->elementscount;
		$xlist = array();
		$ylist = array();
		$mycols = owDatatypeColsDesc($this->otype);
		$field = new basic_field($this);
		while ($z < $elementscount) {
			$res[$obj->elements[$z][$guisession['groupby1']]][$obj->elements[$z][$guisession['groupby2']]] = $obj->elements[$z]['syscount'];
			if (!in_array($obj->elements[$z][$guisession['groupby2']],$xlist) && $obj->elements[$z][$guisession['groupby2']] != '') 
				$xlist[] = $obj->elements[$z][$guisession['groupby2']];

			if (!in_array($obj->elements[$z][$guisession['groupby1']],$ylist) && $obj->elements[$z][$guisession['groupby1']] != '')
				$ylist[] = $obj->elements[$z][$guisession['groupby1']];
			$z++;
		}
		arsort($xlist);
		arsort($ylist);
		echo '<table id="st" class="metalist" onselectstart="return false" style="width: 100%">';
		echo '<tr class="metalistheadrow" id="headerrow">';
		// Output headers
		echo '<td>'.'&nbsp;'."</td>\n";
		foreach ($xlist as $x) {
			$lstyle = 'valign="top" nowrap';
			$text = $field->parsefield($mycols[$guisession['groupby2']],$x,IN_LIST);
			if (!$text) $text = '[IKKE ANGIVET]';
			echo '<td '.$lstyle.'>'.$text."</td>\n";
		}
		echo '<td>'.'TOTAL'."</td>\n";
		echo '</tr>';
		
		// Output listing

		$total = 0;
		foreach ($ylist as $y) {
			$tdclass = ($z % 2) ? 'mlodd' : 'mleven';
			echo "\n<tr class=\"".$tdclass.'">';
			$text = $field->parsefield($mycols[$guisession['groupby1']],$y,IN_LIST);
			if (!$text) $text = '[IKKE ANGIVET]';
			echo '<td>'.$text.'</td>';
			$ytotal = 0;
			foreach ($xlist as $x) {
				echo '<td align="right">'.$res[$y][$x].'</td>';
				$total += $res[$y][$x];
				$ytotal += $res[$y][$x];
				$xtotal[$x] += $res[$y][$x];
			}
			echo '<td align="right">'.$ytotal.'</td>';
			echo '</tr>';			
			$z++;
		}

		// Output footer
		echo '<tr class="metalistbottomrow" id="footerrow">';
		echo '<td>'.'TOTAL'."</td>\n";
		foreach ($xlist as $x) {
			echo '<td align="right">'.$xtotal[$x].'</td>';
		}
		echo '<td align="right">'.$total."</td>\n";
		echo '</tr>';
		echo '</table>';
	}

	function outputHeaders() {
		$obj =& $this->_listobj;
		$guisession =& $_SESSION['gui'][$this->otype];

		echo '<tr class="metalistheadrow" id="headerrow">';
		if (!isset($guisession['infocols'])) $this->setinfocols();
		$i = 0;
		$widecolumn = false;
		foreach ($guisession['cols'] as $key => $curheader) {
			// this makes sure, that the last normal column,
			// before any of the object-columns, will have a width of 100%
			// so all normal columns are left-aligned, and all object-
			// and info-columns are right-aligned
			if (!$widecolumn && 
				($i == sizeof($guisession['cols'])-1 || 
				(in_array($guisession['cols'][$i+1],array('objectid','changed','created','createdbyname','language'))))
			) {
				$lstyle = 'valign="top" style="width: 100%"';
				$widecolumn = true;
			} else {
				$lstyle = 'valign="top" nowrap';
			}
			echo '<td '.$lstyle.'>'.$this->getcolheader($curheader,$obj)."</td>\n";
			$i++;
		}
		
		// Output infocolheaders
		if ($this->showinfocols) {
			foreach ($guisession['infocols'] as $curheader) {
				echo '<td>' . $this->getinfocolheader($curheader,$obj)."</td>\n";
			}
		}
		
		echo '</tr>';
	}

	function outputFooters() {
		$obj =& $this->_listobj;
		$guisession =& $_SESSION['gui'][$this->otype];

		echo '<tr class="metalistbottomrow" id="footerrow">';
		foreach ($guisession['cols'] as $curheader) {
			echo '<td>'.$this->getcolfooter($curheader,$obj).'</td>';
		}
		
		if ($this->showinfocols) {
			foreach ($guisession['infocols'] as $curheader) {
				echo '<td>'.$this->getinfocolfooter($curheader,$obj).'</td>';
			}
		}
		echo '</tr>';
	}
	
	function outputListing() {
		$obj =& $this->_listobj;
		$guisession =& $_SESSION['gui'][$this->otype];

		// Session-array containing objectids in the right order
		// Used by prev/next-buttons in combi-view
		$_SESSION['guitemp'][$this->otype]['list'] = array();
		
		$z = 0;
		$elementscount = $obj->elementscount;
		
		$mycols = owDatatypeColsDesc($this->otype);
		$field = new basic_field($this);
		
		// Output listing
		while ($z < $elementscount) {
			if ($z >= $this->offset && $z < $this->offset + $this->limit) {
				$output = true;
			} else {
				$output = false;
			}
			
			$element =& $obj->elements[$z];
			// Save order of objectids from the last list in
			// a sessionvariable.
			// This is needed for the prev-next buttons in combi-view
			$_SESSION['guitemp'][$this->otype]['list'][] = $element['objectid'];
			
			if ($output) {
				$tdclass = ($z % 2) ? 'mlodd' : 'mleven';
				echo "\n<tr id=\"".$element['objectid'].'" class="'.$tdclass.'">';
	
				$lstyle = '';
	
				// Output columns
				foreach ($guisession['cols'] as $curcol) {
					if ($curcol == 'name') {
						$lstyle .= ' id="name-'.$obj->elements[$z]['objectid'] . '"';
						if ($guisession['variantcompare']) {
							if (in_array($guisession['variantcompare'],$obj->elements[$z]['variants'])) {
								$lstyle .= ' style="color: #FF0000"';
							}
						}
					}
					echo '<td nowrap '.$lstyle.'>';
					echo $field->parsefield($mycols[$curcol],$obj->elements[$z][$curcol],IN_LIST);
					echo '</td>';
				}
				
				if ($this->showinfocols) {
					foreach ($guisession['infocols'] as $curcol) {
						echo '<td>'.$this->getinfocolelement($element,$curcol,$obj).'</td>';
					}
				}
		
				echo '</tr>';
			}
			
			$z++;
		}
	}
	
	function view() {
		basic_collection::view();
		
		// Create our listing object
		$this->_listobj = owNew($this->otype);
		
		// Apply userfilters (categories, search etc)
		$this->setFilters();
		
		// Shortcuts
		$obj =& $this->_listobj;
		$guisession =& $_SESSION['gui'][$this->otype];
		
		// Enforce paging
		$this->setLimits();
		
		if ($this->parentid) {
			$obj->listobjects($this->parentid);
		} else {
			$obj->listobjects(0);
		}
		
		$this->totalcount = $obj->elementscount;
		
		echo '<div class="metawindow">';
		echo $this->title();
		echo $this->toolbar();

		if ($guisession['stattype'] == 'optael') {
			$this->groupList();
		} else {
			echo '<table id="st" class="metalist" onselectstart="return false" style="width: 100%">';
			$this->outputHeaders();
			$this->outputListing();		
			$this->outputFooters();
			echo '</table>';
	
			echo $this->getRowEventHandler();		
		}
		echo '</div>';
	}

	function getRowEventHandler() {
		// Javascript which sets eventhandlers on all rows
		$result = '<script type="text/javascript">';
		$result .= "
			var table = document.all ? document.all['st'] : document.getElementById ? document.getElementById('st') : null;
			if (table)
				for (var r = 0; r < table.rows.length; r++)
				if (table.rows[r].className == 'mleven' || table.rows[r].className == 'mlodd') {
					table.rows[r].onclick = function() { hideAllMenus(); }
					table.rows[r].ondblclick = function() { ".$this->ondblclick()." }
					table.rows[r].onmousedown = function() { cm(this); o_id=st.gsi().toString(); }
				}
		";
	
		$result .= 'var st = new SelectableTableRows(document.getElementById("st"), true);';
		$result .= '</script>';
		return $result;
	}
		
	function ondblclick() {
		return "cm(this); o_id=st.gsi().toString(); oe(0); return false;";
	}
	
}

?>