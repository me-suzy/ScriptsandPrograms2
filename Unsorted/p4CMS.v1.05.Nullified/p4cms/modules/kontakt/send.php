<?PHP

include("settings.ini.php");
if($_REQUEST['open']!=1){
$an = $_REQUEST['mailto'];

$mail = "";
reset($_POST);

while (list($key, $val) = each($_POST)) {
	if ($key != "mailto") {
		$mail .= stripslashes($key) . ": " . stripslashes($val) . "\r\n\r\n";
	}
}

@mail($an, "Admin CMS [".date("d.m.y H.s")."]", $mail, "From: Admin CMS <noreply>");
?>
<body onload="thx()">

<script>
<!--
  IE4 = (document.all) ? true : false;
  NS4 = (document.layers) ? true : false;
  xsize = 450; 					
  ysize = 300; 					
  ScreenWidth = screen.width;
  ScreenHeight = screen.height;
  xpos = (ScreenWidth/2)-(xsize/2);		
  ypos = (ScreenHeight/2)-(ysize/2);		
  ver4 = (IE4||NS4);
  if (ver4!=true){  
    function OpenIt(){
alert('Bitte installieren Sie einen Browser mit Support von Javascript 1.2.')
        self.history.back();
        }
    }
  
  if (ver4==true){
    function OpenIt(){
        if (NS4){
            window.moveTo(xpos,ypos)
            window.resizeTo(xsize,ysize)
            }
    
        if (IE4){
            window.moveTo(xpos,ypos)
            window.resizeTo(xsize,ysize)
            }
      }
}

function thx() { 
var winWidth = '350';
var winHeight = '200';
var w = (screen.width - winWidth)/2;
var h = (screen.height - winHeight)/2 - 60;
var url = 'send.php?open=1';
var name = 'admin';
var features = 'scrollbars=no,status=no,location=no,resizable=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
window.open(url,name,features);
window.close();
}
//-->
</script>
<? } if($_REQUEST['open']==1){ ?>
<title><?=$titel;?></title>
<body bgcolor="<?=$hintergrundfarbe;?>" link="#000000" vlink="#000000" alink="#000000" >
<table width="100%" height="100%"  border="0" cellpadding="1" cellspacing="0">
  <tr>
    <td><div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><b><?=$titel;?></b><br>
      <br>
    <?=$danketext;?><br>
    <br>
    <a href="javascript:window.close();"><?=$textschliessen;?></a> </font></div></td>
  </tr>
</table>
</body>
<? } ?>
