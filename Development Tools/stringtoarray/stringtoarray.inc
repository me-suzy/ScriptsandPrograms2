<?php

/*======================================================================*\
	Function:	stringtoarray
	Purpose:	pushes each character of a string into an array
	Output:		each character of the string is now in the array
	By :		Francisco Guerra ( http://www.fguerra.net )
\*======================================================================*/

function stringtoarray($string){
$len = strlen($string);

$cur = 0;

$characterarray = array();

	while($cur < $len){
	$char = $string{$cur};
	array_push($characterarray,$char);
	$cur = $cur + 1;
	}

return $characterarray;

}

?>