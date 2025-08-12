<?php
// lib.inc.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: fgraf $
// $Id: lib.inc.php,v 1.233.2.12 2005/09/21 15:34:59 fgraf Exp $


/**
* translation of strings
* @author Alex Haslberger
* @param string $textid textstring to identify the entry in language file
* @return string
*/
if (!function_exists('__')) {
    function __($textid) {
        static $translated = array();
        if (!isset($GLOBALS['langua']) or empty($GLOBALS['langua'])) $GLOBALS['langua'] = 'en';
        if(!isset($translated[$GLOBALS['langua']])){
            include (dirname(__FILE__).'/../lang/'.$GLOBALS['langua'].'.inc.php');
            $translated[$GLOBALS['langua']] = $_lang;
        }
        return isset($translated[$GLOBALS['langua']][$textid]) ? $translated[$GLOBALS['langua']][$textid] : $textid;
    }
}


/**
 * Redirect external links to avoid session propagation by referer
 * Include redirect script
 *
 * @param array $ary
 * @return string
 */
function ob_replace_link($ary) {
    global $path_pre;
    $newlink = sprintf('%sbookmarks/bookmarks.php?lesezeichen=%s', $path_pre, urlencode($ary[3]));
    $return = sprintf('%s%s%s%s', $ary[1], $ary[2], $newlink, $ary[4]);
    return $return;
};

/**
 * Redirect external links to avoid session propagation by referer
 * do that using output buffering, if a transparent session is detected
 *
 * @param string $input
 * @return sring
 */
function ob_clean_referer($input) {
    if (SID != '') {
        return preg_replace_callback('#(<a.*href=)(["\'])(http.*)(\\2[^>]*>)#msiU', 'ob_replace_link', $input);
    } else {
        return $input;
    }
}

ob_start('ob_clean_referer');

// check whether $path_pre doesn't redirect to some outer place
// step 1)
if (isset($path_pre) and $path_pre <> './' and $path_pre <> '../' and $path_pre <> '../../') {
    die('You are not allowed to do this');
}

//***************
// include config
// fetch parameters from config.inc.php - could be placed in the PHProjekt root or two levels above = outside the webroot!
// only avoid including the config if the setup routine is active ...
if (!defined('setup_included')) {
    $config_path = $path_pre.'config.inc.php';
    // set config path for files in subdir
    $config_loaded = include_once($config_path);
    // if the config.inc.php file is not in the root directory, serch two levels above
    if (!$config_loaded) {
        $config_loaded2 = include_once('../../'.$config_path);
        // oh, it cna't be found there either? -> die with panic message
        if (!$config_loaded2) die("panic: config.inc.php doesn't exist!! Did you backup it after installation? ...<br />(If you run this tool for the first time: please read the file INSTALL in the PHProjekt root directory)");
    }
}



// ****************************
// set variables and contstants
// ****************************
// set include path.
$var_ini_set = ini_set('include_path', ini_get('include_path').':./:');
// parse transmitted variables
$include_path = $path_pre.'lib/gpcs_vars.inc.php';

include_once($include_path);


if(defined('PHPR_COMPATIBILITY_MODE') and PHPR_COMPATIBILITY_MODE == 1){
    constants_to_vars();
}

// change path if a script from a subdir is calling ...
$lang_path = $path_pre.'lang';
$lib_path  = $path_pre.'lib';
$img_path  = $path_pre.'img';
// set constant to ensure that the lib is included (especially for those who want to access a script directly)
define('lib_included', '1');

// define db_prefix
if (defined('PHPR_DB_PREFIX')) define('DB_PREFIX', PHPR_DB_PREFIX);
else define('DB_PREFIX', $db_prefix);


// ****************************
// set arrays
// ****************************
$helplink_map = array(
        'onlinemanual'     => 'Navigation_bar',
        'calendar'         => 'Calendar',
        'calendar_easy'    => 'Creat_an_easy_event',
        'modifying_events' => 'Modifying_events',
        'time_card'        => 'Time_card',
        'contact_manager'  => 'Contact_manager',
        'help_desk'        => 'Help_desk',
        'mail_client'      => 'Mail_client',
        'bookmarks'        => 'Bookmarks',
        'chat'             => 'Chat',
        'to_dos'           => 'To_dos',
        'notes'            => 'Notes',
        'resources'        => 'Resources',
        'projects'         => 'Projects',
        'file_storage'     => 'File_storage',
        'surveys'          => 'Surveys',
        'reminder'         => 'Reminder',
        'fulltextsearch'   => 'Fulltextsearch',
        'forum'            => 'Forum',
        'user_profiles'    => 'User_profiles',
        'import'           => 'Import',
        'settings'         => 'Settings',
        'fulltextsearch'   => 'Fulltextsearch',
);

$translated_helps = array(
        'de', 'en'
);

$available_holiday_files = array(
    'Deutschland.php', 'France.php', 'USA.php'
);

// *********************
// error and security 1
// *********************

// define the error level
if (!defined('PHPR_ERROR_REPORTING_LEVEL') or !PHPR_ERROR_REPORTING_LEVEL) error_reporting(4);
else error_reporting( E_ALL & ~E_NOTICE);

// initialize some global vars
$css_inc = array();
$js_inc  = array();
$he_add  = array();
$onload  = array();

// check and secure some special global vars for sorting, filtering, listing, ...
$up          = $_REQUEST['up']          = (int) $_REQUEST['up'];
$sort        = $_REQUEST['sort']        = qss($_REQUEST['sort']);
$sort_module = $_REQUEST['sort_module'] = xss($_REQUEST['sort_module']);
$filter      = $_REQUEST['filter']      = xss($_REQUEST['filter']);
$keyword     = $_REQUEST['keyword']     = xss($_REQUEST['keyword']);
$searchterm  = $_REQUEST['searchterm']  = xss($_REQUEST['searchterm']);
$getstring   = $_REQUEST['getstring']   = xss($_REQUEST['getstring']);
$page        = $_REQUEST['page']        = (int) $_REQUEST['page'];
$perpage     = $_REQUEST['perpage']     = (int) $_REQUEST['perpage'];
if (!in_array(strtolower($_REQUEST['direction']), array('asc', 'desc'))) {
    $_REQUEST['direction'] = '';
}
$direction = $_REQUEST['direction'];


// ****************
// language part 1
// ****************
$found = 0;

// language given? -> include language file
if (isset($langua)) include_once($lang_path.'/'.$langua.'.inc.php');

// determine language for login and -if no language is given in the db- further on
else {
    // determine language of browser
    $langua = getenv('HTTP_ACCEPT_LANGUAGE');
    include_once($lib_path."/languages.inc.php");

    // special patch for canadian users
    if (eregi('ca', $langua)) {
        if (eregi('en', $langua)) { $langua = 'en'; $found = 1; } // english canadian
        if (eregi('fr', $langua)) { $langua = 'fr'; $found = 1; } // french canadian
    }
    // special patch for user with konqueror :-)
    else if (eregi('queror', $langua)) { $langua = 'en'; $found = 1; }
    // otherwise check if language is available
    else {
        if (isset($languages) and isset($languages[$langua])) {
            $found = 1;
        }
    }
    // include the found language
    if ($found) include_once($lang_path.'/'.$langua.'.inc.php');
    // nothing found? -> take english
    else { $langua = 'en'; include_once($lang_path.'/en.inc.php'); }
}
// *********************
// error and security 2
// *********************
// avoid this d... error warning since it does not affect the scritps here
$var_ini_set = ini_set('session.bug_compat_42', 1);
$var_ini_set = ini_set('session.bug_compat_warn', 0);


// limit session to a certain time [minutes]
if (defined('PHPR_SESSION_TIME_LIMIT') and PHPR_SESSION_TIME_LIMIT) {
    if (!$sess_begin) {
        $sess_begin = time();
        $_SESSION['sess_begin'] =& $sess_begin;
    }
    else {
        $now = time();
        if (($now - $sess_begin) > (PHPR_SESSION_TIME_LIMIT*60)) {
            session_unset();
            $indexpath = $path_pre.'index.php';
            // append return path to redirect the user to where he wanted to go
            $return_path = urlencode($_SERVER['REQUEST_URI']);
            die ("<a href='$indexpath?return_path=$return_path' target='_top'>".__('Session time over, please login again')."!</a>");
        }
        else {
            $sess_begin = $now;
            $_SESSION['sess_begin'] =& $sess_begin;
        }
    }
}

// ************
// db functions
// ************
// in setup mode there are no config constants
if (defined('PHPR_DB_HOST')) $db_host = PHPR_DB_HOST;
if (defined('PHPR_DB_USER')) $db_user = PHPR_DB_USER;
if (defined('PHPR_DB_PASS')) $db_pass = PHPR_DB_PASS;
if (defined('PHPR_DB_NAME')) $db_name = PHPR_DB_NAME;

if (defined('PHPR_DB_TYPE')){
    $db_type = PHPR_DB_TYPE;
    include_once($lib_path.'/db/'.$db_type.'.inc.php');
}
else if (isset($_SESSION['db_type']) && $_SESSION['db_type'] != '') {
    $db_type = $_SESSION['db_type'];
    include_once($lib_path.'/db/'.$db_type.'.inc.php');
}

// ************

// ****************
// string functions
// ****************
// safe HTML output
// This is a white list filter, that only allows p, br, b, i, ul, u,
// ol, li, strong, em as valid text.
// It is secure but won't work with more complicated html from
// FCKedit.

function html_out($outstr) {

    // first clean using blacklist
    $outstr = xss($outstr);

    // allowed tags - no attributes!
    // Caution! Write br before b, ul before u ...
    //          because of the RegExp!!
    $tags = 'p|br|b|i|ul|u|ol|li|strong|em|div|span';
    // allowed tags - attributes allowed
    $tags2 = 'font';
    if ($outstr <> '') {
        $outstr = ereg_replace("'","&#39;",htmlspecialchars($outstr, ENT_NOQUOTES));
        $outstr = ereg_replace("&amp;nbsp;","&nbsp;",$outstr);
        $outstr = preg_replace("/&lt;($tags).*?&gt;/i", '<\1>', $outstr);
        $outstr = preg_replace("/&lt;(($tags2).*?)&gt;/i", '<\1>', $outstr);
        $outstr = preg_replace("/&lt;\\/($tags|$tags2)&gt;/i", '</\1>', $outstr);
    }
    return $outstr;
}

// This is a black list filter, that allows all tags and takes out
// some known xss issues. It is _not_ secury, as there are always new
// scripting issues in the browser being found. But at least it works
// with FCKedit generated content.
// taken from Horde_MIME_Viewer, licensed under GPL
// Authors: Anil Madhavapeddy, Jon Parise, Michael Slusarz

function xss($data) {
    /* Deal with <base> tags in the HTML, since they will screw up
     * our own relative paths. */
    if (($i = stristr($data, '<base ')) && ($i = stristr($i, 'http')) &&
        ($j = strchr($i, '>'))) {
        $base = substr($i, 0, strlen($i) - strlen($j));
        $base = preg_replace('|(http.*://[^/]*/?).*|i', '\1', $base);
        if ($base[strlen($base) - 1] != '/') {
            $base .= '/';
        }
        /* Recursively call _cleanHTML() to prevent clever fiends
        * from sneaking nasty things into the page via $base. */
        $base = html_out2($base);
    }

    /* Change space entities to space characters. */
    $data = preg_replace('/&#(x0*20|0*32);?/i', ' ', $data);

    /* Nuke non-printable characters (a play in three acts). */

    /* Rule 1). If we have a semicolon, it is deterministically
     * detectable and fixable, without introducing collateral
     * damage. */
    $data = preg_replace('/&#x?0*([9A-D]|1[0-3]);/i', '&nbsp;', $data);

    /* Rule 2). Hex numbers (usually having an x prefix) are also
     * deterministic, even if we don't have the semi. Note that
     * some browsers will treat &#a or &#0a as a hex number even
     * without the x prefix; hence /x?/ which will cover those
     * cases in this rule. */
    $data = preg_replace('/&#x?0*[9A-D]([^0-9A-F]|$)/i', '&nbsp\\1', $data);

    /* Rule 3). Decimal numbers without trailing semicolons. The
     * problem is that some browsers will interpret &#10a as
     * "\na", some as "&#x10a" so we have to clean the &#10 to be
     * safe for the "\na" case at the expense of mangling a valid
     * entity in other cases. (Solution for valid HTML authors:
     * always use the semicolon.) */
    $data = preg_replace('/&#0*(9|1[0-3])([^0-9]|$)/i', '&nbsp\\2', $data);

    /* Remove overly long numeric entities. */
    $data = preg_replace('/&#x?0*[0-9A-F]{6,};?/i', '&nbsp;', $data);

    /* Get all attribute="javascript:foo()" tags. This is
     * essentially the regex /(=|url\()("?)[^>]*script:/ but
     * expanded to catch camouflage with spaces and entities. */
    $preg = '/((&#0*61;?|&#x0*3D;?|=)|' .
    '((u|&#0*85;?|&#x0*55;?|&#0*117;?|&#x0*75;?)\s*' .
    '(r|&#0*82;?|&#x0*52;?|&#0*114;?|&#x0*72;?)\s*' .
    '(l|&#0*76;?|&#x0*4c;?|&#0*108;?|&#x0*6c;?)\s*' .
    '(\()))\s*' .
    '(&#0*34;?|&#x0*22;?|"|&#0*39;?|&#x0*27;?|\')?' .
    '[^>]*\s*' .
    '(s|&#0*83;?|&#x0*53;?|&#0*115;?|&#x0*73;?)\s*' .
    '(c|&#0*67;?|&#x0*43;?|&#0*99;?|&#x0*63;?)\s*' .
    '(r|&#0*82;?|&#x0*52;?|&#0*114;?|&#x0*72;?)\s*' .
    '(i|&#0*73;?|&#x0*49;?|&#0*105;?|&#x0*69;?)\s*' .
    '(p|&#0*80;?|&#x0*50;?|&#0*112;?|&#x0*70;?)\s*' .
    '(t|&#0*84;?|&#x0*54;?|&#0*116;?|&#x0*74;?)\s*' .
    '(:|&#0*58;?|&#x0*3a;?)/i';
    $data = preg_replace($preg, '\1\8HordeCleaned', $data);

    /* Get all on<foo>="bar()". NEVER allow these. */
    $data = preg_replace('/([\s"\']+' .
    '(o|&#0*79;?|&#0*4f;?|&#0*111;?|&#0*6f;?)' .
    '(n|&#0*78;?|&#0*4e;?|&#0*110;?|&#0*6e;?)' .
    '\w+)\s*=/i', '\1HordeCleaned=', $data);

    /* Remove all scripts since they might introduce garbage if
    * they are not quoted properly. */
    $data = preg_replace('|<script[^>]*>.*?</script>|is', '<HordeCleaned_script />', $data);

    /* Get all tags that might cause trouble - <object>, <embed>,
    * <base>, etc. Meta refreshes and iframes, too. */
    $malicious = array(
    '/<([^>a-z]*)' .
    '(s|&#0*83;?|&#x0*53;?|&#0*115;?|&#x0*73;?)\s*' .
    '(c|&#0*67;?|&#x0*43;?|&#0*99;?|&#x0*63;?)\s*' .
    '(r|&#0*82;?|&#x0*52;?|&#0*114;?|&#x0*72;?)\s*' .
    '(i|&#0*73;?|&#x0*49;?|&#0*105;?|&#x0*69;?)\s*' .
    '(p|&#0*80;?|&#x0*50;?|&#0*112;?|&#x0*70;?)\s*' .
    '(t|&#0*84;?|&#x0*54;?|&#0*116;?|&#x0*74;?)\s*/i',

    '/<([^>a-z]*)' .
    '(e|&#0*69;?|&#0*45;?|&#0*101;?|&#0*65;?)\s*' .
    '(m|&#0*77;?|&#0*4d;?|&#0*109;?|&#0*6d;?)\s*' .
    '(b|&#0*66;?|&#0*42;?|&#0*98;?|&#0*62;?)\s*' .
    '(e|&#0*69;?|&#0*45;?|&#0*101;?|&#0*65;?)\s*' .
    '(d|&#0*68;?|&#0*44;?|&#0*100;?|&#0*64;?)\s*/i',

    '/<([^>a-z]*)' .
    '(b|&#0*66;?|&#0*42;?|&#0*98;?|&#0*62;?)\s*' .
    '(a|&#0*65;?|&#0*41;?|&#0*97;?|&#0*61;?)\s*' .
    '(s|&#0*83;?|&#x0*53;?|&#0*115;?|&#x0*73;?)\s*' .
    '(e|&#0*69;?|&#0*45;?|&#0*101;?|&#0*65;?)\s*' .
    '[^line]/i',

    '/<([^>a-z]*)' .
    '(m|&#0*77;?|&#0*4d;?|&#0*109;?|&#0*6d;?)\s*' .
    '(e|&#0*69;?|&#0*45;?|&#0*101;?|&#0*65;?)\s*' .
    '(t|&#0*84;?|&#x0*54;?|&#0*116;?|&#x0*74;?)\s*' .
    '(a|&#0*65;?|&#0*41;?|&#0*97;?|&#0*61;?)\s*/i',

    '/<([^>a-z]*)' .
    '(j|&#0*74;?|&#0*4a;?|&#0*106;?|&#0*6a;?)\s*' .
    '(a|&#0*65;?|&#0*41;?|&#0*97;?|&#0*61;?)\s*' .
    '(v|&#0*86;?|&#0*56;?|&#0*118;?|&#0*76;?)\s*' .
    '(a|&#0*65;?|&#0*41;?|&#0*97;?|&#0*61;?)\s*/i',

    '/<([^>a-z]*)' .
    '(o|&#0*79;?|&#0*4f;?|&#0*111;?|&#0*6f;?)\s*' .
    '(b|&#0*66;?|&#0*42;?|&#0*98;?|&#0*62;?)\s*' .
    '(j|&#0*74;?|&#0*4a;?|&#0*106;?|&#0*6a;?)\s*' .
    '(e|&#0*69;?|&#0*45;?|&#0*101;?|&#0*65;?)\s*' .
    '(c|&#0*67;?|&#x0*43;?|&#0*99;?|&#x0*63;?)\s*' .
    '(t|&#0*84;?|&#x0*54;?|&#0*116;?|&#x0*74;?)\s*/i',

    '/<([^>a-z]*)' .
    '(i|&#0*73;?|&#x0*49;?|&#0*105;?|&#x0*69;?)\s*' .
    '(f|&#0*70;?|&#0*46;?|&#0*102;?|&#0*66;?)\s*' .
    '(r|&#0*82;?|&#x0*52;?|&#0*114;?|&#x0*72;?)\s*' .
    '(a|&#0*65;?|&#0*41;?|&#0*97;?|&#0*61;?)\s*' .
    '(m|&#0*77;?|&#0*4d;?|&#0*109;?|&#0*6d;?)\s*' .
    '(e|&#0*69;?|&#0*45;?|&#0*101;?|&#0*65;?)\s*/i');

    $data = preg_replace($malicious, '<HordeCleaned_tag', $data);

    /* Comment out style/link tags, only if we are viewing inline.
     * NEVER show style tags to Netscape 4.x users since 1) the
     * output will really, really suck and 2) there might be
     * security issues. */
     $pattern = array('/\s+style\s*=/i',
      '|<style[^>]*>(?:\s*<\!--)*|i',
      '|(?:-->\s*)*</style>|i',
      '|(<link[^>]*>)|i');
      $replace = array(' HordeCleaned=',
      '<!--',
      '-->',
      '<!-- $1 -->');
      $data = preg_replace($pattern, $replace, $data);

    /* A few other matches. */
    $pattern = array('|<([^>]*)&{.*}([^>]*)>|',
    '|<([^>]*)mocha:([^>]*)>|i',
    '|<([^>]*)binding:([^>]*)>|i');
    $replace = array('<&{;}\3>',
    '<\1HordeCleaned:\2>',
    '<\1HordeCleaned:\2>');
    $data = preg_replace($pattern, $replace, $data);

    /* Attempt to fix paths that were relying on a <base> tag. */
    if (!empty($base)) {
        $pattern = array('|src=(["\'])/|i',
        '|src=[^\'"]/|i',
        '|href= *(["\'])/|i',
        '|href= *[^\'"]/|i');
        $replace = array('src=\1' . $base,
        'src=' . $base,
        'href=\1' . $base,
        'href=' . $base);
        $data = preg_replace($pattern, $replace, $data);
    }

    /* Check for phishing exploits. */
    if (preg_match('/href\s*=\s*["\']?\s*(http|https|ftp):\/\/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/i', $data)) {
        /* Check 1: Check for IP address links. */
        $phish_warn = true;
    } elseif (preg_match_all('/href\s*=\s*["\']?\s*(?:http|https|ftp):\/\/([^\s"\'>]+)["\']?[^>]*>\s*(?:(?:http|https|ftp):\/\/)?(.*?)<\/a/i', $data, $m)) {
        /* $m[1] = Link; $m[2] = Target
        * Check 2: Check for links that point to a different host than
        * the target url; if target looks like a domain name, check it
        * against the link. */
        $links = count($m[0]);
        for ($i = 0; $i < $links; $i++) {
            $m[2][$i] = strip_tags($m[2][$i]);
            if (preg_match('/^[.-_\da-z]+\.[a-z]{2,}/i', $m[2][$i]) &&
            strpos(urldecode($m[1][$i]), $m[2][$i]) !== 0 &&
            strpos($m[2][$i], urldecode($m[1][$i])) !== 0) {
                /* Don't consider the link a phishing link if the domain
                * is the same on both links (e.g. adtracking.example.com &
                * www.example.com). */
                preg_match('/\.?([^\.\/]+\.[^\.\/]+)\//', $m[1][$i], $host1);
                preg_match('/\.?([^\.\/]+\.[^\.\/]+)(\/.*)?$/', $m[2][$i], $host2);
                if (!(count($host1) && count($host2)) ||
                strcasecmp($host1[1], $host2[1]) !== 0) {
                    $phish_warn = true;
                }
            }
        }
    }

    return $data;
}

// allow only some chars for db table and fields names
function qss($data) {
    $data = preg_replace("/[^0-9a-z_.]/i", "", $data);
    return $data;
}

// the same specialy for hidden form fields and select field option values (uev -> UrlEncodedValues)
function uev_out($outstr) {
    return ereg_replace("'", "&#39;", htmlspecialchars(urlencode($outstr)));
}

// here we will build the iso format from a timestamp, e.g. 20040115235912 will be 2004-01-15 23.59
function show_iso_date1($date) {
    return substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2).' '.substr($date,8,2).':'.substr($date,10,2);
}
function show_iso_date2($date) {
    return substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
}


// quotation related string treatment for arrays
// to call with array_walk()
// function arr_addsl(&$item,$key){$item = addslashes($item);}
// is moved to gpcs_vars.inc.php because used before loading lib.inc.php
function arr_stripsl(&$item, $key) {
    $item = stripslashes($item);
}
function arr_dequote(&$item, $key) {
    $item = addslashes(ereg_replace('^\"|\"$', '', $item));
}

// change colours in list view
function tr_tag($dblclick, $parent='', $rec_id=0, $str_value='', $color=null, $module='id', $classname='') {
    global $cnr, $menu2, $output1, $contextmenu;

    $tr_class    = '';
    $tr_bgcolor  = '';
    $tr_hover_on = '';

    // class overrules given color
    if ($classname <> '') {
        $tr_class = "class='$classname'";
    }
    else if ($color <> '') {
        $tr_bgcolor = "style='background:$color'";
    }
    else {
        // alternate bgcolor
        if (($cnr/2) == round($cnr/2)) $tr_bgcolor = "style='background:".PHPR_BGCOLOR1."'";
        else                           $tr_bgcolor = "style='background:".PHPR_BGCOLOR2."'";
    }
    $cnr++;

    // highlight and marker in table rows
    $i = ($rec_id ? $rec_id : $cnr);
    //$i = $cnr;
    #$i = strval("$module".'xxx'."$i");
    $str_mark = " id='$i'";
    if ($rec_id) {
        $r_ID   = $rec_id;
        #$rec_id = strval("$module".'xxx'."$rec_id");
        if (!$str_value) $str_value = __('No Value');
        //für valides xhtml
        if ($contextmenu > 0) $str_mark .= " onclick=\"marker(this,'$rec_id')\" oncontextmenu=\"startMenu('".$menu2->menulistID."','$rec_id','','$r_ID')\"";
    }
    //für valides xhtml
    echo "<script type=\"text/javascript\">allRows['$i'] = new Array('$color','$str_value');</script>\n";
    if (PHPR_TR_HOVER) $tr_hover_on = "onmouseover=\"hiliOn(this,'$i')\" onmouseout=\"hiliOff(this,'$i')\" ondblclick=\"".$parent."location.href = '$dblclick'\"".$str_mark;

    // html output
    $output1 .= "<tr $tr_class $tr_bgcolor $tr_hover_on>\n";
}

// how many records on one page should be displayed?
$perpage_values = array( '10', '20', '30', '50', '100' );

// set default skin, if not already set in the session.
if (!isset($skin)) $skin = PHPR_SKIN;


// ***************************
// Authentication and settings
// ***************************

// fetch user data
// pass this check only it the constant 'avoid_auth' is set in the script
if (!defined('avoid_auth')) include_once($lib_path.'/auth.inc.php');
// end authentication
// ***************

// set style again to undertake session settings
set_style();

// *************
// date and time
function today() {
    global $year, $month, $day;
    $year  = date('Y', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    $month = date('m', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
    $day   = date('d', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
}

// set time
// FIXIT FIX IT
#$dbTSnull = date('YmdHis', mktime(date('H')+PHPR_TIMEZONE, date('i'), date('s'), date('m'), date('d'), date('Y')));
$dbTSnull = date('YmdHis', time() + PHPR_TIMEZONE*3600); // this should be a little bit faster

// ****************
// language part 2
// ****************
// default direction of text, will be overwritten by certain languages
$dir_tag = 'ltr';
// set charset
if     (eregi('pl|cz|hu|si',$langua)) { $lcfg = 'charset=iso-8859-2'; }
else if ($langua=='sk') {                $lcfg = 'charset=windows-1250'; }
else if ($langua=='ru') {                $lcfg = 'charset=windows-1251'; }
else if ($langua=='he') { $dir_tag = 'rtl';  $lcfg = 'charset=windows-1255'; }
else if (eregi('lv|lt|ee',$langua)) {    $lcfg = 'charset=windows-1257'; }
else if ($langua=='tw') { $lcfg = 'charset=big5'; }
else if ($langua=='zh') { $lcfg = 'charset=gb2312'; }
else if ($langua=='jp') { $lcfg = 'charset=EUC-JP'; }
else { $lcfg = 'charset=iso-8859-1'; }
if ($lcfg <> '') { $lang_cfg = '<meta http-equiv="Content-Type" content="text/html; '.$lcfg.'" />'."\n"; }
else {$lang_cfg = '';}

// assign help files
// list all languages without own help files, they have to take english
if (eregi('br|da|ee|he|hu|is|jp|ko|lt|lv|no|pl|pt|ru|se|sk',$langua)) { $doc = $path_pre.'/help/en'; }
else if ($langua=='tw') { $doc = $path_pre.'/help/zh'; }
// assuming catalan users would like to read spanish help  :)
else if ($langua=='ct') { $doc = $path_pre.'help/es'; }
// the rest gets their own help files
else { $doc = $path_pre.'help/'.$langua; }
// end help files
// end language definitions
// ************************

// ******
// layout
// ******
/*
// skins & css
// include the chosen skin
$css_loaded = @include_once($path_pre.'layout/'.PHPR_SKIN.'/'.PHPR_SKIN.'.php');
//fallback to default layout doesn't exist anymore
if (!$css_loaded) {
    include($path_pre.'layout/default/default.php');
    $skin = 'default';
} else {
    $skin = PHPR_SKIN;
}
*/


// end skins & css

// ****************
// menu & separator

// perpage values
if (!$perpage) {
    if ($start_perpage) $perpage = $start_perpage;
    else                $perpage = '30';
}

// sets a form element (in fact: all elements of a form) to inactive
function read_o($read_o, $type='disabled') {
    if ($read_o == 0) return '';
    else {
        if ($type == 'readonly') return ' readonly="readonly" style="background-color:'.PHPR_BGCOLOR3.';"';
        else return ' disabled="disabled" style="background-color:'.PHPR_BGCOLOR3.';"';
    }
}

// end layout
// **********

// group string for sql queries
if ($user_group) {
    if ($module == 'links') $sql_user_group = "(t_gruppe = '$user_group')";
    else                    $sql_user_group = "(gruppe = '$user_group')";
}
// all groups available for e.g. admin root, must be true in all cases
else {
    $sql_user_group = '(1 = 1)';
}

// transmit SID in GET-strings if needed (no cookies) only
$sid = (SID ? '&amp;'.SID : '');

/**
* get elements of tree
* same as show_elements_of_tree() but returning data instead of rendered html output
* @author Alex Haslberger
* @param ...
* @return string $html
*/
function get_elements_of_tree($table, $name, $query, $acc, $order, $sel_record, $parent, $exclude_ID=0) {
    global $records, $selected_record, $db_table, $children;

    $records  = array();
    $children = array();
    $db_table = $table;
    $selected_record = $sel_record;

    $new_val = array();
    foreach(explode(',', $name) as $a_val) {
        $new_val[] = qss($a_val);
    }
    $name = implode(',', $new_val);

    $result = db_query("SELECT ID, ".qss($acc).", ".qss($parent).", $name
                          FROM ".qss(DB_PREFIX.$table)."
                               $query
                               $order") or db_die();
    while ($row = db_fetch_row($result)) {
        if ($row[0] <> $exclude_ID) {
            $record = array();
            // first element will be an array which keeps the children of this record
            foreach ($row as $element) { $record[] = $element; }
            // ... one array for the main records ...
            if ($row[2] == 0 or !$row[2]) {$mainrecords[] = $record[0]; }
            // ... one array which keeps all elements below the current record
            else { $children[$row[2]][] = $row[0]; }
            // ... and one for all records :)
            $records[$record[0]] = $record;
        }
    }
    // end of creating the arrays, now loop over them and display them in the select box
    $data = array();
    if ($mainrecords) foreach($mainrecords as $mainrecID) {
        $data = array_merge($data, get_subelements($mainrecID));
         #$output2 .= show_elem2($mainrecID);
    }
    $children = array();
    return $data;
}
/**
* get subelements
* same as show_elem2() but returning data instead of rendered html output
* @author Alex Haslberger
* @param int $ID
* @return array $data
*/
function get_subelements($ID){
    global $db_table, $indent, $user_kurz, $user_access, $subdirs, $selected_record, $records, $children;

    // additional conditions for some modules
    switch ($db_table) {
        // if the table is table projects, check whether the user is a participant of the project
        case 'projekte':
            $allowed = 1;
            break;
        case 'contacts':
            // last name, first name in the select box gives a better distinction
            $records[$ID][3] = $records[$ID][3].",".$records[$ID][4];
            // if a company record is given, include him as well
            if ($records[$ID][5] <> '') $records[$ID][3] .= ' ('.$records[$ID][5].')';
            // since in the query the permission is already included we don't need another criterium
            $allowed = 1;
            break;
        case 'dateien':
            $records[$ID][3] = ereg_replace("§"," ",$records[$ID][3]);
            $allowed = 1;
            break;
        case 'notes':
            $allowed = 1;
            break;
    }
    $data = array();
    // first show the records itself if access is allowed
    if ($allowed == 1) {
        $tmp = array();
        $tmp['value']    = $records[$ID][0];
        $tmp['selected'] = ($records[$ID][0] == $selected_record) ? true : false;
        $tmp['depth']    = $indent;
        $tmp['text']     = $records[$ID][3];
        $data[] = $tmp;
    }
    // look for subelements
    if (!empty($children[$ID][0])) {
        foreach ($children[$ID] as $child) {
            $indent++;
            $data = array_merge($data, get_subelements($child));
            $indent--;
        }
    }
    return $data;
}
// *********************
// show elements of tree
// this function returns the level of an select-element - useful to indent elements in a list
// parameter: table and column name, $query, $access column, order by, value of element to show as selected, name of parent column, exclude the selected ID select children?
function show_elements_of_tree($table, $name, $query, $acc, $order, $sel_record, $parent, $exclude_ID=0) {
    global $records, $selected_record, $db_table, $children;

    $records  = array();
    $children = array();
    $db_table = $table;
    $selected_record = $sel_record;

    $new_val = array();
    foreach(explode(',', $name) as $a_val) {
        $new_val[] = qss($a_val);
    }
    $name = implode(',', $new_val);
    //avoid errors in case module project isn't activated
    if (PHPR_PROJECTS and check_role('projects') > 0) {
        $result = db_query("SELECT ID, ".qss($acc).", ".qss($parent).", $name
                               FROM ".qss(DB_PREFIX.$table)."
                                    $query
                                    $order") or db_die();
        while ($row = db_fetch_row($result)) {
            if ($row[0] <> $exclude_ID) {
                $record = array();
                // first element will be an array which keeps the children of this record
                foreach ($row as $element) { $record[] = $element; }
                // ... one array for the main records ...
                if ($row[2] == 0 or !$row[2]) {$mainrecords[] = $record[0]; }
                // ... one array which keeps all elements below the current record
                else { $children[$row[2]][] = $row[0]; }
                // ... and one for all records :)
                $records[$record[0]] = $record;
            }
        }
        // end of creating the arrays, now loop over them and display them in the select box
        if ($mainrecords) foreach($mainrecords as $mainrecID) {
             $output2 .= show_elem2($mainrecID);
        }
        $children = array();
    }
    return $output2;
}


function show_elem2($ID) {
    global $db_table, $indent, $user_kurz, $user_access, $subdirs, $selected_record, $records, $children;
     // additional conditions for some modules
    switch ($db_table) {
        // if the table is table projects, check whether the user is a participant of the project
        case 'projekte':
            $allowed = 1;
            break;
        case 'contacts':
            // last name, first name in the select box gives a better distinction
            $records[$ID][3] = $records[$ID][3].",".$records[$ID][4];
            // if a company record is given, include him as well
            if ($records[$ID][5] <> '') $records[$ID][3] .= ' ('.$records[$ID][5].')';
            // since in the query the permission is already included we don't need another criterium
            $allowed = 1;
            break;
        case 'dateien':
            $records[$ID][3] = ereg_replace("§"," ",$records[$ID][3]);
            $allowed = 1;
            break;
        case 'notes':
            $allowed = 1;
            break;
         case 'mail_client':
            $allowed = 1;
            break;
    }
    // first show the records itself if access is allowed
    if ($allowed == 1) {
        $outputtree .= "<option value='".$records[$ID][0]."'";
        if ($records[$ID][0] == $selected_record) $outputtree.= ' selected="selected"';
        $outputtree .= '>';
        for ($i = 1; $i <= $indent; $i++) {
            $outputtree .= '&nbsp;&nbsp;';
        }
        $outputtree .= $records[$ID][3]."</option>\n";
    }

    // look for subelements
    if (!empty($children[$ID][0])) {
        foreach ($children[$ID] as $child) {
            $indent++;
            $outputtree.= show_elem2($child);
            $indent--;
        }
    }
    return $outputtree;
}
// end show elements of tree
// *************************

// adds hidden fields to some forms
//   - for modules that have different forms for create and modify data
$view_param = array( 'up'      => $up,
                     'sort'    => $sort,
                     'perpage' => $perpage,
                     'page'    => $page,
                     'filter'  => $filter,
                     'keyword' => $keyword );

function hidden_fields($hid) {
    if (SID) $hid[session_name()] = session_id();
    if (is_array($hid)) {
      foreach ($hid as $key=>$value) {
          $str .= "<input type='hidden' name='".$key."' value='".xss($value)."' />\n";
      }
    }

    return $str;
}

/**
* check which access status the user has concerning a module and according to his role
* first call: get userroles from database, further calls: return userroles from SESSION
* @author Alex Haslberger
* @param string $module module name
* @return string $role user role
*/
function check_role($module) {
    if(!PHPR_ROLES) {
        return '2';
    }
    global $user_ID;
    // not yet read the userroles from database
    if (!isset($_SESSION['userroles'])) {
        $mapping_active = array(
                'todo'        => PHPR_TODO,
                'votum'       => PHPR_VOTUM,
                'bookmarks'   => PHPR_BOOKMARKS,
                'links'       => PHPR_LINKS,
                'calendar'    => PHPR_CALENDAR,
                'projects'    => PHPR_PROJECTS,
                'timecard'    => PHPR_TIMECARD,
                'contacts'    => PHPR_CONTACTS,
                'notes'       => PHPR_NOTES,
                'mail'        => PHPR_QUICKMAIL,
                'filemanager' => PHPR_FILEMANAGER,
                'forum'       => PHPR_FORUM,
                'helpdesk'    => PHPR_RTS,
                'chat'        => PHPR_CHAT,
                'summary'     => 1,
                'news'        => 1,
                'links'       => 1
            );

        // possible roles
        $roles = array( 'calendar',
                        'contacts',
                        'forum',
                        'chat',
                        'filemanager',
                        'bookmarks',
                        'votum',
                        'mail',
                        'notes',
                        'helpdesk',
                        'projects',
                        'timecard',
                        'todo');

        $query = "SELECT ".DB_PREFIX."roles.ID, ".implode(',', $roles)."
                    FROM ".DB_PREFIX."roles, ".DB_PREFIX."users
                   WHERE ".DB_PREFIX."users.role = ".DB_PREFIX."roles.ID
                     AND ".DB_PREFIX."users.ID = '$user_ID'";
        $res = db_query($query) or db_die();
        $row = db_fetch_row($res);
        // is there a role for this user?
        if ($row[0] > 0) {
            $i = 0;
            foreach ($roles as $role) {
                ++$i;
                // is this module active at all ?
                // the numeric value of the status: 0 = no access, 1 = read, 2 = write
                $access = is_null($row[$i]) ? '2' : $row[$i];
                $_SESSION['userroles'][$role] = $mapping_active[$role] ? $access : '0';
            }
        }
        // otherwise give him the full rights
        else {
            foreach ($roles as $role) {
                // is this module active at all ?
                // the numeric value of the status: 0 = no access, 1 = read, 2 = write
                $_SESSION['userroles'][$role] = $mapping_active[$role] ? '2' : '0';
            }
        }
    }
    return $_SESSION['userroles'][$module];
}
/**
* calculate the users access from given roles, rights ...
*
* @author Alex Haslberger
* @param string $module module name
* @param string $right right to be checked
* @return boolean
*/
function calculate_user_access($module, $right){
    global $user_ID;
    // .....
    return false;
}

/**
* Create exportlink (formerly an exportform) that links to export_page.php
* from which the export is done.
*
* @param  string $file  identifier used in misc/export.php
* @param  string $class
* @return string link to the export-form
*/
function show_export_form($file, $class='') {
    global $img_path, $keyword, $filter, $firstchar, $sort, $up, $month, $year, $anfang, $ende;

    if ($class == '') {
        $class = 'navbutton navbutton_inactive';
    }

    $hidden = array( 'file'      => $file,
                     session_name() => session_id(),
                     'filter'    => $filter,
                     'keyword'   => $keyword,
                     'firstchar' => $firstchar,
                     'up'        => $up,
                     'sort'      => $sort,
                     'month'     => $month,
                     'year'      => $year );

    if ($file == 'project_stat') $hidden = array_merge(array('anfang'=>$anfang, 'ende'=>$ende), $hidden);

    $out = array();
    //Session!
    foreach ($hidden as $key => $value) {
        $out[] = $key.'='.urlencode($value);
    }
    unset($key, $value);
    $out = "<a class='$class' href='../misc/export_page.php?".implode("&amp;", $out)."'>".__('export')."</a>";
    return $out;

    // -----------------
    // the old function returning a drop-down-form.
    // could be used for a JS-version
/*
    global $img_path, $keyword, $filter, $firstchar, $sort, $up, $month, $year, $anfang, $ende;
    $hidden = array( 'file'      => $file,
                     session_name() => session_id(),
                     'filter'    => $filter,
                     'keyword'   => $keyword,
                     'firstchar' => $firstchar,
                     'up'        => $up,
                     'sort'      => $sort,
                     'month'     => $month,
                     'year'      => $year );

    if ($file == 'project_stat') $hidden = array_merge(array('anfang'=>$anfang, 'ende'=>$ende), $hidden);
    $out  = "<form style='display:inline;' action='../misc/export.php' method='post' target='_blank'>\n";
    $out .= hidden_fields($hidden);
    $out .= "<select name='medium' onchange='submit();'>\n";
    $out .= "<option value=''>".__('export').":</option>\n";
    if ($file == 'calendar') {
        $out .= "<option value='ics'>iCal</option>\n";
        $out .= "<option value='xml'>XML</option>\n";
        $out .= "<option value='csv'>CSV</option>\n";
    }
    else {
        if (PHPR_SUPPORT_PDF) $out.= "<option value='pdf'>PDF</option>\n";
        $out .= "<option value='xml'>XML</option>\n";
        $out .= "<option value='html'></option>\n";
        $out .= "<option value='csv'>CSV</option>\n";
        $out .= "<option value='xls'>XLS</option>\n";
        $out .= "<option value='rtf'>RTF</option>\n";
        $out .= "<option value='doc'>DOC</option>\n";
        $out .= "<option value='print'>".__('print')."</option>\n";
    }
    $out .= "</select>\n</form>\n";
    return $out;
*/
}
/**
* nearly the same as show_export_form(), but returns data array instead of string and class parameter has changed to active parameter
* @author Alex Haslberger
* @param string $file module file name
* @param string $active indicates highlight status
* @return array $out array containing all export link data
*/
function get_export_link_data($file, $active = false) {
    global $img_path, $keyword, $filter, $firstchar, $sort, $up, $month, $year, $anfang, $ende;
    $out = array();
    $hidden = array( 'file'         => $file,
                     session_name() => session_id(),
                     'filter'       => $filter,
                     'keyword'      => $keyword,
                     'firstchar'    => $firstchar,
                     'up'           => $up,
                     'sort'         => $sort,
                     'month'        => $month,
                     'year'         => $year );
    if ($file == 'project_stat'){
        $hidden = array_merge(array('anfang' => $anfang, 'ende' => $ende), $hidden);
    }
    if ($file == 'project_stat_date'){
        $hidden = array_merge(array('anfang' => $anfang, 'ende' => $ende), $hidden);
    }
    $tmp = array();
    foreach ($hidden as $key => $value) {
        $tmp[] = $key.'='.urlencode($value);
    }
    $out['href']   = '../misc/export_page.php?'.implode('&amp;', $tmp);
    $out['text']   = __('export');
    $out['active'] = $active;
    return $out;
}
/**
* returns skin related stylesheet
*
* @author Alex Haslberger
* @todo   implement browser specific styles
* @return string
*/
// this function gets the OS of the browser and chooses the appropiate css file
function set_style() {
    global $path_pre, $skin, $css_inc, $css_void_background_image, $setting_skin, $justform;

    if ((isset($css_void_background_image) && $css_void_background_image == true) or $justform==1) {
        $vbi = '?void_background_image=1';
    }
    else {
        $vbi = '';
    }
    if (strstr($_SERVER['QUERY_STRING'], 'module=logout')) {
        $skin = PHPR_SKIN;
    }
    // comes from settings?
    if (isset($setting_skin)) {
        $skin = $setting_skin;
    }
    // custom skin?
    if (isset($skin)) {
        if (file_exists($path_pre."layout/".$skin."/".$skin."_css.php")) {
            $css_inc['mainstyle']  = '<style type="text/css" media="screen">@import "'.$path_pre.'layout/'.$skin.'/'.$skin.'_css.php'.$vbi.'";</style>'."\n";
            $css_inc['printstyle'] = '<style type="text/css" media="print">@import "'.$path_pre.'layout/'.$skin.'/'.$skin.'_css.php?print=1'.$vbi.'";</style>'."\n";
            // is there a browser-file?
            if (file_exists($path_pre."layout/".$skin."/".$skin."_css_ie.php")) {
                $css_inc['iestyle'] = '<!--[if gte IE 5]><style type="text/css" media="screen">@import "'.$path_pre."layout/".$skin."/".$skin."_css_ie.php".'";</style><![endif]-->'."\n";
            }
            else {
                $css_inc['iestyle'] = '';
            }
        }
    }

    // if no skin is set, fallback to default style
    if (!isset( $css_inc['mainstyle'])) {
        $css_inc['mainstyle']  = '<style type="text/css" media="screen">@import "'.$path_pre.'layout/default/default_css.php'.$vbi.'";</style>'."\n";
        $css_inc['printstyle'] = '<style type="text/css" media="print">@import "'.$path_pre.'layout/default/default_css.php?print=1'.$vbi.'";</style>'."\n";
        $css_inc['iestyle']    = '<!--[if gte IE 5]><style type="text/css" media="screen">@import "'.$path_pre."layout/default/default_css_ie.php".'";</style><![endif]-->'."\n";
    }

    // $css_inc is a global var in this namespace so we didn't need to return anything here
    //return $css_inc['mainstyle'] . $css_inc['iestyle'];

/*
  // mac platform ...
  if (eregi("mac", $_SERVER['HTTP_USER_AGENT'])) { return $path_pre."layout/".$skin."/css/mac.css"; }
  // windows OS ...
  elseif (eregi("win", $_SERVER['HTTP_USER_AGENT'])) {
    // special css for 4.x NN browsers
    if (eregi("4.7|4.6|4.5", $_SERVER['HTTP_USER_AGENT'])) { return $path_pre."layout/".$skin."/css/nn4.css"; }
    // css for IE and opera
    else  { return $path_pre."layout/".$skin."/css/win.css"; }
  }
  // default layout - not very nice but could fit a bit at least
  else { return $path_pre."layout/".$skin."/css/common.css"; }
*/
} // end find style sheet


// for ldap
function logit($message) {
    openlog('phprojekt', LOG_NDELAY|LOG_PID, LOG_USER);
    syslog(LOG_DEBUG, $message);
    closelog();
}

// for debugging :)
function get_mt() {
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}

function show_mt($begin) {
    list($usec, $sec) = explode(' ', microtime());
    echo 'This action tooks '.sprintf('%.4f', ((float)$usec + (float)$sec) - $begin).' sec';
}

// returns a set of name properties like last name, first or short name, either from the users or contacts table
// FIXME: parameters with default arguments should be on the right side of any non-default arguments!
function slookup($table='users', $values='nachname,vorname', $inputfield='ID', $value) {
    // disable lookups on disabled modules
    if ($table == 'projekte'    && !PHPR_PROJECTS)    return "";
    if ($table == 'timecard'    && !PHPR_TIMECARD)    return "";
    if ($table == 'contacts'    && !PHPR_CONTACTS)    return "";
    if ($table == 'notes'       && !PHPR_NOTES)       return "";
    if ($table == 'todo'        && !PHPR_TODO)        return "";
    if (strstr($table, 'mail_') && !PHPR_QUICKMAIL)   return "";
    if ($table == 'dateien'     && !PHPR_FILEMANAGER) return "";
    if ($table == 'forum'       && !PHPR_FORUM)       return "";
    if ($table == 'rts'         && !PHPR_RTS)         return "";

    $new_val = array();
    foreach(explode(',', $values) as $a_val) {
        $new_val[] = qss($a_val);
    }
    $values = implode(',', $new_val);
    $query = "SELECT ".$values."
                          FROM ".qss(DB_PREFIX.$table)."
                         WHERE ".qss($inputfield)." = '$value'";
    $result = db_query($query) or db_die($query);
    $row = db_fetch_row($result);
    if (count($row) < 2) return $row[0];
    else return implode(',', $row);
}

// new/extended version of slookup
function slookup5($table='users', $field='nachname,vorname', $var='ID', $value=0, $array=true) {
    // disable lookups on disabled modules
    if ($table == 'projekte'    && !PHPR_PROJECTS)    return "";
    if ($table == 'timecard'    && !PHPR_TIMECARD)    return "";
    if ($table == 'contacts'    && !PHPR_CONTACTS)    return "";
    if ($table == 'notes'       && !PHPR_NOTES)       return "";
    if ($table == 'todo'        && !PHPR_TODO)        return "";
    if (strstr($table, 'mail_') && !PHPR_QUICKMAIL)   return "";
    if ($table == 'dateien'     && !PHPR_FILEMANAGER) return "";
    if ($table == 'forum'       && !PHPR_FORUM)       return "";
    if ($table == 'rts'         && !PHPR_RTS)         return "";

    $new_val = array();
    foreach(explode(',', $field) as $a_val) {
        $new_val[] = qss($a_val);
    }
    $field = implode(',', $new_val);
    $query = "SELECT ".$field."
                FROM ".qss(DB_PREFIX.$table)."
               WHERE ".qss($var)." = '$value'";
    $res = db_query($query) or db_die();
    $row = db_fetch_row($res);
    settype($row, 'array');
    if ($array) {
        $ret = array();
        $fs = explode(',', $field);
        for ($ii=0; $ii<count($row); $ii++) {
            $ret[$fs[$ii]] = $row[$ii];
        }
        return $ret;
    }
    if (count($row) < 2) return $row[0];
    return implode(',', $row);
}

// array with port types, will be used on several stages
// array for mail account types and ports
// mind that the database's columnsize is currently only 10 chars for the arraykeys!
$port = array( 'pop3'            => '110/pop3',
               'pop3s'           => '995/pop3/ssl',
               'pop3 NOTLS'      => '110/pop3/NOTLS',
               'pop3s NVC'       => '995/pop3/ssl/novalidate-cert',
               'imap'            => '143/imap',
               'imap3'           => '220/imap3',
               'imaps'           => '993/imap/ssl',
               'imaps NVC'       => '993/imap/ssl/novalidate-cert',
               'imap4ssl'        => '585/imap4/ssl',
               'imap NOTLS'      => '143/imap/NOTLS',
               'imap NOVAL'      => '143/imap/NOVALIDATE' );


function close_window_link() {
    return '<a href="javascript:window.close()" title="'.__('Close window').'">'.__('Close window')."</a>\n";
}


// load class for sending e-mail
// and initialize the objekt "$mail" - if needed
function use_mail($init='') {
    global $lib_path, $mail;

    include_once($lib_path.'/sendmail.inc.php');
    if ($init) {
        $mail = new send_mail( PHPR_MAIL_MODE, PHPR_MAIL_EOH, PHPR_MAIL_EOL, PHPR_MAIL_AUTH,
                               PHPR_LOCAL_HOSTNAME, PHPR_SMTP_HOSTNAME, PHPR_SMTP_ACCOUNT,
                               PHPR_SMTP_PASSWORD, PHPR_POP_HOSTNAME, PHPR_POP_ACCOUNT,
                               PHPR_POP_PASSWORD );
    }
}


/**
* sets the read flag to one users entry
* @author Albrecht Günther / Alex Haslberger
* @param int $ID id of the entry
* @param string $module module to which the entry belongs
* @return string (maybe it should be an int or even boolean)
*/
function touch_record($module, $ID) {
    global $user_kurz, $user_ID, $tablename, $dbIDnull, $dbTSnull;

    // check if user has an entry for $ID
    $result = db_query("SELECT t_record from ".DB_PREFIX."db_records
                         WHERE t_record = '$ID'
                           AND t_author = '$user_ID'
                           AND t_module = '".DB_PREFIX."$tablename[$module]'");
    $row = db_fetch_row($result);
    //  user has already an entry -> update entry
    if (isset($row[0])) {
        $result = db_query(xss("UPDATE ".DB_PREFIX."db_records
                               SET t_datum = '$dbTSnull',
                                   t_touched = 1
                             WHERE t_record = '$ID'
                               AND t_module = '".DB_PREFIX."$tablename[$module]'
                               AND t_author = '$user_ID'")) or db_die();
        return '1';
    }
    else {
        $result = db_query(xss("INSERT INTO ".DB_PREFIX."db_records
                                        ( t_ID,    t_author,             t_module,             t_record,  t_datum, t_touched)
                                 VALUES ($dbIDnull,'$user_ID','".DB_PREFIX."$tablename[$module]',  '$ID', '$dbTSnull', 1)")) or db_die();
        return '0';
    }
}

$untouched = "(touched IS NULL OR touched NOT LIKE '%\"$user_kurz\"%')";
// end touch record

// *****************
// history functions
// store the values of fields that have been changed in this record
function history_keep($table, $table_fields, $ID) {
    global $user_ID, $dbIDnull, $dbTSnull;
    
    $table_fields = explode(',', $table_fields);
    foreach ( $table_fields as $field ) {
        $last_value = slookup($table, $field, 'ID', $ID);
        
        // exception: if the name of the field is 'acc_read', it must be compared with the variable $acc
        $new_value = ($field == 'acc_read') ? $GLOBALS['acc'] : $GLOBALS[$field];
        
        // no action if it's a new value or not changed
        if ( !$last_value and !$new_value ) { continue; }
        if ( $last_value == $new_value ) { continue; }

        // do this as a last step, because the first two will hit more often
        // get the fieldtype and check if it's a timestamp* field
        $query = "SELECT form_type FROM ".DB_PREFIX."db_manager WHERE db_table='".qss($table)."' AND db_name='$field'";
        $result = db_query($query);
        list($form_type) = db_fetch_row($result);
        if ( strstr($form_type, 'timestamp') ) { continue; }
        unset($query, $result, $form_type);
        
        $last_value = addslashes($last_value);
        db_query(xss("INSERT INTO ".DB_PREFIX."history
                             (   ID,        von    ,   _date,   _table,  _field,_record,  last_value,   new_value)
                      VALUES ($dbIDnull,'$user_ID','$dbTSnull','$table','$field',   '$ID' ,'$last_value','$new_value')")) or db_die();
    }
}

function history_delete($table, $ID) {
    $result = db_query("DELETE FROM ".DB_PREFIX."history
                              WHERE _table = '$table'
                                AND _record = '$ID'") or db_die();
}

function history_show($table, $ID) {
    global $fields;

    // build field array
    foreach ($fields as $field_name => $field) {
        $formfields1[$field['form_name']] = $field_name;
    }

    // add read and write access as well
    $form_fields = array_merge(array(__('Read access')=>'acc', __('Read access')=>'acc_read',__('Write access')=>'acc_write'), $formfields1);
    $str = "<table><thead>\n";
    $str .= "<tr><th>".__('Date')."</th><th>".__('Field')."</th><th>".__('Old value')."</th><th>".
            __('New value')."</th><th>".__('Author')."</th></tr></thead><tbody>\n";

    $result = db_query("SELECT _date, _field, last_value, new_value, nachname, vorname
                          FROM ".DB_PREFIX."history, ".DB_PREFIX."users
                         WHERE ".DB_PREFIX."history.von = ".DB_PREFIX."users.ID
                           AND _table = '$table'
                           AND _record = '$ID'
                      ORDER BY _date DESC") or db_die();

    while ($row = db_fetch_row($result)) {
        // check whether this field has a name in the form
        $form_name = array_search($row[1], $form_fields);
        $form_name ? $fieldname = enable_vars($form_name) : $fieldname = $row[1];
        // if it is a serialized string, split it into an array
        $str .= "<tr><td>".show_iso_date1($row[0])."</td><td>".$fieldname."</td><td>".formatstring($row[2],100)."&nbsp;</td>";
        $str .= "<td>".formatstring($row[3],100)."&nbsp;</td><td>".$row[4].", ".$row[5]."</td></tr>\n";
    }
    $str .= '</tbody></table>';
    return $str;
}
// end history functions
// *********************

function formatstring($string, $length) {
    // if it is serialized string ,return a list
    if (substr($string,-2) == ';}') $string = implode('<br />',unserialize($string));
    return substr($string, 0, 100);
}

// ************* //
// header functions

function set_page_header() {
    return set_html_tag().set_head_tag().set_body_tag().'<a style="display:none" href="#content" title="'.__('go to content').'">'.__('go to content').'</a>';
}

function set_html_tag() {
    global $langua, $dir_tag;
    $html_string  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'."\n";
    $html_string .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$langua.'" lang="'.$langua.'">'."\n";
    return $html_string;
}

// ************* //
function set_head_tag() {
    global $path_pre, $css_inc, $js_inc, $he_add;

    $head  = "<head>\n";
    $head .= "<title>".__(ucfirst($GLOBALS['module']))."</title>\n";

    // neu
    if (isset($css_inc) && is_array($css_inc) && count($css_inc) > 0) {
        foreach ($css_inc as $css) {
            $head .= $css;
        }
    }

    if (SID) $js_inc[] = '>var SID = "'.session_name().'='.session_id().'";';
    else     $js_inc[] = '>var SID = "";';

    $js_inc[] = 'src="/'.PHPR_INSTALL_DIR.'lib/chkform.js">';
    foreach ($js_inc as $js) {
        $head .= '<script type="text/javascript" '.$js."</script>\n";
    }

    if (isset($he_add) && is_array($he_add) && count($he_add) > 0) {
        foreach ($he_add as $he) {
            $head .= $he."\n";
        }
    }

    $head .= print_out_reminder_window();
    $head .= '<link rel="shortcut icon" href="/'.PHPR_INSTALL_DIR.'favicon.ico" />'."\n";
    $head .= $GLOBALS['lang_cfg'];
    $head .= "</head>\n";

    return $head;
}

function set_body_tag() {
    global $onload;
    $body = '<body';
    if (isset($onload) && is_array($onload) && count($onload) > 0) {
        $body .= ' onload="';
        foreach ($onload as $load) {
            $body .= $load;
        }
        $body .= '"';
    }
    return $body.">\n";
}
// end header functions
// ********************

// prepare for htmla editor
if (PHPR_SUPPORT_HTML and $_SESSION['show_html_editor']["$module"] == 1) {
    $js_inc[]  = " src='".$path_pre."lib/fckeditor.js'>";
}
// JavaScript global vars for contexmenu
$js_inc[] = ">var hiliColor = '".PHPR_BGCOLOR_HILI."'; var markColor = '".PHPR_BGCOLOR_MARK."'; var sessid = '$sid';";

// supply the javascript script for the datepicker popup
function datepicker() {
    global $lib_path, $name_month, $name_day2, $today;

    $m = 'm='.implode('_', $name_month);
    $d = '&d='.implode('_', $name_day2);
    $t = '&t='.$today;

    $str = '
<script type="text/javascript">
<!--
var monthDays = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
var dTarget;
var next;
var mo;

function callPick(obj) {
    dTarget = obj;
    var dp = window.open("'.$lib_path.'/datepicker.php?'.$m.$d.$t.'","dp","left=200,top=200,width=230,height=210,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1");
    dp.focus();
    return false;
}

function nM(obj) {
    bothString = new String(obj.value);
    splitString = bothString.split("-");
    yr = splitString[0];
    mn = splitString[1];
    day = splitString[2];
    next = (day*1) + 1;
    yy = (yr*1);
    if (((yy % 4)==0) && (((yy % 100)!=0)|| ((yy % 400)==0))) {
        monthDays = new Array(31,29,31,30,31,30,31,31,30,31,30,31);
    } else {
        monthDays = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
    }
    mo = (mn*1)-1;
    var vgl = monthDays[mo];
    if (day >= vgl) {
        mn = (mn*1)+1;
        next = 1;
    }
    if (mn==13) {
        mn = 1;
        yy = (yr*1)+1;
    }
    obj.value = yy+"-"+twoDigits(mn)+"-"+twoDigits(next);
}

function lM(obj) {
    bothString = new String(obj.value);
    splitString = bothString.split("-");
    yr = splitString[0];
    mn = splitString[1];
    day = splitString[2];
    next = (day*1) - 1;
    yy = (yr*1);
    if (((yy % 4)==0) && (((yy % 100)!=0)|| ((yy % 400)==0))) {
        monthDays = new Array(31,29,31,30,31,30,31,31,30,31,30,31);
    } else {
        monthDays = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
    }
    if (next == 0) {
        mn = (mn*1)-1;
        next = monthDays[mn-1];
    }
    if (mn == 0) {
        mn = 12;
        yy = (yr*1)-1;
        next = monthDays[mn-1];
    }
    obj.value = yy+"-"+twoDigits(mn)+"-"+twoDigits(next);
}

function twoDigits(x) {
    x = "0" + x;
    return x.match(/\d\d$/);
}

function getNetto(anf, end, nanf, nend) {
    if (nanf) {
        a = anf.value;
        e = end.value;
        netto = ((e.substr(0,2) - a.substr(0,2))*60 +(e.substr(2,2) - a.substr(2,2)));
        nettoh = Math.floor(netto/60);
        nettom = netto - (nettoh * 60);
        nanf.value=nettom;
        nend.value=nettoh;
    };
}

//-->
</script>
';
    return $str;
}


function selector($selected, $exclude_ID) {
    global $lib_path;

    echo '
<script type="text/javascript">
<!--
var dTarget;
function callPick2(obj1, obj2) {
    dTarget = obj1;
    sTarget = obj2;
    var dp = window.open("'.$lib_path.'/selector.php?selected='.$selected.'&amp;exclude_ID='.$exclude_ID.'","dp","left=100,top=100,width=430,height=310,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=1");
    dp.focus();
    return false;
}
//-->
</script>
';
}


// supply a random string, mostly used for a new filename
function rnd_string($length=12) {
    srand((double)microtime()*1000000);
    $char = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMANOPQRSTUVWXYZ';
    $rnd_string = '';
    while (strlen($rnd_string) < $length) {
        $rnd_string .= substr($char, (rand()%(strlen($char))), 1);
    }
    return $rnd_string;
}

// crypt string
function encrypt($password, $saltstring) {
    $salt = substr($saltstring, 0, 2);
    $enc_pw = crypt($password, $salt);
    return $enc_pw;
}


// **********
// contact box for large number of data
function select_contacts($contact_ID, $field_name, $exclude_ID=0, $form='cont', $form_id='') {
    global $user_ID, $user_kurz, $sql_user_group;
    global $field, $selected, $read_o, $img_path;

    $selected = ($contact_ID) ? $contact_ID : $field['value'];
    selector($selected, $exclude_ID);
    // first proof whether the table has a big amount of data
    if (PHPR_FILTER_MAXHITS > 0) {
        $result = db_query("SELECT COUNT(ID)
                              FROM ".DB_PREFIX."contacts
                             WHERE (acc_read LIKE 'system'
                                    OR von = '$user_ID'
                                    OR ((acc_read LIKE 'group'
                                         OR acc_read LIKE '%\"$user_kurz\"%')
                                        AND $sql_user_group))") or db_die();
        $row = db_fetch_row($result);
        $amount = $row[0];
    }
    else $amount = 0;

    // end amount check, now decide which element to show
    if (PHPR_FILTER_MAXHITS > 0 and $amount > PHPR_FILTER_MAXHITS) {
        $out1 .= "<input type='hidden' name='".$field_name."' value='".xss($selected)."'>\n";
        $out1 .= "<input type='text' size=25 id='".$form_id."_2' name='tmp_".$field_name."' value='".slookup('contacts','nachname,vorname','ID',$selected)."'";
        if ($field['form_tooltip'] <> '') $out1.= " title='".$field['form_tooltip']."'";
        $elem_pick = ($read_o) ? '' : ("&nbsp;<a href='javascript://' title='".__('This link opens a popup window')."' onclick=\"callPick2(document.frm.elements['".$field_name."'],document.frm.elements['tmp_".$field_name."'])\"><img src='".$img_path."/cont.gif' alt='' border='0'></a>");
        $out1 .= read_o(1,'readonly').">".$elem_pick."\n";
    }
    // default case: show the usual select box with tree view
    else {
        // special hack for forms - if the contact ID is given, mark this one as selected
        $out1 .= "<select class='$form' id='".$form_id."' name='". $field_name."'";
        if ($field['form_tooltip'] <> '') $out1.= " title='".$field['form_tooltip']."'";
        $out1 .= read_o($read_o)."><option value='0'></option>";
        $out1 .= show_elements_of_tree('contacts',
                    'nachname,vorname,firma',
                    "WHERE (acc_read LIKE 'system' OR ((von = $user_ID OR acc_read LIKE 'group' OR acc_read LIKE '%\"$user_kurz\"%') AND $sql_user_group))",
                    'acc_read', " ORDER BY nachname", $selected, 'parent', $exclude_ID);
        $out1 .= "</select>\n";
    }
    return $out1;
}


/**
* function which prints the path to the current page
* @author Nina Schmitt
* @param string home: name of home directory
* @param int fID: forums ID or ID of first element in Path
* @param int ID: topic ID or ID of second element in Path
* @return string string with link to image
*/
function show_path($home, $fID='', $ID='') {
    global $module;
    $h = "$module.php";
    $pa = '<span class="home"><a class="pa" href="'.$h.'">'.$home.'</a> </span>';
    if (empty($fID));
    else if (empty($ID)) {
        $pa .= '> '.slookup('forum', 'titel', 'ID', $fID);
    }
    else {
        $h = $h.'?fID='.$fID;
        $pa .= ' ><a class="pa" href="'.$h.'">'.slookup('forum', 'titel', 'ID', $fID).'</a>';
        $pa .= ' > '.slookup('forum', 'titel', 'ID', $ID);
    }
    return $pa;
}


/**
* function which prints page number and navigation parts
* @author Nina Schmitt
* @return string outputlist
*/
function show_page() {
    global $page, $perpage, $max, $liste, $ID, $fID, $module, $last;
    $page_n = $page + 1;
    $page_p = $page - 1;
    $page_last = floor($last/$perpage) -1;
    $bis = ($page*$perpage) + $perpage;
    if ($bis>$last) $bis = $last;
    if (($last%$perpage)>0) $page_last++;
    $outputlist  = __('Count').": ";
    $outputlist .= 1+($page*$perpage);
    $outputlist .= ' - ';
    $outputlist .= "$bis  ".__('from')." ".$last;
    $outputlist .= '<span class="p2">';
    if ($page) {
        #$outputlist.= "<a class='un' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=0&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid> |< </a> ".__('Begin')."
        # <a class='un' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=$page_p&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid> << </a> ".__('back')." ";
        $outputlist.= "<a class='un' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=0&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid> |< ".__('Begin')."</a>
         <a class='un' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=$page_p&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid> << ".__('back')."</a>";
    }
    if ($perpage < $last) {
        $outputlist .= ' | ';
        for ($i=0; $i*$perpage<$last;$i++) {
            $i1 = $i + 1;
            if ($i==$page) $outputlist .= "<b> $i1 </b>";
            else $outputlist .= " <a class='und' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=$i&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid>$i1</a> ";
        }
        $outputlist .= ' | ';
    }

    if (count($liste) > $page_n*$perpage) {
        $outputlist .= "  <a class='un' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=$page_n&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid>".__('Next')." >></a> &nbsp;";
        $outputlist .= "<a class='un' href='$module.php?mode=view&amp;tree_mode=$tree_mode&amp;perpage=$perpage&amp;page=$page_last&amp;keyword=$keyword&amp;ID=$ID&amp;fID=$fID&amp;filter=$filter'$sid>".__('End')." >| </a>  ";
    }
    $outputlist .= '</span>';
    return $outputlist;
}


function make_list($result) {
    global $page, $perpage, $max, $last;

    $liste = array();
    while ($row = db_fetch_row($result)) {
        $liste[] = $row[0];
    }
    if ((($page+1)*$perpage) > count($liste)) $max = count($liste);
    else $max = ($page+1)*$perpage;
    $last = count($liste);
    return $liste;
}

/**
* function to add messages to message_stack
* @author Nina Schmitt / Alex Haslberger
* @param string $message message to add
* @param string $module module to which the message belongs
* @param string $kat category of message (notice,warning,error)
*/
function message_stack_in($message, $module, $kat) {
    settype($_SESSION['message_stack'][$module], 'array');
    $_SESSION['message_stack'][$module][] = array($kat, $message);
}

/**
* function to return all messages of message_stack belonging to a specific module
* @author Nina Schmitt / Alex Haslberger
* @param string $module which modules messages should be returned
* @return string $out
*/
function message_stack_out($module) {
    if (!isset($_SESSION['message_stack'][$module]) ||
        !is_array($_SESSION['message_stack'][$module])) {
        return ''; // no module in message_stack -> return emtpy string
    }
    $out = array();
    foreach ($_SESSION['message_stack'][$module] as $data) {
        $out[] = "<span class='$data[0]'>Module \"$module:\" $data[1]</span>";
    }
    $out = implode('<br/>', $out);
    if(count($_SESSION['message_stack'][$module]) > 1){
        $out = '<br/>'.$out;
    }
    unset($_SESSION['message_stack'][$module]);
    return $out;
}

/**
 * function to return all messages of all modules in message_stack
 *
 * @author Alex Haslberger
 * @return string $out
 */
function message_stack_out_all() {
    if (!isset($_SESSION['message_stack']) ||
        !is_array($_SESSION['message_stack'])) {
        return ''; // no message_stack -> return empty string
    }
    $out = '';
    foreach ($_SESSION['message_stack'] as $modname => $data) {
        $out .= message_stack_out($modname);
    }
    return $out;
}

/**
 * check if the message stack is empty
 *
 * @return boolean
 */
function message_stack_is_empty() {
    if (isset($_SESSION['message_stack']) &&
        is_array($_SESSION['message_stack']) &&
        count($_SESSION['message_stack'])) {
        return false;
    }
    return true;
}

/**
 * function to generate help links
 *
 * @author Alex Haslberger
 * @param string $topic topic identifier
 * @return string link
 */
function get_helplink($topic='onlinemanual') {
    global $langua, $helplink_map, $translated_helps;
    $langua1 = in_array($langua, $translated_helps) ? $langua : '';
    $pfx = $langua1 ? '-'.$langua1 : '';
    if($langua=='en')$pfx='';
    return 'http://wiki'.$pfx.'.phprojekt.com/index.php/'.$helplink_map[$topic];
}

/**
 * get help button
 *
 * @author Alex Haslberger
 * @param string $topic topic
 * @return string
 */
function get_help_button($topic, $style='button') {
    switch ($style) {
        case 'tab':
            return '<a class="calendar_top_area_tabs_inactive" href="../index.php?redirect=help&amp;link='.$topic.'" target="_blank">?</a>';
        default:
            return '<a href="../index.php?redirect=help&amp;link='.$topic.'" target="_blank" class="navbutton navbutton_inactive">?</a>';
    }
}

/**
 * get go button
 *
 * @author Alex Haslberger
 * @param  string $class css class
 * @return string
 */
function get_go_button($class='button2', $type='button', $name='',$value='') {
    if ($name) $name = ' name="'.$name.'"';
    if ($type == 'button') {
        if($value=='')$value=__('GO');
        return '<input type="submit" class="'.$class.'"'.$name.' value="'.$value.'" />';
    }
    else if($type == 'image') {
        switch ($class) {
            case 'arrow_search':
            default:
                $src = '../img/arrow_search.gif';
                break;
        }
        return '<input type="submit" class="navSearchButton"'.$name.' value="" />';
    }
}

/**
 * get go button with name parameter
 *
 * @param  string $name html name parameter
 * @return string
 */
function get_go_button_with_name($name) {
    return get_go_button('button2', 'button', $name);
}

/**
 * get host path
 *
 * @author Alex Haslberger
 * @return string
 */
function get_host_path() {
    return PHPR_HOST_PATH;
}

/**
 * just a little debug helper
 *
 * @author Alex Haslberger
 * @param array data array
 * @return void
 */
function printr($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

/**
 * return filter execute bar
 *
 * @author Alex Haslberger
 * @param string $help_topic topic identifier for help link
 * @param boolean $show_nav_filter nav filters visible?
 * @param array $add_paras additional parameters array
 * @return string $res html code for filter execute bar
 */
function get_filter_execute_bar($help_topic='', $show_nav_filter=true, $add_paras=array()) {
    global $module;

    $fields = build_array($module, '');

    $hiddenfields = array("<input type='hidden' name='mode' value='view' />");
    if (SID) $hiddenfields[] = "<input type='hidden' name='".session_name()."' value='".session_id()."' />";
    // add some more hidden fields
    if (isset($add_paras['hidden'])) {
        foreach ($add_paras['hidden'] as $name=>$value) {
            $hiddenfields[] = "<input type='hidden' name='$name' value='".xss($value)."' />";
        }
    }

    $res = '
<div class="filter_execute_bar">
    <form action="'.xss($_SERVER['PHP_SELF']).'" method="post" style="display:inline;">
';
    $res .= implode("\n", $hiddenfields)."\n";

    if ($show_nav_filter) {
        $hiddenfields[] = "<input type='hidden' name='module' value='".xss($module)."' />";
        $hiddenfields[] = "<input type='hidden' name='nav' value='".xss($module)."' />";
        $res .= "<span class='filter_execute_bar'>\n";
        $res .= nav_filter($fields).get_go_button();
        $res .= "\n</span>\n<span class='strich'>&nbsp;</span>";

        //if (strlen($help_topic)) $res .= get_help_button($help_topic);

        $filter = get_filters($module);
        if ($filter) {
            $res .= '
    </form>

    <form action="../lib/dbman_filter_pop.php" method="get" style="display:inline;">
';
            $res .= implode("\n", $hiddenfields).'
        '.__('Load filter').'
        <select name="use">
            <option value=""></option>
';
            foreach ($filter as $id=>$value) {
                $res.= '<option value="'.$id.'">'.xss($value)."</option>\n";
            }
            $res .= '        </select>
        '.get_buttons(array(array('type' => 'submit', 'active' => false, 'name' => '', 'value' => __('use'))));
        }
    }

    $res .= '
    </form>
</div>
';
    return $res;
}

/**
 * return filter edit bar
 *
 * @author Alex Haslberger
 * @param boolean $show_filters filters visible?
 * @return string $ret html code for filter edit bar
 */
function get_filter_edit_bar($show_filters=true, $link='',$show_manage_filters=true) {
    global $module, $sort, $tablename;

    if (!$show_filters) return '';

    // define direction
    if ($_SESSION['f_sort'][$module]['direction'] <> '') {
        $dir = ($_SESSION['f_sort'][$module]['direction'] == 'ASC') ? __('ascending') : __('descending');
    }
    else $dir = '';

    $resf      = db_query("SELECT form_name
                             FROM ".DB_PREFIX."db_manager
                            WHERE db_table = '$tablename[$module]'
                              AND db_name  = '".$_SESSION['f_sort'][$module]['sort']."'") or db_die();
    $resfrow   = db_fetch_row($resf);
    $form_name = enable_vars($resfrow[0]);

    $ret = '
    <div class="filter_edit_bar">
        <span class="filter_edit_bar">'.display_filters($module, $link);
        if($show_manage_filters==true)$ret.=display_manage_filters($module);
    $ret.='
            <span class="strich">&nbsp;</span>
            &nbsp;&nbsp;<b>'.__('Sorted by').': </b>'.$form_name.'&nbsp;&nbsp;('.$dir.')
        </span>
    </div>
';
    return $ret;
}

/**
 * return filter top status bar
 *
 * @author Alex Haslberger
 * @return string $ret html code for filter top status bar
 */
function get_status_bar() {
    if (message_stack_is_empty()) return '';
    $ret = '
    <div class="status_bar">
        <span class="status_bar">
            '.__('Status').':&nbsp;'.message_stack_out_all().'
        </span>
    </div>
';
    return $ret;
}

/**
 * return forum path bar
 *
 * @author Alex Haslberger
 * @return string $ret html code for forum path bar
 */
function get_forum_path_bar() {
    global $fID, $ID;

    $ret = '
    <div class="path_bar">
        <span class="path_bar">
        <b>'.__('Path').__(':').'</b>&nbsp;&nbsp;'.show_path(__('Overview forums'), $fID, $ID).'
        </span>
    </div>
';
    return $ret;
}

/**
 * return top page navigation bar
 *
 * @author Alex Haslberger
 * @return string $ret html code for top page navigation bar
 */
function get_top_page_navigation_bar() {
    return get_page_navigation_bar('unten', __('down'));
}

/**
 * return bottom page navigation bar
 *
 * @author Alex Haslberger
 * @return string (html code for bottom page navigation bar)
 */
function get_bottom_page_navigation_bar() {
    return get_page_navigation_bar('oben', __('up'));
}

/**
 * return page navigation bar
 *
 * @author Alex Haslberger
 * @param string $anker_link
 * @param string $anker_text
 * @return string $ret html code for page navigation bar
 */
function get_page_navigation_bar($anker_link, $anker_text) {
    $ret = '
    <a name="'.($anker_link == 'oben' ? 'unten' : 'oben').'"></a>
    <div class="page_navigation_bar">
        <div class="page_navigation_bar_left">
            <span class="page_navigation_bar">
                '.show_page().'
            </span>
        </div>
        <div class="page_navigation_bar_right">
            <span class="page_navigation_bar">
                <a class="pa" href="#'.$anker_link.'">'.$anker_text.'</a>
            </span>
        </div>
    </div>
';
    return $ret;
}

/**
 * return filter bottom status bar
 *
 * @author Alex Haslberger
 * @param string $help_topic topic identifier for help link
 * @return string $res html code for filter bottom status bar
 */
function get_all_filter_bars($help_topic, $result_rows,$add_paras=array(),$link='',$show_manage_filters=true){
    $res  = get_filter_execute_bar($help_topic,true,$add_paras);
    $res .= get_filter_edit_bar(true,$link,$show_manage_filters);
    $res .= get_status_bar();
    $res .= get_top_page_navigation_bar();
    $res .= $result_rows;
    $res .= get_bottom_page_navigation_bar();
    return $res;
}

// do the reminder stuff
function print_out_reminder_window() {
    global $user_ID, $sid, $path_pre, $settings;

    $ret = '';
    $rem = isset($settings['reminder']) ? $settings['reminder'] : PHPR_REMINDER;
    if ($rem > 0 && $user_ID > 0 && !isset($_SESSION['show_reminder_window'])) {
        if (eregi("mac", $_SERVER['HTTP_USER_AGENT'])) {
            // width and height for macs ...
            $width  = 200;
            $height = 80;
        }
        else {
            // ... and for the rest!
            $width  = 170;
            $height = 50;
        }
        $ret = '
<script type="text/javascript">
<!--
WRem = window.open("'.$path_pre.'calendar/reminder.php?'.$sid.'","phproremind","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,left=400,top=300,width='.$width.',height='.$height.'");
WRem.focus();
//-->
</script>
';
        $_SESSION['show_reminder_window'] = true;
    }
    return $ret;
}


/**
* get the mime tipe of a file
* @author Alex Haslberger
* @param string $filename filename of the file
* @return string $mt mimetype
*/
function get_mime_type($filename){
    if(function_exists('mime_content_type')){
        return mime_content_type($filename);
    }
    else{
        // try to guess the mimetype
        $filetype = explode('.', $filename);
        $filetype = $filetype[count($filetype) - 1];
        switch($filetype){
            case 'pdf':
                $mt = 'application/pdf';
                break;
            case 'gif':
                $mt = 'image/gif';
                break;
            case 'htm':
            case 'html':
                $mt = 'text/html';
                break;
            case 'jpg':
            case 'jpeg':
            case 'jpe':
                $mt = 'image/jpeg';
                break;
            case 'mpeg':
            case 'mpg':
            case 'mpe':
                $mt = 'video/mpeg';
                break;
            case 'mov':
            case 'qt':
                $mt = 'video/quicktime';
                break;
            case 'rtf':
                $mt = 'application/rtf';
                break;
            case 'movie':
                $mt = 'video/x-sgi-movie';
                break;
            case 'txt':
                $mt = 'text/plain';
                break;
            case 'avi':
                $mt = 'video/ms-video';
                break;
            case 'wav':
                $mt = 'audio/x-wav';
                break;
            case 'zip':
                $mt = 'application/zip';
                break;
            case 'zip':
                $mt = 'application/zip';
                break;
            case 'doc':
                $mt = 'application/msword';
                break;
            case 'xls':
                $mt = 'application/vnd.ms-excel';
                break;
            case 'js':
                $mt = 'application/x-javascript';
                break;
            case 'swf':
                $mt = 'application/x-shockwave-flash';
                break;
            case 'mp3':
                $mt = 'audio/mpeg';
                break;
            case 'bmp':
                $mt = 'image/bmp';
                break;
            case 'png':
                $mt = 'image/png';
                break;
            case 'css':
                $mt = 'text/css';
                break;
            case 'ppt':
                $mt = 'application/vnd.ms-powerpoint';
                break;
            default:
                $mt = 'application/octet-stream'; // default mime type
                break;
        }
        return $mt;
    }
}
/**
* redirects user if GET['redirect'] isset
* @author Alex Haslberger
* @return void
*/
function redirect($url = ''){
  if ($url <> '') {
    header('Location: '.$url);
  }
  elseif(isset($_GET['redirect'])){
        switch ($_GET['redirect']) {
            // help links redirect
            case 'help':
                header('Location: '.get_helplink($_GET['link']));
                exit;
        }
    }
}
/**
* get tabs area at top of content
* @author Alex Haslberger
* @param array $tabs array containing all tabs data
* @return string $html
*/
function get_tabs_area($tabs) {
    // left tabs
    $left_tabs = '';
    foreach ($tabs as $tab) {
        if ($tab['position'] != 'left') {
            continue;
        }
        $left_tabs .= get_single_tab($tab);
    }
    // right tabs
    $right_tabs = '';
    foreach ($tabs as $tab) {
        if ($tab['position'] != 'right') {
            continue;
        }
        $tab['type'] = 'link';
        //$right_tabs .= get_single_tab($tab);
        $right_tabs .= get_single_button($tab).'&nbsp;';
    }
    //$right_tabs .= '&nbsp;';
    //$right_tabs .= get_single_button(array('type' => 'link', 'href' => 'javascript:alert(\'not implemented yet\');', 'active' => false, 'text' => __('Print'), 'id' => 'print', 'target' => '_self'));
    //$right_tabs .= '&nbsp;';
    $right_tabs .= get_single_button(array('type' => 'link', 'href' => '../index.php?redirect=help&amp;link='.$GLOBALS['module'], 'active' => false, 'text' => __('?'), 'id' => 'help', 'target' => '_blank'));
    // tab area
    $html = '
        <!-- begin tab_selection -->
        <div class="topline"></div>
        <div class="tabs_area">
            <div class="tabs_area_modname">
                <span class="tabs_area_modname">'.__(ucfirst($GLOBALS['module'])).'</span>
            </div>
            <div class="tabs_area_left">
                <span class="tabs_area">'.$left_tabs.'</span>
            </div>
            <div class="tabs_area_right">
                <span class="tabs_area">'.$right_tabs.'</span>
            </div>
        </div>
        <div class="tabs_bottom_line"></div>
        <div class="hline"></div>
        <!-- end tab_selection -->
';
    return $html;
}
/**
* get single tab
* @author Alex Haslberger
* @param array $tab array containing all tab data
* @return string $html
*/
function get_single_tab($tab){
    $html  = '<a ';
    $html .= 'href="'.$tab['href'].'" ';
    if($tab['active']){
        $html .= 'class="tab_active" ';
    }
    else{
        $html .= 'class="tab_inactive" ';
    }
    $html .= 'id="'.$tab['id'].'" ';
    $html .= 'target="'.$tab['target'].'" ';
    $html .= 'title="'.$tab['text'].'" ';
    $html .= '>';
    $html .= $tab['text'];
    $html .= '</a>';
    return $html;
}
/**
* get single button
* @author Alex Haslberger
* @param array $tab array containing all tab data
* @return string $html
*/
function get_single_button($tab){
    switch($tab['type']){
        case 'link':
            $title = isset($tab['title']) ? $tab['title'] : $tab['text'];
            $html  = '<a ';
            $html .= 'href="'.$tab['href'].'" ';
            if($tab['active']){
                $html .= 'class="navbutton navbutton_active" ';
            }
            else{
                $html .= 'class="navbutton navbutton_inactive" ';
            }
            $html .= 'id="'.$tab['id'].'" ';
            $html .= 'target="'.$tab['target'].'" ';
            $html .= 'title="'.$title.'" ';
            $html .= '>';
            $html .= $tab['text'];
            $html .= '</a>';
            break;
        case 'button':
            // not yet implemented
            break;
    }
    return $html;
}
/**
* get buttons area at top of content
* @author Alex Haslberger
* @param array $buttons array containing all buttons data
* @param string $oncontextmenu javascript code
* @return string $html
*/
function get_buttons_area($buttons, $oncontextmenu = ''){
    $html = '
    <div class="nav_area"';
    if($oncontextmenu){
        $html .= ' '.$oncontextmenu;
    }
    $html .= '>
        <span class="nav_area">
        '.get_buttons($buttons,'span').'
        </span>
    </div>
    ';
    return $html;
}
/**
* get buttons area at bottom of content
* @author Alex Haslberger
* @param array $buttons array containing all buttons data
* @return string $html
*/
function get_bottom_buttons_area($buttons){
    $html = '
    <div class="buttons_bottom" style="margin-top:5px;">
    '.get_buttons($buttons).'
    </div>
    ';
    return $html;
}
/**
* get buttons
* @author Alex Haslberger
* @param array $buttons array containing all buttons data
* @return string $html
*/
function get_buttons($buttons,$span=''){
    $html = '';
    foreach($buttons as $button){
        switch($button['type']){
            case 'submit':
            case 'button':
                $class = '';
                if(isset($button['stopwatch'])){
                    $class = $button['stopwatch'] == 'started' ? 'navbutton buttonstop' : 'navbutton buttonstart';
                }
                elseif(isset($button['active'])){
                    $class = $button['active'] ? 'button' : 'button2';
                }
                elseif(!isset($button['active'])){
                    $class = 'button2';
                }
                $html .= '<input type="'.$button['type'].'" name="'.$button['name'].'" value="'.xss($button['value']).'" class="'.$class.'"';
                if(isset($button['onclick'])){
                    $html .= ' onclick="'.$button['onclick'].'"';
                }
                $html .= ' />&nbsp;&nbsp;';
                break;
            case 'hidden':
                $html .= '<input type="hidden" name="'.$button['name'].'" value="'.xss($button['value']).'" />';
                break;
            case 'link':
                $class = '';
                if(isset($button['active'])){
                    $class = $button['active'] ? 'navbutton navbutton_active' : 'navbutton navbutton_inactive';
                }
                elseif(isset($button['stopwatch'])){
                    $class = $button['stopwatch'] == 'started' ? 'navbutton buttonstop' : 'navbutton buttonstart';
                }
                $html .= '<a href="'.$button['href'].'" class="'.$class.'">'.$button['text'].'</a>&nbsp;&nbsp;';
                break;
            case 'form_start':
                if($span)$html.='</span>';
                $html .= '<form style="display:inline" action="'.xss($_SERVER['PHP_SELF']).'" method="post" enctype="multipart/form-data" ';
                if(isset($button['onsubmit'])){
                    $html .= ' onsubmit="'.$button['onsubmit'].'"';
                }
                if(isset($button['enctype'])){
                    $html .= ' enctype="'.$button['enctype'].'"';
                }
                if(isset($button['name'])){
                    $html .= ' name="'.$button['name'].'"';
                }
                $html .= '>';
                 if($span)$html.='<span class="nav_area">';
                $html .= hidden_fields($button['hidden']);
                break;
            case 'form_end':
                if($span)$html.='</span>';
                $html .= '</form>';
                if($span)$html.='<span class="nav_area">';
                break;
            case 'select':
                $html .= '<select name="'.$button['name'].'"';
                if(isset($button['onchange'])){
                    $html .= ' onchange="'.$button['onchange'].'"';
                }
                $html .= '>';
                foreach($button['options'] as $value => $text) {
                    $html .= '<option value="'.xss($value).'"';
                    if ($button['selected'] == $value){
                        $html .= ' selected="selected"';
                    }
                    $html .= '>'.$text.'</option>';
                }
                $html .= '</select>';
                break;
            case 'separator':
                $html .= '<span class="strich">&nbsp;</span>';
                break;
            case 'text':
                $html .= $button['text'];
                break;
            default:
                $html .= $button['type'];
                break;
        }
    }
    return $html;
}
/**
* get message "no hits found"
* @author Alex Haslberger
* @param string $module module name
* @return string $html
*/
function get_no_hits_found_message($module){
    $html = '
    <div class="boxHeader">'.__($module).'</div>
    <div class="boxContent">'. __('there were no hits found.').'</div>
    <br style="clear:both"/><br/>
    ';
    return $html;
}
/**
* get header for box
* @author Alex Haslberger
* @param string $headline headline for box header
* @param string $anker_name name of the anker
* @param array $right_data data containing information for the right content
* @return string $html
*/
function get_box_header($headline, $anker_name, $right_data){
    $html = '
    <a name="'.$anker_name.'" id="'.$anker_name.'"></a>
    <div class="boxHeaderLeft">'.$headline.'</div>
    <div class="boxHeaderRight">';
    switch($right_data['type']){
        case 'anker':
            $html .= '<a class="formBoxHeader" href="#'.$right_data['anker_target'].'">'.$right_data['link_text'].'</a>';
            break;
        case 'text':
            $html .= $right_data['text'];
            break;
    }
    $html .= '</div><br style="clear:both"/>';
    return $html;
}
/**
* get form body
* @author Alex Haslberger
* @param string $form_content all content of form (fields ...)
* @return string $html
*/
function get_form_body($form_content){
    $html = '
    <div class="formbody">
    <fieldset style="margin:0">
    <legend></legend>
    '.$form_content.'
    </fieldset></div>';
    return $html;
}
/**
* get form content
* @author Alex Haslberger
* @param array $form_fields data for all form fields
* @return string $html
*/
function get_form_content($form_fields){
    $html = '<br style="clear:both"/>';
    foreach($form_fields as $field){
        // strip not allowed chars from id
        if(!$field['id'])$id = preg_replace('/\[\]/', '', $field['name']);
        else $id=$field['id'];
        $label_class = isset($field['label_class']) ? $field['label_class'] : 'formbody';
        if (isset($field['label'])) {
            $html .= '<label class="'.$label_class.'"';
            if(isset($id)){
                $html .= ' for="'.$id.'"';
            }
            $html .= '>'.$field['label'].'</label>';
        }
        $styles = array();
        switch ($field['type']) {
            // string
            case 'string':
                $html .= $field['text'];
                break;
            // select
            case 'select':
                if(isset($field['style'])){
                    $styles = explode(';', $field['style']);
                }
                $html .= '<select id="'.$id.'" name="'.$field['name'].'" class="options"';
                if(isset($field['onchange'])){
                    $html .= ' onchange="'.$field['onchange'].'"';
                }
                if(isset($field['multiple']) && $field['multiple'] == true){
                    $html .= ' multiple="multiple" size="5"';
                }
                if(isset($field['width'])){
                    $styles[] = 'width:'.$field['width'];
                }
                if(count($styles)){
                    $html .= ' style="'.implode(';', $styles).'"';
                }
                $html .= "><option value=''></option>\n";
                foreach($field['options'] as $option){
                    $html .= '<option value="'.$option['value'].'"';
                    if(isset($option['selected']) && $option['selected'] == true){
                        $html .= ' selected="selected"';
                    }
                    $html .= '>'.$option['text'].'</option>';
                }
                $html .= '</select>';
                if(isset($field['text_after'])){
                    $html .= '&nbsp;'.$field['text_after'];
                }
                break;
            // text
            case 'text':
                $html .= '<input id="'.$id.'" type="text" name="'.$field['name'].'" class="options" value="';
                if(isset($field['value'])){
                    $html .= xss($field['value']);
                }
                $html .= '"';
                if(isset($field['readonly']) && $field['readonly'] == true){
                    $html .=  "readonly='readonly'";
                    $styles[] = 'background-color:'.PHPR_BGCOLOR3;
                }
                if(isset($field['width'])){
                    $styles[] = 'width:'.$field['width'];
                }
                if(count($styles)){
                    $html .= ' style="'.implode(';', $styles).'"';
                }
                if(isset($field['onblur'])){
                    $html .= ' onblur="'.$field['onblur'].'"';
                }
                $html .= '/>';
                if(isset($field['label_right'])){
                    $html .= $field['label_right'];
                }
                break;
            // hidden
            case 'hidden':
                $html .= '<input id="'.$id.'" type="hidden" name="'.$field['name'].'" value="';
                if(isset($field['value'])){
                    $html .= xss($field['value']);
                }
                $html .= '"/>';
                break;
            // password
            case 'password':
                $html .= '<input id="'.$id.'" type="password" name="'.$field['name'].'" class="options" value="';
                if(isset($field['value'])){
                    $html .= xss($field['value']);
                }
                $html .= '"';
                if(isset($field['readonly']) && $field['readonly'] == true){
                    $html .= ' readonly style="background-color:'.PHPR_BGCOLOR3.';"';
                }
                $html .= '/>';
                break;
            // textarea
            case 'textarea':
                $html .= '<textarea id="'.$id.'" name="'.$field['name'].'" cols="40" rows="4" class="options"';
                if(isset($field['width'])){
                    $styles[] = 'width:'.$field['width'];
                }
                if(isset($field['height'])){
                    $styles[] = 'height:'.$field['height'];
                }
                if(count($styles)){
                    $html .= ' style="'.implode(';', $styles).'"';
                }
                $html .= '>';
                if(isset($field['value'])){
                    $html .= $field['value'];
                }
                $html .= '</textarea><br />';
                break;
                //textarea_answ
            // checkbox
            case 'checkbox':
                $html .= '<input id="'.$id.'" type="checkbox" name="'.$field['name'].'" class="options"';
                if(isset($field['readonly']) && $field['readonly'] == true){
                    $html .= ' disabled style="width:15px; background-color:'.PHPR_BGCOLOR3.'"';
                     }
                 else $html.='style="width:15px;"';
                if(isset($field['checked']) && $field['checked'] == true){
                    $html .= ' checked ="'.$field['checked'].'"';
                }
                 $html.=' />';
                if(isset($field['label_right'])){
                    $html .= $field['label_right'];
                }
                break;
            // file
            case 'file':
                $html .= '<input id="'.$id.'" type="file" name="'.$field['name'].'" class="options"/>';
                break;
            // parsed html
            case 'parsed_html':
                $html .= $field['html'];
                break;
        }
        unset($styles);
        if (!in_array($field['type'], array('hidden'))) {
            $html .= '<br style="clear:both" />';
        }
    }
    return $html;
}
/**
* get the css path
*
* @return string
*/
function get_css_path() {
    global $skin;
    if (isset($skin) && $skin != '') {
        $ret = $skin;
    }
    else if (defined(PHPR_SKIN) && PHPR_SKIN != '') {
        $ret = PHPR_SKIN;
    }
    else {
        $ret = 'default';
    }
    $ret = '/'.PHPR_INSTALL_DIR.'/layout/'.$ret.'/';
    $ret = str_replace('//', '/', $ret);
    return $ret;
}

// module name and table name may differ -> here is the translation table
$tablename['contacts']    = 'contacts';
$tablename['projects']    = 'projekte';
$tablename['notes']       = 'notes';
$tablename['helpdesk']    = 'rts';
$tablename['rts']         = 'rts';
$tablename['todo']        = 'todo';
$tablename['files']       = 'dateien';
$tablename['mail']        = 'mail_client';
$tablename['links']       = 'db_records';
$tablename['calendar']    = 'termine';
$tablename['bookmarks']   = 'lesezeichen';
$tablename['forum']       = 'forum';
$tablename['filemanager'] = 'dateien';

/**
* returns names of commonb db fields like ID or parent
* @author Albrecht Guenther
* @param string module
* @return array
*/
function get_db_fieldname($module,$fieldname) {
    global $db_fieldnames;
    $ret_fieldname = isset($db_fieldnames[$module][$fieldname]) ? $db_fieldnames[$module][$fieldname] : $fieldname;
    return $ret_fieldname;
}

/**
 * get vars values from constants
 *
 * @author Alex Haslberger
 * @return void
 */
function constants_to_vars() {
    $constants = array(
            'VERSION',
            'DB_TYPE',
            'DB_HOST',
            'DB_USER',
            'DB_PASS',
            'DB_NAME',
            'DB_PREFIX',
            'LOGIN',
            'PW_CHANGE',
            'PW_CRYPT',
            'GROUPS',
            'ACC_ALL_GROUPS',
            'ACC_DEFAULT',
            'ACC_WRITE_DEFAULT',
            'LDAP',
            'TIMEZONE',
            'SESSION_TIME_LIMIT',
            'MAXHITS',
            'LOGS',
            'HISTORY_LOG',
            'ERROR_REPORTING_LEVEL',
            'SUPPORT_PDF',
            'SUPPORT_HTML',
            'SUPPORT_CHART',
            'DOC_PATH',
            'ATT_PATH',
            'FILTER_MAXHITS',
            'TODO',
            'VOTUM',
            'LINKS',
            'CALENDAR',
            'EVENTS_PAR',
            'GROUPVIEWUSERHEADER',
            'MAIL_NEW_EVENT',
            'PROFILE',
            'RESSOURCEN',
            'REMINDER',
            'REMIND_FREQ',
            'SMS_REMIND_SERVICE',
            'TIMECARD',
            'CONT_USRDEF1',
            'CONT_USRDEF2',
            'CONTACTS_PROFILES',
            'CALLTYPE',
            'NOTES',
            'TODO_OPTION_ACCEPTED',
            'QUICKMAIL',
            'FAXPATH',
            'MAIL_SEND_ARG',
            'MAIL_EOL',
            'MAIL_EOH',
            'MAIL_MODE',
            'MAIL_AUTH',
            'SMTP_HOSTNAME',
            'LOCAL_HOSTNAME',
            'POP_ACCOUNT',
            'POP_PASSWORD',
            'POP_HOSTNAME',
            'SMTP_ACCOUNT',
            'SMTP_PASSWORD',
            'DAT_REL',
            'DAT_CRYPT',
            'FILEMANAGER_NOTIFY',
            'FORUM',
            'FORUM_TREE_OPEN',
            'FORUM_NOTIFY',
            'RTS',
            'RTS_MAIL',
            'RTS_DUEDATE',
            'RTS_CUST_ACC',
            'CHAT',
            'ALIVEFILE',
            'CHATFILE',
            'CHATFREQ',
            'ALIVEFREQ',
            'MAX_LINES',
            'CHAT_TIME',
            'CHAT_NAMES',
            'SKIN',
            'DEFAULT_SIZE',
            'CUR_SYMBOL',
            'BGCOLOR1',
            'BGCOLOR2',
            'BGCOLOR3',
            'BGCOLOR_MARK',
            'BGCOLOR_HILI',
            'LOGO',
            'HP_URL',
            'TR_HOVER'
        );

    foreach ($constants as $constant) {
        if (defined('PHPR_'.$constant)) {
            $GLOBALS[strtolower($constant)] = constant('PHPR_'.$constant);
        }
    }
    // some vars have been renamed to english
    global $login_kurz, $lesezeichen, $tagesanfang, $tagesende, $projekte, $adressen, $dateien;
    if (defined('PHPR_LOGIN_SHORT')){
        $login_kurz  = PHPR_LOGIN_SHORT;
    }
    if (defined('PHPR_BOOKMARKS')){
        $lesezeichen = PHPR_BOOKMARKS;
    }
    if (defined('PHPR_DAY_START')){
        $tagesanfang = PHPR_DAY_START;
    }
    if (defined('PHPR_DAY_END')){
        $tagesende = PHPR_DAY_END;
    }
    if (defined('PHPR_PROJECTS')){
        $projekte = PHPR_PROJECTS;
    }
    if (defined('PHPR_CONTACTS')){
        $adressen = PHPR_CONTACTS;
    }
    if (defined('PHPR_FILEMANAGER')){
        $dateien = PHPR_FILE_PATH;
    }
}

?>
