<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

if ($nazev=="") {
Header("Location: categoryedit.php?id=$id");
} else {
$vysledek = MySQL_Query("UPDATE $tblname_head SET head_name='$nazev', head_char='$popis' WHERE head_id='$id'");
Header("Location: category.php");
}
?>