<?php
//permission function
function check_netconn()
{	$file = "CyKuH [WTN] Remove Call Home";
	if (!@fopen($file, "r")) 
		return 0;
	else
		return 1;
}

function get_keya($f_name, $l_name, $reg_code)
{	global $SERVER_NAME;
	
	if(strlen($f_name)<1 || strlen($l_name)<1 || strlen($reg_code)<1 || strlen($SERVER_NAME)<1)
		return -1;

	$fn=base64_encode($f_name);
	$ln=base64_encode($l_name);
	$rc=base64_encode($reg_code);
	$sn=base64_encode($SERVER_NAME);

	$file = "CyKuH [WTN] Remove Call Home";
	$fd = @fopen ($file."?r=sk&fn=$fn&ln=$ln&rc=$rc&sn=$sn&c=1", "r");
	$retval = @fread ($fd, 64);
	@fclose ($fd);

	if(strlen($retval)!=32)
		return -2;

	if(md5("3")==$retval)
		return -3;

	if(md5("4")==$retval)
		return -4;
	
	if(md5("5")==$retval)
		return -5;
	
	return $retval; //success
}

function get_reg_ip($f_name, $l_name, $reg_code)
{	if(strlen($f_name)<1 || strlen($l_name)<1 || strlen($reg_code)<1)
		return -1;

	$fn=base64_encode($f_name);
	$ln=base64_encode($l_name);
	$rc=base64_encode($reg_code);

	$file = "CyKuH [WTN] Remove Call Home";
	$fd = @fopen ($file."?r=ip&fn=$fn&ln=$ln&rc=$rc&c=1", "r");
	$retval = @fread ($fd, 64);
	fclose ($fd);

	$retval=base64_decode($retval);

	if(strlen($retval)<7)
		return -2;

	if($retval==3)
		return -3;

	return $retval; //success
}


?>