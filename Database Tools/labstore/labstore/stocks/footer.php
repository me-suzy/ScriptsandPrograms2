</div><div style="padding-left: 5px;">
<p><span style="color:#dcdcdc;">
<?php
////
if (!($all_affect_items == "no") or ($all_affect_items == "no" and $client == "allowed")){
echo ('<a>Add </a>');
// depending on activated modules (config.php)
$to_show = '';
foreach ($modules_array as $key=>$value)
{
$to_show .= '<a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name='.$value[4].'" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name='.$value[4].'\')" title="'.$value[2].'">';
if ($table == $value[4]){$to_show .= '<b>'.$value[0].'</b>';}
else {$to_show .= $value[0];}
$to_show .= '</a>/'; 
}
$to_show = substr($to_show, 0, -1); // remove last /
echo ($to_show);}
////
echo(' || <a href="'.$parentsite_url.'help/help.htm" onclick="return popitup(\''.$parentsite_url.'help/help.htm\')">Help</a> || <a href="'.$parentsite_url.'admin.php">Admin</a> || <a href="'.$parentsite_url.'index.php">'.$parentsite_name.'</a> || <a href="'.$mainsite_url.'index.php">'.$mainsite_name.'</a></span></p></div>');
// keywords (invisible)
echo ('<p><span style="color:#ffffff; font-size:2px;">Keywords - '.$meta_keywords.' - '.$meta_description.' - '.$meta_generator.'</span></p>
</body>
</html>
');
?>
