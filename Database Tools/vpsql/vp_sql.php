
<?php
//vpSQL
//copyright 2005 A. C. Kulik inc
//released under terms of GPL
//Use, change and distribute it as you wish as long as this heading is included.
//Use at your own risk. I accept no responsibility for loss or damage.
//See the included docs.html for instructions

$DBname="/vpxlite/admin/data/"; //path to the database directory relative root leading following slasges

//Don't change anything below
$dataset='';
$DBname=$_SERVER['DOCUMENT_ROOT'].$DBname;
function vp_query($sql,$resource='')
{
global $DBname,$fields,$autoinc,$dataset;
$action=strtoupper(trim(substr($sql,0,strpos($sql,' '))));
$sql=trim(substr($sql,strpos($sql,' ')));
switch ($action)
{
 case 'SELECT':
 {
 global $fields;
   $p=false;
   if (strpos($sql,'LIMIT')!==false) {$p = strpos($sql,'LIMIT');}
   elseif (strpos($sql,'limit')) {$p = strpos($sql,'limit');}
   if ($p!==false)
   {
     $limit=trim(substr($sql,$p+5));
     $sql=trim(substr($sql,0,$p));
   }
   $p=false;
   if (strpos($sql,'ORDER BY')!==false) {$p = strpos($sql,'ORDER BY');}
   elseif (strpos($sql,'order by')) {$p = strpos($sql,'order by');}
   if ($p!==false)
   {
     $orderby=trim(substr($sql,$p+8));
     $sql=trim(substr($sql,0,$p));
   }
   $p=false;
   $p=strpos($sql,'WHERE');
   if (strpos($sql,'WHERE')!==false) {$p = strpos($sql,'WHERE');}
   elseif (strpos($sql,'where')) {$p = strpos($sql,'where');}
   if ($p!==false)
   {
     $where=trim(substr($sql,$p+5));
     $sql=trim(substr($sql,0,$p));
   }
   $p=false;
   if (strpos($sql,'FROM')!==false) {$p = strpos($sql,'FROM');}
   elseif (strpos($sql,'from')) {$p = strpos($sql,'from');}
   if ($p!==false)
   {
     $table=trim(substr($sql,$p+4));
     $sql=trim(substr($sql,0,$p));
   }
  $p=false;
  $result=opentable($table,$where);
  if (!$result) return false;
  if ($where<>'')
  {
   $where = formatwhere($where,$fields);
   if ($where)
   {
     $i= count($result);
     for ($n=0;$n<$i;$n++) if(filter($result[$n],$where,$fields)==false) unset($result[$n]);
   }
   else return false;
  }
  if ($orderby <> '')
  {
   $orderby = explode(' ',$orderby);
   if (count($orderby) == 1)
   {
     $result = order_by($result,$orderby[0]);
   }
   else
   {
     $orderby[1]=strtoupper($orderby[1]);
     $result = order_by($result,$orderby[0],$orderby[1]);
   }
   if ($limit)
   {
     $limit = explode(',',$limit);
     if (!isset($limit[1])) $result=array_slice($result,0,$limit[0]);
     else $result=array_slice($result,$limit[0]-1,$limit[1]);
   }
  }
  $dataset[]=$result;
  $n=end($dataset);
  $k=key($dataset);
  reset($dataset);
  return $k;
  break;
 }

  case 'INSERT':
  {
   $sql=trim(substr($sql,4));
   $p=strpos($sql,' ');
   $table=trim(substr($sql,0,$p));
   $sql=trim(substr($sql,$p));
   unset($p);
$insertfields= substr($sql, strpos($sql,'(')+1,$l= strpos($sql,')') -1 -strpos($sql,'('));
$values= substr($sql, strpos($sql,"'")+1,strrpos($sql,"'")-strpos($sql,"'") -1 );
if (strrpos($insertfields,"'")) unset($insertfields);
   if (isset($insertfields))
   {
     $insertfields=trim($insertfields);
     $insertfields=explode(',',$insertfields);
   }
   $d=array("','","', '","' ,'");
   $values=str_replace($d,"|",$values);
   $values=trim($values," ('')");
   $values = str_replace("\r\n", "[NL]", $values);
   $values = str_replace("\n\r", "[NL]", $values);
   $values = str_replace("\n", "[NL]", $values);
   $values = str_replace("\r", "[NL]", $values);
   $values = str_replace(";", "[SC]", $values);
   $values=explode("|",$values);
   $tablename = $DBname.$table.".vpd";
   $f = @fopen($tablename, "r+");
   if (!$f) return false;
   flock($f,LOCK_EX);
   $tmp = fgets($f);
   $tmp=trim($tmp);
   list($id,$fields)=explode(":",$tmp);
   $id = $id + 1;
   $fields=explode(';',$fields);
   array_shift($fields);

   if (count($values) == count($fields))
   {
     $values=implode(';',$values);
     $values="$id;$values\n";
   }
   elseif ((isset($insertfields))and(count($values) <> count($fields)))
   {
     for ($i=0;$i<count($fields);$i++)
     {
       $pos=array_search($fields[$i],$insertfields);
       if ($pos===false) $tmpfields="$tmpfields;";
       else $tmpfields="$tmpfields;$values[$pos]";
       $t=$values[$pos];
     }
   $values="$id$tmpfields\n";
   }
   else
   {
     fclose($f);
     return false;
   }
   fseek($f,0,SEEK_END);
   fwrite($f,$values);
   $id = str_pad($id,4,0,STR_PAD_LEFT);
   fseek($f,0);
   fwrite($f,$id);
   flock($f,LOCK_UN);
   fclose($f);
   return true;
   break;
  }
  case 'UPDATE':
  {
    global $fields;
    $table=trim(substr($sql,0,strpos($sql,' ')));
    $tmp=trim(stristr($sql,' '));
    $tmp=trim(stristr($tmp,' '));
    $tmp=strrev($tmp);
    $temp1=trim(stristr($tmp,'erehw'));
    $update=trim(substr($temp1,strpos($temp1,' ')));
    $search=trim(substr($temp1,0,strpos($temp1,' ')));
    $where=trim(substr($tmp,0,strpos($tmp,$search)));
    $where=strrev($where);
    $update=strrev($update);
    $update=str_replace("',","|",$update);
    $update=str_replace("' ,","|",$update);
    $update=explode("|",$update);
    $result=opentable($table);
    if ($where<>'')
    {
     $where = formatwhere($where,$fields);
     for ($n=0;$n<count($result);$n++)
     {
       if(filter($result[$n],$where,$fields))
       {
         for ($i=0;$i<count($update);$i++)
         {
           list($key,$value)=explode('=',$update[$i]);
           $result[$n][$key]=trim($value,"'");
         }
       $updated=$updated+1;
       }
     }
    }
    flushtable($table,$result);
    return $updated;
    break;
  }
 case 'DELETE':
 {
 global $fields;
 $deleted =0;
   if (strpos($sql,'WHERE')!==false) {$p = strpos($sql,'WHERE');}
   else{$p = strpos($sql,'where');}
   if ($p!==false)
   {
     $where=trim(substr($sql,$p+5));
     $sql=trim(substr($sql,0,$p));
   }
   unset($p);
   if (strpos($sql,'FROM')!==false) {$p = strpos($sql,'FROM');}
   else{$p = strpos($sql,'from');}
   if ($p!==false)
   {
     $table=trim(substr($sql,$p+4));
     $sql=trim(substr($sql,0,$p));
   }
   unset($p);
  $result=opentable($table);
  if ($where<>'')
  {
   $where = formatwhere($where,$fields);
   if ($where)
   {
     $i= count($result);
     for ($n=0;$n<$i;$n++)
     {
       if(filter($result[$n],$where,$fields))
       {
         unset($result[$n]);
         $deleted=$deleted+1;
       }
     }
   }
   else return false;
  }
  else
  {
    $fields=implode(';',$fields);
    $deleted=count($result);
    $fields = "0000:$fields\n";
    $f=@fopen($DBname.$table.'.vpd',"w");
    if (!$f) return false;
    fwrite($f,$fields);
    fclose($f);
    return $deleted;
  }
  flushtable($table,$result);
  return $deleted;
  }
  break;
 }
}

  function vp_fetch_array($rid)
  {
    global $dataset;
    if ($dataset[$rid]==false) return false;
    $return=current($dataset[$rid]);
    next($dataset[$rid]);
    return $return;
  }

  function vp_num_rows($rid)
  {
    global $dataset;
    if ($dataset[$rid]==false) return false;
    return count($dataset[$rid]);
  }

  function vp_insert_id($table)
  {
    global $DBname;
    $table=$DBname.$table.'.vpd';
    $f = @fopen($table, "rb");
    if (!$f) return false;
    flock($f,LOCK_SH);
    $id=fread($f,4);
    flock($f,LOCK_UN);
    fclose($f);
    return ltrim($id,'0');
  }
// Non-sql functions

  function vp_createtable($tablename,$fields)  //Name of table and comma delimited string of field names
  {
    global $DBname;
    $fields = str_replace(',',';',$fields);
    $fields = "0000:vpid;$fields\n";
    $f=@fopen($DBname.$tablename.'.vpd',"w");
    if (!$f) return false;
    fwrite($f,$fields);
    fclose($f);
    return true;
  }

  function vp_delete_table($table)
  {
    global $DBname;
    return unlink($DBname.$table.'.vpd');
  }

  function vp_getfields($table)
  {
    global $DBname;
    $f = @fopen($DBname.$table.'.vpd', "rb");
    if (!$f) return false;
    $fields = fgets($f);
    trim($f);
    $fields=explode(':',$fields);
    $fields=explode(';',$fields[1]);
    return $fields;
  }

  function opentable($tablename)
  {
    global $DBname,$fields,$autoinc;
    $tablename = $DBname.$tablename.".vpd";
    $f = @fopen($tablename, "rb");
    if (!$f) return false;
    flock($f,LOCK_SH);
    fseek($f,0,SEEK_END);
    $filesize = ftell($f);
    fseek($f,0,SEEK_SET);
    $dbVar = trim(fread($f,$filesize));
    str_replace("\r","",$dbVar);
    flock($f,LOCK_UN);
    fclose($f);
    $dbVar = explode("\n", $dbVar);
    for ($i=0;$i<count($dbVar);$i++) trim($dbVar[$i]);

    $tmp = array_shift($dbVar);
    trim($tmp);
    $tmp = explode(":",$tmp);
    $autoinc=$tmp[0];
    $fields=explode(";",$tmp[1]);
    for ($n=0;$n<count($dbVar);$n++)
    {
      trim($dbVar[$n]);
      $dbVar[$n]= format_rec($dbVar[$n],$fields);
    }
    return $dbVar;
  }

  function flushtable ($tablename,$table)
  {
    global $DBname,$fields,$autoinc;
    for ($i=0;$i<count($table);$i++)
    {
      $table[$i] = str_replace("\r\n", "[NL]", $table[$i]);
      $table[$i] = str_replace("\n\r", "[NL]", $table[$i]);
      $table[$i] = str_replace("\n", "[NL]", $table[$i]);
      $table[$i] = str_replace("\r", "[NL]", $table[$i]);
      $table[$i] = str_replace(";", "[SC]", $table[$i]);
      $table[$i] = @implode(";",$table[$i]);
    }
      $tmp = implode(";",$fields);
      str_pad($autoinc,'0');
      $tmp = "$autoinc:$tmp";
      if (count($table)==0) $table=$tmp."\n";
      else
      {
        array_unshift($table,$tmp);
        $table = implode("\n",$table);
      }
      $f=fopen($DBname.$tablename.'.vpd', "wb");
      flock($f,LOCK_EX);
      fwrite($f,$table);
      flock($f,LOCK_UN);
      fclose($f);
  }
  function format_rec($rec,$fields)
  {
    if (!is_array($rec)) $rec = explode(";",$rec);
    for ($i=0;$i<count($fields);$i++)
    {
      $record[$fields[$i]] = $rec[$i];
    }
    $record=str_replace("[NL]","\n",$record);
    $record=str_replace("[SC]",";",$record);
    return $record;
   }

   function formatwhere($where,$fields)
   {
    if (strpos($where,' and ')){$where = str_replace('and',') and (',$where); $bracket=true;}
    if (strpos($where,' AND ')){$where = str_replace('AND',') and (',$where); $bracket=true;}
    if (strpos($where,' or ')){$where = str_replace('or',') or (',$where); $bracket=true;}
    if (strpos($where,' OR ')){$where = str_replace('OR',') or (',$where); $bracket=true;}
    if (strpos($where,' not ')){$where = str_replace('not',') not (',$where); $bracket=true;}
    if (strpos($where,' NOT ')){$where = str_replace('NOT',') not (',$where); $bracket=true;}
    if (strpos($where,' xor ')){$where = str_replace('not',') xor (',$where); $bracket=true;}
    if (strpos($where,' XOR ')){$where = str_replace('NOT',') xor (',$where); $bracket=true;}
    $where=str_replace(">=","|",$where);
    $where=str_replace("<=","||",$where);
    $where = str_replace('=',' == ',$where);
    $where=str_replace("|",">=",$where);
    $where=str_replace("||","<=",$where);
    if ($bracket) $where = "($where)";
   $F=strstr($where,'(');
   if ($F)
   {
     $F=trim(substr($F,1,strpos($F,' ')));
     if (!in_array($F,$fields)) return false;
     $F=strstr($where,')');
     $F=strstr($F,'(');
     $F=trim($F,"(' '");
     $F=trim(substr($F,0,strpos($F,' ')));
     if (!in_array($F,$fields)) return false;
   }
   else
   {
     $F=trim(substr($where,0,strpos($where,' ')));
     if (!in_array($F,$fields)) return false;
   }
    return $where;
   }

   function filter($record,$where,$fields)
   {
    $filter = 'if ('.$where.') $filtered=true; else $filtered=false;';
    while (list ($fieldNr, $fieldName) = each ($fields))
    {
      $filter = str_replace($fieldName,'$record['.$fieldName.']', $filter);
    }
    eval($filter);
    return $filtered;
   }

   function order_by($table,$column,$type="ASC") // or $type=DESC
   {
     if (count($table)==0) return false;
     foreach ($table as $row) $sortarr[] = strtolower($row[$column]);
     if ($type=="ASC") $sortflag=SORT_ASC; else $sortflag=SORT_DESC;
     array_multisort($sortarr,$sortflag,$table);
     return $table;
   }
?>