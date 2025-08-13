           
            <TABLE WIDTH="400" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="">
            		<TR BGCOLOR="#DDDDDD">
            			<TD ALIGN="CENTER" colspan="2" BGCOLOR="#DDDDDD"> * - mandatory fields. </TD>
            		</TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT" WIDTH="200">Login*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="TEXT" NAME="result[login]" value="<%$result.login%>"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">Password*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="result[password]"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">Retype password*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="result[repassword]"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">First name*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[firstName]" value="<%$result.firstName%>"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">Last name*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[lastName]" value="<%$result.lastName%>"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">Company name:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[companyName]" value="<%$result.companyName%>"></TD></TR>
                     <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">address*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[address]" value="<%$result.address%>"></TD></TR>
                     <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">zip code*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[zip]" value="<%$result.zip%>"></TD></TR>
                     <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT">country*:</TD>
                            <TD>
                                <SELECT name="result[country]" STYLE="width:205">
        							<%html_options values=$countryListValues output=$countryListOutput selected=$result.country%>
								</SELECT>
							</TD>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT" WIDTH="80">e-mail*:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[email]" value="<%$result.email%>"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT" WIDTH="80">phone:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[phone]" value="<%$result.phone%>"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT" WIDTH="80">fax:</TD>
                            <TD WIDTH="120"><INPUT TYPE="text" NAME="result[fax]" value="<%$result.fax%>"></TD></TR>
                    <TR BGCOLOR="#DDDDDD">
                            <TD CLASS="NORMALTEXT" WIDTH="80">How you found us:</TD>
                            <TD WIDTH="120">
                            	<TEXTAREA NAME="result[foundus]" style="width:205;"><%$result.foundus%></textarea>
                            </TD>
                    </TR>

            </TABLE>
