<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage handler
 */
require_once('ow.php');

class basic_event {
	var $_events = null;
	var $userhandler;
	
	function parseEventContent($objectid,$eventid) {
		$smarty =& $this->userhandler->getSmarty();
		$smarty->default_resource_type = 'event';
		$this->userhandler =& getUserHandler();
		$arr[0] = owReadExpand($objectid[0]);
		
		$eventobj = owRead($eventid);
		$smarty->assign('user',$this->userhandler->getSmartyVars());
		$smarty->assign('result',$arr);
		$smarty->assign('subject',$eventobj->elements[0]['subject']);
		$arr['content'] = $smarty->fetch($eventid);
		$arr['emailto'] = explode(';',$smarty->get_template_vars('emailto'));
		$arr['msgto'] = explode(';',$smarty->get_template_vars('msgto'));
		$arr['subject'] = $smarty->get_template_vars('subject');
		$arr['skip'] = $smarty->get_template_vars('skip');
		$arr['attach'] = $smarty->get_template_vars('attach');
		$arr['htmlmail'] = $smarty->get_template_vars('htmlmail');
		return $arr;
	}
		
	function fireEvent($objectid,$eventid) {
		global $CONFIG;
		$this->userhandler =& getUserHandler();
		$this->userhandler->setUnlimitedAccess(true);
		$resultarr = $this->parseEventContent($objectid,$eventid);
		$obj = owRead($eventid);

		if ($CONFIG['eventmail']) {
			require_once('core/mimemail/htmlMimeMail.php');
			$mail = new htmlMimeMail();
			if ($resultarr['htmlmail'] == 1) {
				$mail->setHTML($resultarr['content']);
				$mail->setHTMLEncoding('8bit');
			} else {
				$mail->setText($resultarr['content']);
				$mail->setTextEncoding('8bit');
			}
		}
		if (is_array($resultarr['msgto'])) $obj->elements[0]['msgdest2'] = array_merge($obj->elements[0]['msgdest2'],$resultarr['msgto']);
		
		$dest1to = array(); # array of objectid's of users
		$dest2to = array(); # array of objectid's of users
		if (is_array($obj->elements[0]['msgdest1'])) {
			foreach ($obj->elements[0]['msgdest1'] as $dest) {
				switch($dest) {
					case 1:
						$curobj = owRead($objectid[0]);
						if ($curobj) $dest1to[] = $curobj->getCreatedBy();
						break;
					case 2:
						$userobj = owRead($obj->getCreatedBy());
						if ($userobj) $dest1to[] = $userobj->getParentId();
						break;
					case 3:
						$dest1to[] = $obj->getCheckedBy();
						break;
					case 4:
						$dest1to[] = $this->userhandler->getObjectId();
						break;
					case 5:
						$dest1to[] = $this->userhandler->getCreatedBy();
						break;
					case 6:
						$curobj = owRead($objectid[0]);
						$tmp = $curobj->resolveAccess();
						if (is_array($dest1to)) {
							$dest1to = array_merge($dest1to, $tmp);
						} else {
							$dest1to = $tmp;
						}
						break;
				}
			}
		}

		if (is_array($obj->elements[0]['msgdest2'])) {
			foreach ($obj->elements[0]['msgdest2'] as $dest) {
				if ($dest != 0) {
					$uobj = owRead($dest);
					if ($uobj->type == 'usergroup') {
						$dest2to = array_merge($dest2to,$uobj->getMembers());
					} else {
						$dest2to[] = $dest;
					}
				}
			}
		}
		
		$internalto = array();
		$emailto = array();
		
		if (is_array($obj->elements[0]['msgtype1'])) {
			foreach ($obj->elements[0]['msgtype1'] as $type) {
				switch($type) {
					case 1:
						$internalto = array_merge($internalto,$dest1to);
						break;
					case 2:
						$emailto = array_merge($emailto,$dest1to);
						break;
				}
			}
		}

		if (is_array($obj->elements[0]['msgtype2'])) {
			foreach ($obj->elements[0]['msgtype2'] as $type) {
				switch($type) {
					case 1:
						$internalto = array_merge($internalto,$dest2to);
						break;
					case 2:
						$emailto = array_merge($emailto,$dest2to);
						break;
				}
			}
		}

		
		$internalto = array_unique($internalto);
		$emailto = array_unique($emailto);
		$emailadr = array();
				
		if (is_array($emailto)) {
			foreach ($emailto as $usrid) {
				$usrobj = owRead($usrid);
				if ($usrobj->elements[0]['email'] != '') $emailadr[] = $usrobj->elements[0]['email'];
			}
		}

		if (is_array($resultarr['emailto'])) $emailadr = array_merge($emailadr,$resultarr['emailto']);
		
		$emailadr = array_unique($emailadr);

		$this->userhandler->setUnlimitedAccess(false);
		
		if ($CONFIG['eventmail']) {
			$mail->setSubject($resultarr['subject']);
			$mail->setFrom($CONFIG['eventfrom']);
		}
		if ($resultarr['skip'] != '1') {
			if ($CONFIG['eventmail']) {
				foreach ($emailadr as $email) {
					if (!empty($email)) $mail->send(array($email));
				}
			}

			if ($resultarr['attach'] == '1') {
				$fobj = owNew('binfile');
				$arr['mimetype'] = 'text/plain';
				$arr['name'] = $resultarr['subject'];
				$arr['_makecontent_'] = $resultarr['content'];
				$fobj->createObject($arr,$objectid[0]);
			}		
		}
	}
	
	function event($event='', $otype='', $objectid=array()) {
		if (null == $this->_events) {
			$obj = owNew('event');
			$obj->setlistaccess(true);
			$obj->listobjects();
			$this->_events = $obj->elements;
		}

		if (is_array($this->_events)) {
			foreach($this->_events as $curr) {
				if ($curr['triggerevent'] == $event && ($curr['triggertype'] == $otype)) {
					$this->fireEvent($objectid,$curr['objectid']);
				}
			}
		}
		
	}
	
}

function &GetEventHandler() {
	static $_eventhandler = null;
	if (null == $_eventhandler) {
		$_eventhandler = new basic_event;
	}
	return $_eventhandler;
}

?>