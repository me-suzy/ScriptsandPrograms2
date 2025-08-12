<?php

include('../prepend.inc.php');
if(isset($id))
	report_delete($id);
header("Location: ./reported.php");

?>