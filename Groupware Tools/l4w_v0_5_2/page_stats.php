<?php

   	/*=====================================================================
	// $Id: page_stats.php,v 1.2 2004/11/04 08:31:34 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

    // --- Standard Inclusions --------------------------------------
    include ("inc/pre_include_standard.inc.php");
   
    // --- PHPGACL --------------------------------------------------
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    require_once ('inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);
    
	// --- pagestats ------------------------------------------------
	set_page_stats(__FILE__);

    // --- Header ---------------------------------------------------
	include ("inc/header.inc.php");

    $headline  = "<img src='".$img_path."stats.gif' align=top>&nbsp;";
	$headline .= translate ("page_stats");	
	
	include ("modules/common/headline.php");
?>

<table class="adminframe">
<tr><td>

    <table width='100%'>
    <tr>
        <td colspan=2>
            <input type=submit name='submit' value='Data Mining' onClick='javascript:window.location.href="page_stats_mining.php"'>
        </td>
    </tr>
    <tr><th colspan=2><b>This month, all Users:</b></th></tr>
<?php

    $month_start = date("Ym")."01";
    $month_end   = date("Ymd", mktime (0,0,0, date("m")+1, 1, date ("Y")));

    // $this Month, all Users
    //SELECT user, page, day, month, year, SUM(counter) AS count
    $query = "
    SELECT page, SUM(counter) AS count
         FROM page_stats 
        WHERE day>='$month_start' AND day < '$month_end'
        GROUP BY page
        ORDER BY count DESC
    ";
    $res = mysql_query ($query);

    while ($row = mysql_fetch_array($res)) {
        if (!isset ($max)) $max = $row['count'];
        $page = explode ("\\", $row['page']);
        $page = "<b>".$page[count($page)-1]."</b>";
        echo "<tr>";
        echo "<td>".$page."</td>";
        echo "<td>";
        
        //echo $row['page'].": ".$row['count']."<br>";
        $query = "
            SELECT user, page, day, month, year, counter AS count
            FROM page_stats 
            WHERE page='".mysql_escape_string ($row['page'])."' AND
                  day>='$month_start' AND 
                  day < '$month_end' 
            ORDER BY user ASC
        ";
        $detail_res = mysql_query ($query);
        echo mysql_error();
        while ($detail_row = mysql_fetch_array ($detail_res)) {
            $faktor = round (500 * $detail_row['count'] / $max);
            $title  = "User: ".get_username_by_user_id($detail_row['user'])." (".$detail_row['count']."x)";
            echo "<img src='".$img_path."bar_left.gif' alt='pic' height='8' width='5'>";
            echo "<img src='".$img_path."bar.gif' alt='pic' height='8' width='$faktor' title='$title'>";
            echo "<img src='".$img_path."bar_right.gif' alt='pic' height='8' width='5'>";
        }
        echo "</td>";    
        echo "</tr>";    
    }    

?>

    </table>

</td></tr>
</table>

</body>
</html>

