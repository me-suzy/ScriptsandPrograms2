<?php

   	/*=====================================================================
	// $Id: usermanager.php,v 1.2 2004/11/04 08:31:34 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	include ("inc/pre_include_standard.inc.php");

	// pagestats
	set_page_stats($user_id, "groupstructure2.php");

    $start_group = var_include ("start_group", "GET");

    // --- PHPGACL --------------------------------------------------
    require_once('extern/phpgacl/gacl.class.php');
    require_once('extern/phpgacl/gacl_api.class.php');
    //require_once('/admin/gacl_admin.inc.php');
    
    $gacl_options = array(
    						'debug' => false,
    						'items_per_page' => 100,
    						'max_select_box_items' => 100,
    						'max_search_return_items' => 200,
    						'db_type' => 'mysql',
    						'db_host' => $db_host,
    						'db_user' => $db_user,
    						'db_password' => $db_pass,
    						'db_name' => $db_name,
    						'db_table_prefix' => 'gacl_',
    						'caching' => FALSE,
    						'force_cache_expire' => TRUE,
    						'cache_dir' => '/tmp/phpgacl_cache',
    						'cache_expire_time' => 600
    					);
    
    $gacl_api = new gacl_api($gacl_options);

    include ("header_wo_doctype.inc");
	$headline = "<font color='yellow'>".get_from_texte ("Users", $language)." III</font>";


	include ("leiste.php");

?>

<form name='formular' method='post'>
<table valign=top width='100%'>
<tr><td>

	<table border=0>
    <tr>
        <td colspan='6'>
            <a href='gacl_manager.php?action=add_user&referrer=usermanager.php'>[New User]</a>&nbsp;
            <a href='groupmanager.php'>[View Groups]</a>&nbsp;
        </td>
    </tr>
	<tr>
	    <th>Login</th>
	    <th>Vorname</th>
	    <th>Nachname</th>
	    <th>Groups</th>
	    <th colspan=2>Action</th>
	</tr>
    <?php
    
        $query = '
			 SELECT section_value,value,name, vorname,nachname FROM gacl_aro 
			 LEFF JOIN users ON users.id=value
			 WHERE hidden=0 
			 ORDER BY section_value,order_value,name
			 ';
		$users_res = mysql_query ($query);
		echo mysql_error();
		
		while($row = mysql_fetch_array ($users_res)) {
            echo "<tr>";
            echo "<td><b>".$row['name']."</b></td>";
            echo "<td><b>".$row['vorname']."</b></td>";
            echo "<td><b>".$row['nachname']."</b></td>";
            echo "<td><b>".$groups."</b></td>";
            //echo "<td><a href='gacl_manager.php?action=edit_group&use_group=$key'>Edit</a></td>";
            //echo "<td><a href='gacl_manager.php?action=del_group&use_group=$key'>Delete</a></td>";
            echo "<td><a href='gacl_manager.php?action=edit_groups&referrer=usermanager.php&use_user=".$row['value']."'>[Edit Users Groups]</a></td>";
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