<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage control
 */

class basic_control {
	var $context;
	var $otype;
	var $data;
	var $objectid;
	var $ret;
	var $parentid;
	var $relcol;
	var $relval;
	
	function getview($view) {
		$uh =& getUserHandler();
		$app = $uh->getAppName();
		if (file_exists(dirname(__FILE__).'/basic_view_'.$view.'.php')) 
			require_once('basic_view_'.$view.'.php');
		if (file_exists(dirname(__FILE__).'/'.$this->otype.'_view_'.$view.'.php')) {
			require_once($this->otype.'_view_'.$view.'.php');
		} elseif ($view == 'init' && file_exists(dirname(__FILE__).'/'.$this->otype.'_view_list.php')) {
			$view = 'list';
			require_once($this->otype.'_view_list.php');
		}
		if (owIsExtendedDatatype($this->otype)) {
			if (file_exists(dirname(__FILE__).'/extension/'.owGetBasedatatype($this->otype).'/'.$this->otype.'_view_'.$view.'.php'))
				require_once(dirname(__FILE__).'/extension/'.owGetBasedatatype($this->otype).'/'.$this->otype.'_view_'.$view.'.php');
		}
		if (!empty($app)) {
			if (file_exists(dirname(__FILE__).'/app/'.$app.'/'.$app.'_basic_view_'.$view.'.php')) {
				require_once('app/'.$app.'/'.$app.'_basic_view_'.$view.'.php');
			}
			if (file_exists(dirname(__FILE__).'/app/'.$app.'/'.$app.'_'.$this->otype.'_view_'.$view.'.php')) {
				require_once('app/'.$app.'/'.$app.'_'.$this->otype.'_view_'.$view.'.php');
			}
		}
		
		$s = 'basic_view_'.$view;
		if (!empty($app)) {
			if (class_exists($app.'_'.$this->otype.'_view_'.$view)) {
				$s = $app.'_'.$this->otype.'_view_'.$view;
			} elseif (class_exists($this->otype.'_view_'.$view)) {
				$s = $this->otype.'_view_'.$view;
			} elseif (class_exists($app.'_basic_view_'.$view)) {
				$s = $app.'_basic_view_'.$view;
			}
		} elseif (class_exists($this->otype.'_view_'.$view)) {
			$s = $this->otype.'_view_'.$view;
		}
		$obj = new $s;
		$obj->data = $this->data;
		$obj->otype = $this->otype;
		$obj->context = &$this->context;
		$obj->objectid = $this->objectid;
		$obj->ret = $this->ret;
		$obj->view = $view;
		$obj->parentid = $this->parentid;
		$obj->relcol = $this->relcol;
		$obj->relval = $this->relval;
#		$obj->isextendeddatatype = owIsExtendedDatatype($this->otype);
#		$obj->extension_basedatatype = owGetBasedatatype($this->otype);
		$obj->loadLanguage();
		return $obj;
	}
	
	function getmodel($model) {
		$uh =& getUserHandler();
		$app = $uh->getAppName();
		if (file_exists(dirname(__FILE__).'/basic_model_'.$model.'.php')) 
			require_once('basic_model_'.$model.'.php');
		if (file_exists(dirname(__FILE__).'/'.$this->otype.'_model_'.$model.'.php')) {
			require_once($this->otype.'_model_'.$model.'.php');
		}
		if (!empty($app)) {
			if (file_exists(dirname(__FILE__).'/app/'.$app.'/'.$app.'_basic_model_'.$model.'.php')) {
				require_once(dirname(__FILE__).'/app/'.$app.'/'.$app.'_basic_model_'.$model.'.php');
			}		
			if (file_exists(dirname(__FILE__).'/app/'.$app.'/'.$app.'_'.$this->otype.'_model_'.$model.'.php')) {
				require_once('app/'.$app.'/'.$app.'_'.$this->otype.'_model_'.$model.'.php');
			}		
		}
		
		$s = 'basic_model_'.$model;
		if (!empty($app)) {
			if (class_exists($app.'_'.$this->otype.'_model_'.$model)) {
				$s = $app.'_'.$this->otype.'_model_'.$model;
			} elseif (class_exists($this->otype.'_model_'.$model)) {
				$s = $this->otype.'_model_'.$model;
			} elseif (class_exists($app.'_basic_model_'.$model)) {
				$s = $app.'_basic_model_'.$model;
			}
		} elseif (class_exists($this->otype.'_model_'.$model)) {
			$s = $this->otype.'_model_'.$model;
		}
		$obj = new $s;
		$obj->data = $this->data;
		$obj->otype = $this->otype;
		$obj->context = &$this->context;
		$obj->objectid = $this->objectid;
		$obj->control = $model;
		$obj->parentid = $this->parentid;
		return $obj;	
	}

	function model($cmd) {
		foreach($cmd as $curcmd) {
			$obj = $this->getmodel($curcmd);
			$obj->model();
			$userhandler =& getUserHandler();
			$eventhandler =& GetEventHandler();
			if ($userhandler->getObjectIdStack()) {
				$eventhandler->event($curcmd, $this->otype, $userhandler->getObjectIdStack());
			} else {
				$eventhandler->event($curcmd, $this->otype, $this->objectid);
			}
			unset($obj);
		}
	}
	
	function view($view) {
		$errorhandler =& getErrorHandler();
		if ($errorhandler->haserror()) {
			echo nl2br($errorhandler->outputError());
#			die();
		}
		
		$userhandler =& getUserHandler();

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['_DONTCONVERT_'])) {
			$variables = array();
			foreach ($_GET as $key=>$val) {
				$variables[$key] = $val;
			}
			
			unset($_POST['cmd']);
			unset($_POST['MAX_FILE_SIZE']);
			
			$filtercols = array();
			
			if (!empty($this->otype)) {
				$filtercols = array_keys(owDatatypeCols($this->otype));
			}

			foreach ($_POST as $key=>$val) {
				$variables[$key] = $val;
			}

			foreach($filtercols as $remove) {
				unset($variables[$remove]);
			}
			
			$variables['_FROMPOST_'] = 1;
			$querystring = '';
			
			foreach ($variables as $key => $val) {
				if (!is_array($val)) {
					$querystring .= ($key . '=' . urlencode($val) . '&');
				}
			}
			
			$_SESSION['objectstack'] = $userhandler->getObjectIdStack();
			if ($errorhandler->haserror()) {
				$_SESSION['errorhandler'] = $errorhandler->error;
			}
			
			// Throw away output (should not be any)
			ob_end_clean();
			header('Location: '.$this->getGuiUrl().$querystring);
			
			die();
		} else {

			if (@$_GET['_FROMPOST_'] == 1) {
				$userhandler->setObjectidStack($_SESSION['objectstack']);
				unset($_SESSION['objectstack']);
				if (isset($_SESSION['errorhandler'])) {
					$errorhandler->error = $_SESSION['errorhandler'];
					unset($_SESSION['errorhandler']);
				}
			}
		}
		
		ob_start();

		foreach($view as $curview) {
			$obj = $this->getview($curview);
			echo $obj->view();
			$userhandler->clearObjectIdStack();
			unset($obj);
		}
		
		$theview = ob_get_contents();
		ob_end_clean();
		return $theview;
	}

	function viewcomplete($view) {
		$theview = $this->view($view);
		$context =& $this->context;
		
		ob_start();
		echo $context->header_start();
		echo $context->header_end();
		echo $context->body();
		echo $theview;
		$errorhandler =& GetErrorHandler();
		if ( $errorhandler->haserror()) echo '<script language="javascript">alert(\'' . str_replace("'", "\\'",$errorhandler->outputerror() ) . "');</script>";
		echo $context->footer();
		ob_end_flush();
	}


	function getGuiUrl() {
		$userhandler =& getUserHandler();
		if ($userhandler->getWebUser()) {
			return $_SERVER['PHP_SELF'].'?pageid='.$_REQUEST['pageid']."&";
		} else {
			return $userhandler->getSystemUrl() .'gui.php?w=1&';
		}
	}	
}
	
function getcontrol($otype,$objectid,&$context,$parentid='') {
	if (file_exists($otype.'_control.php')) {
		@require_once($otype.'_control.php');
	}
	if (class_exists($otype.'_control')) {
		$s = $otype.'_control';
	} else {
		$s = 'basic_control';
	}
	$obj = new $s;
	$obj->data = $_REQUEST;
	$obj->ret = @$_REQUEST['_ret'];
	$obj->otype = $otype;
	$obj->context = &$context;
	$obj->objectid = $objectid;
	if (isset($_REQUEST['_parentid'])) $obj->parentid = $_REQUEST['_parentid'];
	if ($parentid) $obj->parentid = $parentid;
	if (isset($_REQUEST['_relcol'])) $obj->relcol = $_REQUEST['_relcol'];
	if (isset($_REQUEST['_relval'])) $obj->relval = $_REQUEST['_relval'];
	return $obj;	
}
	
?>