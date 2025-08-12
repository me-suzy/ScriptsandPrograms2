// this checks all checkboxes in a form
function checkAll(formObj) {
	for(x = 1; x < formObj.elements.length; x++) {
		checkbox = formObj.elements[x];
		if(checkbox.name != "results[choice]") {
			checkbox.checked = formObj.check_all.checked;
		}
	}
}

// gives an alert in the admin cp
function pruneAlert() {
	alert("You cannot change the status of this user because: \n1. This user is an Administrator \n2. This user is a Super Moderator \n3. This user is a Moderator \n4. This user is protected by the $uneditable_user variable \n5. This user is you");
}

// handles colors in the style system
function colorViewer(name) {
	if(!document.getElementById) return;

	input = document.getElementById(name);
	color = document.getElementById(name + '5');

	try {
		color.style.backgroundColor = input.value;
	} catch(csserror) {
		color.style.backgroundColor = "transparent";
	}
}

// this function does the pm receipt alerts
function pmReceipt(receiptid,pid,username) {
	denyORaccept = confirm(username + " has requested a PM receipt that verifies you read the message.\nWould you like to confirm?");

	if(denyORaccept == true) {
		// open window...
		window.open("other.php?do=receipt&action=confirm&receiptid=" + receiptid + "&pid=" + pid,"newpop3","height=1, width=1");
	}

	else {
		window.open("other.php?do=receipt&action=check&receiptid=" + receiptid + "&pid=" + pid,"newpop1","height=1, width=1");
	}
}

// this function does the PM popup alert
function pmNotify() {
	viewORno = confirm("You have new personal messages.\nWould you like to view them?");

	if(viewORno == true) {
		newwindowORsame = confirm("Would you like to open them in a new window?\nIf not, they will open in the current window.");

		if(newwindowORsame == true) {
			window.open("personal.php","newpop2");
		} else {
			window.location = "personal.php";
		}
	}
}