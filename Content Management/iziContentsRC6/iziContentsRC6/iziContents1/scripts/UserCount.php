<?php

	$lifespan = time() - 300;

	$strQuery = "SELECT SID FROM ".$GLOBALS["eztbSessions"]." WHERE expiration < '".$lifespan."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs     = dbFetch($result);
	$uRecCount = dbRowsReturned($result);
	dbFreeResult($result);

	echo 'There are '.$uRecCount.' users currently online';

?>
