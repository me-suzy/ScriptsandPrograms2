<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

global $system_path;
require_once($system_path . 'core/mimemail/htmlMimeMail.php');

function smarty_function_sendmail($params, &$smarty) {

    if (empty($params['to'])) {
        $smarty->trigger_error("sendmail: missing recipient address");
        return;
    }

	$mail = new htmlMimeMail();

	if (isset($params['html']) && 1 == $params['html']) {
		$mail->setHTML($params['message']);
		$mail->setHTMLEncoding('8bit');
	} else {
		$mail->setText($params['message']);
		$mail->setTextEncoding('8bit');
	}

	$mail->setSubject($params['subject']);


    if (!isset($params['fromname'])) $params['fromname'] = $params['from'];
    if (isset($params['from'])) {
		$mail->setFrom($params['fromname'] . ' <' . $params['from'] . '>');
	}

	$mail->send(array($params['to']));

}


?>
