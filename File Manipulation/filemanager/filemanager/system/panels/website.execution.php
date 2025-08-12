<?php

$execution_end	= Utilities::getMicroTime();
$execution_time = round($execution_end - $execution_start,2);
if(Application::getExecutionTime() == "true") {
	echo "&nbsp;Execution-Time: $execution_time seconds";
}

?>