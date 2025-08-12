<?php

// filemanager_data.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Albrecht Guenther, $Author: nina $
// $Id: filemanager_data.php,v 1.47.2.2 2005/07/28 13:39:18 nina Exp $

// check whether the lib has been included - authentication!
if (!defined("lib_included")) die("Please use filemanager.php!");

// check_role
if (check_role("filemanager") < 2) die("You are not allowed to do this!");


$include_path2 = $path_pre."lib/permission.inc.php";
include_once($include_path2);
$include_path3 = $path_pre."lib/access.inc.php";
include_once($include_path3);

$userfile      = $_FILES['userfile']['tmp_name'];
$userfile_name = $_FILES['userfile']['name'];
$userfile_size = $_FILES['userfile']['size'];

if (!$_POST['filename']) $_POST['filename'] = $userfile_name;
//echo $userf_tname;
//print_r($userfile);
// assign acc_group and category
$acc = assign_acc($acc, 'dateien');

if ($delete_b <> '' || $action == 'delete') {
    // delete many entries at once (contextmenu)
    if (isset($ID_s)) $ID = $ID_s;
    manage_delete_records($ID, $module);
}
else if ($modify_b <> '' and $ID > 0) {
    update_record($ID);
}
else if ($create_b <> '') {
    create();
}
else if ($action == 'lockfile') {
    $result = db_query("SELECT von, acc, acc_write
                          FROM ".DB_PREFIX."dateien
                         WHERE ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    if ($row[0] == 0) die('no entry found.');
    if ($row[0] <> $user_ID && (strpos($row[1], $_SESSION['user_kurz']) === false ||
        strpos($row[1], $_SESSION['user_kurz']) !== false && strpos($row[2], 'w') === false)) {
        die('You are not privileged to do this!');
    }
    if (eregi("xxx", $ID)) $ID = substr($ID, 14);
    $error = '';
    // define the locking status
    $locked = define_locking_status($ID);
    // finally: the db action :)
    if (!$error) {
        $result = db_query("update ".DB_PREFIX."dateien
                               set lock_user = '$locked'
                             where ID = '$ID'") or db_die();
    }
}


function update_record() {
    global $ID, $acc, $profil, $persons, $kat, $new_category, $typ, $parent, $div2,
           $dbIDnull, $user_ID, $remark, $dbTSnull, $project, $filename, $filepath,
           $userfile, $userfile_size, $userfile_name, $c_m, $cryptstring, $cryptstring2,
           $locked, $contact, $versioning, $acc_write, $user_ID, $user_kurz, $user_group, $tablename,$new_sub_dir;

    assign_cat();
    // fetch missing values from old record
    $result = db_query("select ID,von,filename,remark,kat,acc,datum,filesize,gruppe,tempname,
                               typ,parent,div2,pw,acc_write,version,lock_user,contact,userfile
                          from ".DB_PREFIX."dateien
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // this field is only for uploads displayed
	if ($new_sub_dir <> '') $parent = set_new_subdir($parent, $new_sub_dir);


    // *******************
    // update or move item
 
        // if it's a file and new upload -> replace file
        if (ereg("f", $typ) and $userfile <> "none" and $userfile) {

            // check whether it's really an upload
            check_upload($userfile);

            // set filename and build string for db query
            $stringfilename = "filename='".addslashes($filename)."',userfile='$userfile_name',";
            $stringfilesize = "filesize='$userfile_size',";
            //$file = $row[9];

            $oldfilename = slookup($tablename['filemanager'], 'tempname', 'ID', $ID);
            if ($oldfilename != '') {
                // delete old file
                delete_file($oldfilename);
            }
            else {
                $oldfilename = rnd_string();
            }

            // action: copy file!
            // first case: no file content encryption
            if (!$cryptstring) {
                // fetch file from tmp directory and move it into specified dir ...
                copy_file($userfile, '', $oldfilename, PHPR_FILE_PATH);
            }
            // oh, crypting!! :-)
            else {
                copy_crypt($userfile, '', $oldfilename, PHPR_FILE_PATH, $cryptstring, $cryptstring2);
                $cryptstring = encrypt($cryptstring, $cryptstring);
            }

            // last action - count the version number one up
            // exception: this file has a flag for versioning
            $result = db_query("update ".DB_PREFIX."dateien
                                   set version = version+1
                                 where ID = '$ID'") or db_die();
        }

        // next case: move or update existing file -> carry password
        if (ereg("f", $typ) and ($userfile == "none" or !$userfile)) {
            $stringfilename = "filename='".addslashes($filename)."',";
            $cryptstring    = $row[13];
        }
        // for dir and link: assign new filename
        else if (!ereg("f", $typ)) {
            $stringfilename = "filename='$filename',tempname='$filepath',";
        }

        // following parameters can only be set by the author: 1. read access, 2. write access, 3. directory
        if ($row[1] == $user_ID) {
            $acc_string       = " acc='$acc',";
            $acc_write_string = "acc_write = '$acc_write',";
            $parent_string    = "parent='$parent',";

            // if versioning is allowed, add a 'v' to the filetype
            if (ereg("f", $row[10])) {
                if ($versioning) $version_string = "typ = 'fv',";
                else             $version_string = "typ = 'f',";
            }
        }
        // otherwise a dummy string :)
        else {
            $acc_string       = "";
            $acc_write_string = "";
            $parent_string    = "";
            $versionstring    = "";
        }

        // define the locking status
        $locked = define_locking_status($ID);
        // finally: the db action :)
        $result = db_query(xss("update ".DB_PREFIX."dateien
                                set $stringfilename
                                    $stringfilesize
                                    remark = '".addslashes($remark)."',
                                    kat = '$kat',
                                    $acc_string
                                    $parent_string
                                    div2 = '$div2',
                                    datum = '$dbTSnull',
                                    pw = '$cryptstring',
                                    $acc_write_string
                                    $version_string
                                    lock_user = '$locked',
                                    contact = '$contact'
                                where ID = '$ID'")) or db_die();
    
}


//insert a link or a directory
function create() {
    global $userfile, $userfile_size, $userfile_name, $sid, $kat, $remark, $sql_user_group,
           $overwrite, $user_ID, $parent, $path_pre, $dbIDnull,$acc, $dbTSnull, $user_group,
           $typ, $project, $cryptstring, $cryptstring2, $locked, $file, $acc_write, $versioning,
           $new_sub_dir, $contact, $sql_fieldstring, $sql_valuestring, $filepath;
    // assign acc_group and category
    // $acc = insert_access($module, 'acc');
    // upload the file
    if ($userfile <> '' and $userfile_size <> '') {
        $filepath = insert_file();
        //$filename = $userfile_name;
    }
    // file versioning?
    if(isset($versioning)) $typ = $typ.'v';

    sqlstrings_create();

    // this field is only for uploads displayed
    if ($new_sub_dir <> '') $parent = set_new_subdir($parent, $new_sub_dir);

    // everythings fine? -> insert record into database
    $result = db_query(xss("insert into ".DB_PREFIX."dateien
                                        (   ID,        von,    pw,   acc,        gruppe,     filesize,  tempname, userfile,   typ,   parent,       acc_write,version, ".$sql_fieldstring.")
	values ($dbIDnull,'$user_ID','$cryptstring','$acc','$user_group','$userfile_size','$filepath','$userfile_name','$typ','$parent','$acc_write', '0', ".$sql_valuestring.")")) or db_die();
}


// insert a new file
function insert_file() {
    global $parent, $sql_user_group, $userfile, $userfile_size, $userfile_name, $cryptstring, $cryptstring2;

    // loop over all objects with the same name in the same virtual directory
    $result = db_query("select ID, filename, tempname
                          from ".DB_PREFIX."dateien
                         where filesize > 0 and
                               parent like '$parent' and
                               $sql_user_group") or db_die();
    while ($row = db_fetch_row($result)) {
        // same name? -> delete file
        if ($row[1] == $userfile_name) {
            // check if overwriting is o.k.
            check_overwrite();
            // first delete old record ...
            $result = db_query("delete from ".DB_PREFIX."dateien
                                      where ID = '$row[0]'") or db_die();
            // ... then the file itself
            delete_file($row[2]);
        }
    }
    // scramble filename
    $newfilename = rnd_string();

    // first case: no file content encryption
    if (!$cryptstring) {
        // fetch file from tmp directory and move it into specified dir ...
        copy_file($userfile, "", $newfilename, PHPR_FILE_PATH);
    }
    // oh, crypting!! :-)
    else {
        copy_crypt($userfile, "", $newfilename, PHPR_FILE_PATH, $cryptstring, $cryptstring2);
        $cryptstring = encrypt($cryptstring, $cryptstring);
    }

    // ... and check whether the file really went into the directory! if not: give some advices
    if (!file_exists($path_pre.PHPR_FILE_PATH."/$newfilename")) {
        die( "Oops! Something went wrong ...<br>Please check whether the file exists in the upload directory<br>
        (Maybe the webserver is not allowed to copy the file from the tmp dir into the upload dir)<br>
        and the variable dat_rel in the config has the correct value.<br>
        Typical values would be:<br> dateien = \"/usr/local/httpd/phprojekt/file\";
        and dat_rel = \"file\"; for Linux or dateien = \"c:\htdocs/phprojekt/file\"; and dat_rel = \"file\"; for windows");
    } // end check
    return $newfilename;
}


// this function encrypts the incoming file and copies into the upload dir
function copy_crypt($oldfilename, $olddir, $newfilename, $newdir, $cryptstring,$cryptstring2) {
    // first check whether the two passwords are the same
    if ($cryptstring != $cryptstring2) {
        die("<h3> ".__('Passwords dont match!')."! <a href='filemanager.php?mode=forms&action=upload'>".__('back')." ...</a></h3>");
    }

    // then create an appropiate string:
    //1. crypt the password ...
    $cryptstring = encrypt($cryptstring, $cryptstring);
    $bytes = 65536;

    // 2: string must be longer than the content piece
    for ($i = 0; $i <= floor($bytes/strlen($cryptstring)); $i++) {
        $pwnew .= $cryptstring;
    }
    // then open both files
    if ($olddir <> '') $old_path = $olddir.'/'.$oldfilename;
    else               $old_path = $oldfilename;

    if ($newdir <> '') $new_path = $newdir.'/'.$newfilename;
    else               $new_path = $newfilename;

    $old = fopen($old_path, "rb");
    $new = fopen($new_path, "w");
    // crypt the content and write it into the new file
    while($line = fread($old, $bytes)) {
        $line2 = $line ^ $pwnew;
        fputs($new, $line2);
    }
    // close both files
    fclose($old);
    fclose($new);
}


function delete_file($filename) {
    $path = PHPR_FILE_PATH."/".$filename;
    @unlink($path);
}


function copy_file($oldfilename, $olddir, $newfilename, $newdir) {
    if ($olddir <> '') $old_path = $olddir.'/'.$oldfilename;
    else               $old_path = $oldfilename;

    if ($newdir <> '') $new_path = $newdir.'/'.$newfilename;
    else               $new_path = $newfilename;

    $success = move_uploaded_file($old_path, $new_path);
    if (!$success) die("Panic - Could not copy $old_path to $new_path!<br />");
}


function check_overwrite() {
    global $overwrite, $sid, $acc, $kat, $remark;
    if (!$overwrite) {
        die(__('A file with this name already exists!').
            "! <br /><a href='./filemanager.php?mode=forms&acc=$acc&kat=$kat&remark$remark$sid'>".
            __('back')."</a>");
    }
}


function check_owner() {
    global $row, $user_ID, $sid, $acc, $kat, $remark;
    if ($row[2] <> $user_ID) {
        die(__('You are not allowed to overwrite this file since somebody else uploaded it').
            " <br /><a href='./filemanager.php?mode=forms&acc=$acc&kat=$kat&remark=$remark$sid'>".
            __('back')."</a>");
    }
}


// delete records from database
function delete_record($ID) {
    // fetch file name etc.
    $result = db_query("select ID, filename, tempname, typ, filesize
                          from ".DB_PREFIX."dateien
                         where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);

    // unlink file
    if ($row[4] > 0) unlink(PHPR_FILE_PATH."/$row[2]");

    // delete record in db
    $result2 = db_query("delete from ".DB_PREFIX."dateien
                               where ID = '$ID'") or db_die();
    // delete corresponding entry from db_record
    $result = db_query("delete from ".DB_PREFIX."db_records
                              where t_record = '$ID' and t_module = 'filemanager'") or db_die();
    if ($row[3] == "d" or $row[3] == "fv") del($row[0]); // look for files in the subdirectory
    $action = "";
}


// delete subdirectories
function del($ID) {
    $result = db_query("select ID, filename, tempname, typ, filesize
                          from ".DB_PREFIX."dateien
                         where parent = '$ID'") or db_die();
    while ($row = db_fetch_row($result)) {
        // only delete file when it is not a link
        if ($row[4] > 0) unlink(PHPR_FILE_PATH."/$row[2]");
        // delete record as such
        $result2 = db_query("delete from ".DB_PREFIX."dateien
                                   where ID = '$row[0]'") or db_die();
        if ($row[3] == "d") del($row[0]); // look for files/links etc. in the subdirectory
    }
}


// check for same record name
function check_name() {
    global $sql_user_group, $ID, $filename, $typ, $sid;
    $result = db_query("select ID, filename, typ
                          from ".DB_PREFIX."dateien") or db_die();
    while ($row = db_fetch_row($result)) {
        if ($row[0] <> $ID and $row[1] == $filename and ereg("f", $row[2])) {
            die(__('This name already exists')."! <br><a href='filemanager.php?mode=forms$sid'>".__('back')."</a>");
        }
    }
}


// assign category
function assign_cat() {
    global $kat, $new_category;
    // if no manual category is given, use the one from the select box
    if (!$kat) $kat = $new_category;
}


// check whether it's really an upload
function check_upload($userfile) {
    if (!is_uploaded_file($userfile)) die("Oops, the uploaded file is not in the upload directory!");
}


// this routine sends out an email notification to all users of the group which have access to the file
function notify_members() {
    global $acc, $filename, $userfile_name, $lib_path, $acc;

    // if the object is a filem, assign the value to $filename. For links and directories the value is already in $filename
    if ($userfile_name) $filename = $userfile_name;

    // record free for all users of this group?
    if ($acc == "group") $acc = "all";
    // or personal?  -> end this routine
    else if ($acc == "private") return 1;

    // include the library from lib
    include_once($lib_path."/email_notification.inc.php");
    // call routine to send mails with notification about the new record
    email_notification(__('Files'), $acc, $filename);
}


// the locking status needs to be defined
function define_locking_status($ID) {
    // $lock and $unlock are the respective values from the form
    global $lock, $unlock, $user_ID, $error;

    // fetch value from db
    $result = db_query("select von, lock_user, typ, acc, acc_write
                            from ".DB_PREFIX."dateien
                        where ID = '$ID'") or db_die();
    $row = db_fetch_row($result);
    // checkbox 'lock this field' has been selected by the user
    if ($lock == 'true') {
        // simply return the user_ID of this user
        if ($row[1] > 0 or ($row[2] != 'f')) {
            $error = 1;
            return 0;
        }
        else return $user_ID;
    }
    else if ($unlock == 'true') {
    // first check whether this user has the right to unlock the file
        if ($row[0] <> $user_ID and $row[1] <> $user_ID and
            strpos($row[3], $_SESSION['user_kurz']) === false and
            strpos($row[4], 'w') === false) {
            die("You are not privileged to unlock this file");
        }
        // now unlock the file by deleting the old value in this db field
        return 0;
    }
    // no action at all? return the old value from the db record ...
    else return $row[1];
}


function set_new_subdir($parent, $new_sub_dir) {
    global $user_ID, $dbIDnull, $dbTSnull, $user_group, $acc, $acc_write;

    $result = db_query(xss("insert into ".DB_PREFIX."dateien
                                   (   ID,        von,     filename,      acc,    datum,      gruppe,    typ,  parent,     acc_write,  remark)
                            values ($dbIDnull,'$user_ID','$new_sub_dir','".$acc[0]."','$dbTSnull','$user_group','d','$parent','$acc_write', '$new_sub_dir')")) or db_die();
    // fetch ID from new record
    $result = db_query("select ID
                          from ".DB_PREFIX."dateien
                         where filename='$new_sub_dir' and
                               datum = '$dbTSnull'") or db_die();
    $row = db_fetch_row($result);
    return $row[0];
}


// extra routine: notify colleagues about the new record
if (PHPR_FILEMANAGER_NOTIFY and $notify) notify_members();

if (!$justform) {
    $mode = 'view';
    include_once("./filemanager_view.php");
}
else {
    echo '<script type="text/javascript">self.opener.location.reload();self.close()</script>';
}

?>
