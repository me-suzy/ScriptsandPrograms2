<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      Lizard Cart
    </title>
<link rel="STYLESHEET" type="text/css" href="site.css">
<SCRIPT SRC='nopcart.js'></SCRIPT>
<script type="text/javascript">   //<![CDATA[
function on(id) {
    id.style.backgroundColor="#3366CC";
}
function off(id) {
  id.style.backgroundColor="#003399";
}
function go(where) {
  main.location.href="where";
}
function CheckForm( theform )
{
	var bMissingFields = false;
	var strFields = "";
	
	if( theform.first_name.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: First\n";
	}
	if( theform.last_name.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: Last Name\n";
	}
	if( theform.address1.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: Address\n";
	}
	if( theform.city.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: City\n";
	}
	if( theform.state.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: State\n";
	}
	if( theform.zip.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: Zipcode\n";
	}
	if( theform.night_phone_a.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: Phone\n";
	}

	if( theform.night_phone_b == '' ){
		bMissingFields = true;
		strFields += "     Billing: Phone\n";
	}
	
	if( theform.night_phone_c == '' ){
		bMissingFields = true;
		strFields += "     Billing: Phone\n";
	}	
	if( theform.day_phone_a.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: Phone\n";
	}

	if( theform.day_phone_b == '' ){
		bMissingFields = true;
		strFields += "     Billing: Phone\n";
	}
	
	if( theform.day_phone_c == '' ){
		bMissingFields = true;
		strFields += "     Billing: Phone\n";	
	}
	
	if( theform.email.value == '' ){
		bMissingFields = true;
		strFields += "     Billing: Email\n";
	}
		
	if( bMissingFields ) {
		alert( "I'm sorry, but you must provide the following field(s) before continuing:\n" + strFields );
		return false;
	}
	
	return true;
}
    //]]></script>
  </head>
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
              <td width="10" background="graphics/bg.gif">
                <img alt="image" src="graphics/head.gif" />
              </td>
            </tr>
            <tr height="20">
              <td>
	
			  <!-- begin top nav -->
			  
                <table border="0" cellspacing="0" cellpadding="0" height="18">
                  <tr style="color: White; font-size: 10px; font-weight: bold; font-family: Tahoma;">
                    <td width=1></td>
					<td class="sep"></td>
				<td onmouseover="on(this)" onmouseout="off(this)" style="cursor: hand" >
                      &#160;&#160;<a href="index.php"><font color="#FFFFFF">Home</font></a>&#160;&#160;</td>
					  <td class="sep"></td>					
				<td onmouseover="on(this)" onmouseout="off(this)" style="cursor: hand" >
                      &#160;&#160;<a href="products.php"><font color="#FFFFFF">Products</font></a>&#160;&#160;</td>
					  <? include ("config.inc.php");
					  $pResult = mysql_query("select * from pages ");
                       while ($row=mysql_fetch_object($pResult)) { ?>
                    <td class="sep"></td>
					
                    <td onmouseover="on(this)" onmouseout="off(this)"
                    style="cursor: hand" >
                      &#160;&#160;<a href="pages.php?id=<? echo "$row->id" ?>">
					  <font color="#FFFFFF"><? echo "$row->page_title" ?></font></a>&#160;&#160;<? } ?>
                    </td>
					 <td align="center">
					 						  <?php

                          $date = date ("H");
                          echo date ("<b>l dS of F Y h:i:s A</b>");


                                ?>
						  
                             
					 
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
                    <td width="3" bgcolor="#003399" align="left"><img alt="image" src="graphics/left_blue_bg.gif" /></td>
                    <td bgcolor="#003399"
                    style="color: White; font-family: Tahoma; font-weight: bold;">
                      Welcome
                    </td>
                    <td width="1" bgcolor="#003399"
                    background="graphics/right_blue_bg.gif"></td>
                  </tr>
                  <tr>
                    <td colspan="3" bgcolor="#F1F1F1" height="100"
                    valign="top">
                      <table cellspacing="0" cellpadding="4" width="100%"
                      height="100%">
                        <tr>
                          <td valign="top">
						  <?php
                         if ($date < 12) echo "<div align=\"center\"><b>Good Morning!</div>";
                        else if ($date < 18) echo "<div align=\"center\"><b>Good Afternoon!</div>";
                        else echo "<div align=\"center\"><b>Good Evening!</div>";
                                ?>
						  
      <img src="graphics/lizardcart.jpg" width="190" height="114" border="0" alt="">
                          </td>
                        </tr>
						<tr><td align="center" bgcolor="#3366CC" bordercolor="#0000FF">
						    <form method="post" action="search.php"> 
   <b><font color="#FFFFFF"><b>Search</b></font></b><br><br>
   <select name="metode" size="1">
    <option value="item_name">Name</option>
    <option value="item_desc">Decription</option>
	<option value="item_descde">Detail Decription</option>
	<option value="item_category">Category</option>
    </select>
    <input type="text" name="search" size="25"> 
    <input type="submit" value="Begin Searching!!">
    </form>
						</td></tr>
                      </table>
                    </td>
                  </tr>
                </table>
				<!-- end Side bar -->
              </td>
              <td bgcolor="white" valign="top">
			  <!-- begin main content -->
                <table width="100%" height="100%" border="0" cellspacing="10" cellpadding="10" bordercolor="#0000FF">
                  <tr>
                    <td valign="top">

