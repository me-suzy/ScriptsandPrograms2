<!--
	Page display the upload interface to be used for uploading files into the FTP syste,m
-->
<?php
	$id = rand() . '.' . time();
?>
<html>
	<head>
		<title>Nucraft FTP Upload Form</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
	</head>
	
	<body>
		<table cellpadding="0" cellspacing="0" border="0">
		<form method="post" enctype="multipart/form-data"
			  onSubmit="window.open('/upload/progress.php?UPLOAD_IDENTIFIER=<?php echo $id; ?>','UploadMeter','width=370,height=115', true); return true; "
   			  action="?UPLOAD_IDENTIFIER=<?php echo $id; ?>">
		<input type="hidden" name="UPLOAD_IDENTIFIER" value="<?php echo $id; ?>">
			<tr><td colspan="2" align="center" class="titleCell">
				Nucraft FTP File Upload
			</td></tr>
			<tr><td height="10"></td></tr>
			
			<tr><td colspan="2" class="formtext">
				<input type="file" name="file" size="30" maxlength="255" /><br/>
				<input type="submit" name="submit" value="Upload File" />
			</td></tr>
			<tr><td height="5"></td></tr>
			
			<tr><td colspan="2" align="center" class="error">
			<?php echo isset($error_msg) ? $error_msg : ''; ?>
			</td></tr>
		</form>
		</table>
	</body>
</html>