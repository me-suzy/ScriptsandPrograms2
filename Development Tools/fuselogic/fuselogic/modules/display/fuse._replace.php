<?php

$temp = getLayout();

if(@include_once('class.stopwatch.php')){
    $time = &new stopwatch(FL_MICROTIME_START);	
		$out = $time->time();
		$temp1 = str_replace('<REPLACE_TIME/>',$out,$temp);
}

ob_start();
if(@(int)$_COOKIE['show_fuselogic_info'] == 1){
    echo '<hr><a href="'.index().module().'/hide_info">Hide FuseLogic Info</a>';
		include_once('fuselogic_info.php');
}else{
    echo '<a href="'.index().module().'/show_info">Show FuseLogic Info</a>';
}	
$out = ob_get_contents();
ob_end_clean();
$temp = str_replace('<REPLACE_INFO/>',$out,$temp1);

if(function_exists('ob_tidyhandler')){       
		echo ob_tidyhandler($temp);		
}else{
    echo $temp;
}
?>