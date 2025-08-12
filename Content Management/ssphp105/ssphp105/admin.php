<?php
    require_once("topheader.php");
    require_once("config.php");
    //require_once("footer.php");
    
    if (isset($_SESSION['login']) == false)
    {
        require_once("header.php");
        echo lgbox("Admin Log-in","<center>$bad<BR>Please log in<BR><BR>
        <table border=0 cellpadding=0 cellspacing=5>
        <form method=post action='/login.php'>
        <tr><td>Username</td><td><input type=text size=15 maxlength=25 name=uname></td></tr>
        <tr><td>Password</td><td><input type=password size=15 maxlength=50 name=upass></td></tr>
        </table>
        <input type=Submit value='Log In'>
        </form>
        </center>
        <BR><BR>");
        //loginpage
        
        include("footer.php");
        
        exit();
    }
    
    
    
    
    
    if (($_POST["func"] != "")&&($_SESSION["login"] <= 1))
    {
    
        require_once("config.php");
        
        foreach($_POST as $key=>$val) 
        {
            $$key = $val;
        }
        if ($func == "pagebox")
        {
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            
            $result2 = mysql_query("update pages set box = \"$boxlist\" where id = $pageid");
            
            mysql_close($link);
        }
        
        
        if ($func == "add")
        {
            
            $content = htmlspecialchars(stripslashes($content));
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from boxes where side = $lr order by pos");
            $left = 0;
            while ($row = mysql_fetch_array($result,MYSQL_BOTH))
            {
                $left = $row["pos"];
            }
            if ($admin == "on")
                $admin = 1;
            else
                $admin = 0;
            $left++;
            //echo "tname: $tname<BR>lr: $lr<BR>pos: $left<BR>content:$content<BR><BR>";
            $result2 = mysql_query("insert into boxes (tname, side, pos, content, admin) values (\"$tname\", $lr, $left, \"$content\", $admin)");

            mysql_close($link);
        }
        if ($func == "prefs")
        {
            
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            
            $result1 = mysql_query("update prefs set theme = \"$stheme\"");
            $result2 = mysql_query("update prefs set logo = \"$slogo\"");
            $result3 = mysql_query("update prefs set title = \"$stitle\"");
            
            mysql_close($link);
        }
        
        if ($func == "addpage")
        {
            
            $content = htmlspecialchars(stripslashes($content));
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            //echo "tname: $tname<BR>lr: $lr<BR>pos: $left<BR>content:$content<BR><BR>";
            $result2 = mysql_query("insert into pages (sname, content, box) values (\"$sname\", \"$content\",\"$boxlist\")");
            mysql_close($link);
        }
        
        if ($func == "edit")
        {
            
            $content = htmlspecialchars(stripslashes($content));
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result2 = mysql_query("update boxes set tname = \"$tname\" where id = $id");
            $result3 = mysql_query("update boxes set content = \"$content\" where id = $id");

            mysql_close($link);
        }
        
        if ($func == "pedit")
        {
            
            $content = htmlspecialchars(stripslashes($content));
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result2 = mysql_query("update pages set sname = \"$sname\" where id = $id");
            $result3 = mysql_query("update pages set content = \"$content\" where id = $id");
            mysql_close($link);
        }
        
        if ($func == "adduser")
        {
            //uname,pwd,access
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from users where uname = \"$uname\"");
            if (mysql_num_rows($result) > 0)
            {
                $addmsg = "<BR>Unable to add username, user already exists<BR>";
            }
            else
            {
                $pwd = md5($pwd);
                mysql_query("insert into users (uname,pass,modstatus) values (\"$uname\",\"$pwd\",$access)");
                $addmsg = "<BR>Added $uname successfully<BR>";
            }
        }
        
        
        prune();
    }
    
    if (($_GET["func"] != "")&&($_SESSION["login"] <= 1))
    {
        require_once("header.php");
        foreach($_GET as $key=>$val) 
        {
            $$key = $val;
        }
        if ($func == "switch")
        {
            $content = htmlspecialchars(stripslashes($content));
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from boxes where id = $id");
            $row = mysql_fetch_array($result);
            $curside = $row["side"];
            if ($curside == 1)
                $nside = 2;
            else
                $nside = 1;
            //get new pos 
            $result = mysql_query("select * from boxes where side = $nside order by pos");
            $left = 0;
            while ($row = mysql_fetch_array($result,MYSQL_BOTH))
            {
                $left = $row["pos"];
            }
            $left++;
            
            $result3 = mysql_query("update boxes set side = $nside where id = $id");
            $result4 = mysql_query("update boxes set pos = $left where id = $id");
            if (($result3)&&($result4))
            {   
                $message = "Switched Box<BR>";
            }
            mysql_close($link);
        }
        if ($func == "up")
        {
            //get current pos 
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from boxes where id = $id");
            $row = mysql_fetch_array($result);
            $curside = $row["side"];
            $curpos = $row["pos"];
            $newpos = $curpos - 1;
            //check to see if other
            
            $result2 = mysql_query("select * from boxes where side=$curside and pos=$newpos");
            if (mysql_num_rows($result2) > 0)
            {
                //swapping
                $row2 = mysql_fetch_array($result2,MYSQL_BOTH);
                $bid = $row2["id"];
                $result3 = mysql_query("update boxes set pos=$curpos where id=$bid");
            }
            $result4 = mysql_query("update boxes set pos=$newpos where id=$id");
        }
        
        if ($func == "down")
        {
            //get current pos 
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from boxes where id = $id");
            $row = mysql_fetch_array($result);
            $curside = $row["side"];
            $curpos = $row["pos"];
            $newpos = $curpos + 1;
            //check to see if other
            
            $result2 = mysql_query("select * from boxes where side=$curside and pos=$newpos");
            if (mysql_num_rows($result2) > 0)
            {
                //swapping
                $row2 = mysql_fetch_array($result2,MYSQL_BOTH);
                $bid = $row2["id"];
                $result3 = mysql_query("update boxes set pos=$curpos where id=$bid");
            }
            $result4 = mysql_query("update boxes set pos=$newpos where id=$id");
            mysql_close($link);
        }
        if ($func == "delete")
        {
            //get current pos 
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("delete from boxes where id = $id");
            mysql_close($link);
        }
        if ($func == "pdelete")
        {
            if ($id != 0)
            {
                //get current pos 
                $link = mysql_connect($dbhost,$dbuser,$dbpass);
                mysql_select_db($dbname);
                $result = mysql_query("delete from pages where id = $id");
                mysql_close($link);
            }
        }
            
        if ($func == "edit")
        {
        
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from boxes where id = $id");
            $row = mysql_fetch_array($result);
            $tname = $row["tname"];
            $contents = html_entity_decode($row["content"]);
            
                $eaox = 
                "
                <BR><center>
                <form method=post action='admin.php'>
                Name <input type=text name='tname' size=15 maxlength=30 value='$tname'> 
                <BR>
                <textarea name='content' rows=10 cols=50>$contents</textarea><BR>
                <input type=submit value='Edit Box'>
                <input type=hidden name=id value=$id>
                <input type=hidden name=func value='edit'>
                </form></center>
                ";
                
            $edityes = 1;
            
            mysql_close($link);
        }
        if ($func == "pedit")
        {
        
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            $result = mysql_query("select * from pages where id = $id");
            $row = mysql_fetch_array($result);
            $sname = $row["sname"];
            $contents = html_entity_decode($row["content"]);
            
                $eaox = 
                "
                <BR><center>
                <form method=post action='admin.php'>
                Name <input type=text name='sname' size=15 maxlength=30 value='$sname'> 
                <BR>
                <textarea name='content' rows=10 cols=50>$contents</textarea><BR>
                <input type=submit value='Edit Box'>
                <input type=hidden name=id value=$id>
                <input type=hidden name=func value='pedit'>
                </form></center>
                ";
                
            $pedityes = 1;
            
            mysql_close($link);
        }
        if ($func == "lk")
        {
            
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            
            $result = mysql_query("select * from boxes where id = $id");
            $row = mysql_fetch_array($result,MYSQL_BOTH);
            $admin = $row["admin"];
            if ($admin == 1)
                $newadmin = 2;
            else if ($admin == 2)
                $newadmin = 0;
            else
                $newadmin = 1;
            $result2 = mysql_query("update boxes set admin = $newadmin where id = $id");


            mysql_close($link);
        }
        
        if ($func == "plk")
        {
            
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            
            $result = mysql_query("select * from pages where id = $id");
            $row = mysql_fetch_array($result,MYSQL_BOTH);
            $admin = $row["admin"];
            if ($admin == 1)
                $newadmin = 2;
            else if ($admin == 2)
                $newadmin = 0;
            else
                $newadmin = 1;
            $result2 = mysql_query("update pages set admin = $newadmin where id = $id");


            mysql_close($link);
        }
        if ($func == "deluser")
        {
            $ptdel = $_GET["uname"];
            $link = mysql_connect($dbhost,$dbuser,$dbpass);
            mysql_select_db($dbname);
            
            $result = mysql_query("select * from prefs");
            $row = mysql_fetch_array($result,MYSQL_BOTH);
            
            if ($row["owner"] == $ptdel)
            {
                $addmsg = "<BR>Unable to delete $ptdel, they are the site owner<BR>";
            }
            else
            {
                $result2 = mysql_query("delete from users where uname = \"$ptdel\"");
                if ($result2) 
                    $addmsg = "<BR>Deleted $ptdel successfully<BR>";
            }

            mysql_close($link);
        }
        
        prune();
    }
    
    require_once("header.php");
    if ($message != "")
        echo $message;
    unset($message);
    
    if ($edityes == 1)
    {
        echo lgbox("Edit Box",$eaox);
    }
    if ($pedityes == 1)
    {
        echo lgbox("Edit Page",$eaox);
    }
    
    $baox = 
    "
    <BR><center>
    <form method=post action='admin.php'>
    Name <input type=text name='tname' size=15 maxlength=30> 
    <select name=lr>
    <option selected value=1>Left</option>
    <option value=2>Right</option></select> <input type=checkbox name=admin>Admin
    <BR>
    <textarea name='content' rows=10 cols=50></textarea><BR>
    <input type=submit value='Add Box'>
    <input type=hidden name=func value='add'>
    </form></center>
    ";
    
    
    include("config.php");
        $link = mysql_connect($dbhost,$dbuser,$dbpass);
    mysql_select_db($dbname);
    
    $boxes = "";
    
    $content = "";
    
    $boxlist = "<select name='boxlist'>";
    
    $result = mysql_query("select * from boxes order by side, pos"); 
    while ($row = mysql_fetch_array($result,MYSQL_BOTH))
    {
        $tname = $row["tname"];
        $id = $row["id"];
        $pos = $row["pos"];
        $side = $row["side"];
        $admin = $row["admin"];
        $boxlist .= "<option value='$tname'>$tname</option>";
        if ($admin == 2)
        {
            //locked
            $adminimg = "
            <a href='admin.php?func=lk&id=$id'>
            <img src='images/icon_arrow.gif' alt='This section can only be viewed by the adminstrator, click to make public' border=0>
            </A>";
        }
        else if ($admin == 1)
        {
            //locked
            $adminimg = "
            <a href='admin.php?func=lk&id=$id'>
            <img src='images/icon_arrow_blue.gif' alt='This can be viewed by adminstrators and moderators, click to enable admin only' border=0>
            </A>";
        }
        else
        {
            $adminimg = "
            <a href='admin.php?func=lk&id=$id'>
            <img src='images/icon_arrow_grey.gif' alt='This section can be viewed by the anyone, click to protect' border=0>
            </A>";
        
        }
            
        if ($side == 1)
            $dside = "left";
        else
            $dside = "right";
    
        
        $content .= "<tr><td>$adminimg</td><td>$tname</td><td>$dside <a href='admin.php?func=switch&id=$id'>[switch]</A></td><td>
        $pos <a href='admin.php?func=up&id=$id'>[Up]</A><a href='admin.php?func=down&id=$id'>[Down]</A></td><td>
        <a href='admin.php?func=edit&id=$id'>[edit]</a></td><td>
        <a href='admin.php?func=delete&id=$id'>[delete]</A></td></tr>
        ";
    }

    
    $caox = 
    "
    <BR><center>
    <table border=0 cellpadding=5 border=1 bordercolor='ffffff' cellspacing=2>
    <tr><td></td><td>Name</td><td>Side</td><td>Position</td><td>Edit</td><td>Delete</td></tr>
    $content
    </table>
    ";
    
    
    $pcontent = "";
    $result = mysql_query("select * from pages order by sname"); 
    while ($row = mysql_fetch_array($result,MYSQL_BOTH))
    {
        $id = $row["id"];
        $name = $row["sname"];
        $admin = $row["admin"];
        $box = $row["box"];
        $perm = $row["perm"];
        if ($perm == 1)
            $deltext = "";
        else
            $deltext = "[delete]";
        
        $newbox = $boxlist . "<option selected value='$box'>$box</option></select>";

        if ($admin == 2)
        {
            //locked
            $adminimg = "
            <a href='admin.php?func=plk&id=$id'>
            <img src='images/icon_arrow.gif' alt='This section can only be viewed by the adminstrator, click to make public' border=0>
            </A>";
        }
        else if ($admin == 1)
        {
            //locked
            $adminimg = "
            <a href='admin.php?func=plk&id=$id'>
            <img src='images/icon_arrow_blue.gif' alt='This can be viewed by adminstrators and moderators, click to enable admin only' border=0>
            </A>";
        }
        else
        {
            $adminimg = "
            <a href='admin.php?func=plk&id=$id'>
            <img src='images/icon_arrow_grey.gif' alt='This section can be viewed by the anyone, click to protect' border=0>
            </A>";
        
        }
        $stu = "valign=bottom";
        
        $pcontent .= "
        <form method=post action=admin.php>
        <input type=hidden name=func value='pagebox'>
        <input type=hidden name=pageid value='$id'>
        <tr>
        <td $stu>$adminimg</TD>
        <td $stu>$name</td>
        <td $stu><a href='admin.php?func=pedit&id=$id'>[Edit]</A></td>
        <td $stu><a href='admin.php?func=pdelete&id=$id'>$deltext</A></td>
        <td $stu> $newbox <input type=submit value='Move'></td>
        </tr></form>";
    }
    
    $anotherbox = $boxlist . "</select>";
    
    $paox = 
    "
    <center>
    <BR>
    <form method=post action='admin.php'>
    Page Name <input type=text name='sname' size=15 maxlength=30> 
    <BR>Box $anotherbox<BR>
    <textarea name='content' rows=10 cols=50></textarea><BR>
    <input type=submit value='Add Page'>
    <input type=hidden name=func value='addpage'>
    </form>
    <BR>
    <table border=0 cellpadding=5 cellspacing=0>
    <tr><td></td><td>Name</td><td>Edit</td><td>Delete</td><td>Box</td></tr>
    $pcontent
    </table>
    </center>
    ";
    $baox .= $caox;
    
    $uhresult = mysql_query("select * from prefs");
    $uhrow = mysql_fetch_array($uhresult);
    $curtheme = $uhrow["theme"];
    $curtitle = $uhrow["title"];
    $curlogo = $uhrow["logo"];
    
    $themeoption = "<select name='stheme'><option selected value='$curtheme'>$curtheme</option>";
    $dir = "themes/";

    // Open a known directory, and proceed to read its contents
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ((filetype($dir . $file) == "dir")&&($file != ".")&&($file != ".."))
                    $themeoption .= "<option value='$file'>$file</option>\n";
            }
            closedir($dh);
        }
    }
    $themeoption .= "</select>";

    
    
    
    $pprefs = "
    <center>
    <form method=post action='admin.php'>
    <input type=hidden name=func value='prefs'>
    <table border=0 cellpadding=5 cellspacing=0>
    <tr><td>Site Title</td><td><input type=text size=20 name=stitle value='$curtitle'></td></tr>
    <tr><td>Logo URL</td><td><input type=text size=20 name=slogo value='$curlogo'></td></tr>
    <tr><td>Site Theme</td><td>$themeoption</td></tr>
    </table>
    <input type=submit value='Update'></form></center>";
    
    $uusers = "
    <center>
    <BR>Add user $addmsg
    <form method=post action='admin.php'>
    <input type=hidden name=func value='adduser'>
    <table border=0 cellpadding=5 cellspacing=0>
    <tr><td>Username</td><td><input type=text size=20 name='uname'></td></tr>
    <tr><td>Password</td><td><input type=password size=20 name='pwd'></td></tr>
    <tr><td>Access</td><td><input type=radio name=access value=1>Admin <input type=radio name=access value=2>Moderator</td></tr> 
    </table>
    <input type=submit value='Add'></form>
    <BR><BR>
    Delete User
    <table border=0 cellpadding=5 cellspacing=0>
    <tr><td>Username</td><td>Access Lvl</td><td></td></tr>";
    
    $reresult = mysql_query("select * from users");
    $num = 0;
    while ($rerow = mysql_fetch_array($reresult,MYSQL_BOTH))
    {
        $reuser = $rerow["uname"];
        
        if ($rerow["modstatus"] == 0)
            $reacc = "Site Owner";
        else if ($rerow["modstatus"] == 1)
            $reacc = "Admin";
        else
            $reacc = "Moderator";
        
        $redel = "<a href='admin.php?func=deluser&uname=$reuser'>[delete]</A>";
        $uusers .= "<tr><td>$reuser</td><td>$reacc</td><td>$redel</td></tr>";
    }
    $uusers .= "</table></center>";
    
    if (($_SESSION["login"] == 0)||($_SESSION["login"] == 1))
        echo lgbox("Site Boxes",$baox);
        
    if (($_SESSION["login"] == 0)||($_SESSION["login"] == 1))
        echo lgbox("Site Pages",$paox);
    
    if (($_SESSION["login"] == 0)||($_SESSION["login"] == 1))
        echo lgbox("Preferences",$pprefs);
    
    if ($_SESSION["login"] == 0)
        echo lgbox("User Admin",$uusers);    
        
    if ($_SESSION["login"] == 2)
        echo lgbox("Moderator","As a moderator you have access to certain pages but no control over adding or editing site content");
    
    function prune()
    {
        include("config.php");
        $link = mysql_connect($dbhost,$dbuser,$dbpass);
        mysql_select_db($dbname);
            
        $num = 1;
        while ($num < 3)
        {
            $pos = 1;
            $result222 = mysql_query("select * from boxes where side = $num order by pos");
            while ($row = mysql_fetch_array($result222,MYSQL_BOTH))
            {
                $id = $row["id"];
                $result2 = mysql_query("update boxes set pos = $pos where id = $id");
                $pos++;
            }
            $num++;
        }
        mysql_close($link);
    }
    
    include("footer.php");
?>