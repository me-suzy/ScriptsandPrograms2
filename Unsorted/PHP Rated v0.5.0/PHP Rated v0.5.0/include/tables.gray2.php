<?

/*
 * $Id: tables.gray2.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

function table($title, $content){
global $base_url;
$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td>
	<table border="0" cellspacing="0" width="100%" cellpadding="0">
	<tr>
		<td width="100%" bgcolor="#111111">
		<table border="0" cellpadding="2" cellspacing="1" width="100%">
		<tr>
			<td width="100%" bgcolor="#C0C0C0" class="title">.: $title :.</td>
		</tr>
		<tr>
			<td width="100%" bgcolor="#dddddd">$content</td>
		</tr>
		</table>
		</td>
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
	<table border="0" cellspacing="0" width="100%" cellpadding="0">
	<tr>
		<td width="100%" bgcolor="#111111">
		<table border="0" cellpadding="2" cellspacing="1" width="100%">
		<tr>
			<td width="100%" bgcolor="#C0C0C0" class="title">.: $title :.</td>
		</tr>
		<tr>
			<td width="100%" bgcolor="#dddddd">$content</td>
		</tr>
		</table>
		</td>
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
 * $Id: tables.gray2.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>