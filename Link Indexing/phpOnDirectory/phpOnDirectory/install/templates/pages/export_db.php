<?php
//Database Settings
function my_db_connection($server, $username, $password,$db) {
    global $db_error;

    $db_error = false;

    if (!$server) {
      $db_error = 'No Server selected.';
      return false;
    }

    @mysql_connect($server, $username, $password) or $db_error = mysql_error();
    mysql_select_db($db);

    return true;
}
function import_to_mysql($matches) {
    global $error;
    //echo $matches[0],"<br>";
    mysql_query($matches[0]);
    if (mysql_error()) $error = "Mysql Error: ".mysql_error();
    return "";
}
if (isset($HTTP_POST_VARS['DB_SERVER'])&&!empty($HTTP_POST_VARS['DB_SERVER'])&&(!isset($HTTP_POST_VARS['flag']))) {
    $db = array();    
    $db['DB_SERVER'] = trim(stripslashes($HTTP_POST_VARS['DB_SERVER']));
    $db['USER_NAME'] = trim(stripslashes($HTTP_POST_VARS['USER_NAME']));
    $db['USER_PASSWORD'] = trim(stripslashes($HTTP_POST_VARS['USER_PASSWORD']));
    $db['DB_NAME'] = trim(stripslashes($HTTP_POST_VARS['DB_NAME']));
    $db_error = false;

    my_db_connection($db['DB_SERVER'], $db['USER_NAME'], $db['USER_PASSWORD'],$db['DB_NAME']);

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
    
    if (is_file($dir_fs_www_root . 'install/sql/phpDirectory.sql')){
        $fd = fopen($dir_fs_www_root . 'install/sql/phpDirectory.sql',"r");
        $query = fread($fd,filesize($dir_fs_www_root . 'install/sql/phpDirectory.sql'));
        fclose($fd);
    } else {
        $error = "Can't open $dir_fs_www_root . 'install/sql/phpDirectory.sql'";
    }
    if (!$error) {
        preg_replace_callback ("/create .+?;/is", "import_to_mysql", $query, -1);
        preg_replace_callback ("/insert .+?\)\s*;/is", "import_to_mysql", $query, -1);
    }
    
    if ($error) {
?>        
    <form name="install" action="install.php" method="post">
    
    <table width="95%" border="0" cellpadding="2">
      <tr>
        <td>
          <p>Database import was <b>NOT</b> successful!</p>
          <p>The error message returned is:</p>
          <p><?php echo $error; ?></p>
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
?>        
		<form name="install" action="install.php?step=2" method="post">
		<table width="95%" border="0"  align=center cellpadding="2">
		  <tr align=center>
		    <td >
		      <p>Database import was <b>successful</b>.</p>
		      <p>Please click below to continue the installation process.</p>
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
            echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
      }
    }
?>   
        </form>    
<?php
} else header("Location: index.php");exit;
?>