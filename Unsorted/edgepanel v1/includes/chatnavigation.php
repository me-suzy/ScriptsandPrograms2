<html>
<head>
<title>Chat Top</title>

<style type="text/css">
<!--
td {  font-family: Verdana; font-size: 11px; color: #383838;line-height: 20px}
a {  color: #4F769E; text-decoration: none}
a:hover {  text-decoration: underline}
.heading { color: #4F769E;font-weight: bold}
INPUT {  font-family: Verdana; font-size: 11px; color: #383838;}
SELECT {  font-family: Verdana; font-size: 11px; color: #383838;}
TEXTAREA {  font-family: Verdana; font-size: 11px; color: #383838;}
.highlight { color: #EE9700;}
.unhighlight { }
-->

</style>
</head>

<script language=Javascript>
function closeWindow () {

	parent.frames["mainFrame"].document.location.href = '../admin/chatlogout.php';
	
}
</script>

<body topmargin=0 leftmargin=0 background=../i/chat-navbar-bg.jpg onUnload=closeWindow()>



<br>
<table width="90%" border="0" cellspacing="0" cellpadding="0" >
                      <tr>
                        <td valign="top" height="27"><span class="heading">&nbsp;&nbsp;&nbsp;<a href="../admin/chatselect.php" target=mainFrame>&raquo; 
                          Home</a></span></td>
                      </tr>
                      <tr>
                        <td valign="top" height="27"><span class="heading">&nbsp;&nbsp;&nbsp;<a href="../admin/selectchat.php" target=mainFrame>&raquo; 
                          Find Chat</a></span></td>
                      </tr>                    
                      <tr>
                        <td valign="top" height="27"><span class="heading">&nbsp;&nbsp;&nbsp;<a href="javascript:closeWindow();">&raquo; 
                          Exit Chat</a></span></td>
                      </tr>                    
                    </table>
</body>
</html>