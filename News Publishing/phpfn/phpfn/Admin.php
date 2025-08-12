<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Config/Config.php');
require_once('Inc/AdminFunctions.php');

$AdminScript = $_SERVER['PHP_SELF'];

// Is the installer still enabled?
if ($AllowInstall)
{ 
   print '<HR><CENTER><B>ERROR!!!!<BR>Please disable installation (within the Config file) urgently!</B></CENTER><HR>';
   exit();
}

// Make sure we're authorised
require_once ('Inc/AccessControl.php');

// Activate buffering
ob_start();

// Determine the action. No action? Default to a news-list
$Action = isset($_GET['action']) ? $_GET['action'] : 'NewsList';
$Mode = isset($_GET['mode']) ? $_GET['mode'] : '';

// If session restrictions do not exists, make defaults
SetAdminDefaultRestrictions();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
	<HEAD>
		<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<TITLE><?= $SiteDescription ?> Administration</TITLE>
		<LINK rel="stylesheet" href="Inc/Styles.css" type="text/css">
		<SCRIPT type="text/javascript" language="javascript" src="Inc/AdminJavaScript.js"></SCRIPT>

		<?php
		if ($UseTinyMCE == 1)
		{
			?>
			<!-- \\\\\\\\\\\\\ Begin TinyMCE 2.0RC3 \\\\\\\\\\\\\\\\ -->
			<SCRIPT language="javascript" type="text/javascript" src="Inc/tinymce/jscripts/tiny_mce/tiny_mce.js"></SCRIPT>
			<SCRIPT language="javascript" type="text/javascript">
				tinyMCE.init({
					mode : "exact",
					elements : "ShortPost_news,LongPost_news",
					force_br_newlines : true,
					theme : "advanced",
					language : "en",
					plugins : 		"table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,filemanager,ibrowser",
					theme_advanced_buttons1_add_before : "save,separator",
					theme_advanced_buttons1_add : "fontselect,fontsizeselect",
					theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor,print",
					theme_advanced_buttons2_add_before: "cut,copy,paste,separator,replace,separator",
					theme_advanced_buttons3_add_before: "ibrowser,filemanager,tablecontrols,separator",
					theme_advanced_buttons3_add: "emotions,iespell,flash,advhr",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_path_location : "bottom",
					plugin_insertdate_dateFormat : "%Y-%m-%d",
					plugin_insertdate_timeFormat : "%H:%M:%S",
					extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
				});
			</SCRIPT>
			<?php
		}
		?>

	</HEAD>
	<BODY>
		<TABLE width="100%" border="0" align="center" cellspacing="0" cellpadding="3">
			<TR>
				<TD valign="bottom" class="Welcome">
					<DIV align="center"><?= $WelcomeMessage . " " . $LoggedInFullName . " to " . $SiteDescription ?></DIV>
				</TD>
				<TD align="right">
					<A href="Admin.php"><IMG src="Inc/Images/<?= $AdminSiteLogo ?>" align="right" border="0" alt="Logo"></A>
				</TD>
			</TR>
		</TABLE>

		<TABLE width="100%" border="1" align="center" cellspacing="0" cellpadding="0">
			<TR>
				<TD width="130" valign="top">
					<TABLE width="158" align="center" border="1" cellspacing="0" cellpadding="0">
						<TR>
							<TD colspan="3" class="MenuHeading">
								&nbsp;&nbsp;News Tasks
							</TD>
						</TR>
					</TABLE>
					
					<TABLE width="158" align="center" border="0" cellspacing="2" cellpadding="0">
						<TR>
							<TD class="MenuOption">
								<A href="<?=$AdminScript?>?action=news&amp;mode=post">New Article</A><BR />
								<A href="<?=$AdminScript?>?action=NewsList">Edit Articles</A><BR />
								<?
								if (($LoggedInCanApprovePosts) && ($ArticlesRequireApproval))
								{
									?>
									<A href="<?=$AdminScript?>?action=postsapproval">Approve Posts</A><BR />
									<?
								}
								if ($EnableComments)
								{
									?>
									<A href="<?=$AdminScript?>?action=CommentsApproval">Approve Comments</A><BR />
									<?
								}
								?>
								<A href="<?=$SiteDomain ?>" target="_blank">View Live Site</A><BR />
							</TD>
						</TR>
					</TABLE>
					<BR />

					<?php
					// Allow admin functions?
					if ($LoggedInAccessLevel == "2")
					{
						?>
						<TABLE width="158" align="center" border="1" cellspacing="0" cellpadding="0">
							<TR>
								<TD colspan="3" class="MenuHeading">
									&nbsp;&nbsp;Admin Tools
								</TD>
							</TR>
						</TABLE>

						<TABLE width="158" align="center" border="0" cellspacing="2" cellpadding="0">
							<TR>
								<TD class="MenuOption">
									<A href="<?=$AdminScript?>?action=Templates">Templates</A><BR>
									<A href="<?=$AdminScript?>?action=UserCodes">User-Def. Codes</A><BR>
									<A href="<?=$AdminScript?>?action=Categories">Categories</A><BR>
									<A href="<?=$AdminScript?>?action=ImageList">Images</A><BR>
									<A href="<?=$AdminScript?>?action=Users">Users</A><BR>

									<?
									if ($EnableAudit == 1)
									{
										?>
										<A href="<?=$AdminScript?>?action=Audit">View Audit</A><BR>
										<?
									}
									?>
									<A href="<?=$AdminScript?>?action=Statistics">Statistics</A><BR>
									<A href="<?=$AdminScript?>?action=Mass">Mass Maintenance</A><BR>
								</TD>
							</TR>
						</TABLE>
						<BR />

						<TABLE width="158" align="center" border="1" cellspacing="0" cellpadding="0">
							<TR>
								<TD colspan="3" class="MenuHeading">
									&nbsp;&nbsp;Housekeeping
								</TD>
							</TR>
						</TABLE>
						<TABLE width="158" align="center" border="0" cellspacing="2" cellpadding="0">
							<TR>
								<TD class="MenuOption">
									<?php

									if ($EnableArchive == 1)
									{
										?>
										<A href="<?=$AdminScript?>?action=Archive">Archive News</A><BR>
										<?
									}

									if ($EnableNewsPurge == 1)
									{
										?>
										<A href="<?=$AdminScript?>?action=PurgeNews">Purge News</A><BR>
										<?
									}

									if ($EnableAudit == 1)
									{
										?>
										<A href="<?=$AdminScript?>?action=PurgeAudit">Purge Audit</A><BR>
										<?
									}
									?>
								</TD>
							</TR>
						</TABLE>
						<BR />
						<?php
					}
					?>

					<TABLE width="158" align="center" border="1" cellspacing="0" cellpadding="0">
						<TR>
							<TD colspan="3" class="MenuHeading">
								&nbsp;&nbsp;User Options
							</TD>
						</TR>
					</TABLE>

					<TABLE width="158" align="center" border="0" cellspacing="2" cellpadding="0">
						<TR>
							<TD class="MenuOption">
								<A href="<?=$AdminScript?>?action=Password">Change&nbsp;Password</A><BR />

								<?php
								if ($OnlineVersionCheck)
								{
									?>
									<A href="<?=$AdminScript?>?action=VersionCheck">Online&nbsp;Version&nbsp;Check</A><BR />
									<?php
								}
								?>
								<A href="<?=$AdminScript?>?action=Logout">Log Out</A>
							</TD>
						</TR>
					</TABLE>
					<BR>
				</TD>

				<TD valign="top" width="100%">
					<?php

					// Process the appropriate action
					if ($Action == 'Logout') {
						if ($Mode == 'Destroy')
							require ('Inc/Logout.php');
						else
							require ('Inc/CheckLogout.php');
					} elseif ($LoggedInMustChangePassword == '1') {
						$ErrorText = 'You must change your password before you can proceed';
						require ('Inc/Password.php');
					} elseif ($Action == 'Statistics') {
						require ('Inc/Statistics.php');
					} elseif ($Action == 'NewsList') {
						require ('Inc/NewsList.php');
					} elseif ($Action == 'CommentsApproval') {
						require ('Inc/CommentsApproval.php');
					} elseif ($Action == 'postsapproval') {
						require ('Inc/PostsApproval.php');
					} elseif ($Action == 'news') {
						require ('Inc/Post.php');
					} elseif ($Action == 'ImageList') {
						require ('Inc/Images.php');
					} elseif ($Action == 'Users') {
						require ('Inc/Users.php');
					} elseif ($Action == 'Password') {
						require ('Inc/Password.php');
					} elseif ($Action == 'Categories') {
						require ('Inc/Categories.php');
					} elseif ($Action == 'Templates') {
						require ('Inc/Templates.php');
					} elseif ($Action == 'UserCodes') {
						require ('Inc/UserDefinedCodes.php');
					} elseif ($Action == 'PurgeNews') {
						require ('Inc/PurgeOldNews.php');
					} elseif ($Action == 'PurgeAudit') {
						require ('Inc/PurgeOldAudit.php');
					} elseif ($Action == 'Archive') {
						require ('Inc/ArchiveOldNews.php');
					} elseif ($Action == 'Audit') {
						require ('Inc/ViewAudit.php');
					} elseif ($Action == 'DoSticky') {
						require ('Inc/SetSticky.php');
					} elseif ($Action == 'DoVisible') {
						require ('Inc/SetVisible.php');
					} elseif ($Action == 'DoLock') {
						require ('Inc/SetLock.php');
					} elseif ($Action == 'VersionCheck') {
						require ('Inc/VersionCheck.php');
					} elseif ($Action == 'Mass') {
						require ('Inc/MassMaintenance.php');
					} else {
						require ('Inc/NewsList.php');
					}
				?>
				</TD>
			</TR>
		</TABLE>
		<?php
		include('Inc/Footer.php');
		ob_end_flush();
		?>
	</BODY>
</HTML>