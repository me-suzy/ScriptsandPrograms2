<?
include ('../includes/global.php');
 
//DB connectivity
$link=dbconnect();

$else="<li>No Users found for $u. If you used more than one word, try using only ONE word.";
$else.="Your search must be specific. Try again with a single user id";

function find($query)
{
    global $else;
    if($res=mysql_query($query))
    {
	print "<table><tr><thead>EMAIL ID'S</thead></tr><tr><td><ol>";
	while($res1=mysql_fetch_array($res))
	{
	    print "<li><a href='userinfo_admin.php?u=$res1[1]'>$res1[1]</a></li>";
	}
	print "</ol></td></tr></table>";
    }
}

function search($query)
{
    if($res1=mysql_query($query))
    {
        global $else;
        print "<table><tr><thead>EMAIL ID'S</thead></tr><tr><td><ol type=1>";
        $res1=mysql_fetch_array($res1);
	print "<li><a href='userinfo_admin.php?u=$res1[1]'>$res1[1]</a></li>";
	$query2="select parent_id from member_referrals where mem_id=$res1[0] ";
	$query3="select mem_id from member_referrals where parent_id=$res1[0] ";
	if($res2=mysql_query($query2))
	{
	    $res2=mysql_fetch_array($res2);
	    if($res2=mysql_query("select email_id from member_details where mem_id=$res2[0] "))
	    {
	        $res3=mysql_fetch_array($res2);
		print "<li><a href='userinfo_admin.php?u=$res3[0]'>$res3[0]</a></li>";
            }
        }
	if($res2=mysql_query($query3))
	{
	    while($res3=mysql_fetch_array($res2))
	    {
		if($res3=mysql_query("select email_id from member_details where mem_id=$res3[0] "))
		{
		    $res3=mysql_fetch_array($res3);
		    print "<li><a href='userinfo_admin.php?u=$res3[0]'>$res3[0]</a></li>";
		}
		else
		    print $else;
	    }
	 }
         print "</ol></td></tr></table>";
    }
}

if($t=="show_user")
{
    if($u != "")
    {
        $query1="select mem_id,email_id from member_details where email_id='$u' ";
        search($query1);
    }
}
elseif($t=="search")
{
   if($tosearch !="" && $key != "")
   {
       $query1="select mem_id,email_id from member_details where $tosearch=$key ";
       $query2="select mem_id,email_id from member_details where $tosearch='$key' ";
       if($tosearch == "mem_id")
           search($query1);
       elseif($tosearch == "email_id")
           search($query2);
       else
           find($query2);
   }
}
elseif($t=="by_date")
{
    if($from !="" && $to != "")
    {
        $query1="select mem_id,email_id from member_details where joined_date >= '$from' and joined_date <='$to' ";
        find($query1);
    }
}
elseif($t=="remove_email")
{
    if($u != "")
    {
	$query1="SELECT mem_id from member_details where email_id='$u' ";
	$res=mysql_query($query1);
	$mem_id=mysql_fetch_array($res);

        $query2="delete from member_details where email_id='$u' ";

	$query11="select ad_id from advt_email where email_id='$u'";
	$result=mysql_query($query11);
	if ($ad_id=mysql_fetch_array($result)) {
	    $sql4="delete from email_clicks where ad_id='$ad_id[0]'";
	    mysql_query($sql4);
	}
	$sql1="delete from additional_info where mem_id='$mem_id[0]'";
	$sql2="delete from advt_email where email_id='$u'";
	$sql3="delete from advt_banner where email_id='$u'";
	$sql5="delete from member_credit where mem_id='$mem_id[0]'";
	$sql6="delete from member_debit where mem_id='$mem_id[0]'";
	$sql7="delete from paid_mail where mem_id='$mem_id[0]'";
	$sql8="delete from member_earnings where mem_id=$mem_id[0]";
	$sql9="delete from member_referrals where mem_id=$mem_id[0]";
        if ((mysql_query($query2))&&(mysql_query($sql1))&&(mysql_query($sql2))&&(mysql_query($sql3))&&(mysql_query($sql5))&&(mysql_query($sql6))&&(mysql_query($sql7))&&(mysql_query($sql8))&&(mysql_query($sql9)))
        {
            if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }

            //to move the tiers on level above
            move_Tier($mem_id[0]);
            $userid[0]=$u;

            //to delete this user id from the gold membership
            $out=remove_GoldMember($userid,"$gold_path/random.txt");

            print "Record's of $u are Successfuly Deleted";
        }
    }
}
elseif($t=="done" && $add=="Add Referral")
{
    $query="select * from member_details where email_id='$ref_id'";
    if ($rs=mysql_query($query))
    {	
        $rs=mysql_fetch_array($rs);
	$qry="select * from member_referrals where mem_id=$rs[0]";
	if($rs1=mysql_query($qry))
	    $num1=mysql_num_rows($rs1);
	if($num1==0)   {
	    $qry="insert into member_referrals values ($rs[0],$mem_id)";
	    mysql_query($qry);
	}
    }
}
else
{
    //print "<b>Your search should be specific. Try with a single user id</b>";
}

// DB disconnection
dbclose($link);

?>
<body>
<font face="arial" size="3"><b><center>Account Manager</center></b></font>
<table><tr><td>
<form action="infoadmin.php?t=show_user" method="post">
<font face="arial" color="green" size="3"><b>(a). <u>View</u></b></font><br>
<table>
<tr><td>
<font face="arial" size="2">The User to View :</font>
</td>
<td> <input type="text" name="u">
</td></tr>
</table>
<br>
<center><input type="submit" value="View the User's Information"></center>
<br><br>
</form>
<form action="infoadmin.php?t=search" method="POST">
<font face="arial" color="green" size="3"><b>(b). <u>Search</u></b></font><br>
<table>
<tr><td>
<font face="arial" size="2">Type the key value to search :</font></td>
<td>
<input type="text" name="key"></td>
<tr><td>
<font face="arial" size="2">Search for : </font><center></td>
<td>
        <select name="tosearch">
        <option value selected>Select any one from this Option</option>
        <option value="mem_id">Member Id</option>
        <option value="email_id">Email Id</option>
        <option value="f_name">First Name</option>
        <option value="l_name">Last Name</option>
        <option value="address">Living Addrerss</option>
        <option value="city">City</option>
        <option value="state">State</option>
        <option value="country">Country</option>
        <option value="zip">Zip Code</option>
        <option value="ipadds">IP Address</option>
</select></center><br>
</td></tr>
</table>
<font face="arial" size="2">
(The Search will return all users that contain any of the given information.
<br>e.g. E-Mail: your@yahoo.com, First Name: John, Last Name: Doe, Password: pass, etc. etc.)<br>
<br></font>
<center><input type="submit" value=" Search  by Values  "></center>
</form></td><td>
<br><br><br><br><br><br><br><form action="infoadmin.php?t=by_date" method="POST">
   <font face="arial" size="2"><br><br>Search by Joined Date:<br><br></font>
   <font face="arial" size="2">From : &nbsp</font><input type="text" name="from" size=10 maxlength=10>&nbsp;&nbsp;
   <font face="arial" size="2">To : &nbsp</font><input type="text" name="to" size=10 maxlength=10><font face="arial" size="2"><br><br>(Enter in "yyyy-mm-dd". Format should not be changed.)<br></font>
   <br>
   <center> <input type="submit" value="Search by Date"></center>
</form></td>
<br><br><center><tr><td></td></tr><tr><td>
<font face="arial" color="green" size="3"><b>(c). <u>Delete</u></b></font><br>
<form action="infoadmin.php?t=remove_email" method="POST">

    The User to be Deleted : &nbsp
   <input type="text" name="u"><br><br>
   <center><input type="submit" value="Delete User"></center>
</form>
</td></tr></table>
</body>

<?
print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTM;
?>