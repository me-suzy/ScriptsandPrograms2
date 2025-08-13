</head>
<BODY bgcolor="#007F7F">
<table border="1" cellspacing="0" cellpadding="0" align="center" width="500">
  <tr bgcolor="#C6C3C6">
    <td>
      <table width="500" border="0" cellspacing="0" cellpadding="1" align="center">
        <tr bgcolor="#400080">
          <td height="20" bgcolor="#000084"><b><font color="#FFFFFF">
            &nbsp;myAgenda <?=$version;?> Installation</b></td>
          <td height="20" align="right" bgcolor="#000084"><a href="javascript:void(0);" onclick="Cancel()"><img src="cross.gif" width="16" height="14" border="0"></a></td>
        </tr>
        <tr align="center">
          <td colspan="2">
            <form method="post" name="myAgenda" action="<?=$SELF;?>" onsubmit="return validate()">
			<input type="hidden" name="STEP" value="4">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr bgcolor="#FFFFFF" valign="bottom">
                  <td height="30"><b>&nbsp;&nbsp;&nbsp;&nbsp;Installation</b></td>
                </tr>
                <tr bgcolor="#FFFFFF">
                  <td height="30">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Installation finish.</td>
                </tr>
                <tr bgcolor="#C6C3C6">
                  <td valign="top" align="center">
                    <table width="500" border="0" cellspacing="0" cellpadding="5" align="center" height="140">
                            <?php
							if($msg) {
							?>
							<tr>
								<td colspan="3"><font color="#FF0000"><b><?=$msg;?></b></font></td>
							</tr>
							<?php
							}else{
							?>
                      <tr>
                        <td align="center"><b>Installation successfully completed. Delete install directory and its contents now.</b></td>
                            </tr>
                            <?php
							}
							?>
                          </table>
                  </td>
                </tr>
                <tr bgcolor="#C6C3C6">
                  <td align="center" height="20">
                    <img src="h_line.gif" height="18" width="490">
                  </td>
                </tr>
                <tr bgcolor="#C6C3C6" align="right">
                  <td><input type="button" value="Finish" onclick="location.href='../admin'" style="width:75px;height:23px;">&nbsp;&nbsp;</td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>