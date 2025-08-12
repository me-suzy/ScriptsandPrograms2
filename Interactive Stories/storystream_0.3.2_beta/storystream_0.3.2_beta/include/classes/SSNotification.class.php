<?php
/** @file SSNotification.class.php
 *	Copyright (C) 2004  Karim Shehadeh
 *
 * 	Contains the implementation of the SSNotification class.
 *
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *	
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *	
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *	@version 0.1
 *	@date April, 2004	
 */

/** Used by the engine to send notifications to certain users
 *	You can use this class to send notifications of certain events
 *	to users who have requested that they be notified of said events.
 *	The class will take care of finding the right users and sending the
 *	email.  The text of the email is stored with the active theme
 *	under the 'text' folder.
 *	@author Karim Shehade
 *	@date 4/22/2004
 */
class SSNotification extends SSObject
{
	/** Wraps up the necessary calls to send an email through the StoryStream engine
	 * 	@param string $from The person/email address from whom this email will appear to come
	 * 	@param string $to The email address of the person who will receive this message.
	 * 	@param string $subject The subject of the email
	 *	@param string $body The body of the email message
	 *	@return bool Returns true if the email was sent, false otherwise.
	 */
	function sendMail ($from, $to, $subject, $body) {
	              
		$mime = new Mail_mime("\r\n");
		$mime->setTXTBody($body);
		$hdrs = $mime->headers(array('From'=>$from,'Subject'=>$subject));
		
		$mail =& Mail::factory('mail');
		
		$result = $mail->send($to, $hdrs, $mime->get());
		if ($result === true) {
			return true;
		}
		else {
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);			
		}
		return false;
	}
	
	/** Sends a notification that the given story has been added to the database
	 * 	This is called each time a story is added to the databse.  It will
	 * 	send an email to all users who are registered to receive these types 
	 * 	of emails.
	 * 	@param SSStory $story The story that was added
	 */
	function sendStoryNotification ($story) {
	
		// Make sure that a notification should be sent.
		if ($story->shouldSendNotification ()) {
			
			$tableConstant = 'TABLE_USER';
			$tableName = $GLOBALS[$tableConstant]['name'];
			$statusField = $GLOBALS[$tableConstant]['fields']['STATUS'];
			$notifyField = $GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_STORY'];
			
			$query = "SELECT * FROM $tableName WHERE $statusField=".USER_STATUS_ACTIVE." AND $notifyField=1";
			$result = $GLOBALS['DBASE']->simpleQuery ($query);		
			if (!DB::isError ($result)) {
			
				$group = $story->getGroup ();
				$userlist = array ();
				$resultObj = new DB_result ($GLOBALS['DBASE'],$result);
				while (($array = $resultObj->fetchRow ())) {

					$user = new SSUser;
					$user->_setDBKeyValueArray ($array);
					
					// Only send a notification to members of the group if 
					//	the story is part of a group.
					if (!$group || $group->isUserInGroup($user)) {
						//Populate the user object with the values returned by
						//	the query.
						
						$name = stripslashes ($story->get (PROP_NAME));
						$subject = STR_107.$name;
						$text = implode ('', file ($GLOBALS['APP']->getThemeDir ().'/text/notify_story.txt'));				
						$link = $GLOBALS['baseUrl'].'read/read.php?t=2&i='.$story->get (PROP_ID);
						$text = sprintf ($text, $name, stripslashes ($story->get (PROP_DESCRIPTION)), $link, $GLOBALS['baseUrl']);
						$from = 'StoryStream Notification <notifier@storystream.org>';
						$to = $user->get ('email');
						$this->sendMail ($from, $to, $subject, $text);
					}
				}			
			}
			else {
				$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
			}
		}
	}
	
	/** Sends a notification that the given scene has been added to the database
	 * 	This is called each time a scene is added to the databse.  It will
	 * 	send an email to all users who are registered to receive these types 
	 * 	of emails.
	 * 	@param SSScene $scene The scene that was added
	 */
	function sendSceneNotification ($scene) {
		$tableConstant = 'TABLE_USER';
		$tableName = $GLOBALS[$tableConstant]['name'];
		$statusField = $GLOBALS[$tableConstant]['fields']['STATUS'];
		$notifyField = $GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_SCENE_FORK'];
		
		$query = "SELECT * FROM $tableName WHERE $statusField=".USER_STATUS_ACTIVE." AND $notifyField=1";
		$result = $GLOBALS['DBASE']->simpleQuery ($query);		
		if (!DB::isError ($result)) {
		
			$userlist = array ();
			$resultObj = new DB_result ($GLOBALS['DBASE'],$result);
			while (($array = $resultObj->fetchRow ())) {
								
				//Populate the user object when the values returned by
				//	the query.
				$user = new SSUser;
				$user->_setDBKeyValueArray ($array);
				$name = stripslashes ($scene->get (PROP_NAME));
				$subject = STR_108.$name;
				$text = implode ('', file ($GLOBALS['APP']->getThemeDir ().'/text/notify_scene.txt'));				
				$link = $GLOBALS['baseUrl'].'read/read.php?t=1&i='.$scene->get (PROP_ID);
				$text = sprintf ($text, $name, stripslashes ($scene->get (PROP_DESCRIPTION)), $link, $GLOBALS['baseUrl']);
				$from = 'StoryStream Notification <notifier@storystream.org>';
				$to = $user->get ('email');
				$this->sendMail ($from, $to, $subject, $text);
			}			
		}
		else {
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
		}
	}
	
	/** Sends a notification that the given fork has been added to the database
	 * 	This is called each time a fork is added to the databse.  It will
	 * 	send an email to all users who are registered to receive these types 
	 * 	of emails.
	 * 	@param SSFork $fork The fork that was added
	 */
	function sendForkNotification ($fork) {
	
		$tableConstant = 'TABLE_USER';
		$tableName = $GLOBALS[$tableConstant]['name'];
		$statusField = $GLOBALS[$tableConstant]['fields']['STATUS'];
		$notifyField = $GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_SCENE_FORK'];
		
		$query = "SELECT * FROM $tableName WHERE $statusField=".USER_STATUS_ACTIVE." AND $notifyField=1";
		$result = $GLOBALS['DBASE']->simpleQuery ($query);		
		if (!DB::isError ($result)) {
		
			$userlist = array ();
			$resultObj = new DB_result ($GLOBALS['DBASE'],$result);
			while (($array = $resultObj->fetchRow ())) {
								
				//Populate the user object when the values returned by
				//	the query.
				$user = new SSUser;
				$user->_setDBKeyValueArray ($array);
				$name = stripslashes ($fork->get (PROP_NAME));
				$subject = STR_109.$name;
				$text = implode ('', file ($GLOBALS['APP']->getThemeDir ().'/text/notify_fork.txt'));				
				$link = $GLOBALS['baseUrl'].'read/read.php?t=3&i='.$fork->get (PROP_ID);
				$text = sprintf ($text, $name, stripslashes ($fork->get (PROP_DESCRIPTION)), $link, $GLOBALS['baseUrl']);
				$from = 'StoryStream Notification <notifier@storystream.org>';
				$to = $user->get ('email');
				$this->sendMail ($from, $to, $subject, $text);
			}			
		}
		else {
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
		}
	}
	
	/** Sends a notification containing a given announcment
	 * 	This is called each time an announcment is added to the database.  It will
	 * 	send an email to all users who are registered to receive these types 
	 * 	of emails.
	 * 	@param SSAnnouncement $announcement The announcement to notify of.
	 */
	function sendAnnouncementNotification ($announcement) {
	
		$tableConstant = 'TABLE_USER';
		$tableName = $GLOBALS[$tableConstant]['name'];
		$statusField = $GLOBALS[$tableConstant]['fields']['STATUS'];
		$notifyField = $GLOBALS[$tableConstant]['fields']['NOTIFY_UPDATES'];
		
		$query = "SELECT * FROM $tableName WHERE $statusField=".USER_STATUS_ACTIVE." AND $notifyField=1";
		$result = $GLOBALS['DBASE']->simpleQuery ($query);		
		if (!DB::isError ($result)) {
		
			$userlist = array ();
			$resultObj = new DB_result ($GLOBALS['DBASE'],$result);
			while (($array = $resultObj->fetchRow ())) {
								
				//Populate the user object with the values returned by
				//	the query.
				$user = new SSUser;
				$user->_setDBKeyValueArray ($array);
				$subject = STR_110.$announcement->get ('subject');
				$text = implode ('', file ($GLOBALS['APP']->getThemeDir ().'/text/notify_update.txt'));				
				$date = date ("l, F j, Y - h:i a", $announcement->get ('date'));
				$link = $GLOBALS['baseUrl'].'members/announce.php?a=view&aid='.$announcement->get (PROP_ID);
				$text = sprintf ($text, $announcement->get ('subject'), $date, stripslashes ($announcement->get ('text')), $link, $GLOBALS['baseUrl']);
				$from = 'StoryStream Notification <notifier@storystream.org>';
				$to = $user->get ('email');
				$this->sendMail ($from, $to, $subject, $text);
			}			
		}
		else {
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
		}
	}
	
	/**  Sends a registration email to the email address stored
     *	This should only be called by newRegistration and newEmailAddress
     *	since both of these require that the email address be confirmed.
     *	@return bool True if the mail was sent successfully.
	*/
	function _sendRegistrationEmail ($user) {
		
		$from = $GLOBALS['CONFIG']->get ('admin_email');
		$to = $user->get ('email');
		$subject = 'StoryStream Account Registration';
		$hash = $user->get ('hash');
		
		$crlf = "\r\n";
		$link = $GLOBALS['baseUrl'].'/members/?a=confirm&hash='.$hash.'&email='.urlencode ($to);
		$msg = implode ('', file ($GLOBALS['APP']->getThemeDir ().'/text/confirm_registration.txt'));		
		$text = $msg.$crlf.$link.$crlf;
		
		return $this->sendMail ($from, $to, $subject, $text);
	}	
}
?>
