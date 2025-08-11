<?php

// $Header: /cvsroot/bitweaver/_bit_themes/admin/admin_content_templates.php,v 1.1.1.1.2.2 2005/08/02 14:18:17 lsces Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../../bit_setup_inc.php' );

include_once( THEMES_PKG_PATH.'templates_lib.php' );

if (!$gBitUser->hasPermission( 'bit_p_edit_content_templates' )) {
	$gBitSmarty->assign('msg', tra("You dont have permission to use this feature"));

	$gBitSystem->display( 'error.tpl' );
	die;
}

if (!isset($_REQUEST["template_id"])) {
	$_REQUEST["template_id"] = 0;
}

$gBitSmarty->assign('template_id', $_REQUEST["template_id"]);

if ($_REQUEST["template_id"]) {
	$info = $templateslib->get_template($_REQUEST["template_id"]);

	if ($templateslib->template_is_in_section($_REQUEST["template_id"], 'html')) {
		$info["section_html"] = 'y';
	} else {
		$info["section_html"] = 'n';
	}

	if ($templateslib->template_is_in_section($_REQUEST["template_id"], 'wiki')) {
		$info["section_wiki"] = 'y';
	} else {
		$info["section_wiki"] = 'n';
	}

	if ($templateslib->template_is_in_section($_REQUEST["template_id"], 'newsletters')) {
		$info["section_newsletters"] = 'y';
	} else {
		$info["section_newsletters"] = 'n';
	}

	if ($templateslib->template_is_in_section($_REQUEST["template_id"], 'cms')) {
		$info["section_cms"] = 'y';
	} else {
		$info["section_cms"] = 'n';
	}
} else {
	$info = array();

	$info["name"] = '';
	$info["content"] = '';
	$info["section_cms"] = 'n';
	$info["section_html"] = 'n';
	$info["section_wiki"] = 'n';
	$info["section_newsletters"] = 'n';
}

$gBitSmarty->assign('info', $info);

if (isset($_REQUEST["remove"])) {
	
	$templateslib->remove_template($_REQUEST["remove"]);
}

if (isset($_REQUEST["removesection"])) {
	
	$templateslib->remove_template_from_section($_REQUEST["rtemplate_id"], $_REQUEST["removesection"]);
}

$gBitSmarty->assign('preview', 'n');

if (isset($_REQUEST["preview"])) {
	$gBitSmarty->assign('preview', 'y');

	if (isset($_REQUEST["section_html"]) && $_REQUEST["section_html"] == 'on') {
		$info["section_html"] = 'y';

		$parsed = nl2br($_REQUEST["content"]);
	} else {
		$info["section_html"] = 'n';

		$parsed = $gBitSystem->parseData($_REQUEST["content"]);
	}

	$gBitSmarty->assign('parsed', $parsed);

	if (isset($_REQUEST["section_wiki"]) && $_REQUEST["section_wiki"] == 'on') {
		$info["section_wiki"] = 'y';
	} else {
		$info["section_wiki"] = 'n';
	}

	if (isset($_REQUEST["section_newsletters"]) && $_REQUEST["section_newsletters"] == 'on') {
		$info["section_newsletters"] = 'y';
	} else {
		$info["section_newsletters"] = 'n';
	}

	if (isset($_REQUEST["section_cms"]) && $_REQUEST["section_cms"] == 'on') {
		$info["section_cms"] = 'y';
	} else {
		$info["section_cms"] = 'n';
	}

	$info["content"] = $_REQUEST["content"];
	$info["name"] = $_REQUEST["name"];
	$gBitSmarty->assign('info', $info);
}

if (isset($_REQUEST["save"])) {
	
	$tid = $templateslib->replace_template($_REQUEST["template_id"], $_REQUEST["name"], $_REQUEST["content"]);

	$gBitSmarty->assign("template_id", '0');
	$info["name"] = '';
	$info["content"] = '';
	$info["section_cms"] = 'n';
	$info["section_wiki"] = 'n';
	$info["section_newsletters"] = 'n';
	$info["section_html"] = 'n';
	$gBitSmarty->assign('info', $info);

	if (isset($_REQUEST["section_html"]) && $_REQUEST["section_html"] == 'on') {
		$templateslib->add_template_to_section($tid, 'html');
	} else {
		$templateslib->remove_template_from_section($tid, 'html');
	}

	if (isset($_REQUEST["section_wiki"]) && $_REQUEST["section_wiki"] == 'on') {
		$templateslib->add_template_to_section($tid, 'wiki');
	} else {
		$templateslib->remove_template_from_section($tid, 'wiki');
	}

	if (isset($_REQUEST["section_newsletters"]) && $_REQUEST["section_newsletters"] == 'on') {
		$templateslib->add_template_to_section($tid, 'newsletters');
	} else {
		$templateslib->remove_template_from_section($tid, 'newsletters');
	}

	if (isset($_REQUEST["section_cms"]) && $_REQUEST["section_cms"] == 'on') {
		$templateslib->add_template_to_section($tid, 'cms');
	} else {
		$templateslib->remove_template_from_section($tid, 'cms');
	}
}

if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'created_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}

$gBitSmarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

$gBitSmarty->assign('find', $find);

$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);
$channels = $templateslib->list_all_templates($offset, $maxRecords, $sort_mode, $find);

$cant_pages = ceil($channels["cant"] / $maxRecords);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($offset / $maxRecords));

if ($channels["cant"] > ($offset + $maxRecords)) {
	$gBitSmarty->assign('next_offset', $offset + $maxRecords);
} else {
	$gBitSmarty->assign('next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $maxRecords);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}

$gBitSmarty->assign_by_ref('channels', $channels["data"]);



// Display the template
$gBitSystem->display( 'bitpackage:themes/admin_content_templates.tpl');

?>
