<?php

   	/*=====================================================================
	// $Id: groupmanager.php,v 1.2 2005/04/03 06:30:09 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

    // --- Standard Inclusions --------------------------------------
	include ("inc/pre_include_standard.inc.php");

    // --- PHPGACL --------------------------------------------------
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    //require_once('/admin/gacl_admin.inc.php');
    require_once ('inc/acl.inc.php');

    $gacl_api = new gacl_api($gacl_options);
    $gacl     = new gacl    ($gacl_options);

    // --- Security -------------------------------------------------
    if (!$gacl->acl_check('Groupmanager','Show','Person',$_SESSION['user_id']))
        die ("Security alert in ".__FILE__);

	// --- pagestats ------------------------------------------------
	set_page_stats("groupmanager.php");

    // --- Header ---------------------------------------------------
    include ("header_wo_doctype.inc");
	
	// --- Headline -------------------------------------------------
    list ($headline, $headline_right) = restricted_access_headline (
                                            $gacl, 'Groupmanager', 'Show','Permissions','Groupmanager');
	include ("leiste.php");

?>

<form name='formular' method='post'>
<table valign=top width='100%' style="border-width:1px; border-style:solid; border-color:#FF0000;">
<tr><td>

	<table border=0>
	    <tr>
        <td colspan='6'>
            <a href='usermanager.php'>[View Users]</a>&nbsp;
        </td>
    </tr>
    <tr>
	    <th>Group</th>
	    <th> # Members</th>
	    <th colspan=3>Action</th>
	</tr>
    <?php
    
        $query = '
			SELECT		a.id, a.name, a.value, count(b.aro_id)
			FROM		'.TABLE_PREFIX.'gacl_aro_groups a
			LEFT JOIN	'.TABLE_PREFIX.'gacl_groups_aro_map b ON b.group_id=a.id
			GROUP BY	a.id,a.name,a.value';
		$grp_res = mysql_query ($query);
		echo mysql_error();
		$group_data = array();
		
			while($row = mysql_fetch_array ($grp_res)) {
				$group_data[$row[0]] = array(
					'name' => $row[1],
					'value' => $row[2],
					'count' => $row[3]
				);
			}
    
        $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');
        $root_group       = $gacl_api->get_root_group_id();
        foreach ($formatted_groups AS $key => $value) {
            echo "<tr>";
            echo "<td><b>$value</b></td>";
            echo "<td align=right><a href='gacl_manager.php?action=show_members&use_group=$key'>".$group_data[$key]['count']."</a></td>";
            echo "<td><a href='gacl_manager.php?action=add_group&use_group=$key'>Add</a></td>";
            if ($key != $root_group) {
                echo "<td><a href='gacl_manager.php?action=edit_group&use_group=$key'>Edit</a></td>";
                echo "<td><a href='gacl_manager.php?action=delete_group&use_group=$key'>Delete</a></td>";
            }
            else
                echo "<td colspan=2>&nbsp;</td>";
            echo "</tr>\n";
       }    
       
    ?>

	</table>

</td></tr>
</table>
</form>
<?php include ("inc/timer.inc");?>
</html>
</body>