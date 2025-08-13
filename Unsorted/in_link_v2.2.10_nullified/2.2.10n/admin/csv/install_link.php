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
	if ($rs && !$rs->EOF) 
	{	$t=update_all_link_count($rs->fields[0]);
		$val+=$t;
		$query="Update inl_cats set cat_links=$t where cat_id = ".$rs->fields[0];
		$conn->Execute($query);
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
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
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
if($sep=="|")
	$sep="\|";
if(strlen($submit)>0){
	$time=time();
	if($cnt==0)
	{	if(!$file=@fopen($link_path,"r"))
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
		$link_image=addslashes($link_image);
	}
	$percent=100*$cnt/$total;
	$percent=@number_format($percent,1);

	if($link_pend==1)
		$pend=1;
	else
		$pend=0;
	if($link_pick==1)
		$pick=1;
	else
		$pick=0;
	if($link_vis==1)
		$vis=1;
	else
		$vis=0;
	if(@file_exists($link_path))
	{	$fd = @fopen($link_path, "r");
		if($cnt>0)
			fseek($fd,$cnt);
		for($o=0;$o<50;$o++)
		{	$string=fgets($fd,2000);
			if(strlen($string)>0)
			{	$string=ereg_replace("\n$","",$string);
				$data=split($sep,$string);
				for($k=0;$k<count($data);$k++)
					$data[$k]=addslashes($data[$k]);				
				if($name){$insert_name=$data[$name-1];}
				if($url){$insert_url=$data[$url-1];}
				if($date){$insert_date=strtotime($data[$date-1]);}
				if($desc){$insert_desc= ereg_replace("''","\n",$data[$desc-1]);}
				if($hits){$insert_hits=$data[$hits-1];}
				if($votes){$insert_votes=$data[$votes-1];}
				if($rating){$insert_rating=$data[$rating-1];}
				if($insert_rating>5){$insert_rating=5;}
				if($cust1!="" || $cust2!="" || $cust3!="" || $cust4="" || $cust15="" || $cust6!="")
				{	
					if($cust1){$insert_cust1=$data[$cust1-1];}
					if($cust2){$insert_cust2=$data[$cust2-1];}
					if($cust3){$insert_cust3=$data[$cust3-1];}
					if($cust4){$insert_cust4=$data[$cust4-1];}
					if($cust5){$insert_cust5=$data[$cust5-1];}
					if($cust6){$insert_cust6=$data[$cust6-1];}
					$rs=&$conn->Execute("select max(cust_id) from inl_custom");
					if ($rs && !$rs->EOF) 
						$max=$rs->fields[0];
					$stop=100;
					do{
						$max++;
						$query="Insert into inl_custom (cust_id, cust1, cust2, cust3, cust4, cust5, cust6) values ('$max', '$insert_cust1', '$insert_cust2', '$insert_cust3', '$insert_cust4', '$insert_cust5', '$insert_cust6')";
						#echo $query,"<br>";
						$stop--;
						if($stop==0)
							break;
					}while ($conn->Execute($query) != true);
					$link_cust=$max;
				}else
					$link_cust=0;

				$rs=&$conn->Execute("select max(link_id) from inl_links");
				if ($rs && !$rs->EOF) 
					$max=$rs->fields[0];
				$stop=100;
				do{
					$max++;
					$query="INSERT INTO inl_links (link_id, link_name, 	link_desc, link_url, link_date, link_user, link_hits, link_votes, link_rating, link_pick, link_vis, link_image, link_cust, link_numrevs) VALUES ('$max', '$insert_name', '$insert_desc', '$insert_url', '$insert_date', '1', '$insert_hits', '$insert_votes', '$insert_rating', '$pick', '$vis', '$link_image', '$link_cust', '0')";
					#echo $query,"<br>";
					$stop--;
					if($stop==0)
						break;
				}while ($conn->Execute($query) != true);
				
				$link_id=$max;
				if($link_id)
				{
					
					$query="INSERT INTO inl_lc (link_id, cat_id, link_pend) VALUES ( '$link_id', '$cat', '$pend')";
						#echo $query,"<br>";
						$conn->Execute($query);
				}
				else
						$error="Inserting $insert_name Failed<br>";
			}
			else
			{	
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

			location.href=\"install_link.php?link_path=$link_path&link_pend=$link_pend&link_pick=$link_pick&link_vis=$link_vis&link_image=$link_image&submit=1&cnt=$cnt&total=$total&name=$name&url=$url&date=$date&desc=$desc&hits=$hits&votes=$votes&rating=$rating&cust1=$cust1&cust2=$cust2&cust3=$cust3&cust4=$cust4&cust5=$cust5&cust6=$cust6&cat=$cat&sep=$sep&sid=$sid\"

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
		If you run this import more than once please make sure you clean up your 
		browser cache!</SPAN><BR>
		<SPAN class="hint"><IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"></SPAN> 
		Make sure you do not have any new line or data separator characters in 
		your data!</TD>
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
			<TD valign="top" class="text">Full Path</TD>
			<TD> 
			  <INPUT type="text" name="link_path" class="text" size="50" value="<?php echo $filedir; ?>">
			  <SPAN class="text">(ex. home/dir/text.db)</SPAN></TD>
		  </TR>
		  <TR bgcolor="#DEDEDE"> 
			<TD valign="top"><SPAN class="text"> Data Separator</SPAN></TD>
			<TD valign="top" bgcolor="#DEDEDE"><SPAN class="text"> 
			  <INPUT type="text" name="sep" size="3" maxlength="1">
			  ( ex. ; )</SPAN></TD>
		  </TR>
		  <TR> 
			<TD valign="top" class="text" bgcolor="#F6F6F6"> 
			  <?php echo $la_pending;?>
			</TD>
			<TD bgcolor="#F6F6F6"> 
			  <INPUT type="checkbox" name="link_pend" value='1'>
			</TD>
		  </TR>
		  <TR bgcolor="#DEDEDE"> 
			<TD valign="top" class="text"> 
			  <?php echo $la_editor_pick;?>
			</TD>
			<TD> 
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
		  <TR bgcolor="#DEDEDE"> 
			<TD valign="top"><SPAN class="text"> 
			  <?php echo $la_link_graphic; ?>
			  </SPAN></TD>
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
		your fields in your links text file!<BR>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		Start counting from 1! <BR>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		Be extremely careful in counting because you can damage your database!!!</SPAN></TD>
	</TR>
	<TR> 
	  <TD class="tabletitle" bgcolor="#666666">Advanced</TD>
	</TR>
	<TR> 
	  <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Name 
		<INPUT type="text" name="name" size="2" maxlength="2">
		&nbsp;&nbsp; Url 
		<INPUT type="text" name="url" size="2" maxlength="2">
		&nbsp;&nbsp; Date 
		<INPUT type="text" name="date" size="2" maxlength="2">
		&nbsp;&nbsp; Description 
		<INPUT type="text" name="desc" size="2" maxlength="2">
		&nbsp;&nbsp; Hits 
		<INPUT type="text" name="hits" size="2" maxlength="2">
		&nbsp;&nbsp; Votes 
		<INPUT type="text" name="votes" size="2" maxlength="2">
		&nbsp;&nbsp; Rating&nbsp;&nbsp; 
		<INPUT type="text" name="rating" size="2" maxlength="2">
		</SPAN></TD>
	</TR>
	<TR> 
	  <TD bgcolor="#F6F6F6" nowrap valign="top" align="left"> <SPAN class="hint">Category 
		ID is required, otherwise all links will be inserted in the root!</SPAN></TD>
	</TR>
	<TR> 
	  <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Category 
		ID 
		<INPUT type="text" name="cat" size="4" maxlength="5">
		</SPAN></TD>
	</TR>
	<TR> 
	  <TD bgcolor="#F6F6F6" nowrap valign="top" align="left"> You can use those 
		6 custom fields for your link</TD>
	</TR>
	<TR> 
	  <TD bgcolor="#DEDEDE" nowrap valign="top" align="left"> <SPAN class="hint">Custom 
		1 
		<INPUT type="text" name="cust1" size="2" maxlength="2">
		&nbsp;&nbsp; Custom 2 
		<INPUT type="text" name="cust2" size="2" maxlength="2">
		&nbsp;&nbsp; Custom 3 
		<INPUT type="text" name="cust3" size="2" maxlength="2">
		&nbsp;&nbsp; Custom 4 
		<INPUT type="text" name="cust4" size="2" maxlength="2">
		&nbsp;&nbsp; Custom 5 
		<INPUT type="text" name="cust5" size="2" maxlength="2">
		&nbsp;&nbsp; Custom 6 
		<INPUT type="text" name="cust6" size="2" maxlength="2">
		</SPAN></TD>
	</TR>
  </TABLE>
<P>
<INPUT type="hidden" name="cnt" value="0">
<INPUT type="hidden" name="sid" value="<?php echo $sid; ?>">
<INPUT type="submit" name="submit" value="<?php echo $la_title_import; ?>" class="button">
<INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
<INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
</P>
</FORM>
</BODY>
</HTML>
