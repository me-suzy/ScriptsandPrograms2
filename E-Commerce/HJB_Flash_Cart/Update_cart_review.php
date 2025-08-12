<?php
/*
	  i=0;
  while (i<=(_root.totalCartItems-1)){
		_root["itemExportItem"+i]=cartContents[i]['Item'];
		_root["itemExportQTY"+i]=cartContents[i]['QTY'];
		_root["itemExportPrice"+i]=cartContents[i]['Price'];
		_root["itemExportpriceNum"+i]=cartContents[i]['priceNum'];
		
  i++;
  }
  loadVariablesNum("Update_cart_session.php", 0, "POST");
  */

if ($_SESSION['totalCartItems']>0){
	  echo "&totalCartItems=".$_SESSION['totalCartItems']."&itemsImported=true";
	  $i=0;
	  while ($i<= ($_SESSION['totalCartItems']-1)){
			
			echo "&importedCartItem".$i."=".$_SESSION['exportedCart'][$i]['Item']."&importedCartQTY".$i."=".$_SESSION['exportedCart'][$i]['QTY']."&importedCartPrice".$i."=".$_SESSION['exportedCart'][$i]['Price']."&importedCartpriceNum".$i."=".$_SESSION['exportedCart'][$i]['priceNum'];
	
	  $i++;
	  }
}
?>