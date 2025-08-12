<?php
include ("includes/menu.inc");
?>
<body background="images/bg.jpg" text="#000000" link="#000000" vlink="#000000" alink="#000000">
<div align="center">
  <form name="form1" method="post" action="process.php">
    <table width="286" border="0">
      <tr> 
        <td width="47" height="27"><strong> Name: </strong></td>
        <td width="48"><div align="right"><font size="2">*</font></div></td>
        <td width="179"><input name="name" type="text" id="name" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td colspan="2"><strong>Street:</strong></td>
        <td><input name="street" type="text" id="street" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td colspan="2"><strong>City:</strong></td>
        <td><input name="city" type="text" id="city" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td colspan="2"><strong>State:</strong></td>
        <td><input name="state" type="text" id="state" size="30" maxlength="2"></td>
      </tr>
      <tr> 
        <td colspan="2"><strong>Zip:</strong></td>
        <td><input name="zip" type="text" id="zip" size="30" maxlength="15"></td>
      </tr>
      <tr> 
        <td><strong>Email:</strong></td>
        <td><div align="right"><font size="2">*</font></div></td>
        <td><input name="email" type="text" id="sale2" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td><div align="center"></div></td>
        <td>&nbsp;</td>
        <td><div align="center"><font size="2">*Annotates a required field</font></div></td>
      </tr>
    </table>
    <p>
      <input type="submit" name="Submit" value="Submit">
      <input name="reset" type="reset" id="reset" value="Reset">
    </p>
  </form>
  <?php
include ("includes/footer.inc");
?>
</div>
