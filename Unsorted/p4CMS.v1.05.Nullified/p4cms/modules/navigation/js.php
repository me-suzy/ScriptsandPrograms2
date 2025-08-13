<?
 @require_once("${abs_pfad}include/config.inc.php"); 
 @require_once("${abs_pfad}include/mysql-class.inc.php");
 @require_once("${abs_pfad}include/functions.inc.php");
 
 $_REQUEST[id] = str_replace("\\","",$anavi);
 
 $refurl = $_SERVER[REQUEST_URI];
 
 $sql =& new MySQLq();
 $sql->Query("SELECT * FROM " . $sql_prefix . "navis WHERE id='$_REQUEST[id]'");
 $row = $sql->FetchRow();
 $sql->Close();
 
 $ebene1 = stripslashes($row->ebene1);
 $ebene1a = stripslashes($row->ebene1a);
 $ebene2 = stripslashes($row->ebene2);
 $ebene2a = stripslashes($row->ebene2a);
 
 $vor = stripslashes($row->vor);
 $vor = str_replace("\r", "", $vor);
 $vor = str_replace("\n", "", $vor);
 echo $vor;
 
 $nach = stripslashes($row->nach);
 $nach = str_replace("\r", "", $nach);
 $nach = str_replace("\n", "", $nach);
 
 $sql =& new MySQLQ();
 $sql->Query("SELECT * FROM " . $sql_prefix . "navi_items WHERE ebene='1' AND parent='$_REQUEST[id]' ORDER BY rang ASC");
 while ($row = $sql->FetchRow()) { 	
 	if ($refurl == $row->link) {
 		$akt = $ebene1a;
 		$exp = true;
 	} else {
 		$akt = $ebene1;
 		$exp = false;
 	}
 	
 	$sql2 =& new MySQLq();
 	$sql2->Query("SELECT * FROM " . $sql_prefix . "navi_items WHERE ebene='2' AND parent='$row->id' AND link='$refurl' order by subrang ASC");
 	if ($sql2->RowCount() > 0) {
 		$akt = $ebene1a;
 		$exp = true;
 	}
 	$sql2->Close();
 	
 	$akt = str_replace("{TITEL}", stripslashes($row->titel), $akt);
 	$akt = str_replace("{URL}", stripslashes($row->link), $akt);
 	$akt = str_replace("{TARGET}", stripslashes($row->target), $akt);
 	$akt = str_replace("\r", "", $akt);
 	$akt = str_replace("\n", "", $akt);
 	
 	echo $akt;
 	
 	if ($exp) {
 		$sql2 =& new MySQLq();
 		$sql2->Query("SELECT * FROM " . $sql_prefix . "navi_items WHERE ebene='2' AND parent='$row->id' ORDER BY subrang ASC");
 		while ($row2 = $sql2->FetchRow()) {
 			 	if ($refurl == $row2->link) {
 					$akt = $ebene2a;
 				} else {
 					$akt = $ebene2;
 				}
 				
 			 	$akt = str_replace("{TITEL}", stripslashes($row2->titel), $akt);
 				$akt = str_replace("{URL}", stripslashes($row2->link), $akt);
 				$akt = str_replace("{TARGET}", stripslashes($row->target), $akt);
 				$akt = str_replace("\r", "", $akt);
 				$akt = str_replace("\n", "", $akt);
 	
 				echo $akt;
 		}
 		$sql2->Close();
 	}
 }
 $sql->Close();
 
 echo $nach;
?>