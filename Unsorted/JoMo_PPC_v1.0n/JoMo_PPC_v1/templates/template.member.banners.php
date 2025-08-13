<%include file="header.php"%>

<BR>
<%include file="member.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="350" background="">
<TR ALIGN="CENTER" colspan="1" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'white'"   >
        <td>
        </u>Banner Mangement</u>
        </td>
</TR>
<tr><td>We use banners (468x 60 pixels) in our search results.  We give our members the exclusive opportunity to list their banners on our website.  Below you can add, edit, delete, and deactivate banners.  Banners are only active if you have activated them.
</table>

<script language="javascript">
	function order(col){
			formBanners.orderby.value=col;
			formBanners.orderdir.value = formBanners.orderdir.value=="DESC"?"ASC":"DESC";			
			formBanners.submit();
	}
</script>


<FORM name="formBanners" ACTION="<%$selfURL%>" method="post" ENCTYPE="multipart/form-data">

	<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000">
	
	<input type="hidden" name="cmd" value="upload" >
	<input type="hidden" name="memberID" value="<%$member.memberID%>" >
	<input type="hidden" name="bannerID" value="" >    

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
		option "Per Impression" is <%if $bannerPerImpression == 1%>ON.<%else%>OFF - per impression banners won't be 
		displayed in search results. <%/if%>
		per impression bid=$<%$bannerImpressionBid%>
		<br>
		if banner is disabled then it won't be displayed in search results. Only ACTIVE banners will be displayed.<br>
		click on name to view and edit banner.<br>
        </TD>
</tr>
</table>


<%if $cmd=="view" %>
<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" WIDTH="100%" background="" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'#ccffcc'"   >
        <TD Align="center" colspan="5">
        	banner "<%$banner.name%>".
	</TD>
</TR>        	
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'white'"   >
        <TD Align="center" colspan="5">
        <div id="divbanners" align="center">
                <IMG src="<%$banner.path%>"><br>
        </div>
        </TD>
</TR>
</TABLE>
<br>
<%/if%>


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


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:#FFB13E"   >
        <TD Align="center">active</TD>
		<TD Align="center">per<br> impr.</TD>
        <TD Align="center">name</TD>
        <TD Align="center" width="250"> <a href="#" onclick="order('url'); return false;">URL</a></TD>
        <TD Align="center" width="250">keywords</TD>
        <TD Align="center">  <a href="#" onclick="order('bid'); return false;">Bid</a></TD>
        <TD Align="center" >commands </TD>
</TR>

<%section name=BannerLoop loop=$banners%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left"
                bgcolor="<%if $smarty.section.BannerLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>">
        <TD align="left">
        	    <%$banners[BannerLoop].activity%>
        </TD>
        <TD align="center">
        	    <%if $banners[BannerLoop].isPerImpression == 1%>yes<%else%>---<%/if%>
        </TD>
        <TD align="left">
                <A HREF="<%$selfURL%>?memberMode=banner&memberID=<%$member.memberID%>&bannerID=<%$banners[BannerLoop].bannerID%>&cmd=edit"
                        STYLE="font-size:11px;font-family:Verdana;color:#FF0000"><%$banners[BannerLoop].name%></A>
        </TD>
        <TD align="left">
                <%if $banners[BannerLoop].url == ''%>not available<%else%><%$banners[BannerLoop].url%><%/if%>
        </TD>
        <TD align="left">
                <%if $banners[BannerLoop].keywords == ''%>no keywords<%else%><%$banners[BannerLoop].keywords%><%/if%>
        </TD>
        <TD align="center">
			<%if $banners[BannerLoop].isPerImpression == 1%><%$bannerImpressionBid%><%else%><%$banners[BannerLoop].bid%><%/if%>
        </TD>
        <TD Align="center" nowrap>
                <A HREF="<%$selfURL%>?memberMode=banners&memberID=<%$member.memberID%>&bannerID=<%$banners[BannerLoop].bannerID%>&cmd=delete"
                        onClick = "return confirm('Are you sure?')"  STYLE="font-size:11px;font-family:Verdana;color:#FF0000">
                        delete
                </A> |
                <A HREF="<%$selfURL%>?memberMode=banner&memberID=<%$member.memberID%>&bannerID=<%$banners[BannerLoop].bannerID%>&cmd=edit"
                        STYLE="font-size:11px;font-family:Verdana;color:#FF0000">
                        edit
                </A> |
                <A HREF="#" STYLE="font-size:11px;font-family:Verdana;color:#FF0000"
                    onclick="
                    formBanners.cmd.value='view'; 
                    document.all['formBanners'].bannerID.value = <%$banners[BannerLoop].bannerID%>;
                    document.all['formBanners'].memberID.value = <%$member.memberID%>;
                    formBanners.submit(); return false;
                    "
                    >
                        view
                </A> |

        </TD>
</TR>
<%sectionelse%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left" bgcolor="#c8c8c8">
        <TD align="left" colspan="7">
        no banners
        </TD>
</TR>

<%/section%>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="#cccccc" bordercolor="#666666">
<TR ALIGN="CENTER" colspan="1" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;"   >

<!--{create-->
<TR ALIGN="CENTER">
        <TD Align="left" colspan="5">
                <input type="button" value="create new banner" name="submitCreate" onclick="location.href='<%$selfURL%>?mode=members&memberMode=banner&cmd=create'">
        </TD>
</TR>
<!--}create-->

<!--{upload new image-->
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;"   >
        <TD Align="left" colspan="5">
                upload new banner:<br>
                file:<input type="file" name="userfile" ><br>
                url:&nbsp<input type="text" name="url" value="http://"><br>
                <input type="submit" name="submitUpload" value="upload">
        </TD>
</TR>
<!--}upload new image-->
</TABLE>


<BR>


</form>

<%include file="member.footer.php"%>