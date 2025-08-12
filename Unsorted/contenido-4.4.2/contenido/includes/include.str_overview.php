<?php

/******************************************
* File      :   includes.str_overview.php
* Project   :   Contenido
* Descr     :   Displays structure
*
* Author    :   Olaf Niemann
* Created   :   28.03.2003
* Modified  :   28.03.2003
*
* © four for business AG
*****************************************/

$debug = false;

$tmp_area = "str";

$sess->register("remakeStrTable");
$sess->register("StrTableClient");
$sess->register("StrTableLang");

if ($force == 1) {
    $remakeStrTable = true;
}

if ($StrTableClient != $client)
{
    $remakeStrTable = true;	
}

if ($StrTableLang != $lang)
{
	$remakeStrTable = true;
}

$StrTableClient = $client;
$StrTableLang = $lang;

if (!isset($idcat) )  $idcat  = 0;
if (!isset($action) ) $action = 0;

function buildTree (&$rootItem, &$items)
{
	global $nextItem;
	
	while ($item_list = each($items))
	{
		
		list($key, $item) = $item_list;
		
		unset($newItem);
		$newItem = new TreeItem($item['name'], $item['idcat'], true);
	
		$newItem->custom['idtree'] = $item['idtree'];
		$newItem->custom['level'] = $item['level'];
		$newItem->custom['idcat'] = $item['idcat'];
		$newItem->custom['idtree'] = $item['idtree'];
		$newItem->custom['parentid'] = $item['parentid'];
		$newItem->custom['preid'] = $item['preid'];
		$newItem->custom['postid'] = $item['postid'];
		$newItem->custom['visible'] = $item['visible'];
		$newItem->custom['idtplcfg'] = $item['idtplcfg'];
		$newItem->custom['public'] = $item['public'];
		
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


if ( $perm->have_perm_area_action($area) ) {

    $sql = "SELECT
                idtree, A.idcat, level, name, parentid, preid, postid, visible, public, idtplcfg
            FROM
                ".$cfg["tab"]["cat_tree"]." AS A,
                ".$cfg["tab"]["cat"]." AS B,
                ".$cfg["tab"]["cat_lang"]." AS C
            WHERE
                A.idcat     = B.idcat AND
                B.idcat     = C.idcat AND
                C.idlang    = '".$lang."' AND
                B.idclient  = '".$client."'
            ORDER BY
                idtree";
                


    # Debug info
    if ( $debug ) {

        echo "<pre>";
        echo $sql;
        echo "</pre>";

    }

    $db->query($sql);

	$items = array();
	while ($db->next_record())
	{
		$entry = array();
		$entry['idtree'] = $db->f("idtree");
		$entry['idcat'] = $db->f("idcat");
		$entry['level'] = $db->f("level");
		$entry['name'] = $db->f("name");
		$entry['parentid'] = $db->f("parentid");
		$entry['preid'] = $db->f("preid");
		$entry['postid'] = $db->f("postid");
		$entry['visible'] = $db->f("visible");
		$entry['public'] = $db->f("public");
		$entry['idtplcfg'] = $db->f("idtplcfg");
	
		array_push($items, $entry);
	}




	if (!is_string($serializedRootStrItem))
	{
		$rootStrItem = new TreeItem("root",-1);
		buildTree($rootStrItem, $items);
		
		$sess->register("serializedRootStrItem");
	} else {
		$rootStrItem = unserialize($serializedRootStrItem);

		if ($remakeStrTable == true)
		{
			$list = array();
			$rootStrItem->getExpandedList($list);
			
			unset($rootStrItem);
			$rootStrItem = new TreeItem("root",-1);
			buildTree($rootStrItem,$items);
			
			foreach ($list as $key=>$value)
			{
				$rootStrItem->markExpanded($value);
			}
			
			$remakeStrTable = false;
		}
	}
		
	if (is_numeric($collapse))
	{
		$rootStrItem->markCollapsed($collapse);
	}
	
	if (is_numeric($expand))
	{
		$rootStrItem->markExpanded($expand);
	}	
	
	$serializedRootStrItem = serialize($rootStrItem);
		
	$objects = array();
	
	$rootStrItem->traverse($objects);

    # Reset Template
    $tpl->reset();
    $tpl->set('s', 'SID', $sess->id);

	// We don't want to show our root
	unset($objects[0]);
	
    foreach ($objects as $key=>$value) {

            // check if there area any permission for this $idcat   in the mainarea 6 (=str) and there subareas
            if ( 
                $perm->have_perm_area_action($tmp_area, "str_newtree") ||
                $perm->have_perm_area_action($tmp_area, "str_newcat") ||
                $perm->have_perm_area_action($tmp_area, "str_makevisible") ||
                $perm->have_perm_area_action($tmp_area, "str_makepublic") ||
                $perm->have_perm_area_action($tmp_area, "str_deletecat") ||
                $perm->have_perm_area_action($tmp_area, "str_moveupcat") ||
                $perm->have_perm_area_action($tmp_area, "str_movesubtree") ||
                $perm->have_perm_area_action($tmp_area, "str_renamecat") ||
                $perm->have_perm_area_action("str_tplcfg", "str_tplcfg") ||
                $perm->have_perm_item("6", $value->id) ){

                if ( $value->custom['level'] == 0 && $value->custom['preid'] != 0 ) {

                    $tpl->set('d', 'BGCOLOR', '#FFFFFF');
                    $tpl->set('d', 'CATEGORY', '&nbsp;');
                    $tpl->set('d', 'INDENT', '3px');
                    $tpl->set('d', 'RENAMEBUTTON', '&nbsp;');
                    $tpl->set('d', 'NEWCATEGORYBUTTON', '&nbsp;');
                    $tpl->set('d', 'VISIBLEBUTTON', '&nbsp;');
                    $tpl->set('d', 'PUBLICBUTTON', '&nbsp;');
                    $tpl->set('d', 'DELETEBUTTON', '&nbsp;');
                    $tpl->set('d', 'UPBUTTON', '&nbsp;');
                    $tpl->set('d', 'COLLAPSE', '&nbsp;');
                    $tpl->set('d', 'TPLNAME', '&nbsp;');
                    $tpl->set('d', 'MOVEBUTTON', '&nbsp;');
                    $tpl->set('d', 'TEMPLATEBUTTON', '&nbsp;');
                    $tpl->next();
                    
                }

                $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];
                
                $tpl->set('d', 'BGCOLOR', $bgcolor);
   
                $tpl->set('d', 'COLLAPSE', $value->getExpandCollapseButton());
                //if ($action == 30 && $perm->have_perm_area_action_item($tmp_area, "str_renamecat", $idcat)) {
                if ( $action === "str_renamecat" && !isset($newcategoryname) ) {

                    if ($value->custom['idcat'] != $idcat) {

                        $spaces = "";
                        
                        $tpl->set('d', 'INDENT', ($value->custom['level'] * 15) . "px");
                        $tpl->set('d', 'CATEGORY', $value->name);
                        
                    } else {

                        $html = '<a name="renamethis">
                                    <table cellspacing="0" cellpaddin="0" border="0">
                                    
                                        <form name="renamecategory" method="post" action="'.$sess->url("main.php?frame=$frame").'">

                                        <input type="hidden" name="contenido" value="'.$sess->id.'" />
                                        <input type="hidden" name="action" value="str_renamecat" />
                                        <input type="hidden" name="idcat" value="'.$idcat.'" />

                                        <tr>
                                            <td class="text_medium"><input class="text_medium" type="text" name="newcategoryname" value="'.$value->name.'"></td>
                                            <td>&nbsp;
                                                <a href="javascript:history.back()"><img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'but_cancel.gif" border="0"></a>
                                                <input type="image" src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'but_ok.gif">
                                            </td>
                                        </tr>

                                        </form>

                                    </table>';


                        $html .=  " <script language=\"JavaScript\">
                                    <!--
                                    document.renamecategory.newcategoryname.focus();
                                    //-->
                                    </script>";

						$tpl->set('d', 'INDENT', ($value->custom['level'] * 15) . "px");
                        $tpl->set('d', 'CATEGORY', $html);
                    }
                    
                } else {
                    $spaces = "";

                    $tpl->set('d', 'INDENT', ($value->custom['level'] * 15) . "px");
                    $tpl->set('d', 'CATEGORY', $value->name);
                }
                
                $template = $tpl->getTemplateNameFromTPLCFG($value->custom['idtplcfg']);

                if ($template == "")
                {
                    $template = i18n("--- None ---");
                }
                    
                $tpl->set('d', 'TPLNAME', $template);	
                ///////// "Kategorie umbenennen" Button
                if($perm->have_perm_area_action($tmp_area, "str_renamecat") || $perm->have_perm_area_action_item($tmp_area, "str_renamecat", $value->id)) {
                        $tpl->set('d', 'RENAMEBUTTON', "<a class=action href=\"".$sess->url("main.php?area=$area&action=str_renamecat&frame=$frame&idcat=".$value->id)."#renamethis\"><img src=\"".$cfg["path"]["images"]."but_rename.gif\" border=\"0\" title=\"".i18n("Rename category")."\" alt=\"".i18n("Rename category")."\"></a>");
                }else{
                        $tpl->set('d', 'RENAMEBUTTON', '&nbsp;');
                }

                ///////// "neue Unterkategorie" Button
                if($perm->have_perm_area_action($tmp_area, "str_newcat") || $perm->have_perm_area_action_item($tmp_area, "str_newcat", $value->id)) {
                    $tpl->set('d', 'NEWCATEGORYBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_newcat&frame=$frame&idcat=".$value->id)."#newcathere\"><img src=\"".$cfg["path"]["images"]."folder_new.gif\" border=\"0\" title=\"".i18n("New category")."\" alt=\"".i18n("New category")."\"></a>");
                }else{
                   $tpl->set('d', 'NEWCATEGORYBUTTON', '&nbsp;');
                }


#                if ($value->id == $idcat) { echo "<a name=clickedhere>"; }


                if($perm->have_perm_area_action($tmp_area, "str_makevisible") || $perm->have_perm_area_action_item($tmp_area,"str_makevisible",$value->id)) {
                    if ($value->custom['visible'] == 1) {
                        $tpl->set('d', 'VISIBLEBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_makevisible&frame=$frame&idcat=".$value->id."&visible=".$value->custom['visible'])."#clickedhere\"><img src=\"images/online.gif\" width=\"11\" height=\"12\" border=\"0\" title=\"".i18n("Make offline")."\" alt=\"".i18n("Make offline")."\"></a>");
                    } else {
                        $tpl->set('d', 'VISIBLEBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_makevisible&frame=$frame&idcat=".$value->id."&visible=".$value->custom['visible'])."#clickedhere\"><img src=\"images/offline.gif\" width=\"11\" height=\"12\" border=\"0\"  title=\"".i18n("Make online")."\"  alt=\"".i18n("Make online")."\"></a>");
                    }
                } else {
                    $tpl->set('d', 'VISIBLEBUTTON', '&nbsp;');
                }


                if($perm->have_perm_area_action($tmp_area, "str_makepublic") || $perm->have_perm_area_action_item($tmp_area,"str_makepublic",$value->id)) {
                    if ($value->custom['public'] == 1) {
                        $tpl->set('d', 'PUBLICBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_makepublic&frame=$frame&idcat=".$value->id."&public=".$value->custom['public'])."#clickedhere\"><img src=\"images/folder.gif\" height=13 width=15 border=0 title='".i18n("Protect category")."' alt='".i18n("Protect category")."'></a>");
                    } else {
                        $tpl->set('d', 'PUBLICBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_makepublic&frame=$frame&idcat=".$value->id."&public=".$value->custom['public'])."#clickedhere\"><img src=\"images/folder_locked.gif\" height=13 width=15 border=0 title='".i18n("Unprotect category")."' alt='".i18n("Unprotect category")."'></a>");
                    }
                } else {
                   $tpl->set('d', 'PUBLICBUTTON', '&nbsp;');
                }

                $hasChildren = strNextDeeper($value->id);
                $hasArticles = strHasArticles($value->id);
                if(($hasChildren == 0) && ($hasArticles == false) &&($perm->have_perm_area_action($tmp_area, "str_deletecat") || $perm->have_perm_area_action_item($tmp_area,"str_deletecat",$value->id))) {
                    
                    $message = sprintf(i18n("Do you really want to delete the following category:<br><br><b>%s</b>"),$value->name);
                    $delete = '<a href="javascript://" onclick="box.confirm(\''.i18n("Delete category").'\', \''.$message.'\', \'deleteStr('.$value->id.','.$value->custom['parentid'].')\')">'."<img src=\"".$cfg["path"]["images"]."delete.gif\" title=\"".i18n("Delete category")."\" alt=\"".i18n("Delete category")."\" border=\"0\"></a>";
//                   $tpl->set('d', 'DELETEBUTTON', "<a class=action href=\"".$sess->url("main.php?area=$area&action=str_deletecat&frame=$frame&idcat=".$value->id."&parentid=".$value->custom['parentid'])."#deletethis\"><img src=\"".$cfg["path"]["images"]."delete.gif\" alt=\"Kategorie löschen\" border=\"0\"></a>");

                    $tpl->set('d', 'DELETEBUTTON', $delete);
                } else {
                    $message = i18n("No permission");
                    
                    if ($hasChildren)
                    {
                        $message = i18n("One or more subtrees are existing, unable to delete");
                    }

                    if ($hasArticles)
                    {
                        $message = i18n("One or more articles are existing, unable to delete");
                    }
                    if ($hasChildren && $hasArticles)
                    {
                        $message = i18n("One or more subtrees and one or more articles are existing, unable to delete.");
                    }
                    
                    
                    $tpl->set('d', 'DELETEBUTTON', '<img src="'.$cfg["path"]["images"].'delete_inact.gif" alt="'.$message.'" title="'.$message.'">');
                }

                if($perm->have_perm_area_action($tmp_area, "str_moveupcat") || $perm->have_perm_area_action_item($tmp_area,"str_moveupcat",$value->id)) {

                    $rand = rand();

                    if ($value->custom['parentid']==0 && $value->custom['preid']==0) {
                        $tpl->set('d', 'UPBUTTON', '&nbsp;');
                    } else {
                        $tpl->set('d', 'UPBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_moveupcat&frame=$frame&idcat=".$value->id."&rand=$rand")."#clickedhere\"><img src=\"images/folder_moveup.gif\" border=0 title='".i18n("Move category up")."' alt='".i18n("Move category up")."'></a>");
                    }
                } else {
                    $tpl->set('d', 'UPBUTTON', '&nbsp;');
                }
                
                if (($action === "str_movesubtree") && (!isset($parentid_new)))
                {
                    if($perm->have_perm_area_action($tmp_area, "str_movesubtree") || $perm->have_perm_area_action_item($tmp_area,"str_movesubtree",$value->id))
                    {
                        if ($value->id == $idcat)
                        {
                            $tpl->set('d', 'MOVEBUTTON', "<a name=#movesubtreehere><a href=\"".$sess->url("main.php?area=$area&action=str_movesubtree&frame=$frame&idcat=$idcat&parentid_new=0")."\"><img src=\"".$cfg["path"]["images"]."but_move_subtree_main.gif\" height=16 width=16 border=0 title='".i18n("Move tree")."' alt='".i18n("Move tree")."'></a>");
                        } else {
                                $allowed = strMoveCatTargetallowed($value->id, $idcat);
                                if ($allowed == 1)
                                {
                                       $tpl->set('d', 'MOVEBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_movesubtree&frame=$frame&idcat=$idcat&parentid_new=".$value->id)."\"><img src=\"".$cfg["path"]["images"]."but_move_subtree_target.gif\" height=16 width=16 border=0 title='".i18n("Place tree here")."' alt='".i18n("Place tree here")."'></a>");
                                } else {
                                       $tpl->set('d', 'MOVEBUTTON', '&nbsp;');
                                }
                        }
                    } else {
                        $tpl->set('d', 'MOVEBUTTON', '&nbsp;');
                    }
                } else {
                    if($perm->have_perm_area_action($tmp_area, "str_movesubtree") || $perm->have_perm_area_action_item($tmp_area,"str_movesubtree",$value->id)) {
                        $tpl->set('d', 'MOVEBUTTON', "<a href=\"".$sess->url("main.php?area=$area&action=str_movesubtree&frame=$frame&idcat=".$value->id)."#movesubtreehere\"><img src=\"".$cfg["path"]["images"]."but_move_subtree.gif\" height=16 width=16 border=0 title='".i18n("Move tree")."' alt='".i18n("Move tree")."'></a>");
                    }else{
                        $tpl->set('d', 'MOVEBUTTON', '&nbsp;');
                    }
                }

                if ($perm->have_perm_area_action("str_tplcfg", "str_tplcfg") || $perm->have_perm_area_action_item("str_tplcfg","str_tplcfg",$value->id))
                {
                    $tpl->set('d', 'TEMPLATEBUTTON', "<a href=\"".$sess->url("main.php?area=str_tplcfg&frame=$frame&idcat=".$value->id."&idtpl=".$value->custom['idtplcfg'])."\"><img src=\"".$cfg["path"]["images"]."but_cat_conf.gif\" title=\"".i18n("Configure category")."\" alt=\"".i18n("Configure category")."\" border=\"0\"></a>");
                } else {
                    $tpl->set('d', 'TEMPLATEBUTTON', "&nbsp;");
                }
                $tpl->next();


                if ($action === "str_newcat" && !isset($categoryname) && $value->id==$idcat && $perm->have_perm_area_action_item($tmp_area,"str_newcat",$idcat)) {

                    $html  = "<FORM style=\"margin:0px\" name=\"addsubcategory\" method=post action=\"".$sess->url("main.php?frame=$frame")."\">
                                <a class=\"text_medium\" name=newcathere>";
                    $html .= $sess->hidden_session();
                    $html .= "    <INPUT type=hidden name=action VALUE=\"str_newcat\">
                                  <input type=hidden name=send value=1>
                                  <INPUT type=hidden name=idcat VALUE=\"".$value->id."\">
                                  <INPUT type=text class=\"text_medium\"  name=categoryname>";
                    $html2 = "<a href='javascript:history.back()'><img src=\"".$cfg["path"]["images"]."but_cancel.gif\" border=0></a>
                                  <INPUT type=image src=\"".$cfg["path"]["images"]."but_ok.gif\" border=0>
                                 </FORM>";

                    $html2 .= "  <script language=\"JavaScript\">
                                <!--
                                    document.addsubcategory.categoryname.focus();
                                //-->
                                </script>";

                    $tpl->set('d', 'BGCOLOR', $bgcolor);
                    $tpl->set('d', 'CATEGORY', $html);
                    $tpl->set('d', 'INDENT', '3px');
                    $tpl->set('d', 'RENAMEBUTTON', '&nbsp;');
                    $tpl->set('d', 'NEWCATEGORYBUTTON', $html2);
                    $tpl->set('d', 'VISIBLEBUTTON', '&nbsp;');
                    $tpl->set('d', 'TPLNAME', '&nbsp;');
                    $tpl->set('d', 'PUBLICBUTTON', '&nbsp;');
                    $tpl->set('d', 'DELETEBUTTON', '&nbsp;');
                    $tpl->set('d', 'UPBUTTON', '&nbsp;');
                    $tpl->set('d', 'MOVEBUTTON', '&nbsp;');
                    $tpl->set('d', 'TEMPLATEBUTTON', '&nbsp;');
                    $tpl->set('d', 'COLLAPSE', '');
                    $tpl->next();
                
                }
                 
            }//end if -> perm

    }

     if (($treename == "") && ($action==="str_newtree") && ($perm->have_perm_area_action($tmp_area,"str_newtree"))) {
            $html  = "<a name=newtreehere><FORM style=\"margin:0px\" name=\"newtree\" method=post action=\"".$sess->url("main.php?frame=$frame")."\">
                     ";
            $html .= $sess->hidden_session();
            $html .= "<INPUT type=hidden name=action VALUE=\"str_newtree\">
                      <INPUT type=text name=treename>";
            $html2 = "<a href='javascript:history.back()'><img src=\"".$cfg["path"]["images"]."but_cancel.gif\" border=0></a>
                      <INPUT type=image src=\"".$cfg["path"]["images"]."but_ok.gif\" border=0>
                      </FORM>";

            $html2 .= "<script language=\"JavaScript\">
                          document.newtree.treename.focus();
                      </script>";

            $tpl->set('d', 'BGCOLOR', $bgcolor);
            $tpl->set('d', 'CATEGORY', $html);
            $tpl->set('d', 'INDENT', '3px');
            $tpl->set('d', 'RENAMEBUTTON', '&nbsp;');
            $tpl->set('d', 'NEWCATEGORYBUTTON', $html2);
            $tpl->set('d', 'TPLNAME', '&nbsp;');
            $tpl->set('d', 'VISIBLEBUTTON', '&nbsp;');
            $tpl->set('d', 'PUBLICBUTTON', '&nbsp;');
            $tpl->set('d', 'DELETEBUTTON', '&nbsp;');
            $tpl->set('d', 'UPBUTTON', '&nbsp;');
            $tpl->set('d', 'MOVEBUTTON', '&nbsp;');
            $tpl->set('d', 'COLLAPSE', '');
            $tpl->set('d', 'TEMPLATEBUTTON', '&nbsp;');
            $tpl->next();
     }


    # Neuer Baum link
    if ($perm->have_perm_area_action($tmp_area,"str_newtree")) {
        $tpl->set('s', 'NEWTREE', "<a class=action href=\"".$sess->url("main.php?area=$area&action=str_newtree&frame=$frame")."#newtreehere\">".i18n("Create new tree")."</a>");
    } else {
        $tpl->set('s', 'NEWTREE', '');
    }

    # Generate template
    $tpl->generate($cfg['path']['templates'] . $cfg['templates']['str_overview']);

}


?>
