<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);

$link1=@mysql_connect("localhost", "$links_user", "$links_pass");
if(!$link1){echo "Connection to LinksSQL failed"; exit();}


$db2=$sql_db;
$db1=$links_db;
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
<TABLE width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <TR> 
      <TD class="tabletitle" bgcolor="#666666">Processing</TD>
  </TR>
  <TR>
    <TD bgcolor="#F6F6F6">
        <TABLE width="100%" border="0" cellspacing="0" cellpadding="4">
          <TR> 
            <TD valign="top" class="text">Message Board:
	  
<?php

function fix_cats($sub){
	global $conn;
	$query="select cat_id, cat_cats from inl_cats where cat_sub=$sub";
	$val["link"]=0;
	$val["cat"]=0;
	$rs = &$conn->Execute($query);
	while($rs && !$rs->EOF)
	{
		$t=fix_cats($rs->fields[0]);
		$val["cat"]=1+$val["cat"]+$t["cat"];
		$val["link"]=$t["link"]+$val["link"];
		$query="Update inl_cats set cat_cats=".$t["cat"].", cat_links=".$t["link"]." where cat_id = ".$rs->fields[0];
		$conn->Execute($query);
		$rs->MoveNext();
	}
	$rs = &$conn->Execute("select count(link_id) from inl_lc where cat_id=$sub and link_pend=0");
	$val["link"]=$rs->fields[0]+$val["link"];
	return $val; 
}

function lostandfound($lost){
	global $conn;
	$query="Select * from inl_cats where cat_sub='$lost'";
	$rs = &$conn->Execute($query);
	if($rs && !$rs->EOF)
		$count = $rs->RecordCount();
	else
		$count=0;
	if($count == 0 || !$count){
		$query="delete from inl_cats where cat_id='$lost'";
		$conn->Execute($query);
	}
}
$time=time();
if($start<1)
{	$conn->Execute("Insert into inl_config (name, value) VALUES ('links_user', '$links_user')");
	$conn->Execute("Insert into inl_config (name, value) VALUES ('links_pass', '$links_pass')");
	$conn->Execute("Insert into inl_config (name, value) VALUES ('links_db', '$links_db')");
	
	$limitby=" limit 0, 100";
	$start=100;
	$count=0;
	
	$query="insert into inl_cats (cat_name, cat_desc, cat_sub, cat_links, cat_user, cat_perm, cat_vis, cat_date, cat_pick,  cat_cust) values ('Lost and Found', 'This category contains all the lost categories and their links', '0' , '0', 1, 0, 0, '$time', 0, 0)";
	$conn->Execute($query);
	$lst = $conn->Insert_ID("inl_cats","cat_id");
}
else
{
	$limitby=" limit $start, 100";
	$start=$start+100;
}
$result = mysql_db_query($db1,"Select * from Category order by FatherID asc $limitby", $link1);
if($row = @mysql_fetch_array($result))
{

	do
	{
		$old_id=$row["ID"];         
		$cat_name=$conn->qstr($row["Name"], get_magic_quotes_gpc());
		$cat_desc=$conn->qstr($row["Description"], get_magic_quotes_gpc());  
		$cat_sub=$row["FatherID"];
		$cat_date = time();
		$meta_desc = $conn->qstr($row["Meta_Description"], get_magic_quotes_gpc());
		$meta_keywords = $conn->qstr($row["Meta_Keywords"], get_magic_quotes_gpc()) ;
		if($cat_sub!=0)
		{
			$re = @mysql_db_query($db1,"Select Name, Description from Category where ID=".$row["FatherID"], $link1);
			if($ro = @mysql_fetch_array($re))
			{
				$query="Select cat_id from inl_cats where cat_name=".$conn->qstr($ro["Name"], get_magic_quotes_gpc())." and cat_desc=".$conn->qstr($ro["Description"], get_magic_quotes_gpc());
				$rs = &$conn->Execute($query);	
				if($rs && !$rs->EOF)
				{
					$cat_sub=$rs->fields[0];
				}
				else
				{
					$cat_sub=-1;
					echo $row["FatherID"]." failed to find parent<br>";
				}
			}
			else
			{
				$cat_sub=-1;
				echo $row["FatherID"]." failed to find parent<br>";
			}
		}
		if($cat_sub==-1)
			$cat_sub=$lst;
		
		$query="INSERT INTO inl_cats (cat_name, cat_desc, cat_user, cat_sub, cat_perm, cat_pend, cat_vis, cat_links, cat_cats, cat_date, cat_pick, cat_image, cat_cust, meta_keywords, meta_desc) VALUES ($cat_name, $cat_desc, '1', '$cat_sub', '4', '0', '1', '0', '0', '$time', '0', '', '0', $meta_keywords, $meta_desc)";
		$conn->Execute($query);
		$cat_id = $conn->Insert_ID("inl_cats","cat_id");

		$resi = @mysql_db_query($db1,"Select LinkID from CatLinks where CategoryID='$old_id'", $link1);
		if($ri = @mysql_fetch_array($resi))
		{
			do{
				$rez = @mysql_db_query($db1,"Select * from Links where ID='".$ri["LinkID"]."'", $link1);
				if($red = @mysql_fetch_array($rez))
				{
					$link_name=$conn->qstr($red["Title"], get_magic_quotes_gpc());
					$link_desc=$conn->qstr($red["Description"], get_magic_quotes_gpc());
					$link_url=$conn->qstr($red["URL"], get_magic_quotes_gpc());
					$link_date=strtotime($red["Add_Date"]);
					$link_hits=$red["Hits"];
					$link_votes=$red["Votes"];
					$link_rating=$red["Rating"];
					
					$query="Select link_id from inl_links where link_name=$link_name and link_desc=$link_desc and link_url=$link_url";
					$rs1=&$conn->Execute($query);
					if ($rs1 && !$rs1->EOF) 
					{	$link_id=$rs->fields[0];
					}
					else
					{	$query="insert into inl_custom (cust1, cust2, cust3, cust4 ) values (".$conn->qstr($red["LinkOwner"]).", ".$conn->qstr($red["Contact_Name"], get_magic_quotes_gpc()).", ".$conn->qstr($red["Contact_Email"], get_magic_quotes_gpc()).", '".$red["Mod_Date"]."')";
						$conn->Execute($query);
						$link_cust=$conn->Insert_ID("inl_custom","cust_id");
						
						$query="INSERT INTO inl_links (link_name, link_desc, link_url, link_date, link_user, link_hits, link_votes, link_rating, link_pick, link_vis, link_image, link_cust, link_numrevs) VALUES ($link_name, $link_desc, $link_url, '$link_date', '1', '$link_hits', '$link_votes', '$link_rating', '0', '1', '', '$link_cust', '0') ";
						
						$conn->Execute($query);
						$link_id=$conn->Insert_ID("inl_links","link_id");
					}
					$query="insert into inl_lc (link_id, cat_id) values ($link_id, $cat_id)";
					$conn->Execute($query);
				}
			}while($ri = @mysql_fetch_array($resi));
		}
		$count++;
	}while($row = @mysql_fetch_array($result));
	echo " $count Categories transfered successfully<br>";
}else{
	fix_cats(0);
	lostandfound($lst);
	echo "Import Finished Successfully - $count Categories Imported<br>"; 
	$result = mysql_db_query($db1,"SELECT * FROM Users", $link1);
	if($row = @mysql_fetch_array($result))
	{
		$users=0;
		do
		{	$email=$conn->qstr($row["Email"], get_magic_quotes_gpc());
			if($row["Status"]=="Administrator")
				$user_perm=2;
			else
				$user_perm=4;
			$first=$conn->qstr($row["Name"], get_magic_quotes_gpc());
			$user_pass=md5($row["Password"]);
			$user_name=$conn->qstr($row["Username"], get_magic_quotes_gpc());
			$query="INSERT INTO inl_users ( user_name, user_pass, first, email, user_perm, user_date, user_cust, user_status, user_pend) VALUES ( $user_name, '$user_pass', $first, $email, '$user_perm', '$time', '0', '1', '0')";
			$conn->Execute($query);
			$user++;
		}while($row = @mysql_fetch_array($result));
		echo " $users Users imported<br>";
	}
	$stop=1;
}

$redirect="<script language=\"javascript\">
<!-- 

	location.href=\"import.php?start=$start&count=$count&lst=$lst\"  

//-->
</script> 
";	
	
	

?>
			</TD>
            
          </TR>
		 </TABLE>
    
		  
    </TD>
  </TR>
  
</TABLE>
<?php if($stop!=1){echo $redirect;} ?>
</body>

</html>