<?
if ( ( !isset( $PHP_AUTH_USER )) || (!isset($PHP_AUTH_PW))  
     || ( $PHP_AUTH_USER != 'admin' ) || ( $PHP_AUTH_PW != '123456' ) ) { 

    header( 'WWW-Authenticate: Basic realm="Lizard Cart Admin"' ); 
    header( 'HTTP/1.0 401 Unauthorized' ); 
        echo "<HTML><BODY BGCOLOR=000066 LINK=ffcc00 VLINK=ffcc00 ALINK=ffcc00>
    <DIV ALIGN=center>
    <FONT FACE=arial,verdana SIZE=3 COLOR=ffffff>
    <B>You must have a username and password to enter this page in
    <BR><BR><font size=6><A HREF=\"http://$SERVER_NAME\">$SERVER_NAME</a><B></FONT>
    <BR><BR>Your ip was logged as 
    <BR><FONT COLOR=ffcc00>$REMOTE_ADDR</FONT>
    <BR>Date of attempted entry 
    <BR><FONT COLOR=ffcc00>".$strDate."</FONT>
    <BR>If you feel this is an error please contact 
    <BR><A HREF=\"mailto:$SERVER_ADMIN\">$SERVER_ADMIN</A>
    <BR><BR>Back to <A HREF=\"$HTTP_REFERER\">$HTTP_REFERER</A>";
    exit;
	}
?>