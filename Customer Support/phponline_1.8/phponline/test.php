<?php

$MsgTemp = "test";

$MsgTempOut = "";
for($i=0;$i<strlen($MsgTemp);$i++)
{
	$VCHR = strtoupper(dechex(ord($MsgTemp{$i})));
	if(strlen($VCHR)==1)
		$VCHR = '0'.$VCHR;
	$MsgTempOut .= ('\x'.$VCHR);
}



echo "test1=123&cass2=test&ltime=$DTime&sema10=1&msgtemp=$MsgTempOut&test=tes1212";

?>

