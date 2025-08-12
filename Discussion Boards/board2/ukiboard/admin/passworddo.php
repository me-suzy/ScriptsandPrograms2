<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

if ($codelat==1) {
  if ($pasjmeno=="") {
  Header("Location: password.php");
  } else {
    $vysledek = MySQL_Query("UPDATE $tblname_admin SET admin_name='$pasjmeno' WHERE admin_control='$_SESSION[identifikace]'");
   Header("Location: ../index.php");
  }
}

if ($codelat==2) {
  $resultold = mysql_query("SELECT admin_pasw FROM $tblname_admin WHERE admin_control='$_SESSION[identifikace]'");
    $oldies = mysql_result($resultold,0,"admin_pasw");
    $pasheslooldies = MD5($pashesloold);
  if ($pashesloold=="" || $pasheslonew=="" || $pasheslocon=="" || $pasheslonew!=$pasheslocon || $pasheslooldies!=$oldies || $pashesloold==$pasheslonew) {
  Header("Location: password.php");
  } else {
    $pasheslodo = MD5($pasheslonew);
    $vysledek = MySQL_Query("UPDATE $tblname_admin SET admin_pasw='$pasheslodo' WHERE admin_control='$_SESSION[identifikace]'");
   Header("Location: ../index.php");
  }
}
?>