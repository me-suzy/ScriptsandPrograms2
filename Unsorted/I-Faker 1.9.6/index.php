<?
srand((double)microtime()*1000000);
do{$tFile="t".rand(0,999999).".tmp";}while(file_exists("temp/".$tFile));
$msg="";
function post($value){return htmlspecialchars(trim(urldecode(@$_POST[$value])));}
function ispost($value){return (strlen(trim(urldecode(@$_POST[$value])))!=0)?true:false;}

if(ispost("sbSend")){
  switch(post("sbSend")){
  case "Upload":
    if(is_uploaded_file($_FILES["file"]["tmp_name"])){
	  if(move_uploaded_file($_FILES["file"]["tmp_name"],"proxy/".$_FILES["file"]["name"])){	   
	    $msg="File ".$_FILES["file"]["name"]." uploaded";	   
	  }else{
	    $msg="File ".$_FILES["file"]["name"]." not uploaded";
	  }
	}
  break;
  case "Delete":
    if(ispost("slProxyList")){
      if(unlink("proxy/".post("slProxyList")))
        $msg="File ".post("slProxyList")." deleted";
	  }else{
	    $msg="File ".post("slProxyList")." not deleted";
	  }
  break;
  case "Generate users":
    if(ispost("edUrl")){
      $res=post("edUrl")."@@".(ispost("edRef")?post("edRef"):"0")."@@".post("slProxy")."@@".(isset($_POST["chWait"])?"1":"0")."@@".(ispost("edPerH")?post("edPerH"):"0")."@@".(ispost("edMax")?post("edMax"):"0");
	  $file=fopen("temp/".$tFile,"a");
	  if($file){
	    fputs($file,$res."\n");		
	    fclose($file);
		$tt="http://www.i-faker.com/task.php?tmp=".$tFile;
		$msg = "Generation users. Results see in new window.";
	  }
    }else{
      $msg="Url is not indicated";
    }
  break;
  }
}
?>
<html>
<head>
<title>I-FAKER advanced fake traffic </title>
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript">
<!--
function breakAll(){
document.mForm.edTest.value="break";
document.mForm.edRef.value="break";
document.mForm.edProxy.value="break";
document.mForm.sbb.Submit();
//alert();
//parent.set.location.href="set.php?var="+document.mForm.edTest.value+"@@"+document.mForm.edRef.value+"@@"+document.mForm.edProxy.value;
}
<?
if(isset($tt)){
  printf("var v=window.open(\"".$tt."\");\n");
}
?>
-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body background="img/background.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<TABLE width="570" align="center" cellpadding="0" cellspacing="0">
  <TR> 
    <TD align=middle><img src="img/top.jpg" width="600" height="32" border="0"></TD>
  </TR>
  <TR> 
    <TD align=middle background="img/tbl_back.jpg"><div align="center">
        <p><font size="1">Message: <?printf($msg);?></font></p>
        <p>&nbsp;</p>
      </div></TD>
  </TR>
  <TR> 
    <TD background="img/tbl_back.jpg"><form name="mForm" enctype="multipart/form-data" method="post" >
        <TABLE cellSpacing=1 cellPadding=2 width="100%" align=center border=0>
          <TR> 
            <TD colspan="2" align=right><img src="img/01_url.gif" width="160" height="15"></TD>
            <TD><INPUT class="inputs" size=75 name=edUrl></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right><img src="img/02_referrer.gif" width="160" height="15"></TD>
            <TD><INPUT class="inputs" size=75 name=edRef value=""></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right nowrap><img src="img/03_waitnotify.gif" width="160" height="15"></TD>
            <TD><INPUT class="inputs" type=checkbox value="" name=chWait></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right><img src="img/04_listip.gif" width="160" height="15"></TD>
            <TD><SELECT class="inputs" name=slProxy>
                <?$h=opendir("proxy"); while(FALSE!=($file=readdir($h))){if($file!="." && $file!=".."){echo "<option value=\"".$file."\">".$file."</option>";}}?>
              </SELECT></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right><img src="img/05_usershour.gif" width="160" height="15"></TD>
            <TD><INPUT class="inputs" name=edPerH size="6" maxlength="6"></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right><img src="img/06_maxusers.gif" width="160" height="15"></TD>
            <TD><INPUT class="inputs" name=edMax size="6" maxlength="6"></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="3"></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD colspan="3"><div align="center"> 
                <INPUT class="inputs" onclick="addTask();" type=submit value="Generate users" name=sbSend>
              </div></TD>
            <TD></TD>
          </TR>
          <TR> 
            <TD></TD>
            <TD width="10"></TD>
            <TD></TD>
            <TD></TD>
          </TR>
        </TABLE>
      </form></TD>
  </TR>
  <TR> 
    <TD background="img/tbl_back.jpg"> 
      <hr width="550">
    </TD>
  </TR>
  <TR> 
    <TD height="70" background="img/tbl_back.jpg">
        <TABLE cellSpacing=1 cellPadding=2 width="100%" border=0><FORM id=frmAdd name=frmAdd action="" method=post encType=multipart/form-data>
          <TR> 
            <TD align=right><img src="img/04_listip.gif" width="160" height="15"></TD>
            <TD><SELECT class="inputs" name=slProxyList>
                <?$h=opendir("proxy"); while(FALSE!=($file=readdir($h))){if($file!="." && $file!=".."){echo "<option value=\"".$file."\">".$file."</option>";}}?>
              </SELECT></TD>
          </TR>
          <TR> 
            <TD align=right nowrap><img src="img/08_upload.gif" width="160" height="15"></TD>
            <TD><INPUT name=file type=file class="inputs" size="40"></TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right>&nbsp;</TD>
          </TR>
          <TR> 
            <TD colspan="2" align=right><div align="center"> 
                <INPUT class="inputs" type=submit value=Delete name=sbSend">
                <INPUT class="inputs" type=submit value=Upload name=sbSend">
              </div></TD>
          </TR>
        </TABLE></form>
      </TD>
  </TR>
  <TR> 
    <TD background="img/tbl_back.jpg"><hr width="550"></TD>
  </TR>
  <TR>
    <TD><img src="img/bottom.jpg" width="600" height="57"></TD>
  </TR>
</TABLE>
</body>
</html>
