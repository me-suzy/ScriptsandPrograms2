<? 
include("easytemplate.php"); 
$var  = "Hello world"; 

$tpl = new EasyTemplate; 
print $tpl->display("template1.html"); 
?> 