<html>
<?
include ("../includes/global.php");

print "<center>";
links();
print "</center>";

//DB connectivity
$link= dbconnect();
if($t=="done")
{
    if($pwd == $p)
    {
         if(!$password)
         {
              //insertion of updated datas by the user
              $query =<<<SQL
              update member_details set email_id='$newemail',address='$address',
              city='$city', state='$State[$state]', zip='$zipcode',country='$Country[$country]' 
              where email_id='$email' and password='$pwd'
SQL;

              mysql_query($query);
              //update into addition details

	      $qry=<<<SQL
	      SELECT * FROM member_details where email_id='$email' and password='$pwd'
SQL;
              if ($res=mysql_query($qry))
	      {
	           $res=mysql_fetch_array($res);
                   for($i=0;$i<count($finterests);$i++)
                   {
			$int.=$finterests[$i]."::";
                   }
 		   $sql= <<<SQL
                        update additional_info set age=$fage,gender='$gender',
                        marital='$fmarital',household='$fhousehold',childrens='$fchildren',income='$fincome',
                        housestatus='$fhousestatus',learning='$flearning',occupation='$foccupation',
                        vehicles='$fvehicles',creditcard='$creditcard',spentonline='$fspentonline',
                        interests='$int',html_email='$html_email'where mem_id=$res[0]
SQL;
		   mysql_query($sql);
	      }
              $out_message= <<< HTM
<b>Your details are successfully updated in our database <br><br>
HTM;

              $ps=md5($pwd);
         }
         else
         {
               $query =<<<SQL
               update member_details set  email_id='$newemail',address='$address',city='$city',
                state='$State[$state]',zip='$zipcode',country='$Country[$country]'
	       ,password='$password' where email_id='$email' and password='$pwd'
SQL;
               mysql_query($query);
               //update into addition details
               $qry=<<<SQL
	       SELECT * FROM member_details where email_id='$email' and password='$pwd'
SQL;
               if ($res=mysql_query($qry))
	       {
	            $res=mysql_fetch_array($res);
                    for($i=0;$i<count($finterests);$i++)
                    {
			$int.=$finterests[$i]."::";
                    }
 		    $sql= <<<SQL
                        update additional_info set age=$fage,gender='$gender',
                        marital='$fmarital',household='$fhousehold',childrens='$fchildren',income='$fincome',
                        housestatus='$fhousestatus',learning='$flearning',occupation='$foccupation',
                        vehicles='$fvehicles',creditcard='$creditcard',spentonline='$fspentonline',
                        interests='$int',html_email='$html_email'where mem_id=$res[0]
SQL;
		    mysql_query($sql);
	       }
               $out_message= <<< HTM
<b>Your details are successfully updated in our database <br><br>
HTM;
               $ps=md5($password);
         }
         if($email != $newemail)
         {
              /*
              This condition is to check and replace the Gold Memberid
              in the random.txt if he is a GoldMember
              */
              if(file_exists("../gold_membership/gold_funs.php")) { include ("../gold_membership/gold_funs.php"); }
              $bool=check_GoldMember($email);
              if($bool == "exist")
              {
                   replace_GoldMember($email,$newemail);
              }
         }
         //function to display message
         out_message($out_message,$color_feedback_good);
         $out_message= <<< HTM
<a  href="$site_url/member_area.php?email=$newemail&ps=$ps"><b><font
face="Arial" color="blue" size="3">Return To Member's Area</font></b></a>
HTM;

        //function to display message
        out_message($out_message,$color_feedback_good);
    }
    elseif($pwd != $p)
    {
         $out_message= <<< HTM
<br><br><br><br><center><b>Hi&nbsp;&nbsp;&nbsp;
Old Password  you entered is Invalid !!!!. So enter it correctly.</b><br><br>
HTM;
         //function to display message
         out_message($out_message,$color_feedback_bad);
    }
    print "<br><br><br><br>$html_footer";
    exit;
}
$query=<<<SQL
      select email_id,f_name,address,city,state,zip,country,password,mem_id  from member_details
      where  email_id='$email'
SQL;
if($res =mysql_query ($query))
{
     $res = mysql_fetch_array($res);

     //fetching values from additiona_info tables for demographic information
     $query=<<<SQL
      select * from additional_info where  mem_id=$res[mem_id]
SQL;
     if($rset =mysql_query ($query))
     {
        $addl_det= mysql_fetch_array($rset);
     }
     if($ps == md5($res[7]) )
     {
          if($email != "" && $ps != "")
          {
               $member_links=menu($email,$ps);
               print "$member_links <br><br>";
          }
          //country list
          for ($i=0;$i<count($Country);$i++)
          {
               if($res[6] == $Country[$i])
               {
                    $coun.="<option value=\"$i\" SELECTED>$Country[$i]</option>\n";
                    continue;
               }
               $coun.="<option value=\"$i\">$Country[$i]</option>\n";
          }
          //state list
          for ($i=0;$i<count($State);$i++)
          {
               if($res[4] == $State[$i])
               {
                    $stat.="<option value=\"$i\" SELECTED>$State[$i]</option>\n";
                    continue;
               }
               $stat.="<option value=\"$i\">$State[$i]</option>\n";
          }
         //code for additional details
         $ointarray=explode("::",$addl_det[interests]);

         $i = 0;
         foreach($interests as $key => $value)
         {
	     $fcheck="";
	     for ($j=0;$j<count($ointarray);$j++)
	     {
	          if ($ointarray[$j] == $key)	
	          {
		       $fcheck="checked";
	          }
	     }
             if($i<15)
	     { 
	         $interests1.=<<<HTM
<input type="checkbox" name="finterests[]" value="$key" $fcheck ><font face="arial" size="2" >$interests[$key]</font><br>\n
HTM;
	     }
             elseif($i>=15 && $i<30)
	     {
	         $interests2.=<<<HTM
<input type="checkbox" name="finterests[]" value="$key" $fcheck><font face="arial" size="2" >$interests[$key]</font><br>\n
HTM;
	     }
            elseif($i>=30) 
	    {
                $interests3.=<<<HTM
<input type="checkbox" name="finterests[]" value="$key" $fcheck><font face="arial" size="2" >$interests[$key]</font><br>\n
HTM;
	    }
	    $i++;
        }
        //age
        foreach ($age as $key => $value)
        {
	    if ($addl_det[age] == $key )
	    {
		$ag.="<option value=$key selected>$age[$key]</option>\n";
		continue;
	    }	
            $ag.="<option value=$key>$age[$key]</option>\n";
        }
        //marital status
        foreach($marital as $key => $value)
        {
	    if ($addl_det[marital] == $key )
	    {
	    	$mari.="<option value=$key selected>$marital[$key]</option>\n";
		continue;
	    }
            $mari.="<option value=$key>$marital[$key]</option>\n";
        }
        //household members
        foreach($household as $key => $value)
        {
	    if( $addl_det[household] == $key )
	    {
		$hhold.="<option value=$key selected>$household[$key]</option>\n";
		continue;
	    }
            $hhold.="<option value=$key>$household[$key]</option>\n";
        }
        //childrens
        foreach($children as $key => $value)
        {
	    if ($addl_det[childrens] == $key )
	    {
		$ch.="<option value=$key selected>$children[$key]</option>\n";
		continue;
	    }
            $ch.="<option value=$key>$children[$key]</option>\n";
        }
        //annual income
        foreach($income as $key => $value)
        {
	    if ($addl_det[income] == $key ) 
	    {
	    	$inc.="<option value=$key selected>$income[$key]</option>\n";
		continue;
	    }
            $inc.="<option value=$key>$income[$key]</option>\n";
        }
        //rented or owned
        foreach($housestatus as $key => $value)
        {
	    if ($addl_det[housestatus] == $key )
	    {
		$hs.="<option value=$key selected>$housestatus[$key]</option>\n";
		continue;
	    }
            $hs.="<option value=$key>$housestatus[$key]</option>\n";
       }
       //education level
       foreach($learning as $key => $value)
       {
	    if ($addl_det[learning] == $key )
	    {
 		$lear.="<option value=$key selected>$learning[$key]</option>\n";
		continue;
            }
            $lear.="<option value=$key>$learning[$key]</option>\n";
       }
       //occupation
       foreach($occupation as $key => $value)
       {
	    if ($addl_det[occupation] == $key )
	    {
		$occ.="<option value=$key selected>$occupation[$key]</option>\n";
		continue;
	    }
            $occ.="<option value=$key>$occupation[$key]</option>\n";
       }
       //vehicle details
       foreach($vehicles as $key => $value)
       {
	    if ($addl_det[vehicles] == $key )
	    {
		$veh.="<option value=$key selected>$vehicles[$key]</option>\n";
		continue;
	    }
            $veh.="<option value=$key>$vehicles[$key]</option>\n";
       }
       //spending online
       foreach($spentonline as $key => $value)
       {
	   if ($addl_det[spentonline] == $key)
	   {
		$sol.="<option value=$key selected>$spentonline[$key]</option>\n";
		continue;
           }
           $sol.="<option value=$key>$spentonline[$key]</option>\n";
       }
?>
<script language=javascript>
function chek_pwd(key)
{
    if((document.frm_edit.p.value) != (document.frm_edit.pwd.value))
    {
        alert("The password you entered does'nt matches with original password. So re-enter old password");
        document.frm_edit.pwd.focus;
        return(false);
    }
    return(true);
}
</script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Member Area-Edit Your Information</title>
</head>
<body>
<form action="edit_your_info.php?t=done" method="post"
name=frm_edit onsubmit="return chek_pwd(this)">
<table>
<tr>
<td>
<font face="arial" size="2"> E-Mail Address:</font>
</td><td>
<font face="arial"> <input value="<?=$res[0]?>" name="newemail" size="20"></font>
</td></tr>
<tr>
<td>
<font face="arial" size="2" >New Password:</font>
</td>
<td>
 <input type="password" value
name="password" size="20">
</td></tr>
<tr><td></td><td>
<font face="arial" size="-1" >
Please make sure that you enter a new password!!</font></td></tr>
<tr><td>
<font face="arial" size="2" >Address:</font></td>
<td>
 <input value="<?=$res[2]?>" name="address" size="20"></td>
</tr>
<tr><td>
<font face="arial" size="2" >City: </font></td>
<td><input value="<?=$res[3]?>" name="city" size="20" maxlength=25></td>
</tr>
<tr><td>
<font face="arial" size="2" >State: </font></td><td>
<select name="state">
<?=$stat?>
</select>
</td></tr>
<tr><td>
<font face="arial" size="2" >Zip Code:</font></td>
<td> <input value="<?=$res[5]?>"name="zipcode" size="20" maxlength=15> 
</td>
</tr>
<tr><td>
<font face="arial" size="2" >Country:</font>
</td>
<td> <select name="country">
<?=$coun?>
</select></td></tr>
<tr><td>
<font face="arial" size="2" >Please enter your old password for security purposes:</font>
</td>
<td>
<input type="password" value name="p" size="20"><br><br>
</td></tr>

<tr><td>
<font face="arial" size="2" >Gender: </font></td>
<td>
<SELECT NAME="gender">
<?if ($addl_det[gender] == "Male")
	$mgender="selected";
else
	$fgender="selected";
?>
<option value="Male" <?= $mgender?>>Male</option>
<option value="Female" <?=$fgender?>>Female</option>

</SELECT>
</td></tr>
<tr><td>
<font face="arial" size="2" >Age:</font></td>
<td> <SELECT NAME="fage">

<?=$ag?>
</SELECT>
</td></tr>
<tr><td>
<font face="arial" size="2" >Marital Status: </font></td>
<td>
                         <SELECT NAME="fmarital">

<?=$mari?>

</SELECT></td></tr>
<tr><td>
<font face="arial" size="2" >Number of People in Household: </font></td>
<td>          <SELECT NAME="fhousehold">

<?=$hhold?>

</SELECT>
</td></tr>
<tr><td>
<font face="arial" size="2" >Number of Children:</font></td>
<td>                      <SELECT NAME="fchildren">

<?=$ch?>

</SELECT></td></tr>
<tr><td>
<font face="arial" size="2" >Household Annual Income:</font></td>
<td>                 <SELECT NAME="fincome">

<?=$inc?>

</SELECT></td></tr>
<tr><td>
<font face="arial" size="2" >Is your home owned or rented?:</font></td>
<td>           <SELECT NAME="fhousestatus">

<?=$hs?>

</SELECT></td></tr>
<tr><td>
<font face="arial" size="2" >Education level completed: </font></td>
<td>              <SELECT NAME="flearning">

<?=$lear?>

</SELECT></td>
</tr>
<tr><td>
<font face="arial" size="2" >Occupation: </font></td>
<td>                             <SELECT NAME="foccupation">

<?=$occ?>

</SELECT></td>
</tr>
<tr><td>
<font face="arial" size="2" >Number of Vehicles:</font></td>
<td>                      <SELECT NAME="fvehicles">

<?=$veh?>

</SELECT></td>
</tr>
<tr><td>
<font face="arial" size="2" >Do you have a credit card?:    </td>
<td>          <SELECT NAME="creditcard">
<?if ($addl_det[creditcard] == "yes")
	$ycredit="selected";
else
	$ncredit="selected";
?>
<option value="yes" <?=$ycredit?>>Yes</option>
<option value="no" <?=$ncredit?>>No</option>

</SELECT></td>
</tr>
<tr><td>
<font face="arial" size="2" >Amount spent online in the past year?: </td>
<td>  <SELECT NAME="fspentonline">

<?=$sol?>
</SELECT></td></tr>
<tr><td>
<font face="arial" size="2" >Can you receive HTML emails?:  </td>
<td>          <SELECT NAME="html_email">
<?if  ($addl_det[html_email] == "yes")
	$yhtmlselect="selected";
else
	$nhtmlselect="selected";
?>
<option value="yes" <?=$yhtmlselect?>>Yes</option>
<option value="no" <?=$nhtmlselect?>>No</option>
</SELECT></td></tr>
</table>
<font face="arial" size="2" >Please select your interests:</font>
<TABLE BORDER=0 WIDTH=98% ALIGN=CENTER>
<tr>
<td width=33%>
<font color="blue">
<?=$interests1?>
</td>
<td width=33%>
<font color="blue">
<?=$interests2?>
</td>
<td width=34%>
<font color="blue">
<?=$interests3?>
</td>
</tr>
<tr></tr><tr></tr>
<tr></tr><tr></tr>
</table>
<input type="hidden" value="<?=$res[0]?>" name="email" size="20">
<input type="hidden" value="<?=$res[7]?>" name="pwd" size="20">
<center><input type="submit" value="Save the Information"></center>
</form>
</body>
</html>
  <?
    }
    else
    {
        $out_message= <<< HTM
<b>Error in Login. Either the User Id or Password
entered is Wrong. <br> <br><br><br>Go back and provide a valid Login Details</b>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=$site_url/index.php">
HTM;

        //function to display message
        out_message($out_message,$color_feedback_bad);

    }
}
//to close to DB link
dbclose($link);

print "<br><br><br><br>$html_footer";
?>