		<?
		$mtime2 = explode(" ", microtime());
		$endtime = $mtime2[1] + $mtime2[0];
		$totaltime = ($endtime - $starttime);
		$totaltime = number_format($totaltime, 7);

		echo "<font size=1 color=red face=Verdana><b>".$lang[187].": ".$totaltime." ".$lang[188]."</b></font>";
		?>
<center><Table Border="0" CellSpacing="0" CellPadding="0">
	<Tr>
		<Td Width="740" Height="73" background=<?php echo $url; ?>/images/bottom.jpg align=center class=desc valign=bottom>AzDGDatingLite, Version 1.1.0<br>Designed&Programming by <a href="http://www.azdg.com" target="_blank">AzDG</a><br>&copy;AzDGDatingLite - all right`s received.<br><br></Td>
	</Tr>
</Table>
</center>
</body>
</html>
