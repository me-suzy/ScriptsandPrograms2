<?
include ('../includes/global.php');

//connecting to DB
$link=dbconnect();

    $query="select count(*) from member_details ";
    if($res=mysql_query($query))
    {
        $res=mysql_fetch_array($res);
        print "<br><br><br><br><center><font face='arial' size='3'><b>Total number of users are </b></font>";
        print "<h1><font color=blue face='Zurich BlkEx BT'>". $res[0]."</font></h1></center>" ;
    }

//disconnecting from DB
dbclose($link);

print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTM;
?>