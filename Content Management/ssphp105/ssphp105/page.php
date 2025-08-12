<?php
    require_once("topheader.php");
    require_once("header.php");
    
    $id = $_GET["id"];
    if ($id == "")
    {
        $id = 1;
    }

        $link = mysql_connect($dbhost,$dbuser,$dbpass);
        mysql_select_db($dbname);
        $result = mysql_query("select * from pages where id = $id");
        if (!$result)
        {
            echo "Invalid ID";
        }
        else
        {
            while ($row = mysql_fetch_array($result,MYSQL_BOTH))
            {
            
                $badmin = $row["admin"];
                $sname = $row["sname"];
                $content = html_entity_decode($row["content"]);
            
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
            
                $sname = $row["sname"];
                $content = html_entity_decode($row["content"]);
                if ($add == 1)
                    echo lgbox("$sname","$content");
                else
                    require_once("admin.php");
            }
        }
        mysql_close($link);
    
    
    
    require_once("footer.php");
?>