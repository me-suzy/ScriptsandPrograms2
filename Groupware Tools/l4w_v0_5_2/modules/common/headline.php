<?php

  /**
    * $Id: headline.php,v 1.11 2005/07/20 20:20:40 carsten Exp $
    *
    * The common headline shown at top of each page in l4w_main frame
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */
?>

<!--<table class="headline" border="0" cellpadding=0 cellspacing=0 width='100%'>
<tr class="headline">-->
<table class="headline" cellspacing=0 width="100%">
    <tr class="headline">
	    <td class="headline">
            &nbsp;<?php echo $headline ?>
        </td>
        <td align="right" class="headline" width=50 
            background='<?=$img_path?>leiste_bg.jpg'>&nbsp;<?php if (isset ($headline_middle)) echo $headline_middle; else echo "&nbsp;"?></td>
        <td align=right class="headline" 
            background='<?=$img_path?>leiste_bg.jpg'><?php if (isset($headline_right)) echo $headline_right; else echo "&nbsp;"?></td>
<?php 
	//if (isset ($echo_form_closed) && $echo_form_closed) 
	//	echo "</form>\n"; 
?>
</tr>
</table>