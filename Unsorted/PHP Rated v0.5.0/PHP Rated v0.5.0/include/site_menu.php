<?

/*
 * $Id: site_menu.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$title = "Site Menu";

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td align="right" valign="top">
<a href="$base_url/index.php?$sn=$sid" target="_top">Go Home</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=view&amp;s=f">View the Girls</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=view&amp;s=m">View the Guys</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=f">Girls Toplist</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=m">Guys Toplist</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=vc">View Comments</a>
<br />
EOF;

if(!isset($userid)){
$content .= <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;show=signup">Signup Now!</a>
<br />
EOF;
}

$content .= <<<EOF
</td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

/*
 * $Id: site_menu.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
