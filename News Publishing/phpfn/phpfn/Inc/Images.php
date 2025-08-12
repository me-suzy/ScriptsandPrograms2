<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

CheckAuthority();

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : '';
$GetId = isset($_GET['ID']) ? $_GET['ID'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=ImageList">here</A> to return to image maintenance';

if ($Action == 'ImageList' AND $Mode == 'rename')
{
	// Rename?
	if (isset($_POST['submit']) OR isset($_POST['newcommon']))
	{
		$ImageID = $_POST['ID'];

		// Invalid data?
		if ($_POST['newcommon'] == '')
		{
			$errormsg = 'The Common Name field cannot be left blank.  Go back and try again.';
			DisplayError($errormsg, 0);
		}
		else
		{
			$NewName = $_POST['newcommon'];
			$sql = "UPDATE news_images SET ImageName='$NewName' WHERE ID='$ImageID'";

			if (mysql_query($sql))
			{
				// Write audit, if required
				if ($EnableAudit == 1)
					WriteAuditEvent(AUDIT_TYPE_IMAGE, 'C', $GetId, "Image renamed: " . $NewName);

				$_SESSION['Info'] = "The image's 'common name' has been updated.";
				header('location:' . $AdminScript . '?action=ImageList');
				exit;
			}
			else
			{
				$errormsg = 'An error was found trying to update the image name.';
				DisplayError($errormsg, 1);
			}
		}
	}
	else
	{
		$resultrow = mysql_query("SELECT * FROM news_images WHERE ID='$GetId'"); 
		$result = mysql_fetch_array($resultrow);

		DisplayGroupHeading('Rename Image');
		?>

		<FORM name="ImageMaint" action="<?=$AdminScript?>?action=ImageList&amp;mode=rename" method="post">
			<TABLE class="Admin">
				<TR>
					<TD width="100">
						<CENTER><IMG src="<?=$NewsDir.$ImageDir?>/<?=$result['ImageFilename'] ?>"></CENTER>
					</TD>
					<TD colspan="2">
						<CENTER>If needed you can change the 'common name' associated with an image.</CENTER>
					</TD>
				</TR>
				<TR>
					<TD>
					</TD>
					<TD align="right">Common Name:</TD>
					<TD align="left">
						<INPUT type="text" name="newcommon" value="<?php echo $result['ImageName']; ?>" size="20" maxlength="100">
					</TD>
				</TR>
				<TR>
					<TD colspan="3">
						<HR width="100%" size="2">
					</TD>
				</TR>
				<TR>
					<TD colspan="3" class="C">
						<INPUT type="hidden" name="ID" value="<?=$GetId?>">
						<INPUT type="hidden" name="ImageName" value="<?php echo $result[ImageName]; ?>">
						<INPUT class="but" type="reset" name="submit" value="Reset">
						<INPUT class="but" type="submit" name="submit" value="Save Changes">
					</TD>
				</TR>
			</TABLE>
		</FORM>
		<SCRIPT language="javascript" type="text/javascript">
			ImageMaint.newcommon.focus();
		</SCRIPT>
		<?php
	}

// Attempt to delete an image
}
elseif ($Action == 'ImageList' AND $Mode == 'delete' AND $Confirm == 'yes')
{
	$result = mysql_query("SELECT * FROM news_images WHERE ID='$GetId'");
	$row = mysql_fetch_array($result);
	$ImageName = $row['ImageName'];
	$ImageFileName = $row['ImageFilename'];

	// First, remove the image from the database
	$result = mysql_query("DELETE FROM news_images WHERE ID=$GetId");
	if ($result)
	{
		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_IMAGE, 'D', $GetId, "Image deleted: " .$ImageName);

		$updatenews = mysql_query("UPDATE news_posts SET ImageID = '' WHERE ImageID = $GetId");
		if ($updatenews)
		{
			// Now remove the file from the server
			$filelink =  $_SERVER['DOCUMENT_ROOT'] . $NewsDir . $ImageDir . "/" . $ImageFileName;
			$delete = @unlink($filelink);
			if ($delete)
			{
				$_SESSION['Info'] = 'The image file has been removed from the server, and the database has been updated.';
				header('location:' . $AdminScript . '?action=ImageList');
				exit;
			}
			else
			{
				$errormsg = 'There was a problem removing the image file from the system.';
				DisplayError($errormsg, 1);
			}
		}
		else
		{
			$errormsg = 'There was an error whilst detaching the image from news articles.';
			DisplayError($errormsg, 1);
		}
	}
	else
	{
		$errormsg = 'There was an error whilst removing the image from the Images table.';
		DisplayError($errormsg, 1);
	}
}

// Request confirmation for the delete
elseif ($Action == 'ImageList' AND $Mode == 'delete' AND $Confirm == '')
{
	$results = mysql_query("SELECT * FROM news_images WHERE ID='$GetId'");
	$result = mysql_fetch_array($results);

	DisplayGroupHeading('Delete Image');
	?>
	<TABLE class="Admin">
		<TR>
			<TD width="100">
				<CENTER><IMG src="<?=$NewsDir.$ImageDir?>/<?=$result['ImageFilename'] ?>"></CENTER>
			</TD>
			<TD>
				<CENTER>You have requested to delete the following image.<BR>
				Are you sure you want to remove this image?<BR><BR>
				<A href="<?=$AdminScript?>?action=ImageList&amp;mode=delete&amp;confirm=yes&amp;ID=<?= $result['ID'] ?>">Yes</A> | 
				<A href="<?=$AdminScript?>?action=ImageList">No</A>
			</TD>
		</TR>
	</TABLE>
	<?php

// Upload an image
}

elseif ($Action == 'ImageList' AND $Mode == 'upload')
{
	if (!isset($_FILES['ImageFile']))
	{
		// Display the form
		DisplayGroupHeading('Upload New Image');
		?>
		<TABLE class="Admin">
			<FORM name="ImageUpload" enctype="multipart/form-data" action="<?=$AdminScript?>?action=ImageList&mode=upload" method="post">
				<INPUT type="hidden" name="MAX_FILE_SIZE" value="<?=$MaxImageFileSize?>">
				<TR>
					<TD rowspan="2" align="center" width="20%">
						<IMG src="Inc/Images/ImageUploads.gif">
					</TD>
					<TD align="right">
						Common Name:
					</TD>
					<TD>
						<INPUT type="text" name="CommonName" size="12">
					</TD>
				</TR>
				<TR>
					<TD align="right">
						File Path:
					</TD>
					<TD>
						<INPUT name="ImageFile" size="12" type="file">
					</TD>
				</TR>
				<TR>
					<TD colspan="3">
						<HR width="100%" size="2">
					</TD>
				</TR>
				<TR>
					<TD colspan="3" class="C">
						<INPUT class="but" type="reset" name="submit" value="Reset" />
						<INPUT class="but" type="submit" name="submit" value="Upload File" />
					</TD>
				</TR>
			</FORM>
		</TABLE>
		<SCRIPT language="javascript" type="text/javascript">
			ImageUpload.CommonName.focus();
		</SCRIPT>

        <?php
	}
	else
	{
        print "<pre>";

		// First we check to see if the file already exists on the server by checking it against the db
		$ImageFilename = $_FILES['ImageFile']['name'];
		$filethere = mysql_query("SELECT * FROM news_images WHERE ImageFilename = '$ImageFilename'");
       	$filecount = mysql_num_rows($filethere);
		if ($filecount > 0)
		{
			$errormsg = 'A file already exists with this file name.  Change the file name and try uploading it again.';
			DisplayError($errormsg, 0);
			exit();
		}
		// Now we're going to see if null data was entered
		if ($_POST['CommonName'] == '')
		{
			$errormsg = 'The entire form must be filled in.  Go back and try again.';
			DisplayError($errormsg, 0);
		}
		elseif (move_uploaded_file($_FILES['ImageFile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $NewsDir . $ImageDir . "/" . $_FILES['ImageFile']['name']))
		{
			// It worked so lets move the data into the database now...
			$ImageFilename = $_FILES['ImageFile']['name'];
			$sql = "INSERT INTO news_images SET ImageName='$_POST[CommonName]', ImageFilename='$ImageFilename'";
            if (mysql_query($sql))
            {
				// Write audit, if required
				if ($EnableAudit == 1)
						WriteAuditEvent(AUDIT_TYPE_IMAGE, 'A', mysql_insert_id(), "Image created: " . $_POST['CommonName']);

				$_SESSION['Info'] = 'Image successfully uploaded and added to database.';
				header('location:' . $AdminScript . '?action=ImageList');
				exit;
			}
			else
			{
				$errormsg = 'There was an error adding the new image.';
				DisplayError($errormsg, 1);
			}
        }
        else
        {
			// The upload failed.
			$errormsg = 'An error occured while trying to upload the image to the server.<br>Make sure the correct image directory is specified in the config<br>file and that this directory has been CHMOD to 777.';
			DisplayError($errormsg, 0);
		}
	}
}
elseif ($Action == 'ImageList')
{
	DisplayGroupHeading('News Images');
	?>
	<TABLE class="Admin" >
		<TR>
			<TD width="110">
				<DIV align="center">
					<A href="<?=$AdminScript?>?action=ImageList&amp;mode=upload"><IMG src="Inc/Images/Images.gif" border="0" alt="Images"><BR>Upload Images</A>
				</DIV>
			</TD>
			<TD>
				Each post can have one image associated with it. It's a good idea to keep these images fairly small in size, otherwise they will become too dominant on the page. Think of them as a "logo" style of image. 
			</TD>
		</TR>
	</TABLE>
	<BR>
	<BR>

	<?php
	DisplayGroupHeading('Image Maintenance');
	DisplayInfoMessage();

	// Set the display details
	$ShowPage = isset($_REQUEST['ShowPage']) ? $_REQUEST['ShowPage'] : 1;

	$ResultSet= mysql_query('SELECT * FROM news_images');
	$NumRecords = mysql_num_rows($ResultSet);

	// Construct a paging-bar
	$RecStart = $AdminImagesPerPage * ($ShowPage-1); 
	$PageNavBar = ConstructPagingBar($_SERVER['PHP_SELF'].'?action=ImageList', $NumRecords, $AdminImagesPerPage, $ShowPage, $RecStart, $AdminImagesPageBar, '', '');

	// Grab all the current images...
	$ResultSet = mysql_query("SELECT * FROM news_images ORDER BY ImageName ASC LIMIT $RecStart, $AdminImagesPerPage");
	$NumRecords = mysql_num_rows($ResultSet);

	// If there are any images in the database, display them
	if ($NumRecords > 0)
	{
		$buildpass=0;
		?>
		
		<TABLE class="Admin">
			<TR>
				<?php
				// Build the table, 3 images per row
				while ($Row = mysql_fetch_array($ResultSet))
				{
					$buildpass++;
					?>
					<TD class="ImageListing">
						<IMG src="<?=$NewsDir.$ImageDir?>/<?=$Row['ImageFilename'] ?>" border="0" alt="<?=$Row['ImageName'] ?>">
						<BR><STRONG><?= $Row['ImageName'] ?></STRONG>
						<BR>
						<?php echo $Row['ImageFilename']; ?><BR>
						<A href="<?=$AdminScript?>?action=ImageList&amp;mode=rename&amp;ID=<?= $Row['ID'] ?>">Rename</A> | 
						<A href="<?=$AdminScript?>?action=ImageList&amp;mode=delete&amp;ID=<?= $Row['ID'] ?>">Delete</A>
					</TD>
					<?php
					// Time to start a new row?
					if ($buildpass == 3)
					{
						$buildpass = 0;
						echo ('</TR><TR>');
					}
				}
				?>
			</TR>
		</TABLE>
		<BR><BR>
		<DIV align="center"><?= $PageNavBar ?></DIV>
		<?php
	}
}
?>
