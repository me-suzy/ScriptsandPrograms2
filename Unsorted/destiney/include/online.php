<?php

$users = "";
$num_logged_users = 0;
$gu_sql = "select data from $tb_sessions";
$gu_query = mysql_query($gu_sql) or die(mysql_error());
$total_user_sessions = mysql_num_rows($gu_query);
if($total_user_sessions){
	$username_array = array();
	$userid_array = array();
	while($gu_array = mysql_fetch_array($gu_query)){
		if(strlen(trim($gu_array["data"]))){
			$strings = split(";", $gu_array["data"]);
			for($x = 0; $x < sizeof($strings); $x++){
				if(strlen($strings[$x])){
					if(eregi("^username", $strings[$x])){
						$parts = split(":", $strings[$x]);
						$username_array[] = eregi_replace("\"", "", $parts[2]);
					}
				}
			}
		}
	}
	if(sizeof($username_array)){
		$un = array_unique($username_array);
		sort($un);
		reset($un);
		$num_logged_users = (int) sizeof($un);

$users = <<<EOF
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
	<td width="1%" class="regular">&nbsp;</td>
	<td width="99%" class="smallregular">
EOF;

		for($z = 0; $z < sizeof($un); $z++){
		if(strlen($un[$z])){
			$check_sql = "
				select
					id,
					image_status
				from
					$tb_users
				where
					username = '$un[$z]'
			";
			$check_query = mysql_query($check_sql) or die(mysql_error());
			$check_array = mysql_fetch_array($check_query);
			if($check_array["image_status"] == "approved"){

$users .= <<<EOF
<a href="$base_url/?i=$check_array[id]" class="small">$un[$z]</a>
EOF;

				} else {
					$users .= $un[$z];
				}
				if($z < sizeof($un)-1){
					$users .= ", ";
				}
			}
		}
		$users .= "</td></tr></table>";
	}
}

$num_anon_users = $total_user_sessions - $num_logged_users;

$title = "Visitors Online";

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular">&nbsp;Anonymous:</td>
	<td align="right" class="regular">$num_anon_users&nbsp;</td>
</tr>
<tr>
	<td class="regular">&nbsp;Logged In:</td>
	<td align="right" class="regular">$num_logged_users&nbsp;</td>
</tr>
<tr>
	<td class="smallregular" colspan="2"><br>$users</td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

?>