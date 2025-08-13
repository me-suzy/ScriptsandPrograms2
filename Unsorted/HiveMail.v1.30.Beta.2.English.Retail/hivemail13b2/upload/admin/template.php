<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: template.php,v $ - $Revision: 1.36 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
if ($cmd != 'compare' or $diff != 'main') {
	cp_header(' &raquo; Template Editor', ($cmd != 'compare' or !$diff), ($cmd != 'compare' or !$diff), '<script language="JavaScript" src="../misc/anchors.js"></script><script language="JavaScript" src="../misc/templates.js"></script>', ($cmd == 'compare' and $diff));
	cp_nav('skintemplate');
}

// ############################################################################
// Set the default cmd
default_var($cmd, 'modify');

$copyAndSearch = '<input type="text" name="query" size="30" class="bginput" style="height: 20px;" onChange="n = 0; this.form.dosearch.value = \'Find\';" onKeyDown="n = 0; this.form.dosearch.value = \'Find\';" /> <input type="button" name="dosearch" class="button" value="Find" onClick="if (findInPage(this.form.query.value, this.form.template_user_data)) { this.value = \'Find next\'; }" /> <input type="button" name="copyall" class="button" value="Copy to Clipboard" onClick="highlightAndCopy(this.form.template_user_data);" />';

// ############################################################################
// Remove template
if ($_POST['cmd'] == 'kill') {
	$template = getinfo('template', $templateid);
	$DB_site->query("
		DELETE FROM hive_template
		WHERE templateid = $templateid
	");

	if ($revert) {
		adminlog($templateid, true, 'kill', "Reverted template $template[title] in set: $template[templatesetid]");
		cp_redirect('The template has been reverted.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	} else {
		adminlog($templateid, true, 'kill', "Removed template $template[title] from set: $template[templatesetid]");
		cp_redirect('The template has been removed.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
	$cmd = 'modify';
}

// ############################################################################
// Remove template
if ($cmd == 'remove') {
	adminlog($templateid);
	$template = getinfo('template', $templateid);
	startform('template.php', 'kill', 'Are you sure you want to '.iif($revert, 'revert', 'remove').' this template?');
	hiddenfield('revert', $revert);
	hiddenfield('templateid', $templateid);
	if ($revert) {
		starttable('Revert template "'.$template['title'].'" (ID: '.$templateid.')');
		textrow('Are you <b>sure</b> you want to revert this template back to original? This procedure <b>cannot</b> be reveresed!');
		endform('Revert template', '', 'Go Back');
	} else {
		starttable('Remove template "'.$template['title'].'" (ID: '.$templateid.')');
		textrow('Are you <b>sure</b> you want to remove this template? This procedure <b>cannot</b> be reveresed!');
		endform('Remove template', '', 'Go Back');
	}
	endtable();
}

// ############################################################################
// Update a template
if ($_POST['cmd'] == 'update') {
	if (empty($template['title'])) {
		adminlog($templateid, false);
		cp_error('The template must have a title.');
	} elseif ($DB_site->query_first("SELECT templateid FROM hive_template WHERE title='".addslashes($template['title'])."' AND templatesetid = $template[templatesetid] AND templateid <> $templateid")) {
		adminlog($templateid, false);
		cp_error('A template with the title you specified already exists in this set.');
	} else {
		$template['parsed_data'] = parse_template($template['user_data']);
		$DB_site->auto_query('template', $template, "templateid = $templateid");
		adminlog($templateid, true, 'update', "Updated template $template[title] in set: $template[templatesetid]");
		cp_redirect('The template has been updated.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
}

// ############################################################################
// Update a template
if ($cmd == 'edit') {
	// Get some useful information
	$template = getinfo('template', $templateid);
	adminlog($templateid);
	$template['user_data'] = str_replace('.php', '.p'.md5(TIMENOW).'hp', $template['user_data']);
	$templatesetid = $template['templatesetid'];
	if ($template['templatesetid'] == -1) {
		$templateset = array('title' => 'Global Set', 'templatesetid' => -1);
	} else {
		$templateset = getinfo('templateset', $template['templatesetid']);
	}
	$templategroup = getinfo('templategroup', $template['templategroupid']);

	// The form
	startform('template.php', 'update', '', array('template_title' => 'title'));
	starttable('Update template "'.$template['title'].'" (ID: '.$templateid.')');

	// We can't choose the template set
	tablerow(array('Template set:', $templateset['title']), true);
	hiddenfield('template[templatesetid]', $templateset['templatesetid']);

	// Can we choose the template group?
	if ($DB_site->query_first("SELECT templateid FROM hive_template WHERE templatesetid = -1 AND title = '".addslashes($template['title'])."'") and $template['templatesetid'] != -1) {
		tablerow(array('Template group:', $templategroup['title']), true);
		hiddenfield('template[templategroupid]', $templategroup['templategroupid']);
	} else {
		tableselect('Template group:', 'template[templategroupid]', 'templategroup', $templategroup['templategroupid']);
	}

	// This is the template we edit
	hiddenfield('templateid', $templateid);

	// The title and data
	inputfield('Template title:', 'template[title]', $template['title']);
	textrow('<textarea name="template[user_data]" style="width: 100%;" rows="25" id="template_user_data">'.htmlchars($template['user_data']).'</textarea><br />'.$copyAndSearch);
	textarea('Backup information:<br /><br /><font size="1">Use this box to save any kind of information<br />about this template.<br />This is extremely useful if you make drastic<br />changes to a template and wish to keep the<br />old one.<br />Anything that is stored here will never be<br />displayed on your site.</font>', 'template[backup_data]', $template['backup_data'], 10, 50);

	// Submit button
	endform('Update template');
	endtable();
}

// ############################################################################
// Create a new template
if ($_POST['cmd'] == 'insert') {
	if (empty($template['title'])) {
		adminlog(0, false);
		cp_error('The template must have a title.');
	} elseif ($DB_site->query_first("SELECT templateid FROM hive_template WHERE title='".addslashes($template['title'])."' AND templatesetid = $template[templatesetid]")) {
		adminlog(0, false);
		cp_error('A template with the title you specified already exists in this set.');
	} else {
		$template['parsed_data'] = parse_template($template['user_data']);
		$DB_site->auto_query('template', $template);
		adminlog($DB_site->insert_id(), true, 'insert', "Added template $template[title] to set: $template[templatesetid]");
		cp_redirect('The template has been created.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
}

// ############################################################################
// Create a new template
if ($cmd == 'add') {
	adminlog();

	// Get and define some important stuff
	$templateset = getinfo('templateset', $templatesetid, false, false);

	// The form
	startform('template.php', 'insert', '', array('template_title' => 'title'));
	starttable('Create new template');

	// We can choose template set and group
	selectbox('Template set:', 'template[templatesetid]', iif($debug, array('-1' => 'Global Set'), array()) + table_to_array('templateset', 'templatesetid', '1 = 1', 'title'), $templateset['templatesetid']);
	tableselect('Template group:', 'template[templategroupid]', 'templategroup', $templategroupid);

	// The title and dataa
	inputfield('Template title:', 'template[title]', $template['title']);
	textrow('<textarea name="template[user_data]" style="width: 100%;" rows="25" id="template_user_data">'.htmlchars($template['user_data']).'</textarea>');

	// Submit button
	endform('Create template');
	endtable();
}

// ############################################################################
// Change an original template
if ($_POST['cmd'] == 'updateorig') {
	if (empty($template['title'])) {
		adminlog(0, false);
		cp_error('The template must have a title.');
	} elseif ($DB_site->query_first("SELECT templateid FROM hive_template WHERE title='".addslashes($template['title'])."' AND templatesetid = $template[templatesetid]")) {
		adminlog($templateid, false);
		cp_error('A template with the title you specified already exists in this set.');
	} else {
		$template['parsed_data'] = parse_template($template['user_data']);
		$DB_site->auto_query('template', $template);
		adminlog($DB_site->insert_id(), true, 'updateorig', "Customized template $template[title] within set: $template[templatesetid]");
		cp_redirect('The template has been changed.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
}

// ############################################################################
// Change an original template
if ($cmd == 'changeorig') {
	// Get some useful information
	$template = getinfo('template', $templateid);
	$templateset = getinfo('templateset', $templatesetid);
	$templategroup = getinfo('templategroup', $template['templategroupid']);
	adminlog($templateid);
	$template['user_data'] = str_replace('.php', '.p'.md5(TIMENOW).'hp', $template['user_data']);

	// The form
	startform('template.php', 'updateorig', '', array('template_title' => 'title'));
	starttable('Change original template "'.$template['title'].'"');

	// We can't choose template set or group
	tablerow(array('Template set:', $templateset['title']), true);
	tablerow(array('Template group:', $templategroup['title']), true);
	hiddenfield('template[templatesetid]', $templateset['templatesetid']);
	hiddenfield('template[templategroupid]', $templategroup['templategroupid']);

	// This is the template we edit
	hiddenfield('templateid', $templateid);

	// The title and data
	inputfield('Template title:', 'template[title]', $template['title']);
	textrow('<textarea name="template[user_data]" style="width: 100%;" rows="25" id="template_user_data">'.htmlchars($template['user_data']).'</textarea><br />'.$copyAndSearch);
	textarea('Backup information:<br /><br /><font size="1">Use this box to save any kind of information<br />about this template.<br />This is extremely useful if you make drastic<br />changes to a template and wish to keep the<br />old one.<br />Anything that is stored here will never be<br />displayed on your site.</font>', 'template[backup_data]', $template['backup_data'], 10, 50);

	// Submit button
	endform('Change template');
	endtable();
}

// ############################################################################
// Update a template
if ($_POST['cmd'] == 'updateunknown') {
	// Get template information
	$template = getinfo('template', $templateid);

	// Parse template data
	$template['user_data'] = $user_data;
	$template['parsed_data'] = parse_template($template['user_data']);

	// Update an origial template
	if ($template['templatesetid'] == -1) {
		$template['templatesetid'] = $templatesetid;
		$template['templateid'] = NULL;
		$DB_site->auto_query('template', $template);
		$templateid = $DB_site->insert_id();
	} else {
		$DB_site->auto_query('template', $template, "templateid = $templateid");
	}

	adminlog($templateid, true);
	cp_redirect('The template has been updated.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
}

// ############################################################################
// Compare to original template
if ($cmd == 'compare') {
	// Get some useful information
	if ($diff != 'main' and $diff != 'left' and $diff != 'top' and $diff != 'bottom') {
		$orig = getinfo('template', $origid);
		$cust = getinfo('template', $custid);
		$set = getinfo('templateset', $cust['templatesetid']);
		adminlog($custid);
	}

	switch ($diff) {
		case 'left':
			?>
			<script language="JavaScript">
			<!--
			
			function gotoDiff(offset) {
				var totalDiffs = top.bodyframe.totalDiffs;
				var scrollTop = top.bodyframe.getScrollTop();
				var marginHeight = 20;
				var foundDiff = false;
				switch (offset) {
					case 1:
						for (var i = 1; i <= totalDiffs; i++) {
							if (zeroUp(top.bodyframe.diffLocations[i] - marginHeight) > scrollTop) {
								foundDiff = true;
								break;
							}
						}
						break;

					case -1:
						for (var i = totalDiffs; i >= 1; i--) {
							if (zeroUp(top.bodyframe.diffLocations[i] - marginHeight) < scrollTop) {
								foundDiff = true;
								break;
							}
						}
						break;

					case 10:
						var i = totalDiffs;
						var foundDiff = true;
						break;

					case -10:
						var i = 1;
						var foundDiff = true;
						break;
				}

				if (i > totalDiffs || i < 1 || !foundDiff) {
					alert('No more differences found.');
				} else {
					//alert('Current: '+scrollTop+', going to: '+i+': '+(top.bodyframe.diffLocations[i] - marginHeight));
					top.bodyframe.window.scrollTo(0, zeroUp(top.bodyframe.diffLocations[i] - marginHeight));
					if (scrollTop == top.bodyframe.getScrollTop()) {
						// Nothing has changed... meants there are no more diffs,
						// or that they are out of the scrolling region
						alert('No more differences found.');
					}
				}
			}

			//-->
			</script>
			<table cellpadding="0" cellspacing="0" style="border-width: 0px;">
				<tr>
					<td style="padding-top: 27px;"><button name="first" type="button" style="padding: 2px; background: #eeeeee;" onClick="gotoDiff(-10); return false;"><img src="../misc/cp_diff_first.gif" alt="Go to first difference" /></button></td>
				</tr>
				<tr>
					<td style="padding-top: 3px;"><button name="prev" type="button" style="padding: 2px; background: #eeeeee;" onClick="gotoDiff(-1); return false;"><img src="../misc/cp_diff_up.gif" alt="Go to previous difference" /></button></td>
				</tr>
				<tr>
					<td style="padding-top: 3px;"><button name="next" type="button" style="padding: 2px; background: #eeeeee;" onClick="gotoDiff(1); return false;"><img src="../misc/cp_diff_down.gif" alt="Go to next difference" /></button></td>
				</tr>
				<tr>
					<td style="padding-top: 3px;"><button name="last" type="button" style="padding: 2px; background: #eeeeee;" onClick="gotoDiff(10); return false;"><img src="../misc/cp_diff_last.gif" alt="Go to last difference" /></button></td>
				</tr>
			</table>
			<?php
			break;

		case 'top':
			starttable('', '100%');
			tablehead(array('Original Template', 'Customized Template'), 1, true);
			endtable();
			break;

		case 'bottom':
			startform('template.php');
			starttable('<input type="button" class="bginput" value="Close Comparison Window" onClick="top.window.close();" />', '100%');
			endtable();
			endform();
			break;

		case 'body':
			formatDiff($orig['user_data'], $cust['user_data']);
			break;

		case 'main':
			?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "XHTML1-f.dtd">
			<html>
			<head>
			<title>HiveMail&trade; Control Panel &raquo; Template Editor</title>
			<frameset cols="30,*" framespacing="0" frameborder="no" border="0">
			<frame name="leftframe" src="template.php?cmd=compare&diff=left" scrolling="no" noresize="noresize" frameborder="no" marginwidth="0" marginheight="0" border="no" />
			<frameset rows="30,*,40"  framespacing="0" border="0" frameborder="no" frameborder="no" border="0">
			<frame name="topframe" src="template.php?cmd=compare&diff=top" scrolling="no" noresize="noresize" frameborder="no" marginwidth="10" marginheight="10" border="no" />
			<frame name="bodyframe" src="template.php?cmd=compare&diff=body&origid=<?php echo $origid; ?>&custid=<?php echo $custid; ?>" scrolling="yes" noresize="noresize" frameborder="no" marginwidth="10" marginheight="10" border="no" />
			<frame name="bottomframe" src="template.php?cmd=compare&diff=bottom" scrolling="no" noresize="noresize" frameborder="no" marginwidth="10" marginheight="10" border="no" />
			</frameset>
			</frameset>
			</head>
			</html>
			<?php
			break;

		default:
			?><script language="JavaScript">
			<!--
			function diffwin(logid) {
				window.open('template.php?cmd=compare&diff=main&origid=<?php echo $origid; ?>&custid=<?php echo $custid; ?>', 'diffwin<?php echo $custid; ?>', 'width='+((screen.width - 100 > 1050) ? (1050) : (screen.width - 100))+',height='+((screen.height - 100 > 800) ? (800) : (screen.height - 100))+',resizable=yes,scrollbars=yes');
			}
			// -->
			</script><?php

			// The form
			startform('template.php', 'update');
			hiddenfield('template[templatesetid]', $cust['templatesetid']);
			hiddenfield('template[templategroupid]', $cust['templategroupid']);
			hiddenfield('templateid', $custid);
			hiddenfield('template[title]', $cust['title']);
			starttable('Comparing template "'.$cust['title'].'" in set "'.$set['title'].'"');
			tablerow(array('Custom Template', 'Original Template'), true);
			tablerow(array("<textarea name=\"template[user_data]\" id=\"cust\" style=\"width: 100%;\" rows=\"25\" onScroll=\"if (!getElement('scrollthem').checked) return; getElement('orig').scrollTop = this.scrollTop; getElement('orig').scrollLeft = this.scrollLeft;\">".htmlchars($cust['user_data'])."</textarea>".'<br /><input type="button" name="copyallcust" class="bginput" value="Copy" onClick="highlightAndCopy(getElement(\'cust\'));" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="querycust" size="21" class="bginput" style="height: 16px;" onChange="n = 0; this.form.dosearchcust.value = \'Find\';" onKeyDown="n = 0; this.form.dosearchcust.value = \'Find\';" /> <input type="button" name="dosearchcust" class="bginput" value="Find" onClick="if (findInPage(this.form.querycust.value, getElement(\'cust\'))) { this.value = \'Find next\'; }" />', "<textarea name=\"orig\" style=\"width: 100%;\" rows=\"25\" onScroll=\"if (!getElement('scrollthem').checked) return; getElement('cust').scrollTop = this.scrollTop; getElement('cust').scrollLeft = this.scrollLeft;\">".htmlchars($orig['user_data'])."</textarea>".'<br /><input type="button" name="copyallorig" class="bginput" value="Copy" onClick="highlightAndCopy(getElement(\'orig\'));" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="queryorig" size="21" class="bginput" style="height: 16px;" onChange="n = 0; this.form.dosearchorig.value = \'Find\';" onKeyDown="n = 0; this.form.dosearchorig.value = \'Find\';" /> <input type="button" name="dosearchorig" class="bginput" value="Find" onClick="if (findInPage(this.form.queryorig.value, getElement(\'orig\'))) { this.value = \'Find next\'; }" />'), true);
			textrow('For a colored display of all differences between the templates, please <a href="#" onClick="diffwin(); return false;">click here</a>.<br />');
			textrow('<input type="checkbox" name="scrollthem" checked="checked" /> Enable concurrent scrolling.');
			textrow('<b>Note:</b> When you submit this form only the custom template (on the left) will be updated.');
			endform('Update Custom Template');
			endtable();
			break;
	}
}

// ############################################################################
// List the templates
if ($cmd == 'modify' and 0) {
	adminlog();

	// Set defaults value
	default_var($templatesetid, 1);
	default_var($templategroupid, -1);

	// Get set and group information
	if ($templatesetid != -1) {
		$set = getinfo('templateset', $templatesetid);
	} else {
		$set = array('title' => 'Global Set');
	}
	if ($templategroupid != -1) {
		$group = getinfo('templategroup', $templategroupid);
	} else {
		$group = array('title' => 'All');
	}

	// Change group, set or mode
	startform('template.php', 'modify');
	starttable('Viewing options');
	tableselect('Viewing the "'.$set['title'].'" set:', 'templatesetid', 'templateset', $templatesetid, '1 = 1');
	tableselect('Viewing '.strtolower($group['title']).' templates:', 'templategroupid', 'templategroup', $templategroupid, '1 = 1 ORDER BY display', 'all groups');
	inputfield('Viewing templates that contain '.iif($query, '"'.htmlchars($query).'"', 'anything').':', 'query', $query);
	selectbox('Viewing in:', 'cmd', array('modify' => 'paginated format&nbsp;&nbsp;', 'linear' => 'linear format '), 'modify');
	endform('Reload Page');
	endtable();

	echo "<script language=\"JavaScript\">
<!--
function gotoTemplate(evtObj, ID, action) {
	if (evtObj.shiftKey) {
		window.open('template.php?cmd='+action+'&templatesetid=$templatesetid&templategroupid=$templategroupid&templateid='+ID);
		return false;
	} else {
		window.location = 'template.php?cmd='+action+'&templatesetid=$templatesetid&templategroupid=$templategroupid&templateid='+ID;
	}
}
// -->
</script>
";

	// Custom templates
	echo '<br /><br />';
	starttable("$group[title] - Custom templates &nbsp;".makelink('add template', "template.php?cmd=add&templatesetid=$templatesetid&templategroupid=$templategroupid"));

	$customs = $DB_site->query("
		SELECT custom.templateid, custom.title, custom.templatesetid, custom.description".iif($adminloadtemplates, ', custom.user_data')."
		FROM hive_template AS custom
		LEFT JOIN hive_template AS orig ON (orig.title = custom.title AND orig.templatesetid = -1)
		WHERE custom.templatesetid = $templatesetid".iif($templategroupid != -1, " AND custom.templategroupid = $templategroupid")." AND orig.templateid IS NULL".iif(!empty($query), " AND custom.user_data LIKE '%".addslashes($query)."%'")."
		ORDER BY custom.title
	");
	if ($DB_site->num_rows($customs) == 0) {
		textrow('No custom templates.');
	} else {
		while ($template = $DB_site->fetch_array($customs)) {
			$thisclass = getclass();
			$thistextarea = md5(microtime());
			echo "	<tr>\n";
			echo "		<td class=\"$thisclass\" nowrap=\"nowrap\">".iif($adminloadtemplates, "<img src=\"../misc/plus.gif\" onClick=\"oc('$thistextarea', this);\" /> ")."<b><a href=\"javascript:gotoTemplate(event, $template[templateid], 'edit');\">$template[title]</a></b><br />$template[description]</td>\n";
			echo "		<td class=\"$thisclass\" nowrap=\"nowrap\" align=\"right\">".makelink('edit', "javascript:gotoTemplate(event, $template[templateid], 'edit');").' '.makelink('remove', "javascript:gotoTemplate(event, $template[templateid], 'remove');")."</td>\n";
			echo "	</tr>\n";
			// The hidden textarea
			if ($adminloadtemplates) {
				echo "	<tr id=\"$thistextarea\" style=\"display: none;\">\n";
				echo "		<td align=\"center\" class=\"$thisclass\" nowrap=\"nowrap\" colspan=\"2\"><form action=\"template.php\" method=\"post\"><input type=\"hidden\" name=\"cmd\" value=\"updateunknown\" /><input type=\"hidden\" name=\"templateid\" value=\"$template[templateid]\" /><input type=\"hidden\" name=\"templatesetid\" value=\"$template[templatesetid]\" /><textarea style=\"width: 97%;\" rows=\"15\" name=\"user_data\">".htmlchars($template['user_data'])."</textarea><br /><br /><input type=\"submit\" value=\"Update Template\" class=\"button\" /></form></td>\n";
				echo "	</tr>\n";
			}
		}
	}
	endtable();

	// Default templates
	echo '<br /><br />';
	starttable("$group[title] - Default templates");

	$defaults = $DB_site->query("
		SELECT	orig.templateid AS origtemplateid,
				".iif($adminloadtemplates, 'orig.user_data AS origuser_data,')."
				orig.title,
				orig.templatesetid,
				orig.description,
				".iif($adminloadtemplates, 'custom.user_data AS customuser_data,')."
				custom.templateid AS customtemplateid
		FROM hive_template AS orig
		LEFT JOIN hive_template AS custom ON (orig.title = custom.title AND custom.templatesetid = $templatesetid)
		WHERE orig.templatesetid = -1".iif($templategroupid != -1, " AND orig.templategroupid = $templategroupid").iif(!empty($query), " AND IF(custom.templateid IS NULL, orig.user_data LIKE '%".addslashes($query)."%', custom.user_data LIKE '%".addslashes($query)."%')")."
		ORDER BY orig.title
	");
	while ($template = $DB_site->fetch_array($defaults)) {
		// Define some variables for later
		$thisclass = getclass();
		$thistextarea = md5(microtime());

		// The template is customized within this set
		if (!empty($template['customtemplateid'])) {
			echo "	<tr>\n";
			echo "		<td class=\"$thisclass\" nowrap=\"nowrap\">".iif($adminloadtemplates, "<img src=\"../misc/plus.gif\" onClick=\"oc('$thistextarea', this);\" /> ")."<b><a href=\"javascript:gotoTemplate(event, $template[customtemplateid], 'edit');\">$template[title]</a></b><br />$template[description]</td>\n";
			echo "		<td class=\"$thisclass\" nowrap=\"nowrap\" align=\"right\">".makelink('edit', "javascript:gotoTemplate(event, $template[customtemplateid], 'edit');").' '.makelink('revert to original', "javascript:gotoTemplate(event, $template[customtemplateid], 'edit&revert=1');")."</td>\n";
			echo "	</tr>\n";
			// The hidden textarea
			if ($adminloadtemplates) {
				echo "	<tr id=\"$thistextarea\" style=\"display: none;\">\n";
				echo "		<td align=\"center\" class=\"$thisclass\" nowrap=\"nowrap\" colspan=\"2\"><form action=\"template.php\" method=\"post\"><input type=\"hidden\" name=\"cmd\" value=\"updateunknown\" /><input type=\"hidden\" name=\"templateid\" value=\"$template[customtemplateid]\" /><input type=\"hidden\" name=\"templatesetid\" value=\"$templatesetid\" /><textarea style=\"width: 97%;\" rows=\"15\" name=\"user_data\">".htmlchars($template['customuser_data'])."</textarea><br /><br /><input type=\"submit\" value=\"Update Template\" class=\"button\" /></form></td>\n";
				echo "	</tr>\n";
			}
		}

		// The template is original
		else {
			echo "	<tr>\n";
			echo "		<td class=\"$thisclass\" nowrap=\"nowrap\">".iif($adminloadtemplates, "<img src=\"../misc/plus.gif\" onClick=\"oc('$thistextarea', this);\" /> ")."<b><a href=\"javascript:gotoTemplate(event, $template[origtemplateid], 'changeorig');\">$template[title]</a></b><br />$template[description]</td>\n";
			echo "		<td class=\"$thisclass\" nowrap=\"nowrap\" align=\"right\">".makelink('change original', "javascript:gotoTemplate(event, $template[rigtemplateid], 'changeorig');")."</td>\n";
			echo "	</tr>\n";
			// The hidden textarea
			if ($adminloadtemplates) {
				echo "	<tr id=\"$thistextarea\" style=\"display: none;\">\n";
				echo "		<td align=\"center\" class=\"$thisclass\" nowrap=\"nowrap\" colspan=\"2\"><form action=\"template.php\" method=\"post\"><input type=\"hidden\" name=\"cmd\" value=\"updateunknown\" /><input type=\"hidden\" name=\"templateid\" value=\"$template[origtemplateid]\" /><input type=\"hidden\" name=\"templatesetid\" value=\"$templatesetid\" /><textarea style=\"width: 97%;\" rows=\"15\" name=\"user_data\">".htmlchars($template['origuser_data'])."</textarea><br /><br /><input type=\"submit\" value=\"Update Template\" class=\"button\" /></form></td>\n";
				echo "	</tr>\n";
			}
		}
	}
	endtable();
}

// ############################################################################
// List the templates
if ($cmd == 'linear' or $cmd == 'modify') {
	adminlog();

	// When switching modes
	default_var($templategroupids, $templategroupid);

	// Cache templates and group them by set and group
	$gettemplates = $DB_site->query('
		SELECT templateid, title, templatesetid, templategroupid
		FROM hive_template
		WHERE 1 = 1 '.iif(!empty($query), ' AND user_data LIKE "%'.addslashes(str_replace('*', '%', $query)).'%"').'
		ORDER BY title
	');
	$templates = array();
	while ($template = $DB_site->fetch_array($gettemplates)) {
		$templates["$template[templatesetid]"]["$template[templategroupid]"]["$template[title]"] = $template;
	}

	// Global set
	if ($debug) {
		$sets = array('-1' => array('templatesetid' => '-1', 'title' => 'Global Set'));
	} else {
		$sets = array();
	}

	// Cache template sets and groups
	$sets += table_to_array('templateset', 'templatesetid');
	$groups = table_to_array('templategroup', 'templategroupid', '1 = 1 ORDER BY display');

	// Get full list of groups
	$allgroupids = '';
	foreach($groups as $tgroup) {
		$allgroupids .= "$tgroup[templategroupid],";
	}

	// For searching
	if (!empty($query) and !isset($_GET['templategroupids'])) {
		$templategroupids = implode(',', array_keys($groups));
	}
	if (!empty($query)) {
		$custtemps = array();
		while ($custtemp = $DB_site->fetch_array($getcusttemps, 'SELECT title, templatesetid FROM hive_template WHERE templatesetid <> -1')) {
			$custtemps["$custtemp[title]"]["$custtemp[templatesetid]"] = true;
		}
	}

	$query = htmlchars($query);

	echo "<div align=\"left\">\n<ul>\n<div align=\"center\" style=\"font-weight: bold; font-size: 14px;\"><a href=\"template.php?cmd=add\">Create new template</a></div>&nbsp;";
	if (!empty($query)) {
		echo "\t<li style=\"padding-bottom: 10px; list-style-type: none;\"><span class=\"cp_temp_cust\"><b>Searching templates for:&nbsp;&nbsp;<tt>".str_replace('*', '<span class="cp_temp_orig">*</span>', $query)."</tt> ...</b></span></li>\n";
	}

	if (getop('cp_templatetree')) {
		?><li style="padding-bottom: 10px; list-style-type: none;">
		<script src="../misc/tree/ua.js"></script>
		<script src="../misc/tree/ftiens4.js"></script>
		<script language="JavaScript">
		<!--

		USETEXTLINKS = 1;
		STARTALLOPEN = 0;
		USEFRAMES = 0;
		USEICONS = 1;
		WRAPTEXT = 1;
		PERSERVESTATE = 1;

		foldersTree = gFld("<b>HiveMail&trade; Templates</b>", "javascript:undefined");

		<?php
		// </script><?php

		foreach ($sets as $setid => $set) {
		  ?>aux1 = insFld(foldersTree, gFld("<?php echo "<b>$set[title]</b></a> <span class=\\\"cp_small\\\">".iif($setid != -1, "[<a href=\\\"templateset.php?cmd=edit&templatesetid=$setid\\\">edit set</a>]").iif($setid != -1 and $setid != 1, " [<a href=\\\"templateset.php?cmd=remove&templatesetid=$setid\\\">remove set</a>]")." [<a href=\\\"template.php?cmd=add&templatesetid=$setid&templategroupids=$templategroupids\\\">add template</a>]</span>"; ?>", "javascript:undefined"));
	<?php

			$customoutput = '';
			foreach ($groups as $groupid => $group) {
				if (!is_array($templates["$setid"]["$groupid"])) {
					continue;
				}
				
				// Doing it this way because for some reason array_diff() doesn't want to work
				$workwith = array();
				foreach ($templates["$setid"]["$groupid"] as $title => $template) {
					if (!@array_key_exists($title, $templates['-1']["$groupid"])) {
						$workwith["$title"] = $template;
					}
				}
				if (count($workwith) == 0) {
					continue;
				}
				?>aux2 = insFld(aux1, gFld("<?=$group['title']?>", "javascript:undefined"));
	<?php
				foreach ($workwith as $title => $template) {
					?>insDoc(aux2, gLnk("S", "</a><?php echo $title."<span class=\"cp_small\">".makelink('edit', "template.php?cmd=edit&query=$query&templateid=".$template['templateid']).' '.makelink('remove', "template.php?cmd=remove&templateid=".$template['templateid'])."</span>"; ?>", "", "cust"));
	<?php
				}
			}

			foreach ($groups as $groupid => $group) {
				if (!empty($query) and !is_array($templates['-1']["$groupid"])) {
					continue;
				}

				?>aux2 = insFld(aux1, gFld("<?=$group['title']?>", "javascript:undefined"));
	<?php
				foreach ($templates['-1']["$groupid"] as $title => $template) {
					if (!empty($query) and $custtemps["$title"]["$setid"] == true and !is_array($templates["$setid"]["$groupid"]["$title"])) {
						continue;
					}
					if (is_array($templates["$setid"]["$groupid"]["$title"])) {
						?>insDoc(aux2, gLnk("S", "</a><?php echo "<a href=\\\"template.php?cmd=edit&query=$query&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']."\\\" style=\\\"text-decoration: none;\\\"><span class=\\\"cp_temp_edit\\\" style=\\\"text-decoration: none;\\\">$title</span> <span class=\\\"cp_small\\\">[<a href=\\\"template.php?cmd=edit&query=$query&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']."\\\">edit</a>] ".iif($setid != -1, "[<a href=\\\"template.php?cmd=remove&revert=1&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']."\\\">revert to original</a>] [<a href=\\\"template.php?cmd=compare&origid=".$templates['-1']["$groupid"]["$title"]['templateid']."&custid=".$templates["$setid"]["$groupid"]["$title"]['templateid']."\\\">compare to original</a>]", "[<a href=\\\"template.php?cmd=remove&templateid=".$template['templateid']."\\\">remove</a>]")."</span></span>"; ?>", "", "cust"));
	<?php
					} else {
						?>insDoc(aux2, gLnk("S", "</a><?php echo "<a href=\\\"template.php?cmd=changeorig&query=$query&templatesetid=$setid&templateid=".$templates['-1']["$groupid"]["$title"]['templateid']."\\\" style=\\\"text-decoration: none;\\\"><span class=\\\"cp_temp_orig\\\" style=\\\"text-decoration: none;\\\">$title</span> <span class=\\\"cp_small\\\">[<a href=\\\"template.php?cmd=changeorig&query=$query&templatesetid=$setid&templateid=".$templates['-1']["$groupid"]["$title"]['templateid']."\\\">change original</a>]</span></span>"; ?>", "", "orig"));
	<?php
					}
				}
			}
		}

		?>
		initializeDocument();
		//-->
		</script></li><?php
	} else {
		foreach ($sets as $setid => $set) {
			echo "\t<li style=\"padding-bottom: 10px; list-style-type: disk;\"><b>$set[title]</b> <span class=\"cp_small\">".makelink('edit set', "templateset.php?cmd=edit&templatesetid=$setid").iif($setid != -1 and $setid != 1, ' '.makelink('remove set', "templateset.php?cmd=remove&templatesetid=$setid")).' '.makelink('add template', "template.php?cmd=add&templatesetid=$setid&templategroupids=$templategroupids").' '.makelink('expand all groups', "template.php?templatesetid=$setid&query=$query&templategroupids=$allgroupids").' '.makelink('collapse all groups', "template.php?templatesetid=$setid&query=$query").'</span>';
			if ($templatesetid == $setid or (!empty($query) and !isset($_GET['templatesetid']))) {
				echo "\n\t\t<ul>\n";

				####################################################################

				$customoutput = '';
				foreach ($groups as $groupid => $group) {
					if (!is_array($templates["$setid"]["$groupid"])) {
						continue;
					}
					
					// Doing it this way because for some reason array_diff() doesn't want to work
					$workwith = array();
					foreach ($templates["$setid"]["$groupid"] as $title => $template) {
						if (!@array_key_exists($title, $templates['-1']["$groupid"])) {
							$workwith["$title"] = $template;
						}
					}
					if (count($workwith) == 0) {
						continue;
					}
					if (strstr(",$templategroupids,", ",$groupid,")) {
						$customoutput .= "\t\t\t<li class=\"tgroup\" style=\"list-style-type: circle;\"><b><a href=\"template.php?templatesetid=$setid&query=$query&templategroupids=".substr(str_replace(",$groupid,", ',', ",$templategroupids,"), 1, -1)."\" class=\"cp_group_link\"><span style=\"color: black;\">$group[title]</span></a></b> <span class=\"cp_small\">";
						$customoutput .= makelink('collapse', "template.php?templatesetid=$setid&query=$query&templategroupids=".substr(str_replace(",$groupid,", ',', ",$templategroupids,"), 1, -1))."</span>\n\t\t\t\t<ul>\n";
						foreach ($workwith as $title => $template) {
							$customoutput .= "\t\t\t\t\t<li style=\"list-style-type: square;\"><a href=\"template.php?cmd=edit&query=$query&templateid=".$template['templateid']."\" style=\"text-decoration: none;\"><span class=\"cp_temp_cust\" style=\"text-decoration: none;\">$title</span> <span class=\"cp_small\">".makelink('edit', "template.php?cmd=edit&query=$query&templateid=".$template['templateid']).' '.makelink('remove', "template.php?cmd=remove&templateid=".$template['templateid'])."</span></span></li>\n";
						}
						$customoutput .= "\t\t\t\t</ul>\n\t\t\t";
					} else {
						$customoutput .= "\t\t\t<li class=\"tgroup\" style=\"list-style-type: circle;\"><b><a href=\"template.php?templatesetid=$setid&query=$query&templategroupids=$templategroupids,$groupid\" class=\"cp_group_link\"><span style=\"color: black;\">$group[title]</span></a></b> <span class=\"cp_small\">";
						$customoutput .= makelink('expand', "template.php?templatesetid=$setid&query=$query&templategroupids=$templategroupids,$groupid").' '.makelink('expand alone', "template.php?templatesetid=$setid&query=$query&templategroupids=$groupid").'</span>';
					}
					$customoutput .= "</li>\n";
				}
				
				if (!empty($customoutput)) {
					echo "\t\t\t<li style=\"padding: 5px; list-style-type: none;\"><i>Custom templates:</i></li>\n$customoutput";
				}

				####################################################################

				echo "\t\t\t<li style=\"padding: 5px; list-style-type: none;\"><i>Default templates:</i></li>\n";
				foreach ($groups as $groupid => $group) {
					if (!empty($query) and !is_array($templates['-1']["$groupid"])) {
						continue;
					}

					if (!is_array($templates['-1']["$groupid"])) {
						echo "\t\t\t<li class=\"tgroup\" style=\"list-style-type: circle;\"><b>$group[title]</b> <span class=\"cp_small\">[no templates]</span>";
					} elseif (strstr(",$templategroupids,", ",$groupid,") or (!empty($_POST['query']) and is_array($templates['-1']["$groupid"]))) {
						echo "\t\t\t<li class=\"tgroup\" style=\"list-style-type: circle;\"><a name=\"g$groupid\" /><b><a href=\"template.php?templatesetid=$setid&query=$query&templategroupids=".substr(str_replace(",$groupid,", ',', ",$templategroupids,"), 1, -1)."\" class=\"cp_group_link\"><span style=\"color: black;\">$group[title]</span></a></b> <span class=\"cp_small\">";
						echo makelink('collapse', "template.php?templatesetid=$setid&query=$query&templategroupids=".substr(str_replace(",$groupid,", ',', ",$templategroupids,"), 1, -1))."</span>\n\t\t\t\t<ul>\n";
						if (is_array($templates['-1']["$groupid"])) {
							foreach ($templates['-1']["$groupid"] as $title => $template) {
								if (!empty($query) and $custtemps["$title"]["$setid"] == true and !is_array($templates["$setid"]["$groupid"]["$title"])) {
									continue;
								}
								if (is_array($templates["$setid"]["$groupid"]["$title"])) {
									echo "\t\t\t\t\t<li style=\"list-style-type: square;\"><a href=\"template.php?cmd=edit&query=$query&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']."\" style=\"text-decoration: none;\"><span class=\"cp_temp_edit\" style=\"text-decoration: none;\">$title</span> <span class=\"cp_small\">".makelink('edit', "template.php?cmd=edit&query=$query&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']).' '.iif($setid != -1, makelink('revert to original', "template.php?cmd=remove&revert=1&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']).' '.makelink('compare to original', "template.php?cmd=compare&origid=".$templates['-1']["$groupid"]["$title"]['templateid']."&custid=".$templates["$setid"]["$groupid"]["$title"]['templateid']), makelink('remove', "template.php?cmd=remove&templateid=".$template['templateid']))."</span></span></li>\n";
								} else {
									echo "\t\t\t\t\t<li style=\"list-style-type: square;\"><a href=\"template.php?cmd=changeorig&query=$query&templatesetid=$setid&templateid=".$templates['-1']["$groupid"]["$title"]['templateid']."\" style=\"text-decoration: none;\"><span class=\"cp_temp_orig\" style=\"text-decoration: none;\">$title</span> <span class=\"cp_small\">".makelink('change original', "template.php?cmd=changeorig&query=$query&templatesetid=$setid&templateid=".$templates['-1']["$groupid"]["$title"]['templateid'])."</span></span></li>\n";
								}
							}
						} else {
							echo "\t\t\t\t\t<li>No templates</li>\n";
						}
						echo "\t\t\t\t</ul>\n\t\t\t";
					} else {
						echo "\t\t\t<li class=\"tgroup\" style=\"list-style-type: circle;\"><b><a href=\"template.php?templatesetid=$setid&query=$query&templategroupids=$templategroupids,$groupid#g$groupid\" class=\"cp_group_link\"><span style=\"color: black;\">$group[title]</span></a></b> <span class=\"cp_small\">";
						echo makelink('expand', "template.php?templatesetid=$setid&query=$query&templategroupids=$templategroupids,$groupid").' '.makelink('expand alone', "template.php?templatesetid=$setid&query=$query&templategroupids=$groupid").'</span>';
					}
					echo "</li>\n";
				}
				
				####################################################################

				echo "\t\t</ul>\n\t";
			} else {
				echo "\t\t<ul>\n\t\t<li><span class=\"cp_small\">".makelink('expand', "template.php?templatesetid=$setid&query=$query&templategroupids=$templategroupids")."</span></li>\n\t\t</ul>";
			}
			echo " </li>\n";
		}
	}
	echo "\t<li style=\"padding-bottom: 10px; padding-top: 15px; list-style-type: none;\">Templates are the skeleton, so to speak, of the program. Every page your users see is created using using the templates listed below.<br /><br />
	Templates that appear in <span class=\"cp_temp_orig\"><b>this color</b></span> are original templates that were not modified for the selected template set. Original templates are identical for all template sets, and can never be changed or deleted. Templates that appear in <span class=\"cp_temp_cust\"><b>this color</b></span> are modified templates that are based on original templates, or custom templates you have created.<br /><br />
	For your convenience, we have created a number of <b>template groups</b> and categorized all templates so you are able to find them easier. For example, all templates that are used for the address book pages are listed under the 'Address Book' group.</li>\n";

	echo "</ul>\n</div>\n";

	startform('template.php', 'modify', '', array('query' => 'query'));
	starttable('Search through templates', '550', true, 2, false, 4, true);
	inputfield('List templates that contain:<br /><span class="cp_small">Asterisks (*) can be used as a wildcards.</span>', 'query', $query, 25, '&nbsp;&nbsp;<input type="submit" value="  Find  " class="button" style="padding: 0px; height: 20px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	endform();
	endtable();
}

// ############################################################################
// Revert selected templates
if ($_POST['cmd'] == 'doupgrade') {
	if (!is_array($revert)) {
		cp_error('No templates were selected.');
	}

	// Find which templates are going to be reverted
	$titles = array();
	foreach ($revert as $title => $sets) {
		intme($revert["$title"], true);
		if (is_array($sets)) {
			$titles[] = addslashes($title);
		}
	}

	// Get the original templates
	$origs = $DB_site->query('
		SELECT title, user_data, parsed_data
		FROM hive_template
		WHERE templatesetid = -1 AND title IN ("'.implode('", "', $titles).'")
		ORDER BY title
	');

	// Revert the templates
	while ($orig = $DB_site->fetch_array($origs)) {
		$DB_site->query('
			UPDATE hive_template
			SET backup_data = user_data, user_data = "'.addslashes($orig['user_data']).'", parsed_data = "'.addslashes($orig['parsed_data']).'", upgraded = 0
			WHERE title = "'.addslashes($orig['title']).'" AND templatesetid IN ('.implode(', ', $revert["$orig[title]"]).')
		');
	}

	cp_redirect('Selected tempaltes have been reverted.', 'template.php');
}

// ############################################################################
// List the modified templates
if ($cmd == 'upgrade') {
	adminlog();
	echo '<script language="JavaScript" src="../misc/checkall.js"></script>';

	// Get template sets
	$tempsets = table_to_array('templateset', 'templatesetid');

	// Cache templates and group them by set and group
	$gettemplates = $DB_site->query('
		SELECT templateid, title, templatesetid, upgraded
		FROM hive_template
		ORDER BY templatesetid
	');
	$templates = array();
	$origtemplates = array();
	while ($template = $DB_site->fetch_array($gettemplates)) {
		if ($template['templatesetid'] == -1) {
			$origtemplates[] = $template['title'];
		} elseif (array_contains($template['title'], $origtemplates) and $template['upgraded']) {
			$templates["$template[title]"][$template['templatesetid']] = $template['templateid'];
			$sets[$template['templatesetid']] = $tempsets[$template['templatesetid']];
		}
	}

	if (!is_array($templates) or empty($templates)) {
		echo 'No customized templates were modified during the last upgrade.</b>';
	} else {
		$headcells = array('&nbsp;');
		foreach ($sets as $setinfo) {
			$headcells[] = $setinfo['title'];
		}
		$headcells[] = "<input name=\"allbox\" type=\"checkbox\" value=\"Check All\" title=\"Select/Deselect All\" onClick=\"checkAll(this.form);\" />";

		startform('template.php', 'doupgrade', 'Are you sure you want to revert the selected templates?');
		starttable('Revert Templates');
		tablehead($headcells);
		foreach ($templates as $title => $setids) {
			echo "	<tr class=\"".getclass()."\">\n";
			echo "		<td width=\"100%\"><a>$title</a></td>\n";
			foreach ($sets as $setid => $setinfo) {
				if (isset($setids[$setid])) {
					echo "		<td align=\"center\"><input type=\"checkbox\" name=\"revert[$title][]\" value=\"$setid\" onClick=\"checkMain(this.form, 'revert[$title]', 'rowbox[$title]'); checkMain(this.form, 'revert');\" /></td>\n";
				} else {
					echo "		<td>&nbsp;</td>\n";
				}
			}
			echo "		<td><input name=\"rowbox[$title]\" type=\"checkbox\" value=\"Check All\" title=\"Select/Deselect All\" onClick=\"checkAll(this.form, 'revert[$title]'); checkMain(this.form);\" /></td>\n";
			echo "	</tr>\n";
		}
		endform('Revert Selected Templates', '', '', '', count($sets) + 2);
		endtable();
	}
}

if ($cmd != 'compare' or $diff != 'main') {
	cp_footer($cmd != 'compare' or !$diff);
}
?>