var cal = new CalendarPopup();
			
function disableTime(type){
	// get start/stop/periodic elements
	var start 	= document.getElementById('start_time');
	var stop 	= document.getElementById('stop_time');
	var periodic = document.getElementById('periodic_time');
	if(type == 'interval'){
		// disable periodic
		periodic.disabled = true;
		start.disabled = false;
		stop.disabled = false;
	}
	if(type == 'periodic'){
		start.disabled = true;
		stop.disabled = true;
		periodic.disabled = false;
	}
}

function validatePoll(){
	var start 	= document.getElementById('start_time');
	var stop 	= document.getElementById('stop_time');
	var periodic = document.getElementById('periodic_time');
	
	// findout if the user have selected periodic or interval
	if(!periodic.disabled){
		// check if a day is selected
		if(periodic.options[periodic.selectedIndex].value == 'none'){
			alert("You must select a day");
			return false;
		}
	}
	if(!start.disabled){
		var date = /(\d{4})\-([0-1][0-9])\-([0-3][0-9])/;
		// check if both start and end date is set and valid
		if(!start.value.match(date)){
			alert("Start date not valid");
			return false;
		}
		if(!stop.value.match(date)){
			alert("Stop date not valid");
			return false;
		}
		// check that start is not after end
		var m = date.exec(start.value);
		var startDate = new Date(m[1], m[2], m[3]);

		var m = date.exec(stop.value);
		var stopDate = new Date(m[1], m[2], m[3]);
		
		if(startDate.getTime() > stopDate.getTime()){
			alert("You can't have a start date after the end date");
			return false;
		}
	}
	return true;
}