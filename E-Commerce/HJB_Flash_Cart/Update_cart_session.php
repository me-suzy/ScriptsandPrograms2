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
  session_start();
  $_SESSION['totalCartItems']=$_POST['totalCartItems'];
  
  $i=0;
  while ($i<= ($_SESSION['totalCartItems']-1)){
  		$postText="itemExportItem".$i;
  		$_SESSION['exportedCart'][$i]['Item']=$_POST[$postText];
		$postText="itemExportQTY".$i;
  		$_SESSION['exportedCart'][$i]['QTY']=$_POST[$postText];
		$postText="itemExportPrice".$i;
  		$_SESSION['exportedCart'][$i]['Price']=$_POST[$postText];
		$postText="itemExportpriceNum".$i;
  		$_SESSION['exportedCart'][$i]['priceNum']=$_POST[$postText];
  $i++;
  }
  
?>