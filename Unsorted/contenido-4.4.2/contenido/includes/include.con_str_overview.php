<?php

/******************************************
* File      :   includes.con_str_overview.php
* Project   :   Contenido
* Descr     :   Displays the structure in
*               the left frame.
*
* Author    :   Jan Lengowski
* Created   :   26.01.2003
* Modified  :   24.04.2003
*
* © four for business AG
*****************************************/

if ( !is_object($db2) ) $db2 = new DB_Contenido;

$sess->register("remakeCatTable");
$sess->register("CatTableClient");
$sess->register("CatTableLang");

if ($CatTableClient != $client)
{
	$remakeCatTable = true;
}

if ($CatTableLang != $lang)
{
	$remakeCatTable = true;
}

$CatTableClient = $client;
$CatTableLang = $lang;

$sql = "SELECT
			a.preid AS preid,
			a.postid AS postid,
			a.parentid AS parentid,
            c.idcat AS idcat,
            c.level AS level,
            b.name AS name,
            b.public AS public,
            b.visible AS online,
            d.idtpl AS idtpl
        FROM
            ".$cfg["tab"]["cat"]." AS a,
            ".$cfg["tab"]["cat_lang"]." AS b,
            ".$cfg["tab"]["cat_tree"]." AS c
        LEFT JOIN
            ".$cfg["tab"]["tpl_conf"]." AS d
            ON d.idtplcfg = b.idtplcfg
        WHERE
            a.idclient  = '".$client."' AND
            b.idlang    = '".$lang."' AND
            c.idcat     = b.idcat AND
            b.idcat     = a.idcat
        ORDER BY
            c.idtree ASC";

$db->query($sql);

if (isset($online))
{
	$remakeCatTable = true;
}

if (isset($public))
{
	$remakeCatTable = true;
}

if (isset($idtpl))
{
	$remakeCatTable = true;
}

if (isset($force))
{
	$remakeCatTable = true;
}

function buildTree (&$rootItem, &$items)
{
	global $nextItem;
	
	while ($item_list = each($items))
	{
		
		list($key, $item) = $item_list;
		
		unset($newItem);
		$newItem = new TreeItem($item['name'], $item['idcat'],true);
	
		$newItem->custom['visible'] = $item['visible'];
		$newItem->custom['online'] = $item['visible'];
		$newItem->custom['idtpl'] = $item['idtpl'];
		$newItem->custom['public'] = $item['public'];
		$newItem->custom['level'] = $item['level'];
		$newItem->custom['parentid'] = $item['parentid'];
		$newItem->custom['public'] = $item['public'];
		$newItem->custom['preid'] = $item['preid'];
		$newItem->custom['postid'] = $item['postid'];
		
		$nextItem = $items[$key+1];
		$lastItem = $items[$key-1];
		
		
		$rootItem->addItem($newItem);
	

		if ($nextItem['level'] > $item['level'])
		{
			$oldRoot = $rootItem;
			buildTree($newItem, $items);
			$rootItem = $oldRoot;
		}
		
		if ($nextItem['level'] < $item['level'])
		{
			return;
		}
		
	}
	
}	

$items = array();
while ($db->next_record())
{
	$entry = array();
	
	$entry['idcat'] = $db->f("idcat");
	$entry['level'] = $db->f("level");
	$entry['name'] = $db->f("name");
	$entry['public'] = $db->f("public");
	$entry['online'] = $db->f("online");
	$entry['idtpl'] = $db->f("idtpl");
	$entry['visible'] = $db->f("online");
	$entry['preid'] = $db->f("preid");
	$entry['postid'] = $db->f("postid");
	$entry['parentid'] = $db->f("parentid");
	
	array_push($items, $entry);
}


if (!is_string($serializedRootCatItem))
{
	$rootCatItem = new TreeItem("root",-1);
	buildTree($rootCatItem, $items);
	
	$sess->register("serializedRootCatItem");
} else {
	$rootCatItem = unserialize($serializedRootCatItem);
	
	if ($remakeCatTable == true)
		{
			$list = array();
			$rootCatItem->getExpandedList($list);
			
			unset($rootStrItem);
			$rootCatItem = new TreeItem("root",-1);
			buildTree($rootCatItem,$items);
			
			foreach ($list as $key=>$value)
			{
				$rootCatItem->markExpanded($value);
			}
			
			$remakeCatTable = false;
		}
}
	
if (is_numeric($collapse))
{
	$rootCatItem->markCollapsed($collapse);
}

if (is_numeric($expand))
{
	$rootCatItem->markExpanded($expand);
}	

if ($expand == "all")
{
	$rootCatItem->expandAll(-1);
}

if ($collapse == "all")
{
	$rootCatItem->collapseAll(-1);
}

$serializedRootCatItem = serialize($rootCatItem);
	
$objects = array();

$rootCatItem->traverse($objects);

$tpl->reset();

//if ( $perm->have_perm_item(6, 0) ) {

    # create javascript multilink
    $tmp_mstr = '<a href="javascript://" onclick="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';

    $mstr = sprintf($tmp_mstr, 'right_bottom',
                               $sess->url("main.php?area=$area&frame=4&idcat=0"),
                               'right_top',
                               $sess->url("main.php?area=$area&frame=3&idcat=0"),
                               'Lost and Found');

    $img_folder = '<img src="images/folder_on_error.gif">';

    $tpl->set('d', 'IMAGE',     $img_folder);
    $tpl->set('d', 'CFGDATA',   $cfgdata);
    $tpl->set('d', 'BGCOLOR',   $bgcolor);
    $tpl->set('d', 'INDENT',    $indent);
    $tpl->set('d', 'CAT',       $mstr);
    $tpl->set('d', 'COLLAPSE', '&nbsp;');
    $tpl->next();

    $tpl->set('d', 'COLLAPSE', '');
    $tpl->set('d', 'IMAGE', '');
    $tpl->set('d', 'CAT', '&nbsp;');
    $tpl->next();

    $selflink = "main.php";
    $expandlink = $sess->url($selflink . "?area=$area&frame=$frame&expand=all");
    $collapselink = $sess->url($selflink . "?area=$area&frame=$frame&collapse=all");
    $collapseimg = '<a href="'.$collapselink.'" alt="'.i18n("Close all categories").'" title="'.i18n("Close all categories").'"><img src="images/but_minus.gif" border="0"></a>';
    $expandimg = '<a href="'.$expandlink.'" alt="'.i18n("Open all categories").'" title="'.i18n("Open all categories").'"><img src="images/but_plus.gif" border="0"></a>';
    $allLinks = $expandimg .'<img src="images/spacer.gif" width="3">'.$collapseimg;

    $tpl->set('d', 'IMAGE',     '');
    $tpl->set('d', 'CFGDATA',   '');
    $tpl->set('d', 'BGCOLOR',   '#ffffff');
    $tpl->set('d', 'INDENT',    0);
    $tpl->set('d', 'CAT',       '&nbsp;');
    $tpl->set('d', 'COLLAPSE', $allLinks);
    $tpl->next();

//}

unset($objects[0]);
foreach ($objects as $key=>$value) {

    if (
         $perm->have_perm_area_action("con", "con_makestart") ||
         $perm->have_perm_area_action("con", "con_makeonline") ||
         $perm->have_perm_area_action("con", "con_deleteart") ||
         $perm->have_perm_area_action("con", "con_tplcfg_edit") ||
         $perm->have_perm_area_action("con", "con_makecatonline") ||
         $perm->have_perm_area_action("con", "con_changetemplate") ||
         $perm->have_perm_area_action("con_editcontent", "con_editart") ||
         $perm->have_perm_area_action("con_editart", "con_edit") ||
         $perm->have_perm_area_action("con_editart", "con_newart") ||
         $perm->have_perm_area_action("con_editart", "con_saveart") ||
         $perm->have_perm_area_action_item("con", "con_makestart",$value->id) ||
         $perm->have_perm_area_action_item("con", "con_makeonline",$value->id) ||
         $perm->have_perm_area_action_item("con", "con_deleteart",$value->id) ||
         $perm->have_perm_area_action_item("con", "con_tplcfg_edit",$value->id) ||
         $perm->have_perm_area_action_item("con", "con_makecatonline",$value->id) ||
         $perm->have_perm_area_action_item("con", "con_changetemplate",$value->id) ||
         $perm->have_perm_area_action_item("con_editcontent", "con_editart",$value->id) ||
         $perm->have_perm_area_action_item("con_editart", "con_edit",$value->id) ||
         $perm->have_perm_area_action_item("con_editart", "con_newart",$value->id) ||
         $perm->have_perm_area_action_item("con_editart", "con_saveart",$value->id)) {

		if ($value->custom['parentid'] == 0)
		{			
            #$tpl->set('d', 'COLLAPSE', '');
			#$tpl->set('d', 'IMAGE', '');
			#$tpl->set('d', 'CAT', '&nbsp;');
			#$tpl->next();
		}
		
        $idcat = $value->id;
        $level = $value->level - 1;
        $name = $value->name;

        # Indent for every level
        $cnt = $value->level - 1;
        $indent = 0;

		$tpl->set('d', 'COLLAPSE', $value->getExpandCollapseButton());
        for ($i = 0; $i < $cnt; $i ++) {
            # 15 px for every level
            $indent += 12;
        }

        # create javascript multilink
        $tmp_mstr = '<a href="javascript://" onclick="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';

        $mstr = sprintf($tmp_mstr, 'right_top',
                                   $sess->url("main.php?area=$area&frame=3&idcat=$idcat&idtpl=$idtpl"),
                                   'right_bottom',
                                   $sess->url("main.php?area=$area&frame=4&idcat=$idcat&idtpl=$idtpl"),
                                   $name);

        $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];

        # Create cfgdata string
        $idtpl = ( $value->custom['idtpl'] != '' ) ? $value->custom['idtpl'] : 0;

        if ($perm->have_perm_area_action_item("con", "con_changetemplate",$value->id) ||
        	$perm->have_perm_area_action("con", "con_changetemplate")) {
      		
            $changetemplate = 1;
            
      	} else {
      		$changetemplate = 0;
      		
      	}
      	
      	
      	if ($perm->have_perm_area_action_item("con", "con_makecatonline",$value->id) ||
        		    $perm->have_perm_area_action("con", "con_makecatonline"))
       	{
       		$onoffline = 1;
       	} else {
       		$onoffline = 0;
       	}
       	
       	if ($perm->have_perm_area_action_item("con", "con_makepublic",$value->id) ||
        		    $perm->have_perm_area_action("con", "con_makepublic"))
		{
			$makepublic = 1;
		} else {
			$makepublic = 0;
		}
       	
       	/* Build cfgdata string */
        $cfgdata = $idcat."-".$idtpl."-".$value->custom['online']."-".$value->custom['public']."-".
        		   $changetemplate ."-".
        	       $onoffline ."-".
        	       $makepublic;       	

        # Select the appropriate folder-
        # image depending on the structure
        # properties
        $sql2 = "SELECT
                    c.is_start AS is_start,
                    a.online AS online
                FROM
                    ".$cfg["tab"]["art_lang"]." AS a,
                    ".$cfg["tab"]["art"]." AS b,
                    ".$cfg["tab"]["cat_art"]." AS c
                WHERE
                    a.idlang = ".$lang." AND
                    a.idart = b.idart AND
                    b.idclient = '".$client."' AND
                    b.idart = c.idart AND
                    c.idcat = '".$idcat."'";

        $db2->query($sql2);

        $no_start   = true;
        $no_online  = true;

        while ( $db2->next_record() ) {

            if ( $db2->f("is_start") == 1 ) {
                $no_start = false;
            }

            if ( $db2->f("online") == 1 ) {
                $no_online = false;
            }
        }

        if ( $value->custom['online'] == 1 ) {
            # Category is online

            if ( $value->custom['public'] == 0 ) {
                # Category is locked
                if ( $no_start || $no_online ) {
                    # Error found
                    $tmp_img = "folder_on_error_locked.gif";

                } else {
                    # No error found
                    $tmp_img = "folder_on_locked.gif";

                }

            } else {
                # Category is public
                if ( $no_start || $no_online ) {
                    # Error found
                    $tmp_img = "folder_on_error.gif";

                } else {
                    # No error found
                    $tmp_img = "folder_on.gif";

                }
            }

        } else {
            # Category is offline

            if ( $value->custom['public'] == 0 ) {
                # Category is locked
                if ( $no_start || $no_online ) {
                    # Error found
                    $tmp_img = "folder_off_error_locked.gif";

                } else {
                    # No error found
                    $tmp_img = "folder_off_locked.gif";

                }

            } else {
                # Category is public
                if ( $no_start || $no_online ) {
                    # Error found
                    $tmp_img = "folder_off_error.gif";

                } else {
                    # No error found
                    $tmp_img = "folder_off.gif";

                }
            }
        }

        $img_folder = sprintf('<img src="images/%s" width="15" height="13" alt="">', $tmp_img);

        $tpl->set('d', 'IMAGE',     $img_folder);
        $tpl->set('d', 'CFGDATA',   $cfgdata);
        $tpl->set('d', 'BGCOLOR',   $bgcolor);
        $tpl->set('d', 'INDENT',    $indent);
        $tpl->set('d', 'CAT',       $mstr);
        $tpl->next();

    } // end if have_perm

} // end while

$tpl->generate($cfg['path']['templates'] . $cfg['templates']['con_str_overview']);

?>

