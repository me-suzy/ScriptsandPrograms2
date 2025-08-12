<?php

/* *************************************************** */
/* TEMPLATE INFORMATION DEFINING THE LOOK OF THE PAGES */
/* *************************************************** */

// A list of the colors of the bars in the statistics.
$template['color'] = array("red", "green", "blue", "yellow", "magenta", "cyan", "white", "gray", "orange", "brown", "maroon", "pink", "wheat", "teal", "gold", "indigo");

$template['fontstring'] = "<font size=\"{$config['fontsize']}\" face=\"{$config['font']}\">";
$template['fontstring2'] = "<font size=\"{$config['fontsize2']}\" face=\"{$config['font']}\">";

$template['bgcolor'] = "#CCCCCC";


$template['pre_question_comment'] = "<i>";
$template['post_question_comment'] = "</i><br /><br />";

$template['pre_error_header'] = "<h1><font color=\"red\">";
$template['post_error_header'] = "</font></h1>";

$template['pre_error_string'] = "<i><b><font size=\"+1\">";
$template['post_error_string'] = "</font></b></i>";

$template['pre_admin_header'] = "<h1>";
$template['post_admin_header'] = "</h1>";

$template['pre_admin_menu'] = "<ul>";
$template['post_admin_menu'] = "</ul>";

$template['pre_admin_menuitem'] = "<li>";
$template['post_admin_menuitem'] = "</li>";

$template['pre_admin_list'] = <<<ENDSTRING
<table width="100%" border="0">
<tr>
<th>{$template['fontstring']}{$lang['voteid']}</font></th>
<th width="50%">{$template['fontstring']}{$lang['question']}</font></th>
<th>{$template['fontstring']}{$lang['date']}</font></th>
<th>{$template['fontstring']}{$lang['state']}</font></th>
<th>{$template['fontstring']}{$lang['answers']}</font></th>
<th>{$template['fontstring']}{$lang['voters']}</font></th>
<th>{$template['fontstring']}{$lang['function']}</font></th>
</tr>
ENDSTRING;
$template['post_admin_list'] = "</table>";

$template['pre_admin_listrow'] = "<tr>";
$template['post_admin_listrow'] = "</tr>";

$template['pre_admin_listitem'] = "<td>{$template['fontstring']}";
$template['post_admin_listitem'] = "</font></td>";

$template['pre_voter_list'] = <<<ENDSTRING
<table width="50%" border="0">
<tr>
<th>{$template['fontstring']}{$lang['voterid']}</font></th>
<th>{$template['fontstring']}{$lang['IP']}</font></th>
<th>{$template['fontstring']}{$lang['answer']}</font></th>
<th>{$template['fontstring']}{$lang['function']}</font></th>
</tr>
ENDSTRING;
$template['post_voter_list'] = "</table>";

$template['pre_voter_listrow'] = "<tr>";
$template['post_voter_listrow'] = "</tr>";

$template['pre_voter_listitem'] = "<td>{$template['fontstring']}";
$template['post_voter_listitem'] = "</font></td>";

$template['pre_createquestion'] = <<<ENDSTRING
<table border="0">
<tr>
<th>{$template['fontstring']}{$lang['choice_id']}</font></th>
<th>{$template['fontstring']}{$lang['question']}</font></th>
<th>{$template['fontstring']}{$lang['voters']}</font></th>
<th>{$template['fontstring']}{$lang['function']}</font></th>
</tr>
ENDSTRING;
$template['post_createquestion'] = "</table>";

$template['pre_createquestion_row'] = "<tr>";
$template['post_createquestion_row'] = "</tr>";

$template['pre_createquestion_item'] = "<td>{$template['fontstring']}";
$template['post_createquestion_item'] = "</font></td>";

$template['pre_admin_info'] = "<b>";
$template['post_admin_info'] = "</b><br /><br />";

?>
