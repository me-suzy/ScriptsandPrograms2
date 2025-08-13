<%include file="header.php"%>
    
<h1>FAQ</h1>
<OL>
<%section name=c loop=$categories%>
    <LI>
		     <a href="#<%$categories[c]%>"><b><%$categories[c]%></b></a>
<%/section%>
</OL>


<%section name=c loop=$categories%>
    <h2>    <a name="<%$categories[c]%>"><b><%$categories[c]%></b></a> <br> </h2>

    <%section name=q loop=$questions[c]%>
        <span style="font-weight:bold;">
        Q:<%$questions[c][q].question%>
        </span>
        <br>                            
        <span style="font-weight:normal; margin:5 5 40 0;">
        A:<%$questions[c][q].answer%>   
        </span>
        <br>
             
    <%/section%>             
             
<%/section%>

        
        
<%include file="footer.php"%>