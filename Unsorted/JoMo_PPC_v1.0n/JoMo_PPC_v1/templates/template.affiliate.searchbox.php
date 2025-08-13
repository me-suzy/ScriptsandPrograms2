<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="affiliate.menu.php"%>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="">
<TR ALIGN="CENTER" colspan="1" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'white'"   >
        <td>
        Search boxes.<br>
        
        </td>
</TR>
</table>

<FORM name="f" ACTION="<%$selfURL%>" method="post" ENCTYPE="multipart/form-data">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			You can use these search boxes on your pages.
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


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:lightgreen"   >
        <TD Align="center">active</TD>
		<TD Align="center">per<br> impr.</TD>
        <TD Align="center">name</TD>
        <TD Align="center" width="250">URL</TD>
        <TD Align="center" width="250">keywords</TD>
        <TD Align="center" >bid</TD>
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
                <A HREF="<%$selfURL%>?memberMode=banners&memberID=<%$member.memberID%>&bannerID=<%$banners[BannerLoop].bannerID%>&cmd=view"
                        STYLE="font-size:11px;font-family:Verdana;color:#FF0000">
                        view
                </A> |

        </TD>
</TR>
<%sectionelse%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left" bgcolor="#c8c8c8">
        <TD align="left" colspan="5">
        no banners
        </TD>
</TR>

<%/section%>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" WIDTH="600" background="" bgcolor="#cccccc" bordercolor="#666666">
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

<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000">

<input type="hidden" name="cmd" value="upload" >
<input type="hidden" name="memberID" value="<%$member.memberID%>" >

</form>

<%include file="affiliate/aff.footer.php"%>