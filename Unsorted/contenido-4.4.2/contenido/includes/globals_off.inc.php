<?php

$types_to_register = array('GET','COOKIE','POST','SERVER','ENV');
foreach ($types_to_register as $global_type) {
	$arr = @${'HTTP_' . $global_type . '_VARS'};
	if (@count($arr) > 0) {

		extract($arr, EXTR_OVERWRITE);
	}
}

?>
