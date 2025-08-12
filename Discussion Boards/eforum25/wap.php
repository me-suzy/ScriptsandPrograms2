<?php 
include "config.php";
include "incl/wml.inc";
?><head>
<meta http-equiv="Cache-Control" content="must-revalidate" forua="true" /> 
<meta http-equiv="Cache-Control" content="no-cache" forua="true" />
<meta http-equiv="Cache-control" content="max-age=2" forua="true" />
</head><card id="forums" title="Forums">
<?php
if(count($forum_name)<2||count($forum_data)<2||count($forum_desc)<2){
print "<onevent type=\"onenterforward\"><go href=\"wnd.php?f=$f&amp;u=$random\" /></onevent><p><a href=\"wnd.php?f=$f&amp;u=$random\">Forward...</a></p>";
}else{
for($i=0;$i<count($forum_data);$i++){
$display_name=abc_only($forum_name[$i],0);
if(strlen($display_name)<3){$display_name='Forum #'.($i+1);}
print "<p><b><a href=\"wnd.php?f=$i&amp;u=$random\">$display_name</a></b></p>";}
}?>
</card></wml>