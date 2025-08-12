<?
include ('../includes/global.php');

//DB connectivity
$link=dbconnect();

if($action == "change")
{
    $user_count=count($userid);
    if($Btn == "SetActive")
    {
         $qry="update member_details set last_login=now(),status='active' where email_id='";

         for($i=0;$i<$user_count;$i++)
         {
              mysql_query($qry."$userid[$i]'");
         }
         print "Status Changed as Active for $user_count Selected Users";
    }
    elseif($Btn == "SetIn-active")
    {
         $qry1="SELECT  last_login FROM member_details  where email_id='";
         $qry2="update member_details set last_login=";
         for($i=0;$i<$user_count;$i++)
         {
              $res=mysql_query($qry1."$userid[$i]'");
              $res=mysql_fetch_array($res);
              mysql_query($qry2."$res[0],status='inactive' where email_id='$userid[$i]'");
         }
         print "Status Changed as In-Active for $user_count Selected Users";
    }
}
elseif($t=="show_users_not_logged")
{
    (($days == "")?$num_days=$max_days_for_login:$num_days=$days);

    $query1 = <<< SQL
    SELECT email_id,TO_DAYS(NOW())-TO_DAYS(last_login),last_login,status FROM member_details
    where (TO_DAYS(NOW())-TO_DAYS(last_login) > $num_days) order by last_login
SQL;

    if($res = mysql_query($query1) or die(mysql_error()))
    {
         print "<font face='arial' size='2'> Total Users : </font> ".mysql_num_rows($res);
         print <<< HTM
         <form action="$admin_url/user_status.php" method="post" name=messageList>
           <br><br><table border=1><tr>
           <td><font face="arial" size="1" color="green"><b>SelectAll</b></FONT><br>
           <center><Input type=checkbox name=selectall1 VALUE="" OnClick=CheckAll2(this)></center></td>
           <td>User Id</td><td>Days Not Logged</td><td>Status</td></tr>
HTM;
           while($res1 = mysql_fetch_array($res))
           {
                   print <<<HTML
                     <tr><td><input type=checkbox name="userid[]" value=$res1[0]></td>
                     <td><a href="$admin_url/userinfo_admin.php?u=$res1[0]">$res1[0]</a></td>
                     <td>$res1[1]</td><td>$res1[3]</td></tr>
HTML;
           }
    }
print <<< HTM
</table> <br>
<input type=submit value="SetActive" name="Btn">
<input type=submit value="SetIn-active" name="Btn">
<input type=hidden value="change" name="action">
</form>
HTM;

dbclose($link);
}

print <<< HTM
<form action="$admin_url/user_status.php?t=show_users_not_logged" method="post">
<font face='arial' size='2'>
Display members which have NOT logged in for <input type="text" name="days" size=3> over days...
<input type="submit" value="Display"></center>
<br><br><br></font>
</form>
<br><br>
Click here to go back to <b><a href="$admin_url/admin.php">Admin Index Page </a></b>
HTM;
?>
<script language="JavaScript" type="text/javascript">
//---------------------- function for check all with check box------------------//

function CheckAll2(chk)
{
for (var i=0;i < document.messageList.elements.length;i++)
	{
		var e = document.messageList.elements[i];
		if (e.type == "checkbox")
		{
			e.checked = chk.checked
		}
	}
}
</script>