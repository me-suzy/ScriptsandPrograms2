<script>
  function checkf(){
  	if (f.all["result[login]"].value==""){
    	alert("please specify login");
        return false;
    }
    if (f.all["result[email]"].value==""){
    	alert("please specify email");
        return false;
    }
    
    if ((f.all["result[password]"].value!="" || f.all["result[repassword]"].value!="")&& f.all["result[password]"].value!=f.all["result[repassword]"].value){
      alert("passwords not the same");
      return false;
    }
    return true;
  }
  
</script>

<FORM name="f" action="<%$selfURL%>" method="post" onsubmit="return checkf();">
<input type="hidden" name="cmd" value="update">
<input type="hidden" name="memberID" value="<%$member.memberID%>">
<input type="hidden" name="result[memberID]" value="<%$member.memberID%>">

<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
    <TR>
    <TD ALIGN="CENTER" colspan="2">
        <TABLE WIDTH="200" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="">
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="120">Login:</TD>
                <TD WIDTH="120"><INPUT TYPE="TEXT" NAME="result[login]" value="<%$member.login%>"></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="80">Password:</TD>
                <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="result[password]" value=""><br>
                <span style="font-size:12px;">leave blank if not changed</span></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="120">Retype password:</TD>
                <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="result[repassword]" value=""><br>
                <span style="font-size:12px;">leave blank if not changed</span></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="80">First name:</TD>
                <TD WIDTH="120"><INPUT TYPE="text" NAME="result[firstName]" value="<%$member.firstName%>"></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="80">Last name:</TD>
                <TD WIDTH="120"><INPUT TYPE="text" NAME="result[lastName]" value="<%$member.lastName%>"></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="80">e-mail</TD>
                <TD WIDTH="120"><INPUT TYPE="text" NAME="result[email]" value="<%$member.email%>"></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                <TD CLASS="NORMALTEXT" WIDTH="80">address</TD>
                <TD WIDTH="120"><INPUT TYPE="text" NAME="result[address]" value="<%$member.address%>"></TD></TR>
            <TR BGCOLOR="#DDDDDD">
                    <TD CLASS="NORMALTEXT" WIDTH="80">phone</TD>
                    <TD WIDTH="120"><INPUT TYPE="text" NAME="result[phone]" value="<%$member.phone%>"></TD></TR>

        </TABLE>
                </TD>
                </TR>
                <TR BGCOLOR="#999999">
                      <TD COLSPAN="1" ALIGN="CENTER">
                          <INPUT TYPE="SUBMIT" NAME="submit" VALUE="UPDATE">
                      </TD>
                      <TD COLSPAN="1" ALIGN="CENTER">
                          <INPUT TYPE="reset" NAME="submit" VALUE="RESET">
                      </TD>

                </TR>

        </TABLE>

</FORM>