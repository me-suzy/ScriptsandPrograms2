<?php
ob_start();
session_start();
$browser_string=$HTTP_USER_AGENT;
$client_ip=gethostbyname(localhost);     
if(session_is_registered("whossession"))      
{        
    $_SESSION['who']="admin";        
    $_SESSION['level']="superadmin";      
}      
else      
{        
    session_register("whossession");        
    $_SESSION['who']="admin";        
    $_SESSION['level']="superadmin";      
}?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>  
<title>Baal Smart Form</title>
<link rel="STYLESHEET" type="text/css" href="helpdeskrevolutions.css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0">
<table border="0" cellspacing="0" width="100%">
    <tr><td bgcolor="#6C9CFF" valign="top">
        <table border="0" cellpadding="2" cellspacing="0" height="100%">
            <tr><td align="right" valign="bottom">
                <font size="3" face="Comic Sans MS, Trebuchet MS, Verdana, Tahoma, Arial" color="#FFFFFF"><h2>&nbsp;Baal Smart Form</h2></font>
            </td></tr>
        </table>    
    </td></tr>
</table>

<?php
extract ($HTTP_POST_VARS,EXTR_OVERWRITE);
if(isset($tableprefix))
    $tableprefix .= "_";
    
$MYSQL_ERRNO = "";
$MYSQL_ERROR = "";
$name1 = "tbluser";
$name2 = "tblgroup";
$name3 = "tblforum";
$name4 = "tblsubforum";
$name5 = "msgs";
$name6 = "adminprefs";

function db_connect()
{   
    $link_id=@mysql_connect($_POST['dbservername'],$_POST['dbusername'],$_POST['dbpassword']);  
    if( $link_id == 0 ) 
    {           
        echo "<center>Could Not Connect to Database.  Check hostname, username and password</center><br />";        
        return 0 ;  
    }   
    
    return $link_id;
}   

function table_exists($tablename)
{
    if (@mysql_query ("SELECT * FROM {$tablename} LIMIT 0,1"))
    {
        return true;    
    }   
    else
    {
        return false;   
    }
}

function create_access()
{   
    $filename = "dataaccess.php";   
    if (!$datafile = @fopen($filename, 'w'))    
    {
        echo "Cannot open file $filename";
        exit;   
    }   
    else    
    {           
        $strcontent = "<?php ";     
        $strcontent = $strcontent . "\$dbservername=\"" . $_POST['dbservername'] . "\" ;" ;     
        $strcontent = $strcontent . "\$dbusername=\"" . $_POST['dbusername'] . "\" ;" ;     
        $strcontent = $strcontent . "\$dbpassword=\"" . $_POST['dbpassword'] . "\" ;" ;     
        $strcontent = $strcontent . "\$dbname=\"" . $_POST['dbname'] . "\" ;" ;     
        $strcontent = $strcontent . " ?>";      
        fwrite($datafile, $strcontent , strlen($strcontent));       
        fclose($datafile);
    }
    $filename1 = '../incl/db.php'; 
    if (!$datafile1 = fopen($filename1, 'w'))   
    {            
        echo "Cannot open file ($filename1)";            
        exit;   
    }   
    else    
    {
        $strcontent1="<?php
    \$db=array(
              \"host\"=>\"" . $_POST['dbservername'] . "\",
              \"user\"=>\"" . $_POST['dbusername'] . "\",
              \"pass\"=>\"" . $_POST['dbpassword'] . "\",
              \"dbname\"=>\"" . $_POST['dbname'] . "\"
             );
                
    \$tableprefix=\"" . ((isset($_POST["tableprefix"])) ? $_POST["tableprefix"] . "_" : "" ) . "\";
?>";  
        fwrite($datafile1, $strcontent1 , strlen($strcontent1));      
        fclose($datafile1); 
    }       
        echo "<center>Data base references updated " ;  
        echo "<br /><br /><br /><a href=\"../regadmin.php\">Proceed as Administrator</a></center>";
}   
    
function install()
{   
    global $tableprefix;    
    $testdb = mysql_select_db($_POST['dbname']);    
    if(!$testdb)    
    {       
        $querydb="create database {$_POST['dbname']}" ;    
        $resultdb=mysql_query($querydb);    
        if(mysql_select_db($_POST['dbname']) == false)    
        {      
            $MYSQL_ERRNO=mysql_errno();      
            $MYSQL_ERROR=mysql_error();      
            echo "Could not create database.<br>" ;      
            return 0;    
        }   
    }   
    else    
    {       
        echo "<center>Adding this installation to the same Database.</center><br />";       
    }       
    $table1 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tbluser (   
                        userid INT NOT NULL AUTO_INCREMENT ,    
                        username VARCHAR( 40 ) NOT NULL ,   
                        password VARCHAR( 40 ) ,    
                        userrole VARCHAR( 20 ) NOT NULL,  
						joindate VARCHAR( 10 ) NOT NULL ,
						level VARCHAR( 40 ) NOT NULL,
						occupation VARCHAR( 60 ) ,
						location VARCHAR( 60 ) ,  
                        mail VARCHAR( 60 ), 
						notifypost VARCHAR( 1 ),
						notifymsg VARCHAR( 1 ),
                        PRIMARY KEY ( userid ) )";
                                
    $table2 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblgroup(   
                        groupid INT NOT NULL AUTO_INCREMENT ,   
                        groupname VARCHAR( 80 ) NOT NULL ,  
                        PRIMARY KEY ( groupid ) )";     
                        
    $table3 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblforum(   
                        forumid INT NOT NULL AUTO_INCREMENT ,   
                        groupname VARCHAR(80) , 
                        subject VARCHAR( 100 ) ,    
                        authorname VARCHAR( 60 ) ,  
                        detail TEXT,    
                        lastpost DATETIME DEFAULT 'NOW()' NOT NULL, 
                        totalpost INT(11),  
                        views INT(11),  
                        sticky BOOL DEFAULT 0 NOT NULL,
                        position INT(11) NULL,
                        PRIMARY KEY ( forumid ) )";     
                        
    $table4 = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblsubforum (   
                        subforumid INT NOT NULL AUTO_INCREMENT ,    
                        forumid INT(11),    
                        groupname VARCHAR( 80 ) ,   
                        subject VARCHAR( 100 ) ,    
                        authorname VARCHAR( 60 ) ,  
                        dateposted DATETIME DEFAULT 'NOW()' NOT NULL,   
                        detail TEXT,    
                        sticky BOOL DEFAULT 0 NOT NULL,
                        PRIMARY KEY ( subforumid ) )";      
                    
    $table5 = "CREATE TABLE IF NOT EXISTS {$tableprefix}msgs (  
                        msgsid INT NOT NULL AUTO_INCREMENT ,    
                        subject VARCHAR( 100 ) ,    
                        fromid INT( 11 ) ,  
                        toid INT(11),   
                        dateposted DATETIME DEFAULT 'NOW()' NOT NULL,   
                        detail TEXT,    
                        didread CHAR DEFAULT 'n',   
                        PRIMARY KEY ( msgsid ) )";      
                    
    $table6 = "CREATE TABLE IF NOT EXISTS {$tableprefix}adminprefs (    
                        prefid INT NOT NULL AUTO_INCREMENT ,    
                        msgs CHAR DEFAULT 'y',  
                        PRIMARY KEY ( prefid ) )";      
                    
    $ra = mysql_query($table1); 
    $rb = mysql_query($table2); 
    $rc = mysql_query($table3); 
    $rd = mysql_query($table4); 
    $re = mysql_query($table5); 
    $rf = mysql_query($table6);     
    
    if($ra && $rb && $rc && $rd && $re && $rf)  
    {       
        echo "<center>Installation Completed.</center><br />";      
    }
    else
    {
        echo "<center>There was a problem creating the tables.<br />Most likely installation with this table prefix already exists.<br /></center>";    
    }
}

function upgrade()
{   
    global $tableprefix;    
    $testdb = mysql_select_db($_POST['dbname']);    
    $result;    
    if(!$testdb)    
    {       
        $result = "<center>There is no Database with the name {$_POST['dbname']} installed.<br />";     
        $result .= "Please go back and check that you have the correct Database name or do a Fresh Install.<br />";     
        $result .= "<br /><a href=\"install.php\">Go back</a><br /></center>";      
        return $result; 
        }   
        else    
        {

            $table[] = "CREATE TABLE IF NOT EXISTS {$tableprefix}tbluser (  
            userid INT NOT NULL AUTO_INCREMENT ,    
            username VARCHAR( 40 ) NOT NULL ,   
            password VARCHAR( 40 ) ,    
            userrole VARCHAR( 20 ) NOT NULL,  
			joindate VARCHAR( 10 ) NOT NULL,
			level VARCHAR( 40 ) NOT NULL,
			occupation VARCHAR( 60 ) ,
			location VARCHAR( 60 ) ,    
            mail VARCHAR( 60 ), 
            PRIMARY KEY ( userid ) )";
			
			$table[] = "ALTER TABLE {$tableprefix}tbluser ADD COLUMN joindate VARCHAR(10) NOT NULL";
			$table[] = "update {$tableprefix}tbluser set joindate=now() WHERE joindate=''";
			$table[] = "ALTER TABLE {$tableprefix}tbluser ADD COLUMN level VARCHAR(40) NOT NULL";
			$table[] = "update {$tableprefix}tbluser set level='New User' WHERE level=''";
			$table[] = "ALTER TABLE {$tableprefix}tbluser ADD COLUMN occupation VARCHAR(60)";
			$table[] = "ALTER TABLE {$tableprefix}tbluser ADD COLUMN notifypost VARCHAR(1)";
			$table[] = "ALTER TABLE {$tableprefix}tbluser ADD COLUMN notifymsg VARCHAR(1)";
			$table[] = "ALTER TABLE {$tableprefix}tbluser ADD COLUMN location VARCHAR(60))";
            
			$table[] = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblgroup(  
            groupid INT NOT NULL AUTO_INCREMENT ,   
            groupname VARCHAR( 80 ) NOT NULL ,  
            PRIMARY KEY ( groupid ) )";
            if(table_exists(tblgroup))
            {
                $table[] = "INSERT INTO {$tableprefix}tblgroup SELECT * FROM tblgroup;";    
            }   

            

            $table[] = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblforum(  
            forumid INT NOT NULL AUTO_INCREMENT ,   
            groupname VARCHAR(80) , 
            subject VARCHAR( 100 ) ,    
            authorname VARCHAR( 60 ) ,  
            detail TEXT,    
            lastpost DATETIME DEFAULT 'NOW()' NOT NULL, 
            totalpost INT(11),  
            views INT(11),  
            sticky BOOL DEFAULT 0 NOT NULL,
            position INT(11) NULL,
            PRIMARY KEY ( forumid ) )";     
            if(table_exists(tblforum))
            {
                $table[] = "INSERT INTO {$tableprefix}tblforum SELECT * FROM tblforum;";    
            }
            
    
            $table[] = "CREATE TABLE IF NOT EXISTS {$tableprefix}tblsubforum (  
            subforumid INT NOT NULL AUTO_INCREMENT ,    
            forumid INT(11),    
            groupname VARCHAR( 80 ) ,   
            subject VARCHAR( 100 ) ,    
            authorname VARCHAR( 60 ) ,  
            dateposted DATETIME DEFAULT 'NOW()' NOT NULL,   
            detail TEXT,    
            sticky BOOL DEFAULT 0 NOT NULL,
            PRIMARY KEY ( subforumid ) )";      
            if(table_exists(tblsubforum))
            {
                $table[] = "INSERT INTO {$tableprefix}tblsubforum SELECT * FROM tblsubforum;";      
            }
            
            

            $table[] = "CREATE TABLE IF NOT EXISTS {$tableprefix}msgs ( 
            msgsid INT NOT NULL AUTO_INCREMENT ,    
            subject VARCHAR( 100 ) ,    
            fromid INT( 11 ) ,  
            toid INT(11),   
            dateposted DATETIME DEFAULT 'NOW()' NOT NULL,   
            detail TEXT,    
            didread CHAR DEFAULT 'n',   
            PRIMARY KEY ( msgsid ) )";  
            if(table_exists(msgs))
            {
                $table[] = "INSERT INTO {$tableprefix}msgs SELECT * FROM msgs;";        
            }       
            
            
            $table[] = "CREATE TABLE IF NOT EXISTS {$tableprefix}adminprefs (   
            prefid INT NOT NULL AUTO_INCREMENT ,    
            msgs CHAR DEFAULT 'y',  
            PRIMARY KEY ( prefid ) )";  
            if(table_exists(adminprefs))
            {
                $table[] = "INSERT INTO {$tableprefix}msgs SELECT * FROM msgs;";    
            }
            
            mysql_query("UPDATE {$tableprefix}adminprefs SET msgs=\"y\";");
            
            foreach($table as $tablecreate)
            {
                $querynum = mysql_query($tablecreate);
                if(!$querynum)
                {
                    $something++;   
                }   
            }
            
            if($something)
            {
                $result = "<center>There was an error Upgrading the Tables</center><br />";     
            }
        }   
        
        $result = "<center>Tables Upgraded Successfully</center><br />";    
        return $result;
}

$link_id=db_connect();
if($link_id)
{   
    if($_POST['installtype'] == freshinstall)   
    {       
        echo install(); 
    }   
    else if($_POST['installtype'] == upgrade)   
    {       
        echo upgrade(); 
    }
    create_access();        
}
else 
    echo "<center>** Not connected **</center>";

ob_end_flush();
?>
</body></html>