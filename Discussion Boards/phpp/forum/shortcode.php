<?
$funcsarray = array("b", "i", "u", "quote", "url", "img", "list");
for($i = 0; $i < count($funcsarray); $i++) {
echo "<input type=\"button\" value=\" [$funcsarray[$i]] \" onclick=\"javascript:addcode('[$funcsarray[$i]]');\"/>";
if($funcsarray[$i] != "list") echo "<input type=\"button\" value=\" [/$funcsarray[$i]] \" onclick=\"javascript:addcode('[/$funcsarray[$i]]');\"/><br/>";
}

echo "<br/><br/>
<a href=\"javascript:addcode(':) ');\"><img src=\"gfx/smileys/smile.gif\" alt=\":)\"/></a> 
<a href=\"javascript:addcode(':( ');\"><img src=\"gfx/smileys/sad.gif\" alt=\":(\"/></a> 
<a href=\"javascript:addcode(';) ');\"><img src=\"gfx/smileys/wink.gif\" alt=\";)\"/></a> 
<a href=\"javascript:addcode(':\'( ');\"><img src=\"gfx/smileys/cry.gif\" alt=\":'(\"/></a><br/>
<a href=\"javascript:addcode(':D ');\"><img src=\"gfx/smileys/grin.gif\" alt=\":D\"/></a> 
<a href=\"javascript:addcode(':P ');\"><img src=\"gfx/smileys/tongue.gif\" alt=\":P\"/></a> 
<a href=\"javascript:addcode(':S ');\"><img src=\"gfx/smileys/confused.gif\" alt=\":S\"/></a> 
<a href=\"javascript:addcode('8) ');\"><img src=\"gfx/smileys/cool.gif\" alt=\"8)\"/></a><br/>
<a href=\"javascript:addcode(':$ ');\"><img src=\"gfx/smileys/blush.gif\" alt=\":$\"/></a> 
<a href=\"javascript:addcode(':| ');\"><img src=\"gfx/smileys/line.gif\" alt=\":|\"/></a> 
<a href=\"javascript:addcode(':@ ');\"><img src=\"gfx/smileys/angry.gif\" alt=\":@\"/></a> 
<a href=\"javascript:addcode(':O ');\"><img src=\"gfx/smileys/shock.gif\" alt=\":O\"/></a>";
?>