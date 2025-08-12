<?

function report_success($file='danger') {
	$touch = touch($file.'.success');
	?>
	<script language="javascript">
		top.upgrade_top.location = 'index.php?system_backed_up=yes';
	</script>
	<?
	return $touch;
}

function report_failure($file='danger') {
	$touch = touch($file.'.failure');
	?>
	<script language="javascript">
		top.upgrade_top.location = 'index.php?system_backed_up=yes';
	</script>
	<?
	return $touch;
}

function report_ignore($file='danger') {
	$touch = touch($file.'.ignore');
	?>
	<script language="javascript">
		top.upgrade_top.location = 'index.php?system_backed_up=yes';
	</script>
	<?
	return $touch;
}

?>