<?php

// filemanager_down.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: paolo $
// $Id: filemanager_down.php,v 1.19 2005/06/22 19:33:26 paolo Exp $

// include lib to fetch the sessiond data and to perform check
$path_pre = "../";
$include_path = $path_pre."lib/lib.inc.php";
include_once $include_path;

// check_role
if (check_role("filemanager") < 1) die("You are not allowed to do this!");

$ID    = xss($ID);
$mode  = xss($mode);
$mode2 = xss($mode2);

if (eregi("xxx", $ID)) $ID = substr($ID, 14);
else                   $ID = (int) $ID;

// fetch values from db
$result = db_query("select ID, userfile, acc, tempname, typ, div1, pw, lock_user,filename
                      from ".DB_PREFIX."dateien
                     where ID = '$ID' and (acc like 'system' or ((von = '$user_ID' or acc like 'group' or acc like '%\"$user_kurz\"%') and $sql_user_group))") or db_die();
$row = db_fetch_row($result);

// check privilege
if (!$row[0]) die("You are not allowed to do this");

switch (true) {
    // filename is cryptted
    case ($row[6] <> '' and !$pw):
        echo "
<form>
    <input type='text' name='pw' />
    <input type='hidden' name='ID' value='$ID' />
    <input type='hidden' name='mode' value='$mode' />
    <input type='hidden' name='mode2' value='$mode2' />
</form>
";
        exit;
        break;

    // pw is set
    case ($pw <> ''):
        $encryptstring = encrypt($pw, $pw);
        if ($encryptstring <> $row[6]) die("<b>".__('Passwords dont match!')."!</b>");
        $arr = explode(realpath(__FILE__.'/../../'), PHPR_FILE_PATH);
        $path = '../'.$arr[1].'/'.$row[3];
        break;

    // the file is locked by another user
    case (($row[7] > '0') and ($row[7] != $user_ID)):
        die("Sorry but this file locked by ".slookup('users', 'nachname,vorname', 'ID', $row[7]));
        break;

    // link
    case ($row[4] == 'l'):
        $filelink = (eregi("://", $row[3])) ? $row[3] : "file://".$row[3];
        header("Location:".$filelink);
        exit;

    // case directory
    case ($row[4] == 'd'):
        die("You cannot download a directory :-)");
        break;

    // case normal file
    default:
        $arr = explode(realpath(__FILE__.'/../../'), PHPR_FILE_PATH);
        $path = '../'.$arr[1].'/'.$row[3];
}

$name =  $row[1];
if (!$row[1]) $name = $row[8];
$name = ereg_replace("§", " ", $name);
if (!file_exists($path)) die("Panic! specified file not found ...");

// include content type definition
$include_path = $path_pre."lib/get_contenttype.inc.php";
include_once($include_path);

// Send file contents, decrypt if needed

if (!$encryptstring) {
    // Just output the file
    readfile($path);
}
else {
    // first create an appropiate string:
    //1. crypt the password,
    //$encryptstring = encrypt($encryptstring, $encryptstring);
    $bytes = 65536;
    // 2: string must be longer than the content piece
    for ($i=0; $i <= floor($bytes/strlen($encryptstring)); $i++) {
        $encryptstringnew .= $encryptstring;
    }
    // open the file
    $file = fopen($path, "rb");
    while($line = fread($file, $bytes)) {
        // shift the content back ...
        $line2 = $encryptstringnew ^ $line ;
        // output
        echo $line2;
    }
}

?>
