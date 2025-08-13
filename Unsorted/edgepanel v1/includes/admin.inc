<html>
<head>
<title>Administrator's Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<script language=Javascript>

function showInfo(text) {

       document.all["infoBox"].innerHTML = text;
       
}

function resetInfo() {

       document.all["infoBox"].innerHTML = "Select a field for more information";
       
}

function confirmDelete(id) {

	if(confirm("Are you sure you wish to delete this theme? \n\nNo further warning will be given, and this cannot be undone.")) {
	
	          document.location.href = "removetheme.php?id="+id;
	          
	}
	
}

function setColor(layer) {

         if(document.all[layer].className == 'highlight') {

                   document.all[layer].className = 'unhighlight';
                   
         }
         else {
         
                   document.all[layer].className = 'highlight';
                   
         }
         
}

</script>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><br><br>
      <div align="center">
        <table width="800" border="0" cellspacing="0" cellpadding="0" style="border: 1px solid #999999">
          <tr> 
            <td><img src="../i/admin-header.jpg" width="800" height="100"></td>
          </tr>
          <tr> 
            <td bgcolor="#D5D5D5" style="border-top: 1px solid #999999" height="25">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;&nbsp;<b>You Are Here &raquo;</b> {ADMIN_PAGE_NAVIGATION}</td>
                  <td>
                    <div align="right">{ADMIN_USER} | &nbsp;<img src="../i/logout.gif" width="10" height="10">&nbsp;&nbsp;<a href="logout.php">Logout</a>&nbsp;&nbsp;</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
                <tr>
                  <td width="170" height="10" background="../i/navbar-bg.jpg" valign="top"><br>
                    <table width="90%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td valign="top" height="27"><span class="heading">&nbsp;&nbsp;&nbsp;<a href="index.php">&raquo; 
                          Home</a></span></td>
                      </tr>
                      <tr>
                        <td><span class="heading">&nbsp;&nbsp; &raquo; Support 
                          System</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="support.php">+ Help Desk (Tickets)</a></td>
                      </tr>                    
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="livechat.php">+ Live Chat</a></td>
                      </tr>                      
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="supportfields.php">+ Manage 
                          Fields</a></td>
                      </tr>
                      <tr>
                        <td valign="top" height="27">&nbsp;&nbsp;&nbsp;<a href="supportcategories.php">+ 
                          Manage Categories</a></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<span class="heading">&raquo; Servers</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="servernews.php">+ Manage 
                          News</a></td>
                      </tr>
                      <tr>
                        <td valign="top" height="27">&nbsp;&nbsp;&nbsp;<a href="servers.php">+ 
                          Manage Servers</a> </td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<span class="heading">&raquo; News</span></td>
                      </tr>
                      <tr>
                        <td valign="top" height="27">&nbsp;&nbsp;&nbsp;<a href="news.php">+ 
                          Manage News</a></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;<span class="heading">&nbsp;&raquo; Users</span></td>
                      </tr>
                      <tr>
                        <td valign="top">&nbsp;&nbsp;&nbsp;<a href="useradmins.php">+ Manage 
                          Admins</a></td>
                      </tr>
                      <tr>
                        <td valign="top" height="27">&nbsp;&nbsp;&nbsp;<a href="users.php">+ Manage 
                          Users/Clients</a></td>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<span class="heading">&raquo; Configuration</span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="config.php">+ Script Configuration</a></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="update.php">+ Check for 
                          Update</a></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="defaults.php">+ Script Defaults</a></td>
                      </tr>
                      <tr>
                        <td>&nbsp;&nbsp;&nbsp;<a href="cleanup.php">+ Cleanup Database</a></td>
                      </tr>                      
                    </table>
                    <br>
                  </td>
                  <td height="10" valign="top"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" height="100%">
                      <tr> 
                        <td valign="top"><br>
                          {PAGE_CONTENT}<br>
                          <br>
                        </td>
                        <td valign="top" width="1">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td valign="top" colspan="2" height="37"> 
                          <div align="right"><img src="../i/admin-logo.jpg" width="216" height="37"></div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              
            </td>
          </tr>
          <tr> 
            <td bgcolor="#eeeeee" height="25" style="border-top: 1px dotted #999999">&nbsp;&copy; 
              Copyright 2002 Edge Scripting. All Rights Reserved</td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
