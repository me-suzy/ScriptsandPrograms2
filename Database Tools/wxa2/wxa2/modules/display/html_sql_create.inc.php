<div id=sql>
drop table if exists <?=t_name($g_t)?> ;<br>

create table <?=t_name($g_t)?> (
<br>id int(11) not null  AUTO_INCREMENT PRIMARY KEY, <br><?=join(",<br>", $arr_sql_create)?>);</div>

<?reset($arr_sql_create_ref);
while (list($k,$v)=each($arr_sql_create_ref))	
	{
	$table_ref=t_name("ref" . "_" . $v[0] ."_" .  $v[1]);
	?>
	<div id=sql>
drop table if exists <?=$table_ref?> ;<br>

create table <?=$table_ref?> (
<br><?=$v[0]?>_id int(11) not null,
<br><?=$v[1]?>_id  int(11) not null,
<br>PRIMARY KEY  (<?=$v[0]?>_id,<?=$v[1]?>_id )
);</div>
	<?	}?>
