<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$data="";

//first do the header!
$fp=fopen($client_template_dir."/header.htm", "r");
while(!feof($fp)){
$data.=fgets($fp, 1024);
}

$fp=fopen($client_template_dir."/$template_file", "r");
while(!feof($fp)){
$data.=fgets($fp, 1024);
}

//now add the footer!
$fp=fopen($client_template_dir."/footer.htm", "r");
while(!feof($fp)){
$data.=fgets($fp, 1024);
}

//add the standard vars to $tempvars..
$cl=mysql_fetch_array(mysql_query("SELECT * FROM clients WHERE id='$client_id'"));
$tempvars[client_name]=$cl[name];
$tempvars[account_balance]=$payment_unit.$cl[account_balance];
		$invs=mysql_query("SELECT * FROM invoices WHERE paid='0' && to_client='".$cl[id]."'");
	$outstanding=0;
	while($i=mysql_fetch_array($invs)){
   $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$i[id]."'");
                                              $tpaid=0;
											  while($g=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$g[amount];
                                              }
											  $outstanding=$outstanding+$i[amount]-$tpaid;
	}
$tempvars[outstanding_balance]=$payment_unit.$outstanding;

$tempvars[no_unpaid_bills]=mysql_num_rows($invs);
		$invs=mysql_query("SELECT * FROM invoices WHERE to_client='".$cl[id]."'");
		while($i=mysql_fetch_array($invs)){
		$totalbilled=$totalbilled+$i[amount];
		}
$tempvars[total_billed]=$payment_unit.$totalbilled;
$tempvars[no_active_projects]=mysql_num_rows(mysql_query("SELECT * FROM projects WHERE client_id='$client_id'"));

//contact vars!
$contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contact_id'"));
$tempvars[contact_id]=$contact[id];
$tempvars[contact_first_name]=$contact[firstname];
$tempvars[contact_last_name]=$contact[lastname];
$tempvars[contact_email]=$contact[email];
$tempvars[contact_phone]=$contact[phone];
$tempvars[contact_phone2]=$contact[phone2];
$tempvars[contact_address]=nl2br($contact[address]);
$tempvars[contact_title]=$contact[title];
$tempvars[contact_username]=$contact[username];
$tempvars[contact_password]=$contact[password];

//client vars!
$tempvars[client_billing_method]=$cl[billing_method];
$tempvars[client_date_added]=date("F j, Y", $cl[date_added]);
$con=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$cl[bill_to_contact]."'"));
$tempvars[client_billing_contact]=$con[firstname].' '.$con[lastname];
$con=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$cl[primary_contact]."'"));
$tempvars[client_primary_contact]=$con[firstname].' '.$con[lastname];


//project information..
$project_status=array(0=>Active, 1=>Suspended, 2=>Completed, 3=>Proposal);
if($project_id){
$pi=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='$project_id'"));

$tempvars[project_name]=$pi[project_name];
$tempvars[project_status]=$project_status[$pi[status]];
$tempvars[start_date]=date("F j, Y", $pi[start_date]);
	if($pi[finish_date]>0){
	$tempvars[finish_date]=date("F j, Y", $pi[finish_date]);
	}else{
	$tempvars[finish_date]="Unkown";
	}
$tempvars[bill_in]=$billing_methods[$pi[bill_in]];
$con=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='".$pi[project_manager]."'"));
$tempvars[project_manager]=$con[firstname].' '.$con[lastname];

$fp=fopen($client_template_dir."/stage.htm", "r");
while(!feof($fp)){
$sformat.=fgets($fp, 1024);
}

//print stage info..
$stages=mysql_query("SELECT * FROM project_stages WHERE project_id='$project_id'");
while($s=mysql_fetch_array($stages)){
	$thisstage=str_replace("%stage_name%", $s[stage_name], $sformat);
	$thisstage=str_replace("%description%", nl2br($s[description]), $thisstage);
	$thisstage=str_replace("%details%", nl2br($s[details]), $thisstage);
	$thisstage=str_replace("%cost%", $payment_unit.$s[cost], $thisstage);
	$thisstage=str_replace("%start_date%", date("F j, Y", $s[start_date]), $thisstage);
	if($s[finish_date]>0){
		$thisstage=str_replace("%finish_date%", date("F j, Y", $s[finish_date]), $thisstage);
	}else{
		$thisstage=str_replace("%finish_date%", "Unknown", $thisstage);
	}
	
	$comp="No";if($s[completed]){$comp="Yes";}
	$billed="No";if($s[billed]){$billed="Yes";}
	$thisstage=str_replace("%completed%", $comp, $thisstage);
	$thisstage=str_replace("%billed%", $billed, $thisstage);

	
$allstages.=$thisstage;
}
$tempvars[stages]=$allstages;
}

//list projects..
$fp=fopen($client_template_dir."/project_list.htm", "r");
while(!feof($fp)){
$pformat.=fgets($fp, 1024);
}

$res=mysql_query("SELECT * FROM projects WHERE client_id='$client_id' ORDER BY status ASC");
while($r=mysql_fetch_array($res)){
$thisproject=str_replace("%project_name%", $r[project_name], $pformat);
$thisproject=str_replace("%project_status%", $project_status[$r[status]], $thisproject);
$thisproject=str_replace("%start_date%", date("F j, Y", $r[start_date]), $thisproject);
	if($r[finish_date]>0){
	$thisproject=str_replace("%finish_date%", date("F j, Y", $r[finish_date]), $thisproject);
	}else{
	$thisproject=str_replace("%finish_date%",  "Unknown", $thisproject);
	}
$thisproject=str_replace("%more_url%",  'index.php?projectinfo:'.$r[id], $thisproject);
$allprojects.=$thisproject;
}
$tempvars[list_projects]=$allprojects;

//list all bills..
$res=mysql_query("SELECT * FROM invoices WHERE to_client='$client_id' ORDER BY paid ASC, due_date");
$fp=fopen($client_template_dir."/billing_items.htm", "r");
while(!feof($fp)){
$bformat.=fgets($fp, 1024);
}

while($r=mysql_fetch_array($res)){
$thisbill=str_replace("%invoice_date%", date("F j, Y", $r[date]), $bformat);
$thisbill=str_replace("%due_date%", date("F j, Y", $r[due_date]), $thisbill);
$thisbill=str_replace("%amount%", $payment_unit.$r[amount], $thisbill);
    $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$r[id]."'");
                                              $tpaid=0;
                                              while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
											  $owing=$r[amount]-$tpaid;
											  $owing=$payment_unit.$owing." still owing";
											  $status="Un-Paid";
											  if($r[paid]==1){$owing="Settled";$status="Paid";}
$thisbill=str_replace("%still_owing%", $owing, $thisbill);
$thisbill=str_replace("%bill_status%", $status, $thisbill);
$pr=mysql_fetch_array(mysql_query("SELECT * FROM projects WHERE id='".$r[project_id]."'"));
$thisbill=str_replace("%bill_project%", $pr[project_name], $thisbill);
$stages=explode(",", $r[stage_id]);
$all_stages="";
foreach($stages as $stage){
if($stage){
$st=mysql_fetch_array(mysql_query("SELECT * FROM project_stages WHERE id='$stage'"));
$all_stages.=$st[stage_name]."/";
}
}
$thisbill=str_replace("%bill_stages%", $all_stages, $thisbill);
$thisbill=str_replace("%show_invoice_url%", "index.php?showinvoice:".$r[id], $thisbill);
$allbills.=$thisbill;
}
$tempvars[list_bills]=$allbills;

if($invoice_id){
//print the invoice data!
$res=mysql_fetch_array(mysql_query("SELECT * FROM invoices WHERE id='$invoice_id'"));
if($res[sent_type]==email){
$tempvars[show_invoice]=nl2br($res[data]);
}else{
$tempvars[show_invoice]=$res[data];
}

}


//now print out a list of all the files and folders!
if($folder){$handle=opendir($upload_dir.'/'.$client_id.'/'.$folder);

while (($file = readdir($handle))!==false) {
      if($file!="." && $file!=".."){
    //sort them into dirs and files..
      $thisfile=$upload_url.'/'.$client_id.'/'.$folder.$file;
      if(is_dir($upload_dir.'/'.$client_id.'/'.$folder.$file)){
         $alldirs[$file]=$thisfile;
      }else{
         $allfiles[$file]=$thisfile;
      }
  }
}
@ksort($allfiles);
@ksort($alldirs);

//get the format for listing items!
$fp=fopen($client_template_dir."/file_list_format.htm", "r");
while(!feof($fp)){
$fformat.=fgets($fp, 1024);
}


if(substr($folder, 0,1)=="/"){
$folder=substr($folder,1);
}
  
  if($folder){
  $folder2="/".$folder;
      $places=explode("/", $folder2);
      $items=count($places);
      for($r=0; $r<$items-2; $r++){
           $upfolder.=$places[$r].'/';
   }
   }

  $upfolder=str_replace("//", "", $upfolder);
  $folder=str_replace("//", "", $folder);

//echo move up link..
if($upfolder){$thisfolder=str_replace("%filename%", "Move Up", $fformat);
$thisfolder=str_replace("%file_url%", "index.php?files:$upfolder", $thisfolder);
$thisfolder=str_replace("%size%", "-", $thisfolder);
$thisfolder=str_replace("%icon%", "$image_url/icons/move_up.gif", $thisfolder);
$allfolders.=$thisfolder;
}

//do the folders!
if($alldirs){foreach($alldirs as $filename=>$thisfile){
$thisfolder=str_replace("%filename%", $filename, $fformat);
$thisfolder=str_replace("%file_url%", "index.php?files:$folder$filename/", $thisfolder);
$thisfolder=str_replace("%size%", "-", $thisfolder);
$thisfolder=str_replace("%icon%", "$image_url/icons/folder.gif", $thisfolder);
$allfolders.=$thisfolder;
}}
$tempvars[list_folders]=$allfolders;

//do the folders!
if($allfiles){foreach($allfiles as $filename=>$thisfile){
$thisfile2=str_replace("%filename%", $filename, $fformat);
$thisfile2=str_replace("%file_url%", $thisfile, $thisfile2);
$thisfile2=str_replace("%size%", round(filesize($upload_dir.'/'.$client_id.'/'.$folder.'/'.$filename)/1000, 1)."kb", $thisfile2);
          //try and establish its file-type and grab an icon for it..
             list(,$type)=explode(".", $filename);
              if(is_file($image_dir.'/icons/'.$type.'.gif')){
                   $icon=$image_url.'/icons/'.$type.'.gif';
              }else{
                   $icon=$image_url.'/icons/unknown.gif';
              }
$thisfile2=str_replace("%icon%", $icon, $thisfile2);			  
$allfiles2.=$thisfile2;
}}
$tempvars[list_files]=$allfiles2;
}

if($tempvars){foreach($tempvars as $name=>$value){
$data=str_replace("%$name%", $value, $data);
}}

echo $data;





?>