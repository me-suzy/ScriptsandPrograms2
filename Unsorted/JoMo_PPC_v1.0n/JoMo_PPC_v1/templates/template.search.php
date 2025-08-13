<%include file="header.php"%>

<table width="100%" align="center">
 <tr>
  <td><center>
   <%include file="searchbox.php"%>
  </center></td>
 </tr>
</table>

<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="left" bgcolor="#cccccc">
   Search results: (string='<%$str%>'), found: <%$totalLinks%> links. time: <%$searchTime%> sec.
  </td>
  <td align="right" bgcolor="#cccccc">
   pages: 
   <%if $prev ne 0%> <a href="<%$selfURL%>?mode=search&str=<%$str%>&page=<%$prev%>"> << </a>   <%/if%>
   <%section name=Page loop=$pages%>
   	<a href="<%$selfURL%>?mode=search&str=<%$str%>&page=<%$pages[Page]%>"> <%$pages[Page]%> </a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   <%if $next ne 0%> <a href="<%$selfURL%>?mode=search&str=<%$str%>&page=<%$next%>"> >> </a>   <%/if%>
   &nbsp
  </td>
 </tr>
</table>

<%if $istopbanner == 1%>
<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="center" bgcolor="white">
   <a href="<%$selfURL%>?mode=rd&bannerID=<%$topbanner.bannerID%>" target="<%$target%>"> <img src="<%$topbanner.path%>" hspace="0" vspace="0" border="0"> </a>   
  </td>
 </tr> 
</table>
<%/if%>

<table width="100%" align="center" bgcolor="#eeeeee">
 
  <%section name=Link loop=$results%>
  <tr>
  <td align="left" bgcolor="<%if $smarty.section.Link.index is even %>#dddddd<%else%>#e8e8e8<%/if%>">
     <%$indexes[Link]%>.
     <span style="font-size:16px;" >
     
     <%if $results[Link].linkID == 0%>
     	<a href="<%$results[Link].linkURL%>" target="<%$target%>" class="clsALink"><b><%$results[Link].title%></b></a>
     <%else%>
     <a href="<%$selfURL%>?mode=rd&linkID=<%$results[Link].linkID%>&position=<%$indexes[Link]%>" target="<%$target%>" class="clsALink"><b><%$results[Link].title%></b></a>
     <%/if%>
     </span>
     <br>
     <div class="clsLinkDescr" style="font-size:14px; padding:3 0 0 10;"><%$results[Link].description%></div>
     
       <div height="30" style="font-size:11px;padding:3 0 10 10;">
       <%if $results[Link].linkID == 0%>
       		<a href="<%$results[Link].linkURL%>" target="<%$target%>" class="clsALink"><%$results[Link].url%></a> 
       <%else%>
         	<a href="<%$selfURL%>?mode=rd&linkID=<%$results[Link].linkID%>&position=<%$indexes[Link]%>" target="<%$target%>" class="clsALink"><%$results[Link].linkURL%></a> 
        <%/if%>
        
<%if $results[Link].bid > 0 %>         
         <span class="clsBid">(Advertiser's Max Bid: $<%$results[Link].bid%>)</span>
       </div>
     <%/if%>
     
   </td>
  </tr>
  <%sectionelse%>
   <span class="clsNoResults">no items</span>
  <%/section%>

</table>
<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="left" bgcolor="#cccccc">
   Search results: (string='<%$str%>'), found: <%$totalLinks%> links. time: <%$searchTime%> sec.
  </td>
  <td align="right" bgcolor="#cccccc">
   pages: 
   <%if $prev ne 0%> <a href="<%$selfURL%>?mode=search&str=<%$str%>&page=<%$prev%>"> << </a>   <%/if%>
   <%section name=Page loop=$pages%>
   	<a href="<%$selfURL%>?mode=search&str=<%$str%>&page=<%$pages[Page]%>"> <%$pages[Page]%> </a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   <%if $next ne 0%> <a href="<%$selfURL%>?mode=search&str=<%$str%>&page=<%$next%>"> >> </a>   <%/if%>
   &nbsp
  </td>
 </tr>
</table>


<%if $isbottombanner == 1%>
<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="center" bgcolor="white">
   <a href="<%$selfURL%>?mode=rd&bannerID=<%$bottombanner.bannerID%>" target="<%$target%>"
   	<%if $bottombanner.isPerImpression == 1%>onclick="return false;"<%/if%>
	> <img src="<%$bottombanner.path%>" border="0"> </a>   
  </td>
 </tr> 
</table>
<%/if%>

<%include file="footer.php"%>