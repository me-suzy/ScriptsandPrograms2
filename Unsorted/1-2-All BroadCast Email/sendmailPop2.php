<?PHP
	$backendfind = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 
						 limit 1
                       ");
	$backend = mysql_fetch_array($backendfind);
	$pop_op = $backend["pop_op"];
	$b_max = $backend["pop_nu"];
	$count = 0;
	$count_f = 0;
	$count_d = 0;
	
	if ($addy != ""){
	foreach ($addy as $something) 
	{
	$b_find = mysql_query ("SELECT * FROM ListMembers
							WHERE email LIKE '$something'
							limit 1
							");
							$b_found = mysql_fetch_array($b_find);
	$nb = $b_found["bounced"];
	$nb_d = $b_found["bounced_d"];
	$ex_nl = $b_found["nl"];
	$nb = $nb + 1;
	$t_date = date("Y-m-d");
	$t_time = date("H:i:s");
	mysql_query ("INSERT INTO 12all_Bounce (email, mid, tdate, ttime, nl) VALUES ('$ex_em' ,'$ex_mi' ,'$t_date' ,'$t_time' ,'$ex_nl')");  
	if ($nb < $b_max AND $t_date != $nb_d){
	mysql_query("UPDATE ListMembers SET bounced='$nb', bounced_d='$t_date' WHERE (email='$something')");
	$count_f = $count_f + 1;
	}
	else {
	mysql_query ("DELETE FROM ListMembers
                                WHERE email LIKE '$something'
								");
	$count_d = $count_d + 1;
	}
	$count = $count + 1;
	}
	print "$count $lang_328";
	if ($count != 0){
	print "<p>$count_d $lang_329 $b_max $lang_330.";
	print "<br>$count_f e-mail $lang_331 $b_max $lang_332.";
	}
	
	}

		if ($addresses == ""){
		print "0 $lang_328";
	}
?>