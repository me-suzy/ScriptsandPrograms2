<?php
// Instant Protect by Eugene Zossimov: rossos@avacom.net
// Thank you for using Instant Protect
//
// USAGE:
// CHMOD desired directory to 777
// Upload this file to the directory you just "CHMODED"...
// Call the script: http://www.yourdomain.com/your_yet_unprotected_directory/protect.php
// After protecting your directory, CHMOD it to 755 if you want...


if ($submit)
{
$username = $_POST["user"];
$password = $_POST["pass"];
$authname = $_POST["authname"];
$file1 = ".htpasswd";
$file2 = ".htaccess";
$curdir = getcwd();
$md1945 = crypt($password);
$htpasswd = "$username:$md1945";
$htaccess = "AuthType Basic\r\n";
$htaccess .= "AuthUserFile $curdir/.htpasswd\r\n";
$htaccess .= "AuthGroupFile /dev/null\r\n";
$htaccess .= "AuthName ".$authname."\r\n";
$htaccess .= "Require valid-user\r\n";

$fp = fopen($file1, "w") or die("Couldn't open HTPASSWD for writing!");
$numBytes = fwrite($fp, $htpasswd) or die("Couldn't create file!");

fclose($fp);

$fp2 = fopen($file2, "w") or die("Couldn't open HTACCESS for writing!");
$numBytes2 = fwrite($fp2, $htaccess) or die("Couldn't create file!");

fclose($fp2); ?>
<div align="center">
  <p>&nbsp;</p>
  <p><font color="#FF0000" size="3" face="Arial, Helvetica, sans-serif"><strong>YOUR
    DIRECTORY IS NOW PROTECTED</strong></font></p>
</div>

<?php
}

?>

<html>
<head>
<title>INSTANT PROTECT</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) {
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function instantprotectval() {
  var args = instantprotectval.arguments; var myDot=true; var myV=''; var myErr='';var addErr=false;var myReq;
  for (var i=1; i<args.length;i=i+4){
    if (args[i+1].charAt(0)=='#'){myReq=true; args[i+1]=args[i+1].substring(1);}else{myReq=false}
    var myObj = MM_findObj(args[i].replace(/\[\d+\]/ig,""));
    myV=myObj.value;
    if (myObj.type=='text'||myObj.type=='password'||myObj.type=='hidden'){
      if (myReq&&myObj.value.length==0){addErr=true}
      if ((myV.length>0)&&(args[i+2]==1)){ //fromto
        var myMa=args[i+1].split('_');if(isNaN(parseInt(myV))||myV<myMa[0]/1||myV > myMa[1]/1){addErr=true}
      } else if ((myV.length>0)&&(args[i+2]==2)){
          var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-z]{2,4}$");if(!rx.test(myV))addErr=true;
      } else if ((myV.length>0)&&(args[i+2]==3)){ // date
        var myMa=args[i+1].split("#"); var myAt=myV.match(myMa[0]);
        if(myAt){
          var myD=(myAt[myMa[1]])?myAt[myMa[1]]:1; var myM=myAt[myMa[2]]-1; var myY=myAt[myMa[3]];
          var myDate=new Date(myY,myM,myD);
          if(myDate.getFullYear()!=myY||myDate.getDate()!=myD||myDate.getMonth()!=myM){addErr=true};
        }else{addErr=true}
      } else if ((myV.length>0)&&(args[i+2]==4)){ // time
        var myMa=args[i+1].split("#"); var myAt=myV.match(myMa[0]);if(!myAt){addErr=true}
      } else if (myV.length>0&&args[i+2]==5){ // check this 2
            var myObj1 = MM_findObj(args[i+1].replace(/\[\d+\]/ig,""));
            if(myObj1.length)myObj1=myObj1[args[i+1].replace(/(.*\[)|(\].*)/ig,"")];
            if(!myObj1.checked){addErr=true}
      } else if (myV.length>0&&args[i+2]==6){ // the same
            var myObj1 = MM_findObj(args[i+1]);
            if(myV!=myObj1.value){addErr=true}
      }
    } else
    if (!myObj.type&&myObj.length>0&&myObj[0].type=='radio'){
          var myTest = args[i].match(/(.*)\[(\d+)\].*/i);
          var myObj1=(myObj.length>1)?myObj[myTest[2]]:myObj;
      if (args[i+2]==1&&myObj1&&myObj1.checked&&MM_findObj(args[i+1]).value.length/1==0){addErr=true}
      if (args[i+2]==2){
        var myDot=false;
        for(var j=0;j<myObj.length;j++){myDot=myDot||myObj[j].checked}
        if(!myDot){myErr+='* ' +args[i+3]+'\n'}
      }
    } else if (myObj.type=='checkbox'){
      if(args[i+2]==1&&myObj.checked==false){addErr=true}
      if(args[i+2]==2&&myObj.checked&&MM_findObj(args[i+1]).value.length/1==0){addErr=true}
    } else if (myObj.type=='select-one'||myObj.type=='select-multiple'){
      if(args[i+2]==1&&myObj.selectedIndex/1==0){addErr=true}
    }else if (myObj.type=='textarea'){
      if(myV.length<args[i+1]){addErr=true}
    }
    if (addErr){myErr+='* '+args[i+3]+'\n'; addErr=false}
  }
  if (myErr!=''){alert('The required information is incomplete or contains errors:\t\t\t\t\t\n\n'+myErr)}
  document.MM_returnValue = (myErr=='');
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#CCCCCC"><form name="form1" method="post" action="protect.php">
              <p align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">INSTANT
                PROTECT</font></strong></p>
              <table width="280" border="0" align="center" cellpadding="2" cellspacing="0">
                <tr>
                  <td width="101"><div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">User:</font></div></td>
                  <td width="99"> <div align="left"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
                      <input name="user" type="text" id="user" size="10">
                      </font></div></td>
                </tr>
                <tr>
                  <td><div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Pass:</font></div></td>
                  <td> <div align="left"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
                      <input name="pass" type="text" id="pass" size="10">
                      </font></div></td>
                </tr>
                <tr>
                  <td><div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Site
                      Name:</font></div></td>
                  <td> <div align="left"> <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
                      <input name="authname" type="text" id="authname" size="10">
                      </font></div></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="submit" type="submit" id="submit" onClick="instantprotectval('form1','user','#q','0','Please enter username','pass','#q','0','Please enter password','authname','#q','0','Please enter site name.');return document.MM_returnValue" value="Protect Now!"></td>
                </tr>
              </table>
            </form></td>
        </tr>
        <tr>
          <td><div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">&copy;2003 Eugene Zossimov |
              <a href="http://www.ezrhythm.com">www.ezrhythm.com</a></font></div></td>
        </tr>
      </table></td>
  </tr>
</table>
<div align="center">
  <p>&nbsp;</p>

</div>
</body>
</html>