<?php
/**************************************************************************
    FILENAME        :   admin_config.php
    PURPOSE OF FILE :   Manage configuration of site
    LAST UPDATED    :   21 November 2005
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
if( !empty($getmodules) )
{
	$module['Configuration']['Configuration'] = "config";
    $permision['Configuration'] = 0;
	return;
}

if (($check['level'] != 0) && ($check['level'] != 1))
{
 error_message("Sorry, you can't access this section");
}	
$allowed_array = array( 'troopname' => true,
						'disablesite' => true,
						'patrolpage' => true,
						'defaulttheme' => true,
						'cookiename' => true,
						'disablereason' => true,
						'adcode' => true,
						'photos_per_page' => true,
						'session_length' => true,
						'troop_description' => true,
                        'sitemail' => true,
						'numnews' => true,
						'confirmarticle' => true,
						'confirmalbum' => true,
						'confirmphoto' => true,
						'confirmevent' => true,
						'confirmdownload' => true,
						'confirmnews' => true,
						'confirmcomment' => true,
                        'avyx' => true,
                        'avyy' => true,
                        'sigsize' => true,
                        'activetime' => true,
                        'numlatest' => true,
                        'allowtroop' => true,
                        'register' => true,
                        'softdebug' => true,
                        'zone' => true,
                        'postcount' => true,
                        'photoy' => true,
                        'photox' => true,
                        'allowtemplate' => true,
                        'photos_per_row' => true);
                        
$submit = $_POST['Submit'];
$new = array();
if ($submit == "Update Config") 
{
	$result = $data->select_query("config");
	$iserror = false;
	while ( $row = $data->fetch_array($result) )
	{
		$config_name = $row['name'];
		$config_value = $row['value'];
		$default_config[$config_name] = $config_value;
		
		$new[$config_name] = $default_config[$config_name];
        $errorconfig = '';
		if ($allowed_array[$config_name] && isset($_POST[$config_name]) )
		{
			$newvalue = safesql($_POST[$config_name], "text");
            $sql = $data->update_query("config","value = $newvalue", "name = '$config_name'", "", "", false);
		    if (!$sql) 
            {
                $iserror = true;
                $errorconfig .= 'Error with configuration item: ' . $config_name . '<br />';  
            }
        }
	}
   if (!$iserror) 
   {
        echo "<script> alert('Updated configuration'); window.location = '$pagename';</script>\n";
        exit; 
   }
   else
   {
    $message.= "Error updating configuration, errors are:<br / > $errorconfig";
   }
}

$theme_q = $data->select_query("themes");
$theme = array();
$numthemes = $data->num_rows($theme_q);
while ($theme[] =  $data->fetch_array($theme_q));

$sql = $data->select_query("timezones", "ORDER BY offset ASC");
$zone = array();
$numzones = $data->num_rows($sql);
while ($zone[] =  $data->fetch_array($sql));

$config = read_config();
$tpl->assign('configs', $config);
$tpl->assign('theme', $theme);
$tpl->assign('numthemes', $numthemes);
$tpl->assign('zone', $zone);
$tpl->assign('numzones', $numzones);
$filetouse = 'admin_config.tpl';
?>