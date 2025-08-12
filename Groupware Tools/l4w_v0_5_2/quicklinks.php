<?php

   	/*=====================================================================
	// $Id: quicklinks.php,v 1.1 2004/11/04 08:30:47 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

    // --- Standard Inclusions --------------------------------------
    include ("inc/pre_include_standard.inc.php");
       
	// --- pagestats ------------------------------------------------
	set_page_stats(__FILE__);

    // --- Header ---------------------------------------------------
	include ("inc/header.inc.php");

    $headline  = "<img src='".$img_path."quicklinks.gif' align=middle>&nbsp;";
	$headline .= translate ("quicklinks");	
	
	include ("modules/common/headline.php");

    $quicklinks_res = get_quicklinks ();
    $num            = mysql_num_rows ($quicklinks_res);

?>

<table>
<tr><td>

    <table width='100%'>
    <tr><th colspan=2><b><?=translate ('all quicklinks')?> (<?=$num?>):</b></th></tr>
    <?php
        while ($row = mysql_fetch_array ($quicklinks_res)) {
            (trim ($row['name']) != '') ? $name = $row['name'] : $name = '?';
            $type = translate ($row['object_type']);
            echo "<tr>";
            echo "<td>".$type."</td>";
            echo "<td><a href='".$row['link']."'>".$name."</a></td>";
            echo "</tr>\n";    
        }    
    ?>
    </table>

</td></tr>
</table>

</body>
</html>

