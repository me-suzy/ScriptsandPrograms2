<?php

// $Id: gpcs_vars.inc.php,v 1.21.2.1 2005/09/05 11:33:01 fgraf Exp $

//---------------------------------------------
// import of get,  post, cookie and session vars
//   independent of register_globals and magic_quotes_gpc
//   all 4 combinations of this ini settings have identical results.

//   The POST section shows how to import arrays. The other sections
//   must be changed in this way if arrays are expected.

function arr_addsl(&$item, $key) {
    $item = addslashes($item);
}

// only import the vars if register_globals is not activated 

if (ini_get('register_globals') != 1) {
    //--------------------------------------------
    // currently there are three reasons to do this:
    // a) session.auto_start=1
    // b) _POST should not set _GET
    // c) a superglobal should not be overwritten
    $gpcs_names = array( '_REQUEST',
                         '_GET',
                         '_POST',
                         '_COOKIE',
                         '_SESSION' );

    //--------------------------------------------
    // use _GET instead of HTTP_GET_VARS - it's
    // available since php 4.1 (december 2001), and
    // there is no HTTP_GET_VARS in php5 any more.
    if (isset($_GET)) {
        reset($_GET);
        foreach ($_GET as $key=>$value) {
            if (get_magic_quotes_gpc()) $$key = $value;
            else {
                if ( (is_array($value) && (!in_array($key, $gpcs_names))) ) {
                    array_walk($value, 'arr_addsl');
                    $$key = $value;
                }
                else $$key = addslashes($value);
            }
        }
    }

    if (isset($_POST)) {
        reset($_POST); $POST_VARS = array();
        foreach($_POST as $key=>$value) {
            if (get_magic_quotes_gpc()) $$key = $value;
            else {
                if ( (is_array($value) && (!in_array($key, $gpcs_names))) ) {
                    array_walk($value, 'arr_addsl');
                    $$key = $value;
                }
                else $$key = addslashes($value);
            }
            $POST_VARS[$key] = $$key;
        }
    }


    if (isset($_COOKIE)) {
        reset($_COOKIE);
        foreach ($_COOKIE as $key=>$value) {
            if (get_magic_quotes_gpc()) $$key = $value;
            else $$key = addslashes($value);
        }
    }
};

if (isset($path_pre) and $path_pre <> './' and $path_pre <> '../' and $path_pre <> '../../') {
    die('You are not allowed to do this');
}

// bypass soap request
if (!defined('soap_request')) {
    session_name(PHPR_SESSION_NAME);
    session_start();
    
    // make session fixation more work 
    @$session_crc = md5($_SERVER['HTTP_ACCEPT_CHARSET'].$_SERVER['HTTP_ACCEPT_ENCODING'].$_SERVER['HTTP_ACCEPT_LANGUAGE']);
    if (isset($_SESSION['_crc'])) {
        if ($_SESSION['_crc'] != $session_crc ) die(__('Your authentication is not valid any more due to a browser change.'));
    } else {
        $_SESSION['_crc'] = $session_crc;         
    }
}

if (ini_get('register_globals') != 1) {
    if(isset($_SESSION)) {
        reset($_SESSION);
        foreach (array_keys($_SESSION) as $key) {
            $GLOBALS[$key] =& $_SESSION[$key];
        }
    }
}
//--------------------------------------------
// Import of ONLY ONE uploaded file with the static handle 'userfile'.
//  Can be later improved to handle multifile upload.
// set variable $userfile to zero in order to avoid that it will be redirected ...
$userfile = '';
if (isset($_FILES['userfile'])){
    $userfile_name = $_FILES['userfile']['name'];
    $userfile_type = $_FILES['userfile']['type'];
    $userfile_size = $_FILES['userfile']['size'];
    $userfile = $_FILES['userfile']['tmp_name'];
}

//---------------------------------------------
// Import some used server/environment vars
$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
$PHP_SELF = $_SERVER['PHP_SELF'];

//---------------------------------------------
// Treatment for runtime-generated data (SQL, ecec()...)
// Result independent of magic_quotes_runtime.
function quote_runtime($x) {
    if (!get_magic_quotes_runtime()) {
        if (is_array($x)) array_walk($x, 'arr_addsl');
        else $x = addslashes($x);
    }
    return $x;
}

//---------------------------------------------
// register session vars independent of register_globals
// function call:  reg_sess_vars(array_of_varnames);
//       example:  reg_sess_vars(array("probe","test"));
function reg_sess_vars($sess_vars) {
    if (is_array($sess_vars)) {
        foreach ($sess_vars as $varname) {
            $_SESSION[$varname] =& $GLOBALS[$varname];
        }
    }
    else {
        $_SESSION[$sess_vars] = $GLOBALS[$sess_vars];
    }
}

//-----------------------------------------------
// unregister a session var independent of register_globals
// function call: unreg_sess_var(varname);
//       example: unreg_sess_var("probe");
function unreg_sess_var($varname) {
    if (!is_numeric($varname) and !is_string($varname)) return;
    unset($_SESSION[$varname]);
}

?>
