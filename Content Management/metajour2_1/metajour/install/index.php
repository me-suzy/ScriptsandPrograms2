<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jesper Laursen <jl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */
 
session_start();

/* Find and include config.php */
require('../config.php');
require('install_core.php');

function top() {
	$string = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>IPW METAjour installer</title>
<style type="text/css">
	body td th {
		font-family: verdana, arial, sans-serif;
		font-size: 9pt;
	}
	
	
	#content {
		width: 500px;
		border: 1px solid black;
		padding: 10px;
		background-color: #cccccc;
	}
	
	.headline {
		font-weight: bold;
	}
	
	.buttonbar {
		width: 464px;
		text-align: right;
		padding: 0px;
	}
	
    .returnmsg {
        color: red;
    }
    
	button {
		font-family: verdana, arial, sans-serif;
		font-size: 9pt;
		border: 1px solid #aaaaaa;
		padding: 2px;
		margin: 2px 0px 2px 4px;
		
	}
	
	input {
		font-family: verdana, arial, sans-serif;
		font-size: 9pt;
		border: 1px solid #aaaaaa;
		width: 275px;
	}
</style>
<script type="text/javascript" src="jsrs/jsrsCore.js"></script>

</head>
<body>
<table width="100%">
	<tr>
		<td align="center">
			<div id="content">
';

	return $string;
}

function bottom() {
	$string = '
</div>
</td>
</tr>
</table>
</body>
</html>
';
	return $string;
}

function printResult($name, $value, $result) {
    $html = '<tr>';
    $html .= '<th align="left">'.$name.':</th>';
	$html .= '<td align="left">'.$value.'</td>';
    $html .= sprintf('<td align="left" class="returnmsg">%s</td>', !$result ? 'Error':'<span style="color: green;">OK</span>');
	$html .= '</tr>';
    return $html;
}

function makeField($text, $field) {
    return '<tr><td>'.$text.'</td><td>'.$field.'</td></tr>';
}

function screen1() {
    global $returnmsg;
    
    $test = new Screen1;
    
	echo top();
	$url = $_SERVER['PHP_SELF'] . '?page=2';
	?>
	<p class="headline">Welcome to the installation of IPW METAjour</p>
    	<table width="100%">
        <?php
            echo printResult('Configfile exists', '', file_exists('../config.php'));
            echo printResult('No system tables exists', '', $test->notablesExists());
            echo printResult('Correct PHP-version', '', $test->correctPHPversion());
            echo printResult('Correct MySQL-version', '', $test->correctMySQLversion());            
        ?>
        </table>
    <?php
    if (!$test->haserror) {
    ?>
	<p class="buttonbar">    
    <button onclick="location.href='<?php echo $url; ?>'; return false;">Next</button></p>
	<?php
    }
	echo bottom();	
}

function screen2() {    
    global $system_path, $system_url, $CONFIG;    
	$next = $_SERVER['PHP_SELF'] . '?page=3';
	$prev = $_SERVER['PHP_SELF'] . '?page=1';
    
    $test = new Screen2($CONFIG);
    
	echo top();
	?>
	<p class="headline">Configure path and url</p>
    <table width="100%">
    <?php
        echo printResult('System path', $system_path, $test->checkSystemPath($system_path));
        echo printResult('System url', $system_url, $test->checkSystemUrl($system_url));
    ?>
    </table>
    <p class="headline">Database</p>
    <table width="100%">
    <?php
        echo printResult('Database program', '', $test->checkadodbexists($system_path));
        echo printResult('Database connection', '', $test->checkDatabaseConn($system_path));            
    ?>
    </table>
	<p class="buttonbar">
			<button onclick="location.href='<?php echo $prev; ?>'; return false;">Prev</button>
            <?php if (!$test->haserror) { ?> <button onclick="location.href='<?php echo $next; ?>'; return false;">Next</button><?php } ?>
	</p>
	<?php
	echo bottom();
}

function screen3() {
	$next = $_SERVER['PHP_SELF'] . '?page=4';
	$prev = $_SERVER['PHP_SELF'] . '?page=2';
    
    $test = new Screen3();
    
    echo top();    
    $test->createsql();
    ?>
	<p class="buttonbar">
			<button onclick="location.href='<?php echo $prev; ?>'; return false;">Prev</button>
            <?php if (!$test->haserror) { ?> <button onclick="location.href='<?php echo $next; ?>'; return false;">Next</button><?php } ?>
	</p>
	<?php
	echo bottom();
}

function Screen4() {
    global $system_path, $system_url, $CONFIG;   
    require_once('../ow.php');
    require_once('createsite.inc.php');
    
    $test = new Screen4();   
    echo top();
    print ('<p class="headline">Create site</p>');
    $data = $_POST;
    if ($data['step'] == '2') {
            $failure = false;
            if ($data['password'] != $data['passwordcheck']) {
                $result .= "Passwords mismatch<BR>";
                $failure = true;
            }
            if ($data['viewer_path'] == '') {
                $result .= "You must enter the absolute path to the documentroot<BR>";
                $failure = true;
            }
        
            if ($data['viewer_url'] == '') {
                $result .= "You must enter the URL to the website<BR>";
                $failure = true;
            }
        
            if ($data['name'] == '') {
                $result .= "You must enter a descriptive name of the site<BR>";
                $failure = true;
            }
        
            if ($data['username'] == '') {
                $result .= "You must enter a name for the administrator user account<BR>";
                $failure = true;
            }
    
            if (!$failure) {
                $site = createSite($data['username'], $data['password'], $data['viewer_path'], $data['viewer_url'], $data['name'], $data['language']);
                $result .= "<strong>Site has been created with number: ".$site."<BR>";
            }
        }
        
        if ($data['step'] != '2' || $failure) {
            $result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'?page='.$_GET['page'].'" enctype="multipart/form-data" style="margin: 0px; padding: 0px;">';
            $result .= '<input type="hidden" name="step" value="2">';   
            $result .= '<table style="text-align: left;">';
            $result .= makeField('Name of site','<input type="text" name="name" size=40 value="MyWebsite, Inc.">');
            $result .= makeField('Absolute path to documentroot','<input type="text" name="viewer_path" size=40 value="'.str_replace('/metajour/','/', $system_path).'">');
            $result .= makeField('URL to website','<input type="text" name="viewer_url" size=40 value="'.str_replace('/metajour/','/', $system_url).'">');
            $result .= makeField('Administrator username','<input type="text" name="username" value="administrator">');
            $result .= makeField('Administrator password','<input type="password" name="password">');
            $result .= makeField('Repeat password','<input type="password" name="passwordcheck">');
            $result .= makeField('Initial language','<select name="language"><option value="EN">English</option><option value="DA">Danish</option></select>');
            $result .= '<tr><td colspan="2" style="text-align: right;"><input id="submit1" name="submit1" type="submit" class="mformsubmit" value="Create"></td></tr>';
            $result .= '</table>';               
            $result .= '</form>';
        }
        
        echo $result;
    echo bottom();
}

switch($_GET['page']) {
	case '2':
		screen2();
		break;
    case '3':
		screen3();
		break;
    case '4':
		screen4();
		break;
	default:
		screen1();
}
?>
