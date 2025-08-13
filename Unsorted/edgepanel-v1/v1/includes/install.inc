<html>
<head>
<title>ServerInfo&trade; Script Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
td {  font-family: Verdana; font-size: 11px; color: #383838;line-height: 20px}
a {  color: #4F769E; text-decoration: none}
a:hover {  text-decoration: underline}
.heading { color: #4F769E;font-weight: bold}
.sub { color: #4F769E;}
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
            <td><img src="i/admin-header.jpg" width="800" height="100"></td>
          </tr>
          <tr> 
            <td bgcolor="#D5D5D5" style="border-top: 1px solid #999999" height="25">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>&nbsp;&nbsp;<b>You Are Here &raquo;</b> Script Installation</td>
                  <td>
                    <div align="right"></div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
                <tr>
                  <td width="170" height="10" background="i/navbar-bg.jpg" valign="top"><br>
                    {SIDE_NAV} <br>
                  </td>
                  <td height="10" valign="top"> 
                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                      <tr> 
                        <td valign="top"><br>
                          {PAGE_CONTENT}</td>
                        <td valign="top" width="1">&nbsp;</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              
            </td>
          </tr>
          <tr> 
            <td bgcolor="#eeeeee" height="25" style="border-top: 1px dotted #999999">&nbsp;&copy; 
              Copyright 2002 Mark Carruth</td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
