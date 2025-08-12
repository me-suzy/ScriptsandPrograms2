<body bgcolor="#FFFFFF" text="#000000" link="#0000CC" alink="#0000CC" vlink="#0000CC">
<?php $IW->Client_GetHeader (); ?>
<center>
<table border=0 cellpadding=5 cellspacing=0 width=750>
<td valign=top width=200 height=450>
<?php include "navbar/".$IW->Client_GetNav ()."/navbar.inc"; ?>
</td>
<td valign=top width=550 height=450>
<?php $IW->Client_GetContent ($D,$V); ?>
</td>
</tr>
</table>
</center>
<?php $IW->Client_GetFooter (); ?>
</body>