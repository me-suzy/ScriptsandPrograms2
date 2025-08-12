<?php
if ($show_record_numbers_change_table !== 0) {
	// get the number of contacts in the database
	$sql = "SELECT COUNT(*) FROM ".$quote.$table_name.$quote;

	if ($enable_authentication === 1 && $enable_browse_authorization === 1) { // $ID_user_field_name = '$_SESSION['logged_user_infos_ar']['username_user']' where clause part in order to select only the records the current user owns
		$ID_user_field_name = get_ID_user_field_name($fields_labels_ar);

		if ($ID_user_field_name !== false) { // no ID_user fields available, don't use authorization
			$sql .= " WHERE ".$quote.$table_name.$quote.'.'.$quote.$ID_user_field_name.$quote." = '".add_slashes($_SESSION['logged_user_infos_ar']['username_user'])."'";
		} // end if

	} // end if

	// execute the select query
	$res_count = execute_db($sql, $conn);

	while ($count_row = fetch_row_db($res_count)){
		$records_number = $count_row[0];
	} // end while

	if ($enable_admin_authentication === 1 and $_SESSION['logged_user_infos_ar']['user_type_user'] === $users_table_user_type_administrator_value){$change_table_select = build_change_table_select($exclude_not_allowed = 1, $inlcude_users_table = 1);}
	else {$change_table_select = build_change_table_select();}
} // end if
if ($show_record_numbers_change_table !== 0) {
?>

<br /><br /><div style="background-color:#f0f0f0; padding:5px; align:left;">
<?php
if ($change_table_select != ""){
?>
<form style ="display:inline;" method="get" action="<?php echo $dadabik_main_file; ?>" name="change_table_form" id="change_table_form">This is for table -&gt; 
<?php 
echo $change_table_select;
if ( $autosumbit_change_table_control == 0) {echo ('<input type="submit" class="button_change_table" value="'. $submit_buttons_ar["change_table"].'" />');}
echo (' &lt;- you may choose another table</form>');
?>
<br />
<?php echo ''.$normal_messages_ar["total_records"].': '.$records_number; ?> || <a class="home" href="<?php echo $dadabik_main_file; ?>?table_name=<?php echo urlencode($table_name); ?>">For this table</a>:&nbsp;
<?php
if ($enable_insert == "1"){
?>
<a class="bottom_menu" href="<?php $dadabik_main_file; ?>?function=show_insert_form&amp;table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["insert_short"]; ?></a>
<?php
}
?>
 / <a class="bottom_menu" href="<?php $dadabik_main_file; ?>?function=show_search_form&amp;table_name=<?php echo urlencode($table_name); ?>"><?php echo $submit_buttons_ar["search_short"]; ?></a> / <a class="bottom_menu" href="<?php $dadabik_main_file; ?>?function=search&amp;where_clause=&amp;page=0&amp;table_name=<?php echo urlencode($table_name); ?>"><?php echo $normal_messages_ar["show_all"]; ?></a>
<?php
if ( $table_name === $users_table_name && ($function === 'edit' || $function === 'show_insert_form')){
?>
 || <a class="bottom_menu" href="javascript:void(generic_js_popup('pwd.php','',400,300))"><?php echo $login_messages_ar['pwd_gen_link']; ?></a>
 <?php
}
?>
 <a class="bottom_menu" href="#"><?php echo ' || '.$normal_messages_ar["top"]; ?></a>
</div>
<?php
}
}
?>
<p>
<?php
if($parentsite_name != '')
{echo ('<a href="'.$parentsite_url.'">'.$parentsite_name.'</a> | ');}

echo ('<a href="'.$dadabik_main_file.'">'.$site_name.' - data browser</a> | <a href="admin.php">'.$site_name.' - administration home page</a> | ');

if($mainsite_name != '')
{echo ('<a href="'.$mainsite_url.'">'.$mainsite_name.'</a>');}
?>
</p>
</body>
</html>