<?

/*
 * $Id: tables.original.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

function table($title, $content){
global $HTTP_USER_AGENT, $table_border_color, $table_title_color, $table_content_color;
if(strstr($HTTP_USER_AGENT,"Mozilla/4") && !strstr($HTTP_USER_AGENT,"MSIE")){
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="1" width="100%" align="center">
<tr>
<td width="100%" class="tb_header" nowrap="nowrap"><span class="title">&nbsp;.: $title :.</span></td>
</tr>
<tr>
<td width="100%" class="plain" bgcolor="$table_content_color">$content</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
} else {
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="1" width="100%" align="center">
<tr>
<td width="100%" bgcolor="$table_border_color">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td width="100%" class="tb_header">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td width="100%" class="title" nowrap="nowrap">&nbsp;.: $title :.</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="100%" bgcolor="$table_border_color">
<table border="0" cellpadding="2" cellspacing="0" width="100%" bgcolor="$table_title_color">
<tr>
<td width="100%" class="plain" bgcolor="$table_content_color">
<table border="0" cellspacing="1" cellpadding="2" width="100%" align="center" bgcolor="$table_border_color">
<tr>
<td width="100%" bgcolor="$table_content_color">$content</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
}
return $html;
}

function small_table($title, $content){
global $HTTP_USER_AGENT,$table_border_color,$table_title_color,$table_content_color;
if(strstr($HTTP_USER_AGENT,"Mozilla/4") && !strstr($HTTP_USER_AGENT,"MSIE")){
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="1" width="100%" align="center">
<tr>
<td width="100%" class="tb_header" nowrap="nowrap"><span class="title">&nbsp;.: $title :.</span></td>
</tr>
<tr>
<td width="100%" class="plain" bgcolor="$table_content_color">$content</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
} else {
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="1" width="100%" align="center">
<tr>
<td width="100%" bgcolor="$table_border_color">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td width="100%" class="tb_header">
<table border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
<td width="100%" class="title" nowrap="nowrap">&nbsp;.: $title :.</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="100%" bgcolor="$table_border_color">
<table border="0" cellpadding="2" cellspacing="0" width="100%" bgcolor="$table_title_color">
<tr>
<td width="100%" class="plain" bgcolor="$table_content_color">
<table border="0" cellspacing="1" cellpadding="2" width="100%" align="center" bgcolor="$table_border_color">
<tr>
<td width="100%" bgcolor="$table_content_color">$content</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
}
return $html;
}

function content_table($content){
global $HTTP_USER_AGENT, $table_border_color, $table_title_color, $table_content_color;
if(strstr($HTTP_USER_AGENT,"Mozilla/4") && !strstr($HTTP_USER_AGENT,"MSIE")){
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="1" width="100%" align="center">
<tr>
<td width="100%" class="plain" bgcolor="$table_content_color">$content</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
} else {
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table border="0" cellspacing="0" cellpadding="1" width="100%" align="center">
<tr>
<td width="100%" bgcolor="$table_border_color">
<table border="0" cellpadding="2" cellspacing="0" width="100%" bgcolor="$table_title_color">
<tr>
<td width="100%" class="plain" bgcolor="$table_content_color">
<table border="0" cellspacing="1" cellpadding="2" width="100%" align="center" bgcolor="$table_border_color">
<tr>
<td width="100%" bgcolor="$table_content_color">$content</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
}
return $html;
}

/*
 * $Id: tables.original.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>