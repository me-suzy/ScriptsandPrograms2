//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: common.js,v $
// | $Date: 2002/11/12 15:21:01 $
// | $Revision: 1.1 $
// +-------------------------------------------------------------+

function getElement(name) {
	if (document.getElementById) {
		return document.getElementById(name);
	} else if (document.all) {
		return document.all[name];
	} else if (document.layers){
		return document[name];
	}
}