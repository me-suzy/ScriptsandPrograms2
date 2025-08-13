<?php
//Read in config file
$admin = 1;
$backup_inport = 1;
$configfile = "../../includes/config.php";
include($configfile);

//Conn to Server
$link1=@mysql_connect("localhost", "$indexu_user", "$indexu_pass");
if(!$link1){echo "Connection to IndexU failed"; exit();}

$db2=$mysql_db;
$db1=$indexu_db;


function fix_cats($sub){
	global $conn;
	$query="select cat_id, cat_cats from inl_cats where cat_sub=$sub";
	$val["link"]=0;
	$val["cat"]=0;
	$rs = $conn->Execute($query);
	while($rs && !$rs->EOF)
	{
		$t=fix_cats($rs->fields[0]);
		$val["cat"]=1+$val["cat"]+$t["cat"];
		$val["link"]=$t["link"]+$val["link"];
		$query="Update inl_cats set cat_cats=".$t["cat"].", cat_links=".$t["link"]." where cat_id = ".$rs->fields[0];
		$conn->Execute($query);
		$rs->MoveNext();
	}
	$rs = $conn->Execute("select count(link_id) from inl_lc where cat_id=$sub and link_pend=0");
	$row = $rs->fields;
	$val["link"]=$row[0]+$val["link"];
	return $val; 
}
function addlink($link_name, $link_desc, $link_date, $link_votes, $link_hits, $link_rating, $link_url,$link_pick, $cat_id, $oid, $db1, $link1)
{	global $conn;

	$link_name = inl_escape($link_name);
	$link_url = inl_escape($link_url);
	$link_desc = inl_escape($link_desc);
	$link_vis = 1;
	$link_user = 1;
	$link_rating=$link_rating/2;
	if($link_rating>5)
		$link_rating=5;
	
	$query="insert into inl_links (link_name, link_url, link_desc, link_date, link_rating, link_vis, link_pick, link_votes, link_hits, link_cust, link_user) values ('$link_name', '$link_url', '$link_desc', '$link_date', '$link_rating', '$link_vis', '$link_pick', '$link_votes', '$link_hits', 0, '$link_user')";
	$conn->Execute($query);

	$link_id = $conn->Insert_ID("inl_links","link_id");
	$rio=mysql_db_query($db1, "select name, review, date from review where link_id=$oid", $link1);
	$cou=0;
	if($ra = @mysql_fetch_array($rio))
	{
		do{
			$date=strtotime($ra["date"]);
			$query= "Insert into inl_reviews (rev_text, rev_date, rev_link, rev_user, rev_pend) values ('".$ra["review"]."', '$date', $oid, 1, 0)";
			if ($conn->Execute($query) === true) 
				$cou++;
		}while($ra = @mysql_fetch_array($rio));
	}
	$query="insert into inl_lc (link_id, cat_id) values ($link_id, $cat_id)";
	$conn->Execute($query);
	$query="update inl_links set numrevs=$cou where link_id=$link_id";
	$conn->Execute($query);

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

if($start<1)
{
	$conn->Execute("Insert into inl_config (name, value) VALUES ('indexu_user', '$indexu_user')");
	$conn->Execute("Insert into inl_config  (name, value) VALUES ('indexu_pass', '$indexu_pass')");
	$conn->Execute("Insert into inl_config  (name, value) VALUES ('indexu_db', '$indexu_db')");
	$limitby=" limit 0, 100";
	$start=100;
	$count=0;
}
else
{
	$limitby=" limit $start, 100";
	$start=$start+100;
}
$result = mysql_db_query($db1,"Select * from category order by parent_id asc $limitby", $link1);
if($row = @mysql_fetch_array($result))
{
	do
	{
		$old_id=$row["category_id"];
		$cat_name=$row["name"];
		$cat_desc=$row["description"];
		$cat_sub=$row["parent_id"];
		$cat_date = time();
		$cat_name = inl_escape($cat_name);
		$cat_desc = inl_escape($cat_desc);
		if($cat_sub!=0)
		{
			$re = @mysql_db_query($db1,"Select name, description from category where category_id=$cat_sub", $link1);
			if($ro = @mysql_fetch_array($re))
			{
				$query="Select cat_id from inl_cats where cat_name='".$ro["name"]."' and cat_desc='".$ro["description"]."'";
				$rs = &$conn->Execute($query);
				if ($rs && !$rs->EOF)
					$cat_sub=$rs->fields[0];
				else
				{
					$cat_sub=-1;
					echo $row["sub"]." failed to find parent<br>";
				}
			}
			else
			{
				$cat_sub=-1;
				echo $row["sub"]." failed to find parent<br>";
			}
		}
		if($cat_sub!=-1)
		{
			$query="insert into inl_cats (cat_name, cat_desc, cat_sub, cat_links, cat_user, cat_perm, cat_vis, cat_date, cat_pick,  cat_cust) values ('$cat_name', '$cat_desc', '$cat_sub' , 0, 1, 4, 1, '$cat_date', 0, 0)";
			$conn->Execute($query);
			$cat_id = $conn->Insert_ID("inl_cats","cat_id");
			$resi = @mysql_db_query($db1,"Select * from link where category_id=$old_id", $link1);
			if($ri = @mysql_fetch_array($resi))
			{
				do{
					$ri["date"]=strtotime($ri["date"]);
					addlink($ri["title"], $ri["description"], $ri["date"], $ri["num_votes"], $ri["num_hits"], $ri["rating"], $ri["url"],$ri["f_pick"], $cat_id, $ri["link_id"], $db1, $link1);
				}while($ri = @mysql_fetch_array($resi));
			}
			
			$count++;
		}
	}while($row = @mysql_fetch_array($result));
	echo " $count Categories transfered successfully. Please wait...<br>";
}else{
	$conn->Execute("Delete from inl_config where name='indexu_user'");
	$conn->Execute("Delete from inl_config where name='indexu_pass'");
	$conn->Execute("Delete from inl_config where name='indexu_db'");
	fix_cats(0);
	echo "Import Finished Successfully - $count Categories Imported";
	$stop=1;
}

$redirect="<script language=\"javascript\">
<!-- 

	location.href=\"import.php?start=$start&count=$count\"  

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