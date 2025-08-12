<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

function insert_content_markup($tpl_source, &$smarty) {
	if (strpos($tpl_source,'<!--METAZO_CONTENT_BEGIN-->') === false) {
		return str_replace('{$document.section[0].content}','<!--METAZO_CONTENT_BEGIN-->{$document.section[0].content}<!--METAZO_CONTENT_END-->',$tpl_source);
	} else {
		return $tpl_source;
	}
}

##################################################################################
#  FORMATTING THE METADATA HEADER
##################################################################################
function getmetadata($objectid, &$document) {
	global $viewer_url;
	$obj = owNew('metadata');
	if ($objectid == 0) {
		$objectid = $obj->locatedefault();
	}
	unset($document['metadata']);
	if ($objectid) {
	if ($obj->readobject($objectid)) {
		$str = '<META NAME="Title" CONTENT="'.$document['name'].'">
<META NAME="Description" CONTENT="'.$obj->elements[0]['description'].'">
<META NAME="Keywords" CONTENT="'.$obj->elements[0]['keyword'].'">
<META NAME="Robots" CONTENT="ALL">
<META NAME="Revisit-after" CONTENT="7">
<META NAME="Author" CONTENT="'.$obj->elements[0]['publisher'].'">
<META NAME="Copyright" CONTENT="'.$obj->elements[0]['copyright'].'">
<META HTTP-EQUIV="Content-language" CONTENT="dan">
<META NAME="Rating" CONTENT="General">
<META NAME="ObjectType" CONTENT="Text">
<META HTTP-EQUIV="Content-Script-Type" CONTENT="text/javascript">
<META NAME="DC.Title" CONTENT="'.$document['name'].'">
<META NAME="DC.Description" CONTENT="'.$obj->elements[0]['description'].'">
<META NAME="DC.Subject" CONTENT="'.$obj->elements[0]['keyword'].'">
<META NAME="DC.Creator" CONTENT="'.$obj->elements[0]['publisher'].'">
<META NAME="DC.Rights" CONTENT="'.$obj->elements[0]['copyright'].'">
<META NAME="DC.Identifier" CONTENT="'.$viewer_url.'">
<META NAME="DC.Publisher" CONTENT="'.$obj->elements[0]['publisher'].'">
<META NAME="DC.Date" SCHEME="ISO8601" CONTENT="'.$document['object']['edited'].'">
<META NAME="DC.Language" scheme="NISOZ39.50" CONTENT="dan">
<META NAME="DC.Type" CONTENT="Text">
<META NAME="DC.Format" SCHEME="MIME" CONTENT="text/html">
<LINK REL="schema.dc" HREF="http://purl.org/metadata/dublin_core_elements">';
		$document['metadata']['content'] = $str;
		$document['metadata']['object'] = $obj->getobjectarray();
	}
	}
}

##################################################################################
#  PARSE A DOCUMENT SECTION
##################################################################################
function parsedocumentsection($id, $documentsectioncontent) {
	global $temp_documentsectioncontent, $document;
	$temp_documentsectioncontent = $documentsectioncontent;

	function smarty_resource_self_source($tpl_name, &$tpl_source, &$smarty) {
		global $temp_documentsectioncontent;
		$tpl_source = $temp_documentsectioncontent;
		return true;
	}

	function smarty_resource_self_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {
		$tpl_timestamp = strtotime('now');
		return true;
	}

	function smarty_resource_self_secure($tpl_name, &$smarty) {
		return true;
	}

	function smarty_resource_self_trusted($tpl_name, &$smarty) {
	}

	$userhandler =& getUserHandler();
	$smarty = $userhandler->getSmarty();
	$smarty->register_resource('self', array('smarty_resource_self_source','smarty_resource_self_timestamp','smarty_resource_self_secure','smarty_resource_self_trusted'));
	$smarty->assign_by_ref('document',$document);
	return $smarty->fetch('self:me');
}

##################################################################################
#  GETDOCUMENT
##################################################################################
function getdocument($id, &$document) {
	global $system_path, $login_result;
	$pageobj = owNew('document');
	$eh =& geterrorhandler();
	$eh->disable();
	$pageobj->readObject($id);
	$eh->enable();
	if ($pageobj->getReadError() == 1) output_http404();
	if (is_null($document)) {
		$document = $pageobj->elements[0];
		$document['pageid'] = $id;
	} else {
		$newdocument = $pageobj->elements[0];
		array_merge($document, $newdocument);
	}
	// two different notations for compatibility
	$document['login']['result'] = $login_result;
	$document['loginresult'] = $login_result;
	
	$userhandler =& getUserHandler();
		
	if ($pageobj->getReadError() > 0) return $pageobj->getReadError();
	
	$userhandler->setTempAppName($pageobj->elements[0]['useapp']);
	
	if ($pageobj->elements[0]['structureid'] != 0) {
		/**
		 * @todo need to check this
		 */
		require_once($userhandler->getSystemPath().'/extension/menu/menu.class.php');
		$extension = new ext_menu;
		$extension->ext_menu();
		$extension->getstructure($pageobj->elements[0]['structureid'], 1, "", 0, 0, array());
		$document['structure'] = $extension->extresult;
	}

	$cnt = 0;
	$obj = owNew('documentsection');
	$obj->listobjects($id);
	while ($cnt < $obj->elementscount) {
		$document['section'][$cnt] = $obj->elements[$cnt];

		//parse section-content if document-scripting is activated
		if (1 == $obj->elements[$cnt]['script']) {
			$document['section'][$cnt]['content'] = parsedocumentsection($document['section'][$cnt]['object']['objectid'], $document['section'][$cnt]['content']);
		}

		//execute any extensions assigned to the section
		if ($obj->elements[$cnt]['extension'] != '') {

			$type = $obj->elements[$cnt]['extension'];
			$configset = trim($obj->elements[$cnt]['configset']);
			// set name of configset/instance when none is given.
			// Should never occur, as the field is set by 
			// documentsectionclass->update()
			if (empty($configset)) $configset = 'cfg'.$obj->elements[$cnt]['objectid'];

			require_once($userhandler->getSystemPath().'/extension/'.$type.'/'.$type.'.class.php');
			$extclass = "ext_".$type;
			$extension = new $extclass();
			$extension->setdocument($document);
			$extension->execute($configset);
			$arr['config'] = $extension->extconfig;
			$arr['output'] = $extension->extoutput;
			$arr['result'] = $extension->extresult;
			$document['section'][$cnt]['ext'][$type][$name] = $arr;
			$document['section'][$cnt]['contentonly'] = $document['section'][$cnt]['content'];
			$document['section'][$cnt]['content'] = $document['section'][$cnt]['content'] . $arr['output'];
			unset($arr);
			unset($extension);
		}

		$cnt++;
	}

	return $pageobj->getReadError();
}


	function installDefaultTemplate() {
		$obj = owNew('template');
		$id = $obj->locateDefault();
		// just return id, if a default template exists
		if ($id) return $id;
		
		$uh =& getUserHandler();
		$userid = $uh->getSystemAccountId();
		$path = $uh->getSystemPath().'standard/primary/';
		$cid = array();

		$obj = owImportObj('system_default_menu',$path,$cid);
		$obj->setCreatedBy($userid);

		$obj = owImportObj('system_default_template',$path,$cid);
		$obj->setDefault(true);
		$obj->setCreatedBy($userid);
		return $obj->getObjectId();
	}

	function installPasswordTemplate() {
		installDefaultTemplate();
		$uh =& getUserHandler();
		$userid = $uh->getSystemAccountId();
		$path = $uh->getSystemPath().'standard/primary/';
		$cid = array();
		$obj = owImportObj('standard_password',$path,$cid);
		$obj->setCreatedBy($userid);
		return $obj->getObjectId();
	}

#########################################################################################
#  SHOWPAGE
#########################################################################################
function showpage($id, $login_result) {
	global $system_path, $document, $contentblock;
	$contentblock = '';

	$readerror = getdocument($id, $document);
	$userhandler =& getUserHandler();
	$smarty = $userhandler->getSmarty();
	$smarty->default_resource_type = 'template';

	$templateobj = owNew('template');
	if (!$document['templateid']) $document['templateid'] = $templateobj->locatedefault();
	if (!$document['templateid']) $document['templateid'] = installDefaultTemplate();
	if (!$document['templateid']) output_http500('Unable to locate default template');

	if ($readerror == 2 || isset($_REQUEST['forcelogin'])) {
		$tempobj = owNew('document');
		$userhandler->setunlimitedAccess(true);
		$tempobj->readObject($id);
		$userhandler->setunlimitedAccess(false);
		$userhandler->setTempAppName($tempobj->elements[0]['useapp']);

		// try to find installed standard_password template
		if ($userhandler->getAppName() != '') {
			$document['templateid'] = $templateobj->locateByName($userhandler->getAppName().'_standard_password');
			if (!$document['templateid']) {
				$document['templateid'] = $templateobj->locateByName('standard_password');
			}
		} else {
			$document['templateid'] = $templateobj->locateByName('standard_password');
		}

		// if no standard_password template found, install one
		if (!$document['templateid']) $document['templateid'] = installPasswordTemplate();
		
		// no template found or installed - die
		// should never occur
		if (!$document['templateid']) output_http404('Access denied');
		/**
		 * @todo Make some http auth as fall back authentication
		 */
	}

	$templateobj->readobject($document['templateid']);
	$document['template'] = $templateobj->elements[0];
	$userhandler->addHeaderCache($document['templateid'],$templateobj->elements[0]['header']);
	if ($templateobj->elements[0]['style']) $userhandler->addStyleCache($document['templateid'],$templateobj->elements[0]['style']);

	$styleobj = owNew('stylesheet');
	if ($document['stylesheetid'] == 0) $document['stylesheetid'] = $styleobj->locatedefault();
	if ($document['stylesheetid']) {
		$userhandler->addHeaderCache($document['stylesheetid'], '<link rel="stylesheet" type="text/css" href="' . $userhandler->getViewerUrl() . 'getstylesheet.php?objectid=' . $document['stylesheetid'] . '">');
		$document['stylesheet']['content'] = '<link rel="stylesheet" type="text/css" href="getstylesheet.php?objectid=' . $document['stylesheetid'] . '">';
	}

	$metaobj = owNew('metadata');
	if ($document['metadataid'] == 0) $document['metadataid'] = $metaobj->locatedefault();
	if ($document['metadataid']) {
		getmetadata($document['metadataid'],$document);
		$userhandler->addHeaderCache($document['metadataid'],$document['metadata']['content']);
	}

	$db = &getdbconn();
	$res = &$db->execute("select includeid from template_include where templateid='".$document['templateid']."'");
	while ($row = $res->fetchrow()) {
		$tplobj = owRead($row['includeid']);
		if ($tplobj) {
			$userhandler->addHeaderCache($row['includeid'],$tplobj->elements[0]['header']);
			$userhandler->addStyleCache($row['includeid'],$tplobj->elements[0]['style']);
		}
	}

	$sql = "
		select distinct tm.type, tm.configset from template_modules tm left join template_include ti on tm.template = ti.includeid where ti.templateid IN (
		SELECT '".$document['templateid']."'
		UNION 
		SELECT includeid
		FROM template_include
		WHERE templateid
		IN (
		
		SELECT '".$document['templateid']."'
		UNION 
		SELECT includeid
		FROM template_include
		WHERE templateid = '".$document['templateid']."'
		)
		
		)
		or tm.template='".$document['templateid']."'";

	#OLD SQL: 
	# "select distinct tm.type, tm.configset from template_modules tm left join template_include ti on tm.template = ti.includeid where ti.templateid='".$document['templateid']."' or tm.template='".$document['templateid']."'"

	$res = &$db->execute($sql);
	while ($row = $res->fetchrow()) {
		$type = $row['type'];
		$name = $row['configset'];
		$extclass = "ext_".$type;
		require_once($userhandler->getSystemPath().'/extension/'.$type.'/'.$type.'.class.php');
		$extension = new $extclass();
		$extension->setdocument($document);
		foreach ($extension->functions as $function) {
			if ($function == '_do') $function = 'execute';
			$phpcode = "function " . $function . "_" . $type . "_" . $name . "(\$params) {" .
			"	global \$document, \$contentblock;
				\$extclass = \"ext_\".$type;
				\$extension = new $extclass();
				\$extension->setdocument(\$document);
				\$extension->execute($name, \$params, $function);
				if (method_exists(\$extension,'initState') && \$extension->didSomething()) {
					\$contentblock .= \$extension->extoutput;
					\$document['ext'][$type][$name]['config'] = array();
					\$extension->execute($name, \$params, 'initState', false);
					\$document['ext'][$type][$name]['output'] = \$extension->extoutput;
					\$document['ext'][$type][$name]['result'] = array();
				} else {
					\$document['ext'][$type][$name]['config'] = \$extension->extconfig;
					\$document['ext'][$type][$name]['output'] = \$extension->extoutput;
					\$document['ext'][$type][$name]['result'] = \$extension->extresult;
				}
			}";
			eval($phpcode);
			$smarty->register_function('document.ext.' . $type . '.' . $name . '.' . $function, $function . '_' . $type . '_' . $name);
		}
		unset($extension);
	}
	$document['header'] = '¤¤HEADERBLOCK¤¤';
	# $document['section'][0]['content'] .= '¤¤CONTENTBLOCK¤¤';
	$smarty->assign_by_ref('document',$document);
	$smarty->register_prefilter("insert_content_markup");
	$out = $smarty->fetch($document['template']['name']);
	
	#$out = str_replace('¤¤CONTENTBLOCK¤¤',$contentblock,$out);
	if ($contentblock) $out = preg_replace('/<!--METAZO_CONTENT_BEGIN-->.*<!--METAZO_CONTENT_END-->/s',$contentblock,$out);
	
	$hstr = '';
	foreach ($userhandler->getHeaderCache() as $cur) {
		$hstr .= $cur . "\n";
	}
	$sstr = '';
	foreach ($userhandler->getStyleCache() as $cur) {
		$sstr .= $cur . "\n";
	}
	if (!empty($sstr)) {
		$sstr = '<style type="text/css">' . "\n\t" . '<!--' . "\n" . $sstr . "\t" . '-->' . "\n\t" . '</style>' ."\n";
	}
	$themeid = $styleobj->locateByName('theme');
	$laststr = '';
	if ($themeid) {
		$laststr = '<link rel="stylesheet" type="text/css" href="getstylesheet.php?objectid=' . $themeid . "\">\n";
	}
	
	echo str_replace('¤¤HEADERBLOCK¤¤',"\n" . $hstr . "\n" . $sstr . $laststr . "\n",$out);
	
}

#########################################################################################
#  MAIN SECTION
#########################################################################################

if (!session_id()) session_start();

require_once("site.php");
if (isset($_SESSION['site']) && $_SESSION['site'] != $site) {
	unset($_SESSION['usr']);
	$_SESSION['site'] = $site;
}
require_once($system_path . 'core/util/showpage.inc.php');

$timer = new c_Timer;
$timer->start();

require_once($system_path . 'adodb.php');
require_once($system_path . 'ow.php');
require_once($system_path . 'basic_user.php');
header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$userhandler =& getUserHandler();

if (isset($_REQUEST['_cmd']) && $_REQUEST['_cmd'] == 'logout') 
	$userhandler->LogOutWebsite();

$userhandler->setWebuser(true);

if (!isset($site) || $site == '' || !isset($system_path) || $system_path == '') output_http500();
if (!isset($_REQUEST['pageid']) || $_REQUEST['pageid'] == '') output_http404();

$login_result = 0;
$userhandler->recognizeUser();
if (isset($_REQUEST['lang'])) $userhandler->setLanguage($_REQUEST['lang']);
if (isset($_REQUEST['_cmd']) && $_REQUEST['_cmd'] == 'login') {
	$login = $userhandler->LogIn($site, $_REQUEST['_username'], $_REQUEST['_password'],1);
	if (!$login) $login_result = -1;
}

// Done initializing

ob_start();
switch($CONFIG['doctype']) {
	case 'DOCTYPE_401_TRANS':
		$doctype = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		break;
	case 'DOCTYPE_401_TRANS_WITH_URL':
		$doctype = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n" .
		           "\"http://www.w3.org/TR/html4/loose.dtd\">\n";
		break;
	default:
		$doctype = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
}

echo $doctype;
echo "<!--
METAjour version 2.1 - Content Management System
Copyright (C) 2002-2005 IPW Systems A/S, Jan H. Andersen
-->
";

	showpage($_REQUEST['pageid'],$login_result);
	$user = $userhandler->getSmartyVars();
	$timer->stop();
	$generated = $timer->elapsed();
	$db = getdbconn();
	$sql = "INSERT DELAYED INTO document_statistics 
	(sessionid, userid, timestamp, site, pageid, ip, useragent, browser, 
	version, maj_ver, min_ver, letter_ver, javascript, platform, os, 
	browserlanguage, userlanguage, usercreated, referer, generated)
	VALUES (
	'".session_id()."', 
	'".$_SESSION['usr']['validuserid']."', 
	NOW(), 
	'".$site."', 
	'".$_REQUEST['pageid']."', 
	'".$user['ip']."',
	'".$user['useragent']."', 
	'".$user['browser']."', 
	'".$user['browserversion']."', 
	'".$user['browsermajversion']."',
	'".$user['browserminversion']."', 
	'".$user['browserletterversion']."', 
	'".$user['javascript']."', 
	'".$user['platform']."', 
	'".$user['os']."', 
	'".$user['browserlanguage']."', 
	'".$user['language']."', 
	'".$user['created']."', 
	'".$_SERVER['HTTP_REFERER']."', 
	'".$generated."'
	)";
	echo "<!-- ".$generated."-->\n";
	$db->Execute($sql);

	ob_end_flush();
?>
