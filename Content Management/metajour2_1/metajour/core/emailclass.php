<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('basicclass.php');

class email extends basic {

	function email() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('subject',0,UI_STRING);
		$this->addcolumn('content',0,UI_TEXT_WRAP);
	
		$this->removeview('createvariant');
	}

	function tableUpdate() {
		if (!tableExists('email')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `email` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `subject` varchar(255) NOT NULL default '',
				  `content` mediumtext NOT NULL,
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				);
				");
		}
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'subject';
		$arr[] = 'content';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		$arr[] = 'objectid';
		return $arr;
	}
	
	function prv_createobject($arr) {
		global $CONFIG;
		basic::prv_createobject($arr);

		if ($CONFIG['eventmail']) {
			require_once('core/mimemail/htmlMimeMail.php');
			$mail = new htmlMimeMail();
			if ($resultarr['htmlmail'] == 1) {
				$mail->setHTML($arr['content']);
				$mail->setHTMLEncoding('8bit');
			} else {
				$mail->setText($arr['content']);
				$mail->setTextEncoding('8bit');
			}
			$mail->setSubject($arr['subject']);
			$u = owNew('user');
			$u->setlistaccess(true);
			$u->readobject($this->userhandler->getObjectId());
			
			$name = $u->elements[0]['realname'];
			if ($name == '') $name = $u->elements[0]['name'];
			$email = $u->elements[0]['email'];
			$mail->setFrom($name.' <'.$email.'>');
			if (!empty($arr['name'])) $mail->send(array($arr['name']));
		}
	}
	
}
