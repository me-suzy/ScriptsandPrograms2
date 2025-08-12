<?php
/***********************************************/
/* All Code Copyright 2004 Matthew Frederico   */
/* Under the COPv1 License (Contribute or Pay) */
/***********************************************/
/* Author: matt@ultrize.com                    */
/* Url   : http://www.ultrize.com/minibill     */
/***********************************************/

function recurVal($is_recurring = 'no')
{
        switch ($is_recurring)
        {
            //.. Recurs
            case 'no'       : $recurring = 0;   break;
            case 'Daily'    : $recurring = 1;   break;
            case 'Weekly'   : $recurring = 7;   break;
            case 'Monthly'  : $recurring = 30;  break;
            case 'Quarter'  : $recurring = 92;  break;
            case 'Semi'     : $recurring = 182; break;
            case 'Yearly'   : $recurring = 365; break;
        }
		return $recurring;
}


function charge_card($data)
{
	//...... Will eventually move to separate modules.
    //...... Authorize.net credit card verification
    if ($ADMIN) $postvars[x_test_request] = 'true';
    $postvars[x_Amount]     = $amount;
    $postvars[x_Card_Code]  = $cvv;
    $postvars[x_Card_Num]   = $data[user][cardnum];
    $postvars[x_Cust_ID]    = $id;
    $postvars[x_invoice_num]= $uniq_id;
    $postvars[x_description]= $data[user][shipping];
    $postvars[x_Email]      = $data[user][email];
    $postvars[x_Exp_Date]   = $data[user][cc_exp_mo].'/'.$data[user][cc_exp_yr];
    $postvars[x_First_Name] = $data[user][firstname];
    $postvars[x_Last_Name]  = $data[user][lastname];
    $postvars[x_Address]    = $data[user][address];
    $postvars[x_City]       = $data[user][city];
    $postvars[x_State]      = $data[user][state];
    $postvars[x_Zip]        = $data[user][zipcode];
    $postvars[x_phone]      = $data[user][phone];
    $postvars[x_Country]    = $data[user][country];
    $postvars[x_Comments]   = $data[user][shipping];

}


/* Applies a coupon to the given total amount */
function applyCoupon($coupon,$grand_total)
{
	if ($coupon['type'] == 'amount') 
		$grand_total = $grand_total - ($_SESSION['coupon']['amount']);
	elseif ($coupon['type'] == 'percent') 
		$grand_total = $grand_total - ceil(($grand_total * ($_SESSION['coupon']['amount'] / 100)));

	return($grand_total);
}

/* Builds the data SET query for updates or inserts */
function buildSet($vars,$whereid,$tableName)
{
	$is_update =0;
	foreach($vars as $key=>$val)
	{
		if ($key == $whereid)
		{
			$is_update =1;
			$WHERE = "WHERE $whereid='$val'";
			continue;
		}
		$set .= "$key='".addslashes($val)."',";
	}
	$set = substr($set,0,strlen($set)-1);

	if ($is_update)
	{
		$Q="UPDATE $tableName SET $set $WHERE";
	}
	else
	{
		$Q="INSERT INTO $tableName SET $set";
	}

	return($Q);
}


function getEnumValues($Table,$Column)
{
   $dbSQL = "SHOW COLUMNS FROM ".$Table." LIKE '".$Column."'";
   $dbQuery = mysql_query($dbSQL);

   $dbRow = mysql_fetch_assoc($dbQuery);
   $EnumValues = $dbRow["Type"];

   $EnumValues = substr($EnumValues, 6, strlen($EnumValues)-8);
   $EnumValues = str_replace("','",",",$EnumValues);

   return explode(",",$EnumValues);
}

function time_left($seconds)
{
    $days = intval($seconds / 86400);
    $rem = fmod($seconds,86400);

    $hours = sprintf("%02d",intval($rem / 3600));
    $rem = fmod($rem,3600);

    $minutes = sprintf("%02d",intval($rem / 60));
    $rem = fmod($rem,60);

    $seconds = sprintf("%02d",intval($rem));
    return (array('days'=>$days,'hours'=>$hours,'minutes'=>$minutes,'seconds'=>$seconds));
}

function is_admin_ip($ip_addr)
{
	global $config;
	$ips = split(",",$config[admin_ip_list]);
	foreach($ips as $ip)
	{
		if ($ip == $ip_addr) return(1);
	}
	return(0);
}


function is_email($email){
return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
}

//Encrypt Function
function encrypt($encrypt) 
{
	global $key;
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt, MCRYPT_MODE_ECB, $iv);
	$encode = base64_encode($passcrypt);
	return $encode;
}
 
//Decrypt Function
function decrypt($decrypt) 
{
	global $key;
	$decoded = base64_decode($decrypt);
	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_ECB, $iv);
	return $decrypted;
} 


?>
