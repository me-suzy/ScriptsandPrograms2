<?

/*
 * $Id: tables.gray.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

function table($title, $content){
global $base_url;
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td width="13" height="18"><img border="0" src="$base_url/images/gray_top_left.gif" width="13" height="18" alt="" /></td>
	<td height="18" class="gray_top_middle">&nbsp;</td>
	<td width="13" height="18"><img border="0" src="$base_url/images/gray_top_right.gif" width="13" height="18" alt="" /></td>
</tr>
<tr>
	<td width="13" class="gray_title_left">&nbsp;</td>
	<td bgcolor="#DCDCDC">
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
		<td>.: $title :.</td>
	</tr>
	</table>
	</td>
	<td width="13" class="gray_title_right">&nbsp;</td>
</tr>
<tr>
	<td width="13" height="1"><img border="0" src="$base_url/images/gray_left_middle.gif" width="13" height="1" alt="" /></td>
	<td height="1" class="gray_middle_fill"></td>
	<td width="13" height="1"><img border="0" src="$base_url/images/gray_right_middle.gif" width="13" height="1" alt="" /></td>
</tr>
<tr>
	<td width="13" class="gray_left_body">&nbsp;</td>
	<td bgcolor="#ECECEC">
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
		<td>$content</td>
	</tr>
	</table>
	</td>
	<td width="13" class="gray_right_body">&nbsp;</td>
</tr>
<tr>
	<td height="13" width="13"><img border="0" src="$base_url/images/gray_bottom_left.gif" width="13" height="13" alt="" /></td>
	<td height="13" class="gray_bottom_middle"></td>
	<td height="13" width="13"><img border="0" src="$base_url/images/gray_bottom_right.gif" width="13" height="13" alt="" /></td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
return $html;
}

function small_table($title, $content){
global $base_url;
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0">
<tr>
<td>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td width="13" height="18"><img border="0" src="$base_url/images/gray_top_left.gif" width="13" height="18" alt="" /></td>
	<td height="18" class="gray_top_middle">&nbsp;</td>
	<td width="13" height="18"><img border="0" src="$base_url/images/gray_top_right.gif" width="13" height="18" alt="" /></td>
</tr>
<tr>
	<td width="13" class="gray_title_left">&nbsp;</td>
	<td bgcolor="#DCDCDC">
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
		<td>.: $title :.</td>
	</tr>
	</table>
	</td>
	<td width="13" class="gray_title_right">&nbsp;</td>
</tr>
<tr>
	<td width="13" height="1"><img border="0" src="$base_url/images/gray_left_middle.gif" width="13" height="1" alt="" /></td>
	<td height="1" bgcolor="#767676"></td>
	<td width="13" height="1"><img border="0" src="$base_url/images/gray_right_middle.gif" width="13" height="1" alt="" /></td>
</tr>
<tr>
	<td width="13" class="gray_left_body">&nbsp;</td>
	<td bgcolor="#ECECEC">
	<table cellpadding="3" cellspacing="0" border="0" width="100%">
	<tr>
		<td>$content</td>
	</tr>
	</table>
	</td>
	<td width="13" class="gray_right_body">&nbsp;</td>
</tr>
<tr>
	<td height="13" width="13"><img border="0" src="$base_url/images/gray_bottom_left.gif" width="13" height="13" alt="" /></td>
	<td height="13" class="gray_bottom_middle"></td>
	<td height="13" width="13"><img border="0" src="$base_url/images/gray_bottom_right.gif" width="13" height="13" alt="" /></td>
</tr>
</table>
</td>
</tr>
</table>
EOF;
return $html;
}

function content_table($content){
$html = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td>$content</td>
</tr>
</table>
EOF;
return $html;
}

/*
 * $Id: tables.gray.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>