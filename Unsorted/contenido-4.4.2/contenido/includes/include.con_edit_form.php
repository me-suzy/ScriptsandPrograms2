<?php

/*****************************************
* File      :   $RCSfile: include.con_edit_form.php,v $
* Project   :   Contenido
* Descr     :   Form for editing the
*               article properties
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   21.01.2003
* Modified  :   $Date: 2003/11/07 10:17:37 $
*
* © four for business AG, www.4fb.de
*
* $Id: include.con_edit_form.php,v 1.25.2.3 2003/11/07 10:17:37 timo.hummel Exp $
******************************************/

$tpl->reset();

if ($action == "con_newart" && $newart != true)
{}
else {
	
    if ($perm->have_perm_area_action($area, "con_edit") ||
        $perm->have_perm_area_action_item($area,"con_edit", $idcat)) {
    
        $sql = "SELECT * FROM ".$cfg["tab"]["cat_art"]." WHERE idart = '".$idart."' AND idcat = '".$idcat."'";
        $db->query($sql);
        $db->next_record();
        
        $tmp_is_start     = $db->f("is_start");
        $tmp_cat_art      = $db->f("idcatart");
    
        $sql = "SELECT * FROM ".$cfg["tab"]["art_lang"]." WHERE idart = '$idart' AND idlang = '$lang'";
    
        $db->query($sql);
        $db->next_record();
    
        if ( $db->f("created") ) {
    
            //****************** this art was edited before ********************
            $tmp_firstedit    = 0;
            $tmp_idartlang    = $db->f("idartlang");
            $tmp_page_title   = stripslashes($db->f("pagetitle"));
            $tmp_idlang       = $db->f("idlang");
            $tmp_title        = $db->f("title");
            $tmp_summary      = $db->f("summary");
            $tmp_created      = $db->f("created");
            $tmp_lastmodified = $db->f("lastmodified");
            $tmp_author       = $db->f("author");
            $tmp_modifiedby	  = $db->f("modifiedby");
            $tmp_online       = $db->f("online");
            $tmp_datestart    = $db->f("datestart");
            $tmp_dateend      = $db->f("dateend");
            $tmp_sort         = $db->f("artsort");
            $tmp_movetocat    = $db->f("time_move_cat");
            $tmp_targetcat    = $db->f("time_target_cat");
            $tmp_onlineaftermove = $db->f("time_online_move");
            $tmp_usetimemgmt = $db->f("timemgmt");
            $tmp_locked = $db->f("locked");
            
            $tmp_redirect_checked  = ($db->f("redirect") == '1') ? 'checked' : '';
            $tmp_redirect_url           = ($db->f("redirect_url") != '0') ? $db->f("redirect_url") : "http://";
            $tmp_external_redirect_checked = ($db->f("external_redirect") == '1') ? 'checked' : '';
    
            $idtplinput          = $db->f("idtplinput");
    
    		if ($tmp_modifiedby == "")
    		{
    			$tmp_modifiedby = $tmp_author;
    		}
    		
    		$col = new InUseCollection;
    		
    		/* Remove all own marks */
    		$col->removeSessionMarks($sess->id);
    		
    		if (($obj = $col->checkMark("article", $tmp_idartlang)) === false)
    		{
    			$col->markInUse("article", $tmp_idartlang, $sess->id, $auth->auth["uid"]);
    			$inUse = false;
        		$disabled = "";						
    		} else {
    			
    			$vuser = new User;
    			$vuser->loadUserByUserID($obj->get("userid"));
    			$inUseUser = $vuser->getField("username");
    			$inUseUserRealName = $vuser->getField("realname");
    			
    			$message = sprintf(i18n("Article is in use by %s (%s)"), $inUseUser, $inUseUserRealName);
    			$notification->displayNotification("warning", $message);			
    			$inUse = true;
    	    	$disabled = 'disabled="disabled"';			
    		}
    		
    		if ($tmp_locked == 1)
    		{
    			$inUse = true;
    			$disabled = 'disabled="disabled"';
    		}
    		
            /*
            $sql = "SELECT keyword FROM ".$cfg["tab"]["keywords"]." WHERE idlang='$lang' AND auto REGEXP '$idart='";
            $db->query($sql);
            
            while ($db->next_record()) {
                $tmp_keyautoart .= $db->f("keyword")."  ";
            }
            
            $sql = "SELECT keyword FROM ".$cfg["tab"]["keywords"]." WHERE idlang='$lang' AND self REGEXP '$idart='";
            $db->query($sql);
            
            while ($db->next_record()) {
                $tmp_keyart .= $db->f("keyword")."  ";
            }
            */
    
        } else {
    
            //***************** this art is edited the first time *************
    
            if (!$idart) $tmp_firstedit    = 1;                //**** is needed when input is written to db (update or insert)
    
            $tmp_idartlang      = 0;
            $tmp_idlang         = $lang;
            $tmp_page_title     = stripslashes($db->f("pagetitle"));
            $tmp_title          = "";
            $tmp_summary        = "";
            $tmp_created        = date("Y-m-d H:i:s");
            $tmp_lastmodified   = date("Y-m-d H:i:s");
            $tmp_author         = "";
            $tmp_online         = "0";
            $tmp_datestart      = "0000-00-00 00:00:00";
            $tmp_dateend        = "0000-00-00 00:00:00";
            $tmp_keyart         = "";
            $tmp_keyautoart     = "";
            $tmp_sort           = "";
    
            $tmp_redirect_checked  = '';
            $tmp_redirect_url           = "http://";
            $tmp_external_redirect = '';
            
        }
    
        $tmp2_created = $tmp_created[8].$tmp_created[9].".".$tmp_created[5].$tmp_created[6].".".$tmp_created[0].$tmp_created[1].$tmp_created[2].$tmp_created[3]."&nbsp;".$tmp_created[11].$tmp_created[12].$tmp_created[13].$tmp_created[14].$tmp_created[15].$tmp_created[16].$tmp_created[17].$tmp_created[18];
        $tmp2_lastmodified = $tmp_lastmodified[8].$tmp_lastmodified[9].".".$tmp_lastmodified[5].$tmp_lastmodified[6].".".$tmp_lastmodified[0].$tmp_lastmodified[1].$tmp_lastmodified[2].$tmp_lastmodified[3]."&nbsp;".$tmp_lastmodified[11].$tmp_lastmodified[12].$tmp_lastmodified[13].$tmp_lastmodified[14].$tmp_lastmodified[15].$tmp_lastmodified[16].$tmp_lastmodified[17].$tmp_lastmodified[18];
        $tmp2_datestart = $tmp_datestart[8].$tmp_datestart[9].".".$tmp_datestart[5].$tmp_datestart[6].".".$tmp_datestart[0].$tmp_datestart[1].$tmp_datestart[2].$tmp_datestart[3];
        $tmp2_dateend = $tmp_dateend[8].$tmp_dateend[9].".".$tmp_dateend[5].$tmp_dateend[6].".".$tmp_dateend[0].$tmp_dateend[1].$tmp_dateend[2].$tmp_dateend[3];
    
    
    
    
        $tpl->set('s', 'ACTION', $sess->url("main.php?area=$area&frame=$frame&action=con_saveart") );
        $tpl->set('s', 'HIDDENSESSION', $sess->hidden_session(true));
        $tpl->set('s', 'TMP_FIRSTEDIT', $tmp_firstedit);
        $tpl->set('s', 'IDART', $idart);
        $tpl->set('s', 'SID', $sess->id);
        $tpl->set('s', 'IDCAT', $idcat);
        $tpl->set('s', 'IDARTLANG', $tmp_idartlang );
    
        $hiddenfields = '<input type="hidden" name="idcat" value="'.$idcat.'">
                         <input type="hidden" name="idart" value="'.$idart.'">
                         <input type="hidden" name="prearea" value="'.$prearea.'">
                         <input type="hidden" name="send" value="1">';
    
        $tpl->set('s', 'HIDDENFIELDS', $hiddenfields);
        
        /* Title */
        $tpl->set('s', 'TITEL', i18n("Title"));
        
    
    
       	$tpl->set('s', 'TITEL-FIELD', '<input '.$disabled.' type="text" class="txt" name="title" value="'.htmlspecialchars($tmp_title).'">');
        
        $tpl->set('s', 'ARTID', "&nbsp;".i18n("Article number").': '.$tmp_cat_art);
        
        /* Author */
        $tpl->set('s', 'AUTOR', i18n("Author"));
        $tpl->set('s', 'AUTOR-ERSTELLUNGS-NAME', $classuser->getRealnameByUserName($tmp_author).'<input type="hidden" class="bb" name="author" value="'.$auth->auth["uname"].'">');
        $tpl->set('s', 'AUTOR-AENDERUNG-NAME', $classuser->getRealnameByUserName($tmp_modifiedby));
    //    $tpl->set('s', 'AUTOR-ERSTELLUNGS-NAME', $tmp_author.'<input type="hidden" class="bb" name="author" value="'.$auth->auth["uname"].'">');
    //    $tpl->set('s', 'AUTOR-AENDERUNG-NAME', $tmp_author);
        /* Created */
        $tmp_erstellt = ($tmp_firstedit == 1) ? '<input type="hidden" name="created" value="'.date("Y-m-d H:i:s").'">' : '<input type="hidden" name="created" value="'.$tmp_created.'">';
        $tpl->set('s', 'ERSTELLT', i18n("Created"));
        $tpl->set('s', 'ERSTELLUNGS-DATUM', $tmp2_created.$tmp_erstellt);
    
        /* Last modified */
        $tpl->set('s', 'LETZTE-AENDERUNG', i18n("Last modified"));
        $tpl->set('s', 'AENDERUNGS-DATUM', $tmp2_lastmodified.'<input type="hidden" name="lastmodified" value="'.date("Y-m-d H:i:s").'">');
    
        /* Redirect */
        $tpl->set('s', 'WEITERLEITUNG', i18n("Redirect"));
        $tpl->set('s', 'CHECKBOX', '<input '.$disabled.' type="checkbox" name="redirect" value="1" '.$tmp_redirect_checked.'>');
        
        /* Redirect - URL */
        $tpl->set('s', 'URL', '<input type="text" '.$disabled.' class="txt" name="redirect_url" value="'.$tmp_redirect_url.'">');
        
        /* Redirect - New window */
        $tpl->set('s', 'CHECKBOX-NEUESFENSTER', '<input type="checkbox" '.$disabled.' id="external_redirect" name="external_redirect" value="1" '.$tmp_external_redirect_checked.'><label for="external_redirect">'.i18n("New Window").'</label>');
    
    	
    
        
        /* Online */
        if ($perm->have_perm_area_action("con", "con_makeonline") ||
            $perm->have_perm_area_action_item("con","con_makeonline", $idcat))
        {
            $tmp_ocheck = ($tmp_online != 1) ? '<input '.$disabled.' type="checkbox" name="online" value="1">' : '<input type="checkbox" '.$disabled.' name="online" value="1" checked="checked">';
        } else {
            $tmp_ocheck = ($tmp_online != 1) ? '<input disabled="disabled" type="checkbox" name="" value="1">' : '<input disabled="disabled" type="checkbox" name="" value="1" checked="checked">';
        }
        
        $tpl->set('s', 'ONLINE', 'Online');
        $tpl->set('s', 'ONLINE-CHECKBOX', $tmp_ocheck);
        
    
        /* Startartikel */
        if ($perm->have_perm_area_action("con", "con_makestart") ||
            $perm->have_perm_area_action_item("con","con_makestart", $idcat))
        {
        	$tmp_start = ($tmp_is_start == 0) ? '<input '.$disabled.' type="checkbox" name="is_start" value="1">' : '<input '.$disabled.' type="checkbox" name="is_start" value="1" checked="checked">';
        } else {
        	$tmp_start = ($tmp_is_start == 0) ? '<input disabled="disabled" type="checkbox" name="" value="1">' : '<input disabled="disabled" type="checkbox" name="" value="1" checked="checked">';
        }
        $tpl->set('s', 'STARTARTIKEL', i18n("Start article"));
        $tpl->set('s', 'STARTARTIKEL-CHECKBOX', $tmp_start);
        
        /* Sortierung */
        $tpl->set('s', 'SORTIERUNG', i18n("Sort key"));
        $tpl->set('s', 'SORTIERUNG-FIELD', '<input type="text" '.$disabled.' class="txt" name="artsort" value="'.$tmp_sort.'">');
    
        /* Category select */
        $tpl2 = new Template;
        $tpl2->set('s', 'ID',       'catsel');
        $tpl2->set('s', 'NAME',     'idcatnew[]');
        $tpl2->set('s', 'CLASS',    'text_medium');
        $tpl2->set('s', 'OPTIONS',  'multiple="multiple" '.$disabled.' size="14" style="width: 250px;scrollbar-face-color:#C6C6D5;scrollbar-highlight-color:#FFFFFF;scrollbar-3dlight-color:#747488;scrollbar-darkshadow-color:#000000;scrollbar-shadow-color:#334F77;scrollbar-arrow-color:#334F77;scrollbar-track-color:#C7C7D6;"');
    
        if ( $tplinputchanged == 1 ) {
            $tmp_idcat_in_art = $idcatnew;
    
        } else {
            $sql = "SELECT idcat FROM ".$cfg["tab"]["cat_art"]." WHERE idart='".$idart."'";          // get all idcats that contain art
            $db->query($sql);
    
            while ( $db->next_record() ) {
                $tmp_idcat_in_art[] = $db->f("idcat");
            }
    
            if (!is_array($tmp_idcat_in_art)) {
                $tmp_idcat_in_art[0] = $idcat;
            }
        }
        
        
        
        /* Start date */    
    	if ($tmp_datestart == "0000-00-00 00:00:00")
    	{
    		$tpl->set('s', 'STARTDATE', '');
    	} else {
    		$tpl->set('s', 'STARTDATE', $tmp_datestart);
    	}
    	
    	
    	/* End date */
    	if ($tmp_dateend == "0000-00-00 00:00:00")
    	{
    		$tpl->set('s', 'ENDDATE','');
    	} else {
    		$tpl->set('s', 'ENDDATE', $tmp_dateend);
    	}
    
        $sql = "SELECT
                    A.idcat,
                    A.level,
                    C.name
                FROM
                    ".$cfg["tab"]["cat_tree"]." AS A,
                    ".$cfg["tab"]["cat"]." AS B,
                    ".$cfg["tab"]["cat_lang"]." AS C
                WHERE
                    A.idcat=B.idcat AND
                    B.idcat=C.idcat AND
                    C.idlang='$lang' AND
                    B.idclient='$client'
                ORDER BY
                    A.idtree";
    
        $db->query($sql);
    
        while ( $db->next_record() ) {
    
            $spaces = "";
    
            for ($i = 0; $i < $db->f("level"); $i ++) {
                $spaces .= "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
    
            if ( !in_array($db->f("idcat"), $tmp_idcat_in_art) ) {
                $tpl2->set('d', 'VALUE', $db->f("idcat"));
                $tpl2->set('d', 'SELECTED', '');
                $tpl2->set('d', 'CAPTION', $spaces.$db->f("name"));
    
                $tpl2->next();
    
            } else {
                $tpl2->set('d', 'VALUE', $db->f("idcat"));
                $tpl2->set('d', 'SELECTED', 'selected="selected"');
                $tpl2->set('d', 'CAPTION', $spaces.$db->f("name"));
                $tpl2->next();
    
            }
        }
    
        $select = $tpl2->generate($cfg["path"]["templates"] . $cfg["templates"]["generic_select"], true);
    
        /* Struktur */
        $tpl->set('s', 'STRUKTUR', i18n("Category"));
        $tpl->set('s', 'STRUKTUR-FIELD', $select);
        
        if ($tmp_notification) {
            $tpl->set('s', 'NOTIFICATION', '<tr><td colspan="4">'.$tmp_notification.'<br></td></tr>');
        } else {
            $tpl->set('s', 'NOTIFICATION', '');
        }
    
    	if (($perm->have_perm_area_action("con", "con_makeonline") ||
            $perm->have_perm_area_action_item("con","con_makeonline", $idcat)) && $inUse == false)
        {
        	$allow_usetimemgmt = '';
        	$tpl->set('s', 'CHOOSEEND', '<a href="javascript:endcal.popup(\'\',\''.$cfg['path']['contenido_fullhtml'].$cfg['path']['templates'].'\');"><img src="images/calendar.gif" width="16" height="16" border="0" alt="Endzeitpunkt wählen"></a>');
        	$tpl->set('s', 'CHOOSESTART', '<a href="javascript:startcal.popup(\'\',\''.$cfg['path']['contenido_fullhtml'].$cfg['path']['templates'].'\');"><img src="images/calendar.gif" width="16" height="16" border="0" alt="Startzeitpunkt wählen"></a>');
        } else {
        	$allow_usetimemgmt = ' disabled="disabled"';
        	$tpl->set('s', 'CHOOSEEND', '');
        	$tpl->set('s', 'CHOOSESTART', '');
        }
        
        $tpl->set('s', 'SDOPTS', $allow_usetimemgmt);
        $tpl->set('s', 'EDOPTS', $allow_usetimemgmt);
        
    	if ($tmp_usetimemgmt == '1')
    	{
    		$tpl->set('s','TIMEMGMTCHECKED', 'checked'.$allow_usetimemgmt);
    	} else {
    		$tpl->set('s', 'TIMEMGMTCHECKED', $allow_usetimemgmt);
    	} 
    
    	unset ($tpl2);
        /* Nach Kategorie Verschieben */
        $tpl2 = new Template;
        $tpl2->set('s', 'ID',       'catsel');
        $tpl2->set('s', 'NAME',     'time_target_cat');
        $tpl2->set('s', 'CLASS',    'text_medium');
        $tpl2->set('s', 'OPTIONS',  'size="1" style="width: 160px;scrollbar-face-color:#C6C6D5;scrollbar-highlight-color:#FFFFFF;scrollbar-3dlight-color:#747488;scrollbar-darkshadow-color:#000000;scrollbar-shadow-color:#334F77;scrollbar-arrow-color:#334F77;scrollbar-track-color:#C7C7D6;"'.$allow_usetimemgmt);
        
            $sql = "SELECT
                    A.idcat,
                    A.level,
                    C.name
                FROM
                    ".$cfg["tab"]["cat_tree"]." AS A,
                    ".$cfg["tab"]["cat"]." AS B,
                    ".$cfg["tab"]["cat_lang"]." AS C
                WHERE
                    A.idcat=B.idcat AND
                    B.idcat=C.idcat AND
                    C.idlang='$lang' AND
                    B.idclient='$client'
                ORDER BY
                    A.idtree";
    
        $db->query($sql);
    
        while ( $db->next_record() ) {
    
            $spaces = "";
    
            for ($i = 0; $i < $db->f("level"); $i ++) {
                $spaces .= "&nbsp;&nbsp;";
            }
    
            if ( $db->f("idcat") != $tmp_targetcat) {
                $tpl2->set('d', 'VALUE', $db->f("idcat"));
                $tpl2->set('d', 'SELECTED', '');
                $tpl2->set('d', 'CAPTION', $spaces.$db->f("name"));
    
                $tpl2->next();
    
            } else {
                $tpl2->set('d', 'VALUE', $db->f("idcat"));
                $tpl2->set('d', 'SELECTED', 'selected="selected"');
                $tpl2->set('d', 'CAPTION', $spaces.$db->f("name"));
                $tpl2->next();
    
            }
        }
    
        $select = $tpl2->generate($cfg["path"]["templates"] . $cfg["templates"]["generic_select"], true);
    
        /* Seitentitel */
        $title_input = '<input type="text" '.$disabled.' size="64" name="page_title" value="'.htmlspecialchars($tmp_page_title).'">';
        $tpl->set("s", "TITLE-INPUT", $title_input);
    
    	/* Meta-Tags */
    	$availableTags = conGetAvailableMetaTagTypes();
    	
    	
    	foreach ($availableTags as $key => $value)
    	{
    		$tpl->set('d', 'METAINPUT', 'META'.$value);
    		
    		switch ($value["fieldtype"])
    		{
    			case "text":
    					$element = '<input '.$disabled.' type="text" name="META'.$value["name"].'" size=64 maxlength='.$value["maxlength"].' value="'.conGetMetaValue($tmp_idartlang,$key).'">';
    					break;
    			case "textarea":
    					$element = '<textarea '.$disabled.' name="META'.$value["name"].'" cols='.$value["maxlength"].' rows=3>'.conGetMetaValue($tmp_idartlang,$key).'</textarea>';
    					break;
    		}
    		
    		$tpl->set('d', 'METAFIELDTYPE', $element);
    		//$tpl->set('d', 'METAVALUE', conGetMetaValue($tmp_idartlang,$key));
    		$tpl->set('d', 'METATITLE', $value["name"].':');	
    		$tpl->next();
    	}
    
        /* Struktur */
        $tpl->set('s', 'MOVETOCATEGORYSELECT', $select);
        
        
        if ($tmp_movetocat == "1")
        {
        	$tpl->set('s','MOVETOCATCHECKED', 'checked'.$allow_usetimemgmt);
        } else {
        	$tpl->set('s','MOVETOCATCHECKED', ''.$allow_usetimemgmt);
        }
        
        if ($tmp_onlineaftermove == "1")
        {
        	$tpl->set('s', 'ONLINEAFTERMOVECHECKED', 'checked'.$allow_usetimemgmt);
        } else {
        	$tpl->set('s', 'ONLINEAFTERMOVECHECKED', ''.$allow_usetimemgmt);
        }
        
        /* Summary */
        $tpl->set('s', 'SUMMARY', i18n("Summary"));
        $tpl->set('s', 'SUMMARY-INPUT', '<textarea '.$disabled.' style="width: 250px" class="text_medium" name="summary" cols="50" rows="5">'.$tmp_summary.'</textarea>');
    
        $sql = "SELECT
                    b.idcat
                FROM
                    ".$cfg["tab"]["cat"]." AS a,
                    ".$cfg["tab"]["cat_lang"]." AS b,
                    ".$cfg["tab"]["cat_art"]." AS c
                WHERE
                    a.idclient = '".$client."' AND
                    a.idcat    = b.idcat AND
                    c.idcat    = b.idcat AND
                    c.idart    = '".$idart."'";
    
        $db->query($sql);
        $db->next_record();
        
        $idcat = $db->f("idcat");
    
        if ( isset($idart) ) {
    
            if ( !isset($idartlang) || 0 == $idartlang ) {
                $sql = "SELECT idartlang FROM ".$cfg["tab"]["art_lang"]." WHERE idart = $idart AND idlang = $lang";
                $db->query($sql);
                $db->next_record();
                $idartlang = $db->f("idartlang");
            }
    
        }
    
        if ( isset($idcat) ) {
    
            if ( !isset($idcatlang) || 0 == $idcatlang ) {
                $sql = "SELECT idcatlang FROM ".$cfg["tab"]["cat_lang"]." WHERE idcat = $idcat AND idlang = $lang";
                $db->query($sql);
                $db->next_record();
                $idcatlang = $db->f("idcatlang");
            }
            
        }
        
        if ( isset($idcat) && isset($idart) ) {
    
            if ( !isset($idcatart) || 0 == $idcatart ) {
                $sql = "SELECT idcatart FROM ".$cfg["tab"]["cat_art"]." WHERE idart = $idart AND idcat = $idcat";
                $db->query($sql);
                $db->next_record();
                $idcatart = $db->f("idcatart");
            }
    
        }
    
        if ( 0 != $idart && 0 != $idcat ) {
            $script = 'artObj.setProperties("'.$idart.'", "'.$idartlang.'", "'.$idcat.'", "'.$idcatlang.'", "'.$idcatart.'");';
        } else {
            $script = 'artObj.reset();';
        }
    
        $tpl->set('s', 'DATAPUSH', $script);
    
    	
    	$tpl->set('s', 'BUTTONDISABLE', $disabled);
    	
    	if ($inUse == true)
    	{
    		$tpl->set('s', 'BUTTONIMAGE', 'but_ok_disabled.gif');
    	} else {
    		$tpl->set('s', 'BUTTONIMAGE', 'but_ok.gif');		
    	}
        /* Genereate the Template */
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['con_edit_form']);
    
    
    } else {
    
        /* User hat no permission
           to see this form  */
        $notification->displayNotification("error", i18n("Permission denied"));
        
    }
}
?>
