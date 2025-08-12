<table class="color2" cellSpacing="0" cellPadding="3" align=center border="0">
              <tbody>
              <tr>
                
      <td class="color3"><b><?php echo $library_name; ?> Login</b></td>
    </tr>
              <tr>
                
      <td>Please enter your username and password: 
        <form name="login_form" action="<?php echo $PHP_SELF; ?>?cookie_set=true&login=true" method="post">
          <table width="100%" border="0" cellpadding="1" cellspacing="5">
            <tr>
              <td width="32%">Username:</td>
              <td width="68%"><input class="Input" id="username" tabindex="1" size=15 name="username"></td>
            </tr>
            <tr>
              <td>Password:</td>
              <td><input class="Input" id="password" tabindex="2" type="password" size="15" name="password"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td> <div align="left">
                  <input class="Input" type="submit" value="Login &gt;" name="submit">
                </div></td>
            </tr>
          </table>
        </form></TD></TR></TBODY></TABLE>