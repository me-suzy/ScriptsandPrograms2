 <table> <form name=younamit><tr><td>Click anywhere in the sql box to select all the code<? if (is_array( $data->sheets[0])) {
  $table_name = str_database_name(str_replace(".xls","",$xlsFile));
  ?>
   <textarea cols=90 rows=15 class=s onclick="this.focus();this.select()">CREATE TABLE <?= $table_name?> 
  (<? 

  for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
  $v=$data->sheets[0]['cells'][1][$j];
  ?>
  <?=str_database_name($v)?> varchar(255) <? if ($j< $data->sheets[0]['numCols']) {?>,
  <? }
  }
  ?>);
  
  <?
  for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
 	?>INSERT INTO   <?=$table_name?> (<?
	
	//columns
for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
  $v=$data->sheets[0]['cells'][1][$j];
  ?><?=str_database_name($v)?><? if ($j< $data->sheets[0]['numCols']) {?>, <? }	
  }   //end columns
  
  ?>) values (<? 
  
  //values
  for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
  $v=$data->sheets[0]['cells'][$i][$j];
  ?> <?=str_database_value($v)?> <? if ($j< $data->sheets[0]['numCols']) {?>,<?  }
 } // end values
  ?>);
  <?
  
  }    // end all records
  
  
 

  
  }
  ?> </textarea></td></tr></form></table>