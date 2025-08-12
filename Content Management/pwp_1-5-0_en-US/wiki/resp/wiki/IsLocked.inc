<?php
/**
 * This response shows infos about
 * a locked page and provides an unlock link to resolve
 * the situation by force.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class IsLocked extends Response {

	function IsLocked( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (!isset($args['TStamp']) or !isset($args['User'])) {
			$gError->add("Missing or invalid parameters in 'IsLocked', cannot continue!");
		} else {
			$args['TStamp'] = (int)((($args['TStamp']+29)/60)+1);
			$args['User']     = ($args['User']=='')?('an unknown user'):("<strong>{$args['User']}</strong>");
		}

		if (!isset($args['Goto'])) { //may be history or erase page
			$args['Goto'] = 'wiki/ViewPage';
		}

		if (!isset($args['URLArgs'])) {
			$args['URLArgs'] = '';
		} else {
			$args['URLArgs'] = '&amp;'.$args['URLArgs'];
		}

		if (!isset($_REQUEST['iPage'])) { //may be history or erase page
			$_REQUEST['iPage'] = '';
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		//print the infos

		if ($args['TStamp'] > 60) {
		echo <<<eoh
$buffer
<div id='textcolumn' style='padding: 50,0,50,0;'>
<p>
The page {$_REQUEST['iPage']} has been locked by {$args['User']} for
more than 60 minute(s).
</p>
<p>
This is most likely a lock of an aborted edit operation. It is recommended to
<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/RemoveLock&amp;iPage={$_REQUEST['iPage']}&amp;iGoto={$args['Goto']}{$args['URLArgs']}'>remove the lock</a>
by following the link.
</p>
</div>
eoh;
		} else {
		echo <<<eoh
$buffer
<div id='textcolumn' style='padding: 50,0,50,0;'>
<p>
The page {$_REQUEST['iPage']} has been locked by {$args['User']} for
about <strong>{$args['TStamp']}</strong> minute(s).
</p>
<p>
You can either wait until the user saves the page which will remove the lock automatically or you can
<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/RemoveLock&amp;iPage={$_REQUEST['iPage']}&amp;iGoto={$args['Goto']}{$args['URLArgs']}'>remove the lock</a>
by yourself.
In the latter case you or the other user
might end up in a conflict when trying to save the changes.
</p>
</div>
eoh;
		}
		$args['Heading'] = "Locked page: {$_REQUEST['iPage']}";
		$this->changeState('out/Html');
	}

}

?>