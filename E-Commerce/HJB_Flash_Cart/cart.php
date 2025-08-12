<?php 
	include_once("$DOCUMENT_ROOT/library/db.php");
	
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
	
	//connect to database
	$connect=mysql_connect($host_default, $login_default, $pw_default);
	$select_db=mysql_select_db($db_default);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $siteName; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14pt; }
.style5 {font-size: 24pt}
.style8 {color: #92673D}
.style9 {
	color: #A6673C;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10pt;
	text-decoration: none;
}
.style11 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #ECE9D8; }
body {
	background-color: #CCCCCC;
}
-->
</style>
</head>

<body>
<div align="center">  <br>
  <table width="850" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td>        <table width="850"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td bgcolor="#CCDFEC"><div align="left"> 
              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><a href="/index.php"><img src="/images/logo_cart.gif" border="0"></a></td>
                  <td><div align="right">
                      <table width="300" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
                        <tr>
                          <td width="300" bgcolor="F6D48A"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td width="33%">&nbsp;</td>
                                <td width="67%"><div align="center" class="style1">Search Our Catalog </div></td>
                              </tr>
                            </table>
                              <form name="form1" method="post" action="/cart.php" style="display:inline">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="searchTerm" type="text" id="searchTerm">
            <input type="submit" name="Submit" value="Go">            
            <input name="thisIsASearch" type="hidden" id="thisIsASearch" value="true">
                            </form></td>
                        </tr>
                                        </table>
                    </div>
                    </td>
                </tr>
              </table>
            </div>              
            <div align="right">
              </div></td>
          </tr>
        </table>
        <div align="center">
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="850" height="600">
            <param name="movie" value="cart.swf">
            <param name="quality" value="high">
            <param name="FlashVars" value="blankity=blankity<?php
													$query="SELECT cartcategories.id, cartcategories.name 
															FROM cartcategories, cartcategories_to_sites
															WHERE cartcategories.id=cartcategories_to_sites.cartCategoriesID
															AND cartcategories_to_sites.sitesID=1
															ORDER BY cartcategories.name
															";
													$result=mysql_query($query);
													$i=1;
													while($row=mysql_fetch_array($result)){
														echo "&category".$i."=".$row['name']."";
														echo "&categoryID".$i."=".$row['id']."";
														$i++;
													}
													$totalCategories=($i-1);
													echo "&totalCategories=".$totalCategories."&CheckOutProcessType=".$CheckOutProcessType."&termsAndAgreements=".$termsAndAgreements."&ChargeSalesTax=".$ChargeSalesTax."&salesTaxAmount=".$salesTaxAmount."&stateAbrev=".$stateAbrev;
													include_once ("Update_cart_review.php");
													//is this a search request
													if ($_POST['thisIsASearch']=="true"){
														echo "&thisIsASearch=truep&searchTerm=".$_POST['searchTerm'];
													}
													 ?>">
            <embed src="cart.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="850" height="600"></embed>
          </object>
        </div>
      </td>
    </tr>
  </table>
  <br>
  <span class="style9">  <a href="http://www.mans-garage.com/hjb" class="style9">HJB Flash Cart</a> - The All Flash Shopping Cart -<a href="all_products.php" class="style9">Product Listing</a> <br>
  Copyright &copy; 1997-<?php echo date("Y"); ?> <a href="http://www.nationwideshelving.com" class="style9">NWS</a> All Rights Reserved </span><br>
</div>
</body>
</html>
