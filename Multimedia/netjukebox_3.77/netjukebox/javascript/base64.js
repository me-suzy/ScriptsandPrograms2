// Code based on http://www.aardwulf.com/tutor/base64/



function base64_encode(input)
{
var string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
var output = "";
var chr1, chr2, chr3 = "";
var enc1, enc2, enc3, enc4 = "";
var i = 0;
do 
	{
	chr1 = input.charCodeAt(i++);
	chr2 = input.charCodeAt(i++);
	chr3 = input.charCodeAt(i++);
	enc1 = chr1 >> 2;
	enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
	enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
	enc4 = chr3 & 63;
	if (isNaN(chr2))
		enc3 = enc4 = 64;
	else if (isNaN(chr3))
		enc4 = 64;
	output = output + string.charAt(enc1) + string.charAt(enc2) + string.charAt(enc3) + string.charAt(enc4);
	chr1 = chr2 = chr3 = "";
	enc1 = enc2 = enc3 = enc4 = "";
	}
while (i < input.length);
return output;
}



function base64_decode(input)
{
var string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
var output = "";
var chr1, chr2, chr3 = "";
var enc1, enc2, enc3, enc4 = "";
var i = 0;
do
	{
	enc1 = string.indexOf(input.charAt(i++));
	enc2 = string.indexOf(input.charAt(i++));
	enc3 = string.indexOf(input.charAt(i++));
	enc4 = string.indexOf(input.charAt(i++));
	chr1 = (enc1 << 2) | (enc2 >> 4);
	chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
	chr3 = ((enc3 & 3) << 6) | enc4;
	output = output + String.fromCharCode(chr1);
	if (enc3 != 64)
		output = output + String.fromCharCode(chr2);
	if (enc4 != 64)
		output = output + String.fromCharCode(chr3);
	chr1 = chr2 = chr3 = "";
	enc1 = enc2 = enc3 = enc4 = "";
	}
while (i < input.length);
return output;
}