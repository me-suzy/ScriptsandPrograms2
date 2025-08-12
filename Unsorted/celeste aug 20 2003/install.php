<?php
/**
 * Project Source File
 * Celeste 2003 1.1.0 Build 0805
 * Aug 05, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2003 CelesteSoft.com. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */

function ceUse($FileName) {
  if ($FileName) include_once('./install/'.$FileName.'.php');
}

function import_install($ClassName) {
  if ($ClassName)  include_once('./install/class.'.$ClassName.'.php');
}

set_magic_quotes_runtime(0);
ob_implicit_flush();
echo str_repeat(' ', 512);

// start here
ceUse('functions');
import_install('database');
import_install('template');
import_install('modify_setting');
import_install('user');


$step = getParam('step');


  if( empty($step) || $step == 1 )
  {
    // first step, display welcome page
    $t = new templateElement(readfromfile('./install/welcome.html'));
    print $t->parse();

  }
  elseif ($step == 2)
  {
    // settings
    $t = new templateElement(readfromfile('./install/setting.html'));

    $t->set('SET_DATABASE_HOST', 'localhost');
    $t->set('SET_DATABASE_USER', 'root');
    $t->set('SET_DATABASE_PASSWORD', '');
    $t->set('SET_DATABASE_DBNAME', 'celeste2003');
    $t->set('SET_TITLE', 'Celeste 2003!');
    $t->set('SET_COOKIE_HEADER', 'ce');

    $t->set('SET_FORUM_URL', 'http://'.$_SERVER['HTTP_HOST'].str_replace('install.php?step=2', 'index.php', $_SERVER['REQUEST_URI']) );

    print $t->parse();

  }
  elseif ($step == 3)
  {
    // save settings
    @chmod($_POST['DATA_PATH'].'/settings/config.global.php', 0777);
    if(!file_exists($_POST['DATA_PATH'].'/settings/config.global.php') ||
       !is_writable($_POST['DATA_PATH'].'/settings/config.global.php') ) {
      $t = new templateElement(readfromfile('./install/error.html'));
      $t->set('error_msg', 'File "'.$_POST['DATA_PATH'].'/settings/config.global.php'.'" is NOT writable. Please check your Data Path and change its mode to 0777.');
      die($t->parse());
    }
    $mset = new modify_setting($_POST['DATA_PATH'].'/settings/config.global.php');
    $mset->set('SET_DATABASE_HOST', $_POST['SET_DATABASE_HOST']);
    $mset->set('SET_DATABASE_USER', $_POST['SET_DATABASE_USER']);
    $mset->set('SET_DATABASE_PASSWORD', $_POST['SET_DATABASE_PASSWORD']);
    $mset->set('SET_DATABASE_DBNAME', $_POST['SET_DATABASE_DBNAME']);
    $mset->set('SET_TITLE', $_POST['SET_TITLE']);
    $mset->set('SET_COOKIE_HEADER', $_POST['SET_COOKIE_HEADER']);
    $mset->set('SET_FORUM_URL', $_POST['SET_FORUM_URL']);
    $mset->save();
    writetofile('./install/config.tmp.php', $mset->settings);
    unset($mset);

    // check database settings
    $DB = new DB( 0, 0, 0, 0, 1, 0 );
    //$DB->haltOnError = 0;
    if( $DB->connect($_POST['SET_DATABASE_HOST'], $_POST['SET_DATABASE_USER'], $_POST['SET_DATABASE_PASSWORD'], $_POST['SET_DATABASE_DBNAME']) === false ) {
      $t = new templateElement(readfromfile('./install/error.html'));
      $t->set('error_msg', 'Cannot connect to MySQL server. <br>Please return to double check your database setting.');
      die($t->parse());
    }

    // save DATA_PATH
    @chmod('./acp.php', 0777);
    @chmod('./index.php', 0777);
    @chmod('./modpanel.php', 0777);
    if(!is_writable('./acp.php')) {
      $t = new templateElement(readfromfile('./install/error.html'));
      $t->set('error_msg', 'Cannot access file "acp.php", please change its mode to 0777.');
      die($t->parse());
    }
    if(!is_writable('./index.php')) {
      $t = new templateElement(readfromfile('./install/error.html'));
      $t->set('error_msg', 'Cannot access file "index.php", please change its mode to 0777.');
      die($t->parse());
    }
    if(!is_writable('./modpanel.php')) {
      $t = new templateElement(readfromfile('./install/error.html'));
      $t->set('error_msg', 'Cannot access file "modpanel.php", please change its mode to 0777.');
      die($t->parse());
    }
    $mset = new modify_setting('./acp.php');
    $mset->set('DATA_PATH', $_POST['DATA_PATH']);
    $mset->save();
    unset($mset);
    $mset = new modify_setting('./index.php');
    $mset->set('DATA_PATH', $_POST['DATA_PATH']);
    $mset->save();
    unset($mset);
    $mset = new modify_setting('./modpanel.php');
    $mset->set('DATA_PATH', $_POST['DATA_PATH']);
    $mset->save();
    unset($mset);

    ////////////////////////////
    list($pre_template, $app_template) = explode('<!-- SP LINE -->', readfromfile('./install/construct_tables.html'));

    $t = new templateElement($pre_template);
    print $t->parse();
    flush();

    $DB->haltOnError = 1;
    // create tables
    $tables = file('./install/structure.sql');
    foreach($tables as $per_sql) {
      if(!empty($per_sql))
        $DB->update($per_sql);
    }

    unset($t);
    $t = new templateElement($app_template);
    print $t->parse();

  }
  elseif ($step == 4) 
  {
    list($pre_template, $app_template) = explode('<!-- SP LINE -->', readfromfile('./install/insert_template.html'));
    $t = new templateElement($pre_template);
    print $t->parse();
    flush();

    include('./install/config.tmp.php');
    // insert template
    $DB = new DB( SET_DATABASE_HOST, SET_DATABASE_USER, SET_DATABASE_PASSWORD, SET_DATABASE_DBNAME );
    $tables = file('./install/template.sql');
    foreach($tables as $per_sql) {
      if(!empty($per_sql))
        $DB->update(preg_replace('/;$/', '', $per_sql));
    }

    unset($t);
    $t = new templateElement($app_template);
    print $t->parse();

  }
  elseif ($step == 5)
  {
    // setup account
    $t = new templateElement(readfromfile('./install/create_admin.html'));
    print $t->parse();

  }
  elseif ($step == 6)
  {
    include('./install/config.tmp.php');
    // final step
    $DB = new DB( SET_DATABASE_HOST, SET_DATABASE_USER, SET_DATABASE_PASSWORD, SET_DATABASE_DBNAME );
    $admin_user = new user();
    $admin_user->setProperty('username', slashesencode($_POST['username']));
    $admin_user->setProperty('password', slashesencode($_POST['password']));
    $admin_user->setProperty('email', slashesencode($_POST['email']));
    $admin_user->setProperty('usergroupid', 1);
    $admin_user->store();

    $t = new templateElement(readfromfile('./install/final.html'));
    print $t->parse();
  }
