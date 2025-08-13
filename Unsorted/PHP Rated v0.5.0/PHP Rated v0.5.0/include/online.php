<?

/*
 * $Id: online.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$uo_sql = "
	select
		count(*) as count
	from
		$tb_sessions
	where
		expire > UNIX_TIMESTAMP() - 300
";

$uo_query = sql_query($uo_sql);
$uo_total = sql_result($uo_query, 0, "count") + 0;

$title = "Visitors Online";

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular">&nbsp;Visitors Online:</td>
<td align="right" class="regular">$uo_total&nbsp;</td></tr>
</table>
EOF;

$final_output .= table($title, $content);

/*
 * $Id: online.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>