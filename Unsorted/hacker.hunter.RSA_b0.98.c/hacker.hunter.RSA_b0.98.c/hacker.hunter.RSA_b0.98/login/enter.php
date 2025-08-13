<?
include ("inc.php");
include ("rsa.php");
session_start();
if (session_is_registered("login_ip")){
	if ($HTTP_SESSION_VARS["login_ip"] == $HTTP_SERVER_VARS["REMOTE_ADDR"]){
		$keys = generate_keys ();
		$key1=$keys[0];
		$key2=$keys[2];
		session_register ("key1");
		session_register ("key2");?>
function rsa_encode (m, e, n) {
	var coded = "";
	var zero = "0";
	var one = "1";
	for (i=0; i<m.length; i+=3) {
		tmpasci=one.toString();
		for (h=0; h<3; h++) {
			if (i+h < m.length){
				tmpstr = (m.charCodeAt(i+h) - 30).toString();
				if (tmpstr.length < 2) {
					tmpstr = zero.toString() + tmpstr.toString();
				}
			} else {
				break;
			}
			tmpasci += tmpstr.toString();
		}
		tmpasci = tmpasci.toString() +one.toString();
		coded += (powmod(tmpasci, e, n)) + " ";
	}
	return coded.substring(0, coded.length-1);
}

function powmod(base, expi, modulus) {
	var accum = 1;
	var i = 0;
	var basepow2 = base;
	while ((expi >> i)>0) {
		if (((expi >> i) & 1) == 1) {
			accum = (accum * basepow2) % modulus;
		}
		basepow2 = (basepow2 * basepow2) % modulus;

		i++;
	}
	return accum;
}
function submitIt() {
var g= document.login.form_password;
g.value=rsa_encode (g.value, <? echo $keys[1].", ".$keys[0].");\n";
echo "document.login.submit();\n}\n";
	} else {
		session_unregister ("login_ip");
	}
} else {
session_destroy();
	echo "Hello !";
}?>