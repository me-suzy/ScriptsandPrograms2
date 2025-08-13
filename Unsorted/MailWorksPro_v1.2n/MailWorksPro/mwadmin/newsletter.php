<?php include("templates/top.php"); ?>
<?php include_once("includes/functions.php"); ?>
<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
  $what = @$_POST["what"];
  
  if($what == "")
  $what = @$_GET["what"];
  
  switch($what)
  {
    case "new":
      ShowNewForm();
	  break;
	case "doNew":
	  ProcessNew();
	  break;
	case "delete":
	  DeleteNewsletters();
	  break;
	case "modify":
	  ModifyNewsletter();
	  break;
	case "doModify":
	  ProcessModify();
	  break;

	default:
	  ShowNewsletterList();
  }
  
  function ShowNewForm()
  {
	$name = str_replace("\"", "'", @$_GET["name"]);
	$subject = str_replace("\"", "'", @$_GET["subject"]);
	$tType = @$_GET["tType"];
	
	if($tType == "")
	{
		$tType = 0;
		$tShow = true;
	}
	else
	{
		$tShow = false;
	}
  ?>  
	    <script language="JavaScript">
	    
			function changeTemplate()
			{
				var name = escape(document.frmNewsletter.name.value);
				var subject = escape(document.frmNewsletter.subject.value);
				var tType = document.frmNewsletter.templateId.selectedIndex;
				
				document.location.href = 'newsletter.php?what=new&name='+name+'&subject='+subject+'&tType='+tType;
			}

			function switchToWYSIWYG()
			{
				if(viewMode1 == 2)
				{
					alert('You must change back to WYSIWYG editing mode first.');
					return false;
				}
						
				return true;
			}

			function toggleP()
			{
				if(document.all.pMenu.style.display == 'inline')
				{
					document.all.pMenu.style.display = 'none';
					document.all.pText.innerHTML = 'Personalization Tags »';
				}
				else
				{
					document.all.pMenu.style.display = 'inline';
					document.all.pText.innerHTML = '« Personalization Tags';
				}
			}
	    
	    </script>
	    
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Create Newsletter</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
              Please complete the form below to create a new newsletter. Once created, this newsletter will be saved
              and you will have the option to send it to your subscriber list.
			<br><br>
			<a style="cursor:hand" onClick="toggleP()"><u><span id="pText">Personalization Tags »</span></u></a><br><br>
			<table style="display:none" width="95%" align="center" id="pMenu"><tr><td>
				<span class="Info">
					It's easy to add a subscribers first name, last name, complete name or email address to your newsletter. Simply
					type in one or more of the personalization tags shown below and they will be replaced with the appropriate values when
					the newsletter is sent.
					<br><br>
					For example: to greet your subscribers by their first name, type <font color="red">Hi %%first_name%%</font>.
					<ul>
						<li><i>%%complete_name%%</i> The users complete (first and last) name</li>
						<li><i>%%first_name%%</i> The users first name</li>
						<li><i>%%last_name%%</i> The users last name</li>
						<li><i>%%email%%</i> The users email address</li>
					</ul>
				</span>
			</td></tr></table>
			</span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">		  
		  </td></tr>
		  <tr><td>
		    <form onSubmit="return switchToWYSIWYG()" name="frmNewsletter" action="newsletter.php" method="post">
			  <input type="hidden" name="what" value="doNew">
			  <table width="95%" align="center" border="0">
			    <tr>
				  <td valign="top">
				    <span class="BodyText">
					  <br>
				      Newsletter Name:<br>(ex: "YourSite.com Newsletter Issue #23")<br>
					  <input type="text" name="name" size="40" value="<?php echo $name; ?>">
					  <br><br>
				      Subject Line For Newsletter:<br>(ex: "The YourSite.com Newsletter")<br>
					  <input type="text" name="subject" size="40" value="<?php echo $subject; ?>">
					  <br><br>
					  Newsletter Template:<br>
					  <select name="templateId" style="width: 196pt" onChange="changeTemplate()">
					    <?php GetTemplateList($tType); ?>
					  </select>
					  <br><br>
					  Newsletter Content:<br>
					  <?php
						
						// Do we need to show the content area for this newsletter?
						if($tType > 0)
						{
							$tType--;
							$result = mysql_query("select nFormat from templates order by nName asc limit $tType, 1");
							
							if($row = mysql_fetch_row($result))
							{
								$format = $row[0];
								
								if($format == "text")
								{
									// Show a <textarea> tag
								?>
									<textarea name="content" rows="20" cols="65"></textarea>
									<input type="hidden" name="contentType" value="0">
									<br>
								<?php
								}
								else
								{
									// Show an EWP control
									require_once("class.ewp.php");
									$myEWP = new EWP;
									$myEWP->HideTableButton();
									$myEWP->ShowControl(545, 265, "ewp_images");
									
									echo "<input type='hidden' name='contentType' value='1'>";
									echo "<br>";
								}
							}
							else
							{
								echo "<i>ERROR: Selected template was not found in the database</i>";
							}
						}
						else
						{
						?>
							<i>[Please select a template first]</i><br><br>
						<?php
						}
					  ?>
					  <input type="button" value="« Cancel" onClick="ConfirmCancel('newsletter.php')">
					  <input type="submit" name="submit" value="Add Newsletter »">
					</span>
				  </td>
				</tr>
			  </table>
			</form>
		  </td></tr>
		</table>
	<?php
	}
	
	function ProcessNew()
	{
		// Make sure all fields are completed
		$name = @$_POST["name"];
		$subject = @$_POST["subject"];
		$templateId = @$_POST["templateId"];
		$contentType = @$_POST["contentType"];
		$content = "";
		$err = "";
		
		if($contentType == 0)
		{
			$content = @$_POST["content"];
		}
		else
		{
			require_once("class.ewp.php");
			$myEWP = new EWP;
			$content = $myEWP->GetValue();
		}
		
		// Has the user entered all of the required fields?
		if($name == "")
			$err .= "<li>You forgot to enter a name for this newsletter</li>";
		
		if($subject == "")
			$err .= "<li>You forgot to enter a subject line for this newsletter</li>";
			
		if($templateId == -1)
			$err .= "<li>You forgot to select a template for this newsletter</li>";
			
		if($content == "")
			$err .= "<li>You forgot to enter content for this newsletter</li>";
			
		if($err != "")
		{
			?>
		  <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Create Newsletter</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  The form that you've just submitted is incomplete. Please review the errors below and then go back and
				  correct them:
				  <ul><?php echo $err; ?></ul>
				  <p style="margin-left:5pt">
				  <a href="javascript:history.go(-1)"><< Go Back</a><br>&nbsp;
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
			  </table>
			<?php
			die();
		}
		else
		{
			// All of the fields were complete, save this newsletter to the database
			$query = "insert into newsletters(nName, nTitle, nContent, nTemplateId, nStatus) ";
			$query .= "values('$name', '$subject', '$content', '$templateId', 'pending')";
			
			doDbConnect();

			if(@mysql_query($query))
			{
				// Query executed OK
				?>
					<table width="98%" align="center" border="0">
					  <tr><td height="30">
					    <span class="MainHeading">Create Newsletter</span>
					  </td></tr>
					  <tr><td background="images/yellowbg.gif">
					    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
						<span class="Info">
						  <br>
						  You have successfully created and saved a newsletter. To send this newsletter to your subscriber list
						  now, click on the "Send Newsletter" link below.
						  <br><br>
						  <a href="sendnewsletter.php">Send Newsletter</a> | <a href="newsletter.php">Continue</a>
						  <br>&nbsp;
  						</span>
					    </td></tr>
					    <tr><td background="images/yellowbg1.gif">
					    </td></tr>
					    <tr><td>
  					    </td></tr>
					  </table>
				<?php
			}
			else
			{
				// Query failed
				?>
				
				
				<?php
			}
		}
	}

	function ShowNewsletterList()
	{
		// Show a list of templates currently in the database
		doDbConnect();
		?>
			<script language="JavaScript">

				function CheckConfirmDelete()			
				{
					if(confirm('WARNING: You are about to permanently delete the selected newsletters. Click OK to continue.'))
						return true;
					else
						return false;
				}
			
			</script>
			
			<form onSubmit="return CheckConfirmDelete()" action="newsletter.php" method="post">
			<input type="hidden" name="what" value="delete">

			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Newsletters</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  A list of existing newsletters is shown below. To add a new newsletter, click the "Add Newsletter" link.
				  To remove one/more newsletters, click the checkbox for that newsletter and then click the "Delete Selected" button.
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
			  </table>
			  
			  <table width="98%" align="center" border="0" cellspacing="2" cellpadding="2">
				<tr>
					<td width="100%" colspan="4" height="40" class="Info">
						<a href="newsletter.php?what=new"> Add Newsletter »</a>
					</td>
				</tr>
				<tr>
					<td bgcolor="#183863" width="10%" height="20" class="MenuHeading">
						&nbsp;
					</td>
					<td bgcolor="#183863" width="35%" height="20" class="MenuHeading">
						Newsletter Name
					</td>
					<td bgcolor="#183863" width="35%" height="20" class="MenuHeading">
						Template
					</td>
					<td bgcolor="#183863" width="20%" height="20" class="MenuHeading">
						Status
					</td>
				</tr>
				<?php
				
					$result = mysql_query("select * from newsletters inner join templates on newsletters.nTemplateId = templates.pk_nId order by newsletters.nName asc");
					
					if(mysql_num_rows($result) == 0)
					{
					?>
						<tr>
							<td colspan="4" width="100%" height="25" class="Info">
								There are no newsletters in the database.
							</td>
						</tr>
					<?php
					}
					
					while($row = mysql_fetch_row($result))
					{
					?>
						<tr>
							<td width="10%" height="20" class="TableCell">
								<input type="checkbox" name="nId[]" value="<?php echo $row[0]; ?>">
							</td>
							<td width="35%" height="20" class="TableCell">
								<a href="newsletter.php?what=modify&nId=<?php echo $row[0]; ?>"><?php echo $row[1]; ?></a>
							</td>
							<td width="35%" height="20" class="TableCell">
								<?php echo $row[7]; ?>
							</td>
							<td width="20%" height="20" class="TableCell">
								<?php echo $row[5]; ?>
							</td>
						</tr>
					<?php
					}
				?>
					<tr>
						<td width="100%" colspan="4">
							<input type="submit" value="Delete Selected »">
						</td>
					</tr>
			  </table>
			</form>
		<?php
	}

	  function DeleteNewsletters()
	  {
		// This function will remove the selected newsletters from the database
		
		doDbConnect();
		
		$nId = @$_POST["nId"];
		$query = "";
		$result = "";

		?>

		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Delete Newsletter(s)</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">

		<?php
		
		if(is_array($nId) == true)
		{
			// Templates have been chosen, run 2 delete queries
			$query = "delete from newsletters where pk_nId = " . implode(" OR pk_nId = ", $nId);
			
			if(@mysql_query($query))
				$result = true;
			else
				$result = false;
			
			if($result == true)
			{
				// Query executed OK
				$status = "<br>You have successfully delete one/more newsletters from the database.<br><br>";
				$status .= "<a href='sendnewsletter.php'>Send Newsletter</a> | <a href='newsletter.php'>Continue >></a>";
			}
			else
			{
				// Delete querie(s) failed
				$status = "<br>An error occured while trying to delete the selected newsletter(s).<br><br>";
				$status .= "<a href='javascript:document.location.reload()'>Try Again</a>";
			}
		}
		else
		{
			// No newsletters have been chosen
			$status = "<br>You didn't select one/more newsletters to delete.<br><br>";
			$status .= "<a href='javascript:history.go(-1)'>Try Again</a>";
		}
		
		echo $status;
	  ?>
  		  </span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">
		  </td></tr>
		  <tr><td>
  		  </td></tr>
		</table>
	<?php
	}

	function ModifyNewsletter()
	{
		// Change the details of a newsletter
		doDbConnect();
		
		$nId = @$_GET["nId"];
		$name = str_replace("\"", "'", @$_GET["name"]);
		$subject = str_replace("\"", "'", @$_GET["subject"]);
		$tType = @$_GET["tType"];
				
		$result = mysql_query("select * from newsletters where pk_nId = $nId");
		
		if($row = mysql_fetch_array($result))
		{
			$templateId = $row["nTemplateId"];
			
			if(@$_GET["c"] != "")
			{
				// Workout the type of content area to show
				$tResult = mysql_query("select pk_nId from templates order by nName asc limit $tType, 1");
									
				if($tRow = mysql_fetch_row($tResult))
					$templateId = $tRow[0];
					
				$name = @$_GET["name"];
				$subject = @$_GET["subject"];
			}
			else
			{
				$name = $row["nName"];
				$subject = $row["nTitle"];
			}
		?>
			<script language="JavaScript">
	    
				function changeTemplate()
				{
					var name = escape(document.frmNewsletter.name.value);
					var subject = escape(document.frmNewsletter.subject.value);
					var tType = document.frmNewsletter.templateId.selectedIndex;

					document.location.href = 'newsletter.php?what=modify&c=1&name='+name+'&subject='+subject+'&nId=<?php echo $nId; ?>&tType='+tType;
				}
				
				function switchToWYSIWYG()
				{
					if(viewMode1 == 2)
					{
						alert('You must change back to WYSIWYG editing mode first.');
						return false;
					}
						
					return true;
				}
				
				function toggleP()
				{
					if(document.all.pMenu.style.display == 'inline')
					{
						document.all.pMenu.style.display = 'none';
						document.all.pText.innerHTML = 'Personalization Tags »';
					}
					else
					{
						document.all.pMenu.style.display = 'inline';
						document.all.pText.innerHTML = '« Personalization Tags';
					}
				}
	    
			</script>

			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Modify Newsletter</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
			      Please complete the form below to modify the selected newsletter. Click on the "Update Newsletter" button
			      to update the details of this newsletter.
					<br><br>
					<a href="javascript:void(0)" onClick="toggleP()"><u><span id="pText">Personalization Tags »</span></u></a><br><br>
					<table style="display:none" width="95%" align="center" id="pMenu"><tr><td>
						<span class="Info">
							It's easy to add a subscribers first name, last name, complete name or email address to your newsletter. Simply
							type in one or more of the personalization tags shown below and they will be replaced with the appropriate values when
							the newsletter is sent.
							<br><br>
							For example: to greet your subscribers by their first name, type <font color="red">Hi %%first_name%%</font>.
							<ul>
								<li><i>%%complete_name%%</i> The users complete (first and last) name</li>
								<li><i>%%first_name%%</i> The users first name</li>
								<li><i>%%last_name%%</i> The users last name</li>
								<li><i>%%email%%</i> The users email address</li>
							</ul>
						</span>
					</td></tr></table>
				</span>
			  </td></tr>
			  <tr><td background="images/yellowbg1.gif">		  
			  </td></tr>
			  <tr><td>
		    <form onSubmit="return switchToWYSIWYG()" name="frmNewsletter" action="newsletter.php" method="post">
			  <input type="hidden" name="what" value="doModify">
			  <input type="hidden" name="nId" value="<?php echo $nId; ?>">
			  <table width="95%" align="center" border="0">
			    <tr>
				  <td valign="top">
				    <span class="BodyText">
					  <br>
				      Newsletter Name:<br>(ex: "YourSite.com Newsletter Issue #23")<br>
					  <input type="text" name="name" size="40" value="<?php echo $name; ?>">
					  <br><br>
				      Subject Line For Newsletter:<br>(ex: "The YourSite.com Newsletter")<br>
					  <input type="text" name="subject" size="40" value="<?php echo $subject; ?>">
					  <br><br>
					  Newsletter Template:<br>
					  <select name="templateId" style="width: 196pt" onChange="changeTemplate()">
					    <?php GetTemplateList(-2, $templateId, false); ?>
					  </select>
					  <br><br>
					  Newsletter Content:<br>
					  <?php
						
							// Get the format type for this newsletter
							if(@$_GET["c"] == "")
							{
								$templateId = $row["nTemplateId"];
							}
							else
							{
								// Workout the type of content area to show
								$tResult = mysql_query("select pk_nId from templates order by nName asc limit $tType, 1");
								
								if($tRow = mysql_fetch_row($result))
									$templateId = $tRow[0];
							}
							
							$tResult = mysql_query("select nFormat from templates where pk_nId = " . $templateId);
							$tFormat = "";
							
							if($tRow = mysql_fetch_row($tResult))
								$format = $tRow[0];
								
							if($format == "text")
							{
								// Show a <textarea> tag
							?>
								<textarea name="content" rows="20" cols="65"><?php echo str_replace("<", "&lt;", $row["nContent"]); ?></textarea>
								<input type="hidden" name="contentType" value="0">
								<br>
							<?php
							}
							else
							{
								// Show an EWP control
								require_once("class.ewp.php");
								$myEWP = new EWP;
								$myEWP->SetValue($row["nContent"]);
								$myEWP->HideTableButton();
								$myEWP->ShowControl(545, 265, "ewp_images");
								
								echo "<input type='hidden' name='contentType' value='1'>";
								echo "<br>";
							}
					  ?>
					  <input type="button" value="« Cancel" onClick="ConfirmCancel('newsletter.php')">
					  <input type="submit" name="submit" value="Update Newsletter »">
					</span>
				  </td>
				</tr>
			  </table>
			</form>
			  </td></tr>
			</table>
		<?php
		}
		else
		{
		?>
			<table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Invalid Newsletter Selected</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <br>The newsletter that you have selected is either invalid or has been deleted from the database.
				  <br><br>
				  <a href="newsletter.php">Continue >></a>
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
			  </table>
		<?php
		}
	}

	function ProcessModify()
	{
		// Make sure all fields are completed
		$nId = @$_POST["nId"];
		$name = @$_POST["name"];
		$subject = @$_POST["subject"];
		$templateId = @$_POST["templateId"];
		$contentType = @$_POST["contentType"];
		$content = "";
		$err = "";
		
		if($contentType == 0)
		{
			$content = @$_POST["content"];
		}
		else
		{
			require_once("class.ewp.php");
			$myEWP = new EWP;
			$content = $myEWP->GetValue();
		}
		
		// Has the user entered all of the required fields?
		if($name == "")
			$err .= "<li>You forgot to enter a name for this newsletter</li>";

		if($subject == "")
			$err .= "<li>You forgot to enter a subject line for this newsletter</li>";
			
		if($templateId == -1)
			$err .= "<li>You forgot to select a template for this newsletter</li>";
			
		if($content == "")
			$err .= "<li>You forgot to enter content for this newsletter</li>";
			
		if($err != "")
		{
			?>
		  <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Moddify Newsletter</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  The form that you've just submitted is incomplete. Please review the errors below and then go back and
				  correct them:
				  <ul><?php echo $err; ?></ul>
				  <p style="margin-left:5pt">
				  <a href="javascript:history.go(-1)"><< Go Back</a><br>&nbsp;
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
			  </table>
			<?php
			die();
		}
		else
		{
		  doDbConnect();
		  
		  $query = "update newsletters set nName='$name', nTitle='$subject', nContent='$content', nTemplateId=$templateId where pk_nId=$nId";
		  $result = @mysql_query($query);
		  $status = "";
		  
		  if($result)
		  {
		    $status = "<br>Your newsletter has been successfully modified.<br><br>";
			$status .= "<a href='sendnewsletter.php'>Send Newsletter</a> | <a href='newsletter.php'>Continue >></a>";
		  }
		  else
		  {
			$status = "<br>Some errors occured while trying to modify this newsletter.<br><br>";
		    $status .= "<a href='javascript:history.go(-1)'><< Go Back</a><br>&nbsp;";
		  }
		  ?>
		    <table width="98%" align="center" border="0">
			  <tr><td height="30">
			    <span class="MainHeading">Modify Newsletter</span>
			  </td></tr>
			  <tr><td background="images/yellowbg.gif">
			    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
				<span class="Info">
				  <?php echo $status; ?>
  				</span>
			    </td></tr>
			    <tr><td background="images/yellowbg1.gif">
			    </td></tr>
			    <tr><td>
  			    </td></tr>
			  </table>
		  <?php
		}
	}
	?>
		
<?php include("templates/bottom.php"); ?>
