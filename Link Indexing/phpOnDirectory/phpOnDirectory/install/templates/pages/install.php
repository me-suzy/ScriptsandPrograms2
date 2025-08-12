<?php
//Database Settings
function my_db_connection($server, $username, $password) {
    global $db_error;

    $db_error = false;

    if (!$server) {
      $db_error = 'No Server selected.';
      return false;
    }

    @mysql_connect($server, $username, $password) or $db_error = mysql_error();

    return true;
}

if (isset($HTTP_POST_VARS['DB_SERVER'])&&!empty($HTTP_POST_VARS['DB_SERVER'])&&(!isset($HTTP_POST_VARS['flag']))) {
    $db = array();    
    $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
    $db['USER_NAME'] = trim(stripslashes($HTTP_POST_VARS['USER_NAME']));
    $db['USER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['USER_PASSWORD']));
    $db['DB_NAME'] = trim(stripslashes($HTTP_POST_VARS['DB_NAME']));
    $db_error = false;

    my_db_connection($db['DB_SERVER'], $db['USER_NAME'], $db['USER_PASSWORD']);

    if ($db_error != false) {
?>        
<form name="install" action="install.php" method="post">

<table width="95%" border="0" cellpadding="2">
  <tr>
    <td>
      <p>A test connection made to the database was <b>NOT</b> successful!</p>
      <p>The error message returned is:</p>
      <p><?php echo $db_error; ?></p>
      <p>Please click on the <i>Back</i> button below to review your database server settings.</p>
      <p>If you require help with your database server settings, please consult your hosting company.</p>
    </td>
  </tr>
</table>
<?php
      reset($HTTP_POST_VARS);
      foreach ($HTTP_POST_VARS as $key => $value){
            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
      }
      echo '<input type="hidden" name="flag" value="1">';
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><input type="submit" value="Back"></td>
  </tr>
</table>
<?php
    }
    else //No db_errror
    {
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
      $dir_fs_www_root = implode('/', $dir_fs_www_root) . '/';        
        
?>        
		<form name="install" action="install.php?step=export_db" method="post">
		<table width="95%" border="0"  align=center cellpadding="2">
		  <tr align=center>
		    <td >
		      <p>Database connection <b>successful</b>.</p>
		      <p>Please continue the installation process.</p>
		    </td>
		  </tr>
		</table>        
		<table border="0" width="100%" cellspacing="2" cellpadding="2">
		  <tr>
		    <td align="center"><input type="submit" value="Continue"></td>
		  </tr>
		</table>
<?php
     reset($HTTP_POST_VARS);
      foreach ($HTTP_POST_VARS as $key => $value){
//            echo $key .'=>'. $value.'<br>';
            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
      }
//      exit;
    }
?>   
</form>    

<?php
}
else {
?>
<table cellspacing="0" cellpadding="5" width="70%" border="1" align="center">
<form name="install" action="install.php" method="post">
	<tr>
		<td colspan="2" style="font-weight: bold; text-align: center;">Database Settings</td>
	</tr>
	<tr>
		<td>Database Server<br>(Hostname or IP-address of database server)</td>
		<td>
			<input type="text" name="DB_SERVER" value="<?=(!empty($HTTP_POST_VARS['DB_SERVER'])?$HTTP_POST_VARS['DB_SERVER']:'')?>"><br>
			For example: localhost, 127.0.0.1
		</td>
	</tr>
	<tr>
		<td>User Name</td>
		<td><input type="text" name="USER_NAME" value="<?=(!empty($HTTP_POST_VARS['USER_NAME'])?$HTTP_POST_VARS['USER_NAME']:'')?>"></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="USER_PASSWORD" value="<?=(!empty($HTTP_POST_VARS['USER_PASSWORD'])?$HTTP_POST_VARS['USER_PASSWORD']:'')?>"></td>
	</tr>
	<tr>
		<td>Database Name</td>
		<td><input type="text" name="DB_NAME" value="<?=(!empty($HTTP_POST_VARS['DB_NAME'])?$HTTP_POST_VARS['DB_NAME']:'')?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type=submit value="Continue"></td>
	</tr>
</table>
</form>
<?php    
}
?>