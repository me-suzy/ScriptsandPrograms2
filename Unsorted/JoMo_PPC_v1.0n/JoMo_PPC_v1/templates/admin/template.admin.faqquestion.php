<%include file="admin/admin.header.php"%>
<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<DIV ALIGN="CENTER">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>
                <%if $cmd eq 'create'%>                Create new question.
                <%else%>        Edit question.
                <%/if%>
        </H3>
</TD>
</TR>
</TABLE>


<script>
 function onFormSubmit(){
    /*
	if (f.all["result[title]"].value==""){
		alert("Please specify title.");
		return false;
	} 
    */   
    
    c = document.forms['f'].checkCategory.checked;
        
    if (c){                                   
        document.all["result[category]"].value=document.all["newCategory"].value;
    }
    else{
        document.all["result[category]"].value=document.all["category"].value;
    }     
    
    if (f.all["result[category]"].value==""){
		alert("Please specify category.");
		return false;
	} 
    
    return true;
 }         
 
 function onChangeCategory(){
    c = document.forms['f'].checkCategory.checked;
    //f = document.forms['f'];
    document.all["newCategory"].disabled=true;
    document.all["category"].disabled=true;
        
    if (c){                                   
        document.all["newCategory"].disabled=false;
        document.all["result[category]"].value=document.all["newCategory"].value;
        
    }
    else{
        document.all["category"].disabled=false;                      
        document.all["result[category]"].value=document.all["category"].value;
    }
    
    //alert(c);
    
 }

</script>

<form name="f" action="<%$selfURL%>" method="post" onsubmit="return onFormSubmit();">

    <input type="hidden" name = "mode" value="faqquestion">   
    <input type="hidden" name = "result[questionID]" value="<%$question.questionID%>">
    <input type="hidden" name = "result[category]" value="<%$question.category%>">
    <input type="hidden" name = "cmd" value=<%if $cmd == 'edit'%>"signupedit"<%else%>"signupcreate"<%/if%>>

        

<table border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0" bordercolor="black">

 <tr valign="top">
  <td align="right">category:</td>
  <td>                      
        <SELECT name="category" STYLE="width:150">
         <%html_options values=$categoryIDs output=$categoryNames selected=$question.category%>
        </SELECT><br>
        <input type="checkbox" name="checkCategory" onclick="onChangeCategory();">other
        <input type="text" ID="newCategory" name="newCategory" DISABLED style="width:150">
  </td>
 </tr>

 <tr valign="top">
  <td align="right">question:</td>
  <td><textarea name="result[question]" rows="5" cols="50"><%$question.question%></textarea></td>
 </tr>

 <tr valign="top">
  <td align="right">answer:</td>
  <td><textarea name="result[answer]" rows="10" cols="50"><%$question.answer%></textarea></td>
 </tr>

 <tr>
  <td colspan="2" align="center">

		<input type="submit" value="<%if $cmd eq 'create'%>create<%else%>update<%/if%> question">
        <input type="reset" value="reset">
        <input type="button" value="cancel" onclick="location.href='<%$selfURL%>?mode=faqquestion&cmd=cancel'">
                
  </td>

 </tr>
</table>



</form>

</DIV>

<%include file="admin/admin.footer.php"%>