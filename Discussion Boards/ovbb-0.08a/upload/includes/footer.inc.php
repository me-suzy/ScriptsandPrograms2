<div class="medium" align="center"><br>[ <b><a href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></a></b> ] [ <b><a href="mailto:<?php echo($CFG['general']['admin']['email']); ?>">Contact Us</a></b> ] [ <b><a href="online.php">Who's Online</a></b> ]</div>

</td></tr>
</table>

<div class="small" style="color: <?php echo($CFG['style']['credits']); ?>;" align="center">
	<br><b><?php echo(htmlspecialchars($CFG['general']['name'])); ?></b>
	<br><?php echo($CFG['general']['copyright']); ?>
	<br><a style="color: <?php echo($CFG['style']['credits']); ?>;" href="http://www.ovbb.org">Powered by OvBB V<?php echo($CFG['version']); ?></a><br><br>
</div>

<?php
	// Display the page generation statistics.
	echo(PageStats());
?>

</body>
</html>