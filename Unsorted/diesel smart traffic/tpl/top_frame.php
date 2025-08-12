<?php
	require "../conf/sys.conf";
	require "../lib/mysql.lib";
	require "../lib/ban.lib";
	require "../lib/bann.lib";

//security random position
srand ((double) microtime() * 1000000);
$pos=rand(0,4);

if (!$ipsfseconds) $ipsfseconds=10;
?>

<html>
<head>
<title>Top Frame</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../style.css" type="text/css">

<script src="bannerad3.js" type="text/javascript"></script>

</head>
<body LEFTMARGIN="0" MARGINWIDTH="0" TOPMARGIN="0" MARGINHEIGHT="0" bgcolor="#EEEEEE" text="#333333">

<TABLE width=100% cellpadding=5 cellspacing=0 border=0>

<tr><td align=center>

<font size=2>
<B>
<?php if ($pos<2) echo "<A href='../start.php?topframe=1&uid=$uid' target=_top>NEXT SITE</A> |"; ?>
<A href='<?php echo $url ?>' target=_top>CLOSE FRAME</A> |
<?php if ($pos==2) echo "<A target=_top href='../start.php?uid=$uid&topframe=1'>SURF MORE</A> |"; ?>
<A href="javascript:window.external.AddFavorite('<?php echo $ROOT_HOST."framer.php?topframe=1&uid=".$uid."&url=".$url; ?>','<?php echo $url ?>');">BOOKMARK</A>
<?php if ($pos>2) echo " | <A href='../start.php?uid=$uid&topframe=1' target=_top>NEXT</A>"; ?>
</B>
</font>
</td>
<td rowspan=2>

<script type="text/javascript">
// Banner Ad Rotater v3.02
// Author: Anarchos > anarchos3@hotmail.com > http://anarchos.xs.mw/bannerad.phtml
// Courtesy of SimplytheBest.net http://simplythebest.net/scripts/
<!--
myAd = new Banner( 5, 468, 60, "Visit our sponsor", 1, 1 );
myAd.Ad( "http://www.qksrv.net/image-1311856-7128689", "http://www.qksrv.net/click-1311856-7128689", "_blank", "eBay" );
myAd.Ad( "http://www.qksrv.net/image-1311856-9358462", "http://www.qksrv.net/click-1311856-9358462", "_blank", "Franklin Covey" );
myAd.Ad( "http://www.qksrv.net/image-1311856-5598148", "http://www.qksrv.net/click-1311856-5598148", "_blank", "TVLand" );
myAd.Ad( "http://www.qksrv.net/image-1311856-8064552", "http://www.qksrv.net/click-1311856-8064552", "_blank", "Half Price Book");
myAd.Ad( "http://www.qksrv.net/image-1311856-9873367", "http://www.qksrv.net/click-1311856-9873367", "_blank", "One Minute Millionaire");
myAd.Ad( "http://www.qksrv.net/image-1311856-8205248", "http://www.qksrv.net/click-1311856-8205248", "_blank", "Rooms To Go");
myAd.Ad( "http://www.qksrv.net/image-1311856-10283680", "http://www.qksrv.net/click-1311856-10283680", "_blank", "Fitness Heaven");
myAd.Ad( "http://www.qksrv.net/image-1311856-8810436", "http://www.qksrv.net/click-1311856-8810436", "_blank", "Free Condoms");
myAd.Ad( "http://www.qksrv.net/image-1311856-1155850", "http://www.qksrv.net/click-1311856-1155850", "_blank", "Total Gym");
myAd.Ad( "http://www.qksrv.net/image-1311856-347454", "http://www.qksrv.net/click-1311856-347454", "_blank", "Bath & Body");
myAd.Ad( "http://www.qksrv.net/image-1311856-544350", "http://www.qksrv.net/click-1311856-544350", "_blank", "Refund Sweeper");
myAd.Ad( "http://www.qksrv.net/image-1311856-4083190", "http://www.qksrv.net/click-1311856-4083190", "_blank", "Super Your Woman");
myAd.Ad( "http://www.qksrv.net/image-1311856-194113", "http://www.qksrv.net/click-1311856-194113", "_blank", "AAA Fruit Baskets");
myAd.Ad( "http://www.qksrv.net/image-1311856-9706461", "http://www.qksrv.net/click-1311856-9706461", "_blank", "Hobbytron");
myAd.Ad( "http://www.qksrv.net/image-1311856-7372563", "http://www.qksrv.net/click-1311856-7372563", "_blank", "Prize Games");
myAd.Ad( "http://www.qksrv.net/image-1311856-7064384", "http://www.qksrv.net/click-1311856-7064384", "_blank", "Creative Labs");
myAd.Ad( "http://www.qksrv.net/image-1311856-1555847", "http://www.qksrv.net/click-1311856-1555847", "_blank", "Free Cartridges");
myAd.Ad( "http://www.qksrv.net/image-1311856-7279233", "http://www.qksrv.net/click-1311856-7279233", "_blank", "Dish Network");
myAd.Ad( "http://www.qksrv.net/image-1311856-10281961", "http://www.qksrv.net/click-1311856-10281961", "_blank", "GEAR Software");
myAd.Ad( "http://www.qksrv.net/image-1311856-10281025", "http://www.qksrv.net/click-1311856-10281025", "_blank", "National Debt Consolidators");
myAd.Ad( "http://www.qksrv.net/image-1311856-51984", "http://www.qksrv.net/click-1311856-51984", "_blank", "Guaranteed Loan");
myAd.Ad( "http://www.qksrv.net/image-1311856-10278752", "http://www.qksrv.net/click-1311856-10278752", "_blank", "Free DVD");
myAd.Ad( "http://www.qksrv.net/image-1311856-3881536", "http://www.qksrv.net/click-1311856-3881536", "_blank", "GOTO MY PC");
myAd.Ad( "http://www.qksrv.net/image-1311856-597746", "http://www.qksrv.net/click-1311856-597746", "_blank", "00inkjet");
myAd.Ad( "http://www.qksrv.net/image-1311856-6947562", "http://www.qksrv.net/click-1311856-6947562", "_blank", "Logo Works");
myAd.Ad( "http://www.qksrv.net/image-1311856-9928711", "http://www.qksrv.net/click-1311856-9928711", "_blank", "Free Gift Central");
myAd.Ad( "http://www.qksrv.net/image-1311856-14617", "http://www.qksrv.net/click-1311856-14617", "_blank", "Animation Factory");
myAd.Ad( "http://www.qksrv.net/image-1311856-5789013", "http://www.qksrv.net/click-1311856-5789013", "_blank", "Index Tools");
myAd.Ad( "http://www.qksrv.net/image-1311856-8829407", "http://www.qksrv.net/click-1311856-8829407", "_blank", "123 Turn Key");
myAd.Ad( "http://www.qksrv.net/image-1311856-5592559", "http://www.qksrv.net/click-1311856-5592559", "_blank", "free Hosting Web");
myAd.Ad( "http://www.qksrv.net/image-1311856-8829035", "http://www.qksrv.net/click-1311856-8829035", "_blank", "Search Engine Watch");
myAd.Ad( "http://www.qksrv.net/image-1311856-6672935", "http://www.qksrv.net/click-1311856-6672935", "_blank", "tech 24");
myAd.Ad( "http://www.qksrv.net/image-1311856-10029187", "http://www.qksrv.net/click-1311856-10029187", "_blank", "Batter Cable");
myAd.Ad( "http://www.qksrv.net/image-1311856-9111108", "http://www.qksrv.net/click-1311856-9111108", "_blank", "Digital Etc");
myAd.Ad( "http://www.qksrv.net/image-1311856-4003024", "http://www.qksrv.net/click-1311856-4003024", "_blank", "Lik Sang");
myAd.Ad( "http://www.qksrv.net/image-1311856-10025570", "http://www.qksrv.net/click-1311856-10025570", "_blank", "1-800-FLORALS");
myAd.Ad( "http://www.qksrv.net/image-1311856-7089891", "http://www.qksrv.net/click-1311856-7089891", "_blank", "The Space Store");
myAd.Ad( "http://www.qksrv.net/image-1311856-1031905", "http://www.qksrv.net/click-1311856-1031905", "_blank", "Sea Eagle Inflatable Boats");
myAd.Ad( "http://www.qksrv.net/image-1311856-1481489", "http://www.qksrv.net/click-1311856-1481489", "_blank", "Dog's Health");
myAd.Ad( "http://www.qksrv.net/image-1311856-3483738", "http://www.qksrv.net/click-1311856-3483738", "_blank", "Personalized Golf Gifts");
myAd.Ad( "http://www.qksrv.net/image-1311856-9787231", "http://www.qksrv.net/click-1311856-9787231", "_blank", "New Line Cinema Studio Store");
myAd.Ad( "http://www.qksrv.net/image-1311856-543670", "http://www.qksrv.net/click-1311856-543670", "_blank", "Win Free Gas for Life");
myAd.Ad( "http://www.qksrv.net/image-1311856-8123221", "http://www.qksrv.net/click-1311856-8123221", "_blank", "WBShop");
myAd.output();
// -->
</script>


</td> 
</tr>

<tr>
<td align=center>
<font color='#333333'>Minimum wait time to get credits : </font><input size='2' type='text' name='tmr' id='tmr' value='<?php echo $ipsfseconds; ?>'>
<script language='JavaScript'>
setInterval("if (tmr.value>0) tmr.value--;", 1000); 
</script>
</td>
</tr>


</table>
</body>
</html>
