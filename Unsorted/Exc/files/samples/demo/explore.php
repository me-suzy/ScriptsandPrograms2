<?php

require "init.php";

$session = array();

if( isset($HTTP_POST_FILES['excel_file']) &&
    ($HTTP_POST_FILES['excel_file']['tmp_name'] != '') ) {
        $upfilename = $uploaddir.session_id();
        @unlink($upfilename);
	if( !move_uploaded_file($HTTP_POST_FILES['excel_file']['tmp_name'],$upfilename) )
		die( $die_hdr.'Internal error: cannot read uploaded file'.$die_ftr );
} else {
	die( $die_hdr.'No file uploaded'.$die_ftr );
}

$session['file'] = $upfilename;
$session['exists'] = true;

header("Location: http://".$HTTP_SERVER_VARS['HTTP_HOST'].dirname($HTTP_SERVER_VARS['PHP_SELF'])."/showframes.html");

?>