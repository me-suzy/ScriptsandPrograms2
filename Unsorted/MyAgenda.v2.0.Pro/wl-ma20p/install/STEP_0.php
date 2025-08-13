</head>
<BODY bgcolor="#007F7F">
<table border="1" cellspacing="0" cellpadding="0" align="center" width="500">
  <tr bgcolor="#C6C3C6">
    <td>
      <table width="500" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr bgcolor="#400080">
          <td height="20" bgcolor="#000084"><b><font color="#FFFFFF">
            &nbsp;myAgenda <?=$version;?> Installation</font></b></td>
          <td height="20" align="right" bgcolor="#000084"><a href="javascript:void(0);" onclick="Cancel()"><img src="cross.gif" width="16" height="14" border="0"></a></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <form method="post" name="myform" action="<?=$SELF;?>">
			<input type="hidden" name="STEP" value="1">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr bgcolor="#FFFFFF">
                  <td  height="60">
                    <table border="0" cellspacing="0" cellpadding="0" width="500">
                      <tr>
                        <td width="190"><img src="install.jpg" width="164" height="300" alt="myAgenda<?=$version;?>"></td>
                        <td width="310" valign="top" ><br>
                          <b><br>
                          Welcome to the Installation Wizard for<br>
						  myAgenda<?=$version;?></b><br>
                          <br>
                          <br>
                          <br>
                            <?php
							if($msg) {
							?>
							<font color="#FF0000"><b><?=$msg;?></b></font>
							<?php
							}else{
							?>
	                          Press 'Next' button to begin the installation.
   							<?php
							}
							?>
						</td>
	                  </tr>
                    </table>
                  </td>
                </tr>
                <tr bgcolor="#C6C3C6">
                  <td align="center" height="20">
                    <img src="h_line.gif" height="18" width="490">
                  </td>
                </tr>
                <tr bgcolor="#C6C3C6" align="right">
                  <td><input type="button" value="< Back" style="width:75px;height:23px;" disabled><input type="submit" name="NEXT" value="Next >" style="width:75px;height:23px;" <?=($msg ? "disabled" : "");?>>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Cancel" onclick="Cancel()" style="width:75px;height:23px;">&nbsp;&nbsp; </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>