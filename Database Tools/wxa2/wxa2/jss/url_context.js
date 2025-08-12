
var script_name = window.location.pathname;
if (script_name.lastIndexOf("/")==script_name.length-1) script_name = window.location.pathname + "index.php";
var current_parms = window.location.search.substring(1);


function goto_href(new_parms)
{ document.location = get_href(new_parms);}

function get_href(new_parms){if (new_parms==null) return script_name; else return script_name + "?" + QueryString_Parse( new_parms);}
function get_parms(new_parms) {return "?" + QueryString_Parse( new_parms);}

function goto_simple_href(parms)
{ document.location = get_simple_href(parms);}

function get_simple_href(parms)
{return script_name + "?" + parms}

function getValue(key, arr_key, arr_values)
{
var value="";
	for (var i=0;i<arr_key.length;i++)
	{	if (arr_key[i]==key)
		{	value = arr_values[i];
			//find key in new parms
			break;	}	}
	return value;
}

//read the params and returns new string without repeat
function QueryString_Parse(new_parms)
{
var query_string='';
	newQueryString_keys = new Array();
    newQueryString_values = new Array();
	var query = new_parms;
	var pairs = query.split("&");
	for (var i=0;i<pairs.length;i++)
	{
		var pos = pairs[i].indexOf('=');
		if (pos >= 0)
		{
			var key = pairs[i].substring(0,pos);
			var value = pairs[i].substring(pos+1);
			newQueryString_keys[newQueryString_keys.length] = key;
			newQueryString_values[newQueryString_values.length] = value;	
			//add to query_string
			if (value!='null')
				query_string += "&" + key + "=" + value;		
		}
	} // end of newQueryString parse 

	currentQueryString_keys = new Array();
	currentQueryString_values = new Array();
	var query = current_parms;
	var pairs = query.split("&");
	for (var i=0;i<pairs.length;i++)
	{
		var pos = pairs[i].indexOf('=');
		if (pos >= 0)
		{
			var key = pairs[i].substring(0,pos); // key
			var value = pairs[i].substring(pos+1); //value
			//currentQueryString_keys[currentQueryString_keys.length] = argname;
			//currentQueryString_values[currentQueryString_values.length] = value;		
			//if not exists in new one, then add current
			if (getValue(key,newQueryString_keys,newQueryString_values)=="")
				query_string += "&" + key + "=" + value;
		}
	} // end of currentQueryString parse 

	return query_string;
}
