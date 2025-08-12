<?php 
include "./ressourcen/config.php";

$page->kopf();

$bgcolor[] = "#FFFFFF";
$bgcolor[] = "#EBEBEB";
$bgcolor[] = "#F5F5F5";
$bgcolor[] = "#E2E2E2";

$actionLogFile = $config->data_path.$_GET['dbs']."/action.log";
if (file_exists($actionLogFile)) $arrFile = file($actionLogFile);
else $arrFile = array();

if (count($arrFile) == 0) {
?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="txtblaugr2">
		<tr>
			<td>&nbsp;&nbsp;<strong><?php echo $_GET['dbs'];?></strong></td>
		</tr>
		<tr><td height="15"></td></tr>
		<tr><td>&nbsp;&nbsp;<?php echo $funcs->text("Action-Log ist leer!", "Action-Log is empty!!");?></td></tr>
		</table>

<?php 
} else {
	$arrFile = array_reverse($arrFile);
	?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="txtblaugr2">
		<tr>
			<td>&nbsp;&nbsp;<strong><?php echo $_GET['dbs'];?></strong></td>
		</tr>
		<tr><td height="15"></td></tr>
		</table>
		
		
		<table width="100%" border="0" cellspacing="0" cellpadding="5" class="txtkl">
		<tr bgcolor="#0B5A9B">
			<th class="txtgelb"><?php echo $funcs->text("Datum", "Date");?></th>
			<th class="txtgelb"><?php echo $funcs->text("Tabelle", "Table");?></th>
			<th class="txtgelb"><?php echo $funcs->text("Aktion", "Action");?></th>
			<th class="txtgelb"><?php echo $funcs->text("Beschreibung", "Description");?></th>
		</tr>
	<?php 
	for ($i=0; $i<count($arrFile); $i++) {
		$arrRow = explode(" | ", $arrFile[$i]);
		
		$datefuncs = new DateFuncs();
		$datefuncs->set_inputdate($arrRow[0]);
		?>
		<tr valign="top" bgcolor="<?php echo $bgcolor[$i%2];?>">
			<td<?php if (!$arrRow[4]) echo " class=\"err\";"?>><?php echo $datefuncs->get_date(false)."<br>".$datefuncs->get_time();?> h</td>
			<td<?php if (!$arrRow[4]) echo " class=\"err\";"?> bgcolor="<?php echo $bgcolor[($i%2)+2];?>"><?php echo $arrRow[2];?></td>
			<td<?php if (!$arrRow[4]) echo " class=\"err\";"?>><?php echo $arrRow[3];?></td>
			<td<?php if (!$arrRow[4]) echo " class=\"err\";"?> bgcolor="<?php echo $bgcolor[($i%2)+2];?>"><?php echo str_replace("<br>", " ", $arrRow[5]);?></td>
		</tr>
		<?php 
	}
	?>
		</table>
<?php 
}
?>
<br>
</body>
</html>

