<?php

echo '<br><table  cellpadding="5" cellspacing="0" border="1" align="center">';
echo '<tr>';
echo '<th>No.</th>';
echo '<th>Module Name</th>';   
echo '<th>Sub Module Name';				
echo '</th></tr>';	
		
$i=0;
$j=0;		
$color[1] = '#9999ff';
$color[0] = '#ccccff';		
foreach($FuseLogic->modules as $module => $path){  
    $i++;  
	  @$k = $k>0?0:1;
		echo '<tr>';
		echo '<td bgcolor="'.$color[$k].'">'.$i.'</td>';
		echo '<td bgcolor="'.$color[$k].'">'.$module.'</td>';   
		
		echo '<td bgcolor="'.$color[$k].'">';
		if(count($FuseLogic->fuse[$module])> 0){		 
		foreach($FuseLogic->fuse[$module] as $subModule => $file){
		    $j++;
		    echo '<a href="'.index().$module.'/'.$subModule.'">'.$subModule.'</a><br>';				
		}
		}
		echo '</td></tr>';	
    
}
echo '</table>';

echo '<div align="center"><h3>There are "'.$j.'" Fuses</h3></div>';
?>