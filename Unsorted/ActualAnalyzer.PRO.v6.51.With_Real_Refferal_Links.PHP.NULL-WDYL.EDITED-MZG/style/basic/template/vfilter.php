<?php

$topstart=<<<TOPSTART
<DIV class=bor750><input type=hidden name="filter_prm"><table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20>
<td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%%%RHEADER%%</b></SPAN></font></td>
<td align=right background="%%RF%%style/%%STYLE%%/image/bg.gif"><div class=tabl><input width=14 height=14 name="f_clear" type=image src="%%RF%%style/%%STYLE%%/image/blank.gif" title="%%CLEAR%%" border=0></div></td></tr>
<tr bgcolor="#FFFFFF" height=20><td colspan=3><SPAN class=f11>&nbsp;&nbsp;%%CHEADER%%</SPAN></td></tr><tr><td colspan=3><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC"><td align=right><div class=tabl>%%DESC%%:</div></td><td colspan=4><select name="filter_sort" class=list>

TOPSTART;

$listc=<<<LISTC
<option value="%%VALUE%%" %%SELECTED%%>%%ITEM%%</option>

LISTC;

$topend=<<<TOPEND
</select>
</td>
</tr>

TOPEND;

$cdigit=<<<CDIGIT
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=right width=137><div class=tabl>%%DESC%%:</div></td>
<td width=320><input type=text size=12 maxlength=12 class=box90 name="%%NAME%%" value="%%VALUE%%"></td>
<td width=96><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="1" %%STATE1%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC1%%</SPAN></td></tr></table></td>
<td width=95><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="2" %%STATE2%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC2%%</SPAN></td></tr></table></td>
<td width=96><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="3" %%STATE3%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC3%%</SPAN></td></tr></table></td>
</tr>

CDIGIT;

$ctext=<<<CTEXT
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=right width=137><div class=tabl>%%DESC%%:</div></td>
<td width=320><input type=text size=45 maxlength=60 class=box320 name="%%NAME%%" value="%%VALUE%%"></td>
<td width=96><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="1" %%STATE1%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC1%%</SPAN></td></tr></table></td>
<td colspan=2 width=192><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="2" %%STATE2%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC2%%</SPAN></td></tr></table></td>
</tr>

CTEXT;

$cliststart=<<<CLISTSTART
<tr height=20 bgcolor="#EEEEEE" onmouseover="this.className='sel'" onmouseout="this.className='usel'">
<td align=right width=137><div class=tabl>%%DESC%%:</div></td><td width=320><select name="%%NAME%%" class=list>

CLISTSTART;

$clistend=<<<CLISTEND
</select><td width=96><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="1" %%STATE1%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC1%%</SPAN></td></tr></table></td>
<td colspan=2 width=192><table width="100%" border=0 cellspacing=0 cellpadding=0><tr valign=center>
<td><div class=stabl><input type=radio name="%%NAME%%_cl" value="2" %%STATE2%%></div></td>
<td width="100%"><SPAN class=f11>%%DESC2%%</SPAN></td></tr></table></td>
</tr>

CLISTEND;

$bottom=<<<BOTTOM
</table></td></tr><tr height=20><td colspan=3 background="%%RF%%style/%%STYLE%%/image/bg2.gif" align=center>
<SPAN class=f11><input TABINDEX=500 width=20 height=20 name="set" type=image src="%%RF%%style/%%STYLE%%/image/go.gif" title="%%SUBMIT%%" border=0></SPAN></td></tr></table></DIV><br>

BOTTOM;
?>