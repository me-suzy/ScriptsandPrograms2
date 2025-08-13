<?php
//Admin Users Functions

//display users
function getusers($ordby,$displaylim, $searchby, $dall, $start, $qur){
	global $conn, $lim, $userlist, $pagenav,$la_disabled, $la_navbar_seperator,$la_editor, $page_nav_vars, $la_admin, $la_button_approve, $la_users,$la_username,$la_advanced_search,$la_no_users,$la_button_display_all, $la_button_search,$la_button_delete,$la_full_name,$la_button_edit,$la_email, $la_status,$la_date,$la_button_new_user,$pend, $la_mailing_list, $user_name,$first, $last, $fday, $dmonth, $fyear,$lday, $lmonth, $lyear,$email, $user_perm, $status, $ucust1,$ucust2,$ucust3,$ucust4,$ucust5,$ucust6, $sep, $searchkey, $submit, $search, $sid, $session_get, $duplicatemail;
	
	if($duplicatemail ==1)
		$dup_email = "&duplicatemail=1";
		
	if(!$ordby && $duplicatemail==1)
		$ordby = "email";

	if($sid && $session_get)
		$att_sid="sid=$sid&";

	if(!$pend){$pend="0";}
	if(!$duplicatemail){$duplicatemail="0";}

	if(!$page_nav_vars["pend"]){$page_nav_vars["pend"]=$pend;}
	$userlist="<table border='0' cellPadding='4' cellSpacing='0' width='100%'>
              <tr bgColor='#999999' vAlign='center'>";
	$title=array($la_username,$la_full_name,$la_email,$la_status,$la_date);
	$order=array("user_name","first, last","email","user_perm","user_date");
	$attach="";
	if($user_name){$attach.="&user_name=$user_name";}
	if($first){$attach.="&first=$first";}
	if($last){$attach.="&last=$last";}
	if($fday){$attach.="&fday=$fday";}
	if($dmonth){$attach.="&dmonth=$dmonth";}
	if($fyear){$attach.="&fyear=$fyear";}
	if($lday){$attach.="&lday=$lday";}
	if($lmonth){$attach.="&lmonth=$lmonth";}
	if($lyear){$attach.="&lyear=$lyear";}	
	if($email){$attach.="&email=$email";}
	if($user_perm){$attach.="&user_perm=$user_perm";}
	if($status){$attach.="&status=$status";}
	if($sep){$attach.="&sep=$sep";}
	if($ucust1){$attach.="&ucust1=$ucust1";}
	if($ucust2){$attach.="&ucust2=$ucust2";}
	if($ucust3){$attach.="&ucust3=$ucust3";}
	if($ucust4){$attach.="&ucust4=$ucust4";}
	if($ucust5){$attach.="&ucust5=$ucust5";} 
	if($ucust6){$attach.="&ucust6=$ucust6";}
	if($submit){$attach.="&submit=$submit";}
	if($search){$attach.="&search=$search";}
	if($result){$attach.="&result=$result";}
	if($pend){$attach.="&pend=$pend";}
	if($searchkey){$attach.="&searchkey=$searchkey";}
	if($user_perm){$attach.="&user_perm=$user_perm";}
	
	for($r=0;$r<5;$r++)
	{	$userlist=$userlist."<td class='textTitle'><a href=\"users.php?$att_sid"."orderby=$order[$r]$attach".$dup_email."\"><font weight='900' face='Arial, Helvetica, sans-serif' size='2' color='#FFFFFF'>";
		if($ordby==$order[$r])
			$userlist=$userlist. "<img src=\"images/orderarrow2.gif\" border='0'>";
		else
			$userlist=$userlist."<img src=\"images/orderarrow1.gif\" border='0'>";

		$userlist=$userlist."$title[$r]</font></a></td>";
	}
	
	$userlist=$userlist."<td class='textTitle'>&nbsp;</td></tr>";

	if(strlen($qur)>0)
		$query1=$qur;
	elseif ($duplicatemail==1)
	{
		$rs = &$conn->Execute("SELECT email, count(email) AS count FROM inl_users GROUP BY email HAVING count>1");		
		if($rs && !$rs->EOF)
		{
			$query1="select * from inl_users where email='".$rs->fields[0]."'";
			$rs->MoveNext();
		

			while($rs && !$rs->EOF)
			{
				$query1.=" or email='".$rs->fields[0]."'";
				$rs->MoveNext();
			}

			$query1.=" $searchby order by $ordby";
		}
		else
			$query1.="";
	}
	else
		$query1="Select * from inl_users where user_perm>1 and user_pend=$pend $searchby order by $ordby";

	/*if($start)
		$ogran=" limit $start, $displaylim";
	else$ogran=" limit $displaylim";
	if($displaylim)
		$query=$query1.$ogran;
	else
		$query=$query1;*/
	$query=$query1;
	if(strlen($dall)>0)
		$query="Select * from inl_users where user_perm>1 and user_pend=$pend $searchby order by $ordby";
	elseif($lim>0)
		pagenav("", $query1, "users", $start, $page_nav_vars);
	
	settype($start,"integer");
	settype($displaylim,"integer");
	//echo $query,";$displaylim,$start";

	if($displaylim)
		$rs = &$conn->SelectLimit($query,$displaylim,$start);
	else
		$rs = &$conn->Execute($query);
	if($rs)
	{	$i=0;
		while($rs && !$rs->EOF)
		{	if(($i%2)==1)
				$bgc="#dedede";
			else
				$bgc="#f6f6f6";

			$i++;
			$userlist=$userlist."<tr bgColor='$bgc' vAlign='center'><form action=\"users.php?$att_sid"."w=w$attach\" method=\"post\">
            	    <input type='hidden' name='pend' value='$pend'> 
				<td class='text' nowrap>".$rs->fields[1]."</td>
			    <td class='text' nowrap>".$rs->fields[3]." ".$rs->fields[4]."</td>
            	    <td class='text' nowrap><a href=\"mailto:".$rs->fields[5]."\">".$rs->fields[5]."</a></td>
            	    <td class='text' nowrap>";
			if($rs->fields[9]==1){
				if($rs->fields[6]==2){$status=$la_admin;}
				elseif($rs->fields[6]==3){$status=$la_users;}
				elseif($rs->fields[6]==4){$status=$la_mailing_list;}
				elseif($rs->fields[6]==5){$status=$la_editor;}
			}else{$status="$la_disabled";}
			$dat=date("n/j/Y",$rs->fields[7]); 
            	$userlist=$userlist. " $status</td>
				<td class='text' nowrap>$dat</td>
            	      <td class='text' noWrap><input class='button1' name='submit' type='submit' value='$la_button_edit'>
            	      <input class='button2' name='submit' type='submit' value='$la_button_delete'> ";
			if($pend==1){$userlist=$userlist. " <input class='button3' name='submit' type='submit' value='$la_button_approve'>";}		
			$userlist=$userlist. "<input type='hidden' name='userid' value='".$rs->fields[0]."'> </td>
              		</form></tr>";
		$rs->MoveNext();
		}
	if(($i%2)==1)
		$bgc="#dedede";
	else
		$bgc="#f6f6f6";

	$userlist=$userlist  ." <td bgColor='$bgc' class='text' align='left' colSpan='2' valign='top'>";
	
	if($pend!=1 && $duplicatemail!=1){$userlist=$userlist  ."<form name='form2' action=\"edituser.php?$att_sid\" method=\"post\"><input class='button' name='submit' type='submit' value='$la_button_new_user'></form>";}
	$userlist=$userlist  ."&nbsp;</td>
					<td bgColor='$bgc' class='text' colSpan='4' align='right' valign='top'><form action=\"users.php?$att_sid\" method=\"post\">";
					
	if($duplicatemail!=1)
		$userlist.="<input type='text' name='searchkey' class='text' size='30'>&nbsp;
                                <input class='button' name='search' type='submit' value='$la_button_search'>&nbsp;
                                <input class='button' name='no' type='submit' value='$la_button_display_all'>";
	$userlist.="<br><input type='hidden' name='pend' value='$pend'>";

    if($duplicatemail!=1)
		$userlist.="<a href=\"search_advanced.php?$att_sid"."user=1&pend=$pend\"><b class=\"botlinks\"><span class=\"adminitem\">$la_advanced_search <img height=\"9\" name=\"ar1\" border=0 src=\"images/arrow1.gif\" width=\"8\"></span></b></a>";
		
	$userlist.="<br></form>$pagenav</td></tr></table>";
	}
	else
		$userlist = "<tr bgColor='#dedede' vAlign='center'><td class='text' align='center' colspan='6'>$la_no_users</td></tr></table>";
	return $userlist;
}


//edit users
function adduser($user_name1,$user_pass1, $email1, $first1, $last1,$umonth1 ,$uday1, $uyear1, $user_perm1, $user_status1, $ucust1, $ucust2, $ucust3, $ucust4, $ucust5, $ucust6){
	global $conn;
	
	$user_date1=mktime(0,0,0,$umonth1,$uday1,$uyear1);
	$user_name1=inl_escape($user_name1);
	$user_pass1=md5($user_pass1);
	$email1=inl_escape($email1);
	$first1=inl_escape($first1);
	$last1=inl_escape($last1);
	$ucust1=inl_escape($ucust1);
	$ucust2=inl_escape($ucust2);
	$ucust3=inl_escape($ucust3);
	$ucust4=inl_escape($ucust4);
	$ucust5=inl_escape($ucust5);
	$ucust6=inl_escape($ucust6);
	if($user_status1!=1){$user_status1=0;}
	$user_perm1=inl_escape($user_perm1);
	if(strlen($ucust1)>0 || strlen($ucust2)>0 || strlen($ucust3)>0 || strlen($ucust4)>0 || strlen($ucust5)>0 || strlen($ucust5)>0)
	{
		$query="INSERT INTO inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$ucust1', '$ucust2', '$ucust3', '$ucust4', '$ucust5', '$ucust6')";
		$conn->Execute($query);
		$user_cust=&$conn->Insert_ID("inl_custom","cust_id");
	}
	else
		$user_cust=0;
	$query="insert into inl_users (user_name, user_pass, first, last, email, user_perm, user_date, user_status, user_cust) values ('$user_name1', '$user_pass1', '$first1', '$last1', '$email1', $user_perm1, $user_date1, $user_status1, $user_cust)";
	if($conn->Execute($query)==true)
		return 1;
	else
		return 0;
}


//display user
function getuser($uid){
	global $conn, $user_name, $first, $last, $email, $uday, $umonth, $uyear, $user_pass, $user_perm_t, $user_status, $cust1, $cust2, $cust3, $cust4, $cust5, $cust6;
	$query="Select * from inl_users where user_id=$uid";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF){
		$user_name=$rs->fields[1];
		$first=$rs->fields[3];
		$last=$rs->fields[4];
		$email=$rs->fields[5];
		$user_pass=$rs->fields[2];
		$user_perm_t=$rs->fields[6];
		$uyear=date("Y",$rs->fields[7]);
		$uday=date("j",$rs->fields[7]);
		$umonth=date("n",$rs->fields[7]);
		$user_status=$rs->fields[9];
		$user_cust=$rs->fields[8];
		if($user_cust!=0){
				$query="Select * from inl_custom where cust_id=$user_cust";
				$rs = &$conn->Execute($query);
				if($rs && !$rs->EOF){
					$cust1=$rs->fields[1];
					$cust2=$rs->fields[2];
					$cust3=$rs->fields[3];
					$cust4=$rs->fields[4];
					$cust5=$rs->fields[5];
					$cust6=$rs->fields[6];
				}
		}
	}				 
}


//edit user
function edituser($id1,$user_name1,$user_pass1, $email1, $first1, $last1,$umonth1 ,$uday1, $uyear1, $user_perm1, $user_status1, $ucust1, $ucust2, $ucust3, $ucust4, $ucust5, $ucust6)
{	global $conn;
	$user_date1=mktime(0,0,0,$umonth1,$uday1,$uyear1);
	$id1=inl_escape($id1);
	$user_name1=inl_escape($user_name1);
	if($user_pass1) //if password was changed
		$user_pass1=md5($user_pass1);
	$email1=inl_escape($email1);
	$first1=inl_escape($first1);
	$last1=inl_escape($last1);
	$user_perm1=inl_escape($user_perm1);
	$ucust1=inl_escape($ucust1);
	$ucust2=inl_escape($ucust2);
	$ucust3=inl_escape($ucust3);
	$ucust4=inl_escape($ucust4);
	$ucust5=inl_escape($ucust5);
	$ucust6=inl_escape($ucust6);
	if($user_status1!=1){$user_status1=0;}
	if($user_pass1) //if password was changed
		$query="update inl_users set user_name='$user_name1', user_pass='$user_pass1', first='$first1', last='$last1', email='$email1', user_perm=$user_perm1, user_date=$user_date1, user_status=$user_status1 where user_id=$id1";
	else
		$query="update inl_users set user_name='$user_name1', first='$first1', last='$last1', email='$email1', user_perm=$user_perm1, user_date=$user_date1, user_status=$user_status1 where user_id=$id1";
	if(!$conn->Execute($query))
		return 0;


	$query="SELECT user_cust FROM inl_users WHERE user_id=$id1";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF)
		$user_cust_id=$rs->fields[0];
	else
		return 0;

	if($ucust1 || $ucust2 || $ucust3 || $ucust4 || $ucust5 || $ucust6) //cust fields exist
	{	if($user_cust_id) //record already exists
		{	$query="update inl_custom set cust1='$ucust1', cust2='$ucust2', cust3='$ucust3', cust4='$ucust4', cust5='$ucust5', cust6='$ucust6' where cust_id=".$user_cust_id;

			if($conn->Execute($query))
				return 1;
			else
				return 0;
		}
		else
		{	$query="insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$ucust1', '$ucust2', '$ucust3', '$ucust4', '$ucust5', '$ucust6')";

			$conn->Execute($query);
			$user_cust_id=$conn->Insert_ID("inl_custom","cust_id");

			$query="UPDATE inl_users SET user_cust=$user_cust_id WHERE user_id=$id1";
			$conn->Execute("$query");
			if($conn->Execute($query))
				return 1;
			else
				return 0;
		}
	}
	else  //no custom fields
	{	if($user_cust_id)
		{	$query="DELETE FROM inl_custom WHERE cust_id=$user_cust_id"; //delete record
			$conn->Execute($query);

			$query="UPDATE inl_users SET user_cust=0 WHERE user_id=$id1";
			if($conn->Execute($query))
				return 1;
			else
				return 0;
		} //else nothing needs to be done
		else
			return 1;

	}
	
}


//delete user
function deleteuser($uid){
	global $conn, $subject, $email, $reply, $email_perm, $from;
	if($email_perm[7]==1){
		$query="select * from inl_users where user_id=$uid";
		$rs = &$conn->Execute($query);
		global $user_data;
		$user_data = $rs->fields;
		if($user_data["user_pend"]==1){
			include("../includes/admin_email_lib.php");
			$body=email_parse("mail_user_denied");
			if($user_data[5])
				@mail($user_data[5], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
		}
	}
	$conn->Execute("Delete from inl_fav where user_id='$uid'");
	$query="Select user_cust from inl_users where user_id=$uid";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF){
		$query="delete from inl_users where user_id=$uid";
		$conn->Execute($query);
		if($rs->fields[0]!=0){
			$query="delete from inl_custom where cust_id=".$rs->fields[0];
			$conn->Execute($query);
		}
	}
}


//count total users
function totalusers(){
	global $conn, $tes;
	$query="Select count(user_id) as count from inl_users";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF)
		$tes=$rs->fields[0];
}
function approve_user($id){
	global $conn, $subject, $email, $reply, $email_perm, $from;
	$query="Update inl_users set user_pend=0 where user_id='$id'";
	$conn->Execute($query);
	if($email_perm[6]==1){
		$query="select * from inl_users where user_id=$id";
		$rs = &$conn->Execute($query);
		global $user_data;
		$user_data=$rs->fields;
		include("../includes/admin_email_lib.php");
		$body=email_parse("mail_user_approved");
		if($user_data["email"])
			@mail($user_data["email"], $subject, $body, "From:$from<$reply>\r\nReply-to:$reply");
	}
}

?>