<?php

	// ERROR_REPORTING(E_NONE);
	
	require_once("ew_lang/language.php");
	
	$validImageTypes = array("image/pjpeg", "image/jpeg", "image/gif", "image/x-png");
	$ImageDirectory = $_GET["imgDir"];


	$URL = $_SERVER["HTTP_HOST"];
	$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
	$scriptName = dirname($_SERVER["SCRIPT_NAME"]) . "/ew/class.editworks.php";

	// Workout the location of class.editworks.php
	$url = $_SERVER["SERVER_NAME"];
	$scriptName = "class.editworks.php";
	$scriptDir = strrev($_SERVER["PATH_INFO"]);
	$slashPos = strpos($scriptDir, "/");
	$scriptDir = strrev(substr($scriptDir, $slashPos, strlen($scriptDir)));

	if($_GET["imgSrc"] != "")
	{
		// Delete the image
		$imgPath = $ImageDirectory . "/" . $_GET["imgSrc"];
		unlink($DOCUMENT_ROOT . $imgPath);
	}

	if($_GET["ToDo"] == "UploadImage")
	{
		
		//Upload the image to the images directory
		$newFileName = $_FILES["upload"]["name"];
		$newFileType = $_FILES["upload"]["type"];
		$newFileLocation = $_FILES["upload"]["tmp_name"];
		$validFileType = false;
		$errorText = "";
		
		// Is this a valid file type?
		if(in_array($newFileType, $validImageTypes))
			$validFileType = true;
	
		if($validFileType == false)
		{
			// Invalid file type
			$statusText = sTxtImageErr;
		}
		else
		{
			$uploadSuccess = copy($newFileLocation, ereg_replace("/$", "", $DOCUMENT_ROOT) . $ImageDirectory . "/" . $newFileName);
			$statusText = $newFileName . " " . sTxtImageSuccess . "!";
		}
	}

	$counter = 0;
	?>
	
		<script language=JavaScript>
		window.onload = this.focus

		function deleteImage(imgSrc)
		{
			var delImg = confirm("<?php echo sTxtImageDelete ?>?");

			if (delImg == true) {
				document.location.href = '<?php echo $_SERVER["PHP_SELF"]; ?>?ToDo=DeleteImage&imgDir=<?php echo $ImageDirectory; ?>&tn=<?php echo $_GET["tn"]; ?>&imgSrc='+imgSrc;
			}

		}

		function setBackground(imgSrc)
		{
			var setBg = confirm("<?php echo sTxtImageSetBackgd ?>?");

			if (setBg == true) {
				window.opener.setBackgd('http://<?php echo $_SERVER["HTTP_HOST"]; ?><?php echo $scriptDir; ?><?php echo $ImageDirectory; ?>/' + imgSrc);
				self.close();
			}
		}

		function viewImage(imgSrc)
		{
			var sWidth =  screen.availWidth;
			var sHeight = screen.availHeight;
			
			window.open('<?php echo $ImageDirectory; ?>/' + imgSrc, 'image', 'width=700, height=500,left='+(sWidth/2-350)+',top='+(sHeight/2-500));
		}

		function grey(tr) {
				tr.className = 'b4';
		}

		function ungrey(tr) {
				tr.className = '';
		}

		function insertImage(imgSrc) {
			error = 0

			var sel = window.opener.foo.document.selection;
			if (sel!=null) {
				var rng = sel.createRange();
			   	if (rng!=null) {

					HTMLTextField = '<img src="http://<?php echo $_SERVER["HTTP_HOST"]; ?><?php echo $ImageDirectory; ?>/'+imgSrc+'">';
					rng.pasteHTML(HTMLTextField)
				} // End if
			} // End If

			if (error != 1) {
				window.opener.foo.focus();
				self.close();
			}
		} // End function

		</script>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="15"><img src="_images/1x1.gif" width="15" height="1"></td>
		  <td class="heading1"><?php echo sTxtInsertImage; ?></td>
	  </tr>
		<tr>
			<form enctype="multipart/form-data" action="http://<?php echo $_SERVER["HTTP_HOST"]; ?><?php echo $_SERVER["PHP_SELF"]; ?>?ToDo=UploadImage&imgDir=<?php echo $ImageDirectory; ?>&tn=<?php echo $_GET["tn"]; ?>" method="post" id=form1 name=form1>
			<td>&nbsp;</td>
			  <td class="body"><?php echo sTxtInsertImageInst; ?><br>
				<?php echo sTxtCloseWin; ?>
				<br><br>
				<?php if($_GET["du"] != "1") { ?>
					<?php echo sTxtUploadImage; ?>: <input type="file" name="upload" class="Text220"> <input type="submit" value="Upload" class="Text50" id=submit1 name=submit1>
					<br><br><span class="err"><?php echo $statusText; ?></span>
				<?php } ?>
			  </td>
			</form>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="body">
			  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="bevel1">
				<tr>
				    <td>&nbsp;&nbsp;<?php echo sTxtInsertImage; ?></td>
				</tr>
			  </table>
			</td>
		</tr>
		<tr>
			<td colspan="2"><img src="ew_images/1x1.gif" width="1" height="10"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="body">

			<?php if($_GET["tn"] == 1) { ?>
			    <table border="0" cellspacing="0" cellpadding="10" width="98%" class="bevel2">
			<?php } else { ?>
				<table border="0" cellspacing="0" cellpadding="3" width="98%" class="bevel2">
			<?php } ?>

		  <tr>

		<?php if($_GET["tn"] == 1) {

			$dirHandle = @opendir($DOCUMENT_ROOT . $ImageDirectory) or die("Image directory has not been configured correctly");
			while(false !== ($file = readdir($dirHandle)))
			{
				if($file != "." && $file != "..")
				{
				?>
					<td width="25%">
						<span class="body"><?php echo $file; ?><br></span>
						<img align="left" src="<?php echo $ImageDirectory . "/" . $file; ?>" width="80" height="80" border=1>
						<br><a href="javascript:viewImage('<?php echo $file; ?>')" class="imagelink"><?php echo sTxtImageView; ?></a><br>
						<a href="javascript:insertImage('<?php echo $file; ?>')" class="imagelink"><?php echo sTxtImageInsert; ?></a><br>
						<?php if($_GET["dt"] != "0") { ?>
							<a href="javascript:setBackground('<?php echo $file; ?>')" class="imagelink"><?php echo sTxtImageBackgd; ?></a><br>
						<?php } ?>
						<?php if($_GET["dd"] != "1") { ?>
							<a href="javascript:deleteImage('<?php echo URLEncode($file); ?>')" class="imagelink"><?php echo sTxtImageDel; ?></a><br>
						<?php } ?>
					</td>
				<?php
					$counter++;
				}
				
				if($counter % 4 == 0)
					echo "</tr><tr>";
			}
		}
		else
		{
		?>
			</tr>
			<tr>
				<td width="40%">
					<span class="body"><b>&nbsp;<?php echo sTxtImageName; ?></b></span>
				</td>
				<td width="20%">
					<span class="body"><b><?php echo sTxtFileSize; ?></b></span>
				</td>
				<td width="10%">
					<span class="body"><b><?php echo sTxtImageView; ?></b></span>
				</td>
				<td width="10%">
					<span class="body"><b><?php echo sTxtImageInsert; ?></b></span>
				</td>
				<?php if($_GET["dt"] != "0") { ?>
					<td width="10%">
						<span class="body"><b><?php echo sTxtImageBackgd; ?></b></span>
					</td>
				<?php } ?>
				<?php if($_GET["dd"] != "1") { ?>
					<td width="10%">
						<span class="body"><b><?php echo sTxtImageDel; ?></b></span>
					</td>
				<?php } ?>
			</tr>
		<?php

			$dirHandle = opendir($DOCUMENT_ROOT . $ImageDirectory);
			while(false !== ($file = readdir($dirHandle)))
			{
				if($file != "." && $file != "..")
				{
				?>
					<tr onmouseover="grey(this)" onmouseout="ungrey(this)">
						<td width="40%" class="body">
							&nbsp;<?php echo $file; ?>
						</td>
						<td width="20%" class="body">
							<?php echo filesize(ereg_replace("/$", "", $DOCUMENT_ROOT) . $ImageDirectory . "/" . $file); ?> Bytes
						</td>
						<td width="10%">
							<a href="javascript:viewImage('<?php echo $file; ?>')" class="imagelink"><?php echo sTxtImageView; ?></a>
						</td>
						<td width="10%">
							<a href="javascript:insertImage('<?php echo $file; ?>')" class="imagelink"><?php echo sTxtImageInsert; ?></a>
						</td>
						<?php if($_GET["dt"] != "0") { ?>
							<td width="10%">
								<a href="javascript:setBackground('<?php echo $file; ?>')" class="imagelink"><?php echo sTxtImageBackgd; ?></a>
							</td>
						<?php } ?>
						<?php if($_GET["dd"] != "1") { ?>
							<td width="10%">
								<a href="javascript:deleteImage('<?php echo URLEncode($file); ?>')" class="imagelink"><?php echo sTxtImageDel; ?></a>
							</td>
						<?php } ?>
					</tr>
			<?php
			}
		}
	}
?>

</table>
	</td>
  </tr>
  <tr>
	<td colspan="2"><img src="ew_images/1x1.gif" width="1" height="10"></td>
  </tr>
  <tr>
  <td></td>
	<td>
	<input type="button" name="Submit" value="<?php echo sTxtCancel?>" class="Text50" onClick="javascript:window.close()">
	</td>
  </tr>
</table>	
