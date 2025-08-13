<?
include ("../includes/global.php");
 
//DB connectivity
$link=dbconnect();

$qry="select * from banner order by rand()";
if($res=mysql_query($qry))
{
    $res1=mysql_fetch_array($res);

    $output=<<<HTM
    <center>
    $res1[title]<br>
    $res1[html]
    </center>
HTM;

    print $output;

    $query1="select * from banner_imp where banner_id=$res1[banner_id] and click_date=now()";
    if($result=mysql_query($query1))
    {

	if($result=mysql_fetch_array($result))
	{
	    $cnt=$result[count]+1;
	    $query="update banner_imp set count=$cnt where banner_id=$res1[banner_id] and click_date=now()";

	    mysql_query($query);
	}
	else
	{
	    $query="insert into banner_imp set banner_id=$res1[banner_id] , count=1, click_date=now()";

	    mysql_query($query);
	}
    }
}

dbclose($link);

?>