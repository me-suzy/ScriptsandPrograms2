<?php
/**************************************************************************
    FILENAME        :   admin_install_theme.php
    PURPOSE OF FILE :   Manage themes
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
if( !empty($getmodules) )
{
	$module['Configuration']['Templates'] = "install_theme";
    $permision['Templates'] = 1;
	return;
}

if ($check['level'] != 1 && $check['level'] != 0)
{
 error_message("Sorry, you can't access this section");
}	

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) 
{
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$action = $_GET['action'];
if ($action == "uninstall")
{
    $id = $_GET['id'];
    $data->update_query("authuser", "theme_id=0", "theme_id=$id", "", "", false);
    if ($config['defaulttheme'] == $id)
    {
        $sql = $data->select_query("themes", "WHERE id != $id");
        if ($data->num_rows($sql) == 0)
        {
            echo "<script> alert('You can\'t delete the last template'); window.location = 'admin.php?page=install_theme';</script>\n";
            exit;            
        }
        else
        {
            $theme = $data->fetch_array($sql);
            $data->update_query("config", "value={$theme['id']}", "name='defaulttheme'", "", "", false);
       }
    }
    $sql = $data->delete_query("themes", "id=$id", "Templates", "Template $id deleted");
    if($sql)
    {
        echo "<script> alert('Template deleted'); window.location = 'admin.php?page=install_theme';</script>\n";
        exit;
    }
}
elseif ($action == "install")
{
    $dir = $_GET['id'];
    $newtemplatedir = dir( "../templates/".$dir);
    while (false !== ($template =$newtemplatedir->read()))
    {
        if (strpos($template, '.cfg') !== false)
        {
            $file  = fopen("../templates/$dir/$template", "r");
            if ($file == NULL)
            {
                echo "ERROR";
            }
            
            fscanf($file, "%s", $check);
            if ($check == "<"."?php")
            {
                fscanf($file, "%s", $check);
            }
            if ($check == "//CMScoutTemplate")
            {
                fclose($file);
                include ("../templates/$dir/$template");
                $name = safesql($templateinfo['theme_name'], "text");
                $directory = safesql($dir, "text");
                $cfgfilename = safesql($template, "text");
                if ($data->insert_query("themes", "'', $name, $directory, $cfgfilename", "Templates", "Installed new template {$templateinfo['theme_name']}"))
                {
                    echo "<script> alert('{$templateinfo['theme_name']} is now installed'); window.location = 'admin.php?page=install_theme';</script>\n";
                    exit;
                }
            }
        }
    }
}
else $action = "";

$sql = $data->select_query("themes");
$numinstalledthemes = $data->num_rows($sql);
$installedthemes = array();
$themenames = array();
while($temp = $data->fetch_array($sql))
{
    $themedirs[] = $temp['dir'];
    $installedthemes[] = $temp;
}

$themedir = dir("../templates");
$notinstalled = array();
$i = 0;
while (false !== ($entry = $themedir->read()))
{
    if (strrpos($entry, '.') === false && $entry !== "admin")
    {
        if(in_array($entry, $themedirs))
        {
            continue;
        }
        $currentthemedir = dir("../templates/$entry");
        while (false !== ($template = $currentthemedir->read()))
        {
            if (strpos($template, '.cfg') !== false)
            {
                $file  = fopen($currentthemedir->path."/".$template, "r");
                if ($file == NULL)
                {
                    echo "ERROR";
                }
                
                fscanf($file, "%s", $check);
                if ($check == "<"."?php")
                {
                    fscanf($file, "%s", $check);
                }
                if ($check == "//CMScoutTemplate")
                {
                    fclose($file);
                    include ($currentthemedir->path."/".$template);
                    $notinstalled[$i]['theme_name'] = $templateinfo['theme_name'];
                    $notinstalled[$i]['directory'] = $entry;
                    $i++;
                }
            }
        }
    }
}

$tpl->assign("notinstalled", $notinstalled);
$tpl->assign("numnotinstalled", $i);
$tpl->assign("numinstalledthemes", $numinstalledthemes);
$tpl->assign("installedthemes", $installedthemes);
$tpl->assign("action", $action);
$filetouse = "admin_install_theme.tpl";
?>

