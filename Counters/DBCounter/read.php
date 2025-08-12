<?php
	$file = "counter.txt";
	
	$open = fopen( $file, "r" ); 
	$count = fread ( $open, filesize ( $file ) );
	fclose( $open );
?>