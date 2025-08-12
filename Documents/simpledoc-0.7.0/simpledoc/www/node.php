<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | node.php                                                           |
// | Execute tree actions.                                              |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';
include 'shared/nocache.php';

set_error_handler('logError');
$LOGERROR = 'error.txt';

$do = get('do');
$id = get('id');
$name = get('name');
$is_folder = get('is_folder');

$id = fix_charset($id);
$name = fix_charset($name);

switch ($do)
{
    case 'moveUp':
    case 'moveDown':
    case 'moveLeft':
    case 'moveRight':
    case 'insert':
    case 'insertBefore':
    case 'insertAfter':
    case 'insertInsideAtStart':
    case 'insertInsideAtEnd':
    case 'remove':
        $Node = new Node($id);
        break;
}

switch ($do)
{
    case 'moveUp':
        $Node->moveUp();
        break;
    
    case 'moveDown':
        $Node->moveDown();
        break;

    case 'moveLeft':
        $Node->moveLeft();
        break;

    case 'moveRight':
        $Node->moveRight();
        break;

    case 'insert':
        $Node->insert($name, $is_folder);
        break;

    case 'insertBefore':
        $Node->insertBefore($name, $is_folder);
        break;

    case 'insertAfter':
        $Node->insertAfter($name, $is_folder);
        break;

    case 'insertInsideAtStart':
        $Node->insertInsideAtStart($name, $is_folder);
        break;

    case 'insertInsideAtEnd':
        $Node->insertInsideAtEnd($name, $is_folder);
        break;

    case 'remove':
        $Node->remove();
        break;

    default:
        trigger_error("node.php failed, unknown action: '$do' (query_string={$_SERVER['QUERY_STRING']})", E_USER_ERROR);
        break;
}

?>