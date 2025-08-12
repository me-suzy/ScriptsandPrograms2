<?
include ("../../includes/global.php");
include ("../gold_funs.php");

if($action == "add_gold" && $Btn == "AddGoldMember")
{
       $joined_date=date("m/d/Y");
       $gold_det="$email&&$info&&$joined_date\n";
//echo $gold_path;
       $file=fopen("$gold_path/random.txt",'a');
       fwrite($file,$gold_det) or die("Could Not Able To Write The File");
       fclose($file);
       print <<<HTM
              <center><b> Gold Member Added Successfully</b>
              <META HTTP-EQUIV=REFRESH CONTENT="3; URL=$gold_admin_url/index.php">
              </center>
HTM;

}
elseif($action == "delete" && $Btn == "Remove")
{
       /*
                this function will delete the gold member data from random.txt file.
                This takes input of emailid, provided the var should be an array. Multiple id's
                can also be passed as an array to this function.
       */

       $out=remove_GoldMember($userid,"$gold_path/random.txt");

       $user_count=count($userid);
       //writing the updated contents to the random.txt file
       if($out == "true")
       {
		get_GoldMember_Det();
              print <<<HTM
              <center><b> $user_count Selected Gold Members Deleted</b>
              <META HTTP-EQUIV=REFRESH CONTENT="3; URL=$gold_admin_url/index.php">
              </center>
HTM;
             
       }
       if($out == "false")
       {
                print "Records Not Deleted";
       }
}
else
{
        get_GoldMember_Det();

        $file_content=file_reader("$gold_path/random.txt");
        $count=(count($gold_EmailId));
        print <<< HTM
           <font face="arial" size="3"><b><center>Gold Member's List</center></b></font>
           <font face="arial" size="2">Total Users :  $count</font>
           <br><br>
           <form action="$gold_admin_url/index.php" method="post" name="messageList">
           <table border=1><tr>
           <td><font face="ms sans serif" size="1" color="green"><b>SelectAll</b></FONT><br>
           <center><Input type=checkbox name="selectall1" VALUE="" OnClick=CheckAll2(this)></center></td>
           <td>Gold Member Id</td>
          <!-- <td>Information</td> -->
           <td>Joined Date</td>
           <td>No. of Days</td>
           </tr>
HTM;

           $curr_date=date("m/d/Y");

           for($i=0;$i<$count;$i++)
           {
                if($Btn == "Search........")
                {
                    $diff_days=date_diff($gold_Date[$i], $search_from);

                    if($symbol ==">")
                    {
                        if($no_of_days >= $diff_days)
                        {
                                //to display results
                                display_results($i);
                                continue;
                        }
                    }
                    else
                    {
                        if($no_of_days <= $diff_days)
                        {
                                //to display results
                                display_results($i);
                                continue;
                        }
                    }
                }

                $diff_days=date_diff($gold_Date[$i], $curr_date);
                //to display results
                display_results($i);
           }

print <<< HTM
</table> <br>
<center><font face="arial" size="2">
Current Date : <input type=text value="$curr_date" name="search_from" size="10">
<br>
Display Gold Members who have registered for
<input type=radio value="&lt;" name="symbol"> Less Than
<input type=radio value="&gt;" name="symbol"> More Than
<input type="text" name="no_of_days" size=3>  days...  from the date shown above.
<input type=submit value="Search........" name="Btn">
</font>
</center>
<br><br>
<input type=submit value="Remove" name="Btn">
<input type=hidden value="delete" name="action">
</form>
HTM;


print <<< HTM
<br><br><br>
<font face="arial" size="3"><b><center>Add A Gold Member</center></b></font><br>
<font face="arial" size="2" color="red"><b>
Note: If you are adding a member as a Gold Member, he should be a member of this site.<br>
Problem will occour if you add a email id directly to GoldMember's List.<br><br>
</b></font>
<form method="post" action="$gold_admin_url/index.php">

<center><font face="arial" size="2" >Email Id: </font><INPUT type="text" name="email"><br><br>
<!--Information:<br>
(Please include any information that you will want to be displayed for your banner impressions)<br>
<textarea name="info" cols="40" rows="6"></textarea><br><br>-->

<INPUT type="submit" value="AddGoldMember" name="Btn"></center>
<INPUT TYPE="HIDDEN" NAME="action" VALUE="add_gold">
</form>
HTM;

}


function date_diff($date1, $date2)
{
  $s = strtotime($date2)-strtotime($date1);
  $d = intval($s/86400);
  return $d;
}

function display_results($i)
{
        global $gold_EmailId,$gold_Info,$gold_Date,$diff_days,$admin_url;
        print <<<HTML
        <tr>
                <td><input type=checkbox name="userid[]" value=$gold_EmailId[$i]></td>
                <td><a href="$admin_url/userinfo_admin.php?u=$gold_EmailId[$i]">$gold_EmailId[$i]</a></td>
                <!--<td>$gold_Info[$i]</td>-->
                <td>$gold_Date[$i]</td>
                <td>$diff_days</td>
        </tr>
HTML;

}

print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
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











