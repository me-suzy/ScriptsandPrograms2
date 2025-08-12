<?php 
	include_once("$DOCUMENT_ROOT/library/db.php");
	
	//connect to database
	$connect=mysql_connect($host_default, $login_default, $pw_default);
	$select_db=mysql_select_db($db_default);
	
	$totalProducts=0;
	
	//is this a category request, or a search request?
	if ($_GET['thisIsASearch']=="truep"){
	//this is a search requestlets pull the products out o the database based on the category which was sent via the flash cart.
		
		$query="SELECT *
				FROM cartitems
				WHERE (name LIKE '%".$_GET['searchTerm']."%'
				OR description LIKE '%".$_GET['searchTerm']."%')
				AND status='1'
				ORDER BY name
				";
	}else{
		$query="SELECT cartitems.*
				FROM cartitems, cartitems_to_cartcategories
				WHERE cartitems.id=cartitems_to_cartcategories.cartItemsID
				AND cartitems_to_cartcategories.cartCategoriesID='".$_GET['catID']."'
				AND cartitems.status='1'
				ORDER BY cartitems.name
				";
	}
	$result=mysql_query($query);
	echo "startLoadProducts=true";
	
	//flash does not want any holes in the numbers, yet holes exist.  We need to de-hole the values prior to sending to flash.
	//by holes I mean skipped rows.
	$i=1;
	while($row=mysql_fetch_array($result)){
		//we need to know if the image for this item exists.  if yes, we need to tell flash so that it knows what to do.
				
		echo "&price".$i."=".$row['price']."&productIDactual".$i."=".$row['id']."&itemName".$i."=".$row['name']."&descriptionMain".$i."=".$row['description'];
	$i++;
	
	}
	
	echo "&totalProducts=".($i-1)."&stopProductLoad=stop";
?>