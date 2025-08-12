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

function DisplayData($ID, $TemplateName, $Headline, $ShortPost, $LongPost, $Comments)
{
	global $ErrorText, $AdminScript, $AdminTextareaColumns, $EnableComments;

	DisplayGroupHeading(  ($ID != -1 ? 'Modify' : 'Create' ) . ' Template');
	?>
	<TABLE class="Admin">
		<FORM name="TempMaint" action="<?=$AdminScript?>?action=Templates" method="post">
	   		<INPUT type="hidden" name="id" value="<?= $ID ?>">

			<?php
			if ($ErrorText != '')
			{
				?>
				<TR>
					<TD class="ErrorText">
						<?= $ErrorText ?>
					</TD>
				</TR>
				<?php
			}
			?>

			<TR>
				<TD>
					<TABLE border="0">
						<TR>
							<TD class="FieldPrompt">
								Template Name:
							</TD>
							<TD>
								<INPUT type="text" name="TemplateName" value="<?= $TemplateName ?>" size="20" maxlength="30" />
							</TD>
						</TR>
					</TABLE>
				</TD>
			</TR>



			<TR>
				<TD>
					<BR><HR><B>Headline Listing:</B>
				</TD>
			<TR>

			<TR>
				<TD>
					Valid Codes (case-sensitive):<br><strong>{headline} {author} (categories} {newsdate} {newstime} {timesread} {image} {imagel} {imagec} {imager} {id}</strong><br><br>
				</TD>
			</TR>

			<TR>
				<TD align="left">
					<TEXTAREA name="Headline" cols="<?=$AdminTextareaColumns?>" rows="10"><?=$Headline?></TEXTAREA>
				</TD>
			</TR>



			<TR>
				<TD>
					<BR><HR><B>Short Post:</B>
				</TD>
			<TR>

			<TR>
				<TD>
					Valid Codes (case-sensitive):<br><strong>{headline} {author} (categories} {newsdate} {newstime} {timesread} {image} {imagel} {imagec} {imager}  {news} {rating} {comments} {readmore} {id}</strong><br><br>
				</TD>
			</TR>

			<TR>
				<TD align="left">
					<TEXTAREA name="ShortPost" cols="<?=$AdminTextareaColumns?>" rows="10"><?=$ShortPost?></TEXTAREA>
				</TD>
			</TR>



			<TR>
				<TD>
					<BR><HR><B>Long Post:</B>
				</TD>
			<TR>

			<TR>
				<TD>
					Valid Codes (case-sensitive):<br><strong>{headline} {author} (categories} {newsdate} {newstime} {timesread} {image} {news} {rating} {comments} {id}</strong><br><br>
				</TD>
			</TR>

			<TR>
				<TD align="left">
					<TEXTAREA name="LongPost" cols="<?=$AdminTextareaColumns?>" rows="10"><?=$LongPost?></TEXTAREA>
				</TD>
			</TR>

			<?php
			if ($EnableComments == 1)
			{
				?>
				<TR>
					<TD>
						<BR><HR><B>Comments:</B>
					</TD>
				<TR>

				<TR>
					<TD>
						Valid Codes (case-sensitive):<br><strong>{commentdate} {commenttime} {name} {email} {ip} {comment}</strong><br><br>
					</TD>
				</TR>

				<TR>
					<TD align="left">
						<TEXTAREA name="Comments" cols="<?=$AdminTextareaColumns?>" rows="10"><?=$Comments?></TEXTAREA>
					</TD>
				</TR>
				<?php
			}
			else
			{
				?>
				<INPUT type="hidden" name="Comments" value="{comments}">
				<?php
			}
			?>

  			<TR>
  				<TD>
  					<HR width="100%" size="2">
				</TD>
			</TR>
			<TR>
				<TD class="C">
					<INPUT class="but" type="reset" name="submit" value="Reset">
					<INPUT class="but" type="submit" name="submit" value="Save Changes">
				</TD>
			</TR>
		</FORM>
	</TABLE>
	<SCRIPT language="javascript" type="text/javascript">
		TempMaint.TemplateName.focus();
	</SCRIPT>
	<?php
}

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : '';
$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=Templates">here</A> to return to Template maintenance';

if ($Action == 'Templates' AND $Mode == 'delete' AND $Confirm == 'yes')
{
	if ($GetId == 1)
	{
		$errormsg = 'Illegal attempt to delete the default template!' . $ReturnText;
		DisplayError($errormsg, 0);
		exit;
	}

	// Get the template name
	$sql = "SELECT TemplateName FROM news_templates WHERE ID = $GetId";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$TemplateName = $row['TemplateName'];

	// Delete the category
	$result = mysql_query("DELETE FROM news_templates WHERE ID=$GetId");
	if ($result)
	{
		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_TEMPLATE, 'D', $GetId, "Template deleted: " . $TemplateName);

		// Change all posts to have the default template
		$result = mysql_query("UPDATE news_posts SET TemplateID = '1' WHERE TemplateID = $GetId");
		if ($result)
		{
			$_SESSION['Info'] = 'The template has been deleted. Associated articles now assigned to the Default template.';
			header('location:' . $AdminScript . '?action=Templates');
			exit;
		}
		else
		{
			$_SESSION['Info'] = 'The template has been deleted, but there was an error when detaching articles.';
			header('location:' . $AdminScript . '?action=Templates');
			exit;
		}
	}
	else
	{
		$errormsg = 'There was an error deleting the template from the database.' . $ReturnText;
		DisplayError($errormsg, 1);
	}
}

elseif ($Action == 'Templates' AND $Mode == 'delete' AND $Confirm == '')
{
	// Request confirmation
	$templates = mysql_query("SELECT TemplateName FROM news_templates WHERE ID=$GetId");
	if (!$templates)
	{
		$errormsg = 'Error fetching template information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}
	$template = mysql_fetch_array($templates);

	DisplayGroupHeading('Remove Template');
	?>
	<TABLE class="Admin">
		<TR>
			<TD width="80">
				<CENTER><IMG src="Inc/Images/Question.gif" alt="Question"></CENTER>
			</TD>
			<TD>
				<DIV class="plaintext">
					Are you sure you want to remove template<I> <?= $template['TemplateName'] ?></I> from the news system?
				</DIV>
				<BR>
				<BR>
		  		<CENTER>
		  			<A href="<?=$AdminScript?>?action=Templates&amp;mode=delete&amp;confirm=yes&amp;id=<?=$GetId?>">Yes</A> |
		  			<A href="<?=$AdminScript?>?action=Templates">No</A>
		  		</CENTER>
			</TD>
		</TR>
	</TABLE>

	<?php
}

elseif ($Action == 'Templates' AND $Mode == 'edit')
{
	// Get template information from the database that matches the ID variable
	$templates=mysql_query("SELECT * FROM news_templates WHERE ID=$GetId");
	if (!$templates)
	{
		$errormsg = 'Error fetching template information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$template = mysql_fetch_array($templates);
	$TemplateName = $template['TemplateName'];
	$Headline = $template['Headline'];
	$ShortPost = $template['ShortPost'];
	$LongPost = $template['LongPost'];
	$Comments = $template['Comments'];

	// Display the template information in the form for editing
	DisplayData($GetId, $TemplateName, $Headline, $ShortPost, $LongPost, $Comments);
}

elseif ($Action == 'Templates' AND $Mode == 'copy')
{
	// Get template information from the database that matches the ID variable
	$templates=mysql_query("SELECT * FROM news_templates WHERE ID=$GetId");
	if (!$templates)
	{
		$errormsg = 'Error fetching template information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$template = mysql_fetch_array($templates);
	$TemplateName = $template['TemplateName'];
	$Headline = $template['Headline'];
	$ShortPost = $template['ShortPost'];
	$LongPost = $template['LongPost'];
	$Comments = $template['Comments'];

	// Display the template information in the form for editing
	DisplayData(-1, $TemplateName, $Headline, $ShortPost, $LongPost, $Comments);
}

elseif ($Action == 'Templates' AND $Mode == 'create')
{
	DisplayData(-1, '', '', '', '', '');
}

elseif (isset($_POST['submit']))
{
	$ID = $_POST['id'];
	$TemplateName = $_POST['TemplateName'];
	$Headline = $_POST['Headline'];
	$ShortPost = $_POST['ShortPost'];
	$LongPost = $_POST['LongPost'];
	$Comments = $_POST['Comments'];

	// Verify that all fields have been completed
	if ($TemplateName == '' || $Headline == '' || $ShortPost == '' || $LongPost == '' || $Comments == '')
	{
		$ErrorText = 'You must enter a template description and define all templates.';
		DisplayData($ID, $TemplateName, $Headline, $ShortPost, $LongPost, $Comments);
	}
	else
	{
		// Update/insert
		if ($ID <> -1)
			$sql = "UPDATE news_templates SET TemplateName='$TemplateName', Headline='$Headline', ShortPost='$ShortPost', LongPost = '$LongPost', Comments = '$Comments' WHERE ID='$ID'";
		else
			$sql = "INSERT INTO news_templates SET TemplateName='$TemplateName', Headline='$Headline', ShortPost='$ShortPost', LongPost = '$LongPost', Comments = '$Comments'";

		if (mysql_query($sql))
		{
			// Write audit, if required
			if ($EnableAudit == 1)
			{
				if ($ID <> -1)
					WriteAuditEvent(AUDIT_TYPE_TEMPLATE, 'C', $ID, "Template updated: " . $TemplateName);
				else
					WriteAuditEvent(AUDIT_TYPE_TEMPLATE, 'A', mysql_insert_id(), "Template created: " . $TemplateName);
			}

			$_SESSION['Info'] = 'The template details have been updated successfully.';
			header('location:' . $AdminScript . '?action=Templates');
			exit;
		}	
		else
		{	
			$errormsg = 'There was a problem updating the template details.' . $ReturnText;
			DisplayError($errormsg, 1);
		}
	}
}

elseif ($Action == 'Templates')
{
	// Display the category admin section
	DisplayGroupHeading('News Templates');
	?>
	<TABLE class="Admin">
 		<TR>
 			<TD width="100">
				<DIV align="center">
					<A href="<?=$AdminScript?>?action=Templates&amp;mode=create">
						<IMG src="Inc/Images/CreateTemplate.gif" align="middle" border="0" alt="Create">
						<BR>Create Template
					</A>
				</DIV>
			</TD>
 			<TD width="450">
 				<DIV class="plaintext">Templates are used to control the display of news articles. You can add as many templates as you like, and each news article can be assigned to one template.</DIV>
 			</TD>
 		</TR>
 	</TABLE>
	<BR>

	<?php
	DisplayGroupHeading('Template Maintenance');
	DisplayInfoMessage();
	?>
	<TABLE class="Admin">
		<TR>
			<TD>
				<TABLE border="0">

				<?php
				$templates = mysql_query("SELECT ID, TemplateName FROM news_templates ORDER BY TemplateName ASC");
				if (!$templates)
				{
					$errormsg = 'Error retrieving template list from database.';
					DisplayError($errormsg, 1);
				}

				// Display current templates in the system
				while ($template = mysql_fetch_array($templates))
				{
					$id = $template['ID'];
					?>
					<TR>
						<TD class="plaintext">
							<A href="<?=$AdminScript?>?action=Templates&amp;mode=edit&amp;id=<?=$id?>">
								<IMG src="Inc/Images/EditTemplate.gif" border="0" align="middle" alt="Edit">
							</A>
							<A href="<?=$AdminScript?>?action=Templates&amp;mode=copy&amp;id=<?=$id?>">
								<IMG src="Inc/Images/CopyTemplate.gif" border="0" align="middle" alt="Copy">
							</A>
							<?php
							if (($id != 1 ))			// Cannot delete default template
							{
								?>
								<A href="<?=$AdminScript?>?action=Templates&amp;mode=delete&amp;id=<?=$id?>">
									<IMG src="Inc/Images/RemoveTemplate.gif" border="0" align="middle" alt="Delete">
								</A>
								<?php
							}
							else
							{
								?>
								<IMG src="Inc/Images/RemoveTemplateDisabled.gif" border="0" align="middle" alt="Cannot Delete Default">
								<?php
							}
							?>
						</TD>
						<TD class="plaintext">
							<?=$template['TemplateName'] ?>
						</TD>
					</TR>
					<?php
				}
				?>
				</TABLE>
			</TD>
		</TR>
	</TABLE>
	<?php
}
?>