<?php
function __autoload ($Class)
{
	$XMLReflectionClasses = array
		(
			'XMLReflectionClass',
			'XMLReflectionExtension',
			'XMLReflectionFunction',
			'XMLReflectionMethod',
			'XMLReflectionObject',
			'XMLReflectionParameter',
			'XMLReflectionProperty',
			'XMLReflection',
		);
	
	if (in_array ($Class, $XMLReflectionClasses))
	{
		require_once('XMLReflection/ToDom.php');
		require_once('XMLReflection/' . $Class . '.php');
	}
	else
	{
		return false;
	}
}
?>