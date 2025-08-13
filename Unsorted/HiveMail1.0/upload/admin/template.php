<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: template.php,v $
// | $Date: 2002/11/07 15:41:46 $
// | $Revision: 1.31 $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require_once('./global.php');
cp_header(' &raquo; Template Editor');

// ############################################################################
// Set the default do
if (!isset($do)) {
	$do = 'modify';
}

// ############################################################################
// Remove template
if ($_POST['do'] == 'kill') {
	$template = getinfo('template', $templateid);
	$DB_site->query("
		DELETE FROM template
		WHERE templateid = $templateid
	");

	if ($revert) {
		cp_redirect('The template has been reverted.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	} else {
		cp_redirect('The template has been removed.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
	$do = 'modify';
}

// ############################################################################
// Remove template
if ($do == 'remove') {
	$template = getinfo('template', $templateid);
	startform('template.php', 'kill', 'Are you sure you want to revert this template?');
	hiddenfield('revert', $revert);
	hiddenfield('templateid', $templateid);
	if ($revert) {
		starttable('Revert template "'.$template['title'].'" (ID: '.$templateid.')');
		textrow('Are you <b>sure</b> you want to revert this template back to original? This procedure <b>cannot</b> be reveresed!');
		endform('Revert template', '', 'Go Back');
	} else {
		starttable('Remove template "'.$template['title'].'" (ID: '.$templateid.')');
		textrow('Are you <b>sure</b> you want to remove this set? This procedure <b>cannot</b> be reveresed!');
		endform('Remove template', '', 'Go Back');
	}
	endtable();
}

// ############################################################################
// Update a template
if ($_POST['do'] == 'update') {
	if (empty($template['title'])) {
		cp_error('The template must have a title.');
	} elseif ($DB_site->query_first("SELECT templateid FROM template WHERE title='".addslashes($template['title'])."' AND templatesetid = $template[templatesetid] AND templateid <> $templateid")) {
		cp_error('A template with the title you specified already exists in this set.');
	} else {
		$template['parsed_data'] = parse_template($template['user_data']);
		$DB_site->auto_query('template', $template, "templateid = $templateid");
		cp_redirect('The template has been updated.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
}

// ############################################################################
// Update a template
if ($do == 'edit') {
	// Get some useful information
	$template = getinfo('template', $templateid);
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
	if ($DB_site->query_first("SELECT templateid FROM template WHERE templatesetid = -1 AND title = '".addslashes($template['title'])."'") and $template['templatesetid'] != -1) {
		tablerow(array('Template group:', $templategroup['title']), true);
		hiddenfield('template[templategroupid]', $templategroup['templategroupid']);
	} else {
		tableselect('Template group:', 'template[templategroupid]', 'templategroup', $templategroup['templategroupid']);
	}

	// This is the template we edit
	hiddenfield('templateid', $templateid);

	// The title and data
	inputfield('Template title:', 'template[title]', $template['title']);
	textarea('Template data:', 'template[user_data]', $template['user_data'], 25, 100);
	textarea('Backup information:<br /><br /><font size="1">Use this box to save any kind of information<br />about this template.<br />This is extremely useful if you make drastic<br />changes to a template and wish to keep the<br />old one.<br />Anything that is stored here will never be<br />displayed on your site.</font>', 'template[backup_data]', $template['backup_data'], 10, 100);

	// Submit button
	endform('Update template');
	endtable();
}

// ############################################################################
// Create a new template
if ($_POST['do'] == 'insert') {
	if (empty($template['title'])) {
		cp_error('The template must have a title.');
	} elseif ($DB_site->query_first("SELECT templateid FROM template WHERE title='".addslashes($template['title'])."' AND templatesetid = $template[templatesetid]")) {
		cp_error('A template with the title you specified already exists in this set.');
	} else {
		$template['parsed_data'] = parse_template($template['user_data']);
		$DB_site->auto_query('template', $template);
		cp_redirect('The template has been created.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
}

// ############################################################################
// Create a new template
if ($do == 'add') {
	// Get and define some important stuff
	$templateset = getinfo('templateset', $templatesetid, false, false);

	// The form
	startform('template.php', 'insert', '', array('template_title' => 'title'));
	starttable('Create new template');

	// We can choose template set and group
	selectbox('Template set:', 'template[templatesetid]', iif($debug, array('-1' => 'Global Set'), array()) + table_to_array('templateset', 'templatesetid', '1 = 1', '', 'title'), $templateset['templatesetid']);
	tableselect('Template group:', 'template[templategroupid]', 'templategroup', $templategroupid);

	// The title and dataa
	inputfield('Template title:', 'template[title]', $template['title']);
	textarea('Template data:', 'template[user_data]', $template['user_data'], 25, 100);
	textarea('Backup information:<br /><br /><font size="1">Use this box to save any kind of information<br />about this template.<br />This is extremely useful if you make drastic<br />changes to a template and wish to keep the<br />old one.<br />Anything that is stored here will never be<br />displayed on your site.</font>', 'template[backup_data]', $template['backup_data'], 10, 100);

	// Submit button
	endform('Create template');
	endtable();
}

// ############################################################################
// Change an original template
if ($_POST['do'] == 'updateorig') {
	if (empty($template['title'])) {
		cp_error('The template must have a title.');
	} elseif ($DB_site->query_first("SELECT templateid FROM template WHERE title='".addslashes($template['title'])."' AND templatesetid = $template[templatesetid]")) {
		cp_error('A template with the title you specified already exists in this set.');
	} else {
		$template['parsed_data'] = parse_template($template['user_data']);
		$DB_site->auto_query('template', $template);
		cp_redirect('The template has been changed.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
	}
}

// ############################################################################
// Change an original template
if ($do == 'changeorig') {
	// Get some useful information
	$template = getinfo('template', $templateid);
	$templateset = getinfo('templateset', $templatesetid);
	$templategroup = getinfo('templategroup', $template['templategroupid']);

	// The form
	startform('template.php', 'updateorig', '', array('template_title' => 'title'));
	starttable('Change original tepmlate "'.$template['title'].'"');

	// We can't choose template set or group
	tablerow(array('Template set:', $templateset['title']), true);
	tablerow(array('Template group:', $templategroup['title']), true);
	hiddenfield('template[templatesetid]', $templateset['templatesetid']);
	hiddenfield('template[templategroupid]', $templategroup['templategroupid']);

	// This is the template we edit
	hiddenfield('templateid', $templateid);

	// The title and data
	inputfield('Template title:', 'template[title]', $template['title']);
	textarea('Template data:', 'template[user_data]', $template['user_data'], 25, 100);
	textarea('Backup information:<br /><br /><font size="1">Use this box to save any kind of information<br />about this template.<br />This is extremely useful if you make drastic<br />changes to a template and wish to keep the<br />old one.<br />Anything that is stored here will never be<br />displayed on your site.</font>', 'template[backup_data]', $template['backup_data'], 10, 100);

	// Submit button
	endform('Change template');
	endtable();
}

// ############################################################################
// Update a template
if ($_POST['do'] == 'updateunknown') {
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
	} else {
		$DB_site->auto_query('template', $template, "templateid = $templateid");
	}

	cp_redirect('The template has been updated.', "template.php?templategroupid=$template[templategroupid]&templatesetid=$template[templatesetid]");
}

// ############################################################################
// List the templates
if ($do == 'modify' and 0) {
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
	tableselect('Viewing '.strtolower($group['title']).' templates:', 'templategroupid', 'templategroup', $templategroupid, '1 = 1 ORDER BY display', true);
	inputfield('Viewing templates that contain '.iif($query, '"'.htmlspecialchars($query).'"', 'anything').':', 'query', $query);
	selectbox('Viewing in:', 'do', array('modify' => 'paginated format&nbsp;&nbsp;', 'linear' => 'linear format '), 'modify');
	endform('Reload Page');
	endtable();

	echo "<script language=\"JavaScript\">
<!--
function gotoTemplate(evtObj, ID, action) {
	if (evtObj.shiftKey) {
		window.open('template.php?do='+action+'&templatesetid=$templatesetid&templategroupid=$templategroupid&templateid='+ID);
		return false;
	} else {
		window.location = 'template.php?do='+action+'&templatesetid=$templatesetid&templategroupid=$templategroupid&templateid='+ID;
	}
}
// -->
</script>
";

	// Custom templates
	echo '<br /><br />';
	starttable("$group[title] - Custom templates &nbsp;".makelink('add template', "template.php?do=add&templatesetid=$templatesetid&templategroupid=$templategroupid"));

	$customs = $DB_site->query("
		SELECT custom.templateid, custom.title, custom.templatesetid, custom.description".iif($adminloadtemplates, ', custom.user_data')."
		FROM template custom
		LEFT JOIN template orig ON (orig.title = custom.title AND orig.templatesetid = -1)
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
				echo "		<td align=\"center\" class=\"$thisclass\" nowrap=\"nowrap\" colspan=\"2\"><form action=\"template.php\" method=\"post\"><input type=\"hidden\" name=\"do\" value=\"updateunknown\" /><input type=\"hidden\" name=\"templateid\" value=\"$template[templateid]\" /><input type=\"hidden\" name=\"templatesetid\" value=\"$template[templatesetid]\" /><textarea style=\"width: 97%;\" rows=\"15\" name=\"user_data\">".htmlspecialchars($template['user_data'])."</textarea><br /><br /><input type=\"submit\" value=\"Update Template\" class=\"bginput\" /></form></td>\n";
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
		FROM template orig
		LEFT JOIN template custom ON (orig.title = custom.title AND custom.templatesetid = $templatesetid)
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
				echo "		<td align=\"center\" class=\"$thisclass\" nowrap=\"nowrap\" colspan=\"2\"><form action=\"template.php\" method=\"post\"><input type=\"hidden\" name=\"do\" value=\"updateunknown\" /><input type=\"hidden\" name=\"templateid\" value=\"$template[customtemplateid]\" /><input type=\"hidden\" name=\"templatesetid\" value=\"$templatesetid\" /><textarea style=\"width: 97%;\" rows=\"15\" name=\"user_data\">".htmlspecialchars($template['customuser_data'])."</textarea><br /><br /><input type=\"submit\" value=\"Update Template\" class=\"bginput\" /></form></td>\n";
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
				echo "		<td align=\"center\" class=\"$thisclass\" nowrap=\"nowrap\" colspan=\"2\"><form action=\"template.php\" method=\"post\"><input type=\"hidden\" name=\"do\" value=\"updateunknown\" /><input type=\"hidden\" name=\"templateid\" value=\"$template[origtemplateid]\" /><input type=\"hidden\" name=\"templatesetid\" value=\"$templatesetid\" /><textarea style=\"width: 97%;\" rows=\"15\" name=\"user_data\">".htmlspecialchars($template['origuser_data'])."</textarea><br /><br /><input type=\"submit\" value=\"Update Template\" class=\"bginput\" /></form></td>\n";
				echo "	</tr>\n";
			}
		}
	}
	endtable();
}

// ############################################################################
// List the templates
if ($do == 'linear' or $do == 'modify') {
	// When switching modes
	default_var($templategroupids, $templategroupid);

	// Cache templates and group them by set and group
	$gettemplates = $DB_site->query('
		SELECT templateid, title, templatesetid, templategroupid
		FROM template
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
		while ($custtemp = $DB_site->fetch_array($getcusttemps, 'SELECT title, templatesetid FROM template WHERE templatesetid <> -1')) {
			$custtemps["$custtemp[title]"]["$custtemp[templatesetid]"] = true;
		}
	}

	echo "<div align=\"left\">\n<ul>\n";
	echo "\t<li style=\"padding-bottom: 10px; list-style-type: none;\">Templates are the skeleton, so to speak, of the program. Every page your users see is created using using the templates listed below.<br /><br />
	Templates that appear in <span class=\"cp_temp_orig\"><b>this color</b></span> are original templates that were not modified for the selected template set. Original templates are identical for all template sets, and can never be changed or deleted. Templates that appear in <span class=\"cp_temp_cust\"><b>this color</b></span> are modified templates that are based on original templates, or custom templates you have created.<br /><br />
	For your convenience, we have created a number of <b>template groups</b> and categorized all templates so you are able to find them easier. For example, all templates that are used for the address book pages are listed under the 'Address Book' group.</li>\n";
	if (!empty($query)) {
		echo "\t<li style=\"padding-bottom: 10px; list-style-type: none;\"><span class=\"cp_temp_cust\"><b>Searching templates for:&nbsp;&nbsp;<tt>".str_replace('*', '<span class="cp_temp_orig">*</span>', htmlspecialchars($query))."</tt> ...</b></span></li>\n";
	}
	foreach ($sets as $setid => $set) {
		echo "\t<li style=\"padding-bottom: 10px; list-style-type: disk;\"><b>$set[title]</b> <span class=\"cp_small\">".makelink('edit set', "templateset.php?do=edit&templatesetid=$setid").iif($setid != -1 and $setid != 1, ' '.makelink('remove set', "templateset.php?do=remove&templatesetid=$setid")).' '.makelink('add template', "template.php?do=add&templatesetid=$setid&templategroupids=$templategroupids").' '.makelink('expand all groups', "template.php?templatesetid=$setid&query=$query&templategroupids=$allgroupids").' '.makelink('collapse all groups', "template.php?templatesetid=$setid&query=$query").'</span>';
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
						$customoutput .= "\t\t\t\t\t<li style=\"list-style-type: square;\"><a href=\"template.php?do=edit&query=$query&templateid=".$template['templateid']."\" style=\"text-decoration: none;\"><span class=\"cp_temp_cust\" style=\"text-decoration: none;\">$title</span> <span class=\"cp_small\">".makelink('edit', "template.php?do=edit&query=$query&templateid=".$template['templateid']).' '.makelink('remove', "template.php?do=remove&templateid=".$template['templateid'])."</span></span></li>\n";
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
								echo "\t\t\t\t\t<li style=\"list-style-type: square;\"><a href=\"template.php?do=edit&query=$query&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']."\" style=\"text-decoration: none;\"><span class=\"cp_temp_edit\" style=\"text-decoration: none;\">$title</span> <span class=\"cp_small\">".makelink('edit', "template.php?do=edit&query=$query&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']).' '.iif($setid != -1, makelink('revert to original', "template.php?do=remove&revert=1&templateid=".$templates["$setid"]["$groupid"]["$title"]['templateid']), makelink('remove', "template.php?do=remove&templateid=".$template['templateid']))."</span></span></li>\n";
							} else {
								echo "\t\t\t\t\t<li style=\"list-style-type: square;\"><a href=\"template.php?do=changeorig&query=$query&templatesetid=$setid&templateid=".$templates['-1']["$groupid"]["$title"]['templateid']."\" style=\"text-decoration: none;\"><span class=\"cp_temp_orig\" style=\"text-decoration: none;\">$title</span> <span class=\"cp_small\">".makelink('change original', "template.php?do=changeorig&query=$query&templatesetid=$setid&templateid=".$templates['-1']["$groupid"]["$title"]['templateid'])."</span></span></li>\n";
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
	echo "</ul>\n</div>\n";
}

// ############################################################################
if ($do == 'search') {
	startform('template.php', 'modify');
	starttable('Find templates', '550');
	inputfield('List templates that contain:<br /><span class="cp_small">Asterisks (*) can be used as a wildcards.</span>', 'query');
	endform('Search');
	endtable();
}

cp_footer();
?>