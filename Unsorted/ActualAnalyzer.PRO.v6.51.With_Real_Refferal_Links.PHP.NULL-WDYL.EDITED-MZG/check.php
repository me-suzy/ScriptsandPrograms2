<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/auth.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('check.php||constructor of config class is fail');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'check',_CHECKDB);
if($err->flag) {
  $err->reason('check.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}
if(isset($GLOBALS['vcheck_h'])) {
    if(!empty($GLOBALS['vcheck_h'])) {
        download($GLOBALS['vcheck_h']);
        if($err->flag) {
          $err->reason('check.php||the \'download\' function has failed');
          $err->log_out();
          $err->scr_out();
          exit;
        }
    }
}
elseif(isset($HTTP_POST_VARS['vcheck_h'])) {
    if(!empty($HTTP_POST_VARS['vcheck_h'])) {
        download($HTTP_POST_VARS['vcheck_h']);
        if($err->flag) {
          $err->reason('check.php||the \'download\' function has failed');
          $err->log_out();
          $err->scr_out();
          exit;
        }
    }
}
else {
    check();
    if($err->flag) {
      $err->reason('check.php||the \'check\' function has failed');
      $err->log_out();
      $err->scr_out();
      exit;
    }
}
exit;

//===================================================================
function createdata($table) {
  global $err,$conf;

  $cm=array();
  echo 'DELETE FROM '.$table.";\n";
  echo 'DROP TABLE '.$table.";\n";
  echo 'CREATE TABLE '.$table."\n(\n";
  $request='SHOW COLUMNS FROM '.$table;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('check.php|createdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $ii=0;
  $n=mysql_num_rows($result);
  while($row=mysql_fetch_row($result)) {
      $str=trim($row[0]).' '.trim($row[1]);
      if(empty($row[2])) $str.=' NOT NULL';
      $a=split('\(',trim($row[1]));
      if(!strcmp($a[0],'char')||!strcmp($a[0],'varchar')) { $cm[$ii]=1; }
      if($ii<$n-1) echo ' '.$str.",\n";
      else echo ' '.$str."\n";
      $ii++;
  }
  echo ");\n\n";
  mysql_free_result($result);

  $request='SELECT * FROM '.$table;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('check.php|createdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $numf=mysql_num_fields($result);
  if(mysql_num_rows($result))
      while($row=mysql_fetch_row($result)) {
          echo 'INSERT INTO '.$table.' VALUES (';
          $str='';
          for($i=0;$i<$numf;$i++) {
              if(!isset($cm[$i])) {
                  if(empty($str)) $str=$row[$i];
                  else $str.=','.$row[$i];
              }
              else {
                  if(empty($str)) $str="'".$row[$i]."'";
                  else $str.=",'".$row[$i]."'";
              }
          }
          echo $str.");\n";
      }
  mysql_free_result($result);
}
//===================================================================
function download($table) {
  global $err,$conf;

  $conf->link=@mysql_connect($conf->dbhost,$conf->dbuser,$conf->dbpass);
  if(!$conf->link) {$err->reason('check.php|check|can\'t connect to mysql server');return;}
  $rez=mysql_select_db($conf->dbase);
  if(!$rez) {$err->reason('check.php|download|can\'t select database '.$conf->dbase.' -- '.mysql_error());return;}

  Header('Content-Type: zip');
  Header('Content-Disposition: inline; filename="'.$table.'.sql"');

  if(!strcmp($conf->dbase,$table)) {
      $request='SHOW TABLES LIKE "aa_%"';
      $resultt=mysql_query($request,$conf->link);
      if(!$resultt) {$err->reason('check.php|download|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($rowt=mysql_fetch_row($resultt)) {
          createdata($rowt[0]);
          if($err->flag) {$err->reason('check.php|download|\'createdata\' function has failed');return;}
      }
      mysql_free_result($resultt);
  }
  else {
      createdata($table);
      if($err->flag) {$err->reason('check.php|download|\'createdata\' function has failed');return;}
  }

  if($conf->link) {
    $rez=mysql_close($conf->link);
    if(!$rez) {$err->reason('check.php|download|can\'t close connect with mysql server');return;}
  }
}
//===================================================================
function check() {
  global $err,$conf;

  $conf->link=@mysql_connect($conf->dbhost,$conf->dbuser,$conf->dbpass);
  if(!$conf->link) {$err->reason('check.php|check|can\'t connect to mysql server');return;}
  $rez=mysql_select_db($conf->dbase);
  if(!$rez) {$err->reason('check.php|check|can\'t select database '.$conf->dbase.' -- '.mysql_error());return;}

  $request='SELECT VERSION()';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('check.php|check|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  mysql_free_result($result);
  $parse=split('\.',$row[0]);
  $checkver=$parse[0].'.'.$parse[1];
  $str=<<<TOP
<html><head><title>Check database '$conf->dbase'</title><SCRIPT LANGUAGE="JavaScript">
<!--
function FormVal(form,val) {form.vcheck_h.value=val}
//-->
</SCRIPT></head><body>
<div align=center><a name="top"></a><form name=check method="post" action="check.php">
<input type=hidden name=uname value="$conf->uname"><input type=hidden name=passw value="$conf->passw">
<input type=hidden name=vcheck_h><table>
TOP;
  $str.='<tr align="center"><td colspan="11" bgcolor="#C0C0C0">Database \'<b>'.$conf->dbase.'</b>\'. MySQL version = '.$row[0].'</td></tr>';
  $str.='<tr bgcolor="#C0C0C0" align="center">';
  $str.='<td>Name</td>';
  $str.='<td>Type</td>';
  $str.='<td>Row format</td>';
  $str.='<td>Rows</td>';
  $str.='<td>Average row\'s length, b</td>';
  $str.='<td>Data length, b</td>';
  $str.='<td>Index length, b</td>';
  $str.='<td>Data free, b</td>';
  $str.='<td>Create time</td>';
  $str.='<td>Max data length, Tb</td>';
  $str.='<td><input width=20 height=20 type=image src="./style/'.$conf->style.'/image/info.gif" title="Download base" border=0 onclick=\'FormVal(check,"'.$conf->dbase.'")\'></td>';
  $str.='</tr>';
  echo $str;
  $sdata=0;
  $sindex=0;
  $srows=0;
  $sfree=0;
  if($checkver>3.22) {
      $request='SHOW TABLE STATUS';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('check.php|check|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          $str='<tr bgcolor="#C0C0C0"">';
          $str.='<td>'.$row->Name.'</td>';
          $str.='<td>'.$row->Type.'</td>';
          $str.='<td>'.$row->Row_format.'</td>';
          $str.='<td align="right">'.$row->Rows.'</td>';
          $str.='<td align="right">'.$row->Avg_row_length.'</td>';
          $str.='<td align="right">'.$row->Data_length.'</td>';
          if($row->Index_length<$row->Data_length) $str.='<td align="right">'.$row->Index_length.'</td>';
          else  $str.='<td align="right"><font color="#FF0000">'.$row->Index_length.'</font></td></td>';
          if($row->Data_free==0) $str.='<td align="right">'.$row->Data_free.'</td>';
          else $str.='<td align="right"><font color="#FF0000">'.$row->Data_free.'</font></td>';
          $str.='<td align="center">'.$row->Create_time.'</td>';
          $str.='<td align="right">'.sprintf("%.3f",$row->Max_data_length/1024/1024/1024).'</td>';
          $str.='<td align="center"><input width=20 height=20 type=image src="./style/'.$conf->style.'/image/info.gif" title="Download '.$row->Name.'" border=0 onclick=\'FormVal(check,"'.$row->Name.'")\'></td>';
          $str.='</tr>';
          echo $str;
          $sdata+=$row->Data_length;
          $sindex+=$row->Index_length;
          $srows+=$row->Rows;
          $sfree+=$row->Data_free;
      }
      mysql_free_result($result);
  }
  else {
      $request='SHOW TABLES LIKE "aa_%"';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('check.php|check|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_row($result)) {
          $avg=0;
          $rows=0;
          $data=0;
          $request='DESCRIBE '.$row[0];
          $resultc=mysql_query($request,$conf->link);
          if(!$resultc) {$err->reason('check.php|check|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($rowc=mysql_fetch_object($resultc)) {
              $parse=split('\(',$rowc->Type);
              $parse=split('\)',$parse[1]);
              $avg+=$parse[0];
          }
          mysql_free_result($resultc);
          $request='SELECT COUNT(*) FROM '.$row[0];
          $resultc=mysql_query($request,$conf->link);
          if(!$resultc) {$err->reason('check.php|check|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($resultc)) {
              $rowc=mysql_fetch_row($resultc);
              $rows=$rowc[0];
          }
          $data=$avg*$rows;
          mysql_free_result($resultc);
          $str='<tr bgcolor="#E0E0E0">';
          $str.='<td>'.$row[0].'</td>';
          $str.='<td></td>';
          $str.='<td></td>';
          $str.='<td align="right">'.$rows.'</td>';
          $str.='<td align="right">'.$avg.'</td>';
          $str.='<td align="right">'.$data.'</td>';
          $str.='<td></td>';
          $str.='<td></td>';
          $str.='<td></td>';
          $str.='<td></td>';
          $str.='<td align="center"><input width=20 height=20 type=image src="./style/'.$conf->style.'/image/info.gif" title="Download '.$row[0].'" border=0 onclick=\'FormVal(check,"'.$row[0].'")\'></td>';
          $str.='</tr>';
          echo $str;
          $sdata+=$data;
          $srows+=$rows;
      }
      mysql_free_result($result);
  }
  $str='<tr align="center"><td colspan="11" bgcolor="#C0C0C0">Total data = '.$sdata.'</td></tr>';
  $str.='<tr align="center"><td colspan="11" bgcolor="#C0C0C0">Total index = '.$sindex.'</td></tr>';
  $str.='<tr align="center"><td colspan="11" bgcolor="#C0C0C0">Total free = '.$sfree.'</td></tr>';
  $str.='<tr align="center"><td colspan="11" bgcolor="#C0C0C0">Total data+index+free = '.($sdata+$sindex+$sfree).'</td></tr>';
  $str.='<tr align="center"><td colspan="11" bgcolor="#C0C0C0">Total rows = '.$srows.'</td></tr>';
  $str.='</table></form></body></html>';
  echo $str;

  if($conf->link) {
    $rez=mysql_close($conf->link);
    if(!$rez) {$err->reason('check.php|check|can\'t close connect with mysql server');return;}
  }
}

?>
