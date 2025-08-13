<?
function manlix_read_file($path)
{
	if(!is_file($path))		return false;
	elseif(!filesize($path))	return array();
	elseif($array=file($path))	return $array;

	else
	while(!$array=file($path))sleep(1);

	return $array;
}

function manlix_stripslashes($string)
{
	if(empty($string))	return false;

	else
	{
	$result=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($string)))));

		if(!$result)	return false;
		elseif($result!=" ")	return $result;
	}
}

function manlix_char_generator($chars,$times)
{

	if(!strlen($chars))		return false;
	elseif(!is_numeric($times))	return false;

	else
	{
	$result=null;
		for($i=0;$i<$times;$i++)
		$result.=$chars[rand(0,strlen($chars)-1)];
	}

return $result;
}

function manlix_strip_new_line($string)
{
return preg_replace("/(".chr(9)."|".chr(10)."|".chr(11)."|".chr(12)."|".chr(13).")/",null,$string);
}
?>