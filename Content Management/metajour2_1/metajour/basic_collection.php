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

class basic_collection extends basic_view {
	var $menuwidth = 200;
	
	function basic_collection() {
		$this->basic_view();
	}

	function loadLanguage() {
		basic_view::loadLanguage();
	}

	function contextmenu_single() {
		if ($this->CanView('view')) $result .= '
		if (parent.dialog) {
			addMenuItem(new menuItem("'.$this->gl('context_view').'", "view", "code:parent.dialog.location.href=\''.$this->callGuiDynamic('','view').'\'"));
		} else {
			addMenuItem(new menuItem("'.$this->gl('context_view').'", "view", "code:window.location.href=\''.$this->callGuiDynamic('','view').'\'"));
		}
		';
		if ($this->CanView('edit')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_edit').'", "combi", "code:oe();"));';
		if ($this->CanView('preview')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_preview').'", "preview", "code:open(\''.$this->userhandler->getViewerUrl().'showpage.php?pageid=\' + o_id)"));';
		if ($this->CanView('view') && $this->otype == 'binfile') $result .= 'addMenuItem(new menuItem("'.$this->gl('context_viewfile').'", "viewfile", "code:window.open(\''.$this->userhandler->getSystemUrl().'getfile.php?objectid=\' + o_id, \'fileview\', \'directories=no,location=no,menubar=no,status=no,toolbar=no,resizable=yes\')"));';
		if ($this->CanView('delete')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_delete').'", "delete", "code:'.$this->modelessdialogdynamic('','delete','jscallerreload,jswindowclose').'"));';
		#$result .= 'addMenuItem(new menuItem("'.$this->gl('context_undelete').'UNDELETE!", "undelete", "code:'.$this->modelessdialogdynamic('','undelete','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('createcopy')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createcopy').'", "createcopy", "code:'.$this->modelessdialogdynamic('','createcopy','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('createvariant')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createvariant').'", "createvariant", "code:'.$this->modelessdialogdynamic('','createvariant','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('createrevision')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createrevision').'", "createrevision", "code:'.$this->modelessdialogdynamic('','createrevision','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('requestapproval')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_requestapproval').'", "requestapproval", "code:'.$this->modelessdialogdynamic('','requestapproval','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('approvepublish')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_approvepublish').'", "approvepublish", "code:'.$this->modelessdialogdynamic('','approvepublish','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('default')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_default').'", "default", "code:'.$this->modelessdialogdynamic('','default','jscallerreload,jswindowclose').'"));';
		return $result;
	}
	
	function contextmenu_advanced() {
		if ($this->CanView('language')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_language').'", "language", "code:'.$this->modelessdialogdynamic('','language','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('publish')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_publish').'", "publish", "code:'.$this->modelessdialogdynamic('','publish','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('active')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_active').'", "active", "code:'.$this->modelessdialogdynamic('','active','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('category') || $this->CanView('access')) $result .= 'addMenuItem(new menuItem("-"));';
		if ($this->CanView('category')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_categories').'", "category1", "code:'.$this->modelessdialogdynamicLarge('','category','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('access')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_access').'", "access", "code:'.$this->modelessdialogdynamicVeryLarge('','access','jscallerreload,jswindowclose').'"));';
		$result .= 'addMenuItem(new menuItem("-"));';
		if ($this->CanView('createdby')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_createdby').'", "createdby", "code:'.$this->modelessdialogdynamic('','createdby','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('checkedby')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_checkedby').'", "checkedby", "code:'.$this->modelessdialogdynamic('','checkedby','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('changedby')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_changedby').'", "changedby", "code:'.$this->modelessdialogdynamic('','changedby','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('created')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_created').'", "created", "code:'.$this->modelessdialogdynamic('','created','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('checked')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_checked').'", "checked", "code:'.$this->modelessdialogdynamic('','checked','jscallerreload,jswindowclose').'"));';
		if ($this->CanView('changed')) $result .= 'addMenuItem(new menuItem("'.$this->gl('context_changed').'", "changed", "code:'.$this->modelessdialogdynamic('','changed','jscallerreload,jswindowclose').'"));';
		if ($this->userhandler->getRevisionControl() && $this->CanView('createfuture')) $result .= 'addMenuItem(new menuItem("Vis tidligere revisioner", "history", "code:window.location.href=\''.$this->callGuiDynamic('','listhistory','','','','_relval=\'+o_id+\'').'\'"));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_exportobj').'", "exportobj", "code:'.$this->modelessdialogdynamic('','exportobj','jscallerreload,jswindowclose').'"));';
		$result .= 'addMenuItem(new menuItem("'.$this->gl('context_variantcompare').'", "variantcompare", "code:'.$this->modelessdialogdynamic('','variantcompare','jscallerreload,jswindowclose').'"));';
		return $result;
	}
	
	function contextmenu_multiple() {
	}
		
	function oeJavascript() {
		$relationurl = '';
		if ($this->relcol) $relationurl = '_relcol=' . $this->relcol . '&_relval=' . $this->relval;
		return "
		if (parent.dialog) {
			parent.dialog.location.href='".$this->callGuiDynamic('','combi','',$this->view,$this->parentid, $relationurl)."'; 
		} else {
			window.location.href='".$this->callGuiDynamic('','combi','',$this->view,$this->parentid, $relationurl)."'; 
		}
		return false;
		";
	}
	
	function view() {
		$this->context->addonload('initjsDOMenu');
		
		$this->context->addheader('
		<link rel="stylesheet" type="text/css" href="css/jsdomenu.css">
		<script type="text/javascript" src="js/selectableelements.js"></script>
		<script type="text/javascript" src="js/selectabletablerows.js"></script>
		<script type="text/javascript" src="js/jsdomenu.js"></script>');
		$this->context->addheader('<script type="text/javascript">');

		$obj = owNew('category');
		$obj->listobjects();
		$z = 0;
		$category = '';
		while ($z < $obj->elementscount) {
			if ($obj->elements[$z]['datatype'] == $this->otype || $obj->elements[$z]['datatype'] == '') {
				$category .= 'addMenuItem(new menuItem("' . $obj->elements[$z]['name'].'", "category", "code:' . $this->modelessdialogdynamic('', 'addcategory', 'jscallerreload,jswindowclose', '', '&categoryid=' . $obj->elements[$z]['objectid']) . '"));';
			}
			$z++;
		}
		unset($obj);

		$obj = owNew('filter');
		$obj->listobjects();
		$z = 0;
		$filter = '';
		while ($z < $obj->elementscount) {
			if ($obj->elements[$z]['datatype'] == $this->otype || $obj->elements[$z]['datatype'] == '')
				$filter .= 'addMenuItem(new menuItem("'.$obj->elements[$z]['name'].'", "filter", "code:'.$this->modelessdialogdynamic('','filter','jscallerreload,jswindowclose','','&filterid='.$obj->elements[$z]['objectid']).'"));';
			$z++;
		}
		unset($obj);


		
		$this->context->addheader('
			function createjsDOMenu() {
				mainMenu = new jsDOMenu('.$this->menuwidth.');
				with (mainMenu) {
					'.$this->contextmenu_single().'
				}
				categoryMenu = new jsDOMenu('.$this->menuwidth.');
				with (categoryMenu) {
					'.$category.'
				}
				filterMenu = new jsDOMenu('.$this->menuwidth.');
				with (filterMenu) {
					'.$filter.'
				}
				advancedMenu = new jsDOMenu('.$this->menuwidth.');
				with (advancedMenu) {
					'.$this->contextmenu_advanced().'
				}
	  			if (mainMenu.items.category) mainMenu.items.category.setSubMenu(categoryMenu);
	  			if (mainMenu.items.filter) mainMenu.items.filter.setSubMenu(filterMenu);
	  			if (mainMenu.items.advanced) mainMenu.items.advanced.setSubMenu(advancedMenu);
				//  mainMenu.items["edit"].enabled = false;
				//  mainMenu.items["edit"].setClassName("jsdomenuinactive");
				mainMenu.setNoneExceptFilter(new Array("A.cm","TD.*", "SPAN.*"));
				activatePopUpMenuBy(1, 0);
				setPopUpMenu(mainMenu);
	
				multiMenu = new jsDOMenu('.$this->menuwidth.');
				with (multiMenu) {
					'.$this->contextmenu_multiple().'
				}
	  			if (mainMenu.items.category) multiMenu.items.category.setSubMenu(categoryMenu);
	  			if (mainMenu.items.filter) multiMenu.items.filter.setSubMenu(filterMenu);
	  			if (mainMenu.items.advanced) multiMenu.items.advanced.setSubMenu(advancedMenu);
				multiMenu.setNoneExceptFilter(new Array("A.cm","TD.*", "SPAN.*"));
			}
			');

	ob_start();
?>			
	function modifysinglemenu() {
		// This function parses all elements of the contextmenu for single
		// object selections (mainMenu), to determine the availability of
		// the menuitems
	}
		
	function cm(el) {
		// This function is called on oncontextmenu from object listings
		// where single and multiple object selections are possible
		var event = window.event;
		
		// If right-click
		if (event && event.button == 2) {
			if (st.getSelectedItems().length == 0) {
				el.click(event);
			} else if (st.getSelectedItems().length > 0 && ! st.getItemSelected(el)) {
				el.click(event);
			}
		}
		
		if(st.gsi().length >1) {
			setPopUpMenu(multiMenu);
		} else {
			modifysinglemenu();
			setPopUpMenu(mainMenu);
		}
		hideAllMenus();
	}
	
	function scm(el) {
		// This function is called on oncontextmenu from object listings
		// where only single object selections are possible
		modifysinglemenu();
		setPopUpMenu(mainMenu);
		hideAllMenus();
	}

	function oe(ask) {
		<?php
		echo $this->oeJavascript();
		?>
	}

	var o_id = '';
	</script>
<?php

	$output = ob_get_contents();
	ob_end_clean();
	$this->context->addheader($output);
	
	}
	
}

?>