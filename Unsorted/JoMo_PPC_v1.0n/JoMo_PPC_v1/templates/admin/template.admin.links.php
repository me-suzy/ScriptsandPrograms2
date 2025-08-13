<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Links management.</b>
        </TD>
</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="10" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="left">
                <a href="<%$selfURL%>?mode=options&optionCategory=listing"><b>View and edit listing options</b></a>
        </TD>
</TR>
</TABLE>

<script language="javascript">
	function order(col){
			linksForm.orderby.value=col;
			linksForm.orderdir.value = linksForm.orderdir.value=="DESC"?"ASC":"DESC";			
			linksForm.submit();
	}
</script>

<form name="linksForm" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="mode" value="links">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="linkID" value="0">
<input type="hidden" name="orderby" value="<%$orderby%>">
<input type="hidden" name="orderdir" value="<%$orderdir%>">
<input type="hidden" name="page" value="<%$page%>">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
                click on title to view link.<br>
				if link is disabled then it won't be displayed in search result.<br>
        </TD>
</tr>
</table>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
                
            member:
            <SELECT name="memberID" STYLE="width:150">
             <OPTION value="0" SELECTED>all members
             <%html_options values=$memberIDs output=$memberNames selected=$memberID%>
            </SELECT>
            <!--
            url:
            <SELECT name="urlID" STYLE="width:150">
             <OPTION value="0" SELECTED>all urls
             <%html_options values=$urlIDs output=$urlNames selected=$urlID%>
            </SELECT>
			-->
            keyword
            <input type="text" name="keywordName" STYLE="width:150" value=<%$keywordName%>>
             &nbsp
            <input type="submit" value="apply" name="submitSetFilter" style="width:100;">
        </TD>

</TR>
</TABLE>

<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="left" bgcolor="#cccccc">
   <%$nLinks%> links. 
  </td>
  <td align="right" bgcolor="#cccccc" width="200">
   pages: 
   <%if $prev ne 0%> <a href="#" onclick="linksForm.page.value=<%$prev%>; linksForm.submit(); return false;"> << </a>   <%/if%>
   
   <%section name=Page loop=$pages%>
   	<a href="#" onclick="linksForm.page.value=<%$pages[Page]%>; linksForm.submit(); return false;"
		style="color:<%if $pages[Page] == $page%>red<%else%>black<%/if%>;"> <%$pages[Page]%> </a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   
   <%if $next ne 0%> <a href="#" onclick="linksForm.page.value=<%$next%>; linksForm.submit(); return false;"> >> </a>   <%/if%>

  </td>
 </tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center" width="20">               
			<a href="#" onclick="order('linkID'); return false;"> ID  </a>      </TD>
        <TD Align="center">                active        </TD>
        <TD Align="center" width="150">
			Member</TD>
        <TD Align="center" width="200">                
			<a href="#" onclick="order('url'); return false;">URL   </a>     </TD>
        <TD Align="center" width="150">  
			<a href="#" onclick="order('title'); return false;"> Title  </a>      </TD>
        <TD Align="center" width="150">  
			<a href="#" onclick="order('keywordName'); return false;"> Keyword   </a>     </TD>
        <TD Align="center">  
			<a href="#" onclick="order('bid'); return false;"> Bid   </a>     </TD>		
        <TD Align="center">  
			<a href="#" onclick="order('creationDate'); return false;"> Created   </a>     </TD>				
        <TD Align="center" > Commands        </TD>

</TR>
<%section name=LinkLoop loop=$links%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.LinkLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
        <TD align="center">
                <%$links[LinkLoop].linkID%>
        </TD>
        <TD align="center">
        	    <%$links[LinkLoop].activity%>
        </TD>
        <TD align="left">
					<%$links[LinkLoop].memberName%>
        </TD>
        <TD align="left">
        	<a href="<%$selfURL%>?mode=url&urlID=<%$links[LinkLoop].urlID%>&cmd=edit"><%$links[LinkLoop].url%></a>
        </TD>
        <TD align="left">
        	<a href="<%$selfURL%>?mode=link&linkID=<%$links[LinkLoop].linkID%>&cmd=edit"><%$links[LinkLoop].title%></a>
            
        </TD>
        <TD align="left">
                <%$links[LinkLoop].keywordName%>
        </TD>
        <TD align="center">
                <%$links[LinkLoop].bid%>
        </TD>
        <TD align="center">
                <%$links[LinkLoop].creationDate%>
        </TD>

        <TD Align="center" nowrap>
                <A HREF="#"
                        onclick="linksForm.cmd.value=<%if $links[LinkLoop].adminStatus == 1%>'deactivate'<%else%>'activate'<%/if%>; 
							linksForm.linkID.value=<%$links[LinkLoop].linkID%>;
							linksForm.submit(); return false;">
						<%if $links[LinkLoop].adminStatus == 1%>deactivate<%else%>activate<%/if%>
						</A> |
                <A HREF="#"
                        onClick = "linksForm.linkID.value=<%$links[LinkLoop].linkID%>; linksForm.cmd.value='delete'; if (confirm('Are you sure?')) linksForm.submit(); return false;" 
						>delete</A> |
						
                <A HREF="<%$selfURL%>?mode=link&linkID=<%$links[LinkLoop].linkID%>&cmd=edit">view</A> |

        </TD>
</TR>

<%/section%>

</TABLE>


<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
	<TD>
		<UL>
			<LI><a href="#"
				onclick="if (!confirm('Are you sure?')) return false; linksForm.cmd.value='activateall';linksForm.submit(); return false;"> 
				activate all links</a><br>
			<LI><a href="#"
				onclick="if (!confirm('Are you sure?')) return false; linksForm.cmd.value='deactivateall';linksForm.submit(); return false;"> 
				deactivate all links</a>		
		</UL>
    </TD>
</TR>
</TABLE>


</FORM>



<%include file="member.footer.php"%>
