<%$customs.header%>

<table width="100%" align="center" cellpadding="3" bgcolor="<%$customs.formcolor%>">
 <tr>
  <td align="left" >
   Search results: (string='<%$str%>'), found: <%$totalLinks%> links. time: <%$searchTime%> sec.
  </td>
  <td align="right">
   pages: 
   <%if $prev ne 0%> <a href="<%$selfURL%>?mode=search&affiliateID=<%$affiliateID%>&format=HTML&str=<%$str%>&page=<%$prev%>"> << </a>   <%/if%>
   <%section name=Page loop=$pages%>
   	<a href="<%$selfURL%>?mode=search&affiliateID=<%$affiliateID%>&format=HTML&str=<%$str%>&page=<%$pages[Page]%>"> <%$pages[Page]%> </a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   <%if $next ne 0%> <a href="<%$selfURL%>?mode=search&affiliateID=<%$affiliateID%>&format=HTML&str=<%$str%>&page=<%$next%>"> >> </a>   <%/if%>
   &nbsp
  </td>
 </tr>
</table>

<table width="100%" align="center" bgcolor="<%$customs.backgroundcolor%>">
 
  <%section name=Link loop=$results%>
  <tr style="color:<%$customs.textcolor%>;">
  <td align="left" bgcolor="<%if $smarty.section.Link.index is even %><%$customs.evenbackcolor%><%else%><%$customs.oddbackcolor%><%/if%>">
     <%$indexes[Link]%>.
     <span style="font-size:16px;" >
     
     <%if $results[Link].linkID == 0%>
     	<a href="<%$results[Link].linkURL%>" target="<%$target%>" 
			class="link" style="color:<%$customs.linkcolor%>;"><b><%$results[Link].title%></b></a>
     <%else%>
	     <a href="<%$selfURL%>?mode=rd&linkID=<%$results[Link].linkID%>&affiliateID=<%$affiliateID%>&position=<%$indexes[Link]%>" target="<%$target%>" class="link" style="color:<%$customs.linkcolor%>;">
		 <b><%$results[Link].title%></b></a>
     <%/if%>
     </span>
     <br>
     <div class="clsLinkDescr" style="font-size:14px; padding:3 0 0 10;"><%$results[Link].description%></div>
     
       <div height="30" style="font-size:11px;padding:3 0 10 10;">
	   <%if $customs.showURL == 1 %> 
	       <%if $results[Link].linkID == 0%>
	       <a href="<%$results[Link].linkURL%>" target="<%$target%>" class="link" style="color:<%$customs.linkcolor%>;"><%$results[Link].linkURL%></a> 
	       <%else%>
	         <a href="<%$selfURL%>?mode=rd&affiliateID=<%$affiliateID%>&linkID=<%$results[Link].linkID%>&position=<%$indexes[Link]%>" target="<%$target%>" class="clsALink" style="color:<%$customs.linkcolor%>;"><%$results[Link].linkURL%></a> 
	        <%/if%>
        <%/if%>			
        
		<%if $customs.showBid == 1 %>         
			 <%if $results[Link].bid > 0 %>         
		         <span class="clsBid">(Advertiser's Max Bid: $<%$results[Link].bid%>)</span>
		     <%/if%>
		<%/if%>
		
	   </div>
     
   </td>
  </tr>
  <%sectionelse%>
   <span class="clsNoResults">no items</span>
  <%/section%>

</table>


<%$customs.footer%>
