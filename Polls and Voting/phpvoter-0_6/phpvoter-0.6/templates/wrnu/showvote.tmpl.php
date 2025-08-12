<?php

print <<<ENDSTRING
{$lang['info']}
<h2>{$tmplarr['date']} - {$tmplarr['question']}</h2>
<b>{$lang['question_state']}{$tmplarr['state']}</b><br /><br />
<i>{$lang['question_comment']}{$tmplarr['comment']}</i><br /><br />
<!---- Start på tabellerna ------>

	<TABLE WIDTH="400" BORDER=0 CELLSPACING=0 CELLPADDING=0>
	<TR>
		<TD HEIGHT="55" WIDTH="52" BACKGROUND="/images/vote/horn.gif">
		&nbsp;
		</TD>

		<TD HEIGHT="55" WIDTH="348">
	
			<TABLE WIDTH="231" BORDER="0" CELLSPACING="0" CELLPADDING="0" BACKGROUND="/images/vote/linjal.gif">
			<TR><TD WIDTH="210" HEIGHT="55">
				<TABLE WIDTH="210" BORDER=0 CELLSPACING=0 CELLPADDING=0 BACKGROUND="#">
				<TR>	<TD ALIGN="left" WIDTH="30"><FONT SIZE="-2" FACE="helvetica, arial">0%</FONT></TD>
					<TD WIDTH="150"><FONT SIZE="-2" FACE="helvetica, arial"><CENTER>50%</CENTER></FONT></TD>
					<TD ALIGN="right" WIDTH="30"><FONT SIZE="-2" FACE="helvetica, arial">100%</FONT></TD>
				</TR>
				<TR>	<TD ALIGN="left" WIDTH="30"><FONT SIZE="-2" FACE="helvetica, arial">|</FONT></TD>
					<TD><FONT SIZE="-2" FACE="helvetica, arial"><CENTER>|</CENTER></FONT></TD>
					<TD ALIGN="right" WIDTH="30"><FONT SIZE="-2" FACE="helvetica, arial">|</FONT></TD>
				</TR>
				</TABLE>
			</TD>
                        <TD WIDTH="21" HEIGHT="55" BACKGROUND="/images/vote/blipp.gif">&nbsp;</TD>
			</TR>
			</TABLE>

		</TD>			

	</TR>
	<TR>
		<TD WIDTH="52" HEIGHT="100" BACKGROUND="/images/vote/linjal2.gif">
		&nbsp;
		</TD>

		<TD WIDTH="280" HEIGHT="100" VALIGN="top">

		<!--- Staplar --->
ENDSTRING;

for ($i = 0; $i < $tmplarr['number']; $i++) {
  print <<<ENDSTRING
                        <table height="35" border="0" cellspacing="0" cellpadding="0">
                                <tr>

                                        <td width="2" height="35" bgcolor="black" background="line.gif">
                                        <img src="trans.gif" width="2" height="35" border="0" alt="" /><br />
                                        </td>
                                        <td width="{$tmplarr[$i]['width']}" height="35" bgcolor="{$tmplarr[$i]['color']}" background="dots.gif">
                                                <table border="0" cellspacing="0" cellpadding="0" height="35">
                                                        <tr>
                                                                <td><img src="trans.gif" width="{$tmplarr[$i]['width']}" height="1" border="0" alt="" /><br /></td>
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

print <<<ENDSTRING
<br /><br />
ENDSTRING;

for ($i = 0; $i < $tmplarr['number']; $i++) {
  $rowcolor = $i % 2 ? " bgcolor=\"{$template['bgcolor']}\"" : "";
  print <<<ENDSTRING
<table width="350" border="0" cellspacing="0" cellpadding="0"$rowcolor>
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

print <<<ENDSTRING
{$template['fontstring']}{$lang['totalvoters']}{$tmplarr['total']}</font>
<BR>
		<!--- Slut på staplar --->
		</TD>
	</TR>

	<TR>
	<TD WIDTH="52" HEIGHT="64" BACKGROUND="/images/vote/blipp2.gif">
	&nbsp;
	</TD>	
	</TR>

	</TABLE>


<!---Slut på tabellerna--->
ENDSTRING;

?>
