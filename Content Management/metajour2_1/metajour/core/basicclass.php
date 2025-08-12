<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jesper Laursen <jl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

define('UI_STRING', 0);
define('UI_STRING_LITERAL', 8);
define('UI_TEXT', 1);
define('UI_TEXT_LITERAL', 9);
define('UI_TEXT_WRAP', 10);
define('UI_TEXT_LITERAL_WRAP', 11);
define('UI_DECIMAL', 12);
define('UI_PASSWORD', 2);
define('UI_HIDDEN', 4);
define('UI_CHECKBOX', 5);
define('UI_FILEUPLOAD', 6);
define('UI_BINFILE', 13);
define('UI_BINFILE_THUMB', 14);
define('UI_LANGUAGE', 50);
define('UI_COUNTRY', 51);
define('UI_READONLY',60);
define('UI_LISTDIALOG',61);
define('UI_LISTDIALOG_STRING',62);
define('UI_DATE', 70);
define('UI_USERGROUP', 902);
define('UI_CLASS', 903);
define('UI_COMPONENT', 904);
define('UI_COMBO', 905);
define('UI_COMBO_MULTIPLE', 906);
define('UI_USERSGROUPS', 907);
define('UI_USERSGROUPS_MULTIPLE', 908);
define('UI_APP', 909);
define('UI_APP_MULTIPLE', 910);
define('UI_RELATION', 1000);
define('UI_RELATION_MULTIPLE', 1002);
define('UI_RELATION_NODEFAULT', 1001);
define('UI_NOTHING', 2000);
define('UI_PHONE', 666);

define('ACCESS_ANONYMOUS', 0);
define('ACCESS_USER', 10);
define('ACCESS_EDITOR', 20);
define('ACCESS_MANAGER', 30);
define('ACCESS_ADMINISTRATOR', 40);

define('INDEX_NORMAL', 0);
define('INDEX_PARENT', 1);
define('INDEX_OBJECT', 2);

define('F_LITERAL',0);
define('F_REL',1);
define('F_REL_MUL',2);
define('F_COMBO',3);
define('F_COMBO_MUL',4);

define('LIKE',0);
define('LIKESTART',2);
define('LIKEEND',3);
define('GREATER',4);
define('GREATEREQUAL',5);
define('LESS',6);
define('LESSEQUAL',7);
define('EQUAL',8);
define('NOTEQUAL',9);
define('SOUNDSLIKE',10);
define('NOTSOUNDSLIKE',11);

define('IN_FORM', 1);
define('IN_VIEW', 2);
define('IN_LIST', 3);


require_once($system_path.'adodb.php');
require_once($system_path.'basic_error.php');
require_once($system_path.'basic_event.php');
require_once($system_path.'basic_user.php');

/* Disable that pesky magic_quotes */
if (get_magic_quotes_gpc()) {
	
	function stripslashes_deep($value) {
		$value = is_array($value) ? array_map('stripslashes_deep', $value) :stripslashes($value);
		return $value;
	}

	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}


/**
 * Baseclass for all core classes
 * @abstract
 * @package METAjour
 * @subpackage core
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 */

class basic {
	/**
	 * An array with all data abstractlistobjects.
	 * @access public
	 * @var array
	 * @see _getObjects()
	 */
	var $elements;
	/**
	 * The number of elements in $elements.
	 * @var integer
	 * @see elements
	 * @access public
	 */
	var $elementscount;
	/**
	 * Internal variable holding the settings and data of the current object
	 * Only valid after executing readobject()
	 * @access private
	 * @var array
	 */
	var $prv_options;
	/**
	 * Errorhandler object
	 * @access public
	 * @var object
	 */
	var $errorhandler;
	/**
	 * Userhandler object
	 * @access public
	 * @var object
	 */
	var $userhandler;
	/**
	 * Errorhandler object
	 * @access public
	 * @var object
	 */
	var $eventhandler;
	/**
	 * Latest _getObjects error code
	 * @access private
	 * @var integer
	 * see getReadError()
	 */
	var $readerror = 0;
	/**
	 * Indicates if 2 objects of the same type can 
	 * have identical names
	 * @access public
	 * @var bool
	 */
	var $allowduplicate = TRUE;
	/**
	 * Indicates if an object can have an empty name
	 * @access public
	 * @var bool
	 */
	var $allownoname = TRUE;
	/**
	 * Holds the name of the parent class (if any)
	 * @access private
	 * @var bool
	 * @see setSuperType()
	 * @see getSuperType()
	 */
  var $supertype = '';
	/**
	 * Holds the name of the child class (if any)
	 * @access private
	 * @var bool
	 * @see setSubType()
	 * @see getSubType()
	 */
  var $subtype = '';
	/**
	 * The name of the primary table containing the data for this datatype
	 * Automatically set to current classname in constructor
	 * @var string
	 * @access private
	 * @see setobjecttable()
	 */
  	var $objecttable = '';
	/**
	 * The name of the datatype (and class)
	 * Automatically set to current classname in constructor
	 * @var string
	 * @access private
	 * @see setobjecttype()
	 */
  var $type = '';
  var $standard_sort_colname = 'name';
  var $standard_sort_way = 'ASC';
  
  var $filter_variant = FALSE;
  var $filter_createdby = FALSE;
  var $filter_future = FALSE;
  var $filter_history = FALSE;
	var $filter_category = FALSE;
	var $filter_deleted = FALSE;
	var $filter_data = FALSE;
	var $filter_datavalue = FALSE;
	var $filter_name = FALSE;
	var $filter_nameonly = FALSE;
	var $filter_exactname = FALSE;
	var $filter_approved = -1;
  var $filter_groupby = FALSE;
	var $filter_limit = FALSE;
	var $sort_colname = FALSE;
	var $sort_way = 'ASC';
	var $filter_getname = FALSE;
	var $filter_searchcolname = array();
	var $filter_searchvalue = array();
	var $filter_searchtype = array();
	var $filter_advsearchcolname = array();
	var $filter_advsearchvalue = array();
	var $filter_advsearchtype = array();
	var $filter_listvariants = FALSE;
	var $listaccess = FALSE;
	var $filter_specialpurpose = FALSE;
	var $filter_rawwhere = FALSE;
	
	/**
	 * Indicates use of UI_RELATION_MULTIPLE or UI_COMBO_MULTIPLE
	 * Automatically set by addColumn
	 * @var bool
	 * @access private
	 */
	var $_hasmultiple = FALSE;			# 
	var $_hasrelatedfields = FALSE;			# 
	var $_view = array();
	var $_relationdatatypes = array();
	var $_childdatatypes = array();
	var $_useapp = FALSE;
	var $prv_column = array();
	var $_columnidx = array();
	var $_removeelement = false;
	var $_forcevariant = false;
	var $_multitypes_old = array();
	var $_multitypes = array();

	function basic() {
		$this->_multitypes_old = array(UI_RELATION_MULTIPLE, UI_COMBO_MULTIPLE, UI_USERSGROUPS_MULTIPLE, UI_APP_MULTIPLE);
		$this->_multitypes = array(F_REL_MUL, F_COMBO_MUL);
		$this->errorhandler =& getErrorHandler();
		$this->eventhandler =& getEventHandler();
		$this->userhandler =& getUserHandler();
		$userhandler =& $this->userhandler;
		$this->site = $userhandler->getSite();
		$this->system_url = $userhandler->getSystemUrl();
		$this->system_path = $userhandler->getSystemPath();
		$this->viewer_url = $userhandler->getViewerUrl();
		$this->viewer_path = $userhandler->getViewerPath();
		$this->webuser = $userhandler->getWebuser();
		  
		$this->_adodb =& getDbConn();
		$this->_view = array();
		$this->initViews();
		$this->setobjecttype(get_class($this));
		$this->setobjecttable(get_class($this));
	}

	#########################################################################################
	#
	#  CLASS CONSTRUCTION
	#
	#########################################################################################

	function setUseApp($value) {
		$this->_useapp = $value;
	}
	
	function getViews() {
		return $this->_view;
	}
	
	function allowed($name) {
		return in_array($name, $this->_view) && ($this->userhandler->GetLevel() >= 40 || $this->userhandler->GetProfileView($this->type,$name));
	}
	
	function addView($name) {
		$this->_view[] = $name;
	}
	
	function removeView($name) {
		$i = array_search($name,$this->_view);
		if ($i !== false) unset($this->_view[$i]);
	}
	
	function initViews() {
		#defines all the default views usually available
		#apply additional views with addview
		#remove views with removeview
		#$this->addview('menu');
		$this->addview('list');
		#$this->addview('tree');
		$this->addview('view');
		$this->addview('create');
		$this->addview('edit');
		$this->addview('delete');
		$this->addview('move');
		$this->addview('properties');

		$this->addview('createdby');
		$this->addview('changedby');
		$this->addview('checkedby');
		$this->addview('created');
		$this->addview('changed');
		$this->addview('checked');
		$this->addview('language');
		$this->addview('publish');
		$this->addview('access');
		$this->addview('active');
		$this->addview('approved');
		$this->addview('category');
		$this->addview('readonly');

		$this->addview('createcopy');
		$this->addview('createvariant');
	}

	function setObjectType($name) {
		$this->type = $name;
	}

	function getObjectType() {
		return $this->type;
	}

	function tableUpdate() {
	}
	
	/**
	 * @return int index key of value $name in the $this->prv_column array
	 */
	function getColumnIdx($name) {
		return $this->_columnidx[$name];
	}

	function initLayout() {
		foreach ($this->prv_column as $key => $val) {
			if (is_string($val['inputtype'])) {
				require_once('field/'.$val['inputtype'].'.field.php');
				$s = $val['inputtype'].'field';
				$this->prv_column[$key]['obj'] = new $s;
				$this->prv_column[$key]['obj']->setName($val['name']);
				if (!empty($val['validate'])) $this->prv_column[$key]['obj']->setValidation($val['validate']);
				if (!empty($val['relation'])) $this->prv_column[$key]['obj']->setRelation($val['relation']);
				if (!empty($val['style'])) $this->prv_column[$key]['obj']->addStyle($val['style']);
			}
		}
		$this->addChildDatatype('note');
		$this->addChildDatatype('binfile');
	}
	
	function convertToDatabase($arr) {
		foreach ($this->prv_column as $key => $val) {
			if (isset($arr[$val['name']]) && is_object($this->prv_column[$key]['obj'])) {
				if ($arr[$val['name']] == '' && $this->prv_column[$key]['obj']->emptyisnull) {
					$arr[$val['name']] = NULL;
				} else {
					$arr[$val['name']] = $this->prv_column[$key]['obj']->convertToDatabase($arr[$val['name']]);
				}
			}
		}
		return $arr;
	}
		
	function addColumn($name, $coltype=0, $inputtype=0, $relation='', $validate='') {
		$this->_columnidx[$name] = sizeof($this->prv_column);
		$this->prv_column[] = array('name' => $name, 'type' => $coltype, 'inputtype' => $inputtype, 'relation' => $relation, 'validate' => $validate, 'obj' => false);
		if ($inputtype == UI_RELATION_MULTIPLE || $inputtype == UI_COMBO_MULTIPLE || $inputtype == UI_USERSGROUPS_MULTIPLE || $inputtype == UI_APP_MULTIPLE
		|| $coltype == F_REL_MUL || $coltype == F_COMBO_MUL) $this->_hasmultiple = true;
	}
	
	function hasFieldType($field) {
		foreach ($this->prv_column as $f) {
			if ($f['inputtype'] === $field) return true;
		}
		return false;
	}
	
	function getColumns() {
		return $this->prv_column;
	}

	function stdListCol() {
		$arr = array();
		$arr[] = 'name';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		$arr[] = 'language';
		$arr[] = 'objectid';
		return $arr;
	}
	
	function stdListInfocol() {
		$arr = array();
		if ($this->allowed('default')) $arr[] = 'default';
		$arr[] = 'active';
		$arr[] = 'permission';
		$arr[] = 'variant';
		if ($this->allowed('createfuture')) $arr[] = 'locked';
		return $arr;
	}

	function &getColObj($name) {
		if ($this->prv_column[$this->_columnidx[$name]]['obj']) {
			return $this->prv_column[$this->_columnidx[$name]]['obj'];
		} else {
			return false;
		}
	}
	
	function addColumnStyle($name, $style) {
		$this->prv_column[$this->_columnidx[$name]] = array_merge($this->prv_column[$this->_columnidx[$name]], array('style' => $style));
	}

	function addFieldsetStyle($name, $style) {
		$this->prv_column[$this->_columnidx[$name]] = array_merge($this->prv_column[$this->_columnidx[$name]], array('fieldsetstyle' => $style));
	}

	function addLabelStyle($name, $style) {
		$this->prv_column[$this->_columnidx[$name]] = array_merge($this->prv_column[$this->_columnidx[$name]], array('labelstyle' => $style));
	}

	function addFieldStyle($name, $style) {
		$this->prv_column[$this->_columnidx[$name]] = array_merge($this->prv_column[$this->_columnidx[$name]], array('fieldstyle' => $style));
	}

	function combineFields($name1, $name2) {
		$this->prv_column[$this->_columnidx[$name1]] = array_merge($this->prv_column[$this->_columnidx[$name1]], array('skipend' => "1"));
		$this->prv_column[$this->_columnidx[$name2]] = array_merge($this->prv_column[$this->_columnidx[$name2]], array('skipstart' => "1"));
	}

	function byside2($name1, $name2) {
		$this->byside($name1, $name2);
	}

	function byside3($name1, $name2, $name3) {
		$this->byside($name1, $name2, $name3);
	}

	function byside4($name1, $name2, $name3, $name4) {
		$this->byside($name1, $name2, $name3, $name4);
	}
	
	function byside() {
		$a = func_get_args();
		for($i=1;$i<count($a);$i++) {
			$this->combineFields($a[$i-1], $a[$i]);
		}
	}
	
	function clearRelationDatatypes() {
		$this->_relationdatatypes = array();
	}
	
	function addRelationDatatype($datatype,$column,$foreigncolumn) {
		$arr['datatype'] = $datatype;
		$arr['column'] = $column;
		$arr['foreigncolumn'] = $foreigncolumn;
		$this->_relationdatatypes[] = $arr;
	}
	
	function relateFields($masterfield, $detailfield, $foreigncolumn) {
		$this->_hasrelatedfields = TRUE;
		$this->prv_column[$this->_columnidx[$masterfield]]['detailfield'] = $detailfield;
		$this->prv_column[$this->_columnidx[$masterfield]]['foreigncolumn'] = $foreigncolumn;

		// this information tells UI_LISTDIALOG where to look for
		// information about which datatype and field that should limit the
		// dialog-window. Same principle as for addRelationDatatype
		// in a completely different way.
		$this->prv_column[$this->_columnidx[$detailfield]]['masterfield'] = $masterfield;
		$this->prv_column[$this->_columnidx[$detailfield]]['foreigncolumn'] = $foreigncolumn;
	}
	
	function getRelationDatatypes() {
		return $this->_relationdatatypes;
	}

	function clearChildDatatypes() {
		$this->_childdatatypes = array();
	}
	
	function addChildDatatype($datatype) {
		$this->_childdatatypes[] = $datatype;
	}
		
	function getChildDatatypes() {
		return $this->_childdatatypes;
	}
	
	function setSubtype($name) {
		$this->subtype = $name;
	}

	function setSupertype($name) {
		$this->supertype = $name;
	}

	/**
	 * @return string objecttype of child class (not child object!)
	 */
	function getSubtype() {
		return $this->subtype;
	}

	/**
	 * @return string objecttype of parent class (not parent object!)
	 */
	function getSupertype() {
		return $this->supertype;
	}

	function setObjectTable($name) {
		$this->objecttable = $name;
	}

	#########################################################################################
	#
	#  ACCESS CONTROL
	#
	#########################################################################################

	function setListAccess($value) {
		$this->listaccess = $value;
	}

	function canTouch() {
		if (!$this->userhandler->GetRestrictLanguage()) return true;
		$res = $this->_adodb->getOne("SELECT count(*) from OBJECT where objectid = ".$this->getObjectId()." and language=" 
		  . $this->_adodb->qstr($this->userhandler->GetObjectLanguage()));
		return ($res > 0) ? true : false;
	}

	/**
	 * Used to check if the current user has access to the current object
	 *
	 * Administrator has always access.
	 * Other users depend on the objects permissions
	 * @return boolean true if the user has access, false otherwise
	 */
	function hasAccess() {
		
		$total = (isset($this->prv_options['access'])) ? sizeof($this->prv_options['access']) : 0;
		if ($this->webuser && !$total) return true;
		
		if (!$this->webuser && $this->listaccess) return true;
		
		$userlevel = $this->userhandler->getLevel();
		if ($userlevel >= ACCESS_MANAGER) return true;
		
		if ($this->userhandler->getUnlimitedAccess()) return true;
		
		$userid = $this->userhandler->getObjectId();
		if (!$this->webuser && $userid == $this->prv_options['createdby']) return true;
		
		$res = false;
			
		if ($total > 0) {
			$memberof = $this->userhandler->getGroups();
			
			if ($this->webuser) {
				$res = true;
				
				foreach ($this->prv_options['access'] as $access) {
					if ($userlevel > ACCESS_ANONYMOUS && $access['user_read'] == $userid) return true;
					if ($access['user_read'] != 0) $res = false;
					
					if (is_array($memberof)) {
						if (in_array($access['group_read'], $memberof)) return true;
					}
					if ($access['group_read'] != 0) $res = false;
				}
			} else {
				foreach ($this->prv_options['access'] as $access) {
					if ($access['user_write'] == $userid) return true;
					if (is_array($memberof)) {
						if (in_array($access['group_write'], $memberof)) return true;
					}
				}
			}
		}

		return $res;
	}

	function setAccess($readaccess, $writeaccess) {
		if (!$this->cantouch()) return false;

		$this->_adodb->execute("DELETE FROM object_access WHERE objectid = ".$this->getObjectId());

		$haspermission = false;
		if (is_array($readaccess)) {
			foreach ($readaccess as $val) {
				if ($val != '') {
					$val = (int)$val;
					if ($val < 0) {
						$this->_adodb->execute("INSERT INTO object_access (objectid, group_read) VALUES (".$this->getObjectId().", " . abs($val) . ")");
					} else {
						$this->_adodb->execute("INSERT INTO object_access (objectid, user_read) VALUES (".$this->getObjectId().", " . $val . ")");
					}
					$haspermission = true;
				}
			}
		}
		
		if (is_array($writeaccess)) {
			foreach ($writeaccess as $val) {
				if ($val != '') {
					$val = (int)$val;
					if ($val < 0) {
						$this->_adodb->execute("INSERT INTO object_access (objectid, group_write) VALUES (".$this->getObjectId().", " . abs($val) . ")");
					} else {
						$this->_adodb->execute("INSERT INTO object_access (objectid, user_write) VALUES (".$this->getObjectId().", " . $val . ")");
					}
					$haspermission = true;
				}
			}
		}

		if ($haspermission) {
			$this->_setOption('haspermission', '1');
		} else {
			$this->_setOption('haspermission', '0');
		}
		return true;
	}

	/**
	 * @return Array with objectids for users which have access to the current object
	 */
	function resolveAccess($forceSystemCheck = false) {
		// If we don't get passed an objectid we use the id for the current object
		$objectid = $this->getobjectid();
		$result = array();
		if ($this->webuser && !$forceSystemCheck) {
			$obj = owRead($objectid);
			$obj->webuser = $this->webuser;

			$total = (isset($obj->prv_options['access'])) ? sizeof($obj ->prv_options['access']) : 0;

			$ug = owNew('usergroup');
			if ($this->webuser) {
				$x = 0;
				while ($x < $total) {
					$result[] = $obj->prv_options['access'][$x]['user_read'];

					if ($obj->prv_options['access'][$x]['group_read']) {
						$ug->readobject($obj->prv_options['access'][$x]['group_read']);
						$members = $ug->getmembers();
						if (is_array($members)) {
							$result = array_merge($result, $members);
						}
					}
					$x++;
				}

				if (count($result) == 0) {
					// No permissions found. Defaults to all users
					$users = owNew('user');
					$users->listobjects();
					foreach($users->elements as $element) {
						$result[] = $element['object']['objectid'];
					}
				}

				$ug = owNew('usergroup');
				$ug->readobject($ug->getsystemgroup(ACCESS_ADMINISTRATOR));
				$members = $ug->getmembers();
				if (is_array($members)) {
					$result = array_merge($result, $members);
				}
				owget($objectid, $obj);
				$obj->readobject($objectid);
				// Owner always has access
				$result[] = $obj->getcreatedby();
			}

		} else {

			// Administrators always has access
			$ug = owNew('usergroup');
			$ug->readobject($ug->getsystemgroup(ACCESS_ADMINISTRATOR));
			$members = $ug->getmembers();
			if (is_array($members)) {
				$result = $members;
			}
			
			$ug = owNew('usergroup');
			$ug->readobject($ug->getsystemgroup(ACCESS_MANAGER));
			$members = $ug->getmembers();
			if (is_array($members)) {
				$result = array_merge($result, $members);
			}
			
			$obj = owRead($objectid);
			// Owner always has access
			$result[] = $obj->getcreatedby();

			$total = (isset($obj->prv_options['access'])) ? sizeof($obj ->prv_options['access']) : 0;
			
			$x = 0;
			while ($x < $total) {
				$result[] = $obj->prv_options['access'][$x]['user_write'];
				
				if ($obj->prv_options['access'][$x]['group_write'] != 0) {
					$ug = new usergroup;
					if ($obj->prv_options['acccess'][$x]['group_write']) 
						$ug->readobject($obj->prv_options['acccess'][$x]['group_write']);
					$members = $ug->getmembers();
					if (is_array($members)) {
						$result = array_merge($result, $members);
					}
				}
				$x++;
			}
		}

		// Remove duplicates. Does not use array_unique as it introduces "holes" in the array.
		$tmp = array_flip($result);
		unset($tmp[0]);
		return array_flip($tmp);
	}

	
	#########################################################################################
	#
	#  CATEGORIES
	#
	#########################################################################################


	/**
	 * Used to check if an object is member of a category
	 * @param categoryid
	 * @return bool true if objectid is a member of categoryid, else false
	 */
	function isMember($categoryid) {
		$categoryid = (int)$categoryid;
		return ($this->_adodb->getOne("SELECT COUNT(*) FROM object_category WHERE objectid = ".$this->getObjectId()." AND categoryid = $categoryid") > 0) ? true : false;
	}

	/**
	 * Add an objectid to one or more categories
	 * @param array categoryid
	 * @param int objectid
	 */
	function setCategory($category) {
		if (!$this->cantouch()) return false;

		$this->_adodb->execute("DELETE FROM object_category WHERE objectid = ".$this->getObjectId());
		$hascategory = false;
		foreach ($category as $val) {
			$val = (int)$val;
			if ($val) $this->_adodb->execute("REPLACE INTO object_category (objectid, categoryid) VALUES (".$this->getObjectId().", $val)");
			$hascategory = true;
		}
		
		if ($hascategory) {
			$this->_setOption('hascategory', '1');
		} else {
			$this->_setOption('hascategory', '0');
		}
		return true;
	}

	/**
	 * Add an objectid to a category
	 * @param int categoryid
	 * @param int objectid
	 */
	function setSingleCategory($categoryid) {
		if (!is_numeric($categoryid)) {
			$this->errorhandler->setError('setsinglecategory', 'Categoryid must be an integer!');
			fatalError();
			return false;
		}
		if (!$this->cantouch()) return false;
		$this->_setOption('hascategory', '1');
		$this->_adodb->execute("REPLACE INTO object_category (objectid, categoryid) VALUES (".$this->getObjectId().", $categoryid)");
		return true;
	}

	function getCategory() {
		return $this->_adodb->getAll("SELECT categoryid FROM object_category WHERE objectid = ".$this->getObjectId());
	}

	/**
	 * @return bool true if this object or any of it's childs (recursively)
	 * are needed by other objects through cross-references
	 */
	function isRequired() {
		$a = $this->getRequiredBy();
		return !empty($a);
	}

	/**
	 * @return array of objectid's of objects that depends on the
	 * current object or any of it's childs
	 */	
	function getRequiredBy() {
		$result = array();
		$childs = $this->getChilds();
		if (!empty($childs)) {
			foreach ($childs as $cur) {
				$obj = owRead($cur);
				if ($obj) {
					$arr = $obj->getRequiredBy();
				} else {
					$arr = false;
				}
				if ($arr) $result = array_merge($result,$arr);
			}
		}
		$arr = $this->_adodb->getCol("SELECT DISTINCT objectid FROM object_dependency WHERE dependson = ".$this->getObjectId());
		if ($arr) $result = array_merge($result,$arr);
		return $result;
	}
	
	#########################################################################################
	#
	#  CHILD OBJECTS
	#
	#########################################################################################

	/**
	 * @return bool true if current object has one or more child objects
	 */
	function hasChild() {
		return $this->prv_options['haschild'];
	}

	/**
	 * @return array of objectid's for child objects of current object
	 */
	function getChilds() {
		return ($this->hasChild()) 
			? $this->_adodb->getCol("SELECT objectid FROM object WHERE site = " . $this->site . " AND parentid = " . $this->getObjectId() . " AND deleted = 0 AND variantof = 0 ORDER BY childorder")
			: array();
	}

	/**
	 * @return int number of child objects of current object
	 */
	function getNumChild() {
		return sizeof($this->getChilds());
	}

	#########################################################################################
	#
	#  FUTURE REVISIONS
	#
	#########################################################################################

	/**
	 * @return bool true if current object has one or more previous revisions
	 */
	function hasOldRevision() {
		return $this->prv_options['hasoldrevision'];
	}

	/**
	 * @return bool true if current object has one or more future objects
	 */
	function hasFutureRevision() {
		return $this->prv_options['hasfuturerevision'];
	}


	/**
	 * @return array of objectid's for future objects of current object
	 */
	function getFutureRevisions() {
		return ($this->hasFutureRevision())
			? $this->_adodb->getCol("SELECT objectid FROM object WHERE site = " . $this->site . " AND futurerevisionof = " . $this->getObjectId() ." AND deleted = 0")
			: array();
	}

	function getCurrentRevision() {
		return ($this->prv_options['futurerevisionof']) ? $this->prv_options['futurerevisionof'] : $this->prv_options['oldrevisionof'];
	}
	
	function getOldRevisions() {
		return ($this->hasOldRevision())
			? $this->_adodb->getCol("SELECT objectid FROM object WHERE site = " . $this->site ." AND oldrevisionof = " . $this->getObjectId() . " AND deleted = 0")
			: array();
	}

	/**
	 * @return number of future objects of current object
	 */
	function getNumFutureRevision() {
		return sizeof($this->getFutureRevisions());
	}

	#########################################################################################
	#
	#  VARIANTS
	#
	#########################################################################################

	/**
	 * @return bool true if current object has one or more variant objects
	 */
	function hasVariant() {
		return $this->prv_options['hasvariant'];
	}

	/**
	 * @return bool true if current object is a variant of another object
	 */
	function isVariant() {
		return $this->prv_options['variantof'] > 0;
	}

	/**
	 * @return array of objectid's for variant objects of current object
	 */
	function getVariants($language='') {
		if ($this->hasvariant()) {
			$query = ($language != '') ?
				"select objectid from object where site = '$this->site' and variantof = '".$this->prv_options['objectid']."' and deleted = 0 and language = '$language'"
				: "select objectid from object where site = '$this->site' and variantof = '".$this->prv_options['objectid']."' and deleted = 0";
			return $this->_adodb->getCol($query);
		} else {
			return array();
		}
	}

	/**
	 * @return array of objectid's and language for variant objects of current object
	 */
	function getVariantsLang($language='') {
		if ($this->hasvariant()) {
			$query = ($language != '') ?
				"select objectid, language from object where site = '$this->site' and variantof = '".$this->prv_options['objectid']."' and deleted = 0 and language = '$language'"
				: "select objectid, language from object where site = '$this->site' and variantof = '".$this->prv_options['objectid']."' and deleted = 0";
			return $this->_adodb->getAll($query);
		} else {
			return array();
		}
	}

	/**
	 * @return int number of variant objects of current object
	 */
	function getNumVariant() {
		return sizeof($this->getvariants());
	}


	#########################################################################################
	#
	#  FAMILY OBJECTS (other objects with same parent as current object)
	#
	#########################################################################################


	/**
	 * @return array of objectid's for objects with same parent as current object
	 */
	function getSiblings() {
		return $this->_adodb->getCol("select objectid from object where site = '$this->site' and parentid = '".$this->prv_options['parentid']."' and deleted = 0 and variantof = 0 order by childorder");
	}

	/**
	 * @return int number of objects with same parent as current object but with lower order
	 */
	function getFamilyBefore($child) {
		return $this->_adodb->getOne("select count(*) from object where site = '$this->site' and parentid = '".$this->prv_options['parentid']."' and deleted = 0 and variantof = 0 and childorder < $child");
	}

	/**
	 * @return int number of objects with same parent as current object
	 */
	function getNumSiblings() {
		return sizeof($this->getSiblings());
	}

	#########################################################################################
	#
	#  MISC. OBJECT INFORMATION GET/SET
	#
	#########################################################################################


	/**
	 * @return int site of current object
	 */
	function getSite() {
		return $this->prv_options['site'];
	}

	/**
	 * @return string type of current object
	 */
	function getType() {
		return $this->prv_options['type'];
	}

	/**
	 * @return int objectid of current object
	 */
	function getObjectId() {
		return $this->prv_options['objectid'];
	}

	/**
	 * @return int createdby (user-objectid) of current object
	 */
	function getCreatedBy() {
		return $this->prv_options['createdby'];
	}

	/**
	 * @return string datetime of creation of current object
	 */
	function getCreated() {
		return $this->prv_options['created'];
	}

	/**
	 * @return int changedby (user-objectid) of current object
	 */
	function getChangedBy() {
		return $this->prv_options['changedby'];
	}

	/**
	 * @return string datetime of last update of current object
	 */
	function getChanged() {
		return $this->prv_options['changed'];
	}

	/**
	 * @return int checkedby (user-objectid) of current object
	 */
	function getCheckedBy() {
		return $this->prv_options['checkedby'];
	}

	/**
	 * @return string datetime of last check of current object
	 */
	function getChecked() {
		return $this->prv_options['checked'];
	}

	/**
	 * @return string publication datetime of current object
	 */
	function getPublish() {
		// For performance reasons this function is inlined
		// in basic::isExpired();
		return $this->prv_options['publish'];
	}

	/**
	 * @return int parentid (objectid) of current object
	 */
	function getParentId() {
		return $this->prv_options['parentid'];
	}

	/**
	 * @return string expiration datetime of current object
	 */
	function getExpire() {
		// For performance reasons this function is inlined
		// in basic::isExpired();
		return $this->prv_options['expire'];
	}

	function getChildOrder() {
		return $this->prv_options['childorder'];
	}

	function getReadonly() {
		return $this->prv_options['readonly'];
	}

	function isChild() {
		return ($this->prv_options['parentid'] != 0) ? 1 : 0;
	}

	function getLanguage() {
		return $this->prv_options['language'];
	}

	function isExpired() {
		#$publish = $this->prv_options['publish'];
		#$expire = $this->prv_options['expire'];
		if ($this->prv_options['publish']  == '0000-00-00 00:00:00' && $this->prv_options['expire'] == '0000-00-00 00:00:00') return false;
		if ($this->prv_options['publish'] != '0000-00-00 00:00:00' && date('Y-m-d H:i:s') <= $this->prv_options['publish']) return true;
		if ($this->prv_options['expire'] != '0000-00-00 00:00:00' && date('Y-m-d H:i:s') >= $this->prv_options['expire']) return true;
		return false;
	}

	function pendingApproval() {
		return $this->prv_options['approved'] == 2;
	}


	function isLocked() {
		return $this->hasFutureRevision() || $this->pendingApproval() || $this->isOldRevision();
	}

	function isApproved() {
		return $this->prv_options['approved'] == 1;
	}

	function isOldRevision() {
		return $this->prv_options['oldrevisionof'] != 0;
	}

	function isActive() {
		return $this->prv_options['active'];
	}

	function isReadonly() {
		return $this->prv_options['readonly'];
	}

	function isDeleted() {
		return $this->prv_options['deleted'];
	}

	function getName() {
		return $this->elements[0]['name'];
	}

	function getAccess() {
		return $this->elements[0]['access'];
	}
	
	function getObjectArray() {
		return $this->prv_options;
	}

	function getVariantOf() {
		return $this->prv_options['variantof'];
	}

	function _setOption($opt, $val) {
		if (!$this->cantouch()) return false;
		$this->_adodb->execute("update object set $opt = " . $this->_adodb->qstr($val) . " where objectid = ".$this->getObjectId());
		$this->prv_options[$opt] = $val;
		return true;
	}

	function _foreignSetOption($objectid, $opt, $val) {
		$this->_adodb->execute("update object set $opt = " . $this->_adodb->qstr($val) . " where objectid = $objectid");
	}

	function setParentId($value) {
		# vi sætter haschild = 1 på det nye parentobjekt
		$oldparentid = $this->prv_options['parentid'];
		$this->_foreignSetOption($value, 'haschild', '1');
		$this->_setOption('parentid', $value);
		# vi checker om haschild skal ændres på det gamle parentobjekt
		if ($oldparentid > 0) $this->updateHasChild($oldparentid);
	}

	function setVariantOf($value) {
		# vi sætter hasvariant = 1 på originalobjektet
		$this->_foreignSetOption($value, 'hasvariant', '1');
		$this->_setOption('variantof', $value);
	}

	function setFutureRevisionOf($value) {
		# vi sætter hasfuturerevision = 1 på originalobjektet
		$this->_foreignSetOption($value, 'hasfuturerevision', '1');
		$this->_setOption('futurerevisionof', $value);
	}

	function setHasFutureRevision($value) {
		$this->_setOption('hasfuturerevision', $value);
	}

	function setOldRevisionOf($value) {
		# vi sætter hasoldrevision = 1 på originalobjektet
		$this->_foreignSetOption($value, 'hasoldrevision', '1');
		$this->_setOption('oldrevisionof', $value);
	}

	function hasExtradata() {
		return $this->prv_options['hasextradata'];
	}
	
	function setExtradata($extradata) {
		if (is_array($extradata)) {
			$this->_setOption('hasextradata', true);
			foreach($extradata as $key => $val) {
				$this->_adodb->execute("replace into object_extradata values (".$this->getObjectId().", '$key', '$val')");
			}
		} else {
			$this->_setOption('hasextradata', false);
		}
	}

	function setVariantFields($extradata) {
		$this->_adodb->execute("delete from object_variantfield where objectid = '".$this->getObjectId()."'");
		if (is_array($extradata)) {
			foreach($extradata as $val) {
				$this->_adodb->execute("insert into object_variantfield values (".$this->getObjectId().", '$val')");
			}
		}
	}

	function getVariantFields() {
		return $this->_adodb->getCol("select field from object_variantfield where objectid = '".$this->getObjectId()."'");
	}

	function setCreated($value) {
		if ($value == '') {
			$value = date('Y-m-d H:i:s');
		}
		$this->_setOption('created', $value);
	}


	function setCreatedBy($value) {
		$this->_setOption('createdby', $value);
	}

	function setChangedBy($value) {
		$this->_setOption('changedby', $value);
	}

	function setChanged($value) {
		if ($value == '') {
			$value = date('Y-m-d H:i:s');
		}
		$this->_setOption('changed', $value);
	}

	function setCheckedBy($value) {
		$this->_setOption('checkedby', $value);
	}

	function setChecked($value) {
		if ($value == '') {
			$value = date('Y-m-d H:i:s');
		}
		$this->_setOption('checked', $value);
	}

	/**
	 * set webhidden flag on current object
	 * indicates that this object is not visible outside METAZO
	 */
	function setWebHidden($value) {
		$this->_setOption('webhidden', $value);
	}

	/**
	 * set syshidden flag on current object
	 * indicates that this object is not visible inside METAZO
	 * used on auto-created webusers
	 */
	function setSysHidden($value) {
		$this->_setOption('syshidden', $value);
	}
	
	/**
	 * set standard flag on current object, alias of setDefault
	 */
	function setStandard() {
		$this->setDefault();
	}

	/**
	 * set standard flag on current object
	 */
	function setDefault() {
		$tempid = $this->_adodb->getone("select objectid from object where site = '".$this->site."' and type = '".$this->type."' and standard = 1");
		if ($tempid) {
			$this->_adodb->execute("update object set standard=0 where objectid = $tempid");
		}
		$this->_setOption('standard',1);
	}

	/**
	 * set language of current object
	 */
	function setLanguage($value) {
		$this->_setOption('language', $value);
	}

	/**
	 * set publish datetime of current object
	 */
	function setPublish($value) {
		if ($value == '') {
			$value = '0000-00-00 00:00:00';
		}
		$this->_setOption('publish', $value);
	}

	/**
	 * set expiration datetime of current object
	 */
	function setExpire($value) {
		if ($value == '') {
			$value = '0000-00-00 00:00:00';
		}
		$this->_setOption('expire', $value);
	}

	function setApproved($value) {
		if ($value) {
			$this->_setOption('approved', 1);
		} else {
			$this->_setOption('approved', 0);
		}		
	}

	function setPending($value) {
		if ($value) {
			$this->_setOption('approved', 2);
		} else {
			$this->_setOption('approved', 0);
		}		
	}

	/**
	 * set active flag on current object
	 */
	function setActive($value) {
		$this->_setOption('active', $value);
	}

	/**
	 * set readonly flag on current object
	 */
	function setReadonly($value) {
		$this->_setOption('readonly', $value);
	}

	#########################################################################################
	#
	#  OBJECT OPERATIONS
	#
	#########################################################################################

	function moveUp() {
		# her skal noget cantouch ind
		$row = $this->_adodb->getrow("select objectid, childorder from object where site = '$this->site' and type = '$this->type' and childorder < ".$this->prv_options['childorder']." and deleted = 0 and variantof = 0 and parentid = ".$this->prv_options['parentid']." order by childorder DESC");
		if (count($row)) {
			$aboveid = $row['objectid'];
			$thisorder = $this->prv_options['childorder'];
			$aboveorder = $row['childorder'];
			$this->_foreignSetOption($aboveid, 'childorder', $thisorder);
			$this->_setOption('childorder', $aboveorder);
		}
	}

	function moveDown() {
		# her skal noget cantouch ind
		$row = $this->_adodb->getrow("select objectid, childorder from object where site = '$this->site' and type = '$this->type' and childorder > ".$this->prv_options['childorder']." and deleted = 0 and variantof = 0 and parentid = ".$this->prv_options['parentid']." order by childorder");
		if (count($row)) {
			$aboveid = $row['objectid'];
			$thisorder = $this->prv_options['childorder'];
			$aboveorder = $row['childorder'];
			$this->_foreignSetOption($aboveid, 'childorder', $thisorder);
			$this->_setOption('childorder', $aboveorder);
		}
	}

	function moveTo($val) {
		# her skal noget cantouch ind
		$this->_adodb->execute("update object set childorder = childorder+2 where site = '$this->site' and type = '$this->type' and parentid = ".$this->prv_options['parentid']." and childorder > $val");
		$val++;
		$this->_adodb->execute("update object set childorder = ".$val." where site = '$this->site' and objectid = ".$this->prv_options['objectid']);
	}

	function moveToAbsolute($val) {
		$this->_adodb->execute("update object set childorder = ".$val." where site = '$this->site' and objectid = ".$this->prv_options['objectid']);
	}

	function moveBefore($val) {
		# her skal noget cantouch ind
		$this->_adodb->execute("update object set childorder = childorder+2 where site = '$this->site' and type = '$this->type' and parentid = ".$this->prv_options['parentid']." and childorder >= $val");
		$val++;
		$this->_adodb->execute("update object set childorder = ".$val." where site = '$this->site' and objectid = ".$this->getObjectId());
	}

	function updateHasChild($parentid) {
		if (!$parentid) return false;

		$count = $this->_adodb->getone("select count(*) from object where parentid=$parentid and deleted=0");
		if (!$count) {
			$this->_adodb->execute("update object set haschild = 0 where objectid = $parentid");
		}
		return true;
	}

	function updateHasFutureRevision($parentid) {
		if (!$parentid) return false;

		$count = $this->_adodb->getone("select count(*) from object where futurerevisionof=$parentid and deleted=0");
		if (!$count) {
			$this->_adodb->execute("update object set hasfuturerevision = 0 where objectid = $parentid");
		}
		
		return true;
	}
	
	function hasDependent() {
		# must be overrided by childclass
		return false;
	}
	
	function deactivateDependent() {
		# must be overrided by childclass		
	}

	/**
	 *
	 */
	function prv_DeleteObject() {
		$this->_adodb->execute("DELETE FROM $this->objecttable WHERE objectid = ".$this->getObjectId());
        return true;
	}
	
	/**
	 *
	 */
	function deleteObject() {
		if (!$this->canTouch()) return false;
		// exit if this object or any of it's childs are required by other objects
		
		if ($this->isRequired()) return false;
		
		if (!$this->prv_options['readonly']) $this->_setOption('deleted', true);
		
		$childs = $this->getChilds();
		if (is_array($childs) && !empty($childs)) {
			foreach ($childs as $cur) {
				$obj = owRead($cur);
				if ($obj) { 
					$res = $obj->deleteObject();
					if (!$res) return false;
				}
			}
		}
		$variants = $this->getVariants();
		if (is_array($variants) && !empty($variants)) {
			foreach ($variants as $cur) {
				$obj = owRead($cur);
				$res = $obj->deleteObject();
				if (!$res) return false;
			}
		}
		$future = $this->getFutureRevisions();
		if (is_array($future) && !empty($future)) {
			foreach ($future as $cur) {
				$obj = owRead($cur);
				$res = $obj->deleteObject();
				if (!$res) return false;
			}
		}
		$old = $this->getOldRevisions();
		if (is_array($old) && !empty($old)) {
			foreach ($old as $cur) {
				$obj = owRead($cur);
				$res = $obj->deleteObject();
				if (!$res) return false;
			}
		}
		
		if ($this->getVariantOf()) {
			$variantscount = $this->_adodb->getone("select count(*) from object where variantof = " . $this->getVariantOf() . " and deleted = 0");
			if (!$variantscount) $this->_foreignSetOption($this->getVariantOf(), 'hasvariant', false);
		}
		
		// we delete all dependencies, so it's possible to later delete
		// objects used by the current object
		
		$this->_adodb->execute("delete from object_dependency where objectid = ".$this->getObjectId());
		
		// we clear the value of any references to this object
		// this should not occur, since it's not possible to delete
		// objects referenced by other objects
		
		$this->_adodb->execute("update object_search set fieldvalue = 0, fieldrep = '' where fieldvalue = ".$this->getObjectId());

		$this->updateHasChild($this->getParentId());
		$this->updateHasFutureRevision($this->prv_options['futurerevisionof']);
		return true;
	}

	/**
	 *
	 */
	function undeleteObject() {
		$this->_setOption('deleted', false);
		$childs = $this->_adodb->getCol("select objectid from object where parentid = '".$this->getObjectId()."' and deleted = 1 and variantof = 0 order by childorder");
		if (is_array($childs) && !empty($childs)) {
			foreach ($childs as $cur) {
				$this->errorhandler->disable();
				$obj = owNew(owGetDatatype($cur));
				$obj->setfilter_deleted(true);
				$obj->readobject($cur);
				$this->errorhandler->enable();
				$res = $obj->undeleteobject();
				if (!$res) return false;
			}
		}
		$count = $this->_adodb->getone("select count(*) from object where parentid=".$this->getParentId()." and deleted=0");
		if (!$count) {
			$this->_adodb->execute("update object set haschild = 0 where objectid = ".$this->getParentId());
		}
		if ($count) {
			$this->_adodb->execute("update object set haschild = 1 where objectid = ".$this->getParentId());
		}
		return true;
	}

	/**
	 *
	 */
	function eraseObject($objectid) {
		# der skal også laves rekursiv sletning
		if ($this->userhandler->GetLevel() < ACCESS_ADMINISTRATOR) return false;
		if (!is_array($objectid)) $objectid = array($objectid);
		
		$childs = $this->_adodb->getCol("select objectid from object where parentid = '".$this->getObjectId()."' and variantof = 0");
		if (is_array($childs) && !empty($childs)) {
			foreach ($childs as $cur) {
				$obj = owRead($cur);
				$res = $obj->eraseObject();
				if (!$res) return false;
			}
		}
		$variants = $this->_adodb->getCol("select objectid from object where variantof = ".$this->getObjectId());
		if (is_array($variants) && !empty($variants)) {
			foreach ($variants as $cur) {
				$obj = owRead($cur);
				$res = $obj->eraseObject();
				if (!$res) return false;
			}
		}
		$future = $this->_adodb->getCol("select objectid from object where futurerevisionof = ".$this->getObjectId());
		if (is_array($future) && !empty($future)) {
			foreach ($future as $cur) {
				$obj = owRead($cur);
				$res = $obj->eraseObject();
				if (!$res) return false;
			}
		}
		$old = $this->_adodb->getCol("select objectid from object where oldrevisionof = ".$this->getObjectId());
		if (is_array($old) && !empty($old)) {
			foreach ($old as $cur) {
				$obj = owRead($cur);
				$res = $obj->eraseObject();
				if (!$res) return false;
			}
		}
		
		$counter = 0;        
		foreach ($objectid as $id) {            
			$obj = owRead($id);            
			if ($obj && $obj->deleteObject() && $obj->prv_deleteobject()) {
				$this->_adodb->execute("delete from object where objectid = ".$id);
				$this->_adodb->execute("delete from object_access where objectid = ".$id);
				$this->_adodb->execute("delete from object_category where objectid = ".$id);
				$this->_adodb->execute("delete from object_dependency where objectid = ".$id);
				$this->_adodb->execute("delete from object_extradata where objectid = ".$id);
				$this->_adodb->execute("delete from object_multiple where objectid = ".$id);
				$this->_adodb->execute("delete from object_search where objectid = ".$id);
				$this->_adodb->execute("delete from object_variantfield where objectid = ".$id);
				$counter++;
			}
		}
		return $counter;
	}

	function _isDuplicate($name) {
		$sql = "select name from object, " .$this->objecttable . " where site = '$this->site'" .
		" and object.objectid = ".$this->objecttable.".objectid and object.deleted = 0".
		" and name = '$name' and object.objectid <> '".$this->getObjectId()."'"; $result = $this->_adodb->getone($sql);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	function createObject_Precheck($arr) {
		return true;
	}
	
	function prv_CreateObject($arr) {

		$cols = '';
		$values = '';

		foreach ($this->prv_column as $val) {
			// check if the column has been posted and is not a
			// multiple-field
			if (isset($arr[$val['name']]) && !in_array($val['inputtype'],$this->_multitypes_old) && !in_array($val['type'],$this->_multitypes)) {
				$cols .= $val['name'] . ", ";
				if (is_null($arr[$val['name']])) {
					$values .=  "NULL, ";
				} else {
					$values .=  $this->_adodb->qstr($arr[$val['name']]) .", ";
				}
			}
		}

		$sql = ($cols != '') ?
			"insert into $this->objecttable (objectid, " . substr($cols, 0, -2) . ") values (".$this->getObjectId().", ". substr($values, 0, -2) .")"
			: "insert into $this->objecttable (objectid) values (".$this->getObjectId().")";

		$res = $this->_adodb->execute($sql);
		if (!$res) $this->errorhandler->seterror('prv_createobject:sql_error: ' . $this->_adodb->errorMsg());
			
		if ($this->_hasmultiple) {
			foreach ($this->prv_column as $val) {
				if (isset($arr[$val['name']]) && (in_array($val['inputtype'],$this->_multitypes_old) || in_array($val['type'],$this->_multitypes))) {
					if (is_array($arr[$val['name']])) {
						foreach ($arr[$val['name']] as $value) {
							if ($value != '') {
								$sql = sprintf('insert into object_multiple values (%s, "%s", "%s")',
								               $this->getObjectId(), $val['name'], $this->_adodb->qstr($value));
								$res = $this->_adodb->execute($sql);
								if (!$res) $this->errorhandler->seterror('prv_createobject:sql_error: ' . $this->_adodb->errorMsg());
							}
						}
					}
				}
			}
		}
		
		foreach($this->prv_column as $val) {
			if ($val['inputtype'] == 'inline' && !empty($val['relation'])) {
				$obj = owNew($val['relation']);
				$s = '__'.$val['name'].'__name';
				if (isset($arr['__'.$val['name'].'__name'])) {
					$i = 0;
					foreach($arr['__'.$val['name'].'__name'] as $dummy) {
						$subarr = array();
						foreach($obj->prv_column as $cur) {
							if (isset($arr['__'.$val['name'].'__'.$cur['name']][$i]))
								$subarr[$cur['name']] = $arr['__'.$val['name'].'__'.$cur['name']][$i];
						}
						if ($arr['__'.$val['name'].'__status'][$i] == 'N') {
							$obj->createObject($subarr,$this->getObjectId());
						}
						$i++;
					}
				}
			}
		}
	}
	
	function createObject($arr,$parentid=0, $prv_options=array()) {
		$this->initLayout();
		$arr = $this->convertToDatabase($arr);
		if (!$this->createObject_Precheck($arr)) return false;
		if (!$this->allowduplicate) {
			if ($this->_isDuplicate($arr['name'])) {
				$this->errorhandler->setError('basic_duplicate');
				return false;
			}
			if (empty($arr['name'])) {
				$this->errorhandler->setError('basic_noname');
				return false;
			}
		}
		if (!$this->allownoname) {
			if (empty($arr['name'])) {
				$this->errorhandler->setError('basic_noname');
				return false;
			}
		}
		$this->prv_options['objectid'] = 0;
		$this->prv_options['site'] = $this->site;
		$this->prv_options['type'] = $this->type;
		$this->prv_options['createdby'] = $this->userhandler->GetObjectId();
		$this->prv_options['created'] = date('Y-m-d H:i:s');
		$this->prv_options['changedby'] = $this->userhandler->GetObjectId();
		$this->prv_options['changed'] = date('Y-m-d H:i:s');
		$this->prv_options['checkedby'] = $this->userhandler->GetObjectId();
		$this->prv_options['checked'] = date('Y-m-d H:i:s');
		$this->prv_options['publish'] = "0000-00-00 00:00:00";
		$this->prv_options['expire'] = "0000-00-00 00:00:00";
		$this->prv_options['childorder'] = 0;
		$this->prv_options['parentid'] = 0;
		$this->prv_options['language'] = $this->userhandler->GetObjectLanguage();
		$this->prv_options['approved'] = true;
		$this->prv_options['active'] = true;
		$this->prv_options['readonly'] = false;
		$this->prv_options['haschild'] = false;
		$this->prv_options['haspermission'] = false;
		$this->prv_options['deleted'] = false;
		$this->prv_options['futurerevisionof'] = 0;
		$this->prv_options['hasfuturerevision'] = false;
		$this->prv_options['hascategory'] = false;
		$this->prv_options['hasextradata'] = false;
		$this->prv_options['hasrules'] = false;
		$this->prv_options['hasvariant'] = false;
		$this->prv_options['variantof'] = 0;
		$this->prv_options['oldrevisionof'] = 0;
		$this->prv_options['hasoldrevision'] = false;
		$this->prv_options['standard'] = false;
		$this->prv_options['webhidden'] = false;
		$this->prv_options['syshidden'] = false;
		
		// Merge overruled options
		foreach ($prv_options AS $key => $value) {
			$this->prv_options[$key] = $value;
		}
		
		if ($this->_useapp) {
			$this->prv_options['useapp'] = $this->userhandler->getAppName();
		} else {
			$this->prv_options['useapp'] = '';
		}
		$res = $this->_adodb->execute("insert into object (site,type,createdby,created,
						changedby, changed, checkedby, checked, publish, expire, childorder,
						parentid, language, approved,active, readonly, haschild,
						haspermission, deleted, futurerevisionof, hasfuturerevision, hascategory,
						hasextradata, hasrules, hasvariant, variantof, oldrevisionof, hasoldrevision,
						standard, webhidden, syshidden, useapp)
					values (
						'".$this->prv_options['site']."',
						'".$this->prv_options['type']."',
						'".$this->prv_options['createdby']."',
						'".$this->prv_options['created']."',
						'".$this->prv_options['changedby']."',
						'".$this->prv_options['changed']."',
						'".$this->prv_options['checkedby']."',
						'".$this->prv_options['checked']."',
						'".$this->prv_options['publish']."',
						'".$this->prv_options['expire']."',
						'".$this->prv_options['childorder']."',
						'".$this->prv_options['parentid']."',
						'".$this->prv_options['language']."',
						'".$this->prv_options['approved']."',
						'".$this->prv_options['active']."',
						'".$this->prv_options['readonly']."',
						'".$this->prv_options['haschild']."',
						'".$this->prv_options['haspermission']."',
						'".$this->prv_options['deleted']."',
						'".$this->prv_options['futurerevisionof']."',
						'".$this->prv_options['hasfuturerevision']."',
						'".$this->prv_options['hascategory']."',
						'".$this->prv_options['hasextradata']."',
						'".$this->prv_options['hasrules']."',
						'".$this->prv_options['hasvariant']."',
						'".$this->prv_options['variantof']."',
						'".$this->prv_options['oldrevisionof']."',
						'".$this->prv_options['hasoldrevision']."',
						'".$this->prv_options['standard']."',
						'".$this->prv_options['webhidden']."',
						'".$this->prv_options['syshidden']."',
						'".$this->prv_options['useapp']."'
						)");

		if (!$res) $this->errorhandler->seterror('createobject:sql_error: ' . $this->_adodb->errorMsg());

		$this->prv_options['objectid'] = $this->_adodb->insert_id();

		if ($this->type == "user") $arr['name'] = str_replace('¤_¤',$this->prv_options['objectid'],$arr['name']);

		if ($parentid <> 0) {
			$this->setParentId($parentid);
			$max = $this->_adodb->getone("select max(childorder) from object where parentid = $parentid") + 1;
			$this->_setOption('childorder', $max);
		}

		$this->prv_CreateObject($arr);
		$this->readObject($this->getObjectId());
		$this->createSearchIndex();
		return true;
	}

	function createSearchIndex() {
		$idx = owReadTextual($this->getObjectId());
		$this->_adodb->execute("DELETE FROM object_search WHERE objectid = ".$this->getObjectId());
		$this->_adodb->execute("DELETE FROM object_dependency WHERE objectid = ".$this->getObjectId());
		foreach ($idx as $cur) {
			if ($cur['fieldrep'] != '') {
				if (is_array($cur['fieldrep'])) $cur['fieldrep'] = implode(', ', $cur['fieldrep']);
				$sql = sprintf("INSERT INTO object_search 
				               (objectid, fieldname, fieldvalue, fieldrep, language, variantof) 
				               VALUES (%d, %s, %d, %s, '%s', %d)", $this->getObjectId(), 
				               $this->_adodb->qstr($cur['name']), $cur['fieldvalue'],
				               $this->_adodb->qstr($cur['fieldrep']), $this->getLanguage(), $this->getVariantOf());
				$res = $this->_adodb->execute($sql);
				if (!$res) $this->errorhandler->seterror('createsearchindex:sql_error: ' . $this->_adodb->errorMsg());
			}

			if ($cur['fieldvalue']) {
				$sql = sprintf("INSERT INTO object_dependency (objectid, dependson) VALUES (%d, %d)",
				               $this->getObjectId(), $cur['fieldvalue']);
				$res = $this->_adodb->execute($sql);
				if (!$res) $this->errorhandler->seterror('createsearchindex:sql_error: ' . $this->_adodb->errorMsg());
			}

		}
	}
	
	function updateObject_Precheck($arr) {
		return true;
	}

	/**
	 * 
	 */
	function prv_UpdateObject($arr) {
		
		foreach($this->prv_column as $val) {
			if ($val['inputtype'] == 'inline' && !empty($val['relation'])) {
				$obj = owNew($val['relation']);
				$s = '__'.$val['name'].'__name';
				if (isset($arr['__'.$val['name'].'__name'])) {
					$i = 0;
					foreach($arr['__'.$val['name'].'__name'] as $dummy) {
						$subarr = array();
						foreach($obj->prv_column as $cur) {
							if (isset($arr['__'.$val['name'].'__'.$cur['name']][$i]))
								$subarr[$cur['name']] = $arr['__'.$val['name'].'__'.$cur['name']][$i];
						}
						if ($arr['__'.$val['name'].'__status'][$i] == 'N') {
							$obj->createObject($subarr,$this->getObjectId());
						} elseif ($arr['__'.$val['name'].'__objectid'][$i] != '' ) {
							$tobj = owRead($arr['__'.$val['name'].'__objectid'][$i]);
							$tobj->updateObject($subarr);
						}
						$i++;
					}
				}
			}
		}
		
		$sql = '';
		foreach($this->prv_column as $val) {
			if (isset($arr[$val['name']]) && !in_array($val['inputtype'],$this->_multitypes_old) && !in_array($val['type'],$this->_multitypes)) {
				if (is_null($arr[$val['name']])) {
					$sql .= $val['name']." = NULL, ";
				} else {
					$sql .= $val['name']." = ". $this->_adodb->qstr($arr[$val['name']]) .", ";
				}
			}
		}
		if ($sql != '') {
			$sql = "UPDATE $this->objecttable SET " . substr($sql, 0, -2) . " WHERE objectid = ".$this->getObjectId();
			$res = $this->_adodb->execute($sql);
			if (!$res) $this->errorhandler->seterror('prv_updateobject:sql_error: ' . $this->_adodb->errorMsg());
		}
		
		if ($this->_hasmultiple) {
			$this->_adodb->execute('DELETE FROM object_multiple WHERE objectid = '.$this->getObjectId());
			foreach ($this->prv_column as $val) {
				if (isset($arr[$val['name']]) && (in_array($val['inputtype'], $this->_multitypes_old) || in_array($val['type'], $this->_multitypes))) {
					foreach ($arr[$val['name']] as $value) {
						if ($value != '') {
							$sql = sprintf('INSERT INTO object_multiple VALUES (%d, %s, %s)',
							     $this->getObjectId(), $this->_adodb->qstr($val['name']), $this->_adodb->qstr($value));
							$res = $this->_adodb->execute($sql);
							if (!$res) $this->errorhandler->seterror('prv_updateobject:sql_error: ' . $this->_adodb->errorMsg());
						}
					}
				}
			}
		}
	}

	function updateObject($arr) {
		if (!$this->cantouch()) return false;
		$this->initLayout();
		$arr = $this->convertToDatabase($arr);

		if (!$this->updateObject_Precheck($arr)) return false;
		if (!$this->allowduplicate) {
			if ($this->_isDuplicate($arr['name']) && isset($arr['name'])) {
				$this->errorhandler->setError('basic_duplicate');
				return false;
			}
			if (empty($arr['name']) && isset($arr['name'])) {
				$this->errorhandler->setError('basic_noname');
				return false;
			}
		}
		if (!$this->allownoname) {
			if (empty($arr['name']) && isset($arr['name'])) {
				$this->errorhandler->setError('basic_noname');
				return false;
			}
		}

		$value = date('Y-m-d H:i:s');
		$this->_adodb->execute("UPDATE object SET changed = '$value', changedby = " . $this->userhandler->GetObjectId() . " WHERE objectid = ".$this->getObjectId());
		$parentid = $this->_adodb->getone("SELECT parentid FROM object WHERE objectid = ".$this->getObjectId());
		if ($parentid > 0) $this->_adodb->execute("UPDATE object SET changed = '$value', changedby = ".$this->userhandler->GetObjectId()." WHERE objectid = $parentid");
		$this->prv_UpdateObject($arr);
		$this->readObject($this->getObjectId());
		$this->createSearchIndex();
		return true;
	}

	function setfilter_rawwhere($value) {
		$this->filter_rawwhere = $value;
	}

	function setfilter_variant($value) {
		$this->filter_variant = $value;
	}

	function setfilter_createdby($value) {
		$this->filter_createdby = $value;
	}

	function setfilter_future($value) {
		$this->filter_future = $value;
	}

	function setfilter_history($value) {
		$this->filter_history = $value;
	}

	function setfilter_category($value) {
		$this->filter_category = $value;
	}

	function setfilter_name($value) {
		$this->filter_name = $value;
	}
	
	function setfilter_nameonly($value) {
		$this->filter_nameonly = $value;
	}

	function setfilter_groupby($value) {
		$this->filter_groupby = $value;
	}

	function setfilter_data($colname, $value) {
		$this->filter_data = $colname;
		$this->filter_datavalue = $value;
	}

	function setfilter_search($colname, $value, $type = 0) {
		$this->filter_searchcolname[] = $colname;
		$this->filter_searchvalue[] = $value;
		$this->filter_searchtype[] = $type;
	}

	function setfilter_advsearch($colname, $value, $type = 0) {
		$this->filter_advsearchcolname[] = $colname;
		$this->filter_advsearchvalue[] = $value;
		$this->filter_advsearchtype[] = $type;
	}

	function setfilter_deleted($value) {
		$this->filter_deleted = $value;
	}

	function setfilter_approved($value) {
		$this->filter_approved = $value;
	}

	function setsort_col($colname) {
		$this->sort_colname = $colname;
	}

	function setsort_way($s) {
		$this->sort_way = $s;
	}

	function setfilter_getname($value) {
		$this->filter_getname = $value;
	}
	
	function setfilter_limit($start, $count) {
		$this->filter_limit = array('start'=>$start, 'count'=>$count);
	}

	function forceVariant($value = true) {
		$this->_forcevariant = $value;
	}
	
	/**
	 * @abstract
	 */
	function prv_ReadObject() {
		return false;
	}

	/**
	 * @return objectid of object with name
	 */
	function locateByName($name) {
		$result = $this->_adodb->getone("SELECT " . $this->objecttable . ".objectid AS res FROM " . $this->objecttable . ", object
		                                WHERE " . $this->objecttable . ".objectid = object.objectid AND site=" . $this->site . " AND name = " . $this->_adodb->qstr($name) . "
		                                AND object.deleted = 0");
		#if (!$result) $this->errorhandler->seterror('basic_locatebyname');
		return $result;
	}

	/**
	 * @return bool true on succesfull reading of object with name $name and false on failure
	 */ 
	function readObjectByName($name) {
		$result = $this->locateByName($name);
		if ($result) {
			$this->readObject($result);
			if ($this->readerror != 0) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * @return objectid of object where column standard = 1
	 */
	function locateDefault() {
		$res = $this->_adodb->query("SELECT objectid FROM object WHERE site = ".$this->site." AND type = '".$this->type."' AND standard = 1 AND deleted = 0");
		if ($res === false) {
			$this->errorhandler->seterror('basic_locatedefault');
			return false;
		} else {
			$row = $res->fetchRow();
			return $row['objectid'];
		}
	}

	function readObject($objectid) {
		return $this->_getObjects(0,$objectid);
	}

	function readAllObjectsByObjectId($parentid=0) {
		return $this->_getObjects($parentid,0,INDEX_OBJECT);
	}

	function readAllObjectsByParentId() {
		return $this->_getObjects(0,0,INDEX_PARENT);
	}

	function listObjects($parentid=0,$objectid=0) {
		return $this->_getObjects($parentid,$objectid);
	}

	function getNumElements($parentid = 0, $objectid = 0) {
		$result = 0;
		if ($objectid != 0 && !is_array($objectid)) {
			$sql = "select count(*) from object, $this->objecttable where site = '$this->site' and type = '$this->type' and object.objectid = $objectid and object.objectid = ".$this->objecttable.".objectid";
		} else {
			$sql = $this->_getSql($parentid, $objectid, true);
		}
		
		
		$result = $this->_adodb->getOne($sql);
		if ($result === false)
		$this->errorhandler->seterror("SQL-error: " . $this->_adodb->errorMsg());
		
		return $result;
		
	}
	
	function _getSql($parentid=0,$objectid, $count = false) {
		$filter = '';
		$addfrom = '';
		$sorting = '';
		$star = '*';

		if ($this->_useapp) {
			$filter .= ' and object.useapp = "'.$this->userhandler->getAppName().'"';
		}
		($this->webuser) ?
			$filter .= ' and object.webhidden = 0'
			: $filter .= ' and object.syshidden = 0';

		($this->filter_variant) ?
			$filter .= ' and object.variantof = '.$this->filter_variant
			: $filter .= ' and object.variantof = 0';

		if ($this->filter_createdby) 
			$filter .= ' and object.createdby = '.$this->filter_createdby;

		($this->filter_future) ?
			$filter .= ' and object.futurerevisionof = '.$this->filter_future
			: $filter .= ' and object.futurerevisionof = 0';

		($this->filter_history) ? 
			$filter .= ' and object.oldrevisionof = '.$this->filter_history 
			: $filter .= ' and object.oldrevisionof = 0';

		if ($this->filter_category) {
			$addfrom = ", object_category";
			$filter .= " and object.objectid = object_category.objectid and object_category.categoryid = ".$this->filter_category;
		}

		if (!empty($this->filter_searchcolname)) {
			$si = 0;
			foreach ($this->filter_searchcolname as $colname) {
				$type = $this->filter_searchtype[$si];
				$value = $this->filter_searchvalue[$si];

				switch ($type) {
					case LIKE:
							$filter .= ' and '.$colname." LIKE '%".$value."%'";
							break;
					case LIKESTART:
							$filter .= ' and '.$colname." LIKE '".$value."%'";
							break;
					case LIKEEND:
							$filter .= ' and '.$colname." LIKE '%".$value."'";
							break;
					case GREATER:
							$filter .= ' and '.$colname." > '".$value."'";
							break;
					case GREATEREQUAL:
							$filter .= ' and '.$colname." >= '".$value."'";
							break;
					case LESS:
							$filter .= ' and '.$colname." < '".$value."'";
							break;
					case LESSEQUAL:
							$filter .= ' and '.$colname." <= '".$value."'";
							break;
					case NOTEQUAL:
							$filter .= ' and '.$colname." <> '".$value."'";
							break;
					case EQUAL:
							$filter .= ' and '.$colname." = '".$value."'";
							break;
					case SOUNDSLIKE:
							$filter .= ' and SUBSTRING(SOUNDEX('.$colname."),1,4) = SUBSTRING(SOUNDEX('".$value."'),1,4)";
							break;
					case NOTSOUNDSLIKE:
							$filter .= ' and SOUNDEX('.$colname.") <> SOUNDEX('".$value."')";
							break;
				}
				$si++;
			}
		}


		if (!empty($this->filter_advsearchcolname)) {
			$si = 0;
			foreach ($this->filter_advsearchcolname as $colname) {
				$p = '';
				
				if ( is_string($this->prv_column[$this->_columnidx[$colname]]['inputtype'])) {
					if ($this->prv_column[$this->_columnidx[$colname]]['inputtype'] == 'decimal') {
						$p = '+0 ';
					}
				}

				$type = $this->filter_advsearchtype[$si];
				$value = $this->filter_advsearchvalue[$si];
				switch ($type) {
					case LIKE:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep LIKE '%$value%')";
							break;
					case LIKESTART:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep LIKE '$value%')";
							break;
					case LIKEEND:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep LIKE '%$value')";
							break;
					case GREATER:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep > '$value' $p )";
							break;
					case GREATEREQUAL:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep >= '$value' $p )";
							break;
					case LESS:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep < '$value' $p )";
							break;
					case LESSEQUAL:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep <= '$value' $p )";
							break;
					case NOTEQUAL:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep <> '$value' $p )";
							break;
					case EQUAL:
							$filter .= " and object.objectid IN (select objectid from object_search os where os.fieldname = '$colname' and os.fieldrep = '$value' $p )";
							break;
/*					case SOUNDSLIKE:
							$filter .= " and SUBSTRING(SOUNDEX('.$colname."),1,4) = SUBSTRING(SOUNDEX('".$value."'),1,4)";
							break;
					case NOTSOUNDSLIKE:
							$filter .= " and SOUNDEX('.$colname.") <> SOUNDEX('".$value."')";
							break;*/
				}
				$si++;
			}
		}

		if ($this->filter_name) $filter .= " and ".$this->objecttable.".name LIKE '%".$this->filter_name."%'";

		if ($this->filter_exactname) $filter .= " and ".$this->objecttable.".name = '".$this->filter_exactname."'";

		if ($this->filter_data) $filter .= " and ".$this->filter_data." = '".$this->filter_datavalue."'";

		($this->filter_deleted) ?
			$filter .= ' and object.deleted = 1' 
			: $filter .= ' and object.deleted = 0';

		if ($this->filter_approved == 0) $filter .= ' and object.approved = 0';

		if ($this->filter_approved == 1) $filter .= ' and object.approved = 1';

		if ($this->filter_getname && !$count) {
			
			if ($this->type == 'user') {
				$star = 'object.*, '.$this->objecttable.'.*, user2.name as createdbyname';
				$filter .= ' and user2.objectid = object.createdby';
				$addfrom .= ', user user2';
			} else {
				$filter .= ' and user.objectid = object.createdby';
				$addfrom .= ', user';
				$star = 'object.*, '.$this->objecttable.'.*, user.name as createdbyname';
			}
			
			
		}
		
		if ($this->filter_nameonly) {
				$star = 'object.*, ' . $this->objecttable  . '.name';
		}
		
		if ($this->sort_colname) {
			$sortcol = $this->sort_colname;
			if ($sortcol == 'objectid') $sortcol = 'object.objectid';
			
			foreach($this->prv_column as $val) {
				if (($val['name'] == $sortcol) && ($val['inputtype'] == UI_RELATION || $val['inputtype'] == UI_RELATION_NODEFAULT || $val['inputtype'] == UI_COMBO || $val['inputtype'] == UI_LISTDIALOG)) {
					$addfrom .= " LEFT JOIN object_search ON ( object.objectid = object_search.objectid AND object_search.fieldname = '".$val['name']."' )";
					$sortcol = 'object_search.fieldrep';
				}
			}
			
			$sorting = $sortcol . " " . $this->sort_way . ", ";
		}
		
		if ($this->filter_specialpurpose == 'doccount') {
			$addfrom .= " LEFT JOIN document_count ON (object.objectid = document_count.objectid)";
			$filter .= " AND document_count.objectid > 0 ";
		}

		if ($this->filter_rawwhere) {
			$filter .= ' '.$this->filter_rawwhere;
		}
		

		if (!is_array($objectid)) {
			$typesql = " and object.type = '$this->type'";
			$objectidsql = '';
		} else {
			$typesql = '';
			$objectidsql = ' and object.objectid IN ('.implode(',',$objectid).')';
		}
		
		
		if ($count) {
			$star = "count(*)";
			$sorting = '';
		}
		
		$groupby = '';
		if ($this->filter_groupby) {
			$groupby = ' group by '
			.$this->objecttable.'.'.$this->filter_groupby[0].','
			.$this->objecttable.'.'.$this->filter_groupby[1]
			;
			$star = 'count(*) as syscount,'
			.$this->objecttable.'.'.$this->filter_groupby[0].','
			.$this->objecttable.'.'.$this->filter_groupby[1]
			;
			$sorting = '';
		}
		
		if (is_null($parentid)) {
			## testkode for nyt opslag baseret på parentid
		$result =   "select $star from object, ".$this->objecttable .
		  " $addfrom where object.site = '$this->site'". $typesql .
		  " and ".$this->objecttable.".objectid = object.objectid " .
		  $objectidsql.
		  $filter ." order by $sorting object.childorder";
		} else {
		$result =   "select $star from object, ".$this->objecttable .
		  " $addfrom where object.site = '$this->site'". $typesql .
		  " and object.parentid = '$parentid' $filter and ".$this->objecttable.".objectid = object.objectid " .
		  $objectidsql.
		  $groupby." order by $sorting object.childorder";
		}

		return $result;
	}

	function getReadError() {
		return $this->readerror;
	}
	
	function resolveVariants($candidates_for_variants, $idxtype, $language) {
		global $ADODB_EXTENSION;
		if (!empty($candidates_for_variants)) {
			
			$count = count($candidates_for_variants);
			$variants = array();
			$db =& $this->_adodb;
			$i = 0;
			
			while ($i < $count) {
				$variantids = array_slice($candidates_for_variants, $i, 1000);
					
				$in = implode(',', $variantids);
				unset($variantids);
				$sql = "SELECT * FROM object, " . $this->objecttable . " WHERE object.variantof in ($in) AND object.objectid = " . $this->objecttable . ".objectid AND object.deleted = 0 AND object.language = '$language'";
				unset($in);
				$res =& $db->query($sql);
				unset($sql);
				while (!$res->EOF) {
					$row =& $res->fields;
					$variantof = $row['variantof'];
					if ($row['language'] == $selectedlanguage) {
						$variants[$variantof] = $row;
					} else {
						if (!isset($variants[$variantof])) {
							$variants[$variantof] = $row;
						}
					}
					if ($ADODB_EXTENSION) {
						adodb_movenext($res);
					} else {
						$res->moveNext();
					}
					
				}
				$i = $i + 1000;
			}
				
			foreach ($this->elements as $index=>$element) {
				if ($idxtype == INDEX_PARENT) {
					$count = count($element);
				} else {
					$count = 1;
				}
					
				for ($i = 0; $i < $count; ++$i) {
						
					$objectid = ($idxtype == INDEX_PARENT) ? $element[$i]['objectid'] : $element['objectid'];
					if (isset($variants[$objectid])) {
						$variant = $variants[$objectid];
						if (!empty($variant)) {
							$active = $variant['active'];
							if ($active) {
								$vfields = $db->getcol("SELECT field FROM object_variantfield WHERE objectid = ".$variant['objectid']);
								if (!empty($vfields)) {
									foreach ($vfields as $field) {
										/**
										 * @todo handle variants with UI_COMBO_MULTIPLE and UI_RELATION_MULTIPLE fields
										 * @todo handle variants with extradata fields
										*/
										
										if ($idxtype == INDEX_PARENT) {
											$this->elements[$index][$i][$field] = $variant[$field];
										} else {
											$this->elements[$index][$field] = $variant[$field];
										}
										
									}
								}
							} else {
								if ($idxtype == INDEX_PARENT) {
									unset($this->elements[$index][$i]);
								} else {
									unset($this->elements[$index]);
								}
							}
						}
					}
				}
				// Make sure there are no "holes" in our array.
				if ($idxtype == INDEX_PARENT) {
					$this->elements[$index] = array_values($this->elements[$index]);
				}
					
			}
		}
		
		// Make sure there are no "holes" in our array.
		if ($idxtype == INDEX_NORMAL) {
			$this->elements = array_values($this->elements);
		}
		unset($variants);
	}
	
	/**
	 * @return bool true on read succes, false when no record found or current user don't have access to object
	 */
	function _getObjects($parentid,$objectid,$idxtype = INDEX_NORMAL) {
		global $ADODB_EXTENSION;
		if (!is_numeric($parentid) && !is_null($parentid))
			fatalError('Parentid must be an integer!');
			
		if (!is_numeric($objectid) && !is_array($objectid)) 
			fatalError('Objectid must be an integer!');
		if (is_array($objectid)) {
			foreach ($objectid as $curid) {
				if (!is_numeric($curid))
				fatalError('Objectid must be an integer!');
				
			}
		}
		$db =& $this->_adodb;
		unset($this->elements);
		$this->elements = array();
		$this->elementscount = 0;
		if ($objectid != 0 && !is_array($objectid)) {
			$sql = "select * from object, $this->objecttable where site = '$this->site' and type = '$this->type' and object.objectid = $objectid and object.objectid = ".$this->objecttable.".objectid";
			if (!$this->filter_deleted) $sql .= ' and object.deleted = 0';
		} else {
			$sql = ($idxtype == INDEX_PARENT)
			     ? $this->_getSql(NULL, $objectid) 
				 : $this->_getSql($parentid, $objectid);
				 
		}
		if ($this->filter_limit === false) {
			$res = $db->_execute($sql);
		} else {
			$res = $db->selectLimit($sql, $this->filter_limit['count'], $this->filter_limit['start']);
		}

		$result = false;		
		// Check for valid query
		if ($res === false) {
			$this->errorhandler->seterror('error_basic_invalidsql',$sql. ' sql_error: ' . $this->_adodb->errorMsg());
			
			/**
			 * @todo: Should readerror be 1 or 3 here?
			 */
			$this->readerror = 3;
			return false;
		}
		
		if ($objectid != 0 && $res->numRows() == 0) {
			$this->readerror = 1; #trying to read nonexisting record
			$this->errorhandler->seterror('error_basic_recordnotfound','Trying to access objectid: '.$objectid);
			return false;
		}
		
		$candidates_for_variants = array();
		$elements =& $this->elements;

		if ($this->_forcevariant) {
			$language = $this->userhandler->getGuiListLanguage();
		} else {
			$language = $this->userhandler->getLanguage();
		}
		
		while (!$res->EOF) {
			$this->prv_options = $res->fields;
			$prv_options =& $this->prv_options;
			
			/**
			 * @todo Factor all this out into a separate method
			 */
			if ($this->prv_options['haspermission']) {
				$this->prv_options['access'] = $db->getall('select user_read, user_write, group_read, group_write from object_access where objectid = '.$this->prv_options['objectid']);
			}
			
			if ($this->hasaccess()) {
				
				if (!$this->webuser || ($this->webuser && $this->isapproved() && !$this->isexpired() && $prv_options['active']) || ($this->webuser && $this->prv_options['futurerevisionof'] != 0) ) {
					$result = true;
					$myobjectid = $this->prv_options['objectid'];
					$myparentid = $this->prv_options['parentid'];
					
					switch ($idxtype) {
						case INDEX_OBJECT:
							$index = $myobjectid;
							break;
						case INDEX_PARENT:
							$index = @sizeof($elements[$myparentid]);
							break;
						case INDEX_NORMAL:
							$index = @sizeof($elements);
							break;
					}
					
					$active = true;
					
					if ($idxtype == INDEX_PARENT) {
						$elements[$myparentid][$index] = $prv_options;
					} else {
						$elements[$index] = $prv_options;
					}
						
					$_prv = $this->prv_readobject();
					
					if (is_array($_prv)) {
						if ($idxtype == INDEX_PARENT) {
							$elements[$myparentid][$index] = array_merge($elements[$myparentid][$index], $_prv);
						} else {
							$elements[$index] = array_merge($elements[$index], $_prv);
						}
					}
					
					if ($this->filter_listvariants) {
						$sql = 'select distinct language from object where variantof = '.$myobjectid;
						$varr = $db->getCol($sql);
							if ($idxtype == INDEX_PARENT) {
								$elements[$myparentid][$index]['variants'] = $varr;
							} else {
								$elements[$index]['variants'] = $varr;
							}
					}
					
					/* read values for columns with multiple selections */
					if ($this->_hasmultiple) {
						foreach ($this->prv_column as $val) {
							if ($this->filter_nameonly && $val != 'name')
								continue;
								
							if (in_array($val['inputtype'],$this->_multitypes_old) || in_array($val['type'],$this->_multitypes)) {
								$sql = sprintf("select value from object_multiple where objectid = %s and field = '%s'",$myobjectid,$val['name']);
								
								if ($idxtype == INDEX_PARENT) {
									$elements[$myparentid][$index][$val['name']] = $db->getcol($sql);
								} else {
									$elements[$index][$val['name']] = $db->getcol($sql);
								}
							}
						}
					}
					
					if (!$this->filter_nameonly) {
	
						/* read extradata columns */
						if ($prv_options['hasextradata']) {
							$extradata = $this->_adodb->getall(sprintf("select field, value from object_extradata where objectid = %s",$myobjectid));
							if (is_array($extradata)) {
								$tarr = array();
								foreach ($extradata as $earr) {
									$tarr[$earr['field']] = $earr['value'];
								}
								/* merge extradata with current object */
								if ($idxtype == INDEX_PARENT) {
									$elements[$myparentid][$index] = array_merge($elements[$myparentid][$index], $tarr);
								} else {
									$elements[$index] = array_merge($elements[$index], $tarr);
								}
							}
						}
					}
//FIXME
					if ($prv_options['language'] != $language && ($this->_forcevariant || $this->webuser) && $prv_options['hasvariant']) {
						$candidates_for_variants[] = $myobjectid;
					}
					
					if ($active) {
						
						if ($idxtype == INDEX_PARENT) {
							$elements[$myparentid][$index]['object'] = $elements[$myparentid][$index] ;
						} else {
							$elements[$index]['object'] = $elements[$index];
						}
					}
					
					if ($this->_removeelement) {
						$this->_removeelement = false;
						if ($idxtype == INDEX_PARENT) {
							unset($this->elements[$myparentid][$index]);
						} else {
							unset($this->elements[$index]);
						}
					}
				}
			} else {
				if ($objectid != 0 && !is_array($objectid)) {
					#trying to read record without correct access
					#only when reading one single objectid
					$this->readerror = 2; 
					$this->errorhandler->seterror('error_basic_noaccess','Trying to access protected objectid: '.$objectid);
					return false;
				} 
			}
			if ($ADODB_EXTENSION) {
				adodb_movenext($res);
			} else {
				$res->moveNext();
			}
			
		}

		$this->resolveVariants($candidates_for_variants, $idxtype, $this->userhandler->getPrimaryLanguage());
		
		$this->resolveVariants($candidates_for_variants, $idxtype, $language);
		$this->elementscount = @sizeof($this->elements);
		
		return $result;
	}
	
}

?>
