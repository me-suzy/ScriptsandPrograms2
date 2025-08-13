<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);
include("../../includes/cats_lib.php");
?>
<HTML>
<HEAD>
<TITLE><?php echo $la_pagetitle; ?></TITLE>
<META http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
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
if(strlen($submit)>0)
{	
	if(!$cnt)
		$cnt=0;
	{	if(!$file=@fopen($cat_path."/categories.db","r"))
		{	echo "<span class='error'>Could not open file</span><p align='center'><a href='install_cat.php' class='tableitem'>Back</a> <IMG src='../images/arrow1.gif' width='8' height='9'></p></body></html>";
			exit();
		}else
		{	$total=0;
			while(!feof($file))
			{	fgetc($file);
				if(ftell($file)>0)
					$total=ftell($file);
			}
			fclose($file);
		}
		$cat_image=inl_escape($cat_image);
	}
	$percent=100*$cnt/$total;
	$percent=@number_format($percent,1);
	$perm=($all_cat_perm)*3+($reg_cat_perm);
	if($cat_vis==1)
		$vis=1;
	else
		$vis=0;
	if($cat_pend==1)
		$pend=1;
	else
		$pend=0;
	if($cat_pick==1)
		$pick=1;
	else
		$pick=0;
	if(@file_exists($cat_path."/categories.db"))
	{	$fd = @fopen($cat_path."/categories.db", "r");
		if($cnt>0)
			fseek($fd,$cnt);
		for($o=0;$o<50;$o++)
		{	$string=fgets($fd,2000);
			if(strlen($string)>0)
			{	$string=ereg_replace("\n$","",$string);
				$data=split("\|",$string);
				$data[1]=inl_escape($data[1]);
				$data[1]=ereg_replace("_"," ",$data[1]); //spaces instead of ' ' in GT 
				$data[2]=inl_escape($data[2]);
				$data[4]=inl_escape($data[4]);
				$data[5]=inl_escape($data[5]);
				$cat=split("/",$data[1]);
				$count=count($cat);
				$t=0;
				$id=0;
				$skip=0;
				while($count>0)
				{	$query="SELECT cat_id FROM inl_cats WHERE cat_sub=$id and cat_name='$cat[$t]'";
					$rs = &$conn->Execute($query);
					if ($rs && !$rs->EOF) 
					{	if($count==1)
						{	$date=@time();
							$name=$cat[$t];
							$query="Update inl_cats set cat_desc='$data[2]', meta_keywords='$data[4]', meta_desc='$data[5]' where cat_id=".$rs->fields[0];
							if ($conn->Execute($query) === false)
								$error.="Update $cat[$t] Failed<br>";
							break;
						}
						else{
							$id=$rs->fields[0];
							$t++;
							$count--;
						}
					}
					else
					{	if($count!=1)
						{	$date=@time();
							$name=$cat[$t];
							
							$query="INSERT INTO inl_cats (cat_name, cat_user, cat_sub, cat_perm, cat_pend, cat_vis, cat_links, cat_cats, cat_date, cat_pick, cat_image, cat_cust) VALUES ('$name',  '1', '$id', '$perm', '$pend', '$vis', '0', '0', '$date', '$cat_pick', '$cat_image', '0')";
							$conn->Execute($query);
							$id=$conn->Insert_ID("inl_cats","cat_id");
							$count--;
							$t++;
						}
						else
						{	$name=$cat[$t];
							$date=@time();
							$query="INSERT INTO inl_cats (cat_name, cat_desc, cat_user, cat_sub, cat_perm, cat_pend, cat_vis, cat_links, cat_cats, cat_date, cat_pick, cat_image, cat_cust, meta_desc, meta_keywords) VALUES ('$name', '$data[2]', '1', '$id', '$perm', '$pend', '$vis', '0', '0', '$date', '$cat_pick', '$cat_image', '0', '$data[4]', '$data[5]')";
							if ($conn->Execute($query) === false)  
								$error.="Insert $name Failed<br>";
							break;
						}
					}
				}
			}else{
				update_all_cat_count(0); 
				echo "<hr noshade size=1><span class='text'><b>Message Board: DONE</b></span><hr noshade size=1><p align='center'><a href='install_link.php' class='tableitem'>Proceed to links import</a> <IMG src='../images/arrow1.gif' width='8' height='9'></p></body></html>";
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

			location.href=\"install_cat.php?cat_path=$cat_path&cat_pend=$pend&cat_pick=$pick&cat_vis=$vis&cat_image=$cat_image&submit=1&cnt=$cnt&total=$total&all_cat_perm=$all_cat_perm&$reg_cat_perm=$reg_cat_perm\"

			//-->
			</script>
			</body>
			</html>";
		exit();

	}else{
		echo "<span class='test'>Could not open file</span></body></html>";
		exit();
	}
}

?>
<FORM name="installcat" method="post" action="install_cat.php?">
  <TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
      <TD class="tabletitle" bgcolor="#666666">Troubleshooting</TD>
  </TR>
  <TR> 
      <TD bgcolor="#F6F6F6"> <SPAN class="hint"><IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		This import will create categories even if they were missing in your GT database and will not lose any subcategories<br>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		If you run this import more than once please make sure you clean up your browser cache!<br>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		If your database stucture is diffrent then the GT Links 2.x flat-file do not run this import!!!<br>
		<IMG src="../images/mark.gif" width="16" height="16" align="absmiddle"> 
		If your categories have been inserted successfully you can </SPAN><a href='install_link.php' class='tableitem'>Proceed to links import</a> <IMG src='../images/arrow1.gif' width='8' height='9'> </TD>
  </TR>
  <TR> 
	<TD class="tabletitle" bgcolor="#666666"><?php echo "$la_title_import $la_categories"; ?></TD>
  </TR>
  <TR> 
	<TD bgcolor="#F6F6F6"> 
	  	<TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
		<TR> 
		    <TD valign="top"><SPAN class="text">
              <?php echo $la_cat_path; ?>
              </SPAN></TD>
		  <TD> <SPAN class="text">
			<INPUT type="text" name="cat_path" class="text" size="50" value="<?php echo $filedir; ?>">
			  /categories.db </span></TD>
		</TR>
		<TR bgcolor="#DEDEDE"> 
		    <TD valign="top" colspan="2"><SPAN class="text">
			  <?php echo $la_cat_specific; ?>
			</SPAN></TD>
		    
		</TR>
		<TR> 
		  <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_pending; ?></TD>
		  <TD bgcolor="#F6F6F6"> 
			<INPUT type="checkbox" name="cat_pend" class="text" value="1">
			</TD>
		</TR>
		<TR> 
		  <TD valign="top" class="text" bgcolor="#F6F6F6"><?php echo $la_editor_pick; ?></TD>
		  <TD bgcolor="#F6F6F6"> 
			<INPUT type="checkbox" name="cat_pick" class="text" value="1">
			</TD>
		</TR>
		<TR bgcolor="#F6F6F6"> 
		  <TD valign="top" class="text"><?php echo $la_visible; ?></TD>
		  <TD> 
			<INPUT type="checkbox" name="cat_vis" class="text" value="1" checked>
		  </TD>
		</TR>
		<TR bgcolor="#F6F6F6"> 
		    <TD valign="top"><SPAN class="text">
			  <?php echo $la_category_graphic; ?>
			  </SPAN></TD>
		  <TD> 
			<INPUT type="text" name="cat_image" class="text" size="30" value="">
		  </TD>
		</TR>

		<TR bgcolor="#DEDEDE"> 
		<TD valign="top" class="text" bgcolor="#DEDEDE" colspan="2">
		<?php echo $la_cat_permissions; ?>:</TD>
		</TR>

		
		<TR bgcolor="#F6F6F6"> 
		<TD valign="top" class="text" bgcolor="#F6F6F6">
		<?php echo $la_registered_users; ?>:</TD>
		
		  <TD bgcolor="#F6F6F6"> 
			<SELECT name="reg_cat_perm" class="text">
			  <OPTION value="1"><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
		  </TD>
		</TR>

		<TR bgcolor="#F6F6F6"> 
		<TD valign="top" class="text" bgcolor="#F6F6F6">
		<?php echo $la_notregistered_visitors; ?>:</TD>
		
		  <TD bgcolor="#F6F6F6"> 
			<SELECT name="all_cat_perm" class="text">
			  <OPTION value="1"><?php echo $la_drop_add_approval; ?></OPTION>
			  <OPTION value="0"><?php echo $la_drop_add_no; ?></OPTION>
			  <OPTION value="2"><?php echo $la_drop_add_yes; ?></OPTION>
			</SELECT>
		  </TD>
		</TR>
	  </TABLE>
	</TD>
  </TR>
</TABLE>
  <P>
	<INPUT type="submit" name="submit" value="<?php echo $la_title_import; ?>" class="button">
	<INPUT type="reset" name="Submit2" value="<?php echo $la_button_reset; ?>" class="button">
	<INPUT type="button" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button" onClick="history.back();">
  </P>
</FORM>
</BODY>
</HTML>