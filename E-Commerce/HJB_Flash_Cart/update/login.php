<?php

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

if ($_POST['submitted']){
	if ($_POST['username'] && $_POST['password']){
		//reset error
		$error="";
		
		//validates user
		include ("$DOCUMENT_ROOT/library/inc/validate_user.php");
		$validated=validate_user($_POST['username'], $_POST['password']);
	
		//Logs user in or give them an error.
		if ($validated==true){
			session_start ();
			$_SESSION['logged_in']=true;
			
			//sets refer variable based on the post value of refer.
			if ($_POST['refer']){
				$refer=$_POST['refer'];
			}else{
				$refer="/update/";
			}
			header ("location: $refer");
		}else{
			$error="The username or password you entered are incorrect.  Please try again.";
		}
	}else{//if username or password are not completed
		$error="You did not enter a username or password, please try again.";
	}
}
?>
<html>
<head>
<title>Nationwide Shelving Login</title>
<style type="text/css">
<!--
.style1 {font-family: Verdana, Arial, Helvetica, sans-serif}
.style2 {color: #FF0000}
.style3 {color: #FFFFFF}
.style10 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #FF0000; }
.style7 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
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
<BODY onLoad="form1.username.focus()">
<?php //INCLUDE HEADER
include ("$DOCUMENT_ROOT/library/header.php"); ?>
<table width="100%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#010556">
  <tr>
    <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="18%" valign="top" bgcolor="#010556"><br>          </td>
          <td width="82%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="1%">&nbsp;</td>
                <td width="99%"><div align="center"><br>
                  <br>
                  <br>
                  <br>
                  <br>
                  <br>
                  <br>
                    <table width="40%"  border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
                      <tr>
                        <td bgcolor="#010556"><p class="style1 style3">LOGIN</p>
                            <p class="style1"><span class="style2"><?php echo $error; ?></span></p>
                            <form name="form1" method="post" action="login.php">
                              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="49%" class="style1"><div align="right" class="style3">USERNAME</div></td>
                                  <td width="4%" class="style1">&nbsp;</td>
                                  <td width="47%" ><input name="username" type="text" id="username" size="15"></td>
                                </tr>
                                <tr>
                                  <td class="style1"><div align="right" class="style3">PASSWORD</div></td>
                                  <td class="style1">&nbsp;</td>
                                  <td ><input name="password" type="password" id="password" size="17">
                                      <input name="refer" type="hidden" id="refer" value="<?php  echo $refer; ?>">
                                  <input name="submitted" type="hidden" id="submitted" value="true"></td>
                                </tr>
                                <tr>
                                  <td colspan="3" class="style1"><div align="center">
                                      <input type="submit" name="Submit" value="Login">
                                    </div></td>
                                </tr>
                              </table>
                          </form></td>
                      </tr>
                    </table>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                </div>
                </td>
              </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>