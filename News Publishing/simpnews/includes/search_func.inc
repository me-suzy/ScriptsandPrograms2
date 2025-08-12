<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function search_highlight($text,$musts,$cans)
{
	global $searchhighlightcolor, $searchhighlight, $searchpart;

	if(($searchhighlight==0) || ($searchpart!=0))
		return $text;
	$newtext=$text;
	$newtext=str_replace("&#","@@",$newtext);
	for($i=0;$i<count($musts);$i++)
	{
		$searchword=preg_quote(str_replace("&#","@@",$musts[$i]));
		$newtext = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $searchword . ")#i', '<span style=\"color:$searchhighlightcolor\">\\\\1</span>', '\\0')", '>' . $newtext . '<'), 1, -1));
	}
	for($i=0;$i<count($cans);$i++)
		if(!in_array($cans[$i],$musts))
		{
			$searchword=preg_quote(str_replace("&#","@@",$cans[$i]));
			$newtext = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $searchword . ")#i', '<span style=\"color:$searchhighlightcolor\">\\\\1</span>', '\\0')", '>' . $newtext . '<'), 1, -1));
		}
	$newtext=str_replace("@@","&#",$newtext);
	return $newtext;
}

function addhighlights($linkdest, $musts, $cans)
{
	global $searchhighlight;

	if($searchhighlight==0)
		return $linkdest;
	$isfirst=true;
	$highlightwords="";
	for($i=0;$i<count($musts);$i++)
	{
		if(!$isfirst)
			$highlightwords.="|";
		else
			$isfirst=false;
		$highlightwords.=base64_encode(undo_htmlentities($musts[$i]));
	}
	for($i=0;$i<count($cans);$i++)
	{
		if(!in_array($cans[$i],$musts))
		{
			if(!$isfirst)
				$highlightwords.="|";
			else
				$isfirst=false;
			$highlightwords.=base64_encode(undo_htmlentities($cans[$i]));
		}
	}
	$linkdest.="&highlight=".rawurlencode($highlightwords);
	return $linkdest;
}
?>