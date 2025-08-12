<?php

@session_start();

echo "<table border=\"0\" align=\"center\"><tr><td align=\"center\">"; 

echo "<br><br>Hi, I am  <b> Fuse: ".module().'/'.subModule()."</b>   <br><br>";

@session_register('AVAR');
if(isset($_GET['increase'])){
    @$_SESSION['AVAR'] += 1;
}else @$_SESSION['AVAR'] -= 1;;
echo "   Session Variable = {$_SESSION['AVAR']}   <br><br>";

$a = index().module().'/'.subModule();
echo '<br><a href="'.$a.'">Decrease</a>:::::::::::';
echo '<a href="'.$a.'?increase">Increase</a><br>';

echo '<br>'.getLayout().'<br>';

echo "</td></tr></table>";

?>