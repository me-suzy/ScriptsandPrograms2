<p>
<?php
if($parentsite_name != '')
{echo ('<a href="'.$parentsite_url.'">'.$parentsite_name.'</a> | ');}

echo ('<a href="'.$dadabik_main_file.'">'.$site_name.' - data browser</a> | <a href="admin.php">'.$site_name.' - administration home page</a> | <a href="help.htm" onclick="return popitup(\'help.htm\')">Administration help</a> | ');

if($mainsite_name != '')
{echo ('<a href="'.$mainsite_url.'">'.$mainsite_name.'</a>');}
?>
</p>
</body>
</html>