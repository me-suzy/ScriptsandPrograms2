<%include file="header.php"%>

<div height="20"> &nbsp</div>

<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF" background="">
                        <TR>
                        <TD ALIGN="CENTER" >Member registration page</TD>
                        </TR>
                        <TR>
                        <TD ALIGN="CENTER" ><b><%$msg%></b></TD>
                        </TR>

                </TABLE>
        </TD>
        </TR>
</TABLE>



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
    
    if (f.all["result[password]"].value==""){
    	alert("please specify password");
        return false;
    }
    if (f.all["result[password]"].value!=f.all["result[repassword]"].value){
      alert("passwords not the same");
      return false;
    }
    
    
    
    
    return true;
  }
</script>


<FORM name="f" action="<%$selfURL%>" method="post" onsubmit="return checkf();">
	<input type="hidden" name="action" value="<%$memberMode%>">
	<input type="hidden" name="cmd" value="register">
	<INPUT TYPE="hidden" NAME="result[memberID]" value="<%$result.memberID%>"></TD></TR>

	<TABLE WIDTH="400" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
    	<TR>
			<TD ALIGN="CENTER" colspan="2">
				<%include file="member.form.php"%>
	    	</TD>
	    </TR>
        <TR BGCOLOR="#999999">
               <TD COLSPAN="1" ALIGN="CENTER">
                  <INPUT TYPE="SUBMIT" value="submit" name="register">
              </TD>
              
              <TD COLSPAN="1" ALIGN="CENTER">
                  <INPUT TYPE="button" CLASS="MENUBUTTON" NAME="submit" VALUE="CANCEL"
                   onclick="location.href='<%$selfURL%>?mode=members&memberMode=login'">
              </TD>
        </TR>

    </TABLE>

			
	
</FORM>	

<%include file="member.footer.php"%>