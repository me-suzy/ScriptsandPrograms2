<?php

$template_content = <<<ENDSTRING
{$lang['info']}
<h2>{$tmplarr['date']} - {$tmplarr['question']}</h2>
<b>{$lang['question_state']}{$tmplarr['state']}</b><br /><br />
<i>{$lang['question_comment']}{$tmplarr['comment']}</i><br /><br />
<table border="1" cellpadding="5" bordercolor="#000000">
<tr><td bgcolor="#aaaaaa">
ENDSTRING;

for ($i = 0; $i < $tmplarr['number']; $i++) {
  $template_content .= <<<ENDSTRING
                        <table height="35" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                        <td width="2" height="35" bgcolor="black" background="line.gif">
                                        <img src="trans.gif" width="2" height="35" border="0" alt="" /><br />
                                        </td>
                                        <td width="{$tmplarr[$i]['width']}" height="35" bgcolor="{$tmplarr[$i]['color']}" background="dots.gif">
                                                <table border="0" cellspacing="0" cellpadding="0" height="35">
                                                        <tr>
                                                                <td><img src="trans.gif" width="$tmplarr[$i]['width']" height="1" border="0" alt="" /><br /></td>
                                                        </tr>
                                                </table>
                                        </td>
                                        <td width="2" height="35" background="line.gif">
                                        <img src="trans.gif" width="2" height="35" border="0" alt="" /><br />
                                        </td>
                                        <td align="center">{$template['fontstring2']}{$tmplarr[$i]['answer']}</font></td>
				</tr>
			</table>
			<img src="trans.gif" width="1" height="1" border="0" alt="" /><br />
ENDSTRING;
}

$template_content .= <<<ENDSTRING
<br /><br />
ENDSTRING;

for ($i = 0; $i < $tmplarr['number']; $i++) {
  $rowcolor = $i % 2 ? " bgcolor=\"{$template['bgcolor']}\"" : "";
  $template_content .= <<<ENDSTRING
<table width="350" border="0" cellspacing="0" cellpadding="0" $rowcolor>
	<tr>
	<td width="20" height="20" bgcolor="{$tmplarr[$i]['color']}">&nbsp;</td>
	<td width"10">&nbsp;</td><td width="220">{$template['fontstring']}
{$tmplarr[$i]['choice']}
</font></td>
	<td width="100">{$template['fontstring2']}
{$tmplarr[$i]['votes']}
</font></td>
	</tr>
</table>
ENDSTRING;
}

$template_content .= <<<ENDSTRING
{$template['fontstring']}{$lang['totalvoters']}{$tmplarr['total']}</font>
<br />
</td></tr>
</table>
ENDSTRING;

?>
