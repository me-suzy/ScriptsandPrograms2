<%include file="header.php"%>
<BR>
<%include file="member.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="350" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b><u>Keyword Management</u></b>
        </TD>
</TR>
<TR ALIGN="left" bgcolor="" ><TD Align="center">
Keywords are what the searches find. You can deactivate Links so that they won't show up in search results.  You can later re-activate them and they will show up in search results again.  You can have as many keywords as you want for each URL and as many URLs as you want.
Be sure your links are set at active if you want them to show up in search results on our site.
</TABLE>


<script language="javascript">
	function order(col){
			f.orderby.value=col;
			f.orderdir.value = f.orderdir.value=="DESC"?"ASC":"DESC";			
			f.submit();
	}
	
	function checkAll(c){
		cc = document.all[c];		 
                                              
        ff = document.all['f'];
        for (i=0; i<ff.elements.length; i++){
            o = ff.elements[i];
            tt = o.type;
            //alert(o.type);
            if (tt=="checkbox"){
                o.checked = cc.checked;
            }
        }
        return;		
        /*
		l = document.all['hcheck'].length;
		
		if (l==null){
		}
		else
		for (i=0;i<document.all['hcheck'].length; i++)		{
			v = document.forms['f'].hcheck(i);
			j = v.value;
			ch = document.all['check['+j+"]"];
			if (ch!=null){
				ch.checked = cc.checked;
			}
		}
		*/
	}
	
</script>

<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="memberMode" value="links">
<input type="hidden" name="linkID" value="">
<input type="hidden" name="cmd" value="">

<input type="hidden" name="orderby" value="<%$orderby%>">
<input type="hidden" name="orderdir" value="<%$orderdir%>">
<input type="hidden" name="page" value="">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER" bgcolor="#eeeeee">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
</table>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
                url:
            <SELECT name="urlID" STYLE="width:250">
             <OPTION value="0" SELECTED>all urls
             <%html_options values=$urlIDs output=$urlTitles selected=$urlID%>
            </SELECT>

            keyword
            <INPUT name="keywordName" TYPE="text" style="width:200;" value="<%$keywordName%>">
			
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
   <%if $prev ne 0%> <a href="#" onclick="f.page.value=<%$prev%>; f.submit(); return false;"> << </a>   <%/if%>
   
   <%section name=Page loop=$pages%>
   	<a href="#" onclick="f.page.value=<%$pages[Page]%>; f.submit(); return false;"
		style="color:<%if $pages[Page] == $page%>red<%else%>black<%/if%>;"> <%$pages[Page]%> </a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   
   <%if $next ne 0%> <a href="#" onclick="f.page.value=<%$next%>; f.submit(); return false;"> >> </a>   <%/if%>

  </td>
 </tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR ALIGN="CENTER" bgcolor="#FFB13E" >
<!--
        <TD Align="center" width="20">   <a href="#" onclick="order('linkID'); return false;">ID</a></TD>
-->        
        <TD Align="center">
	        <input type="checkbox" name="check0" onclick="checkAll('check0');">
        </TD>
        <TD Align="center">                status        </TD>
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
<!--
        <TD align="center">
                <%$links[LinkLoop].linkID%>
        </TD>
-->     
        <TD Align="center">
            <input type="checkbox" name="check[<%$links[LinkLoop].linkID%>]" id="check[<%$links[LinkLoop].linkID%>]" value="<%$links[LinkLoop].linkID%>"></TD>   
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
                    onclick="f.cmd.value=<%if $links[LinkLoop].status == 1%>'deactivate'<%else%>'activate'<%/if%>; 
					f.linkID.value=<%$links[LinkLoop].linkID%>;
					f.submit(); return false;">
				<%if $links[LinkLoop].status == 1%>deactivate<%else%>activate<%/if%>
				</A> |
                <A HREF="#"
                        onClick = "f.linkID.value=<%$links[LinkLoop].linkID%>; f.cmd.value='delete'; if (confirm('Are you sure?')) f.submit(); return false;" 
						>delete</A> |
                <A HREF="<%$selfURL%>?memberMode=link&linkID=<%$links[LinkLoop].linkID%>&cmd=edit"
                        >edit</A> |
                <A HREF="<%$selfURL%>?memberMode=links&linkID=<%$links[LinkLoop].linkID%>&memberID=<%$memberID%>&cmd=autobid"
                        >autobid</A> |

        </TD>
</TR>

<%/section%>

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'#C0C0C0'"   >
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

			<input type="button" value="delete" style="width:80"
                onclick="if (!confirm('Are you sure to delete selected items?')) return false; document.forms['f'].cmd.value='deleteselected'; f.submit();">
           
           <input type="button" value="add link" style="width:150"
                onclick="location.href='<%$selfURL%>?memberMode=link&cmd=create&memberID=<%$memberID%>'">
           <input type="button" value="activate"
           		onclick="if (!confirm('Are you sure to activate selected items?')) return false; document.forms['f'].cmd.value='activateselected'; f.submit();">
           <input type="button" value="deactivate"
           		onclick="if (!confirm('Are you sure to deactivate selected items?')) return false; document.forms['f'].cmd.value='deactivateselected'; f.submit();">
           		<br>

           <a href="<%$selfURL%>?mode=members&memberMode=bulksubm">bulk submission</a> (upload multiple links) <br>
           
           		
        </TD>
</TR>        

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:normal;font-family:Verdana;background-color:'#FFFF99'"   >
    <TD Align="left" colspan="9">
        <div align="left">
         <b><u>Auto Bid</u> selected links:</b><br>
			This feature allows you to automatically outbid any links by .01 up to your maximum amount.
Specifiy your bid below (0 - no limit) (.25 - your max bid is 25¢).
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
				document.forms['f'].cmd.value='autobidall';
				document.forms['f'].submit(); 
				return true;">
	</TD>
</TR>        

</TABLE>

</FORM>



<%include file="member.footer.php"%>