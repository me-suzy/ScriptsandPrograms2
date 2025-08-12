<!--

var prev_fld = '';

function chkChrs(frm, fld, txt, searchfor, how) {
    var string = document.forms[frm].elements[fld].value;
    var stopit;
    if (how) {
        var proof = searchfor.exec(string);
        stopit = (proof != string);
    }
    else {
        stopit = searchfor.test(string);
    }
    if (stopit && (fld == prev_fld || prev_fld == '')) {
        alert(txt);
        prev_fld = fld;
        document.forms[frm].elements[fld].focus();
        return
    }
    else prev_fld = '';
}

function reg_exp(frm, fld, txt, searchfor) {
    string = document.forms[frm].elements[fld].value;
    if (string != '' && string != searchfor.exec(string)) {
        alert(txt);
        return false;
    }
}

function chkISODate(frm, fld, txt) {
    var string = document.forms[frm].elements[fld].value;
    if (string != "") {
        var searchfor = /^\d\d\d\d-\d\d-\d\d$/;
        var result = searchfor.test(string);
        if(result == false) {
            alert(txt);
            document.forms[frm].elements[fld].focus();
             return false;
        }
        if (chkISODate.arguments.length == 4) {
            var x = "";
            var i = -1;
            while (x != fld) {
                i++;
                x = document.forms[frm].elements[i].name;
            }
            i--;
            if (document.forms[frm].elements[i].value > document.forms[frm].elements[fld].value) {
                txt = chkISODate.arguments[3];
                alert(txt);
                document.forms[frm].elements[i].focus();
                return false;
            }
        }
    }
     return true;
}
function chkNumbers(frm, fld, txt) {
	 var string = document.forms[frm].elements[fld].value;
    if (string != "") {
        var searchfor = /^\d+$/
        var result = searchfor.test(string);
        if(result==false) {
            alert(txt);
            document.forms[frm].elements[fld].focus();
            return false;
        }
    }
    return true;
    
}

function chkForm(frm) {
    for (var i=1; i<chkForm.arguments.length; i++){
        var fld=chkForm.arguments[i];
        i++;
        var txt=chkForm.arguments[i];
        if (document.forms[frm].elements[fld].value == "") {
            alert(txt);
            document.forms[frm].elements[fld].focus();
            return false;
        }
    }
    return true;
}

function show(sessid) {
    // sessid is deprecated -> FIXME
    var x = document.frm.contact.value;
    if (x > 0) {
        var path=self.location.href;
        path = path.replace(/filemanager\/filemanager\.php.+/,'');
        path = path.replace(/todo\/todo\.php.+/,'');
        path = path.replace(/projects\/projects\.php.+/,'');
        path = path.replace(/notes\/notes\.php.+/,'');
        // add 'replaces' for further modules ^^here^^

        var y = path + 'misc/print.php?module=contacts&amp;contact_ID=' + x + '&' + SID;
        window.open(y);
    }
}

// Marker and pointer for list view
var marked  = new Array();
var allRows = new Array();

function hiliOn(tr, i) {
    if (typeof(marked[i]) == 'undefined' || !marked[i]) {
        tr.style.backgroundColor = hiliColor;
    }
}

function hiliOff(tr, i) {
    if (typeof(marked[i]) == 'undefined' || !marked[i]) {
        tr.style.backgroundColor = allRows[i][0];
    }
}

function marker(tr, i) {
    if (typeof(marked[i]) == 'undefined' || !marked[i]) {
        marked[i] = true;
        tr.style.backgroundColor = markColor;
    }
    else {
        marked[i] = false;
        tr.style.backgroundColor = allRows[i][0];
    }
}

function selectAll() {
    for (var i in allRows) {
        marked[i] = true;
        document.getElementById(i).style.backgroundColor = markColor;
    }
}

function deselectAll() {
    marked = new Array;
    for (var i in allRows) {
        document.getElementById(i).style.backgroundColor = allRows[i][0];
    }
}


// Context menu
var recID;
var menuName;
var column;
var menuStatus
var menuWidth = new Array();

function startMenu(m,i,c) {
	ie5=(document.getElementById && document.all && document.styleSheets)?1:0;                                                                      
	nn6=(document.getElementById && !document.all)?1:0;
	if(menuStatus == 1)doHide();
	menuName = m;
	recID = i;
	column = c;
    var mWid = 200;
    if(m == 'menu2'){
        mWid = 150;
    }
    menuWidth[m] = mWid;
	menuHeight=90;
	menuStatus=0; 
	document.oncontextmenu=showMenu; 
	document.onmouseup=hideMenu;
	if(typeof(allRows[i]) != 'undefined') document.getElementById('recname').firstChild.data = allRows[i][1];
}  


function showMenu(e) {

    var xPos;
    var yPos;
    if (ie5) {
        if (event.clientX<document.body.offsetWidth-menuWidth[menuName]) xPos=event.clientX+document.body.scrollLeft;
        else xPos=event.clientX+document.body.scrollLeft-menuWidth[menuName];
        if (event.clientY>document.body.offsetHeight-menuHeight) yPos=event.clientY+document.body.scrollTop-menuHeight;
        else yPos=event.clientY+document.body.scrollTop;

    }
    else {
        if (e.pageX>window.screen.width-menuWidth[menuName] - 20) {
            xPos=e.pageX-menuWidth[menuName];
        }
        else xPos=e.pageX;
        if (e.pageY + 550>window.screen.height){

            yPos=e.pageY - 100;
        }
        else yPos=e.pageY;
    }

    cMenu = document.getElementById(menuName);
    cMenu.style.left = xPos + "px";
    cMenu.style.top  = yPos + "px";
    menuStatus = 1;
    return false;

}

function hideMenu(e) {
    if (menuStatus==1 && ((ie5 && event.button==1) || (nn6 && e.which==1))) setTimeout("doHide()",250);
}

function doHide() {
    document.getElementById(menuName).style.top = -750+"px";
    menuStatus = 0;
    document.oncontextmenu = nop;
}

function doLink(url, target, msg) { 
    if (msg) {
        if (!confirm(msg)) return
    }
    //    url = url + recID + sessid; Johann 20.4.2005 - i dont see why the name of the last initialized context menu has to be added to the url, at least it works again now :-)
    url = url + recID + '&' + SID; //Ich habe es wieder rein, weil sonst das sortieren usw. nicht mehr funktioniert
    switch (target) {
        case "_blank":
        window.open(url,'new','left=5px,top=5px,height=540px,width=760px,scrollbars=1,resizable');
        break;
    case "_top":
    	window.open(url,'new','left=5px,top=5px,width=760px,height=540px,scrollbars=1,resizable');
        break;
    default:
        location.href = url;
        break;
    }
}

function proc_marked(url, target, msg) {
	var list = "";
    for (var i in marked) {
        var is = i.split('xxx');
        var i1 = i;
        if (is[1]>0) {
            i1 = is[1];
        }
        if (marked[i]) {
            list = list + i1 + ",";
        }
    }
    if (list != "") {
    	if(msg) {
            if (!confirm(msg)) return;
        }
        url = url + list.replace(/,$/,"") + '&' + SID;
        switch (target) {
            case "_blank":
                window.open(url,'new','left=5px,top=5px,height=540px,width=760px,scrollbars=1,resizable');
                break;
            case "_top":
                window.open(url,'new','left=5px,top=5px,height=540px,width=760px,scrollbars=1,resizable');
                break;
            default:
                location.href = url;
                break;
        }
    }
}

function show_help(lnk) {
    var helpwin = window.open(lnk, 'helpwin', '');
}

function go_web() {
 x = document.frm.url.value;
 if (x.substr(0,4) != "http") x = "http://" + x;
 window.open(x,"_blank");
}
function mailto(field,adress,sessID,quickmail) {
  // sessid is deprecated -> FIXME
  if(field != 0) adress = document.frm[field].value;
  if (quickmail != 0){
    x = '&' + SID;
    path = self.location.href;
    path = path.replace(/[^\/]+\/[^\/]+\.php.*/,'');
    location.href = path + "mail/mail.php?mode=send_form&amp;form=email&amp;recipient=" + adress + x;
  }
  else location.href = "mailto:" + adress;
}

function go_phone(phonetype,phonenumber) {
  window.open("../misc/cti_" + phonetype + ".inc.php?phonenumber=" + phonenumber,"_blank","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,left=200,top=200,width=400,height=150,resizable=1")
}

function nop() {}

//-->
