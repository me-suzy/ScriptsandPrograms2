<?php
	include_once("$DOCUMENT_ROOT/library/auth.php");
	include_once("$DOCUMENT_ROOT/library/db.php");
	include_once("$DOCUMENT_ROOT/library/menus.php");

	session_start();
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
	if ($_GET['type']=="new"){
		$query="SELECT * FROM orders WHERE orderStatus='new'";
	}else if ($_GET['type']=="progress"){
		$query="SELECT * FROM orders WHERE orderStatus='progress'";
	}else if ($_GET['type']=="completed"){
		$query="SELECT * FROM orders WHERE orderStatus='completed'";
	}else{
		$query="SELECT * FROM orders";
	}
	$result=mysql_query($query);
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Manage Shopping Cart Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.style12 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; font-size: 18pt; }
.style13 {color: #FFFFFF}
.style14 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style15 {color: #FFFFFF; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
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
                <td width="99%" valign="top"><p><span class="style12">View Orders </span></p>                
                  <p class="style14">                    What orders would you like to view?  </p>
                  <form name="form1">
                    <select name="menu1" onChange="MM_jumpMenu('parent',this,0)">
                      <option value="view_orders_main.php?type=all" selected>All</option>
                      <option value="view_orders_main.php?type=new" <?php if ($_GET['type']=="new"){ echo "selected"; } ?>>New</option>
                      <option value="view_orders_main.php?type=progress" <?php if ($_GET['type']=="progress"){ echo "selected"; } ?>>In Progress</option>
                      <option value="view_orders_main.php?type=completed" <?php if ($_GET['type']=="completed"){ echo "selected"; } ?>>Completed</option>
                                        </select>
                  </form>
                  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr bgcolor="#010556">
                      <td width="33%"><div align="center" class="style13 style14">Customer Name </div></td>
                      <td width="33%"><div align="center" class="style15">Order Status</div></td>
                      <td width="33%"><div align="center" class="style15">Delete</div></td>
                    </tr>
					<?php
						while ($row=mysql_fetch_array($result)){
					?>
                    <tr>
                      <td><span class="style14"><a href="view_orders.php?id=<?php echo $row['id']; ?>"><?php echo $row['firstName']." ".$row['lastName']; ?></a> </span></td>
                      <td><span class="style14"><?php echo $row['orderStatus']; ?></span></td>
                      <td><div align="center" class="style14"><a href="delete_order.php?id=<?php echo $row['id']; ?>">Delete</a></div></td>
                    </tr>
					<?php
						}
					?>
                  </table>                  <p><br>
                    <br>
                    <br>
                    <br>
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
