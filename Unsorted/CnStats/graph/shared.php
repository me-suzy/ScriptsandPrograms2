<?
$nmax=$max+($max/10);
if ($max<=9000000) $nmax=9000000;
if ($max<=8000000) $nmax=8000000;
if ($max<=7000000) $nmax=7000000;
if ($max<=6000000) $nmax=6000000;
if ($max<=5000000) $nmax=5000000;
if ($max<=4000000) $nmax=4000000;
if ($max<=3000000) $nmax=3000000;
if ($max<=2000000) $nmax=2000000;
if ($max<=1500000) $nmax=1500000;
if ($max<=1200000) $nmax=1200000;
if ($max<=1000000) $nmax=1000000;

if ($max<=900000) $nmax=900000;
if ($max<=800000) $nmax=800000;
if ($max<=700000) $nmax=700000;
if ($max<=600000) $nmax=600000;
if ($max<=500000) $nmax=500000;
if ($max<=400000) $nmax=400000;
if ($max<=300000) $nmax=300000;
if ($max<=200000) $nmax=200000;
if ($max<=150000) $nmax=150000;
if ($max<=120000) $nmax=120000;
if ($max<=100000) $nmax=100000;

if ($max<=90000) $nmax=90000;
if ($max<=80000) $nmax=80000;
if ($max<=70000) $nmax=70000;
if ($max<=60000) $nmax=60000;
if ($max<=50000) $nmax=50000;
if ($max<=40000) $nmax=40000;
if ($max<=30000) $nmax=30000;
if ($max<=20000) $nmax=20000;
if ($max<=15000) $nmax=15000;
if ($max<=12000) $nmax=12000;
if ($max<=10000) $nmax=10000;

if ($max<=9000) $nmax=9000;
if ($max<=8000) $nmax=8000;
if ($max<=7000) $nmax=7000;
if ($max<=6000) $nmax=6000;
if ($max<=5000) $nmax=5000;
if ($max<=4000) $nmax=4000;
if ($max<=3000) $nmax=3000;
if ($max<=2000) $nmax=2000;
if ($max<=1000) $nmax=1000;

if ($max<=900) $nmax=900;
if ($max<=800) $nmax=800;
if ($max<=700) $nmax=700;
if ($max<=600) $nmax=600;
if ($max<=500) $nmax=500;
if ($max<=400) $nmax=400;
if ($max<=300) $nmax=300;
if ($max<=200) $nmax=200;
if ($max<=150) $nmax=150;
if ($max<=120) $nmax=120;
if ($max<=100) $nmax=100;

if ($max<=90) $nmax=90;
if ($max<=80) $nmax=80;
if ($max<=70) $nmax=70;
if ($max<=60) $nmax=60;
if ($max<=50) $nmax=50;
if ($max<=40) $nmax=40;
if ($max<=30) $nmax=30;
if ($max<=20) $nmax=20;
if ($max<=10) $nmax=10;

$im=imagecreate($W,$H);

// Çàäàåì îñíîâíûå öâåòà
$bg[0]=imagecolorallocate($im,255,255,255);
$bg[1]=imagecolorallocate($im,231,249,234);
$bg[2]=imagecolorallocate($im,212,243,215);
$c=imagecolorallocate($im,184,225,189);

// Öâåòà äëÿ ñòîëáèêîâ
$bar[2][0]=imagecolorallocate($im,127,127,255);
$bar[2][1]=imagecolorallocate($im,95,95,223);
$bar[2][2]=imagecolorallocate($im,64,64,191);
$bar[0][0]=imagecolorallocate($im,255,127,127);
$bar[0][1]=imagecolorallocate($im,223,95,95);
$bar[0][2]=imagecolorallocate($im,191,64,64);
$bar[1][0]=imagecolorallocate($im,0,223,0);
$bar[1][1]=imagecolorallocate($im,0,191,0);
$bar[1][2]=imagecolorallocate($im,0,160,0);
$text=imagecolorallocate($im,136,197,145);
?>