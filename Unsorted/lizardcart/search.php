
<? 

include ("config.inc.php");
include ("header.php");
?>

	<table border="3" cellspacing="3" cellpadding="3" align="center">
	<tr><td colspan="2" align="center">Search Results:</td></tr>
    <?
	
    //error message (not found message) 
    $XX = "<center>No Record Found</center>";
    $query = mysql_query("SELECT * FROM products WHERE $metode LIKE '%$search%' LIMIT 0, 30 ");
    while ($row = mysql_fetch_array($query))
    {
	$variable1=$row["id"];
    $variable2=$row["item_name"];
    $variable3=$row["item_desc"];
	$variable4=$row["item_descde"];
    $variable5=$row["item_category"];
    print ("<tr><td>ID:</td><td>$variable1</td></tr>
    <tr><td>Name:</td><td>$variable2</td></tr>
	<tr><td>Decription:</td><td>$variable3</td></tr>
    <tr><td>Detail Decription:</td><td>$variable4</td></tr>
    <tr><td>Category:</td><td>$variable5</td></tr>
	<tr><td></td><td><a href=detail.php?id=$variable1>View</a></td></tr>"); 
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
