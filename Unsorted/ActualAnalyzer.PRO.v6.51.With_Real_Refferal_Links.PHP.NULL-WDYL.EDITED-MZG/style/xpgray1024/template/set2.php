<?php
$set=<<<SET
<DIV class=bor750><input type=hidden name=langlist value=%%LANG%%><table width=750 border=0 cellspacing=0 cellpadding=0 align=center>
<tr height=20><td background="%%RF%%style/%%STYLE%%/image/bg.gif"><font color="#FFFFFF"><SPAN class=f12>&nbsp;&nbsp;<b>%%HEADER%%</b></SPAN></font></td>
</tr><tr height=20><td><SPAN class=f11>&nbsp;&nbsp;%%STEPS%%</SPAN></td></tr><tr><td><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td colspan=2><SPAN class=f11><b>%%SHEADER%%</b></SPAN></td></tr><tr bgcolor="#EEEEEE" height=20 align=left>
<td width=328 valign=top><input TABINDEX=1 type=text size=45 maxlength=255 class=box328 name=fldurl value="%%FLDURL%%"></td>
<td width=439><SPAN class=f11>&nbsp;&nbsp;%%URLDESC%% <i>"http://www.mydomain.com/%%FOLDER%%/"</i>).</SPAN></td></tr></table>
<table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td colspan=2><SPAN class=f11><b>%%DHEADER%%</b></SPAN></td>
</tr><tr bgcolor="#EEEEEE" height=20 align=left><td width=198 valign=top><input TABINDEX=2 type=text size=30 maxlength=40 class=box198 name=fldhost value="%%FLDHOST%%"></td>
<td width=549><SPAN class=f11>&nbsp;&nbsp;%%HOSTDESC%% <i>"localhost:3306"</i>).</SPAN></td></tr><tr bgcolor="#EEEEEE" height=20 align=left>
<td valign=top><input TABINDEX=3 type=text size=30 maxlength=40 class=box198 name=fldbase value="%%FLDBASE%%"></td><td><SPAN class=f11>&nbsp;&nbsp;%%BASEDESC%% <i>"analyzer"</i>).</SPAN></td>
</tr><tr bgcolor="#EEEEEE" height=20 align=left><td valign=top><input TABINDEX=4 type=text size=30 maxlength=40 class=box198 name=flduser value="%%FLDUSER%%"></td>
<td><SPAN class=f11>&nbsp;&nbsp;%%USERDESC%% <i>"admin"</i>).</SPAN></td></tr><tr bgcolor="#EEEEEE" height=20 align=left>
<td valign=top><input TABINDEX=5 type=text size=30 maxlength=40 class=box198 name=fldpass value="%%FLDPASS%%"></td><td><SPAN class=f11>&nbsp;&nbsp;%%PASSDESC%% <i>"admin"</i>).</SPAN></td>
</tr></table><table width=750 border=0 cellspacing=1 cellpadding=0><tr bgcolor="#CCCCCC" height=20 align=center><td colspan=2><SPAN class=f11><b>%%THEADER%%</b></SPAN></td>
</tr><tr bgcolor="#EEEEEE" height=20 align=left><td width=438 valign=top><select TABINDEX=6 name=fldtzone class=list428>
<option value="-12">(GMT -12:00 hours) Eniwetok, Kwajalein</option>
<option value="-11">(GMT -11:00 hours) Midway Island, Samoa</option>
<option value="-10">(GMT -10:00 hours) Hawaii</option>
<option value="-9">(GMT -9:00 hours) Alaska</option>
<option value="-8">(GMT -8:00 hours) Pacific Time (US &amp; Canada), Tijuana</option>
<option value="-7">(GMT -7:00 hours) Mountain Time (US &amp; Canada), Arizona</option>
<option value="-6">(GMT -6:00 hours) Central Time (US &amp; Canada), Mexico City</option>
<option value="-5">(GMT -5:00 hours) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>
<option value="-4">(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-3">(GMT -3:00 hours) Brassila, Buenos Aires, Georgetown, Falkland Is</option>
<option value="-2">(GMT -2:00 hours) Mid-Atlantic, Ascension Is., St. Helena</option>
<option value="-1">(GMT -1:00 hours) Azores, Cape Verde Islands</option>
<option value="0" selected>(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>
<option value="1">(GMT +1:00 hours) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>
<option value="2">(GMT +2:00 hours) Cairo, Helsinki, Kaliningrad, South Africa</option>
<option value="3">(GMT +3:00 hours) Baghdad, Riyadh, Moscow, Nairobi</option>
<option value="4">(GMT +4:00 hours) Abu Dhabi, Baku, Muscat, Tbilisi</option>
<option value="5">(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="6">(GMT +6:00 hours) Almaty, Colombo, Dhaka, Novosibirsk</option>
<option value="7">(GMT +7:00 hours) Bangkok, Hanoi, Jakarta</option>
<option value="8">(GMT +8:00 hours) Beijing, Hong Kong, Perth, Singapore, Taipei</option>
<option value="9">(GMT +9:00 hours) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>
<option value="10">(GMT +10:00 hours) Canberra, Guam, Melbourne, Sydney</option>
<option value="11">(GMT +11:00 hours) Magadan, New Caledonia, Solomon Islands</option>
<option value="12">(GMT +12:00 hours) Auckland, Wellington, Fiji, Marshall Island</option>
</select></td><td width="309"><SPAN class=f11>&nbsp;&nbsp;%%TZONEDESC%%</SPAN></td></tr></table></td></tr>
<tr height=20>
<td align=center background="%%RF%%style/%%STYLE%%/image/bg2.gif"><table width="100%" border=0 cellspacing=0 cellpadding=0><tr>
<td align=right width="47%"><a href="admin.php" onclick="return false"><img TABINDEX=7 width=20 height=20 src="%%RF%%style/%%STYLE%%/image/back.gif" title="%%BACK%%" border=0 onclick='FormExt(admin,"step")'></a></td>
<td width="6%">&nbsp;</td><td align=left width="47%"><a href="admin.php" onclick="return false"><img TABINDEX=8 width=20 height=20 src="%%RF%%style/%%STYLE%%/image/go.gif" title="%%NEXT%%" border=0 onclick='FormExt(admin,"step2")'></a></td>
</tr></table></td></tr></table></DIV><br>

SET;
?>