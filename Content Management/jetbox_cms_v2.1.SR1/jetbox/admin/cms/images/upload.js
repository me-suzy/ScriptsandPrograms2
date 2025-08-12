function getObj(name){
	if (document.getElementById) {
		this.obj = document.getElementById(name)
		this.style = document.getElementById(name).style
	}
	else if (document.all) {
		this.obj = document.all[name]
		this.style = document.all[name].style
	}
	else if (document.layers) {
		this.obj = document.layers[name]
		this.style = document.layers[name]
	}
}




function path() {
//out = '\\\\'; add = '/'; temp = document.getElementById('" + photo + "').value; alert("+temp+"); while (temp.indexOf(out)>-1) {pos= temp.indexOf(out);temp = (temp.substring(0, pos) + add + temp.substring((pos + out.length), temp.length));}; " + photo + "src = 'file://' + temp;
}



function resize() {
	// default image = 200 * 150
	if (!document.images) {
		alert('Your browser does not support the "document.images" object. No Resizing')
		return
	}

	document.images['test'].src=document.images['invis'].src;
	document.images['previewimg'].src=document.images['invis'].src;
	var width = document.images['invis'].width
	var height = document.images['invis'].height
	if(width!=1 && height!=1){
		show_size="<br>Width: "+width+"px <br> Height: "+height+"px";
		if (document.getElementById('previewimgsize')){
			document.getElementById('previewimgsize').innerHTML=show_size;
		}
	}
	if ((width / height) >= (200/150) && width > 200) {
		height = Math.ceil(200 / width * height);
		width = 200;
	}
	else if(height > 150) {
		width = Math.ceil(150 / height * width);
		height = 150;
	}

	document.images['test'].width = width
	document.images['test'].height= height;
	document.images['previewimg'].width = width;
	document.images['previewimg'].height = height;
}


function rth(i){
	if (!document.images) {
		alert('Your browser does not support the "document.images" object. No Thumbnail Resizing')
		return
	}
	if (nopic != 0) {
		var preview = 'preview' + i
		//calculate new size for thumbnail
		var width  = document.images['invis'].width
		var height = document.images['invis'].height

		if ( height <= 40 && width <= 50 ) {
			document.images[preview].height = height
			document.images[preview].width  = width
		}
		else if ((width / height) <= (50/40)) {
			document.images[preview].height = 40
			document.images[preview].width = 40 / height * width
		}
		else {
			document.images[preview].width = 50
			document.images[preview].height = 50 / width * height
		}
	}
	resize();
}

function checkform ( form ){
	//if (form.user.value == "None") {
	//	alert( "Please select a directory." )
	//	form.user.focus()
	//	return false
	//}
	return true
}

//resizes left iframe to show as much as possible of the folder tree
function redraw() {
	//node='lister';
	//var height = document.all ? document.body.clientHeight : window.innerHeight;
	height = window.innerHeight ? window.innerHeight : document.body.clientHeight;

	document.getElementById('lister').height = (height - 352)<321?321:height - 352;
	//alert("prima");
}

// add event handler to resize the iframe containing the folder tree
window.onresize = redraw;

function disable() {
	document.getElementById('upl').disabled = true;
}

//shows info on the selected folder from the folder tree
function showFolderInfo_tree(name, id, owner, amount, ul, ps, upload, edit_prop){

	if(edit_prop==1){
		 edit_prop_url= '<a href="?task=editrecord&f_id='+ id +'">Edit folder</a>';
	}
	else{
		 edit_prop_url= '';
	}

	if(document.getElementById('f_id')){document.getElementById('f_id').value = id;}
	if(document.getElementById('previewimgsize')){document.getElementById('previewimgsize').innerHTML='';}
	if(document.images['invis']){document.images['invis'].src='images/clearpixel.gif';}
	if(document.getElementById('foldereditlink')){document.getElementById('foldereditlink').innerHTML=edit_prop_url;}

	document.getElementById('foldername').innerHTML = name;
	document.getElementById('owner').innerHTML = 'Owner: ' + owner;
	document.getElementById('amoutitems').innerHTML = 'Contains ' + amount + ' images';
	document.getElementById('uploadallow').innerHTML = ul;
	document.getElementById('showallow').innerHTML =  ps;
	showLayer('loading');
	hideLayer('preview');
	hideLayer('emptyheader');
	if(document.getElementById('upload')){
		if(upload==0){
			hideLayer('upload');
		}
		else{
			showLayer('upload');
		}
	}
}

//show preview and info on image selected from tree
function showPreview_tree(img_id, name, remove, front_end_url){
	document.getElementById('img_id').value=img_id;
	document.getElementById('previewimgname').innerHTML='<b>' + name + '</b><br>';
	showLayer('preview');
	hideLayer('emptyheader');
	hideLayer('loading');
	if(document.getElementById('upload')){
		hideLayer('upload');
	}
	if(document.getElementById('txtFileName')){
		document.getElementById('txtFileName').value= front_end_url + 'img_viewer.php?img_id=' + img_id;
	}
	if(document.forms[0].src){
		document.forms[0].src.value= front_end_url + 'img_viewer.php?img_id=' + img_id
	}
	remove==1 ? showLayer('removebutton') : hideLayer('removebutton');
	document.images['invis'].src= front_end_url +'img_viewer.php?img_id=' + img_id;
	resize();
}



function showPreview_upload(i,update_preview){
	var srca = MM_findObj('photo'+i);
	if(!srca.value || !checkUpload(i)){

		srca.className='error';
		document.images['invis'].src='images/clearpixel.gif';
		document.images['preview'+i].src='images/clearpixel.gif';
		document.getElementById('previewimgsize').innerHTML='';
	}
	else{
		srca.className='flat';
		document.images['invis'].src=srca.value;
		if(update_preview){
			document.images['preview'+i].src=srca.value;
		}
		nopic=1;
	}
}

function checkUpload(i) {
	var fileObj = MM_findObj('photo'+i);
	if (fileObj == null){return false;}
	var regexp = /\/|\\/;
	var parts = fileObj.value.split(regexp);
	var filename = parts[parts.length-1].split(".");
	if (filename.length <= 1) {
		alert('Please upload a file with an extensions, e.g. "imagefile.jpg".');
		return false;
	}
	var ext = filename[filename.length-1].toLowerCase();
	//var DenyExtensions = ["php", "php3", "php4", "phtml", "shtml", "cgi", "pl", "", "html", "doc", "xls", "txt", "pdf"];
	//for (i=0; i<DenyExtensions.length; i++) {
	//		if (ext == DenyExtensions[i]) {
	//			alert('Files with this extension are not allowed.');
	//			return false;
	//		}
	//}
	var AllowExtensions = ["gif", "jpeg", "jpg", "png"];
	for (i=0; i<AllowExtensions.length; i++) {
			if (ext == AllowExtensions[i]) {
				//changeLoadingStatus('upload');
				
				return true;

			}
	}
	alert('Files with this extension are not allowed.');
	return false;
}

function check_all(){
	for (i=0; i<5; i++) {
		fileObj = MM_findObj('photo'+i);
		if (fileObj.value != ''){
			if(!checkUpload(i)){
				return false;
			}
		}
	}
	return true;
}