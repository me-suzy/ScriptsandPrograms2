/**************************************************************************
	Copyright (c) 2001 Geir Landrö (drop@destroydrop.com)
	JavaScript Tree - www.destroydrop.com/hugi/javascript/tree/
	Version 0.96	

	This script can be used freely as long as all copyright messages are
	intact.
	
	Heavily modified by Arjan Haverkamp (arjan@webpower.nl), Feb. 22 2002
**************************************************************************/

// Arrays for nodes and icons
var nodes		= [];
var openNodes	= [];
var icons		= [];
var d = document;
var imgs = ['plus', 'plusbottom', 'minus', 'minusbottom', 'folder', 'folderopen', 'gif', 'jpg', 'png', 'ppt', 'pdf', 'xls', 'zip', 'doc', 'html', 'txt', 'swf', 'other'];

// Loads all icons that are used in the tree
function preloadIcons() {
	for(x = 0; x < imgs.length; x++) {
		icons[x] = new Image();
		icons[x].src = 'pics/' + imgs[x] + '.gif';
	}
}

var curActive = 0;

function setActive(nodeId, folderId, path) {
	var linkObj = d.getElementById('link'+nodeId);
	d.getElementById('link'+curActive).className = 'inactive';
	linkObj.className = 'active';
	curActive = nodeId;
	parent.setInfo(folderId, path, linkObj.title, folderId==nodeId);
}

// Create the tree
function createTree(arrName, startNode, openNode) {
	nodes = arrName;
	preloadIcons();
	if (startNode == null) startNode = 0;
	if (openNode != 0 || openNode != null) setOpenNodes(openNode);
	
	if (startNode !=0) {
		var nodeValues = nodes[getArrayId(startNode)].split('|');
		d.write('<a href="' + nodeValues[3] + '"><img src="pics/folderopen.gif" align="absbottom">' + nodeValues[2] + '</a><br>');
	} 
	else {
		d.write('<img src="pics/base.gif" align="absbottom">');
		d.write('<a class="active" id="link0" href="javascript:setActive(0,0,\'\')">root</a><br>');
	}
	
	var recursedNodes = [];
	addNode(startNode, recursedNodes);
}
// Returns the position of a node in the array
function getArrayId(node) {
	var nodeValues;
	for (i=0; i<nodes.length; i++) {
		nodeValues = nodes[i].split('|');
		if (nodeValues[0]==node) return i;
	}
}
// Puts in array nodes that will be open
function setOpenNodes(openNode) {
	var nodeValues;
	for (i=0; i<nodes.length; i++) {
		nodeValues = nodes[i].split('|');
		if (nodeValues[0]==openNode) {
			openNodes.push(nodeValues[0]);
			setOpenNodes(nodeValues[1]);
		}
	} 
}
// Checks if a node is open
function isNodeOpen(node) {
	for (i=0; i<openNodes.length; i++)
		if (openNodes[i]==node) return true;
	return false;
}
// Checks if a node has any children
function hasChildNode(parentNode) {
	var nodeValues;
	for (i=0; i< nodes.length; i++) {
		nodeValues = nodes[i].split('|');
		if (nodeValues[1] == parentNode) return true;
	}
	return false;
}
// Checks if a node is the last sibling
function lastSibling (node, parentNode) {
	var lastChild = 0, nodeValues;
	for (i=0; i< nodes.length; i++) {
		nodeValues = nodes[i].split('|');
		if (nodeValues[1] == parentNode)
			lastChild = nodeValues[0];
	}
	if (lastChild==node) return true;
	return false;
}
// Adds a new node in the tree
function addNode(parentNode, recursedNodes) {
	var fId, nodeValues, ls, hcn, ino;
	for (var i = 0; i < nodes.length; i++) {

		nodeValues = nodes[i].split('|');
		if (nodeValues[1] == parentNode) {
			
			ls  = lastSibling(nodeValues[0], nodeValues[1]);
			hcn = hasChildNode(nodeValues[0]);
			ino = isNodeOpen(nodeValues[0]);

			// Write out line & empty icons
			d.write('<nobr>');
			for (g=0; g<recursedNodes.length; g++) {
				if (recursedNodes[g] == 1) d.write('<img src="pics/line.gif" align="absbottom">');
				else  d.write('<img src="pics/pixel.gif" align="absbottom">');
			}

			// put in array line & empty icons
			if (ls) recursedNodes.push(0);
			else recursedNodes.push(1);

			// Write out join icons
			if (hcn) {
				if (ls) {
					d.write('<a href="javascript:oc(' + nodeValues[0] + ',1)"><img id="join' + nodeValues[0] + '" src="pics/');
				 	if (ino) d.write('minus');
					else d.write('plus');
					d.write('bottom.gif" align="absbottom"></a>');
				} else {
					d.write('<a href="javascript:oc(' + nodeValues[0] + ',0)"><img id="join' + nodeValues[0] + '" src="pics/');
					if (ino) d.write('minus');
					else d.write('plus');
					d.write('.gif" align="absbottom"></a>');
				}
			} else {
				if (ls) d.write('<img src="pics/join.gif" align="absbottom">');
				else d.write('<img src="pics/joinbottom.gif" align="absbottom">');
			}

			// Write out folder & page icons
			if (hcn) {
				d.write('<img id="icon' + nodeValues[0] + '" src="pics/folder')
				if (ino) d.write('open');
				d.write('.gif" align="absbottom">');
			} else
				d.write('<img id="icon' + nodeValues[0] + '" src="pics/' + imgs[nodeValues[4]] + '.gif" align="absbottom">');
			// If folder: nodeValues[0], else: nodeValues[1];
			fId = (hcn || nodeValues[4] == 4) ? nodeValues[0] : nodeValues[1];
			d.write('<a id="link' + nodeValues[0] + '" href="javascript:setActive(' + nodeValues[0] + ',' + fId + ',\'' + nodeValues[3] + '\')" title="' + nodeValues[5] + '">');

			// Write out node name
			d.write(nodeValues[2]);

			// End link
			d.write('</a></nobr><br>');
			
			// If node has children write out divs and go deeper
			if (hcn) {
				d.write('<div id="div' + nodeValues[0] + '"');
					if (!ino) d.write(' style="display:none"');
				d.write('>');
				addNode(nodeValues[0], recursedNodes);
				d.write('</div>');
			}
			
			// remove last line or empty icon 
			recursedNodes.pop();
		}
	}
}
// Opens or closes a node
function oc(node, bottom) {
	var theDiv = d.getElementById('div' + node);
	var theJoin	= d.getElementById('join' + node);
	var theIcon = d.getElementById('icon' + node);
	
	if (theDiv.style.display == 'none') {
		if (bottom==1) theJoin.src = icons[3].src;
		else theJoin.src = icons[2].src;
		theIcon.src = icons[5].src;
		theDiv.style.display = '';
	} else {
		if (bottom==1) theJoin.src = icons[1].src;
		else theJoin.src = icons[0].src;
		theIcon.src = icons[4].src;
		theDiv.style.display = 'none';
	}
}
// Push and pop not implemented in IE
if(!Array.prototype.push) {
	function array_push() {
		for(var i=0;i<arguments.length;i++)
			this[this.length]=arguments[i];
		return this.length;
	}
	Array.prototype.push = array_push;
}
if(!Array.prototype.pop) {
	function array_pop(){
		lastElement = this[this.length-1];
		this.length = Math.max(this.length-1,0);
		return lastElement;
	}
	Array.prototype.pop = array_pop;
}
