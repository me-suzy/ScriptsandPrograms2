<title>...</title></head><body>
<form name="y" action="log.php" method="post"><input type="hidden" name="f" value="<?php print $f;?>" />
<table width="280" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr class="c"><td><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><?php print $lang[67];?></span></td><td align="right" nowrap="nowrap"><a href="fgtnp.php" title="<?php print $lang[61];?>"><img src="pics/psw.gif" width="17" height="14" border="0" alt="<?php print $lang[61];?>" /></a></td></tr></table></td></tr>
<tr class="a"><td><table width="100%" cellspacing="6">
<tr><td align="right" width="80"><b><?php print $lang[16];?>:</b></td><td align="right" width="220"><input size="25" type="text" name="enter_name" class="ia" maxlength="<?php print $flood[1];?>" value="" /></td></tr>
<tr><td align="right" width="80"><b><?php print $lang[56];?>:</b></td><td align="right" width="220"><input size="25" type="<?php print $pass_field;?>" name="enter_pass" class="ia" maxlength="25" value="" /></td></tr>
<tr><td colspan="2" align="right"><input type="submit" value="<?php print $lang[3];?>" class="ib" />
</td></tr></table></td></tr></table></td></tr></table></form></body></html>