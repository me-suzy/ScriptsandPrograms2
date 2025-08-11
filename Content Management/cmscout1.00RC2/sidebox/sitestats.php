<?php
/**************************************************************************
    FILENAME        :   sitestats.php
    PURPOSE OF FILE :   Sidebox: Shows stats for site
    LAST UPDATED    :   01 November 2005
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
            if ($check['lastlogged'] > 0)
            {
                $query_article = $data->select_query("patrol_articles","WHERE date_post > {$check['lastlogged']}");
            }
            else
            {
                $query_article = $data->select_query("patrol_articles");
            }
            $numarticle = $data->num_rows($query_article);
            $userpatrol = $check['team'];
            $tpl->assign('numarticles', $numarticle);
            
            
            $total_pointsq = $data->select_query("patrolpoints");
            if ($userpatrol) 
            {	
                $personal_pointsq = $data->select_query("patrolpoints",  "WHERE Patrolname='{$check['team']}'");
                $patrolpoints = $data->fetch_array($personal_pointsq);
            }
            $totalpoints = 0;
            while ($points = $data->fetch_array($total_pointsq)) 
            {
             $totalpoints = $totalpoints + $points['Points'];
            }
            if ($totalpoints != 0) {
                $percentage = ceil(($patrolpoints['Points'] / $totalpoints)*100);
            }
        $query_article = $data->select_query("patrol_articles");
        $totarticle = $data->num_rows($query_article);
    
        $tpl->assign('totalarticles', $totarticle);

?>