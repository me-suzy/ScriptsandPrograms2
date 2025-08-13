<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Banners management.</b>
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
			formBanners.orderby.value=col;
			formBanners.orderdir.value = formBanners.orderdir.value=="DESC"?"ASC":"DESC";			
			formBanners.submit();
	}
</script>


<form name="formBanners" ACTION="<%$selfURL%>" method="post" ENCTYPE="multipart/form-data">

    <input type="hidden" name="mode" value="banners">
    <input type="hidden" name="cmd" value="">
    <input type="hidden" name="itemID" value="0">
    
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
				if banner is disabled then it won't be displayed in search result.<br>
        </TD>
</tr>
</table>


<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
                
            member:
            <SELECT name="memberID" STYLE="width:150">
			 <OPTION value="-1" <%if $memberID == -1%>SELECTED<%/if%>>banner's pool
             <OPTION value="0" <%if $memberID == 0%>SELECTED<%/if%>>all members
			 <OPTION value="-2">--------
             <%html_options values=$memberIDs output=$memberNames selected=$memberID%>
            </SELECT>

<!--            
            url:
            <SELECT name="url" STYLE="width:150">
             <OPTION value="" SELECTED>all urls
             <%html_options values=$urlValues output=$urlNames selected=$url%>
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
   <%$nItems%> banners. 
  </td>
  <td align="right" bgcolor="#cccccc" width="200">
   pages: 
   <%if $prev ne 0%> <a href="#" onclick="formBanners.page.value=<%$prev%>; formBanners.submit(); "> << </a>   <%/if%>
   
   <%section name=Page loop=$pages%>
   	<a href="#" onclick="formBanners.page.value=<%$pages[Page]%>; formBanners.submit(); "
		style="color:<%if $pages[Page] == $page%>red<%else%>black<%/if%>;"> <%$pages[Page]%> </a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   
   <%if $next ne 0%> <a href="#" onclick="formBanners.page.value=<%$next%>; formBanners.submit(); "> >> </a>   <%/if%>

  </td>
 </tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center" width="20">               ID        </TD>
        <TD Align="center">                status        </TD>
        <TD Align="center" width="200">   Member        </TD>
        <TD Align="center" width="150">
  			Banner</TD>
        <TD Align="center" width="200"> <a href="#" onclick="order('url'); return false;">URL</a></TD>
        <TD Align="center" width="180">   Keywords        </TD>
        <TD Align="center">  <a href="#" onclick="order('bid'); return false;">Bid</a></TD>
        <TD Align="center">  <a href="#" onclick="order('creationDate'); return false;">Created</a> </TD>
        <TD Align="center" > Commands        </TD>

</TR>
<%section name=ItemLoop loop=$items%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
        <TD align="center">
                <%$items[ItemLoop].bannerID%>
        </TD>
        <TD align="center">
        	    <%$items[ItemLoop].activity%>
        </TD>
        <TD align="left">
                <%$items[ItemLoop].membername%> 
        </TD>
        <TD align="left">
   				<A HREF="<%$selfURL%>?mode=banner&bannerID=<%$items[ItemLoop].bannerID%>&cmd=edit"
                    ><%$items[ItemLoop].name%></A>
                <%if $items[ItemLoop].isPerImpression == 1%><br>(per impression)<%/if%>
        </TD>        
        <TD align="left">
                <%$items[ItemLoop].url%>
        </TD>
        <TD align="left">
                <%if $items[ItemLoop].keywords == ''%>no keywords<%else%><%$items[ItemLoop].keywords%><%/if%>
        </TD>
        <TD align="center">
                <%if $items[ItemLoop].isPerImpression == 1%><%$bannerImpressionBid%><%else%><%$items[ItemLoop].bid%><%/if%>
        </TD>
        <TD align="center">
                <%$items[ItemLoop].creationDate%>
        </TD>

        <TD Align="center" nowrap>
                <A HREF="#"
                        onclick="formBanners.cmd.value=<%if $items[ItemLoop].adminStatus == 1%>'deactivate'<%else%>'activate'<%/if%>; 
							formBanners.itemID.value=<%$items[ItemLoop].bannerID%>;
							formBanners.submit();">
						<%if $items[ItemLoop].adminStatus == 1%>deactivate<%else%>activate<%/if%>
						</A> |
        		<A HREF="#"
                        onclick="if (!confirm('Are you sure to delete banner?')) return false; 
                            formBanners.cmd.value='delete'; 
							formBanners.itemID.value=<%$items[ItemLoop].bannerID%>;
							formBanners.submit();">
						delete
						</A> |
				<A HREF="<%$selfURL%>?mode=banner&bannerID=<%$items[ItemLoop].bannerID%>&cmd=edit"
                        STYLE="font-size:11px;font-family:Verdana;color:#FF0000">
                        edit
                </A> |						

        </TD>
</TR>

<%/section%>

</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<!--{create-->
<TR ALIGN="CENTER">
        <TD Align="left" colspan="5">
                <input type="button" value="create new banner" name="submitCreate" 	onclick="location.href='<%$selfURL%>?mode=banner&cmd=create'">
        </TD>
</TR>
<!--}create-->
</TABLE>


<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
	<TD>
		<UL>
			<LI><a href="#"
				onclick="if (!confirm('Are you sure?')) return false; formBanners.cmd.value='activateall';formBanners.submit();"> 
				activate all banners</a>
			<LI><a href="#"
				onclick="if (!confirm('Are you sure?')) return false; formBanners.cmd.value='deactivateall';formBanners.submit();"> 
				deactivate all banners</a>		
		</UL>
    </TD>
</TR>


<!--{upload new image-->
<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000">

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;"   >
        <TD Align="left" colspan="5">
                add banner to banner pool:<br>
                file:<input type="file" name="userfile" ><br>
                url:&nbsp<input type="text" name="newurl" value="http://"><br>
                <input type="button" name="submitUpload" value="upload"
					onclick="formBanners.cmd.value='upload';formBanners.submit();">
        </TD>
</TR>
<!--}upload new image-->
</TABLE>


<BR>



</FORM>



<%include file="admin/admin.footer.php"%>