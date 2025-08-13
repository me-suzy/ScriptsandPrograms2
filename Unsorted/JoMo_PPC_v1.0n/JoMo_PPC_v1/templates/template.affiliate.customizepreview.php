<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="affiliate.menu.php"%>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="">
<TR ALIGN="CENTER" colspan="1" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'white'"   >
        <td>
        Preview.<br>
        </td>
</TR>
</table>

<FORM name="f" ACTION="<%$selfURL%>" method="post">

<table width="100%">
<tr>
<td align="center">

<%$customs.header%>

<table width="100%" align="center" cellpadding="3" bgcolor="<%$customs.formcolor%>">
 <tr>
  <td align="left" >
   Search results: (string='test search string'), found: 2 links. time: 0.01 sec.
  </td>
  <td align="right">
   pages: 
   <a href="#"> << </a>
   	<a href="#"> 1 </a> | <a href="#"> 2 </a> | <a href="#"> 3 </a> | 
   <a href="#"> >> </a>
   &nbsp
  </td>
 </tr>
</table>


<table width="100%" align="center" bgcolor="<%$customs.backgroundcolor%>">
  <tr style="color:<%$customs.textcolor%>;">
  <td align="left" bgcolor="<%$customs.evenbackcolor%>" >
     1.
     <span style="font-size:16px;" >
	     <a href="#" style="color:<%$customs.linkcolor%>;"><b>title 1</b></a>
     </span>
     <br>
     <div class="clsLinkDescr" style="font-size:14px; padding:3 0 0 10;">it is description of sample site</div>
     
       <div height="30" style="font-size:11px;padding:3 0 10 10;">
	   	<%if $customs.showURL == 1 %>         
         <a href="#" >www.abcxyz.com</a> 
		<%/if%> 
        
		<%if $customs.showBid == 1 %>         
	         <span class="clsBid">(Advertiser's Max Bid: $0.01)</span>
	     <%/if%>
       </div>
     
   </td>
  </tr>

  <tr style="color:<%$customs.textcolor%>;">
  <td align="left" bgcolor="<%$customs.oddbackcolor%>">
     2.
     <span style="font-size:16px;" >
	     <a href="#" style="color:<%$customs.vlinkcolor%>;"><b>title 2</b></a>
     </span>
     <br>
     <div class="clsLinkDescr" style="font-size:14px; padding:3 0 0 10;">it is second description of sample site</div>
     
       <div height="30" style="font-size:11px;padding:3 0 10 10;">
	   	<%if $customs.showURL == 1 %>         
         <a href="#" >www.defxyz.com</a> 
		<%/if%> 
        
		<%if $customs.showBid == 1 %>         
	         <span class="clsBid">(Advertiser's Max Bid: $0.01)</span>
	     <%/if%>
       </div>
     
   </td>
  </tr>
  
</table>


<%$customs.footer%>

</table>  

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="#cccccc" bordercolor="#666666">
<TR ALIGN="CENTER">
        <TD Align="center" colspan="5">
                <input type="submit" value="cancel" name="" onclick="cmd.value='';f.submit();">
        </TD>
</TR>

</TABLE>

<%section name=ColorLoop loop=$colors%>
	<input type="hidden" name="result[<%$colors[ColorLoop].colorID%>]" value="<%$colors[ColorLoop].value%>">
<%/section%>	

<input type="hidden" name="result[header]" value="<%$customs.header%>">
<input type="hidden" name="result[footer]" value="<%$customs.footer%>">
<input type="hidden" name="result[showURL]" value="<%$customs.showURL%>" >
<input type="hidden" name="result[showBid]" value="<%$customs.showBid%>" >
<input type="hidden" name="result[openLinkInNewWindow]" value="<%$customs.openLinkInNewWindow%>">


<input type="hidden" name="mode" value="affiliates" >
<input type="hidden" name="affMode" value="customize" >

<input type="hidden" name="affiliateID" value="<%$member.affiliateID%>" >
<input type="hidden" name="cmd" value="" >

</form>

<%include file="affiliate.footer.php"%>