
<? 
include ("atho.inc.php");
include ("header.php");?>

	<table width="100%" border="3" cellspacing="3" cellpadding="3">
	<tr><td colspan="2" align="center">Search Results:</td></tr>
    <?
	include ("config.inc.php");
    //error message (not found message) 
    $XX = "<center>No Record Found</center>";
    $query = mysql_query("SELECT * FROM var_notify WHERE $metode LIKE '%$search%' LIMIT 0, 30 ");
    while ($row = mysql_fetch_array($query))
    {
	$variable1=$row["std_ipn"];
    $variable2=$row["first_name"];
    $variable3=$row["last_name"];
	$variable4=$row["payment_date"];
    $variable5=$row["payer_email"];
    print ("<tr><td>ID:</td><td>$variable1</td></tr>
    <tr><td>First Name:</td><td>$variable2</td></tr>
	<tr><td>Last Name:</td><td>$variable3</td></tr>
    <tr><td>Payment Date:</td><td>$variable4</td></tr>
    <tr><td>Email Address:</td><td>$variable5</td></tr>
	<tr><td><a href=orderedit.php?action=0&std_ipn=$variable1>Edit</a>&nbsp;&nbsp;<a href=orderedit.php?action=2&std_ipn=$variable1>Delete</a></td><td><a href=orderlist.php?std_ipn=$variable1>View</a></td></tr>"); 
    }
	?>

    
	</table>
	<?
    //below this is the function for no reco
    //     rd!!
    if (!$variable1) 
    {
    print ("$XX");
    }
    //end
    ?>

<? include ("footer.php");?>
