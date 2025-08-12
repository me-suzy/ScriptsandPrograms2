<?php
session_start();
$_SESSION['identifikace'] = session_id();

if ($akc=="prihlas") {
  $mdheslo = md5($heslo);
  $result1 = mysql_query("SELECT * FROM $tblname_admin WHERE admin_name='$login' AND admin_pasw='$mdheslo' AND admin_level='1'");
  $zrow = mysql_num_rows($result1);
  if ($zrow<>1) {
    mysql_query("UPDATE $tblname_admin SET admin_control = 'xxx' WHERE admin_control = '$_SESSION[identifikace]'");
    $_SESSION['jeprihl'] = "ne";
    Header("Location: index.php");
    exit();
    } else {
      $casloginu = time();
      mysql_query("UPDATE $tblname_admin SET admin_control = '$_SESSION[identifikace]', admin_time='$casloginu' WHERE admin_name='$login' AND admin_pasw='$mdheslo'");
      $_SESSION['jeprihl'] = "ano";
      Header("Location: admin.php");
      exit();
  }
}

function logincontrol($tblname_admin)
{
  $result2 = mysql_query("SELECT admin_time FROM $tblname_admin WHERE admin_control='$_SESSION[identifikace]' AND admin_level='1'");
  $krow = mysql_num_rows($result2);
    if ($krow=="1") {
      $_SESSION['jeprihl'] = "ano";
      $cas = mysql_result($result2,0,"admin_time");
      $casplus = $cas + 1800;
      $novycas = time();
      if ($casplus<$novycas) {
        mysql_query("UPDATE $tblname_admin SET admin_control = 'xxx' WHERE admin_control = '$_SESSION[identifikace]'");
        $_SESSION = array();
        session_destroy();
        } else {
          mysql_query("UPDATE $tblname_admin SET admin_time = '$novycas' WHERE admin_control = '$_SESSION[identifikace]'");
      }
      } else {
        $_SESSION['jeprihl'] = "ne";
    }
}

function sessioncontrol()
{
  if ($_SESSION['jeprihl'] != "ano") {
    Header("Location: index.php");
    exit();
  }
}
?>