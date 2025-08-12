<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

$kontrola = 0;
$result = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_order");
  $numRows = mysql_num_rows($result);

  for ($i=0;$i<$numRows;$i++) {
    for ($j=0;$j<$numRows;$j++) {
      if ($i!=$j) {
        if ($volba[$i]==$volba[$j]) $kontrola = 1;
      }
    }
  }

if ($kontrola!=1) {
  $result = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_order");
    $numRows = mysql_num_rows($result);
    $RowCount = 0;
    $poradi = 1;
      while($RowCount < $numRows)
      {
      $vysledek = MySQL_Query("UPDATE $tblname_head SET head_order='$poradi' WHERE head_id='$volba[$RowCount]'");
      $RowCount++;
      $poradi++;
      }
}

Header("Location: categoryorder.php");
?>