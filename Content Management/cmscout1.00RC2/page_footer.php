<?php
/**************************************************************************
    FILENAME        :   page_footer.php
    PURPOSE OF FILE :   Displays copyright message
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
$date = getdate();
$year = $date['year'];
$tpl->assign("sitmail", $config['sitemail']);
$copyright = "Website content &copy;$year {$config['troopname']}<br />";
$copyright .= "The views expressed in the website are not necessarily those of the Scout Association<br />";
$copyright .= "If you have any problems contact the {mailto address=\$sitmail encode=hex text=\"webmaster\"}<br />";
$copyright .= "Powered by CMScout V{$config['version']} &copy;2005 CMScout Group";
if ($config['softdebug'] == 1)
{
    $endtime = microtime();
    $totaltime = $endtime - $starttime;
    $counter = $data->get_counter();
    $copyright .= "<br />This page took $totaltime seconds to render<br />CMScout performed $counter database queries";
}
$tpl->assign('copyright', $copyright);
?>