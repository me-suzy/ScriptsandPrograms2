<?php

/******************************************
* File      :   includes.con_art_overview.php
* Project   :   Contenido
* Descr     :   Displays all articles
*               of a category
*
* Author    :   Jan Lengowski
* Created   :   26.01.2003
* Modified  :   23.06.2003
*
* © four for business AG
*****************************************/

$idcat = ( isset($HTTP_GET_VARS['idcat']) ) ? $HTTP_GET_VARS['idcat'] : -1;

$debug = false;

if ( is_numeric($idcat) && ($idcat >= 0)) {

    /* Das ist ne ziemlich
       kranke abfrage timo.. */
       
    /* Ist nicht anders zu lösen, es sei denn,
       man baut das Geraffel endlich mal gescheit :)
           - Timo */

    /* Ich hab das rechtesytem nicht geschrieben,
       sonst wäre es was gescheites ;) - Jan */
       
    /* Obacht, die Abfrage wird ab heute noch viel kränker.
       Timo */

    /*  Ich bekomm noch Albträume von dem System...
    
        $perm->have_perm_area_action("Meine Freundin", "Spass haben") = false, Kopfschmerzen
        $perm->have_perm_area_action("Küche", "Was Trinken") = false, Nix da
        $perm->have_perm_area_action("Bett", "Länger Schlafen") = false, Wecker klingelt
        
        ... to be continued ;-)
        
        - Jan
    */
       
    if (((  $idcat == 0 ||
    		$perm->have_perm_area_action(1)) && $perm->have_perm_item(6, $idcat))   ||
            $perm->have_perm_area_action("con", "con_makestart")                    ||
            $perm->have_perm_area_action("con", "con_makeonline")                   ||
            $perm->have_perm_area_action("con", "con_deleteart")                    ||
            $perm->have_perm_area_action("con", "con_tplcfg_edit")                  ||
            $perm->have_perm_area_action("con", "con_lock") 	                    ||
            $perm->have_perm_area_action("con", "con_makecatonline")                ||
            $perm->have_perm_area_action("con", "con_changetemplate")               ||
            $perm->have_perm_area_action("con_editcontent", "con_editart")          ||
            $perm->have_perm_area_action("con_editart", "con_edit")                 ||
            $perm->have_perm_area_action("con_editart", "con_newart")               ||
            $perm->have_perm_area_action("con_editart", "con_saveart")				||
            $perm->have_perm_area_action("con_tplcfg", "con_tplcfg_edit")           ||
            $perm->have_perm_area_action_item("con", "con_makestart", $idcat)       ||
            $perm->have_perm_area_action_item("con", "con_makeonline", $idcat)      ||
            $perm->have_perm_area_action_item("con", "con_deleteart", $idcat)       ||
            $perm->have_perm_area_action_item("con", "con_tplcfg_edit", $idcat)     ||
            $perm->have_perm_area_action_item("con", "con_lock", $idcat)            ||
            $perm->have_perm_area_action_item("con", "con_makecatonline", $idcat)   ||
            $perm->have_perm_area_action_item("con", "con_changetemplate", $idcat)  ||
            $perm->have_perm_area_action_item("con_editcontent", "con_editart", $idcat)       ||
            $perm->have_perm_area_action_item("con_editart", "con_edit", $idcat)    ||
            $perm->have_perm_area_action_item("con_editart", "con_newart", $idcat)  ||
            $perm->have_perm_area_action_item("con_tplcfg", "con_tplcfg_edit",$idcat) ||
            $perm->have_perm_area_action_item("con_editart", "con_saveart", $idcat)) {

        $sql = "SELECT
                    a.idart AS idart,
                    a.idartlang AS idartlang,
                    a.title AS title,
                    c.idcat AS idcat,
                    c.is_start AS is_start,
                    c.idcatart AS idcatart,
                    a.idtplcfg AS idtplcfg,
                    a.online AS online,
                    a.created AS created,
                    a.lastmodified AS lastmodified,
                    a.timemgmt AS timemgmt,
                    a.datestart AS datestart,
                    a.dateend AS dateend,
					a.artsort AS artsort,
					a.locked AS locked
                FROM
                    ".$cfg["tab"]["art_lang"]." AS a,
                    ".$cfg["tab"]["art"]." AS b,
                    ".$cfg["tab"]["cat_art"]." AS c
                WHERE
                    a.idlang    = '".$lang."' AND
                    a.idart     = b.idart AND
                    b.idclient  = '".$client."' AND
                    b.idart     = c.idart AND
                    c.idcat     = '".$idcat."'";

        # Article sort
        if ( isset($sort) ) {

            if ( $sort == 1 ) {
                $sql .= " ORDER BY a.title ASC";

            } elseif ( $sort == 2 ) {
                $sql .= " ORDER BY a.lastmodified DESC";

            } elseif ( $sort == 3 ) {
                $sql .= " ORDER BY a.created DESC";
                
            } elseif ( $sort == 4 ) {
                $sql .= " ORDER BY a.artsort ASC";
            }
        }

        # Default sort order
        if ( !isset($sort) ) {
            $sql .= " ORDER BY a.title ASC";
            
        }

        # Debug info
        if ( $debug ) {

            echo "<pre>";
            echo $sql;
            echo "</pre>";

        }

        $lidcat = $idcat;
        
        $db->query($sql);

        # Reset Template
        $tpl->reset();

        # No article
        $no_article = true;

        while ( $db->next_record() ) {

            $idtplcfg   = $db->f("idtplcfg");
            $idartlang  = $db->f("idartlang");
            $idcat      = $db->f("idcat");
            $idcatlang  = 0;
            $idart      = $db->f("idart");
            $online     = $db->f("online");
            $is_start   = $db->f("is_start");
            $idcatart   = $db->f("idcatart");
            $created    = $db->f("created");
            $modified   = $db->f("lastmodified");
            $title      = htmlspecialchars($db->f("title"));
            $timemgmt   = $db->f("timemgmt");
            $datestart  = $db->f("datestart");
            $dateend    = $db->f("dateend");
            $sortkey    = $db->f("artsort");
            $locked     = $db->f("locked");
           
            if ($online == 1) {
                $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light"] : $cfg["color"]["table_dark"];
            } else {
                $bgcolor = ( is_int($tpl->dyn_cnt / 2) ) ? $cfg["color"]["table_light_offline"] : $cfg["color"]["table_dark_offline"];
            }

            /* Id of the row,
               stores informations about
               the article and category */
            $tmp_rowid  = $idart."-".$idartlang."-".$idcat."-".$idcatlang."-".$idcatart;
            $tpl->set('d', 'ROWID', $tmp_rowid);

            # Backgroundcolor of the table row
            $tpl->set('d', 'BGCOLOR', $bgcolor);

            # Article Title
            if ($perm->have_perm_area_action( "con_editcontent", "con_editart" ) ||
                $perm->have_perm_area_action_item( "con_editcontent", "con_editart" ,$idcat) )
            {

                	$tmp_alink = $sess->url("main.php?area=con_editcontent&action=con_editart&changeview=edit&idartlang=$idartlang&idart=$idart&idcat=$idcat");
                	$link = '<a href="'.$tmp_alink.'">'.$title.'</a>';
            } else {
                $tmp_alink = "";
                $link = $title;
            }
            
            if ($timemgmt == "1")
            {
            	$sql = "SELECT NOW() AS TIME";
            	
            	$db3 = new DB_Contenido;
            	
            	$db3->query($sql);
            	$db3->next_record();
            	            	
            	$starttimestamp = strtotime($datestart);
            	$endtimestamp = strtotime($dateend);
            	$nowtimestamp = strtotime($db3->f("TIME"));
            	
            	if (($nowtimestamp < $endtimestamp) && ($nowtimestamp > $starttimestamp))
            	{ 	
					$tpl->set('d', 'USETIME', '<img src="images/but_time_2.gif" alt="Artikel mit Zeitsteuerung online" title="Artikel mit Zeitsteuerung online">');        	    	
		        } else {
		        	$tpl->set('d', 'USETIME', '<img src="images/but_time_1.gif" alt="Artikel mit Zeitsteuerung offline" title="Artikel mit Zeitsteuerung offline">');
            	}
            } else {
            		$tpl->set('d', 'USETIME', '&nbsp');
            }
            
          
            # Article Title
            if ($perm->have_perm_area_action( "con", "con_lock" ) ||
                $perm->have_perm_area_action_item( "con", "con_lock" ,$idcat) )
            {
                if ($locked == 1)
                {
                	$lockimg = 'images/lock_closed.gif';
    				$lockalt = i18n("Remove lock");
                } else {
                	$lockimg = 'images/lock_open.gif';
                	$lockalt = i18n("Lock article");
                }
                $tmp_lock = '<a href="'.$sess->url("main.php?area=con&idcat=$idcat&action=con_lock&frame=4&idart=$idart").'" title="'.$lockalt.'"><img src="'.$lockimg.'" title="'.$lockalt.'" alt="'.$lockalt.'" border="0"></a>';
            } else {
                if ($locked == 1)
                {
                	$lockimg = 'images/lock_closed.gif';
    				$lockalt = i18n("Article is locked");
                } else {
                	$lockimg = 'images/lock_open.gif';
                	$lockalt = i18n("Article is not locked");
                }
            	$tmp_lock = '<img src="'.$lockimg.'" title="'.$lockalt.'" alt="'.$lockalt.'" border="0">';
            }
            
            
            
            $tpl->set('d', 'LOCKED', $tmp_lock);
            
            if ($sortkey == "")
            {
            	$sortkey = "&nbsp;";
            }
            $tpl->set('d', 'SORTKEY', $sortkey);
            $tpl->set('d', 'ARTICLE', $link);

            # Created
            $tpl->set('d', 'CREATED', $created);

            # Lastmodified
            $tpl->set('d', 'LASTMODIFIED', $modified);

            # Article conf button
            if ($perm->have_perm_area_action("con_editart","con_edit") ||
                $perm->have_perm_area_action_item("con_editart","con_edit",$lidcat))
            {
                $tmp_artconf = '<a href="'.$sess->url("main.php?area=con_editart&prearea=$area&action=con_edit&frame=4&idart=$idart&idcat=$idcat").'" title="'.i18n("Article properties").'"><img src="'.$cfg["path"]["images"].'but_art_conf2.gif" alt="'.i18n("Article properties").'" title="'.i18n("Article properties").'" border="0"></a>';
            } else {
                $tmp_artconf="&nbsp;";
            }
            
            $tpl->set('d', 'ARTCONF', $tmp_artconf);

            # Article Template
            if ( 0 == $idtplcfg ) { # Uses Category Template
                $a_tplname = "--- ".i18n("None")." ---";
                $a_idtpl = 0;

            } else { # Has own Template

                if ( !is_object($db2) ) {
                    $db2 = new DB_Contenido;
                }

                $sql2 = "SELECT
                            b.name AS tplname,
                            b.idtpl AS idtpl
                         FROM
                            ".$cfg["tab"]["tpl_conf"]." AS a,
                            ".$cfg["tab"]["tpl"]." AS b
                         WHERE
                            a.idtplcfg = '".$idtplcfg."' AND
                            a.idtpl = b.idtpl";
                            
                $db2->query($sql2);
                $db2->next_record();
                
                $a_tplname = $db2->f("tplname");
                $a_idtpl = $db2->f("idtpl");
            }

            $tpl->set('d', 'TPLNAME', $a_tplname);

            # Template conf button
            if ($perm->have_perm_area_action("con","con_tplcfg_edit") ||
                $perm->have_perm_area_action_item("con","con_tplcfg_edit",$lidcat))
            {
                $tmp_link = '<a href="'.$sess->url("main.php?area=con_tplcfg&action=tplcfg_edit&idart=$idart&idcat=$idcat&idtpl=$cat_idtpl&frame=4").'"><img src="images/configure.gif" width="15" height="15" title="'.i18n("Configure template").'" alt="'.i18n("Configure template").'" border="0"></a>';
            } else {
                $tmp_link = "&nbsp;";
            }
            $tpl->set('d', 'TPLCONF', $tmp_link);

            # Make Startarticle button
            if ( ($perm->have_perm_area_action("con","con_makestart") || $perm->have_perm_area_action_item("con","con_makestart",$lidcat)) && $lidcat != 0) {
            	
            	if ( 0 == $is_start ) {
                    $imgsrc = (isArtInMultipleUse($idart)) ? "isstart0m.gif" : "isstart0.gif";
            		$tmp_link = '<a href="'.$sess->url("main.php?area=con&idcat=$idcat&action=con_makestart&idcatart=$idcatart&frame=4&is_start=" . !$is_start).'" title="'.i18n("Make start article").'"><img src="images/'.$imgsrc.'" border="0" title="'.i18n("Make start article").'" alt="'.i18n("Make start article").'"></a>';
            	} else {
                    $imgsrc = (isArtInMultipleUse($idart)) ? "isstart1m.gif" : "isstart1.gif";
					$tmp_link = '<a href="'.$sess->url("main.php?area=con&idcat=$idcat&action=con_makestart&idcatart=$idcatart&frame=4&is_start=" . !$is_start).'" title="'.i18n("Make start article").'"><img src="images/'.$imgsrc.'" border="0" title="'.i18n("Make start article").'" alt="'.i18n("Make start article").'"></a>';
            	}
            	
            } else {
                $tmp_img = (1 == $is_start) ? '<img src="images/isstart1.gif" border="0" title="'.i18n("Start article").'" alt="'.i18n("Start article").'">' : '<img src="images/isstart0.gif" border="0" title="" alt="">';
                $tmp_link = $tmp_img;
            }

            $tpl->set('d', 'START', $tmp_link);
            
            # Make On-/Offline button
            
            if ( $online ) {
                
                if (($perm->have_perm_area_action("con","con_makeonline") ||
                    $perm->have_perm_area_action_item("con","con_makeonline",$lidcat)) && ($lidcat != 0))
                {
                    $tmp_online = '<a href="'.$sess->url("main.php?area=con&idcat=$idcat&action=con_makeonline&frame=4&idart=$idart").'" title="'.i18n("Make offline").'"><img src="images/online.gif" title="'.i18n("Make offline").'" alt="'.i18n("Make offline").'" border="0"></a>';
                } else {
                    $tmp_online = '<img src="images/online.gif" title="'.i18n("Article is online").'" alt="'.i18n("Article is online").'" border="0">';
                }
            } else {
                if (($perm->have_perm_area_action("con","con_makeonline") ||
                    $perm->have_perm_area_action_item("con","con_makeonline",$lidcat)) && ($lidcat != 0))
                {
                    $tmp_online = '<a href="'.$sess->url("main.php?area=con&idcat=$idcat&action=con_makeonline&frame=4&idart=$idart").'" title="'.i18n("Make online").'"><img src="images/offline.gif" title="'.i18n("Make online").'" alt="'.i18n("Make online").'" border="0"></a>';
                } else {
                    $tmp_online = '<img src="images/offline.gif" title="'.i18n("Article is offline").'" alt="'.i18n("Article is offline").'" border="0">';
                }
            }

            $tpl->set('d', 'ONLINE', $tmp_online);
            
            # Delete button
            if ($perm->have_perm_area_action("con","con_deleteart") ||
                $perm->have_perm_area_action_item("con","con_deleteart",$lidcat))
            {
            	$tmp_title = $title;

            	if (strlen($tmp_title) > 30)
            	{
	                $tmp_title = substr($tmp_title, 0, 27) . "...";
            	}
            	
            	$confirmString = sprintf(i18n("Are you sure to delete the following article:<br><br><b>%s</b>"),$tmp_title);
            	$tmp_del = '<a href="javascript://" onclick="box.confirm(\''.i18n("Delete article").'\', \''.$confirmString.'\', \'deleteArticle('.$idart.','.$idcat.')\')" title="'.i18n("Delete article").'"><img src="images/delete.gif" title="'.i18n("Delete article").'" alt="'.i18n("Delete article").'" border="0"></a>';
            	
            } else {
            $tmp_del = "&nbsp;";
            }
            $tpl->set('d', 'DELETE', $tmp_del);

            # Next iteration
            $tpl->next();
            
            # Articles found
            $no_article = false;

        }

        /* If there is only one article -> mark it */
        if ($tpl->dyn_cnt == 1) {
            $script = 'var theOne = document.getElementById("'.$tmp_rowid.'");
                       artRow.over( theOne );
                       artRow.click( theOne )';
        } else {
            $script = '';
        }
        
        $tpl->set('s', 'ROWMARKSCRIPT', $script);

        # Sortierungs select
        $s_types = array(1 => i18n("Alphabetical"),
                         2 => i18n("Last change"),
                         3 => i18n("Created date"),
                         4 => i18n("Sort key"));

        $tpl2 = new Template;
        $tpl2->set('s', 'NAME', 'sort');
        $tpl2->set('s', 'CLASS', 'text_medium');
        $tpl2->set('s', 'OPTIONS', 'onchange="artSort(this)"');
        
        foreach ($s_types as $key => $value) {

            $selected = ( isset($HTTP_GET_VARS['sort']) && $HTTP_GET_VARS['sort'] == $key ) ? 'selected="selected"' : '';

            $tpl2->set('d', 'VALUE',    $key);
            $tpl2->set('d', 'CAPTION',  $value);
            $tpl2->set('d', 'SELECTED', $selected);
            $tpl2->next();
            
        }

        $select     = ( !$no_article ) ? $tpl2->generate($cfg["path"]["templates"] . $cfg['templates']['generic_select'], true) : '&nbsp;';
        $caption    = ( !$no_article ) ? i18n("Sort articles") : '&nbsp;';
        
        $tpl->set('s', 'ARTSORTCAPTION', $caption);
        $tpl->set('s', 'ARTSORT', $select);

        # Extract Category and Catcfg
        $sql = "SELECT
                    b.name AS name,
                    d.idtpl AS idtpl
                FROM
                    ".$cfg["tab"]["cat"]." AS a,
                    ".$cfg["tab"]["cat_lang"]." AS b,
                    ".$cfg["tab"]["tpl_conf"]." AS c
                LEFT JOIN
                    ".$cfg["tab"]["tpl"]." AS d
                ON
                    d.idtpl = c.idtpl
                WHERE
                    a.idclient = '".$client."' AND
                    a.idcat    = '".$idcat."' AND
                    b.idlang   = '".$lang."' AND
                    b.idcat    = a.idcat AND
                    c.idtplcfg = b.idtplcfg";

        $db->query($sql);
        $db->next_record();

        conCreateLocationString($idcat, "&nbsp;/&nbsp;", $cat_name);
        $cat_idtpl = $db->f("idtpl");

        # Hinweis wenn kein Artikel gefunden wurde
        if ( $no_article ) {

            $tpl->set("d", "START",         "&nbsp;");
            $tpl->set("d", "ARTICLE",       i18n("No articles found"));
            $tpl->set("d", "CREATED",       "&nbsp;");
            $tpl->set("d", "LASTMODIFIED",  "&nbsp;");
            $tpl->set("d", "ARTCONF",       "&nbsp;");
            $tpl->set("d", "TPLNAME",       "&nbsp;");
            $tpl->set("d", "LOCKED",       "&nbsp;");
            $tpl->set("d", "TPLCONF",       "&nbsp;");
            $tpl->set("d", "ONLINE",        "&nbsp;");
            $tpl->set("d", "DELETE",        "&nbsp;");
            $tpl->set("d", "USETIME",       "&nbsp;");
            $tpl->set("d", "SORTKEY",       "&nbsp;");

            $tpl->next();

        }

        # Kategorie anzeigen und Konfigurieren button
        /* JL 23.06.03 Check right from "Content" instead of "Category"
        if ($perm->have_perm_area_action("str_tplcfg","str_tplcfg") ||
            $perm->have_perm_area_action_item("str_tplcfg","str_tplcfg",$lidcat)) */
        if ($perm->have_perm_area_action_item( "con", "con_tplcfg_edit", $lidcat ) ||
        	$perm->have_perm_area_action( "con", "con_tplcfg_edit" )) {

            if ( 0 != $idcat ) {

				$tmp_link = '<a href="'.$sess->url("main.php?area=con_tplcfg&action=tplcfg_edit&idcat=$idcat&idtpl=$idtpl&frame=4&mode=art").'">'.i18n("Configure Category").'</a>';
				$tmp_img  = '<a href="'.$sess->url("main.php?area=con_tplcfg&action=tplcfg_edit&idcat=$idcat&idtpl=$idtpl&frame=4&mode=art").'"><img src="'.$cfg["path"]["images"].'but_cat_conf2.gif" border="0" title="'.i18n("Configure Category").'" alt="'.i18n("Configure Category").'"></a>';

                $tpl->set('s', 'CATEGORY', $cat_name);
                $tpl->set('s', 'CATEGORY_CONF', $tmp_img);
                $tpl->set('s', 'CATEGORY_LINK', $tmp_link);
                
            } else {            	

                $tpl->set('s', 'CATEGORY', $cat_name);
        	    $tpl->set('s', 'CATEGORY_CONF', '&nbsp;');
        	    $tpl->set('s', 'CATEGORY_LINK', '&nbsp;');
        	    
            }	        	
        	
        } else {

        	   $tpl->set('s', 'CATEGORY', $cat_name);
                $tpl->set('s', 'CATEGORY_CONF', '&nbsp;');
                $tpl->set('s', 'CATEGORY_LINK', '&nbsp;');
                       	
        }
        
        # SELF_URL (Variable für das javascript);
        $tpl->set('s', 'SELF_URL', $sess->url("main.php?area=con&frame=4&idcat=$idcat"));

        # Neuer Artikel link
        if ($perm->have_perm_area_action("con_editart", "con_newart") ||
        	$perm->have_perm_area_action_item("con_editart", "con_newart", $lidcat)) {

            if ( 0 != $idcat ) {
            	
                $tpl->set('s', 'NEWARTICLE_TEXT', '<a href="'.$sess->url("main.php?area=con_editart&frame=$frame&action=con_newart&idcat=$idcat").'">'.i18n("Create new article").'</a>');
                $tpl->set('s', 'NEWARTICLE_IMG', '<a href="'.$sess->url("main.php?area=con_editart&frame=$frame&action=con_newart&idcat=$idcat").'" title="'.i18n("Create new article").'"><img src="images/but_art_new.gif" border="0" alt="'.i18n("Create new article").'"></a>');
                
            } else {
            	
            	$tpl->set('s', 'NEWARTICLE_TEXT', '&nbsp;');
                $tpl->set('s', 'NEWARTICLE_IMG', '&nbsp;');
                
            }
            
        } else {

            $tpl->set('s', 'NEWARTICLE_TEXT', '&nbsp;');
            $tpl->set('s', 'NEWARTICLE_IMG', '&nbsp;');
            
        }

        /* Session ID */
        $tpl->set('s', 'SID', $sess->id);
        
        /* Display notification */
        if ($tmp_notification) {

            $str = '
            <tr>
                <td colspan="9">'.$tmp_notification.'</td>
            </tr>

            <tr>
                <td colspan="9"><img src="images/spacer.gif" width="1" height="10"></td>
            </tr>';

        } else {
            $str = "";
            
        }
        
        $tpl->set('s', 'NOTIFICATION', $str);

        # Generate template
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['con_art_overview']);
    
    } else {
        $notification->displayNotification("error", i18n("Permission denied"));
        
    }

} else {
        $tpl->reset();
        $tpl->set('s', 'CONTENTS', '');
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['blank']);
}

?>
