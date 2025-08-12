<div id=list>
<?
$str_list=array();
reset($arr_fields_lst);

while (list($lst_order, $arr_field) = each($arr_fields_lst))
	{
	while (list($field_name, $field_caption) = each($arr_field))
		{
		$str_list[]="<td class=list_header>";
		$newod="";
		if ($g_o==$field_name)
			{if ($g_od=="asc") $newod="&od=desc"; else $newod="&od=asc";}
	
			
		$str_list[]="<a href=javascript:goto_href('o=$field_name$newod')>";
		if ($g_o==$field_name)
			{
			$str_list[]="<img src=i/buttons/arrow_" . $g_od . ".gif width=16 height=16 border=0 align=absmiddle>&nbsp;";
			}
		$str_list[] = "<li class=list_header>$field_caption";
	
		$str_list[]="</li></a></td>";
		}
	}
	

?>
<table class=list>
<?
if ($g_i!="" && $arr_tables[$g_t]["parent_id_ref"]!="")
	{?>
	<tr><td class=list_header colspan=<?=count($str_list)?>>
	<?
	$arr_tmp=array();
	$arr_tmp["name"]=$g_t;
	reset($g_arr_fields_att);
	$arr_tmp["caption_field"]=key($g_arr_fields_att);
	$arr_tmp["parent_field"]=$arr_tables[$g_t]["parent_id_ref"];
	?>
	<?=html_full_path($arr_tmp,$g_i, "javascript:goto_href('a=frm&i=--id--')" ) ?></td></tr>
	<?}
?>
<tr><?=join("", $str_list)?></tr>
<?if ($row_list_count==0) {?>
<tr><td colspan=<?=count($str_list)?> class=list_cell_empty><?=msg("table_empty")?></td></tr><?}
else 	
	{
	while ($db_lst->next_record())
	{
	reset($str_list);
	?>
	<tr  
	onmouseover="h(this); showtip('id <?=$db_lst->f(t_name($g_t) . "_id")?>')" 
onmouseout="uh(this);hidetip()" ondblclick="goto_href('a=frm&i=<?=$db_lst->f(t_name($g_t) . "_id")?>')" onmousedown=line_switch(<?=$db_lst->f(t_name($g_t) . "_id")?>) id=tr<?=$db_lst->f(t_name($g_t) . "_id")?>>
<?reset($arr_fields_lst);
	while (list($lst_order, $arr_field) = each($arr_fields_lst))
		{
		
		while (list($field_name, $field_caption) = each($arr_field))
			{
			$arr_field_attr=$g_arr_fields_att[$field_name];
			?>
		
		<td class=list_cell>&nbsp;<?
if (count($arr_field_attr["item"])>0)
	echo $arr_field_attr["item"][$db_lst->f($arr_field_attr["query_field"])];
else
	echo $db_lst->f($arr_field_attr["query_field"])

?>&nbsp;</td>
		<?}
		}
	?></tr><?
	}
	}?>
	<tr><td colspan=<?=count($str_list)?> class=list_cell_empty></td></tr>
</table>
</div>