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
<B>Buy credits using EGOLD</B><BR>
<p>
<br>Just pay $<?php echo $pmode[amount];?> to <?php echo $ADMIN_EGOLDN." ($ADMIN_EGOLD)";?> through <A href=http://www.e-gold.com>EGOLD</A> to receive <?php echo $pmode[credits];?> credits :
<br>

<form action="https://www.e-gold.com/sci_asp/payments.asp" method="POST">
<p>
    <input type="hidden" name="PAYEE_ACCOUNT" value="<?php echo $ADMIN_EGOLD;?>">
    <input type="hidden" name="PAYEE_NAME" value="<?php echo $ADMIN_EGOLDN;?>">
    <input type="hidden" name="PAYMENT_AMOUNT" value="<?php echo $pmode[amount];?>">
    <input type="hidden" name="PAYMENT_UNITS" value="1">
    <input type="hidden" name="PAYMENT_METAL_ID" value="1">
    <input type="hidden" name="STATUS_URL" 
        value="<?php echo $ROOT_HOST;?>clients/egpayprocess.php">
    <input type="hidden" name="PAYMENT_URL" 
        value="<?php echo $ROOT_HOST;?>clients/egsuccess.php">
    <input type="hidden" name="BAGGAGE_FIELDS" VALUE="custom">
    <input type="hidden" name="custom" VALUE="<?php echo $auth;?>">
    <input type="hidden" name="NOPAYMENT_URL" 
        value="<?php echo $ROOT_HOST;?>clients/egfailed.php">
    <input type="submit" name="PAYMENT_METHOD" value="pay via e-gold">
</p>
</form>

</blockquote></blockquote>
<?php include "../tpl/clients_bottom.ihtml"; ?>