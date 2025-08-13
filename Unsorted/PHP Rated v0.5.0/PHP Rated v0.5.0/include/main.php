<?

/*
 * $Id: main.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$title = $site_title;

$main_text = template("main_text");
eval("\$main_text = \"$main_text\";");

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td>
$main_text
</td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

/*
 * $Id: main.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>