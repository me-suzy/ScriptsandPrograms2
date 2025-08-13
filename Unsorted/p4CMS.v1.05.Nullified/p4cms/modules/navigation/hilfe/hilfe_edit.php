<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Hilfe - Modul Navigation - bearbeiten&amp;anlegen</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link href="/p4cms/style/style.css" rel="stylesheet" type="text/css">

<script>
<!--
  IE4 = (document.all) ? true : false;
  NS4 = (document.layers) ? true : false;
  xsize = 450; 					
  ysize = 300; 					
  ScreenWidth = screen.width;
  ScreenHeight = screen.height;
  xpos = (ScreenWidth/0)-(xsize/0);		
  ypos = (ScreenHeight/0)-(ysize/0);		
  ver4 = (IE4||NS4);
  if (ver4!=true){  
    function wmove(){
alert('Bitte installieren Sie einen Browser mit Support von Javascript 1.2.')
        self.history.back();
        }
    }
  
  if (ver4==true){
    function wmove(){
        if (NS4){
            window.moveTo(xpos,ypos)
           /// window.resizeTo(xsize,ysize)
            }
    
        if (IE4){
            window.moveTo(xpos,ypos)
            /// window.resizeTo(xsize,ysize)
            }
      }
}
//-->
</script>
</head>

<body onload="wmove();">
<table width="100%"  border="0" cellpadding="5" cellspacing="1" class="boxstandartborder">
  <tr>
    <td class="boxstandart"><table width="100%"  border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td><b>Modul Navigation - bearbeiten&amp;anlegen</b></td>
        <td><div align="right">
  <form name="form1" method="post" action="">
    <input name="Submit" type="button" class="button" onClick="print();" value="Hilfe drucken">
    <input name="Submit" type="button" class="button" onClick="window.close();" value="Fenster schliessen">
  </form>
  <br>
</div></td>
      </tr>
    </table></td>
  </tr>
</table>
<br>
<img src="/p4cms/modules/navigation/hilfe/edit.gif" width="784" height="327" border="0" usemap="#Map">
<map name="Map">
  <area shape="circle" coords="50,30,11" href="#1">
  <area shape="circle" coords="231,30,14" href="#2">
  <area shape="circle" coords="458,29,14" href="#3">
  <area shape="circle" coords="517,30,13" href="#4">
  <area shape="circle" coords="230,125,13" href="#5">
  <area shape="circle" coords="51,227,14" href="#6">
  <area shape="circle" coords="229,295,15" href="#7">
</map>
<br>
<table width="100%"  border="0" cellpadding="2" cellspacing="1" class="boxstandartborder">
  <tr>
    <td class="boxstandart"><b><font color="#CC0000"><a name="1"></a>1.)</font> Position/Reihenfolge</b><br>
      Hier geben Sie die Reihenfolge an, wie die Haupt-Punkte auf der Seite ausgegeben werden sollen. Wert 1 entspricht Position 1.<br>
      <hr size="1" noshade>
      <b><font color="#CC0000"><a name="2"></a>2.)</font> Titel</b><br> 
      Dies ist der Titel des Links. Dieser Text erscheint in Ihrem Men&uuml; als Men&uuml;punkt <br>
      <hr size="1" noshade>
      <b><font color="#CC0000"><a name="3"></a>3.)</font> URL </b><br> 
      Legen Sie hier die Url zu dem Dokument fest, auf das der Link verweisen soll. Es kann neben Links in Form von &quot;/datei.htm&quot; auch Javascript verwendet werden. <br>
      Beachten Sie bitte, dass alle Links mit einem anf&uuml;hrenden Slash &quot;/&quot; beginnen m&uuml;ssen. 
      <hr size="1" noshade>
      <b><font color="#CC0000"><a name="4" id="4"></a>4.)</font> Ziel </b><br> 
      Hier k&ouml;nnen Sie das Ziel des Hyperlinks festlegen. Wenn Sie dieses Feld leer lassen, bezieht sich der Hyperlink auf die gleiche Seite . <br>
      gleiche Seite : <b>_self</b> oder (leer) oder neue Seite : <b>_blank</b> <hr size="1" noshade>
      <b><font color="#CC0000"><a name="5" id="5"></a>5.)</font> Titel Unter-Ebene</b><br> 
      Hier k&ouml;nnen Sie beliebig viele Links anlegen, welche Sie zu einer &uuml;bergeordneten Rubrik hinzuf&uuml;gen m&ouml;chten. 
      <hr size="1" noshade>
      <b><font color="#CC0000"><a name="6" id="6"></a>6.)</font> <b>Position/Reihenfolge</b> Links Unter-Ebene</b><br>
Hier geben Sie die Reihenfolge an, wie die Links einer Haupt-Rubrik auf der Seite ausgegeben werden sollen. Wert 1 entspricht Position 1.
<hr size="1" noshade>
<b><font color="#CC0000"><a name="7" id="7"></a>7.)</font> <b>Neuen Haupt-Link anlegen </b></b><br> 
Felder zum anlegen eines neuen Haupt-Links</td>
  </tr>
</table>

</body>
</html>
