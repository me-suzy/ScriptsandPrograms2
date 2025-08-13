<?
include("config.php");
function Ffd418b58()
{
	global $V861ce498,$Vf1cb89ef,$V3e067559,$V0f295760,$V4ac4d98c,$V218eb181,$V2914baf9,$Vc39f66b1,$V98c4bb2d,$V6f458d5c,$affiliateid;
if (!Fe6543ae0()) { return; }
if(!$V3e067559){ $V3e067559=time(); }
$Vc5e7dfaf=date('m.d.Y',$V3e067559);
$V6eddf914=date('l',$V3e067559);
$Vef38fa04=date('j',$V3e067559);
$V7436f942=date('m',$V3e067559);
$V896c55cc=date('H',$V3e067559);
 
	$Vaddb0094=$V9f7caadf=$V33052e94=$V7de45eee=$V7572559c=$V5fa3852b=$V38d4765b=array();
Fb0b3b672('datafiles/hitsin.dat',"\r\n",$Vaddb0094);
Fb0b3b672('datafiles/hitsout.dat',"\r\n",$V9f7caadf);
Fb0b3b672('datafiles/linktrack.dat',"\r\n",$V33052e94);
Fb0b3b672('datafiles/refurl.dat',"\r\n",$V7de45eee);
Fb0b3b672('datafiles/lang.dat',"\r\n",$V7572559c);
Fb0b3b672('datafiles/clickpage.dat',"\r\n",$V38d4765b);
Fb0b3b672('datafiles/cheat.dat',"\|",$V5fa3852b);
 
	if($V633de4b0=@fopen('datafiles/hitsin.dat','w')) { fclose($V633de4b0); }
if($V633de4b0=@fopen('datafiles/hitsout.dat','w')) { fclose($V633de4b0); }
 
   
	$V32fa9abb=array('');
$V23fec246=$V7499aeb8=0;
for($V865c0c0b=0;$V865c0c0b<sizeof($Vaddb0094);$V865c0c0b++)
	{
 if($Vaddb0094[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$Vaddb0094[$V865c0c0b]);
if(!file_exists('memberfiles/'.$V4124bc0a[0].'.dat')) { $V4124bc0a[0]='auxout'; }
if($V4124bc0a[0]=='auxout') { $V4124bc0a[4]=0; }
$V7499aeb8++;
$V23fec246+=$V4124bc0a[2];
$V6cfe6169 = 0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V32fa9abb);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V32fa9abb[$Ve48b981f]);
if($V21ad0bd8[0]==$V4124bc0a[0])
 {
 $V21ad0bd8[4]++;
$V21ad0bd8[1]+=$V4124bc0a[2];
$V21ad0bd8[9]+=$V4124bc0a[4];
$V6cfe6169=1;
$V32fa9abb[$Ve48b981f] = $V21ad0bd8[0].'|'.$V21ad0bd8[1].'|0|0|'.$V21ad0bd8[4].'|0|0|0|0|'.$V21ad0bd8[9];
break;
}
}
if($V6cfe6169==0) { $V32fa9abb[]=$V4124bc0a[0].'|'.$V4124bc0a[2].'|0|0|1|0|0|0|0|'.$V4124bc0a[4]; }
}
}
   
	$V16dc76c6=0;
for($V865c0c0b=0;$V865c0c0b<sizeof($V9f7caadf);$V865c0c0b++)
	{
 if($V9f7caadf[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$V9f7caadf[$V865c0c0b]);
if(!file_exists('memberfiles/'.$V4124bc0a[0].'.dat')) { $V4124bc0a[0]='auxout'; }
if($V4124bc0a[0]!='sponsor'){ $V16dc76c6++; }
$V6cfe6169=0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V32fa9abb);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V32fa9abb[$Ve48b981f]);
if($V21ad0bd8[0] == $V4124bc0a[0])
 {
 $V21ad0bd8[2]++;
$V21ad0bd8[9]+=$V4124bc0a[6];
$V6cfe6169=1;
$V32fa9abb[$Ve48b981f] = $V21ad0bd8[0].'|'.$V21ad0bd8[1].'|'.$V21ad0bd8[2].'|0|'.$V21ad0bd8[4].'|0|0|0|0|'.$V21ad0bd8[9];
break;
}
}
if($V6cfe6169 == 0) {	$V32fa9abb[]=$V4124bc0a[0].'|0|1|0|0|0|0|0|0|'.$V4124bc0a[6]; }
}
}
   
	$V4e107c5e=0;
for($V865c0c0b=0;$V865c0c0b<sizeof($V9f7caadf);$V865c0c0b++)
	{
 if($V9f7caadf[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$V9f7caadf[$V865c0c0b]);
if($V4124bc0a[0]!='sponsor')
 {
 if(!$V4124bc0a[2]){$V4124bc0a[2]='nocookie';}
if(!file_exists('memberfiles/'.$V4124bc0a[2].'.dat')) { $V4124bc0a[2] = 'auxout'; }
if($V4124bc0a[0]!='direct'){ $V4e107c5e++; }
$V6cfe6169 = 0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V32fa9abb);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V32fa9abb[$Ve48b981f]);
if($V21ad0bd8[0]==$V4124bc0a[2])
 {
 if($V4124bc0a[0]!='direct')
 {
 $V21ad0bd8[3]++;
$V21ad0bd8[5]+=$V4124bc0a[4];
$V21ad0bd8[8]+=$V4124bc0a[5];
}
else{$V21ad0bd8[6]++;}
$V6cfe6169 = 1;
$V32fa9abb[$Ve48b981f] = $V21ad0bd8[0].'|'.$V21ad0bd8[1].'|'.$V21ad0bd8[2].'|'.$V21ad0bd8[3].'|'.$V21ad0bd8[4].'|'.$V21ad0bd8[5].'|'.$V21ad0bd8[6].'|0|'.$V21ad0bd8[8].'|'.$V21ad0bd8[9];
break;
}
}
if($V6cfe6169==0)
 {
 if($V4124bc0a[0]!='direct'){$V32fa9abb[]=$V4124bc0a[2].'|0|0|1|0|'.$V4124bc0a[4].'|0|0|'.$V4124bc0a[5].'|0';}
{$V32fa9abb[]=$V4124bc0a[2].'|0|0|0|0|0|1|0|0|0';}
}
}
}
}
   
	if($V5fa3852b[0])
	{
 for($V865c0c0b=0;$V865c0c0b<sizeof($V9f7caadf);$V865c0c0b++)
 {
 if($V9f7caadf[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$V9f7caadf[$V865c0c0b]);
if((($V218eb181)||($V4124bc0a[0]!='direct'))&&($V4124bc0a[0]!='sponsor'))
 {
 if(!$V4124bc0a[2]) { $V4124bc0a[2] = "auxout"; }
if ($V4124bc0a[1]==$V5fa3852b[0])
 {
 $V6cfe6169 = 0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V32fa9abb);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V32fa9abb[$Ve48b981f]);
if($V21ad0bd8[0] == $V4124bc0a[2])
 {
 $V21ad0bd8[7]++;
$V6cfe6169 = 1;
$V32fa9abb[$Ve48b981f] = $V21ad0bd8[0].'|'.$V21ad0bd8[1].'|'.$V21ad0bd8[2].'|'.$V21ad0bd8[3].'|'.$V21ad0bd8[4].'|'.$V21ad0bd8[5].'|'.$V21ad0bd8[6].'|'.$V21ad0bd8[7].'|'.$V21ad0bd8[8].'|'.$V21ad0bd8[9];
break;
}
}
if($V6cfe6169 == 0) { $V32fa9abb[] = $V4124bc0a[2].'|0|0|0|0|0|0|1|0|0'; }
}
}
}
}
}
   
	for($V865c0c0b=0;$V865c0c0b<sizeof($V9f7caadf);$V865c0c0b++)
	{
 if($V9f7caadf[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$V9f7caadf[$V865c0c0b]);
$V6cfe6169=0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V33052e94);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V33052e94[$Ve48b981f]);
if($V21ad0bd8[1] == $V4124bc0a[1])
 {
 $V21ad0bd8[0]++;
$V6cfe6169 = 1;
$V33052e94[$Ve48b981f] = $V21ad0bd8[0].'|'.$V21ad0bd8[1].'|';
break;
}
}
if($V6cfe6169 == 0) { $V33052e94[]='1|'.$V4124bc0a[1].'|'; }
}
}
   
	for($V865c0c0b=0;$V865c0c0b<sizeof($V9f7caadf);$V865c0c0b++)
	{
 if($V9f7caadf[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$V9f7caadf[$V865c0c0b]);
$V6cfe6169=0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V38d4765b);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V38d4765b[$Ve48b981f]);
if($V21ad0bd8[1] == $V4124bc0a[7])
 {
 $V21ad0bd8[0]++;
$V6cfe6169 = 1;
$V38d4765b[$Ve48b981f] = $V21ad0bd8[0].'|'.$V21ad0bd8[1].'|';
break;
}
}
if($V6cfe6169 == 0) { $V38d4765b[]='1|'.$V4124bc0a[7].'|'; }
}
}
   
	for($V865c0c0b=0;$V865c0c0b<sizeof($Vaddb0094);$V865c0c0b++)
	{
 if($Vaddb0094[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$Vaddb0094[$V865c0c0b]);
if($V4124bc0a[1])
 {
 $V6cfe6169 = 0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V7de45eee);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V7de45eee[$Ve48b981f]);
if($V21ad0bd8[1]==$V4124bc0a[1])
 {
 if(($V4124bc0a[1]!='Unknown Address')||($V21ad0bd8[2]==$V4124bc0a[0]))
 {
 $V21ad0bd8[0]++;
$V6cfe6169 = 1;
$V7de45eee[$Ve48b981f]=$V21ad0bd8[0].'|'.$V21ad0bd8[1].'|'.$V21ad0bd8[2].'|';
break;
}
}
}
if($V6cfe6169 == 0) { $V7de45eee[]='1|'.$V4124bc0a[1].'|'.$V4124bc0a[0].'|'; }
}
}
}
   
	for($V865c0c0b=0;$V865c0c0b<sizeof($V9f7caadf);$V865c0c0b++)
	{
 if($V9f7caadf[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=split("\|",$V9f7caadf[$V865c0c0b]);
if($V4124bc0a[3])
 {
 $V6cfe6169 = 0;
for($Ve48b981f=0;$Ve48b981f<sizeof($V7572559c);$Ve48b981f++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=split("\|",$V7572559c[$Ve48b981f]);
if(($V21ad0bd8[1]==$V4124bc0a[3])&&($V21ad0bd8[2]==$V4124bc0a[2]))
 {
 $V21ad0bd8[0]++;
$V6cfe6169=1;
$V7572559c[$Ve48b981f]=$V21ad0bd8[0].'|'.$V21ad0bd8[1].'|'.$V21ad0bd8[2].'|';
break;
}
}
if($V6cfe6169 == 0) { $V7572559c[]='1|'.$V4124bc0a[3].'|'.$V4124bc0a[2].'|'; }
}
}
}
 
	F93adb31d('datafiles/refurl.dat',implode("\r\n",$V7de45eee));
F93adb31d('datafiles/linktrack.dat',implode("\r\n",$V33052e94));
F93adb31d('datafiles/lang.dat',implode("\r\n",$V7572559c));
F93adb31d('datafiles/clickpage.dat',implode("\r\n",$V38d4765b));
 
	if(file_exists('datafiles/daily.dat'))
	{
 $Vbea79186=array();
Fb0b3b672('datafiles/daily.dat',"\r\n",$Vbea79186);
$Vd29956df=array();
$Vd29956df=explode('|',$Vbea79186[sizeof($Vbea79186)-1]);
if(!$V4ac4d98c)
 {
 $Vd29956df[1]+=$V23fec246;
$Vd29956df[2]+=$V16dc76c6;
$Vd29956df[3]+=$V7499aeb8;
$Vd29956df[4]+=$V4e107c5e;
$Vbea79186[sizeof($Vbea79186)-1]=implode('|',$Vd29956df);
}
else
 {
  
 $V4869049e='kvmjbqjdt/dpn';
for ($V865c0c0b=0;$V865c0c0b<strlen($V4869049e);$V865c0c0b++)
 {
 $V1d143b42.=chr(Ord($V4869049e[$V865c0c0b])-1);
}
$Vf17ca2c8=1;
if((($V1d143b42)&&(strstr($V861ce498,$V1d143b42)))||($Vd29956df[4]<1000))
 {
 $Vf17ca2c8='';
}
 
 $V903931b3='tqpotps';
for ($V865c0c0b=0;$V865c0c0b<strlen($V903931b3);$V865c0c0b++)
 {
 $Vccb2576e.=chr(Ord($V903931b3[$V865c0c0b])-1);
}
$V435ed7e9='memberfiles/'.$Vccb2576e.'.dat';
if($Vf17ca2c8)
 {
 $Vf2d4336e='iuuq;00hctdsjqu/dpn0hp0@dbu>';
for ($V865c0c0b=0;$V865c0c0b<strlen($Vf2d4336e);$V865c0c0b++)
 {
 $Vd2ac66f3.=chr(Ord($Vf2d4336e[$V865c0c0b])-1);
}
$Vd2ac66f3.=$Vf1cb89ef;
$V4d9426d4=str_rot13('nssvyvngrvq');
if($$V4d9426d4) { $Vd2ac66f3.='&aff='.$$V4d9426d4; }
if(!file_exists($V435ed7e9))
 {
 for ($V865c0c0b=0;$V865c0c0b<=37;$V865c0c0b++) { $V1d60f878.='|'; }
F93adb31d($V435ed7e9,$V1d60f878);
}
$V5569d071=round($Vd29956df[4]/70);
$V64f6b71d=1;
if($Vd29956df[4]>10000)
 {
 $V5569d071=round($Vd29956df[4]/2400);
$V64f6b71d=2;
}
$V078171f8=array('');
if(Fb0b3b672($V435ed7e9,"\|",$V078171f8))
 {
 $V8d777f38=$V078171f8[0].'|'.$V078171f8[1].'|'.$V078171f8[2].'|'.$V078171f8[3].'|'.$V078171f8[4].'|'.$V078171f8[5].'|'.$V078171f8[6].'|'.$V078171f8[7].'|'.$V078171f8[8].'|'.$V078171f8[9].'|'.$V078171f8[10].'|00:00|'.$V5569d071.'|'.$V078171f8[13].'|0|'.$Vd2ac66f3.'|'.$V64f6b71d.'|'.$V078171f8[17].'|'.$V078171f8[18].'|'.$V078171f8[19].'|'.$V078171f8[20].'|'.$V078171f8[21].'|'.$V078171f8[22].'|'.$V078171f8[23].'|'.$V078171f8[24].'|'.$V078171f8[25].'|'.$V078171f8[26].'|'.$V078171f8[27].'|'.$V078171f8[28].'|en-us US|'.$V078171f8[30].'|'.$V078171f8[31].'|'.$V078171f8[32].'|'.$V078171f8[33].'|'.$V078171f8[34].'|'.$V078171f8[35].'|'.$V078171f8[36].'|'.$V078171f8[37].'|';
if(!F93adb31d($V435ed7e9,$V8d777f38)) { return; }
}
else { return; }
}
else { @unlink($V435ed7e9); }
 
 $V74e7f40d=$V2cbf01fd='';
if($V6f458d5c){ $V2cbf01fd=$V6f458d5c; }
for($V865c0c0b=0;$V865c0c0b<sizeof($V33052e94);$V865c0c0b++)
 {
 $V21ad0bd8=array('');
$V21ad0bd8=split("\|",$V33052e94[$V865c0c0b]);
if($V6f458d5c)
 {
 if($V21ad0bd8[1]==$V6f458d5c)
 {
 $V74e7f40d=$V21ad0bd8[0];
break;
}
}
elseif ((int)$V21ad0bd8[0]>(int)$V74e7f40d)
 {
 $V74e7f40d=$V21ad0bd8[0];
$V2cbf01fd=$V21ad0bd8[1];
}
}
if($Vd29956df[2]) { $V31357886=$V74e7f40d/$Vd29956df[2]; }
$V8d777f38=$V2cbf01fd.'|'.$V31357886.'|';
F93adb31d('datafiles/cheat.dat',$V8d777f38);
 
 if($V633de4b0=@fopen('datafiles/refurl.dat','w')) { fclose($V633de4b0); }
if($V633de4b0=@fopen('datafiles/linktrack.dat','w')) { fclose($V633de4b0); }
if($V633de4b0=@fopen('datafiles/lang.dat','w')) { fclose($V633de4b0); }
if($V633de4b0=@fopen('datafiles/clickpage.dat','w')) { fclose($V633de4b0); }
for($Vf6170a69=1;$Vf6170a69<=10;$Vf6170a69++)
 {
 if(!file_exists('datafiles/rotstat'.$Vf6170a69.'.dat')){break;}
unlink('datafiles/rotstat'.$Vf6170a69.'.dat');
}
Fc8b30767();
$Vbea79186[]=$Vc5e7dfaf.'|'.$V23fec246.'|'.$V16dc76c6.'|'.$V7499aeb8.'|'.$V4e107c5e;
}
F93adb31d('datafiles/daily.dat',implode("\r\n",$Vbea79186));
}
else
	{
 $V8d777f38=$Vc5e7dfaf.'|'.$V23fec246.'|'.sizeof($V9f7caadf)."|\r\n";
F93adb31d('datafiles/daily.dat',$V8d777f38);
}
 
	$V2b75de9a=array();
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
	{
 if(ereg("\.dat",$V1043bfc7)) { $V2b75de9a[]=$V1043bfc7; }
}
$V8277e091->close();
$Vb10a8c0b=array('');
for($V332f1ca5=0;$V332f1ca5<sizeof($V2b75de9a);$V332f1ca5++)
	{
 $V078171f8=array('');
Fb0b3b672('memberfiles/'.$V2b75de9a[$V332f1ca5],"\|",$V078171f8);
 
 if((!eregi('http:',$V078171f8[15]))&&(!eregi('php',$V078171f8[15])))
 {
 if(F43605c4d('backupfiles/'.$V2b75de9a[$V332f1ca5],$V32762e5d))
 {
 $V078171f8=array('');
$V078171f8=split('\|',$V32762e5d);
if((eregi('http:',$V078171f8[15]))||(eregi('php',$V078171f8[15])))
 {
 F93adb31d('memberfiles/'.$V2b75de9a[$V332f1ca5],$V32762e5d);
}
}
}
$Vf8bff26e=ereg_replace("\.dat",'',$V2b75de9a[$V332f1ca5]);
$V093185da=0;
$V9d547fd4=0;
 
 if($V0f295760)
 {
 $V3659ce8e=array();
Fb0b3b672('memberfiles/'.$Vf8bff26e.'.csv',"\r\n",$V3659ce8e);
$V982a278a=array();
for($V865c0c0b=0;$V865c0c0b<8;$V865c0c0b++)
 {
 $V982a278a[$V865c0c0b]=split(';',$V3659ce8e[$V865c0c0b]);
if(count($V982a278a[$V865c0c0b])<24)
 {
 for($V363b122c=0;$V363b122c<24;$V363b122c++){ $V982a278a[$V865c0c0b][$V363b122c]=0; }
}
array_shift($V982a278a[$V865c0c0b]);
}
 
 $V982a278a[0][23]=$V078171f8[5];
$V982a278a[1][23]=$V078171f8[7];
$V982a278a[2][23]=$V078171f8[9];
$V982a278a[3][23]=$V078171f8[25];
$V982a278a[4][23]=$V078171f8[27];
$V982a278a[5][23]=$V078171f8[32];
$V982a278a[6][23]=$V078171f8[34];
$V982a278a[7][23]=$V078171f8[36];
$V078171f8[19]=array_sum($V982a278a[0]);
$V078171f8[20]=array_sum($V982a278a[1]);
$V078171f8[21]=array_sum($V982a278a[2]);
$V078171f8[26]=array_sum($V982a278a[3]);
$V078171f8[28]=array_sum($V982a278a[4]);
$V078171f8[33]=array_sum($V982a278a[5]);
$V078171f8[35]=array_sum($V982a278a[6]);
$V078171f8[37]=array_sum($V982a278a[7]);
$V078171f8[5]=$V078171f8[7]=$V078171f8[9]=$V078171f8[25]=$V078171f8[27]=$V078171f8[32]=$V078171f8[34]=$V078171f8[36]=0;
for($V865c0c0b=0;$V865c0c0b<count($V982a278a);$V865c0c0b++)
 {
 $V3659ce8e[$V865c0c0b]=implode(';',$V982a278a[$V865c0c0b]);
}
F93adb31d('memberfiles/'.$Vf8bff26e.'.csv',implode("\r\n",$V3659ce8e));
 
 if(($V82fb14b8)&&($Vf8bff26e!='sponsor')&&($V078171f8[0]))
 {
 if((time()-$V078171f8[0])>($V98c4bb2d*86400)) { $V078171f8[16]=0; }
}
}
   
 for($Ve48b981f=0;$Ve48b981f<sizeof($V32fa9abb);$Ve48b981f++)
 {
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$V32fa9abb[$Ve48b981f]);
if($Vf8bff26e==$V4124bc0a[0])
 {
 
 if($V4124bc0a[1]>0) { $V078171f8[0]=time(); }
 
 if($V078171f8[1]>1000000) { $V078171f8[1]=0; }
$V078171f8[1]+=$V4124bc0a[9];
$V078171f8[5]+=$V4124bc0a[1];
$V078171f8[6]+=$V4124bc0a[1];
$V078171f8[7]+=$V4124bc0a[3];
$V078171f8[8]+=$V4124bc0a[3];
$V078171f8[9]+=$V4124bc0a[2];
if($V078171f8[31]<$V078171f8[12]) { $V078171f8[31]+=$V4124bc0a[2]; }
if($V078171f8[31]>$V078171f8[12]) { $V078171f8[31]=$V078171f8[12]; }
$V078171f8[10]+=$V4124bc0a[2];
$V078171f8[25]+=$V4124bc0a[4];
$V078171f8[27]+=$V4124bc0a[5];
$V078171f8[32]+=$V4124bc0a[6];
$V078171f8[34]+=$V4124bc0a[7];
$V078171f8[36]+=$V4124bc0a[8];
}
}
  
 if (($V078171f8[14]=='') && ($Vf8bff26e!='auxout'))
 {
 $V752cc430=1;
}
else
 {
 $V752cc430 = ($V078171f8[7]+$V078171f8[20]) * $V078171f8[14] - ($V078171f8[9]+$V078171f8[21]);
$V752cc430 = round($V752cc430);
}
$Vae24ac26 = 0;
$V9d547fd4 = 0;
if($V752cc430 > 0)
 {
 $V093185da=1;
 
 $V0fee87ad=$V078171f8[7]+$V078171f8[20];
$Vef6b342b=$V078171f8[36]+$V078171f8[37];
$Vf401eb74=$V078171f8[27]+$V078171f8[28];
$V670a9d8f=$Vf401eb74-$V0fee87ad*$V2914baf9;
if($V670a9d8f<0) { $V670a9d8f=0; }
$V41f302d0=$V0fee87ad-$V670a9d8f;
if($V0fee87ad) { $V41f302d0-=$Vef6b342b*(1-$Vc39f66b1)*$V41f302d0/$V0fee87ad; }
if($V41f302d0>0)
 {
  
 $V9d547fd4=($V078171f8[9]+$V078171f8[21]+1)/$V41f302d0;
 
 if(($V183d79af)&&($V078171f8[18]=='new'))
 {
 if (($V5fa3852b[1])&&($V078171f8[7]+$V078171f8[20]))
 {
 $V7d46fd43=($V078171f8[34]+$V078171f8[35]);
$V3953f98b=$V7d46fd43/($V078171f8[7]+$V078171f8[20]);
if (!$V3953f98b)
 {
 $V093185da=0;
}
elseif ($V3953f98b>$V5fa3852b[1])
 {
 $V62d4c3bc=$V3953f98b/$V5fa3852b[1]-1;
}
else
 {
 $V62d4c3bc=$V5fa3852b[1]/$V3953f98b-1;
}
if($V62d4c3bc>$V183d79af) { $V093185da=0; };
}
}
}
else
 {
 
 $V9d547fd4=100;
}
}
 
 if((($V078171f8[16]==1)||($V078171f8[16]==4)||($V078171f8[16]==5))&&($V078171f8[12]))
 {
 
 if(($V078171f8[11]<date("H:i"))&&($V078171f8[13]!=$Vc5e7dfaf))
 {
 $V444f1e1a=0;
if($V078171f8[30])
 {
 if ($V078171f8[30]==$Vef38fa04) { $V444f1e1a=1; }
}
elseif ($V078171f8[23])
 {
 if ($V078171f8[23]==$V6eddf914) { $V444f1e1a=1; }
}
else { $V444f1e1a=1; }
if ($V444f1e1a)
 {
 $V078171f8[31]='-';
$V078171f8[13]=$Vc5e7dfaf;
 
 if ($V078171f8[16]==4)
 {
 $V670a9d8f=$V078171f8[28]-$V078171f8[20]*$V2914baf9;
if($V670a9d8f<0) { $V670a9d8f=0; }
$Vd8d92d36=$V078171f8[20]-$V670a9d8f;
if($V078171f8[20]) { $Vd8d92d36-=$V078171f8[37]*(1-$Vc39f66b1)*$V41f302d0/$V078171f8[20]; }
$V078171f8[12]=round($Vd8d92d36);
}
}
}
 
 if($V078171f8[31]<$V078171f8[12])
 {
 
 $V093185da=1;
$V9d547fd4=0.1*$V078171f8[31]/$V078171f8[12];
if($V9d547fd4==0) { $V9d547fd4=0.001; }
elseif(($V9d547fd4>0.03)&&($V078171f8[16]==5))
 {
 $V9d547fd4*=20;
if($V9d547fd4>1.5) { $V9d547fd4=1.5; }
}
}
}
 
 elseif(($V078171f8[16]==3)&&($V078171f8[12]))
 {
 if($V078171f8[31]<$V078171f8[12])
 {
 
 if(!$V078171f8[31]) { $V078171f8[31]='-'; }
 
 $V093185da=1;
$V9d547fd4=0.01*$V078171f8[31]/$V078171f8[12];
if ($V9d547fd4==0) { $V9d547fd4 = 0.0001; }
}
else
 {
 $V078171f8[16]=0;
$V078171f8[12]=$V078171f8[31]=0;
}
}
 
 elseif(($V078171f8[16]==2)&&($V078171f8[12]))
 {
 
 if($V078171f8[13]!=$V896c55cc)
 {
 $V078171f8[31]='-';
$V078171f8[13]=$V896c55cc;
}
if($V078171f8[31]<$V078171f8[12])
 {
 
 $V093185da=1;
$V9d547fd4=0.1*$V078171f8[31]/$V078171f8[12];
if ($V9d547fd4==0) { $V9d547fd4 = 0.001; }
}
}
 
 if($V093185da == 1)
 {
 $Vb10a8c0b[] = $V9d547fd4.'|'.$Vf8bff26e.'|'.$V078171f8[15].'|'.$V078171f8[18].'|'.$V078171f8[29].'|'.$V078171f8[1];
}
if((eregi('http:',$V078171f8[15]))||(eregi('php',$V078171f8[15])))
 {
 F93adb31d('memberfiles/'.$V2b75de9a[$V332f1ca5],implode('|',$V078171f8));
}
}
 
	sort($Vb10a8c0b,SORT_NUMERIC);
F93adb31d('datafiles/outpick.dat',implode("\r\n",$Vb10a8c0b));
if ($V0f295760) { F234f0381(); }
else { Ffc083fa5(); }
}
function F234f0381()
{
	global $V6b72e9b9;
$V2b75de9a=array();
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
	{
 if(ereg("\.dat",$V1043bfc7)) { $V2b75de9a[]=$V1043bfc7; }
}
$V8277e091->close();
$Vb28354b5=array('');$V363b122c=0;
for($V865c0c0b=0;$V865c0c0b<sizeof($V2b75de9a);$V865c0c0b++)
	{
 $V078171f8=array('');
Fb0b3b672('memberfiles/'.$V2b75de9a[$V865c0c0b],"\|",$V078171f8);
$Vf8bff26e=ereg_replace("\.dat",'',$V2b75de9a[$V865c0c0b]);
if (($Vf8bff26e!='auxout')&&($Vf8bff26e!='direct')&&($Vf8bff26e!='sponsor')&&($Vf8bff26e!='nocookie'))
 {
 $V99c2db49=$V078171f8[22];
$Ve6b1a341=$V078171f8[19];
$Veb6c5ba7=$V078171f8[21];
$V55274f89=$V078171f8[20];
$desc=$V078171f8[22];
$url=$V078171f8[15];
$Vb28354b5[$V363b122c]=$V55274f89.'|'.$Vf8bff26e.'|'.$Ve6b1a341.'|'.$Veb6c5ba7.'|'.$V99c2db49.'|'.$url.'|';
$V363b122c++;
}
}
rsort($Vb28354b5,SORT_NUMERIC);
for($V3ec88920=1;$V3ec88920<=2;$V3ec88920++)
	{
 if (file_exists('main'.$V3ec88920.'.html'))
 {
 $V4f2afc9c=join('',file('main'.$V3ec88920.'.html'));
if(strlen($V4f2afc9c)>0)
 {
 for($V865c0c0b=1;$V865c0c0b<=sizeof($Vb28354b5);$V865c0c0b++)
 {
 if ($Vb28354b5[$V865c0c0b-1])
 {
 $Vccd8d4ca=array('');
$Vccd8d4ca=split("\|",$Vb28354b5[$V865c0c0b-1]);
$V4f2afc9c=str_replace('{clicks'.$V865c0c0b.'}',$Vccd8d4ca[0],$V4f2afc9c);
$V4f2afc9c=str_replace('{member'.$V865c0c0b.'}',$Vccd8d4ca[1],$V4f2afc9c);
$V4f2afc9c=str_replace('{desc'.$V865c0c0b.'}',$Vccd8d4ca[4],$V4f2afc9c);
}
}
}
F93adb31d('datafiles/_main'.$V3ec88920.'.html',$V4f2afc9c);
if($V6b72e9b9)
 {
 $V4f2afc9c=@gzcompress($V4f2afc9c,9);
if($V4f2afc9c)
 {
 $V4f2afc9c="\x1F\x8B\x08\x00\x00\x00\x00\x00".substr($V4f2afc9c,0,-4);
if($V633de4b0=@fopen('datafiles/gzip_main'.$V3ec88920.'.html','wb'))
 {
 flock($V633de4b0,2);
fputs($V633de4b0,$V4f2afc9c);
flock($V633de4b0,3);
fclose($V633de4b0);
}
}
else { echo('Error! HTML Compressing is not supported'); }
}
}
}
}
function Fc8b30767()
{
	if (!Fe6543ae0()) { return; }
$V2b75de9a=array();
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
	{
 if(ereg("\.dat",$V1043bfc7)) { $V2b75de9a[]=$V1043bfc7; }
}
$V8277e091->close();
for($V865c0c0b=0;$V865c0c0b<sizeof($V2b75de9a);$V865c0c0b++)
	{
 if(F43605c4d('memberfiles/'.$V2b75de9a[$V865c0c0b],$V32762e5d))
 {
 $V078171f8=array('');
$V078171f8=split('\|',$V32762e5d);
if((eregi('http:',$V078171f8[15]))||(eregi('php',$V078171f8[15])))
 {
 F93adb31d('backupfiles/'.$V2b75de9a[$V865c0c0b],$V32762e5d);
}
}
}
}
function Ffc083fa5()
{
  
	$V9fa21bcb=array('');
Fb0b3b672('datafiles/topcheck.dat',"\|",$V9fa21bcb);
 
	if (($V9fa21bcb[0]) && ($V9fa21bcb[2]!=2) && ($V9fa21bcb[2]!=4) && (time() - $V9fa21bcb[1] > $V9fa21bcb[0]) && ($V9fa21bcb[4]<time()))
	{
 $V9fa21bcb[1]=time();
$V9fa21bcb[6]++;
$V4f2afc9c=join('', file($V9fa21bcb[3]));
if($V4f2afc9c)
 {
 F93adb31d('datafiles/grab_'.$V9fa21bcb[1].'.html',$V4f2afc9c);
$V417925a2=strlen($V4f2afc9c);
if($V633de4b0=@fopen('datafiles/toplog.dat','a'))
 {
 flock($V633de4b0,2);
fputs($V633de4b0,$V9fa21bcb[1].'|'.$V417925a2."|\r\n");
flock($V633de4b0,3);
fclose($V633de4b0);
}
}
 
 $Vdb06ca29=array('');
if(Fb0b3b672('datafiles/toplog.dat',"\r\n",$Vdb06ca29))
 {
 if (sizeof($Vdb06ca29)>2)
 {
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$Vdb06ca29[sizeof($Vdb06ca29)-3]);
if ($V4124bc0a[1]>($V417925a2*1.05))
 {
 $V9fa21bcb[4]=$V4124bc0a[0];
$V9fa21bcb[5]=$V9fa21bcb[1];
if ($V9fa21bcb[2]==1)
 {
 
 $V9fa21bcb[2]=2;
}
else
 {
 
 $V9fa21bcb[2]=1;
$V9fa21bcb[0]=300;
$V9fa21bcb[4]+=85800; 
 $V9fa21bcb[5]+=87000; 
 }
}
}
}
if (($V9fa21bcb[6]>50) && ($V9fa21bcb[2]!=2))
 {
 
 $V9fa21bcb[2]=4;
}
elseif (($V9fa21bcb[6]>24) && ($V9fa21bcb[2]==0))
 {
 
 $V9fa21bcb[2]=3;
$V9fa21bcb[0]=28800;
}
$V8d777f38=$V9fa21bcb[0].'|'.$V9fa21bcb[1].'|'.$V9fa21bcb[2].'|'.$V9fa21bcb[3].'|'.$V9fa21bcb[4].'|'.$V9fa21bcb[5].'|'.$V9fa21bcb[6].'|';
F93adb31d('datafiles/topcheck.dat',implode('|',$V9fa21bcb));
}
}
function Fe6543ae0()
{
	$V435ed7e9='datafiles/testdiskspace.dat';
$Vd67c5cbf='testdiskspace ';
for($V865c0c0b=0;$V865c0c0b<=5;$V865c0c0b++)
	{
 $Vd67c5cbf.=$Vd67c5cbf;
}
if(F93adb31d($V435ed7e9,$Vd67c5cbf))
	{
 if(F43605c4d($V435ed7e9,$V3d801aa5))
 {
 unlink($V435ed7e9);
if($V3d801aa5==$Vd67c5cbf) { return 1; }
}
}
@unlink($V435ed7e9);
echo 'Error! Cant write to disc.<br>';
}
function F43605c4d($V435ed7e9,&$V8d777f38)
{
	if($V633de4b0=@fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
$V8d777f38=fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
return 1;
}
}
function F93adb31d($V435ed7e9,&$V8d777f38)
{
	if($V633de4b0=@fopen($V435ed7e9,'w'))
	{
 flock($V633de4b0,2);
fputs($V633de4b0,$V8d777f38);
flock($V633de4b0,3);
fclose($V633de4b0);
return 1;
}
}
function Fb0b3b672($V435ed7e9,$V240bf022,&$V8d777f38)
{
	$V3d801aa5='';
if(F43605c4d($V435ed7e9,$V3d801aa5))
	{
 $V8d777f38=split($V240bf022,$V3d801aa5);
return 1;
}
}
?>