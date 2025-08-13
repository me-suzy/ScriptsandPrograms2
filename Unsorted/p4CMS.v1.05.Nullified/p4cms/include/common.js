<!--
function storeCaret (textEl) {
	if (textEl.createTextRange)
		textEl.caretPos = document.selection.createRange().duplicate();
}

function insertAtCaret (textEl, text) {
	if (textEl.createTextRange && textEl.caretPos) {
			var caretPos = textEl.caretPos;
			caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	}
	else textEl.value = textEl.value + text;
}

function edipop(id) {

 var w = screen.width-10;
 var l = 0;
 var h = screen.height - 60;
 var url = 'editor/_editor.php?id='+id;
 var name = 'editorid' + id;
 var features = 'scrollbars=no,status=no,toolbar=no,resizable=yes,width='+w+',height='+h+',top=0,left='+l;
 var hwnd = window.open(url,name,features);
 browser_max(hwnd);

}

function browser_max(wnda){ 
    wnda.moveTo(0,0);
    wnda.resizeTo(screen.availWidth,screen.availHeight);
}
function PopUpCMS(sid) {
  var w = screen.width-10;
  var l = 0;
  var h = screen.height - 60;
  var url = 'cms.php?d4sess=' + sid;
  var name = sid;
  var features = 'scrollbars=no,toolbar=no,menuebar=no,resizable=yes,width='+w+',height='+h+',top=0,left='+l;
  var hwnd = window.open(url,name,features);
  browser_max(hwnd);
}
function edipop(id) {
  var w = screen.width-10;
  var l = 0;
  var h = screen.height - 60;
  var url = 'editor/_editor.php?id='+id;
  var name = 'id';
  var features = 'scrollbars=no,status=no,toolbar=no,resizable=yes,width='+w+',height='+h+',top=0,left='+l;
  var hwnd = window.open(url,name,features);
  browser_max(hwnd);
}
function SaveDialog(basedir) {
  var winWidth = 750;
  var winHeight = 440;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'savedlg.php?basedir=' + basedir;
  var name = 'savedialog';
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function SaveDialogExt(exta) {
  var winWidth = 750;
  var winHeight = 440;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'savedlg.php?basedir=&exta=' + exta;
  var name = 'savedialog';
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function rubDlg() {
  var winWidth = 560;
  var winHeight = 320;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'schemadlg.php';
  var name = 'filedlg';
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function stded(se,fn) {
  var winWidth = 560;
  var winHeight = 330;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'stdwert.php?textfeld=' + se + '&formular=' + fn;
  var name = 'stwertdlg';
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function MediaPool(typ, target) {
  var winWidth = 560;
  var winHeight = 440;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'mediapool.php?typ=' + typ + '&target=' + target;
  var name = 'mpool';
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function confirmLink(theLink, theQuestion)
{if (typeof(window.opera) != 'undefined') {return true;}
var is_confirmed = confirm(theQuestion);if (is_confirmed) {
location.href = theLink;};}

function CopyVVars(sw) {
 document.forms['vorlage'].elements['text'].value = document.frames["frvorlageneditor"].objContent.DOM.body.innerHTML;
}
function CopyRVars(sw) {
 document.forms['rubrik'].elements['text'].value = document.frames["frvorlageneditor"].objContent.DOM.body.innerHTML;
}
function ChangeVView(view) {
 document.all.query.style.visibility="hidden";
 document.all.insert.style.visibility="hidden";
 document.all.welc.style.visibility="hidden";
 if (view == 'query') {
 	document.all.query.style.visibility="visible";
 }
 if (view == 'insert') {
 	document.all.insert.style.visibility="visible";
 }
}
function PasswortVergleich() {
if(document.forms["uform"].elements["retype"].value != document.forms["uform"].elements["pass"].value) {
alert("Die Passwörter stimmen nicht überein!");
document.forms["uform"].elements["pass"].focus();
return false;}
if(document.forms["uform"].elements["benutzername"].value=="") {
alert("Geben Sie bitte einen Benutzernamen an!");
return false;}
}
function ChangeFField(wert, formular, feld) {
 document.all[feld].value = wert;
}
function OpenVar(vfor, sid) {
  var winWidth = 500;
  var winHeight = 400;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'addvar.php?varfor=' + vfor + '&d4sess=' + sid;
  var name = 'add' + vfor + sid;
  var features = 'scrollbars=yes,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function Backup(sid) {
  var winWidth = 316;
  var winHeight = 119;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  if (document.all.backupman.elements.carchiv.checked == true) {
   var kop = "ja";
  } else{
   var kop = "nein";
  }
  if (document.all.backupman.elements.cgal.checked == true) {
   var gal = "ja";
  } else{
   var gal = "nein";
  }
  var url = 'backup.php?d4sess=' + sid + '&kopie=' + kop + '&gal=' + gal;
  var name = 'backup' + sid;
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function AnalyzeFileName(sid) {
  var winWidth = 366;
  var winHeight = 159;
  var w = (screen.width - winWidth)/2;
  var h = (screen.height - winHeight)/2 - 60;
  var url = 'backup_manager.php?d4sess=' + sid + '&action=analyze&filename=' + document.all.aform.elements['filename'].value;
  var name = 'analyze' + sid;
  var features = 'scrollbars=no,width='+winWidth+',height='+winHeight+',top='+h+',left='+w;
  window.open(url,name,features);
}
function DLExit(sid, kopie, gal) {
  window.opener.location.href='backup.php?action=get&d4sess='+sid+'&kopie='+kopie+'&gal='+gal;
  setTimeout("parent.close()",3000);
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function SwitchMode(mode, sid) {
  parent.frames['register'].location.href='reg.php?mode=' + mode + '&d4sess=' + sid;
  parent.frames['struktur'].location.href='struktur_' + mode + '.php?d4sess=' + sid;
  parent.frames['inhalt'].location.href=mode + '_main.php?d4sess=' + sid;
}
function LogOut() {
  window.opener.location.href='index.php';
  window.close();
}
function SwitchPage(page, frame) {
  parent.frames[frame].location.href=page;
}
function EditDocument(page, sid) {
  parent.frames['inhalt'].location.href='dokument.php?mode=edit&id=' + page + '&d4sess=' + sid;
}
function EditAbfrage(page, sid) {
  parent.frames['inhalt'].location.href='abfrage.php?mode=edit&id=' + page + '&d4sess=' + sid;
}
function ShowFile(page, sid) {
  parent.frames['inhalt'].location.href='file_viewer.php?path=' + page + '&d4sess=' + sid;
}
function EditVorlage(page, sid) {
  parent.frames['inhalt'].location.href='vorlage.php?mode=edit&id=' + page + '&d4sess=' + sid;
}
function EditRubrik(page, sid) {
  parent.frames['inhalt'].location.href='rubrik.php?mode=edit&id=' + page + '&d4sess=' + sid;
}
/*--------------------------------------------------|
| dTree 2.05 | www.destroydrop.com/javascript/tree/ |
|---------------------------------------------------|
| Copyright (c) 2002-2003 Geir Landrö               |
|                                                   |
| This script can be used freely as long as all     |
| copyright messages are intact.                    |
|                                                   |
| Updated: 17.04.2003                               |
|--------------------------------------------------*/

// Node object
function Node(id, pid, name, url, title, target, icon, iconOpen, open) {
	this.id = id;
	this.pid = pid;
	this.name = name;
	this.url = url;
	this.title = title;
	this.target = target;
	this.icon = icon;
	this.iconOpen = iconOpen;
	this._io = open || false;
	this._is = false;
	this._ls = false;
	this._hc = false;
	this._ai = 0;
	this._p;
};

// Tree object
function dTree(objName) {
	this.config = {
		target					: null,
		folderLinks			: true,
		useSelection		: true,
		useCookies			: true,
		useLines				: true,
		usp4cmsns				: true,
		useStatusText		: false,
		closeSameLevel	: false,
		inOrder					: false
	}
	this.icon = {
		root				: 'gfx/tree/base.gif',
		folder			: 'gfx/tree/folder.gif',
		folderOpen	: 'gfx/tree/folderopen.gif',
		node				: 'gfx/tree/page.gif',
		empty				: 'gfx/tree/empty.gif',
		line				: 'gfx/tree/line.gif',
		join				: 'gfx/tree/join.gif',
		joinBottom	: 'gfx/tree/joinbottom.gif',
		plus				: 'gfx/tree/plus.gif',
		plusBottom	: 'gfx/tree/plusbottom.gif',
		minus				: 'gfx/tree/minus.gif',
		minusBottom	: 'gfx/tree/minusbottom.gif',
		nlPlus			: 'gfx/tree/nolines_plus.gif',
		nlMinus			: 'gfx/tree/nolines_minus.gif'
	};
	this.obj = objName;
	this.aNodes = [];
	this.aIndent = [];
	this.root = new Node(-1);
	this.selectedNode = null;
	this.selectedFound = false;
	this.completed = false;
};

// Adds a new node to the node array
dTree.prototype.add = function(id, pid, name, url, title, target, icon, iconOpen, open) {
	this.aNodes[this.aNodes.length] = new Node(id, pid, name, url, title, target, icon, iconOpen, open);
};

// Open/close all nodes
dTree.prototype.openAll = function() {
	this.oAll(true);
};
dTree.prototype.closeAll = function() {
	this.oAll(false);
};

// Outputs the tree to the page
dTree.prototype.toString = function() {
	var str = '<div class="dtree">\n';
	if (document.getElementById) {
		if (this.config.useCookies) this.selectedNode = this.getSelected();
		str += this.addNode(this.root);
	} else str += 'Browser not supported.';
	str += '</div>';
	if (!this.selectedFound) this.selectedNode = null;
	this.completed = true;
	return str;
};

// Creates the tree structure
dTree.prototype.addNode = function(pNode) {
	var str = '';
	var n=0;
	if (this.config.inOrder) n = pNode._ai;
	for (n; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == pNode.id) {
			var cn = this.aNodes[n];
			cn._p = pNode;
			cn._ai = n;
			this.setCS(cn);
			if (!cn.target && this.config.target) cn.target = this.config.target;
			if (cn._hc && !cn._io && this.config.useCookies) cn._io = this.isOpen(cn.id);
			if (!this.config.folderLinks && cn._hc) cn.url = null;
			if (this.config.useSelection && cn.id == this.selectedNode && !this.selectedFound) {
					cn._is = true;
					this.selectedNode = n;
					this.selectedFound = true;
			}
			str += this.node(cn, n);
			if (cn._ls) break;
		}
	}
	return str;
};

// Creates the node icon, url and text
dTree.prototype.node = function(node, nodeId) {
	var str = '<div class="dTreeNode">' + this.indent(node, nodeId);
	if (this.config.usp4cmsns) {
		if (!node.icon) node.icon = (this.root.id == node.pid) ? this.icon.root : ((node._hc) ? this.icon.folder : this.icon.node);
		if (!node.iconOpen) node.iconOpen = (node._hc) ? this.icon.folderOpen : this.icon.node;
		if (this.root.id == node.pid) {
			node.icon = this.icon.root;
			node.iconOpen = this.icon.root;
		}
		str += '<img id="i' + this.obj + nodeId + '" src="' + ((node._io) ? node.iconOpen : node.icon) + '" alt="" />';
	}
	if (node.url) {
		str += '<a id="s' + this.obj + nodeId + '" class="' + ((this.config.useSelection) ? ((node._is ? 'nodeSel' : 'node')) : 'node') + '" href="' + node.url + '"';
		if (node.title) str += ' title="' + node.title + '"';
		if (node.target) str += ' target="' + node.target + '"';
		if (this.config.useStatusText) str += ' onmouseover="window.status=\'' + node.name + '\';return true;" onmouseout="window.status=\'\';return true;" ';
		if (this.config.useSelection && ((node._hc && this.config.folderLinks) || !node._hc))
			str += ' onclick="javascript: ' + this.obj + '.s(' + nodeId + ');"';
		str += '>';
	}
	else if ((!this.config.folderLinks || !node.url) && node._hc && node.pid != this.root.id)
		str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');" class="node">';
	str += node.name;
	if (node.url || ((!this.config.folderLinks || !node.url) && node._hc)) str += '</a>';
	str += '</div>';
	if (node._hc) {
		str += '<div id="d' + this.obj + nodeId + '" class="clip" style="display:' + ((this.root.id == node.pid || node._io) ? 'block' : 'none') + ';">';
		str += this.addNode(node);
		str += '</div>';
	}
	this.aIndent.pop();
	return str;
};

// Adds the empty and line icons
dTree.prototype.indent = function(node, nodeId) {
	var str = '';
	if (this.root.id != node.pid) {
		for (var n=0; n<this.aIndent.length; n++)
			str += '<img src="' + ( (this.aIndent[n] == 1 && this.config.useLines) ? this.icon.line : this.icon.empty ) + '" alt="" />';
		(node._ls) ? this.aIndent.push(0) : this.aIndent.push(1);
		if (node._hc) {
			str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');"><img id="j' + this.obj + nodeId + '" src="';
			if (!this.config.useLines) str += (node._io) ? this.icon.nlMinus : this.icon.nlPlus;
			else str += ( (node._io) ? ((node._ls && this.config.useLines) ? this.icon.minusBottom : this.icon.minus) : ((node._ls && this.config.useLines) ? this.icon.plusBottom : this.icon.plus ) );
			str += '" alt="" /></a>';
		} else str += '<img src="' + ( (this.config.useLines) ? ((node._ls) ? this.icon.joinBottom : this.icon.join ) : this.icon.empty) + '" alt="" />';
	}
	return str;
};

// Checks if a node has any children and if it is the last sibling
dTree.prototype.setCS = function(node) {
	var lastId;
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.id) node._hc = true;
		if (this.aNodes[n].pid == node.pid) lastId = this.aNodes[n].id;
	}
	if (lastId==node.id) node._ls = true;
};

// Returns the selected node
dTree.prototype.getSelected = function() {
	var sn = this.getCookie('cs' + this.obj);
	return (sn) ? sn : null;
};

// Highlights the selected node
dTree.prototype.s = function(id) {
	if (!this.config.useSelection) return;
	var cn = this.aNodes[id];
	if (cn._hc && !this.config.folderLinks) return;
	if (this.selectedNode != id) {
		if (this.selectedNode || this.selectedNode==0) {
			eOld = document.getElementById("s" + this.obj + this.selectedNode);
			eOld.className = "node";
		}
		eNew = document.getElementById("s" + this.obj + id);
		eNew.className = "nodeSel";
		this.selectedNode = id;
		if (this.config.useCookies) this.setCookie('cs' + this.obj, cn.id);
	}
};

// Toggle Open or close
dTree.prototype.o = function(id) {
	var cn = this.aNodes[id];
	this.nodeStatus(!cn._io, id, cn._ls);
	cn._io = !cn._io;
	if (this.config.closeSameLevel) this.closeLevel(cn);
	if (this.config.useCookies) this.updateCookie();
};

// Open or close all nodes
dTree.prototype.oAll = function(status) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n]._hc && this.aNodes[n].pid != this.root.id) {
			this.nodeStatus(status, n, this.aNodes[n]._ls)
			this.aNodes[n]._io = status;
		}
	}
	if (this.config.useCookies) this.updateCookie();
};

// Opens the tree to a specific node
dTree.prototype.openTo = function(nId, bSelect, bFirst) {
	if (!bFirst) {
		for (var n=0; n<this.aNodes.length; n++) {
			if (this.aNodes[n].id == nId) {
				nId=n;
				break;
			}
		}
	}
	var cn=this.aNodes[nId];
	if (cn.pid==this.root.id || !cn._p) return;
	cn._io = true;
	cn._is = bSelect;
	if (this.completed && cn._hc) this.nodeStatus(true, cn._ai, cn._ls);
	if (this.completed && bSelect) this.s(cn._ai);
	else if (bSelect) this._sn=cn._ai;
	this.openTo(cn._p._ai, false, true);
};

// Closes all nodes on the same level as certain node
dTree.prototype.closeLevel = function(node) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.pid && this.aNodes[n].id != node.id && this.aNodes[n]._hc) {
			this.nodeStatus(false, n, this.aNodes[n]._ls);
			this.aNodes[n]._io = false;
			this.closeAllChildren(this.aNodes[n]);
		}
	}
}

// Closes all children of a node
dTree.prototype.closeAllChildren = function(node) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.id && this.aNodes[n]._hc) {
			if (this.aNodes[n]._io) this.nodeStatus(false, n, this.aNodes[n]._ls);
			this.aNodes[n]._io = false;
			this.closeAllChildren(this.aNodes[n]);		
		}
	}
}

// Change the status of a node(open or closed)
dTree.prototype.nodeStatus = function(status, id, bottom) {
	eDiv	= document.getElementById('d' + this.obj + id);
	eJoin	= document.getElementById('j' + this.obj + id);
	if (this.config.usp4cmsns) {
		p4cmsn	= document.getElementById('i' + this.obj + id);
		p4cmsn.src = (status) ? this.aNodes[id].iconOpen : this.aNodes[id].icon;
	}
	eJoin.src = (this.config.useLines)?
	((status)?((bottom)?this.icon.minusBottom:this.icon.minus):((bottom)?this.icon.plusBottom:this.icon.plus)):
	((status)?this.icon.nlMinus:this.icon.nlPlus);
	eDiv.style.display = (status) ? 'block': 'none';
};


// [Cookie] Clears a cookie
dTree.prototype.clearCookie = function() {
	var now = new Date();
	var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);
	this.setCookie('co'+this.obj, 'cookieValue', yesterday);
	this.setCookie('cs'+this.obj, 'cookieValue', yesterday);
};

// [Cookie] Sets value in a cookie
dTree.prototype.setCookie = function(cookieName, cookieValue, expires, path, domain, secure) {
	document.cookie =
		escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
};

// [Cookie] Gets a value from a cookie
dTree.prototype.getCookie = function(cookieName) {
	var cookieValue = '';
	var posName = document.cookie.indexOf(escape(cookieName) + '=');
	if (posName != -1) {
		var posValue = posName + (escape(cookieName) + '=').length;
		var endPos = document.cookie.indexOf(';', posValue);
		if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
		else cookieValue = unescape(document.cookie.substring(posValue));
	}
	return (cookieValue);
};

// [Cookie] Returns ids of open nodes as a string
dTree.prototype.updateCookie = function() {
	var str = '';
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n]._io && this.aNodes[n].pid != this.root.id) {
			if (str) str += '.';
			str += this.aNodes[n].id;
		}
	}
	this.setCookie('co' + this.obj, str);
};

// [Cookie] Checks if a node id is in a cookie
dTree.prototype.isOpen = function(id) {
	var aOpen = this.getCookie('co' + this.obj).split('.');
	for (var n=0; n<aOpen.length; n++)
		if (aOpen[n] == id) return true;
	return false;
};

// If Push and pop is not implemented by the browser
if (!Array.prototype.push) {
	Array.prototype.push = function array_push() {
		for(var i=0;i<arguments.length;i++)
			this[this.length]=arguments[i];
		return this.length;
	}
};
if (!Array.prototype.pop) {
	Array.prototype.pop = function array_pop() {
		lastElement = this[this.length-1];
		this.length = Math.max(this.length-1,0);
		return lastElement;
	}
};

function Vorschau() 
{
remote = window.open("","vorschau","scrollbars=1,width=800,height=500");
remote.location.href = "/p4cms/vorlage_vorschau.php"+"?"+"action=eins";
if (remote.opener == null) remote.opener = window;remote.opener.name = "opener";
}

function Vorschau2(namef) 
{
	var piep = namef;
remote = window.open("","vorschau","scrollbars=1,width=800,height=500");
remote.location.href = '/p4cms/dokument_vorschau.php?VAL='+piep;
//remote.location.href = url;
if (remote.opener == null) remote.opener = window;remote.opener.name = "opener";
}
//-->
