<?php
/**************************************************************************
    FILENAME        :   upgrade.php
    PURPOSE OF FILE :   Upgrades from one version to another (This file: 1.0RC1 to 1.0RC2)
    LAST UPDATED    :   22 November 2005
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
$bit = "./../";
require_once ("../common.php");

$step = isset($_GET['step']) ? $_GET['step'] : 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>CMScout Installation System (Upgrading)</title>
<link href="install.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="content">
<?php 
switch ($step)
{
    case 1:
        if ($config['version'] != "1.00RC2")
        {
?>
You are currently using <strong>CMScout V<?php echo $config['version'] ?></strong> <br />
This upgrade script will upgrade your CMScout to <strong>V1.00RC2</strong><br />
<input type="button" value="Continue" class="button" onclick="window.location='upgrade.php?step=2'" />
<?php
        }
        else
        {
?>
You are already using CMScout <strong>V1.00RC2</strong> <br />
CMScout Upgrade script will not continue.
<?php
        }
        break;
    case 2:
        if ($config['version'] != "1.00RC2")
        { 
            $dbconnection = mysql_connect("$dbhost:$dbport", $dbusername, $dbpassword);
            $selectedb = mysql_select_db($dbname);
            require("upgradedata.php");
            if($dbconnection)
            {
            ?>
                Now upgrading Database.<br />
                Please wait.... <br />               
        <?php
                $numsql = count($sql);
    
                $errors = "";
                $isok = true;
                for($i=0;$i<$numsql;$i++)
                {
                    $st = strip_tags($sql[$i]);
                    $temp = mysql_query($sql[$i]);
                    if ($temp)
                    {
                        echo "<span style=\"color:#168700\">SQL statement $i completed</span><br />";
                    }
                    else
                    {
                        echo "<span style=\"color:#bc0101\">Error with SQL statement $i. The error was: " . mysql_error() . "</span><br />";
                        $isok = false;
                    }
                }
                
                if ($isok)
                {
                    echo "Congratulations. The CMScout database has now been updated to V1.00RC2. Don't forget to update the files too. Please delete the install directory before continuing to use your site.";
                }
                else
                {
                    echo "There was some sort of error while updating the CMScout.";
                }
            }
            else
            {
                echo "Error connection to database. Please make sure that your config.php script is correct";
            }
        }
        else
        {
?>
You are already using CMScout <strong>V1.00RC2</strong> <br />
CMScout Upgrade script will not continue.
<?php
        }
?>



<?php
}
?>
</div>
</body>
</html>