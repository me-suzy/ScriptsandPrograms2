<?
$V5b79c40f=$V23fe3c6c=$V02653f3a=array();
for($Vf6170a69=1;$Vf6170a69<=10;$Vf6170a69++)
{
	if(!file_exists('datafiles/rotator'.$Vf6170a69.'.dat')){break;}
 
	$V5b79c40f[$Vf6170a69][]=array();
$V39b1e5fd=array();
Fbd5fc730('datafiles/rotator'.$Vf6170a69.'.dat',$V39b1e5fd);
 
	$V02653f3a[$Vf6170a69]=0;
srand((float)microtime()*10000000);
if(rand(0,4))
	{
 $stats=array();
Fbd5fc730('datafiles/rotstat'.$Vf6170a69.'.dat',$stats);
arsort($stats,SORT_NUMERIC);
$V02653f3a[$Vf6170a69]=count($stats);
foreach($stats as $V8c6b5adb=>$V1269457f)
 {
 $V5b79c40f[$Vf6170a69][]=array($V8c6b5adb,$V39b1e5fd[$V8c6b5adb]);
unset($V39b1e5fd[$V8c6b5adb]);
}
}
$V898340e1=count($V39b1e5fd)-$V02653f3a[$Vf6170a69];
if($V898340e1>0)
	{
 $V71d1e1e7=array();
if($V898340e1>1) { $V71d1e1e7=array_rand($V39b1e5fd,$V898340e1); }
else { $V71d1e1e7=$V39b1e5fd; }
foreach($V71d1e1e7 as $V8c6b5adb=>$V1269457f)
 {
 $V5b79c40f[$Vf6170a69][]=array($V1269457f,$V39b1e5fd[$V1269457f]);
}
}
$V23fe3c6c[$Vf6170a69]=1;
}
 
function rlink($Vf6170a69)
{
	global $V5b79c40f,$V23fe3c6c,$V02653f3a;
echo 'go.php?link='.$V5b79c40f[$Vf6170a69][$V23fe3c6c[$Vf6170a69]][0];
if(!$V02653f3a[$Vf6170a69])
	{
 if($Vf6170a69>1){echo '&V0d3d238b='.$Vf6170a69;};
echo '&thumb='.$V5b79c40f[$Vf6170a69][$V23fe3c6c[$Vf6170a69]][0];
}
if($V5b79c40f[$Vf6170a69][$V23fe3c6c[$Vf6170a69]][1]) { echo '&url='.$V5b79c40f[$Vf6170a69][$V23fe3c6c[$Vf6170a69]][1]; }
}
 
function rdesc($Vf6170a69)
{
	global $V5b79c40f,$V23fe3c6c;
echo $V5b79c40f[$Vf6170a69][$V23fe3c6c[$Vf6170a69]][0];
$V23fe3c6c[$Vf6170a69]++;
}
function Fbd5fc730($V435ed7e9,&$V8d777f38)
{
	if($V633de4b0=@fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
while($V5b4d9906=fgets($V633de4b0,1024))
 {
 $Vde695463=array();
$Vde695463=split(';',$V5b4d9906);
$V8d777f38[trim($Vde695463[1])]=$Vde695463[0];
}
flock($V633de4b0,3);
fclose($V633de4b0);
}
}
?>