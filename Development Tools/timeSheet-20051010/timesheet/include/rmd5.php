<?php 
function binmd5($val)
{
	return(pack("H*",md5($val)));
}
function keyED($txt,$encrypt_key) 
{ 
	$encrypt_key = md5($encrypt_key); 
	$ctr=0; 
	$tmp = ""; 
    #print "TXT: ".strlen($txt)."\n";
    #print "ENC: ".strlen($encrypt_key)."\n";
    #print "B64: ".base64_encode($encrypt_key)."\n";
	for ($i=0;$i<strlen($txt);$i++) 
	{ 
		if ($ctr==strlen($encrypt_key)) $ctr=0; 
		$tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1); 
		$ctr++; 
	} 
	return $tmp; 
} 

function bencrypt($txt,$key)
{
	return base64_encode(encrypt($txt,$key));
}

function bdecrypt($txt,$key)
{
	return decrypt(base64_decode($txt),$key);
}

function encrypt($txt,$key) 
{ 
	srand((double)microtime()*1000000); 
	$encrypt_key = md5(rand(0,32000)); 
	$ctr=0; 
	$tmp = ""; 

	for ($i=0;$i<strlen($txt);$i++) 
	{ 
		if ($ctr==strlen($encrypt_key)) $ctr=0; 
		$tmp.= substr($encrypt_key,$ctr,1) . 
		(substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1)); 
		$ctr++; 
	} 
	return keyED($tmp,$key); 
} 

function decrypt($txt,$key) 
{ 
	$txt = keyED($txt,$key); 
	$tmp = ""; 
	for ($i=0;$i<strlen($txt);$i++) 
	{ 
		$md5 = substr($txt,$i,1); 
		$i++; 
		$tmp.= (substr($txt,$i,1) ^ $md5); 
	} 
	return $tmp; 
} 
?>
