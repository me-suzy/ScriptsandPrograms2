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

function DisplayData($ID, $CatDesc)
{
	global $ErrorText, $AdminScript;

	// De-sanitise the input
    $CatDesc = stripslashes($CatDesc);

	DisplayGroupHeading(  ($ID != -1 ? 'Modify' : 'Create' ) . ' Category');
	?>
	<TABLE class="Admin">
		<FORM name="CatMaint" action="<?=$AdminScript?>?action=Categories" method="post">
	   		<INPUT type="hidden" name="id" value="<?= $ID ?>">

			<?php
			if ($ErrorText != '')
			{
				?>
				<TR>
					<TD colspan="3" class="ErrorText">
						<?= $ErrorText ?>
					</TD>
				</TR>
				<?php
			}
			?>

			<TR>
		 		<TD rowspan="5" align="center" width="20%">
			 		<IMG src="Inc/Images/CreateCategory.gif">
		 		</TD>
			</TR>
			<TR>
				<TD class="FieldPrompt">
					Category:
				</TD>
				<TD align="left">
					<INPUT type="text" name="CatDesc" value="<?= $CatDesc ?>" size="20" maxlength="255" />
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
					<INPUT class="but" type="submit" name="submit" value="Save Changes" />
				</TD>
			</TR>
		</FORM>
	</TABLE>
	<SCRIPT language="javascript" type="text/javascript">
		CatMaint.CatDesc.focus();
	</SCRIPT>
	<?php
}

$Action = isset($_GET['action']) ? $_GET['action'] : '';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$Confirm = isset($_GET['confirm']) ? $_GET['confirm'] : '';
$GetId = isset($_GET['id']) ? $_GET['id'] : '';
$ReturnText = ' Click <A href="' . $AdminScript . '?action=Categories">here</A> to return to category maintenance';

if ($Action == 'Categories' AND $Mode == 'delete' AND $Confirm == 'yes')
{
	// Get the category description
	$sql = "SELECT CatDesc FROM news_categories WHERE ID = $GetId";
	$result = mysql_query($sql) or die('Query failed : ' . mysql_error());
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$CatDesc = $row['CatDesc'];

	// Delete the category
	$result = mysql_query("DELETE FROM news_categories WHERE ID='$GetId'");
	if ($result)
	{
		// Write audit, if required
		if ($EnableAudit == 1)
			WriteAuditEvent(AUDIT_TYPE_CATEGORY, 'D', $GetId, "Category deleted: " . $CatDesc);

		// Remove all category-assignments for posts belonging to this category
		$result = mysql_query("DELETE FROM news_postcategories WHERE CatID = '$GetId'");
		if ($result)
		{
			$_SESSION['Info'] = 'The category has been deleted successfully.';
			header('location:' . $AdminScript . '?action=Categories');
			exit;
		}
		else
		{
			$_SESSION['Info'] = 'The category has been deleted, but there was an error when detaching articles.';
			header('location:' . $AdminScript . '?action=Categories');
			exit;
		}
	}
	else
	{
			$errormsg = 'Unable to remove the category from the database.' . $ReturnText;
			DisplayError($errormsg, 1);
	}
	echo "<br><br>";
}

elseif ($Action == 'Categories' AND $Mode == 'delete' AND $Confirm == '')
{
	// Request confirmation
	$categories = mysql_query("SELECT CatDesc FROM news_categories WHERE ID=$GetId");
	if (!$categories)
	{
		$errormsg = 'Error fetching category information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}
	$category = mysql_fetch_array($categories);

	DisplayGroupHeading('Remove Category');
	?>
	<TABLE class="Admin">
		<TR>
			<TD width="80">
				<CENTER><IMG src="Inc/Images/Question.gif"></CENTER>
			</TD>
			<TD>
				<DIV class="plaintext">
					Are you sure you want to remove category<I> <?= $category['CatDesc'] ?></I> from the news system?
				</DIV>
				<BR>
				<BR>
		  		<CENTER>
		  			<A href="<?=$AdminScript?>?action=Categories&amp;mode=delete&amp;confirm=yes&amp;id=<?=$GetId?>">Yes</A> |
		  			<A href="<?=$AdminScript?>?action=Categories">No</A>
		  		</CENTER>
			</TD>
		</TR>
	</TABLE>

	<?php
}

elseif ($Action == 'Categories' AND $Mode == 'edit')
{
	// Get category information from the database that matches the ID variable
	$category=mysql_query("SELECT CatDesc FROM news_categories WHERE ID=$GetId");
	if (!$category)
	{
		$errormsg = 'Error fetching category information from the database.';
		DisplayError($errormsg, 1);
		exit();
	}

	$category = mysql_fetch_array($category);
	$CatDesc = $category['CatDesc'];

	// Display the category information in the form for editing
	DisplayData($GetId, $CatDesc);
}

elseif ($Action == 'Categories' AND $Mode == 'create')
{
	DisplayData(-1, '');
}

elseif (isset($_POST['submit']))
{
	$ID = $_POST['id'];
	$CatDesc = $_POST['CatDesc'];

	// Verify that all fields have been completed
	if ($CatDesc == '')
	{
		$ErrorText = 'You must enter a category description.';
		DisplayData($ID, $CatDesc);
	}
	else
	{
		// Update/insert
		if ($ID <> -1)
			$sql = "UPDATE news_categories SET CatDesc='$CatDesc' WHERE ID='$ID'";
		else
			$sql = "INSERT INTO news_categories SET CatDesc='$CatDesc'";

		if (mysql_query($sql))
		{
			// Write audit, if required
			if ($EnableAudit == 1)
			{
				if ($ID <> -1)
					WriteAuditEvent(AUDIT_TYPE_CATEGORY, 'C', $ID, "Category updated: " . $CatDesc);
				else
					WriteAuditEvent(AUDIT_TYPE_CATEGORY, 'A', mysql_insert_id(), "Category created: " . $CatDesc);
			}

			$_SESSION['Info'] = 'The category details have been updated successfully.';
			header('location:' . $AdminScript . '?action=Categories');
			exit;
		}	
		else
		{	
			$errormsg = 'There was a problem updating the category details.' . $ReturnText;
			DisplayError($errormsg, 1);
		}
	}
}

elseif ($Action == 'Categories')
{
	// Display the category admin section
	DisplayGroupHeading('News Categories');
	?>
	<TABLE class="Admin">
 		<TR>
 			<TD width="100">
				<DIV align="center">
					<A href="<?=$AdminScript?>?action=Categories&amp;mode=create">
					<IMG src="Inc/Images/CreateCategory.gif" align="middle" border="0" alt="Create">
					<BR>Create Category</A>
				</DIV>
			</TD>
 			<TD width="450">
 				<DIV class="plaintext">Categories can be used to show different sets of items on different pages in your site. You can add as many categories as you like, and each item can belong to one category (and can also be unclassified).</DIV>
 			</TD>
 		</TR>
 	</TABLE>
	<BR>

	<?php
	DisplayGroupHeading('Category Maintenance');
	DisplayInfoMessage();
	?>
	<TABLE class="Admin">
		<TR>
			<TD>
				<BR>
				<TABLE border="0">

				<?php
				$categories = mysql_query("SELECT ID, CatDesc FROM news_categories ORDER BY CatDesc ASC");
				if (!$categories)
				{
					$errormsg = 'Error retrieving category list from database.';
					DisplayError($errormsg, 1);
				}

				// Display current categories in the system
				while ($category = mysql_fetch_array($categories))
				{
					$id = $category['ID'];
					$CatDesc = $category['CatDesc'] . " (ID=$id)";
					?>
					<TR>
						<TD class="plaintext">
							<a href="<?=$AdminScript?>?action=Categories&amp;mode=edit&amp;id=<?=$id?>"><IMG src="Inc/Images/EditCategory.gif" border="0" align="middle" alt="Edit"></a>
							<a href="<?=$AdminScript?>?action=Categories&amp;mode=delete&amp;id=<?=$id?>"><IMG src="Inc/Images/RemoveCategory.gif" border="0" align="middle" alt="Delete"></a>
						</TD>
						<TD class="plaintext">
							<?=$CatDesc ?>
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
