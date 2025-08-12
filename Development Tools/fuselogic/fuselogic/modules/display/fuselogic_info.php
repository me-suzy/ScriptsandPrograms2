<?php
require_once('function.viewarray.php');
echo '<h3>Executed Fuse</h3>';
echo viewarray($FLQueue->getLogs());	 
echo '<br><h3>Included Files</h3>';
echo viewarray(get_included_files());
echo '<br/>';

echo '<h3>API Info</h3>';
$array = array();
$array[] =  'index() = '.index();
$array[] = '__FILE__ = '.str_replace('\\','/',__FILE__);
$array[] =  'module() = '.module();
$array[] = 'subModule() = '.subModule();
$array[] =  'userModule() = '.userModule();
$array[] =  'userSubModule() = '.userSubModule();
$array[] =  'WebPath() = '.WebPath();
$array[] =  '<b>WebPath(\''.userModule().'\') = '.WebPath(userModule()).'</b>';
$array[] =  'Real_Path(\'display\') = '.Real_Path('display');
$array[] =  '<b>Real_Path(\''.userModule().'\') = '.Real_Path(userModule()).'</b>';
$array[] =  '<b>Fuse(\''.userModule().'/'.userSubModule().'\') = '.Fuse(userModule().'/'.userSubModule()).'</b>';

echo viewarray($array);
unset($array);


echo '<h3>Included Path</h3>';
$temp = explode(';',ini_get('include_path'));
if(count($temp) < 2){
    $temp = explode(':',ini_get('include_path'));
}
echo viewarray($temp);

?>
