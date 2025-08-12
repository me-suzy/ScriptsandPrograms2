<? 
include("easytemplate.php"); 

$rows[] = array("id"=>"0","visitor"=>"Fize"); 
$rows[] = array("id"=>"1","visitor"=>"Malik"); 
$rows[] = array("id"=>"2","visitor"=>"Sager"); 
$rows[] = array("id"=>"3","visitor"=>"Ahmad"); 
$rows[] = array("id"=>"4","visitor"=>"Khalid"); 
$rows[] = array("id"=>"5","visitor"=>"Shaleh"); 

$tpl = new EasyTemplate; 
print $tpl->display("template3.html");

?>