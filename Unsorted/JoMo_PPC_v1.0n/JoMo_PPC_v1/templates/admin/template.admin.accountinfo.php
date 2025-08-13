<%include file="admin/admin.header.php"%>

<div height="20"> &nbsp</div>

<%include file="admin/admin.menu.php"%>


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



<FORM name="f" action="<%$selfURL%>" method="post" >
	<input type="hidden" name="itemID" value="<%$result.itemID%>">
	<input type="hidden" name="result[<%$accountType%>ID]" value="<%$result.itemID%>">
	
	<input type="hidden" name="accountType" value="<%$accountType%>">
	<input type="hidden" name="cmd" value="update">


	<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#ffffff" background="">
	    <TR>
	      <TD ALIGN="CENTER" CLASS="clsText1">
	    	  <h1><%if $accountType == "member"%>member<%else%>affiliate<%/if%> info</h1>
	    	  name:<%$result.firstName%> <%$result.lastName%>.
	      </TD>
	    </TR>
	    <TR>
	      <TD ALIGN="center">
	      	<b>account:</b><br>
		      account is <%if $result.isActive == 1%>active<%else%>NOT active<%/if%> <br>
		      account balance: $<%$result.balance%><br>
		      	+/-&nbsp <input type="text" name="balanceValue" value="0" style="width:40; height:22;font-size:13px;">
	                <input type="submit" value="update balance" value="submitBalance"
	                	onclick="
	                	document.forms['f'].cmd.value='balance';
	                	return true;" >
		      <br>
	      </TD>
	    </TR>
	</TABLE>
	
	<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#ffffff" background="">
	        <TR>
	          <TD ALIGN="CENTER" CLASS="clsText1"><h2>personal info</h2>
	          <%$msg%>
	          </TD>
	        </TR>
	</TABLE>
	
	
	<TABLE WIDTH="400" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
    	<TR>
			<TD ALIGN="CENTER" colspan="2">
				<%include file="member.form.php"%>
		    </TD>
	    </TR>
        <TR BGCOLOR="#999999">
               <TD COLSPAN="2" ALIGN="CENTER">
                  <INPUT TYPE="SUBMIT" value="submit" name="update">
                  <INPUT TYPE="reset" NAME="submit" VALUE="RESET">
                  <INPUT TYPE="button" VALUE="CANCEL" onclick="location.href='<%$selfURL%>?mode=accounts'">
              </TD>
              
        </TR>

    </TABLE>
	
</FORM>	

<%include file="admin/admin.footer.php"%>