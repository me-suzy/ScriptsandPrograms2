function hide_details(){
	var httpRequest;
	
	if (window.XMLHttpRequest) httpRequest = new XMLHttpRequest();
	else if (window.ActiveXObject) httpRequest = new ActiveXObject('Msxml2.XMLHTTP');
	
	httpRequest.open('GET', 'set_session_var.php?var=details&val=0', true);
	
	httpRequest.onreadystatechange = function (evt) {
		if (httpRequest.readyState == 4) {
		}
	};
	
	httpRequest.send(null);
	
	document.getElementById('exif_data').style.display = 'none';
	document.getElementById('exif_toggle').innerHTML = '<a accesskey="d" href="javascript:void(0);" onclick="show_details();">Show details</a>';
}

function show_details(){
	var httpRequest;
	
	if (window.XMLHttpRequest) httpRequest = new XMLHttpRequest();
	else if (window.ActiveXObject) httpRequest = new ActiveXObject('Msxml2.XMLHTTP');
	
	httpRequest.open('GET', 'set_session_var.php?var=details&val=1', true);
	
	httpRequest.onreadystatechange = function (evt) {
		if (httpRequest.readyState == 4) {
		}
	};
	
	httpRequest.send(null);
	
	document.getElementById('exif_data').style.display = '';
	document.getElementById('exif_toggle').innerHTML = '<a accesskey="d" href="javascript:void(0);" onclick="hide_details();">Hide details</a>';
}