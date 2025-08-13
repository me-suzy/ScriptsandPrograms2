<?PHP
 include("../../include/config.inc.php");
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");
 
 $mail = $_REQUEST['mail'];
 $list = $_REQUEST['list'];
 $code = $_REQUEST['code'];
 $error = "";
 
 /* Mailadresse aus der DB suchen */
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "listsubscribers WHERE liste='$list' AND email='$mail'");
 if ($sql->RowCount() > 0) {
 	$row = $sql->FetchRow();
 } else {
 	$error = "Ihre E-Mail - Adresse konnte in unserem Verteiler nicht gefunden werden.";
 }
 $sql->Close();
 
 /* Code prüfen */
 $must_code = md5(crc32($row->email . $row->name . $row->liste));
 $have_code = $code;
 if ($must_code == $have_code) {
 	$sql =& new MySQLq();
 	$sql->Query("DELETE FROM " . $sql_prefix . "listsubscribers WHERE id='$row->id'");
 	$sql->Close();
 	$error = "Ihre E-Mail - Adresse wurde von der Verteilerliste entfernt.";
 } else {
 	$error = "Der Sicherheitscode ist nicht korrekt.";
 }
 
 /* Resultat ausgeben*/
 echo "<center><h3><font face=\"verdana\">$error</font></h3></center>";
?>