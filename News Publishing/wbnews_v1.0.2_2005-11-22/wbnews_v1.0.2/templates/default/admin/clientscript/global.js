/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created:  15th May 2005                          #||
||#     Filename: global.js                              #||
||#                                                      #||
||########################################################||
/*========================================================*/

/* Browsers */
var userAgent 	= navigator.userAgent.toLowerCase();
var is_op 		= (userAgent.indexOf('opera') != -1); //is opera
var is_saf 		= (userAgent.indexOf('safari') != -1); //is safari
var is_ie 		= (userAgent.indexOf('msie') != -1 && (!is_op) && (!is_saf)); //is internet explorer
var is_nn 		= (userAgent.indexOf('netscape') != -1); //is netscape
var is_moz 		= ((navigator.product == 'Gecko') && (!is_saf)); //is mozilla

/* GLOBAL FUNCTIONS */
function inArray(value, array)
{
	if (array.length === 0)
	{
		return false;
	}
	else
	{
		for (var i = 0; i < array.length; i = i + 1)
		{
			if (array[i] == value)
			{
				return true;
			}
		}
		return false;
	}
}
	
function removeArrayElement(id, array)
{
	if (!inArray(id, array))
	{
		return array;
	}
	else
	{
		var tmp = new Array(array.length - 1); 
		var b = 0;
		
		for (var i = 0; i < array.length; i = i + 1)
		{
			if (array[i] != id)
			{
				tmp[b] = array[i];
			}
			else
			{
				b = b - 1;
			}
			b = b + 1;
		}
		return tmp;
	}
}

function getObject(id)
{
	if (document.getElementById(id) !== null)
	{
		return document.getElementById(id);
	} 
}

/* Hex, RGB */
function giveHex(code)
{
	var value;
	
	switch (code)
	{
	case 10:
		value = "A";
	break;
	case 11:
		value = "B";
	break;
	case 12:
		value = "C";
	break;
	case 13:
		value = "D";
	break;
	case 14:
		value = "E";
	break;
	case 15:
		value = "F";
	break;
	default:
		value = code;
	break;
	}
	
	return value;
}

function giveDec(code)
{
	var value;
	
	switch (code)
	{
	case 'a':
		value = 10;
	break;
	case 'B':
		value = 11;
	break;
	case 'C':
		value = 12;
	break;
	case 'D':
		value = 13;
	break;
	case 'E':
		value = 14;
	break;
	case 'F':
		value = 15;
	break;
	default:
		value = eval(code);
	break;
	}
	
	return value;

}

function getRGB(hex)
{
	var red, blue, green, a, b, c, d, e, f;
	hex = hex.toUpperCase();
	
	a = giveDec(hex.substring(0, 1));
	b = giveDec(hex.substring(1, 2));
	c = giveDec(hex.substring(2, 3));
	d = giveDec(hex.substring(3, 4));
	e = giveDec(hex.substring(4, 5));
	f = giveDec(hex.substring(5, 6));
	
	red 	= (a * 16) + b;
	blue 	= (c * 16) + d;
	green 	= (e * 16) + f;
	
	return red + ", " + blue + ", " + green;
}

function getHex(red, blue, green)
{
	var a, b, c, d, e, f;
	
	a = giveHex(Math.floor(red / 16));
	b = giveHex(red % 16);
	c = giveHex(Math.floor(blue / 16));
	d = giveHex(blue % 16);
	e = giveHex(Math.floor(green / 16));
	f = giveHex(green % 16);
	
	return a + b + c + d + e + f;
}
/* End Hex, RGB */