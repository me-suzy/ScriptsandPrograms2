<?php

sess_gc();

$final_output .= <<<FO
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
<td valign="top">
FO;

include("$include_path/site_menu.php");

$final_output .= <<<FO
</td>
</tr>
FO;

$final_output .= <<<FO
<tr>
<td valign="top">
FO;

include("$include_path/online.php");

$final_output .= <<<FO
</td>
</tr>
<tr>
<td valign="top">
FO;

$final_output .= <<<FO
</td>
</tr>
FO;

if($show_site_stats == 1){

$final_output .= <<<FO
<tr>
<td valign="top">
FO;

include("$include_path/stats.php");

$final_output .= <<<FO
</td>
</tr>
FO;

}

$final_output .= <<<FO
</table>
FO;

?>