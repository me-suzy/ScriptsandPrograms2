<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------
	
	session_start();
	
	include ("config.php");
	include ("settings.inc.php");
	include_once ("header.inc.php");

?>

<script language="JavaScript" type="text/JavaScript">
	function decision(message, url){
		if(confirm(message)) location.href = url;
	}
</script>


<?php
		
	//
	// if session is not registered go to login page
	
	if( !isset( $_SESSION["admin"] ) && !isset( $_SESSION["demo"] ) )
		abcPageExit("<Script language=\"javascript\">window.location=\"login.php\"</script>");
	
	echo "<h2>".$lng[823]."</h2>";
	
	$item=$_REQUEST['item'];
	$action=$_REQUEST['action'];
	
	if($_REQUEST['action']=='search')
		$_SESSION['options']=$options=$_REQUEST['options'];
	else
		$options=$_SESSION['options'];

	$_order='n asc';
	$per_page=50;
	$page=$_REQUEST['page']!=''?$_REQUEST['page']:1;
	
	$conditions='1';
	if(is_array($options['categories'])&&count($options['categories'])>0)
	{
			$conditions.=' and (0';
			foreach($options['categories'] as $value)
				$conditions.=" or category='".mysql_escape_string($value)."'";
			$conditions.=')';
	}
	if($options['search_str']!='')
		$conditions.=" and (EN like '%".mysql_escape_string($options['search_str'])."%' or description like '%".mysql_escape_string($options['search_str'])."%')";

	if($action=='add_language'&&!$_SESSION['demo'])
	{
		mysql_query("alter table ".$prefix."store_lang add column `".$item['new_language']."` text NOT NULL");
		mysql_query("insert into ".$prefix."store_language_charsets set language='".$item['new_language']."', charset='".$item['charset']."'") or die(mysql_error());
		if($item['copy_from']!='')
			mysql_query("update ".$prefix."store_lang set `".$item['new_language']."`=`".$item['copy_from']."`");
	}
	
	// Delete language
	
	if(isset($_GET['delete'])&&!$_SESSION['demo']){
		
		mysql_query("alter table ".$prefix."store_lang drop column `".$_GET['delete']."`");
		mysql_query("delete from ".$prefix."store_language_charsets where language='".$_GET['delete']."'") or die(mysql_error());
	}
	
	// Edit
	
	if($action=='enable_disable'&&!$_SESSION['demo'])
	{
		
		// Enable/disable
		
		mysql_query("update ".$prefix."store_language_charsets set `enabled`= '0'") or die(mysql_error());
		
		if ( is_array ( $_REQUEST['to_delete'] ) && !empty ( $_REQUEST['to_delete'] ) ) {
			
			foreach($_REQUEST['to_delete'] as $key=>$value)
				$_REQUEST['to_delete'][$key]="'".$value."'";
			
			mysql_query("update ".$prefix."store_language_charsets set `enabled`= '1' where language in(".join(',', $_REQUEST['to_delete']).")") or die(mysql_error());
		}
		
		// Charset
		
		if ( is_array ( $_REQUEST['charset'] ) && !empty ( $_REQUEST['charset'] ) ) {
			
			foreach($_REQUEST['charset'] as $key=>$value)
				mysql_query("update ".$prefix."store_language_charsets set `charset`='$value' where language='$key'") or die(mysql_error());
		}
		
	}
	
//get listing of fields in languages table
	$columns_r=mysql_query("SHOW COLUMNS FROM ".$prefix."store_lang") or die(mysql_error());
	$columns=array();
	while($tmp=mysql_fetch_assoc($columns_r))
		if(!in_array($tmp['Field'], array('category', 'description', 'n')))
			$columns[$tmp['Field']]=$tmp['Field'];
	
	
	
	
//update an editable item	
	if($action=='update'&&!$_SESSION['demo'])
	{
		$lng_changes='';
		if($item['new_category']!='')
			$item['category']=$item['new_category'];
		foreach($columns as $key=>$value)
		{
			if(get_magic_quotes_gpc()==1)
				$item[$key]=stripslashes($item[$key]);
			$lng_changes.="`".$key."`"."='".mysql_escape_string($item[$key])."', ";
		}
		mysql_query("update ".$prefix."store_lang set ".$lng_changes." description='".$item['description']."', category='".$item['category']."' where n='".$item['n']."'") or die(mysql_error());
	}

//get info on item being edited
	if($action=='edit'||$action=='update')
	{
		$item_r=mysql_query("select * from ".$prefix."store_lang where n='".$_REQUEST['id']."'") or die(mysql_error());
		$item=mysql_fetch_assoc($item_r);
	}
	

//get listing of categories	
	$categorized_r=mysql_query("select * from ".$prefix."store_lang group by category");
	$categories=array();
	while($category=mysql_fetch_assoc($categorized_r))
		$categories[]=$category['category'];


	if($action=='search'||$action=='search_res')
	{
	//get total number of messages
		$total_r=mysql_query("select count(*) as total from ".$prefix."store_lang where ".$conditions)or die(mysql_error());
		$total=mysql_fetch_assoc($total_r);
		$total=$total['total'];
	
	//get messages for current page
		$_msgs_res=mysql_query("select * from ".$prefix."store_lang where ".$conditions." order by ".$_order." limit ".($page-1)*$per_page.', '.$per_page) or die(mysql_error());
	
		$msgs=array();
		$count=0;
		while(($_msg=mysql_fetch_assoc($_msgs_res))&&$count<$per_page)
		{
			$msgs[]=$_msg;
			$count++;
		}
		
		$pages=range(1, ceil($total/$per_page));
	}

// Check if enabled
	
$sql_lng = "select `language`,`enabled`,`charset` from `".$prefix."store_language_charsets`";
if ( $tbl_lng = mysql_query ($sql_lng) ) {
	while ( $res_lng = mysql_fetch_assoc ($tbl_lng) ) {
		
		$enabled_array[ $res_lng['language'] ] = $res_lng['enabled'];
		$charsets_array[ $res_lng['language'] ] = $res_lng['charset'];
	}
	
}
	//

?>
	<b>Manage languages</b>
	<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
<?
if($action=='update')
{?><script language="JavaScript">opener.history.go(0);window.close();</script><?}
if(($action=='drop_language'||$action=='enable_disable'||$action=='add_language'||$action=='update')&&$_SESSION['demo'])
	include('_guest_access.php');
if($action==''||$action=='drop_language'||$action=='enable_disable'||$action=='add_language')
{
?>
	<tr>
	<td bgcolor="#e6e6e6" align="center"><b>Language</b></td>
	<td bgcolor="#e6e6e6" align="center"><b>Charset</b></td>
	<td bgcolor="#e6e6e6" align="center"><b>Enabled</b></td>
	<td bgcolor="#e6e6e6" align="center"><b>Actions</b></td>
	</tr>
	<form action="?" method="post" name="frm1">
	<input type="Hidden" name="action" value="enable_disable">
		
	<?if(is_array($columns))
		foreach($columns as $key=>$value){?>
		<tr>
		<td width="80%"><?=$key;?></td>
		<td>
		<select name="charset[<?=$key;?>]">
                <?foreach( $charsets_unique as $value ) {
                	
                	if ( $charsets_array[$key] == $value )
                		echo "<option value='$value' selected >$value</option>";
                	else	echo "<option value='$value'>$value</option>";
                	
                }
                ?>
		</select>
		</td>
		<td width="1%" align="center"><input type="Checkbox" name="to_delete[]" value="<?=$key;?>" 
		<?if( $enabled_array[$key] == 1 ){?>checked<?}?> ></td>
		<td width="5%" align="center"><?if($key!='EN'){?><a href="javascript:decision('Are you really want to delete selected language?','?delete=<?=$key?>')">Delete</a><?}?><?if($key=='EN'){?>&nbsp;<?}?></td>
		
		</tr>
	<?}?>
	<tr>
		<td colspan="4" align="center">
		<input type="Button" value=" Edit " onclick="if(confirm('Are you really want to edit selected language packs?')) document.frm1.submit();">
	</tr>
	</table>
	</form>

	
	
	<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
	<tr><td colspan="2" bgcolor="#e6e6e6"><b>Add new language</b></td></tr>
	<form action="?" method="post">
	<tr>
		<td width="50%">Name:</td>
		<td width="50%"><input type="Text" name="item[new_language]"></td>
	</tr>
	<tr>
		<td>Make a copy of existing language pack:</td>
		<td><select name="item[copy_from]"><option><?foreach($columns as $key=>$value){?><option><?=$key;?><?}?></select></td>
	</tr>
	<tr>
		<td>Character set for this language:</td>
		<td>
			<select name="item[charset]">
                        <?foreach($charsets as $value)
                        	echo "<option value='$value[value]'>$value[name]</option>";
                        ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="Submit" value="Add language"></td>
	</tr>
	<input type="Hidden" name="action" value="add_language">
	</table>
	</form>
	
	
	<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
	<tr><td bgcolor="#e6e6e6" colspan="2"><b>Search messages</b></td></tr>
<form action="?" method="get">
	<tr>
		<td width="50%">Show messages from categories</td>
		<td width="50%"><select name="options[categories][]" multiple size="10"><?foreach($categories as $value){?><option  value="<?=addslashes($value);?>"<?=in_array($value, $options['categories'])?' selected':'';?>><?=$value;?><br><?}?></select></td>
	</tr>
	<tr>
		<td>Strings whose description or english translation contains following text</td>
		<td><input type="Text" name="options[search_str]" value="<?=$options['search_str'];?>"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="Submit" value="Show"></td>
	</tr>
<input type="Hidden" name="action" value="search">
</form>
</table>
<?
}

if($action=='search'||$action=='search_res')
{?>
	<table border="1" bordercolor="#e6e6e6" cellpadding="4" cellspacing="0" width="95%">
	<tr>
		<td colspan="5"><a href="?">Back</a></td>
	</tr>
	<?if(count($msgs)>0){?>
	<tr>
		<td colspan="5"><?foreach($pages as $value){if($value!=$page){?><a href="?action=search_res&page=<?=$value;?>"><?=$value;?></a><?}else{?><?=$value;?><?}?>&nbsp;<?}?></td>
	</tr>
	<tr>
		<td bgcolor="#e6e6e6"><b>N</b></td>
		<td bgcolor="#e6e6e6"><b><?=$sys_lng?></b></td>
		<?if($sys_lng!='EN'){?><td bgcolor="#e6e6e6"><b>EN</b></td><?}?>
		<td bgcolor="#e6e6e6"><b>Description</b></td>
		<td bgcolor="#e6e6e6"><b>Category</b></td>
	</tr>
	<?foreach($msgs as $value){?>
	<tr>
		<td><a href="#" onclick="window.open('?action=edit&id=<?=$value['n'];?>');"><?=$value['n'];?></a></td>
		<td><?=htmlspecialchars($lng[$value['n']]);?></td>
		<?if($sys_lng!='EN'){?><td><?=htmlspecialchars($value['EN']);?></td><?}?>
		<td><?=htmlspecialchars($value['description']);?></td>
		<td><?=htmlspecialchars($value['category']);?></td>
	</tr>
	<?}
		}else{?>
		<tr>
			<td><i>No messages found</i></td>
		</tr>
		<?}?>
<?	}
	if($action=='edit'||$action=='update'){?>
	<tr>
		<td colspan="4"><a href="?">Back</a></td>
	</tr>
	<form action="?" method="post">
		<?foreach($columns as $key=>$value){?>
		<tr>
			<td><?=htmlspecialchars($key);?></td>
			<td><textarea name="item[<?=htmlspecialchars($key);?>]" rows="5" cols="55"><?=htmlspecialchars($item[$key]);?></textarea></td>
		</tr>
		<?}?>
		<tr>
			<td></td>
		</tr>
		<tr>
			<td>Category(select one from<br>list or type new name in box):</td>
			<td><select name="item[category]"><?if(is_array($categories))foreach($categories as $key=>$value){?><option<?=$item['category']==$value?' selected':'';?>><?=$value;?><?}?></select> New: <input type="Text" name="item[new_category]"></td>
		</tr>
		<tr>
			<td colspan="2">Description:<br><textarea name="item[description]" rows="4" cols="80"><?=htmlspecialchars($item['description']);?></textarea></td>
		</tr>
		<tr>
			<td colspan="2"><input type="Submit" value="Update"></td>
		</tr>
		<input type="Hidden" name="action" value="update">
		<input type="Hidden" name="item[n]" value="<?=$item['n'];?>">
		<input type="Hidden" name="id" value="<?=$item['n'];?>">
		</form>
	<?}?>
</table>
<br>
<br>
<?
	include ("footer.inc.php");
?>