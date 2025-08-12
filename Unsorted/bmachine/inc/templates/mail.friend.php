<?php


// DONOT TOUCH THE WORD " echo <<<EOF " below!
// Enter your contents between <<<EOF and EOF;

echo <<<EOF

<br>
<p align="center"><B><FONT color="black" face=Verdana size=2>Send the article 
&quot;$ar[title]&quot; to a friend</FONT></B></p>
<form name="contact" method="post" action="mail.php">
<input type="hidden" name="in" value="$_GET[id]">
<input type="hidden" name="act" value="mail">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="511">
<tr><td width="106"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Your 
Name</font></span></p>
</td>
<td width="405">
<p><input type="text" name="name" size="24"></p>
</td></tr><tr><td width="106" height="40" valign="top"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Your 
Email</font></span></p>
</td>
<td width="405" height="40" valign="top">
<p><input type="text" name="email" size="24"></p>
</td></tr><tr><td width="106"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Friend's 
email 1</font></span></p>
</td>
<td width="405">
<p><input type="text" name="e1" size="24"> &nbsp;<font size="1" face="Verdana"><i>[ 
Enter atleast 1 email ]</i></font></p>
</td></tr><tr><td width="106"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Friend's 
email 2</font></span></p>
</td>
<td width="405">
<p><input type="text" name="e2" size="24"></p>
</td></tr><tr><td width="106"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Friend's 
email 3</font></span></p>
</td>
<td width="405">
<p><input type="text" name="e3" size="24"></p>
</td></tr><tr><td width="106"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Friend's 
email 4</font></span></p>
</td>
<td width="405">
<p><input type="text" name="e4" size="24"></p>
</td></tr><tr><td width="106"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Friend's 
email 5</font></span></p>
</td>
<td width="405">
<p><input type="text" name="e5" size="24"></p>
</td></tr><tr><td width="106" valign="top"><p><span style="font-size:8pt;"><font face="Verdana" color="black">Your 
Comments<br>[optional]</font></span></p>
</td>
<td width="405">
<p><textarea name="comments" rows="11" cols="47"></textarea></p>
</td></tr><tr><td width="106"><p>&nbsp;</p></td>
<td width="405">
<p><input type="submit" value="  Send  " style="font-family:Verdana;"></p>
</td></tr></table></form><br>

EOF;
// DONOT TOUCH THE WORD " EOF; " above!
?>