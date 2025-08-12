<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Configuration</title>

<script language="JavaScript" type="text/javascript">
	function sende1() {
		document.forms.formdata.action = 'install.php';
		document.forms.formdata.submit();
	}
	function sende2() {
		document.forms.formdata.action = 'get_install.php';
		document.forms.formdata.submit();
	}
</script>
<style>
	body {
		font-family: Verdana, Arial, Helvetica, sans-serif ,geneva;
		font-size: 12px;
	}
	td {
		font-size: 11px;
	}
	.text {
		font-family: Verdana, Arial, Helvetica, sans-serif ,geneva;
		font-size: 11px;
	}
	.head {
		font-family: Verdana, Arial, Helvetica, sans-serif ,geneva;
		font-size: 18px;
		color: #000000;
	}
	.headwhite {
		font-family: Verdana, Arial, Helvetica, sans-serif ,geneva;
		font-size: 14px;
		color: #ffffff;
	}
	.comment {
		font-family: Verdana, Arial, Helvetica, sans-serif ,geneva;
		font-size: 14px;
		color: #000000;
	}
</style>
</head>

<body bgcolor="White" bottommargin="0" leftmargin="0" rightmargin="0" topmargin="0" marginheight="0" marginwidth="0">
<?php 
include "install_config.php";

// get project_info
$project_name = "";
$project_version = "";
if (file_exists($home."project_info.php")) {
	$a_project_info = file($home."project_info.php");
	for ($i=0; $i<count($a_project_info); $i++) {
		$a_data = explode(";", $a_project_info[$i]);
		if (strtolower($a_data[0]) == "project_name") $project_name = $a_data[1];
		if (strtolower($a_data[0]) == "version") $project_version = $a_data[1];
		if (strtolower($a_data[0]) == "install_protection") $install_protection = $a_data[1];
	}
}

if (!$o_inst_props->correctLogin()) $o_inst_props->s_mode = "private";
?>

<form name="formdata" action="install.php" method="post">
<input type="hidden" name="inst_savefile" value="1">
<input type="hidden" name="username" value="<?php echo $o_inst_props->s_user;?>">
<input type="hidden" name="password" value="<?php echo $o_inst_props->s_password;?>">
<table border="0" width="600" cellpadding="0" cellspacing="0">
<tr><td colspan="3" align="center"><span class="head"><b>Configuration</b></span></td></tr>
<?php if ($project_name != "") {?><tr><td colspan="3" align="center"><span class="head"><b><?php echo $project_name; if ($project_version != "") echo " V".$project_version;?></b></span></td></tr><?php }?>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3" align="right"><a target="_top" href="<?php echo $home;?>"><< back<?php if ($project_name != "") {echo " to ".$project_name; if ($project_version != "") echo " V".$project_version;}?></a></td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<?php 
$a_color[] = "#c0c0c0";
$a_color[] = "white";

$a_varnames = $o_inst_props->getVarnames();

if (isset($HTTP_POST_VARS['inst_savefile']) AND $HTTP_POST_VARS['inst_savefile']) {
	$o_inst_props->compareConfigFile();
	for ($i=0; $i<count($a_varnames); $i++)	{
		if (isset($HTTP_POST_VARS[$a_varnames[$i]])) 
			$o_inst_props->setVariableValue($a_varnames[$i], $HTTP_POST_VARS[$a_varnames[$i]]);
	}
	//echo "<pre>";
	//print_r($_POST);
	//echo "</pre>";
	
	if (isset($HTTP_POST_VARS['inst_savefile']) AND $HTTP_POST_VARS['inst_savefile']) {
		$b_ok = $o_inst_props->storeConfigFile();
		$b_ok2 = $o_inst_props->storeClassConfigFile();
?>
<tr><td colspan="3" align="center" class="head"><?php if (($b_ok) and ($b_ok2)) echo "Successfully saved"; else echo "Error saving configuration !!!";?></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<?php 
	}
	$b_ok = false;
}

//		echo "<pre>";
//		print_r($o_inst_props);
//		echo "</pre>";

for ($i=0; $i<count($a_varnames); $i++) {
	$a_variable = $o_inst_props->getVariableArray($a_varnames[$i]);
	$a_variable_values = $o_inst_props->getVariableValues($a_varnames[$i]);
	if ($a_variable['typ'] == "sep") {
?>
<tr><td colspan="3" height="30" bgcolor="#800000">&nbsp;</td></tr>
<?php 
	} elseif ($a_variable['typ'] == "head") {
?>
<tr><td colspan="3" height="30" bgcolor="#800000"><font class="headwhite"><strong><?php echo $a_variable['value'];?></strong></font></td></tr>
<?php 
	} elseif ($a_variable['typ'] == "comment") {
?>
<tr><td colspan="3" height="30" bgcolor="#ffffff"><font class="comment"><?php echo $a_variable['value'];?></font></td></tr>
<?php 
	} else {
?>
<tr bgcolor="<?php echo $a_color[$i%2];?>">
	<td height="50">
		<strong><?php echo $a_variable['descr'];?></strong>
	</td>
	<td width="20">&nbsp;</td>
	<td width="330">
		<?php 
	if (($a_variable['mode'] == "protected") AND ($o_inst_props->s_mode == "protected") OR ($o_inst_props->s_mode == "private") OR ($a_variable['mode'] == "private")) {?>
			<input type="password" disabled class="text" size="50" style="width=320;" name="<?php echo $a_variable['name'];?>" value="xxxxxxxxxxx">
	<?php } else {
		if (count($a_variable_values) > 0 ) {?>
			<select name="<?php echo $a_variable['name'];?>" class="text" style="width=320;">
				<?php  for ($k=0; $k<count($a_variable_values); $k++) {?>
				<option <?php if ($a_variable_values[$k] == $a_variable["value"]) echo "selected";?>><?php echo $a_variable_values[$k];?>
				<?php }?>
			</select>
		<?php } elseif ($a_variable['typ'] == "string") {?>
			<input type="text" class="text" size="50" style="width=320;" name="<?php echo $a_variable['name'];?>" value="<?php echo $a_variable["value"];?>">
		<?php } else {
				$a_variable['value'] = str_replace("<br>", "\r\n", $a_variable['value']);
				$a_variable['value'] = str_replace("\\", "", $a_variable['value']);
		?>
		<textarea class="text" cols="50" rows="5" style="width=320;" name="<?php echo $a_variable['name'];?>"><?php echo $a_variable["value"];?></textarea>
		<?php }
	}?>
	</td>
</tr>
<?php 
	}
}
?>
<tr><td colspan="2" bgcolor="#800000"></td><td height="30" bgcolor="#800000"><input type="submit" name='submitsave' onclick="sende1();" value="Save configuration"></td></tr>
<?php if (($o_inst_props->s_mode != "private") AND ($o_inst_props->s_mode != "protected") ) {?>
<tr><td colspan="3">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</td></tr>
<tr>
	<td colspan="3">To save the configuration use SAVE CONFIGURATION.<br>If it doesn't work, because your server is restricted, use GET CONFIGURATION FILE. Save this file to disc and copy it in the "res"-directory.</td>
</tr>
<tr><td colspan="2"></td><td height="30"><input type="submit" name='submitget' onclick="sende2();" value="Get configuration file"></td></tr>
<?php } ?>
</table>

<br>
</form>
<br>
</body>
</html>
