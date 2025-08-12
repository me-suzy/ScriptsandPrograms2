<?php

   	/*=====================================================================
	// $Id: page_stats_mining.php,v 1.1 2004/10/20 12:21:12 carsten Exp $
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
	include ("header.inc");

    $headline  = "<img src='".$img_path."contacts.gif' align=top>&nbsp;";
	$headline .= translate ("page_stats");	
	
	include ("leiste.php");
?>

<table class="adminframe">
<tr><td>

    <table width='100%'>
    <tr>
        <td colspan=2>
            ---
        </td>
    </tr>
    
<?php

    $month_start = date("Ym")."01"; // first day in this month

    $query       = "
        SELECT user, page, day, month(day) AS mymonth, SUM(counter) AS counter
        FROM page_stats 
        WHERE day<'$month_start' 
        GROUP BY page, mymonth, user
        ORDER BY day DESC
        ";
    echo $query."<br>";
    $res = mysql_query ($query);
    while ($row = mysql_fetch_array ($res)) {
        $insert_query = "INSERT INTO page_stats (user, page, day, month, year, counter) VALUES
                         (
                            '".$row['user']."',
                            '".$row['page']."',
                            '',
                            '".date("Y").$row['mymonth']."',
                            '',
                            '".$row['counter']."'
                         )";
        echo $insert_query."<br>";
                         
        mysql_query ($insert_query);
        echo mysql_error();

        $del_query = "DELETE FROM page_stats WHERE day<'$month_start'";        
        mysql_query ($del_query);
        echo mysql_error();
        
    }
    echo "<tr><td colspan=2>Done...</td></tr>\n";

?>

    </table>

</td></tr>
</table>

</body>
</html>

