//////////////////////////////////////////////////////////////////////////////
// myAgenda v2.0															//
// =============															//
// Copyright (C) 2003  Mesut Tunga - mesut@tunga.com						//
// http://php.tunga.com														//
//////////////////////////////////////////////////////////////////////////////

function validate_email(input) {
	s= input.value
	if(s.search) {
		return (s.search(new RegExp("^([-!#$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,4}$","gi"))>=0)
	}
	if(s.indexOf) {
		at_character=s.indexOf('@')
		if(at_character<=0 || at_character+4>s.length)
			return false
	}
	if(s.length<6)
		return false
	else
		return true
}

function clearForm(f) {
	for (var i=0; i<f.elements.length; i++) {
		if (f.elements[i].type == 'text') {
			f.elements[i].value = '';
		} else if (f.elements[i].type.substring(0,6) == 'select') {
			f.elements[i].selectedIndex = 0;
		} else if (f.elements[i].type.substring(0,8) == 'checkbox') {
			f.elements[i].checked = false;
		} else if (f.elements[i].type.substring(0,5) == 'radio') {
			f.elements[i].checked = false;
		} else if (f.elements[i].type == 'textarea') {
			f.elements[i].value = '';
		}
	}
}

function checkAll(f) {
  for(var i=0 ; i<f.elements.length; i++) {
      var e = f.elements[i];
      if((e.type == 'checkbox') && (e.name != 'checkall'))
 	  e.checked = f.checkall.checked;
  }
}

function checkCtrl(f) {
  var len = f.elements.length;
  var totboxes = 0;
  var toton = 0;

	for(var i=0 ; i<len ; i++) {
		var e = f.elements[i];
		if((e.type == 'checkbox') && (e.name != 'checkall')) {
			totboxes++;
			if(e.checked) {
				toton++;
			}
		}
	}
	if(totboxes == toton) {
		f.checkall.checked = true;
	} else {
		f.checkall.checked = false;
	}
}
function IsDigit() {
	return (event.keyCode >= 48) && (event.keyCode <= 57)
}

function popUP(url, width, height, param) {
	var params = "screenX=50,screenY=50,top=50,left=50,width="+width+",height="+height+","+param;
	window.open(url, "Dummy", params)
}