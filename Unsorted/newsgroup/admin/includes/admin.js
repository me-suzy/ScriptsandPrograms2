// Function to help uploading a file.
// Pop up window is opened.

function ftp(param){

		window.file = param;
		file_handler = window.open ('upload.php','cal','width=600,height=400,resizable=yes')
}

// Function to send the form with the action "Add"
function add(){

	document.form.action.value="Add";
	document.form.submit();
}

// Function to send the form with the action "Modify"
function modify()
{
	if (confirm("The DB entry is about to be updated..."))
	{
	document.form.action.value="Modify";
	//alert(document.form.action.value);
	document.form.submit();
	}
}

// Function to send the form with the action "Del"
function del()
{
	if (confirm("Are you sure you want to delete this entry?"))
	{
		document.form.action.value="Del";
		document.form.submit();
	}
}

// Funtion to help getting or downloading the NewsGroups' list
function get_list(param,host){

		window.file = param;
		file_handler = window.open ('get_list.php?server='+ host,'cal','width=600,height=400,resizable=yes')
}


// Funtion to help showing the NewsGroups' list into a menu element
function pick_group(param,host){

		//alert(host);
		window.file = param;
		file_handler = window.open ('pick_group.php?server='+ host,'cal','width=600,height=400,resizable=yes')
}


// Function that closes a Window and returns fills
// some form entries in the opener Window
function return_value(result){
	this.inFile=result
	file.value=this.inFile;
	// Change the image we have just Uploaded!
	image.src = this.path + this.inFile;		
	//alert(image.src);
	window.close();
}

// Function used with the Upload Pop Up Method
function init(){
	this.file = opener.file;		
	this.image = opener.image;
	this.path = opener.path;
	this.inFile = file.value;
	
}

// Close a window
function close(){
	window.close();
}

// Function to mark 'Import all articles'
function import_all(param){
	param.value = "*";
}