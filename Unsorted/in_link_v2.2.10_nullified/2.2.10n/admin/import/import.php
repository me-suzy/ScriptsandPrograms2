<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);

$link1=@mysql_connect("localhost", "$inlink1_user", "$inlink1_pass");
if(!$link1){echo "Connection to Inlink1 failed"; exit();}
$db1=$inlink1_db;
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
	if($count == 0){
		$query="delete from inl_cats where cat_id='$lost'";
		$conn->Execute($query);
	}
}

function addlink( $link_name, $link_desc, $link_date, $link_votes, $link_hits, $link_rating, $link_url, $link_cat, $cat_id, $link2)
{
	global $conn;
	$link_name = inl_escape($link_name);
	$link_url = inl_escape($link_url);
	$link_desc = inl_escape($link_desc);
	$link_vis = 1;
	$link_pick = 0;
	$link_user = 1;
	$link_rating=$link_rating/2;
	if($link_rating>5)
		$link_rating=5;
	
	$rs=&$conn->Execute("select max(link_id) from inl_links");
	if ($rs && !$rs->EOF) 
		$max=$rs->fields[0];
	$stop=100;
	do{
		$max++;
		$query="insert into inl_links (link_id, link_name, link_url, link_desc, link_date, link_rating, link_vis, link_pick, link_votes, link_hits, link_cust, link_user) values ($max, '$link_name', '$link_url', '$link_desc', '$link_date', '$link_rating', '$link_vis', '$link_pick', '$link_votes', '$link_hits', 0, '$link_user')";
		$stop--;
		if($stop==0)
			break;
	}while ($conn->Execute($query) != true);
	$link_id=$max;

	$query="insert into inl_lc (link_id, cat_id) values ($link_id, $cat_id)";
	$conn->Execute($query);
}

if($start<1)
{	$conn->Execute("Insert into inl_config (name, value) VALUES ('inlink1_user', '$inlink1_user')");
	$conn->Execute("Insert into inl_config (name, value) VALUES ('inlink1_pass', '$inlink1_pass')");
	$conn->Execute("Insert into inl_config (name, value) VALUES ('inlink1_db', '$inlink1_db')");

	$limitby=" limit 0, 100";
	$start=100;
	$count=0;
	$result =@mysql_db_query($db1,"Select * from links where catid=0", $links1);
	while($row=@mysql_fetch_array($result))
	{
		addlink($row["name"], $row["linkdesc"], $row["date"], $row["votes"], $row["hits"], $row["rating"], $row["url"], $row["catid"], 0,$link2);
	}	
	$query="drop table temp_cat";
	$res=@mysql_db_query($db1, $query, $link1);
	$query="create table temp_cat (id int(11), name varchar(200), descrip mediumtext, sub int(11) not null default 0, links int(11), perm int(11))";
	$res=@mysql_db_query($db1, $query, $link1);
	if(!$res)
	{
		echo "Creating temporary table failed","<br>",mysql_error();
		$conn->Execute("Delete from inl_config where name='inlink1_user'");
		$conn->Execute("Delete from inl_config where name='inlink1_pass'");
		$conn->Execute("Delete from inl_config where name='inlink1_db'");

		exit();

	}
	$query="insert into temp_cat select * from cat";
	$res=@mysql_db_query($db1,$query, $link1);
	if(!$res)
	{
		echo "Copying to temporary table failed","<br>",mysql_error();
		$query="drop table temp_cat";
		$res=@mysql_db_query($db1, $query, $link1);
		$conn->Execute("Delete from inl_config where name='inlink1_user'");
		$conn->Execute("Delete from inl_config where name='inlink1_pass'");
		$conn->Execute("Delete from inl_config where name='inlink1_db'");
		if(!$res)
		{
			echo "Deleteing temporary table failed","<br>";
			exit();
		}else
		{
			exit();
		}
	}
	$time=time();
	
	$rs=&$conn->Execute("select max(cat_id) from inl_cat");
	if ($rs && !$rs->EOF) 
		$max=$rs->fields[0];
	$stop=100;
	do{
		$max++;
		$query="insert into inl_cats (cat_id, cat_name, cat_desc, cat_sub, cat_links, cat_user, cat_perm, cat_vis, cat_date, cat_pick,  cat_cust) values ($max, 'Lost and Found', 'This category contains all the lost categories and their links', '0' , '0', 1, 0, 0, '$time', 0, 0)";
		$stop--;
		if($stop==0)
			break;
	}while ($conn->Execute($query) != true);
	$lst = $max;
}
else
{
	$limitby=" limit $start, 100";
	$start=$start+100;
}
$result = mysql_db_query($db1,"Select * from temp_cat order by sub asc $limitby", $link1);
if($row = @mysql_fetch_array($result))
{
	do
	{
		$old_id=$row["id"];         $cat_name=$row["name"];
		$cat_desc=$row["descrip"];  $cat_sub=$row["sub"];
		$cat_perm=$row["perm"];
		if($cat_perm==0){$cat_perm=8;}
		elseif($cat_perm==1){$cat_perm=0;}
		else{$cat_perm=4;}
		$cat_links=$row["links"];   $cat_date = time();
		$cat_name = inl_escape($cat_name);
		$cat_desc = inl_escape($cat_desc);
		if(!$cat_sub){$cat_sub=0;}
		if($cat_sub!=0)
		{
			$re = @mysql_db_query($db1,"Select name, descrip from temp_cat where id=".$row["sub"], $link1);
			if($ro = @mysql_fetch_array($re))
			{
				$query="Select cat_id from inl_cats where cat_name='".$ro["name"]."' and cat_desc='".$ro["descrip"]."'";
				$rs = &$conn->Execute($query);	
				if($rs && !$rs->EOF)
				{
					$cat_sub=$rs->fields[0];
				}
				else
				{
					$cat_sub=-1;
					echo $row["sub"]." ".$row["name"]." failed to find parent<br>";
				}
			}
			else
			{
				$cat_sub=-1;
				echo $row["sub"]." failed to find parent<br>";
			}
		}
		if($cat_sub==-1)
			$cat_sub=$lst;
		$rs=&$conn->Execute("select max(cat_id) from inl_cat");
		if ($rs && !$rs->EOF) 
			$max=$rs->fields[0];
		$stop=100;
		do{
			$max++;
			$query="insert into inl_cats (cat_id, cat_name, cat_desc, cat_sub, cat_links, cat_user, cat_perm, cat_vis, cat_date, cat_pick,  cat_cust) values ($max, '$cat_name', '$cat_desc', '$cat_sub' , '$cat_links', 1, $cat_perm, 1, '$cat_date', 0, 0)";
		$stop--;
		if($stop==0)
			break;
		}while ($conn->Execute($query) != true);
		$cat_id = $max;

		$resi = @mysql_db_query($db1,"Select * from links where catid=$old_id", $link1);
		if($ri = @mysql_fetch_array($resi))
		{
			do{
				addlink($ri["name"], $ri["linkdesc"], $ri["date"], $ri["votes"], $ri["hits"], $ri["rating"], $ri["url"], $ri["catid"], $cat_id, $link2);
			}while($ri = @mysql_fetch_array($resi));
		}
		$count++;
	}while($row = @mysql_fetch_array($result));
	echo " $count Categories transfered successfully<br>";
}else{
	$query="drop table temp_cat";
	$res=@mysql_db_query($db1, $query, $link1);
	if(!$res){echo "Deleteing temporary table failed","<br>",mysql_error();}
	$conn->Execute("Delete from inl_config where name='inlink1_user'");
	$conn->Execute("Delete from inl_config where name='inlink1_pass'");
	$conn->Execute("Delete from inl_config where name='inlink1_db'");
	fix_cats(0);
	lostandfound($lst);
	echo "Import Finished Successfully - $count Categories Imported";
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