<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);
function update_all_link_count($sub=0) 
{	global $conn;
	$query="select cat_id from inl_cats where cat_sub=$sub and cat_pend=0";
	$val=0;
	//all childern and their links
	$rs = &$conn->Execute($query);
	
	while ($rs && !$rs->EOF) 
	{	$t=update_all_link_count($rs->fields[0]);
		$val+=$t;
		$query="Update inl_cats set cat_links=$t where cat_id = ".$rs->fields[0];
		$conn->Execute($query);

		$rs->MoveNext();
	}

	//its own links
	$query="select count(link_id) from inl_lc where cat_id=$sub and link_pend=0";
	$rs = &$conn->Execute($query);
	if ($rs && !$rs->EOF) 
	$val+=$rs->fields[0];

	return $val; 
}
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle;?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<LINK rel="stylesheet" href="../admin.css" type="text/css">
</HEAD>

<BODY bgcolor="#FFFFFF" text="#000000">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="0">
  <TR> 
    <TD rowspan="2" width="0"><IMG src="../images/icon8-.gif" width="32" height="32"></TD>
    <TD class="title" width="100%"><?php echo $la_nav7; ?></TD>
    <TD rowspan="2" width="0"><A href="../help/manual.pdf"><IMG src="../images/but1.gif" width="30" height="32" border="0"></A><A href="../confirm.php?action=logout" target="_top"><IMG src="../images/but2.gif" width="30" height="32" border="0"></A></TD>
  </TR>
  <TR> 
    <TD width="100%"><IMG src="images/line.gif" width="354" height="2"></TD>
  </TR>
</TABLE>
<BR>

<?php

if(strlen($submit)>0){
	$time=time();
	if($cnt==0)
	{	if(!$file=@fopen($link_path."/links.db","r"))
		{	echo "<span class='test'>Could not open file</span><p align='center'><a href='install_link.php' class='tableitem'>Back</a> <IMG src='../images/arrow1.gif' width='8' height='9'></p></body></html>";
			exit();
		}else
		{	$total=0;
			while(!feof($file))
			{	fgetc($file);
				if(ftell($file)>0){$total=ftell($file);}
			}
			fclose($file);
		}
		$link_image=inl_escape($link_image);
		$query="insert into inl_cats (cat_name, cat_desc, cat_sub, cat_links, cat_user, cat_perm, cat_vis, cat_date, cat_pick,  cat_cust) values ('Lost and Found', 'This category contains all the lost categories and their links', '0' , '0', 1, 0, 0, '$time', 0, 0)";
		$conn->Execute($query);
		$lost =$conn->Insert_ID("inl_cats","cat_id");
	}
	$percent=100*$cnt/$total;
	$percent=@number_format($percent,1);

	if($link_pend==0)
		$pend=0;
	else
		$pend=1;
	if($link_pick==1)
		$pick=1;
	else
		$pick=0;
	if($link_vis==1)
		$vis=1;
	else
		$vis=0;
	if(@file_exists($link_path."/links.db"))
	{	$fd = @fopen($link_path."/links.db", "r");
		if($cnt>0)
			fseek($fd,$cnt);
		for($o=0;$o<50;$o++)
		{	$string=fgets($fd,2000);
			if(strlen($string)>0)
			{	$string=ereg_replace("\n$","",$string);
				$data=split("\|",$string);
				for($k=0;$k<count($data);$k++)
					$data[$k]=inl_escape($data[$k]);
				
				if($name){$insert_name=$data[$name];}
				if($url){$insert_url=$data[$url];}
				if($date){$insert_date=strtotime($data[$date]);}
				if($desc){$insert_desc= ereg_replace("''","\n",$data[$desc]);}
				if($hits){$insert_hits=$data[$hits];}
				if($votes){$insert_votes=$data[$votes];}
				if($rating){$insert_rating=$data[$rating]/2;}
				if($insert_rating>5){$insert_rating=5;}
				if($fullname){$insert_user_name=$data[$fullname];}
				if($email){$insert_user_email=$data[$email];}
				if($cust1!="" || $cust2!="" || $cust3!="" || $cust4="" || $cust15="" || $cust6!=""){	
					if($cust1){$insert_cust1=$data[$cust1];}
					if($cust2){$insert_cust2=$data[$cust2];}
					if($cust3){$insert_cust3=$data[$cust3];}
					if($cust4){$insert_cust4=$data[$cust4];}
					if($cust5){$insert_cust5=$data[$cust5];}
					if($cust6){$insert_cust6=$data[$cust6];}
					
					$query="Insert into inl_custom (cust1, cust2, cust3, cust4, cust5, cust6) values ('$insert_cust1', '$insert_cust2', '$insert_cust3', '$insert_cust4', '$insert_cust5', '$insert_cust6')";
					$conn->Execute($query);
					$link_cust=$conn->Insert_ID("inl_custom","cust_id");
				}else
					$link_cust=0;
				if(strlen($insert_user_email)>0){
					$query="Select * from inl_users where email='$insert_user_email'";
					$rs = &$conn->Execute($query);
					if ($rs && !$rs->EOF) 
						$link_user=$rs->fields[0];
					else
					{
						$ti=trim($insert_user_name);
						if(strlen($ti)>0)
						{
							$space=strpos($ti," ",0);
							if(!$space)
							{
								$first=$ti;
								$last="";
							}
							else
							{
								$first=substr($ti, 0, $space);
								$last=substr($ti, $space+1, strlen($ti));
							}
						}
						$password=md5($pass);
						
						$query="INSERT INTO inl_users (user_name,first, last,user_pass, email, user_perm, user_date, user_cust, user_status, user_pend) VALUES ('$insert_user_email','$first','$last','$password','$insert_user_email', '4', '$time', '0', '1', '0')";
						$conn->Execute($query);
						$link_user=$conn->Insert_ID("inl_users","user_id");
					}
				}
				else
					$link_user=$ses["user_id"];

				$ids="";
				$f=0;
				for($n=0;$n<3;$n++)
				{	if($cat[$n]>0)
					{	$m=$cat[$n];
						if($data[$m]=="")
							break;
						$data[$m]=ereg_replace("_"," ",$data[$m]);
						$link=split("/",$data[$m]);
						
						$count=count($link);
						$id=0;
						for($t=0;$t<$count;$t++)
						{	
							$query="Select cat_id from inl_cats where cat_sub=$id and cat_name='".$link[$t]."'";
							$rs = &$conn->Execute($query);
							if ($rs && !$rs->EOF) 
							{	if($t==$count-1)
									$ids[$f]=$rs->fields[0];
								else
									$id=$rs->fields[0];
							}
							else
							{	$error="Inserting $data[$name] Failed<br>";
								$ids[$f]=$lost;
								break;
							}
						}
					$f++;
					}
				}
				if($ids[0]>0)
				{	
					$query="INSERT INTO inl_links (link_name, 	link_desc, link_url, link_date, link_user, link_hits, link_votes, link_rating, link_pick, link_vis, link_image, link_cust, link_numrevs) VALUES ('$insert_name', '$insert_desc', '$insert_url', '$insert_date', '$link_user', '$insert_hits', '$insert_votes', '$insert_rating', '$pick', '$vis', '$link_image', '$link_cust', '0')";
					$conn->Execute($query);
					$link_id=$conn->Insert_ID("inl_links","link_id");
					if($link_id)
					{
						for($v=0;$v<count($ids);$v++)
						{	
							$query="INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ( '$link_id', '$ids[$v]', '$pend')";
							#echo $query,"<br>";
							$conn->Execute($query);
						}
					}

					else
						$error="Inserting $data[$name] Failed<br>";
				}
			}
			else
			{	$query="Select count(link_id) from inl_lc where cat_id=$lost";
				$rs = &$conn->Execute($query);
				if (!$rs->EOF && $rs)
					$res=$rs->fileds[0];
				if($res==0){
					$query="delete from inl_cats where cat_id='$lost'";
						$conn->Execute($query);
				}

				update_all_link_count(0); 
				echo "<hr noshade size=1><span class='text'><b>Message Board: DONE</b></span><hr noshade size=1></body></html>";
				exit();
			}
		}
		$cnt=ftell($fd);
		fclose($fd);
		if($percent>=0)
			$rt="$percent %"; 
		echo "<hr noshade size=1><span class='text'>
			<b>Message Board: $rt</b></span><br><span class='error'>$error</span><hr noshade size=1>

			<script language=\"javascript\">
			<!-- 

			location.href=\"install_link.php?link_path=$link_path&link_pend=$link_pend&link_pick=$link_pick&link_vis=$link_vis&link_image=$link_image&submit=1&cnt=$cnt&total=$total&lost=$lost&name=$name&url=$url&date=$date&desc=$desc&hits=$hits&votes=$votes&rating=$rating&fullname=$fullname&pass=$pass&email=$email&cust1=$cust1&cust2=$cust2&cust3=$cust3&cust4=$cust4&cust5=$cust5&cust6=$cust6&cat[0]=$cat[0]&cat[1]=$cat[1]&cat[2]=$cat[2]\"

			//-->
			</script>
			</body>
			</html>";
		exit();

	}else{
		echo "<span class='test'>Could not open file</span><p align='center'><a href='install_link.php' class='tableitem'>Back</a> <IMG src='../images/arrow1.gif' width='8' height='9'></p></body></html>";
		exit();
	}
}

?>
<?php if($error){echo "<p align='center' class='error'>$error</p>";}?>
<?php if($msg){echo "<p align='center' class='text'><b>$msg</b></p>";}?>
<FORM name="install_link" method="get" action="install_link.php">
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
<TR> 
      <TD class="tabletitle" bgcolor="#666666">Troubleshooting</TD>
  </TR>
  <TR> 
      <TD bgcolor="#F6F6F6"> <SPAN class="hint"><IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		This import will assume that all your categories were inserted sucessfully. If the category is missing all ist links will be inserted into a subcategory of root called Lost And Found<BR>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		If you run this import more than once please make sure you clean up your browser cache!<BR>
		</SPAN></TD>
  </TR>
  <TR> 
    <TD class="tabletitle" bgcolor="#666666">
	<?php echo "$la_title_import $la_links";?>
	</TD>
  </TR>
  <TR>
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="text"><?php echo $la_link_path; ?></TD>
            <TD> 
              <INPUT type="text" name="link_path" class="text" size="50" value="<?php echo $filedir; ?>">
              <SPAN class="text">
              /links.db
              </SPAN></TD>
          </TR>
		  <TR bgcolor="#DEDEDE"> 
		    <TD valign="top" colspan="2"><SPAN class="text">
			  <?php echo $la_link_specific; ?>
			</SPAN></TD>
		    
		</TR>
          <TR> 
            <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_pending;?></TD>
            <TD bgcolor="#F6F6F6"> 
              <INPUT type="checkbox" name="link_pend" value='1'>
            </TD>
          </TR>
    
		  <TR> 
            <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_editor_pick;?></TD>
            <TD bgcolor="#F6F6F6"> 
              <INPUT type="checkbox" name="link_pick" value='1'>
            </TD>
          </TR>
    
          <TR bgcolor="#F6F6F6">
            <TD valign="top" class="text">
              <?php echo $la_visible; ?>
            </TD>
            <TD>
              <INPUT type="checkbox" name="link_vis" checked value='1'>
            </TD>
          </TR>
          <TR bgcolor="#F6F6F6">
            <TD valign="top"><SPAN class="text"><?php echo $la_link_graphic; ?></SPAN></TD>
            <TD> 
              <INPUT type="text" name="link_image" class="text" size="30" value="">
            </TD>
          </TR>
        </TABLE>

     
    </TD>
  </TR>
 <TR> 
      <TD class="tabletitle" bgcolor="#666666">Warning</TD>
  </TR>
  <TR> 
      <TD bgcolor="#F6F6F6"> <SPAN class="hint"><IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
        If your database has been customized you can specify the order of all
        your fields in your links.db!<BR>
        <IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
        Do not count your id, start from the second field which will be 1 e.t.c!<BR>
        <IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
        Be extremely careful in counting because you can damage your database!!!</SPAN></TD>
  </TR>
   <TR> 
      <TD class="tabletitle" bgcolor="#666666">Advanced</TD>
  </TR>
  <TR> 
      <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Name <INPUT type="text" name="name" size="2" value="1" maxlength="2">&nbsp;&nbsp;
        Url <INPUT type="text" name="url" size="2" value="2" maxlength="2">&nbsp;&nbsp;
        Date <INPUT type="text" name="date" size="2" value="3" maxlength="2">&nbsp;&nbsp;
        Description <INPUT type="text" name="desc" size="2" value="5" maxlength="2">&nbsp;&nbsp;
        Hits <INPUT type="text" name="hits" size="2" maxlength="2">&nbsp;&nbsp;
        Votes <INPUT type="text" name="votes" size="2" maxlength="2">&nbsp;&nbsp;
        Rating&nbsp;&nbsp; <INPUT type="text" name="rating" size="2" maxlength="2"></SPAN></TD>
  </TR>

  <TR> 
      <TD bgcolor="#F6F6F6" nowrap valign="top" align="left"> <SPAN class="hint">The
        first category is required, all others are optional categories to which
        the link is referenced</SPAN></TD>
  </TR>

  <TR> 
      <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Category 1 <INPUT type="text" name="cat[0]" size="2" value="4" maxlength="2">&nbsp;&nbsp;
        Category 2 <INPUT type="text" name="cat[1]" size="2" maxlength="2">&nbsp;&nbsp;
        Category 3 <INPUT type="text" name="cat[2]" size="2" maxlength="2"></SPAN></TD>
  </TR>

  <TR> 
      <TD bgcolor="#F6F6F6" nowrap valign="top" align="left"> You can use those 
		6 custom fields for your link</TD>
  </TR>

  <TR> 
      <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Custom
        1 <INPUT type="text" name="cust1" size="2" maxlength="2">&nbsp;&nbsp;
        Custom 2 <INPUT type="text" name="cust2" size="2" maxlength="2">&nbsp;&nbsp;
        Custom 3 <INPUT type="text" name="cust3" size="2" maxlength="2">&nbsp;&nbsp;
        Custom 4 <INPUT type="text" name="cust4" size="2" maxlength="2">&nbsp;&nbsp;
        Custom 5 <INPUT type="text" name="cust5" size="2" maxlength="2">&nbsp;&nbsp;
        Custom 6 <INPUT type="text" name="cust6" size="2" maxlength="2"></SPAN></TD>
  </TR>
<TR> 
      <TD bgcolor="#F6F6F6" nowrap valign="top" align="left">Users Settings - 
		this will insert a user</TD>
  </TR>

  <TR> 
      <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Name 
		<INPUT type="text" name="fullname" size="2" maxlength="2" value="6">
		&nbsp;&nbsp; Email 
		<INPUT type="text" name="email" size="2" maxlength="2" value="7">
		--- Password 
		<INPUT type="text" name="pass" size="30" maxlength="50">
		Username will be prefilled with the email!!!</SPAN></TD>
  </TR>

</TABLE>
<P>
<INPUT type="hidden" name="cnt" value="0">
<INPUT type="submit" name="submit" value="<?php echo $la_title_import; ?>" class="button">
<INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
<INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
</P>
</FORM>
</BODY>
</HTML>
