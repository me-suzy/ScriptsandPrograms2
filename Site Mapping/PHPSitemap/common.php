<?php

function GetFileContents($path)
{
	$fd = fopen($path, 'r');
	while (!feof($fd)) $contents .= fgets($fd, 4096);
	fclose ($fd);
	return $contents;
}

?>