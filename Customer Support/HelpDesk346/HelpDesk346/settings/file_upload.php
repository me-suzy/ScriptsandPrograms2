<?php
	//Revised on May 17, 2005
	//Revised by C.E.
	//Revision Number 2
	$path = getcwd();
	chdir('..');
	include_once "checksession.php";
	include_once "./includes/settings.php";
	include_once("./includes/functions.php");
	
	if (isset($_POST['comeback'])) include_once "./settings/file.upload.process.php";
	
	$chkSupp = ($OBJ->get('enable_file_blocking')) ? " checked=\"checked\" " : " ";
	#print "<pre>";
	#print_r($_POST);
	#print "</pre>";
	#exit;	
?>
<html>
	<head>
		<title>Helpdesk File Upload Settings</title>
		<link rel="stylesheet" href="./style.css" type="text/css" />
		<script language="Javascript">
			function Changer( )
			{
				with (document.myForm)
				{
					extAdd.disabled = !extAdd.disabled;
					cmdAddExt.disabled = !cmdAddExt.disabled;
					
					fname.disabled = !fname.disabled;
					cmdAddName.disabled = !cmdAddName.disabled;
					pos.disabled = !pos.disabled;
				}
			}
			
			function Loader()
			{
				with (document.myForm)
				{
					if (!enableBlock.checked) {
						extAdd.disabled = cmdAddExt.disabled = fname.disabled = cmdAddName.disabled = pos.disabled = true;
					}
				}
			}
		</script>
	</head>
	
	<body onload="Loader()">
	<?php
		if ($OBJ->get('navigation') == 'B') {
			$ppath = '../';
			include_once "./dataaccessheader.php";
		}
		else {
			include_once '../';
			include_once "./textnavsystem.php";
		}
		chdir($path);
	?>
	<table cellpadding="0" cellspacing="0" border="0">
	<form method="post" action="" name="myForm">
	<input type="hidden" name="comeback" value="1" />
		<tr><td colspan="2" align="center">
			<strong>File Upload Blocking Settings</strong>
		</td></tr>
		<tr><td height="5">
		
		<tr>
			<td class="formtext" valign="top">Maximum File Upload Size:</td>
			<td valign="top">
				<input type="text" name="size" value="<?php echo $OBJ->getSize(); ?>" /><br/>
				<i>You can use a number followed by "B", "KB", "MB", etc to denote the measurement of the size</i>
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		
		<tr><td colspan="2">
			<input type="checkbox" name="enableBlock" onclick="Changer()" value="1"<?php echo $chkSupp; ?>/>Enable File Upload Blocking
		</td></tr>
		<tr><td height="10"></td></tr>
		
		<tr>
			<th align="left">Blocked Extensions</th>
			<td><b>Extension</b>:&nbsp;<input type="text" name="extAdd" size="5" maxlength="10" />&nbsp;
									   <input type="submit" name="cmdAddExt" value="Add" class="button" /><br/>
			</td>
		</tr>
		<tr>
			<td></td>
			<td valign="top">
			<!-- Echo List of Blocked Extensions -->
			<?php
				if (count($OBJ->get('fext_block_list'))) {
					?>
			<select name="ext" size="5">
			<?php
					foreach ($OBJ->get('fext_block_list') as $index)
						echo '<option value="' . $index . '">' . $index . "</option>\n";
			?>
			</select>
			<input type="submit" name="cmdDelExt" size="Delete" class="button" value="Delete" />
			<?php
				}
				else {
					echo "-<i>No File Extensions Blocked</i>-";	
				}
			?>
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		
		<tr>
			<th align="left" valign="top">Blocked Filenames</th>
			<td>
				<b>Filename</b>:&nbsp;<input type="text" name="fname" size="10" maxlength="30" />&nbsp;
									  <input type="submit" name="cmdAddName" value="Add" class="button"/><br/>
				<b>Position</b>:&nbsp;<input type="radio" name="pos" value="0" checked="checked" />Matches&nbsp;
									  <input type="radio" name="pos" value="1" />Start&nbsp;
									  <input type="radio" name="pos" value="2" />Contains&nbsp;
									  <input type="radio" name="pos" value="3" />End
			</td>
		</tr>
		<tr>
			<td></td>
			<td valign="top">
			<?php
				if (count($OBJ->get('fname_block_list'))) {
				$display_array = array('Matches', 'Starts With', 'Contains', 'Ends With');
			?>
			<select name="name" size="5">
			<?php
					foreach ($OBJ->get('fname_block_list') as $index) {
						echo '<option value="' . $index['id'] . '">' . $index['value'] . ' (' . $display_array[$index['position']] . ')</option>' . chr(10);
						#print_r($index);
						#print "\n";
					}
			?>
			</select>
			<input type="submit" name="cmdDelName" size="Delete" class="button" value="Delete" />
			<?php
				}
				else {
					echo "-<i>No File Names Blocked</i>-";	
				}
			?>
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		
		<tr><td colspan="2" align="center" style="color:red">
			<input type="submit" name="submit" value="Update" class="button" /><br/>
			<?php echo isset($error_msg) ? $error_msg : ''; ?>
		</td></tr>
		<tr><td height="10"></td></tr>
		
		<tr><td colspan="2" align="center">
			<i>Before Commiting Extensions/Names to be blocked be sure that you select Enable File Blocked and then Update.<br/>
			Clicking the box to enable the fields and then adding block entries will have no effect.</i>
		</td></tr>
	</form>
	</table>
	</body>
</html>