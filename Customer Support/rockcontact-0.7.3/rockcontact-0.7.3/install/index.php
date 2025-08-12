<?

require_once("../includes/config.inc.php");
require_once("../includes/common.inc.php");

$diag = get_diagnostic_system();

echo "<html><head><title></title></head><body>";
echo "Extension <b>sqlite</b> available : ". put_field_color($diag['SQLITE_PRESENT']) ."<br>";
if ($diag['SQLITE_PRESENT']){
  echo "Extension <b>sqlite encoding</b> : ". $diag['SQLITE_LIBENCODING'] ."<br>";
  echo "Extension <b>sqlite version</b> : ". $diag['SQLITE_LIBVERSION'] ."<br>";
}
echo "Extension <b>GD</b> available : ". put_field_color($diag['GD_PRESENT']) ."<br><br>";

echo "Directory <b>". INSTALL_DIR ."</b> Exist : ". put_field_color($diag['INSTALL_DIR_EXIST']) ."<br><br>";

echo "Directory <b>". BD_DIR ."</b> writable by web server : ". put_field_color($diag['DB_DIR_WRITABLE']) ."<br>";

if ( $diag['DB_DIR_WRITABLE'] && $diag['SQLITE_PRESENT'] ) {
  if ( ! $diag['BD_NAME_EXIST'] || $diag['BD_NAME_FILE_SIZE'] <= 0 ){
    echo '<br><form action="createdb.php"><input type="submit" value="Create Database"></form><br><br>';
  }
}

echo "DB <b>". BD_NAME ."</b> exist : ". put_field_color($diag['BD_NAME_EXIST']) ."<br>";
echo "DB <b>". BD_NAME ."</b> writable by web server : ". put_field_color($diag['BD_NAME_WRITABLE']) ."<br>";

echo "<br><br>";
if (install_terminate($diag)) {
  echo 'Congratulations, installation of Rockcontact is complete, your can safely remove the install directory now.';
} else {
  echo '<span style="color: #ff0000">Installation will now terminate, please correct the problem in RED before proceeding.</span>';
}

echo '<br><br><a href="index.php">RELOAD PAGE</a>';
echo "</html></body>";

function put_field_color($field){
  if ($field === TRUE)
    return '<span style="color: #00FF00;">TRUE</span>';
  else if ($field === FALSE)
    return '<span style="color: #FF0000;">FALSE</span>';
}

function install_terminate($diag){
  $keys = array_keys($diag);
  for($x = 0; $x < count($keys); $x++){
    if ($diag[$keys[$x]] === FALSE)
      return FALSE;
  }
  return TRUE;
}

function get_diagnostic_system(){
  $rv = array ();
  array_add($rv,'SQLITE_PRESENT', extension_loaded('sqlite'));
  if ($rv['SQLITE_PRESENT']){
    array_add($rv,'SQLITE_LIBENCODING', sqlite_libencoding());
    array_add($rv,'SQLITE_LIBVERSION', sqlite_libversion());
  }
  array_add($rv,'GD_PRESENT', extension_loaded('gd'));
  array_add($rv,'DB_DIR_WRITABLE', is_writable(BD_DIR));
  array_add($rv,'INSTALL_DIR_EXIST', file_exists(INSTALL_DIR));
  array_add($rv,'BD_NAME_EXIST', file_exists(BD_NAME));
  array_add($rv,'BD_NAME_WRITABLE', is_writable(BD_NAME));
  if($rv['BD_NAME_EXIST'])
    array_add($rv,'BD_NAME_FILE_SIZE', filesize(BD_NAME));
  else
    array_add($rv,'BD_NAME_FILE_SIZE', 0);
  return $rv;
}

?>
