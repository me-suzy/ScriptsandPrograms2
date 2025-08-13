// Checks if the spell checker can be used with this browser
function browserCompatible() {
	var ua = navigator.userAgent.toLowerCase(); 

	var isGecko = (ua.indexOf('gecko') != -1);
	var isMozilla = (isGecko && ua.indexOf("gecko/") + 14 == ua.length);
	var isNS = (isGecko ? (ua.indexOf('netscape') != -1) : (ua.indexOf('mozilla') != -1 && (ua.indexOf('spoofer') + ua.indexOf('compatible') + ua.indexOf('opera') + ua.indexOf('webtv') + ua.indexOf('hotjava')) == -5));
	var isIE = (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1 && ua.indexOf("webtv") == -1); 

	var versionMinor = parseFloat(navigator.appVersion); 
	if (isNS && isGecko) {
		versionMinor = parseFloat(ua.substring(ua.lastIndexOf('/') + 1));
	} else if (isIE && versionMinor >= 4) {
		versionMinor = parseFloat(ua.substring(ua.indexOf('msie ') + 5));
	} else if (isMozilla) {
		versionMinor = parseFloat(ua.substring(ua.indexOf('rv:') + 3));
	}
	var versionMajor = parseInt(versionMinor); 

	if (isMozilla || (isNS && versionMajor >= 6) || (isIE && versionMajor >= 5)) {
		return true;
	} else {
		return false;
	}
}

// Opens spell checking window
function popIt() {
	if (browserCompatible()) {
		var n = window.open('about:blank', 'formwin', 'toolbar=no,menubar=no,scrollbars=yes,height=250,width=500,status=yes');
		return true;
	} else {
		alert('Spell Checker is only supported in Netscape 6.0+ and IE 5.0+');
		return false;
	}
}

// Submits forms in the new window
function spellCheck(frm, e) {
	var origMeth;
	var origAction;
	var origTarget;
	var retVal = popIt();
	var rFrm;
	if (retVal) {
		rFrm = eval('document.'+frm);
		origMeth = rFrm.method;
		origAction = rFrm.action;
		origTarget = rFrm.target;
		rFrm.method = 'POST';
		rFrm.action = 'scp_main.php?f='+frm+'&e='+e;
		rFrm.target = 'formwin';
		rFrm.submit();
		rFrm.method = origMeth;
		rFrm.action = origAction;
		rFrm.target = origTarget;
	}
}