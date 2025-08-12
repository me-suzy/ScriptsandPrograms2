<?php

$fl_module = array();
require_once('class.dir_reader.php');

$dir_reader = &new dir_reader();

$temp1 = array();
$root = dirname(dirname(__FILE__));
$dir_reader->read_directory($root);
$temp1 = $dir_reader->get_directory();

$count = count($temp1);
$temp2 = array();
for($i=0;$i<$count;$i++){
    if(substr($temp1[$i],0,1) !== '_'){
		    $temp2[] = $temp1[$i];
		}
}
/*
echo '<br>';
echo '<div align="center">';
echo '<table border="1" align="center">';
echo '<tr><th>Module</th><th>Path</th><th>Sub Module</th></tr>';
*/
$count = count($temp2);
for($i=0;$i<$count;$i++){
    $file = str_replace('\\','/',$root.'/'.$temp2[$i].'/module_setting.php');		
    if(file_exists($file)){	
		    $setting = array();
				include $file;
				if(isset($setting['module_name'])){
				   $count1 = count($setting['module_name']);					 
					 if($count1 > 1){
					     for($j=0;$j<$count1;$j++){
							    if(isset($setting['module_name'][$j]) and !isset($fl_module[$setting['module_name'][$j]])){
					            $fl_module[$setting['module_name'][$j]] = $temp2[$i];												
											echo '<tr><td>'.$setting['module_name'][$j].'</td>';
											echo '<td>'.$temp2[$i].'</td>';
											echo '<td>';
											ksort($setting['sub_module']);
											foreach($setting['sub_module'] as $sub_module_name => $file_name){
											    if(substr($sub_module_name,0,1) === '_'){
											        echo $sub_module_name.'<br>';
											    }else{
									            echo '<a href="'.index().$setting['module_name'][$j].'/'.$sub_module_name.'">'.$sub_module_name.'</a><br>';
											    }
											}
											echo '</td></tr>';																		
							    }
							 }					     
					 }else{
					     if(!isset($fl_module[$setting['module_name']])){
					         $fl_module[$setting['module_name']] = $temp2[$i];		
									 echo '<tr><td>'.$setting['module_name'].'</td>';
									 echo '<td>'.$temp2[$i].'</td>';
									 echo '<td>';
									 ksort($setting['sub_module']);
									 foreach($setting['sub_module'] as $sub_module_name => $file_name){
									     if(substr($sub_module_name,0,1) === '_'){
											     echo $sub_module_name.'<br>';
											 }else{
									         echo '<a href="'.index().$setting['module_name'].'/'.$sub_module_name.'">'.$sub_module_name.'</a><br>';
											 }
									 }
									 echo '</td></tr>';		
							 }				       
					 }					 
				}		 				
		}		
}
echo '</table>';
echo '</div>';
echo '<br>';


//print_r($fl_module);
/*
echo '<br>';
echo '<div align="center">';
echo '<table border="1">';
echo '<tr><th>Module Name</th><th>Path</th></tr>';
foreach($fl_module as $module_name => $directory_name){
    echo '<tr><td>'.$module_name.'</td><td>'.$directory_name.'</td>';
		
		echo '</tr>';
}
echo '</table>';
echo '</div>';
echo '<br>';
unset($fl_module);
*/

?>