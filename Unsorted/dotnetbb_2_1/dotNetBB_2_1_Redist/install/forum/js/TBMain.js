function bRoll2(itemName,itemState) {
	if(itemState==1){
			itemName.className='msgButtonRoll';
	} else {
			itemName.className='msgButton';
	}
}

function ResendMailNofify(popURL, cid) {	
	popURL += '?w=6&p='+cid;	
	window.open(popURL, 'tbPop3', 'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=400,height=200');
}

function quickJ(aPath) {
	if (aPath!='0')
		window.location.href=aPath;
}