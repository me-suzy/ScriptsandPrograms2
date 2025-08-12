<?
	/*
	Silentum Boards v1.4.3
	board_bottom.php copyright 2005 "HyperSilence"
	Modification of this page allowed as long as this notice stays intact
	*/

	require_once("permission.php");
?>
<object>
<table cellspacing="0" style="margin-left: auto; margin-right: auto; width: <?=$twidth?>">
	<tr>
		<td style="text-align: center"><span class="normal">Powered by <strong><a href="http://www.hypersilence.net">Silentum Boards v1.4.3</a></strong> &copy; 2005 &quot;HyperSilence&quot;<? if($config['show_page_execution_time'] == 1) {
	$mtime = explode(" ",microtime());
	$mtime = $mtime[1] + $mtime[0];
	echo sprintf("<br />Page executed in ~%s seconds",round($mtime-$starttime,3));
	}

	echo "</span></td>
	</tr>
</table>
</object>
</body>
</html>";

	error_reporting(0);
	exit;
	exit;

	if($config['use_output_caching'] == 1) ob_end_flush();
?>