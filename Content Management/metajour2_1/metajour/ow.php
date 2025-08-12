<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

require_once('basic_user.php');
$owcache = array();

function owcacheget($key) {
	global $owcache;
	return (isset($owcache[$key])) ? $owcache[$key] : false;
}

function owcacheput($key, $value) {
	global $owcache;
	$owcache[$key] = $value;
}

function packData($datatypecols, $data) {
	$arr = array();
	foreach ($datatypecols as $cur) {
		if (isset($data[$cur['name']])) {
			$arr[$cur['name']] = $data[$cur['name']];
		}
	}
	return $arr;
}


/**
 * Create new instance of datatype and read object
 * @return object
 * @param $objectid int objectid of object to read
 * @param $cache bool - if true store datatype information in cache
 */
function owRead($objectid, $cache = false) {
	global $system_path;
	$eh = &getErrorHandler();
	$uh =& getUserHandler();
	$app = $uh->getAppName();
	$cached = owcacheget('read' . $objectid);
	if ($cached !== false) {
		$row  = $cached;
	} else {
		$db =& getdbconn();
		$sql = "select class.datatype from object, class where class.datatype = object.type and object.objectid = '$objectid'";
		$row =& $db->getrow($sql);
		if ($cache) owcacheput('read' . $objectid, $row);
	}
	if (is_array($row) && !empty($row)) {
		$obj = owNew($row['datatype']);
		if ($obj) {
			if ($obj->readobject($objectid)) return $obj;
		}
		return false;
	} else {
		$eh->seterror('error_owread_notfound','Trying to access objectid: '.$objectid);
		fatalError();
	}	
}

function owReadExpand($objectid, $readtree = true) {
	$_relationtypes_old = array(UI_RELATION, UI_RELATION_NODEFAULT, UI_BINFILE, UI_BINFILE_THUMB, UI_LISTDIALOG, UI_LISTDIALOG_STRING, UI_USERGROUP, UI_USERSGROUPS);
	$_relationmultitypes_old = array(UI_RELATION_MULTIPLE, UI_USERSGROUPS_MULTIPLE);

	$errorhandler =& getErrorHandler();
	if ($objectid == 0) return '';
	$obj = owRead($objectid);
	if (!$obj) return array();
	$mycols = owDatatypeCols($obj->gettype());
	foreach ($mycols as $cur) {
		/*
		 * @todo: take care of UI_RELATION_MULTIPLE too
		 *
		 */
		if (in_array($cur['inputtype'],$_relationtypes_old) || $cur['type'] == F_REL) {
			// to avoid generating errormessages when trying to 
			// expand a non-existing or protected record
			$errorhandler->disable();
			$obj->elements[0][$cur['name']] = owReadExpand($obj->elements[0][$cur['name']], false);
			$errorhandler->enable();
		}
	}
	if ($obj->getParentId() != 0 && $readtree) 
		$obj->elements[0]['p'] = owReadExpand($obj->getParentId(),false);
	if ($obj->getType() == "case" && $obj->hasChild() && $readtree) {
		$c = array();

		$childs = owNew('caseaction');
		$childs->listobjects($obj->getObjectId());
		if (!empty($childs->elements)) {
		foreach ($childs->elements as $elem) {
			$c[] = owReadExpand($elem['objectid'], false);
		}
		}
		$childs = owNew('casenote');
		$childs->listobjects($obj->getObjectId());
		if (!empty($childs->elements)) {
		foreach ($childs->elements as $elem) {
			$c[] = owReadExpand($elem['objectid'], false);
			
		}
		}
		$obj->elements[0]['c'] = $c;
	}

	if ($obj->getType() != "case" && $obj->getSubType() && $obj->hasChild() && $readtree) {
		$c = array();

		$childs = owNew($obj->getSubType());
		if ($childs) {
			$childs->listobjects($obj->getObjectId());
			if (!empty($childs->elements)) {
				foreach ($childs->elements as $elem) {
					$c[] = owReadExpand($elem['objectid'], false);
				}
			}
			$obj->elements[0]['c'] = $c;
		}
	}

	return $obj->elements[0];
}

function owReadTextual($objectid, $col='') {
	$_relationtypes_old = array(UI_RELATION, UI_RELATION_NODEFAULT, UI_BINFILE, UI_BINFILE_THUMB, UI_LISTDIALOG, UI_LISTDIALOG_STRING, UI_USERGROUP, UI_USERSGROUPS);
	$_relationmultitypes_old = array(UI_RELATION_MULTIPLE, UI_USERSGROUPS_MULTIPLE);

	if ($objectid == 0) return array();
	$obj = owRead($objectid, false);
	if (!$obj) return array();
	$mycols = owDatatypeColsDesc($obj->gettype());
	if (!empty($col)) {
		$tmp = array();
		foreach ($mycols as $key=>$val) {
			if ($val['name'] == $col) {
				$tmp[] = $mycols[$key];
				break;
			}
		}
		$mycols = $tmp;
	}
	
	$arr = array();
	$i = 0;
	foreach ($mycols as $cur) {
		if (in_array($cur['inputtype'],$_relationtypes_old) || $cur['type'] == F_REL) {
			$arr[$i]['name'] = $cur['name'];
			if (owValid($obj->elements[0][$cur['name']])) {
				$arr[$i]['fieldrep'] = owReadName($obj->elements[0][$cur['name']]);
				$arr[$i]['fieldvalue'] = $obj->elements[0][$cur['name']];
			} else {
				$arr[$i]['fieldrep'] = '';
				$arr[$i]['fieldvalue'] = 0;
			}
		}
		elseif ($cur['inputtype'] == UI_COMBO || $cur['type'] == F_COMBO) {
			$arr[$i]['name'] = $cur['name'];
			$arr[$i]['fieldrep'] = $cur['comboarray'][$obj->elements[0][$cur['name']]];
		}
		elseif ($cur['inputtype'] == UI_COMBO_MULTIPLE || $cur['type'] == F_COMBO_MUL) {
			if (is_array($obj->elements[0][$cur['name']])) {
				foreach ($obj->elements[0][$cur['name']] as $c) {
					$arr[$i]['name'] = $cur['name'];
					$arr[$i]['fieldrep'] = $cur['comboarray'][$c];
					$i++;
				}
			}
		}
		elseif (in_array($cur['inputtype'],$_relationmultitypes_old) || $cur['type'] == F_REL_MUL) {
			if (is_array($obj->elements[0][$cur['name']])) {
				foreach ($obj->elements[0][$cur['name']] as $c) {
					$arr[$i]['name'] = $cur['name'];
					if (owValid($c)) {
						$arr[$i]['fieldrep'] = owReadName($c);
						$arr[$i]['fieldvalue'] = $c;
					} else {
						$arr[$i]['fieldrep'] = '';
						$arr[$i]['fieldvalue'] = 0;
					}
					$i++;
				}
			}
		}
		else {
			$arr[$i]['name'] = $cur['name'];
			$arr[$i]['fieldrep'] = $obj->elements[0][$cur['name']];
		}
		$i++;
	}

	return $arr;
}

function owReadName($objectid) {
	if ($objectid == 0) return '';
	$errorhandler =& getErrorHandler();
	$errorhandler->disable(); #oh, shut up
	$obj = owNew(owGetDatatype($objectid));
	$obj->setlistaccess(true);
	$obj->readobject($objectid);
	$errorhandler->enable();
	
	if ($obj) return $obj->elements[0]['name'];
	return '';
}

function owReadCol($objectid, $colname) {
	if ($objectid == 0) return '';
	$obj = owRead($objectid);
	if ($obj) return $obj->elements[0][$colname];
	return '';
}

/**
 * Returns the app of the datatype given
 * @return string name of app
 * @param $datatype string name of datatype to examine
 */
function owGetDatatypeApp($datatype) {
	$db =& getdbconn();
	return $db->getone("select app from class where datatype = '$datatype'");
}

/**
 * Returns the datatype of the objectid given
 * @return string name of datatype
 * @param $objectid int objectid of object to examine
 */
function owGetDatatype($objectid) {
	$db =& getdbconn();
	return $db->getone("select type from object where objectid = '$objectid'");
}

function owGetMasterColumnValues($otype, $column) {
	$uh =& getUserHandler();
	$site = $uh->getSite();	
	
	$db =& getdbconn();
	$query = "SELECT DISTINCT os.fieldrep 
		FROM object_search os INNER JOIN object o 
		ON os.objectid = o.objectid
		WHERE o.type=" . $db->qstr($otype) . "
		AND os.fieldname=" . $db->qstr($column) . "
		AND os.variantof = 0
		AND o.site = $site";
		
	$res = $db->getCol($query);
	return $res;
}

/**
 * Create new instance of datatype
 * @return object
 * @param $datatype string name of datatype
 */
function owNew($datatype) {
	global $system_path;
	$eh =& getErrorHandler();
	$uh =& getUserHandler();
	#$app = $uh->getAppName();
	$cached = owcacheget('create' . $datatype);
	if ($cached !== false) {
		$row = $cached;
	} else {
		$db =& getdbconn();
		$row =& $db->getrow("select type, basedatatype, app from class where datatype = '$datatype'");
		owcacheput('create' . $datatype, $row);
	}
	if (is_array($row) && !empty($row)) {
		$app = $row['app'];
		switch($row['type']) {
			case 0:
				if (!empty($app) && file_exists($system_path.'app/'.$app.'/core/'.$app.'_'.$datatype.'class.php')) {
					require_once($system_path.'app/'.$app.'/core/'.$app.'_'.$datatype.'class.php');
					$s = $app.'_'.$datatype;
					return new $s;
				} else {
					@include_once($system_path.'core/'.$datatype.'class.php');
					if (class_exists($datatype)) {
						return new $datatype;
					} else {
						$eh->seterror('error_ownew_classnotfound','Trying to create datatype: '.$datatype);
						fatalError();
					}
				}
				
				$eh->seterror('error_unexpected','Trying to create datatype: '.$datatype);
				return false;

			case 1:
				if ($row['basedatatype'] == '') $row['basedatatype'] = $datatype;
				@include_once($system_path.'extension/'.$row['basedatatype']."/".$datatype.".datatype.php");
				if (class_exists($datatype)) {
					return new $datatype;
				} else {
					$eh->seterror('error_ownew_classnotfound','Trying to create datatype: '.$datatype);
					fatalError();
				}
				$eh->seterror('error_unexpected','Trying to create datatype: '.$datatype);
				fatalError();
		}
	} else {
		$eh->seterror('error_ownew_classnotfound','Trying to create datatype: '.$datatype);
		fatalError();
	}
}

function owValid($objectid) {
	$db =& getdbconn();
	$sql = "select objectid from object where objectid = $objectid";
	return $db->getone($sql);
}

/**
 * Relaxed check of availability of datatype
 * @return bool true if datatype is valid (class-file exists)
 * @param $datatype string name of datatype
 */
function owTry($datatype) {
	global $system_path;
	$cached = owcacheget('create' . $datatype);
	if ($cached !== false) {
		$row = $cached;
	} else {
		$db =& getdbconn();
		$row =& $db->getrow("select type, basedatatype, app from class where datatype = '$datatype'");
		owcacheput('create' . $datatype, $row);
	}
	if (is_array($row) && !empty($row)) {
		$app = $row['app'];
		switch($row['type']) {
			case 0:
				if (!empty($app) && file_exists($system_path.'app/'.$app.'/core/'.$app.'_'.$datatype.'class.php')) {
					if (file_exists($system_path.'app/'.$app.'/core/'.$app.'_'.$datatype.'class.php')) 
						return true;
				} else {
					if (file_exists($system_path.'core/'.$datatype.'class.php')) 
						return true;
				}

			case 1:
				if ($row['basedatatype'] == '') 
					$row['basedatatype'] = $datatype;
				if (file_exists($system_path.'extension/'.$row['basedatatype']."/".$datatype.".datatype.php"))
					return true;
		}
	}
	return false;
}


	/**
	 * @return string name of primary datatype
	 * @param $datatype name of the datatype to examine
	 * This function will return the primary datatype of a datatype
	 * The use is ONLY intended for datatypes that are a part of an
	 * extension. For instance the forumdata datatype, which is a
	 * part of the forum extension, and has the forum datatype as it's
	 * primary datatype
	 */
function owGetBasedatatype($datatype) {
	$db =& getdbconn();
	$basedatatype = & $db->getone("select basedatatype from class where datatype = '$datatype'");
	if ($basedatatype == '') $basedatatype = $datatype;
	return $basedatatype;
}

	/**
	 * @return bool true if datatype is a functional extension (contains a .class.php file)
	 * @param $datatype name of the datatype (extension) to examine
   * @todo The file_exists check should be replaced by getting information from the class table
	 */
function owIsExtension($datatype) {
	global $system_path;
	return file_exists($system_path."extension/".owGetBasedatatype($datatype) . "/" . $datatype . ".class.php");
}

function owIsExtendedDatatype($datatype) {
	global $system_path;
	# returns true if $extension contains a datatype definition file
	# the file_exists check will later be replaced by information in the class table
	return file_exists($system_path."extension/".owGetBasedatatype($datatype) . "/" . $datatype . ".datatype.php");
}

function owListCore($all=false) {
	$uh =& getUserHandler();
	$site = $uh->getSite();
	$app = $uh->getAppName();
	$db =& getdbconn();
	if ($all) {
		$col = & $db->getcol("select datatype from class where type=0 and (site='0' OR site='$site') order by datatype");
	} else {
		$col = & $db->getcol("select datatype from class where type=0 and (app='' OR app='$app') and (site='0' OR site='$site') order by datatype");
	}
	return $col;
}

function owListExtensions($all=false) {
	$uh =& getUserHandler();
	$site = $uh->getSite();
	$app = $uh->getAppName();
	$db =& getdbconn();
	if ($all) {
		$col = & $db->getcol("select datatype from class where type=1 and (site='0' OR site='$site') order by datatype");
	} else {
		$col = & $db->getcol("select datatype from class where type=1 and (app='' OR app='$app') and (site='0' OR site='$site') and basedatatype = '' order by datatype");
	}
	return $col;
}

function owListExtensionDatatypes($datatype) {
	$uh =& getUserHandler();
	$site = $uh->getSite();
	$app = $uh->getAppName();
	$db =& getdbconn();
	$col = $db->getcol("select datatype from class where type=1 and (app='' OR app='$app') and (site='0' OR site='$site') and basedatatype = '$datatype' order by datatype");
	return $col;
}

function owListExtensionsDesc($all=false) {
	$arr = owListExtensions($all);
	$res = array();
	$uh =& getUserHandler();
	$language = strtolower($uh->getGuiLanguage());
	$deflang = 'en';
	foreach ($arr as $cur) {
		$namefile = $uh->getSystemPath().'extension/'.$cur.'/lang/info.'.$language.'.php';
		$namefiledeflang = $uh->getSystemPath().'extension/'.$cur.'/lang/info.'.$deflang.'.php';
		$LANG = array();
		if (file_exists($namefile)) {
			include($namefile);
		} elseif (file_exists($namefiledeflang)) {
			include($namefiledeflang);
		}
		$res[$cur]['name'] = $cur;
		$res[$cur]['description'] = $LANG['name'];
		if ($res[$cur]['description'] == '') $res[$cur]['description'] = $cur;

		require_once($uh->getSystemPath().'/extension/'.$cur.'/'.$cur.'.class.php');
		$extclass = "ext_".$cur;
		$extension = new $extclass();
		if ($extension->hasConfigSet()) $res[$cur]['hasconfigset'] = '1';
	}
	return $res;
}

function owListSystem() {
	$uh =& getUserHandler();
	$site = $uh->getSite();
	$app = $uh->getAppName();
	$db =& getdbconn();
	$col = & $db->getcol("select datatype from class where type=5 and (app='' OR app='$app') and (site='0' OR site='$site') order by datatype");
	return $col;
}

function owGetLanguages() {
	$db =& getdbconn();
	$col = & $db->getcol("select distinct language from object where site = '".$_SESSION['site']."' AND deleted = 0 AND language <> ''");
	return $col;	
}

function locateLangFiles($file) {
	$uh =& getUserHandler();
	$app = $uh->getAppName();
	$language = $uh->getGuiLanguage();
	$deflang = 'da';

	$arr = array();
	
	if ( file_exists(dirname(__FILE__).'/lang/'.$file.'.'.$language.'.php')) {
		$arr[] = 'lang/'.$file.'.'.$language.'.php';
	} elseif (file_exists(dirname(__FILE__).'/lang/'.$file.'.'.$deflang.'.php')) {
		$arr[] = 'lang/'.$file.'.'.$deflang.'.php';
	}
	if (!empty($app)) {
		if ( file_exists(dirname(__FILE__).'/app/'.$app.'/lang/'.$app.'_'.$file.'.'.$language.'.php')) {
			$arr[] = 'app/'.$app.'/lang/'.$app.'_'.$file.'.'.$language.'.php';
		} elseif (file_exists(dirname(__FILE__).'/app/'.$app.'/lang/'.$app.'_'.$file.'.'.$deflang.'.php')) {
			$arr[] = 'app/'.$app.'/lang/'.$app.'_'.$file.'.'.$deflang.'.php';
		}
	}
	if (owIsExtendedDatatype($file)) {
		$basedatatype = owGetBasedatatype($file);
		if ( file_exists(dirname(__FILE__).'/extension/'.$basedatatype.'/lang/'.$file.'.'.$language.'.php')) {
			$arr[] = 'extension/'.$basedatatype.'/lang/'.$file.'.'.$language.'.php';
		} elseif (file_exists(dirname(__FILE__).'/extension/'.$basedatatype.'/lang/'.$file.'.'.$deflang.'.php')) {
			$arr[] = 'extension/'.$basedatatype.'/lang/'.$file.'.'.$deflang.'.php';
		}
	}
	return $arr;
}

/**
 * @return array of user defined columns for $datatype
 * The array includes the descriptions of the fields (label)
 **/
function owDatatypeExtraCols($datatype) {
	$arr = array();
	$edobj = owNew('extradata');
	
	if (is_numeric($datatype)) {
		$datatype = owGetDatatype($datatype);
	}
	
	$id = $edobj->locateByName($datatype);
	
	if ($id) {
		$edobj->readObject($id);
		$i = 0;
		if (is_array($edobj->elements[0]['fieldname'])) {
			foreach ($edobj->elements[0]['fieldname'] as $val) {
				$arr[$val]['name'] = $val;
				$arr[$val]['inputtype'] = $edobj->elements[0]['fieldtype'][$i];
				$arr[$val]['relation'] = $edobj->elements[0]['fieldrelation'][$i];
				$arr[$val]['label'] = $edobj->elements[0]['fielddescription'][$i];
				$i++;
			}
		}
	}
	return $arr;
}

/**
 * @return array of private columns for $datatype
 * The array does not include the descriptions of the fields
 **/
function owDatatypePrivCols($datatype) {
	$eh =& getErrorHandler();
	$eh->disable();
	if (is_numeric($datatype)) { // $datatype is an objectid
		$obj = owRead($datatype);
	} else {
		$obj = owNew($datatype);
	}
	$eh->enable();
	$arr = array();
	if ($obj) {
		$obj->initLayout();
		$tarr = $obj->getColumns();
		foreach ($tarr as $cur) {
			$arr[$cur['name']] = $cur;
		}
	}
	return $arr;
}

/**
 * @return array of all columns for $datatype
 * The array does not include the descriptions of the fields (except for the user defined columns)
 **/
function owDatatypeCols($datatype) {
	global $OWLANG;
	if (isset($OWLANG[$datatype])) return $OWLANG[$datatype];
	return array_merge(owDatatypePrivCols($datatype),owDatatypeExtraCols($datatype));
}

function owLabel($datatype,$colname) {
	global $OWLANG;
	if (!isset($OWLANG[$datatype])) owDatatypeColsDesc($datatype);

	return $OWLANG[$datatype][$colname]['label'];
}

/* $datatype can be numeric in which case it is an objectid */
function owDatatypeColsDesc($datatype) {
	global $OWLANG;
	$uh =& getUserHandler();
	$app = $uh->getAppName();
	
	if (isset($OWLANG[$datatype])) {
		return $OWLANG[$datatype];
	} else {
		
		$arr = owDatatypePrivCols($datatype);
		
		if (is_numeric($datatype)) {
			$langfiles = locateLangFiles(owGetDatatype($datatype));
		} else {
			$langfiles = locateLangFiles($datatype);
		}
		
		$LANG = array();
		foreach ($langfiles as $langfile) {
			include($langfile);
		}

		foreach ($arr as $cur) {
			if ($cur['inputtype'] == UI_COMBO || $cur['inputtype'] == UI_COMBO_MULTIPLE || $cur['type'] == F_COMBO || $cur['type'] == F_COMBO_MUL) {
				$arr[$cur['name']]['comboarray'] = $LANG['option_'.$cur['name']];
			}
			if (isset($LANG['label_'.$cur['name']])) {
				$arr[$cur['name']]['label'] = $LANG['label_'.$cur['name']];
			} else {
				/*
				AUTOMATIC LANG WRITING DISABLED IN DIST-version
				*/
				/*if ($app != "") {
					$f = @fopen('app/'.$app.'/lang/'.$app.'_'.$datatype.'.da.php','a');
				} else {
					$f = @fopen('lang/'.$datatype.'.da.php','a');
				}
				if ($f !== false) {
					fwrite($f,"<?php\n\$LANG['label_".$cur['name']."'] = '".$cur['name']."';\n?>");
					fclose($f);
				}*/
				$arr[$cur['name']]['label'] = $cur['name'];
			}
		}
		
		$result = array_merge($arr,owDatatypeExtraCols($datatype));
		$OWLANG[$datatype] = $result;
		return $result;
	}
}

/**
 * @return string descriptive name of datatype
 **/	
function owDatatypeDesc($datatype) {
	/**
	 * @todo handle extension language files and apps
	 */
	$uh =& getUserHandler();
	$app = $uh->getAppName();
	
	$language = strtolower($uh->getGuiLanguage());
	$deflang = 'en';
	$db =& getdbconn();
	$row = $db->getrow("select name from classname where datatype = '$datatype' AND language = '$language'");
	if (count($row)) {
		return $row['name'];
	}
	
	if (!(owIsExtendedDatatype($datatype) || owIsExtension($datatype))) {
		if ( file_exists(dirname(__FILE__).'/lang/'.$datatype.'.'.$language.'.php')) {
			include('lang/'.$datatype.'.'.$language.'.php');
			if (isset($LANG['name'])) {
				$result = $LANG['name'];
				$db->execute("insert into classname values ('$datatype','$language','$result')");
				return $result;
			}
		}
		if (!empty($app)) {
			if ( file_exists(dirname(__FILE__).'/app/'.$app.'/lang/'.$app.'_'.$datatype.'.'.$language.'.php')) {
				include('app/'.$app.'/lang/'.$app.'_'.$datatype.'.'.$language.'.php');
				if (isset($LANG['name'])) {
					$result = $LANG['name'];
					$db->execute("insert into classname values ('$datatype','$language','$result')");
					return $result;
				}
			}
		}
	} else {
		if ( file_exists(dirname(__FILE__).'/extension/'.owGetBasedatatype($datatype).'/lang/'.$datatype.'.'.$language.'.php')) {
			include('extension/'.owGetBasedatatype($datatype).'/lang/'.$datatype.'.'.$language.'.php');
			if (isset($LANG['name'])) {
				$result = $LANG['name'];
				$db->execute("insert into classname values ('$datatype','$language','$result')");
				return $result;
			}
		}
	}

	$row = $db->getrow("select name from classname where datatype = '$datatype' AND language = '$deflang'");
	if (count($row)) {
		return $row['name'];
	}
	if (!owIsExtendedDatatype($datatype)) {
		if ( file_exists(dirname(__FILE__).'/lang/'.$datatype.'.'.$deflang.'.php')) {
			include('lang/'.$datatype.'.'.$deflang.'.php');
			if (isset($LANG['name'])) {
				$result = $LANG['name'];
				$db->execute("insert into classname values ('$datatype','$deflang','$result')");
				return $result;
			}
		}
		if (!empty($app)) {
			if ( file_exists(dirname(__FILE__).'/app/'.$app.'/lang/'.$app.'_'.$datatype.'.'.$deflang.'.php')) {
				include('app/'.$app.'/lang/'.$app.'_'.$datatype.'.'.$deflang.'.php');
				if (isset($LANG['name'])) {
					$result = $LANG['name'];
					$db->execute("insert into classname values ('$datatype','$deflang','$result')");
					return $result;
				}
			}
		}
	} else {
		if ( file_exists(dirname(__FILE__).'/extension/'.owGetBasedatatype($datatype).'/lang/'.$datatype.'.'.$deflang.'.php')) {
			include('extension/'.owGetBasedatatype($datatype).'/lang/'.$datatype.'.'.$deflang.'.php');
			if (isset($LANG['name'])) {
				$result = $LANG['name'];
				$db->execute("insert into classname values ('$datatype','$deflang','$result')");
				return $result;
			}
		}
	}
	return $datatype;
}

function owGetAppDescription($app) {
	if (empty($app)) return '';
	$uh =& getUserHandler();
	$namefile = $uh->getSystemPath().'app/'.$app.'/lang/appname.da.php';
	if (file_exists($namefile)) {
		include($namefile);
		return $LANG['appname'];
	} else {
		return $app;
	}
}

function owGetApps() {
	$uh =& getUserHandler();
	$path = $uh->getSystemPath().'app/*';
	$matches = glob($path);
	$arr = array();
	$arr[0]['app'] = 'metajour';
	$arr[0]['name'] = 'IPW METAjour';
	$s = array();
	$s[] = $arr[0]['name'];
	foreach ($matches as $match) {
		$base = basename($match);
		if ($base != 'CVS') {
			$cnt = sizeof($arr);
			$arr[$cnt]['app'] = $base;
			$arr[$cnt]['name'] = $base;
			$namefile = $uh->getSystemPath().'app/'.$base.'/lang/appname.da.php';
			if (file_exists($namefile)) {
				include($namefile);
				$arr[$cnt]['name'] = $LANG['appname'];
			}
			$s[] = $arr[$cnt]['name'];
		}
	}
	asort($s);
	$newarr = array();
	foreach ($s as $key => $val) {
		$newarr[] = $arr[$key];
	}
	return $newarr;
}


function owReIndexAll() {
	echo "<h1>Indexing objects</h1>";
	set_time_limit(60000);
	$errorhandler =& getErrorHandler();
	$uh =& getUserHandler();
	$eh =& getErrorHandler();
	$site = $uh->getSite();
	$db =& getdbconn();
	$oids =& $db->getcol("select objectid from object where site='$site' and deleted = 0 and syshidden = 0 order by type");
	$i = 0;
	foreach ($oids as $objectid) {
		$eh->disable();
		$obj = owRead($objectid, false);
		$eh->enable();
		if ($obj) {
			$obj->createSearchIndex();
		}
		$i++;
	}
	echo "Done! Indexed $i objects<BR>";
}

function languageName($langcode) {
	$db =& getdbconn();
	return $db->getone("select language from system_languages where langcode = '$langcode'");
}

function viewDescription($view) {
	$uh =& getUserHandler();
	if ($uh->getGuiLanguage() == 'da') {
		switch($view) {
			case "list": return "Liste/Menu"; break;
			case "tree": return "Hierarkisk oversigt"; break;
			case "menu": return "Adgang fra hovedmenu"; break;
			case "edit": return "Redigering"; break;
			case "view": return "Visning"; break;
			case "create": return "Oprette ny"; break;
			case "move": return "Flyt"; break;
			case "delete": return "Slet"; break;
			case "properties": return "Vis egenskaber"; break;
			case "created": return "Ændre Oprettet tid"; break;
			case "changed": return "Ændre Redigeret tid"; break;
			case "checked": return "Ændre Kontrolleret tid"; break;
			case "createdby": return "Ændre Oprettet af"; break;
			case "changedby": return "Ændre Redigeret af"; break;
			case "checkedby": return "Ændre Kontrolleret af"; break;
			case "language": return "Ændre sprog"; break;
			case "publish": return "Ændre publiceringstid"; break;
			case "access": return "Ændre rettigheder"; break;
			case "active": return "Aktivering/deaktivering"; break;
			case "approved": return "Godkendelse"; break;
			case "category": return "Tilknytte kategori"; break;
			case "readonly": return "Sætte til readonly"; break;
			case "default": return "Sætte til standard"; break;
			case "createcopy": return "Oprette kopi"; break;
			case "createvariant": return "Oprette variant"; break;
			case "createfuture": return "REV: Oprette kommende version"; break;
			case "requestapproval": return "REV: Anmod om godkendelse"; break;
			case "approvepublish": return "REV: Godkend og udgiv"; break;
			case "preview": return "Preview"; break;
			case "search": return "Statistik"; break;
			default: return $view;
		}
	} else {
		switch($view) {
			case "list": return "List/Menu"; break;
			case "tree": return "Tree"; break;
			case "menu": return "Access from menu"; break;
			case "edit": return "Editing"; break;
			case "view": return "Show"; break;
			case "create": return "Create"; break;
			case "move": return "Move"; break;
			case "delete": return "Delete"; break;
			case "properties": return "View properties"; break;
			case "created": return "Change Created time"; break;
			case "changed": return "Change Edited time"; break;
			case "checked": return "Change Checked time"; break;
			case "createdby": return "Change Created by"; break;
			case "changedby": return "Change Edited by"; break;
			case "checkedby": return "Change Checked by"; break;
			case "language": return "Change language"; break;
			case "publish": return "Change publication"; break;
			case "access": return "Change access"; break;
			case "active": return "Activate/deactivate"; break;
			case "approved": return "Approve"; break;
			case "category": return "Assign category"; break;
			case "readonly": return "Set as readonly"; break;
			case "default": return "Set as default"; break;
			case "createcopy": return "Create copy"; break;
			case "createvariant": return "Create variant"; break;
			case "createfuture": return "REV: Create revision"; break;
			case "requestapproval": return "REV: Request approval"; break;
			case "approvepublish": return "REV: Approve and publish"; break;
			case "preview": return "Preview"; break;
			case "search": return "Statistics"; break;
			default: return $view;
		}
	}
}

function owImportObj($name, $path, &$convid) {
	$_objchilds = array();
	include($path.$name.".obj.php");
	$name = "objfile_".$_objoldid;
	$resultobj = $name();
	if (!empty($_objchilds) && is_array($_objchilds)) {
		foreach($_objchilds as $cur) {
			$obj = owImportObj($cur, $path, $convid);
			$obj->setParentId($resultobj->getObjectId());
		}
	}
	$convid[$_objoldid] = $resultobj->getObjectId();
	return $resultobj;
}

function owExportObj($objectid, &$zip, $ischild = false) {
	$obj = owRead($objectid);
	$childstr = '';
	if ($obj->hasChild()) {
		$childs = $obj->getChilds();
		foreach ($childs as $child) {
			$n = owExportObj($child,$zip,true);
			$childstr .= "\$_objchilds[] = '$n';\n";
		}
	}
	$name = strtolower(preg_replace('/([^a-zA-Z0-9])/', '', $obj->elements[0]['name']));
	if (empty($name)) $name = "objfile";
	if ($ischild) $name = "_child_".$name."_".$objectid;
	$type = $obj->getType();
	$str .= "<?php\n";
	$str .= "#IPW METAZO 2.0 OBJECT\n";
	$str .= "\$_objoldid = $objectid;\n";
	if ($childstr) $str .= $childstr;
	$str .= "function objfile_$objectid () {\n";
	$str .= "\$obj = owNew('$type');\n";
	$cols = owDatatypeColsDesc($type);
	foreach ($cols as $cur) {
		if (is_array($obj->elements[0][$cur['name']])) {
			$str .= "\$objdata['".$cur['name']."'] = unserialize(\"".serialize($obj->elements[0][$cur['name']]).");\n";
		} else {
			$str .= "\$objdata['".$cur['name']."'] = \"".str_replace("\'","'",str_replace('$','\$',addslashes($obj->elements[0][$cur['name']])))."\";\n";
		}
	}

	$str .= "\$obj->createObject(\$objdata);\n";
	if ($ischild) {
		$childorder = $obj->elements[0]['object']['childorder'];
		$str .= "\$obj->moveToAbsolute($childorder);\n";
	}
	if ($obj->elements[0]['object']['standard']) {
		$str .= "\$obj->setDefault();\n";
	}
	$str .= "return \$obj;\n";
	$str .= "}\n";
	$str .= "?>\n";

	$zip->addFile($str,$name.'.obj.php');
}

function owImportObjects($path) {
	$errorhandler =& getErrorHandler();
	$errorhandler->disable(); #oh, shut up
	$cid = array();
	$matches = glob($path.'*.obj.php');
	foreach ($matches as $match) {
		$base = basename($match, '.obj.php');
		if (substr($base,0,7) != '_child_') {
			owImportObj($base,$path,$cid);
		}
	}
	foreach ($cid as $key => $val) {
		$obj = owRead($val);
		if ($obj) {
			$cols = owDatatypeColsDesc($obj->getType());
			foreach ($cols as $cur) {
				if ($cur['inputtype'] == UI_RELATION || $cur['inputtype'] == UI_LISTDIALOG || $cur['inputtype'] == UI_RELATION_NODEFAULT || $cur['inputtype'] == UI_BINFILE || $cur['inputtype'] == UI_BINFILE_THUMB  || $cur['type'] == F_REL) {
					if (isset($cid[$obj->elements[0][$cur['name']]])) {
						$obj->elements[0][$cur['name']] = $cid[$obj->elements[0][$cur['name']]];
					} else {
						$obj->elements[0][$cur['name']] = 0;
					}
				}
			}
			$obj->updateObject($obj->elements[0]);
		}
	}
	$errorhandler->enable();
}

function scanForApps() {
	$reserveddirs = array('CVS');
	$uh =& getUserHandler();
	$apps = glob($uh->getSystemPath().'app/*',GLOB_ONLYDIR);
	$res = array();
	if (is_array($apps)) {
		foreach ($apps as $a) {
			$appname = basename($a);
			if (!in_array($appname,$reserveddirs)) {
				$res[] = $appname;
			}
		}
	}
	return $res;
}

function scanForExtensionDirs() {
	$reserveddirs = array('CVS');
	$uh =& getUserHandler();
	$exts = glob($uh->getSystemPath().'extension/*',GLOB_ONLYDIR);
	$res = array();
	if (is_array($exts)) {
		foreach ($exts as $a) {
			$extname = basename($a);
			if (!in_array($extname,$reserveddirs)) {
				$res[] = $extname;
			}
		}
	}
	return $res;
}

function scanForCoreDatatypes() {
	$reservedclasses = array('basic','absfile','system');
	$uh =& getUserHandler();
	$files = glob($uh->getSystemPath().'core/*class.php');
	$res = array();
	if (is_array($files)) {
		foreach ($files as $f) {
			$classname = basename($f,'class.php');
			if (!in_array($classname,$reservedclasses)) {
				$arr = array();
				$arr['path'] = $f;
				$arr['classname'] = $classname;
				$arr['app'] = '';
				$res[] = $arr;
			}
		}
	}
	return $res;
}

function scanForExtensionDatatypes($extension) {
	$uh =& getUserHandler();
	$files = glob($uh->getSystemPath()."extension/$extension/*.datatype.php");
	$res = array();
	if (is_array($files)) {
		foreach ($files as $f) {
			$classname = basename($f,'.datatype.php');
			$arr = array();
			$arr['path'] = $f;
			$arr['classname'] = $classname;
			$arr['basedatatype'] = '';
			if ($classname != $extension) $arr['basedatatype'] = $extension;
			$res[] = $arr;
		}
	}
	return $res;
}


function scanForExtensions() {
	$db =& getDbConn();
	$uh =& getUserHandler();
	$res = scanForExtensionDirs();
	foreach ($res as $r) {
		$ext = scanForExtensionDatatypes($r);
		if (is_array($ext)) {
			foreach ($ext as $e) {
				require_once($e['path']);
				$tmp = new $e['classname'];
				$tmp->tableUpdate();
				$db->execute("REPLACE INTO `class` VALUES (0, '', 1, '".$tmp->getObjectType()."', '".$e['basedatatype']."')");
			}
		}
		if (file_exists($uh->getSystemPath()."extension/".$r."/".$r.".class.php")) {
			require_once($uh->getSystemPath()."extension/".$r."/".$r.".class.php");
			$n = 'ext_'.$r;
			$tmp = new $n;
			$tmp->installExtension();
			$db->execute("REPLACE INTO `class` VALUES (0, '', 1, '".$r."', '')");
		}
	}
}


function scanForAppDatatypes($app) {
	$uh =& getUserHandler();
	$files = glob($uh->getSystemPath()."app/$app/core/*class.php");
	$res = array();
	if (is_array($files)) {
		foreach ($files as $f) {
			$classname = basename($f,'class.php');
			$arr = array();
			$arr['path'] = $f;
			$arr['classname'] = $classname;
			$arr['app'] = $app;
			$res[] = $arr;
		}
	}
	return $res;
}

function scanForDatatypes() {
	$res = scanForCoreDatatypes();
	$apps = scanForApps();
	if (is_array($apps)) {
		foreach ($apps as $app) {
			$res = array_merge($res,scanForAppDatatypes($app));
		}
	}
	return $res;
}

function tableExists($tablename) {
	$db =& getDbConn();
	$tab = $db->getone("show tables like '$tablename'");
	return ($tab) ? true : false;
}

function colExists($tablename, $columnname) {
	if (tableExists($tablename)) {
		$db =& getDbConn();
		$col = $db->getone("describe $tablename $columnname");
		return ($col) ? true : false;
	} else {
		return false;
	}
}

function indexExists($tablename, $indexname) {
	if (tableExists($tablename)) {
		$result = false;
		$db =& getdbconn();
		$res =& $db->query("SHOW INDEX FROM $tablename");
		while ($row = $res->fetchRow()) {
			if ($row['Key_name'] == $indexname) {
				$result = true;
				break;
			}
		}
		return $result;
	} else {
		return false;
	}
}
function getDbColType($tablename, $columnname) {
	$db =& getDbConn();
	$row = $db->getrow("describe $tablename $columnname");
	return $row['Type'];
}

function getDbVersion() {
	$db =& getDbConn();
	return $db->getone('select dbversion from dbversion');
}

function checkDatabase() {
	$db =& getDbConn();
	// in distribution version change to:
	// if (getDbVersion() < latest_dbversion_number) {
	if (getDbVersion() < 210) {
		$res = scanForDatatypes();
		foreach ($res as $r) {
			require_once($r['path']);
			$tmp = new $r['classname'];
			$tmp->tableUpdate();
			$db->execute("REPLACE INTO `class` VALUES (0, '".$r['app']."', 0, '".$tmp->getObjectType()."', '')");
		}
		$db->execute("REPLACE INTO `class` VALUES (0, '', 5, 'sys', '');");
		if (!colExists('extradata_data','relation')) {
			$db->execute("ALTER TABLE `extradata_data` ADD `relation` VARCHAR( 255 ) NOT NULL;");
		}
		if (!colExists('user','locale')) {
			$db->execute("ALTER TABLE `user` ADD `locale` VARCHAR( 5 ) NOT NULL;");
			$db->execute("ALTER TABLE `user` ADD `guilistlanguage` VARCHAR( 5 ) NOT NULL;");
		}
		if (!indexExists('structureelement', 'pageid')) {
			$db->execute("ALTER TABLE `structureelement` ADD INDEX pageid (pageid)");
		}

		if (!tableExists('document_count')) {
			$db->execute("
				CREATE TABLE `document_count` (
				  `objectid` int(11) NOT NULL default '0',
				  `numviews` int(11) NOT NULL default '0',
				  `lasttableupdate` datetime NOT NULL default '0000-00-00 00:00:00',
				  PRIMARY KEY  (`objectid`)
				);
			");
		}
		if (!tableExists('binfile_statistics')) {
			$db->execute("
				CREATE TABLE `binfile_statistics` (
				  `sessionid` varchar(32) NOT NULL default '',
				  `userid` int(11) NOT NULL default '0',
				  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
				  `site` int(11) NOT NULL default '0',
				  `pageid` int(11) NOT NULL default '0',
				  `ip` varchar(15) NOT NULL default '',
				  `useragent` varchar(60) NOT NULL default '',
				  `browser` varchar(15) NOT NULL default '',
				  `version` varchar(5) NOT NULL default '',
				  `maj_ver` varchar(5) NOT NULL default '',
				  `min_ver` varchar(5) NOT NULL default '',
				  `letter_ver` varchar(5) NOT NULL default '',
				  `javascript` varchar(5) NOT NULL default '',
				  `platform` varchar(15) NOT NULL default '',
				  `os` varchar(15) NOT NULL default '',
				  `browserlanguage` varchar(7) NOT NULL default '',
				  `userlanguage` varchar(5) NOT NULL default '',
				  `usercreated` tinyint(4) NOT NULL default '0',
				  `referer` varchar(255) NOT NULL default '',
				  `generated` double NOT NULL default '0',
				  `host` varchar(255) NOT NULL default '',
				  `tld` varchar(10) NOT NULL default '',
				  KEY `timestamp` (`timestamp`),
				  KEY `sessionid` (`sessionid`),
				  KEY `userid` (`userid`),
				  KEY `site` (`site`),
				  KEY `pageid` (`pageid`),
				  KEY `ip` (`ip`),
				  KEY `host` (`host`),
				  KEY `tld` (`tld`)
				);
			");
		}
		scanForExtensions();
		// Check for user created usergroups with level = 0 
		// Only anonymous should have level = 0
		$db->execute("UPDATE usergroup SET level=-1 WHERE level=0 AND name != 'ANONYMOUS'");
		// in distribution version add:
		$db->execute("update dbversion set dbversion = 210");
	}
}

?>
