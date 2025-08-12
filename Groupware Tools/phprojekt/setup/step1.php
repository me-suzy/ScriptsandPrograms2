<?php

// step1.php - PHProjekt Version 5.0
// copyright © 2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: step1.php,v 1.15 2005/06/30 18:09:45 paolo Exp $

// check whether setup.php calls this script - authentication!
if (!defined('setup_included')) die('Please use setup.php!');


// choose update or install or config! :-)
if (!ereg("update|install|config", $setup)) die(__('Please choose Installation,Update or Configure!'));

// prevents blank input fields
echo "
<script language='JavaScript' src='lib/chkform.js' type='text/javascript'></script>
<h3>PHProjekt SETUP</h3>
<p style='font-size:10pt;'><br />
";

if ($setup == "install") {
    echo "
<table bgcolor='#D0D0D0' width='350'>
    <tr>
        <td>
            <b> ".__('Welcome to the setup of PHProject!<br>')."</b>
            <br /><br />
";
    // remarkable hints
    echo __('Please remark:<ul><li>A blank database must be available<li>Please ensure that the webserver is able to write the file config.inc.php')."<br />\n";
    echo __('<li>If you encounter any errors during the installation, please look into the <a href=help/faq_install.html target=_blank>install faq</a>or visit the <a href=http://www.PHProjekt.com/forum.html target=_blank>Installation forum</a></i>')."<br />\n";

    if (isset($db_type)) {
        // hint for pg faq
        if ($db_type == "postgresql") {
            echo "<li>".__('Please read the FAQ about the installation with postgres')."<br />\n";
        }
        // offer a manual installation for mysql users
        else if ($db_type == "mysql") {
            echo "<li>".__('If you want to install PHProjekt manually, you find<a href=http://www.phprojekt.com/files/sql_dump.tar.gz target=_blank>here</a> a mysql dump and a default config.inc.php')."<br />\n";
        }
    }

    echo "</ul>\n";
    echo __('Please fill in the fields below')."<br /><br />\n";
    echo __('(In few cases the script wont respond.<br>Cancel the script, close the browser and try it again).<br>');
    echo "
            <br /><br />
            </p>
        </td>
    </tr>
</table>
<br />
";

    // form to insert the db access parameters
    echo "
<form action='setup.php' method='post' name='db_par' onsubmit=\"return chkForm('db_par','db_host','".__('Please fill in the following field').": ".__('Hostname')."','db_user','".__('Please fill in the following field').": ".__('Username')."','db_name','".__('Please fill in the following field').": ".__('Name of the existing database')."')\">
    <input type='hidden' name='step' value='2a' />
    <input type='hidden' name='".session_name()."' value='".session_id()."' />
    <table bgcolor='#D0D0D0' width='350' border='0' cellpadding='0' cellspacing='1'>
        <tr>
            <td>".__('db_type').":</td>
            <td>
                <select name='db_type'>
";
    if (function_exists('mysql_connect')) echo "<option value='mysql'>MySQL</option>\n";
    if (function_exists('sqlite_open'))   echo "<option value='sqlite'>SQLite</option>\n";
    if (function_exists('ocilogon'))      echo "<option value='oracle'>Oracle</option>\n";
    if (function_exists('ifx_connect'))   echo "<option value='informix'>Informix</option>\n";
    if (function_exists('pg_connect'))    echo "<option value='postgresql'>Postgres</option>\n";
    if (function_exists('mssql_connect')) echo "<option value='ms_sql'>MS SQL</option>\n";
    if (function_exists('ibase_connect')) echo "<option value='interbase'>Interbase</option>\n";
    echo "
                </select>
                <br /><br />
            </td>
        </tr>
        <tr>
            <td>".__('Hostname').":</td>
            <td><input type='text' name='db_host' /></td>
        </tr>
        <tr>
            <td>".__('Username').":</td>
            <td><input type='text' name='db_user' /></td>
        </tr>
        <tr>
            <td>".__('Password').":</td>
            <td><input type='password' name='db_pass' /></td>
        </tr>
        <tr>
            <td>".__('Name of the existing database').":</td>
            <td><input type='text' name='db_name' /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type='submit' value='".__('go')."' /></td>
        </tr>
    </table>
</form>
";
}
// alternatively take the values of the config and put it in the session
else {
    echo __('Do you want to backup your database right now? (And zip it together with the config.inc.php ...)<br>Of course I will wait!')."
<br /><br />
<form action='setup.php' method='post'>
    <input type='hidden' name='step' value='2a' />
    <input type='submit' value='".__('Next')."' />
</form>
";
}

$_SESSION['langua'] =& $langua;
$_SESSION['setup'] =& $setup;

?>
