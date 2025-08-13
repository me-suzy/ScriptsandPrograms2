<%include file="header.php"%>

<BR>
<%include file="member.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Links.</b>
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

<input type="hidden" name="memberMode" value="links">
<input type="hidden" name="linkID" value="">
<input type="hidden" name="cmd" value="">

<input type="hidden" name="orderby" value="<%$orderby%>">
<input type="hidden" name="orderdir" value="<%$orderdir%>">
<input type="hidden" name="page" value="">


<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
                url:
            <SELECT name="urlID" STYLE="width:150">
             <OPTION value="0" SELECTED>all urls
             <%html_options values=$urlIDs output=$urlTitles selected=$urlID%>
            </SELECT>

            keyword
            <SELECT name="keywordID" STYLE="width:150">
             <OPTION value="0" SELECTED>all keywords
             <%html_options values=$keywordIDs output=$keywordNames selected=$keywordID%>
            </SELECT>

            <input type="submit" value="apply" name="submitSetfilter">
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


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER" bgcolor="#eeeeee">
        <TD align="left" colspan="8">
                you can deactivate link, and it won't be displayed in search results. 
                Later you can activate it. Also link is not active, if you haven't got enough money or if admin disabled it. <br>
                click on title to edit link.
        </TD>
</tr>
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center" width="20">   <a href="#" onclick="order('linkID'); return false;">ID</a></TD>
        <TD Align="center">                active        </TD>
        <TD Align="center" width="200">  <a href="#" onclick="order('url'); return false;">URL</a></TD>
        <TD Align="center" width="200"> <a href="#" onclick="order('title'); return false;">Title</a></TD>
        <TD Align="center" width="200"> <a href="#" onclick="order('keywordName'); return false;">Keyword</a></TD>
        <TD Align="center">            <a href="#" onclick="order('bid'); return false;">Bid</a> (min=<%$minBidValue%>)       </TD>
        <TD Align="center" >                Commands        </TD>

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

                <%*<%$links[LinkLoop].urlID%>.*%>
                <a href="<%$selfURL%>?memberMode=url&urlID=<%$links[LinkLoop].urlID%>&cmd=edit"><%$links[LinkLoop].url%></a>
                <%*- <%$links[LinkLoop].urltitle%>.*%>
        </TD>
        <TD align="left">
                <a href="<%$selfURL%>?memberMode=link&linkID=<%$links[LinkLoop].linkID%>&cmd=edit">
                <%$links[LinkLoop].title%> </a>
        </TD>
        <TD align="left">
                <%$links[LinkLoop].keywordName%>
        </TD>
        <TD align="center">
                <%$links[LinkLoop].bid%>
        </TD>

        <TD Align="center" nowrap>
                <A HREF="#"
                    onclick="linksForm.cmd.value=<%if $links[LinkLoop].status == 1%>'deactivate'<%else%>'activate'<%/if%>; 
					linksForm.linkID.value=<%$links[LinkLoop].linkID%>;
					linksForm.submit(); return false;">
				<%if $links[LinkLoop].status == 1%>deactivate<%else%>activate<%/if%>
				</A> |
                <A HREF="#"
                        onClick = "linksForm.linkID.value=<%$links[LinkLoop].linkID%>; linksForm.cmd.value='delete'; if (confirm('Are you sure?')) linksForm.submit();" 
						>delete</A> |
                <A HREF="<%$selfURL%>?memberMode=link&linkID=<%$links[LinkLoop].linkID%>&cmd=edit"
                        >edit</A> |
                <A HREF="<%$selfURL%>?memberMode=links&linkID=<%$links[LinkLoop].linkID%>&memberID=<%$memberID%>&cmd=autobid"
                        >autobid</A> |

        </TD>
</TR>

<%/section%>

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'#a0a0a0'"   >
        <TD Align="left" colspan="9">
        <BR>
         <!--add new link:
         to URL:
            <SELECT name="result[urlID]" STYLE="width:150">
             <OPTION value="0" SELECTED>----------
             <%html_options values=$urlIDs output=$urlTitles selected=0%>
            </SELECT>

            keyword:
            <SELECT name="result[keywordID]" STYLE="width:150">
             <OPTION value="0" SELECTED>-----------
             <%html_options values=$keywordIDs output=$keywordNames selected=0%>
            </SELECT>
            -->

                <input type="button" value="add link" style="width:150"
                onclick="location.href='<%$selfURL%>?memberMode=link&cmd=create&memberID=<%$memberID%>'">
                <a href="<%$selfURL%>?mode=members&memberMode=bulksubm">bulk submission.</a>(upload multiple links)
        </TD>
</TR>        

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:normal;font-family:Verdana;background-color:'#a0a0a0'"   >
    <TD Align="left" colspan="9">
        <div align="left">
         <b>auto bid selected links:</b><br>
			put a maximum amount you want your bid to go to, <br>
			and it would outbid the highest bid by .01 up to the amount	of their maximum bid.
			specify the limit bid value for keywords. (0 - no limit).
        </div> 
         	<!--min bid per keyword: <input type="text" value="<%$minBidValue%>" style="width:50" DISABLED><br>
         	-->
			max bid per keyword:	
			<!--
			<br>
				<input type="radio" name="maxBidType" CHECKED>up to max bid, placed on the keyword by others<br> 
				<input type="radio" name="maxBidType">custom 
			-->
					<input type="text" name="maxBid" value="0" style="width:50" ><br>
		
         <input type="button" value="run" style="width:80" 
		 	onclick="
				document.forms['linksForm'].cmd.value='autobidall';
				document.forms['linksForm'].submit(); 
				return true;">
	</TD>
</TR>        

</TABLE>

</FORM>



<%include file="member.footer.php"%>