<title>...</title></head><body>
<form name="y" action="<?php print $PHP_SELF;?>" method="post" onsubmit="return check2form(<?php print "'$lang[36]'";?>)">
<input type="hidden" name="image" value="<?php print $image; ?>" /><input type="hidden" name="sex" value="m" />
<table width="280" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="5">
<tr class="c"><td><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><?php print $temp_title;?></span></td><td align="right" nowrap="nowrap"><a href="fgtnp.php" title="<?php print $lang[61];?>"><img src="pics/psw.gif" width="17" height="14" border="0" alt="<?php print $lang[61];?>" /></a></td></tr></table></td></tr>
<tr class="a"><td><table width="100%" cellspacing="6">
<tr><td align="right" width="80"><b><?php print $lang[16];?><script type="text/javascript">cheat2validator()</script>:</b></td><td align="right" width="220"><input size="25" type="text" name="name" class="ia" maxlength="<?php print $flood[1];?>" value="<?php print $name;?>" /></td></tr>
<tr><td align="right" width="80"><b><?php print $lang[56];?>:</b></td><td align="right" width="220"><input size="25" type="<?php print $pass_field;?>" name="pass" class="ia" maxlength="25" value="" /></td></tr>
<tr><td align="right" width="80"><b><?php print $lang[57];?>:</b></td><td align="right" width="220"><input size="25" type="text" name="mail" class="ia" maxlength="50" value="<?php print $mail;?>" /></td></tr>
<tr><td align="right" colspan="2"><b><?php print $lang[58];?>:</b><br />
<textarea cols="32" rows="6" name="text" class="ia" onfocus="if(this.value=='-'){this.value=''}"><?php print $text;?></textarea></td></tr></table>
<table width="100%" cellspacing="6"><tr><td width="40%" nowrap="nowrap">
<a href="#" onclick="attach_image('<?php print $lang[43];?>');return false"><img src="pics/im.gif" width="20" height="15" align="left" alt="<?php print $lang[41];?>" border="0" /></a>
<b><a href="#" onclick="add_bb('[b][/b]');return false" title="<?php print $lang[12];?>">B</a></b> <b><a href="#" onclick="add_bb('[i][/i]');return false" title="<?php print $lang[13];?>">I</a></b> <b><a href="#" onclick="add_bb('[c][/c]');return false" title="<?php print $lang[14];?>">C</a></b>
</td><td width="60%" align="right">
<input type="button" value="<?php print $lang[38];?>" class="ib" onclick="preview_user()" />
<input type="submit" value="<?php print $lang[3];?>" class="ib" />
</td></tr></table></td></tr></table></td></tr></table><?php print $name_exists;?></form></body></html>