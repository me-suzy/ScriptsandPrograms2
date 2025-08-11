<?php
// $Id: config_setup.php,v 1.23 2005/06/25 10:39:30 krabu Exp $
/**
 * Back-End configurator
 *
 * This is the backend configurator. It's a quick and dirty configuration tool
 * designed to be used to configure an initial install of Back-End. It is NOT
 * designed to install, upgrade, or perform ongoing maintenance.
 *
 * USER FACING FUNCTIONALITY:
 *  A wizard-type web-app that verifies the webserver's environment, and then
 *  configures BE.
 *
 * DESIGN:
 *  Pretty ugly really. The pages in the wizard are defined as a set of steps
 *  are defined in an array. Each step is a string. The string is used as a
 *  prefix to uniquify functions that define behaviour for each of the steps.
 *  The loader is the chunk of behaviour at the start of this script that
 *  determines which step to run.
 *
 *  There are two functions that the loader cares about: '<prefix>_show'
 *  displays the initial HTML of the step; '<prefix>_run' is run when the
 *  '_show' step is submitted (and should therefore do all of the processing
 *  associated with the step). Additionally, the human meaningful name of the
 *  step is determined by running '<prefix>_name' (although this is only done
 *  in _showHeaders). A step controls which step is to be run by setting the
 *  'op' variable.
 *
 * TODO:
 *  - Multi-lingualize.
 *  - After boot-strapping, test and then use phplib/phpslash
 *  - Generalize the editing of the config file (instead of using special
 *    constants, use the config file syntax)
 *
 * @package     Back-End
 * @copyright   2002-5 - Open Concept Consulting
 * @version     0.7 $Id: config_setup.php,v 1.23 2005/06/25 10:39:30 krabu Exp $
 * @copyright   Copyright (C) 2003 OpenConcept Consulting
 * @author Evan Hughes (evan@openconcept.ca), OpenConcept Consulting
 *
 *
 * This file is part of Back-End.
 *
 * Back-End is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Back-End is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Back-End; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

#ini_set ('error_reporting', E_ALL);

// Setup of names and constants.
define('PRODUCT', 'Back-End'); // Name of the product.

define('PSL_INIFILE_PATH','/etc/'.strtolower(PRODUCT).'/'); // Location for phpslash configurations. This is the location that phpslash will fall back to if config.ini.php is not found in "public_html" (the publicly accessible files of phpslash): HINT check config.php for $psl_inifile_path content

define('CONFIG_IN', 'config_setup.ini.php'); // Name of the blank config file.
# define('CONFIG_IN', 'config-dist.ini.php'); // Name of the blank config file.
define('CONFIG_OUT', 'config.ini.php'); // Name of the target config file.

define('TABLE_PATH', '/tables/'); // Path to the sql table directory.

# define('TABLE_CORE', 'psl_core.sql'); // sql required data.
# define('TABLE_SAMPLE', 'psl_example.sql'); // sql example data.
define('DEBIAN_DOC_PATH', '/usr/share/doc/'.strtolower(PRODUCT).'/'); // Path to the sql table directory under debian (for .deb package. strings are all lowercase)
/* Return values for the verification functions. These get binary or'd so
 * leave 'em as 0 and 1.
 */
define('VERIFICATION_FAILED', 1);
define('VERIFICATION_SUCCEEDED', 0);


// Setup constants for step return values.
define('STEP_ERROR', 98); // Returned when a step has a problem
define('STEP_FAULT', 99); // Returned when a step has a problem, but just wants to be displayed (ie, it handled the error display)
define('STEP_OK', 100); // Returned when a step runs properly

// Setup constants for return values from _filterFile
define('FF_READ_FAIL', 101);
define('FF_WRITE_FAIL', 102);

// Variable to allow config.ini.php to be echoed to browser
$modifiedConfigFile='';

# Setup the config.ini.php file
$steps = array(
    'start',
    'verify_environment',
    'setup_config',
    'setup_database',
    'redirect'
);

if (isset($_REQUEST['op'])) {
	$op = clean($_REQUEST['op']);
} else {
	$op = '';
}

if (isset($_REQUEST['step'])) {
	$step = clean($_REQUEST['step']);
} else {
	$step = $steps[0];
}

if (isset($_REQUEST['var'])) {
	$var = clean($_REQUEST['var']);
} else {
	$var = $steps[0];
}

$user='';

switch($op) {
    case(''): /* Just show off the step */
        $fn = $step . '_show';
        break;
    case('run'):
        $fn = $step . '_run';
        break;
}

$result = $fn();

if ($result == STEP_ERROR) {
    print 'Error on line ' . $GLOBALS['errorLine'] . ' of configurator: ' . $GLOBALS['errorText'];
}
else if ($result == STEP_FAULT) {
    /* The error was handled by the step. Ignore. */
}
else if ($result == STEP_OK && $op = 'run') {
    header("Location: ?step=" . getNextStep());
}


function _setError($text, $line) {
    $GLOBALS['errorText'] = $text;
    $GLOBALS['errorLine'] = $line;
}


/* Returns the name of the next step */
function getNextStep() {
    global $step;
    global $steps;
    /* Step succeeded. Get the next step */
    $nextStep = sizeof($steps);
    if ($nextStep < 1) {
        trigger_error("ERROR! The steps array is zero sized!");
    }
    for ($i = 0; $i < sizeof($steps); $i++) {
        if ($steps[$i] == $step) {
            $nextStep = $i+1;
            $i = sizeof($steps);
        }
    }

    if ($nextStep < sizeof($steps)) {
        return $steps[$nextStep];
    } else {
        trigger_error("ERROR! Couldn't find step named '$step'");
        return null;
    }
}

/* Returns the name of the current step */
function getCurrentStep() {
    global $step;
    return $step;
}



/////////////////////////////////////////////////////////////////////////////
//
//
//
//  start - Displays a splash screen and an explanation.
//
//
//
/////////////////////////////////////////////////////////////////////////////

function start_name() {
    return 'Welcome';
}


function start_show() {
    _showHeaders();
    ?>

<h1>Welcome to the <?php print PRODUCT?> configurator</h1>


<p>This script will help you configure your install of <?php print PRODUCT?>. Since each
step modifies system files and settings, you should not use your browsers
forward and back buttons. </p>


<p><div align="center">
    <a href="?step=<?php print getNextStep()?>">Start</a>
</div></p>
    <?php
}

function start_run() {
    return STEP_OK;
}


/////////////////////////////////////////////////////////////////////////////
//
//
//
//  verify_environment - Verifies that the ws environment is properly
//  configured.
//
//
//
/////////////////////////////////////////////////////////////////////////////

function verify_environment_name() {
    return 'Verify environment';
}

/* Shows the result of verification.
 */
function _showVerificationResult($test, $result=null) {
    $class = ''; // The CSS class to use for the row
    $resultText = ''; // The text to display for the result
    if (null == $result) {
        $class = 'success';
        $resultText = 'Passed';
    } else {
        $class = 'failure';
        $resultText = $result;
    }
    ?>
    <tr class="<?php print $class?>">
        <td><?php print $test?></td>
        <td><?php print $resultText?></td>
    </tr>
    <?php
}

/* Verify that we're using the correct version of php
 */
function _verifyVersion() {
    $testName = 'PHP Version';
    $version = explode('.', phpversion());
    if (($version[0] <= 4) && ($version[1] < 1)) {
        _showVerificationResult($testName,
            "This server is running PHP " . phpversion() . ". " . PRODUCT
            . " requires a version newer than 4.1. Please upgrade to a newer "
            . " version of PHP."
        );
        return VERIFICATION_FAILED;
    } else {
        _showVerificationResult($testName);
        return VERIFICATION_SUCCEEDED;
    }
}


function _verifyXMLSupport() {
    $testName = 'XML support';
    if (function_exists('xml_parser_create')) {
        _showVerificationResult($testName);
        return VERIFICATION_SUCCEEDED;
    } else {
        _showVerificationResult($testName,
            "Cannot find function to create an XML parser. Please install a copy of php that has XML support built in."
        );
        return VERIFICATION_FAILED;
    }

}


/* Verify that we have mysql
 */
function _verifyMySql() {
    $testName = 'MySQL support';
    if (function_exists('mysql_pconnect')) {
        _showVerificationResult($testName);

        return VERIFICATION_SUCCEEDED;
    } else {
        _showVerificationResult($testName,
            "Cannot find mysql functions. You must have a version of PHP that can talk to MySQL to run " . PRODUCT
        );
        return VERIFICATION_FAILED;
    }

}

/* Verify that we've got magic quotes turned off
 */
function _verifyMagicQuotes() {
    $testName = '<tt>magic_quotes_gpc</tt> is off';

    if (get_magic_quotes_gpc()) {
        _showVerificationResult($testName,
            '<tt>magic_quotes_gpc</tt> is currently <b>on</b>. Please turn it
            off in your <a href="http://www.php.net/manual/en/configuration.php#configuration.file" target="_blank">initialization files</a>.'
        );
        return VERIFICATION_FAILED;
    } else {
        _showVerificationResult($testName);
        return VERIFICATION_SUCCEEDED;
    }
}


function verify_environment_show() {
    _showHeaders();
    ?>

<h1>Verifying runtime environment</h1>


<p>This step verifies that your web server is properly configured to run
<?php print PRODUCT;?>. You will not be able to progress to further steps until the
environment has been properly configured.
</p>
<table align="center">
    <th>
        <tr class="verificationTableHeader">
            <td>Test</td>
            <td>Result</td>
        </tr>
    </th>
    <?php
    $result = VERIFICATION_SUCCEEDED;
    $result |= _verifyVersion();
    $result |= _verifyMagicQuotes();
    $result |= _verifyMySql();
    $result |= _verifyXMLSupport();
    ?>
</table>

    <?php
    if (VERIFICATION_SUCCEEDED == $result) {
        ?>
        <p>Your webserver is properly configured to run <?php print PRODUCT;?>. Please
        continue.</p>
        <?php
    } else {
        ?>
        <p>Your webserver is not properly configured to run <?php print PRODUCT;?>. We
        recommend that you correct these errors before continuing. </p>
        <?php
    }

    ?>

    <p><div align="center">
        <a href="?step=<?php print getNextStep()?>">Next step</a>
    </div></p>
    <?php
}

function verify_environment_run() {
    return STEP_OK;
}

/////////////////////////////////////////////////////////////////////////////
//
//
//
//  setup_config - Sets up config files.
//
//
//
/////////////////////////////////////////////////////////////////////////////

function _removeTrailingSlash($string) {
    return preg_replace('/\/\s*$/', '', $string);
}

/** Guesses at the name of the directory BE is runnin' in. */
function _guessBASE_DIR() {
#    $regex = '/^(.*[\/])[^\/]/';
#    preg_match($regex, dirname($_SERVER['SCRIPT_FILENAME']), $match);
#    $baseDir = dirname($_SERVER['SCRIPT_FILENAME']);

$path = explode('/',dirname($_SERVER['SCRIPT_FILENAME']));
for ($i=0 ; $i+1 < count ($path) ; $i++) {
   $newPathArray[$i] = $path[$i];
}
$newPathVar  = implode('/', $newPathArray);
// echo "22 $newPathVar 22";
   return _removeTrailingSlash($newPathVar);
}

function _guessCLASS_DIR() {
   return _guessBASE_DIR() . '/class';
}

/** Jumping the gun here a bit as this will only be required
 ** when we jump to a psl8 base. */
function _guessINCLUDE_DIR() {
#    $regex = '/^(.*[\/])[^\/]/';
#    preg_match($regex, dirname($_SERVER['SCRIPT_FILENAME']), $match);
#    $baseDir = dirname($_SERVER['SCRIPT_FILENAME']);

$path = explode('/',dirname($_SERVER['SCRIPT_FILENAME']));
for ($i=0 ; $i+1 < count ($path) ; $i++) {
   $newPathArray[$i] = $path[$i];
}

$newPathVar  = implode('/', $newPathArray) ."/include";
// echo "22 $newPathVar 22";
    return _removeTrailingSlash($newPathVar);
}

/** Guesses at the name of the public directory BE is using. */
function _guessPUBLIC_DIR() {
#    $regex = '/^(.*[\/])[^\/]/';
#    preg_match($regex, dirname($_SERVER['SCRIPT_FILENAME']), $match);
#    $baseDir = dirname($_SERVER['SCRIPT_FILENAME']);

$path = explode('/',dirname($_SERVER['SCRIPT_FILENAME']));
for ($i=0 ; $i < count ($path) ; $i++) {
   $newPathArray[$i] = $path[$i];
}
$newPathVar  = implode('/', $newPathArray);
// echo "22 $newPathVar 22";
    return _removeTrailingSlash($newPathVar);
}


/** Guesses at the name of the table directory BE uses. */
function _guessTABLE_PATH() {
#    $regex = '/^(.*[\/])[^\/]/';
#    preg_match($regex, dirname($_SERVER['SCRIPT_FILENAME']), $match);
#    $baseDir = dirname($_SERVER['SCRIPT_FILENAME']);

$path = explode('/',dirname($_SERVER['SCRIPT_FILENAME']));
for ($i=0 ; $i+1 < count ($path) ; $i++) {
   $newPathArray[$i] = $path[$i];
}
$newPathVar  = implode('/', $newPathArray) . TABLE_PATH;
// echo "22 $newPathVar 22";
    return _removeTrailingSlash($newPathVar);
}

/** Guesses the name of the directory that BE is accessable from (via the web
 * server)
 */
function _guessROOT_URL() {
    $url = parse_url($_SERVER['REQUEST_URI']);
    return _removeTrailingSlash(dirname($url['path']));
}

function _guessDomain() {
    $url = explode('.', $_SERVER["HTTP_HOST"]);
    if (count($url) == 5 ) {
       $domain = $url[1] . '.' . $url[2] . '.' . $url[3] . '.' . $url[4];
    } elseif (count($url) == 4 ) {
       $domain = $url[1] . '.' . $url[2] . '.' . $url[3];
    } elseif (count($url) == 3 ) {
       $domain = $url[1] . '.' . $url[2];
    } else {
       $domain = $_SERVER["HTTP_HOST"];
    }
    return _removeTrailingSlash($domain);
}

function _guessSubDomain() {
    $url = explode('.', $_SERVER["HTTP_HOST"]);
    if (count($url) > 2) {
       $subdomain = $url[0] . '.';
    } else {
       $subdomain = '';
    }
    return _removeTrailingSlash($subdomain);
}

function setup_config_name() {
    return 'Set up configuration file';
}

function setup_config_show() {
    _showHeaders();
    ?>
<h1>Set up configuration files</h1>


<p>Set up the basic configuration for <?php print PRODUCT;?> by filling out this form.
Values, where set, have been guessed. They are probably correct, but if you
know better, please enter proper values.</p>

<form action="?" method="post">
<input type="hidden" name="op" value="run" />
<input type="hidden" name="step" value="setup_config" />
<table>
    <tr class="banner">
        <td colspan=2>Server setup</td>
    </td>

    <!-- Base directory -->
    <!-- TODO: Change to INCLUDE_DIR after upgrade to psl8 base -->
    <tr class="setVal" valign="top">
        <td width="30%" class="name">Base directory</td>
        <td class="field"><input type="text" name="var[BASE_DIR]" value="<?php print _guessBASE_DIR()?>" /><br />
            The base directory is the root of your <?php print PRODUCT;?>
            install. Generally this should be under your public directory
            (public_html).
        </td>
    </tr>

    <!-- Class directory -->
    <tr class="setVal" valign="top">
        <td width="30%" class="name">Class directory</td>
        <td class="field"><input type="text" name="var[CLASS_DIR]" value="<?php print _guessCLASS_DIR()?>" /><br />
            This is where the class directory is located, this is usually within the Base Directory.
        </td>
    </tr>

    <!-- Public directory -->
    <tr class="setVal" valign="top">
        <td width="30%" class="name">Public directory</td>
        <td class="field"><input type="text" name="var[PUBLIC_DIR]" value="<?php print _guessPUBLIC_DIR()?>" /><br />
            The public directory is the directory which contains this script.
        </td>
    </tr>

    <!-- Root url -->
    <tr class="setVal" valign="top">
        <td class="name">Root Path</td>
        <td class="field"><input type="text" name="var[ROOT_DIR]" value="<?php print _guessROOT_URL()?>" /><br />
            Leave this blank unless you are setting this in a sub-directory of your site.
            If you wanted to install this to http://www.back-end.org/justATest/ you would put justATest here.
        </td>
    </tr>

    <!-- Root dir -->
    <tr class="setVal" valign="top">
        <td class="name">Root Domain</td>
        <td class="field"><input type="text" name="var[ROOT_DOMAIN]" value="<?php print _guessDomain()?>" /><br />
            Put the domain where you are installing it here.
            If you wanted to install this to
            http://www.back-end.org/justATest/ you would put back-end.org
            here.

            <div class="warning"><span class="eyeCatcher">Note:</span> a root domain of localhost will not presently allow
            you to login.  Please see the INSTALL.html file in the Back-End root directory for more information.</div>
        </td>
    </tr>

    <!-- Root sub dir -->
    <tr class="setVal" valign="top">
        <td class="name">Root Sub-Domain</td>
        <td class="field"><input type="text" name="var[ROOT_SUB_DOMAIN]" value="<?php print _guessSubDomain()?>" /><br />
            This should be something like www. or just left blank if not used.
            <br />
        </td>
    </tr>


    <!-- Root ranomization -->
    <tr class="setVal" valign="top">
        <td class="name">Random Phrase for Security</td>
        <td class="field"><input type="text" name="var[RANDOMIZATION]" /><br />
            If blank one will be added for you.
            <br />
        </td>
    </tr>


    <tr class="banner">
        <td colspan=2>Database setup</td>
    </td>

    <!-- Db host -->
    <tr class="setVal" valign="top">
        <td class="name">Database host</td>
        <td class="field"><input type="text" name="var[DB_HOST]" value="localhost" /><br />
            The name of the computer that your database is lives on. If it is
            the same computer that <?php print PRODUCT;?> lives on, just leave it as
            <tt>localhost</tt>.
        </td>
    </tr>

    <!-- Db database -->
    <tr class="setVal" valign="top">
        <td class="name">Database name</td>
        <td class="field"><input type="text" name="var[DB_DB]" value="be7" /><br />
            The name of the database that <?php print PRODUCT;?> should use.
        </td>
    </tr>

    <!-- Db user -->
    <tr class="setVal" valign="top">
        <td class="name">Database user</td>
        <td class="field"><input type="text" name="var[DB_USER]" value="user" /><br />
            The name of the user that <?php print PRODUCT;?> should use to connect to
            the database.
        </td>
    </tr>

    <!-- Db pass -->
    <tr class="setVal" valign="top">
        <td class="name">Database password</td>
        <td class="field"><input type="text" name="var[DB_PASSWORD]" value="password" /><br />
            The name of the password that <?php print PRODUCT;?> should use to connect
            to the database.
        </td>
    </tr>


</table>
<input type="submit" value="Set values" />
</form>
    <?php
}


/** Verifies that we can log into the database with the given
 * username/password
 */
function _verifyDatabaseLogin($db_host, $db_name, $db_user, $db_pass) {
    @$db = mysql_connect($db_host, $db_user, $db_pass);

    $errno = mysql_errno();
    if ($db === FALSE || 0 != $errno) {
        _showHeaders();
        ?>
        <h1>Could not connect to database</h1>

        A connection could not be established with the named fields. The error
        returned was <?php print $errno; ?>: <i>"<?php print
        mysql_error();?>"</i>.


        This means that
        <?php
        switch($errno) {
            case(1045):
                print "<p>The username and password supplied don't work. </p>";
                print "<p>The received values were <nobr>username: \"$db_user\",
                password: \"$db_pass\".</nobr> Supply a valid username and password.</p>";
                break;

            case(2005):
                print "<p>The MySQL server could not be located. Verify that the
                database is accessible from the machine the webserver is
                running on.</p>";
                break;

        }

        return STEP_FAULT;
    }

    mysql_close($db);

    return STEP_OK;
}

function setup_config_run() {
    $vars = array();

    foreach ($_POST['var'] AS $name => $value) {
        $name = clean($name);
        $vars[$name] = clean($value);
    }

    // Generate a ranom value
    if (!isset($vars['RANDOMIZATION']) || empty($vars['RANDOMIZATION'])) {
       $vars['RANDOMIZATION'] = rand();
    }

    $db_loginResult = _verifyDatabaseLogin(
            clean($_POST['var']['DB_HOST']),
            clean($_POST['var']['DB_DB']),
            clean($_POST['var']['DB_USER']),
            clean($_POST['var']['DB_PASSWORD'])
    );

    if (STEP_OK != $db_loginResult) {
        return $db_loginResult;
    }

    $result = _filterFile(CONFIG_IN, CONFIG_OUT, $vars);
    if (null == $result) {
        return STEP_OK;
    }

    /* Display error whines */
    _showHeaders();
    switch ($result) {
        case(FF_READ_FAIL):
        ?>
<h1>Could not read blank configuration file</h1>

<p>
I was not able to read the blank configuration file named <tt><?php print CONFIG_IN?></tt>.
</p>
        <?php
            if (is_readable(CONFIG_IN)) {
                ?>
                Strange, because the file exists and is readable. I am unable
                to suggest a course of action.
                <?php
            }
            else if (file_exists(CONFIG_IN) && !is_readable(CONFIG_IN)) {
                ?>
                Please make the file <tt><?php print CONFIG_IN?></tt> readable
                to the webserver process.
                <?php
            }
            else if (!file_exists(CONFIG_IN)) {
                ?>
                I cannot find the file <tt><?php print CONFIG_IN?></tt>. This
                indicates that I am running in the wrong location, or that
                <tt><?php print CONFIG_IN?></tt> is missing. Please locate
                the file and put it in the directory
                <tt><?php print dirname($_SERVER['SCRIPT_FILENAME'])?></tt>.
                <?php
            }
            else {
                ?>
                I am unable to determine why I cannot read from
                <tt><?php print CONFIG_IN?></tt>.
                <?php
            }
            break;
        case(FF_WRITE_FAIL):
            ?>
<h1>Could not write configuration file</h1>

<p>
I was not able to write the configuration file named <tt><?php print CONFIG_OUT?></tt>.
</p>
<?php
            /* Attempt to diagnose the problem */
            if (is_writable(CONFIG_OUT)) {
                ?>
                Strange, because the file exists and is writable.
                <?php
            }
            else if (file_exists(CONFIG_OUT)) {
                ?>
                The file <tt><?php print CONFIG_OUT?></tt> exists, but I am unable to
                write to it. Please set it to be writable by the webserver.
                <?php
            }
            else if (!file_exists(CONFIG_OUT)) {
                ?>
                Please create the file <tt><?php print CONFIG_OUT?></tt>, and set it to
                be writable by the webserver. That will enable me to write the
                contents of the new config file to it.
                <?php
            }
            else if (!is_string(CONFIG_OUT) || strlen(CONFIG_OUT) < 1) {
                ?>
                The name of the configuration file doesn't seem to be
                available to me.
                <?php
                $catValue = 'meow';
                define('CAT', $catValue);
                if (CAT != $catValue) {
                    ?>
                    I don't seem to be able to define constants. This suggests
                    that there is something very wrong with your version of
                    php.
                    <?php
                }
            }
            else {
                ?>
                I am unable to determine why I cannot write to this file.
                <?php
            }

            break;
        default:
            print ("Unknown return value: " . $result);
    }

    ?>

<p>
Please try to remedy this problem and then reload this page. Alternately, you should be able to save the following (under the line) to a text file called config.ini.php and upload it to your public directory on your server.
</p>

    <?php
    global $modifiedConfigFile;
	 echo "<p><hr /><pre>" . $modifiedConfigFile . "</pre></p>";

    return STEP_FAULT;
}


/////////////////////////////////////////////////////////////////////////////
//
//
//
//  setup_database - Sets up database. Installs files.
//
//
//
/////////////////////////////////////////////////////////////////////////////


function setup_database_name() {
    return 'Set up database';
}

function setup_database_show() {
    $vars = parse_ini_file(CONFIG_OUT);
    $vars['table_filepath'] = null;
    _showHeaders();
    ?>
<h1>Initialize the database</h1>

<p></p>
<form action="?" method="post">
<input type="hidden" name="step" value="setup_database" />
<input type="hidden" name="op" value="run" />

<table>

<?php
$db = mysql_connect($vars['DB_Host'], $vars['DB_User'], $vars['DB_Password']);
$db_list = mysql_list_dbs($db);
$tableAlreadyExists = false;
while ($row = mysql_fetch_object($db_list)) {
    if ($vars['DB_Database'] == $row->Database)
    	$tableAlreadyExists = true;
}
$result = true;
if($tableAlreadyExists) {
  /* Use the database */
  $result = mysql_query(
            'USE `' . mysql_escape_string($vars['DB_Database']) . '`'
  );

  if (mysql_errno() != 0) {
     _setError(
         "Could use database " . $vars['DB_Database'] . " as "
         . $user['name'] . ' (pass: "' . $user['pass'] .'") -- Server said '
         . mysql_error(),
         __LINE__
     );
     return STEP_ERROR;
  }


  $result = mysql_query(
     "SELECT id FROM psl_block_type where name = 'page' "
  );

  if (!$result) {
     $tableAlreadyExists = false;
  }

}
if (!$tableAlreadyExists) {
?>
    <tr>
        <td colspan=2>
    <?php
    if($result) {
       print PRODUCT;?> needs to have the database configured before it can run. It
will create a database named <tt><?php print $vars['DB_Database']?></tt>. Fill
in the
following information to allow <?php print PRODUCT;?> to create that database and
the necessary tables inside of it.
     <?php
     } else {
        print PRODUCT;?> needs to have the database configured before it can run. It
will use the database named <tt><?php print $vars['DB_Database']?></tt>. Fill
in the
following information to allow <?php print PRODUCT;?> to use that database and create
the necessary tables inside of it.
     <?php
     } ?>
     </td>
    </td>

    <tr class="banner">
        <td colspan=2>Privileged user information</td>
    </td>

    <tr>
        <td colspan=2>
	    <?php if($result) { ?>
            Enter the name and password of a database user that has a
            sufficient access level to create a database, and then create
            tables inside of it.
            <?php } else { ?>
            Enter the name and password of a database user that has a
            sufficient access level to use the database, and then create
            tables inside of it.
	    <?php } ?>
            <p>
            This information will <i>not</i> be stored after this program has completed.
            </p>
            <table align="center">
                <tr class="setVal" valign="top">
                    <td class="name">User name</td>
                    <td class=""><input type="text" name="user[name]" value="" /></td>
                </tr>

                <tr class="setVal" valign="top">
                    <td class="name">Password</td>
                    <td class="value"><input type="text" name="user[pass]" value="" /></td>
                </tr>
                <!-- DB table location -->
                <tr class="setVal" valign="top">
                    <td class="name">Database schema directory</td>
                    <td class="field"><input type="text" name="user[table_path]" value="<?php print _guessTABLE_PATH() ?>"><br>
                      Path to the directory containing the database schema files.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="banner">
        <td>Additional Data to install</td>
    </tr>

    <tr>
        <td>
            <p>Do you wish to include sample information? This information shows
            off the capabilities of <?php print PRODUCT;?>, and is recommended to users
            who are evaluating <?php print PRODUCT;?>.</p>

            <div align="center">
            <label>
                <input type="checkbox" checked="checked" name="includeSample" />
                Yes, include sample data
            </label>
            </div>
        </td>
    </tr>
    <tr class="banner">
        <td>Set Adminstrator User Name and Password</td>
    </tr>
    <tr>
        <td>
            <p>Enter the name and password that will be used to administer <?php print PRODUCT;?>.</p>
            <div align="center">
            <table align="center">
                <tr class="setVal" valign="top">
                    <td class="name">Admin name</td>
                    <td class=""><input type="text" name="user[adminname]" value=""></td>
                </tr>

                <tr class="setVal" valign="top">
                    <td class="name">Admin Password</td>
                    <td class="value"><input type="password" name="user[adminpass]" value=""></td>
                </tr>
            </table>
            </div>
        </td>
    </tr>

    </table>
    <? if(!$result) {
       echo "<input type=\"hidden\" name=\"do_not_create\" value=\"alreadyExists\">";
    } ?>
    <input type="submit" value="Set values">
    </form>
<?php
} else {


  /* Use the database */
  $result = mysql_query(
            'USE `' . mysql_escape_string($vars['DB_Database']) . '`'
  );
  if (mysql_errno() != 0) {
     _setError(
         "Could use database " . $vars['DB_Database'] . " as "
         . $user['name'] . ' (pass: "' . $user['pass'] .'") -- Server said '
         . mysql_error(),
         __LINE__
     );
     return STEP_ERROR;
  }


  $result = mysql_query(
     "SELECT id FROM psl_block_type where name = 'page' "
  );

  if (!$result) {
        print "DB Error, could not query block_types\n";
        print 'MySQL Error: ' . mysql_error();
        exit;
  }

  if (!($row = mysql_fetch_row($result))) {
         echo "<tr class=\"banner\"><br>\n";
         echo "<td colspan=2>Database Needs Upgrade</td><br>\n";
         echo "</tr><br>\n";
         echo "<tr><br>\n";
         echo "<td colspan=2><br>\n";
         echo "Database should be upgraded to current configuration.<br>\n";
         echo "</td><br>\n";
         echo "</tr><br>\n";
  } else {
     ?>
     <tr class="banner">
        <td colspan=2>Database already exists</td>
    </tr>
    <?php
  }

echo "<input type=\"hidden\" name=\"user[name]\" value=\"" . $vars['DB_User'] ."\">";
echo "<input type=\"hidden\" name=\"user[pass]\" value=\"" . $vars['DB_Password'] ."\">";
echo "<input type=\"hidden\" name=\"user[table_filepath]\" value=\"" . $vars['table_filepath'] ."\">";
echo "<input type=\"hidden\" name=\"do_not_create\" value=\"alreadyExists\">";

?>

</table>
<input type="submit" value="Continue" />
</form>
<?php
 }
}

function setup_database_run() {
    /* Get the name of the database */
    $vars = parse_ini_file(CONFIG_OUT);

    $user = clean($_REQUEST['user']);

    /* Connect to the database */
    $db = mysql_connect($vars['DB_Host'], $user['name'], $user['pass']);
    if (mysql_errno() != 0) {
        _showHeaders();
        print "<h1>Could not log into mysql</h1>";
        ?>
        <p>I could not log in to your mysql database. </p>
        <?php
        _setError(
            "Could not login to " . $vars['DB_Host'] . " as "
            . $user['name'] . ' (pass: "' . $user['pass'] .'") -- Server said '
            . mysql_error(),
            __LINE__
        );
        return STEP_ERROR;
    }


    /* Create the database */
    if (!isset($_REQUEST['do_not_create'])) {
        $result = mysql_query(
                "CREATE DATABASE " . mysql_escape_string($vars['DB_Database'])
        );
        if (mysql_errno() != 0) {
            _showHeaders();
            print "<h1>Could not create database <tt>" . $vars['DB_Database']
                . "</tt></h1>";
            if (mysql_errno() == 1007) {
                /* Construct the 'no-create' url */
                $noCreate = getCurrentStep() . '&op=run'
                    . '&user[pass]=' . urlencode($user['pass'])
                    . '&user[name]=' . urlencode($user['name'])
                    . '&user[adminpass]=' . urlencode($user['adminpass'])
                    . '&user[adminname]=' . urlencode($user['adminname'])
                    . '&user[table_path]=' . urlencode($user['table_path'])
                    . '&do_not_create=yup'
                ?>
                <p>While trying to create the database, I received a mysql error
                of <tt><?php print mysql_errno()?></tt>
                (<tt><?php print mysql_error()?></tt>), which suggests
                that there is already a database of this name. </p>


                <p>
                You may either:
                </p>
                <ul>
                    <li>
                        <a href="?step=<?php print $noCreate?>">
                            initialize <?php print PRODUCT;?> to use this database
                        </a> (note: will fail if <?php print PRODUCT;?> tables
                        already exist in this database)</li>
                    <li>Import the data directly</li>
                </ul>
                <?php
                return STEP_FAULT;
            }
            else {
                ?>
                An unknown error occured during database creation:
                <p>
                <tt><?php print mysql_errno()?> (<?php print mysql_error()?>)</tt>.
                </p>

                <p>
                Please correct it and reload this page.
                </p>
                <?php
                return STEP_FAULT;
            }
        }
    }


    /* Use the database */
    $result = mysql_query(
            'USE `' . mysql_escape_string($vars['DB_Database']) . '`'
    );
    if (mysql_errno() != 0) {
        _setError(
            "Could use database " . $vars['DB_Database'] . " as "
            . $user['name'] . ' (pass: "' . $user['pass'] .'") -- Server said '
            . mysql_error(),
            __LINE__
        );
        return STEP_ERROR;
    }

    $result = mysql_list_tables($vars['DB_Database']);

    if (!$result) {
        print "DB Error, could not list tables\n";
        print 'MySQL Error: ' . mysql_error();
        exit;
    }

    while ($row = mysql_fetch_row($result)) {
        if ($row[0]=='active_sessions') {

            // Perhaps this should give the user the option of nixing their DB & starting afresh? - mg
            return STEP_OK;

        }
    }

    mysql_free_result($result);


    /* Run the SQL scripts. */
    $files = array(
            $user['table_path'] . '/BE_core.sql',
            $user['table_path'] . '/BE_actions/add_action_tables.sql',
            $user['table_path'] . '/PET_petition/add_petition_tables.sql'
            # $user['table_path'] . '/' . TABLE_CORE
    );

    if (isset($_POST['includeSample'])) {
        $files[] = $user['table_path'] . '/BE_exampleData.sql';
        $files[] = $user['table_path'] . '/BE_actions/populate_action_values.sql';
        $files[] = $user['table_path'] . '/PET_petition/populate_petition_values.sql';
        # $files[] = $user['table_path'] . '/' . TABLE_SAMPLE;
    }

#echo "Importing Files";print_r($files);

    foreach ($files as $file) {
        /* Load the file */
        $fileText = '';
        if (!loadFile($file, $fileText)) {
            _setError("Could not load SQL file " . $file, __LINE__);
        }

        /* Break the file up into statements */
        $sqlCmds = array();
        PMA_splitSqlFile($sqlCmds, $fileText);

        /* Run each statement */
        foreach ($sqlCmds as $sqlCmd) {
#echo ". ";
            $result = mysql_query($sqlCmd);
            if (mysql_errno() != 0) {
                _setError(
                    "SQL from <b>$file</b> failed: <hr /><pre>$sqlCmd</pre>"
                    . "MySQL said: <b>" . mysql_error() . "</b><hr />"
                    . "This is an internal error in the installation"
                    . " process. You have not done anything wrong. "
                    . " Please contact the " . PRODUCT . " development list.",
                    __LINE__
                );
                return STEP_ERROR;
            }
        }
    }

    if(!isset($_REQUEST['user']['adminname']) || !isset($_REQUEST['user']['adminpass'])) {
       _setError(
                    "Please set the administrator user name and password.",
                    __LINE__
                );

       return STEP_ERROR;
    } else {
       $sqlCmd = "UPDATE psl_author
                  SET author_name = '" . mysql_escape_string($_REQUEST['user']['adminname'])."',
                      author_realname = '". mysql_escape_string($_REQUEST['user']['adminname']) ."',
                      password = MD5('". mysql_escape_string($_REQUEST['user']['adminname']).":".mysql_escape_string($_REQUEST['user']['adminpass'])."')
                  WHERE author_id = '1'";
       $result = mysql_query($sqlCmd );
        if (mysql_errno() != 0) {
                _setError(
                    "SQL failed: <pre>$sqlCmd</pre><br>Server said: "
                    . mysql_error(),
                    __LINE__
                );
                return STEP_ERROR;
        }

    }
    return STEP_OK;
}


/////////////////////////////////////////////////////////////////////////////
//
//
//
//  redirect - Setup is complete. Congradulate the user and connect.
//
//
//
/////////////////////////////////////////////////////////////////////////////

function redirect_name() {
    return 'Finished';
}

function redirect_show() {
    _showHeaders();
    ?>
<h1>Configuration complete</h1>


<p>
The configuration of <?php print PRODUCT;?> is complete.
</p>

<div align="center">
    <a href="index.php">Start using <?php print PRODUCT;?></a>
</div>
    <?php
}



/////////////////////////////////////////////////////////////////////////////
//
//
//
//  Helper functions.
//
//
//
/////////////////////////////////////////////////////////////////////////////




function _showHeaders() {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Back-End CMS Configurator</title>
    <style><!--
    body {   margin: 0px; padding: 0px; font-family: arial, geneva, helvetica, sans-serif; color: #000; background-color: #E4E4D2; }
    }
    .verificationTableHeader {
        background: #ccc;
        font-weight: bolder;
    }
    /* For the old steps */
    a.step:link {
        color: #999;
        text-decoration: line-through;
    }
    a.step:visited {
        color: #999;
        text-decoration: line-through;
    }
    a.step:active {
        text-decoration: underline;
    }
    a.step:hover {
        color: #444;
        text-decoration: underline;
    }
    /* For the pass/fail stuff when we verify the environment */
    .success {
        background: #9e9;
    }
    .failure {
        background: #e99;
    }
    td.name {
        font-weight: bold;
        text-align: right;
    }
    td.field input {
        width: 100%;
    }
    td.value input {

    }
    .banner {
        font-weight: bold;
        font-size: 120%;
        background: #ccc;
    }
    .setVal {
    }
    tr.setVal td {
        padding-bottom: 1em;
    }

    div.warning {
        padding-top: .5em;
    }

    div.warning span.eyeCatcher {
        font-weight: bold;
        color: red;
    }

    --></style>
</head>
<body>
<div style="text-align:center"><img src="./images/logo1.gif" alt="Back-End Configurator"></div>
<table width="100%" background="#ccc">
<?php
    /* Display position in the steps */
    global $step;
    global $steps;

    $doneStep = true; // Have we done this iteration step?
    $cellWidth = abs(100/sizeof($steps)) . "%"; // Width of table cells.

    foreach ($steps as $eachStep) { // I know. bad name.
        $fn = $eachStep . '_name';
        $name = $fn();
        if ($eachStep == $step) {
            /* Current step. Display is prominently. */
            $doneStep = false;
            print "<td width=\"$cellWidth\" style=\"font-size: smaller; background: #ccc; text-decoration: underline\">&middot; $name</td>";
        }
        else {
            if ($doneStep) {
                /* An old step that we've already done. */
                ?>
                <td width=\"<?php print $cellWidth?>\" style='font-size: smaller; background: #ddd;'>&middot;
                    <a href="?step=<?php print $eachStep?>" class="step">
                        <?php print $name?>
                    </a>
                </td>
                <?php
            }
            else {
                /* An new step that we've haven't done yet. */
                print "<td width=\"$cellWidth\" style='font-size: smaller; background: #ddd; color: #666;'>&middot;$name</td>";
            }
        }

    }
?>
</table>

<?php
}


/* Filters a file file, setting variables as it goes.
 *
 * If it succeeds, null is returned. Otherwise a string describing the error
 * is returned.
 */
function _filterFile($fileIn, $fileOut, $variables) {

   global $modifiedConfigFile;

    if (!$in = @fopen($fileIn, 'r')) {
        return FF_READ_FAIL;
    }

    if (!$out = @fopen($fileOut, 'w')) {
        while (!feof ($in)) {
            // $buffer = fgets($in, 4096);
            $buffer = fgets($in);
            foreach ($variables as $regex => $replacement) {
                $buffer = str_replace('<' . $regex . '>', $replacement, $buffer);
            }
            $modifiedConfigFile .= htmlentities($buffer);
        }

        return FF_WRITE_FAIL;
    }

    while (!feof ($in)) {
            $buffer = fgets($in, 4096);
            foreach ($variables AS $regex => $replacement) {
                $buffer = str_replace('<' . $regex . '>', $replacement, $buffer);
                // $buffer = str_replace($regex, $replacement, $buffer);
            }
            fwrite($out, $buffer, strlen($buffer));
    }

    fclose($in);
    fclose($out);

}


/* Loads a file from disk.
 *
 * Stores it into $text. Returns true if the operation succeeds, false
 * otherwise.
 */
function loadFile($filename, &$text) {
    $fp = fopen($filename, 'r');
    if (!$fp) {
        return false;
    }
    $filesize = filesize($filename);
    if ($filesize <= 0) {
        return false;
    }

    $text = fread($fp, $filesize);
    fclose($fp);

    return true;

}


/**
 * Stolen from phpMyAdmin on Apr. 24, 2003. Released under the GPL.
 *
 * Removes comment lines and splits up large sql files into individual queries
 *
 * Last revision: September 23, 2001 - gandon
 *
 * @param   array    the splitted sql commands
 * @param   string   the sql commands
 *
 * @return  boolean  always true
 *
 * @access  public
 */
function PMA_splitSqlFile(&$ret, $sql)
{
    $sql 	      = str_replace('--', '#', $sql);
    $sql          = trim($sql);
    $sql_len      = strlen($sql);
    $char         = '';
    $string_start = '';
    $in_string    = FALSE;
    $time0        = time();

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                } // end if...elseif...else
            } // end for
        } // end if (in string)

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = substr($sql, 0, $i);
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        } // end else if (is delimiter)

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $string_start = $char;
        } // end else if (is start of string)

        // ... for start of a comment (and remove this comment if found)...
        else if ($char == '#'
                || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
            // starting position of the comment depends on the comment type
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
            // if no "\n" exits in the remaining string, checks for "\r"
            // (Mac eol style)
            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                ? strpos(' ' . $sql, "\012", $i+2)
                : strpos(' ' . $sql, "\015", $i+2);
            if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array if required and exit
                if ($start_of_comment > 0) {
                    $ret[]    = trim(substr($sql, 0, $start_of_comment));
                }
                return TRUE;
            } else {
                $sql          = substr($sql, 0, $start_of_comment)
                    . ltrim(substr($sql, $end_of_comment));
                $sql_len      = strlen($sql);
                $i--;
            } // end if...else
        } // end else if (is comment)

#            // ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
#            else if ($release < 32270
#                     && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
#                $sql[$i] = ' ';
#            } // end else if

        // loic1: send a fake header each 30 sec. to bypass browser timeout
        $time1     = time();
        if ($time1 >= $time0 + 30) {
        $time0 = $time1;
        header('X-pmaPing: Pong');
        } // end if
        } // end for

        // add any rest to the returned array
        if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
        $ret[] = $sql;
        }

        return TRUE;
        } // end of the 'PMA_splitSqlFile()' function

   /******************************************************************************
    function   : clean->removes nasty things that hurt databases
    Parameters : $dirty->string or array to clean up
    $allow_html->if true, then we don't convert HTML characters
    like < and > into &gt; and &lt;
    *******************************************************************************/
   function clean ($dirty, $allow_html = false) {
      if (empty($dirty)) {
         return NULL;
      }
      $clean = '';
      if (is_array($dirty)) {
         foreach ($dirty as $key => $val) {
            if (is_scalar($val)) { //PAC Hack for BE_History which passes arrays through GET
                  $clean[$key] = clean($val, $allow_html);
            }
         }
      } else {
         if ($allow_html) {
            $clean = str_replace('\'', '&#039;', (stripslashes($dirty)));
         } else {
            $clean = str_replace('\'', '&#039;', (htmlspecialchars(stripslashes($dirty))));
         }
      }

      return $clean;
   }

?>
