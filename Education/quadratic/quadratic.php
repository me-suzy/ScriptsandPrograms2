<html>
<head>
<title>Quadratic Equation Solver by Scriptsez.net</title>
<STYLE type=text/css>
TD {
	COLOR: #000000; FONT-FAMILY: Verdana, Helvetica, Arial; FONT-SIZE: 14px
}
</style>
</head>
<body>
<?php
extract($HTTP_GET_VARS);
extract($HTTP_POST_VARS);
echo "<FONT SIZE=4 COLOR=#990000 face=arial>Quadratic Equation Solver</FONT><HR color=#AfC3D>";
echo "<table><td>Please enter the constants of quadratic equation:</td></table>";
echo "<form method=post action=?action=solve><table><td><input type=text name=a size=3 maxlength=8>x<sup>2</sup> + </td><td><input type=text name=b size=3 maxlength=8>x + </td><td><input type=text name=c size=3 maxlength=8></td><td>= 0</td><td><input type=submit value=Solve></td></table></form>";
if($action=="solve"){
if($a==""){$a="0";}
if($b==""){$b="0";}
if($c==""){$c="0";}
$d=pow($b,"2");
$m=($d-("4"*($a*$c)));
if(substr($m, 0, 1 )=="-"){
$p=((-1)*($m));
$sq=sqrt($p);
$root="im";
} else { 
$sq=sqrt($m);
$root="real";
}
if($root=="im"){
echo "<FONT COLOR=red face=arial>This equation $a x<sup>2</sup>+($b)x+($c)=0 has imaginary roots, so it can not be solved.</FONT>";
}elseif($a != "0"){
$ans=(((-1)*$b+$sq)/(2*$a));
$ans1=(((-1)*$b-$sq)/(2*$a));
$ans=round($ans,"4");
$ans1=round($ans1,"4");
echo "<table><td>Two possible values of <B>x</B> for $a x<sup>2</sup>+($b)x+($c)=0 are:</td></table><table><tr><td><B>x</B></td><td>=</td><td><B>$ans</B></td> </tr><tr><td><B>x</B></td><td>=</td><td><B>$ans1</B></td></tr></table>";
}else{echo "<table><td>This is not a valid quadratic equation, since the value of constant (a) can never be zero.</td></table>";}
}
echo "<table valign=bottom width=100%><td><img src=blank.gif width=0 height=80></td><tr><td valign=bottom align=center><hr color=#AfC3D>Copyright <a href=http://www.scriptsez.net target='_new'>Scriptsez.net</a></td></tr></table>";
?>
</body>
</html>