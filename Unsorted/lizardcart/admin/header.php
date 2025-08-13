<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      <? echo " $title" ?>
    </title>
<link rel="STYLESHEET" type="text/css" href="../site.css">
<script>
    //<![CDATA[
function on(id) {
    id.style.backgroundColor="#3366CC";
}
function off(id) {
  id.style.backgroundColor="#003399";
}
function go(where) {
  main.location.href=where;
}
    //]]>
</script>
  </head>
  <!-- PRINT STARTS -->
  <body>
  <!-- begin main table -->
    <table border="0" cellspacing="0" cellpadding="0" height="100%"
    width="100%">
      <tr height="82">
        <td valign="top">
		<!-- begin header -->
          <table border="0" cellspacing="0" cellpadding="0" width="100%"
          bgcolor="#003399">
            <tr height="62">
              <td width="10" background="../graphics/bg.gif">
                <img alt="image" src="../graphics/head.gif" />
              </td>
            </tr>
            <tr height="20">
              <td>
	
			  <!-- begin top nav -->
			  
                <table border="0" cellspacing="0" cellpadding="0" height="18">
                  <tr style="color: White; font-size: 10px; font-weight: bold; font-family: Tahoma;">
                    <td width=1></td>
				<td onmouseover="on(this)" onmouseout="off(this)" style="cursor: hand" onclick="go('index.php')">
                      &#160;&#160;<a href="<? echo "$nosecureurl" ?>index.php"><font color="#FFFFFF">Product List</font></a>&#160;&#160;</td>
                    <td class="sep"></td>
                    <td onmouseover="on(this)" onmouseout="off(this)"
                    style="cursor: hand" onclick="go('javascript:void(0)')">
                      &#160;&#160;<a href="<? echo "$nosecureurl" ?>detail.php?action=3"><font color="#FFFFFF">Add A Product</font></a>&#160;&#160;
                    </td>
                    <td class="sep">
                    </td>
                    <td onmouseover="on(this)" onmouseout="off(this)"
                    style="cursor: hand" onclick="go('javascript:void(0)')">
                      &#160;&#160;<a href="<? echo "$nosecureurl" ?>pageedit.php?action=3"><font color="#FFFFFF">Add A Page</font></a>&#160;&#160;
                    </td>
                    <td class="sep">
                    </td>
                    <td onmouseover="on(this)" onmouseout="off(this)"
                    style="cursor: hand" onclick="go('javascript:void(0)')">
                      &#160;&#160;<a href="<? echo "$nosecureurl" ?>pagelist.php"><font color="#FFFFFF">List Pages</font></a>&#160;&#160;
                    </td>
					 <td class="sep">
                    </td>
                    <td onmouseover="on(this)" onmouseout="off(this)"
                    style="cursor: hand" onclick="go('javascript:void(0)')">
                      &#160;&#160;<a href="<? echo "$nosecureurl" ?>automail.php"><font color="#FFFFFF">Email Users</font></a>&#160;&#160;
                    </td>						
					 <td class="sep">
                    </td>
                    <td onmouseover="on(this)" onmouseout="off(this)"
                    style="cursor: hand" onclick="go('javascript:void(0)')">
                      &#160;&#160;<a href="<? echo "$nosecureurl" ?>orderlist.php"><font color="#FFFFFF">IPN List Orders</font></a>&#160;&#160;
                    </td>									  			  
                  </tr>
                </table>
				
				<!-- end top nav -->
				
				
              </td>
            </tr>
          </table>
		  
   <!-- end header -->
				
        </td>
      </tr>
      <tr bgcolor="#3366CC">
        <td>
          <table border="0" cellspacing="0" cellpadding="0" width="100%"
          height="100%">
            <tr>
              <td width="175" valign="top">
                <br />
                 <!-- begin side bar -->
                <table border="0" cellspacing="0" cellpadding="0" width="160"
                align="center">
                  <tr>
                    <td width="3" bgcolor="#003399" align="left"><img alt="image" src="../graphics/left_blue_bg.gif" /></td>
                    <td bgcolor="#003399"
                    style="color: White; font-family: Tahoma; font-weight: bold;">
                      Welcome
                    </td>
                    <td width="1" bgcolor="#003399"
                    background="../graphics/right_blue_bg.gif"></td>
                  </tr>
                  <tr>
                    <td colspan="3" bgcolor="#F1F1F1" height="100"
                    valign="top">
                      <table cellspacing="0" cellpadding="4" width="100%"
                      height="100%">
                        <tr>
                          <td valign="top">
                            <p><b>The Lizard Cart Admin:
							You can add,edit or delete products and pages</b></p>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
				<!-- end Side bar -->
              </td>
              <td bgcolor="white" valign="top">
			  <!-- begin main content -->
                <table border="0" cellspacing="10" cellpadding="10" width="100%"
                height="100%">
                  <tr>
                    <td valign="top">
