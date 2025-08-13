</head>
<BODY bgcolor="#007F7F" onload="document.myAgenda.LICENCE.focus()">
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
            <form method="post" name="myAgenda" action="<?=$SELF;?>">
			<input type="hidden" name="STEP" value="2">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                <tr bgcolor="#FFFFFF">
                  <td height="30"><b>&nbsp;&nbsp;&nbsp;License Agreement</b></td>
                </tr>
                <tr bgcolor="#FFFFFF" valign="top">
                  <td height="30"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Please read the following agreement carefully.</td>
                </tr>
                <tr bgcolor="#C6C3C6">
                  <td align="center">
                    <table width="400" border="0" cellspacing="0" cellpadding="0" align="center">
                      <tr>
                        <td height="40">Press the PAGE DOWN to see the rest of the agreement</td>
                      </tr>
                      <tr>
                        <td>
                          <textarea name="LICENCE" cols="48" rows="10" wrap="VIRTUAL" class="textarea">
: : WDYL : : WDYL : : WDYL : : WDYL : : WDYL : :
  : : Some crappy license agreement removed: :
: : WDYL : : WDYL : : WDYL : : WDYL : : WDYL : :
</textarea></font>
                        </td>
                      </tr>
                      <tr>
                        <td height="50">Do you accept all the terms
                          of the preceding Lisence Agrement? If you choose NO,
                          the setup will close. To install myAgenda <?=$version;?>,
                          you must accept this agreement. </td>
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
                  <td><input type="button" value="< Back" onClick="Back()" style="width:75px;height:23px;"><input type="submit" value="Yes" style="width:75px;height:23px;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="No" onclick="Cancel()" style="width:75px;height:23px;">&nbsp;&nbsp;</td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>