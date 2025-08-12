<?

    require_once("functions.php");
    echo "</center></td><td valign=top class='mainpageright'>";
    
    include("config.php");
    $link2 = mysql_connect($dbhost,$dbuser,$dbpass);

    mysql_select_db("$dbname");
    $result = mysql_query("select * from boxes where side = 2 order by pos");
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
            $add = 0;
            
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
    
    //Please don't remove the advertisement/copyright thing here so other people can download it
    
    echo "</td></tr></table></div>
    <center>
    <p class='footertext' style='font-size: xx-small; font-family: arial'>SSphp v1.0.5<BR>
    Copyright 2005 - <a href='http://www.lan4all.net'>www.lan4all.net</A></p>
    ";
    
?>