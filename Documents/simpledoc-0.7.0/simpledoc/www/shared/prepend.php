<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require dirname(__FILE__).'/sugolib4.php';

define('ROOT', dirname(dirname(__FILE__).'../'));
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('magic_quotes_runtime', 0);
ini_set('zend.ze1_compatibility_mode', 1);
set_error_handler('raiseError');

if (ini_get('magic_quotes_gpc')) {
    stripQuotes($_GET);
    stripQuotes($_POST);
    stripQuotes($_REQUEST);
    stripQuotes($_COOKIE);
}

// SimpleDoc

include ROOT.'/shared/io.php';
include ROOT.'/shared/Node.php';
include ROOT.'/config.php';

global $CONTENT, $SORT, $CHMOD_FILE, $CHMOD_DIR;
$CONTENT = 'content';
$PUBLISH = $CONFIG['publish_dir'];
$SORT = '.sort';
$CHMOD_FILE = 666;
$CHMOD_DIR = 777;

// user
global $USERNAME;
$USERNAME = null;

if (cookie('username') && cookie('password')) {
    if (cookie('username') == $CONFIG['username']
        && cookie('password') == md5($CONFIG['password'])) {
        $USERNAME = $CONFIG['username'];
    }
}

if (!$USERNAME) {
    if (basename($_SERVER['PHP_SELF']) != 'login.php') {
        redirect('login.php');
    }
}

// ----------
// HELP FUNCS
// ----------

function send_zip($dir, $filename, $new_dir = null) {
    include_once ROOT.'/shared/zip.php';
    $files = IoDir::readFull($dir);

    $zip = new zip;
    foreach ($files as $file) {
        $file2 = $new_dir ? $new_dir . substr($file, strlen($dir)) : $file;
        if (IoDir::exists($file)) {
            $zip->add_dir($file2);
        } else if (IoFile::exists($file)) {
            $zip->add_file(IoFile::read($file), $file2);
        }
    }

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $zip->get_file();
    exit;
}

function extract_zip($file, $to) {
    
    global $CHMOD_FILE, $CHMOD_DIR;
    include_once ROOT.'/shared/zip.php';
    
    $zip = new zip;
    $list = $zip->get_List($file);
    if (!count($list)) return;
    
    $root = $list[0]['filename'];
    $root = substr($root, 0, strpos($root, '/'));
    IoDir::create($to.'/'.$root, $CHMOD_DIR);
    
    $list2 = array();
    foreach ($list as $v) {
        $v['filename'] = fix_path($v['filename']);
        $list2[substr_count($v['filename'], '/')][] = $v;
    }
    
    $a = array_keys($list2);
    sort($a);

    foreach ($a as $level) {
        foreach ($list2[$level] as $v) {
            if ($v['folder']) {
                if ($v['filename'] != $root) {
                    IoDir::create($to.'/'.$v['filename'], $CHMOD_DIR);
                }
            }
            else IoFile::create($to.'/'.$v['filename'], $CHMOD_FILE);
        }
    }

    $er = error_reporting(E_ALL ^ E_NOTICE);
    $zip->extract($file, $to);
    error_reporting($er);
}

function get_name($s) {
    $s = str_replace('\\', '/', $s);
    if (strpos($s, '/') === false) { return $s; }
    return substr($s, strrpos($s, '/')+1);
}

function get_readable_size($bytes) {
    $base = 1024;
    $suffixes = array(' B', ' KB', ' MB', ' GB', ' TB', ' PB', ' EB');
    $usesuf = 0;
    $n = (float) $bytes;
    while ($n >= $base) {
        $n /= (float) $base;
        ++$usesuf;
    }
    $places = 2 - floor(log10($n));
    $places = max($places, 0);
    return number_format($n, $places, '.', '') . $suffixes[$usesuf];
}

function fetch_document($id) {
    global $CONTENT, $CONFIG;
    
    $path = $CONTENT.'/'.$id;
    $html = IoFile::read($path);

    if (preg_match('#<h1>(.+)</h1>#i', $html, $matches)) {
        $title = $matches[1];
    } else {
        $title = substr($path, strlen(dirname($path).'/'));
        $title = substr($title, -5) == '.html' ? substr($title, 0, strlen($title)-5) : $title;
    }

    $Page = new Template(ROOT.'/shared/publish/document.tpl');
    $Page->setArray(array(
        'encoding' => $CONFIG['encoding'],
        'title' => $title,
        'html' => $html
    ));
    return $Page->fetch();
}

function fetch_document_tree($id) {
    global $CONTENT, $CONFIG;
    
    $path = $CONTENT.'/'.$id;
    $html = IoFile::read($path);

    if (preg_match('#<h1>(.+)</h1>#i', $html, $matches)) {
        $title = $matches[1];
    } else {
        $title = substr($path, strlen(dirname($path).'/'));
        $title = substr($title, -5) == '.html' ? substr($title, 0, strlen($title)-5) : $title;
    }

    $Page = new Template(ROOT.'/shared/publish/document.tpl');
    $Page->setArray(array(
        'encoding' => $CONFIG['encoding'],
        'title' => $title,
        'html' => $html,
        'tree' => true
    ));
    return $Page->fetch();
}

// Get timezeone on current server, for example 'GMT+01:00', 'GMT-06:00'
// Author: Cezary Tomczak [www.gosu.pl]
function getTimezone() {
    $t1 = mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('y'));
    $t2 = mktime(gmdate('H'), gmdate('i'), gmdate('s'), gmdate('m'), gmdate('d'), gmdate('y'));
    $t = $t1 - $t2;
    $t = floor($t/60);
    $sign = $t>=0 ? '+':'-';
    if ($t<0) $t = -$t;
    $s1 = str_pad(floor($t/60), 2, '0', STR_PAD_LEFT);
    $s2 = str_pad($t % 60, 2, '0', STR_PAD_LEFT);
    return 'GMT'.$sign.$s1.':'.$s2;
}

function build_tree(&$tree, $root, $path = null) {
    if (!isset($path)) $path = $root;
    global $SORT;
    $nodes = IoDir::read($path, array($SORT));
    $names = array();
    foreach ($nodes as $v) {
        $names[] = (strpos($v, '/') !== false ? substr($v, strrpos($v, '/')+1) : $v);
    }
    $sort = IoFile::read($path.'/.sort');
    $sort = $sort ? explode("\n", $sort) : array();
    foreach ($sort as $k => $v) { $sort[$k] = trim($v); }
    $nodes2 = array();
    foreach ($sort as $v) {
        $key = array_search($v, $names);
        $nodes2[] = $nodes[$key];
    }
    sort($names);
    sort($sort);
    if (count($names) != count($sort) || count(array_diff($names, $sort))) {
        return trigger_error("build_tree() failed, $path/.sort contains invalid data", E_USER_ERROR);
    }
    foreach ($nodes2 as $node) {
        $id = substr($node, strlen($root.'/'));
        if (IoDir::exists($node)) {
            $tree[$id] = array();
            build_tree($tree[$id], $root, $node);
        } else {
            $tree[$id] = null;
        }
    }
}

// fix xmlhttprequest charset bug , utf-8 => windows-1250
function fix_charset($str) {
    global $CONFIG;
    if ($CONFIG['encoding'] == 'windows-1250') {
        $a = array('%u0105', '%u0107', '%u0119', '%u0142', '%u0144', '%u015B', 'ó', '%u017A', '%u017C');
        $b = array('¹', 'æ', 'ê', '³', 'ñ', '', 'ó', '', '¿');
        $c = array('%u0104', '%u0106', '%u0118', '%u0141', '%u0143', '%u015A', 'Ó', '%u0179', '%u017B');
        $d = array('¥', 'Æ', 'Ê', '£', 'Ñ', '', 'Ó', '', '¯');
        $str = str_replace($a, $b, $str);
        $str = str_replace($c, $d, $str);
    }
    return $str;
}

// remove unsafe chars when saving config
function config_safe($str) {
    $str = strip_tags($str);
    $str = str_replace("'", "\\'", $str);
    return $str;
}

function fix_path($s) {
    $s = str_replace('\\', '/', $s);
    if (substr($s, -1) == '/') $s = substr($s, 0, -1);
    return $s;
}

?>