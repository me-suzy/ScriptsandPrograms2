<%include file="header.php"%>

<hr size="1" color="#999999">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td width="25%" valign="top">
      <div align="left">

<!------Navigation------>

<table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
  <tr>
    <td><img border="0" src="../images/navigation.gif" width="154" height="18"></td>
  </tr>
  <tr>
    <td bordercolor="#CF2525">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td colspan="2" height="25"><font size="2"><b>Advertisers:</b></font></td>
        </tr>
        <tr>
          <td bgcolor="#E9E9E9" width="5%" rowspan="5">&nbsp;</td>
          <td bgcolor="#E9E9E9" width="95%" height="20"><font size="2"><a href="index.php?mode=advertising_options">Advertising
            Options</a></font></td>
        </tr>
        <tr>
          <td width="95%" height="20"><font size="2"><a href="index.php?mode=faq&category=advertiser">Advertising
            F.A.Q.'s</a></font></td>
        </tr>
        <tr>
          <td bgcolor="#E9E9E9" width="95%" height="20"><font size="2"><a href="index.php?mode=members&amp;memberMode=register">Sign
            Up Today</a></font></td>
        </tr>
        <tr>
          <td width="95%" height="20"><font size="2"><a href="index.php?mode=members">Account
            Login</a></font></td>
        </tr>
        <tr>
          <td width="95%" height="10" bgcolor="#E9E9E9"><font size="1" color="#E9E9E9">.</font></td>
        </tr>
        <tr>
          <td bgcolor="#FFFFFF" width="100%" colspan="2" height="25"><font size="2"><b>Affiliates:</b></font></td>
        </tr>
        <tr>
          <td bgcolor="#E9E9E9" width="5%" rowspan="5">&nbsp;</td>
          <td width="95%" bgcolor="#E9E9E9" height="20"><font size="2"><a href="index.php?mode=affiliates_options">How
            It Works</a></font></td>
        </tr>
        <tr>
          <td width="95%" height="20"><font size="2"><a href="index.php?mode=faq&category=affiliate">Program
            F.A.Q.'s</a></font></td>
        </tr>
        <tr>
          <td width="95%" bgcolor="#E9E9E9" height="20"><font size="2"><a href="index.php?mode=affiliates&amp;affMode=register">Sign
            Up Today</a></font></td>
        </tr>
        <tr>
          <td width="95%" height="20"><font size="2"><a href="index.php?mode=affiliates">Account
            Login</a></font></td>
        </tr>
        <tr>
          <td width="95%" bgcolor="#E9E9E9"><font size="1" color="#E9E9E9">.</font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!-------End Navigation------->

<img border="0" src="../images/spacer.gif" width="7" height="4">

<!---------Top Keywords--------->
<%if $viewKeywords%>
<table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
<tr>
<td><img border="0" src="../images/top_searches.gif" width="154" height="18"></td>
</tr>
<tr>
<td bordercolor="#CF2525">
<font size="2">
<%section name=Keyword loop=$keywords%>
<a href="<%$selfURL%>?mode=search&str=<%$keywords[Keyword].keyword%>" ><%$keywords[Keyword].keyword%></a>
<br>
<%/section%>
<%/if%>
            </td>
          </tr>
        </table>
<!-------End Top Keywords------>
      </div>
      <img border="0" src="../images/large_spacer.gif" width="5" height="24">
<!-----------Stats------------->
      <table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
        <tr>
          <td><img border="0" src="../images/site_stats.gif" width="154" height="18"></td>
        </tr>
        <tr>
          <td bordercolor="#CF2525">
<font size="2">
<%if $viewStatistics%>
Searches Today: <font color="red"><%$todaySearches%></font>
<br>
Average Searches/Day: <font color="red"><%$avgDaySearches%></font>
<br>
Searches Last Month: <font color="red"><%$monthSearches%></font>
<br>
Number of Members: <font color="red"><%$members%></font>
<br>
Visitors Online Now: <font color="red"><%$onlineVisitors%></font>
<br>
Number of Bidded Listings: <font color="red"><%$activeLinks%></font>
<br>
<%/if%>
          </td>
        </tr>
      </table></font>

<!---------End Stats---------->
    </td>
    <td width="50%" valign="top">
    <div align="center">

<!--------Search Box---------->

<%include file="searchbox.php"%>

<!------End Search box-------->

      
        <center>

<!------Category Search------->

<table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td valign="top"><img border="0" src="../images/search_categories.gif" width="352" height="18"></td>
          </tr>
          <tr>
            <td bordercolor="#CF2525" valign="top">
              <table>
                <tr>
                  <td><nobr><font face="Arial, sans-serif" size="2"><a href="index.php?mode=search&amp;str=entertainment"><font color="#009900" id="green2"><b>Arts&nbsp;&amp;&nbsp;Entertainment</b></font></a><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=movies">Movies</a>,&nbsp;<a href="index.php?mode=search&amp;str=music">Music</a>,&nbsp;<a href="index.php?mode=search&amp;str=television">Television</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=autos"><font color="#009900" id="green2"><b>Autos</b></font></a><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=auto+news">News</a>,&nbsp;<a href="index.php?mode=search&amp;str=auto+enthusiasts">Enthusiasts</a>,&nbsp;<a href="index.php?mode=search&amp;str=auto+buying">Buying</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=business+money"><font color="#009900" id="green2"><b>Business&nbsp;&amp;&nbsp;Money</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=investing">Investing</a>,&nbsp;<a href="index.php?mode=search&amp;str=jobs">Jobs</a>,&nbsp;<a href="index.php?mode=search&amp;str=business+industries">Industries</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=computers"><font color="#009900" id="green2"><b>Computers&nbsp;&amp;&nbsp;Internet</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=hardware">Hardware</a>,&nbsp;<a href="index.php?mode=search&amp;str=internet">Internet</a>,&nbsp;<a href="index.php?mode=search&amp;str=software">Software</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=games"><font color="#009900" id="green2"><b>Games</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=video+games">Videogames</a>,&nbsp;
                    <a href="index.php?mode=search&amp;str=role-playing+games">Role-Playing</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=health"><font color="#009900" id="green2"><b>Health</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=medicine">Medicine</a>,&nbsp;<a href="index.php?mode=search&amp;str=fitness">Fitness</a>,&nbsp;<a href="index.php?mode=search&amp;str=alternative+health">Alternative</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=news+media"><font color="#009900" id="green2"><b>News&nbsp;&amp;&nbsp;Media</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=online+news">Online</a>,&nbsp;<a href="index.php?mode=search&amp;str=newspapers">Newspapers</a>,&nbsp;<a href="index.php?mode=search&amp;str=weather">Weather</a>&nbsp;</font>...</font></nobr></td>
                  <td rowSpan="3">&nbsp;&nbsp;</td>
                  <td><nobr><font face="Arial, sans-serif" size="2"><a href="index.php?mode=search&amp;str=recreation"><font color="#009900" id="green2"><b>Recreation</b></font></a><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=food">Food</a>,&nbsp;<a href="index.php?mode=search&amp;str=outdoors">Outdoors</a>,&nbsp;<a href="index.php?mode=search&amp;str=humor">Humor</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=reference"><font color="#009900" id="green2"><b>Reference</b></font></a><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=libraries">Libraries</a>,&nbsp;<a href="index.php?mode=search&amp;str=education">Education</a>,&nbsp;<a href="index.php?mode=search&amp;str=maps">Maps</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=regional+interests"><font color="#009900" id="green2"><b>Regional</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=united+states">United
                    States</a>,&nbsp;<a href="index.php?mode=search&amp;str=europe">Europe</a>,&nbsp;<a href="index.php?mode=search&amp;str=asia">Asia</a>&nbsp;</font>...<br>
                    <a href="index.php?mode=search&amp;str=science+technology"><font color="#009900" id="green2"><b>Science&nbsp;&amp;&nbsp;Technology</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=technology">Technology</a>,&nbsp;<a href="index.php?mode=search&amp;str=social+services">Social&nbsp;Sciences</a>&nbsp;
                    </font>...<br>
                    <a href="index.php?mode=search&amp;str=society"><font color="#009900" id="green2"><b>Society</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=government">Government</a>,&nbsp;<a href="index.php?mode=search&amp;str=people">People</a>,&nbsp;<a href="index.php?mode=search&amp;str=religion">Religion</a>&nbsp;
                    </font>...<br>
                    <a href="index.php?mode=search&amp;str=sports"><font color="#009900" id="green2"><b>Sports</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=football">Football</a>,&nbsp;</font></font><font size="1" face="Arial, sans-serif"><a href="index.php?mode=search&amp;str=baseball">Baseball</a></font><font face="Arial, sans-serif" size="2"><font size="1">,&nbsp;<a href="index.php?mode=search&amp;str=basketball">Basketball</a>&nbsp;
                    </font>...<br>
                    <a href="index.php?mode=search&amp;str=travel"><font color="#009900" id="green2"><b>Travel</b></font></a>
                    <font id="smHd" size="4">&nbsp;</font><br>
                    <font size="1"><a href="index.php?mode=search&amp;str=lodging">Lodging</a>,&nbsp;<a href="index.php?mode=search&amp;str=destinations">Destinations</a>,&nbsp;<a href="index.php?mode=search&amp;str=air+travel">Air&nbsp;Travel</a>&nbsp;
                    </font>...</font></nobr></td>
                </table>
              </td>
          </tr>
        </table>
        </center>
      </div>
<!--------End Category Search-------->
    </td>
    <td width="25%" valign="top">
      <div align="center">
        <table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td>
              <p style="margin-top: 0; margin-bottom: 0"><img border="0" src="../images/top_listings.gif" width="154" height="18"></p>
            </td>
          </tr>
          <tr>
            <td bordercolor="#CF2525">
              <p style="margin-top: 0; margin-bottom: 0">
<!--------Top Listings--------->
     	<font size="2">
     	<%section name=Link loop=$results%><font size="2"><a href="<%$selfURL%>?mode=rd&linkID=<%$results[Link].linkID%>&position=<%$smarty.section.Link.index%>" target="<%$target%>" class="clsALink"><b><%$results[Link].title%></b></a></font>
    	<br>
    	<div class="clsLinkDescr" style="font-size:12px; padding:3 0 0 10;"><%$results[Link].description%></div>
    	<%if $results[Link].bid > 0 %>
    	<div height="10" style="font-size:11px;padding:3 0 5 10;">
    	<a href="<%$selfURL%>?mode=rd&linkID=<%$results[Link].linkID%>&position=<%$smarty.section.Link.index%>" target="<%$target%>" class="clsALink"><%$results[Link].linkURL%></a>
    	<span class="clsBid">(Advertiser's Max Bid: $<%$results[Link].bid%>)</span></div>
    	<%/if%>
    	<br>
    	<%/section%>
<!-------End Top Listings------->
              </p>
            </td>
          </tr>
        </table>
      </div>
      <p align="right" style="margin-top: 0; margin-bottom: 0"><img border="0" src="../images/spacer.gif"></p>
      <div align="center">
        <table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td>
              <p style="margin-top: 0; margin-bottom: 0"><img border="0" src="../images/advertisers.gif" width="154" height="18"></p>
            </td>
          </tr>
          <tr>
            <td bordercolor="#CF2525">

<!-------Advertiser login------->

<FORM ACTION="<%$selfURL%>?mode=members" METHOD=POST> <input type="hidden" name="tryLogin" value="1">
<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC">
<TR><TD><TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
<TR><TD ALIGN="CENTER"><TABLE BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#CCCCCC" background="">
<TR BGCOLOR="#DDDDDD"><TD CLASS="NORMALTEXT" align="right"><font size="2">Login:</font>
</TD>                                         <TD align="center"><font size="2"><INPUT NAME="memberLogin" size="12"></font></TD></TR>
<TR BGCOLOR="#DDDDDD"><TD CLASS="NORMALTEXT" align="right"><font size="2">Password:</font>
</TD>
<TD align="center"><font size="2"><INPUT TYPE="PASSWORD" NAME="memberPassword" size="12">
  </font>
</TD></TR>
<TR BGCOLOR="#DDDDDD"><TD COLSPAN="2" ALIGN="CENTER"><font size="2"><INPUT TYPE="SUBMIT" CLASS="MENUBUTTON" NAME="LOGIN" VALUE="LOGIN">
    </font>
</TD></TR>
<TR BGCOLOR="#DDDDDD"><TD COLSPAN="2" ALIGN="CENTER"><A HREF="<%$selfURL%>?mode=members&memberMode=register" ><font size="2">register</font></a>
    <font size="2"> | </font><A HREF="<%$selfURL%>?mode=members&memberMode=forgot" ><font size="2">forgot password?</font></a>
</TD></TR>
    </TABLE>
</TD></TR></TABLE>
</TD></TR></TABLE>

              </td>
          </tr>
        </table></FORM>
<!-------End Advertiser Login------->
      </div>
      <p align="right" style="margin-top: 0; margin-bottom: 0"><img border="0" src="../images/spacer.gif"></p>
      <div align="center">
        <table border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF">
          <tr>
            <td>
              <p style="margin-top: 0; margin-bottom: 0"><img border="0" src="../images/affiliates.gif" width="154" height="18"></td>
          </tr>
          <tr>
            <td bordercolor="#CF2525">

<!--------Affiliate Login-------->

<FORM ACTION="index.php?mode=affiliates" METHOD=POST> <input type="hidden" name="tryLogin" value="1">
<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC">         
<TR><TD><TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
<TR><TD ALIGN="CENTER"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="2" BGCOLOR="#CCCCCC" background="">
<TR BGCOLOR="#DDDDDD"><TD CLASS="NORMALTEXT" align="right"><font size="2">Login:</font>
</TD>
<TD align="center"><INPUT NAME="login" size="12">
</TD></TR>
<TR BGCOLOR="#DDDDDD"><TD CLASS="NORMALTEXT" align="right"><font size="2">Password:</font>
</TD>
<TD align="center"><INPUT TYPE="PASSWORD" NAME="password" size="12">
</TD></TR>
<TR BGCOLOR="#DDDDDD"><TD COLSPAN="2" ALIGN="CENTER"><INPUT TYPE="SUBMIT" CLASS="MENUBUTTON" NAME="LOGIN" VALUE="LOGIN">
</TD></TR>
<TR BGCOLOR="#DDDDDD"><TD COLSPAN="2" ALIGN="CENTER"><A HREF="<%$selfURL%>?mode=affiliates&affMode=register" ><font size="2">register</font></a>
    <font size="2"> | </font><A HREF="<%$selfURL%>?mode=affiliates&affMode=forgot" ><font size="2">forgot password?</font></a>
</TD></TR>
    </TABLE>
</TD></TR></TABLE></TD></TR></TABLE>
              </td>
          </tr>
        </table></FORM>
<!-------End Affiliate Login------->
      </div>
    </td>
  </tr>
</table>



<%include file="footer.php"%>