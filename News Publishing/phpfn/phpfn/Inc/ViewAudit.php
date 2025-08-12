<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

if (! defined('IN_PHPFN'))
	die('Illegal attempt to access script directly!');

if (! isset($_SESSION['RestrictEventCategory']))
	$_SESSION['RestrictEventCategory'] = '-';

if (! isset($_SESSION['RestrictEventUserID']))
	$_SESSION['RestrictEventUserID'] = '0';

// If specified, store into the session the restriction-information
if (isset($_POST['Cat']))
	$_SESSION['RestrictEventCategory'] = $_POST['Cat'];
if (isset($_POST['EventUserID']))
	$_SESSION['RestrictEventUserID'] = $_POST['EventUserID'];

$EventCategory = $_SESSION['RestrictEventCategory'];
$EventUserID = $_SESSION['RestrictEventUserID'];
$ShowPage = isset($_REQUEST['ShowPage']) ? $_REQUEST['ShowPage'] : 1;

// Determine the number of records in the file, and work out the number of pages
$sql = "SELECT count(*) AS NumRecs FROM news_audit WHERE ID != 0";
$where = "";

// Restrict by Category or user?
if ($EventCategory != '-')
	$where .= ' AND EventCatID=' . $EventCategory;
if ($EventUserID != '0')
	$where .= ' AND EventUserID=' . $EventUserID;

$resultset = mysql_fetch_array(mysql_query($sql . $where)); 

$NumRecords = $resultset['NumRecs'];
$RecStart = $AdminAuditEventsPerPage * ($ShowPage-1); 
$PageNavBar = ConstructPagingBar($_SERVER['PHP_SELF'].'?action=Audit', $NumRecords, $AdminAuditEventsPerPage, $ShowPage, $RecStart, $AdminAuditEventsPageBar, '', '');

DisplayGroupHeading("Audit Log - Page $ShowPage");
?>
<BR>
<TABLE class="Admin">
	<TR>
		<TD class="FieldPrompt">
			<FORM action="<?=$AdminScript?>?action=Audit" method="post">
				Event Category <?= BuildEventCategoryDropdown('Cat', $EventCategory, true) ?>
				User <?= BuildUserDropdown('EventUserID', $EventUserID, false, true) ?>
				<INPUT class="but" type="submit" name="submit" value="Filter" />
			</FORM>
		</TD>
	</TR>
</TABLE>
<BR>
<TABLE class="Admin">
	<TR>
		<TD>
			<TABLE class="Admin">
				<TR>
					<TH>
						Date and Time
					</TH>
					<TH>
						User
					</TH>
					<TH>
						Category
					</TH>
					<TH>
						Type
					</TH>
					<TH>
						ID
					</TH>
				</TR>
				<?php
				// Now obtain the resultset
				$sql = "SELECT EventDateTime, Username, CatDesc, EventType, LinkedID, news_audit.Description FROM news_audit, news_audit_categories LEFT OUTER JOIN news_users ON news_audit.EventUserID=news_users.ID WHERE news_audit.EventCatID=news_audit_categories.ID ";
				$sql .= $where;
				$sql .= " ORDER BY EventDateTime DESC LIMIT $RecStart, $AdminAuditEventsPerPage";

				$results =	mysql_query($sql);
				$odd = true;
				while ($auditrow = mysql_fetch_array($results))
				{
					$EventDateTime = $ShowDateString = date($NewsDisplay_DateFormat, strtotime($auditrow['EventDateTime'])) . '&nbsp;' . date($NewsDisplay_TimeFormat, strtotime($auditrow['EventDateTime']));
					$EventUserName = $auditrow['Username'];
					if (is_null($EventUserName))
						$EventUserName = "(Unknown)";
					$CatDesc = $auditrow['CatDesc'];
					$LinkedID = $auditrow['LinkedID'];
					$Description = $auditrow['Description'];

					switch ($auditrow['EventType'])
					{
						case 'A':
							$EventTypeDesc = "Add";
							break;
						case 'C':
							$EventTypeDesc = "Change";
							break;
						case 'D':
							$EventTypeDesc = "Delete";
							break;
						case 'X':
							$EventTypeDesc = "Other";
							break;
						default:
							$EventTypeDesc = "Unknown!";
							break;
					}
					$ListStyle = "AuditLog" . (($odd) ? "Odd" :"Even");
					?>
					<TR class="<?=$ListStyle?>">
						<TD class="<?=$ListStyle?>">
							<?= $ShowDateString ?>
						</TD>
						<TD class="<?=$ListStyle?>">
							<?=$EventUserName?>
						</TD>
						<TD class="<?=$ListStyle?>">
							<?=$CatDesc?>
						</TD>
						<TD class="<?=$ListStyle?>">
							<?=$EventTypeDesc?>
						</TD>
						<TD class="<?=$ListStyle?>">
							<?=$LinkedID?>
						</TD>
					</TR>
					<?php
					$ListStyle = "AuditListDesc" . (($odd) ? "Odd" :"Even");
					?>					
					<TR>
 						<TD colspan="5" class="<?=$ListStyle?>">
							&nbsp;&nbsp;&nbsp;<?=$Description?>
						</TD>
					</TR>
					<?php
					$odd = ! $odd;
				}
				?>
			</TABLE>
			<BR><BR>
			<DIV align="center">
				<?= $PageNavBar ?>
				<BR>
			</DIV>
		</TD>
	</TR>
</TABLE>