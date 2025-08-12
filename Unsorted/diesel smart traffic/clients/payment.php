<?
	
if($action != "submission" || $mode == "")
header("Location: index.php?src=payment");

	require "../conf/sys.conf";
	require "../lib/mysql.lib";
	require "../lib/group.lib";

	$db = c();

	if(e($m1=q("select id, login, email from members where id='$auth'"))){
	 	echo "You must be a member to use this.";
		exit;
	}

	$mem=f($m1);

	$modes = q("select * from payment_modes where id='$mode'");

	if(e($modes)){
	 	echo "Mode is not selected.";
		exit;
	}

	$pmode = f($modes);
	
	global $HTTP_SERVER_VARS;

	$msg="Member $mem[login] paid $ $pmode[amount]. <br><br> Package : $pmode[idesc] <br><br>  $HTTP_SERVER_VARS[REMOTE_ADDR]";

	q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '$pmode[id]', 'Payment : $mem[login] [$mem[email]], $ $pmode[amount] ($pmode[credits] credits)', '$msg', 'payment', '$mem[id]', '$pmode[credits]', '1','".strtotime(date("d M Y H:i:s"))."')");

	if (!strstr($pmode[pay_link],"http")) header("Location: $pmode[pay_link]?mode=$mode&pay=$pmode[amount]");
				else header("Location: $pmode[pay_link]");
?>