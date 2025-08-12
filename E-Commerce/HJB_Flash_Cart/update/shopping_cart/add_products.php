<?php
	include_once("$DOCUMENT_ROOT/library/auth.php");
	include_once("$DOCUMENT_ROOT/library/db.php");
	include_once("$DOCUMENT_ROOT/library/menus.php");

/*
***************************************************************************************************************************
*****************************************COPYRIGHT 2005 YOU MAY NOT USE THIS WITHOUT PERMISSION****************************

HJB IS PROVIDED "As Is" FOR USE ON WEBSITES WHERE A LICENSE FOR SUCH USE WAS PURCHASED.  IT MAY ONLY BE USED ON ONE SITE PER LICENSING
FEE.  IN ORDER TO USE ON ADDITIONAL SITES, ADDITIONAL LICENSES MUST BE PURCHASED.  


THE PHP SCRIPTS MAY BE ALTERED, AS LONG AS THE CREDIT LINE AND LINKS AT THE BOTTOM OF EACH PAGE REMAIN. THE FLASH MAY NOT IN ANY
WAY BE CHANGED OR ALTERED.  ANY VIOLATION OF THESE TERMS WILL RESULT IN THE FORFEITING OF YOUR RIGHT TO USE THIS SOFTWARE.

NationWideShelving.com does not guarantee this software in anyway.  You use this at your own risk.  NationWideShelving or any of its
employees or subsidiaries are not responsible for any damage, and / or loss of business, reputation, or other damages of any kind
which are caused whether actual or not, by the use of this product.  By using this product you agree to hold NationWideShelving, its
employees, and all subsidiaries harmless for any and all reasons associated with your use of this product.

Your installation of this software consititues an agreement to these terms.

****************************************************************************************************************************
	*/

	session_start();
	
	
	//connect to database
	$connect=mysql_connect($host_default, $login_default, $pw_default);
	$select_db=mysql_select_db($db_default);
	
	//is this a submission?
	if ($_POST['submitted']=="true"){
		$query="INSERT INTO cartitems (name, price, description, status) VALUES ('".$_POST['name']."', '".$_POST['price']."','".$_POST['description']."', '".$_POST['status']."')";
		
		$result=mysql_query($query);
		$productID=mysql_insert_id();
		
		//now lets input the image
		$uploadfile="$DOCUMENT_ROOT/images/cart/".$productID.".jpg";
		
		if ($_FILES['image_file']['tmp_name']){
			move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadfile);
		}else{
			
			//no file was uploaded, so lets create one.
			$filename="$DOCUMENT_ROOT/images/cart/no_image.jpg";
			$handle=fopen($filename, "r");
			$contents = fread($handle, filesize($filename));
			$newImage=fopen($uploadfile, "w+");
			fwrite($newImage, $contents);
		}
		
		//now lets input the Large image
		$uploadfile="$DOCUMENT_ROOT/images/cart/".$productID."large.jpg";
		
		if ($_FILES['image_file_large']['tmp_name']){
			move_uploaded_file($_FILES['image_file_large']['tmp_name'], $uploadfile);
		}else{
			
			//no file was uploaded, so lets create one.
			$filename="$DOCUMENT_ROOT/images/cart/large_no_image.jpg";
			$handle=fopen($filename, "r");
			$contents = fread($handle, filesize($filename));
			$newImage=fopen($uploadfile, "w+");
			fwrite($newImage, $contents);
		}
		
		//finally we need to add the product to any and all selected categories.
		$i=0;
		while ($i<=(count($catsToAdd)-1)){
			$query2="INSERT INTO cartitems_to_cartcategories (cartItemsID, cartCategoriesID) VALUES ('".$productID."', '".$catsToAdd[$i]."')";
			$result2=mysql_query($query2);
		$i++;
		}
		
	
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Manage Shopping Cart Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; font-size: 18pt; }
.style16 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FFFFFF; }
.style17 {color: #FFFFFF}
.style18 {font-size: 9px}
-->
</style>
</head>

<body>
<?php //INCLUDE HEADER
include ("$DOCUMENT_ROOT/library/header.php"); ?>
<table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="18%" valign="top" bgcolor="#010556"><div align="center"><br>
              <br>
              <br>
              <br>
              <?php menu_shopping_cart(); ?>          
              <br>              
          </div></td>
          <td width="82%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="1%">&nbsp;</td>
                <td width="99%" valign="top"><p><span class="style12">Manage Shopping Cart Products</span></p>                
                  <form action="" method="post" enctype="multipart/form-data" name="form1">
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="19%" valign="top"><div align="right" class="style1">Product Name: </div></td>
                        <td width="2%">&nbsp;</td>
                        <td width="79%" valign="top"><input name="name" type="text" id="name">
                            <br></td>
                      </tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Price:</div></td>
                        <td>&nbsp;</td>
                        <td><input name="price" type="text" id="price">
                            <br></td>
                      </tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Description:</div></td>
                        <td>&nbsp;</td>
                        <td><textarea name="description" cols="45" rows="5" id="description"></textarea>
                            <br></td>
                      </tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Status:</div></td>
                        <td>&nbsp;</td>
                        <td><span class="style1">On:
                              <input name="status" type="radio" value="1" checked>
      Off:
      <input name="status" type="radio" value="0">
      <br>
                        </span></td>
                      </tr>
                      <tr>
                        <td valign="top"><div align="right" class="style1">Small Image:<br>
                            <span class="style18"><br>
                            Must Be 54 X 54</span></div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><input type=file name=image_file filter="/*.gif"></td>
                      </tr>
                      <tr>
                        <td valign="top"><div align="right"><span class="style1">Large Image:<br>
                              <span class="style18"><br>
Must Be 300 X 400 </span></span></div></td>
                        <td>&nbsp;</td>
                        <td valign="top"><input name=image_file_large type=file id="image_file_large" filter="/*.gif"></td>
                      </tr>
                      <tr>
                        <td colspan="3"><br>
                            <span class="style1">What Categories Should This Product Appear Under?<br>
                            <br>
                            </span>
                            <table width="95%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
                              <tr bgcolor="#010556">
                                <td width="45%"><span class="style16">Site Name </span></td>
                                <td width="45%"><span class="style16">Category Name </span></td>
                                <td width="10%"><span class="style17">&nbsp;</span></td>
                              </tr>
                              <?php
								$query="SELECT * FROM sites";
								$result=mysql_query($query);
								while ($row=mysql_fetch_array($result)){
							?>
                              <tr>
                                <td colspan="3">&nbsp;</td>
                              </tr>
                              <tr>
                                <td bgcolor="#010556"><span class="style1 style17"><?php echo $row['name']; ?> </span></td>
                                <td><span class="style1">&nbsp;</span></td>
                                <td><span class="style1">&nbsp;</span></td>
                              </tr>
                              <?php
									$query2="SELECT cartcategories.* 
											FROM cartcategories, cartcategories_to_sites 
											WHERE cartcategories.id=cartcategories_to_sites.cartCategoriesID
											AND cartcategories_to_sites.sitesID='".$row['id']."'
											";
									$result2=mysql_query($query2);
									while ($row2=mysql_fetch_array($result2)){
									?>
                              <tr>
                                <td>&nbsp;</td>
                                <td><span class="style1"><?php echo $row2['name']; ?></span></td>
                                <td><div align="center">
                                    <input name="catsToAdd[]" type="checkbox" id="catsToAdd[]" value="<?php echo $row2['id']; ?>">
                                </div></td>
                              </tr>
                              <?php } ?>
                              <?php
									
							}
							?>
                            </table>
                            <span class="style1"> </span></td>
                      </tr>
                      <tr>
                        <td colspan="3"><div align="center"><br>
                                <input type="submit" name="Submit" value="Add Product To Shopping Carts">
                                <input name="submitted" type="hidden" id="submitted" value="true">
</div></td>
                      </tr>
                    </table>
                  </form>
                  <p><span class="style12">                    </span><a href="add_products.php"><br>
                    <br>
                    </a><br>
                    <br>
                  </p></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
