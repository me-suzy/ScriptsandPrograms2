<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
    $username=$PHP_AUTH_USER;
    $password=$PHP_AUTH_PW;
                    if(mysql_num_rows($data=mysql_query("SELECT * FROM contacts WHERE username='$username' && password='$password' && username!=''"))!=1){
			Header("WWW-authenticate: basic realm=\"Client Area Login\"");
            Header("HTTP/1.0 401 Unauthorized");
            exit;
            }
$contact_id=mysql_fetch_array($data);
			$client_id=$contact_id[client_id];
            $contact_id=$contact_id[id];



?>