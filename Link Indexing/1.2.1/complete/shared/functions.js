// JavaScript functions
function openwin(page, root, topicid){
	var url  = root + 'moderate.php?page=' + page + '&topicid=' + topicid;
	var name = Math.round(Math.random() * 10000000);
	var feat = 'width=400,height=300,scrollbars=yes,resizable=yes'
	window.open(url,name,feat);
	return false;
}

function skineditor(){
	var url  = 'skineditor.php';
	var name = Math.round(Math.random() * 10000000);
	var feat = 'width=600,height=400,scrollbars=yes,resizable=yes,location=yes'
	window.open(url,name,feat);
	return false;
}

function skinjump(skinid){
	var url  = 'skineditor.php?skinid=' + skinid;
	window.location.assign(url);
}

function skinfilejump(skinid, fileid){
	var url  = 'skineditor.php?skinid=' + skinid + '&do=Edit File&fileid=' + fileid;
	window.location.assign(url);
}