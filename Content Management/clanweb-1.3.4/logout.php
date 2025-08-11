<?php require('cfg.php');

	  $sql = "DELETE FROM ".$db_prefix."online WHERE cookiesum = '".$_COOKIE['catcookie']."'  LIMIT 1";
	
	mysql_query($sql) or exit('An error occured while deleting data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
	
	setcookie('catcookie','',time()+120);
	Header ("Location: index.php"); 
?>
   
