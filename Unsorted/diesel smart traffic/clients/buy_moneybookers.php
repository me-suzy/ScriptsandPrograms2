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
<B>Buy credits using MoneyBookers.com</B><BR>
<p>
<br>Just pay $<?php echo $pmode[amount];?> to Smart Traffic through <A href=https://www.moneybookers.com/app/?rid=112787>MoneyBookers.com</A> to receive <?php echo $pmode[credits];?> credits :
<br>

<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
<input type="hidden" name="pay_to_email" value="drares@ms.fx.ro">
<input type="hidden" name="status_url" value="<?php echo $ROOT_HOST;?>clients/egpayprocess.php"> 
<input type="hidden" name="language" value="EN">
<input type="hidden" name="amount" value="<?php echo $pmode[amount];?>">
<input type="hidden" name="currency" value="USD">
<input type="hidden" name="detail1_description" value="Smart Traffic Exposures">
<input type="hidden" name="detail1_text" value="Smart Traffic Exposures">
<input type="hidden" name="return_URL" value="<?php echo $ROOT_HOST;?>clients/egsuccess.php">
<input type="hidden" name="cancel_URL" value="<?php echo $ROOT_HOST;?>clients/egfailed.php">
<input type="submit" value="pay via moneybookers">
</form> 



</blockquote></blockquote>
<?php include "../tpl/clients_bottom.ihtml"; ?>