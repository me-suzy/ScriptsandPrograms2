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
<B>Buy credits using StormPay</B><BR>
<p>
<br>Just pay $<?php echo $pmode[amount];?> to Smart Traffic through <A href=http://www.stormpay.com/?118724>StormPay.com</A> to receive <?php echo $pmode[credits];?> credits :
<br>

<form method="post" action="https://www.stormpay.com/stormpay/handle_gen.php" target="_blank"> 
<input  type=hidden  name=generic  value=1>
<input  type=hidden  name=amount  value=<?php echo $pmode[amount];?>>
<input type=hidden name=product_name value=Smart Traffic Banner Impressions> 
<input type=hidden name=payee_email value="drares@ms.fx.ro">
<input type="hidden" name="flag" value=3>
<input type="hidden" name="return_URL" value="<?php echo $ROOT_HOST;?>clients/egsuccess.php">
<input type="hidden" name="cancel_URL" value="<?php echo $ROOT_HOST;?>clients/egfailed.php">
<input type="submit" name="PAYMENT_METHOD" value="pay via stormpay">
</form>



</blockquote></blockquote>
<?php include "../tpl/clients_bottom.ihtml"; ?>