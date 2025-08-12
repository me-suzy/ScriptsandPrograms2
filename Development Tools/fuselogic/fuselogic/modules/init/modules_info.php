<?php

$fl_module = array();
require_once('class.dir_reader.php');

$dir_reader = &new dir_reader();

$temp1 = array();
$root = dirname(dirname(__FILE__));
$dir_reader->read_directory($root);
$temp1 = $dir_reader->get_directory();

$temp2 = array();
foreach($temp1 as $temp){
    if(substr($temp,0,1) !== '_'){
		    $temp2[] = $temp;
		}
}

echo '<br>';
echo '<div align="center"><h3>Modules Info</h3>';
echo '<table border="1" align="center">';
echo '<tr><th>Module</th><th>Path</th><th>Sub Module</th></tr>';
$number_of_fuseaction = 0;
foreach($temp2 as $temp){
    $file = str_replace('\\','/',$root.'/'.$temp.'/module_setting.php');		
    if(file_exists($file)){	
		    $FL_MODULE_SETTING = array();
				include $file;
				if(isset($FL_MODULE_SETTING['module_name'])){
				   $count1 = count($FL_MODULE_SETTING['module_name']);					 
					 if($count1 > 1){
					     for($j=0;$j<$count1;$j++){
							    if(isset($FL_MODULE_SETTING['module_name'][$j]) and !isset($fl_module[$FL_MODULE_SETTING['module_name'][$j]])){
					            $fl_module[$FL_MODULE_SETTING['module_name'][$j]] = $temp;												
											echo '<tr><td>'.$FL_MODULE_SETTING['module_name'][$j].'</td>';
											echo '<td>'.$temp.'</td>';
											echo '<td>';
											ksort($FL_MODULE_SETTING['sub_module']);
											foreach($FL_MODULE_SETTING['sub_module'] as $sub_module_name => $file_name){
											    $number_of_fuseaction++;
											    if(substr($sub_module_name,0,1) === '_'){
											        echo $sub_module_name.'<br>';
											    }else{
									            echo '<a href="'.index().$FL_MODULE_SETTING['module_name'][$j].'/'.$sub_module_name.'">'.$sub_module_name.'</a><br>';
											    }
											}
											echo '</td></tr>';																		
							    }
							 }					     
					 }else{
					     if(!isset($fl_module[$FL_MODULE_SETTING['module_name']])){
					         $fl_module[$FL_MODULE_SETTING['module_name']] = $temp;		
									 echo '<tr><td>'.$FL_MODULE_SETTING['module_name'].'</td>';
									 echo '<td>'.$temp.'</td>';
									 echo '<td>';
									 ksort($FL_MODULE_SETTING['sub_module']);
									 foreach($FL_MODULE_SETTING['sub_module'] as $sub_module_name => $file_name){
									     $number_of_fuseaction++;
									     if(substr($sub_module_name,0,1) === '_'){
											     echo $sub_module_name.'<br>';
											 }else{
									         echo '<a href="'.index().$FL_MODULE_SETTING['module_name'].'/'.$sub_module_name.'">'.$sub_module_name.'</a><br>';
											 }
									 }
									 echo '</td></tr>';		
							 }				       
					 }					 
				}		 				
		}		
}
echo '</table>';
echo '<h4>There are = '.$number_of_fuseaction.' fuse</h4>';
echo '</div>';
echo '<br>';

?>