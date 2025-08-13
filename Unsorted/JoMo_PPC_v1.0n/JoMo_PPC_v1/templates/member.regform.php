<script>
  function checkRegForm(){
  	if (regForm.all["result[login]"].value==""){
    	alert("please specify login");
        return false;
    }
    if (regForm.all["result[password]"].value==""){
    	alert("please specify password");
        return false;
    }
    
    if (regForm.all["result[email]"].value==""){
    	alert("please specify email");
        return false;
    }
    if (regForm.all["result[password]"].value!=regForm.all["result[repassword]"].value){
      alert("passwords not the same");
      return false;
    }
    return true;
  }
</script>

<FORM name="regForm" action="<%$selfURL%>" method="post" onsubmit="return checkRegForm();">
<input type="hidden" name="action" value="register">

<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
                <TR>
                        <TD ALIGN="CENTER" colspan="2">
                        <TABLE WIDTH="200" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="">
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="120">Login:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="TEXT" NAME="result[login]" value="<%$result.login%>"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">Password:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="result[password]"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="120">Retype password:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="result[repassword]"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">First name:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="text" NAME="result[firstName]" value="<%$result.firstName%>"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">Last name:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="text" NAME="result[lastName]" value="<%$result.lastName%>"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">e-mail</TD>
                                        <TD WIDTH="120"><INPUT TYPE="text" NAME="result[email]" value="<%$result.email%>"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">address</TD>
                                        <TD WIDTH="120"><INPUT TYPE="text" NAME="result[address]" value="<%$result.address%>"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">phone</TD>
                                        <TD WIDTH="120"><INPUT TYPE="text" NAME="result[phone]" value="<%$result.phone%>"></TD></TR>

                        </TABLE>
                </TD>
                </TR>
                <TR BGCOLOR="#999999">
                      <TD COLSPAN="1" ALIGN="CENTER">
                          <INPUT TYPE="SUBMIT" CLASS="MENUBUTTON" NAME="submit" VALUE="SUBMIT">
                      </TD>
                      <TD COLSPAN="1" ALIGN="CENTER">
                          <INPUT TYPE="button" CLASS="MENUBUTTON" NAME="submit" VALUE="CANCEL"
                           onclick="location.href='<%$selfURL%>?mode=members&memberMode=login'">
                      </TD>

                </TR>

        </TABLE>

</FORM>