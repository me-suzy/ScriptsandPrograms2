<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="member.menu.php"%>
<BR>
<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="250" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b><u>URL Management</u></b><br>
                Add, Edit, and Delete URLs.  If you are listing multiple websites or areas of your website, this is where you manage your URLs to different locations.
        </TD>
</TR>
</TABLE>

<form ACTION="<%$selfURL%>" method="post">
<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="">
<TR ALIGN="CENTER" bgcolor="FF9900" >
        <TD Align="center" width="30">
                ID
        </TD>
        <TD Align="center" width="200">
                URL
        </TD>
        <TD Align="center" width="200">
                Title
        </TD>
        <TD Align="center">
                #links
        </TD>
        <TD Align="center" >
                Created
        </TD>
        <TD Align="center" >
                Modified
        </TD>
        <TD Align="center" >
                Commands
        </TD>

</TR>

<%section name=URLLoop loop=$urls%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.URLLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
        <TD align="center">
                <%$urls[URLLoop].urlID%>
        </TD>
        <TD align="left">
                <a href="<%$selfURL%>?memberMode=url&urlID=<%$urls[URLLoop].urlID%>&cmd=edit"><%$urls[URLLoop].url%></a>
        </TD>
        <TD align="left">
                <%$urls[URLLoop].title%>
        </TD>
        <TD align="center">
                <%$urls[URLLoop].nLinks%>
        </TD>        
        <TD align="center">
                <%$urls[URLLoop].creationDate%>
        </TD>
        <TD align="center">
                <%$urls[URLLoop].modificationDate%>
        </TD>

        <TD Align="center" nowrap>
                <A HREF="<%$selfURL%>?memberMode=urls&urlID=<%$urls[URLLoop].urlID%>&cmd=delete"
                        onClick = "return confirm('Are you sure?'); " >delete</A> |
                <A HREF="<%$selfURL%>?memberMode=url&urlID=<%$urls[URLLoop].urlID%>&cmd=edit"
                        >edit</A> |
                <A HREF="<%$selfURL%>?memberMode=links&urlID=<%$urls[URLLoop].urlID%>&cmd="
                        >links</A> |

        </TD>
</TR>

<%/section%>

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'#909090'"   >
        <TD Align="left" colspan="7">
                <input type="button" value="add new url" style="width:150"
                onclick="location.href='<%$selfURL%>?memberMode=url&cmd=create'">
        </TD>

</TABLE>


<%include file="member.footer.php"%>