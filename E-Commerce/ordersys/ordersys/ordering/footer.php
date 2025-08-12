</div><div style="padding-left: 5px;">
<p><span style="color:#dcdcdc;">
<?php
if (!($all_affect_items == "no") or ($all_affect_items == "no" and $client == "allowed")){
echo ('
<a>Add </a><a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=item\')">item</a> / <a href="'.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor" onclick="return popitup(\''.$site_url.'index_short.php?function=show_insert_form&amp;table_name=vendor\')">vendor</a> || <a href="orders.php">View/adjust past orders</a> / <a href="vendors.php">vendors</a> || ');}
echo('<a href="vendorsites.htm">Vendor websites</a> || <a href="'.$parentsite_url.'help/help.htm" onclick="return popitup(\''.$parentsite_url.'help/help.htm\')">Help</a> || <a href="admin.php">Admin</a> || <a href="'.$parentsite_url.'index.php">'.$parentsite_name.'</a> || <a href="'.$mainsite_url.'">'.$mainsite_name.'</a>');
echo ('</span></p></div><p><span style="color:#ffffff; font-size:2px;">Keywords - '.$meta_keywords.' - '.$meta_description.' - '.$meta_generator.'</span></p>
</body>
</html>
');
?>
