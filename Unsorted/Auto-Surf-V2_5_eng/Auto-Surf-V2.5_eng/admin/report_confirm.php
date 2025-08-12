<?php

include('../prepend.inc.php');
if(isset($id))
	report_confirm($id);
header("Location: ./reported.php");

?>