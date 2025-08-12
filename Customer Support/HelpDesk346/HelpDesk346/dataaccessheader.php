<img src="<?php echo isset($ppath) ? $ppath : ''; ?>images/main-banner-help-desk.jpg" alt="Help Desk Software, Customer Support First" width="547" height="198" border="0" usemap="#Map2"> 
<map name="Map2">
   		<area shape="rect" coords="2,145,66,197" href="<?php echo isset($ppath) ? $ppath : ''; ?>reportproblem.php">
        <area shape="rect" coords="78,149,159,194" href="<?php echo isset($ppath) ? $ppath : ''; ?>DataAccess.php">
        <area shape="rect" coords="169,146,275,198" href="<?php echo isset($ppath) ? $ppath : ''; ?>DataAccess.php?filter=user">
        <area shape="rect" coords="291,149,367,186" href="<?php echo isset($ppath) ? $ppath : ''; ?>ocm-first.php">
        <area shape="rect" coords="377,148,444,196" href="<?php echo isset($ppath) ? $ppath : ''; ?>kb/">
        <area shape="rect" coords="452,147,545,193" href="<?php echo isset($ppath) ? $ppath : ''; ?>DataAccess.php">
</map>
<?php
	$buffer = '<a href="DataAccess.php?filter=active">Show Active Helpdesk Calls</a>';
	if (isset($_GET['filter']) && $_GET['filter'] == 'active')
		$buffer = "<b>$buffer</b>";
	echo $buffer;
?>