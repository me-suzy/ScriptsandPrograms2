<?php
    
    echo "
    <title>SSPHP setup</title>
    <body style='font-family: tahoma; font-size=12pt'>
    <font size=+1><B>SSphp Setup</B></font><BR><BR>
    ";

    if (!isset($_POST["validate"]))
    {
        echo "
        <form method=post action=install.php>
        <input type=hidden name=validate value=123>
        <table border=0 cellpadding=5 cellspacing=0>
        <tr><td>Admin username</td><td><input type=text name=adminuser size=30></td></tr>
        <tr><td>Admin password</td><td><input type=password name=adminpass size=30></td></tr>
        </table><input type=submit value='Install'></form>
        ";
    }    
    if (isset($_POST["validate"]))
    {
        echo "<hr width=100% style='height: 1px' color='#000000'>";
        
        foreach($_POST as $key=>$val) 
        {
            $$key = $val;
        }
        
        include("config.php");
        $check = 0;
        
        $link = mysql_connect($dbhost,$dbuser,$dbpass);
        
        if (!$link) {
            die("Could not connect: " . mysql_error());
        }
        else
            $check++;
        
        $result = mysql_select_db($dbname);
        if (!$result) {
            echo "Could not select database: " . mysql_error();
            exit();
        }
        //check to make sure not installed
        $checkinstalled = mysql_query("select * from users");
        if ($checkinstalled)
        {
            //not installed
            echo "
            <font size=+1><B>Error</B></font><BR><BR>
            Your SSphp appears to be installed already, please delete 'install.php'
            ";
            exit();
        }
        //--------------------------------
        
        
        
        if (!$result) {
            die ("Can\'t use $dbname : " . mysql_error());
        }
        else
            $check++;
        
        $adminpass = md5($adminpass);
        
        $welcomemessage = "
        Thank you for using SSphp. Please be sure you removed 'install.php'. To set up your site and remove this message, 
        you'll want to <a href='admin.php'>Log In</A><BR>
        ";
    
        //=====================================================================
        $result1 = mysql_query("CREATE TABLE `boxes` (
          `tname` varchar(15) NOT NULL default '',
          `side` int(1) NOT NULL default '0',
          `pos` int(3) NOT NULL default '0',
          `content` blob NOT NULL,
          `id` int(3) NOT NULL auto_increment,
          `admin` int(1) NOT NULL default '0',
          PRIMARY KEY  (`id`)
        ) ;");
        
        if ($result1)
        {
            echo "Added boxes table...<BR>";
            $check++;
        }
        else
            echo "Error creating boxes table: " . mysql_error() . "<BR>";
        //=====================================================================
        $result2 = mysql_query("CREATE TABLE `pages` (
          `sname` varchar(15) NOT NULL default '',
          `content` blob NOT NULL,
          `id` int(3) NOT NULL auto_increment,
          `box` varchar(50) NOT NULL default '',
          `admin` int(1) NOT NULL default '0',
          `perm` int(1) NOT NULL default '0',
          PRIMARY KEY  (`id`)
        ) ;");
        
        if ($result2)
        {
            echo "Added pages table...<BR>";
            $check++;
        }
        else
            echo "Error creating pages table: " . mysql_error() . "<BR>";
        //=====================================================================        
        $result3 = mysql_query("CREATE TABLE `users` (
          `uname` varchar(30) NOT NULL default '',
          `pass` varchar(50) NOT NULL default '',
          `theme` varchar(30) NOT NULL default 'ssphp',
          `modstatus` int(1) NOT NULL default '3'
        ) ;");
        if ($result3)
        {
            echo "Added users table...<BR>";
            $check++;
        }
        else
            echo "Error creating users table: " . mysql_error() . "<BR>";
        //=====================================================================
        $result7 = mysql_query("CREATE TABLE `prefs` (
          `theme` varchar(30) NOT NULL default '',
          `logo` varchar(100) NOT NULL default '',
          `title` varchar(50) NOT NULL default '',
          `owner` varchar(50)
        )");
        if ($result3)
        {
            echo "Added preferences table...<BR>";
            $check++;
        }
        else
            echo "Error creating preferences table: " . mysql_error() . "<BR>";
        //=====================================================================
        $result4 = mysql_query("insert into pages (sname, content, box, perm) values(\"Home\",\"$welcomemessage\",\"Main\",1)");
        if ($result4)
        {
            echo "Added home page...<BR>";
            $check++;
        }
        else
            echo "Error adding home page: " . mysql_error() . "<BR>";
        //=====================================================================
        $result5 = mysql_query("insert into boxes (tname, side, pos, admin) values (\"Main\",1,1,0)");
        if ($result5)
        {
            echo "Added main box...<BR>";
            $check++;
        }
        else
            echo "Error adding main box: " . mysql_error() . "<BR>";
        //=====================================================================
        $result6 = mysql_query("insert into users (uname, pass, modstatus) values (\"$adminuser\",\"$adminpass\",0)");
        if ($result6)
        {
            echo "Added admin info...<BR>";
            $check++;
        }
        else
            echo "Error adding admin info: " . mysql_error() . "<BR>";
        //=====================================================================
        $result8 = mysql_query("insert into prefs (theme,owner,logo) values (\"ssphp\",\"$adminuser\",\"images/logo.png\")");
        if ($result8)
        {
            echo "Added style info...<BR>";
            $check++;
        }
        else
            echo "Error adding style info: " . mysql_error() . "<BR>";
        
        echo "<hr width=100% style='height: 1px' color='#000000'>";

        if ($check == 10)
        {
            echo "Installation Completed<BR><BR>
            <a href='page.php'>Click here to see your new site!</A>";
        }
        else
        {
            echo "Installation Failed, please try correcting the errors above... 
            If there are no errors or you are unable to resolve the issue, <a href='http://www.lan4all.net'>please visit the SSphp website</A> 
            and go to the 'Install Guide' Section<BR><BR>
            <a href='install.php'>Try Again</A>";
        }
    }
?>