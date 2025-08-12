<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path.'ow.php');
require_once($system_path.'basic_error.php');
require_once($system_path.'basic_event.php');
require_once($system_path.'basic_user.php');

class basicextension {
	var $errorhandler;
	var $userhandler;
	var $eventhandler;
	var $extconfig;
	var $extoutput;
	var $extresult;
	
	var $extname;
	var $extconfigset;
	var $extconfigsetid;
	var $extcmd = '';
	
	var $force_extcf = '';
	var $user;
	var $document;
	var $templatefile = '';
	var $indocument = false;
	var $requireconfigset = false;
	var $functions = array();
	var $extparams = array();

	var $smarty;

	function basicextension() {
		$this->errorhandler =& GetErrorHandler();
		$this->eventhandler =& GetEventHandler();
		$this->userhandler =& GetUserHandler();
		
		$this->site = $this->userhandler->getSite();
		$this->system_url = $this->userhandler->getSystemUrl();
		$this->system_path = $this->userhandler->getSystemPath();
		$this->viewer_url = $this->userhandler->getViewerUrl();
		$this->viewer_path = $this->userhandler->getViewerPath();
		$this->webuser = $this->userhandler->getWebuser();
		$this->MeUrl = $this->viewer_url .'showpage.php?pageid='.$_REQUEST['pageid'];
		$this->functions = array();
		$this->extparams = array();
		$this->addfunction('_do');
		if (method_exists($this,'initState')) $this->addfunction('initState');
		$this->addextparam('force_extcf');
		$this->addextparam('cmd');
		$this->smarty = $this->userhandler->getSmarty();
		$this->smarty->default_resource_type = 'template';
	}

	function installExtension() {
	}
	
	function getDefaultConfig() {
	}

	function didSomething() {
		if ($this->extcmd != '') return true;
		return false;
	}

	function getParameters($params) {
		if (isset($params['force_extcf'])) $this->force_extcf = $params['force_extcf'];

		reset ($this->extparams);
		while (list ($key, $val) = each ($this->extparams)) {
			if (isset($params[$val])) $this->extconfig[$val] = $params[$val];
		}
		if ($this->hasConfigSet()) {
			reset ($this->extconfig);
			while (list ($key, $val) = each ($this->extconfig)) {
				if (isset($params[$key])) $this->extconfig[$key] = $params[$key];
			}
		}
	}

	function readConfig() {
		if ($this->hasConfigSet()) {
			$obj = owNew($this->extname);
			# if the supplied configset is an integer it's an objectid
			# else try to locate the configset by that name
			$this->extconfigsetid = is_int($this->extconfigset) ? $this->extconfigset : $obj->locatebyname($this->extconfigset);
			if ($this->extconfigsetid) {
				$obj->readobject($this->extconfigsetid);
				$this->extconfig = $obj->elements[0];
			} elseif (substr($this->extconfigset,0,4) != 'none') {
				$obj->createObject(array('name' => $this->extconfigset));
				$this->extconfigsetid = $obj->getObjectId();
				$obj->readobject($this->extconfigsetid);
				$this->extconfig = $obj->elements[0];
			}
		}
	}

	function hasConfigSet() {
		return (file_exists($this->userhandler->getSystemPath().'extension/'.$this->extname.'/'.$this->extname.'.datatype.php'));
	}
	
	function setdocument($document) {
		$this->document = $document;
	}

	function useTemplate($idxname, $idxid, $stdname) {
		//use: useTemplate('templatename', 'templateid', 'standardname')
		if (isset($this->extconfig[$idxname])) {
			$this->settemplatebyname($this->extconfig[$idxname]);
		} elseif (isset($this->extconfig[$idxid]) && $this->extconfig[$idxid] != 0) {
			$this->settemplate($this->extconfig[$idxid]);
		} else {
			$this->installtemplate($stdname);
			$this->settemplatebyname($stdname);
		}
	}

	function settemplate($templateid) {
		$templateobj = owRead($templateid);
		if ($templateobj) {
			$this->templatefile = $templateobj->elements[0]['name'];
			$this->userhandler->addHeaderCache($templateobj->elements[0]['objectid'],$templateobj->elements[0]['header']);
			$this->userhandler->addStyleCache($templateobj->elements[0]['objectid'],$templateobj->elements[0]['style']);
		}
	}

	function settemplatebyname($name) {
		$templateobj = owNew('template');
		$templateid = $templateobj->locatebyname($name);
		if ($templateid) {
			$this->settemplate($templateid);
		}
	}
	
	function installtemplate($name) {
		$templateobj = owNew('template');
		if  (!$templateobj->locateByName($name)) {
			$userid = $this->userhandler->getSystemAccountId();
			$cid = array();
			$templateobj = owImportObj($name,$this->userhandler->getSystemPath()."extension/".$this->extname."/standard/",$cid);
			$templateobj->setCreatedBy($userid);
		}
		
	}

	function generate() {
		$smarty =& $this->smarty;

		if ($this->templatefile != '') {
			$smarty->assign_by_ref("config",$this->extconfig);
			$smarty->assign_by_ref("result",$this->extresult);
			$smarty->assign_by_ref("_cmd",$this->next_extcmd);
			$smarty->assign_by_ref("document",$this->document);
			$smarty->assign_by_ref("me",$this->MeUrl);
			$smarty->assign_by_ref("indocument",$this->indocument);

			$smarty->assign("_ext",$this->extname);
			if (!empty($this->force_extcf)) {
				$smarty->assign_by_ref("_extcf",$this->force_extcf);
			} else {
				$smarty->assign_by_ref("_extcf",$this->extconfigset);
			}
			
			$this->extoutput = $smarty->fetch($this->templatefile);
		} else {
			$this->extoutput = '';
		}
	}


	function execute($configset, $params="", $function='_do', $indocument = true) {
		// Check if the requested function is legal.
		if (!in_array($function, $this->functions)) $function = '_do';

		$this->indocument = $indocument;
		$this->extconfigset = $configset;
		$this->getDefaultConfig();
		$this->readConfig();
		if (is_array($params)) $this->getParameters($params);

		// if this is the correct instance, set extcmd to the passed _cmd
		if ($_REQUEST['_extcf'] != "" && $_REQUEST['_extcf'] == $this->extconfigset && $_REQUEST['_ext'] == $this->extname) 
			$this->extcmd = $_REQUEST['_cmd'];
		
		$this->$function(); // will by default call the method _do()
		$this->generate();  // assign variables to smarty and fetch result
	}

	function addfunction($name) {
		if (is_array($name)) {
			$this->functions = array_merge($this->functions, $name);
		} else {
			$this->functions[] = $name;
		}
	}

	function addextparam($name) {
		if (is_array($name)) {
			$this->extparams = array_merge($this->extparams, $name);
		} else {
			$this->extparams[] = $name;
		}
	}

	function hasContentTree() {
		return method_exists($this, 'getContentTree');
	}
}

?>