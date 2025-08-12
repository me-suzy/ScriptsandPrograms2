<?php

$top=<<<TOP
<html><head><title>ActualAnalyzer - Error report</title><meta http-equiv="Content-Type" content="text/html; charset="ISO-8859-1">
</head><body bgcolor="#FFFFFF" text="#000000"><table width=750 border=1 cellspacing=0 cellpadding=0 align=center><tr><td>
<table width=750 border=0 cellspacing=0 cellpadding=0 align=center><tr height=20 bgcolor="#666666"><td><font color="#FFFFFF">&nbsp;&nbsp;<b>Information about error</b></font></td>
</tr><tr height=20><td colspan=2>&nbsp;&nbsp;%%TIME%%</td></tr><tr><td colspan=2><table width=750 border=0 cellspacing=1 cellpadding=0>
<tr height=20 bgcolor="#CCCCCC" align=center><td width=90><b>Level</b></td><td width=90><b>File</b></td>
<td width=90><b>Function</b></td><td align=left><b>&nbsp;&nbsp;Description</b></td></tr>

TOP;

$center=<<<CENTER
<tr height=20 bgcolor="#EEEEEE" align=center><td>%%LEVEL%%</td><td>%%FILE%%</td><td>%%FUNCT%%</td><td align=left>&nbsp;&nbsp;%%DESC%%</td></tr>

CENTER;

$bottom=<<<BOTTOM
</table></td></tr></table></td></tr></table></body></html>

BOTTOM;
?>