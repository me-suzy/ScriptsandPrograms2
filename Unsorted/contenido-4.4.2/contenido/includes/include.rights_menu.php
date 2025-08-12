<?php
/******************************************
* File      :   include.rights_menu.php
* Project   :   Contenido
* Descr     :   Displays languages
*
* Author    :   Olaf Niemann
* Created   :   23.04.2003
* Modified  :   23.04.2003
*
* © four for business AG
*****************************************/


$users = new Users();

$tpl->reset();
$tpl->set('s', 'SID', $sess->id);

if (($action == "user_delete") && ($perm->have_perm_area_action($area, $action))) {

   $users->deleteUserByID($userid);
   
   $sql = "DELETE FROM "
			.$cfg["tab"]["groupmembers"]."
				WHERE user_id = '". $userid."'";
	$db->query($sql);
	
	 $sql = "DELETE FROM ".
   			$cfg["tab"]["rights"].
   			" WHERE user_id = \"" .$userid."\"";
   			
   $db->query($sql);
   
  
          
}

$sql = "SELECT
            username, realname, user_id, perms
        FROM
            ".$cfg["tab"]["phplib_auth_user_md5"]."
        ORDER BY
            username ASC";

if ($restriction == 1)
{
	 $sql = "SELECT
            A.username AS username, A.realname AS realname, A.user_id as user_id, A.perms as perms
        FROM
            ".$cfg["tab"]["phplib_auth_user_md5"]." AS A,
            ".$cfg["tab"]["rights"]." AS B,
			".$cfg["tab"]["actions"]." AS C
        WHERE
        	C.name = 'front_allow' AND
			B.user_id = A.user_id AND
        	C.idaction = B.idaction AND
			A.perms LIKE ''
		GROUP BY
			user_id
        ORDER BY
            username ASC";
            
}

if ($restriction == 3)
{
	 $sql = "SELECT
            A.username AS username, A.realname AS realname, A.user_id as user_id, A.perms as perms
        FROM
            ".$cfg["tab"]["phplib_auth_user_md5"]." AS A,
            ".$cfg["tab"]["rights"]." AS B,
			".$cfg["tab"]["actions"]." AS C
        WHERE
        	C.name NOT LIKE 'front_allow' AND
			B.user_id = A.user_id AND
        	C.idaction = B.idaction AND
			A.perms NOT LIKE ''
		GROUP BY
			user_id
        ORDER BY
            username ASC";
}
$db->query($sql);


// Empty Row
$bgcolor = '#FFFFFF';
$tpl->set('s', 'PADDING_LEFT', '10');
$thisperm = split(",", $auth->auth["perm"]);

$accessibleClients = $classclient->getAccessibleClients();

while ($db->next_record())
{

    $userperm = split(",", $db->f("perms"));

    $allow = false; 
    
    // Sysadmin check
    if (in_array("sysadmin", $thisperm))
    {
        $allow = true;
    }

    // Admin check
    foreach ($accessibleClients as $key => $value)
    {
        if (in_array("client[".$key."]", $userperm))
        {
            $allow = true;
        }
    }
    
    // User check
    foreach ($userperm as $localperm)
    {
        
        if (in_array($localperm, $thisperm))
        {
            $allow = true;
        }

    }

    if ($allow == true)
    {

        $dark = !$dark;
        if ($dark) {
            $bgColor = $cfg["color"]["table_dark"];
        } else {
            $bgColor = $cfg["color"]["table_light"];
        }

        $userid = $db->f("user_id");
        $username = $db->f("username");
        $realname = $db->f("realname");

        $tmp_mstr = '<a href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">%s</a>';
        $area = "user";
        $mstr = sprintf($tmp_mstr, 'right_top',
                                       $sess->url("main.php?area=$area&frame=3&userid=$userid"),
                                       'right_bottom',
                                       $sess->url("main.php?area=user_overview&frame=4&userid=$userid"),
                                       $realname . "<br>(". $username . ")");

        if ($perm->have_perm_area_action('user',"user_delete") ) { 
        		$message = sprintf(i18n("Do you really want to delete the user %s?"), $username);
                $deletebutton = "<a onClick=\"event.cancelBubble=true;check=confirm('".$message."'); if (check==true) { location.href='".$sess->url("main.php?area=user&action=user_delete&frame=$frame&userid=$userid&del=")."#deletethis'};\" href=\"#\"><img src=\"".$cfg['path']['images']."delete.gif\" border=\"0\" width=\"13\" height=\"13\" alt=\"".i18n("Delete user")."\" title=\"".i18n("Delete user")."\"></a>";
            } else {
                $deletebutton = "";
            }

        $tpl->set('d', 'BGCOLOR', $bgColor);
        $tpl->set('d', 'TEXT', $mstr);

 		$delTitle = i18n("Delete user");
    	$delDescr = sprintf(i18n("Do you really want to delete the following user:<br><br>%s<br>"),$username);
        

    	$tpl->set('d', 'DELETE', '<a title="'.$delTitle.'" href="javascript://" onclick="box.confirm(\''.$delTitle.'\', \''.$delDescr.'\', \'deleteUser(\\\''.$userid.'\\\')\')"><img src="'.$cfg['path']['images'].'delete.gif" border="0" title="'.$delTitle.'" alt="'.$delTitle.'"></a>');

        $tpl->next();
    }
}



# Generate template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['rights_menu']);

?>
