<?php
    
    include("page.php");
    /*
    echo "        
            <body background='fond.gif' text='ffffff'>
            <link href=\"styles_ie.css\" type=\"text/css\" rel=\"stylesheet\" />
            <br><br>";

    function smbox($topcolor,$title,$content)
    {
        $top = "smtop-" . $topcolor . ".jpg";
        
        $retval = "
            <table width=129 border=0 cellpadding=0 cellspacing=0> 
            <tr>
            <div style='position:absolute'>
            <td background='$top' height=41 class='a'>
            <div style='position:relative; margin-top: -10px; margin-left: -5px'>
            <center>$title</center>
            </div></div>
            
            </td></tr>
            <tr>
            <div style='position:absolute'>
            <td background='smbg.jpg' > 
            <div style='position:relative; margin-top: -8px; margin-left: 5px'>
            $content
            </div></div>
            </td></tr>
            <tr><td background='smbot.jpg' height=36>&nbsp</td></tr>
            </table>
            ";
        
        return $retval;
    }

    function lgbox($title,$content)
    {
        $retval = "
            <table width=511 border=0 cellpadding=0 cellspacing=0> 
            <tr>
            <div style='position:absolute'>
            <td background='lgtop.jpg' height=32 class='a'>
            <div style='position:relative; margin-top: -10px; margin-left: -5px'>
            <center>$title</center>
            </div></div>
            
            </td></tr>
            <tr>
            <div style='position:absolute'>
            <td background='lgbg.jpg'> 
            <div style='position:relative; margin-top: -8px; margin-left: 10px'>
            $content
            </div></div>
            </td></tr>
            <tr><td background='lgbot.jpg' height=38>&nbsp</td></tr>
            </table>
            ";
        return $retval;
    }
    $google = "<!-- Search Google -->
<BR>
<FORM method=GET action=\"http://www.google.com/search\">
<input type=hidden name=ie value=UTF-8>
<input type=hidden name=oe value=UTF-8>
<INPUT TYPE=text name=q size=10 maxlength=255 value=\"\"><BR>
<INPUT type=submit name=btnG VALUE=\"Search\">
</FORM>

<!-- Search Google -->
";

    $worklinks = "
    <A href=\"http://cdptpaex3.adelphia.com/exchange/\">E-mail</A>
    <BR><A href=\"http://cdputotview.adelphia.com/\">IEX</A>
    <BR><A href=\"http://ecenter.adelphia.net/\">eCenter</A>
    <BR><A href=\"http://powertools.adelphia.net/smpweb/index.jsp\">SMP</A>
    <BR><A href=\"http://nemos.dc2.adelphia.net/index.php\">Nemos</A>
    <BR><A href=\"http://oss.adelphia.net/cgi-bin/ldap.cgi\">Ldap</A>
    <BR><A href=\"http://www.oyah.net/\">Oyah</A>";

    echo "<center><img src='banner2.jpg'>
    <table border=0 cellpadding=0 cellspacing=0>
    <tr><td width=129 valign=top>
    
    ";
    echo smbox("blue","Google","$google");
    
    
    echo "</td><td width=549 valign=top><center>";

    echo lgbox("This is a big line","You can eat my anus face asdfasdfasdfasdfasdfa asdfasd fasdfas fasdfas dfasdf asdfasdfasd fasdf asdfa sd fadas dfasdfas dfasdfasd  as dsad fasdfasdf asdas<BR><BR><BR>anus face<<BR><BR>anus face");

    echo "</center><td width=129 valign=top>";
    echo smbox("blue","Work Links","$worklinks");
    echo "</td></tr></table>";*/
?>



    