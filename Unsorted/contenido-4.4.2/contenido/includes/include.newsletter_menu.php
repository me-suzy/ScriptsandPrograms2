<?php
/******************************************
* File      :   include.stat_overview.php
* Project   :   Contenido
* Descr     :   Displays languages
*
* Author    :   Olaf Niemann
* Created   :   23.04.2003
* Modified  :   23.04.2003
*
* © four for business AG
*****************************************/



$tpl->reset();

$tpl->set('s', 'SID', $sess->id);

if ($perm->have_perm_area_action($area))
{

        if (($action == "news_delete") && ($perm->have_perm_area_action($area, $action))) {

           $sql = "DELETE FROM "
                     .$cfg["tab"]["news"].	
                  " WHERE
                     idnews = \"" .$newsid."\"";
           $db->query($sql);
                  
        }

        $sql = "SELECT * FROM ".$cfg["tab"]["news"]." WHERE idclient='$client' AND idlang='$lang' ORDER BY newsdate DESC";
        $db->query($sql);


        // Empty Row
        $bgcolor = '#FFFFFF';
        $tpl->set('s', 'PADDING_LEFT', '10');



        while ($db->next_record())
        {

            $idnews      = $db->f("idnews");
            $name        = $db->f("name");
            $idart       = $db->f("idart");
            $subject     = $db->f("subject");
            $message     = $db->f("message");
            $date        = $db->f("newsdate");


            if ($name == "")
            {
                $name = $idnews;
            }

            $dark = !$dark;
            if ($dark) {
                $bgColor = $cfg["color"]["table_dark"];
            } else {
                $bgColor = $cfg["color"]["table_light"];
            }

            $tmp_mstr = '<a alt="'.$subject.'" title="'.$subject.'". href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';
            $area = "news";
            $mstr = sprintf($tmp_mstr, 'right_top',
                                           $sess->url("main.php?area=$area&frame=3&newsid=$idnews"),
                                           'right_bottom',
                                           $sess->url("main.php?area=news&frame=4&newsid=$idnews"),
                                           $name);

            if ($perm->have_perm_area_action('news',"news_delete") ) { 
            		$deleteMessage = sprintf(i18n("Do you really want to delete the newsletter named %s?"), $name);
                    $deletebutton = "<a onClick=\"event.cancelBubble=true;check=confirm('".$deleteMessage."'); if (check==true) { location.href='".$sess->url("main.php?area=news&action=news_delete&frame=$frame&newsid=$idnews&del=")."#deletethis'};\" href=\"#\"><img src=\"".$cfg['path']['images']."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".i18n("Delete newsletter")."\" title=\"".i18n("Delete newsletter")."\"></a>";
                } else {
                    $deletebutton = "";
                }

            if ($perm->have_perm_area_action('news',"news_send") ) {

				$sendMessage = sprintf(i18n("Do you really want to send the newsletter named %s?"), $name);
                $tmp_send = "<a onClick=\"check=confirm('".$sendMessage."'); if (check==true) { javascript:conMultiLink('%s', '%s', '%s', '%s');}\" href=\"#\">%s</a>";

                $send = sprintf($tmp_send, 'right_top',
                                           $sess->url("main.php?area=$area&frame=3&newsid=$idnews"),
                                           'right_bottom',
                                           $sess->url("main.php?area=news_send&action=news_send&frame=4&newsid=$idnews"),
                                           "<img src=\"".$cfg['path']['images']."send.gif\" border=\"0\" alt=\"".i18n("Send newsletter")."\" title=\"".i18n("Send newsletter")."\"></a>");

        //        $sendbutton = "<a target=\"right_bottom\" onClick=\"event.cancelBubble=true; location.href='".$sess->url("main.php?area=news_send&action=news_send&frame=4&newsid=$idnews")."'};\" href=\"#\">";
                $sendbutton = $send;
                } else {
                    $sendbutton = "";
                }

            if (strlen($subject) > 20)
            {
                $subject = substr($subject,0,17) . "...";
            }

            if (strlen($name) > 20)
            {
                $name = substr($name,0,17) . "...";
            }

            if (strlen($message) > 50)
            {
                $message = substr($message,0,47) . "...";
            }

            $date = substr($date, 0,10);
            $tpl->set('d', 'HBGCOLOR', $cfg["color"]["table_header"]);
            $tpl->set('d', 'BGCOLOR', $bgColor);
            $tpl->set('d', 'NEWSNAME', $mstr);
            
            $delTitle = i18n("Delete newsletter");
        	$delDescr = sprintf(i18n("Do you really want to delete the following newsletter:<br><br>%s<br>"),$name);
        
        	$tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteNews('.$idnews.')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');


            $delTitle = i18n("Send newsletter");
        	$delDescr = sprintf(i18n("Do you really want to send the following newsletter:<br><br>%s<br>"),$name);
        
        	$tpl->set('d', 'SEND', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'sendNews('.$idnews.')\')"><img src="'.$cfg['path']['images'].'send.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
                        
            $tpl->next();
        }



        # Generate template
        $tpl->generate($cfg['path']['templates'] . $cfg['templates']['newsletter_menu']);
}
?>
