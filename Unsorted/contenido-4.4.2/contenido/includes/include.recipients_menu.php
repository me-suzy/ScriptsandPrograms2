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

$tpl->set('s', 'RECIPIENTSSTART',$recipientsStart);
$tpl->set('s', 'SID', $sess->id);

if (($action == "recipients_delete") && ($perm->have_perm_area_action($area, $action))) {

   $sql = "DELETE FROM "
             .$cfg["tab"]["news_rcp"].	
          " WHERE
             idnewsrcp = \"" .$newsrcpid."\"";
   $db->query($sql);
          
}

if (is_string($search) && strlen($search) > 0)
{
	$limitSQL = "AND name LIKE '%$search%' OR email LIKE '%$search%' ";
} else {
	$limitSQL = "";
}

$sql = "SELECT COUNT(*) FROM ".$cfg["tab"]["news_rcp"]." WHERE idclient='$client' AND idlang='$lang' $limitSQL ORDER BY name ASC";
$db->query($sql);
if ($db->next_record())
{
	$numRecipients = $db->f("COUNT(*)");
} else {
	$numRecipients = 0;
}

if (!is_numeric($limit) || $limit == 0)
{
	$limit = 20;
}
	
$tpl->set('s', 'LIMITTO', $limit);

$howManyRecipients = $limit;

if ($recipientsStart > $numRecipients) 
{
	$recipientsStart = $numRecipients-$howManyRecipients;
}

if ($recipientsStart < 0)
{
	$recipientsStart = 0;
}

$limitOptions = array(
						10 => "10",
						20 => "20",
						50 => "50",
						100 => "100");
			
$tpl2 = new Template;
$tpl2->set('s', 'NAME', 'limit');
$tpl2->set('s', 'CLASS', 'text_medium');
$tpl2->set('s', 'ID', 'limit');
$tpl2->set('s', 'OPTIONS', "onChange='rcpChangeLimit()'");

foreach ($limitOptions as $key => $value)
{
	if ($key == $limit)
	{
		$selected = "SELECTED";
	} else {
		$selected = "";
	}
	
	$tpl2->set('d', 'VALUE', $key);
	$tpl2->set('d', 'CAPTION', $value);
	$tpl2->set('d', 'SELECTED', $selected);
	$tpl2->next();
}

$select = $tpl2->generate($cfg["path"]["templates"]. $cfg["templates"]["generic_select"],true);

if ($recipientsStart > 0)
{
	$left = '<a href="'.$sess->url("main.php?area=$area&frame=2&limit=$limit&recipientsStart=".($recipientsStart-$howManyRecipients)).'"><img border="0" src="images/pfeil_links.gif"></a>';
} else {
	$left = '<img src="images/spacer.gif" width="9" height=15">';
}

if (($recipientsStart + $howManyRecipients) < $numRecipients)
{
	$right = '<a href="'.$sess->url("main.php?area=$area&frame=2&limit=$limit&recipientsStart=".($recipientsStart+$howManyRecipients)).'"><img border="0" src="images/pfeil_rechts.gif"></a>';
} else {
	$right = '<img src="images/spacer.gif" width="9" height=15">';
}

$tpl->set('s', 'LEFT', $left);
$tpl->set('s', 'RIGHT', $right);
$tpl->set('s', 'LIMIT', $select);
$pageCount = ceil($numRecipients / $limit);
$currentPage = ceil($recipientsStart / $limit)+1; 
if ($numRecipients > 0)
{
	$tpl->set('s', 'PAGESPEC', i18n("Page") .' '. $currentPage .' / '.$pageCount);
} else {
	$tpl->set('s', 'PAGESPEC', i18n("No results"));
}
$tpl->set('s', 'SEARCH', '<input type="text" name="search" class="text_medium" maxlen="256" size="16">');
$tpl->set('s', 'SEARCHSUBMIT', '<input type="image" onclick="rcpStartSearch()" src="images/submit.gif" alt="'.i18n("Start search").'" title="'.i18n("Start search").'">');



$sql = "SELECT * FROM ".$cfg["tab"]["news_rcp"]." WHERE idclient='$client' AND idlang='$lang' $limitSQL ORDER BY name ASC";
$db->query($sql);
// Empty Row
$bgcolor = '#FFFFFF';
$tpl->set('s', 'PADDING_LEFT', '10');

if ($numRecipients > 0)
{
	$db->seek($recipientsStart);
}

$recipientCount = 0;

while ($db->next_record())
{
	$recipientCount++;
	if ($recipientCount > $howManyRecipients) 
	{
		break;
	}
    $idnewsrcp   = $db->f("idnewsrcp");
    $name        = $db->f("name");
    $email       = $db->f("email");
    $deactivated = $db->f("deactivated");
    $author      = $db->f("author");


	if (!is_alphanumeric($name))
	{
		$name = $email;
	}
	
    $dark = !$dark;
    if ($dark) {
        $bgColor = $cfg["color"]["table_dark"];
    } else {
        $bgColor = $cfg["color"]["table_light"];
    }

	if ($deactivated)
	{
		$fontColor = '<font color="#A20000">';
	} else {
		$fontColor = '<font color="black">';
	}
    $tmp_mstr = '<a alt="'.$email.'" title="'.$email.'" href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">'.'<img src="'.$cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"].'recipient.gif" border="0"></a>';
    $tmp_mstr2 = '<a alt="'.$email.'" title="'.$email.'" href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">'.$fontColor.'%s</font></a>';
    $area = "recipients";
    $mstr = sprintf($tmp_mstr, 'right_top',
                                   $sess->url("main.php?area=$area&frame=3&newsrcpid=$idnewsrcp"),
                                   'right_bottom',
                                   $sess->url("main.php?area=$area&frame=4&newsrcpid=$idnewsrcp"),
                                   $name);
    $mstr2 = sprintf($tmp_mstr2, 'right_top',
                                   $sess->url("main.php?area=$area&frame=3&newsrcpid=$idnewsrcp"),
                                   'right_bottom',
                                   $sess->url("main.php?area=$area&frame=4&newsrcpid=$idnewsrcp"),
                                   $name);
    if ($perm->have_perm_area_action('recipients',"recipients_delete") ) { 
    	    $deleteMessage = sprintf(i18n("Do you really want to delete the recipient %s?"),$name);
            $deletebutton = "<a onClick=\"event.cancelBubble=true;check=confirm('".$deleteMessage."'); if (check==true) { location.href='".$sess->url("main.php?area=recipients&action=recipients_delete&frame=$frame&newsrcpid=$idnewsrcp&del=")."#deletethis'};\" href=\"#\"><img src=\"".$cfg['path']['images']."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".$lngUpl["delfolder"]."\" title=\"".$lngUpl["deluser"]."\"></a>";
        } else {
            $deletebutton = "";
        }

    $tpl->set('d', 'HBGCOLOR', $cfg["color"]["table_header"]);
    $tpl->set('d', 'BGCOLOR', $bgColor);
    $tpl->set('d', 'ICON', $mstr);
    $tpl->set('d', 'RCPNAME', $mstr2);
    $tpl->set('d', 'RCPEMAIL', $email);
 	$delTitle = i18n("Delete recipient");
    $delDescr = sprintf(i18n("Do you really want to delete the following recipient:<br><br>%s<br>"),$name);
        

    $tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteRecipient('.$idnewsrcp.')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');
    $tpl->next();
}




# Generate template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['recipient_menu']);

?>
