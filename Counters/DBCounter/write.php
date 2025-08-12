<?php
	$file = "counter.txt";

	$open = fopen( $file, "w" );
	fwrite( $open, $count );
	fclose( $open );
?>