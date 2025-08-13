<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$section="export";
include "../conf.php";
include "auth.php";

$cond=str_replace('\\', "", $cond);
$res=mysql_query("SELECT * FROM $fromtable $cond");

$fields=explode(",", $fields);

foreach($fields as $field){
list($field,$info)=explode("|", $field);
if($field){$titlebar.="$field,";}
}
reset($fields);
$data="";
while($r=mysql_fetch_array($res)){
$thisline="";
foreach($fields as $field){
if($field){
list($field,$info)=explode("|", $field);
	if($info=="account"){
		if($r[$field]==0){
			$field="Account";
		}else{
			$field=$r[$field];
		}
	}elseif($info=="binary"){
	    $field=$r[$field];
		if($field==1){$field="Yes";}else{$field="No";}
	}elseif($info=="date"){
	   if($r[$field]){
	  $field=date("F j, Y, g:i a", $r[$field]);
	  }else{
	  $field="Unknown";
	  }
	}elseif($info){
	//this field is to be sources from somewhere else!
	//fieldone/fieldtwo-contacts-id- |primary_contact
	$field=$r[$field];
	list($efields,$etable,$efield, $del)=explode("-", $info);
	$field=trim($field);
	$fes=mysql_fetch_array(mysql_query("SELECT * FROM $etable WHERE $efield='$field'"));
	     $field=$del;
		 $efields=explode("/", $efields);
		 foreach($efields as $efield){
		 $field.=$del.$fes[$efield];
		 }
	}else{
	$field=$r[$field];	
	}
	
	$thisline.=str_replace(",", "", $field).",";
}
}

$data.=$thisline."\n";
}

$data=$titlebar."\n".$data;

header("Content-Type:text/plain ; name=\"$fromtable.csv\"");
header("Content-Disposition: attatchment; filename=\"$fromtable.csv\"\r\n");
                        echo $data;

?>