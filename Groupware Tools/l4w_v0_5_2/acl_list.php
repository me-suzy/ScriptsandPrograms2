<?php

   	/*=====================================================================
	// $Id: acl_list.php,v 1.5 2005/07/08 19:45:59 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

    // --- Standard Inclusions --------------------------------------
    include ("inc/pre_include_standard.inc.php");
    $section = $_REQUEST['section_value'];

    // --- PHPGACL --------------------------------------------------
    require_once ('extern/phpgacl/gacl.class.php');
    require_once ('extern/phpgacl/gacl_api.class.php');
    require_once ('inc/acl.inc.php');
    
    $gacl_api = new gacl_api($gacl_options);
    $gacl     = new gacl    ($gacl_options);

    // --- Security -------------------------------------------------
    if (!$gacl->acl_check($section, 'Edit Permissions', 'Person', $_SESSION['user_id']))
        die ("Security alert in ".__FILE__);

    // --- pagestats ------------------------------------------------
    set_page_stats("acl_list.php");
    
    // --- Header ---------------------------------------------------
    include ("inc/header.inc.php");
	
	// --- init -----------------------------------------------------
    $acl_count = 0;

    $headline          = "<img src='".$img_path."permissions.gif' align=top alt='permissions'>&nbsp;";
    $headline         .= translate ("permissions").":&nbsp;";

?>

    <script language=javascript>
    
        function add_permission () {
            link = "gacl_manager.php?action=edit_permissions&section=<?=$section?>";
            link += "&return_to=<?=$_REQUEST['return_to']?>";
            document.location.href=link;
        }    

    </script>

    <form name='formular' action='gacl_manager.php' method='post'>
    <?php
        include ("modules/common/headline.php");
    ?>
    <input type=hidden name='section'    value='<?=$section?>'>
    <input type=hidden name='action'     value='delete_permissions'>
    <input type=hidden name='return_to'  value='<?=$_REQUEST['return_to']?>'>

<?php

    $query = '
        SELECT DISTINCT a.id 
        FROM '.TABLE_PREFIX.'gacl_acl a 
        LEFT JOIN '.TABLE_PREFIX.'gacl_aco_map ac ON ac.acl_id=a.id 
        LEFT JOIN '.TABLE_PREFIX.'gacl_aro_map ar ON ar.acl_id=a.id 
        LEFT JOIN '.TABLE_PREFIX.'gacl_axo_map ax ON ax.acl_id=a.id 
        WHERE ac.section_value="'.$section.'" 
        ORDER BY a.id ASC';
    $acl_res = mysql_query ($query);
    $acl_seq = '';
    while ($acl_row = mysql_fetch_array($acl_res)) {
        //$acls[]   = $acl_row['id'];
        $acl_seq .= $acl_row['id'].",";
    }
    $acl_seq = substr ($acl_seq,0,-1);
    
    $acl_meta_query = '
        SELECT a.id,x.name,a.allow,a.enabled,a.return_value,a.note,a.updated_date 
        FROM '.TABLE_PREFIX.'gacl_acl a 
        INNER JOIN '.TABLE_PREFIX.'gacl_acl_sections x ON x.value=a.section_value 
        WHERE a.id IN ('.$acl_seq.')';

    $acls    = array ();
    $acl_meta_res = mysql_query ($acl_meta_query);
    while ( $row = mysql_fetch_array($acl_meta_res)) {
			$acls[$row[0]] = array(
				'id' => $row[0],
				// 'section_id' => $section_id,
				'section_name' => $row[1],
				'allow' => (bool)$row[2],
				'enabled' => (bool)$row[3],
				'return_value' => $row[4],
				'note' => $row[5],
				'updated_date' => $row[6],
				
				'aco' => array(),
				'aro' => array(),
				'aro_groups' => array(),
				'axo' => array(),
				'axo_groups' => array()
			);
	}

	foreach ( array('aco', 'aro') as $type ) {
		$query = '
			SELECT	a.acl_id,o.name,s.name
			FROM	'.TABLE_PREFIX.'gacl_'. $type .'_map a
			INNER JOIN	'.TABLE_PREFIX.'gacl_'. $type .' o ON (o.section_value=a.section_value AND o.value=a.value)
			INNER JOIN	'.TABLE_PREFIX.'gacl_'. $type . '_sections s ON s.value=a.section_value
			WHERE	a.acl_id IN ('. $acl_seq . ')';

		$res = mysql_query ($query);
        while ( $row = mysql_fetch_array($res) ) {
		    list($acl_id, $name, $section_name) = $row;
		
    		if ( isset($acls[$acl_id]) ) {
	    		$acls[$acl_id][$type][$section_name][] = $name;
	    		//$acls[$acl_id][$type][$section_name]['value']       = $value;
		    }
	    }
	}

	foreach ( array('aro') as $type ) {
		$query = '
			SELECT	a.acl_id,g.name
			FROM	'.TABLE_PREFIX.'gacl_'. $type .'_groups_map a
			INNER JOIN	'.TABLE_PREFIX.'gacl_'. $type .'_groups g ON g.id=a.group_id
			WHERE	a.acl_id IN ('. $acl_seq. ')';
		
		$res = mysql_query ($query);
        while ( $row = mysql_fetch_array($res) ) {
    		list($acl_id, $name) = $row;
				
			if ( isset($acls[$acl_id]) ) {
				$acls[$acl_id][$type .'_groups'][] = $name;
			}
		}
	}
                
?>

<table class="adminframe">
<tr><td>

	<table border=0>
	    <tr>
        <td colspan='6'>
            <a href='<?=$_REQUEST['return_to']?>'>[Back]</a>&nbsp;
            <a href='modules/users/index.php?command=show_users'>[View Users]</a>&nbsp;
            <!--<a href='groupmanager.php'>[View Groups]</a>&nbsp;-->
        </td>
    </tr>
    <tr>
        <td colspan=5><hr></td>
	</tr>
    <tr>
        <td>
            Section: 
        </td>
        <td colspan=4>
            <?=$section?>
            <!--<select name='section'>
                <option value='Use Leads4web' selected>Use Leads4web</option>            
                <option value='Usermanager'  <?php if ($section == "Usermanager")  echo "selected"?>>Usermanager</option>            
                <option value='Groupmanager' <?php if ($section == "Groupmanager") echo "selected"?>>Groupmanager</option>            
            </section>-->
        </td>
	</tr>
    <tr>
        <td colspan=5><hr></td>
	</tr>
    <tr>
        <th align=left width='60'><?=translate('rule')?></th>
        <th align=left width='180'><?=translate('user')?> / <?=translate('group')?></th>
        <th align=left width='100'><?=translate('access')?></th>
        <th align=left width='180'><?=translate('action')?></th>
        <th>#</th>
	</tr>
    <?php
        foreach ($acls AS $acl) {
            $acl_count++;
            echo "<tr><td colspan=5><hr></td></tr>\n"; 
            echo "<tr>"; 
            echo "<td valign=top><b>".$acl['id'].".)</b></td>";
            echo "<td valign=top>";
            $found_person = false;
            foreach ($acl['aro'] AS $key => $value) {
                echo "<b>".$key."</b><br>";
                foreach ($value AS $k => $v) {
                    $found_person = true;
                    echo "&nbsp;&rarr;&nbsp;".$v." (".get_username_by_login ($v).")<br>";
                }
            }
            foreach ($acl['aro_groups'] AS $key => $value) {
                if ($found_person)
                    echo "and<br>";
                echo "<b>Group: </b>".$value."<br>";
            }
            echo "</td>\n"; 
            echo "<td valign=top>";
            if ((bool)$acl['allow'])
                echo "<b>is allowed to</b>";
            else
                echo "<b>is not allowed to</b>";
            echo "</td>\n"; 
            echo "<td>";
            foreach ($acl['aco'] AS $key => $value) {
                //echo "<b>".$key."</b><br>";
                foreach ($value AS $k => $v)
                    echo "&nbsp;&rarr;&nbsp;".$v."<br>";
            }
            echo "</td>\n"; 
            echo "<td><input type=checkbox name='acl_".$acl['id']."'></td>\n"; 
            echo "</tr>\n";
        }    
    ?>
    <tr>
        <td colspan=5><hr></td>
	</tr>
    <tr>
        <td colspan=5 align=right>
            <!--<a href='gacl_manager.php?action=edit_permissions&section=<?=$section?>'>Add Permissions</a>-->
            <input type=button name=add    value='<?=translate('add permission')?>' onClick='javascript:add_permission();'>&nbsp;
            <input type=submit name=delete value='<?=translate('delete selected')?>'>
        </td>
	</tr>
	</table>

</td></tr>
</table>
<input type=hidden name='acl_count'  value='<?=$acl_count?>'>
</form>

</html>
</body>