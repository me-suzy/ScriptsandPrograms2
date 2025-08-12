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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Manage Shopping Cart</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style6 {font-size: 10px}
.style7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.style10 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; }
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; font-size: 18pt; }
.style15 {font-size: 14px;
	color: #FF0000;
}
.style18 {font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #010556;
	font-size: 24px;
}
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
          <td width="18%" valign="top" bgcolor="#010556"><p align="center"><br>
            <br>
            <br>
            <br>
            <?php menu_shopping_cart(); ?>
            <br>              
          </p>          </td>
          <td width="82%" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="1%">&nbsp;</td>
                <td width="99%"><p><span class="style12">Manage Shopping Carts </span></p>
                  <p><span class="style1">Welcome to HJB Flash Cart. What would you like to do. <br> 
                    </span><br>
                  </p>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="33%"><div align="center"><a href="/update/shopping_cart/categories.php?siteID=1"><br>
                                <img src="/images/buttons/manage_sites.gif" width="106" height="125" border="0"></a><br>
                      </div></td>
                      <td width="33%"><div align="center"><a href="manage_categories.php"><br>
                        </a></div>
                          <div align="center"><a href="shopping_cart/manage_products.php"><img src="/images/buttons/manage_products.gif" width="106" height="125" border="0"></a><br>
                        </div></td>
                    </tr>
                    <tr>
                      <td><div align="center"><br>

                              <a href="update_logo.php"><img src="../images/buttons/update_logo.gif" width="106" height="125" border="0"></a><br>
                      </div>
                      </td>
                      <td><div align="center"><br>
                              <a href="shopping_cart/view_orders_main.php"><img src="/images/buttons/view_orders.gif" width="106" height="125" border="0"><br>
                          </a><br>
                      </div></td>
                      <td><div align="center"></div></td>
                    </tr>
                  </table>                  <br></td>
              </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>
