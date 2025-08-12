<?php

    function smbox($title,$content)
    {
        $retval = "
            <table border=0 cellpadding=0 cellspacing=0> 
                <tr>
                    <td class='smtop' valign=top>
                        <div class='smtopboxoffset'>
                            <center>$title</center>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='smmid' valign=top> 
                        <div class ='smmidboxoffset'>
                            $content
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='smbot'>&nbsp;</td>
                </tr>
            </table>
            ";//<tr><td background='smbot.jpg' height=36>&nbsp</td></tr>
        
        return $retval;
    }

    function lgbox($title,$content)
    {
        $retval = "
            <table width=511 border=0 cellpadding=0 cellspacing=0> 
                <tr>
                    <td class='lgtop'>
                        <div class='lgtopboxoffset'>
                            <center>$title</center>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='lgmid'>
                        <div class='lgmidboxoffset'>
                            $content
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class='lgbot'>
                    </td>
                </tr>
            </table>
            <BR>
            ";
        return $retval;
    }
    
    function adminprotect()
    {
        if (!isset($_SESSION['login']))
        {
            include('admin.php');
            die();
        }
        if (($_SESSION['login'] != 1)&&($_SESSION['login'] != 0))
        {
            include('admin.php');
            die();
        }
    }
    function modprotect()
    {
        if (!isset($_SESSION['login']))
        {
            include('admin.php');
            die();
        }
        if (($_SESSION['login'] != 1)&&($_SESSION['login'] != 0)&&($_SESSION['login'] != 2))
        {
            include('admin.php');
            die();
        }
    }
    function ownerprotect()
    {
        if (!isset($_SESSION['login']))
        {
            include('admin.php');
            die();
        }
        if ($_SESSION['login'] != 0)
        {
            include('admin.php');
            die();
        }
    }
    function basedir()
    {
        global $dbhost,$dbuser,$dbpass,$dbname;
        $link = mysql_connect($dbhost,$dbuser,$dbpass);
        mysql_select_db($dbname);
        $result = mysql_query("select * from prefs");
        $row = mysql_fetch_array($result,MYSQL_BOTH);
        $retval = $row["basedir"];
        mysql_close($link);
        return $retval;
    }
    
?>