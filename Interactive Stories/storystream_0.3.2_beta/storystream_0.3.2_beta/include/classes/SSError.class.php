<?php
/** @file SSError.class.php
 *	Copyright (C) 2004  Karim Shehadeh
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
 *	@date October, 2003	
 */

/** A notification of a potential problem, but nothging that will stop the script */
define ('ERROR_TYPE_WARNING', 1);

/** An indication of a serious problem that will likely yield unexpected results */
define ('ERROR_TYPE_SERIOUS', 2);

/** An indication that a critical error has occured that prevents the script from completing successfully*/
define ('ERROR_TYPE_FATAL', 3);


/**  Base class for all objects in the system
 *	The base object will handle a variety of necessities
 *	required by most objects in the system.
*/
class SSError extends PEAR_Error
{
	/** Constructor calls base class constructor */
	function SSError ($message='') {
	
		// Call the base constructor with the message and
		//	and the error action
		parent::PEAR_Error ($message, PEAR_ERROR_RETURN);
	}
	
	function getTrace ($html=false) {
		if (PHPVERSION() >= 4.3) 
		{
			$dbg = debug_backtrace ();
			$s = '';
			foreach ($dbg as $pt) {
				$s .= 'Function: '.$pt['function'].' in '.(isset ($pt['file'])?$pt['file']:'unknown').' ('.(isset ($pt['line'])?$pt['file']:'unknown').')'.($html?'<br>':"\n");
			}
			
			return $s;
		}
		
		return '[Trace Not Supported]';
	}
	
	/** Returns a string that lists all the functions leading to the caller function 
	 * @return string The HTML to display the trace.
	 */
	function traceback () {
		$s = $this->getTrace();
		echo $s;
	}
	
	/** The error handler for PHP errors
	 * 	@param int $errno The PHP error number
	 *	@param string $errstr The PHP error deescription
	 * 	@param string $errfile The file in which the error occured.
	 *	@param int $errline The line at which the error occured.
	 */ 
	function onError ($errno, $errstr, $errfile, $errline) {
	
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		$msg = "PHP Error $errno: $errstr in '$errfile' at line $errline";
		if (SS_DEBUG) {
		
			if (($errno == E_NOTICE) && strpos ($errfile, 'discuss') !== false) {
				// phpBB has produced a notice error so we can ignore it.
				//	phpBB produces *a lot* of these.
				return;
			}
			
			// Display on screen as well as sending email.
			//   NOTE: Ignore serialization errors.  These are
			//       caused by invalid data stored in the database, but
			//       it seems to only happen when there is associated
			//       media file data.  Can't handle it.
			if (strpos ($errstr, 'unserialize') !== false)
			{
                if ($errno == 8)
                {
                    return;
                }
            }
			
   			$dbg_msg = $msg.'<div style="padding-left: 10px">'.nl2br (SSError::getTrace ()).'</div>';
   			$GLOBALS['APP']->addDebugMessage ($msg.'<br><div style="font-size:10px">'.SSError::getTrace (true).'</div>', $errfile, '', $errline, '');
		}
		
		// Only email errors if they're not 'notice' level.
		if ($errno != E_NOTICE && $GLOBALS['admin_email_errors']) {
		
			$msg .= "\r\nGenerated On:".date ('F j, Y, g:i a');
			$msg .= "\r\nPHP Version:".PHP_VERSION.' ('.PHP_OS.')';
			$msg .= "\r\nError Level: ".$errno;
			$msg .= "\r\nURL:".$_SERVER['REQUEST_URI'];
			$msg .= "\r\nRemote Address:".$_SERVER['REMOTE_ADDR'];
			$msg .= "\r\nLogged In:".($user?$user->get('username'):'[Guest]');
			$msg .= "\r\n".SSError::getTrace ();
			//mail ($GLOBALS['admin_email'], 'StoryStream Error', $msg);
		}
	}
};
?>
