<?

	if($mode == "")
	header("Location: index.php?src=payment");

	require "../conf/sys.conf";
	require "../lib/mysql.lib";
	require "../lib/group.lib";

	include "../tpl/clients_top.ihtml";

	$db = c();

	if(e(q("select id from members where id='$auth'"))){
	 	echo "No such user.";
		exit;
	}

	$modes = q("select * from payment_modes where id='$mode'");

	if(e($modes)){
	 	echo "Mode is not selected.";
		exit;
	}

	$pmode = f($modes);
?>
<blockquote>
<blockquote>
<B>Buy credits using PAYPAL</B><BR>
<p>
<br>Just pay $<?php echo $pmode[amount];?> to '<?php echo $ADMIN_PAYPAL;?>' through <A href=http://www.paypal.com>Paypal</A> to receive <?php echo $pmode[credits];?> credits :
<br>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="<?php echo $ADMIN_PAYPAL;?>">
<input type="hidden" name="item_name" value="<?php echo $pmode[credits];?> Credits">
<input type="hidden" name="item_number" value="<?php echo $pmode[id];?>">
<input type="hidden" name="custom" value="<?php echo $auth;?>">
<input type="hidden" name="amount" value="<?php echo $pmode[amount];?>">
<input type="image" src="http://images.paypal.com/images/x-click-but01.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form>
</blockquote></blockquote>
<?php include "../tpl/clients_bottom.ihtml"; ?>