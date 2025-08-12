<?php 
require_once('../classes/pnt/generalFunctions.php');

if ( isSet($_REQUEST["Include"]) )
	include $_REQUEST["Include"];

function bgCol($level)
{
	$bg[1] = "#DDDDDD";
	$bg[2] = "#C4F0FF";
	$bg[3] = "#BDE9FF";
	$bg[4] = "#FFF1CA";

	return $bg[$level%sizeof($bg)];
}

function buildHtml($vInput, $iLevel = 1) 
{
	$bgCol = bgCol($iLevel);
	$result = <<<EOH
<table border='0' cellpadding='0' cellspacing='1' >
<tr>
<td align='left' bgcolor="$bgCol">
EOH;

	if ($vInput===null) {
		$result .= "NULL</td>";
	} 
	else if (is_string($vInput))
	{
		$result .= "string (" . strlen($vInput) . ") \"<b>" . $vInput . "</b>\"</td>";
	} 
	else if (is_bool($vInput))
	{
		$result .= "bool(<b>" . ($vInput ? "true" : "false") . "</b>)</td>";
	} 
	else if (is_array($vInput) || is_object($vInput))
	{
		if (is_array($vInput)) {
			$result .= "array count = [<b>" . count($vInput) . "</b>] dimension = [<b>{$iLevel}</b>]</td></tr><tr><td>";
		} else {
			$result .= "Object class = <b>" . get_class($vInput) . "</b></td></tr><tr><td>";
		}
		$result .=  <<<EOH
<table border='0' cellpadding='5' cellspacing='1' >
EOH;

		while (list($vKey, $vVal) = each($vInput))
		{
			$result .= "<tr><td align='left' bgcolor='".$bgCol."'><b>";
			$result .= 
				(is_array($vInput)
					? buildHtml($vKey, ($iLevel + 1)) 
					: $vKey 
				). "</b></td>";

			$result .= "</b></td><td bgcolor='".$bgCol."'>=></td><td bgcolor='".$bgCol."'><b>";
			$result .= buildHtml($vVal, ($iLevel + 1)) . "</b></td></tr>";
		}

		$result .= "</table>";
	}
	else 
	{
		$result .= gettype($vInput)." (<b>".$vInput."</b>) </td>";
	}

	$result .= "</table>";

	return $result;
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>

<HEAD>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html;CHARSET=iso-8859-1">
	<TITLE>untitled</TITLE>
</HEAD>
<BODY>
<?php

	if ( isSet($_REQUEST["Object"]) ) {
		$ser = stripslashes($_REQUEST["Object"]);
		print $ser;
		print '<BR><BR>';
		$obj = unserialize($ser);
		print buildHtml($obj);		
	}
	else
		print "Missing parameter: Object"
?>
</BODY>
</HTML>
