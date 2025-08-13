function render(c, t) {
	if (c.checked || c == 1) {
		document.getElementById(t).style.display="";
	} else {
		document.getElementById(t).style.display="none";
	}
}

function renderSect(sects, i) { 
	var im = document.images[i];
	var todo = '';
	var cook = '';

	if (im.src.match(/arrow_up.gif$/)) { 
		todo = 'none';
		cook = 'closed';
		im.src = "images/arrow_down.gif";
		im.title = 'Open This Section';
	} else {
		cook = 'open';
		im.src = "images/arrow_up.gif";
		im.title = 'Collapse This Section';
	}

	for (var i = 0; i < sects.length; i++) {
		document.getElementById(sects[i]).style.display = todo;
		setCookie(sects[i], cook);
	}
}