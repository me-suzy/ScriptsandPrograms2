<?
	if($ptx == "" || $ptm == "" || $ptd == ""){
	 	echo "H100: Unable to found transaction information.";
		exit;
	}

	$ptx = base64_decode($ptx);
	$ptm = base64_decode($ptm);
	$ptd = base64_decode($ptd);


	// if user could not pay during 2 hours .. canceling order.

	require "../conf/sys.conf";

	if(strtotime(date("d M Y H:i:s")) > ($ptx + 60*60*2)){
	 	echo "H101: You have old data of your payment. Please, do it again.\nOr contact our system administrator ($ADMIN_MAIL).";
		exit;
	}

	require "../lib/mysql.lib";
	require "../lib/group.lib";
	$db = c();

	if(e(q("select id from members where id='$ptm'"))){
	 	echo "H102: No such user.";
		exit;
	}

	$modes = q("select * from payment_modes where id='$ptd'");

	if(e($modes)){
	 	echo "H103: Mode is not selected.";
		exit;
	}

	$mode = f($modes);
	
	// changing user status. (switching to advertiser).

	q("update members_policy set free='0' where user_id='$ptm'");
	q("update members_policy set expirable='0' where user_id='$ptm'");

	// So adding credits


	if(e(q("select id from members_credits where user_id='$ptm'")))
		q("insert into members_credits values('0','$ptm','$mode[credits]')");
	else
		q("update members_credits set credits_num=credits_num+$mode[credits] where user_id='$ptm'");


	setcookie("ptm");
	setcookie("ptx");
	setcookie("ptd");

 
	include "../tpl/clients_top.ihtml";
	include "../tpl/success.ihtml";
	include "../tpl/clients_bottom.ihtml";

	d($db);
?>