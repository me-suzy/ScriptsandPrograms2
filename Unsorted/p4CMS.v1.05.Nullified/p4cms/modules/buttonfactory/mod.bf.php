<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 include("modules/buttonfactory/buttons.def");
?>
	<form name="bf" action="/p4cms/modules/buttonfactory/draw.php" method="post" target="ppane">
	<input type="hidden" name="bbutton" value="">
	<input type="hidden" name="btext" value="">
	<input type="hidden" name="bt" value="0">
	<input type="hidden" name="banti" value="y">
	<input type="hidden" name="bshad" value="n">
	<input type="hidden" name="dosave" value="n">
	<input type="hidden" name="bl" value="0">
	<input type="hidden" name="bn" value="0">
	<input type="hidden" name="bfont" value="verdana.ttf">
	<input type="hidden" name="bsize" value="10">
	<input type="hidden" name="bfarbe" value="#FFFFFF">
	<input type="hidden" name="filename" value="">
	</form>
	<script>
	function ref() {
		document.forms['bf'].submit();
	}
	function dl() {
		var wd = window.open('/p4cms/modules/buttonfactory/draw.php?bbutton=' + document.all.bbutton.value + '&btext=' + document.all.btext.value + '&bt=' + document.all.bt.value + '&banti=' + document.all.banti.value + '&download=y&bshad=' + document.all.bshad.value + '&bl=' +  document.all.bl.value + '&bn=' + document.all.bn.value + '&bfont=' + document.all.bfont.value + '&bsize=' + document.all.bsize.value + '&bfarbe=' + document.all.bfarbe.value.replace('#','%23'));
	}
	function sv() {
		SaveDialogExt('png');
	}
	function ul() {
  		var winWidth = 550;
  		var winHeight = 340;
  		var w = (screen.width - winWidth)/2;
  		var h = (screen.height - winHeight)/2 - 60;
  		var url = 'modules/buttonfactory/upload.php?d4sess=<?=$sessid;?>';
  		var name = 'upbtn';
 		var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
	  	window.open(url,name,features);		
	}
	</script>
     <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

              <tr>
                <td valign="top" class="boxstandart">
                <b>Motiv:</b><br>
                <iframe name="motiv" width="100%" height="130" src="/p4cms/modules/buttonfactory/motive.php"></iframe>
                <br><br>
                <b>Text:</b><br>
                <input type="text" name="bta" value="" style="width:80%;"> <input type="button" onClick="document.all.btext.value=document.all.bta.value;ref();" value=" Okay " class="button">
                <br><br>
                <b>Schriftart / Winkel:</b><br>
                <select name="afont" onChange="document.all.bfont.value=document.all.afont.value;ref();">
                <option value="arial.ttf">Arial</option>
                <option value="verdana.ttf">Verdana</option>
                <option value="trebuc.ttf">Trebuchet</option>
                <option value="cour.ttf">Courier New</option>
                <option value="gara.ttf">Garamond</option>
                <option value="tahoma.ttf">Tahoma</option>
                </select> / <input type="text" size="3" name="an" value="0" onChange="document.all.bn.value=this.value;ref();">
                <br><br>
                <b>Schrifgröße / Position:</b><br>
                <select name="asize" onChange="document.all.bsize.value=document.all.asize.value;ref();">
                <option value="8">8</option>
<option value="9">9</option>
                <option value="10" selected>10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="14">14</option>         
                </select> / <input name="al" value="0" size="3" onChange="document.all.bl.value=this.value;ref();"> x <input name="at" value="0" size="3" onChange="document.all.bt.value=this.value;ref();">  
                <br><br>
                <b>Effekte:</b><br>
                <input type="checkbox" name="aanti" checked onClick="if(this.checked==true) { document.all.banti.value='y' } else { document.all.banti.value='n' } ref();"> Anti Aliasing &nbsp;&nbsp; <input type="checkbox" name="ashad" onClick="if(this.checked==true) { document.all.bshad.value='y' } else { document.all.bshad.value='n' } ref();"> Schatten
                <br><br>
                <b>Schriftfarbe:</b><br>
                			<table height="48" width="100%" border="0" bordercolor="#000000" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
				<tbody>
					<tr>
						<script language="JavaScript">
							var c = new Array();
							c[1] = "FF";
							c[2] = "CC";
							c[3] = "99";
							c[4] = "66";
							c[5] = "33";
							c[6] = "00";
							var d = 0;
							for(i=1; i <=6; i++) {
								if(i > 0) {
									document.write("</tr><tr>"); 
								}
								for(m=1;m <=6;m++) {

									for(n=1;n <=6;n++) {
										d++;
										color = c[i] + c[m] + c[n];
										document.write("<td style=\"cursor:hand;\" onClick=\"document.all.bfarbe.value='#" + color + "';ref();\" bgcolor=\"#"+color+"\" width=6><img src=\"_i3/img/pix.gif\" width=1 height=1  border=0 alt=\"#"+color+"\"></td>");
									}
								}
							}
						</script>
					</tr>
				</tbody>
			</table>            
                </td>
			</tr>
		</table>                	
		<br>
		  <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

              <tr>
                <td valign="top" class="boxstandart">
               <table>
               <tr>
               <td width="50%"><iframe name="ppane" src="about:blank" width="100%" height="80" scrolling="no"></iframe></td>
               <td width="50%"> <input type="button" value=" Herunterladen " onClick="dl();" class="button" style="width:150;"><br> <input type="button" class="button" value=" Speichern " onClick="sv();" style="width:150;"><br> <input type="button" class="button" value=" Motiv hochladen " onClick="ul();" style="width:150;"></td>
               </tr>
               </table>
                 </td>
			</tr>
		</table>                 