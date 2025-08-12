<?php

// step.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: step.php,v 1.14 2005/06/30 18:09:45 paolo Exp $

// check whether setup.php calls this script - authentication!
if (!defined('setup_included')) die('Please use setup.php!');

include_once './lib/languages.inc.php';

$lang = '';
settype($languages, 'array');
foreach ($languages as $l_short => $l_long) {
    $lang .= '<option value="'.$l_short.'">'.$l_long."</option>\n";
}

echo '
<h3>PHProjekt SETUP</h3>
<form action="setup.php" method="post">
    <input type="hidden" name="step" value="1" />
    <input type="hidden" name="'.session_name().'" value="'.session_id().'" />
    <table bgcolor="#D0D0D0" cellpadding="0" cellspacing="1" border="0">
        <tr>
            <td>Action:</td>
            <td>
                <input type="radio" id="install" name="setup" value="install" />
                <label for="install">First time installation</label><br />
                <input type="radio" id="update" name="setup" value="update" />
                <label for="update">Update to new version</label><br />
                <input type="radio" id="config" name="setup" value="config" />
                <label for="config">Configure current version</label>
                <br /><br />
            </td>
        </tr>
        <tr>
            <td><label for="langua">Language:</label></td>
            <td>
                <select id="langua" name="langua">
'.$lang.'
                </select>
                <br /><br />
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" value="submit" /></td>
        </tr>
    </table>
</form>
';

?>
