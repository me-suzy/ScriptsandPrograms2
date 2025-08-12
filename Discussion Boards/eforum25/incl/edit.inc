<?php if(isset($memname)){$readonly='readonly="readonly" ';}else{$readonly='';}?>
<title><?php print $temp_title;?>...</title></head>
<body><form name="y" action="<?php print $PHP_SELF;?>" method="post" onsubmit="return check1form(<?php print "'$lang[36]'";?>)"><input type="hidden" name="desc" value="1" />
<input type="hidden" name="mem_pic" value="w1" /><input type="hidden" name="f" value="<?php print $f;?>" /><input type="hidden" name="image" value="<?php if(isset($image)){$image=strip_tags($image);print $image;}?>" /><input type="hidden" name="key" value="<?php if(!isset($key)||eregi('[^a-f_0-9]',$key)){$key='';}print $key;?>" />
<table width="400" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td class="q">
<table width="100%" border="0" cellspacing="1" cellpadding="<?php print $cellpadding;?>">
<tr class="c"><td><table width="100%" cellpadding="1" cellspacing="0"><tr><td nowrap="nowrap"><span class="c"><?php print $temp_title;?></span></td><td align="right" nowrap="nowrap"><b><a href="<?php print $temp_link;?>" style="color:#ffffff"><?php print $lang[4];?></a></b></td></tr></table></td></tr>
<tr class="a"><td><table cellspacing="8">
<tr><td align="right" width="80" nowrap="nowrap"><b><?php print $lang[20];?>:</b></td><td width="320"><input size="44" type="text" name="title" class="ia" maxlength="<?php print $flood[2];?>" value="<?php if(isset($title)){$title=strip_tags($title);print $title;}?>" /></td></tr>
<tr><td align="right" width="80" nowrap="nowrap"><b><?php print $lang[16];?><script type="text/javascript">cheat1validator()</script>:</b></td><td width="320"><input size="44" type="text" name="name" class="ia" maxlength="<?php print $flood[1];?>" value="<?php if(isset($name)){$name=strip_tags($name);print $name;}?>" <?php print $readonly;?>/></td></tr>
<tr><td>&nbsp;</td><td><?php include "incl/extr2.inc";?></td></tr>
<tr><td align="right" colspan="2"><textarea cols="56" rows="16" name="text" class="ia"><?php if(isset($text)){print $text;}?></textarea>
<input type="hidden" name="line" value="<?php if(isset($line)){$line=(int)$line;print $line;}?>" /><input type="hidden" name="topic" value="<?php if(!isset($topic)||eregi('[^0-9]',$topic)){$topic='';}print $topic;?>" /></td></tr>
<tr><td colspan="2"><table width="100%" cellpadding="0" cellspacing="0"><tr><td><?php include "incl/extr1.inc";?></td><td align="right"><input type="button" value="<?php print $lang[38];?>" class="ib" onclick="preview_post()" /> <input type="submit" value="<?php print $lang[3];?>" class="ib" /></td></tr></table></td></tr>
</table></td></tr></table></td></tr></table></form><div class="w" align="center"><?php print $pass_reason;?></div></body></html>
