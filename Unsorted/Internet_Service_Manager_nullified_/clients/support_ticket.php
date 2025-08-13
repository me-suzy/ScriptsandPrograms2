<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
include "../conf.php";

if($subject && $details && $email){
	list($p, $k)=explode("=", $parent);
	if(mysql_num_rows($res=mysql_query("SELECT * FROM support_tickets WHERE id='$p' && randkey='$k'"))){
	$parentid=$p;
	$randkey=$k;
	$res=mysql_fetch_array($res);
	$allocated=$res[allocated];
	}
	mysql_query("INSERT INTO support_tickets SET allocated='$allocated', priority='$priority', date='".time()."', department='$department', details='$details', parent='$parentid', randkey='$randkey', subject='$subject', name='$name', email='$email', contact_id='$contact_id'  ");
	include $client_template_dir."/support_ticket_done.htm";
		
}else{
	$fp=fopen($client_template_dir."/support_ticket_form.htm", "r");
	while(!feof($fp)){
	$data.=fgets($fp, 1024);
	}
	if($id && $key){
	$vals="$id=$key";
	$subject="Re-Support Ticket: $id";
	$res=mysql_fetch_array(mysql_query("SELECT * FROM support_tickets WHERE id='$id'"));
	$data=str_replace("%contact_id%", $res[contact_id], $data);	
	$data=str_replace("%contact_email%", $res[email], $data);	
	$data=str_replace("%contact_name%", $res[name], $data);	
	}else{
	$data=str_replace("%contact_id%", "$contact_id", $data);	
$contact=mysql_fetch_array(mysql_query("SELECT * FROM contacts WHERE id='$contact_id'"));
	$data=str_replace("%contact_email%", $contact[email], $data);	
	$data=str_replace("%contact_name%", $contact[firstname].' '.$contact[lastname], $data);		
	
	}
	$data=str_replace("%parent_vals%", $vals, $data);

	$data=str_replace("%subject%", $subject, $data);	

	echo $data;
}


?>