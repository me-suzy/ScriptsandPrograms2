<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path.'extension/basicextension.class.php');
require_once('forum.datatype.php');
require_once('forumdata.datatype.php');

class ext_forum extends basicextension {

	function ext_forum() {
		$this->basicextension();
		$this->extname = 'forum';
		$this->addextparam('templatename_list');
		$this->addextparam('templatename_add');
		$this->addextparam('templatename_reply');
		$this->addextparam('templatename_read');

		#move template ref's to datatype
		$this->addextparam('templateid_list');
		$this->addextparam('templateid_add');
		$this->addextparam('templateid_reply');
		$this->addextparam('templateid_read');
	}

	function findTopMessage($objectid) {
		$obj = owRead($objectid);
		$pobj = owRead($obj->getParentId());
		if ($pobj->getType() == 'forumdata') {
			return $this->findtopmessage($pobj->getObjectId());
		} else {
			return $obj->getObjectId();
		}
	}
	
	function getforum($parent, $level=0) {
		$obj = owNew('forumdata');
		$obj->setsort_col('lastreply');
		$obj->setsort_way('desc');
		$obj->listobjects($parent);

		if (!isset($cnt)) $cnt = 0;
		$z = 0;

		while ($z < $obj->elementscount) {
			$cnt = sizeof($this->extresult);
			$this->extresult[$cnt] = $obj->elements[$z];
			$this->extresult[$cnt]['level'] = $level;
			#if ($obj->elements[$z]['haschild']) $this->getforum($obj->elements[$z]['objectid'], $level+1);
			$z++;
		}
	}

	function getmessage($parent, $level=0) {
		$obj = owRead($parent);
		$cnt = sizeof($this->extresult);
		$this->extresult[$cnt] = $obj->elements[0];

		$obj->listobjects($parent);

		$z = 0;

		while ($z < $obj->elementscount) {
			$cnt = sizeof($this->extresult);
			$this->extresult[$cnt] = $obj->elements[$z];
			$this->extresult[$cnt]['level'] = $level;
			if ($obj->elements[$z]['haschild']) $this->getforum($obj->elements[$z]['objectid'], $level+1);
			$z++;
		}
	}

	function makeforumlist() {
		$this->next_extcmd = "list";
		$this->useTemplate('templatename_list','templateid_list','standard_forum_list');
		$this->getforum($this->extconfigsetid,1);
	}

	function convert_links($str, $www = false) {
	   #this converts http://*  the link ends at the next space or(if there is no space) at the end of the string.
	   $str = preg_replace('#(http://)([^\s]*)#', '<a href="\\1\\2" target="_blank">\\1\\2</a>', $str);
	   #optional, replaces www.*
	   if($www) {
	       $str = preg_replace('=(www.)([^\s]*)=', '<a href="http://\\1\\2" target="_blank">\\1\\2</a>', $str);
	   }
	   return $str;
	}

	
	function _do() {
		if (!$this->extconfigsetid) {
			$obj = owNew('forum');
			$obj->createObject(array('name' => $this->extconfigset));
			$this->extconfigsetid = $obj->getObjectId();
			$this->readconf();
		}
		
		switch ($this->extcmd) {

		case "add":
			$this->next_extcmd = "doadd";
			$this->useTemplate('templatename_add','templateid_add','standard_forum_add');
			break;
			
		case "doadd":
			$obj = owNew('forumdata');
			$arr['name'] = htmlspecialchars($_REQUEST['name']);
			$arr['content'] = $this->convert_links(htmlspecialchars($_REQUEST['content']),true);
			$arr['uname'] = htmlspecialchars($_REQUEST['uname']);
			$obj->createobject($arr, $_REQUEST['parentid']);
			$pobj = owRead($_REQUEST['parentid']);
			if ($pobj) {
				if ($pobj->getType() == 'forumdata') {
					$topmostid = $this->findTopMessage($_REQUEST['parentid']);
					$topobj = owRead($topmostid);
					if ($topobj) {
						$topobj->updateObject(array('lastreply'=>$obj->getCreated(),'numreply'=>$topobj->elements[0]['numreply']+1));
					}
				} else {  # new message - set lastreply to created datetime
					$obj->readObject($obj->getObjectId());
					$obj->updateObject(array('lastreply'=>$obj->getCreated() ));
				}
			}
			$this->makeforumlist();
			break;
			
		case "reply" :
			$this->next_extcmd = "doadd";
			$this->getmessage($_REQUEST['objectid'],1);
			$this->useTemplate('templatename_reply','templateid_reply','standard_forum_reply');
			break;

		case "read" :
			$obj = owRead($_REQUEST['objectid']);
			if ($obj) {
				$obj->updateObject(array('numread'=>$obj->elements[0]['numread']+1));
			}
			$this->getmessage($_REQUEST['objectid'],1);
			$this->useTemplate('templatename_read','templateid_read','standard_forum_read');
			break;
		
		default:
			$this->makeforumlist();
		}
	}
}
?>
