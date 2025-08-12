<?php
require_once('function.viewarray.php');
$temp = getLayout();

echo '<hr>';
echo '<b>FuseLogic version. '.FL_VERSION.' on php-'.phpversion().'</b>';
echo '<br>file = '.$temp;


echo '<hr>';
echo 'file = '.__FILE__;
echo '<br>from module = '.module();
echo '<br>from sub module = '.subModule();
echo '<br><a href="'.index().'">Home</a>';
echo '<br><a href="'.index().'forum">Not Defined Module</a>';
echo '<br><a href="'.index().'init/blablabla">Not Defined Sub Module</a>';


echo '<br><a href="'.index().'init/modules_info">Modules Info</a><br><br>';
echo viewarray(get_included_files());
echo '<br/>';
?>