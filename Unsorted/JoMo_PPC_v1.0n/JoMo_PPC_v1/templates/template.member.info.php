<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="member.menu.php"%>

<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#ffffff" background="">
    <TR>
      <TD ALIGN="CENTER" CLASS="clsText1">
    	  <h1>Welcome <%$result.firstName%> <%$result.lastName%>!</h1>
      </TD>
    </TR>
    <TR>
      <TD ALIGN="CENTER">
	      Your account is <%if $memberAccount.isActive == 1%>active<%else%>NOT active<%/if%> <br>
	      account balance: $<%$memberAccount.balance%> <br>
	      <a href="<%$selfURL%>?memberMode=account">go to account management?</a>
      </TD>
    </TR>
</TABLE>

<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#ffffff" background="">
        <TR>
          <TD ALIGN="CENTER" CLASS="clsText1"><h2>Your personal info</h2>
          <%$msg%>
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
    
	if ((f.all["result[password]"].value!="" || f.all["result[repassword]"].value!="")&& 		f.all["result[password]"].value!=f.all["result[repassword]"].value){
	      alert("passwords not the same");
	      return false;
	    }
    
    return true;
  }
</script>


<FORM name="f" action="<%$selfURL%>" method="post" onsubmit="return checkf();">
	<input type="hidden" name="action" value="<%$memberMode%>">
	<input type="hidden" name="cmd" value="update">
	<INPUT TYPE="hidden" NAME="result[memberID]" value="<%$result.memberID%>"></TD></TR>

	<TABLE WIDTH="400" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
    	<TR>
			<TD ALIGN="CENTER" colspan="2">
				<%include file="member.form.php"%>
		    </TD>
	    </TR>
        <TR BGCOLOR="#999999">
               <TD COLSPAN="1" ALIGN="CENTER">
                  <INPUT TYPE="SUBMIT" value="submit" name="update">
              </TD>
              <TD COLSPAN="1" ALIGN="CENTER">
                  <INPUT TYPE="reset" NAME="submit" VALUE="RESET">
              </TD>
              
        </TR>

    </TABLE>
    
</FORM>

<%include file="member.footer.php"%>