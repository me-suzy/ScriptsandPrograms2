<?php

    require_once("config.php");
    require_once("functions.php");
    
    $notinstalled = "
        <title>SSPHP</title>
        <body style='font-family: tahoma; font-size=12pt'>
        <font size=+1><B>SSphp</B></font><BR><BR>
        Your SSphp is either misconfigured or not installed.  To install it, 
        <a href='install.php'>click here</a>
        ";
    
    $link = mysql_connect($dbhost,$dbuser,$dbpass);
    if (!$link) {
        echo $notinstalled;
        exit();
    }
    
    //check for installation
    $result = mysql_select_db($dbname);
    if (!$result) {
        echo $notinstalled;
        exit();
    }
    
    $checkinstalled = mysql_query("select * from users");
    if (!$checkinstalled)
    {
        //not installed
        echo $notinstalled;
        exit();
    }
    //----
    
    if (!$link) {
        $theme = "themes/ssphp";
        $title = "SSphp";
        $logo = "images/logo.png";
        
        echo "  
        <title>$title</title>      
        <link href=\"/$theme/style.css\" type=\"text/css\" rel=\"stylesheet\" />
        <br><br><center>
        <div class='pagewidth'>
            <img src='$logo'>
            <table border=0 cellpadding=2 cellspacing=0 class='mainpage'>
                <tr>
                    <td valign=top class='mainpageleft'>
                        &nbsp;
                    </td>
                    <td class='mainpagecenter'>" . lgbox("Database error",'Could not connect: ' . mysql_error()) . 
        "           </td>
                    <td valign=top class='mainpageright'>&nbsp;</td>
                </tr>
            </table>
        </div>";
        die();
        
       //die('Could not connect: ' . mysql_error());
    }
    
    $result = mysql_query("select * from prefs");
    $row = mysql_fetch_array($result,MYSQL_BOTH);
    
    $theme = "themes/" . $row["theme"];
    $title = $row["title"];
    $logo = $row["logo"];

    echo "  
    <title>$title</title>      
    <link href=\"/$theme/style.css\" type=\"text/css\" rel=\"stylesheet\" />
    <br>
    <center>
    <div class='pagewidth'>
        <img src='$logo'>
        <table border=0 cellpadding=5 cellspacing=0 class='mainpage'>
            <tr>
                <td valign=top class='mainpageleft'>";
    
    //boxes on the left
    
    $tresult = mysql_query("select * from pages order by sname");
    
    if (isset($_SESSION['login']))
    {
        $adminvar = "<a href='admin.php'>Admin Page</A><BR>
                     <a href='logout.php'>Logout</A><BR>";
    }
    else
    {
        $adminvar = "";
    }
    
    $result = mysql_query("select * from boxes where side = 1 order by pos");
    while ($row = mysql_fetch_array($result,MYSQL_BOTH))
    {
        $title = $row["tname"];
        
        $tvar = "";
        $result2 = mysql_query("select * from pages where box = \"$title\" order by sname");
        while($trow = mysql_fetch_array($result2,MYSQL_BOTH))
        {
            $tid = $trow["id"];
            $tname = $trow["sname"];
            $badmin = $trow["admin"];

            if ($badmin == 0)
            {
                $add = 1;
            }
            else if (($badmin != 0)&&(!isset($_SESSION["login"])))
            {
                $add = 0;
            }                
            else if ($badmin == 1)
            {
                if ($_SESSION['login'] == 1)
                    $add = 1;
                if ($_SESSION['login'] == 2)
                    $add = 1;
                if ($_SESSION['login'] == 0)
                    $add = 1;
            }
            else if ($badmin == 2)
            {
                //echo $_SESSION['login'] . "<BR>";
                if ($_SESSION['login'] == 0)
                    $add = 1;
                if ($_SESSION['login'] == 1)
                    $add = 1;
            }
            if ($add == 1)
                $tvar .= "<LI><a href='/page.php?id=$tid'>$tname</A><BR>";
        
        }
        $content = html_entity_decode($row["content"]);
        $badmin = $row["admin"];
        $content = $tvar . $content;
        
        //echo "badmin: $badmin<BR>";
        
        if ($badmin == 0)
        {
            echo smbox("$title","$content"); 
            echo "<BR>";
        }        
        else if (($badmin == 1)&&(isset($_SESSION["login"])))
        {
            if ($_SESSION['login'] == 0)
                echo smbox("$title","$content");        
            if ($_SESSION['login'] == 1)
                echo smbox("$title","$content");
            if ($_SESSION['login'] == 2)
                echo smbox("$title","$content");
            echo "<BR>";
        }
        else if (($badmin == 2)&&(isset($_SESSION["login"])))
        {
            if ($_SESSION['login'] == 0)
                echo smbox("$title","$content");
            if ($_SESSION['login'] == 1)
                echo smbox("$title","$content");
            echo "<BR>";
        }
        

    }
    
    echo $adminvar;
    
    echo "</td><td valign=top class='mainpagecenter'><center>";
    
    //middle page
    
?> 