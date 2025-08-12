<?php if(isset($members_only)&&$members_only==1){die('<p>forbidden...</p></card></wml>');}?>
<p>Name: <input name="name" />
Subject: <input name="title" />
Message: <input name="text" />
</p><p><anchor>SEND
<go method="get" href="<?php print $PHP_SELF;?>">
<postfield name="name" value="$(name)" />
<postfield name="title" value="$(title)" />
<postfield name="text" value="$(text)" />
<postfield name="f" value="<?php print $f; ?>" />
<postfield name="u" value="<?php print $random; ?>" />
<?php if(isset($t)){print "<postfield name=\"t\" value=\"$t\" />";}?></go></anchor></p>