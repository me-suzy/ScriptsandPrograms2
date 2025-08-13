<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>FAQ.</b>
        </TD>
</TR>
</TABLE>


<script language="javascript">
	function order(col){
			f.orderby.value=col;
			f.orderdir.value = f.orderdir.value=="DESC"?"ASC":"DESC";			
			f.submit();
	}
</script>

<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="mode" value="faq">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="questionID" value="0">
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
                some help goes here.<br>
        </TD>
</tr>
</table>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
                
            category:
            <SELECT name="category" STYLE="width:150">
             <OPTION value="" SELECTED>all categories
             <%html_options values=$categoryIDs output=$categoryNames selected=$category%>
            </SELECT>
            <input type="submit" value="apply" name="submitSetFilter" style="width:100;">
        </TD>

</TR>
</TABLE>

<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="left" bgcolor="#cccccc">
   <%$nQuestions%> questions. 
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


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center" width="20">               
			<a href="#" onclick="order('questionID'); return false;"> ID  </a>      </TD>
        <TD Align="center" width="150"><a href="#" onclick="order('question'); return false;">Question   </a>     </TD>
        <TD Align="center" width="200">                
			<a href="#" onclick="order('answer'); return false;">Answer   </a>     </TD>
        <TD Align="center" width="150">  
			<a href="#" onclick="order('category'); return false;">Category </a>      </TD>
        <TD Align="center" > Commands        </TD>
</TR>
<%section name=QLoop loop=$questions%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.QLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
        <TD align="center">
                <%$questions[QLoop].questionID%>
        </TD>
        <TD align="left">
        	<a href="<%$selfURL%>?mode=faqquestion&questionID=<%$questions[QLoop].questionID%>&cmd=edit"><%$questions[QLoop].question%></a>
        </TD>
        <TD align="left">
        	<%$questions[QLoop].answer%>
        </TD>
        <TD align="center">
                <%$questions[QLoop].category%>
        </TD>

        <TD Align="center" nowrap>
                <A HREF="#"
                        onClick = "f.questionID.value=<%$questions[QLoop].questionID%>; f.cmd.value='delete'; if (confirm('Are you sure?')) f.submit(); return false;" 
						>delete</A> |
                <A HREF="<%$selfURL%>?mode=faqquestion&questionID=<%$questions[QLoop].questionID%>&cmd=edit">edit</A> |
        </TD>
</TR>

<%/section%>

</TABLE>


<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
	<TD>
        <input type="button" value="add question" style="width:150" 
            onclick="location.href='<%$selfURL%>?mode=faqquestion&cmd=create'" >
        <br>     

            
    </TD>
</TR>
</TABLE>


</FORM>



<%include file="member.footer.php"%>
