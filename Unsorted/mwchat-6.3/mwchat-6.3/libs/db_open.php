<?PHP
  if (!isset($CONN)) { $CONN = db_connect($CONFIG[Database_Host], $CONFIG[Database_Port], $CONFIG[Database_Username], $CONFIG[Database_Password], $CONFIG[Database_Name]); } ?>
