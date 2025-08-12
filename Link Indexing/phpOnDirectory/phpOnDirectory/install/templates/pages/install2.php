<?php
function isEmail($str) {
    return preg_match("`^[^@]+ @ [^.]+ (\. [^.]+)+$`x", $str);
}

if (isset($HTTP_POST_VARS['CONST_LINK_ROOT'])&&!empty($HTTP_POST_VARS['CONST_LINK_ROOT'])&&(!isset($HTTP_POST_VARS['flag']))) {
    $sys = array();
    $sys['CONST_LINK_ROOT'] = trim(stripslashes($HTTP_POST_VARS['CONST_LINK_ROOT']));
    $sys['CONST_INCLUDE_ROOT'] = trim(stripslashes($HTTP_POST_VARS['CONST_INCLUDE_ROOT']));
    $sys['CONST_LINK_SITE'] = trim(stripslashes($HTTP_POST_VARS['CONST_LINK_SITE']));
    $sys['CONST_LINK_EMAIL'] = trim(stripslashes($HTTP_POST_VARS['CONST_LINK_EMAIL']));
    $sys['CONST_ADMIN_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['CONST_ADMIN_PASSWORD']));
    $sys['Static'] = trim(stripslashes($HTTP_POST_VARS['Static']));
    $sys['Static'] = (empty($sys['Static'])?'off':$sys['Static']);
//    echo '<pre>';
//    print_r($sys);
//    exit;
    $db = array();
    $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
    $db['USER_NAME'] = trim(stripslashes($HTTP_POST_VARS['USER_NAME']));
    $db['USER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['USER_PASSWORD']));
    $db['DB_NAME'] = trim(stripslashes($HTTP_POST_VARS['DB_NAME']));
    $sys_error = false;

    $error_text = '';
    $counter = 0;
    foreach ($sys as $key => $value) {
        (empty($value)?$counter++:'');
    }
    if ($counter == '1') {
        $error_text .= 'Please complete all fields.<br>';
        $sys_error = true;
    } elseif ($counter > '1') {
        $error_text .= 'Please complete all fields.<br>';
        $sys_error = true;
    }

    if ((!empty($sys['CONST_LINK_EMAIL']))&&(!isEMail($sys['CONST_LINK_EMAIL']))) {
        $error_text .= 'Invalid email address<br>';
        $sys_error = true;
    }

    if ($sys['Static'] == 'on') {
        $DIR_PATH = $sys['CONST_INCLUDE_ROOT'].'/'.$sys['CONST_LINK_SITE'].'_sites';
        if (!is_dir($DIR_PATH)) {
            if (!mkdir($DIR_PATH,0777)) {
                $sys_error = true;
                $error_text .= '<p><div class="boxMe"  style="color: red;"><b>Wrong permission level.</b><br><br>Please change permission level on Main Site Folder to 777!</div></p>';
            }
        }
    }

    if ($sys_error == false) {
          //search for root dir
          $script_filename = getenv('PATH_TRANSLATED');
          if (empty($script_filename)) {
            $script_filename = getenv('SCRIPT_FILENAME');
          }

          $script_filename = str_replace('\\', '/', $script_filename);
          $script_filename = str_replace('//', '/', $script_filename);

          $dir_fs_www_root_array = explode('/', dirname($script_filename));
          $dir_fs_www_root = array();
          for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
            $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
          }
          $dir_fs_document_root = implode('/', $dir_fs_www_root) . '/';
          //eof search for root dir

          if ((substr($dir_fs_document_root, -1) != '/') && (substr($dir_fs_document_root, -1) != '/')) {
            $where = strrpos($dir_fs_document_root, '\\');
            if (is_string($where) && !$where) {
              $dir_fs_document_root .= '/';
            } else {
              $dir_fs_document_root .= '\\';
            }
          }
           if ( ( (file_exists($sys['CONST_INCLUDE_ROOT'] . '/includes/db_connect.php')) && (!is_writeable($sys['CONST_INCLUDE_ROOT'] . '/includes/db_connect.php')) ) ) {
                $sys_error = true;
                $error_text .= '<p><div class="boxMe"><b>The configuration files do not exist, or permission levels are not set.</b><br><br>Please perform the following actions:'.
                               '<ul class="boxMe"><li>cd '.$sys['CONST_INCLUDE_ROOT'].'/includes/</li><li>touch db_connect.php</li><li>chmod 706 db_connect.php</li></ul>'.
                               '<p class="noteBox">If <i>chmod 706</i> does not work, please try <i>chmod 777</i>.</p>'.
                               '<p class="noteBox">If you are running this installation procedure under a Microsoft Windows environment, try renaming the existing configuration file so a new file can be created.</p>';
           }
           else
           {
                $file_contents = '<?php' . "\n" .
                                '$link=mysql_connect("'.$db['DB_SERVER'].'", "'.$db['USER_NAME'].'", "'.$db['USER_PASSWORD'].'");'."\n" .
                                'if (!$link) die ("Database connection failure");'."\n" .
                                'mysql_select_db("'.$db['DB_NAME'].'") or die("Failure in connection ".mysql_error() );'."\n" .
                                "\n" .
                                '$CONST_LINK_ROOT = "'.$sys['CONST_LINK_ROOT'].'";'."\n" .
                                '$CONST_LINK_SITE = "'.$sys['CONST_LINK_SITE'].'";'."\n" .
                                '$CONST_LINK_EMAIL = "'.$sys['CONST_LINK_EMAIL'].'";'."\n" .
                                '$CONST_ADMIN_PASSWORD = "'.$sys['CONST_ADMIN_PASSWORD'].'";'."\n" .
                                '$Static = "'.$sys['Static'].'";'."\n" .
                                '$CONST_LINK_GENERATE = "'.$CONST_LINK_SITE.'_sites";'."\n" .
                                "\n" .
                                '$CONST_INCLUDE_ROOT = "'.$sys['CONST_INCLUDE_ROOT'].'";'."\n" .
                                'session_start();'."\n" .
                                "\n" .
                                'function check_admin()'."\n" .
                                '{'.
                                'global $CONST_ADMIN_PASSWORD;'.
                                '	if ($_SESSION[\'Sess_Password\'] != $CONST_ADMIN_PASSWORD)'."\n" .
                                '		exit;'."\n" .
                                '}'."\n" .
                                '?>';
//<style type="text/css">
//<!--
//.style1 {
//	font-family: Verdana, Arial, Helvetica, sans-serif;
//	font-size: 12px;
//}
//-->
//</style>

                $fp = fopen($sys['CONST_INCLUDE_ROOT'] . '/includes/db_connect.php', 'w');
                fputs($fp, $file_contents);
                fclose($fp);
           }
    }

    if ($sys_error != false) {
?>
<form name="install" action="install.php?step=2" method="post">

<table width="95%" border="0" cellpadding="2">
  <tr>
    <td>
      <p>Configuration form contains errors</p>
      <p>The error message is:</p>
      <p style="color: red;"><?php echo $error_text; ?></p>
      <p>Please click on the <i>Back</i> button below to review your Webserver settings.</p>
      <p>If you require help with your database server settings, please consult your hosting company.</p>
    </td>
  </tr>
</table>
<?php
      reset($HTTP_POST_VARS);
      foreach ($HTTP_POST_VARS as $key => $value){
//            echo $key .'=>'.$value.'<br>';

            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
      }
//      exit;
      echo '<input type="hidden" name="flag" value="1">';
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><input type="submit" value="Back"></td>
  </tr>
</table>
<?php
    }
    else
    {
?>
<table width="95%" border="0" cellpadding="2" class="formPage">
  <tr align=center>
    <td width=20%>&nbsp;</td><td align=left>
      <p>Installation Complete<br>
        <br>
    Thank you for installing phpDirectory</p>
      <p>Please remember to <b>delete</b> your install directory located at
        <?=$sys['CONST_INCLUDE_ROOT']?>
        /install<br>
        You can access your administration page here:
        <?=$sys['CONST_LINK_ROOT']?>
        /admin/ using the password you set up in the installation process.<br>
        <br>
        <a href="<?=$sys['CONST_LINK_ROOT']?>/admin/">Click here</a> to continue to login as admin</span><br>
        </p>
    </td>
 </tr>
</table>
<?php
    }
}
else
{
 // phpinfo();
  $www_location = 'http://' . $_SERVER["SERVER_NAME"] . ($_SERVER["REDIRECT_URL"] ? $_SERVER["REDIRECT_URL"] : $_SERVER["SCRIPT_NAME"]);
  $www_location = substr($www_location, 0, strpos($www_location, 'install')-1);

  $script_filename = getenv('PATH_TRANSLATED');
  if (empty($script_filename)) {
    $script_filename = getenv('SCRIPT_FILENAME');
  }

  $script_filename = str_replace('\\', '/', $script_filename);
  $script_filename = str_replace('//', '/', $script_filename);

  $dir_fs_www_root_array = explode('/', dirname($script_filename));
  $dir_fs_www_root = array();
  for ($i=0, $n=sizeof($dir_fs_www_root_array)-1; $i<$n; $i++) {
    $dir_fs_www_root[] = $dir_fs_www_root_array[$i];
  }
  $dir_fs_www_root = implode('/', $dir_fs_www_root);
  unset($HTTP_POST_VARS['Static']);
?>
<table cellspacing="0" cellpadding="5" width="70%" border="1" align="center">
<form name="install" action="install.php?step=2" method="post">
    <tr>
        <td colspan="2" style="font-weight: bold; text-align: center;">Webserver Settings</td>
    </tr>
    <tr>
        <td>WWW address</td>
        <td>
            <input type="text" name="CONST_LINK_ROOT" value="<?=(!empty($HTTP_POST_VARS['CONST_LINK_ROOT'])?$HTTP_POST_VARS['CONST_LINK_ROOT']:$www_location)?>" style="width: 200">
        </td>
    </tr>
    <tr>
        <td>Webserver root directory</td>
        <td><input type="text" name="CONST_INCLUDE_ROOT" value="<?=(!empty($HTTP_POST_VARS['CONST_INCLUDE_ROOT'])?$HTTP_POST_VARS['CONST_INCLUDE_ROOT']:$dir_fs_www_root)?>" style="width: 200"></td>
    </tr>
    <tr>
        <td>Site Name</td>
        <td><input type="text" name="CONST_LINK_SITE" value="<?=(!empty($HTTP_POST_VARS['CONST_LINK_SITE'])?$HTTP_POST_VARS['CONST_LINK_SITE']:'')?>" style="width: 200"></td>
    </tr>
    <tr>
        <td>Administrator Email</td>
        <td><input type="text" name="CONST_LINK_EMAIL" value="<?=(!empty($HTTP_POST_VARS['CONST_LINK_EMAIL'])?$HTTP_POST_VARS['CONST_LINK_EMAIL']:'')?>" style="width: 200"></td>
    </tr>
    <tr>
        <td>Administrator Password</td>
        <td><input type="password" name="CONST_ADMIN_PASSWORD" value="<?=(!empty($HTTP_POST_VARS['CONST_ADMIN_PASSWORD'])?$HTTP_POST_VARS['CONST_ADMIN_PASSWORD']:'')?>" style="width: 200"></td>
    </tr>
    <tr>
        <td>Use Static Website Version</td>
        <td><input type="checkbox" name="Static" checked></td>
    </tr>
<?php
    unset ($HTTP_POST_VARS['flag']);
    unset ($HTTP_POST_VARS['CONST_LINK_ROOT']);
    unset ($HTTP_POST_VARS['CONST_INCLUDE_ROOT']);
    unset ($HTTP_POST_VARS['CONST_LINK_SITE']);
    unset ($HTTP_POST_VARS['CONST_LINK_EMAIL']);
    unset ($HTTP_POST_VARS['CONST_ADMIN_PASSWORD']);
    reset($HTTP_POST_VARS);
    foreach ($HTTP_POST_VARS as $key => $value) {
          echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
    }
?>
    <tr>
        <td colspan="2" align="center"><input type=submit value="Continue"></td>
    </tr>
</form></table>

<?php
}
?>
